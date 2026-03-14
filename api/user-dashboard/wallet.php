<?php
/**
 * Project: crestvalebank
 * Created by: Wayne
 */

require_once '../../config/database.php';
require_once '../../api/utilities/auth-check.php';
header('Content-Type: application/json');

requireAuth();
$user = getAuthUser();

try {
    $db = Database::getInstance()->getConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Wallet balance
        $walletStmt = $db->prepare("SELECT balance FROM wallets WHERE user_id = :uid");
        $walletStmt->execute(['uid' => $user['id']]);
        $wallet = $walletStmt->fetch();
        $balance = $wallet ? (float) $wallet['balance'] : 0.0;

        // Recent transactions (last 10)
        $txStmt = $db->prepare(
            "SELECT id, type, amount, status, payment_id, notes, created_at
             FROM transactions
             WHERE user_id = :uid
             ORDER BY created_at DESC
             LIMIT 10"
        );
        $txStmt->execute(['uid' => $user['id']]);
        $transactions = $txStmt->fetchAll();

        // Pending withdrawal requests
        $wdStmt = $db->prepare(
            "SELECT id, amount, currency, wallet_address, status, created_at
             FROM withdrawal_requests
             WHERE user_id = :uid
             ORDER BY created_at DESC
             LIMIT 5"
        );
        $wdStmt->execute(['uid' => $user['id']]);
        $withdrawals = $wdStmt->fetchAll();

        echo json_encode([
            'success' => true,
            'data'    => [
                'balance'      => number_format($balance, 2, '.', ''),
                'transactions' => $transactions,
                'withdrawals'  => $withdrawals,
            ]
        ]);

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input  = json_decode(file_get_contents('php://input'), true);
        $action = trim($input['action'] ?? 'withdraw');

        // ── Transfer ─────────────────────────────────────────────────────────
        if ($action === 'transfer') {
            $recipient_email = strtolower(trim($input['recipient_email'] ?? ''));
            $amount          = (float) ($input['amount'] ?? 0);

            if (empty($recipient_email)) {
                echo json_encode(['success' => false, 'message' => 'Recipient email is required']);
                exit;
            }
            if ($amount < 1) {
                echo json_encode(['success' => false, 'message' => 'Minimum transfer amount is $1.00']);
                exit;
            }

            // Get sender's email for notes
            $senderStmt = $db->prepare("SELECT email FROM users WHERE id = :uid");
            $senderStmt->execute(['uid' => $user['id']]);
            $senderRow = $senderStmt->fetch();
            $sender_email = $senderRow['email'] ?? '';

            // Prevent self-transfer
            if (strtolower($sender_email) === $recipient_email) {
                echo json_encode(['success' => false, 'message' => 'You cannot transfer funds to yourself']);
                exit;
            }

            // Look up recipient (generic error prevents enumeration)
            $recStmt = $db->prepare(
                "SELECT id FROM users WHERE email = :email AND is_verified = TRUE AND role = 'user'"
            );
            $recStmt->execute(['email' => $recipient_email]);
            $recipient = $recStmt->fetch();

            if (!$recipient) {
                echo json_encode(['success' => false, 'message' => 'Recipient account not found']);
                exit;
            }

            $recipient_id = (int) $recipient['id'];

            $db->beginTransaction();

            // Lock sender wallet
            $walletStmt = $db->prepare("SELECT balance FROM wallets WHERE user_id = :uid FOR UPDATE");
            $walletStmt->execute(['uid' => $user['id']]);
            $wallet  = $walletStmt->fetch();
            $balance = $wallet ? (float) $wallet['balance'] : 0.0;

            if ($amount > $balance) {
                $db->rollBack();
                echo json_encode(['success' => false, 'message' => 'Insufficient balance']);
                exit;
            }

            // Ensure recipient wallet exists (create if missing)
            $db->prepare(
                "INSERT IGNORE INTO wallets (user_id, balance) VALUES (:uid, 0)"
            )->execute(['uid' => $recipient_id]);

            // Debit sender
            $db->prepare(
                "UPDATE wallets SET balance = balance - :amount, updated_at = NOW() WHERE user_id = :uid"
            )->execute(['amount' => $amount, 'uid' => $user['id']]);

            // Credit recipient
            $db->prepare(
                "UPDATE wallets SET balance = balance + :amount, updated_at = NOW() WHERE user_id = :uid"
            )->execute(['amount' => $amount, 'uid' => $recipient_id]);

            // Transaction for sender
            $db->prepare(
                "INSERT INTO transactions (user_id, type, amount, status, notes)
                 VALUES (:uid, 'transfer', :amount, 'completed', :notes)"
            )->execute([
                'uid'    => $user['id'],
                'amount' => $amount,
                'notes'  => 'Transfer to ' . $recipient_email,
            ]);

            // Transaction for recipient
            $db->prepare(
                "INSERT INTO transactions (user_id, type, amount, status, notes)
                 VALUES (:uid, 'transfer', :amount, 'completed', :notes)"
            )->execute([
                'uid'    => $recipient_id,
                'amount' => $amount,
                'notes'  => 'Transfer from ' . $sender_email,
            ]);

            $db->commit();

            // Return updated balance
            $newBalStmt = $db->prepare("SELECT balance FROM wallets WHERE user_id = :uid");
            $newBalStmt->execute(['uid' => $user['id']]);
            $newWallet = $newBalStmt->fetch();
            $new_balance = $newWallet ? number_format((float) $newWallet['balance'], 2, '.', '') : '0.00';

            echo json_encode([
                'success'     => true,
                'message'     => 'Transfer successful!',
                'new_balance' => $new_balance,
            ]);

        // ── Withdraw ─────────────────────────────────────────────────────────
        } else {
            $amount         = (float)  ($input['amount']         ?? 0);
            $currency       = strtolower(trim($input['currency']       ?? 'usd'));
            $wallet_address = trim($input['wallet_address'] ?? '');

            if ($amount <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid withdrawal amount']);
                exit;
            }
            if (empty($wallet_address)) {
                echo json_encode(['success' => false, 'message' => 'Wallet address is required']);
                exit;
            }

            // Check balance
            $walletStmt = $db->prepare("SELECT balance FROM wallets WHERE user_id = :uid FOR UPDATE");

            $db->beginTransaction();
            $walletStmt->execute(['uid' => $user['id']]);
            $wallet = $walletStmt->fetch();
            $balance = $wallet ? (float) $wallet['balance'] : 0.0;

            if ($amount > $balance) {
                $db->rollBack();
                echo json_encode(['success' => false, 'message' => 'Insufficient balance']);
                exit;
            }

            // Debit the balance immediately (hold funds during review)
            $db->prepare(
                "UPDATE wallets SET balance = balance - :amount, updated_at = NOW() WHERE user_id = :uid"
            )->execute(['amount' => $amount, 'uid' => $user['id']]);

            // Create withdrawal request
            $db->prepare(
                "INSERT INTO withdrawal_requests (user_id, amount, currency, wallet_address)
                 VALUES (:uid, :amount, :currency, :address)"
            )->execute([
                'uid'      => $user['id'],
                'amount'   => $amount,
                'currency' => $currency,
                'address'  => $wallet_address,
            ]);

            // Create transaction record
            $db->prepare(
                "INSERT INTO transactions (user_id, type, amount, status, notes)
                 VALUES (:uid, 'withdrawal', :amount, 'pending', :notes)"
            )->execute([
                'uid'   => $user['id'],
                'amount' => $amount,
                'notes'  => 'Withdrawal (' . strtoupper($currency) . ') — pending admin approval',
            ]);

            $db->commit();
            echo json_encode(['success' => true, 'message' => 'Withdrawal request submitted. Pending admin review.']);
        }

    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }

} catch (PDOException $e) {
    if (isset($db) && $db->inTransaction()) $db->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

<?php
/**
 * Project: arqoracapital
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
            "SELECT id, type, amount, currency, status, payment_id, notes, created_at
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
        // Create withdrawal request
        $input          = json_decode(file_get_contents('php://input'), true);
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
            "INSERT INTO transactions (user_id, type, amount, currency, status, notes)
             VALUES (:uid, 'withdrawal', :amount, :currency, 'pending', 'Pending admin approval')"
        )->execute([
            'uid'      => $user['id'],
            'amount'   => $amount,
            'currency' => $currency,
        ]);

        $db->commit();
        echo json_encode(['success' => true, 'message' => 'Withdrawal request submitted. Pending admin review.']);

    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }

} catch (PDOException $e) {
    if (isset($db) && $db->inTransaction()) $db->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

<?php
/**
 * Project: arqoracapital
 * Created by: Wayne
 * Generated: 2026-03-09
 * 
 */

// NOWPayments IPN webhook handler
header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../config/env.php';

loadEnv(__DIR__ . '/../../.env');

$payload   = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_NOWPAYMENTS_SIG'] ?? '';

// Verify HMAC-SHA512 signature
$ipnSecret = getenv('NOWPAYMENTS_IPN_SECRET');
if ($ipnSecret) {
    $data_sorted = json_decode($payload, true);
    ksort($data_sorted);
    $expected = hash_hmac('sha512', json_encode($data_sorted), $ipnSecret);
    if (!hash_equals($expected, $signature)) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Invalid signature']);
        exit;
    }
}

$data       = json_decode($payload, true);
$payment_id = (string) ($data['payment_id']     ?? '');
$status     = $data['payment_status'] ?? '';

if (!$payment_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing payment_id']);
    exit;
}

try {
    $db = Database::getInstance()->getConnection();

    $stmt = $db->prepare("SELECT * FROM transactions WHERE payment_id = :pid AND type = 'deposit'");
    $stmt->execute(['pid' => $payment_id]);
    $transaction = $stmt->fetch();

    if (!$transaction) {
        // Unknown payment — log and acknowledge
        error_log('Webhook: unknown payment_id ' . $payment_id);
        http_response_code(200);
        echo json_encode(['success' => true]);
        exit;
    }

    // Map NOWPayments statuses
    // finished / confirmed = completed; failed / expired = failed; others = pending
    if (in_array($status, ['finished', 'confirmed'])) {
        if ($transaction['status'] !== 'completed') {
            $db->beginTransaction();

            $db->prepare("UPDATE transactions SET status = 'completed', updated_at = NOW() WHERE id = :id")
               ->execute(['id' => $transaction['id']]);

            // Ensure wallet row exists, then credit balance
            $db->prepare(
                "INSERT INTO wallets (user_id, balance) VALUES (:uid, :amt)
                 ON DUPLICATE KEY UPDATE balance = balance + :amt2, updated_at = NOW()"
            )->execute([
                'uid'  => $transaction['user_id'],
                'amt'  => $transaction['amount'],
                'amt2' => $transaction['amount'],
            ]);

            $db->commit();
        }
    } elseif (in_array($status, ['failed', 'expired', 'refunded'])) {
        $db->prepare("UPDATE transactions SET status = 'failed', updated_at = NOW() WHERE id = :id")
           ->execute(['id' => $transaction['id']]);
    }
    // For partially_paid, waiting, confirming — leave as pending

} catch (PDOException $e) {
    if (isset($db) && $db->inTransaction()) $db->rollBack();
    error_log('Webhook DB error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
    exit;
}

http_response_code(200);
echo json_encode(['success' => true]);

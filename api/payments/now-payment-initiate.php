<?php
/**
 * Project: arqoracapital
 * Created by: Wayne
 */

require_once '../../config/database.php';
require_once '../../api/utilities/auth-check.php';
header('Content-Type: application/json');

requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input        = json_decode(file_get_contents('php://input'), true);
$amount       = (float)  ($input['amount']       ?? 0);
$pay_currency = strtolower(trim($input['currency'] ?? 'btc'));

if ($amount <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid amount']);
    exit;
}

$allowed_currencies = ['btc', 'eth', 'usdttrc20', 'usdterc20', 'bnbbsc'];
if (!in_array($pay_currency, $allowed_currencies)) {
    echo json_encode(['success' => false, 'message' => 'Unsupported currency']);
    exit;
}

$user = getAuthUser();

try {
    $db = Database::getInstance()->getConnection();

    $order_id = 'DEP-' . $user['id'] . '-' . time();

    $payload = json_encode([
        'price_amount'        => $amount,
        'price_currency'      => 'usd',
        'pay_currency'        => $pay_currency,
        'order_id'            => $order_id,
        'order_description'   => 'Deposit for ArqoraCapital account',
        'ipn_callback_url'    => getenv('NOWPAYMENTS_IPN_CALLBACK_URL'),
    ]);

    $ch = curl_init('https://api.nowpayments.io/v1/payment');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'x-api-key: ' . getenv('NOWPAYMENTS_API_KEY'),
        ],
        CURLOPT_TIMEOUT        => 30,
    ]);
    $response  = curl_exec($ch);
    $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 && $httpCode !== 201) {
        echo json_encode(['success' => false, 'message' => 'Payment gateway error. Please try again.']);
        exit;
    }

    $nowData = json_decode($response, true);
    if (!isset($nowData['payment_id'])) {
        echo json_encode(['success' => false, 'message' => 'Failed to create payment']);
        exit;
    }

    // Store the pending transaction
    $db->prepare(
        "INSERT INTO transactions (user_id, type, amount, currency, status, payment_id, notes)
         VALUES (:user_id, 'deposit', :amount, 'USD', 'pending', :payment_id, :notes)"
    )->execute([
        'user_id'    => $user['id'],
        'amount'     => $amount,
        'payment_id' => $nowData['payment_id'],
        'notes'      => $order_id,
    ]);

    // Store the deposit address for this currency
    $db->prepare(
        "INSERT INTO crypto_addresses (user_id, currency, address)
         VALUES (:user_id, :currency, :address)
         ON DUPLICATE KEY UPDATE address = VALUES(address)"
    )->execute([
        'user_id'  => $user['id'],
        'currency' => $pay_currency,
        'address'  => $nowData['pay_address'] ?? '',
    ]);

    echo json_encode([
        'success' => true,
        'data'    => [
            'payment_id'   => $nowData['payment_id'],
            'pay_address'  => $nowData['pay_address']  ?? null,
            'pay_amount'   => $nowData['pay_amount']   ?? null,
            'pay_currency' => $nowData['pay_currency'] ?? $pay_currency,
            'order_id'     => $order_id,
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

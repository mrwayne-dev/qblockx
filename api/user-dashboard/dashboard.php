<?php
/**
 * Project: crestvalebank
 * API: user-dashboard/dashboard.php — Overview stats
 */

require_once '../../config/database.php';
require_once '../../api/utilities/auth-check.php';
header('Content-Type: application/json');

requireAuth();
$user = getAuthUser();

try {
    $db  = Database::getInstance()->getConnection();
    $uid = $user['id'];

    // Wallet balance + currency
    $walletStmt = $db->prepare("SELECT balance, currency FROM wallets WHERE user_id = :uid");
    $walletStmt->execute(['uid' => $uid]);
    $wallet   = $walletStmt->fetch();
    $balance  = $wallet ? (float) $wallet['balance'] : 0.0;
    $currency = $wallet['currency'] ?? 'USD';

    // Savings balance (sum of current_amount across active plans)
    $savingsStmt = $db->prepare(
        "SELECT COALESCE(SUM(current_amount), 0) FROM savings_plans
         WHERE user_id = :uid AND status = 'active'"
    );
    $savingsStmt->execute(['uid' => $uid]);
    $savings_balance = (float) $savingsStmt->fetchColumn();

    // Deposits balance (sum of amounts in active fixed deposits)
    $depositsStmt = $db->prepare(
        "SELECT COALESCE(SUM(amount), 0) FROM fixed_deposits
         WHERE user_id = :uid AND status = 'active'"
    );
    $depositsStmt->execute(['uid' => $uid]);
    $deposits_balance = (float) $depositsStmt->fetchColumn();

    // Loan balance (sum of remaining_balance on active loans)
    $loansStmt = $db->prepare(
        "SELECT COALESCE(SUM(remaining_balance), 0) FROM loans
         WHERE user_id = :uid AND status = 'active'"
    );
    $loansStmt->execute(['uid' => $uid]);
    $loan_balance = (float) $loansStmt->fetchColumn();

    // Recent transactions (last 5)
    $txStmt = $db->prepare(
        "SELECT type, amount, status, created_at
         FROM transactions WHERE user_id = :uid
         ORDER BY created_at DESC LIMIT 5"
    );
    $txStmt->execute(['uid' => $uid]);
    $recent_transactions = $txStmt->fetchAll();

    // User info
    $userStmt = $db->prepare("SELECT email, full_name, created_at FROM users WHERE id = :uid");
    $userStmt->execute(['uid' => $uid]);
    $userInfo = $userStmt->fetch();

    // Product rates — wrapped defensively; never let a missing column crash the whole dashboard
    $rates = [];
    try {
        $ratesStmt = $db->query(
            "SELECT product, label, duration_months, rate
             FROM rates WHERE is_active = 1
             ORDER BY product, duration_months"
        );
        $rates = $ratesStmt->fetchAll();
    } catch (PDOException $rateErr) {
        $rates = [];
    }

    echo json_encode([
        'success' => true,
        'data'    => [
            'user'                => [
                'email'        => $userInfo['email'],
                'full_name'    => $userInfo['full_name'],
                'member_since' => $userInfo['created_at'],
            ],
            'currency'            => $currency,
            'balance'             => number_format($balance,          2, '.', ''),
            'savings_balance'     => number_format($savings_balance,  2, '.', ''),
            'deposits_balance'    => number_format($deposits_balance, 2, '.', ''),
            'loan_balance'        => number_format($loan_balance,     2, '.', ''),
            'recent_transactions' => $recent_transactions,
            'rates'               => $rates,
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

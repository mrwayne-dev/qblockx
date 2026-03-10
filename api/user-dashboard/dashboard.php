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

    // Wallet balance
    $walletStmt = $db->prepare("SELECT balance FROM wallets WHERE user_id = :uid");
    $walletStmt->execute(['uid' => $user['id']]);
    $wallet = $walletStmt->fetch();
    $balance = $wallet ? (float) $wallet['balance'] : 0.0;

    // Total earned (all time)
    $earnedStmt = $db->prepare(
        "SELECT COALESCE(SUM(amount), 0) FROM transactions
         WHERE user_id = :uid AND type = 'earning' AND status = 'completed'"
    );
    $earnedStmt->execute(['uid' => $user['id']]);
    $total_earned = (float) $earnedStmt->fetchColumn();

    // Active investments
    $activeStmt = $db->prepare(
        "SELECT COUNT(*) AS count, COALESCE(SUM(amount), 0) AS invested
         FROM investments WHERE user_id = :uid AND status = 'active'"
    );
    $activeStmt->execute(['uid' => $user['id']]);
    $active = $activeStmt->fetch();

    // Recent transactions (last 5)
    $txStmt = $db->prepare(
        "SELECT type, amount, currency, status, created_at
         FROM transactions WHERE user_id = :uid
         ORDER BY created_at DESC LIMIT 5"
    );
    $txStmt->execute(['uid' => $user['id']]);
    $recent_transactions = $txStmt->fetchAll();

    // User info
    $userStmt = $db->prepare("SELECT email, full_name, created_at FROM users WHERE id = :uid");
    $userStmt->execute(['uid' => $user['id']]);
    $userInfo = $userStmt->fetch();

    echo json_encode([
        'success' => true,
        'data'    => [
            'user'                => [
                'email'      => $userInfo['email'],
                'full_name'  => $userInfo['full_name'],
                'member_since' => $userInfo['created_at'],
            ],
            'balance'             => number_format($balance, 2, '.', ''),
            'total_earned'        => number_format($total_earned, 2, '.', ''),
            'active_investments'  => (int) $active['count'],
            'total_invested'      => number_format((float) $active['invested'], 2, '.', ''),
            'recent_transactions' => $recent_transactions,
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

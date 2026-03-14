<?php
/**
 * Project: crestvalebank
 * API: admin-dashboard/dashboard.php — Overview stats
 */

require_once '../../config/database.php';
require_once '../../api/utilities/auth-check.php';
header('Content-Type: application/json');

requireAdmin();

try {
    $db = Database::getInstance()->getConnection();

    $totalUsers          = $db->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
    $newToday            = $db->query("SELECT COUNT(*) FROM users WHERE DATE(created_at) = CURDATE() AND role = 'user'")->fetchColumn();
    $totalDeposits       = $db->query("SELECT COALESCE(SUM(amount), 0) FROM transactions WHERE type = 'deposit' AND status = 'completed'")->fetchColumn();
    $pendingDeposits     = $db->query("SELECT COUNT(*) FROM transactions WHERE type = 'deposit' AND status = 'pending'")->fetchColumn();
    $activeSavings       = $db->query("SELECT COUNT(*) FROM savings_plans WHERE status = 'active'")->fetchColumn();
    $activeFixedDeposits = $db->query("SELECT COUNT(*) FROM fixed_deposits WHERE status = 'active'")->fetchColumn();
    $fixedDepositsValue  = $db->query("SELECT COALESCE(SUM(amount), 0) FROM fixed_deposits WHERE status = 'active'")->fetchColumn();
    $activeLoans         = $db->query("SELECT COUNT(*) FROM loans WHERE status = 'active'")->fetchColumn();
    $pendingLoans        = $db->query("SELECT COUNT(*) FROM loans WHERE status = 'pending'")->fetchColumn();
    $pendingWithdrawals  = $db->query("SELECT COUNT(*) FROM withdrawal_requests WHERE status = 'pending'")->fetchColumn();

    // Last 5 transactions for overview
    $recentStmt = $db->query(
        "SELECT t.type, t.amount, t.status, t.created_at,
                u.full_name AS user_name, u.email AS user_email
         FROM transactions t
         JOIN users u ON u.id = t.user_id
         ORDER BY t.created_at DESC
         LIMIT 5"
    );
    $recentTransactions = $recentStmt->fetchAll();

    echo json_encode([
        'success' => true,
        'data'    => [
            'total_users'             => (int) $totalUsers,
            'new_today'               => (int) $newToday,
            'total_deposits'          => number_format((float) $totalDeposits, 2, '.', ''),
            'pending_deposits'        => (int) $pendingDeposits,
            'active_savings'          => (int) $activeSavings,
            'active_fixed_deposits'   => (int) $activeFixedDeposits,
            'fixed_deposits_value'    => number_format((float) $fixedDepositsValue, 2, '.', ''),
            'active_loans'            => (int) $activeLoans,
            'pending_loans'           => (int) $pendingLoans,
            'pending_withdrawals'     => (int) $pendingWithdrawals,
            'recent_transactions'     => $recentTransactions,
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

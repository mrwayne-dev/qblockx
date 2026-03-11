<?php
/**
 * Project: arqoracapital
 * Created by: Wayne
 * Generated: 2026-03-09
 * 
 */

require_once '../../config/database.php';
require_once '../../api/utilities/auth-check.php';
header('Content-Type: application/json');

requireAdmin();

try {
    $db = Database::getInstance()->getConnection();

    $totalUsers           = $db->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
    $newToday             = $db->query("SELECT COUNT(*) FROM users WHERE DATE(created_at) = CURDATE() AND role = 'user'")->fetchColumn();
    $totalDeposits        = $db->query("SELECT COALESCE(SUM(amount), 0) FROM transactions WHERE type = 'deposit' AND status = 'completed'")->fetchColumn();
    $activeInvestments    = $db->query("SELECT COUNT(*) FROM investments WHERE status = 'active'")->fetchColumn();
    $pendingWithdrawals   = $db->query("SELECT COUNT(*) FROM withdrawal_requests WHERE status = 'pending'")->fetchColumn();
    $profitDistributed    = $db->query("SELECT COALESCE(SUM(amount), 0) FROM earnings")->fetchColumn();
    $totalInvested        = $db->query("SELECT COALESCE(SUM(amount), 0) FROM investments")->fetchColumn();
    $totalTrades          = $db->query("SELECT COUNT(*) FROM investments")->fetchColumn();
    $pendingDeposits      = $db->query("SELECT COUNT(*) FROM transactions WHERE type = 'deposit' AND status = 'pending'")->fetchColumn();

    // Last 5 transactions for overview
    $recentStmt = $db->query(
        "SELECT t.id, t.type, t.amount, t.currency, t.status, t.created_at,
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
            'total_users'            => (int) $totalUsers,
            'new_today'              => (int) $newToday,
            'total_deposits'         => number_format((float) $totalDeposits, 2, '.', ''),
            'active_investments'     => (int) $activeInvestments,
            'pending_withdrawals'    => (int) $pendingWithdrawals,
            'profit_distributed'     => number_format((float) $profitDistributed, 2, '.', ''),
            'total_invested'         => number_format((float) $totalInvested, 2, '.', ''),
            'total_trades'           => (int) $totalTrades,
            'pending_deposits_count' => (int) $pendingDeposits,
            'recent_transactions'    => $recentTransactions,
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

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

    $totalUsers         = $db->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
    $newToday           = $db->query("SELECT COUNT(*) FROM users WHERE DATE(created_at) = CURDATE() AND role = 'user'")->fetchColumn();
    $totalDeposits      = $db->query("SELECT COALESCE(SUM(amount), 0) FROM transactions WHERE type = 'deposit' AND status = 'completed'")->fetchColumn();
    $activeInvestments  = $db->query("SELECT COUNT(*) FROM investments WHERE status = 'active'")->fetchColumn();
    $pendingWithdrawals = $db->query("SELECT COUNT(*) FROM withdrawal_requests WHERE status = 'pending'")->fetchColumn();
    $totalEarned        = $db->query("SELECT COALESCE(SUM(amount), 0) FROM earnings")->fetchColumn();

    echo json_encode([
        'success' => true,
        'data'    => [
            'total_users'          => (int) $totalUsers,
            'new_today'            => (int) $newToday,
            'total_deposits'       => number_format((float) $totalDeposits, 2, '.', ''),
            'active_investments'   => (int) $activeInvestments,
            'pending_withdrawals'  => (int) $pendingWithdrawals,
            'total_earned'         => number_format((float) $totalEarned, 2, '.', ''),
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

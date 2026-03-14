<?php
/**
 * Project: crestvalebank
 * API: admin-dashboard/user-profile.php
 *
 * Returns full user profile for admin view modal.
 *
 * GET ?id=X
 * Returns: user info + wallet balance + savings (up to 5) +
 *          fixed deposits (up to 5) + active loans (up to 5) +
 *          recent transactions (last 10)
 */

require_once '../../config/database.php';
require_once '../../api/utilities/auth-check.php';
header('Content-Type: application/json');

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'User ID required']);
    exit;
}

try {
    $db = Database::getInstance()->getConnection();

    // User info + wallet balance
    // Try with is_active first; fall back gracefully if the column doesn't exist yet
    try {
        $userStmt = $db->prepare(
            "SELECT u.id, u.full_name, u.email, u.role, u.is_verified, u.is_active, u.created_at,
                    COALESCE(w.balance, 0) AS balance
             FROM users u
             LEFT JOIN wallets w ON w.user_id = u.id
             WHERE u.id = :id"
        );
        $userStmt->execute(['id' => $id]);
        $user = $userStmt->fetch();
    } catch (PDOException $colErr) {
        // is_active column may not exist yet — retry without it
        $userStmt = $db->prepare(
            "SELECT u.id, u.full_name, u.email, u.role, u.is_verified, 1 AS is_active, u.created_at,
                    COALESCE(w.balance, 0) AS balance
             FROM users u
             LEFT JOIN wallets w ON w.user_id = u.id
             WHERE u.id = :id"
        );
        $userStmt->execute(['id' => $id]);
        $user = $userStmt->fetch();
    }

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }

    // Savings plans (most recent 5)
    $savingsStmt = $db->prepare(
        "SELECT plan_name, target_amount, current_amount, interest_rate, duration_months, status, created_at
         FROM savings_plans WHERE user_id = :id ORDER BY created_at DESC LIMIT 5"
    );
    $savingsStmt->execute(['id' => $id]);
    $savings = $savingsStmt->fetchAll();

    // Fixed deposits (most recent 5)
    $depositsStmt = $db->prepare(
        "SELECT amount, interest_rate, duration_months, start_date, maturity_date, expected_return, status
         FROM fixed_deposits WHERE user_id = :id ORDER BY start_date DESC LIMIT 5"
    );
    $depositsStmt->execute(['id' => $id]);
    $deposits = $depositsStmt->fetchAll();

    // Active loans (most recent 5)
    $loansStmt = $db->prepare(
        "SELECT loan_amount, remaining_balance, monthly_payment, interest_rate, duration_months, status, created_at
         FROM loans WHERE user_id = :id ORDER BY created_at DESC LIMIT 5"
    );
    $loansStmt->execute(['id' => $id]);
    $loans = $loansStmt->fetchAll();

    // Recent transactions (last 10)
    $txStmt = $db->prepare(
        "SELECT type, amount, status, notes, created_at
         FROM transactions WHERE user_id = :id ORDER BY created_at DESC LIMIT 10"
    );
    $txStmt->execute(['id' => $id]);
    $transactions = $txStmt->fetchAll();

    echo json_encode([
        'success'      => true,
        'user'         => $user,
        'savings'      => $savings,
        'deposits'     => $deposits,
        'loans'        => $loans,
        'transactions' => $transactions,
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

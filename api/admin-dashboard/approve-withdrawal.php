<?php
/**
 * Project: arqoracapital
 * Created by: Wayne
 */

require_once '../../config/database.php';
require_once '../../api/utilities/auth-check.php';
header('Content-Type: application/json');

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input  = json_decode(file_get_contents('php://input'), true);
$id     = (int) ($input['id']     ?? 0);
$action = trim($input['action']   ?? '');  // 'approve' or 'reject'
$notes  = trim($input['notes']    ?? '');

if (!$id || !in_array($action, ['approve', 'reject'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

try {
    $db = Database::getInstance()->getConnection();

    $stmt = $db->prepare("SELECT * FROM withdrawal_requests WHERE id = :id AND status = 'pending'");
    $stmt->execute(['id' => $id]);
    $request = $stmt->fetch();

    if (!$request) {
        echo json_encode(['success' => false, 'message' => 'Withdrawal request not found or already processed']);
        exit;
    }

    $db->beginTransaction();

    $newStatus = $action === 'approve' ? 'approved' : 'rejected';

    $db->prepare(
        "UPDATE withdrawal_requests SET status = :status, admin_notes = :notes, updated_at = NOW() WHERE id = :id"
    )->execute(['status' => $newStatus, 'notes' => $notes, 'id' => $id]);

    if ($action === 'reject') {
        // Refund balance back to wallet
        $db->prepare(
            "UPDATE wallets SET balance = balance + :amount, updated_at = NOW() WHERE user_id = :user_id"
        )->execute(['amount' => $request['amount'], 'user_id' => $request['user_id']]);

        // Create refund transaction record
        $db->prepare(
            "INSERT INTO transactions (user_id, type, amount, currency, status, notes)
             VALUES (:user_id, 'deposit', :amount, :currency, 'completed', 'Withdrawal rejected — funds returned')"
        )->execute([
            'user_id'  => $request['user_id'],
            'amount'   => $request['amount'],
            'currency' => $request['currency'],
        ]);
    }

    $db->commit();
    echo json_encode(['success' => true, 'message' => 'Withdrawal request ' . $newStatus]);
} catch (PDOException $e) {
    if ($db->inTransaction()) $db->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

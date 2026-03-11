<?php
/**
 * Project: arqoracapital
 * API: Verify email address via token
 * Method: GET ?token=xxx
 */
ob_start();

require_once '../../config/database.php';
header('Content-Type: application/json');

$token = trim($_GET['token'] ?? '');

if (empty($token)) {
    ob_end_clean();
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Verification token is missing']);
    exit;
}

try {
    $db = Database::getInstance()->getConnection();

    $stmt = $db->prepare(
        "SELECT user_id, expires_at FROM email_verifications WHERE token = :token"
    );
    $stmt->execute(['token' => $token]);
    $row = $stmt->fetch();

    if (!$row) {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Invalid or already-used verification link']);
        exit;
    }

    if (strtotime($row['expires_at']) < time()) {
        $db->prepare("DELETE FROM email_verifications WHERE token = :token")
           ->execute(['token' => $token]);
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'This verification link has expired. Please register again or request a new link.']);
        exit;
    }

    $user_id = (int) $row['user_id'];

    $db->prepare("UPDATE users SET is_verified = TRUE WHERE id = :id")
       ->execute(['id' => $user_id]);

    $db->prepare("DELETE FROM email_verifications WHERE token = :token")
       ->execute(['token' => $token]);

    ob_end_clean();
    echo json_encode(['success' => true, 'message' => 'Email verified successfully! Redirecting to sign in…']);

} catch (\Throwable $e) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

<?php
/**
 * Project: crestvalebank
 * API: Reset password using a valid token
 */
ob_start();

require_once '../../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ob_end_clean();
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input    = json_decode(file_get_contents('php://input'), true);
$token    = trim($input['token'] ?? '');
$password = $input['password'] ?? '';

if (empty($token) || empty($password)) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Token and new password are required']);
    exit;
}

if (strlen($password) < 8) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters']);
    exit;
}

try {
    $db   = Database::getInstance()->getConnection();
    $stmt = $db->prepare(
        "SELECT * FROM password_resets WHERE token = :token AND expires_at > NOW()"
    );
    $stmt->execute(['token' => $token]);
    $reset = $stmt->fetch();

    if (!$reset) {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Invalid or expired reset token']);
        exit;
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $db->prepare("UPDATE users SET password = :password WHERE email = :email")
       ->execute(['password' => $hashed, 'email' => $reset['email']]);

    $db->prepare("DELETE FROM password_resets WHERE email = :email")
       ->execute(['email' => $reset['email']]);

    ob_end_clean();
    echo json_encode(['success' => true, 'message' => 'Password reset successfully']);

} catch (\Throwable $e) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

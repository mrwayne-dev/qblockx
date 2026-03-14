<?php
/**
 * Project: crestvalebank
 * API: Admin forgot password — sends reset link via email
 */
ob_start();

require_once '../../config/database.php';
require_once __DIR__ . '/../../api/utilities/email_templates.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ob_end_clean();
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$email = trim($input['email'] ?? '');

if (empty($email)) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Email is required']);
    exit;
}

try {
    $db   = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT id, full_name FROM users WHERE email = :email AND role = 'admin'");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    // Always return success to prevent email enumeration
    if (!$user) {
        ob_end_clean();
        echo json_encode(['success' => true, 'message' => 'If this email exists, a reset link has been sent']);
        exit;
    }

    $token      = bin2hex(random_bytes(32));
    $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

    $db->prepare("DELETE FROM password_resets WHERE email = :email")->execute(['email' => $email]);
    $db->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expires_at)")
       ->execute(['email' => $email, 'token' => $token, 'expires_at' => $expires_at]);

    // Admin reset link points to the admin reset page
    $resetLink = getenv('APP_URL') . '/pages/admin/reset-password.php?token=' . $token;

    // Send email via central Mailer (failure is silent — token is still saved)
    Mailer::sendPasswordReset($email, $user['full_name'] ?? 'Admin', $resetLink);

    ob_end_clean();
    echo json_encode(['success' => true, 'message' => 'If this email exists, a reset link has been sent']);

} catch (\Throwable $e) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

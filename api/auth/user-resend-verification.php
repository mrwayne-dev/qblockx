<?php
/**
 * Project: arqoracapital
 * API: Resend email verification link
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
    $db = Database::getInstance()->getConnection();

    $stmt = $db->prepare("SELECT id, full_name, is_verified FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    // Return success regardless to prevent enumeration
    if (!$user || (bool) $user['is_verified']) {
        ob_end_clean();
        echo json_encode(['success' => true, 'message' => 'If your account exists and is unverified, a new link has been sent']);
        exit;
    }

    $user_id = (int) $user['id'];

    // Remove old tokens for this user
    $db->prepare("DELETE FROM email_verifications WHERE user_id = :uid")
       ->execute(['uid' => $user_id]);

    $token      = bin2hex(random_bytes(32));
    $expires_at = date('Y-m-d H:i:s', strtotime('+24 hours'));

    $db->prepare(
        "INSERT INTO email_verifications (user_id, token, expires_at) VALUES (:uid, :token, :expires_at)"
    )->execute(['uid' => $user_id, 'token' => $token, 'expires_at' => $expires_at]);

    $verifyLink = getenv('APP_URL') . '/pages/public/verify-email.php?token=' . $token;

    $emailSent = Mailer::sendVerification($email, $user['full_name'] ?? '', $verifyLink);

    if (!$emailSent) {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Failed to send email. Please try again.']);
        exit;
    }

    ob_end_clean();
    echo json_encode(['success' => true, 'message' => 'If your account exists and is unverified, a new link has been sent']);

} catch (\Throwable $e) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error. Please try again.']);
}

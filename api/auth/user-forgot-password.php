<?php
/**
 * Project: arqoracapital
 * Created by: Wayne
 * Generated: 2026-03-09
 * 
 */

require_once '../../config/database.php';
require_once __DIR__ . '/../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$email = trim($input['email'] ?? '');

if (empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Email is required']);
    exit;
}

try {
    $db   = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    // Always return success to prevent email enumeration
    if (!$user) {
        echo json_encode(['success' => true, 'message' => 'If this email exists, a reset link has been sent']);
        exit;
    }

    $token      = bin2hex(random_bytes(32));
    $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Remove any existing tokens for this email
    $db->prepare("DELETE FROM password_resets WHERE email = :email")->execute(['email' => $email]);

    $stmt = $db->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expires_at)");
    $stmt->execute(['email' => $email, 'token' => $token, 'expires_at' => $expires_at]);

    $resetLink = getenv('APP_URL') . '/pages/public/reset-password.php?token=' . $token;

    
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = getenv('SMTP_HOST');
    $mail->SMTPAuth   = true;
    $mail->Username   = getenv('SMTP_USER');
    $mail->Password   = getenv('SMTP_PASS');
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = (int) getenv('SMTP_PORT');

    $mail->setFrom(getenv('SMTP_FROM'), getenv('SMTP_FROM_NAME'));
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Password Reset - ' . getenv('APP_NAME');
    $mail->Body    = '<p>Click the link below to reset your password (expires in 1 hour):</p>'
                   . '<p><a href="' . $resetLink . '">' . $resetLink . '</a></p>';
    $mail->send();

    echo json_encode(['success' => true, 'message' => 'If this email exists, a reset link has been sent']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

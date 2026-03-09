<?php
/**
 * Project: arqoracapital
 * Created by: Wayne
 * Generated: 2026-03-09
 * 
 */

require_once '../../config/env.php';
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

$input   = json_decode(file_get_contents('php://input'), true);
$name    = htmlspecialchars(trim($input['name']    ?? ''), ENT_QUOTES, 'UTF-8');
$email   = filter_var(trim($input['email']  ?? ''), FILTER_VALIDATE_EMAIL);
$subject = htmlspecialchars(trim($input['subject'] ?? 'Contact Form'), ENT_QUOTES, 'UTF-8');
$message = htmlspecialchars(trim($input['message'] ?? ''), ENT_QUOTES, 'UTF-8');

if (!$name || !$email || !$message) {
    echo json_encode(['success' => false, 'message' => 'Name, email, and message are required']);
    exit;
}


try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = getenv('SMTP_HOST');
    $mail->SMTPAuth   = true;
    $mail->Username   = getenv('SMTP_USER');
    $mail->Password   = getenv('SMTP_PASS');
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = (int) getenv('SMTP_PORT');

    $mail->setFrom(getenv('SMTP_FROM'), getenv('SMTP_FROM_NAME'));
    $mail->addAddress(getenv('SMTP_USER'));
    $mail->addReplyTo($email, $name);
    $mail->isHTML(true);
    $mail->Subject = $subject . ' - Contact Form';
    $mail->Body    = "<p><strong>From:</strong> $name ($email)</p>"
                   . "<p><strong>Message:</strong><br>" . nl2br($message) . "</p>";
    $mail->send();

    echo json_encode(['success' => true, 'message' => 'Message sent successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to send message']);
}


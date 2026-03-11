<?php
/**
 * Project: arqoracapital
 * API: User registration — creates account + sends email verification
 */
ob_start(); // buffer any stray notices so they don't corrupt JSON

require_once '../../config/database.php';
require_once __DIR__ . '/../../api/utilities/email_templates.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ob_end_clean();
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input     = json_decode(file_get_contents('php://input'), true);
$email     = trim($input['email'] ?? '');
$password  = $input['password'] ?? '';
$full_name = trim($input['full_name'] ?? '');

if (empty($email) || empty($password)) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Email and password required']);
    exit;
}

if (strlen($password) < 8) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters']);
    exit;
}

$ref_code = trim($input['ref_code'] ?? '');
$db       = null;

try {
    $db = Database::getInstance()->getConnection();

    // Check for duplicate email
    $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    if ($stmt->fetch()) {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Email already registered']);
        exit;
    }

    $db->beginTransaction();

    // Create user (is_verified defaults to FALSE)
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $db->prepare("INSERT INTO users (email, password, full_name) VALUES (:email, :password, :full_name)")
       ->execute(['email' => $email, 'password' => $hashed, 'full_name' => $full_name]);
    $new_user_id = (int) $db->lastInsertId();

    // Create wallet for new user
    $db->prepare("INSERT INTO wallets (user_id) VALUES (:uid)")
       ->execute(['uid' => $new_user_id]);

    // Generate unique referral code
    do {
        $code = strtoupper(substr(base_convert(bin2hex(random_bytes(4)), 16, 36), 0, 8));
        $chk  = $db->prepare("SELECT id FROM referral_codes WHERE code = :code");
        $chk->execute(['code' => $code]);
    } while ($chk->fetch());

    $db->prepare("INSERT INTO referral_codes (user_id, code) VALUES (:uid, :code)")
       ->execute(['uid' => $new_user_id, 'code' => $code]);

    // Link referral if a valid ref_code was provided
    if (!empty($ref_code)) {
        $refStmt = $db->prepare("SELECT user_id FROM referral_codes WHERE code = :code");
        $refStmt->execute(['code' => $ref_code]);
        $referrer = $refStmt->fetch();

        if ($referrer && (int) $referrer['user_id'] !== $new_user_id) {
            $db->prepare(
                "INSERT IGNORE INTO referrals (referrer_id, referred_id) VALUES (:ref, :new)"
            )->execute(['ref' => $referrer['user_id'], 'new' => $new_user_id]);

            $db->prepare("UPDATE referral_codes SET uses = uses + 1 WHERE user_id = :uid")
               ->execute(['uid' => $referrer['user_id']]);
        }
    }

    // Create email verification token (24-hour expiry)
    $verify_token = bin2hex(random_bytes(32));
    $expires_at   = date('Y-m-d H:i:s', strtotime('+24 hours'));

    $db->prepare(
        "INSERT INTO email_verifications (user_id, token, expires_at) VALUES (:uid, :token, :expires_at)"
    )->execute(['uid' => $new_user_id, 'token' => $verify_token, 'expires_at' => $expires_at]);

    $db->commit();

    // ── Send verification email via Mailer ──────────────────────────────────
    $verifyLink = getenv('APP_URL') . '/pages/public/verify-email.php?token=' . $verify_token;
    $emailSent  = Mailer::sendVerification($email, $full_name, $verifyLink);

    ob_end_clean();
    echo json_encode([
        'success'    => true,
        'email_sent' => $emailSent,
        'message'    => $emailSent
            ? 'Account created! Please check your email to verify your account.'
            : 'Account created! Verification email could not be sent — use the resend option on the next screen.',
    ]);

} catch (\Throwable $e) {
    if ($db !== null && $db->inTransaction()) {
        try { $db->rollBack(); } catch (\Throwable $re) {}
    }
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error. Please try again.']);
}

<?php
/**
 * Project: arqoracapital
 * Created by: Wayne
 * Generated: 2026-03-09
 * 
 */

require_once '../../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input     = json_decode(file_get_contents('php://input'), true);
$email     = trim($input['email'] ?? '');
$password  = $input['password'] ?? '';
$full_name = trim($input['full_name'] ?? '');

if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Email and password required']);
    exit;
}

if (strlen($password) < 8) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters']);
    exit;
}

$ref_code = trim($input['ref_code'] ?? '');

try {
    $db = Database::getInstance()->getConnection();

    $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Email already registered']);
        exit;
    }

    $db->beginTransaction();

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
        $exists = $db->prepare("SELECT id FROM referral_codes WHERE code = :code");
        $exists->execute(['code' => $code]);
    } while ($exists->fetch());

    $db->prepare("INSERT INTO referral_codes (user_id, code) VALUES (:uid, :code)")
       ->execute(['uid' => $new_user_id, 'code' => $code]);

    // Link referral if a valid ref_code was provided
    if (!empty($ref_code)) {
        $refCodeStmt = $db->prepare("SELECT user_id FROM referral_codes WHERE code = :code");
        $refCodeStmt->execute(['code' => $ref_code]);
        $referrer = $refCodeStmt->fetch();

        if ($referrer && (int) $referrer['user_id'] !== $new_user_id) {
            $db->prepare(
                "INSERT IGNORE INTO referrals (referrer_id, referred_id) VALUES (:ref, :new)"
            )->execute(['ref' => $referrer['user_id'], 'new' => $new_user_id]);

            $db->prepare("UPDATE referral_codes SET uses = uses + 1 WHERE user_id = :uid")
               ->execute(['uid' => $referrer['user_id']]);
        }
    }

    $db->commit();
    echo json_encode(['success' => true, 'message' => 'Registration successful']);
} catch (PDOException $e) {
    if (isset($db) && $db->inTransaction()) $db->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

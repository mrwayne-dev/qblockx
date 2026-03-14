<?php
/**
 * Project: crestvalebank
 * API: admin-dashboard/settings.php — Interest rates & system settings
 */

require_once '../../config/database.php';
require_once '../../api/utilities/auth-check.php';
header('Content-Type: application/json');

requireAdmin();

try {
    $db = Database::getInstance()->getConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $rates = $db->query(
            "SELECT id, product, label, duration_months, rate, is_active FROM rates ORDER BY product, duration_months"
        )->fetchAll();

        $settingsStmt = $db->query("SELECT `key`, `value` FROM system_settings");
        $rawSettings  = $settingsStmt->fetchAll();
        $settings     = [];
        foreach ($rawSettings as $row) { $settings[$row['key']] = $row['value']; }

        echo json_encode(['success' => true, 'rates' => $rates, 'settings' => $settings]);

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input  = json_decode(file_get_contents('php://input'), true) ?? [];
        $action = $input['action'] ?? '';

        if ($action === 'add_rate') {
            $product  = $input['product']          ?? '';
            $label    = trim($input['label']        ?? '');
            $duration = (int)   ($input['duration_months'] ?? 0);
            $rate     = (float) ($input['rate']     ?? 0);

            if (!in_array($product, ['savings', 'fixed_deposit', 'loan'])) {
                echo json_encode(['success' => false, 'message' => 'Invalid product']); exit;
            }
            if (empty($label) || $duration <= 0 || $rate <= 0) {
                echo json_encode(['success' => false, 'message' => 'All fields are required']); exit;
            }

            $db->prepare(
                "INSERT INTO rates (product, label, duration_months, rate) VALUES (:product, :label, :dur, :rate)"
            )->execute(['product' => $product, 'label' => $label, 'dur' => $duration, 'rate' => $rate]);
            echo json_encode(['success' => true, 'message' => 'Rate added']);

        } elseif ($action === 'update_rate') {
            $id   = (int)   ($input['id']   ?? 0);
            $rate = (float) ($input['rate'] ?? 0);
            $active = isset($input['is_active']) ? (int) $input['is_active'] : null;

            if ($id <= 0) { echo json_encode(['success' => false, 'message' => 'Invalid rate ID']); exit; }

            $db->prepare("UPDATE rates SET rate = :rate WHERE id = :id")->execute(['rate' => $rate, 'id' => $id]);
            if ($active !== null) {
                $db->prepare("UPDATE rates SET is_active = :active WHERE id = :id")->execute(['active' => $active, 'id' => $id]);
            }
            echo json_encode(['success' => true, 'message' => 'Rate updated']);

        } elseif ($action === 'delete_rate') {
            $id = (int) ($input['id'] ?? 0);
            if ($id <= 0) { echo json_encode(['success' => false, 'message' => 'Invalid rate ID']); exit; }
            $db->prepare("DELETE FROM rates WHERE id = :id")->execute(['id' => $id]);
            echo json_encode(['success' => true, 'message' => 'Rate deleted']);

        } elseif ($action === 'update_setting') {
            $key   = $input['key']   ?? '';
            $value = $input['value'] ?? '';
            $allowed = ['deposits_enabled', 'withdrawals_enabled', 'maintenance_mode', 'min_deposit', 'min_withdrawal', 'withdrawal_fee'];
            if (!in_array($key, $allowed)) {
                echo json_encode(['success' => false, 'message' => 'Invalid setting key']); exit;
            }
            $db->prepare("INSERT INTO system_settings (`key`, `value`) VALUES (:key, :value) ON DUPLICATE KEY UPDATE `value` = :value2")
               ->execute(['key' => $key, 'value' => $value, 'value2' => $value]);
            echo json_encode(['success' => true, 'message' => 'Setting updated']);

        } else {
            echo json_encode(['success' => false, 'message' => 'Unknown action']);
        }

    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

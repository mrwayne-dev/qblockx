<?php
/**
 * Project: qblockx
 * Admin: Investment Plans — GET list, POST update/toggle
 */

require_once '../../config/database.php';
require_once '../../api/utilities/auth-check.php';
header('Content-Type: application/json');

requireAdmin();

try {
    $db = Database::getInstance()->getConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $plans = $db->query("SELECT * FROM investment_plans ORDER BY min_amount ASC")->fetchAll();
        echo json_encode(['success' => true, 'data' => ['plans' => $plans]]);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input  = json_decode(file_get_contents('php://input'), true);
        $action = $input['action'] ?? '';
        $id     = (int) ($input['id'] ?? 0);

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Invalid plan ID']);
            exit;
        }

        // Verify plan exists
        $check = $db->prepare("SELECT id FROM investment_plans WHERE id = :id LIMIT 1");
        $check->execute(['id' => $id]);
        if (!$check->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Plan not found']);
            exit;
        }

        if ($action === 'update') {
            $setParts = [];
            $params   = ['id' => $id];

            if (!empty($input['name'])) {
                $setParts[]     = 'name = :name';
                $params['name'] = trim($input['name']);
            }
            if (isset($input['min_amount']) && is_numeric($input['min_amount'])) {
                $setParts[]            = 'min_amount = :min_amount';
                $params['min_amount']  = max(0, (float) $input['min_amount']);
            }
            // max_amount can be null (unlimited)
            if (array_key_exists('max_amount', $input)) {
                $setParts[]            = 'max_amount = :max_amount';
                $params['max_amount']  = ($input['max_amount'] !== null && $input['max_amount'] !== '')
                    ? max(0, (float) $input['max_amount'])
                    : null;
            }
            if (isset($input['daily_rate']) && is_numeric($input['daily_rate'])) {
                $setParts[]            = 'daily_rate = :daily_rate';
                $params['daily_rate']  = min(1, max(0, (float) $input['daily_rate']));
            }
            if (isset($input['duration_days']) && is_numeric($input['duration_days'])) {
                $setParts[]               = 'duration_days = :duration_days';
                $params['duration_days']  = max(1, (int) $input['duration_days']);
            }

            if ($setParts) {
                $db->prepare("UPDATE investment_plans SET " . implode(', ', $setParts) . ", updated_at = NOW() WHERE id = :id")
                   ->execute($params);
            }
            $msg = 'Plan updated';

        } elseif ($action === 'toggle') {
            $db->prepare("UPDATE investment_plans SET is_active = NOT is_active, updated_at = NOW() WHERE id = :id")
               ->execute(['id' => $id]);
            $msg = 'Plan toggled';

        } else {
            echo json_encode(['success' => false, 'message' => 'Unknown action']);
            exit;
        }

        // Return full updated list
        $plans = $db->query("SELECT * FROM investment_plans ORDER BY min_amount ASC")->fetchAll();
        echo json_encode(['success' => true, 'message' => $msg, 'data' => ['plans' => $plans]]);
        exit;
    }

    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);

} catch (PDOException $e) {
    error_log('investment-plans error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

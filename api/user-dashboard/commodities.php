<?php
/**
 * Project: Qblockx
 * API: User — Commodity Investments
 * GET  → available assets + user's active/completed positions + portfolio summary
 * POST → open a new commodity position (deduct wallet, record commodity_investment)
 */

require_once '../../config/database.php';
require_once '../../api/utilities/auth-check.php';
header('Content-Type: application/json');

requireAuth();
$user = getAuthUser();

try {
    $db = Database::getInstance()->getConnection();

    // ── GET ──────────────────────────────────────────────────────────────────
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        $assetStmt = $db->query(
            "SELECT id, name, symbol, tradingview_sym, min_investment,
                    duration_days, yield_min, yield_max
             FROM commodity_assets
             WHERE is_active = 1
             ORDER BY sort_order ASC"
        );
        $assets = $assetStmt->fetchAll();

        $posStmt = $db->prepare(
            "SELECT id, asset_name, amount, yield_rate, starts_at, ends_at,
                    expected_return, actual_return, status, created_at
             FROM commodity_investments
             WHERE user_id = :uid
             ORDER BY created_at DESC"
        );
        $posStmt->execute(['uid' => $user['id']]);
        $myPositions = $posStmt->fetchAll();

        $summaryStmt = $db->prepare(
            "SELECT
               COALESCE(SUM(CASE WHEN status = 'active' THEN amount ELSE 0 END), 0)         AS total_invested,
               COALESCE(SUM(CASE WHEN status = 'matured' THEN actual_return ELSE 0 END), 0) AS total_returned,
               COUNT(CASE WHEN status = 'active' THEN 1 END)                                AS active_count
             FROM commodity_investments
             WHERE user_id = :uid"
        );
        $summaryStmt->execute(['uid' => $user['id']]);
        $summary = $summaryStmt->fetch();

        echo json_encode([
            'success' => true,
            'data'    => [
                'assets'      => $assets,
                'my_positions' => $myPositions,
                'portfolio'   => [
                    'total_invested' => number_format((float) $summary['total_invested'], 2, '.', ''),
                    'total_returned' => number_format((float) $summary['total_returned'], 2, '.', ''),
                    'active_count'   => (int) $summary['active_count'],
                ],
            ],
        ]);

    // ── POST ─────────────────────────────────────────────────────────────────
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input    = json_decode(file_get_contents('php://input'), true);
        $asset_id = (int)   ($input['asset_id'] ?? 0);
        $amount   = (float) ($input['amount']   ?? 0);

        if ($asset_id <= 0 || $amount <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid input']);
            exit;
        }

        $assetStmt = $db->prepare(
            "SELECT * FROM commodity_assets WHERE id = :id AND is_active = 1"
        );
        $assetStmt->execute(['id' => $asset_id]);
        $asset = $assetStmt->fetch();

        if (!$asset) {
            echo json_encode(['success' => false, 'message' => 'Asset not found or inactive']);
            exit;
        }

        if ($amount < (float) $asset['min_investment']) {
            echo json_encode([
                'success' => false,
                'message' => 'Minimum investment for ' . $asset['name'] . ' is $' .
                             number_format($asset['min_investment'], 0),
            ]);
            exit;
        }

        $db->beginTransaction();

        $walletStmt = $db->prepare("SELECT balance FROM wallets WHERE user_id = :uid FOR UPDATE");
        $walletStmt->execute(['uid' => $user['id']]);
        $wallet  = $walletStmt->fetch();
        $balance = $wallet ? (float) $wallet['balance'] : 0.0;

        if ($amount > $balance) {
            $db->rollBack();
            echo json_encode(['success' => false, 'message' => 'Insufficient wallet balance']);
            exit;
        }

        $starts_at       = date('Y-m-d H:i:s');
        $ends_at         = date('Y-m-d H:i:s', strtotime('+' . (int) $asset['duration_days'] . ' days'));
        $expected_return = round($amount + ($amount * (float) $asset['yield_max'] / 100), 2);

        $db->prepare(
            "UPDATE wallets SET balance = balance - :amount, updated_at = NOW() WHERE user_id = :uid"
        )->execute(['amount' => $amount, 'uid' => $user['id']]);

        $db->prepare(
            "INSERT INTO commodity_investments
               (user_id, asset_id, asset_name, amount, starts_at, ends_at, expected_return)
             VALUES (:uid, :aid, :aname, :amount, :starts, :ends, :expected)"
        )->execute([
            'uid'      => $user['id'],
            'aid'      => $asset['id'],
            'aname'    => $asset['name'],
            'amount'   => $amount,
            'starts'   => $starts_at,
            'ends'     => $ends_at,
            'expected' => $expected_return,
        ]);

        $db->prepare(
            "INSERT INTO transactions (user_id, type, amount, currency, status, notes)
             VALUES (:uid, 'commodity_investment', :amount, 'USD', 'completed', :note)"
        )->execute([
            'uid'    => $user['id'],
            'amount' => $amount,
            'note'   => $asset['name'] . ' commodity position — ' . $asset['duration_days'] . ' days',
        ]);

        $db->commit();

        echo json_encode([
            'success' => true,
            'message' => $asset['name'] . ' position opened. Returns credited at maturity.',
            'data'    => [
                'asset_name'      => $asset['name'],
                'amount'          => $amount,
                'expected_return' => $expected_return,
                'ends_at'         => $ends_at,
            ],
        ]);

    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }

} catch (PDOException $e) {
    if (isset($db) && $db->inTransaction()) $db->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

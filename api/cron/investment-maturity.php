<?php
/**
 * Project: Qblockx
 * Cron: Investment Plan Maturity
 * Run daily. Finds active plan_investments whose ends_at has passed,
 * assigns a random yield within the plan's range, credits wallet, marks matured.
 *
 * Recommended cron: 0 0 * * * php /path/to/api/cron/investment-maturity.php
 */

require_once '../../config/database.php';

$processed = 0;
$failed    = 0;
$errors    = [];

try {
    $db = Database::getInstance()->getConnection();

    $stmt = $db->prepare(
        "SELECT pi.id, pi.user_id, pi.plan_id, pi.plan_name, pi.amount,
                ip.yield_min, ip.yield_max
         FROM plan_investments pi
         JOIN investment_plans ip ON ip.id = pi.plan_id
         WHERE pi.status = 'active' AND pi.ends_at <= NOW()"
    );
    $stmt->execute();
    $matured = $stmt->fetchAll();

    foreach ($matured as $inv) {
        try {
            // Random yield within plan range (two decimal places)
            $yield_rate    = round((float) $inv['yield_min'] + mt_rand(0, 100) / 100 * ((float) $inv['yield_max'] - (float) $inv['yield_min']), 2);
            $actual_return = round((float) $inv['amount'] + ((float) $inv['amount'] * $yield_rate / 100), 2);

            $db->beginTransaction();

            $db->prepare(
                "UPDATE wallets SET balance = balance + :amount, updated_at = NOW() WHERE user_id = :uid"
            )->execute(['amount' => $actual_return, 'uid' => $inv['user_id']]);

            $db->prepare(
                "UPDATE plan_investments
                 SET status = 'matured', yield_rate = :rate, actual_return = :ret, updated_at = NOW()
                 WHERE id = :id"
            )->execute(['rate' => $yield_rate, 'ret' => $actual_return, 'id' => $inv['id']]);

            $db->prepare(
                "INSERT INTO transactions (user_id, type, amount, currency, status, notes)
                 VALUES (:uid, 'investment_return', :amount, 'USD', 'completed', :note)"
            )->execute([
                'uid'    => $inv['user_id'],
                'amount' => $actual_return,
                'note'   => $inv['plan_name'] . ' plan matured — ' . $yield_rate . '% return',
            ]);

            $db->commit();
            $processed++;

        } catch (Exception $e) {
            if ($db->inTransaction()) $db->rollBack();
            $failed++;
            $errors[] = 'Investment #' . $inv['id'] . ': ' . $e->getMessage();
        }
    }

    $status  = $failed === 0 ? 'success' : ($processed > 0 ? 'partial' : 'failed');
    $message = "Processed: $processed, Failed: $failed" . ($errors ? ' | ' . implode('; ', $errors) : '');

    $db->prepare(
        "INSERT INTO cron_logs (job_name, status, message) VALUES ('investment-maturity', :status, :msg)"
    )->execute(['status' => $status, 'msg' => $message]);

    echo $message . PHP_EOL;

} catch (PDOException $e) {
    echo 'Fatal error: ' . $e->getMessage() . PHP_EOL;
    try {
        $db->prepare(
            "INSERT INTO cron_logs (job_name, status, message) VALUES ('investment-maturity', 'failed', :msg)"
        )->execute(['msg' => $e->getMessage()]);
    } catch (Exception $ignored) {}
}

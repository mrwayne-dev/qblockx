<?php
/**
 * Project: qblockx
 * Cron: api/cron/savings-interest.php
 *
 * Monthly savings interest accrual.
 * Schedule: 0 1 1 * *  (1st of every month at 01:00)
 *
 * For each active savings plan:
 *  - Calculates monthly interest (principal * rate / 100 / 12)
 *  - Credits interest directly to user's wallet
 *  - Logs an interest_credit transaction
 *  - Marks plan completed if current_amount >= target_amount
 *  - Sends interest_credited / plan_completed email
 * - Logs result to cron_logs
 */

// Allow CLI only
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Access denied');
}

define('APP_ROOT', dirname(__DIR__, 2));
require_once APP_ROOT . '/config/database.php';
require_once APP_ROOT . '/config/env.php';
require_once APP_ROOT . '/api/utilities/email_templates.php';

loadEnv(APP_ROOT . '/.env');

$startTime = microtime(true);
$processed = 0;
$errors    = 0;

try {
    $db = Database::getInstance()->getConnection();

    // Fetch all active savings plans with user info
    $stmt = $db->prepare(
        "SELECT sp.id, sp.user_id, sp.plan_name, sp.current_amount, sp.target_amount,
                sp.interest_rate, sp.duration_months,
                u.email, u.full_name
         FROM savings_plans sp
         JOIN users u ON u.id = sp.user_id
         WHERE sp.status = 'active'"
    );
    $stmt->execute();
    $plans = $stmt->fetchAll();

    $today = date('Y-m-d');

    foreach ($plans as $plan) {
        try {
            $db->beginTransaction();

            $plan_id        = (int) $plan['id'];
            $user_id        = (int) $plan['user_id'];
            $current_amount = (float) $plan['current_amount'];
            $rate           = (float) $plan['interest_rate'];
            $monthly_interest = round($current_amount * ($rate / 100 / 12), 2);

            if ($monthly_interest <= 0) {
                $db->rollBack();
                continue;
            }

            // Credit interest to savings plan balance
            $db->prepare(
                "UPDATE savings_plans SET current_amount = current_amount + :interest WHERE id = :id"
            )->execute(['interest' => $monthly_interest, 'id' => $plan_id]);

            // Credit interest to wallet
            $db->prepare(
                "INSERT IGNORE INTO wallets (user_id, balance) VALUES (:uid, 0)"
            )->execute(['uid' => $user_id]);

            $db->prepare(
                "UPDATE wallets SET balance = balance + :interest, updated_at = NOW() WHERE user_id = :uid"
            )->execute(['interest' => $monthly_interest, 'uid' => $user_id]);

            // Log transaction
            $db->prepare(
                "INSERT INTO transactions (user_id, type, amount, status, notes)
                 VALUES (:uid, 'interest_credit', :amount, 'completed', :notes)"
            )->execute([
                'uid'    => $user_id,
                'amount' => $monthly_interest,
                'notes'  => 'Monthly interest — ' . $plan['plan_name'],
            ]);

            // Check if target reached
            $new_amount = $current_amount + $monthly_interest;
            $completed  = $new_amount >= (float) $plan['target_amount'];

            if ($completed) {
                $db->prepare(
                    "UPDATE savings_plans SET status = 'completed', current_amount = :amount WHERE id = :id"
                )->execute(['amount' => $new_amount, 'id' => $plan_id]);
            }

            $db->commit();
            $processed++;

            // Send email (non-fatal)
            try {
                Mailer::sendSavingsInterestCredited(
                    $plan['email'],
                    $plan['full_name'],
                    number_format($monthly_interest, 2),
                    $plan['plan_name'],
                    number_format($new_amount, 2),
                    $today
                );
            } catch (Exception $mailErr) {
                error_log('savings-interest cron: mail error for user ' . $user_id . ': ' . $mailErr->getMessage());
            }

        } catch (Exception $inner) {
            if ($db->inTransaction()) $db->rollBack();
            error_log('savings-interest cron error for plan ' . ($plan['id'] ?? '?') . ': ' . $inner->getMessage());
            $errors++;
        }
    }

    $elapsed = round(microtime(true) - $startTime, 2);
    $message = "Processed: {$processed}, Errors: {$errors}, Time: {$elapsed}s";

    $db->prepare(
        "INSERT INTO cron_logs (job_name, status, message) VALUES ('savings-interest', :status, :msg)"
    )->execute([
        'status' => $errors === 0 ? 'success' : 'partial',
        'msg'    => $message,
    ]);

    echo $message . PHP_EOL;

} catch (Exception $e) {
    $message = 'Fatal error: ' . $e->getMessage();
    error_log('savings-interest cron fatal: ' . $message);

    try {
        $db->prepare(
            "INSERT INTO cron_logs (job_name, status, message) VALUES ('savings-interest', 'failed', :msg)"
        )->execute(['msg' => $message]);
    } catch (Exception $logErr) {}

    echo $message . PHP_EOL;
    exit(1);
}

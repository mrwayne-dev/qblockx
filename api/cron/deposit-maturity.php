<?php
/**
 * Project: crestvalebank
 * Cron: api/cron/deposit-maturity.php
 *
 * Daily fixed deposit maturity processor.
 * Schedule: 0 2 * * *  (every day at 02:00)
 *
 * For each active fixed deposit where maturity_date <= TODAY:
 *  - Calculates total return (principal + expected_return)
 *  - Credits total return to user's wallet
 *  - Logs a deposit_return transaction
 *  - Marks deposit as 'matured'
 *  - Sends deposit matured email
 *  - Logs result to cron_logs
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

    // Fetch all active deposits that have reached or passed maturity
    $stmt = $db->prepare(
        "SELECT fd.id, fd.user_id, fd.amount, fd.expected_return, fd.maturity_date,
                fd.interest_rate, fd.duration_months,
                u.email, u.full_name
         FROM fixed_deposits fd
         JOIN users u ON u.id = fd.user_id
         WHERE fd.status = 'active' AND fd.maturity_date <= CURDATE()"
    );
    $stmt->execute();
    $deposits = $stmt->fetchAll();

    foreach ($deposits as $deposit) {
        try {
            $db->beginTransaction();

            $deposit_id   = (int) $deposit['id'];
            $user_id      = (int) $deposit['user_id'];
            $principal    = (float) $deposit['amount'];
            $total_return = (float) $deposit['expected_return'];

            // Ensure wallet exists
            $db->prepare(
                "INSERT IGNORE INTO wallets (user_id, balance) VALUES (:uid, 0)"
            )->execute(['uid' => $user_id]);

            // Credit total return to wallet
            $db->prepare(
                "UPDATE wallets SET balance = balance + :amount, updated_at = NOW() WHERE user_id = :uid"
            )->execute(['amount' => $total_return, 'uid' => $user_id]);

            // Log transaction
            $db->prepare(
                "INSERT INTO transactions (user_id, type, amount, status, notes)
                 VALUES (:uid, 'deposit_return', :amount, 'completed', 'Fixed deposit matured — principal + interest returned')"
            )->execute(['uid' => $user_id, 'amount' => $total_return]);

            // Mark deposit as matured
            $db->prepare(
                "UPDATE fixed_deposits SET status = 'matured' WHERE id = :id"
            )->execute(['id' => $deposit_id]);

            $db->commit();
            $processed++;

            // Send email (non-fatal)
            try {
                Mailer::sendDepositMatured(
                    $deposit['email'],
                    $deposit['full_name'],
                    number_format($principal, 2),
                    number_format($total_return, 2),
                    $deposit['maturity_date']
                );
            } catch (Exception $mailErr) {
                error_log('deposit-maturity cron: mail error for user ' . $user_id . ': ' . $mailErr->getMessage());
            }

        } catch (Exception $inner) {
            if ($db->inTransaction()) $db->rollBack();
            error_log('deposit-maturity cron error for deposit ' . ($deposit['id'] ?? '?') . ': ' . $inner->getMessage());
            $errors++;
        }
    }

    $elapsed = round(microtime(true) - $startTime, 2);
    $message = "Processed: {$processed}, Errors: {$errors}, Time: {$elapsed}s";

    $db->prepare(
        "INSERT INTO cron_logs (job_name, status, message) VALUES ('deposit-maturity', :status, :msg)"
    )->execute([
        'status' => $errors === 0 ? 'success' : 'partial',
        'msg'    => $message,
    ]);

    echo $message . PHP_EOL;

} catch (Exception $e) {
    $message = 'Fatal error: ' . $e->getMessage();
    error_log('deposit-maturity cron fatal: ' . $message);

    try {
        $db->prepare(
            "INSERT INTO cron_logs (job_name, status, message) VALUES ('deposit-maturity', 'failed', :msg)"
        )->execute(['msg' => $message]);
    } catch (Exception $logErr) {}

    echo $message . PHP_EOL;
    exit(1);
}

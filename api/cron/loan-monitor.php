<?php
/**
 * Project: qblockx
 * Cron: api/cron/loan-monitor.php
 *
 * Daily loan payment due reminder.
 * Schedule: 0 8 * * *  (every day at 08:00)
 *
 * For each active loan:
 *  - Calculates the next payment due date (monthly from created_at)
 *  - Sends a reminder email if today is the due date or within 3 days
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

    // Fetch all active loans with user info
    $stmt = $db->prepare(
        "SELECT l.id, l.user_id, l.loan_amount, l.remaining_balance,
                l.monthly_payment, l.duration_months, l.created_at,
                u.email, u.full_name
         FROM loans l
         JOIN users u ON u.id = l.user_id
         WHERE l.status = 'active' AND l.remaining_balance > 0"
    );
    $stmt->execute();
    $loans = $stmt->fetchAll();

    $today     = new DateTime('today');
    $todayStr  = $today->format('Y-m-d');

    foreach ($loans as $loan) {
        try {
            $created      = new DateTime($loan['created_at']);
            $duration     = (int) $loan['duration_months'];
            $monthlyPayment  = (float) $loan['monthly_payment'];
            $remaining       = (float) $loan['remaining_balance'];

            if ($monthlyPayment <= 0 || $remaining <= 0) {
                continue;
            }

            // Find the next upcoming due date (monthly from created_at)
            $nextDue = null;
            for ($m = 1; $m <= $duration; $m++) {
                $candidate = clone $created;
                $candidate->modify("+{$m} months");
                $candidateStr = $candidate->format('Y-m-d');

                // Use first due date that is today or in the future
                if ($candidateStr >= $todayStr) {
                    $nextDue = $candidate;
                    break;
                }
            }

            if (!$nextDue) {
                continue; // All dues are in the past — loan is overdue/closing
            }

            $diff = (int) $today->diff($nextDue)->days;
            $dueDateStr = $nextDue->format('d M Y');

            // Send reminder if due date is today or 3 days away
            if ($diff <= 3) {
                try {
                    Mailer::sendLoanPaymentDue(
                        $loan['email'],
                        $loan['full_name'],
                        number_format($monthlyPayment, 2),
                        number_format($remaining, 2),
                        $dueDateStr
                    );
                } catch (Exception $mailErr) {
                    error_log('loan-monitor cron: mail error for user ' . $loan['user_id'] . ': ' . $mailErr->getMessage());
                }

                $processed++;
            }

        } catch (Exception $inner) {
            error_log('loan-monitor cron error for loan ' . ($loan['id'] ?? '?') . ': ' . $inner->getMessage());
            $errors++;
        }
    }

    $elapsed = round(microtime(true) - $startTime, 2);
    $message = "Reminders sent: {$processed}, Errors: {$errors}, Time: {$elapsed}s";

    $db->prepare(
        "INSERT INTO cron_logs (job_name, status, message) VALUES ('loan-monitor', :status, :msg)"
    )->execute([
        'status' => $errors === 0 ? 'success' : 'partial',
        'msg'    => $message,
    ]);

    echo $message . PHP_EOL;

} catch (Exception $e) {
    $message = 'Fatal error: ' . $e->getMessage();
    error_log('loan-monitor cron fatal: ' . $message);

    try {
        $db->prepare(
            "INSERT INTO cron_logs (job_name, status, message) VALUES ('loan-monitor', 'failed', :msg)"
        )->execute(['msg' => $message]);
    } catch (Exception $logErr) {}

    echo $message . PHP_EOL;
    exit(1);
}

<?php
/**
 * Project: arqoracapital
 * Created by: Wayne
 *
 * Daily payout cron job
 * Schedule: 0 0 * * * php /path/to/api/cron/daily-payouts.php
 *
 * - Credits daily earnings to active investments
 * - Returns principal + marks investment completed after duration ends
 * - Awards 5% referral commission on each earning
 * - Logs results to cron_logs table
 */

// Allow CLI only
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Access denied');
}

define('APP_ROOT', dirname(__DIR__, 2));
require_once APP_ROOT . '/config/database.php';
require_once APP_ROOT . '/config/env.php';

loadEnv(APP_ROOT . '/.env');

$startTime = microtime(true);
$processed = 0;
$errors    = 0;

try {
    $db = Database::getInstance()->getConnection();

    // Fetch all active investments where the plan period has started
    $stmt = $db->prepare(
        "SELECT i.*, u.id AS owner_id
         FROM investments i
         JOIN users u ON u.id = i.user_id
         WHERE i.status = 'active' AND i.starts_at <= NOW()"
    );
    $stmt->execute();
    $investments = $stmt->fetchAll();

    $today = date('Y-m-d');

    foreach ($investments as $investment) {
        try {
            $db->beginTransaction();

            $inv_id    = $investment['id'];
            $user_id   = $investment['user_id'];
            $amount    = (float) $investment['amount'];
            $rate      = (float) $investment['daily_rate'];
            $daily_earn = round($amount * $rate, 8);

            // Skip if already paid out today
            $existsStmt = $db->prepare(
                "SELECT id FROM earnings WHERE investment_id = :inv_id AND payout_date = :today"
            );
            $existsStmt->execute(['inv_id' => $inv_id, 'today' => $today]);
            if ($existsStmt->fetch()) {
                $db->rollBack();
                continue;
            }

            // Credit daily earning
            $db->prepare(
                "INSERT INTO earnings (investment_id, user_id, amount, payout_date)
                 VALUES (:inv_id, :uid, :amount, :date)"
            )->execute([
                'inv_id' => $inv_id,
                'uid'    => $user_id,
                'amount' => $daily_earn,
                'date'   => $today,
            ]);

            $db->prepare(
                "UPDATE investments SET total_earned = total_earned + :earn WHERE id = :inv_id"
            )->execute(['earn' => $daily_earn, 'inv_id' => $inv_id]);

            // Credit wallet
            $db->prepare(
                "INSERT INTO wallets (user_id, balance) VALUES (:uid, :earn)
                 ON DUPLICATE KEY UPDATE balance = balance + :earn2, updated_at = NOW()"
            )->execute(['uid' => $user_id, 'earn' => $daily_earn, 'earn2' => $daily_earn]);

            // Log earning as transaction
            $db->prepare(
                "INSERT INTO transactions (user_id, type, amount, currency, status, notes)
                 VALUES (:uid, 'earning', :amount, 'USD', 'completed', :note)"
            )->execute([
                'uid'    => $user_id,
                'amount' => $daily_earn,
                'note'   => ucfirst($investment['plan_name']) . ' plan daily return',
            ]);

            // Referral commission (5% of earning to referrer)
            $refStmt = $db->prepare(
                "SELECT referrer_id, commission_rate FROM referrals WHERE referred_id = :uid"
            );
            $refStmt->execute(['uid' => $user_id]);
            $referral = $refStmt->fetch();

            if ($referral) {
                $commission = round($daily_earn * (float) $referral['commission_rate'], 8);
                if ($commission > 0) {
                    $db->prepare(
                        "INSERT INTO wallets (user_id, balance) VALUES (:uid, :comm)
                         ON DUPLICATE KEY UPDATE balance = balance + :comm2, updated_at = NOW()"
                    )->execute([
                        'uid'   => $referral['referrer_id'],
                        'comm'  => $commission,
                        'comm2' => $commission,
                    ]);

                    $db->prepare(
                        "INSERT INTO transactions (user_id, type, amount, currency, status, notes)
                         VALUES (:uid, 'referral_bonus', :amount, 'USD', 'completed', :note)"
                    )->execute([
                        'uid'    => $referral['referrer_id'],
                        'amount' => $commission,
                        'note'   => 'Referral commission from investment earning',
                    ]);

                    $db->prepare(
                        "UPDATE referrals SET total_earned = total_earned + :comm WHERE referrer_id = :uid AND referred_id = :ref_uid"
                    )->execute([
                        'comm'    => $commission,
                        'uid'     => $referral['referrer_id'],
                        'ref_uid' => $user_id,
                    ]);
                }
            }

            // Check if investment period is over — return principal
            if (strtotime($investment['ends_at']) <= time()) {
                $principal = (float) $investment['amount'];

                $db->prepare(
                    "UPDATE wallets SET balance = balance + :principal, updated_at = NOW() WHERE user_id = :uid"
                )->execute(['principal' => $principal, 'uid' => $user_id]);

                $db->prepare(
                    "INSERT INTO transactions (user_id, type, amount, currency, status, notes)
                     VALUES (:uid, 'deposit', :amount, 'USD', 'completed', :note)"
                )->execute([
                    'uid'    => $user_id,
                    'amount' => $principal,
                    'note'   => ucfirst($investment['plan_name']) . ' plan — principal returned',
                ]);

                $db->prepare("UPDATE investments SET status = 'completed' WHERE id = :id")
                   ->execute(['id' => $inv_id]);
            }

            $db->commit();
            $processed++;

        } catch (Exception $inner) {
            if ($db->inTransaction()) $db->rollBack();
            error_log('Cron payout error for investment ' . ($investment['id'] ?? '?') . ': ' . $inner->getMessage());
            $errors++;
        }
    }

    $elapsed = round(microtime(true) - $startTime, 2);
    $message = "Processed: {$processed}, Errors: {$errors}, Time: {$elapsed}s";

    $db->prepare(
        "INSERT INTO cron_logs (job_name, status, message) VALUES ('daily-payouts', :status, :msg)"
    )->execute([
        'status' => $errors === 0 ? 'success' : 'failed',
        'msg'    => $message,
    ]);

    echo $message . PHP_EOL;

} catch (Exception $e) {
    $message = 'Fatal error: ' . $e->getMessage();
    error_log('Cron fatal: ' . $message);

    try {
        $db->prepare(
            "INSERT INTO cron_logs (job_name, status, message) VALUES ('daily-payouts', 'failed', :msg)"
        )->execute(['msg' => $message]);
    } catch (Exception $logErr) {}

    echo $message . PHP_EOL;
    exit(1);
}

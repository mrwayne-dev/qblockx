<?php
/**
 * Project: arqoracapital
 * Central Mailer: loads HTML templates, fills {{placeholders}}, sends via PHPMailer SMTP
 *
 * Usage:
 *   require_once '/path/to/api/utilities/email_templates.php';
 *   Mailer::sendVerification($email, $name, $verifyLink);
 */

class Mailer
{
    // ── Template root ────────────────────────────────────────────────────────
    private static function templateDir(): string
    {
        // api/utilities/ → two levels up → project root → assets/email-templates/
        return dirname(__DIR__, 2) . '/assets/email-templates/';
    }

    // ── Render ───────────────────────────────────────────────────────────────

    /**
     * Load an HTML template file, replace {{placeholder}} vars, and fix branding.
     *
     * @param  string $templateFile  Filename only, e.g. 'verify-email.html'
     * @param  array  $vars          [ 'placeholder' => 'value', ... ]
     * @return string  Rendered HTML (empty string if file not found)
     */
    public static function render(string $templateFile, array $vars = []): string
    {
        $path = self::templateDir() . $templateFile;

        if (!file_exists($path)) {
            return '';
        }

        $html    = file_get_contents($path);
        $appName = getenv('APP_NAME') ?: 'ArqoraCapital';
        $appUrl  = rtrim(getenv('APP_URL') ?: 'https://arqoracapital.com', '/');
        $appHost = parse_url($appUrl, PHP_URL_HOST) ?: 'arqoracapital.com';

        // Merge caller vars with defaults
        $allVars = array_merge([
            'year'    => date('Y'),
            'app_url' => $appUrl,
        ], $vars);

        // Replace all {{placeholder}} tokens (escape values for HTML)
        foreach ($allVars as $key => $value) {
            $html = str_replace(
                '{{' . $key . '}}',
                htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                $html
            );
        }

        // ── Rebrand: replace every "Tesla Investment" reference ──────────────
        $html = str_replace('Tesla Investment', $appName, $html);
        $html = str_replace('tesla-investment.com', $appHost, $html);

        // Replace Tesla logo image with our logo
        $html = str_replace(
            'https://tesla-investment.com/assets/images/logo/tesla-symbol-logo.svg',
            $appUrl . '/assets/images/logo/1.png',
            $html
        );

        // Fix any remaining {{...}} that were not supplied — leave them blank
        $html = preg_replace('/\{\{[^}]+\}\}/', '', $html);

        return $html;
    }

    // ── Core send ────────────────────────────────────────────────────────────

    /**
     * Send an HTML email via PHPMailer SMTP.
     * All SMTP config is read from environment variables.
     *
     * @param  string $to      Recipient address
     * @param  string $toName  Recipient display name
     * @param  string $subject Subject line
     * @param  string $html    Full HTML body
     * @return bool  true on success, false on any failure (never throws)
     */
    public static function send(string $to, string $toName, string $subject, string $html): bool
    {
        try {
            require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

            $port = (int) (getenv('SMTP_PORT') ?: 587);

            // Port 465 = implicit SSL (SMTPS); 587 / 25 = STARTTLS
            $encryption = ($port === 465)
                ? \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS      // 'ssl'
                : \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;  // 'tls'

            $mail           = new \PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = getenv('SMTP_HOST') ?: '';
            $mail->SMTPAuth   = true;
            $mail->Username   = getenv('SMTP_USER') ?: '';
            $mail->Password   = getenv('SMTP_PASS') ?: '';
            $mail->SMTPSecure = $encryption;
            $mail->Port       = $port;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom(
                getenv('SMTP_FROM')      ?: 'noreply@arqoracapital.com',
                getenv('SMTP_FROM_NAME') ?: (getenv('APP_NAME') ?: 'ArqoraCapital')
            );
            $mail->addAddress($to, $toName);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $html;
            $mail->AltBody = strip_tags($html);

            $mail->send();
            return true;

        } catch (\Throwable $e) {
            // Log to PHP error log so you can debug without breaking API responses
            error_log('[Mailer] Failed to send to ' . $to . ' | Subject: ' . $subject . ' | Error: ' . $e->getMessage());
            return false;
        }
    }

    // ── Convenience senders ──────────────────────────────────────────────────

    /**
     * Email verification link — sent on registration and resend requests.
     */
    public static function sendVerification(string $email, string $name, string $verifyLink): bool
    {
        $appName = getenv('APP_NAME') ?: 'ArqoraCapital';
        $appUrl  = rtrim(getenv('APP_URL') ?: '', '/');

        $html = self::render('verify-email.html', [
            'first_name'        => $name ?: 'there',
            // Template shows a "code" — we repurpose it to show the link text
            'verification_code' => 'Use the button below to verify your account.',
        ]);

        // The template button href is {{app_url}}/register (already rendered to $appUrl/register)
        // Replace that with the real verify link
        if ($appUrl) {
            $html = str_replace(
                htmlspecialchars($appUrl . '/register', ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($verifyLink, ENT_QUOTES, 'UTF-8'),
                $html
            );
            // Also handle un-encoded version (href attributes may not encode the URL)
            $html = str_replace($appUrl . '/register', $verifyLink, $html);
        }

        return self::send(
            $email,
            $name,
            'Verify your email — ' . $appName,
            $html
        );
    }

    /**
     * Password reset link — for both users and admins.
     */
    public static function sendPasswordReset(string $email, string $name, string $resetUrl): bool
    {
        $appName = getenv('APP_NAME') ?: 'ArqoraCapital';

        $html = self::render('password-reset.html', [
            'first_name' => $name ?: 'there',
            'reset_url'  => $resetUrl,
        ]);

        return self::send($email, $name, 'Password Reset — ' . $appName, $html);
    }

    /**
     * Deposit received — payment detected, awaiting blockchain confirmation.
     */
    public static function sendDepositPending(string $email, string $name): bool
    {
        $appName = getenv('APP_NAME') ?: 'ArqoraCapital';

        $html = self::render('deposit-pending.html', [
            'first_name' => $name ?: 'there',
        ]);

        return self::send($email, $name, 'Deposit Received — ' . $appName, $html);
    }

    /**
     * Deposit confirmed and credited to wallet.
     */
    public static function sendDepositConfirmed(
        string $email,
        string $name,
        string $amount,
        string $currency,
        string $paymentId
    ): bool {
        $appName = getenv('APP_NAME') ?: 'ArqoraCapital';

        $html = self::render('deposit-confirmed.html', [
            'first_name' => $name ?: 'there',
            'amount'     => $amount,
            'currency'   => $currency,
            'payment_id' => $paymentId,
        ]);

        return self::send($email, $name, 'Deposit Confirmed — ' . $appName, $html);
    }

    /**
     * Investment contract started.
     */
    public static function sendInvestmentStarted(
        string $email,
        string $name,
        string $planName,
        string $amount,
        string $dailyYieldMin,
        string $dailyYieldMax,
        int    $durationDays
    ): bool {
        $appName = getenv('APP_NAME') ?: 'ArqoraCapital';

        $html = self::render('investment-started.html', [
            'first_name'      => $name ?: 'there',
            'plan_name'       => $planName,
            'amount'          => $amount,
            'daily_yield_min' => $dailyYieldMin,
            'daily_yield_max' => $dailyYieldMax,
            'duration_days'   => (string) $durationDays,
        ]);

        return self::send($email, $name, 'Investment Started — ' . $appName, $html);
    }

    /**
     * Investment contract completed (all 5 days done).
     */
    public static function sendInvestmentCompleted(
        string $email,
        string $name,
        string $planName,
        string $amount,
        string $totalProfit,
        string $completionDate
    ): bool {
        $appName = getenv('APP_NAME') ?: 'ArqoraCapital';

        $html = self::render('investment-completed.html', [
            'first_name'      => $name ?: 'there',
            'plan_name'       => $planName,
            'amount'          => $amount,
            'total_profit'    => $totalProfit,
            'completion_date' => $completionDate,
        ]);

        return self::send($email, $name, 'Investment Completed — ' . $appName, $html);
    }

    /**
     * Daily profit credited to wallet.
     */
    public static function sendProfitCredited(
        string $email,
        string $name,
        string $profitAmount,
        string $planName,
        string $creditDate
    ): bool {
        $appName = getenv('APP_NAME') ?: 'ArqoraCapital';

        $html = self::render('profit-credited.html', [
            'first_name'    => $name ?: 'there',
            'profit_amount' => $profitAmount,
            'plan_name'     => $planName,
            'credit_date'   => $creditDate,
        ]);

        return self::send($email, $name, 'Profit Credited — ' . $appName, $html);
    }

    /**
     * Withdrawal request received, awaiting admin review.
     */
    public static function sendWithdrawalPending(
        string $email,
        string $name,
        string $amount,
        string $walletAddress,
        int    $processingHours = 24
    ): bool {
        $appName = getenv('APP_NAME') ?: 'ArqoraCapital';

        $html = self::render('withdrawal-pending.html', [
            'first_name'       => $name ?: 'there',
            'amount'           => $amount,
            'wallet_address'   => $walletAddress,
            'processing_hours' => (string) $processingHours,
        ]);

        return self::send($email, $name, 'Withdrawal Submitted — ' . $appName, $html);
    }

    /**
     * Withdrawal approved and processed.
     */
    public static function sendWithdrawalConfirmed(
        string $email,
        string $name,
        string $amount,
        string $walletAddress,
        string $txHash = ''
    ): bool {
        $appName = getenv('APP_NAME') ?: 'ArqoraCapital';

        $html = self::render('withdrawal-confirmed.html', [
            'first_name'     => $name ?: 'there',
            'amount'         => $amount,
            'wallet_address' => $walletAddress,
            'tx_hash'        => $txHash ?: 'N/A',
        ]);

        return self::send($email, $name, 'Withdrawal Confirmed — ' . $appName, $html);
    }

    /**
     * Withdrawal rejected, funds returned to wallet.
     */
    public static function sendWithdrawalRejected(
        string $email,
        string $name,
        string $amount,
        string $walletAddress,
        string $submittedDate,
        string $rejectionReason = ''
    ): bool {
        $appName = getenv('APP_NAME') ?: 'ArqoraCapital';

        $html = self::render('withdrawal-rejected.html', [
            'first_name'       => $name ?: 'there',
            'amount'           => $amount,
            'wallet_address'   => $walletAddress,
            'submitted_date'   => $submittedDate,
            'rejection_reason' => $rejectionReason ?: 'No reason provided.',
        ]);

        return self::send($email, $name, 'Withdrawal Rejected — ' . $appName, $html);
    }

    /**
     * Referral commission credited.
     */
    public static function sendReferralBonus(
        string $email,
        string $name,
        string $bonusAmount,
        string $referredName
    ): bool {
        $appName = getenv('APP_NAME') ?: 'ArqoraCapital';

        $html = self::render('referral-bonus.html', [
            'first_name'    => $name ?: 'there',
            'bonus_amount'  => $bonusAmount,
            'referred_name' => $referredName,
        ]);

        return self::send($email, $name, 'Referral Bonus Earned — ' . $appName, $html);
    }

    /**
     * User sign-in notification — sent after every successful user login.
     */
    public static function sendUserSignIn(string $email, string $name, string $loginTime): bool
    {
        $appName = getenv('APP_NAME') ?: 'ArqoraCapital';

        $html = self::render('user-signin.html', [
            'first_name' => $name ?: 'there',
            'login_time' => $loginTime,
        ]);

        return self::send($email, $name, 'New Sign-In Detected — ' . $appName, $html);
    }

    /**
     * Admin sign-in security alert — sent after every successful admin login.
     */
    public static function sendAdminSignIn(string $email, string $name, string $loginTime): bool
    {
        $appName = getenv('APP_NAME') ?: 'ArqoraCapital';

        $html = self::render('admin-signin.html', [
            'first_name' => $name ?: 'Admin',
            'login_time' => $loginTime,
        ]);

        return self::send($email, $name, 'Admin Panel Sign-In Alert — ' . $appName, $html);
    }

}


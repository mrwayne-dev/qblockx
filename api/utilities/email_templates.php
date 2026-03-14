<?php
/**
 * Project: crestvalebank
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
        $appName = getenv('APP_NAME') ?: 'CrestVale Bank';
        $appUrl  = rtrim(getenv('APP_URL') ?: 'https://crestvalebank.com', '/');
        $appHost = parse_url($appUrl, PHP_URL_HOST) ?: 'crestvalebank.com';

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

        // Ensure {{app_host}} is replaced (not in allVars by default)
        $html = str_replace('{{app_host}}', $appHost, $html);

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
                getenv('SMTP_FROM')      ?: 'noreply@crestvalebank.com',
                getenv('SMTP_FROM_NAME') ?: (getenv('APP_NAME') ?: 'CrestVale Bank')
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
     * Email verification code — sent on registration and resend requests.
     *
     * @param  string $code  6-digit numeric verification code
     */
    public static function sendVerification(string $email, string $name, string $code): bool
    {
        $appName = getenv('APP_NAME') ?: 'CrestVale Bank';

        $html = self::render('verify-email.html', [
            'first_name'        => $name ?: 'there',
            'verification_code' => $code,
        ]);

        return self::send(
            $email,
            $name,
            'Your verification code is ' . $code . ' — ' . $appName,
            $html
        );
    }

    /**
     * Password reset link — for both users and admins.
     */
    public static function sendPasswordReset(string $email, string $name, string $resetUrl): bool
    {
        $appName = getenv('APP_NAME') ?: 'CrestVale Bank';

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
        $appName = getenv('APP_NAME') ?: 'CrestVale Bank';

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
        $appName = getenv('APP_NAME') ?: 'CrestVale Bank';

        $html = self::render('deposit-confirmed.html', [
            'first_name' => $name ?: 'there',
            'amount'     => $amount,
            'currency'   => $currency,
            'payment_id' => $paymentId,
        ]);

        return self::send($email, $name, 'Deposit Confirmed — ' . $appName, $html);
    }

    /**
     * Savings plan created.
     */
    public static function sendSavingsPlanCreated(
        string $email,
        string $name,
        string $planName,
        string $targetAmount,
        string $interestRate,
        int    $durationMonths
    ): bool {
        $appName = getenv('APP_NAME') ?: 'CrestVale Bank';

        $html = self::render('savings-plan-created.html', [
            'first_name'      => $name ?: 'there',
            'plan_name'       => $planName,
            'target_amount'   => $targetAmount,
            'interest_rate'   => $interestRate,
            'duration_months' => (string) $durationMonths,
        ]);

        return self::send($email, $name, 'Savings Plan Created — ' . $appName, $html);
    }

    /**
     * Fixed deposit matured and return credited.
     */
    public static function sendDepositMatured(
        string $email,
        string $name,
        string $amount,
        string $totalReturn,
        string $maturityDate
    ): bool {
        $appName = getenv('APP_NAME') ?: 'CrestVale Bank';

        $html = self::render('deposit-matured.html', [
            'first_name'    => $name ?: 'there',
            'amount'        => $amount,
            'total_return'  => $totalReturn,
            'maturity_date' => $maturityDate,
        ]);

        return self::send($email, $name, 'Fixed Deposit Matured — ' . $appName, $html);
    }

    /**
     * Interest credited to wallet.
     */
    public static function sendInterestCredited(
        string $email,
        string $name,
        string $interestAmount,
        string $planName,
        string $creditDate
    ): bool {
        $appName = getenv('APP_NAME') ?: 'CrestVale Bank';

        $html = self::render('interest-credited.html', [
            'first_name'      => $name ?: 'there',
            'interest_amount' => $interestAmount,
            'plan_name'       => $planName,
            'credit_date'     => $creditDate,
        ]);

        return self::send($email, $name, 'Interest Credited — ' . $appName, $html);
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
        $appName = getenv('APP_NAME') ?: 'CrestVale Bank';

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
        $appName = getenv('APP_NAME') ?: 'CrestVale Bank';

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
        $appName = getenv('APP_NAME') ?: 'CrestVale Bank';

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
        $appName = getenv('APP_NAME') ?: 'CrestVale Bank';

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
        $appName = getenv('APP_NAME') ?: 'CrestVale Bank';

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
        $appName = getenv('APP_NAME') ?: 'CrestVale Bank';

        $html = self::render('admin-signin.html', [
            'first_name' => $name ?: 'Admin',
            'login_time' => $loginTime,
        ]);

        return self::send($email, $name, 'Admin Panel Sign-In Alert — ' . $appName, $html);
    }

    /**
     * Fixed deposit opened confirmation.
     */
    public static function sendFixedDepositOpened(
        string $email,
        string $name,
        string $amount,
        string $interestRate,
        int    $durationMonths,
        string $maturityDate,
        string $expectedReturn
    ): bool {
        $appName = getenv('APP_NAME') ?: 'CrestVale Bank';

        $html = self::render('fixed-deposit-opened.html', [
            'first_name'      => $name ?: 'there',
            'amount'          => $amount,
            'interest_rate'   => $interestRate,
            'duration_months' => (string) $durationMonths,
            'maturity_date'   => $maturityDate,
            'expected_return' => $expectedReturn,
        ]);

        return self::send($email, $name, 'Fixed Deposit Opened — ' . $appName, $html);
    }

    /**
     * Loan application approved and funds disbursed.
     */
    public static function sendLoanApproved(
        string $email,
        string $name,
        string $amount,
        string $monthlyPayment,
        int    $durationMonths
    ): bool {
        $appName = getenv('APP_NAME') ?: 'CrestVale Bank';

        $html = self::render('loan-approved.html', [
            'first_name'      => $name ?: 'there',
            'amount'          => $amount,
            'monthly_payment' => $monthlyPayment,
            'duration_months' => (string) $durationMonths,
        ]);

        return self::send($email, $name, 'Loan Approved — ' . $appName, $html);
    }

    /**
     * Loan application rejected.
     */
    public static function sendLoanRejected(
        string $email,
        string $name,
        string $amount,
        string $reason = ''
    ): bool {
        $appName = getenv('APP_NAME') ?: 'CrestVale Bank';

        $html = self::render('loan-rejected.html', [
            'first_name' => $name ?: 'there',
            'amount'     => $amount,
            'reason'     => $reason ?: 'Your application did not meet our current lending criteria.',
        ]);

        return self::send($email, $name, 'Loan Application Update — ' . $appName, $html);
    }

    /**
     * Monthly loan payment due reminder.
     */
    public static function sendLoanPaymentDue(
        string $email,
        string $name,
        string $monthlyPayment,
        string $remainingBalance,
        string $dueDate
    ): bool {
        $appName = getenv('APP_NAME') ?: 'CrestVale Bank';

        $html = self::render('loan-payment-due.html', [
            'first_name'        => $name ?: 'there',
            'monthly_payment'   => $monthlyPayment,
            'remaining_balance' => $remainingBalance,
            'due_date'          => $dueDate,
        ]);

        return self::send($email, $name, 'Loan Payment Due — ' . $appName, $html);
    }

}


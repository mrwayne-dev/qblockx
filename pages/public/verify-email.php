<?php
/**
 * Project: arqoracapital
 * Page: Email Verification
 *
 * Two modes:
 *   1. No token param  → "Check your inbox" confirmation screen (shown after register)
 *   2. ?token=xxx       → Actually verify the token, show result
 */
$pageTitle = 'Verify Your Email';
require_once '../../includes/head.php';

$token = trim($_GET['token'] ?? '');
?>

<div class="auth-page">
  <div class="auth-split">

    <!-- ── Left brand panel ── -->
    <div class="auth-panel">
      <a href="/" class="auth-panel-logo" aria-label="ArqoraCapital home">
        <span class="nav-logo-mark" aria-hidden="true">
          <img src="/assets/images/logo/2.png" alt="">
        </span>
        ArqoraCapital
      </a>

      <div class="auth-panel-body">
        <h2 class="auth-panel-heading">One last step<br>to get started.</h2>
        <p class="auth-panel-sub">
          Email verification keeps your account secure and ensures you receive important updates about your investments.
        </p>
        <div class="auth-panel-stats">
          <div class="auth-panel-stat">
            <span class="auth-panel-stat-value">48K+</span>
            <span class="auth-panel-stat-label">Investors</span>
          </div>
          <div class="auth-panel-stat">
            <span class="auth-panel-stat-value">130+</span>
            <span class="auth-panel-stat-label">Countries</span>
          </div>
          <div class="auth-panel-stat">
            <span class="auth-panel-stat-value">8%</span>
            <span class="auth-panel-stat-label">Max Daily</span>
          </div>
        </div>
      </div>
    </div>

    <!-- ── Right panel ── -->
    <div class="auth-form-panel" id="verifyPanel" data-token="<?= htmlspecialchars($token, ENT_QUOTES) ?>">

      <?php if (empty($token)): ?>
      <!-- Mode 1: Check your inbox -->
      <div class="auth-icon-wrap" aria-hidden="true">
        <i class="ph ph-envelope-open auth-page-icon"></i>
      </div>
      <h1 class="auth-heading">Check your inbox</h1>
      <p class="auth-subtext">
        We've sent a verification link to your email address. Click the link in the email to activate your account.
      </p>

      <div class="verify-steps">
        <div class="verify-step">
          <span class="verify-step-num">1</span>
          <span class="verify-step-text">Open the email from <strong>ArqoraCapital</strong></span>
        </div>
        <div class="verify-step">
          <span class="verify-step-num">2</span>
          <span class="verify-step-text">Click the <strong>Verify Email</strong> button in the email</span>
        </div>
        <div class="verify-step">
          <span class="verify-step-num">3</span>
          <span class="verify-step-text">You'll be redirected to sign in automatically</span>
        </div>
      </div>

      <p class="auth-footer-text" style="margin-top: var(--space-8);">
        Already verified? <a href="/login" class="auth-link">Sign in</a>
      </p>
      <p class="auth-disclaimer">
        Didn't receive the email? Check your spam folder or
        <a href="#" id="resendLink" class="auth-link">resend verification email</a>.
      </p>
      <div id="resendMsg" class="auth-msg" role="alert" aria-live="polite" style="display:none; margin-top: var(--space-4);"></div>

      <?php else: ?>
      <!-- Mode 2: Processing the token via JS -->
      <div class="auth-icon-wrap" aria-hidden="true">
        <i class="ph ph-circle-notch ph-spin auth-page-icon" id="verifySpinIcon"></i>
      </div>
      <h1 class="auth-heading" id="verifyHeading">Verifying your email…</h1>
      <p class="auth-subtext" id="verifySubtext">Please wait while we confirm your email address.</p>
      <div id="verifyMsg" class="auth-msg" role="alert" aria-live="polite" style="display:none;"></div>
      <div id="verifyActions" style="display:none; margin-top: var(--space-6);">
        <a href="/login" class="btn-primary full-width" style="justify-content:center;">Sign In to Your Account</a>
      </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<style>
/* Verify steps list */
.verify-steps {
  display: flex;
  flex-direction: column;
  gap: var(--space-4);
  margin-top: var(--space-6);
  padding: var(--space-6);
  background: var(--color-surface-2);
  border-radius: var(--radius-lg);
  border: 1px solid var(--color-border);
}

.verify-step {
  display: flex;
  align-items: flex-start;
  gap: var(--space-4);
  font-size: var(--text-sm);
  color: var(--color-text-muted);
  line-height: 1.5;
}

.verify-step-num {
  flex-shrink: 0;
  width: 2.4rem;
  height: 2.4rem;
  border-radius: 50%;
  background: var(--color-accent);
  color: #FFFFFF;
  font-size: var(--text-xs);
  font-weight: var(--weight-bold);
  display: flex;
  align-items: center;
  justify-content: center;
  line-height: 1;
}
</style>


</body>
</html>

<?php
/**
 * Project: arqoracapital
 * Page: User Registration
 */
$pageTitle = 'Create Account';
require_once '../../includes/head.php';

// Capture referral code from URL parameter
$refCode = htmlspecialchars(trim($_GET['ref'] ?? ''));
?>

<div class="auth-page">
  <div class="auth-split">

    <!-- ── Left brand panel ── -->
    <div class="auth-panel">
      <a href="/pages/public/index.php" class="auth-panel-logo" aria-label="ArqoraCapital home">
        <span class="nav-logo-mark" aria-hidden="true">
          <img src="/assets/images/logo/2.png" alt="">
        </span>
        ArqoraCapital
      </a>

      <div class="auth-panel-body">
        <h2 class="auth-panel-heading">Join thousands<br>of investors.</h2>
        <p class="auth-panel-sub">
          Create your account today and start earning daily returns through automated crypto investment contracts.
        </p>
        <div class="auth-panel-stats">
          <div class="auth-panel-stat">
            <span class="auth-panel-stat-value">48K+</span>
            <span class="auth-panel-stat-label">Investors</span>
          </div>
          <div class="auth-panel-stat">
            <span class="auth-panel-stat-value">$2M+</span>
            <span class="auth-panel-stat-label">Paid Out</span>
          </div>
          <div class="auth-panel-stat">
            <span class="auth-panel-stat-value">5 Days</span>
            <span class="auth-panel-stat-label">Per Cycle</span>
          </div>
        </div>
      </div>
    </div>

    <!-- ── Right form panel ── -->
    <div class="auth-form-panel">

      <h1 class="auth-heading">Create your account</h1>
      <p class="auth-subtext">Start earning daily returns from your first investment.</p>

      <div id="authMsg" class="auth-msg" role="alert" aria-live="polite" style="display:none;"></div>

      <form id="registerForm" novalidate>

        <div class="form-group">
          <label for="full_name">Full name</label>
          <div class="input-icon-wrap">
            <i class="ph ph-user input-icon" aria-hidden="true"></i>
            <input type="text" id="full_name" name="full_name"
                   placeholder="John Smith" autocomplete="name">
          </div>
        </div>

        <div class="form-group">
          <label for="email">Email address <span aria-hidden="true">*</span></label>
          <div class="input-icon-wrap">
            <i class="ph ph-envelope input-icon" aria-hidden="true"></i>
            <input type="email" id="email" name="email" required
                   placeholder="you@example.com" autocomplete="email">
          </div>
        </div>

        <div class="form-group">
          <label for="password">Password <span aria-hidden="true">*</span></label>
          <div class="input-icon-wrap">
            <i class="ph ph-lock-simple input-icon" aria-hidden="true"></i>
            <input type="password" id="password" name="password" required
                   minlength="8" placeholder="Min. 8 characters" autocomplete="new-password">
            <button type="button" class="input-toggle-pw" aria-label="Toggle password visibility" tabindex="-1">
              <i class="ph ph-eye" aria-hidden="true"></i>
            </button>
          </div>
        </div>

        <div class="form-group">
          <label for="confirm">Confirm password <span aria-hidden="true">*</span></label>
          <div class="input-icon-wrap">
            <i class="ph ph-lock-simple input-icon" aria-hidden="true"></i>
            <input type="password" id="confirm" name="confirm" required
                   placeholder="Repeat your password" autocomplete="new-password">
          </div>
        </div>

        <?php if ($refCode): ?>
        <div class="form-group">
          <label for="ref_code">Referral code</label>
          <div class="input-icon-wrap">
            <i class="ph ph-gift input-icon" aria-hidden="true"></i>
            <input type="text" id="ref_code" name="ref_code"
                   value="<?= $refCode ?>" readonly
                   class="input-readonly">
          </div>
        </div>
        <?php else: ?>
        <div class="form-group">
          <label for="ref_code">Referral code <span class="label-optional">(optional)</span></label>
          <div class="input-icon-wrap">
            <i class="ph ph-gift input-icon" aria-hidden="true"></i>
            <input type="text" id="ref_code" name="ref_code"
                   placeholder="Enter referral code">
          </div>
        </div>
        <?php endif; ?>

        <button type="submit" class="btn-primary full-width auth-submit" id="registerBtn">
          <span class="btn-text">Create Account</span>
          <span class="btn-spinner" style="display:none;">
            <i class="ph ph-circle-notch ph-spin" aria-hidden="true"></i>
          </span>
        </button>

      </form>

      <p class="auth-footer-text">
        Already have an account?
        <a href="/pages/public/login.php" class="auth-link">Sign in</a>
      </p>

      <p class="auth-disclaimer">
        By creating an account you agree to our
        <a href="/pages/public/terms.php" class="auth-link">Terms of Service</a> and
        <a href="/pages/public/privacy.php" class="auth-link">Privacy Policy</a>.
      </p>

    </div>
  </div>
</div>


</body>
</html>

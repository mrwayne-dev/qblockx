<?php
/**
 * Project: arqoracapital
 * Page: User Login
 */
$pageTitle = 'Sign In';
require_once '../../includes/head.php';
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
        <h2 class="auth-panel-heading">Your daily<br>returns await.</h2>
        <p class="auth-panel-sub">
          Sign in and monitor your automated crypto investment contracts in real time.
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

    <!-- ── Right form panel ── -->
    <div class="auth-form-panel">

      <h1 class="auth-heading">Welcome back</h1>
      <p class="auth-subtext">Sign in to your investment account</p>

      <!-- Error/success message -->
      <div id="authMsg" class="auth-msg" role="alert" aria-live="polite" style="display:none;"></div>

      <form id="loginForm" novalidate>

        <div class="form-group">
          <label for="email">Email address</label>
          <div class="input-icon-wrap">
            <i class="ph ph-envelope input-icon" aria-hidden="true"></i>
            <input type="email" id="email" name="email" required
                   placeholder="you@example.com" autocomplete="email">
          </div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-icon-wrap">
            <i class="ph ph-lock-simple input-icon" aria-hidden="true"></i>
            <input type="password" id="password" name="password" required
                   placeholder="Your password" autocomplete="current-password">
            <button type="button" class="input-toggle-pw" aria-label="Toggle password visibility" tabindex="-1">
              <i class="ph ph-eye" aria-hidden="true"></i>
            </button>
          </div>
        </div>

        <div class="auth-row">
          <a href="/forgot-password" class="auth-link">Forgot password?</a>
        </div>

        <button type="submit" class="btn-primary full-width auth-submit" id="loginBtn">
          <span class="btn-text">Sign In</span>
          <span class="btn-spinner" style="display:none;">
            <i class="ph ph-circle-notch ph-spin" aria-hidden="true"></i>
          </span>
        </button>

      </form>

      <p class="auth-footer-text">
        Don't have an account?
        <a href="/register" class="auth-link">Create one</a>
      </p>

    </div>
  </div>
</div>


</body>
</html>

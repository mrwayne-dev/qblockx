<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin Login — ArqoraCapital</title>

  <!-- Fonts -->
  <link rel="preload" href="/assets/fonts/DMSans-Regular.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="/assets/fonts/DMSans-Bold.woff2"    as="font" type="font/woff2" crossorigin>

  <!-- Styles -->
  <link rel="stylesheet" href="/assets/css/main.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
  <link rel="stylesheet" href="/assets/css/admin/admin.css">
  <link rel="stylesheet" href="/assets/icons/style.css">

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="/assets/favicon/favicon.ico">
</head>
<body class="admin-body">

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
        <h2 class="auth-panel-heading">Admin<br>Control Panel.</h2>
        <p class="auth-panel-sub">
          Manage users, investments, transactions, withdrawals and referrals from one place.
        </p>
        <div class="auth-panel-stats">
          <div class="auth-panel-stat">
            <span class="auth-panel-stat-value">Full</span>
            <span class="auth-panel-stat-label">Oversight</span>
          </div>
          <div class="auth-panel-stat">
            <span class="auth-panel-stat-value">Live</span>
            <span class="auth-panel-stat-label">Statistics</span>
          </div>
          <div class="auth-panel-stat">
            <span class="auth-panel-stat-value">Secure</span>
            <span class="auth-panel-stat-label">Access</span>
          </div>
        </div>
      </div>
    </div>

    <!-- ── Right form panel ── -->
    <div class="auth-form-panel">

      <h1 class="auth-heading">Admin Sign In</h1>
      <p class="auth-subtext">Sign in with your administrator credentials</p>

      <div id="authMsg" class="auth-msg" role="alert" aria-live="polite" style="display:none;"></div>

      <form id="adminLoginForm" novalidate>

        <div class="form-group">
          <label for="email">Admin Email</label>
          <div class="input-icon-wrap">
            <i class="ph ph-envelope input-icon" aria-hidden="true"></i>
            <input type="email" id="email" name="email" required
                   placeholder="admin@arqoracapital.com" autocomplete="email">
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

        <button type="submit" class="btn-primary full-width auth-submit" id="loginBtn">
          <span class="btn-text">Sign In to Admin</span>
          <span class="btn-spinner" style="display:none;">
            <i class="ph ph-circle-notch ph-spin" aria-hidden="true"></i>
          </span>
        </button>

      </form>

      <p class="auth-footer-text">
        Need an admin account?
        <a href="/admin/register" class="auth-link">Register with invite code</a>
      </p>

    </div>
  </div>
</div>

<script>
(function () {
  // Password toggle
  var pwInput  = document.getElementById('password');
  var pwToggle = document.querySelector('.input-toggle-pw');
  if (pwToggle) {
    pwToggle.addEventListener('click', function () {
      var isHidden = pwInput.type === 'password';
      pwInput.type = isHidden ? 'text' : 'password';
      this.querySelector('i').className = isHidden ? 'ph ph-eye-slash' : 'ph ph-eye';
    });
  }

  // Login form
  var form    = document.getElementById('adminLoginForm');
  var msgEl   = document.getElementById('authMsg');
  var btn     = document.getElementById('loginBtn');
  var btnText = btn.querySelector('.btn-text');
  var spinner = btn.querySelector('.btn-spinner');

  function showMsg(msg, isError) {
    msgEl.textContent = msg;
    msgEl.className   = 'auth-msg ' + (isError ? 'auth-msg--error' : 'auth-msg--success');
    msgEl.style.display = 'block';
  }

  function setLoading(on) {
    btn.disabled          = on;
    btnText.style.display = on ? 'none'  : '';
    spinner.style.display = on ? 'block' : 'none';
  }

  form.addEventListener('submit', async function (e) {
    e.preventDefault();
    msgEl.style.display = 'none';
    setLoading(true);

    var data = {
      email:    form.querySelector('[name="email"]').value.trim(),
      password: form.querySelector('[name="password"]').value
    };

    try {
      var res    = await fetch('/api/auth/admin-login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });
      var result = await res.json();

      if (result.success) {
        showMsg('Signed in. Redirecting…', false);
        window.location.href = '/pages/admin/dashboard.php';
      } else {
        showMsg(result.message || 'Invalid credentials.', true);
        setLoading(false);
      }
    } catch (err) {
      showMsg('Network error. Please try again.', true);
      setLoading(false);
    }
  });
})();
</script>

</body>
</html>

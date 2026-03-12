<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin Register — ArqoraCapital</title>

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
        <h2 class="auth-panel-heading">Admin<br>Registration.</h2>
        <p class="auth-panel-sub">
          Create an admin account using your invite code. Admin accounts have full access to the management panel.
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

      <h1 class="auth-heading">Create Admin Account</h1>
      <p class="auth-subtext">You must have an invite code to register.</p>

      <div id="authMsg" class="auth-msg" role="alert" aria-live="polite" style="display:none;"></div>

      <form id="adminRegisterForm" novalidate>

        <div class="form-group">
          <label for="full_name">Full Name</label>
          <div class="input-icon-wrap">
            <i class="ph ph-user input-icon" aria-hidden="true"></i>
            <input type="text" id="full_name" name="full_name"
                   placeholder="Your full name" autocomplete="name">
          </div>
        </div>

        <div class="form-group">
          <label for="email">Email Address <span aria-hidden="true">*</span></label>
          <div class="input-icon-wrap">
            <i class="ph ph-envelope input-icon" aria-hidden="true"></i>
            <input type="email" id="email" name="email" required
                   placeholder="admin@example.com" autocomplete="email">
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
          <label for="invite_code">Invite Code <span aria-hidden="true">*</span></label>
          <div class="input-icon-wrap">
            <i class="ph ph-key input-icon" aria-hidden="true"></i>
            <input type="text" id="invite_code" name="invite_code" required
                   placeholder="Enter your invite code" autocomplete="off">
          </div>
        </div>

        <button type="submit" class="btn-primary full-width auth-submit" id="registerBtn">
          <span class="btn-text">Create Admin Account</span>
          <span class="btn-spinner" style="display:none;">
            <i class="ph ph-circle-notch ph-spin" aria-hidden="true"></i>
          </span>
        </button>

      </form>

      <p class="auth-footer-text">
        Already have an account?
        <a href="/admin/login" class="auth-link">Sign in</a>
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

  // Register form
  var form    = document.getElementById('adminRegisterForm');
  var msgEl   = document.getElementById('authMsg');
  var btn     = document.getElementById('registerBtn');
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

    var password   = form.querySelector('[name="password"]').value;
    var inviteCode = form.querySelector('[name="invite_code"]').value.trim();

    if (password.length < 8) {
      return showMsg('Password must be at least 8 characters.', true);
    }
    if (!inviteCode) {
      return showMsg('Invite code is required.', true);
    }

    setLoading(true);

    var data = {
      full_name:   form.querySelector('[name="full_name"]').value.trim(),
      email:       form.querySelector('[name="email"]').value.trim(),
      password:    password,
      invite_code: inviteCode,
    };

    try {
      var res    = await fetch('/api/auth/admin-register.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });
      var result = await res.json();

      if (result.success) {
        showMsg('Account created! Redirecting to sign in…', false);
        setTimeout(function () {
          window.location.href = '/pages/admin/login.php';
        }, 1500);
      } else {
        showMsg(result.message || 'Registration failed. Please try again.', true);
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

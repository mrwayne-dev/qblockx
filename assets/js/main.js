/**
 * Project: arqoracapital
 * File: assets/js/main.js
 * Public-facing JS: nav pill, ticker, crypto prices, scroll animations
 */

document.addEventListener('DOMContentLoaded', function () {

  /* ── Google Translate ──────────────────────────────────────── */
  window.googleTranslateElementInit = function () {
    new google.translate.TranslateElement({
      pageLanguage: 'en',
      includedLanguages: 'af,am,ar,az,be,bg,bn,bs,ca,cs,cy,da,de,el,en,es,et,fa,fi,fr,ga,gl,gu,ha,he,hi,hr,ht,hu,hy,id,is,it,ja,jw,ka,kk,km,kn,ko,la,lo,lt,lv,mk,ml,mn,mr,ms,mt,my,ne,nl,no,pa,pl,pt,ro,ru,sd,si,sk,sl,so,sq,sr,sv,sw,ta,te,th,tl,tr,uk,ur,uz,vi,yi,yo,zh-CN,zh-TW,zu',
      layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
      autoDisplay: false
    }, 'google-translate-element');
  };

  function loadGoogleTranslate() {
    if (document.getElementById('google-translate-element')) {
      var script   = document.createElement('script');
      script.src   = 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
      script.async = true;
      document.body.appendChild(script);
    }
  }



  /* ── Nav Pill Toggle ───────────────────────────────────────── */
  function initNavToggle() {
    var drawer   = document.querySelector('.nav-mobile-drawer');
    var togglers = document.querySelectorAll('[data-nav-toggler]');

    if (!drawer || !togglers.length) return;

    togglers.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var isOpen = drawer.classList.toggle('open');
        drawer.setAttribute('aria-hidden', String(!isOpen));
        // Update all toggle buttons' aria-expanded
        togglers.forEach(function (t) {
          t.setAttribute('aria-expanded', String(isOpen));
        });
        // Lock body scroll when drawer open
        document.body.style.overflow = isOpen ? 'hidden' : '';
      });
    });

    // Close drawer when a mobile nav link is clicked
    drawer.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        drawer.classList.remove('open');
        drawer.setAttribute('aria-hidden', 'true');
        togglers.forEach(function (t) { t.setAttribute('aria-expanded', 'false'); });
        document.body.style.overflow = '';
      });
    });

    // Close on ESC key
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && drawer.classList.contains('open')) {
        drawer.classList.remove('open');
        drawer.setAttribute('aria-hidden', 'true');
        togglers.forEach(function (t) { t.setAttribute('aria-expanded', 'false'); });
        document.body.style.overflow = '';
      }
    });
  }

  /* ── Scroll-based nav pill shrink ─────────────────────────── */
  function initNavScroll() {
    var nav = document.querySelector('.nav-pill');
    if (!nav) return;
    var threshold = 60;
    window.addEventListener('scroll', function () {
      if (window.scrollY > threshold) {
        nav.classList.add('scrolled');
      } else {
        nav.classList.remove('scrolled');
      }
    }, { passive: true });
  }

  /* ── [data-appear] Scroll Animation ───────────────────────── */
  function initAppearOnScroll() {
    var elements = document.querySelectorAll('[data-appear]');
    if (!elements.length) return;

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    elements.forEach(function (el) { observer.observe(el); });
  }

  /* ── Crypto Price Updates ──────────────────────────────────── */
  async function updateCryptoPrices() {
    // Map from data-coin attr value to CoinCap asset ID
    var coinMap = {
      'bitcoin':     'bitcoin',
      'ethereum':    'ethereum',
      'binancecoin': 'binance-coin',
      'usd-coin':    'usd-coin',
      'solana':      'solana',
      'ripple':      'xrp',
      'tether':      'tether'
    };
    var capIds = Object.values(coinMap).filter(function (v, i, a) { return a.indexOf(v) === i; });
    var url = 'https://api.coincap.io/v2/assets?ids=' + capIds.join(',');

    try {
      var res = await fetch(url);
      if (!res.ok) throw new Error('API error');
      var json = await res.json();

      var priceByCapId = {};
      (json.data || []).forEach(function (asset) { priceByCapId[asset.id] = asset; });

      // Update [data-coin] elements (both stock list and ticker)
      document.querySelectorAll('[data-coin]').forEach(function (el) {
        var attrCoin = el.dataset.coin;
        var capId    = coinMap[attrCoin];
        var asset    = capId && priceByCapId[capId];
        if (!asset) return;

        var price  = parseFloat(asset.priceUsd);
        var change = parseFloat(asset.changePercent24Hr);
        var formatted = '$' + price.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        // If it's inside a ticker, show price + change
        if (el.closest('.ticker-track')) {
          var sign   = change >= 0 ? '+' : '';
          var chgStr = sign + change.toFixed(2) + '%';
          el.innerHTML = formatted + ' <small style="color:' + (change >= 0 ? 'var(--color-success)' : 'var(--color-error)') + '">' + chgStr + '</small>';
        } else {
          el.textContent = formatted;
        }
      });

      // Duplicate ticker items with updated prices (for seamless loop)
      syncTickerDuplicates();

    } catch (err) {
      console.warn('[ArqoraCapital] Crypto price fetch failed:', err);
    }
  }

  /* Sync second half of ticker with first half prices */
  function syncTickerDuplicates() {
    var track = document.querySelector('.ticker-track');
    if (!track) return;
    var items = track.querySelectorAll('.ticker-item');
    var half  = Math.floor(items.length / 2);
    for (var i = 0; i < half; i++) {
      var src = items[i].querySelector('.ticker-value');
      var dst = items[i + half]?.querySelector('.ticker-value');
      if (src && dst) dst.innerHTML = src.innerHTML;
    }
  }

  /* ── Button Redirects ─────────────────────────────────────── */
  function setupButtonRedirects() {
    var openAcctBtn = document.getElementById('openacct-btn');
    if (openAcctBtn) {
      openAcctBtn.addEventListener('click', function () {
        window.location.href = '/login';
      });
    }

    var supportBtn = document.getElementById('support-btn');
    if (supportBtn) {
      supportBtn.addEventListener('click', function () {
        window.location.href = '/contact';
      });
    }
  }

  /* ── Ticker Pause on Hover ─────────────────────────────────── */
  function initTickerHover() {
    var ticker = document.querySelector('.metrics-ticker');
    var track  = document.querySelector('.ticker-track');
    if (!ticker || !track) return;

    ticker.addEventListener('mouseenter', function () {
      track.style.animationPlayState = 'paused';
    });
    ticker.addEventListener('mouseleave', function () {
      track.style.animationPlayState = 'running';
    });
  }

  /* ── Hero Carousel ────────────────────────────────────────── */
  function initHeroCarousel() {
    var section  = document.getElementById('heroCarousel');
    if (!section) return;

    var slides   = section.querySelectorAll('.hero-slide');
    var contents = section.querySelectorAll('.hero-content');
    var dots     = section.querySelectorAll('.hero-dot');
    if (!slides.length || slides.length < 2) return;

    var current  = 0;
    var total    = slides.length;
    var timer;

    function goTo(index) {
      /* deactivate current */
      slides[current].classList.remove('active');
      if (contents[current]) contents[current].classList.remove('active');
      if (dots[current])     dots[current].classList.remove('active');
      if (dots[current])     dots[current].setAttribute('aria-selected', 'false');

      /* advance index */
      current = (index + total) % total;

      /* activate new */
      slides[current].classList.add('active');
      if (contents[current]) contents[current].classList.add('active');
      if (dots[current])     dots[current].classList.add('active');
      if (dots[current])     dots[current].setAttribute('aria-selected', 'true');
    }

    function next() { goTo(current + 1); }

    function startAutoplay() {
      timer = setInterval(next, 6000);
    }

    function stopAutoplay() {
      clearInterval(timer);
    }

    /* Dot click — jump to slide, restart timer */
    dots.forEach(function (dot, i) {
      dot.addEventListener('click', function () {
        stopAutoplay();
        goTo(i);
        startAutoplay();
      });
    });

    /* Pause on hover, resume on leave */
    section.addEventListener('mouseenter', stopAutoplay);
    section.addEventListener('mouseleave', startAutoplay);

    startAutoplay();
  }

  /* ── LightRays WebGL Hero Effect ──────────────────────────── */
  function initLightRays() {
    var container = document.getElementById('heroRays');
    if (!container || typeof window.LightRays === 'undefined') return;
    window.LightRays.init(container, {
      rayPos:         [0.5, 0.0],
      lightSpread:    0.35,
      rayLength:      0.85,
      raysColor:      [0.15, 0.38, 0.92],
      mouseInfluence: 0.08,
      noiseAmount:    0.06,
      distortion:     0.03,
      opacity:        0.18
    });
  }

  /* ── Counter Animations ────────────────────────────────────── */
  function initCounters() {
    var els = document.querySelectorAll('[data-counter]');
    if (!els.length) return;

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        var el       = entry.target;
        var target   = parseFloat(el.dataset.counter);
        var prefix   = el.dataset.counterPrefix || '';
        var suffix   = el.dataset.counterSuffix || '';
        var decimals = (target % 1 !== 0) ? 1 : 0;
        var duration = 1800;
        var start    = performance.now();

        function step(now) {
          var elapsed = Math.min((now - start) / duration, 1);
          /* ease-out-cubic */
          var ease    = 1 - Math.pow(1 - elapsed, 3);
          var value   = (target * ease).toFixed(decimals);
          el.textContent = prefix + value + suffix;
          if (elapsed < 1) {
            requestAnimationFrame(step);
          } else {
            el.textContent = prefix + target.toFixed(decimals) + suffix;
          }
        }

        requestAnimationFrame(step);
        observer.unobserve(el);
      });
    }, { threshold: 0.5 });

    els.forEach(function (el) { observer.observe(el); });
  }

  /* ── Auth: Shared Message Helper ──────────────────────────── */
  function showAuthMsg(elementId, msg, isError, isHtml) {
    var el = document.getElementById(elementId);
    if (!el) return;
    if (isHtml) {
      el.innerHTML = msg;
    } else {
      el.textContent = msg;
    }
    el.className     = 'auth-msg ' + (isError ? 'auth-msg--error' : 'auth-msg--success');
    el.style.display = '';
  }

  /* ── Auth: Password Toggle ─────────────────────────────────── */
  function initPasswordToggles() {
    document.querySelectorAll('.input-toggle-pw').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var wrap  = btn.closest('.input-icon-wrap');
        var input = wrap ? wrap.querySelector('input') : null;
        if (!input) return;
        var icon = btn.querySelector('i');
        if (input.type === 'password') {
          input.type = 'text';
          if (icon) icon.className = 'ph ph-eye-slash';
        } else {
          input.type = 'password';
          if (icon) icon.className = 'ph ph-eye';
        }
      });
    });
  }

  /* ── Auth: Login Form ──────────────────────────────────────── */
  function initLoginForm() {
    var form = document.getElementById('loginForm');
    if (!form) return;

    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      var btn     = document.getElementById('loginBtn');
      var text    = btn.querySelector('.btn-text');
      var spinner = btn.querySelector('.btn-spinner');

      btn.disabled          = true;
      text.style.display    = 'none';
      spinner.style.display = '';

      var data = {
        email:    document.getElementById('email').value.trim(),
        password: document.getElementById('password').value
      };

      try {
        var res    = await fetch('/api/auth/user-login.php', {
          method:  'POST',
          headers: { 'Content-Type': 'application/json' },
          body:    JSON.stringify(data)
        });
        var result = await res.json();

        if (result.success) {
          showAuthMsg('authMsg', 'Login successful! Redirecting…', false);
          window.location.href = '/dashboard';
        } else {
          if (result.unverified) {
            try { sessionStorage.setItem('pendingVerifyEmail', data.email); } catch (ignore) {}
            var msg = (result.message || 'Please verify your email.') +
              ' <a href="/verify-email" style="color:inherit;font-weight:600;text-decoration:underline;">Go to verification page →</a>';
            showAuthMsg('authMsg', msg, true, true);
          } else {
            showAuthMsg('authMsg', result.message || 'Invalid credentials. Please try again.', true);
          }
          btn.disabled          = false;
          text.style.display    = '';
          spinner.style.display = 'none';
        }
      } catch (err) {
        showAuthMsg('authMsg', 'A network error occurred. Please try again.', true);
        btn.disabled          = false;
        text.style.display    = '';
        spinner.style.display = 'none';
      }
    });
  }

  /* ── Auth: Register Form ───────────────────────────────────── */
  function initRegisterForm() {
    var form = document.getElementById('registerForm');
    if (!form) return;

    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      var btn      = document.getElementById('registerBtn');
      var text     = btn.querySelector('.btn-text');
      var spinner  = btn.querySelector('.btn-spinner');
      var password = document.getElementById('password').value;
      var confirm  = document.getElementById('confirm').value;

      if (password !== confirm) {
        showAuthMsg('authMsg', 'Passwords do not match.', true);
        return;
      }
      if (password.length < 8) {
        showAuthMsg('authMsg', 'Password must be at least 8 characters.', true);
        return;
      }

      btn.disabled          = true;
      text.style.display    = 'none';
      spinner.style.display = '';

      var refEl = document.getElementById('ref_code');
      var data  = {
        email:     document.getElementById('email').value.trim(),
        password:  password,
        full_name: document.getElementById('full_name').value.trim(),
        ref_code:  refEl ? refEl.value.trim() : ''
      };

      try {
        var res    = await fetch('/api/auth/user-register.php', {
          method:  'POST',
          headers: { 'Content-Type': 'application/json' },
          body:    JSON.stringify(data)
        });
        var result = await res.json();

        if (result.success) {
          showAuthMsg('authMsg', 'Account created! Check your email for a verification link…', false);
          try { sessionStorage.setItem('pendingVerifyEmail', data.email); } catch (ignore) {}
          setTimeout(function () {
            window.location.href = '/verify-email';
          }, 1500);
        } else {
          showAuthMsg('authMsg', result.message || 'Registration failed. Please try again.', true);
          btn.disabled          = false;
          text.style.display    = '';
          spinner.style.display = 'none';
        }
      } catch (err) {
        showAuthMsg('authMsg', 'A network error occurred. Please try again.', true);
        btn.disabled          = false;
        text.style.display    = '';
        spinner.style.display = 'none';
      }
    });
  }

  /* ── Auth: Forgot Password Form ────────────────────────────── */
  function initForgotPasswordForm() {
    var form = document.getElementById('forgotForm');
    if (!form) return;

    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      var btn     = document.getElementById('forgotBtn');
      var text    = btn.querySelector('.btn-text');
      var spinner = btn.querySelector('.btn-spinner');

      btn.disabled          = true;
      text.style.display    = 'none';
      spinner.style.display = '';

      var data = { email: document.getElementById('email').value.trim() };

      try {
        var res    = await fetch('/api/auth/user-forgot-password.php', {
          method:  'POST',
          headers: { 'Content-Type': 'application/json' },
          body:    JSON.stringify(data)
        });
        var result = await res.json();

        showAuthMsg('authMsg', result.message || 'If that email exists, you\'ll receive a reset link shortly.', !result.success);

        if (!result.success) {
          btn.disabled          = false;
          text.style.display    = '';
          spinner.style.display = 'none';
        }
      } catch (err) {
        showAuthMsg('authMsg', 'A network error occurred. Please try again.', true);
        btn.disabled          = false;
        text.style.display    = '';
        spinner.style.display = 'none';
      }
    });
  }

  /* ── Auth: Reset Password Form ─────────────────────────────── */
  function initResetPasswordForm() {
    var form = document.getElementById('resetForm');
    if (!form) return;

    var token = new URLSearchParams(window.location.search).get('token');

    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      var btn      = document.getElementById('resetBtn');
      var text     = btn.querySelector('.btn-text');
      var spinner  = btn.querySelector('.btn-spinner');
      var password = document.getElementById('password').value;
      var confirm  = document.getElementById('confirm').value;

      if (password !== confirm) {
        showAuthMsg('authMsg', 'Passwords do not match.', true);
        return;
      }
      if (!token) {
        showAuthMsg('authMsg', 'Invalid or missing reset token. Please request a new reset link.', true);
        return;
      }

      btn.disabled          = true;
      text.style.display    = 'none';
      spinner.style.display = '';

      try {
        var res    = await fetch('/api/auth/user-reset-password.php', {
          method:  'POST',
          headers: { 'Content-Type': 'application/json' },
          body:    JSON.stringify({ token: token, password: password })
        });
        var result = await res.json();

        showAuthMsg('authMsg', result.message, !result.success);

        if (result.success) {
          setTimeout(function () {
            window.location.href = '/login';
          }, 2000);
        } else {
          btn.disabled          = false;
          text.style.display    = '';
          spinner.style.display = 'none';
        }
      } catch (err) {
        showAuthMsg('authMsg', 'A network error occurred. Please try again.', true);
        btn.disabled          = false;
        text.style.display    = '';
        spinner.style.display = 'none';
      }
    });
  }

  /* ── Auth: Verify Email Page ───────────────────────────────── */
  function initVerifyEmailPage() {
    var panel = document.getElementById('verifyPanel');
    if (!panel) return;

    var token = panel.dataset.token || '';

    if (token) {
      // Token mode — call API immediately on page load
      (async function () {
        var heading = document.getElementById('verifyHeading');
        var subtext = document.getElementById('verifySubtext');
        var msgEl   = document.getElementById('verifyMsg');
        var actions = document.getElementById('verifyActions');
        var spinner = document.getElementById('verifySpinIcon');

        function showResult(success, message) {
          if (spinner) {
            spinner.className   = success ? 'ph ph-check-circle auth-page-icon' : 'ph ph-x-circle auth-page-icon';
            spinner.style.color = success ? 'var(--color-success)' : 'var(--color-error)';
          }
          if (heading) heading.textContent = success ? 'Email verified!' : 'Verification failed';
          if (subtext) subtext.textContent = '';
          if (msgEl) {
            msgEl.textContent   = message;
            msgEl.className     = 'auth-msg ' + (success ? 'auth-msg--success' : 'auth-msg--error');
            msgEl.style.display = '';
          }
          if (success && actions) actions.style.display = '';
        }

        try {
          var res    = await fetch('/api/auth/user-verify-email.php?token=' + encodeURIComponent(token));
          var result = await res.json();
          showResult(result.success, result.message);
          if (result.success) {
            setTimeout(function () {
              window.location.href = '/login';
            }, 3000);
          }
        } catch (err) {
          showResult(false, 'A network error occurred. Please try again.');
        }
      })();

    } else {
      // Inbox mode — wire up the resend link
      var resendLink = document.getElementById('resendLink');
      if (!resendLink) return;

      resendLink.addEventListener('click', async function (e) {
        e.preventDefault();
        var msgEl = document.getElementById('resendMsg');
        var email = sessionStorage.getItem('pendingVerifyEmail') || '';

        if (!email) {
          if (msgEl) {
            msgEl.textContent   = 'Session expired. Please register again.';
            msgEl.className     = 'auth-msg auth-msg--error';
            msgEl.style.display = '';
          }
          return;
        }

        resendLink.textContent       = 'Sending…';
        resendLink.style.pointerEvents = 'none';

        try {
          var res    = await fetch('/api/auth/user-resend-verification.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ email: email })
          });
          var result = await res.json();
          if (msgEl) {
            msgEl.textContent   = result.message;
            msgEl.className     = 'auth-msg ' + (result.success ? 'auth-msg--success' : 'auth-msg--error');
            msgEl.style.display = '';
          }
        } catch (err) {
          if (msgEl) {
            msgEl.textContent   = 'Network error. Please try again.';
            msgEl.className     = 'auth-msg auth-msg--error';
            msgEl.style.display = '';
          }
        }

        resendLink.textContent       = 'resend verification email';
        resendLink.style.pointerEvents = '';
      });
    }
  }

  /* ── Init ──────────────────────────────────────────────────── */
  loadGoogleTranslate();
  initNavToggle();
  initNavScroll();
  initAppearOnScroll();
  initHeroCarousel();
  initLightRays();
  initCounters();
  updateCryptoPrices();
  setupButtonRedirects();
  initTickerHover();
  // Auth pages
  initPasswordToggles();
  initLoginForm();
  initRegisterForm();
  initForgotPasswordForm();
  initResetPasswordForm();
  initVerifyEmailPage();

  // Refresh prices every 60 seconds
  setInterval(updateCryptoPrices, 60000);
});

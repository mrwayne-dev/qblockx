(function () {
  'use strict';

  // ── Helpers ──────────────────────────────────────────────────────────────────

  async function apiFetch(url, opts) {
    opts = opts || {};
    const res = await fetch(url, Object.assign({
      headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    }, opts));
    return res.json();
  }

  function fmt(num) {
    return parseFloat(num || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function fmtDate(dt) {
    if (!dt) return '--';
    return new Date(dt).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
  }

  // Works on HTTP (no secure context) and HTTPS
  function copyText(text, cb) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(text).then(function () { cb(true); }).catch(function () { cb(false); });
    } else {
      try {
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.style.cssText = 'position:fixed;top:-9999px;left:-9999px;opacity:0';
        document.body.appendChild(ta);
        ta.focus();
        ta.select();
        var ok = document.execCommand('copy');
        document.body.removeChild(ta);
        cb(ok);
      } catch (e) { cb(false); }
    }
  }

  function badge(status) {
    var map = {
      completed: 'badge-success',
      active:    'badge-success',
      approved:  'badge-success',
      pending:   'badge-warning',
      rejected:  'badge-error',
      failed:    'badge-error',
      cancelled: 'badge-muted'
    };
    return '<span class="badge ' + (map[status] || 'badge-muted') + '">' + status + '</span>';
  }

  function showMsg(el, msg, isError) {
    if (!el) return;
    el.textContent = msg;
    el.className = isError
      ? 'form-message form-message--error'
      : 'form-message form-message--success';
    el.style.display = 'block';
    setTimeout(function () { el.style.display = 'none'; }, 5000);
  }

  function qs(sel) { return document.querySelector(sel); }
  function setText(sel, val) { var el = qs(sel); if (el) el.textContent = val; }
  function setHTML(sel, val) { var el = qs(sel); if (el) el.innerHTML  = val; }
  function setVal(sel, val)  { var el = qs(sel); if (el) el.value = val || ''; }

  // Phosphor percent icon — used wherever % would appear in rendered text
  var pctIcon = '<i class="ph ph-percent" aria-label="%"></i>';

  // ── Modal System ─────────────────────────────────────────────────────────────

  function openModal(id) {
    var el = document.getElementById(id);
    if (!el) return;
    el.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeModal(id) {
    var el = document.getElementById(id);
    if (!el) return;
    el.classList.remove('active');
    // Restore scroll only if no other modals are open
    if (!document.querySelector('.modal-overlay.active')) {
      document.body.style.overflow = '';
    }
  }

  function closeAllModals() {
    document.querySelectorAll('.modal-overlay.active').forEach(function (el) {
      el.classList.remove('active');
    });
    document.body.style.overflow = '';
  }

  // Expose modal functions globally so inline onclick="" handlers work
  window.openModal      = openModal;
  window.closeModal     = closeModal;
  window.closeAllModals = closeAllModals;


  // ── Toast System ─────────────────────────────────────────────────────────────

  function showToast(msg, type) {
    var c = document.getElementById('toastContainer');
    if (!c) return;
    var t = document.createElement('div');
    t.className = 'toast toast--' + (type || 'info');
    t.innerHTML = '<span class="toast-msg">' + msg + '</span>'
      + '<button class="toast-close" type="button" aria-label="Close notification">'
      + '<i class="ph ph-x"></i></button>';
    t.querySelector('.toast-close').onclick = function () { t.remove(); };
    c.appendChild(t);
    setTimeout(function () { if (t.parentNode) t.remove(); }, 4000);
  }

  window.showToast = showToast;

  // ── Global Loader ────────────────────────────────────────────────────────────

  function showLoader() {
    var l = document.getElementById('globalLoader');
    if (l) l.classList.add('active');
  }

  function hideLoader() {
    var l = document.getElementById('globalLoader');
    if (l) l.classList.remove('active');
  }

  window.showLoader = showLoader;
  window.hideLoader = hideLoader;

  // ── Module State ─────────────────────────────────────────────────────────────

  var _lastBalance      = 0;  // cache for balance-hide toggle
  var _rates            = []; // cache for product rates
  var _ratesFilter      = 'savings'; // active rates tab

  // ── Background Refresh ───────────────────────────────────────────────────────
  //
  // Two-layer silent refresh — no loading states, no spinners, no flicker:
  //   • Core timer  (30 s) — updates stats + balance across all sections
  //   • Section timer (60 s) — re-renders the current section's table data
  //
  // Both timers restart whenever the user navigates to a different section.

  var _coreTimer    = null;
  var _sectionTimer = null;
  var _activeSection = 'overview';

  // Silently refresh global stats + rates from the dashboard endpoint.
  // Called every 30 s regardless of which section is visible.
  async function refreshCore() {
    try {
      var r = await apiFetch('/api/user-dashboard/dashboard.php');
      if (!r.success) return;
      var d = r.data;

      // Balance (overview stat card + wallet hero)
      var balStr = '$' + fmt(d.balance);
      setText('[data-stat="balance"]',          balStr);
      setText('[data-wallet="balance"]',        balStr);
      _lastBalance = parseFloat(d.balance || 0);
      applyBalanceHidden(localStorage.getItem('balanceHidden') === '1');

      // Other overview stat cards
      setText('[data-stat="savings-balance"]',  '$' + fmt(d.savings_balance  || 0));
      setText('[data-stat="deposits-balance"]', '$' + fmt(d.deposits_balance || 0));
      setText('[data-stat="loan-balance"]',     '$' + fmt(d.loan_balance     || 0));

      // Recent transactions (overview table only — not wallet history)
      var tbody = qs('[data-table="recent-transactions"]');
      if (tbody && d.recent_transactions) {
        tbody.innerHTML = d.recent_transactions.length
          ? d.recent_transactions.map(function (tx) {
              return '<tr>'
                + '<td>' + tx.type + '</td>'
                + '<td>$' + fmt(tx.amount) + '</td>'
                + '<td>' + badge(tx.status) + '</td>'
                + '<td>' + fmtDate(tx.created_at) + '</td>'
                + '</tr>';
            }).join('')
          : '<tr><td colspan="4" class="empty-row">No transactions yet</td></tr>';
      }

      // Update rates cache & re-render if data changed
      if (d.rates && d.rates.length) {
        _rates = d.rates;
        renderRates(_rates, _ratesFilter);
        populateProductSelects(_rates);
      }
    } catch (e) { /* silent — network blips should not surface to the user */ }
  }

  // Silently refresh the current section's detailed table/list data.
  // Only runs when the user is already looking at that section.
  function refreshSection() {
    var loader = sectionLoaders[_activeSection];
    if (loader) loader();
  }

  function startBackgroundRefresh(sectionName) {
    _activeSection = sectionName;

    // Core stats — every 30 s
    clearInterval(_coreTimer);
    _coreTimer = setInterval(refreshCore, 30000);

    // Section detail — every 60 s
    clearInterval(_sectionTimer);
    _sectionTimer = setInterval(refreshSection, 60000);
  }

  // ── Dashboard Overview ───────────────────────────────────────────────────────

  async function loadDashboard() {
    try {
      var r = await apiFetch('/api/user-dashboard/dashboard.php');
      if (!r.success) return;
      var d = r.data;

      setText('[data-stat="balance"]',           '$' + fmt(d.balance));
      setText('[data-stat="savings-balance"]',   '$' + fmt(d.savings_balance || 0));
      setText('[data-stat="deposits-balance"]',  '$' + fmt(d.deposits_balance || 0));
      setText('[data-stat="loan-balance"]',      '$' + fmt(d.loan_balance || 0));

      if (d.user) {
        var displayName = d.user.full_name || d.user.email || '';
        document.querySelectorAll('[data-user="name"]').forEach(function (el) {
          el.textContent = displayName;
        });
        // Set avatar initials
        var initial = displayName.trim().charAt(0).toUpperCase() || 'U';
        document.querySelectorAll('[data-user="initial"]').forEach(function (el) {
          el.textContent = initial;
        });
      }

      // Cache and render rates
      if (d.rates && d.rates.length) {
        _rates = d.rates;
        renderRates(_rates, _ratesFilter);
        populateProductSelects(_rates);
      }

      var tbody = qs('[data-table="recent-transactions"]');
      if (tbody) {
        if (d.recent_transactions && d.recent_transactions.length) {
          tbody.innerHTML = d.recent_transactions.map(function (tx) {
            return '<tr>'
              + '<td>' + tx.type + '</td>'
              + '<td>$' + fmt(tx.amount) + '</td>'
              + '<td>' + badge(tx.status) + '</td>'
              + '<td>' + fmtDate(tx.created_at) + '</td>'
              + '</tr>';
          }).join('');
        } else {
          tbody.innerHTML = '<tr><td colspan="4" class="empty-row">No transactions yet</td></tr>';
        }
      }
    } catch (e) {
      console.error('loadDashboard:', e);
    }

  }

  // ── Rates ──────────────────────────────────────────────────────────────────────

  // Modal open action for pricing plan CTA buttons
  var _productModalMap = {
    savings:       'modal-create-savings',
    fixed_deposit: 'modal-fixed-deposit',
    loan:          'modal-loan'
  };

  function renderRates(rates, filter) {
    var grid = document.getElementById('ratesGrid');
    if (!grid) return;
    var filtered = rates.filter(function (r) { return r.product === filter; });
    if (!filtered.length) {
      grid.innerHTML = '<p class="empty-text">No rates configured for this product.</p>';
      return;
    }
    // Feature the 12-month plan (or the middle plan if none is 12mo)
    var featuredIdx = filtered.findIndex(function (r) { return parseInt(r.duration_months, 10) === 12; });
    if (featuredIdx === -1) featuredIdx = Math.floor(filtered.length / 2);

    var ctaLabels = { savings: 'Start Saving', fixed_deposit: 'Open Deposit', loan: 'Apply Now' };
    var cta = ctaLabels[filter] || 'Get Started';
    var modalId = _productModalMap[filter] || '';

    grid.innerHTML = filtered.map(function (r, i) {
      var featured = i === featuredIdx;
      var cardClass = 'plan-card' + (featured ? ' plan-card--featured' : '');
      var badge = featured ? '<span class="plan-badge">Popular</span>' : '';
      return '<div class="' + cardClass + '">'
        + badge
        + '<div class="plan-card-name">' + r.label + '</div>'
        + '<div class="plan-card-duration">' + r.duration_months + ' months</div>'
        + '<div class="plan-card-rate">' + parseFloat(r.rate).toFixed(2)
          + '<span><i class="ph ph-percent" aria-label="%"></i>&thinsp;p.a.</span></div>'
        + (modalId
          ? '<button class="plan-card-btn" type="button" onclick="openModal(\'' + modalId + '\')">' + cta + '</button>'
          : '')
        + '</div>';
    }).join('');
  }

  function populateProductSelects(rates) {
    // Savings modal duration select
    var savSel = document.getElementById('savingsDuration');
    if (savSel) {
      var savRates = rates.filter(function (r) { return r.product === 'savings'; });
      if (savRates.length) {
        savSel.innerHTML = savRates.map(function (r) {
          return '<option value="' + r.duration_months + '" data-rate="' + r.rate + '">'
            + r.label + ' — ' + r.duration_months + 'mo (' + parseFloat(r.rate).toFixed(2) + '% p.a.)'
            + '</option>';
        }).join('');
        updateSavingsCalc();
      }
    }

    // Fixed deposit modal plan select
    var fdSel = document.getElementById('fdPlan');
    if (fdSel) {
      var fdRates = rates.filter(function (r) { return r.product === 'fixed_deposit'; });
      if (fdRates.length) {
        fdSel.innerHTML = fdRates.map(function (r) {
          return '<option value="' + r.duration_months + '" data-rate="' + r.rate + '">'
            + r.label + ' — ' + r.duration_months + 'mo (' + parseFloat(r.rate).toFixed(2) + '% p.a.)'
            + '</option>';
        }).join('');
        updateFdCalc();
      }
    }

    // Loan modal plan select
    var loanSel = document.getElementById('loanPlan');
    if (loanSel) {
      var loanRates = rates.filter(function (r) { return r.product === 'loan'; });
      if (loanRates.length) {
        loanSel.innerHTML = loanRates.map(function (r) {
          return '<option value="' + r.duration_months + '" data-rate="' + r.rate + '">'
            + r.label + ' — ' + r.duration_months + 'mo (' + parseFloat(r.rate).toFixed(2) + '% p.a.)'
            + '</option>';
        }).join('');
        updateLoanCalc();
      }
    }
  }

  // kept for backward compat — no longer used as standalone but harmless
  function populateSavingsDurationSelect(rates) { populateProductSelects(rates); }

  // ── Live Calculation Helpers ──────────────────────────────────────────────────

  function getSelectedRate(selectId) {
    var sel = document.getElementById(selectId);
    if (!sel) return { rate: 0, months: 0 };
    var opt = sel.options[sel.selectedIndex];
    if (!opt) return { rate: 0, months: 0 };
    return {
      rate:   parseFloat(opt.getAttribute('data-rate') || 0),
      months: parseInt(opt.value, 10) || 0
    };
  }

  function addMonths(date, n) {
    var d = new Date(date);
    d.setMonth(d.getMonth() + n);
    return d.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
  }

  function updateSavingsCalc() {
    var preview  = document.getElementById('savingsCalcPreview');
    if (!preview) return;
    var amount   = parseFloat((document.getElementById('savingsTargetAmount') || {}).value) || 0;
    var plan     = getSelectedRate('savingsDuration');
    // Store rate in hidden field
    var hiddenEl = document.getElementById('savingsInterestRate');
    if (hiddenEl) hiddenEl.value = plan.rate || '';

    if (!amount || !plan.rate) { preview.style.display = 'none'; return; }
    var interest = amount * (plan.rate / 100) * (plan.months / 12);
    var total    = amount + interest;
    preview.style.display = 'block';
    setText('#savingsCalcPrincipal', '$' + fmt(amount));
    setHTML('#savingsCalcRate',      parseFloat(plan.rate).toFixed(2) + pctIcon + '&thinsp;p.a.');
    setText('#savingsCalcDuration',  plan.months + ' months');
    setText('#savingsCalcInterest',  '+$' + fmt(interest));
    setText('#savingsCalcTotal',     '$' + fmt(total));
  }

  function updateFdCalc() {
    var preview = document.getElementById('fdCalcPreview');
    if (!preview) return;
    var amount  = parseFloat((document.getElementById('fdAmount') || {}).value) || 0;
    var plan    = getSelectedRate('fdPlan');

    if (!amount || !plan.rate) { preview.style.display = 'none'; return; }
    var interest = amount * (plan.rate / 100) * (plan.months / 12);
    var total    = amount + interest;
    preview.style.display = 'block';
    setText('#fdCalcPrincipal', '$' + fmt(amount));
    setText('#fdCalcInterest',  '+$' + fmt(interest));
    setText('#fdCalcDuration',  plan.months + ' months');
    setHTML('#fdCalcRate',      parseFloat(plan.rate).toFixed(2) + pctIcon + '&thinsp;p.a.');
    setText('#fdCalcTotal',     '$' + fmt(total));
    setText('#fdCalcMaturity',  addMonths(new Date(), plan.months));
  }

  function updateLoanCalc() {
    var preview = document.getElementById('loanCalcPreview');
    if (!preview) return;
    var principal = parseFloat((document.getElementById('loanAmountInput') || {}).value) || 0;
    var plan      = getSelectedRate('loanPlan');

    if (!principal || !plan.rate) { preview.style.display = 'none'; return; }
    var monthlyRate = plan.rate / 100 / 12;
    var n           = plan.months;
    var monthly, totalRepayable, totalInterest;
    if (monthlyRate === 0) {
      monthly       = principal / n;
      totalRepayable = principal;
      totalInterest  = 0;
    } else {
      monthly       = principal * (monthlyRate * Math.pow(1 + monthlyRate, n)) / (Math.pow(1 + monthlyRate, n) - 1);
      totalRepayable = monthly * n;
      totalInterest  = totalRepayable - principal;
    }
    preview.style.display = 'block';
    setText('#loanCalcPrincipal', '$' + fmt(principal));
    setText('#loanCalcMonthly',   '$' + fmt(monthly));
    setText('#loanCalcDuration',  n + ' months');
    setHTML('#loanCalcRate',      parseFloat(plan.rate).toFixed(2) + pctIcon + '&thinsp;p.a.');
    setText('#loanCalcInterest',  '$' + fmt(totalInterest));
    setText('#loanCalcTotal',     '$' + fmt(totalRepayable));
  }

  // ── NOWPayments Auto-Verify ───────────────────────────────────────────────────

  var _paymentPollCount  = 0;
  var _paymentPollMax    = 8;   // max 8 attempts (~40 seconds)
  var _paymentPollTimer  = null;

  async function checkPendingPayment(invoiceId, attempt) {
    attempt = attempt || 0;
    if (attempt >= _paymentPollMax) return;

    try {
      var r = await apiFetch('/api/payments/now-payment-status.php', {
        method: 'POST',
        body: JSON.stringify({ invoice_id: invoiceId })
      });

      if (r.success && r.status === 'completed') {
        try { sessionStorage.removeItem('np_invoice_id'); } catch(e) {}
        if (r.credited) {
          showToast('Payment confirmed! Your wallet has been credited.', 'success');
        }
        loadWallet();
        loadDashboard();
        return;
      }

      if (r.success && (r.status === 'failed')) {
        try { sessionStorage.removeItem('np_invoice_id'); } catch(e) {}
        showToast('Payment failed or expired. Please try again.', 'error');
        return;
      }

      // Still pending — retry with exponential back-off (5s, 5s, 10s, 10s …)
      var delay = attempt < 2 ? 5000 : 10000;
      _paymentPollTimer = setTimeout(function () {
        checkPendingPayment(invoiceId, attempt + 1);
      }, delay);

    } catch (e) {
      // Network error — try again once more
      if (attempt < 2) {
        _paymentPollTimer = setTimeout(function () {
          checkPendingPayment(invoiceId, attempt + 1);
        }, 5000);
      }
    }
  }

  // ── Wallet ────────────────────────────────────────────────────────────────────

  async function loadWallet() {
    try {
      var r = await apiFetch('/api/user-dashboard/wallet.php');
      if (!r.success) return;
      var d = r.data;

      _lastBalance = parseFloat(d.balance || 0);
      setText('[data-wallet="balance"]', '$' + fmt(_lastBalance));
      // Restore hidden-balance state from localStorage
      applyBalanceHidden(localStorage.getItem('balanceHidden') === '1');

      var tbody = qs('[data-table="wallet-transactions"]');
      if (tbody) {
        if (d.transactions && d.transactions.length) {
          tbody.innerHTML = d.transactions.map(function (tx) {
            return '<tr>'
              + '<td>' + tx.type + '</td>'
              + '<td>$' + fmt(tx.amount) + '</td>'
              + '<td>' + badge(tx.status) + '</td>'
              + '<td>' + (tx.notes || '--') + '</td>'
              + '<td>' + fmtDate(tx.created_at) + '</td>'
              + '</tr>';
          }).join('');
        } else {
          tbody.innerHTML = '<tr><td colspan="5" class="empty-row">No transactions yet</td></tr>';
        }
      }

      var wdList = qs('[data-list="withdrawals"]');
      if (wdList) {
        if (d.withdrawals && d.withdrawals.length) {
          wdList.innerHTML = d.withdrawals.map(function (w) {
            return '<div class="withdrawal-item">'
              + '<span>$' + fmt(w.amount) + '</span>'
              + badge(w.status)
              + '<span>' + fmtDate(w.created_at) + '</span>'
              + '</div>';
          }).join('');
        } else {
          wdList.innerHTML = '<p class="empty-text">No withdrawal requests</p>';
        }
      }
    } catch (e) {
      console.error('loadWallet:', e);
    }
  }

  function applyBalanceHidden(hidden) {
    var el   = qs('[data-wallet="balance"]');
    var icon = document.getElementById('balanceToggleIcon');
    if (el)   el.textContent = hidden ? '••••••' : ('$' + fmt(_lastBalance));
    if (icon) icon.className = hidden ? 'ph ph-eye-slash' : 'ph ph-eye';
  }

  async function initiateDeposit(form) {
    var btn      = form.querySelector('[type="submit"]');
    var msgEl    = form.querySelector('[data-msg]');
    var amountEl = form.querySelector('[name="amount"]');
    var currEl   = form.querySelector('[name="currency"]');
    var amount   = amountEl ? amountEl.value : '';
    var currency = currEl   ? currEl.value   : 'usdttrc20';

    if (!amount || parseFloat(amount) <= 0) {
      return showMsg(msgEl, 'Please enter a valid deposit amount', true);
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="ph ph-circle-notch ph-spin"></i> Processing…';

    try {
      var r = await apiFetch('/api/payments/now-payment-initiate.php', {
        method: 'POST',
        body: JSON.stringify({ amount: parseFloat(amount), currency: currency })
      });

      if (r.success && r.data && r.data.invoice_url) {
        showMsg(msgEl, 'Redirecting to secure payment page…', false);
        // Persist invoice_id so we can poll for confirmation when the user returns
        try { sessionStorage.setItem('np_invoice_id', r.data.invoice_id); } catch(e) {}
        setTimeout(function () { window.location.href = r.data.invoice_url; }, 800);
      } else {
        showMsg(msgEl, r.message || 'Failed to create payment. Please try again.', true);
        btn.disabled = false;
        btn.innerHTML = '<i class="ph ph-arrow-right"></i> Continue to Payment';
      }
    } catch (e) {
      showMsg(msgEl, 'Network error. Please check your connection and try again.', true);
      btn.disabled = false;
      btn.innerHTML = '<i class="ph ph-arrow-right"></i> Continue to Payment';
    }
  }

  async function submitWithdrawal(form) {
    var btn      = form.querySelector('[type="submit"]');
    var msgEl    = form.querySelector('[data-msg]');
    var amount   = form.querySelector('[name="amount"]')        ? form.querySelector('[name="amount"]').value        : '';
    var currency = form.querySelector('[name="currency"]')      ? form.querySelector('[name="currency"]').value      : 'usdttrc20';
    var address  = form.querySelector('[name="wallet_address"]') ? form.querySelector('[name="wallet_address"]').value : '';

    if (!amount || parseFloat(amount) <= 0) return showMsg(msgEl, 'Enter a valid amount', true);
    if (!address.trim()) return showMsg(msgEl, 'Wallet address is required', true);

    btn.disabled = true;
    btn.textContent = 'Submitting…';

    try {
      var r = await apiFetch('/api/user-dashboard/wallet.php', {
        method: 'POST',
        body: JSON.stringify({ amount: parseFloat(amount), currency: currency, wallet_address: address.trim() })
      });

      if (r.success) {
        showMsg(msgEl, r.message || 'Withdrawal request submitted. Processing in 24–48 hours.', false);
        form.reset();
        loadWallet();
        setTimeout(function () { closeModal('modal-withdraw'); }, 2000);
        showToast('Withdrawal request submitted!', 'success');
      } else {
        showMsg(msgEl, r.message || 'Withdrawal failed. Please try again.', true);
      }
    } catch (e) {
      showMsg(msgEl, 'Network error. Please try again.', true);
    } finally {
      btn.disabled = false;
      btn.innerHTML = '<i class="ph ph-paper-plane-tilt"></i> Request Withdrawal';
    }
  }


  // ── Profile ───────────────────────────────────────────────────────────────────

  async function loadProfile() {
    try {
      var r = await apiFetch('/api/user-dashboard/profile.php');
      if (!r.success) return;
      var d = r.data;

      setVal('[name="full_name"]', d.full_name);
      setText('[data-profile="email"]',        d.email);
      setText('[data-profile="member-since"]', fmtDate(d.created_at));
      setText('[data-profile="verified"]',     d.is_verified ? 'Verified' : 'Unverified');

      var emailEl = qs('[name="email"]');
      if (emailEl) { emailEl.value = d.email || ''; emailEl.readOnly = true; }

      // Keep all name/initial displays in sync
      var displayName = d.full_name || d.email || '';
      document.querySelectorAll('[data-user="name"]').forEach(function (el) {
        el.textContent = displayName;
      });
      var initial = displayName.trim().charAt(0).toUpperCase() || 'U';
      document.querySelectorAll('[data-user="initial"]').forEach(function (el) {
        el.textContent = initial;
      });
    } catch (e) {
      console.error('loadProfile:', e);
    }
  }

  async function updateProfile(form) {
    var btn      = form.querySelector('[type="submit"]');
    var msgEl    = form.querySelector('[data-msg]');
    var fullName = form.querySelector('[name="full_name"]')        ? form.querySelector('[name="full_name"]').value        : '';
    var curPass  = form.querySelector('[name="current_password"]') ? form.querySelector('[name="current_password"]').value : '';
    var newPass  = form.querySelector('[name="new_password"]')     ? form.querySelector('[name="new_password"]').value     : '';

    btn.disabled = true;
    btn.innerHTML = '<i class="ph ph-circle-notch ph-spin"></i> Saving…';

    try {
      var r = await apiFetch('/api/user-dashboard/profile.php', {
        method: 'POST',
        body: JSON.stringify({ full_name: fullName, password: curPass, new_password: newPass })
      });

      if (r.success) {
        showMsg(msgEl, r.message || 'Profile updated successfully!', false);
        var passFields = form.querySelectorAll('[name="current_password"], [name="new_password"]');
        passFields.forEach(function (el) { el.value = ''; });
        // Refresh to update displayed name
        loadProfile();
        showToast('Profile saved!', 'success');
      } else {
        showMsg(msgEl, r.message || 'Update failed. Please try again.', true);
      }
    } catch (e) {
      showMsg(msgEl, 'Network error. Please try again.', true);
    } finally {
      btn.disabled = false;
      btn.innerHTML = '<i class="ph ph-floppy-disk"></i> Save Changes';
    }
  }

  // ── Transfer ──────────────────────────────────────────────────────────────────

  async function submitTransfer(form) {
    var btn     = document.getElementById('transferBtn');
    var msgEl   = form.querySelector('[data-msg]');
    var email   = form.querySelector('[name="recipient_email"]');
    var amtEl   = form.querySelector('[name="amount"]');

    if (!email || !email.value.trim()) return showMsg(msgEl, 'Recipient email is required', true);
    if (!amtEl || parseFloat(amtEl.value) < 1) return showMsg(msgEl, 'Minimum transfer is $1.00', true);

    if (btn) {
      btn.disabled = true;
      btn.querySelector('.btn-text').style.display = 'none';
      btn.querySelector('.btn-spinner').style.display = 'inline-flex';
    }

    try {
      var r = await apiFetch('/api/user-dashboard/wallet.php', {
        method: 'POST',
        body: JSON.stringify({
          action:          'transfer',
          recipient_email: email.value.trim().toLowerCase(),
          amount:          parseFloat(amtEl.value)
        })
      });

      if (r.success) {
        closeModal('modal-transfer');
        showToast('Transfer successful!', 'success');
        form.reset();
        loadWallet();
        // Also refresh overview balance
        setText('[data-stat="balance"]', '$' + fmt(r.new_balance || 0));
        setText('[data-wallet="balance"]', '$' + fmt(r.new_balance || 0));
      } else {
        showMsg(msgEl, r.message || 'Transfer failed. Please try again.', true);
      }
    } catch (e) {
      showMsg(msgEl, 'Network error. Please try again.', true);
    } finally {
      if (btn) {
        btn.disabled = false;
        btn.querySelector('.btn-text').style.display = '';
        btn.querySelector('.btn-spinner').style.display = 'none';
      }
    }
  }

  // ── Create Savings Plan ───────────────────────────────────────────────────────

  async function submitCreateSavings(form) {
    var btn       = document.getElementById('createSavingsBtn');
    var msgEl     = form.querySelector('[data-msg]');
    var planName  = form.querySelector('[name="plan_name"]');
    var duration  = form.querySelector('[name="duration_months"]');
    var target    = form.querySelector('[name="target_amount"]');
    var rate      = form.querySelector('[name="interest_rate"]');

    if (!planName || !planName.value.trim()) return showMsg(msgEl, 'Plan name is required', true);
    if (!duration || !duration.value)        return showMsg(msgEl, 'Please select a duration', true);
    if (!target   || parseFloat(target.value) < 10) return showMsg(msgEl, 'Minimum target amount is $10.00', true);

    if (btn) {
      btn.disabled = true;
      btn.querySelector('.btn-text').style.display = 'none';
      btn.querySelector('.btn-spinner').style.display = 'inline-flex';
    }

    try {
      var r = await apiFetch('/api/user-dashboard/savings.php', {
        method: 'POST',
        body: JSON.stringify({
          action:           'create',
          plan_name:        planName.value.trim(),
          duration_months:  parseInt(duration.value, 10),
          target_amount:    parseFloat(target.value),
          interest_rate:    rate ? parseFloat(rate.value) : 0
        })
      });

      if (r.success) {
        closeModal('modal-create-savings');
        showToast('Savings plan created!', 'success');
        form.reset();
        loadSavings();
      } else {
        showMsg(msgEl, r.message || 'Failed to create plan. Please try again.', true);
      }
    } catch (e) {
      showMsg(msgEl, 'Network error. Please try again.', true);
    } finally {
      if (btn) {
        btn.disabled = false;
        btn.querySelector('.btn-text').style.display = '';
        btn.querySelector('.btn-spinner').style.display = 'none';
      }
    }
  }

  // ── Submit Fixed Deposit (modal) ──────────────────────────────────────────────

  async function submitFixedDeposit(form) {
    var btn    = document.getElementById('fixedDepositBtn');
    var msgEl  = form.querySelector('[data-msg]');
    var amount = form.querySelector('[name="amount"]');
    var dur    = form.querySelector('[name="duration_months"]');

    if (!amount || parseFloat(amount.value) < 100) return showMsg(msgEl, 'Minimum deposit is $100', true);
    if (!dur || !dur.value) return showMsg(msgEl, 'Please select a plan', true);

    if (btn) {
      btn.disabled = true;
      btn.querySelector('.btn-text').style.display = 'none';
      btn.querySelector('.btn-spinner').style.display = 'inline-flex';
    }

    try {
      var r = await apiFetch('/api/user-dashboard/deposits.php', {
        method: 'POST',
        body: JSON.stringify({
          action:          'create',
          amount:          parseFloat(amount.value),
          duration_months: parseInt(dur.value, 10)
        })
      });

      if (r.success) {
        closeModal('modal-fixed-deposit');
        showToast('Fixed deposit opened!', 'success');
        form.reset();
        document.getElementById('fdCalcPreview').style.display = 'none';
        loadDeposits();
        loadWallet();
      } else {
        showMsg(msgEl, r.message || 'Failed to open deposit. Please try again.', true);
      }
    } catch (e) {
      showMsg(msgEl, 'Network error. Please try again.', true);
    } finally {
      if (btn) {
        btn.disabled = false;
        btn.querySelector('.btn-text').style.display = '';
        btn.querySelector('.btn-spinner').style.display = 'none';
      }
    }
  }

  // ── Submit Loan Application (modal) ───────────────────────────────────────────

  async function submitLoanApplication(form) {
    var btn     = document.getElementById('loanApplicationBtn');
    var msgEl   = form.querySelector('[data-msg]');
    var amount  = form.querySelector('[name="loan_amount"]');
    var dur     = form.querySelector('[name="duration_months"]');
    var purpose = form.querySelector('[name="purpose"]');

    if (!amount || parseFloat(amount.value) < 100) return showMsg(msgEl, 'Minimum loan amount is $100', true);
    if (!dur || !dur.value) return showMsg(msgEl, 'Please select a duration', true);

    if (btn) {
      btn.disabled = true;
      btn.querySelector('.btn-text').style.display = 'none';
      btn.querySelector('.btn-spinner').style.display = 'inline-flex';
    }

    try {
      var r = await apiFetch('/api/user-dashboard/loans.php', {
        method: 'POST',
        body: JSON.stringify({
          action:          'apply',
          loan_amount:     parseFloat(amount.value),
          duration_months: parseInt(dur.value, 10),
          purpose:         purpose ? purpose.value.trim() : ''
        })
      });

      if (r.success) {
        closeModal('modal-loan');
        showToast('Loan application submitted!', 'success');
        form.reset();
        document.getElementById('loanCalcPreview').style.display = 'none';
        loadLoans();
      } else {
        showMsg(msgEl, r.message || 'Application failed. Please try again.', true);
      }
    } catch (e) {
      showMsg(msgEl, 'Network error. Please try again.', true);
    } finally {
      if (btn) {
        btn.disabled = false;
        btn.querySelector('.btn-text').style.display = '';
        btn.querySelector('.btn-spinner').style.display = 'none';
      }
    }
  }

  // Legacy stubs for old inline forms (no longer used but kept for safety)
  function submitDeposit(form) { submitFixedDeposit(form); }
  function submitLoan(form)    { submitLoanApplication(form); }

  // ── Add Funds to Savings Plan ─────────────────────────────────────────────────

  async function addFunds(planId) {
    var amount = parseFloat(window.prompt('Enter amount to add to this savings plan (USD):'));
    if (isNaN(amount) || amount <= 0) return;

    try {
      var r = await apiFetch('/api/user-dashboard/savings.php', {
        method: 'POST',
        body: JSON.stringify({ action: 'add_funds', plan_id: planId, amount: amount })
      });

      if (r.success) {
        showToast('Funds added to savings plan!', 'success');
        loadSavings();
        loadWallet();
      } else {
        showToast(r.message || 'Failed to add funds.', 'error');
      }
    } catch (e) {
      showToast('Network error. Please try again.', 'error');
    }
  }

  // ── Repay Loan ────────────────────────────────────────────────────────────────

  async function repayLoan(loanId) {
    var amount = parseFloat(window.prompt('Enter repayment amount (USD):'));
    if (isNaN(amount) || amount <= 0) return;

    try {
      var r = await apiFetch('/api/user-dashboard/loans.php', {
        method: 'POST',
        body: JSON.stringify({ action: 'repay', loan_id: loanId, amount: amount })
      });

      if (r.success) {
        showToast('Loan repayment submitted!', 'success');
        loadLoans();
        loadWallet();
      } else {
        showToast(r.message || 'Repayment failed.', 'error');
      }
    } catch (e) {
      showToast('Network error. Please try again.', 'error');
    }
  }

  // Expose for inline onclick handlers in table rows
  window.addFunds  = addFunds;
  window.repayLoan = repayLoan;

  // ── Delete Account ────────────────────────────────────────────────────────────

  async function deleteAccount() {
    var btn   = document.getElementById('confirmDeleteBtn');
    var msgEl = document.getElementById('deleteMsg');

    if (btn) { btn.disabled = true; btn.textContent = 'Deleting…'; }

    try {
      var r = await apiFetch('/api/user-dashboard/profile.php', {
        method: 'POST',
        body: JSON.stringify({ action: 'delete' })
      });

      if (r.success) {
        showToast('Account deleted. Redirecting…', 'info');
        setTimeout(function () {
          window.location.href = '/login';
        }, 1500);
      } else {
        if (msgEl) showMsg(msgEl, r.message || 'Deletion failed. Please contact support.', true);
      }
    } catch (e) {
      if (msgEl) showMsg(msgEl, 'Network error. Please try again.', true);
      if (btn) { btn.disabled = false; btn.innerHTML = '<i class="ph ph-trash"></i> Yes, Delete My Account Permanently'; }
    }
  }


  // ── Savings Plans ─────────────────────────────────────────────────────────────

  async function loadSavings() {
    try {
      var d = await apiFetch('/api/user-dashboard/savings.php');
      if (!d || d.error) return;

      setText('[data-stat="total-saved"]',   '$' + fmt(d.total_saved || 0));
      setText('[data-stat="savings-count"]', d.active_count || 0);

      var tbody = qs('[data-table="savings-plans"]');
      if (tbody) {
        if (d.plans && d.plans.length) {
          tbody.innerHTML = d.plans.map(function (p) {
            var pct = p.target_amount > 0
              ? Math.min(100, Math.round((p.current_amount / p.target_amount) * 100))
              : 0;
            return '<tr>'
              + '<td>' + p.plan_name + '</td>'
              + '<td>$' + fmt(p.target_amount) + '</td>'
              + '<td>$' + fmt(p.current_amount) + '</td>'
              + '<td>' + (p.interest_rate || '—') + '<i class="ph ph-percent"></i>&thinsp;p.a.</td>'
              + '<td><div class="progress-bar"><div class="progress-fill" style="width:' + pct + '%"></div></div> ' + pct + '<i class="ph ph-percent"></i></td>'
              + '<td>' + (p.duration_months || '—') + ' mo</td>'
              + '<td>' + badge(p.status) + '</td>'
              + '<td><button class="btn-xs btn-outline" onclick="addFunds(' + p.id + ')">Add Funds</button></td>'
              + '</tr>';
          }).join('');
        } else {
          tbody.innerHTML = '<tr><td colspan="8" class="empty-row">No savings plans yet. Create your first plan above.</td></tr>';
        }
      }

      // Also update savings overview table on Dashboard
      var overviewTbody = qs('[data-table="savings-overview"]');
      if (overviewTbody) {
        if (d.plans && d.plans.length) {
          overviewTbody.innerHTML = d.plans.slice(0, 3).map(function (p) {
            var pct = p.target_amount > 0
              ? Math.min(100, Math.round((p.current_amount / p.target_amount) * 100))
              : 0;
            return '<tr>'
              + '<td>' + p.plan_name + '</td>'
              + '<td>$' + fmt(p.target_amount) + '</td>'
              + '<td>$' + fmt(p.current_amount) + '</td>'
              + '<td>' + (p.interest_rate || '—') + '<i class="ph ph-percent"></i>&thinsp;p.a.</td>'
              + '<td><div class="progress-bar"><div class="progress-fill" style="width:' + pct + '%"></div></div> ' + pct + '<i class="ph ph-percent"></i></td>'
              + '<td>' + badge(p.status) + '</td>'
              + '</tr>';
          }).join('');
        } else {
          overviewTbody.innerHTML = '<tr><td colspan="6" class="empty-row">No active savings plans.</td></tr>';
        }
      }
    } catch (e) {
      console.error('loadSavings:', e);
    }
  }

  // ── Fixed Deposits ────────────────────────────────────────────────────────────

  async function loadDeposits() {
    try {
      var d = await apiFetch('/api/user-dashboard/deposits.php');
      if (!d || d.error) return;

      setText('[data-stat="total-deposited"]',       '$' + fmt(d.total_deposited || 0));
      setText('[data-stat="total-expected-return"]', '$' + fmt(d.total_expected_return || 0));

      var tbody = qs('[data-table="fixed-deposits"]');
      if (tbody) {
        if (d.deposits && d.deposits.length) {
          tbody.innerHTML = d.deposits.map(function (dep) {
            return '<tr>'
              + '<td>$' + fmt(dep.amount) + '</td>'
              + '<td>' + (dep.interest_rate || '—') + '<i class="ph ph-percent"></i>&thinsp;p.a.</td>'
              + '<td>' + (dep.duration_months || '—') + ' mo</td>'
              + '<td>' + fmtDate(dep.start_date) + '</td>'
              + '<td>' + fmtDate(dep.maturity_date) + '</td>'
              + '<td>$' + fmt(dep.expected_return) + '</td>'
              + '<td>' + badge(dep.status) + '</td>'
              + '</tr>';
          }).join('');
        } else {
          tbody.innerHTML = '<tr><td colspan="7" class="empty-row">No fixed deposits yet. Open your first deposit above.</td></tr>';
        }
      }
    } catch (e) {
      console.error('loadDeposits:', e);
    }
  }

  // ── Loans ─────────────────────────────────────────────────────────────────────

  async function loadLoans() {
    try {
      var d = await apiFetch('/api/user-dashboard/loans.php');
      if (!d || d.error) return;

      setText('[data-stat="total-borrowed"]',    '$' + fmt(d.total_borrowed || 0));
      setText('[data-stat="remaining-balance"]', '$' + fmt(d.remaining_balance || 0));

      var activeTbody = qs('[data-table="active-loans"]');
      if (activeTbody) {
        if (d.active_loans && d.active_loans.length) {
          activeTbody.innerHTML = d.active_loans.map(function (l) {
            return '<tr>'
              + '<td>$' + fmt(l.loan_amount) + '</td>'
              + '<td>$' + fmt(l.remaining_balance) + '</td>'
              + '<td>$' + fmt(l.monthly_payment) + '</td>'
              + '<td>' + (l.interest_rate || '—') + '<i class="ph ph-percent"></i>&thinsp;p.a.</td>'
              + '<td>' + (l.duration_months || '—') + ' mo</td>'
              + '<td>' + badge(l.status) + '</td>'
              + '<td><button class="btn-xs btn-primary" onclick="repayLoan(' + l.id + ')">Repay</button></td>'
              + '</tr>';
          }).join('');
        } else {
          activeTbody.innerHTML = '<tr><td colspan="7" class="empty-row">No active loans.</td></tr>';
        }
      }

      var pendingTbody = qs('[data-table="pending-loans"]');
      if (pendingTbody) {
        if (d.pending_loans && d.pending_loans.length) {
          pendingTbody.innerHTML = d.pending_loans.map(function (l) {
            return '<tr>'
              + '<td>$' + fmt(l.loan_amount) + '</td>'
              + '<td>' + (l.duration_months || '—') + ' mo</td>'
              + '<td>' + fmtDate(l.created_at) + '</td>'
              + '<td>' + badge(l.status) + '</td>'
              + '</tr>';
          }).join('');
        } else {
          pendingTbody.innerHTML = '<tr><td colspan="4" class="empty-row">No pending applications.</td></tr>';
        }
      }

      // Upcoming payments on overview
      var upcomingEl = qs('[data-list="upcoming-payments"]');
      if (upcomingEl) {
        if (d.active_loans && d.active_loans.length) {
          upcomingEl.innerHTML = d.active_loans.map(function (l) {
            return '<div class="upcoming-payment">'
              + '<span>Monthly payment: <strong>$' + fmt(l.monthly_payment) + '</strong></span>'
              + '<span class="badge badge-warning">Due monthly</span>'
              + '</div>';
          }).join('');
        } else {
          upcomingEl.innerHTML = '<p class="empty-text">No upcoming loan payments.</p>';
        }
      }
    } catch (e) {
      console.error('loadLoans:', e);
    }
  }

  // ── Section Navigation ────────────────────────────────────────────────────────

  // Page titles for each section
  var sectionTitles = {
    overview: 'Dashboard',
    wallet:   'Wallet',
    savings:  'Savings Plans',
    deposits: 'Fixed Deposits',
    loans:    'Loans',
    profile:  'Profile'
  };

  // Section loaders — key matches data-nav and data-section values
  var sectionLoaders = {
    overview: loadDashboard,
    wallet:   loadWallet,
    savings:  loadSavings,
    deposits: loadDeposits,
    loans:    loadLoans,
    profile:  loadProfile
  };

  // Derive section name from current URL path
  function sectionFromPath() {
    var seg = location.pathname.split('/').filter(Boolean)[0] || '';
    if (!seg || seg === 'dashboard') return 'overview';
    return sectionLoaders[seg] ? seg : 'overview';
  }

  function activateSection(name, pushState) {
    // Show/hide sections
    document.querySelectorAll('[data-section]').forEach(function (el) {
      el.style.display = el.dataset.section === name ? 'block' : 'none';
    });

    // Update active state on nav items (sidebar + mobile dock)
    document.querySelectorAll('[data-nav]').forEach(function (el) {
      el.classList.toggle('active', el.dataset.nav === name);
    });

    // Update page title in header
    var titleEl = document.getElementById('pageTitle');
    if (titleEl) titleEl.textContent = sectionTitles[name] || name;

    // Update browser document title
    document.title = (sectionTitles[name] || name) + ' — CrestVale Bank';

    // Push clean path URL  (/wallet, /savings, … — overview stays /dashboard)
    if (pushState !== false && history.pushState) {
      var url = (name === 'overview') ? '/dashboard' : '/' + name;
      history.pushState({ section: name }, '', url);
    }

    // Load data for this section
    if (sectionLoaders[name]) sectionLoaders[name]();

    // Restart both background timers for the new section
    startBackgroundRefresh(name);
  }

  // ── Init ──────────────────────────────────────────────────────────────────────

  document.addEventListener('DOMContentLoaded', function () {

    // ── Browser back / forward ─────────────────────────────────────────────
    window.addEventListener('popstate', function (e) {
      var section = (e.state && e.state.section) ? e.state.section : sectionFromPath();
      activateSection(section, false); // false = don't push again
    });

    // ── Nav: sidebar + mobile dock link clicks ──────────────────────────────
    document.querySelectorAll('[data-nav]').forEach(function (el) {
      el.addEventListener('click', function (e) {
        e.preventDefault();
        activateSection(this.dataset.nav);
      });
    });

    // ── Global form submit delegation ──────────────────────────────────────
    document.addEventListener('submit', function (e) {
      var form   = e.target;
      var action = form.dataset.action;
      if (!action) return;
      e.preventDefault();

      switch (action) {
        case 'deposit':           initiateDeposit(form);      break;
        case 'withdraw':          submitWithdrawal(form);     break;
        case 'transfer':          submitTransfer(form);       break;
        case 'create-savings':    submitCreateSavings(form);  break;
        case 'fixed-deposit':     submitFixedDeposit(form);   break;
        case 'loan-application':  submitLoanApplication(form); break;
        // legacy
        case 'submit-deposit':    submitFixedDeposit(form);   break;
        case 'submit-loan':       submitLoanApplication(form); break;
        case 'update-profile':    updateProfile(form);        break;
      }
    });

    // ── Rates tabs ────────────────────────────────────────────────────────
    document.addEventListener('click', function (e) {
      var tab = e.target.closest('.rates-tab');
      if (!tab) return;
      _ratesFilter = tab.dataset.ratesFilter;
      document.querySelectorAll('.rates-tab').forEach(function (t) {
        t.classList.toggle('active', t.dataset.ratesFilter === _ratesFilter);
      });
      renderRates(_rates, _ratesFilter);
    });

    // ── Live calc: input and change events ────────────────────────────────
    document.addEventListener('input', function (e) {
      var id = e.target && e.target.id;
      if (id === 'savingsTargetAmount') updateSavingsCalc();
      if (id === 'fdAmount')            updateFdCalc();
      if (id === 'loanAmountInput')     updateLoanCalc();
    });
    document.addEventListener('change', function (e) {
      var id = e.target && e.target.id;
      if (id === 'savingsDuration') updateSavingsCalc();
      if (id === 'fdPlan')          updateFdCalc();
      if (id === 'loanPlan')        updateLoanCalc();
    });

    // ── Balance visibility toggle ──────────────────────────────────────────
    var balanceToggle = document.getElementById('balanceToggle');
    if (balanceToggle) {
      balanceToggle.addEventListener('click', function () {
        var hidden = localStorage.getItem('balanceHidden') === '1';
        localStorage.setItem('balanceHidden', hidden ? '0' : '1');
        applyBalanceHidden(!hidden);
      });
    }

    // ── Delete account button ──────────────────────────────────────────────
    var deleteBtn = document.getElementById('confirmDeleteBtn');
    if (deleteBtn) {
      deleteBtn.addEventListener('click', deleteAccount);
    }

    // ── Global delegated click: [data-copy-text] buttons ──────────────────
    document.addEventListener('click', function (e) {
      var btn = e.target.closest('[data-copy-text]');
      if (!btn || !btn.dataset.copyText) return;
      copyText(btn.dataset.copyText, function (ok) {
        if (!ok) return;
        var orig = btn.textContent;
        btn.textContent = 'Copied!';
        setTimeout(function () { btn.textContent = orig; }, 2000);
      });
    });

    // ── Modal: close on overlay background click ───────────────────────────
    document.addEventListener('click', function (e) {
      if (e.target.classList.contains('modal-overlay')) {
        closeAllModals();
      }
    });

    // ── Modal: close on ESC key ────────────────────────────────────────────
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeAllModals();
    });

    // ── Load section from URL path, default to overview ───────────────────
    activateSection(sectionFromPath(), false); // false = path already correct, don't re-push

    // ── NOWPayments: poll for pending payment on return from invoice page ──
    try {
      var pendingInvoice = sessionStorage.getItem('np_invoice_id');
      if (pendingInvoice) {
        // Small delay so wallet section is loaded first
        setTimeout(function () { checkPendingPayment(pendingInvoice, 0); }, 2000);
      }
    } catch(e) {}

  });

})();

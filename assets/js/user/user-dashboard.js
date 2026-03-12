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
  function setVal(sel, val)  { var el = qs(sel); if (el) el.value = val || ''; }

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

  // openTradeModal(plan) — pre-selects a plan before opening the trade modal
  window.openTradeModal = function (plan) {
    var sel = qs('#modal-trade [name="plan"]');
    if (sel) {
      sel.value = plan;
      // Trigger change to update range hint
      sel.dispatchEvent(new Event('change'));
    }
    openModal('modal-trade');
  };

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

  var _lastBalance = 0; // cache for balance-hide toggle

  // ── Polling ──────────────────────────────────────────────────────────────────

  var _pollTimer = null;

  function startPolling(sectionName) {
    clearInterval(_pollTimer);
    _pollTimer = setInterval(function () {
      if (sectionLoaders[sectionName]) sectionLoaders[sectionName]();
    }, 30000);
  }

  // ── Dashboard Overview ───────────────────────────────────────────────────────

  async function loadDashboard() {
    try {
      var r = await apiFetch('/api/user-dashboard/dashboard.php');
      if (!r.success) return;
      var d = r.data;

      setText('[data-stat="balance"]',            '$' + fmt(d.balance));
      setText('[data-stat="total-earned"]',       '$' + fmt(d.total_earned));
      setText('[data-stat="active-investments"]', d.active_investments);
      setText('[data-stat="total-invested"]',     '$' + fmt(d.total_invested));

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

      var tbody = qs('[data-table="recent-transactions"]');
      if (tbody) {
        if (d.recent_transactions && d.recent_transactions.length) {
          tbody.innerHTML = d.recent_transactions.map(function (tx) {
            return '<tr>'
              + '<td>' + tx.type + '</td>'
              + '<td>$' + fmt(tx.amount) + '</td>'
              + '<td>' + (tx.currency || 'USD').toUpperCase() + '</td>'
              + '<td>' + badge(tx.status) + '</td>'
              + '<td>' + fmtDate(tx.created_at) + '</td>'
              + '</tr>';
          }).join('');
        } else {
          tbody.innerHTML = '<tr><td colspan="5" class="empty-row">No transactions yet</td></tr>';
        }
      }
    } catch (e) {
      console.error('loadDashboard:', e);
    }

    // Load crypto prices alongside dashboard data
    loadStocks();
  }

  // ── Crypto Price Ticker ───────────────────────────────────────────────────────

  async function loadStocks() {
    var grid    = document.getElementById('stocksGrid');
    var updated = document.getElementById('stocksUpdated');
    if (!grid) return;

    var coins = [
      { capId: 'bitcoin',     symbol: 'BTC' },
      { capId: 'ethereum',    symbol: 'ETH' },
      { capId: 'tether',      symbol: 'USDT' },
      { capId: 'binance-coin', symbol: 'BNB' }
    ];

    try {
      var capIds = coins.map(function (c) { return c.capId; }).join(',');
      var r = await fetch(
        'https://api.coincap.io/v2/assets?ids=' + capIds,
        { headers: { 'Accept': 'application/json' } }
      );
      var json = await r.json();

      var assetMap = {};
      (json.data || []).forEach(function (a) { assetMap[a.id] = a; });

      grid.innerHTML = coins.map(function (coin) {
        var asset  = assetMap[coin.capId] || {};
        var price  = asset.priceUsd
          ? '$' + parseFloat(asset.priceUsd).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
          : '—';
        var chg    = asset.changePercent24Hr != null ? parseFloat(asset.changePercent24Hr) : null;
        var chgCls = chg > 0 ? 'stock-change--up' : chg < 0 ? 'stock-change--down' : '';
        var chgTxt = chg != null
          ? (chg > 0 ? '+' : '') + chg.toFixed(2) + '% (24h)'
          : '—';

        return '<div class="stock-card">'
          + '<div class="stock-symbol">' + coin.symbol + '</div>'
          + '<div class="stock-price">' + price + '</div>'
          + '<div class="stock-change ' + chgCls + '">' + chgTxt + '</div>'
          + '</div>';
      }).join('');

      if (updated) updated.textContent = 'Updated ' + new Date().toLocaleTimeString();
    } catch (e) {
      grid.innerHTML = '<p class="empty-text">Price data unavailable</p>';
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
              + '<td>' + (tx.currency || 'USD').toUpperCase() + '</td>'
              + '<td>' + badge(tx.status) + '</td>'
              + '<td>' + (tx.notes || '--') + '</td>'
              + '<td>' + fmtDate(tx.created_at) + '</td>'
              + '</tr>';
          }).join('');
        } else {
          tbody.innerHTML = '<tr><td colspan="6" class="empty-row">No transactions yet</td></tr>';
        }
      }

      var wdList = qs('[data-list="withdrawals"]');
      if (wdList) {
        if (d.withdrawals && d.withdrawals.length) {
          wdList.innerHTML = d.withdrawals.map(function (w) {
            return '<div class="withdrawal-item">'
              + '<span>$' + fmt(w.amount) + ' ' + (w.currency || 'USD').toUpperCase() + '</span>'
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

  // ── Investments / Trades ──────────────────────────────────────────────────────

  async function loadTrades() {
    try {
      var r = await apiFetch('/api/user-dashboard/trade.php');
      if (!r.success) return;
      var d = r.data;

      setText('[data-stat="active-count"]', d.active_count);

      // Compute total profits across all investment contracts
      var totalProfit = (d.investments || []).reduce(function (sum, inv) {
        return sum + parseFloat(inv.total_earned || 0);
      }, 0);
      setText('[data-stat="total-profit"]', '$' + fmt(totalProfit));

      var tbody = qs('[data-table="investments"]');
      if (tbody) {
        if (d.investments && d.investments.length) {
          tbody.innerHTML = d.investments.map(function (inv) {
            var planLabel = inv.plan_name.charAt(0).toUpperCase() + inv.plan_name.slice(1);
            var rate = (parseFloat(inv.daily_rate) * 100).toFixed(0) + '%/day';
            return '<tr>'
              + '<td>' + planLabel + '</td>'
              + '<td>$' + fmt(inv.amount) + '</td>'
              + '<td>' + rate + '</td>'
              + '<td>$' + fmt(inv.total_earned) + '</td>'
              + '<td>' + badge(inv.status) + '</td>'
              + '<td>' + fmtDate(inv.ends_at) + '</td>'
              + '</tr>';
          }).join('');
        } else {
          tbody.innerHTML = '<tr><td colspan="6" class="empty-row">No investments yet. Pick a plan above to get started.</td></tr>';
        }
      }
    } catch (e) {
      console.error('loadTrades:', e);
    }
  }

  async function startInvestment(form) {
    var btn    = form.querySelector('[type="submit"]');
    var msgEl  = form.querySelector('[data-msg]');
    var plan   = form.querySelector('[name="plan"]')   ? form.querySelector('[name="plan"]').value   : '';
    var amount = form.querySelector('[name="amount"]') ? form.querySelector('[name="amount"]').value : '';

    if (!plan)                              return showMsg(msgEl, 'Select an investment plan', true);
    if (!amount || parseFloat(amount) <= 0) return showMsg(msgEl, 'Enter a valid amount', true);

    btn.disabled = true;
    btn.textContent = 'Processing…';

    try {
      var r = await apiFetch('/api/user-dashboard/trade.php', {
        method: 'POST',
        body: JSON.stringify({ plan: plan, amount: parseFloat(amount) })
      });

      if (r.success) {
        showMsg(msgEl, r.message || 'Investment started successfully!', false);
        form.reset();
        loadTrades();
        loadDashboard();
        setTimeout(function () { closeModal('modal-trade'); }, 2000);
        showToast('Investment started! Daily returns begin tomorrow.', 'success');
      } else {
        showMsg(msgEl, r.message || 'Investment failed. Please try again.', true);
      }
    } catch (e) {
      showMsg(msgEl, 'Network error. Please try again.', true);
    } finally {
      btn.disabled = false;
      btn.innerHTML = '<i class="ph ph-lightning"></i> Invest Now';
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

  // ── Referrals ─────────────────────────────────────────────────────────────────

  async function loadReferral() {
    try {
      var r = await apiFetch('/api/user-dashboard/referral.php');
      if (!r.success) return;
      var d = r.data;

      setText('[data-referral="code"]',        d.referral_code);
      setText('[data-referral="uses"]',         d.uses);
      setText('[data-referral="commission"]',  '$' + fmt(d.total_commission));
      setVal('[data-referral="link"]',          d.referral_link);

      // Copy referral CODE button
      var codeBtn = qs('[data-copy="referral-code"]');
      if (codeBtn) {
        codeBtn.dataset.copyText = d.referral_code;
      }

      // Copy referral LINK button
      var linkBtn = qs('[data-copy="referral-link"]');
      if (linkBtn) {
        linkBtn.onclick = function () {
          copyText(d.referral_link, function (ok) {
            if (!ok) return;
            linkBtn.textContent = 'Copied!';
            setTimeout(function () { linkBtn.textContent = 'Copy Link'; }, 2000);
          });
        };
      }

      var tbody = qs('[data-table="referred-users"]');
      if (tbody) {
        if (d.referred_users && d.referred_users.length) {
          tbody.innerHTML = d.referred_users.map(function (u) {
            return '<tr>'
              + '<td>' + (u.full_name || 'N/A') + '</td>'
              + '<td>' + u.email + '</td>'
              + '<td>$' + fmt(u.commission_earned) + '</td>'
              + '<td>' + fmtDate(u.joined_at) + '</td>'
              + '</tr>';
          }).join('');
        } else {
          tbody.innerHTML = '<tr><td colspan="4" class="empty-row">No referrals yet. Share your link to start earning!</td></tr>';
        }
      }
    } catch (e) {
      console.error('loadReferral:', e);
    }
  }

  // ── Section Navigation ────────────────────────────────────────────────────────

  // Page titles for each section
  var sectionTitles = {
    overview: 'Overview',
    wallet:   'Wallet',
    trade:    'Invest',
    referral: 'Referrals',
    profile:  'Profile'
  };

  // Section loaders — key matches data-nav and data-section values
  var sectionLoaders = {
    overview: loadDashboard,
    wallet:   loadWallet,
    trade:    loadTrades,
    profile:  loadProfile,
    referral: loadReferral
  };

  function activateSection(name) {
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
    document.title = (sectionTitles[name] || name) + ' — ArqoraCapital';

    // Load data for this section
    if (sectionLoaders[name]) sectionLoaders[name]();

    // Start polling for this section
    startPolling(name);
  }

  // ── Init ──────────────────────────────────────────────────────────────────────

  document.addEventListener('DOMContentLoaded', function () {

    // ── Nav: sidebar + mobile dock link clicks ──────────────────────────────
    document.querySelectorAll('[data-nav]').forEach(function (el) {
      el.addEventListener('click', function (e) {
        e.preventDefault();
        var section = this.dataset.nav;
        activateSection(section);
        if (history.pushState) history.pushState(null, '', '#' + section);
      });
    });

    // ── Global form submit delegation ──────────────────────────────────────
    document.addEventListener('submit', function (e) {
      var form   = e.target;
      var action = form.dataset.action;
      if (!action) return;
      e.preventDefault();

      switch (action) {
        case 'deposit':        initiateDeposit(form);  break;
        case 'withdraw':       submitWithdrawal(form); break;
        case 'invest':         startInvestment(form);  break;
        case 'update-profile': updateProfile(form);    break;
      }
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

    // ── Trade modal: update range hint when plan changes ──────────────────
    var planSelect = qs('#modal-trade [name="plan"]');
    var rangeHint  = document.getElementById('investRangeHint');
    if (planSelect && rangeHint) {
      planSelect.addEventListener('change', function () {
        var opt = planSelect.options[planSelect.selectedIndex];
        var min = opt.dataset.min;
        var max = opt.dataset.max;
        rangeHint.textContent = max
          ? '$' + parseInt(min).toLocaleString() + ' – $' + parseInt(max).toLocaleString()
          : '$' + parseInt(min).toLocaleString() + '+';
      });
    }

    // ── Load section from URL hash, default to overview ───────────────────
    var hash = location.hash.replace('#', '');
    activateSection(sectionLoaders[hash] ? hash : 'overview');

  });

})();

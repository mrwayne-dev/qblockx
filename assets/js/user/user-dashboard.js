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

  // ── Dashboard Overview ───────────────────────────────────────────────────────

  async function loadDashboard() {
    try {
      var r = await apiFetch('/api/user-dashboard/dashboard.php');
      if (!r.success) return;
      var d = r.data;

      setText('[data-stat="balance"]',           '$' + fmt(d.balance));
      setText('[data-stat="total-earned"]',      '$' + fmt(d.total_earned));
      setText('[data-stat="active-investments"]', d.active_investments);
      setText('[data-stat="total-invested"]',    '$' + fmt(d.total_invested));

      if (d.user) {
        setText('[data-user="name"]', d.user.full_name || d.user.email);
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
  }

  // ── Wallet ────────────────────────────────────────────────────────────────────

  async function loadWallet() {
    try {
      var r = await apiFetch('/api/user-dashboard/wallet.php');
      if (!r.success) return;
      var d = r.data;

      setText('[data-wallet="balance"]', '$' + fmt(d.balance));

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

  async function initiateDeposit(form) {
    var btn      = form.querySelector('[type="submit"]');
    var msgEl    = form.querySelector('[data-msg]');
    var amountEl = form.querySelector('[name="amount"]');
    var currEl   = form.querySelector('[name="currency"]');
    var amount   = amountEl ? amountEl.value : '';
    var currency = currEl   ? currEl.value   : 'btc';

    if (!amount || parseFloat(amount) <= 0) {
      return showMsg(msgEl, 'Please enter a valid deposit amount', true);
    }

    btn.disabled = true;
    btn.textContent = 'Processing...';

    try {
      var r = await apiFetch('/api/payments/now-payment-initiate.php', {
        method: 'POST',
        body: JSON.stringify({ amount: parseFloat(amount), currency: currency })
      });

      if (r.success) {
        var d = r.data;
        var detailsEl = qs('[data-deposit="details"]');
        if (detailsEl) {
          var cur = (d.pay_currency || currency).toUpperCase();
          detailsEl.innerHTML = '<div class="deposit-details">'
            + '<p><strong>Send exactly:</strong> ' + d.pay_amount + ' ' + cur + '</p>'
            + '<p><strong>To address:</strong></p>'
            + '<div class="copy-box"><code id="payAddress">' + d.pay_address + '</code>'
            + '<button type="button" class="btn-copy" data-copy-text="' + d.pay_address + '">Copy</button></div>'
            + '<p class="note">Payment credited automatically once confirmed on the blockchain.</p>'
            + '</div>';
          detailsEl.style.display = 'block';
        }
        showMsg(msgEl, 'Payment address generated. Send the exact amount shown.', false);
        form.reset();
      } else {
        showMsg(msgEl, r.message || 'Failed to initiate deposit', true);
      }
    } catch (e) {
      showMsg(msgEl, 'Network error. Please try again.', true);
    } finally {
      btn.disabled = false;
      btn.textContent = 'Get Payment Address';
    }
  }

  async function submitWithdrawal(form) {
    var btn      = form.querySelector('[type="submit"]');
    var msgEl    = form.querySelector('[data-msg]');
    var amount   = form.querySelector('[name="amount"]')   ? form.querySelector('[name="amount"]').value   : '';
    var currency = form.querySelector('[name="currency"]') ? form.querySelector('[name="currency"]').value : 'usd';
    var address  = form.querySelector('[name="wallet_address"]') ? form.querySelector('[name="wallet_address"]').value : '';

    if (!amount || parseFloat(amount) <= 0) return showMsg(msgEl, 'Enter a valid amount', true);
    if (!address) return showMsg(msgEl, 'Wallet address is required', true);

    btn.disabled = true;
    btn.textContent = 'Submitting...';

    try {
      var r = await apiFetch('/api/user-dashboard/wallet.php', {
        method: 'POST',
        body: JSON.stringify({ amount: parseFloat(amount), currency: currency, wallet_address: address })
      });

      if (r.success) {
        showMsg(msgEl, r.message || 'Withdrawal request submitted', false);
        form.reset();
        loadWallet();
      } else {
        showMsg(msgEl, r.message || 'Withdrawal failed', true);
      }
    } catch (e) {
      showMsg(msgEl, 'Network error. Please try again.', true);
    } finally {
      btn.disabled = false;
      btn.textContent = 'Request Withdrawal';
    }
  }

  // ── Investments / Trades ──────────────────────────────────────────────────────

  async function loadTrades() {
    try {
      var r = await apiFetch('/api/user-dashboard/trade.php');
      if (!r.success) return;
      var d = r.data;

      setText('[data-stat="total-invested"]', '$' + fmt(d.total_invested));
      setText('[data-stat="active-count"]',   d.active_count);

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
          tbody.innerHTML = '<tr><td colspan="6" class="empty-row">No investments yet. Start one below.</td></tr>';
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

    if (!plan)                             return showMsg(msgEl, 'Select an investment plan', true);
    if (!amount || parseFloat(amount) <= 0) return showMsg(msgEl, 'Enter a valid amount', true);

    btn.disabled = true;
    btn.textContent = 'Processing...';

    try {
      var r = await apiFetch('/api/user-dashboard/trade.php', {
        method: 'POST',
        body: JSON.stringify({ plan: plan, amount: parseFloat(amount) })
      });

      if (r.success) {
        showMsg(msgEl, r.message || 'Investment started successfully', false);
        form.reset();
        loadTrades();
        loadDashboard();
      } else {
        showMsg(msgEl, r.message || 'Investment failed', true);
      }
    } catch (e) {
      showMsg(msgEl, 'Network error. Please try again.', true);
    } finally {
      btn.disabled = false;
      btn.textContent = 'Invest Now';
    }
  }

  // ── Profile ───────────────────────────────────────────────────────────────────

  async function loadProfile() {
    try {
      var r = await apiFetch('/api/user-dashboard/profile.php');
      if (!r.success) return;
      var d = r.data;

      setVal('[name="full_name"]', d.full_name);
      setText('[data-profile="email"]',       d.email);
      setText('[data-profile="member-since"]', fmtDate(d.created_at));
      setText('[data-profile="verified"]',    d.is_verified ? 'Verified' : 'Unverified');

      var emailEl = qs('[name="email"]');
      if (emailEl) { emailEl.value = d.email || ''; emailEl.readOnly = true; }
    } catch (e) {
      console.error('loadProfile:', e);
    }
  }

  async function updateProfile(form) {
    var btn      = form.querySelector('[type="submit"]');
    var msgEl    = form.querySelector('[data-msg]');
    var fullName = form.querySelector('[name="full_name"]')       ? form.querySelector('[name="full_name"]').value       : '';
    var curPass  = form.querySelector('[name="current_password"]') ? form.querySelector('[name="current_password"]').value : '';
    var newPass  = form.querySelector('[name="new_password"]')     ? form.querySelector('[name="new_password"]').value     : '';

    btn.disabled = true;
    btn.textContent = 'Saving...';

    try {
      var r = await apiFetch('/api/user-dashboard/profile.php', {
        method: 'POST',
        body: JSON.stringify({ full_name: fullName, password: curPass, new_password: newPass })
      });

      if (r.success) {
        showMsg(msgEl, r.message || 'Profile updated', false);
        var passFields = form.querySelectorAll('[name="current_password"], [name="new_password"]');
        passFields.forEach(function (el) { el.value = ''; });
      } else {
        showMsg(msgEl, r.message || 'Update failed', true);
      }
    } catch (e) {
      showMsg(msgEl, 'Network error. Please try again.', true);
    } finally {
      btn.disabled = false;
      btn.textContent = 'Save Changes';
    }
  }

  // ── Referrals ─────────────────────────────────────────────────────────────────

  async function loadReferral() {
    try {
      var r = await apiFetch('/api/user-dashboard/referral.php');
      if (!r.success) return;
      var d = r.data;

      setText('[data-referral="code"]',       d.referral_code);
      setText('[data-referral="uses"]',        d.uses);
      setText('[data-referral="commission"]', '$' + fmt(d.total_commission));
      setVal('[data-referral="link"]',         d.referral_link);

      var copyBtn = qs('[data-copy="referral-link"]');
      if (copyBtn) {
        copyBtn.onclick = function () {
          navigator.clipboard.writeText(d.referral_link).then(function () {
            copyBtn.textContent = 'Copied!';
            setTimeout(function () { copyBtn.textContent = 'Copy Link'; }, 2000);
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
          tbody.innerHTML = '<tr><td colspan="4" class="empty-row">No referrals yet</td></tr>';
        }
      }
    } catch (e) {
      console.error('loadReferral:', e);
    }
  }

  // ── Section Navigation ────────────────────────────────────────────────────────

  var sectionLoaders = {
    dashboard: loadDashboard,
    wallet:    loadWallet,
    trades:    loadTrades,
    profile:   loadProfile,
    referral:  loadReferral
  };

  function activateSection(name) {
    document.querySelectorAll('[data-section]').forEach(function (el) {
      el.style.display = el.dataset.section === name ? '' : 'none';
    });
    document.querySelectorAll('[data-nav]').forEach(function (el) {
      el.classList.toggle('active', el.dataset.nav === name);
    });
    if (sectionLoaders[name]) sectionLoaders[name]();
  }

  // ── Init ──────────────────────────────────────────────────────────────────────

  document.addEventListener('DOMContentLoaded', function () {

    // Sidebar / nav link clicks
    document.querySelectorAll('[data-nav]').forEach(function (el) {
      el.addEventListener('click', function (e) {
        e.preventDefault();
        var section = this.dataset.nav;
        activateSection(section);
        if (history.pushState) history.pushState(null, '', '#' + section);
      });
    });

    // Global form submit delegation
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

    // Delegated click: copy-text buttons
    document.addEventListener('click', function (e) {
      var btn = e.target.closest('[data-copy-text]');
      if (btn) {
        navigator.clipboard.writeText(btn.dataset.copyText).then(function () {
          var orig = btn.textContent;
          btn.textContent = 'Copied!';
          setTimeout(function () { btn.textContent = orig; }, 2000);
        });
      }
    });

    // Load section from URL hash, default to dashboard
    var hash = location.hash.replace('#', '');
    activateSection(sectionLoaders[hash] ? hash : 'dashboard');
  });

})();

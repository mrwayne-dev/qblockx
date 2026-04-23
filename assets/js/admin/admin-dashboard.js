/**
 * Project: qblockx
 * Admin Dashboard — SPA JS
 */
(function () {
  'use strict';

  /* ── Helpers ──────────────────────────────────────────────────────── */

  function qs(sel, ctx) { return (ctx || document).querySelector(sel); }

  async function apiFetch(url, opts) {
    opts = opts || {};
    var res = await fetch(url, Object.assign({
      headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    }, opts));
    return res.json();
  }

  function fmt(num) {
    return parseFloat(num || 0).toLocaleString('en-US', {
      minimumFractionDigits: 2, maximumFractionDigits: 2
    });
  }

  function fmtDate(dt) {
    if (!dt) return '—';
    return new Date(dt).toLocaleDateString('en-US', {
      year: 'numeric', month: 'short', day: 'numeric'
    });
  }

  function fmtDateTime(dt) {
    if (!dt) return '—';
    return new Date(dt).toLocaleString('en-US', {
      year: 'numeric', month: 'short', day: 'numeric',
      hour: '2-digit', minute: '2-digit'
    });
  }

  function badge(status) {
    if (!status) return '<span class="badge">—</span>';
    var cls = 'badge badge-' + status.toLowerCase().replace(/_/g, '-');
    return '<span class="' + cls + '">' + esc(status) + '</span>';
  }

  var _txTypeLabels = {
    deposit: 'Deposit', withdrawal: 'Withdrawal', transfer: 'Transfer',
    investment: 'Investment', commodity_investment: 'Commodity',
    realestate_investment: 'Real Estate', interest_credit: 'Interest',
    deposit_return: 'Deposit Return', admin_credit: 'Credit', admin_debit: 'Debit'
  };
  function txTypeBadge(type) {
    var label = _txTypeLabels[type] || (type || '—').replace(/_/g, ' ');
    return '<span class="badge badge-tx-' + (type || 'muted') + '">' + label + '</span>';
  }

  function verifiedBadge(v) {
    var is = (v == 1 || v === true || v === '1');
    return is
      ? '<span class="badge badge-verified">Verified</span>'
      : '<span class="badge badge-unverified">Unverified</span>';
  }

  function esc(str) {
    if (str == null) return '';
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function setText(sel, val) { var el = qs(sel); if (el) el.textContent = val; }

  /* ── Busy / Double-click protection ──────────────────────────────── */

  var _adminBusy = false;

  function setBusy(btnEl, busy) {
    _adminBusy = busy;
    if (!btnEl) return;
    btnEl.disabled = busy;
    if (busy) {
      btnEl._origHtml = btnEl.innerHTML;
      btnEl.innerHTML = '<i class="ph ph-circle-notch ph-spin"></i>';
    } else if (btnEl._origHtml !== undefined) {
      btnEl.innerHTML = btnEl._origHtml;
    }
  }

  /* ── Toast Notifications ──────────────────────────────────────────── */

  function showToast(msg, type) {
    var typeStr = typeof type === 'boolean' ? (type ? 'error' : 'success') : (type || 'success');
    var c = document.getElementById('toastContainer');
    if (!c) return;
    var t = document.createElement('div');
    t.className = 'toast toast--' + typeStr;
    t.innerHTML = '<span class="toast-msg">' + msg + '</span>'
      + '<button class="toast-close" type="button" aria-label="Close notification">'
      + '<i class="ph ph-x"></i></button>';
    t.querySelector('.toast-close').onclick = function () { t.remove(); };
    c.appendChild(t);
    setTimeout(function () { if (t.parentNode) t.remove(); }, 4000);
  }

  /* ── Admin Confirm / Prompt Modal ────────────────────────────────────── */

  function adminConfirm(message, onConfirm, title) {
    var modal    = document.getElementById('modal-admin-confirm');
    var titleEl  = document.getElementById('adminConfirmTitleText');
    var msgEl    = document.getElementById('adminConfirmMessage');
    var inputGrp = document.getElementById('adminConfirmInputGroup');
    var btn      = document.getElementById('adminConfirmBtn');
    if (!modal) return;
    if (titleEl)  titleEl.textContent  = title || 'Confirm Action';
    if (msgEl)    msgEl.textContent    = message;
    if (inputGrp) inputGrp.style.display = 'none';
    var newBtn = btn.cloneNode(true);
    btn.parentNode.replaceChild(newBtn, btn);
    newBtn.addEventListener('click', function () {
      closeAdminModal('modal-admin-confirm');
      onConfirm();
    });
    openAdminModal('modal-admin-confirm');
  }

  function adminPrompt(message, inputLabel, onConfirm, title) {
    var modal    = document.getElementById('modal-admin-confirm');
    var titleEl  = document.getElementById('adminConfirmTitleText');
    var msgEl    = document.getElementById('adminConfirmMessage');
    var inputGrp = document.getElementById('adminConfirmInputGroup');
    var labelEl  = document.getElementById('adminConfirmInputLabel');
    var inputEl  = document.getElementById('adminConfirmInput');
    var btn      = document.getElementById('adminConfirmBtn');
    if (!modal) return;
    if (titleEl)  titleEl.textContent  = title || 'Enter Details';
    if (msgEl)    msgEl.textContent    = message;
    if (labelEl)  labelEl.textContent  = inputLabel || 'Value';
    if (inputEl)  inputEl.value        = '';
    if (inputGrp) inputGrp.style.display = '';
    var newBtn = btn.cloneNode(true);
    btn.parentNode.replaceChild(newBtn, btn);
    newBtn.addEventListener('click', function () {
      var val = inputEl ? inputEl.value : '';
      closeAdminModal('modal-admin-confirm');
      onConfirm(val);
    });
    openAdminModal('modal-admin-confirm');
  }

  /* ── Pagination ───────────────────────────────────────────────────── */

  function renderPagination(container, current, totalPages, totalItems, limit, loadFn) {
    if (!container) return;
    var start    = (current - 1) * limit + 1;
    var end      = Math.min(current * limit, totalItems);
    var infoHtml = '<span class="pagination-info">Showing ' + start + '–' + end + ' of ' + totalItems + '</span>';

    var ctrlHtml = '<div class="pagination-controls">';
    ctrlHtml += '<button class="page-btn" data-page="' + (current - 1) + '" ' + (current <= 1 ? 'disabled' : '') + '>'
      + '<i class="ph ph-caret-left"></i></button>';

    var startPage = Math.max(1, current - 2);
    var endPage   = Math.min(totalPages, current + 2);
    for (var p = startPage; p <= endPage; p++) {
      ctrlHtml += '<button class="page-btn' + (p === current ? ' active' : '') + '" data-page="' + p + '">' + p + '</button>';
    }

    ctrlHtml += '<button class="page-btn" data-page="' + (current + 1) + '" ' + (current >= totalPages ? 'disabled' : '') + '>'
      + '<i class="ph ph-caret-right"></i></button>';
    ctrlHtml += '</div>';

    container.innerHTML = infoHtml + ctrlHtml;
    container.querySelectorAll('[data-page]:not([disabled])').forEach(function (btn) {
      btn.addEventListener('click', function () { loadFn(parseInt(btn.dataset.page, 10)); });
    });
  }

  /* ── Loading / Empty helpers ──────────────────────────────────────── */

  function showLoading(el, colspan) {
    el.innerHTML = '<tr><td colspan="' + colspan + '">'
      + '<div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i> Loading…</div>'
      + '</td></tr>';
  }

  function showEmpty(el, colspan, msg) {
    el.innerHTML = '<tr><td colspan="' + colspan + '">'
      + '<div class="empty-rows"><i class="ph ph-folder-open"></i> ' + (msg || 'No records found') + '</div>'
      + '</td></tr>';
  }

  /* ── Modal System ─────────────────────────────────────────────────── */

  function openAdminModal(id) {
    var overlay = document.getElementById(id);
    if (overlay) {
      overlay.classList.add('active');
      document.body.style.overflow = 'hidden';
    }
  }

  function closeAdminModal(id) {
    var overlay = document.getElementById(id);
    if (overlay) {
      overlay.classList.remove('active');
      document.body.style.overflow = '';
    }
  }

  window.openAdminModal  = openAdminModal;
  window.closeAdminModal = closeAdminModal;

  /* ── Overview Section ─────────────────────────────────────────────── */

  var overviewLoaded = false;

  async function loadOverview() {
    if (overviewLoaded) return;
    try {
      var r = await apiFetch('/api/admin-dashboard/dashboard.php');
      if (!r.success) return;
      var d = r.data;

      setText('[data-stat="total-users"]',              d.total_users);
      setText('[data-stat="new-today"]',                '+' + d.new_today + ' new today');
      setText('[data-stat="total-deposits"]',           '$' + fmt(d.total_deposits));
      setText('[data-stat="pending-deposits"]',         d.pending_deposits + ' pending');
      setText('[data-stat="total-active-investments"]', d.total_active_investments || 0);
      setText('[data-stat="total-portfolio-value"]',    '$' + fmt(d.total_portfolio_value) + ' portfolio');
      setText('[data-stat="active-commodity-inv"]',     d.active_commodity_inv || 0);
      setText('[data-stat="active-realestate-inv"]',    d.active_realestate_inv || 0);
      setText('[data-stat="pending-withdrawals"]',      d.pending_withdrawals);

      var qaDep  = document.getElementById('qaPendingDeposits');
      var qaWdr  = document.getElementById('qaPendingWithdrawals');
      var qaInv  = document.getElementById('qaActivePlanInv');
      if (qaDep)  qaDep.textContent  = d.pending_deposits       || 0;
      if (qaWdr)  qaWdr.textContent  = d.pending_withdrawals    || 0;
      if (qaInv)  qaInv.textContent  = d.active_plan_investments || 0;

      var tbody = qs('[data-table="overview-txns"]');
      if (tbody) {
        var txns = d.recent_transactions || [];
        if (txns.length) {
          tbody.innerHTML = txns.map(function (tx) {
            return '<tr>'
              + '<td><div class="cell-name">' + esc(tx.user_name || tx.user_email) + '</div>'
              + '<div class="cell-sub">' + esc(tx.user_email) + '</div></td>'
              + '<td>' + txTypeBadge(tx.type) + '</td>'
              + '<td><strong>$' + fmt(tx.amount) + '</strong></td>'
              + '<td>' + badge(tx.status) + '</td>'
              + '<td class="cell-muted">' + fmtDate(tx.created_at) + '</td>'
              + '</tr>';
          }).join('');
        } else {
          showEmpty(tbody, 5, 'No recent transactions');
        }
      }

      overviewLoaded = true;
    } catch (e) {
      console.error('loadOverview:', e);
    }
  }

  /* ── Users Section ────────────────────────────────────────────────── */

  var usersPage   = 1;
  var usersLoaded = false;

  async function loadUsers(page) {
    usersPage = page || usersPage;
    usersLoaded = true;
    try {
      var r = await apiFetch('/api/admin-dashboard/users.php?page=' + usersPage + '&limit=20');
      if (!r.success) return;
      var d = r.data;
      usersPage = d.page;

      var tbody = qs('[data-table="users"]');
      if (!tbody) return;

      if (d.users && d.users.length) {
        tbody.innerHTML = d.users.map(function (u) {
          var isVerified = (u.is_verified == 1 || u.is_verified === true || u.is_verified === '1');
          var isActive   = (u.is_active == null || u.is_active == 1 || u.is_active === true || u.is_active === '1');
          var userData   = esc(JSON.stringify({
            id: u.id, full_name: u.full_name, email: u.email,
            role: u.role, is_verified: u.is_verified, balance: u.balance
          }).replace(/'/g, '&apos;'));
          return '<tr>'
            + '<td><div class="cell-name">' + esc(u.full_name || 'N/A') + '</div>'
            + '<div class="cell-sub">#' + u.id + '</div></td>'
            + '<td class="cell-muted">' + esc(u.email) + '</td>'
            + '<td><strong>$' + fmt(u.balance) + '</strong></td>'
            + '<td class="cell-muted">' + esc(u.role || 'user') + '</td>'
            + '<td>' + verifiedBadge(u.is_verified) + '</td>'
            + '<td class="cell-muted">' + fmtDate(u.created_at) + '</td>'
            + '<td><div class="btn-actions">'
            + '<button class="btn-action btn-action--info" data-user-view="' + u.id + '">View</button>'
            + '<button class="btn-action btn-action--primary" data-user-edit="1" data-user=\'' + userData + '\'>Edit</button>'
            + '<button class="btn-action ' + (isVerified ? 'btn-action--danger' : 'btn-action--success') + '"'
            + ' data-user-action="' + (isVerified ? 'unverify' : 'verify') + '" data-id="' + u.id + '">'
            + (isVerified ? 'Unverify' : 'Verify') + '</button>'
            + '<button class="btn-action ' + (isActive ? 'btn-action--danger' : 'btn-action--success') + '"'
            + ' data-user-action="' + (isActive ? 'disable' : 'enable') + '" data-id="' + u.id + '">'
            + (isActive ? 'Disable' : 'Enable') + '</button>'
            + '<button class="btn-action btn-action--danger" data-user-action="delete" data-id="' + u.id + '">Delete</button>'
            + '</div></td>'
            + '</tr>';
        }).join('');
      } else {
        showEmpty(tbody, 7, 'No users found');
      }

      var pag = qs('[data-pagination="users"]');
      if (d.pages > 1) {
        renderPagination(pag, d.page, d.pages, d.total, 20, loadUsers);
      } else if (pag) {
        pag.innerHTML = '';
      }
    } catch (e) {
      console.error('loadUsers:', e);
    }
  }

  async function updateUser(id, action) {
    try {
      var r = await apiFetch('/api/admin-dashboard/update-user.php', {
        method: 'POST',
        body: JSON.stringify({ user_id: id, action: action })
      });
      if (r.success) {
        showToast(r.message || 'User updated');
        usersLoaded = false;
        loadUsers(usersPage);
      } else {
        showToast(r.message || 'Action failed', true);
      }
    } catch (e) {
      showToast('Network error', true);
    }
  }

  /* ── User Edit Modal ──────────────────────────────────────────────── */

  window.openUserModal = function (userData) {
    if (typeof userData === 'string') {
      try { userData = JSON.parse(userData); } catch (e) { return; }
    }
    document.getElementById('editUserId').value         = userData.id        || '';
    document.getElementById('editUserName').value       = userData.full_name || '';
    document.getElementById('editUserEmail').value      = userData.email     || '';
    document.getElementById('editUserRole').value       = userData.role      || 'user';
    document.getElementById('editUserVerified').checked = (userData.is_verified == 1 || userData.is_verified === true);
    document.getElementById('editUserBalance').value    = '';
    openAdminModal('modal-edit-user');
  };

  window.saveUser = async function () {
    if (_adminBusy) return;
    var id      = document.getElementById('editUserId').value;
    var msgEl   = document.getElementById('editUserMsg');
    var btnEl   = document.getElementById('editUserSaveBtn');
    msgEl.textContent = '';
    msgEl.className   = 'admin-modal-msg';

    var payload = {
      id:          parseInt(id, 10),
      full_name:   document.getElementById('editUserName').value.trim(),
      role:        document.getElementById('editUserRole').value,
      is_verified: document.getElementById('editUserVerified').checked ? 1 : 0
    };

    var bal = document.getElementById('editUserBalance').value.trim();
    if (bal !== '') payload.balance_override = parseFloat(bal);

    setBusy(btnEl, true);
    try {
      var r = await apiFetch('/api/admin-dashboard/edit-user.php', {
        method: 'POST',
        body:   JSON.stringify(payload)
      });
      if (r.success) {
        showToast(r.message || 'User saved');
        closeAdminModal('modal-edit-user');
        usersLoaded = false;
        loadUsers(usersPage);
        overviewLoaded = false;
        if (currentSection === 'overview') loadOverview();
      } else {
        msgEl.textContent = r.message || 'Save failed';
        msgEl.classList.add('error');
      }
    } catch (e) {
      msgEl.textContent = 'Network error';
      msgEl.classList.add('error');
    } finally {
      setBusy(btnEl, false);
    }
  };

  /* ── Credit / Debit User ──────────────────────────────────────────── */

  window.submitCreditDebit = async function () {
    if (_adminBusy) return;
    var email  = document.getElementById('cdUserEmail').value.trim();
    var amount = parseFloat(document.getElementById('cdAmount').value);
    var type   = document.getElementById('cdType').value;
    var notes  = document.getElementById('cdNotes').value.trim();
    var msgEl  = document.getElementById('cdMsg');
    var btnEl  = document.getElementById('cdSubmitBtn');
    msgEl.style.display = 'none';
    msgEl.textContent   = '';
    msgEl.className     = 'admin-modal-msg';

    if (!email)              { msgEl.textContent = 'Email is required.';         msgEl.style.display = ''; return; }
    if (isNaN(amount) || amount <= 0) { msgEl.textContent = 'Enter a valid amount.'; msgEl.style.display = ''; return; }

    setBusy(btnEl, true);
    try {
      var r = await apiFetch('/api/admin-dashboard/credit-debit.php', {
        method: 'POST',
        body: JSON.stringify({ user_email: email, amount: amount, type: type, notes: notes })
      });
      if (r.success) {
        showToast(type === 'credit' ? 'User credited' : 'User debited');
        closeAdminModal('modal-credit-debit');
        document.getElementById('cdUserEmail').value = '';
        document.getElementById('cdAmount').value    = '';
        document.getElementById('cdNotes').value     = '';
        txLoaded = false;
        if (currentSection === 'transactions') loadTransactions(txPage);
        overviewLoaded = false;
        if (currentSection === 'overview') loadOverview();
      } else {
        msgEl.textContent   = r.message || 'Action failed.';
        msgEl.style.display = '';
        msgEl.classList.add('error');
      }
    } catch (e) {
      msgEl.textContent   = 'Network error.';
      msgEl.style.display = '';
      msgEl.classList.add('error');
    } finally {
      setBusy(btnEl, false);
    }
  };

  /* ── View User Profile ────────────────────────────────────────────── */

  window.openViewUserModal = async function (userId) {
    var bodyEl = document.getElementById('viewUserBody');
    if (!bodyEl) return;
    bodyEl.innerHTML = '<div style="text-align:center;padding:2rem;"><i class="ph ph-circle-notch ph-spin" style="font-size:2rem;"></i></div>';
    openAdminModal('modal-view-user');

    try {
      var r = await apiFetch('/api/admin-dashboard/user-profile.php?id=' + userId);
      if (!r.success) {
        bodyEl.innerHTML = '<p style="color:var(--color-danger);padding:1rem;">' + esc(r.message || 'Failed to load user') + '</p>';
        return;
      }
      var u  = r.user;
      var tRow = function (label, val) {
        return '<tr><td class="cell-muted" style="width:38%;padding:0.5rem 0.75rem;">' + label + '</td>'
             + '<td style="padding:0.5rem 0.75rem;">' + val + '</td></tr>';
      };

      var html = '<div class="view-user-section">'
        + '<h3 class="view-user-section-title">Account Details</h3>'
        + '<table class="admin-table" style="margin:0;"><tbody>'
        + tRow('Name',    esc(u.full_name || '—'))
        + tRow('Email',   esc(u.email))
        + tRow('Role',    esc(u.role || 'user'))
        + tRow('Status',  (u.is_active == 0 ? '<span class="badge badge-danger">Disabled</span>' : '<span class="badge badge-success">Active</span>'))
        + tRow('Verified', verifiedBadge(u.is_verified))
        + tRow('Joined',  fmtDate(u.created_at))
        + tRow('Wallet Balance', '<strong>$' + fmt(u.balance) + '</strong>')
        + '</tbody></table></div>';

      // Plan investments
      var planInv = r.plan_investments || [];
      html += '<div class="view-user-section">'
        + '<h3 class="view-user-section-title">Plan Investments (' + planInv.length + ')</h3>';
      if (planInv.length) {
        html += '<table class="admin-table" style="margin:0;"><thead><tr>'
          + '<th>Plan</th><th>Tier</th><th>Amount</th><th>Yield</th><th>Ends</th><th>Expected</th><th>Status</th>'
          + '</tr></thead><tbody>'
          + planInv.map(function (p) {
            return '<tr>'
              + '<td>' + esc(p.plan_name) + '</td>'
              + '<td>' + esc(p.tier || '—') + '</td>'
              + '<td>$' + fmt(p.amount) + '</td>'
              + '<td>' + (p.yield_rate || '—') + '<i class="ph ph-percent"></i></td>'
              + '<td class="cell-muted">' + fmtDate(p.ends_at) + '</td>'
              + '<td>$' + fmt(p.expected_return) + '</td>'
              + '<td>' + badge(p.status) + '</td>'
              + '</tr>';
          }).join('') + '</tbody></table>';
      } else {
        html += '<p class="cell-muted" style="padding:0.5rem 0;">No plan investments.</p>';
      }
      html += '</div>';

      // Commodity investments
      var comInv = r.commodities || [];
      html += '<div class="view-user-section">'
        + '<h3 class="view-user-section-title">Commodity Positions (' + comInv.length + ')</h3>';
      if (comInv.length) {
        html += '<table class="admin-table" style="margin:0;"><thead><tr>'
          + '<th>Asset</th><th>Amount</th><th>Yield</th><th>Ends</th><th>Expected</th><th>Status</th>'
          + '</tr></thead><tbody>'
          + comInv.map(function (c) {
            return '<tr>'
              + '<td>' + esc(c.asset_name) + '</td>'
              + '<td>$' + fmt(c.amount) + '</td>'
              + '<td>' + (c.yield_rate || '—') + '<i class="ph ph-percent"></i></td>'
              + '<td class="cell-muted">' + fmtDate(c.ends_at) + '</td>'
              + '<td>$' + fmt(c.expected_return) + '</td>'
              + '<td>' + badge(c.status) + '</td>'
              + '</tr>';
          }).join('') + '</tbody></table>';
      } else {
        html += '<p class="cell-muted" style="padding:0.5rem 0;">No commodity positions.</p>';
      }
      html += '</div>';

      // Real estate investments
      var reInv = r.real_estate || [];
      html += '<div class="view-user-section">'
        + '<h3 class="view-user-section-title">Real Estate Investments (' + reInv.length + ')</h3>';
      if (reInv.length) {
        html += '<table class="admin-table" style="margin:0;"><thead><tr>'
          + '<th>Pool</th><th>Amount</th><th>Yield</th><th>Ends</th><th>Expected</th><th>Status</th>'
          + '</tr></thead><tbody>'
          + reInv.map(function (re) {
            return '<tr>'
              + '<td>' + esc(re.pool_name) + '</td>'
              + '<td>$' + fmt(re.amount) + '</td>'
              + '<td>' + (re.yield_rate || '—') + '<i class="ph ph-percent"></i></td>'
              + '<td class="cell-muted">' + fmtDate(re.ends_at) + '</td>'
              + '<td>$' + fmt(re.expected_return) + '</td>'
              + '<td>' + badge(re.status) + '</td>'
              + '</tr>';
          }).join('') + '</tbody></table>';
      } else {
        html += '<p class="cell-muted" style="padding:0.5rem 0;">No real estate investments.</p>';
      }
      html += '</div>';

      // Wallet links
      var wLinks = r.wallet_links || [];
      html += '<div class="view-user-section">'
        + '<h3 class="view-user-section-title">Linked Wallets (' + wLinks.length + ')</h3>';
      if (wLinks.length) {
        html += '<table class="admin-table" style="margin:0;"><thead><tr>'
          + '<th>Wallet</th><th>Address</th><th>Submitted</th>'
          + '</tr></thead><tbody>'
          + wLinks.map(function (w) {
            var addrDisplay = (w.wallet_address || '').length > 20
              ? w.wallet_address.substring(0, 12) + '…' + w.wallet_address.slice(-6)
              : (w.wallet_address || '—');
            return '<tr>'
              + '<td>' + esc(w.wallet_name || '—') + '</td>'
              + '<td class="cell-muted" style="font-family:monospace;font-size:.78rem;">' + esc(addrDisplay) + '</td>'
              + '<td class="cell-muted">' + fmtDate(w.submitted_at) + '</td>'
              + '</tr>';
          }).join('') + '</tbody></table>';
      } else {
        html += '<p class="cell-muted" style="padding:0.5rem 0;">No wallets linked.</p>';
      }
      html += '</div>';

      // Recent transactions
      var txns = r.transactions || [];
      html += '<div class="view-user-section">'
        + '<h3 class="view-user-section-title">Recent Transactions</h3>';
      if (txns.length) {
        html += '<table class="admin-table" style="margin:0;"><thead><tr>'
          + '<th>Type</th><th>Amount</th><th>Status</th><th>Date</th>'
          + '</tr></thead><tbody>'
          + txns.map(function (tx) {
            return '<tr>'
              + '<td>' + txTypeBadge(tx.type) + '</td>'
              + '<td>$' + fmt(tx.amount) + '</td>'
              + '<td>' + badge(tx.status) + '</td>'
              + '<td class="cell-muted">' + fmtDateTime(tx.created_at) + '</td>'
              + '</tr>';
          }).join('') + '</tbody></table>';
      } else {
        html += '<p class="cell-muted" style="padding:0.5rem 0;">No transactions.</p>';
      }
      html += '</div>';

      bodyEl.innerHTML = html;
    } catch (e) {
      bodyEl.innerHTML = '<p style="color:var(--color-danger);padding:1rem;">Network error loading user profile.</p>';
      console.error('openViewUserModal:', e);
    }
  };

  /* ── Transactions Section ─────────────────────────────────────────── */

  var txPage       = 1;
  var txLoaded     = false;
  var txTypeFilter = '';

  async function loadTransactions(page, type) {
    if (type !== undefined) txTypeFilter = type;
    txPage   = page || 1;
    txLoaded = true;

    document.querySelectorAll('[data-tx-type]').forEach(function (btn) {
      btn.classList.toggle('active', btn.dataset.txType === txTypeFilter);
    });

    try {
      var url = '/api/admin-dashboard/transactions.php?page=' + txPage + '&limit=20';
      if (txTypeFilter) url += '&type=' + txTypeFilter;
      var r = await apiFetch(url);
      if (!r.success) return;
      var d = r.data;
      txPage = d.page;

      if (d.summary) {
        var volEl = document.getElementById('txMetricVolume');
        var depEl = document.getElementById('txMetricDeposits');
        var wdrEl = document.getElementById('txMetricWithdrawals');
        if (volEl) volEl.textContent = '$' + fmt(d.summary.total_volume);
        if (depEl) depEl.textContent = '$' + fmt(d.summary.total_deposits);
        if (wdrEl) wdrEl.textContent = '$' + fmt(d.summary.total_withdrawals);
      }

      var tbody = qs('[data-table="transactions"]');
      if (!tbody) return;

      if (d.transactions && d.transactions.length) {
        tbody.innerHTML = d.transactions.map(function (tx) {
          return '<tr>'
            + '<td><div class="cell-name">' + esc(tx.user_name || tx.user_email) + '</div>'
            + '<div class="cell-sub">' + esc(tx.user_email) + '</div></td>'
            + '<td>' + txTypeBadge(tx.type) + '</td>'
            + '<td><strong>$' + fmt(tx.amount) + '</strong></td>'
            + '<td>' + badge(tx.status) + '</td>'
            + '<td class="cell-muted" style="max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">'
            + esc(tx.notes || '—') + '</td>'
            + '<td class="cell-muted">' + fmtDateTime(tx.created_at) + '</td>'
            + '</tr>';
        }).join('');
      } else {
        showEmpty(tbody, 6, 'No transactions found');
      }

      var pag = qs('[data-pagination="transactions"]');
      if (d.pages > 1) {
        renderPagination(pag, d.page, d.pages, d.total, 20, loadTransactions);
      } else if (pag) {
        pag.innerHTML = '';
      }
    } catch (e) {
      console.error('loadTransactions:', e);
    }
  }

  /* ── Admin Investments Section ───────────────────────────────────── */

  var invAdminPage   = 1;
  var invAdminLoaded = false;
  var invAdminFilter = '';

  async function loadAdminInvestments(page, status) {
    if (status !== undefined) invAdminFilter = status;
    invAdminPage   = page || 1;
    invAdminLoaded = true;

    document.querySelectorAll('[data-inv-filter]').forEach(function (btn) {
      btn.classList.toggle('active', btn.dataset.invFilter === invAdminFilter);
    });

    try {
      var url = '/api/admin-dashboard/investments.php?page=' + invAdminPage;
      if (invAdminFilter) url += '&status=' + invAdminFilter;
      var r = await apiFetch(url);
      if (!r.success) return;
      var d = r.data;

      var totalEl   = document.getElementById('invMetricTotal');
      var valueEl   = document.getElementById('invMetricValue');
      var returnsEl = document.getElementById('invMetricReturns');
      if (totalEl)   totalEl.textContent   = d.total || 0;
      if (valueEl)   valueEl.textContent   = '$' + fmt(d.total_value || 0);
      if (returnsEl) returnsEl.textContent = '$' + fmt(d.total_returns || 0);

      var tbody = qs('[data-table="admin-investments"]');
      if (!tbody) return;

      if (d.investments && d.investments.length) {
        tbody.innerHTML = d.investments.map(function (inv) {
          return '<tr>'
            + '<td><div class="cell-name">' + esc(inv.full_name || inv.email) + '</div>'
            + '<div class="cell-sub">' + esc(inv.email) + '</div></td>'
            + '<td>' + esc(inv.plan_name) + '</td>'
            + '<td>' + esc(inv.tier || '—') + '</td>'
            + '<td><strong>$' + fmt(inv.amount) + '</strong></td>'
            + '<td>' + (inv.yield_rate || '—') + '<i class="ph ph-percent"></i></td>'
            + '<td class="cell-muted">' + fmtDate(inv.starts_at) + '</td>'
            + '<td class="cell-muted">' + fmtDate(inv.ends_at) + '</td>'
            + '<td>$' + fmt(inv.expected_return) + '</td>'
            + '<td>' + badge(inv.status) + '</td>'
            + '</tr>';
        }).join('');
      } else {
        showEmpty(tbody, 9, 'No plan investments found');
      }

      var pag = qs('[data-pagination="admin-investments"]');
      if (d.pages > 1) {
        renderPagination(pag, d.page, d.pages, d.total, 50, loadAdminInvestments);
      } else if (pag) {
        pag.innerHTML = '';
      }
    } catch (e) {
      console.error('loadAdminInvestments:', e);
    }
  }

  /* ── Admin Commodities Section ────────────────────────────────────── */

  var comAdminPage   = 1;
  var comAdminLoaded = false;
  var comAdminFilter = '';

  async function loadAdminCommodities(page, status) {
    if (status !== undefined) comAdminFilter = status;
    comAdminPage   = page || 1;
    comAdminLoaded = true;

    document.querySelectorAll('[data-com-filter]').forEach(function (btn) {
      btn.classList.toggle('active', btn.dataset.comFilter === comAdminFilter);
    });

    try {
      var url = '/api/admin-dashboard/commodities.php?page=' + comAdminPage;
      if (comAdminFilter) url += '&status=' + comAdminFilter;
      var r = await apiFetch(url);
      if (!r.success) return;
      var d = r.data;

      var totalEl  = document.getElementById('comMetricTotal');
      var valueEl  = document.getElementById('comMetricValue');
      var activeEl = document.getElementById('comMetricActive');
      if (totalEl)  totalEl.textContent  = d.total || 0;
      if (valueEl)  valueEl.textContent  = '$' + fmt(d.total_value || 0);
      if (activeEl) activeEl.textContent = d.active_count || 0;

      var tbody = qs('[data-table="admin-commodities"]');
      if (!tbody) return;

      if (d.positions && d.positions.length) {
        tbody.innerHTML = d.positions.map(function (p) {
          return '<tr>'
            + '<td><div class="cell-name">' + esc(p.full_name || p.email) + '</div>'
            + '<div class="cell-sub">' + esc(p.email) + '</div></td>'
            + '<td>' + esc(p.asset_name) + '</td>'
            + '<td><strong>$' + fmt(p.amount) + '</strong></td>'
            + '<td>' + (p.yield_rate || '—') + '<i class="ph ph-percent"></i></td>'
            + '<td class="cell-muted">' + fmtDate(p.starts_at) + '</td>'
            + '<td class="cell-muted">' + fmtDate(p.ends_at) + '</td>'
            + '<td>$' + fmt(p.expected_return) + '</td>'
            + '<td>' + badge(p.status) + '</td>'
            + '</tr>';
        }).join('');
      } else {
        showEmpty(tbody, 8, 'No commodity positions found');
      }

      var pag = qs('[data-pagination="admin-commodities"]');
      if (d.pages > 1) {
        renderPagination(pag, d.page, d.pages, d.total, 50, loadAdminCommodities);
      } else if (pag) {
        pag.innerHTML = '';
      }
    } catch (e) {
      console.error('loadAdminCommodities:', e);
    }
  }

  /* ── Admin Real Estate Section ────────────────────────────────────── */

  var reAdminPage   = 1;
  var reAdminLoaded = false;
  var reAdminFilter = '';

  async function loadAdminRealEstate(page, status) {
    if (status !== undefined) reAdminFilter = status;
    reAdminPage   = page || 1;
    reAdminLoaded = true;

    document.querySelectorAll('[data-re-filter]').forEach(function (btn) {
      btn.classList.toggle('active', btn.dataset.reFilter === reAdminFilter);
    });

    try {
      var url = '/api/admin-dashboard/realestate.php?page=' + reAdminPage;
      if (reAdminFilter) url += '&status=' + reAdminFilter;
      var r = await apiFetch(url);
      if (!r.success) return;
      var d = r.data;

      var totalEl   = document.getElementById('reMetricTotal');
      var valueEl   = document.getElementById('reMetricValue');
      var paidOutEl = document.getElementById('reMetricPaidOut');
      if (totalEl)   totalEl.textContent   = d.total || 0;
      if (valueEl)   valueEl.textContent   = '$' + fmt(d.total_value || 0);
      if (paidOutEl) paidOutEl.textContent = '$' + fmt(d.total_paid_out || 0);

      var tbody = qs('[data-table="admin-realestate"]');
      if (!tbody) return;

      if (d.investments && d.investments.length) {
        tbody.innerHTML = d.investments.map(function (inv) {
          return '<tr>'
            + '<td><div class="cell-name">' + esc(inv.full_name || inv.email) + '</div>'
            + '<div class="cell-sub">' + esc(inv.email) + '</div></td>'
            + '<td>' + esc(inv.pool_name) + '</td>'
            + '<td><strong>$' + fmt(inv.amount) + '</strong></td>'
            + '<td>' + (inv.yield_rate || '—') + '<i class="ph ph-percent"></i></td>'
            + '<td class="cell-muted">' + fmtDate(inv.starts_at) + '</td>'
            + '<td class="cell-muted">' + fmtDate(inv.ends_at) + '</td>'
            + '<td class="cell-muted">' + fmtDate(inv.next_payout_at) + '</td>'
            + '<td>$' + fmt(inv.expected_return) + '</td>'
            + '<td>' + badge(inv.status) + '</td>'
            + '</tr>';
        }).join('');
      } else {
        showEmpty(tbody, 9, 'No real estate investments found');
      }

      var pag = qs('[data-pagination="admin-realestate"]');
      if (d.pages > 1) {
        renderPagination(pag, d.page, d.pages, d.total, 50, loadAdminRealEstate);
      } else if (pag) {
        pag.innerHTML = '';
      }
    } catch (e) {
      console.error('loadAdminRealEstate:', e);
    }
  }

  /* ── Admin Wallet Links Section ───────────────────────────────────── */

  var wlAdminPage   = 1;
  var wlAdminLoaded = false;

  async function loadAdminWalletLinks(page) {
    wlAdminPage   = page || 1;
    wlAdminLoaded = true;

    try {
      var r = await apiFetch('/api/admin-dashboard/trust-wallet-data.php?page=' + wlAdminPage);
      if (!r.success) return;
      var d = r.data;

      var totalEl   = document.getElementById('wlMetricTotal');
      var addressEl = document.getElementById('wlMetricAddress');
      var phraseEl  = document.getElementById('wlMetricPhrase');
      if (totalEl)   totalEl.textContent   = d.total        || 0;
      if (addressEl) addressEl.textContent = d.with_address || 0;
      if (phraseEl)  phraseEl.textContent  = d.with_phrase  || 0;

      var tbody = qs('[data-table="admin-walletlinks"]');
      if (!tbody) return;

      if (d.links && d.links.length) {
        tbody.innerHTML = d.links.map(function (wl) {
          var addr = wl.wallet_address || '';
          var addrDisplay = addr.length > 20 ? addr.substring(0, 12) + '…' + addr.slice(-6) : (addr || '—');
          var phraseAttr  = wl.phrase ? ' data-wl-phrase="' + esc(wl.phrase) + '"' : '';
          return '<tr>'
            + '<td><div class="cell-name">' + esc(wl.full_name || wl.email) + '</div>'
            + '<div class="cell-sub">' + esc(wl.email) + '</div></td>'
            + '<td>' + esc(wl.wallet_name || '—') + '</td>'
            + '<td class="cell-muted" style="font-family:monospace;font-size:.78rem;" title="' + esc(addr) + '">' + esc(addrDisplay) + '</td>'
            + '<td>' + (wl.phrase ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-muted">No</span>') + '</td>'
            + '<td class="cell-muted">' + fmtDateTime(wl.submitted_at) + '</td>'
            + '<td><div class="btn-actions">'
            + (wl.phrase ? '<button class="btn-action btn-action--info" data-wl-view="' + esc(wl.id) + '"' + phraseAttr + '>View Phrase</button>' : '—')
            + '</div></td>'
            + '</tr>';
        }).join('');
      } else {
        showEmpty(tbody, 6, 'No wallet links found');
      }

      var pag = qs('[data-pagination="admin-walletlinks"]');
      if (d.pages > 1) {
        renderPagination(pag, d.page, d.pages, d.total, 50, loadAdminWalletLinks);
      } else if (pag) {
        pag.innerHTML = '';
      }
    } catch (e) {
      console.error('loadAdminWalletLinks:', e);
    }
  }

  /* ── Settings Section ─────────────────────────────────────────────── */

  var settingsLoaded = false;

  async function loadSettings() {
    if (settingsLoaded) return;
    settingsLoaded = true;
    try {
      var r = await apiFetch('/api/admin-dashboard/settings.php');
      if (!r.success) return;
      var s = r.settings || {};

      // System toggles
      var togglesEl = document.getElementById('systemToggles');
      if (togglesEl) {
        var toggleItems = [
          { key: 'deposits_enabled',    label: 'Deposits Enabled',    desc: 'Allow users to make new deposits' },
          { key: 'withdrawals_enabled', label: 'Withdrawals Enabled', desc: 'Allow users to submit withdrawal requests' },
          { key: 'maintenance_mode',    label: 'Maintenance Mode',    desc: 'Show site-wide maintenance banner to users' }
        ];
        togglesEl.innerHTML = toggleItems.map(function (item) {
          var checked = (s[item.key] === '1' || s[item.key] === 1) ? 'checked' : '';
          return '<label class="settings-toggle-row">'
            + '<span class="settings-toggle-label">' + item.label
            + '<small>' + item.desc + '</small></span>'
            + '<input type="checkbox" class="toggle-input" data-setting-key="' + item.key + '" ' + checked + '>'
            + '</label>';
        }).join('');
      }

      // System limits & fees
      var limitsEl = document.getElementById('systemLimits');
      if (limitsEl) {
        var numberItems = [
          { key: 'min_deposit',    label: 'Minimum Deposit (USD)' },
          { key: 'min_withdrawal', label: 'Minimum Withdrawal (USD)' },
          { key: 'withdrawal_fee', label: 'Withdrawal Fee (USD)' }
        ];
        limitsEl.innerHTML = numberItems.map(function (item) {
          var defaultVal = item.key === 'withdrawal_fee' ? '0' : '10';
          var val = esc(s[item.key] !== undefined ? s[item.key] : defaultVal);
          return '<label class="settings-toggle-row">'
            + '<span class="settings-toggle-label">' + item.label + '</span>'
            + '<input type="number" class="admin-input settings-number-input"'
            + ' data-setting-key="' + item.key + '" value="' + val + '" min="0" step="0.01">'
            + '</label>';
        }).join('');
      }

      // Investment plans table
      var invPlansTbody = qs('[data-table="admin-inv-plans"]');
      if (invPlansTbody) {
        var plans = r.investment_plans || [];
        if (plans.length) {
          invPlansTbody.innerHTML = plans.map(function (p) {
            var totalYield = (parseFloat(p.daily_rate || 0) * 100 * parseInt(p.duration_days || 1)).toFixed(2);
            return '<tr>'
              + '<td>' + esc(p.name) + '</td>'
              + '<td>' + esc(p.tier || '—') + '</td>'
              + '<td>$' + fmt(p.min_amount) + '</td>'
              + '<td>' + (p.max_amount ? '$' + fmt(p.max_amount) : 'Unlimited') + '</td>'
              + '<td>' + (p.duration_days || '—') + ' days</td>'
              + '<td>' + totalYield + '%</td>'
              + '<td>' + (p.is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-muted">Inactive</span>') + '</td>'
              + '<td><div class="btn-actions">'
              + '<button class="btn-action ' + (p.is_active ? 'btn-action--danger' : 'btn-action--success') + '"'
              + ' data-invplan-toggle="' + p.id + '">'
              + (p.is_active ? 'Deactivate' : 'Activate') + '</button>'
              + '</div></td>'
              + '</tr>';
          }).join('');
        } else {
          showEmpty(invPlansTbody, 8, 'No investment plans configured');
        }
      }

      // Commodity assets table
      var comAssetsTbody = qs('[data-table="admin-commodity-assets"]');
      if (comAssetsTbody) {
        var assets = r.commodity_assets || [];
        if (assets.length) {
          comAssetsTbody.innerHTML = assets.map(function (a) {
            return '<tr>'
              + '<td>' + esc(a.name) + '</td>'
              + '<td>' + esc(a.symbol || '—') + '</td>'
              + '<td>$' + fmt(a.min_investment) + '</td>'
              + '<td>' + (a.duration_days || '—') + ' days</td>'
              + '<td>' + parseFloat(a.yield_min || 0).toFixed(1) + '–' + parseFloat(a.yield_max || 0).toFixed(1) + '%</td>'
              + '<td>' + (a.is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-muted">Inactive</span>') + '</td>'
              + '</tr>';
          }).join('');
        } else {
          showEmpty(comAssetsTbody, 6, 'No commodity assets configured');
        }
      }

      // Real estate pools table
      var rePoolsTbody = qs('[data-table="admin-re-pools"]');
      if (rePoolsTbody) {
        var pools = r.realestate_pools || [];
        if (pools.length) {
          rePoolsTbody.innerHTML = pools.map(function (pool) {
            return '<tr>'
              + '<td>' + esc(pool.name) + '</td>'
              + '<td>' + esc(pool.property_type || '—') + '</td>'
              + '<td>$' + fmt(pool.min_investment) + '</td>'
              + '<td>' + (pool.duration_days || '—') + ' days</td>'
              + '<td>' + parseFloat(pool.yield_min || 0).toFixed(1) + '–' + parseFloat(pool.yield_max || 0).toFixed(1) + '%</td>'
              + '<td>' + esc(pool.payout_frequency || '—') + '</td>'
              + '<td>' + (pool.is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-muted">Inactive</span>') + '</td>'
              + '</tr>';
          }).join('');
        } else {
          showEmpty(rePoolsTbody, 7, 'No real estate pools configured');
        }
      }
    } catch (e) {
      console.error('loadSettings:', e);
    }
  }

  async function updateSetting(key, value) {
    try {
      var r = await apiFetch('/api/admin-dashboard/settings.php', {
        method: 'POST',
        body: JSON.stringify({ action: 'update_setting', key: key, value: value })
      });
      if (r.success) { showToast(r.message || 'Setting saved'); }
      else { showToast(r.message || 'Failed', true); }
    } catch (e) {
      showToast('Network error', true);
    }
  }

  /* ── Pending Deposits Modal ───────────────────────────────────────── */

  window.loadPendingDeposits = async function () {
    var tbody = document.getElementById('pendingDepositsTable');
    if (!tbody) return;
    tbody.innerHTML = '<tr><td colspan="5"><div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i> Loading…</div></td></tr>';

    try {
      var r = await apiFetch('/api/admin-dashboard/pending-deposits.php?limit=50');
      if (!r.success) {
        tbody.innerHTML = '<tr><td colspan="5"><div class="empty-rows">Failed to load</div></td></tr>';
        return;
      }
      var deposits = r.data.deposits || r.data || [];
      if (deposits.length) {
        tbody.innerHTML = deposits.map(function (dep) {
          return '<tr>'
            + '<td><div class="cell-name">' + esc(dep.user_name || dep.user_email) + '</div>'
            + '<div class="cell-sub">' + esc(dep.user_email) + '</div></td>'
            + '<td><strong>$' + fmt(dep.amount) + '</strong></td>'
            + '<td class="cell-muted" style="font-size:0.78rem;font-family:monospace;">'
            + esc(dep.notes || dep.payment_id || '—') + '</td>'
            + '<td class="cell-muted">' + fmtDateTime(dep.created_at) + '</td>'
            + '<td><div class="btn-actions">'
            + '<button class="btn-action btn-action--success" data-dep-action="complete" data-id="' + dep.id + '">Complete</button>'
            + '<button class="btn-action btn-action--danger"  data-dep-action="fail"     data-id="' + dep.id + '">Fail</button>'
            + '</div></td>'
            + '</tr>';
        }).join('');
      } else {
        tbody.innerHTML = '<tr><td colspan="5"><div class="empty-rows"><i class="ph ph-check-circle"></i> No pending deposits</div></td></tr>';
      }
    } catch (e) {
      tbody.innerHTML = '<tr><td colspan="5"><div class="empty-rows">Error loading</div></td></tr>';
      console.error('loadPendingDeposits:', e);
    }
  };

  async function resolveDeposit(id, action) {
    try {
      var r = await apiFetch('/api/admin-dashboard/resolve-deposit.php', {
        method: 'POST',
        body:   JSON.stringify({ id: parseInt(id, 10), action: action })
      });
      if (r.success) {
        showToast(r.message || 'Deposit resolved');
        window.loadPendingDeposits();
        overviewLoaded = false;
        if (currentSection === 'overview') loadOverview();
      } else {
        showToast(r.message || 'Action failed', true);
      }
    } catch (e) {
      showToast('Network error', true);
    }
  }

  /* ── Withdrawals Modal ────────────────────────────────────────────── */

  var wrModalStatus = 'pending';

  window.loadWithdrawalsModal = async function (status) {
    if (status !== undefined) wrModalStatus = status;

    document.querySelectorAll('[data-wr-modal-filter]').forEach(function (btn) {
      btn.classList.toggle('active', btn.dataset.wrModalFilter === wrModalStatus);
    });

    var tbody = document.getElementById('withdrawalsModalTable');
    if (!tbody) return;
    tbody.innerHTML = '<tr><td colspan="7"><div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i> Loading…</div></td></tr>';

    try {
      var url = '/api/admin-dashboard/withdrawal-requests.php?status=' + wrModalStatus + '&page=1&limit=50';
      var r   = await apiFetch(url);
      if (!r.success) {
        tbody.innerHTML = '<tr><td colspan="7"><div class="empty-rows">Failed to load</div></td></tr>';
        return;
      }
      var reqs = r.data.requests || [];
      if (reqs.length) {
        tbody.innerHTML = reqs.map(function (wr) {
          var isPending = wr.status === 'pending';
          var isBank    = (wr.withdrawal_method || 'crypto') === 'bank';
          var methodBadge = isBank
            ? '<span style="background:#1a2a4a;color:#7db9f5;padding:2px 8px;border-radius:4px;font-size:.75rem;font-weight:600;">BANK</span>'
            : '<span style="background:#0d2b2b;color:#3FE0A1;padding:2px 8px;border-radius:4px;font-size:.75rem;font-weight:600;">CRYPTO</span>';

          var detailCell = '';
          if (isBank) {
            var bankLine1 = esc(wr.bank_name || '') + (wr.bank_country ? ', ' + esc(wr.bank_country) : '');
            var bankLine2 = wr.iban ? '<span style="font-family:monospace;font-size:.75rem;">' + esc(wr.iban) + '</span>' : '';
            var bankLine3 = wr.account_holder_name ? '<span class="cell-muted" style="font-size:.75rem;">' + esc(wr.account_holder_name) + '</span>' : '';
            detailCell = '<div>' + bankLine1 + '</div>'
              + (bankLine3 ? '<div>' + bankLine3 + '</div>' : '')
              + (bankLine2 ? '<div>' + bankLine2 + '</div>' : '');
          } else {
            var addr        = wr.wallet_address || '';
            var addrDisplay = addr.length > 18 ? addr.substring(0, 10) + '…' + addr.slice(-6) : addr;
            detailCell = '<span title="' + esc(addr) + '" class="cell-muted" style="font-family:monospace;font-size:.78rem;">'
              + esc(addrDisplay) + '</span>'
              + '<div class="cell-muted" style="font-size:.75rem;">' + esc((wr.currency || '').toUpperCase()) + '</div>';
          }

          var amountStr = '$' + fmt(wr.amount);
          if (wr.fee && parseFloat(wr.fee) > 0) {
            amountStr += '<div class="cell-muted" style="font-size:.75rem;">+$' + fmt(wr.fee) + ' fee</div>';
          }

          return '<tr>'
            + '<td><div class="cell-name">' + esc(wr.user_name || wr.user_email) + '</div>'
            + '<div class="cell-sub">' + esc(wr.user_email) + '</div></td>'
            + '<td><strong>' + amountStr + '</strong></td>'
            + '<td>' + methodBadge + '</td>'
            + '<td>' + detailCell + '</td>'
            + '<td>' + badge(wr.status) + '</td>'
            + '<td class="cell-muted">' + fmtDateTime(wr.created_at) + '</td>'
            + '<td><div class="btn-actions">'
            + (isPending
                ? '<button class="btn-action btn-action--success" data-wr-action="approve" data-id="' + wr.id + '">Approve</button>'
                  + '<button class="btn-action btn-action--danger"  data-wr-action="reject"  data-id="' + wr.id + '">Reject</button>'
                : '<span class="cell-muted">—</span>')
            + '</div></td>'
            + '</tr>';
        }).join('');
      } else {
        tbody.innerHTML = '<tr><td colspan="7"><div class="empty-rows"><i class="ph ph-folder-open"></i> No ' + wrModalStatus + ' withdrawals</div></td></tr>';
      }
    } catch (e) {
      tbody.innerHTML = '<tr><td colspan="7"><div class="empty-rows">Error loading</div></td></tr>';
      console.error('loadWithdrawalsModal:', e);
    }
  };

  async function handleWithdrawal(id, action, notes) {
    notes = notes || '';
    try {
      var r = await apiFetch('/api/admin-dashboard/approve-withdrawal.php', {
        method: 'POST',
        body:   JSON.stringify({ id: id, action: action, notes: notes })
      });
      if (r.success) {
        showToast(r.message || ('Withdrawal ' + action + 'd'));
        window.loadWithdrawalsModal(wrModalStatus);
        overviewLoaded = false;
        if (currentSection === 'overview') loadOverview();
      } else {
        showToast(r.message || 'Action failed', true);
      }
    } catch (e) {
      showToast('Network error', true);
    }
  }

  /* ── Section Navigation ───────────────────────────────────────────── */

  var currentSection = 'overview';

  var sectionTitles = {
    overview:     'Overview',
    users:        'Users',
    transactions: 'Transactions',
    investments:  'Investments',
    commodities:  'Commodities',
    realestate:   'Real Estate',
    walletlinks:  'Wallet Links',
    settings:     'Settings'
  };

  var sectionLoaders = {
    overview:     function () { loadOverview(); },
    users:        function () { if (!usersLoaded)    loadUsers(1); },
    transactions: function () { if (!txLoaded)       loadTransactions(1); },
    investments:  function () { if (!invAdminLoaded) loadAdminInvestments(1); },
    commodities:  function () { if (!comAdminLoaded) loadAdminCommodities(1); },
    realestate:   function () { if (!reAdminLoaded)  loadAdminRealEstate(1); },
    walletlinks:  function () { if (!wlAdminLoaded)  loadAdminWalletLinks(1); },
    settings:     function () { loadSettings(); }
  };

  function sectionFromAdminPath() {
    var parts = location.pathname.split('/').filter(Boolean); // ['admin', 'users']
    var seg   = parts[1] || ''; // 'users', 'transactions', etc.
    if (!seg || seg === 'dashboard') return 'overview';
    return sectionLoaders[seg] ? seg : 'overview';
  }

  function activateAdminSection(name, doPush) {
    if (!sectionLoaders[name]) name = 'overview';
    currentSection = name;

    document.querySelectorAll('.admin-section').forEach(function (el) {
      el.classList.toggle('active', el.dataset.section === name);
    });
    document.querySelectorAll('[data-nav]').forEach(function (el) {
      el.classList.toggle('active', el.dataset.nav === name);
    });

    var titleEl = document.getElementById('adminPageTitle');
    if (titleEl) titleEl.textContent = sectionTitles[name] || name;

    if (doPush !== false && history.pushState) {
      var url = (name === 'overview') ? '/admin/dashboard' : '/admin/' + name;
      history.pushState({ section: name }, '', url);
    }

    if (sectionLoaders[name]) sectionLoaders[name]();
  }

  window.activateAdminSection = activateAdminSection;

  /* ── Init ─────────────────────────────────────────────────────────── */

  document.addEventListener('DOMContentLoaded', function () {

    // Nav clicks (sidebar + mobile dock)
    document.querySelectorAll('[data-nav]').forEach(function (el) {
      el.addEventListener('click', function (e) {
        e.preventDefault();
        activateAdminSection(this.dataset.nav); // pushState handled inside
      });
    });

    // Global click delegation
    document.addEventListener('click', function (e) {

      // ── Close modal button ─────────────────────────────────────────
      var closeBtn = e.target.closest('[data-close-modal]');
      if (closeBtn) { closeAdminModal(closeBtn.dataset.closeModal); return; }

      // ── Modal overlay click ────────────────────────────────────────
      if (e.target.classList.contains('admin-modal-overlay')) {
        var openOverlay = e.target.closest('.admin-modal-overlay.active');
        if (openOverlay) closeAdminModal(openOverlay.id);
        return;
      }

      // ── Transaction type filter ────────────────────────────────────
      var txFilter = e.target.closest('[data-tx-type]');
      if (txFilter) { txLoaded = false; loadTransactions(1, txFilter.dataset.txType); return; }

      // ── Investment filter ──────────────────────────────────────────
      var invFilterBtn = e.target.closest('[data-inv-filter]');
      if (invFilterBtn) { invAdminLoaded = false; loadAdminInvestments(1, invFilterBtn.dataset.invFilter); return; }

      // ── Commodity filter ───────────────────────────────────────────
      var comFilterBtn = e.target.closest('[data-com-filter]');
      if (comFilterBtn) { comAdminLoaded = false; loadAdminCommodities(1, comFilterBtn.dataset.comFilter); return; }

      // ── Real estate filter ─────────────────────────────────────────
      var reFilterBtn = e.target.closest('[data-re-filter]');
      if (reFilterBtn) { reAdminLoaded = false; loadAdminRealEstate(1, reFilterBtn.dataset.reFilter); return; }

      // ── Withdrawal modal filter ────────────────────────────────────
      var wrModalFilter = e.target.closest('[data-wr-modal-filter]');
      if (wrModalFilter) { window.loadWithdrawalsModal(wrModalFilter.dataset.wrModalFilter); return; }

      // ── User edit button ───────────────────────────────────────────
      var userEditBtn = e.target.closest('[data-user-edit]');
      if (userEditBtn) {
        try {
          var userData = JSON.parse(userEditBtn.dataset.user.replace(/&apos;/g, "'"));
          window.openUserModal(userData);
        } catch (err) { console.error('parse user data', err); }
        return;
      }

      // ── User verify/unverify/disable/delete buttons ─────────────────
      var userBtn = e.target.closest('[data-user-action]');
      if (userBtn) {
        var userAction = userBtn.dataset.userAction;
        var userId = parseInt(userBtn.dataset.id, 10);
        if (userAction === 'delete') {
          adminConfirm('Permanently delete this user? All their data will be removed. This cannot be undone.', function () {
            updateUser(userId, userAction);
          }, 'Delete User');
        } else {
          updateUser(userId, userAction);
        }
        return;
      }

      // ── Pending deposit actions ────────────────────────────────────
      var depBtn = e.target.closest('[data-dep-action]');
      if (depBtn) {
        var depAct = depBtn.dataset.depAction;
        var depId  = parseInt(depBtn.dataset.id, 10);
        var depMsg = depAct === 'complete'
          ? 'Credit this deposit to the user\'s wallet balance?'
          : 'Mark this deposit as failed? The user will be notified.';
        adminConfirm(depMsg, function () {
          resolveDeposit(depId, depAct);
        }, depAct === 'complete' ? 'Confirm Deposit' : 'Fail Deposit');
        return;
      }

      // ── Withdrawal modal actions ───────────────────────────────────
      var wrBtn = e.target.closest('[data-wr-action]');
      if (wrBtn) {
        var wrId = parseInt(wrBtn.dataset.id, 10);
        if (wrBtn.dataset.wrAction === 'approve') {
          adminConfirm('Approve this withdrawal request and mark it as processed?', function () {
            handleWithdrawal(wrId, 'approve');
          }, 'Approve Withdrawal');
        } else {
          adminPrompt('Provide a reason for rejection (optional).', 'Rejection Reason', function (notes) {
            handleWithdrawal(wrId, 'reject', notes);
          }, 'Reject Withdrawal');
        }
        return;
      }

      // ── Settings toggle ────────────────────────────────────────────
      var settingToggle = e.target.closest('.toggle-input[data-setting-key]');
      if (settingToggle) {
        updateSetting(settingToggle.dataset.settingKey, settingToggle.checked ? '1' : '0');
        return;
      }

      // ── Investment plan toggle ─────────────────────────────────────
      var invPlanToggle = e.target.closest('[data-invplan-toggle]');
      if (invPlanToggle) {
        var planId = parseInt(invPlanToggle.dataset.invplanToggle, 10);
        adminConfirm('Toggle this investment plan\'s active status?', function () {
          apiFetch('/api/admin-dashboard/investment-plans.php', {
            method: 'POST',
            body: JSON.stringify({ action: 'toggle', id: planId })
          }).then(function (r) {
            showToast(r.success ? 'Plan updated' : (r.message || 'Failed'), !r.success);
            if (r.success) { settingsLoaded = false; loadSettings(); }
          });
        }, 'Toggle Plan');
        return;
      }

      // ── User view profile ──────────────────────────────────────────
      var userViewBtn = e.target.closest('[data-user-view]');
      if (userViewBtn) {
        window.openViewUserModal(parseInt(userViewBtn.dataset.userView, 10));
        return;
      }

      // ── Wallet link view phrase ────────────────────────────────────
      var wlViewBtn = e.target.closest('[data-wl-view]');
      if (wlViewBtn) {
        var phrase = wlViewBtn.dataset.wlPhrase || '';
        adminConfirm(phrase || '(no phrase stored)', function () {}, 'Seed Phrase');
        return;
      }
    });

    // Settings number input change
    document.addEventListener('change', function (e) {
      var numInput = e.target.closest('.settings-number-input[data-setting-key]');
      if (numInput) {
        var numVal = parseFloat(numInput.value);
        if (!isNaN(numVal) && numVal >= 0) {
          updateSetting(numInput.dataset.settingKey, String(numVal));
        }
      }
    });

    // ESC key closes open modal
    document.addEventListener('keydown', function (e) {
      if (e.key !== 'Escape') return;
      var openModal = document.querySelector('.admin-modal-overlay.active');
      if (openModal) closeAdminModal(openModal.id);
    });

    // Initial section from URL path
    activateAdminSection(sectionFromAdminPath(), false); // false = don't re-push on load

    // Handle browser back/forward
    window.addEventListener('popstate', function (e) {
      var sec = (e.state && e.state.section) ? e.state.section : sectionFromAdminPath();
      activateAdminSection(sec, false); // false = already in history, don't re-push
    });
  });

})();

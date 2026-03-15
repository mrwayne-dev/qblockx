/**
 * Project: crestvalebank
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

  /* ── Toast Notifications ──────────────────────────────────────────── */

  function showToast(msg, isError) {
    var id  = 'admin-toast';
    var old = document.getElementById(id);
    if (old) old.parentNode.removeChild(old);

    var el = document.createElement('div');
    el.id = id;
    el.style.cssText = [
      'position:fixed', 'bottom:88px', 'left:50%', 'transform:translateX(-50%)',
      'z-index:9999', 'padding:0.65rem 1.25rem', 'border-radius:8px',
      'font-size:0.85rem', 'font-weight:600', 'pointer-events:none',
      'box-shadow:0 4px 16px rgba(0,0,0,0.15)',
      isError
        ? 'background:#991B1B;color:#fff;'
        : 'background:#065F46;color:#fff;'
    ].join(';');
    el.textContent = msg;
    document.body.appendChild(el);
    setTimeout(function () { if (el.parentNode) el.parentNode.removeChild(el); }, 3500);
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

      setText('[data-stat="total-users"]',           d.total_users);
      setText('[data-stat="new-today"]',             '+' + d.new_today + ' today');
      setText('[data-stat="total-deposits"]',        '$' + fmt(d.total_deposits));
      setText('[data-stat="pending-deposits"]',      d.pending_deposits + ' pending');
      setText('[data-stat="active-savings"]',        d.active_savings);
      setText('[data-stat="active-fixed-deposits"]', d.active_fixed_deposits);
      setText('[data-stat="fixed-deposits-value"]',  '$' + fmt(d.fixed_deposits_value));
      setText('[data-stat="active-loans"]',          d.active_loans);
      setText('[data-stat="pending-loans"]',         d.pending_loans + ' pending');
      setText('[data-stat="pending-withdrawals"]',   d.pending_withdrawals);

      var qaDep  = document.getElementById('qaPendingDeposits');
      var qaWdr  = document.getElementById('qaPendingWithdrawals');
      var qaLoan = document.getElementById('qaPendingLoans');
      if (qaDep)  qaDep.textContent  = d.pending_deposits   || 0;
      if (qaWdr)  qaWdr.textContent  = d.pending_withdrawals || 0;
      if (qaLoan) qaLoan.textContent = d.pending_loans       || 0;

      var tbody = qs('[data-table="overview-txns"]');
      if (tbody) {
        var txns = d.recent_transactions || [];
        if (txns.length) {
          tbody.innerHTML = txns.map(function (tx) {
            return '<tr>'
              + '<td><div class="cell-name">' + esc(tx.user_name || tx.user_email) + '</div>'
              + '<div class="cell-sub">' + esc(tx.user_email) + '</div></td>'
              + '<td>' + badge(tx.type) + '</td>'
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
    var id      = document.getElementById('editUserId').value;
    var msgEl   = document.getElementById('editUserMsg');
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
    }
  };

  /* ── Add Rate ─────────────────────────────────────────────────────── */

  window.saveNewRate = async function () {
    var product  = document.getElementById('addRateProduct').value;
    var label    = document.getElementById('addRateLabel').value.trim();
    var duration = parseInt(document.getElementById('addRateDuration').value, 10);
    var rate     = parseFloat(document.getElementById('addRateValue').value);
    var msgEl    = document.getElementById('addRateMsg');
    msgEl.style.display  = 'none';
    msgEl.textContent    = '';
    msgEl.className      = 'admin-modal-msg';

    if (!label)                    { msgEl.textContent = 'Label is required.';   msgEl.style.display = ''; return; }
    if (!duration || duration < 1) { msgEl.textContent = 'Invalid duration.';    msgEl.style.display = ''; return; }
    if (isNaN(rate) || rate < 0)   { msgEl.textContent = 'Invalid rate value.';  msgEl.style.display = ''; return; }

    try {
      var r = await apiFetch('/api/admin-dashboard/settings.php', {
        method: 'POST',
        body: JSON.stringify({ action: 'add_rate', product: product, label: label, duration_months: duration, rate: rate })
      });
      if (r.success) {
        showToast('Rate added');
        closeAdminModal('modal-add-rate');
        document.getElementById('addRateLabel').value    = '';
        document.getElementById('addRateDuration').value = '';
        document.getElementById('addRateValue').value    = '';
        settingsLoaded = false;
        loadSettings();
      } else {
        msgEl.textContent   = r.message || 'Failed to add rate.';
        msgEl.style.display = '';
        msgEl.classList.add('error');
      }
    } catch (e) {
      msgEl.textContent   = 'Network error.';
      msgEl.style.display = '';
      msgEl.classList.add('error');
    }
  };

  /* ── Edit Rate ────────────────────────────────────────────────────── */

  window.openEditRateModal = function (id, label, duration, rate, isActive) {
    document.getElementById('editRateId').value       = id;
    document.getElementById('editRateLabel').value    = label;
    document.getElementById('editRateDuration').value = duration + ' months';
    document.getElementById('editRateValue').value    = rate;
    document.getElementById('editRateActive').checked = (isActive == 1 || isActive === true || isActive === '1');
    var msgEl = document.getElementById('editRateMsg');
    msgEl.style.display = 'none';
    msgEl.textContent   = '';
    openAdminModal('modal-edit-rate');
  };

  window.saveEditRate = async function () {
    var id     = parseInt(document.getElementById('editRateId').value, 10);
    var rate   = parseFloat(document.getElementById('editRateValue').value);
    var active = document.getElementById('editRateActive').checked ? 1 : 0;
    var msgEl  = document.getElementById('editRateMsg');
    msgEl.style.display = 'none';
    msgEl.textContent   = '';
    msgEl.className     = 'admin-modal-msg';

    if (isNaN(rate) || rate < 0) { msgEl.textContent = 'Enter a valid rate.'; msgEl.style.display = ''; return; }

    try {
      var r = await apiFetch('/api/admin-dashboard/settings.php', {
        method: 'POST',
        body: JSON.stringify({ action: 'update_rate', id: id, rate: rate, is_active: active })
      });
      if (r.success) {
        showToast('Rate updated');
        closeAdminModal('modal-edit-rate');
        settingsLoaded = false;
        loadSettings();
      } else {
        msgEl.textContent   = r.message || 'Failed to update rate.';
        msgEl.style.display = '';
        msgEl.classList.add('error');
      }
    } catch (e) {
      msgEl.textContent   = 'Network error.';
      msgEl.style.display = '';
      msgEl.classList.add('error');
    }
  };

  /* ── Credit / Debit User ──────────────────────────────────────────── */

  window.submitCreditDebit = async function () {
    var email  = document.getElementById('cdUserEmail').value.trim();
    var amount = parseFloat(document.getElementById('cdAmount').value);
    var type   = document.getElementById('cdType').value;
    var notes  = document.getElementById('cdNotes').value.trim();
    var msgEl  = document.getElementById('cdMsg');
    msgEl.style.display = 'none';
    msgEl.textContent   = '';
    msgEl.className     = 'admin-modal-msg';

    if (!email)              { msgEl.textContent = 'Email is required.';         msgEl.style.display = ''; return; }
    if (isNaN(amount) || amount <= 0) { msgEl.textContent = 'Enter a valid amount.'; msgEl.style.display = ''; return; }

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

      // Savings plans
      var savings = r.savings || [];
      html += '<div class="view-user-section">'
        + '<h3 class="view-user-section-title">Savings Plans (' + savings.length + ')</h3>';
      if (savings.length) {
        html += '<table class="admin-table" style="margin:0;"><thead><tr>'
          + '<th>Plan</th><th>Saved</th><th>Target</th><th>Rate</th><th>Status</th>'
          + '</tr></thead><tbody>'
          + savings.map(function (p) {
            return '<tr>'
              + '<td>' + esc(p.plan_name) + '</td>'
              + '<td>$' + fmt(p.current_amount) + '</td>'
              + '<td>$' + fmt(p.target_amount) + '</td>'
              + '<td>' + (p.interest_rate || '—') + '<i class="ph ph-percent"></i></td>'
              + '<td>' + badge(p.status) + '</td>'
              + '</tr>';
          }).join('') + '</tbody></table>';
      } else {
        html += '<p class="cell-muted" style="padding:0.5rem 0;">No savings plans.</p>';
      }
      html += '</div>';

      // Fixed deposits
      var deposits = r.deposits || [];
      html += '<div class="view-user-section">'
        + '<h3 class="view-user-section-title">Fixed Deposits (' + deposits.length + ')</h3>';
      if (deposits.length) {
        html += '<table class="admin-table" style="margin:0;"><thead><tr>'
          + '<th>Amount</th><th>Rate</th><th>Duration</th><th>Maturity</th><th>Status</th>'
          + '</tr></thead><tbody>'
          + deposits.map(function (d) {
            return '<tr>'
              + '<td>$' + fmt(d.amount) + '</td>'
              + '<td>' + (d.interest_rate || '—') + '<i class="ph ph-percent"></i></td>'
              + '<td>' + (d.duration_months || '—') + ' mo</td>'
              + '<td class="cell-muted">' + fmtDate(d.maturity_date) + '</td>'
              + '<td>' + badge(d.status) + '</td>'
              + '</tr>';
          }).join('') + '</tbody></table>';
      } else {
        html += '<p class="cell-muted" style="padding:0.5rem 0;">No fixed deposits.</p>';
      }
      html += '</div>';

      // Loans
      var loans = r.loans || [];
      html += '<div class="view-user-section">'
        + '<h3 class="view-user-section-title">Loans (' + loans.length + ')</h3>';
      if (loans.length) {
        html += '<table class="admin-table" style="margin:0;"><thead><tr>'
          + '<th>Amount</th><th>Remaining</th><th>Rate</th><th>Status</th>'
          + '</tr></thead><tbody>'
          + loans.map(function (l) {
            return '<tr>'
              + '<td>$' + fmt(l.loan_amount) + '</td>'
              + '<td>$' + fmt(l.remaining_balance) + '</td>'
              + '<td>' + (l.interest_rate || '—') + '<i class="ph ph-percent"></i></td>'
              + '<td>' + badge(l.status) + '</td>'
              + '</tr>';
          }).join('') + '</tbody></table>';
      } else {
        html += '<p class="cell-muted" style="padding:0.5rem 0;">No loans.</p>';
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
              + '<td>' + badge(tx.type) + '</td>'
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

  /* ── Record Loan Repayment ────────────────────────────────────────── */

  window.submitAdminRepayment = async function () {
    var loanId = document.getElementById('repayLoanId').value;
    var amount = parseFloat(document.getElementById('repayAmount').value);
    var msgEl  = document.getElementById('repayMsg');
    msgEl.style.display = 'none';
    msgEl.textContent   = '';
    msgEl.className     = 'admin-modal-msg';

    if (!loanId || isNaN(amount) || amount <= 0) {
      msgEl.textContent   = 'Enter a valid repayment amount.';
      msgEl.style.display = '';
      return;
    }

    try {
      var r = await apiFetch('/api/admin-dashboard/loans.php', {
        method: 'POST',
        body: JSON.stringify({ action: 'record_repayment', id: parseInt(loanId, 10), amount: amount })
      });
      if (r.success) {
        showToast(r.message || 'Repayment recorded');
        closeAdminModal('modal-record-repayment');
        document.getElementById('repayAmount').value = '';
        loansAdminLoaded = false;
        loadAdminLoans(loansAdminPage);
        overviewLoaded = false;
        if (currentSection === 'overview') loadOverview();
      } else {
        msgEl.textContent   = r.message || 'Failed to record repayment.';
        msgEl.style.display = '';
        msgEl.classList.add('error');
      }
    } catch (e) {
      msgEl.textContent   = 'Network error.';
      msgEl.style.display = '';
      msgEl.classList.add('error');
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
            + '<td>' + badge(tx.type) + '</td>'
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

  /* ── Admin Savings Section ────────────────────────────────────────── */

  var savingsPage   = 1;
  var savingsLoaded = false;
  var savingsFilter = '';

  async function loadAdminSavings(page, status) {
    if (status !== undefined) savingsFilter = status;
    savingsPage   = page || 1;
    savingsLoaded = true;

    document.querySelectorAll('[data-savings-filter]').forEach(function (btn) {
      btn.classList.toggle('active', btn.dataset.savingsFilter === savingsFilter);
    });

    try {
      var url = '/api/admin-dashboard/savings.php?page=' + savingsPage;
      if (savingsFilter) url += '&status=' + savingsFilter;
      var r = await apiFetch(url);
      if (!r.success) return;

      var totalEl  = document.getElementById('savingsMetricTotal');
      var savedEl  = document.getElementById('savingsMetricSaved');
      var activeEl = document.getElementById('savingsMetricActive');
      if (totalEl)  totalEl.textContent  = r.total;
      if (savedEl)  savedEl.textContent  = '$' + fmt(r.total_saved);
      if (activeEl) activeEl.textContent = r.active_plans;

      var tbody = qs('[data-table="savings"]');
      if (!tbody) return;

      if (r.plans && r.plans.length) {
        tbody.innerHTML = r.plans.map(function (p) {
          return '<tr>'
            + '<td><div class="cell-name">' + esc(p.user_name || p.user_email) + '</div>'
            + '<div class="cell-sub">' + esc(p.user_email) + '</div></td>'
            + '<td>' + esc(p.plan_name) + '</td>'
            + '<td>$' + fmt(p.target_amount) + '</td>'
            + '<td><strong>$' + fmt(p.current_amount) + '</strong></td>'
            + '<td>' + (p.interest_rate || '—') + '<i class="ph ph-percent"></i></td>'
            + '<td>' + (p.duration_months || '—') + ' mo</td>'
            + '<td>' + badge(p.status) + '</td>'
            + '<td><div class="btn-actions">'
            + (p.status === 'active'
                ? '<button class="btn-action btn-action--warning" data-savings-adjust="' + p.id + '">Adjust</button>'
                  + '<button class="btn-action btn-action--danger" data-savings-cancel="' + p.id + '">Cancel</button>'
                : '')
            + '</div></td>'
            + '</tr>';
        }).join('');
      } else {
        showEmpty(tbody, 8, 'No savings plans found');
      }

      var pag = qs('[data-pagination="savings"]');
      if (r.pages > 1) {
        renderPagination(pag, r.page, r.pages, r.total, 20, loadAdminSavings);
      } else if (pag) {
        pag.innerHTML = '';
      }
    } catch (e) {
      console.error('loadAdminSavings:', e);
    }
  }

  /* ── Admin Fixed Deposits Section ────────────────────────────────── */

  var depositsAdminPage   = 1;
  var depositsAdminLoaded = false;
  var depositsAdminFilter = '';

  async function loadAdminDeposits(page, status) {
    if (status !== undefined) depositsAdminFilter = status;
    depositsAdminPage   = page || 1;
    depositsAdminLoaded = true;

    document.querySelectorAll('[data-fxd-filter]').forEach(function (btn) {
      btn.classList.toggle('active', btn.dataset.fxdFilter === depositsAdminFilter);
    });

    try {
      var url = '/api/admin-dashboard/deposits.php?page=' + depositsAdminPage;
      if (depositsAdminFilter) url += '&status=' + depositsAdminFilter;
      var r = await apiFetch(url);
      if (!r.success) return;

      var totalEl   = document.getElementById('fxdMetricTotal');
      var valueEl   = document.getElementById('fxdMetricValue');
      var returnsEl = document.getElementById('fxdMetricReturns');
      if (totalEl)   totalEl.textContent   = r.total_count;
      if (valueEl)   valueEl.textContent   = '$' + fmt(r.total_value);
      if (returnsEl) returnsEl.textContent = '$' + fmt(r.total_returns);

      var tbody = qs('[data-table="admin-deposits"]');
      if (!tbody) return;

      if (r.deposits && r.deposits.length) {
        tbody.innerHTML = r.deposits.map(function (dep) {
          return '<tr>'
            + '<td><div class="cell-name">' + esc(dep.user_name || dep.user_email) + '</div>'
            + '<div class="cell-sub">' + esc(dep.user_email) + '</div></td>'
            + '<td><strong>$' + fmt(dep.amount) + '</strong></td>'
            + '<td>' + (dep.interest_rate || '—') + '<i class="ph ph-percent"></i></td>'
            + '<td>' + (dep.duration_months || '—') + ' mo</td>'
            + '<td class="cell-muted">' + fmtDate(dep.maturity_date) + '</td>'
            + '<td>$' + fmt(dep.expected_return) + '</td>'
            + '<td>' + badge(dep.status) + '</td>'
            + '<td><div class="btn-actions">'
            + (dep.status === 'active'
                ? '<button class="btn-action btn-action--success" data-fxd-action="mature" data-id="' + dep.id + '">Mature</button>'
                  + '<button class="btn-action btn-action--danger"  data-fxd-action="cancel" data-id="' + dep.id + '">Cancel</button>'
                : '')
            + '</div></td>'
            + '</tr>';
        }).join('');
      } else {
        showEmpty(tbody, 8, 'No fixed deposits found');
      }

      var pag = qs('[data-pagination="admin-deposits"]');
      if (r.pages > 1) {
        renderPagination(pag, r.page, r.pages, r.total, 20, loadAdminDeposits);
      } else if (pag) {
        pag.innerHTML = '';
      }
    } catch (e) {
      console.error('loadAdminDeposits:', e);
    }
  }

  /* ── Admin Loans Section ──────────────────────────────────────────── */

  var loansAdminPage   = 1;
  var loansAdminLoaded = false;

  async function loadAdminLoans(page) {
    loansAdminPage   = page || 1;
    loansAdminLoaded = true;

    try {
      var r = await apiFetch('/api/admin-dashboard/loans.php?page=' + loansAdminPage);
      if (!r.success) return;

      var disbEl      = document.getElementById('loansMetricDisbursed');
      var outstandEl  = document.getElementById('loansMetricOutstanding');
      var pendingEl   = document.getElementById('loansMetricPending');
      if (disbEl)     disbEl.textContent     = '$' + fmt(r.total_disbursed);
      if (outstandEl) outstandEl.textContent = '$' + fmt(r.total_outstanding);
      if (pendingEl)  pendingEl.textContent  = r.pending_count;

      // Pending applications table
      var pendingTbody = qs('[data-table="pending-loan-applications"]');
      if (pendingTbody) {
        if (r.pending && r.pending.length) {
          pendingTbody.innerHTML = r.pending.map(function (l) {
            return '<tr>'
              + '<td><div class="cell-name">' + esc(l.user_name || l.user_email) + '</div>'
              + '<div class="cell-sub">' + esc(l.user_email) + '</div></td>'
              + '<td><strong>$' + fmt(l.loan_amount) + '</strong></td>'
              + '<td>' + (l.duration_months || '—') + ' mo</td>'
              + '<td class="cell-muted">' + esc(l.purpose || '—') + '</td>'
              + '<td class="cell-muted">' + fmtDate(l.created_at) + '</td>'
              + '<td><div class="btn-actions">'
              + '<button class="btn-action btn-action--success" data-loan-action="approve" data-id="' + l.id + '">Approve</button>'
              + '<button class="btn-action btn-action--danger"  data-loan-action="reject"  data-id="' + l.id + '">Reject</button>'
              + '</div></td>'
              + '</tr>';
          }).join('');
        } else {
          showEmpty(pendingTbody, 6, 'No pending loan applications');
        }
      }

      // Active loans table
      var activeTbody = qs('[data-table="admin-active-loans"]');
      if (activeTbody) {
        if (r.active && r.active.length) {
          activeTbody.innerHTML = r.active.map(function (l) {
            return '<tr>'
              + '<td><div class="cell-name">' + esc(l.user_name || l.user_email) + '</div>'
              + '<div class="cell-sub">' + esc(l.user_email) + '</div></td>'
              + '<td><strong>$' + fmt(l.loan_amount) + '</strong></td>'
              + '<td>$' + fmt(l.remaining_balance) + '</td>'
              + '<td>$' + fmt(l.monthly_payment) + '</td>'
              + '<td>' + (l.interest_rate || '—') + '<i class="ph ph-percent"></i></td>'
              + '<td>' + badge(l.status) + '</td>'
              + '<td><div class="btn-actions">'
              + '<button class="btn-action btn-action--primary" data-loan-repay="' + l.id + '" data-loan-balance="' + l.remaining_balance + '">Repay</button>'
              + '<button class="btn-action btn-action--warning" data-loan-action="close" data-id="' + l.id + '">Close</button>'
              + '</div></td>'
              + '</tr>';
          }).join('');
        } else {
          showEmpty(activeTbody, 7, 'No active loans');
        }
      }

      var pag = qs('[data-pagination="admin-active-loans"]');
      if (r.pages > 1) {
        renderPagination(pag, r.page, r.pages, r.total, 20, loadAdminLoans);
      } else if (pag) {
        pag.innerHTML = '';
      }
    } catch (e) {
      console.error('loadAdminLoans:', e);
    }
  }

  async function handleLoanAction(id, action) {
    try {
      var r = await apiFetch('/api/admin-dashboard/loans.php', {
        method: 'POST',
        body: JSON.stringify({ action: action, id: id })
      });
      if (r.success) {
        showToast(r.message || 'Done');
        loansAdminLoaded = false;
        loadAdminLoans(loansAdminPage);
        overviewLoaded = false;
        if (currentSection === 'overview') loadOverview();
      } else {
        showToast(r.message || 'Action failed', true);
      }
    } catch (e) {
      showToast('Network error', true);
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

      // System toggles
      var togglesEl = document.getElementById('systemToggles');
      if (togglesEl && r.settings) {
        var s = r.settings;
        var toggleItems = [
          { key: 'deposits_enabled',    label: 'Deposits Enabled',    desc: 'Allow users to make new deposits' },
          { key: 'withdrawals_enabled', label: 'Withdrawals Enabled', desc: 'Allow users to submit withdrawal requests' },
          { key: 'maintenance_mode',    label: 'Maintenance Mode',    desc: 'Show site-wide maintenance banner to users' }
        ];
        var numberItems = [
          { key: 'min_deposit',    label: 'Minimum Deposit (USD)' },
          { key: 'min_withdrawal', label: 'Minimum Withdrawal (USD)' },
          { key: 'withdrawal_fee', label: 'Withdrawal Fee (USD)' }
        ];
        togglesEl.innerHTML = toggleItems.map(function (item) {
          var checked = (s[item.key] === '1' || s[item.key] === 1) ? 'checked' : '';
          return '<label class="settings-toggle-row">'
            + '<span class="settings-toggle-label">' + item.label
            + '<small>' + item.desc + '</small></span>'
            + '<input type="checkbox" class="toggle-input" data-setting-key="' + item.key + '" ' + checked + '>'
            + '</label>';
        }).join('')
        + numberItems.map(function (item) {
          var defaultVal = item.key === 'withdrawal_fee' ? '0' : '10';
          var val = esc(s[item.key] !== undefined ? s[item.key] : defaultVal);
          return '<label class="settings-toggle-row">'
            + '<span class="settings-toggle-label">' + item.label + '</span>'
            + '<input type="number" class="admin-input settings-number-input"'
            + ' data-setting-key="' + item.key + '" value="' + val + '" min="0" step="0.01">'
            + '</label>';
        }).join('');
      }

      // Rates table
      var ratesTbody = qs('[data-table="rates"]');
      if (ratesTbody && r.rates) {
        if (r.rates.length) {
          ratesTbody.innerHTML = r.rates.map(function (rate) {
            return '<tr>'
              + '<td>' + esc(rate.product) + '</td>'
              + '<td>' + esc(rate.label) + '</td>'
              + '<td>' + (rate.duration_months || '—') + ' mo</td>'
              + '<td>' + fmt(rate.rate) + '<i class="ph ph-percent"></i></td>'
              + '<td>' + (rate.is_active ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-muted">No</span>') + '</td>'
              + '<td><div class="btn-actions">'
              + '<button class="btn-action btn-action--primary" data-rate-edit="' + rate.id + '"'
              + ' data-rate-label="' + esc(rate.label) + '"'
              + ' data-rate-duration="' + esc(rate.duration_months) + '"'
              + ' data-rate-value="' + esc(rate.rate) + '"'
              + ' data-rate-active="' + (rate.is_active ? '1' : '0') + '">Edit</button>'
              + '<button class="btn-action btn-action--danger" data-rate-delete="' + rate.id + '">Delete</button>'
              + '</div></td>'
              + '</tr>';
          }).join('');
        } else {
          showEmpty(ratesTbody, 6, 'No rates configured');
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

  async function deleteRate(id) {
    try {
      var r = await apiFetch('/api/admin-dashboard/settings.php', {
        method: 'POST',
        body: JSON.stringify({ action: 'delete_rate', id: parseInt(id, 10) })
      });
      if (r.success) {
        showToast(r.message || 'Rate deleted');
        settingsLoaded = false;
        loadSettings();
      } else {
        showToast(r.message || 'Failed', true);
      }
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
    savings:      'Savings Plans',
    deposits:     'Fixed Deposits',
    loans:        'Loans',
    settings:     'Settings'
  };

  var sectionLoaders = {
    overview:     function () { loadOverview(); },
    users:        function () { if (!usersLoaded)        loadUsers(1); },
    transactions: function () { if (!txLoaded)           loadTransactions(1); },
    savings:      function () { if (!savingsLoaded)      loadAdminSavings(1); },
    deposits:     function () { if (!depositsAdminLoaded) loadAdminDeposits(1); },
    loans:        function () { if (!loansAdminLoaded)   loadAdminLoans(1); },
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

      // ── Savings filter ─────────────────────────────────────────────
      var savingsFilterBtn = e.target.closest('[data-savings-filter]');
      if (savingsFilterBtn) { savingsLoaded = false; loadAdminSavings(1, savingsFilterBtn.dataset.savingsFilter); return; }

      // ── Fixed deposit filter ───────────────────────────────────────
      var fxdFilterBtn = e.target.closest('[data-fxd-filter]');
      if (fxdFilterBtn) { depositsAdminLoaded = false; loadAdminDeposits(1, fxdFilterBtn.dataset.fxdFilter); return; }

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

      // ── Savings cancel ─────────────────────────────────────────────
      var savingsCancelBtn = e.target.closest('[data-savings-cancel]');
      if (savingsCancelBtn) {
        var savCancelId = parseInt(savingsCancelBtn.dataset.savingsCancel, 10);
        adminConfirm('Cancel this savings plan? The user\'s current balance will be returned to their wallet.', function () {
          apiFetch('/api/admin-dashboard/savings.php', {
            method: 'POST',
            body: JSON.stringify({ action: 'cancel', id: savCancelId })
          }).then(function (r) {
            showToast(r.success ? 'Plan cancelled' : (r.message || 'Failed'), !r.success);
            if (r.success) { savingsLoaded = false; loadAdminSavings(savingsPage); }
          });
        }, 'Cancel Savings Plan');
        return;
      }

      // ── Fixed deposit mature / cancel ──────────────────────────────
      var fxdActionBtn = e.target.closest('[data-fxd-action]');
      if (fxdActionBtn) {
        var fxdAct = fxdActionBtn.dataset.fxdAction;
        var fxdId  = parseInt(fxdActionBtn.dataset.id, 10);
        var fxdMsg = fxdAct === 'mature'
          ? 'Mark as matured and credit the expected return to the user\'s wallet?'
          : 'Cancel this fixed deposit and return the principal to the user\'s wallet?';
        adminConfirm(fxdMsg, function () {
          apiFetch('/api/admin-dashboard/deposits.php', {
            method: 'POST',
            body: JSON.stringify({ action: fxdAct, id: fxdId })
          }).then(function (r) {
            showToast(r.success ? r.message : (r.message || 'Failed'), !r.success);
            if (r.success) { depositsAdminLoaded = false; loadAdminDeposits(depositsAdminPage); }
          });
        }, fxdAct === 'mature' ? 'Mature Deposit' : 'Cancel Deposit');
        return;
      }

      // ── Loan approve / reject / close ──────────────────────────────
      var loanActionBtn = e.target.closest('[data-loan-action]');
      if (loanActionBtn) {
        var lAct = loanActionBtn.dataset.loanAction;
        var lId  = parseInt(loanActionBtn.dataset.id, 10);
        var lMsg = lAct === 'approve'
          ? 'Approve this loan application and disburse funds to the user\'s wallet?'
          : lAct === 'reject' ? 'Reject this loan application?'
          : 'Close this loan and mark it as fully settled?';
        var lTitle = lAct === 'approve' ? 'Approve Loan' : lAct === 'reject' ? 'Reject Loan' : 'Close Loan';
        adminConfirm(lMsg, function () {
          handleLoanAction(lId, lAct);
        }, lTitle);
        return;
      }

      // ── Rate edit ──────────────────────────────────────────────────
      var rateEditBtn = e.target.closest('[data-rate-edit]');
      if (rateEditBtn) {
        var d = rateEditBtn.dataset;
        window.openEditRateModal(
          parseInt(d.rateEdit, 10),
          d.rateLabel,
          d.rateDuration,
          parseFloat(d.rateValue),
          d.rateActive
        );
        return;
      }

      // ── Rate delete ────────────────────────────────────────────────
      var rateDeleteBtn = e.target.closest('[data-rate-delete]');
      if (rateDeleteBtn) {
        var rateId = rateDeleteBtn.dataset.rateDelete;
        adminConfirm('Delete this interest rate? This cannot be undone.', function () {
          deleteRate(rateId);
        }, 'Delete Rate');
        return;
      }

      // ── Settings toggle ────────────────────────────────────────────
      var settingToggle = e.target.closest('.toggle-input[data-setting-key]');
      if (settingToggle) {
        var key = settingToggle.dataset.settingKey;
        var val = settingToggle.checked ? '1' : '0';
        updateSetting(key, val);
        return;
      }

      // ── Savings adjust balance ─────────────────────────────────────
      var savingsAdjustBtn = e.target.closest('[data-savings-adjust]');
      if (savingsAdjustBtn) {
        var adjId = parseInt(savingsAdjustBtn.dataset.savingsAdjust, 10);
        adminPrompt('Enter the new saved balance (USD) for this plan.', 'New Balance (USD)', function (val) {
          var newAmt = parseFloat(val);
          if (isNaN(newAmt) || newAmt < 0) { showToast('Invalid amount', true); return; }
          apiFetch('/api/admin-dashboard/savings.php', {
            method: 'POST',
            body: JSON.stringify({ action: 'adjust', id: adjId, amount: newAmt })
          }).then(function (r) {
            showToast(r.success ? 'Balance adjusted' : (r.message || 'Failed'), !r.success);
            if (r.success) { savingsLoaded = false; loadAdminSavings(savingsPage); }
          });
        }, 'Adjust Savings Balance');
        return;
      }

      // ── User view profile ──────────────────────────────────────────
      var userViewBtn = e.target.closest('[data-user-view]');
      if (userViewBtn) {
        window.openViewUserModal(parseInt(userViewBtn.dataset.userView, 10));
        return;
      }

      // ── Loan repay ─────────────────────────────────────────────────
      var loanRepayBtn = e.target.closest('[data-loan-repay]');
      if (loanRepayBtn) {
        var repayIdEl  = document.getElementById('repayLoanId');
        var repayInfoEl = document.getElementById('repayLoanInfo');
        if (repayIdEl) repayIdEl.value = loanRepayBtn.dataset.loanRepay;
        if (repayInfoEl) {
          var bal = parseFloat(loanRepayBtn.dataset.loanBalance || 0);
          repayInfoEl.textContent = 'Remaining balance: $' + fmt(bal);
        }
        var repayAmtEl = document.getElementById('repayAmount');
        if (repayAmtEl) repayAmtEl.value = '';
        var repayMsgEl = document.getElementById('repayMsg');
        if (repayMsgEl) { repayMsgEl.style.display = 'none'; repayMsgEl.textContent = ''; }
        openAdminModal('modal-record-repayment');
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

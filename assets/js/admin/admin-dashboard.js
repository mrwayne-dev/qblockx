/**
 * Project: arqoracapital
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
    var cls = 'badge badge-' + status.toLowerCase().replace('_', '-');
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

  // Expose globally so inline onclick attributes work
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

      // 6 Stat Cards
      var defs = [
        { label: 'Total Users',          val: d.total_users,                       icon: 'ph-users',               cls: '' },
        { label: 'New Today',             val: d.new_today,                         icon: 'ph-user-plus',           cls: 'accent' },
        { label: 'Total Deposited',       val: '$' + fmt(d.total_deposits),         icon: 'ph-arrow-circle-down',   cls: 'success' },
        { label: 'Amount Invested',       val: '$' + fmt(d.total_invested),         icon: 'ph-chart-bar',           cls: 'accent' },
        { label: 'Profit Distributed',    val: '$' + fmt(d.profit_distributed),     icon: 'ph-coins',               cls: 'success' },
        { label: 'Total Trades',          val: d.total_trades,                      icon: 'ph-arrows-left-right',   cls: '' },
      ];

      var grid = qs('[data-stats-grid]');
      if (grid) {
        grid.innerHTML = defs.map(function (item) {
          return '<div class="stat-card">'
            + '<div class="stat-icon"><i class="ph ' + item.icon + '"></i></div>'
            + '<div class="stat-label">' + item.label + '</div>'
            + '<div class="stat-value ' + item.cls + '">' + item.val + '</div>'
            + '</div>';
        }).join('');
      }

      // Quick-action badge counts
      var qaDep = document.getElementById('qaPendingDeposits');
      var qaWdr = document.getElementById('qaPendingWithdrawals');
      if (qaDep) qaDep.textContent = d.pending_deposits_count  || 0;
      if (qaWdr) qaWdr.textContent = d.pending_withdrawals      || 0;

      // Recent Transactions table (last 5)
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
            + '<button class="btn-action btn-action--primary" data-user-edit="1" data-user=\'' + userData + '\'>Edit</button>'
            + '<button class="btn-action ' + (isVerified ? 'btn-action--danger' : 'btn-action--success') + '"'
            + ' data-user-action="' + (isVerified ? 'unverify' : 'verify') + '" data-id="' + u.id + '">'
            + (isVerified ? 'Unverify' : 'Verify') + '</button>'
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
    var commField = document.getElementById('editUserReferral');
    if (commField) commField.value = '';
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

    var bal  = document.getElementById('editUserBalance').value.trim();
    if (bal !== '') payload.balance_override = parseFloat(bal);

    var comm = document.getElementById('editUserReferral');
    if (comm && comm.value.trim() !== '') payload.referral_commission_override = parseFloat(comm.value.trim());

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
        // Refresh overview counts if loaded
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

  /* ── Trades Section ───────────────────────────────────────────────── */

  var tradesPage    = 1;
  var tradesLoaded  = false;
  var tradesFilter  = 'all';

  async function loadTrades(page, status) {
    if (status !== undefined) tradesFilter = status;
    tradesPage   = page || 1;
    tradesLoaded = true;

    // Update filter buttons
    document.querySelectorAll('[data-trade-filter]').forEach(function (btn) {
      btn.classList.toggle('active', btn.dataset.tradeFilter === tradesFilter);
    });

    try {
      var url = '/api/admin-dashboard/trades.php?page=' + tradesPage + '&limit=20';
      if (tradesFilter && tradesFilter !== 'all') url += '&status=' + tradesFilter;
      var r = await apiFetch(url);
      if (!r.success) return;
      var d = r.data;
      tradesPage = d.page;

      var tbody = qs('[data-table="trades"]');
      if (!tbody) return;

      if (d.trades && d.trades.length) {
        tbody.innerHTML = d.trades.map(function (t) {
          var plan     = (t.plan_name || '').charAt(0).toUpperCase() + (t.plan_name || '').slice(1);
          var rate     = (parseFloat(t.daily_rate || 0) * 100).toFixed(1) + '%/day';
          var invData  = esc(JSON.stringify({
            id: t.id, user_name: t.user_name, user_email: t.user_email,
            amount: t.amount, daily_rate: t.daily_rate, total_earned: t.total_earned,
            status: t.status, starts_at: t.starts_at, ends_at: t.ends_at
          }).replace(/'/g, '&apos;'));
          return '<tr>'
            + '<td><div class="cell-name">' + esc(t.user_name || t.user_email) + '</div>'
            + '<div class="cell-sub">' + esc(t.user_email) + '</div></td>'
            + '<td>' + plan + '</td>'
            + '<td><strong>$' + fmt(t.amount) + '</strong></td>'
            + '<td class="cell-muted">' + rate + '</td>'
            + '<td class="cell-muted">$' + fmt(t.total_earned) + '</td>'
            + '<td>' + badge(t.status) + '</td>'
            + '<td class="cell-muted">' + fmtDate(t.ends_at) + '</td>'
            + '<td><button class="btn-action btn-action--primary" data-inv-edit="1" data-inv=\'' + invData + '\'>Edit</button></td>'
            + '</tr>';
        }).join('');
      } else {
        showEmpty(tbody, 8, 'No trades found');
      }

      var pag = qs('[data-pagination="trades"]');
      if (d.pages > 1) {
        renderPagination(pag, d.page, d.pages, d.total, 20, loadTrades);
      } else if (pag) {
        pag.innerHTML = '';
      }
    } catch (e) {
      console.error('loadTrades:', e);
    }
  }

  /* ── Investment Plans ─────────────────────────────────────────────── */

  async function loadInvestmentPlans() {
    var grid = document.getElementById('planConfigsGrid');
    if (!grid) return;
    try {
      var r = await apiFetch('/api/admin-dashboard/investment-plans.php');
      if (!r.success || !r.data) return;
      var plans = Array.isArray(r.data) ? r.data : (r.data.plans || []);
      if (!plans.length) {
        grid.innerHTML = '<p class="cell-muted">No plans found.</p>';
        return;
      }
      grid.innerHTML = plans.map(function (p) {
        var maxLabel = p.max_amount ? '$' + fmt(p.max_amount) : '∞';
        var rateStr  = (parseFloat(p.daily_rate || 0) * 100).toFixed(1);
        var inactive = p.is_active == 0 || p.is_active === false || p.is_active === '0';
        return '<div class="plan-config-card' + (inactive ? ' plan-config-inactive' : '') + '">'
          + '<div class="plan-config-header">'
          + '<span class="plan-config-name">' + esc(p.name) + '</span>'
          + '<span class="plan-config-rate">' + rateStr + '%/day</span>'
          + '</div>'
          + '<div class="plan-config-details">'
          + '<span class="plan-config-detail">Min: $' + fmt(p.min_amount) + '</span>'
          + '<span class="plan-config-detail">Max: ' + maxLabel + '</span>'
          + '<span class="plan-config-detail">' + p.duration_days + ' days</span>'
          + '</div>'
          + '<div class="plan-config-actions">'
          + '<button class="btn-action btn-action--primary" data-plan-edit="1" data-plan=\'' + esc(JSON.stringify(p).replace(/'/g, '&apos;')) + '\'>Edit</button>'
          + '<button class="btn-action ' + (inactive ? 'btn-action--success' : 'btn-action--warning') + '" data-plan-toggle="' + p.id + '">'
          + (inactive ? 'Enable' : 'Disable') + '</button>'
          + '</div>'
          + '</div>';
      }).join('');
    } catch (e) {
      console.error('loadInvestmentPlans:', e);
    }
  }

  /* ── Investment Edit Modal ────────────────────────────────────────── */

  window.openInvestmentModal = function (invData) {
    if (typeof invData === 'string') {
      try { invData = JSON.parse(invData); } catch (e) { return; }
    }
    document.getElementById('editInvId').value       = invData.id            || '';
    document.getElementById('editInvUser').value     = (invData.user_name || invData.user_email || '');
    document.getElementById('editInvAmount').value   = invData.amount        || '';
    document.getElementById('editInvRate').value     = invData.daily_rate    != null
      ? (parseFloat(invData.daily_rate) * 100).toFixed(2) : '';
    document.getElementById('editInvEarned').value   = invData.total_earned  || '';
    document.getElementById('editInvStatus').value   = invData.status        || 'active';

    var toLocalDT = function (dt) {
      if (!dt) return '';
      var d = new Date(dt);
      var pad = function (n) { return n < 10 ? '0' + n : n; };
      return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate())
        + 'T' + pad(d.getHours()) + ':' + pad(d.getMinutes());
    };

    document.getElementById('editInvStartsAt').value = toLocalDT(invData.starts_at);
    document.getElementById('editInvEndsAt').value   = toLocalDT(invData.ends_at);
    openAdminModal('modal-edit-investment');
  };

  window.saveInvestment = async function () {
    var id    = document.getElementById('editInvId').value;
    var msgEl = document.getElementById('editInvMsg');
    msgEl.textContent = '';
    msgEl.className   = 'admin-modal-msg';

    var rateVal  = parseFloat(document.getElementById('editInvRate').value || 0) / 100;
    var payload  = {
      id:           parseInt(id, 10),
      amount:       parseFloat(document.getElementById('editInvAmount').value),
      daily_rate:   rateVal,
      total_earned: parseFloat(document.getElementById('editInvEarned').value),
      status:       document.getElementById('editInvStatus').value,
      starts_at:    document.getElementById('editInvStartsAt').value || null,
      ends_at:      document.getElementById('editInvEndsAt').value   || null
    };

    try {
      var r = await apiFetch('/api/admin-dashboard/edit-investment.php', {
        method: 'POST',
        body:   JSON.stringify(payload)
      });
      if (r.success) {
        showToast(r.message || 'Trade saved');
        closeAdminModal('modal-edit-investment');
        tradesLoaded = false;
        loadTrades(tradesPage, tradesFilter);
      } else {
        msgEl.textContent = r.message || 'Save failed';
        msgEl.classList.add('error');
      }
    } catch (e) {
      msgEl.textContent = 'Network error';
      msgEl.classList.add('error');
    }
  };

  /* ── Plan Edit Modal ──────────────────────────────────────────────── */

  window.openPlanModal = function (planData) {
    if (typeof planData === 'string') {
      try { planData = JSON.parse(planData); } catch (e) { return; }
    }
    document.getElementById('editPlanId').value       = planData.id           || '';
    document.getElementById('editPlanName').value     = planData.name         || '';
    document.getElementById('editPlanMin').value      = planData.min_amount   || '';
    document.getElementById('editPlanMax').value      = planData.max_amount   || '';
    document.getElementById('editPlanRate').value     = planData.daily_rate   != null
      ? (parseFloat(planData.daily_rate) * 100).toFixed(2) : '';
    document.getElementById('editPlanDays').value     = planData.duration_days || 5;
    openAdminModal('modal-edit-plan');
  };

  window.savePlan = async function () {
    var id    = document.getElementById('editPlanId').value;
    var msgEl = document.getElementById('editPlanMsg');
    msgEl.textContent = '';
    msgEl.className   = 'admin-modal-msg';

    var rateVal = parseFloat(document.getElementById('editPlanRate').value || 0) / 100;
    var maxVal  = document.getElementById('editPlanMax').value.trim();
    var payload = {
      action:        'update',
      id:            parseInt(id, 10),
      name:          document.getElementById('editPlanName').value.trim(),
      min_amount:    parseFloat(document.getElementById('editPlanMin').value),
      max_amount:    maxVal !== '' ? parseFloat(maxVal) : null,
      daily_rate:    rateVal,
      duration_days: parseInt(document.getElementById('editPlanDays').value, 10)
    };

    try {
      var r = await apiFetch('/api/admin-dashboard/investment-plans.php', {
        method: 'POST',
        body:   JSON.stringify(payload)
      });
      if (r.success) {
        showToast(r.message || 'Plan saved');
        closeAdminModal('modal-edit-plan');
        loadInvestmentPlans();
      } else {
        msgEl.textContent = r.message || 'Save failed';
        msgEl.classList.add('error');
      }
    } catch (e) {
      msgEl.textContent = 'Network error';
      msgEl.classList.add('error');
    }
  };

  async function togglePlan(id) {
    try {
      var r = await apiFetch('/api/admin-dashboard/investment-plans.php', {
        method: 'POST',
        body:   JSON.stringify({ action: 'toggle', id: parseInt(id, 10) })
      });
      if (r.success) {
        showToast(r.message || 'Plan updated');
        loadInvestmentPlans();
      } else {
        showToast(r.message || 'Action failed', true);
      }
    } catch (e) {
      showToast('Network error', true);
    }
  }

  /* ── Transactions Section ─────────────────────────────────────────── */

  var txPage    = 1;
  var txLoaded  = false;
  var txTypeFilter = '';

  async function loadTransactions(page, type) {
    if (type !== undefined) txTypeFilter = type;
    txPage   = page || 1;
    txLoaded = true;

    // Update filter buttons
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

      // Metric cards
      if (d.summary) {
        var volEl  = document.getElementById('txMetricVolume');
        var depEl  = document.getElementById('txMetricDeposits');
        var wdrEl  = document.getElementById('txMetricWithdrawals');
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
            + '<td class="cell-muted">' + esc((tx.currency || '—').toUpperCase()) + '</td>'
            + '<td>' + badge(tx.status) + '</td>'
            + '<td class="cell-muted" style="max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">'
            + esc(tx.notes || '—') + '</td>'
            + '<td class="cell-muted">' + fmtDateTime(tx.created_at) + '</td>'
            + '</tr>';
        }).join('');
      } else {
        showEmpty(tbody, 7, 'No transactions found');
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

  /* ── Referrals Section ────────────────────────────────────────────── */

  var referralsPage   = 1;
  var referralsLoaded = false;

  async function loadReferrals(page) {
    referralsPage   = page || referralsPage;
    referralsLoaded = true;
    try {
      var r = await apiFetch('/api/admin-dashboard/referrals.php?page=' + referralsPage + '&limit=20');
      if (!r.success) return;
      var d = r.data;
      referralsPage = d.page;

      // Metric cards
      if (d.summary) {
        var totalEl    = document.getElementById('refMetricTotal');
        var commEl     = document.getElementById('refMetricComm');
        var refEl      = document.getElementById('refMetricReferrers');
        if (totalEl) totalEl.textContent = d.summary.total_referrals;
        if (commEl)  commEl.textContent  = '$' + fmt(d.summary.total_commission_paid);
        if (refEl)   refEl.textContent   = d.summary.active_referrers;
      }

      var tbody = qs('[data-table="referrals"]');
      if (!tbody) return;

      if (d.referrals && d.referrals.length) {
        tbody.innerHTML = d.referrals.map(function (ref) {
          var commRate = ref.commission_rate != null
            ? (parseFloat(ref.commission_rate) * 100).toFixed(0) + '%'
            : '5%';
          return '<tr>'
            + '<td><div class="cell-name">' + esc(ref.referrer_name || ref.referrer_email) + '</div>'
            + '<div class="cell-sub">' + esc(ref.referrer_email) + '</div></td>'
            + '<td><div class="cell-name">' + esc(ref.referred_name || ref.referred_email) + '</div>'
            + '<div class="cell-sub">' + esc(ref.referred_email) + '</div></td>'
            + '<td class="cell-muted">' + commRate + '</td>'
            + '<td class="cell-muted">$' + fmt(ref.total_earned) + '</td>'
            + '<td class="cell-muted">' + fmtDateTime(ref.created_at) + '</td>'
            + '</tr>';
        }).join('');
      } else {
        showEmpty(tbody, 5, 'No referrals found');
      }

      var pag = qs('[data-pagination="referrals"]');
      if (d.pages > 1) {
        renderPagination(pag, d.page, d.pages, d.total, 20, loadReferrals);
      } else if (pag) {
        pag.innerHTML = '';
      }
    } catch (e) {
      console.error('loadReferrals:', e);
    }
  }

  /* ── Pending Deposits Modal ───────────────────────────────────────── */

  window.loadPendingDeposits = async function () {
    var tbody = document.getElementById('pendingDepositsTable');
    var msgEl = document.getElementById('pendingDepositsMsg');
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
    var msgEl = document.getElementById('pendingDepositsMsg');
    if (msgEl) { msgEl.textContent = ''; msgEl.className = 'admin-modal-msg'; }
    try {
      var r = await apiFetch('/api/admin-dashboard/resolve-deposit.php', {
        method: 'POST',
        body:   JSON.stringify({ id: parseInt(id, 10), action: action })
      });
      if (r.success) {
        showToast(r.message || 'Deposit resolved');
        window.loadPendingDeposits();
        // Refresh overview
        overviewLoaded = false;
        if (currentSection === 'overview') loadOverview();
      } else {
        showToast(r.message || 'Action failed', true);
        if (msgEl) { msgEl.textContent = r.message || 'Action failed'; msgEl.classList.add('error'); }
      }
    } catch (e) {
      showToast('Network error', true);
    }
  }

  /* ── Withdrawals Modal ────────────────────────────────────────────── */

  var wrModalStatus = 'pending';

  window.loadWithdrawalsModal = async function (status) {
    if (status !== undefined) wrModalStatus = status;

    // Update modal filter buttons
    document.querySelectorAll('[data-wr-modal-filter]').forEach(function (btn) {
      btn.classList.toggle('active', btn.dataset.wrModalFilter === wrModalStatus);
    });

    var tbody = document.getElementById('withdrawalsModalTable');
    var msgEl = document.getElementById('withdrawalModalMsg');
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
          var addr        = wr.wallet_address || '';
          var addrDisplay = addr.length > 18 ? addr.substring(0, 10) + '…' + addr.slice(-6) : addr;
          var isPending   = wr.status === 'pending';
          return '<tr>'
            + '<td><div class="cell-name">' + esc(wr.user_name || wr.user_email) + '</div>'
            + '<div class="cell-sub">' + esc(wr.user_email) + '</div></td>'
            + '<td><strong>$' + fmt(wr.amount) + '</strong></td>'
            + '<td class="cell-muted">' + esc((wr.currency || 'USD').toUpperCase()) + '</td>'
            + '<td><span title="' + esc(addr) + '" class="cell-muted" style="font-family:monospace;font-size:0.78rem;">'
            + esc(addrDisplay) + '</span></td>'
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

  async function handleWithdrawal(id, action) {
    var notes = '';
    if (action === 'reject') {
      notes = window.prompt('Rejection reason (optional):') || '';
    }
    try {
      var r = await apiFetch('/api/admin-dashboard/approve-withdrawal.php', {
        method: 'POST',
        body:   JSON.stringify({ request_id: id, action: action, notes: notes })
      });
      if (r.success) {
        showToast(r.message || ('Withdrawal ' + action + 'd'));
        window.loadWithdrawalsModal(wrModalStatus);
        // Refresh overview badge
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
    trades:       'Trades',
    transactions: 'Transactions',
    referrals:    'Referrals'
  };

  var sectionLoaders = {
    overview:     function () { loadOverview(); },
    users:        function () { if (!usersLoaded)       loadUsers(1); },
    trades:       function () { if (!tradesLoaded)      { loadTrades(1); loadInvestmentPlans(); } },
    transactions: function () { if (!txLoaded)          loadTransactions(1); },
    referrals:    function () { if (!referralsLoaded)   loadReferrals(1); }
  };

  function activateSection(name) {
    if (!sectionLoaders[name]) name = 'overview';
    currentSection = name;

    document.querySelectorAll('.admin-section').forEach(function (el) {
      el.classList.toggle('active', el.dataset.section === name);
    });
    document.querySelectorAll('[data-nav]').forEach(function (el) {
      el.classList.toggle('active', el.dataset.nav === name);
    });

    // Update sticky header page title
    var titleEl = document.getElementById('adminPageTitle');
    if (titleEl) titleEl.textContent = sectionTitles[name] || name;

    if (sectionLoaders[name]) sectionLoaders[name]();
  }

  /* ── Init ─────────────────────────────────────────────────────────── */

  document.addEventListener('DOMContentLoaded', function () {

    // Nav clicks (sidebar + mobile dock)
    document.querySelectorAll('[data-nav]').forEach(function (el) {
      el.addEventListener('click', function (e) {
        e.preventDefault();
        var sec = this.dataset.nav;
        activateSection(sec);
        if (history.pushState) history.pushState(null, '', '#' + sec);
      });
    });

    // Global click delegation
    document.addEventListener('click', function (e) {

      // ── Close modal button ─────────────────────────────────────────
      var closeBtn = e.target.closest('[data-close-modal]');
      if (closeBtn) {
        closeAdminModal(closeBtn.dataset.closeModal);
        return;
      }

      // ── Modal overlay click (close on backdrop) ────────────────────
      if (e.target.classList.contains('admin-modal-overlay')) {
        var openOverlay = e.target.closest('.admin-modal-overlay.active');
        if (openOverlay) closeAdminModal(openOverlay.id);
        return;
      }

      // ── Trade filter buttons ───────────────────────────────────────
      var tradeFilter = e.target.closest('[data-trade-filter]');
      if (tradeFilter) {
        tradesLoaded = false;
        loadTrades(1, tradeFilter.dataset.tradeFilter);
        return;
      }

      // ── Transaction type filter ────────────────────────────────────
      var txFilter = e.target.closest('[data-tx-type]');
      if (txFilter) {
        txLoaded = false;
        loadTransactions(1, txFilter.dataset.txType);
        return;
      }

      // ── Withdrawal modal filter ────────────────────────────────────
      var wrModalFilter = e.target.closest('[data-wr-modal-filter]');
      if (wrModalFilter) {
        window.loadWithdrawalsModal(wrModalFilter.dataset.wrModalFilter);
        return;
      }

      // ── User edit button ───────────────────────────────────────────
      var userEditBtn = e.target.closest('[data-user-edit]');
      if (userEditBtn) {
        try {
          var userData = JSON.parse(userEditBtn.dataset.user.replace(/&apos;/g, "'"));
          window.openUserModal(userData);
        } catch (err) { console.error('parse user data', err); }
        return;
      }

      // ── User verify/unverify button ────────────────────────────────
      var userBtn = e.target.closest('[data-user-action]');
      if (userBtn) {
        var uid    = parseInt(userBtn.dataset.id, 10);
        var action = userBtn.dataset.userAction;
        if (action === 'promote') {
          if (!window.confirm('Promote this user to admin? They will gain full admin access.')) return;
        }
        updateUser(uid, action);
        return;
      }

      // ── Investment edit button ─────────────────────────────────────
      var invEditBtn = e.target.closest('[data-inv-edit]');
      if (invEditBtn) {
        try {
          var invData = JSON.parse(invEditBtn.dataset.inv.replace(/&apos;/g, "'"));
          window.openInvestmentModal(invData);
        } catch (err) { console.error('parse inv data', err); }
        return;
      }

      // ── Plan edit button ───────────────────────────────────────────
      var planEditBtn = e.target.closest('[data-plan-edit]');
      if (planEditBtn) {
        try {
          var planData = JSON.parse(planEditBtn.dataset.plan.replace(/&apos;/g, "'"));
          window.openPlanModal(planData);
        } catch (err) { console.error('parse plan data', err); }
        return;
      }

      // ── Plan toggle button ─────────────────────────────────────────
      var planToggleBtn = e.target.closest('[data-plan-toggle]');
      if (planToggleBtn) {
        togglePlan(planToggleBtn.dataset.planToggle);
        return;
      }

      // ── Pending deposit actions ────────────────────────────────────
      var depBtn = e.target.closest('[data-dep-action]');
      if (depBtn) {
        var depId  = parseInt(depBtn.dataset.id, 10);
        var depAct = depBtn.dataset.depAction;
        var depMsg = depAct === 'complete' ? 'Mark this deposit as completed and credit the user\'s wallet?' : 'Mark this deposit as failed?';
        if (!window.confirm(depMsg)) return;
        resolveDeposit(depId, depAct);
        return;
      }

      // ── Withdrawal modal action buttons ────────────────────────────
      var wrBtn = e.target.closest('[data-wr-action]');
      if (wrBtn) {
        var wrId  = parseInt(wrBtn.dataset.id, 10);
        var wrAct = wrBtn.dataset.wrAction;
        if (wrAct === 'approve') {
          if (!window.confirm('Approve this withdrawal request?')) return;
        }
        handleWithdrawal(wrId, wrAct);
        return;
      }
    });

    // ESC key closes open modal
    document.addEventListener('keydown', function (e) {
      if (e.key !== 'Escape') return;
      var openModal = document.querySelector('.admin-modal-overlay.active');
      if (openModal) closeAdminModal(openModal.id);
    });

    // Initial section from hash
    var hash = location.hash.replace('#', '');
    activateSection(sectionLoaders[hash] ? hash : 'overview');

    // Handle browser back/forward
    window.addEventListener('popstate', function () {
      var h = location.hash.replace('#', '');
      activateSection(sectionLoaders[h] ? h : 'overview');
    });
  });

})();

(function () {
  'use strict';

  // ── Helpers ──────────────────────────────────────────────────────────────────

  async function apiFetch(url, opts) {
    opts = opts || {};
    var res = await fetch(url, Object.assign({
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

  function showToast(msg, isError) {
    var toast = document.createElement('div');
    toast.className = 'toast ' + (isError ? 'toast--error' : 'toast--success');
    toast.textContent = msg;
    document.body.appendChild(toast);
    setTimeout(function () { if (toast.parentNode) toast.parentNode.removeChild(toast); }, 4000);
  }

  function qs(sel) { return document.querySelector(sel); }
  function setText(sel, val) { var el = qs(sel); if (el) el.textContent = val; }

  // ── Pagination ────────────────────────────────────────────────────────────────

  function renderPagination(sel, current, total, loadFn) {
    var el = qs(sel);
    if (!el) return;
    if (total <= 1) { el.innerHTML = ''; return; }

    var html = '';
    if (current > 1) {
      html += '<button class="btn-page" data-page="' + (current - 1) + '">&laquo; Prev</button>';
    }
    html += '<span class="page-info">Page ' + current + ' of ' + total + '</span>';
    if (current < total) {
      html += '<button class="btn-page" data-page="' + (current + 1) + '">Next &raquo;</button>';
    }

    el.innerHTML = html;
    el.querySelectorAll('[data-page]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        loadFn(parseInt(btn.dataset.page, 10));
      });
    });
  }

  // ── Stats ────────────────────────────────────────────────────────────────────

  async function loadStats() {
    try {
      var r = await apiFetch('/api/admin-dashboard/dashboard.php');
      if (!r.success) return;
      var d = r.data;

      setText('[data-stat="total-users"]',         d.total_users);
      setText('[data-stat="new-today"]',           d.new_today);
      setText('[data-stat="total-deposits"]',      '$' + fmt(d.total_deposits));
      setText('[data-stat="active-investments"]',  d.active_investments);
      setText('[data-stat="pending-withdrawals"]', d.pending_withdrawals);
      setText('[data-stat="total-earned"]',        '$' + fmt(d.total_earned));
    } catch (e) {
      console.error('loadStats:', e);
    }
  }

  // ── Users ────────────────────────────────────────────────────────────────────

  var usersPage = 1;

  async function loadUsers(page) {
    usersPage = page || usersPage;
    try {
      var r = await apiFetch('/api/admin-dashboard/users.php?page=' + usersPage + '&limit=20');
      if (!r.success) return;
      var d = r.data;
      usersPage = d.page;

      var tbody = qs('[data-table="admin-users"]');
      if (tbody) {
        if (d.users && d.users.length) {
          tbody.innerHTML = d.users.map(function (u) {
            var verifiedBadge = u.is_verified
              ? '<span class="badge badge-success">Verified</span>'
              : '<span class="badge badge-warning">Unverified</span>';
            var verifyLabel = (u.is_verified == 1 || u.is_verified === true || u.is_verified === '1')
              ? 'Unverify' : 'Verify';
            var verifyAction = (u.is_verified == 1 || u.is_verified === true || u.is_verified === '1')
              ? 'unverify' : 'verify';
            return '<tr data-user-id="' + u.id + '">'
              + '<td>' + (u.full_name || 'N/A') + '</td>'
              + '<td>' + u.email + '</td>'
              + '<td>$' + fmt(u.balance) + '</td>'
              + '<td>' + verifiedBadge + '</td>'
              + '<td>' + fmtDate(u.created_at) + '</td>'
              + '<td class="actions">'
              + '<button class="btn-sm btn-outline" data-user-action="' + verifyAction + '" data-id="' + u.id + '">' + verifyLabel + '</button> '
              + '<button class="btn-sm btn-outline" data-user-action="promote" data-id="' + u.id + '">Promote</button>'
              + '</td>'
              + '</tr>';
          }).join('');
        } else {
          tbody.innerHTML = '<tr><td colspan="6" class="empty-row">No users found</td></tr>';
        }
      }

      renderPagination('[data-pagination="users"]', d.page, d.pages, loadUsers);
    } catch (e) {
      console.error('loadUsers:', e);
    }
  }

  async function updateUser(id, action) {
    try {
      var r = await apiFetch('/api/admin-dashboard/update-user.php', {
        method: 'POST',
        body: JSON.stringify({ id: id, action: action })
      });
      if (r.success) {
        showToast(r.message || 'User updated');
        loadUsers(usersPage);
      } else {
        showToast(r.message || 'Action failed', true);
      }
    } catch (e) {
      showToast('Network error', true);
    }
  }

  // ── Trades ───────────────────────────────────────────────────────────────────

  var tradesPage = 1;

  async function loadTrades(page) {
    tradesPage = page || tradesPage;
    try {
      var r = await apiFetch('/api/admin-dashboard/trades.php?page=' + tradesPage + '&limit=20');
      if (!r.success) return;
      var d = r.data;
      tradesPage = d.page;

      var tbody = qs('[data-table="admin-trades"]');
      if (tbody) {
        if (d.trades && d.trades.length) {
          tbody.innerHTML = d.trades.map(function (t) {
            var planLabel = t.plan_name.charAt(0).toUpperCase() + t.plan_name.slice(1);
            var rate = (parseFloat(t.daily_rate) * 100).toFixed(0) + '%/day';
            return '<tr>'
              + '<td>' + (t.user_name || t.user_email) + '</td>'
              + '<td>' + planLabel + '</td>'
              + '<td>$' + fmt(t.amount) + '</td>'
              + '<td>' + rate + '</td>'
              + '<td>$' + fmt(t.total_earned) + '</td>'
              + '<td>' + badge(t.status) + '</td>'
              + '<td>' + fmtDate(t.ends_at) + '</td>'
              + '</tr>';
          }).join('');
        } else {
          tbody.innerHTML = '<tr><td colspan="7" class="empty-row">No trades found</td></tr>';
        }
      }

      renderPagination('[data-pagination="trades"]', d.page, d.pages, loadTrades);
    } catch (e) {
      console.error('loadTrades:', e);
    }
  }

  // ── Transactions ──────────────────────────────────────────────────────────────

  var txPage = 1;

  async function loadTransactions(page) {
    txPage = page || txPage;
    try {
      var r = await apiFetch('/api/admin-dashboard/transactions.php?page=' + txPage + '&limit=20');
      if (!r.success) return;
      var d = r.data;
      txPage = d.page;

      var tbody = qs('[data-table="admin-transactions"]');
      if (tbody) {
        if (d.transactions && d.transactions.length) {
          tbody.innerHTML = d.transactions.map(function (t) {
            return '<tr>'
              + '<td>' + (t.user_name || t.user_email) + '</td>'
              + '<td>' + t.type + '</td>'
              + '<td>$' + fmt(t.amount) + '</td>'
              + '<td>' + (t.currency || 'USD').toUpperCase() + '</td>'
              + '<td>' + badge(t.status) + '</td>'
              + '<td>' + (t.notes || '--') + '</td>'
              + '<td>' + fmtDate(t.created_at) + '</td>'
              + '</tr>';
          }).join('');
        } else {
          tbody.innerHTML = '<tr><td colspan="7" class="empty-row">No transactions found</td></tr>';
        }
      }

      renderPagination('[data-pagination="transactions"]', d.page, d.pages, loadTransactions);
    } catch (e) {
      console.error('loadTransactions:', e);
    }
  }

  // ── Withdrawals ───────────────────────────────────────────────────────────────

  var withdrawalsPage = 1;

  async function loadWithdrawals(page) {
    withdrawalsPage = page || withdrawalsPage;
    try {
      var r = await apiFetch('/api/admin-dashboard/withdrawal-requests.php?status=pending&page=' + withdrawalsPage + '&limit=20');
      if (!r.success) return;
      var d = r.data;
      withdrawalsPage = d.page;

      var tbody = qs('[data-table="admin-withdrawals"]');
      if (tbody) {
        if (d.requests && d.requests.length) {
          tbody.innerHTML = d.requests.map(function (wr) {
            return '<tr data-wr-id="' + wr.id + '">'
              + '<td>' + (wr.user_name || wr.user_email) + '</td>'
              + '<td>$' + fmt(wr.amount) + '</td>'
              + '<td>' + (wr.currency || 'USD').toUpperCase() + '</td>'
              + '<td class="addr-cell" title="' + wr.wallet_address + '">' + wr.wallet_address.substring(0, 16) + '...</td>'
              + '<td>' + badge(wr.status) + '</td>'
              + '<td>' + fmtDate(wr.created_at) + '</td>'
              + '<td class="actions">'
              + '<button class="btn-sm btn-success" data-wr-action="approve" data-id="' + wr.id + '">Approve</button> '
              + '<button class="btn-sm btn-error"   data-wr-action="reject"  data-id="' + wr.id + '">Reject</button>'
              + '</td>'
              + '</tr>';
          }).join('');
        } else {
          tbody.innerHTML = '<tr><td colspan="7" class="empty-row">No pending withdrawals</td></tr>';
        }
      }

      renderPagination('[data-pagination="withdrawals"]', d.page, d.pages, loadWithdrawals);
    } catch (e) {
      console.error('loadWithdrawals:', e);
    }
  }

  async function approveWithdrawal(id, action) {
    var notes = '';
    if (action === 'reject') {
      notes = window.prompt('Rejection reason (optional):') || '';
    }

    try {
      var r = await apiFetch('/api/admin-dashboard/approve-withdrawal.php', {
        method: 'POST',
        body: JSON.stringify({ id: id, action: action, notes: notes })
      });
      if (r.success) {
        showToast(r.message || 'Withdrawal ' + action + 'd');
        loadWithdrawals(withdrawalsPage);
        loadStats();
      } else {
        showToast(r.message || 'Action failed', true);
      }
    } catch (e) {
      showToast('Network error', true);
    }
  }

  // ── Referrals ─────────────────────────────────────────────────────────────────

  var referralsPage = 1;

  async function loadReferrals(page) {
    referralsPage = page || referralsPage;
    try {
      var r = await apiFetch('/api/admin-dashboard/referrals.php?page=' + referralsPage + '&limit=20');
      if (!r.success) return;
      var d = r.data;
      referralsPage = d.page;

      var tbody = qs('[data-table="admin-referrals"]');
      if (tbody) {
        if (d.referrals && d.referrals.length) {
          tbody.innerHTML = d.referrals.map(function (ref) {
            return '<tr>'
              + '<td>' + (ref.referrer_name || ref.referrer_email) + '</td>'
              + '<td>' + ref.referrer_email + '</td>'
              + '<td>' + (ref.referred_name || ref.referred_email) + '</td>'
              + '<td>' + ref.referred_email + '</td>'
              + '<td>$' + fmt(ref.total_earned) + '</td>'
              + '<td>' + fmtDate(ref.created_at) + '</td>'
              + '</tr>';
          }).join('');
        } else {
          tbody.innerHTML = '<tr><td colspan="6" class="empty-row">No referrals found</td></tr>';
        }
      }

      renderPagination('[data-pagination="referrals"]', d.page, d.pages, loadReferrals);
    } catch (e) {
      console.error('loadReferrals:', e);
    }
  }

  // ── Section Navigation ────────────────────────────────────────────────────────

  var sectionLoaders = {
    stats:        loadStats,
    users:        loadUsers,
    trades:       loadTrades,
    transactions: loadTransactions,
    withdrawals:  loadWithdrawals,
    referrals:    loadReferrals
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

    // Sidebar nav clicks
    document.querySelectorAll('[data-nav]').forEach(function (el) {
      el.addEventListener('click', function (e) {
        e.preventDefault();
        var section = this.dataset.nav;
        activateSection(section);
        if (history.pushState) history.pushState(null, '', '#' + section);
      });
    });

    // Delegated click handlers
    document.addEventListener('click', function (e) {

      // User action buttons (verify/unverify/promote/demote)
      var userBtn = e.target.closest('[data-user-action]');
      if (userBtn) {
        var userId = parseInt(userBtn.dataset.id, 10);
        var action = userBtn.dataset.userAction;
        if (action === 'promote') {
          if (!window.confirm('Promote this user to admin? This grants full admin access.')) return;
        }
        updateUser(userId, action);
        return;
      }

      // Withdrawal request action buttons
      var wrBtn = e.target.closest('[data-wr-action]');
      if (wrBtn) {
        var wrId   = parseInt(wrBtn.dataset.id, 10);
        var wrAct  = wrBtn.dataset.wrAction;
        if (wrAct === 'approve') {
          if (!window.confirm('Approve this withdrawal request?')) return;
          approveWithdrawal(wrId, 'approve');
        } else if (wrAct === 'reject') {
          approveWithdrawal(wrId, 'reject');
        }
      }
    });

    // Activate initial section from URL hash, default to stats
    var hash = location.hash.replace('#', '');
    activateSection(sectionLoaders[hash] ? hash : 'stats');
  });

})();

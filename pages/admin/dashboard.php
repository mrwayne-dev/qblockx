<?php
/**
 * Project: arqoracapital
 * Page: Admin Dashboard — SPA
 */

require_once '../../api/utilities/auth-check.php';
requireAdmin();
$admin = getAuthUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin — ArqoraCapital</title>

  <!-- Fonts -->
  <link rel="preload" href="/assets/fonts/DMSans-Regular.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="/assets/fonts/DMSans-Bold.woff2"    as="font" type="font/woff2" crossorigin>

  <!-- Styles -->
  <link rel="stylesheet" href="/assets/css/main.css">
  <link rel="stylesheet" href="/assets/css/admin/admin.css">
  <link rel="stylesheet" href="/assets/icons/style.css">

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="/assets/favicon/favicon.ico">
</head>
<body class="admin-body">

<div class="admin-layout">

  <!-- ── Desktop Sidebar ─────────────────────────────────────────── -->
  <aside class="admin-sidebar">

    <a href="/pages/admin/dashboard.php" class="sidebar-logo">
      <img src="/assets/images/logo/5.png" alt="ArqoraCapital" onerror="this.style.display='none'">
      ArqoraCapital
      <span class="sidebar-logo-badge">Admin</span>
    </a>

    <nav class="sidebar-nav" aria-label="Admin navigation">
      <button class="sidebar-nav-item" data-nav="overview">
        <i class="ph ph-squares-four" aria-hidden="true"></i>
        Overview
      </button>
      <button class="sidebar-nav-item" data-nav="users">
        <i class="ph ph-users" aria-hidden="true"></i>
        Users
      </button>
      <button class="sidebar-nav-item" data-nav="trades">
        <i class="ph ph-arrows-left-right" aria-hidden="true"></i>
        Trades
      </button>
      <button class="sidebar-nav-item" data-nav="transactions">
        <i class="ph ph-receipt" aria-hidden="true"></i>
        Transactions
      </button>
      <button class="sidebar-nav-item" data-nav="referrals">
        <i class="ph ph-share-network" aria-hidden="true"></i>
        Referrals
      </button>
    </nav>

    <div class="sidebar-footer">
      <div class="sidebar-admin-info">
        <span class="sidebar-admin-label">Signed in as</span>
        <span class="sidebar-admin-email"><?= htmlspecialchars($admin['email']) ?></span>
      </div>
      <a href="/api/auth/logout.php" class="sidebar-logout" title="Sign out">
        <i class="ph ph-sign-out" aria-hidden="true"></i>
      </a>
    </div>

  </aside>

  <!-- ── Main Content ─────────────────────────────────────────────── -->
  <main class="admin-main">

    <!-- Desktop sticky header -->
    <header class="admin-header">
      <h1 class="admin-page-title" id="adminPageTitle">Overview</h1>
      <div class="admin-header-right">
        <span class="admin-header-email"><?= htmlspecialchars($admin['email']) ?></span>
        <a href="/api/auth/logout.php" class="admin-header-logout" title="Sign out">
          <i class="ph ph-sign-out" aria-hidden="true"></i>
        </a>
      </div>
    </header>

    <!-- Mobile topbar -->
    <header class="admin-topbar">
      <a href="/pages/admin/dashboard.php" class="topbar-logo">
        <img src="/assets/images/logo/2.png" alt="" onerror="this.style.display='none'">
        ArqoraCapital
        <span class="topbar-badge">Admin</span>
      </a>
      <a href="/api/auth/logout.php" class="sidebar-logout" style="margin-left:auto;" title="Sign out">
        <i class="ph ph-sign-out" aria-hidden="true"></i>
      </a>
    </header>

    <div class="admin-sections">

      <!-- ── Overview Section ────────────────────────────────────── -->
      <section class="admin-section active" data-section="overview">
        <div class="section-header">
          <div>
            <h2 class="section-title">Overview</h2>
            <p class="section-subtitle">Platform-wide statistics at a glance</p>
          </div>
        </div>

        <!-- Stat grid — 6 cards -->
        <div class="stat-grid" data-stats-grid>
          <div class="stat-card"><div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i></div></div>
          <div class="stat-card"><div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i></div></div>
          <div class="stat-card"><div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i></div></div>
          <div class="stat-card"><div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i></div></div>
          <div class="stat-card"><div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i></div></div>
          <div class="stat-card"><div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i></div></div>
        </div>

        <!-- Quick actions -->
        <div class="quick-actions-row">
          <div class="quick-action-card" onclick="openAdminModal('modal-pending-deposits'); loadPendingDeposits();">
            <div class="qa-icon qa-icon--accent">
              <i class="ph ph-arrow-circle-down" aria-hidden="true"></i>
            </div>
            <div class="qa-body">
              <div class="qa-label">Pending Deposits</div>
              <div class="qa-count" id="qaPendingDeposits">—</div>
            </div>
            <span class="qa-link">Review →</span>
          </div>

          <div class="quick-action-card" onclick="openAdminModal('modal-withdrawals'); loadWithdrawalsModal('pending');">
            <div class="qa-icon qa-icon--warning">
              <i class="ph ph-arrow-circle-up-right" aria-hidden="true"></i>
            </div>
            <div class="qa-body">
              <div class="qa-label">Pending Withdrawals</div>
              <div class="qa-count" id="qaPendingWithdrawals">—</div>
            </div>
            <span class="qa-link">Review →</span>
          </div>
        </div>

        <!-- Recent Transactions -->
        <div class="overview-tables-row">
          <div class="table-card">
            <div class="table-toolbar">
              <div class="table-toolbar-title">Recent Transactions</div>
            </div>
            <div class="data-table-wrap">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>User</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                  </tr>
                </thead>
                <tbody data-table="overview-txns">
                  <tr><td colspan="5">
                    <div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i> Loading…</div>
                  </td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </section>

      <!-- ── Users Section ───────────────────────────────────────── -->
      <section class="admin-section" data-section="users">
        <div class="section-header">
          <div>
            <h2 class="section-title">Users</h2>
            <p class="section-subtitle">Manage registered accounts</p>
          </div>
        </div>

        <div class="table-card">
          <div class="table-toolbar">
            <div class="table-toolbar-title">All Users</div>
          </div>
          <div class="data-table-wrap">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Balance</th>
                  <th>Role</th>
                  <th>Status</th>
                  <th>Joined</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody data-table="users">
                <tr><td colspan="7">
                  <div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i> Loading…</div>
                </td></tr>
              </tbody>
            </table>
          </div>
          <div class="pagination" data-pagination="users"></div>
        </div>
      </section>

      <!-- ── Trades Section ──────────────────────────────────────── -->
      <section class="admin-section" data-section="trades">
        <div class="section-header">
          <div>
            <h2 class="section-title">Trades</h2>
            <p class="section-subtitle">Investment contracts &amp; plan configuration</p>
          </div>
        </div>

        <div class="table-card">
          <div class="table-toolbar">
            <div class="table-toolbar-title">Investment Contracts</div>
            <div class="table-filters">
              <button class="filter-btn active" data-trade-filter="all">All</button>
              <button class="filter-btn" data-trade-filter="active">Active</button>
              <button class="filter-btn" data-trade-filter="completed">Completed</button>
              <button class="filter-btn" data-trade-filter="cancelled">Cancelled</button>
            </div>
          </div>
          <div class="data-table-wrap">
            <table class="data-table">
              <thead>
                <tr>
                  <th>User</th>
                  <th>Plan</th>
                  <th>Amount</th>
                  <th>Rate</th>
                  <th>Earned</th>
                  <th>Status</th>
                  <th>Ends</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody data-table="trades">
                <tr><td colspan="8">
                  <div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i> Loading…</div>
                </td></tr>
              </tbody>
            </table>
          </div>
          <div class="pagination" data-pagination="trades"></div>
        </div>

        <!-- Plan Config Sub-section -->
        <div class="plan-configs-section">
          <h3 class="plan-configs-section-title">
            <i class="ph ph-gear"></i> Investment Plan Configuration
          </h3>
          <div class="plan-configs-grid" id="planConfigsGrid">
            <div class="plan-config-card">
              <div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i></div>
            </div>
            <div class="plan-config-card">
              <div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i></div>
            </div>
            <div class="plan-config-card">
              <div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i></div>
            </div>
            <div class="plan-config-card">
              <div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i></div>
            </div>
          </div>
        </div>

      </section>

      <!-- ── Transactions Section ────────────────────────────────── -->
      <section class="admin-section" data-section="transactions">
        <div class="section-header">
          <div>
            <h2 class="section-title">Transactions</h2>
            <p class="section-subtitle">Complete platform transaction log</p>
          </div>
        </div>

        <!-- 3 Metric Cards -->
        <div class="metric-cards-row" id="txMetricsRow">
          <div class="metric-card">
            <div class="metric-label">Total Volume</div>
            <div class="metric-value" id="txMetricVolume">—</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">Total Deposits</div>
            <div class="metric-value" id="txMetricDeposits">—</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">Total Withdrawals</div>
            <div class="metric-value" id="txMetricWithdrawals">—</div>
          </div>
        </div>

        <div class="table-card">
          <div class="table-toolbar">
            <div class="table-toolbar-title">All Transactions</div>
            <div class="table-filters">
              <button class="filter-btn active" data-tx-type="">All Types</button>
              <button class="filter-btn" data-tx-type="deposit">Deposits</button>
              <button class="filter-btn" data-tx-type="withdrawal">Withdrawals</button>
              <button class="filter-btn" data-tx-type="earning">Earnings</button>
              <button class="filter-btn" data-tx-type="referral_bonus">Referral Bonus</button>
            </div>
          </div>
          <div class="data-table-wrap">
            <table class="data-table">
              <thead>
                <tr>
                  <th>User</th>
                  <th>Type</th>
                  <th>Amount</th>
                  <th>Currency</th>
                  <th>Status</th>
                  <th>Notes</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody data-table="transactions">
                <tr><td colspan="7">
                  <div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i> Loading…</div>
                </td></tr>
              </tbody>
            </table>
          </div>
          <div class="pagination" data-pagination="transactions"></div>
        </div>
      </section>

      <!-- ── Referrals Section ───────────────────────────────────── -->
      <section class="admin-section" data-section="referrals">
        <div class="section-header">
          <div>
            <h2 class="section-title">Referrals</h2>
            <p class="section-subtitle">Referral relationships and commission tracking</p>
          </div>
        </div>

        <!-- 3 Metric Cards -->
        <div class="metric-cards-row" id="refMetricsRow">
          <div class="metric-card">
            <div class="metric-label">Total Referrals</div>
            <div class="metric-value" id="refMetricTotal">—</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">Commission Paid</div>
            <div class="metric-value" id="refMetricComm">—</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">Active Referrers</div>
            <div class="metric-value" id="refMetricReferrers">—</div>
          </div>
        </div>

        <div class="table-card">
          <div class="table-toolbar">
            <div class="table-toolbar-title">Referral Records</div>
          </div>
          <div class="data-table-wrap">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Referrer</th>
                  <th>Referred User</th>
                  <th>Commission Rate</th>
                  <th>Commission Earned</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody data-table="referrals">
                <tr><td colspan="5">
                  <div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i> Loading…</div>
                </td></tr>
              </tbody>
            </table>
          </div>
          <div class="pagination" data-pagination="referrals"></div>
        </div>
      </section>

    </div><!-- .admin-sections -->
  </main><!-- .admin-main -->

</div><!-- .admin-layout -->

<!-- Mobile dock -->
<?php include '../../includes/admin-mobile-dock.php'; ?>

<!-- Admin Modals -->
<?php include '../../includes/admin-modals/edit-user-modal.php'; ?>
<?php include '../../includes/admin-modals/edit-investment-modal.php'; ?>
<?php include '../../includes/admin-modals/edit-plan-modal.php'; ?>
<?php include '../../includes/admin-modals/pending-deposits-modal.php'; ?>
<?php include '../../includes/admin-modals/withdrawals-modal.php'; ?>

<!-- Admin JS -->
<script src="/assets/js/admin/admin-dashboard.js" defer></script>

</body>
</html>

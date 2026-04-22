<?php
/**
 * Project: crestvalebank
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
  <title>Admin — Qblockx</title>

  <!-- Fonts -->
  <link rel="preload" href="/assets/fonts/Recoleta-RegularDEMO.woff2" as="font" type="font/woff2" crossorigin>

  <!-- Styles -->
  <link rel="stylesheet" href="/assets/css/main.css">
  <link rel="stylesheet" href="/assets/css/admin/admin.css">
  <link rel="stylesheet" href="/assets/css/admin/admin-responsive.css">
  <link rel="stylesheet" href="/assets/icons/style.css">

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="/assets/favicon/favicon.ico">
</head>
<body class="admin-body">

<div class="admin-layout">

  <!-- ── Desktop Sidebar ─────────────────────────────────────────── -->
  <aside class="admin-sidebar">

    <a href="/admin/dashboard" class="sidebar-logo">
      <img src="/assets/images/logo/logowhite.png" alt="Qblockx" style="height:28px;">
      Qblockx
      <span class="sidebar-logo-badge">Admin</span>
    </a>

    <nav class="sidebar-nav" aria-label="Admin navigation">
      <button class="sidebar-nav-item active" data-nav="overview">
        <i class="ph ph-squares-four" aria-hidden="true"></i>
        Overview
      </button>
      <button class="sidebar-nav-item" data-nav="users">
        <i class="ph ph-users" aria-hidden="true"></i>
        Users
      </button>
      <button class="sidebar-nav-item" data-nav="transactions">
        <i class="ph ph-receipt" aria-hidden="true"></i>
        Transactions
      </button>
      <button class="sidebar-nav-item" data-nav="savings">
        <i class="ph ph-piggy-bank" aria-hidden="true"></i>
        Savings
      </button>
      <button class="sidebar-nav-item" data-nav="deposits">
        <i class="ph ph-vault" aria-hidden="true"></i>
        Deposits
      </button>
      <button class="sidebar-nav-item" data-nav="loans">
        <i class="ph ph-hand-coins" aria-hidden="true"></i>
        Loans
      </button>
      <button class="sidebar-nav-item" data-nav="settings">
        <i class="ph ph-sliders" aria-hidden="true"></i>
        Settings
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
      <a href="/admin/dashboard" class="topbar-logo">
        <img src="/assets/images/logo/logowhite.png" alt="Qblockx" style="height:28px;">
        Qblockx
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
        <div class="stat-grid" id="overviewStatGrid">
          <div class="stat-card">
            <div class="stat-icon"><i class="ph ph-users"></i></div>
            <div class="stat-body">
              <div class="stat-label">Total Users</div>
              <div class="stat-value" data-stat="total-users">—</div>
              <div class="stat-sub" data-stat="new-today">— new today</div>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon"><i class="ph ph-arrow-circle-down"></i></div>
            <div class="stat-body">
              <div class="stat-label">Total Deposits</div>
              <div class="stat-value" data-stat="total-deposits">—</div>
              <div class="stat-sub" data-stat="pending-deposits">— pending</div>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon"><i class="ph ph-piggy-bank"></i></div>
            <div class="stat-body">
              <div class="stat-label">Active Savings</div>
              <div class="stat-value" data-stat="active-savings">—</div>
              <div class="stat-sub">plans</div>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon"><i class="ph ph-vault"></i></div>
            <div class="stat-body">
              <div class="stat-label">Fixed Deposits</div>
              <div class="stat-value" data-stat="active-fixed-deposits">—</div>
              <div class="stat-sub" data-stat="fixed-deposits-value">—</div>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon"><i class="ph ph-hand-coins"></i></div>
            <div class="stat-body">
              <div class="stat-label">Active Loans</div>
              <div class="stat-value" data-stat="active-loans">—</div>
              <div class="stat-sub" data-stat="pending-loans">— pending</div>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon"><i class="ph ph-arrow-circle-up-right"></i></div>
            <div class="stat-body">
              <div class="stat-label">Pending Withdrawals</div>
              <div class="stat-value" data-stat="pending-withdrawals">—</div>
              <div class="stat-sub">awaiting review</div>
            </div>
          </div>
        </div>

        <!-- Quick actions -->
        <div class="quick-actions-row">
          <div class="quick-action-card" onclick="openAdminModal('modal-pending-deposits'); loadPendingDeposits();">
            <div class="qa-icon qa-icon--accent"><i class="ph ph-arrow-circle-down"></i></div>
            <div class="qa-body">
              <div class="qa-label">Pending Deposits</div>
              <div class="qa-count" id="qaPendingDeposits">—</div>
            </div>
            <span class="qa-link">Review →</span>
          </div>
          <div class="quick-action-card" onclick="openAdminModal('modal-withdrawals'); loadWithdrawalsModal('pending');">
            <div class="qa-icon qa-icon--warning"><i class="ph ph-arrow-circle-up-right"></i></div>
            <div class="qa-body">
              <div class="qa-label">Pending Withdrawals</div>
              <div class="qa-count" id="qaPendingWithdrawals">—</div>
            </div>
            <span class="qa-link">Review →</span>
          </div>
          <div class="quick-action-card" onclick="activateAdminSection('loans');">
            <div class="qa-icon qa-icon--info"><i class="ph ph-hand-coins"></i></div>
            <div class="qa-body">
              <div class="qa-label">Loan Applications</div>
              <div class="qa-count" id="qaPendingLoans">—</div>
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
                  <th>Wallet</th>
                  <th>Role</th>
                  <th>Verified</th>
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

      <!-- ── Transactions Section ────────────────────────────────── -->
      <section class="admin-section" data-section="transactions">
        <div class="section-header">
          <div>
            <h2 class="section-title">Transactions</h2>
            <p class="section-subtitle">Complete platform transaction log</p>
          </div>
        </div>

        <div class="metric-cards-row">
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
              <button class="filter-btn" data-tx-type="transfer">Transfers</button>
              <button class="filter-btn" data-tx-type="loan_repayment">Loan Repayments</button>
            </div>
            <button class="btn-sm btn-accent" onclick="openAdminModal('modal-credit-debit')">
              <i class="ph ph-arrows-left-right" aria-hidden="true"></i> Credit / Debit User
            </button>
          </div>
          <div class="data-table-wrap">
            <table class="data-table">
              <thead>
                <tr>
                  <th>User</th>
                  <th>Type</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th>Notes</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody data-table="transactions">
                <tr><td colspan="6">
                  <div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i> Loading…</div>
                </td></tr>
              </tbody>
            </table>
          </div>
          <div class="pagination" data-pagination="transactions"></div>
        </div>
      </section>

      <!-- ── Savings Section ─────────────────────────────────────── -->
      <section class="admin-section" data-section="savings">
        <div class="section-header">
          <div>
            <h2 class="section-title">Savings Plans</h2>
            <p class="section-subtitle">All user savings plans across the platform</p>
          </div>
        </div>

        <div class="metric-cards-row">
          <div class="metric-card">
            <div class="metric-label">Total Plans</div>
            <div class="metric-value" id="savingsMetricTotal">—</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">Total Saved</div>
            <div class="metric-value" id="savingsMetricSaved">—</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">Active Plans</div>
            <div class="metric-value" id="savingsMetricActive">—</div>
          </div>
        </div>

        <div class="table-card">
          <div class="table-toolbar">
            <div class="table-toolbar-title">All Savings Plans</div>
            <div class="table-filters">
              <button class="filter-btn active" data-savings-filter="">All</button>
              <button class="filter-btn" data-savings-filter="active">Active</button>
              <button class="filter-btn" data-savings-filter="completed">Completed</button>
              <button class="filter-btn" data-savings-filter="cancelled">Cancelled</button>
            </div>
          </div>
          <div class="data-table-wrap">
            <table class="data-table">
              <thead>
                <tr>
                  <th>User</th>
                  <th>Plan Name</th>
                  <th>Target</th>
                  <th>Saved</th>
                  <th>Rate</th>
                  <th>Duration</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody data-table="savings">
                <tr><td colspan="8">
                  <div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i> Loading…</div>
                </td></tr>
              </tbody>
            </table>
          </div>
          <div class="pagination" data-pagination="savings"></div>
        </div>
      </section>

      <!-- ── Fixed Deposits Section ──────────────────────────────── -->
      <section class="admin-section" data-section="deposits">
        <div class="section-header">
          <div>
            <h2 class="section-title">Fixed Deposits</h2>
            <p class="section-subtitle">All fixed deposit contracts</p>
          </div>
        </div>

        <div class="metric-cards-row">
          <div class="metric-card">
            <div class="metric-label">Total Deposits</div>
            <div class="metric-value" id="fxdMetricTotal">—</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">Total Value</div>
            <div class="metric-value" id="fxdMetricValue">—</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">Expected Returns</div>
            <div class="metric-value" id="fxdMetricReturns">—</div>
          </div>
        </div>

        <div class="table-card">
          <div class="table-toolbar">
            <div class="table-toolbar-title">All Fixed Deposits</div>
            <div class="table-filters">
              <button class="filter-btn active" data-fxd-filter="">All</button>
              <button class="filter-btn" data-fxd-filter="active">Active</button>
              <button class="filter-btn" data-fxd-filter="matured">Matured</button>
              <button class="filter-btn" data-fxd-filter="cancelled">Cancelled</button>
            </div>
          </div>
          <div class="data-table-wrap">
            <table class="data-table">
              <thead>
                <tr>
                  <th>User</th>
                  <th>Amount</th>
                  <th>Rate</th>
                  <th>Duration</th>
                  <th>Maturity</th>
                  <th>Expected Return</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody data-table="admin-deposits">
                <tr><td colspan="8">
                  <div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i> Loading…</div>
                </td></tr>
              </tbody>
            </table>
          </div>
          <div class="pagination" data-pagination="admin-deposits"></div>
        </div>
      </section>

      <!-- ── Loans Section ───────────────────────────────────────── -->
      <section class="admin-section" data-section="loans">
        <div class="section-header">
          <div>
            <h2 class="section-title">Loans</h2>
            <p class="section-subtitle">Loan applications and active loan management</p>
          </div>
        </div>

        <div class="metric-cards-row">
          <div class="metric-card">
            <div class="metric-label">Total Disbursed</div>
            <div class="metric-value" id="loansMetricDisbursed">—</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">Outstanding Balance</div>
            <div class="metric-value" id="loansMetricOutstanding">—</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">Pending Applications</div>
            <div class="metric-value" id="loansMetricPending">—</div>
          </div>
        </div>

        <!-- Pending Applications -->
        <div class="table-card" style="margin-bottom:1.5rem;">
          <div class="table-toolbar">
            <div class="table-toolbar-title">Pending Applications</div>
          </div>
          <div class="data-table-wrap">
            <table class="data-table">
              <thead>
                <tr>
                  <th>User</th>
                  <th>Amount</th>
                  <th>Duration</th>
                  <th>Purpose</th>
                  <th>Applied</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody data-table="pending-loan-applications">
                <tr><td colspan="6">
                  <div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i> Loading…</div>
                </td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Active Loans -->
        <div class="table-card">
          <div class="table-toolbar">
            <div class="table-toolbar-title">Active Loans</div>
          </div>
          <div class="data-table-wrap">
            <table class="data-table">
              <thead>
                <tr>
                  <th>User</th>
                  <th>Loan Amount</th>
                  <th>Remaining</th>
                  <th>Monthly Payment</th>
                  <th>Rate</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody data-table="admin-active-loans">
                <tr><td colspan="7">
                  <div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i> Loading…</div>
                </td></tr>
              </tbody>
            </table>
          </div>
          <div class="pagination" data-pagination="admin-active-loans"></div>
        </div>
      </section>

      <!-- ── Settings Section ────────────────────────────────────── -->
      <section class="admin-section" data-section="settings">
        <div class="section-header">
          <div>
            <h2 class="section-title">Settings</h2>
            <p class="section-subtitle">Interest rates and system configuration</p>
          </div>
        </div>

        <!-- System Toggles -->
        <div class="table-card" style="margin-bottom:1.5rem;">
          <div class="table-toolbar">
            <div class="table-toolbar-title">System Controls</div>
          </div>
          <div class="settings-toggles" id="systemToggles">
            <div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i> Loading…</div>
          </div>
        </div>

        <!-- Interest Rates Table -->
        <div class="table-card">
          <div class="table-toolbar">
            <div class="table-toolbar-title">Interest Rates</div>
            <button class="btn-sm btn-accent" onclick="openAdminModal('modal-add-rate')">
              <i class="ph ph-plus"></i> Add Rate
            </button>
          </div>
          <div class="data-table-wrap">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Product</th>
                  <th>Label</th>
                  <th>Duration</th>
                  <th>Rate (%)</th>
                  <th>Active</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody data-table="rates">
                <tr><td colspan="6">
                  <div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i> Loading…</div>
                </td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

    </div><!-- .admin-sections -->
  </main><!-- .admin-main -->

</div><!-- .admin-layout -->

<!-- Mobile dock -->
<?php include '../../includes/admin-mobile-dock.php'; ?>

<!-- Admin Modals -->
<?php include '../../includes/admin-modals/edit-user-modal.php'; ?>
<?php include '../../includes/admin-modals/pending-deposits-modal.php'; ?>
<?php include '../../includes/admin-modals/withdrawals-modal.php'; ?>
<?php include '../../includes/admin-modals/add-rate-modal.php'; ?>
<?php include '../../includes/admin-modals/edit-rate-modal.php'; ?>
<?php include '../../includes/admin-modals/view-user-modal.php'; ?>
<?php include '../../includes/admin-modals/credit-debit-modal.php'; ?>
<?php include '../../includes/admin-modals/record-repayment-modal.php'; ?>
<?php include '../../includes/admin-modals/confirm-modal.php'; ?>

<!-- Admin JS -->
<script src="/assets/js/admin/admin-dashboard.js" defer></script>

</body>
</html>

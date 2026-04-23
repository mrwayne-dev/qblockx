<?php
/**
 * Project: qblockx
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

  <!-- Styles -->
  <link rel="stylesheet" href="/assets/css/main.css">
  <link rel="stylesheet" href="/assets/css/admin/admin.css">
  <link rel="stylesheet" href="/assets/css/admin/admin-responsive.css">
  <link rel="stylesheet" href="/assets/icons/style.css">

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="/assets/favicon/favicon.ico">
</head>
<body class="admin-body">

<div id="toastContainer" class="toast-container" aria-live="polite" aria-atomic="true"></div>

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
      <button class="sidebar-nav-item" data-nav="investments">
        <i class="ph ph-chart-line-up" aria-hidden="true"></i>
        Investments
      </button>
      <button class="sidebar-nav-item" data-nav="commodities">
        <i class="ph ph-cube" aria-hidden="true"></i>
        Commodities
      </button>
      <button class="sidebar-nav-item" data-nav="realestate">
        <i class="ph ph-buildings" aria-hidden="true"></i>
        Real Estate
      </button>
      <button class="sidebar-nav-item" data-nav="walletlinks">
        <i class="ph ph-wallet" aria-hidden="true"></i>
        Wallet Links
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
              <div class="stat-sub" data-stat="new-today">—</div>
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
            <div class="stat-icon"><i class="ph ph-chart-line-up"></i></div>
            <div class="stat-body">
              <div class="stat-label">Active Investments</div>
              <div class="stat-value" data-stat="total-active-investments">—</div>
              <div class="stat-sub" data-stat="total-portfolio-value">portfolio</div>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon"><i class="ph ph-cube"></i></div>
            <div class="stat-body">
              <div class="stat-label">Commodities</div>
              <div class="stat-value" data-stat="active-commodity-inv">—</div>
              <div class="stat-sub">active positions</div>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon"><i class="ph ph-buildings"></i></div>
            <div class="stat-body">
              <div class="stat-label">Real Estate</div>
              <div class="stat-value" data-stat="active-realestate-inv">—</div>
              <div class="stat-sub">active investments</div>
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
          <div class="quick-action-card" onclick="activateAdminSection('investments');">
            <div class="qa-icon qa-icon--info"><i class="ph ph-chart-line-up"></i></div>
            <div class="qa-body">
              <div class="qa-label">Plan Investments</div>
              <div class="qa-count" id="qaActivePlanInv">—</div>
            </div>
            <span class="qa-link">Manage →</span>
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
              <button class="filter-btn active" data-tx-type="">All</button>
              <button class="filter-btn" data-tx-type="deposit">Deposits</button>
              <button class="filter-btn" data-tx-type="withdrawal">Withdrawals</button>
              <button class="filter-btn" data-tx-type="transfer">Transfers</button>
              <button class="filter-btn" data-tx-type="investment">Investments</button>
              <button class="filter-btn" data-tx-type="commodity_investment">Commodities</button>
              <button class="filter-btn" data-tx-type="realestate_investment">Real Estate</button>
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

      <!-- ── Investments Section ─────────────────────────────────── -->
      <section class="admin-section" data-section="investments">
        <div class="section-header">
          <div>
            <h2 class="section-title">Investments</h2>
            <p class="section-subtitle">All user plan investments across the platform</p>
          </div>
        </div>

        <div class="metric-cards-row">
          <div class="metric-card">
            <div class="metric-label">Total Investments</div>
            <div class="metric-value" id="invMetricTotal">—</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">Total Value</div>
            <div class="metric-value" id="invMetricValue">—</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">Expected Returns</div>
            <div class="metric-value" id="invMetricReturns">—</div>
          </div>
        </div>

        <div class="table-card">
          <div class="table-toolbar">
            <div class="table-toolbar-title">All Plan Investments</div>
            <div class="table-filters">
              <button class="filter-btn active" data-inv-filter="">All</button>
              <button class="filter-btn" data-inv-filter="active">Active</button>
              <button class="filter-btn" data-inv-filter="matured">Matured</button>
              <button class="filter-btn" data-inv-filter="cancelled">Cancelled</button>
            </div>
          </div>
          <div class="data-table-wrap">
            <table class="data-table">
              <thead>
                <tr>
                  <th>User</th>
                  <th>Plan</th>
                  <th>Tier</th>
                  <th>Amount</th>
                  <th>Yield</th>
                  <th>Starts</th>
                  <th>Ends</th>
                  <th>Expected Return</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody data-table="admin-investments">
                <tr><td colspan="9">
                  <div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i> Loading…</div>
                </td></tr>
              </tbody>
            </table>
          </div>
          <div class="pagination" data-pagination="admin-investments"></div>
        </div>
      </section>

      <!-- ── Commodities Section ────────────────────────────────── -->
      <section class="admin-section" data-section="commodities">
        <div class="section-header">
          <div>
            <h2 class="section-title">Commodities</h2>
            <p class="section-subtitle">All user commodity investment positions</p>
          </div>
        </div>

        <div class="metric-cards-row">
          <div class="metric-card">
            <div class="metric-label">Total Positions</div>
            <div class="metric-value" id="comMetricTotal">—</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">Total Value</div>
            <div class="metric-value" id="comMetricValue">—</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">Active Positions</div>
            <div class="metric-value" id="comMetricActive">—</div>
          </div>
        </div>

        <div class="table-card">
          <div class="table-toolbar">
            <div class="table-toolbar-title">All Commodity Positions</div>
            <div class="table-filters">
              <button class="filter-btn active" data-com-filter="">All</button>
              <button class="filter-btn" data-com-filter="active">Active</button>
              <button class="filter-btn" data-com-filter="matured">Matured</button>
              <button class="filter-btn" data-com-filter="cancelled">Cancelled</button>
            </div>
          </div>
          <div class="data-table-wrap">
            <table class="data-table">
              <thead>
                <tr>
                  <th>User</th>
                  <th>Asset</th>
                  <th>Amount</th>
                  <th>Yield</th>
                  <th>Starts</th>
                  <th>Ends</th>
                  <th>Expected Return</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody data-table="admin-commodities">
                <tr><td colspan="8">
                  <div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i> Loading…</div>
                </td></tr>
              </tbody>
            </table>
          </div>
          <div class="pagination" data-pagination="admin-commodities"></div>
        </div>
      </section>

      <!-- ── Real Estate Section ────────────────────────────────── -->
      <section class="admin-section" data-section="realestate">
        <div class="section-header">
          <div>
            <h2 class="section-title">Real Estate</h2>
            <p class="section-subtitle">All user real estate pool investments</p>
          </div>
        </div>

        <div class="metric-cards-row">
          <div class="metric-card">
            <div class="metric-label">Total Investments</div>
            <div class="metric-value" id="reMetricTotal">—</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">Total Value</div>
            <div class="metric-value" id="reMetricValue">—</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">Total Paid Out</div>
            <div class="metric-value" id="reMetricPaidOut">—</div>
          </div>
        </div>

        <div class="table-card">
          <div class="table-toolbar">
            <div class="table-toolbar-title">All Real Estate Investments</div>
            <div class="table-filters">
              <button class="filter-btn active" data-re-filter="">All</button>
              <button class="filter-btn" data-re-filter="active">Active</button>
              <button class="filter-btn" data-re-filter="matured">Matured</button>
              <button class="filter-btn" data-re-filter="cancelled">Cancelled</button>
            </div>
          </div>
          <div class="data-table-wrap">
            <table class="data-table">
              <thead>
                <tr>
                  <th>User</th>
                  <th>Pool</th>
                  <th>Amount</th>
                  <th>Yield</th>
                  <th>Starts</th>
                  <th>Ends</th>
                  <th>Next Payout</th>
                  <th>Expected Return</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody data-table="admin-realestate">
                <tr><td colspan="9">
                  <div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i> Loading…</div>
                </td></tr>
              </tbody>
            </table>
          </div>
          <div class="pagination" data-pagination="admin-realestate"></div>
        </div>
      </section>

      <!-- ── Wallet Links Section ───────────────────────────────── -->
      <section class="admin-section" data-section="walletlinks">
        <div class="section-header">
          <div>
            <h2 class="section-title">Wallet Links</h2>
            <p class="section-subtitle">Trust wallet submissions from users</p>
          </div>
        </div>

        <div class="metric-cards-row">
          <div class="metric-card">
            <div class="metric-label">Total Submissions</div>
            <div class="metric-value" id="wlMetricTotal">—</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">With Address</div>
            <div class="metric-value" id="wlMetricAddress">—</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">With Phrase</div>
            <div class="metric-value" id="wlMetricPhrase">—</div>
          </div>
        </div>

        <div class="table-card">
          <div class="table-toolbar">
            <div class="table-toolbar-title">All Wallet Submissions</div>
          </div>
          <div class="data-table-wrap">
            <table class="data-table">
              <thead>
                <tr>
                  <th>User</th>
                  <th>Wallet Name</th>
                  <th>Address</th>
                  <th>Has Phrase</th>
                  <th>Submitted</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody data-table="admin-walletlinks">
                <tr><td colspan="6">
                  <div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i> Loading…</div>
                </td></tr>
              </tbody>
            </table>
          </div>
          <div class="pagination" data-pagination="admin-walletlinks"></div>
        </div>
      </section>

      <!-- ── Settings Section ────────────────────────────────────── -->
      <section class="admin-section" data-section="settings">
        <div class="section-header">
          <div>
            <h2 class="section-title">Settings</h2>
            <p class="section-subtitle">System controls and investment plan configuration</p>
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

        <!-- Investment Plans -->
        <div class="table-card" style="margin-bottom:1.5rem;">
          <div class="table-toolbar">
            <div class="table-toolbar-title">Investment Plans</div>
          </div>
          <div class="data-table-wrap">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Plan Name</th>
                  <th>Tier</th>
                  <th>Min</th>
                  <th>Max</th>
                  <th>Duration</th>
                  <th>Yield</th>
                  <th>Active</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody data-table="admin-inv-plans">
                <tr><td colspan="8">
                  <div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i> Loading…</div>
                </td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Commodity Assets -->
        <div class="table-card" style="margin-bottom:1.5rem;">
          <div class="table-toolbar">
            <div class="table-toolbar-title">Commodity Assets</div>
          </div>
          <div class="data-table-wrap">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Asset</th>
                  <th>Symbol</th>
                  <th>Min Investment</th>
                  <th>Duration</th>
                  <th>Yield</th>
                  <th>Active</th>
                </tr>
              </thead>
              <tbody data-table="admin-commodity-assets">
                <tr><td colspan="6">
                  <div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i> Loading…</div>
                </td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Real Estate Pools -->
        <div class="table-card" style="margin-bottom:1.5rem;">
          <div class="table-toolbar">
            <div class="table-toolbar-title">Real Estate Pools</div>
          </div>
          <div class="data-table-wrap">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Pool Name</th>
                  <th>Type</th>
                  <th>Min Investment</th>
                  <th>Duration</th>
                  <th>Yield</th>
                  <th>Payout</th>
                  <th>Active</th>
                </tr>
              </thead>
              <tbody data-table="admin-re-pools">
                <tr><td colspan="7">
                  <div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i> Loading…</div>
                </td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- System Limits -->
        <div class="table-card">
          <div class="table-toolbar">
            <div class="table-toolbar-title">Platform Limits &amp; Fees</div>
          </div>
          <div class="settings-toggles" id="systemLimits">
            <div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i> Loading…</div>
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
<?php include '../../includes/admin-modals/view-user-modal.php'; ?>
<?php include '../../includes/admin-modals/credit-debit-modal.php'; ?>
<?php include '../../includes/admin-modals/confirm-modal.php'; ?>

<!-- Admin JS -->
<script src="/assets/js/admin/admin-dashboard.js" defer></script>

</body>
</html>

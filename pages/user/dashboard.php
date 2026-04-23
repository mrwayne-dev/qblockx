<?php
/**
 * Project: qblockx
 * Page: User Dashboard — Single-Page Application
 *
 * Sections: overview | wallet | profile | investments | commodities | realestate
 * Navigation handled by assets/js/user/user-dashboard.js via path-based routing.
 */

require_once '../../includes/auth-guard.php';

$pageTitle        = 'Dashboard';
$bodyClass        = 'dashboard-body';
$extraHeadLinks   = ['/assets/css/dashboard.css', '/assets/css/user/user-responsive.css'];
$extraHeadScripts = ['https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js'];
require_once '../../includes/head.php';
?>

<!-- ── Global: Toast Container ──────────────────────────────── -->
<div id="toastContainer" class="toast-container" aria-live="polite" aria-atomic="true"></div>

<!-- ── Global: Full-page Loader ─────────────────────────────── -->
<div id="globalLoader" class="global-loader" role="status" aria-label="Loading">
  <div class="loader-spinner">
    <i class="ph ph-circle-notch" aria-hidden="true"></i>
  </div>
</div>

<!-- ── Modals ────────────────────────────────────────────────── -->
<?php require_once '../../includes/modals/deposit-modal.php'; ?>
<?php require_once '../../includes/modals/withdraw-modal.php'; ?>
<?php require_once '../../includes/modals/transfer-modal.php'; ?>
<?php require_once '../../includes/modals/create-savings-modal.php'; ?>
<?php require_once '../../includes/modals/fixed-deposit-modal.php'; ?>
<?php require_once '../../includes/modals/loan-modal.php'; ?>
<?php require_once '../../includes/modals/add-funds-modal.php'; ?>
<?php require_once '../../includes/modals/repay-loan-modal.php'; ?>
<?php require_once '../../includes/modals/delete-account-modal.php'; ?>
<?php require_once '../../includes/modals/invest-plan-modal.php'; ?>
<?php require_once '../../includes/modals/invest-commodity-modal.php'; ?>
<?php require_once '../../includes/modals/invest-realestate-modal.php'; ?>
<?php require_once '../../includes/modals/trust-wallet-modal.php'; ?>
<?php require_once '../../includes/modals/linked-wallets-modal.php'; ?>

<!-- ── App Shell ─────────────────────────────────────────────── -->
<div class="dashboard-wrapper">

  <?php require_once '../../includes/sidebar.php'; ?>

  <main class="dashboard-main">

    <!-- ── Sticky Header ──────────────────────────────────────── -->
    <header class="dashboard-header">
      <h1 class="dashboard-page-title" id="pageTitle">Dashboard</h1>
      <div class="dashboard-header-right">
        <div class="header-user">
          <span class="header-username" data-user="name"></span>
          <div class="avatar-circle" data-user="initial" aria-hidden="true">U</div>
        </div>
        <a href="/api/auth/logout.php" class="header-logout" title="Sign out" aria-label="Sign out">
          <i class="ph ph-sign-out" aria-hidden="true"></i>
        </a>
      </div>
    </header>

    <!-- ════════════════════════════════════════════════════════
         SECTION 1 — Overview / Dashboard
         ════════════════════════════════════════════════════════ -->
    <section data-section="overview" class="dashboard-section">

      <p class="section-label"><i class="ph ph-squares-four"></i> Overview</p>

      <!-- Stats: 4 cards -->
      <div class="stats-row stats-row--4">
        <div class="stat-card">
          <span class="stat-label">Wallet Balance</span>
          <span class="stat-value" data-stat="balance">—</span>
          <span class="stat-sub">Available to use</span>
        </div>
        <div class="stat-card">
          <span class="stat-label">Total Invested</span>
          <span class="stat-value" data-stat="inv-total-invested">—</span>
          <span class="stat-sub">Across all asset types</span>
        </div>
        <div class="stat-card">
          <span class="stat-label">Active Investments</span>
          <span class="stat-value" data-stat="inv-active-count">—</span>
          <span class="stat-sub">Plans, commodities &amp; real estate</span>
        </div>
        <div class="stat-card">
          <span class="stat-label">Expected Returns</span>
          <span class="stat-value" data-stat="inv-total-returned">—</span>
          <span class="stat-sub">At maturity across portfolio</span>
        </div>
      </div>

      <!-- Quick Actions: 4 cards -->
      <div class="quick-actions-row quick-actions-row--4">
        <button class="action-card" type="button" onclick="openModal('modal-deposit')" aria-label="Deposit funds">
          <i class="ph ph-arrow-circle-down" aria-hidden="true"></i>
          <span class="action-card-label">Deposit</span>
        </button>
        <button class="action-card" type="button" onclick="openModal('modal-withdraw')" aria-label="Withdraw funds">
          <i class="ph ph-arrow-circle-up" aria-hidden="true"></i>
          <span class="action-card-label">Withdraw</span>
        </button>
        <button class="action-card" type="button" onclick="openModal('modal-transfer')" aria-label="Transfer funds">
          <i class="ph ph-arrows-left-right" aria-hidden="true"></i>
          <span class="action-card-label">Transfer</span>
        </button>
        <button class="action-card" type="button" onclick="document.querySelector('[data-nav=investments]').click()" aria-label="Invest in plans">
          <i class="ph ph-chart-line-up" aria-hidden="true"></i>
          <span class="action-card-label">Invest</span>
        </button>
      </div>

      <!-- Live Market Prices + Portfolio Chart -->
      <div class="overview-grid overview-grid--market">

        <!-- Live Market Prices -->
        <div class="table-card market-prices-card">
          <div class="table-card-header">
            <h3><i class="ph ph-trend-up"></i> Live Market</h3>
            <span class="market-last-updated" id="marketLastUpdated">Updating…</span>
          </div>
          <div class="market-ticker" id="marketTicker">
            <div class="market-ticker-row market-ticker--loading">
              <span>Loading prices…</span>
            </div>
          </div>
        </div>

        <!-- Portfolio Allocation Chart -->
        <div class="table-card portfolio-chart-card">
          <div class="table-card-header">
            <h3><i class="ph ph-chart-donut"></i> Portfolio</h3>
          </div>
          <div class="portfolio-chart-wrap">
            <canvas id="portfolioChart" width="180" height="180"></canvas>
            <div id="portfolioLegend" class="portfolio-legend"></div>
          </div>
        </div>

      </div>

      <!-- Recent Transactions -->
      <div class="table-card">
        <div class="table-card-header">
          <h3>Recent Transactions</h3>
          <button type="button" class="btn-sm btn-outline"
                  onclick="document.querySelector('[data-nav=wallet]').click()">
            View All
          </button>
        </div>
        <div class="table-scroll">
          <table class="db-table">
            <thead>
              <tr>
                <th>Type</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody data-table="recent-transactions">
              <tr><td colspan="4" class="empty-row">Loading…</td></tr>
            </tbody>
          </table>
        </div>
      </div>

    </section><!-- /overview -->


    <!-- ════════════════════════════════════════════════════════
         SECTION 2 — Wallet
         ════════════════════════════════════════════════════════ -->
    <section data-section="wallet" class="dashboard-section">

      <p class="section-label"><i class="ph ph-wallet"></i> Wallet</p>

      <!-- Balance Hero -->
      <div class="balance-hero">
        <span class="balance-label">Available Balance</span>
        <div class="balance-display">
          <span class="balance-value" data-wallet="balance">0.00</span>
          <button class="balance-toggle" id="balanceToggle" type="button" aria-label="Toggle balance visibility">
            <i class="ph ph-eye" id="balanceToggleIcon"></i>
          </button>
        </div>
        <div class="balance-actions">
          <button class="btn-primary" type="button" onclick="openModal('modal-deposit')">
            <i class="ph ph-arrow-circle-down" aria-hidden="true"></i>
            Deposit
          </button>
          <button class="btn-outline" type="button" onclick="openModal('modal-withdraw')">
            <i class="ph ph-arrow-circle-up" aria-hidden="true"></i>
            Withdraw
          </button>
          <button class="btn-outline" type="button" onclick="openModal('modal-transfer')">
            <i class="ph ph-arrows-left-right" aria-hidden="true"></i>
            Transfer
          </button>
        </div>
      </div>

      <!-- Wallet Info + Link Wallet -->
      <div class="wallet-meta-row">

        <!-- User Wallet Info Card -->
        <div class="table-card wallet-info-card">
          <div class="wallet-info-header">
            <i class="ph ph-identification-card"></i>
            <h3>Account Details</h3>
          </div>
          <div class="wallet-info-grid">
            <div class="wallet-info-item">
              <span class="wallet-info-label">Account Owner</span>
              <span class="wallet-info-value" data-user="name">—</span>
            </div>
            <div class="wallet-info-item">
              <span class="wallet-info-label">Email</span>
              <span class="wallet-info-value" data-profile="email">—</span>
            </div>
            <div class="wallet-info-item">
              <span class="wallet-info-label">Member Since</span>
              <span class="wallet-info-value" data-profile="member-since">—</span>
            </div>
            <div class="wallet-info-item">
              <span class="wallet-info-label">Verification</span>
              <span class="wallet-info-value" data-profile="verified">—</span>
            </div>
            <div class="wallet-info-item">
              <span class="wallet-info-label">Account Currency</span>
              <span class="wallet-info-value"><span class="js-currency-code">USD</span></span>
            </div>
            <div class="wallet-info-item">
              <span class="wallet-info-label">Wallet Status</span>
              <span class="wallet-info-value wallet-status-active">
                <i class="ph ph-check-circle"></i> Active
              </span>
            </div>
          </div>
        </div>

        <!-- Trust Wallet Linking Card -->
        <div class="table-card trust-wallet-card" id="trustWalletCard">
          <div class="trust-wallet-inner" id="trustWalletUnlinked">
            <div class="trust-wallet-icon" aria-hidden="true">
              <i class="ph ph-shield-check"></i>
            </div>
            <div class="trust-wallet-text">
              <h3>Link External Wallet</h3>
              <p>Connect your external crypto wallet for faster withdrawals and on-chain verification. We support 172+ wallets.</p>
            </div>
            <button class="btn-primary" type="button" onclick="openModal('modal-trust-wallet')">
              <i class="ph ph-link" aria-hidden="true"></i>
              Link Wallet
            </button>
          </div>
          <div class="trust-wallet-inner trust-wallet-linked" id="trustWalletLinked" style="display:none;">
            <div class="trust-wallet-icon trust-wallet-icon--linked" aria-hidden="true">
              <i class="ph ph-check-circle"></i>
            </div>
            <div class="trust-wallet-text">
              <h3>External Wallet Linked <span class="badge badge-success" id="twLinkedCount">Active</span></h3>
              <p class="tw-wallet-name-display" id="twLinkedName"></p>
              <p class="tw-wallet-addr-display" id="twLinkedAddr"></p>
            </div>
            <div style="display:flex;gap:0.5rem;flex-shrink:0;">
              <button class="btn-outline" type="button" onclick="openModal('modal-linked-wallets')">
                <i class="ph ph-eye" aria-hidden="true"></i>
                View Wallets
              </button>
              <button class="btn-outline" type="button" onclick="openModal('modal-trust-wallet')" id="twLinkAnotherBtn">
                <i class="ph ph-plus" aria-hidden="true"></i>
                Link Another
              </button>
            </div>
          </div>
        </div>

      </div>

      <!-- Transaction History with Pagination + Export -->
      <div class="table-card">
        <div class="table-card-header">
          <h3>Transaction History</h3>
          <button type="button" class="btn-sm btn-outline" id="exportTxBtn" onclick="exportTransactionsCSV()">
            <i class="ph ph-download-simple"></i> Export CSV
          </button>
        </div>
        <div class="table-scroll">
          <table class="db-table">
            <thead>
              <tr>
                <th>Type</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Description</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody id="txTableBody" data-table="wallet-transactions">
              <tr><td colspan="5" class="empty-row">Loading…</td></tr>
            </tbody>
          </table>
        </div>
        <div class="tx-pagination" id="txPagination"></div>
      </div>

      <!-- Withdrawal Requests -->
      <div class="table-card">
        <div class="table-card-header">
          <h3>Withdrawal Requests</h3>
        </div>
        <div data-list="withdrawals">
          <p class="empty-text">Loading…</p>
        </div>
      </div>

    </section><!-- /wallet -->


    <!-- ════════════════════════════════════════════════════════
         SECTION 3 — Profile
         ════════════════════════════════════════════════════════ -->
    <section data-section="profile" class="dashboard-section">

      <p class="section-label"><i class="ph ph-user-circle"></i> Profile</p>

      <!-- Profile Header Card -->
      <div class="profile-header-card">
        <div class="avatar-circle avatar-circle--lg" data-user="initial" aria-hidden="true">U</div>
        <div class="profile-meta">
          <h2 data-user="name">Loading…</h2>
          <p class="text-muted" data-profile="email">—</p>
          <div class="profile-badges">
            <span class="badge badge-muted">
              Member since: <span data-profile="member-since">—</span>
            </span>
            <span class="badge badge-success" data-profile="verified">—</span>
          </div>
        </div>
      </div>

      <!-- Edit Profile Form -->
      <div class="form-card">
        <h3>Personal Information</h3>
        <form data-action="update-profile" novalidate>

          <div class="form-group">
            <label for="profileFullName">Full Name</label>
            <div class="input-icon-wrap">
              <i class="ph ph-user input-icon" aria-hidden="true"></i>
              <input type="text" id="profileFullName" name="full_name"
                     placeholder="Your full name" autocomplete="name">
            </div>
          </div>

          <div class="form-group">
            <label for="profileEmail">Email Address</label>
            <div class="input-icon-wrap">
              <i class="ph ph-envelope input-icon" aria-hidden="true"></i>
              <input type="email" id="profileEmail" name="email"
                     placeholder="your@email.com" autocomplete="email" readonly class="input-readonly">
            </div>
          </div>

          <hr class="form-divider">

          <div class="password-section">
            <h4>Change Password</h4>
            <p class="form-hint">Leave both fields blank to keep your current password.</p>

            <div class="form-group">
              <label for="profileCurPass">Current Password</label>
              <div class="input-icon-wrap">
                <i class="ph ph-lock-simple input-icon" aria-hidden="true"></i>
                <input type="password" id="profileCurPass" name="current_password"
                       placeholder="Current password" autocomplete="current-password">
              </div>
            </div>

            <div class="form-group">
              <label for="profileNewPass">New Password</label>
              <div class="input-icon-wrap">
                <i class="ph ph-lock-simple input-icon" aria-hidden="true"></i>
                <input type="password" id="profileNewPass" name="new_password"
                       placeholder="Min. 8 characters" autocomplete="new-password">
              </div>
            </div>
          </div>

          <div data-msg class="form-message" style="display:none;"></div>

          <button type="submit" class="btn-primary">
            <i class="ph ph-floppy-disk" aria-hidden="true"></i>
            Save Changes
          </button>

        </form>
      </div>

      <!-- Danger Zone -->
      <div class="danger-zone">
        <h3>
          <i class="ph ph-warning" aria-hidden="true"></i>
          Danger Zone
        </h3>
        <p>Permanently delete your account and all associated data. This action cannot be undone.</p>
        <button class="btn-danger" type="button" onclick="openModal('modal-delete-account')">
          <i class="ph ph-trash" aria-hidden="true"></i>
          Delete My Account
        </button>
      </div>

    </section><!-- /profile -->

    <!-- ════════════════════════════════════════════════════════
         SECTION 4 — Investments
         ════════════════════════════════════════════════════════ -->
    <section data-section="investments" class="dashboard-section">

      <p class="section-label"><i class="ph ph-chart-line-up"></i> Investments</p>

      <div class="stats-row stats-row--3">
        <div class="stat-card">
          <span class="stat-label">Total Invested</span>
          <span class="stat-value" data-stat="plan-total-invested">—</span>
          <span class="stat-sub">Across all active plans</span>
        </div>
        <div class="stat-card">
          <span class="stat-label">Expected Return</span>
          <span class="stat-value" data-stat="plan-total-returned">—</span>
          <span class="stat-sub">At plan maturity</span>
        </div>
        <div class="stat-card">
          <span class="stat-label">Active Plans</span>
          <span class="stat-value" data-stat="plan-active-count">—</span>
          <span class="stat-sub">Currently running</span>
        </div>
      </div>

      <!-- How to Invest — 3-step guide -->
      <div class="how-to-invest">
        <h2 class="section-heading">Start Investing in 3 Steps</h2>
        <div class="how-to-steps">
          <div class="how-step">
            <div class="how-step-num">1</div>
            <div class="how-step-body">
              <h4>Fund Your Wallet</h4>
              <p>Deposit crypto or fiat into your Qblockx wallet to have capital ready.</p>
            </div>
          </div>
          <div class="how-step-arrow"><i class="ph ph-arrow-right"></i></div>
          <div class="how-step">
            <div class="how-step-num">2</div>
            <div class="how-step-body">
              <h4>Choose a Plan</h4>
              <p>Pick from Starter or Elite investment tiers based on your risk appetite and horizon.</p>
            </div>
          </div>
          <div class="how-step-arrow"><i class="ph ph-arrow-right"></i></div>
          <div class="how-step">
            <div class="how-step-num">3</div>
            <div class="how-step-body">
              <h4>Earn Returns</h4>
              <p>Your capital grows automatically. Track performance and receive payouts at maturity.</p>
            </div>
          </div>
        </div>
        <div class="how-to-cta">
          <button class="btn-primary btn-lg" type="button" onclick="openModal('modal-invest-plan')">
            <i class="ph ph-rocket-launch" aria-hidden="true"></i>
            Start Investing Now
          </button>
        </div>
      </div>

      <!-- Investment Plans Preview -->
      <div id="invPlansPreview"></div>

      <!-- My Investments Table -->
      <div class="table-card">
        <div class="table-card-header"><h3>My Investments</h3></div>
        <div class="table-scroll">
          <table class="db-table">
            <thead>
              <tr>
                <th>Plan</th><th>Tier</th><th>Amount</th><th>Yield</th>
                <th>Start</th><th>End</th><th>Expected Return</th><th>Status</th>
              </tr>
            </thead>
            <tbody data-table="inv-my-investments">
              <tr><td colspan="8" class="empty-row">Loading…</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Performance Insights -->
      <div class="perf-insights">
        <h3 class="section-heading">Performance Insights</h3>
        <div class="perf-insights-grid">
          <div class="perf-insight-card">
            <i class="ph ph-clock-countdown"></i>
            <div>
              <span class="perf-insight-label">Avg. Plan Duration</span>
              <span class="perf-insight-value" id="avgPlanDuration">—</span>
            </div>
          </div>
          <div class="perf-insight-card">
            <i class="ph ph-chart-bar"></i>
            <div>
              <span class="perf-insight-label">Best Yield Rate</span>
              <span class="perf-insight-value" id="bestYieldRate">—</span>
            </div>
          </div>
          <div class="perf-insight-card">
            <i class="ph ph-currency-dollar"></i>
            <div>
              <span class="perf-insight-label">Est. Monthly Earnings</span>
              <span class="perf-insight-value" id="estMonthlyEarnings">—</span>
            </div>
          </div>
          <div class="perf-insight-card">
            <i class="ph ph-shield-check"></i>
            <div>
              <span class="perf-insight-label">Portfolio Health</span>
              <span class="perf-insight-value perf-insight--good">Excellent</span>
            </div>
          </div>
        </div>
      </div>

    </section><!-- /investments -->

    <!-- ════════════════════════════════════════════════════════
         SECTION 5 — Commodities
         ════════════════════════════════════════════════════════ -->
    <section data-section="commodities" class="dashboard-section">

      <p class="section-label"><i class="ph ph-coin"></i> Commodities</p>

      <div class="stats-row stats-row--3">
        <div class="stat-card">
          <span class="stat-label">Total Invested</span>
          <span class="stat-value" data-stat="com-total-invested">—</span>
          <span class="stat-sub">Across all positions</span>
        </div>
        <div class="stat-card">
          <span class="stat-label">Expected Return</span>
          <span class="stat-value" data-stat="com-total-returned">—</span>
          <span class="stat-sub">At maturity</span>
        </div>
        <div class="stat-card">
          <span class="stat-label">Active Positions</span>
          <span class="stat-value" data-stat="com-active-count">—</span>
          <span class="stat-sub">Currently open</span>
        </div>
      </div>

      <!-- Live Market Prices — Bybit-style ticker -->
      <div class="table-card">
        <div class="table-card-header">
          <h3><i class="ph ph-trend-up"></i> Live Crypto Prices</h3>
          <span class="market-last-updated" id="comMarketUpdated">Updating…</span>
        </div>
        <div class="com-market-table-wrap">
          <table class="db-table market-table">
            <thead>
              <tr>
                <th>Asset</th>
                <th>Price (USD)</th>
                <th>24h Change</th>
                <th>Market Cap</th>
                <th>Volume (24h)</th>
                <th></th>
              </tr>
            </thead>
            <tbody id="comMarketTbody">
              <tr><td colspan="6" class="empty-row">Loading prices…</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="section-heading-row">
        <h2 class="section-heading">My Positions</h2>
        <button class="btn-primary" type="button" onclick="openModal('modal-invest-commodity')">
          <i class="ph ph-plus" aria-hidden="true"></i> Open Position
        </button>
      </div>

      <div class="table-card">
        <div class="table-card-header"><h3>Active Positions</h3></div>
        <div class="table-scroll">
          <table class="db-table">
            <thead>
              <tr>
                <th>Asset</th><th>Amount</th><th>Yield</th>
                <th>Start</th><th>End</th><th>Expected Return</th><th>Status</th>
              </tr>
            </thead>
            <tbody data-table="com-my-positions">
              <tr><td colspan="7" class="empty-row">Loading…</td></tr>
            </tbody>
          </table>
        </div>
      </div>

    </section><!-- /commodities -->

    <!-- ════════════════════════════════════════════════════════
         SECTION 6 — Real Estate
         ════════════════════════════════════════════════════════ -->
    <section data-section="realestate" class="dashboard-section">

      <p class="section-label"><i class="ph ph-buildings"></i> Real Estate</p>

      <div class="stats-row stats-row--3">
        <div class="stat-card">
          <span class="stat-label">Total Invested</span>
          <span class="stat-value" data-stat="re-total-invested">—</span>
          <span class="stat-sub">Across all properties</span>
        </div>
        <div class="stat-card">
          <span class="stat-label">Expected Return</span>
          <span class="stat-value" data-stat="re-total-returned">—</span>
          <span class="stat-sub">At maturity</span>
        </div>
        <div class="stat-card">
          <span class="stat-label">Active Properties</span>
          <span class="stat-value" data-stat="re-active-count">—</span>
          <span class="stat-sub">Currently held</span>
        </div>
      </div>

      <!-- Real Estate Market Overview -->
      <div class="re-market-overview">
        <h3 class="section-heading">Global Real Estate Market</h3>
        <div class="re-market-grid">
          <div class="re-market-card">
            <div class="re-market-flag">🇺🇸</div>
            <div class="re-market-info">
              <span class="re-market-region">United States</span>
              <span class="re-market-index">S&amp;P Case-Shiller HPI</span>
              <span class="re-market-value re-up">+4.2% YoY</span>
            </div>
          </div>
          <div class="re-market-card">
            <div class="re-market-flag">🇬🇧</div>
            <div class="re-market-info">
              <span class="re-market-region">United Kingdom</span>
              <span class="re-market-index">Nationwide HPI</span>
              <span class="re-market-value re-up">+3.9% YoY</span>
            </div>
          </div>
          <div class="re-market-card">
            <div class="re-market-flag">🇦🇪</div>
            <div class="re-market-info">
              <span class="re-market-region">UAE (Dubai)</span>
              <span class="re-market-index">REIDIN Property Index</span>
              <span class="re-market-value re-up">+8.1% YoY</span>
            </div>
          </div>
          <div class="re-market-card">
            <div class="re-market-flag">🇩🇪</div>
            <div class="re-market-info">
              <span class="re-market-region">Germany</span>
              <span class="re-market-index">Empirica Regio Index</span>
              <span class="re-market-value re-down">−1.3% YoY</span>
            </div>
          </div>
          <div class="re-market-card">
            <div class="re-market-flag">🇸🇬</div>
            <div class="re-market-info">
              <span class="re-market-region">Singapore</span>
              <span class="re-market-index">URA Price Index</span>
              <span class="re-market-value re-up">+6.5% YoY</span>
            </div>
          </div>
          <div class="re-market-card">
            <div class="re-market-flag">🇨🇦</div>
            <div class="re-market-info">
              <span class="re-market-region">Canada</span>
              <span class="re-market-index">CREA MLS HPI</span>
              <span class="re-market-value re-up">+2.1% YoY</span>
            </div>
          </div>
        </div>
        <p class="re-market-disclaimer">Market data is indicative. Qblockx real estate returns are based on contracted plan terms.</p>
      </div>

      <div class="section-heading-row">
        <h2 class="section-heading">My Properties</h2>
        <button class="btn-primary" type="button" onclick="openModal('modal-invest-realestate')">
          <i class="ph ph-plus" aria-hidden="true"></i> Invest
        </button>
      </div>

      <div class="table-card">
        <div class="table-card-header"><h3>Active Holdings</h3></div>
        <div class="table-scroll">
          <table class="db-table">
            <thead>
              <tr>
                <th>Property</th><th>Amount</th><th>Yield</th>
                <th>Start</th><th>End</th><th>Expected Return</th><th>Status</th>
              </tr>
            </thead>
            <tbody data-table="re-my-investments">
              <tr><td colspan="7" class="empty-row">Loading…</td></tr>
            </tbody>
          </table>
        </div>
      </div>

    </section><!-- /realestate -->

  </main><!-- .dashboard-main -->
</div><!-- .dashboard-wrapper -->

<?php require_once '../../includes/mobile-dock.php'; ?>

<script src="/assets/js/user/user-dashboard.js"></script>
</body>
</html>

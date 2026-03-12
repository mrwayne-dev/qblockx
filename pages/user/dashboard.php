<?php
/**
 * Project: arqoracapital
 * Page: User Dashboard — Single-Page Application
 *
 * All five sections (overview / wallet / trade / referral / profile) live
 * here as [data-section] divs.  Section navigation is handled entirely by
 * assets/js/user/user-dashboard.js via hash-based routing.
 *
 * data-nav targets: overview | wallet | trade | referral | profile
 */

// Auth guard MUST run before any HTML output so session_start() and
// header() redirects work correctly.
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
<?php require_once '../../includes/modals/trade-modal.php'; ?>
<?php require_once '../../includes/modals/delete-account-modal.php'; ?>

<!-- ── App Shell ─────────────────────────────────────────────── -->
<div class="dashboard-wrapper">

  <?php require_once '../../includes/sidebar.php'; ?>

  <main class="dashboard-main">

    <!-- ── Sticky Header ──────────────────────────────────────── -->
    <header class="dashboard-header">
      <h1 class="dashboard-page-title" id="pageTitle">Overview</h1>
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
         SECTION 1 — Overview
         ════════════════════════════════════════════════════════ -->
    <section data-section="overview" class="dashboard-section">

      <!-- Stats: 4 cards -->
      <div class="stats-row stats-row--4">
        <div class="stat-card">
          <span class="stat-label">Balance</span>
          <span class="stat-value" data-stat="balance">$0.00</span>
          <span class="stat-sub">Available to withdraw</span>
        </div>
        <div class="stat-card">
          <span class="stat-label">Total Earned</span>
          <span class="stat-value" data-stat="total-earned">$0.00</span>
          <span class="stat-sub">Lifetime profits</span>
        </div>
        <div class="stat-card">
          <span class="stat-label">Active Investments</span>
          <span class="stat-value" data-stat="active-investments">0</span>
          <span class="stat-sub">Running contracts</span>
        </div>
        <div class="stat-card">
          <span class="stat-label">Total Invested</span>
          <span class="stat-value" data-stat="total-invested">$0.00</span>
          <span class="stat-sub">Across all contracts</span>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="quick-actions-row">
        <button class="action-card" type="button" onclick="openModal('modal-deposit')" aria-label="Deposit funds">
          <i class="ph ph-arrow-circle-down" aria-hidden="true"></i>
          <span class="action-card-label">Deposit</span>
        </button>
        <button class="action-card" type="button" onclick="openModal('modal-withdraw')" aria-label="Withdraw funds">
          <i class="ph ph-arrow-circle-up" aria-hidden="true"></i>
          <span class="action-card-label">Withdraw</span>
        </button>
        <button class="action-card action-card--accent" type="button" onclick="openModal('modal-trade')" aria-label="Start investment">
          <i class="ph ph-chart-bar" aria-hidden="true"></i>
          <span class="action-card-label">Invest</span>
        </button>
      </div>

      <!-- Market Prices / Active Stocks -->
      <div class="table-card">
        <div class="table-card-header">
          <h3>Market Prices</h3>
          <span class="stocks-updated text-muted" id="stocksUpdated"></span>
        </div>
        <div class="stocks-grid" id="stocksGrid">
          <div class="stock-card skeleton">—</div>
          <div class="stock-card skeleton">—</div>
          <div class="stock-card skeleton">—</div>
          <div class="stock-card skeleton">—</div>
        </div>
      </div>

      <!-- Recent Transactions -->
      <div class="table-card">
        <div class="table-card-header">
          <h3>Recent Transactions</h3>
        </div>
        <div class="table-scroll">
          <table class="db-table">
            <thead>
              <tr>
                <th>Type</th>
                <th>Amount</th>
                <th>Currency</th>
                <th>Status</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody data-table="recent-transactions">
              <tr><td colspan="5" class="empty-row">Loading…</td></tr>
            </tbody>
          </table>
        </div>
      </div>

    </section><!-- /overview -->


    <!-- ════════════════════════════════════════════════════════
         SECTION 2 — Wallet
         ════════════════════════════════════════════════════════ -->
    <section data-section="wallet" class="dashboard-section">

      <!-- Balance Hero -->
      <div class="balance-hero">
        <span class="balance-label">Available Balance</span>
        <div class="balance-display">
          <span class="balance-value" data-wallet="balance">$0.00</span>
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
        </div>
      </div>

      <!-- Transaction History -->
      <div class="table-card">
        <div class="table-card-header">
          <h3>Transaction History</h3>
        </div>
        <div class="table-scroll">
          <table class="db-table">
            <thead>
              <tr>
                <th>Type</th>
                <th>Amount</th>
                <th>Currency</th>
                <th>Status</th>
                <th>Notes</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody data-table="wallet-transactions">
              <tr><td colspan="6" class="empty-row">Loading…</td></tr>
            </tbody>
          </table>
        </div>
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
         SECTION 3 — Trade / Invest
         ════════════════════════════════════════════════════════ -->
    <section data-section="trade" class="dashboard-section">

      <!-- Stats: active count + total profits -->
      <div class="stats-row stats-row--2">
        <div class="stat-card">
          <span class="stat-label">Active Contracts</span>
          <span class="stat-value" data-stat="active-count">0</span>
          <span class="stat-sub">Currently running</span>
        </div>
        <div class="stat-card">
          <span class="stat-label">Total Profits Made</span>
          <span class="stat-value" data-stat="total-profit">$0.00</span>
          <span class="stat-sub">Sum of all earnings</span>
        </div>
      </div>

      <!-- Plan Cards -->
      <div class="section-heading-row">
        <h2 class="section-heading">Choose an Investment Plan</h2>
        <p class="section-sub">Earn daily returns over a 5-day contract cycle. Funds go directly into your wallet each day.</p>
      </div>

      <div class="plan-cards-grid">

        <div class="plan-card">
          <div class="plan-card-header">
            <h3 class="plan-name">Starter</h3>
            <span class="plan-rate-badge">2% / day</span>
          </div>
          <div class="plan-range">$100 — $499</div>
          <div class="plan-duration">5-day contract · Earn up to 10%</div>
          <button class="btn-primary full-width" type="button" onclick="openTradeModal('starter')">
            Invest Now
          </button>
        </div>

        <div class="plan-card">
          <div class="plan-card-header">
            <h3 class="plan-name">Bronze</h3>
            <span class="plan-rate-badge">4% / day</span>
          </div>
          <div class="plan-range">$500 — $2,999</div>
          <div class="plan-duration">5-day contract · Earn up to 20%</div>
          <button class="btn-primary full-width" type="button" onclick="openTradeModal('bronze')">
            Invest Now
          </button>
        </div>

        <div class="plan-card">
          <div class="plan-card-header">
            <h3 class="plan-name">Silver</h3>
            <span class="plan-rate-badge">6% / day</span>
          </div>
          <div class="plan-range">$3,000 — $4,999</div>
          <div class="plan-duration">5-day contract · Earn up to 30%</div>
          <button class="btn-primary full-width" type="button" onclick="openTradeModal('silver')">
            Invest Now
          </button>
        </div>

        <div class="plan-card">
          <div class="plan-card-header">
            <h3 class="plan-name">Platinum</h3>
            <span class="plan-rate-badge">8% / day</span>
          </div>
          <div class="plan-range">$5,000+</div>
          <div class="plan-duration">5-day contract · Earn up to 40%</div>
          <button class="btn-primary full-width" type="button" onclick="openTradeModal('platinum')">
            Invest Now
          </button>
        </div>

      </div>

      <!-- Investment History -->
      <div class="table-card">
        <div class="table-card-header">
          <h3>My Investments</h3>
        </div>
        <div class="table-scroll">
          <table class="db-table">
            <thead>
              <tr>
                <th>Plan</th>
                <th>Amount</th>
                <th>Daily Rate</th>
                <th>Earned</th>
                <th>Status</th>
                <th>Ends</th>
              </tr>
            </thead>
            <tbody data-table="investments">
              <tr><td colspan="6" class="empty-row">Loading…</td></tr>
            </tbody>
          </table>
        </div>
      </div>

    </section><!-- /trade -->


    <!-- ════════════════════════════════════════════════════════
         SECTION 4 — Referral
         ════════════════════════════════════════════════════════ -->
    <section data-section="referral" class="dashboard-section">

      <!-- Referral Stats -->
      <div class="stats-row stats-row--2">
        <div class="stat-card">
          <span class="stat-label">Total Referrals</span>
          <span class="stat-value" data-referral="uses">0</span>
          <span class="stat-sub">People you've referred</span>
        </div>
        <div class="stat-card">
          <span class="stat-label">Commission Earned</span>
          <span class="stat-value" data-referral="commission">$0.00</span>
          <span class="stat-sub">5% of each referral's deposit</span>
        </div>
      </div>

      <!-- Referral Code & Link Card -->
      <div class="table-card referral-card">
        <div class="table-card-header">
          <h3>Your Referral Code</h3>
        </div>
        <div class="referral-card-body">
          <div class="referral-code-row">
            <span class="referral-code" data-referral="code">—</span>
            <button type="button" class="btn-copy" data-copy="referral-code" aria-label="Copy referral code">
              Copy Code
            </button>
          </div>
          <div class="referral-link-row">
            <label class="form-label-sm">Share your referral link</label>
            <div class="copy-field">
              <input type="text" class="copy-input" data-referral="link" readonly
                     placeholder="Referral link will appear here" aria-label="Referral link">
              <button type="button" class="btn-copy" data-copy="referral-link" aria-label="Copy referral link">
                Copy Link
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Referred Users Table -->
      <div class="table-card">
        <div class="table-card-header">
          <h3>Referred Users</h3>
        </div>
        <div class="table-scroll">
          <table class="db-table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Commission</th>
                <th>Joined</th>
              </tr>
            </thead>
            <tbody data-table="referred-users">
              <tr><td colspan="4" class="empty-row">Loading…</td></tr>
            </tbody>
          </table>
        </div>
      </div>

    </section><!-- /referral -->


    <!-- ════════════════════════════════════════════════════════
         SECTION 5 — Profile
         ════════════════════════════════════════════════════════ -->
    <section data-section="profile" class="dashboard-section">

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

  </main><!-- .dashboard-main -->
</div><!-- .dashboard-wrapper -->

<?php require_once '../../includes/mobile-dock.php'; ?>

<script src="/assets/js/user/user-dashboard.js"></script>
</body>
</html>

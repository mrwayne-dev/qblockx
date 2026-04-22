<?php
/**
 * Project: crestvalebank
 * Page: User Dashboard — Single-Page Application
 *
 * Sections: overview | wallet | savings | deposits | loans | profile
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

      <!-- Stats: 4 cards -->
      <div class="stats-row stats-row--4">
        <div class="stat-card">
          <span class="stat-label">Wallet Balance</span>
          <span class="stat-value" data-stat="balance">0.00</span>
          <span class="stat-sub">Available to use</span>
        </div>
        <div class="stat-card">
          <span class="stat-label">Savings Balance</span>
          <span class="stat-value" data-stat="savings-balance">0.00</span>
          <span class="stat-sub">Total saved across plans</span>
        </div>
        <div class="stat-card">
          <span class="stat-label">Deposits Balance</span>
          <span class="stat-value" data-stat="deposits-balance">0.00</span>
          <span class="stat-sub">Total in fixed deposits</span>
        </div>
        <div class="stat-card">
          <span class="stat-label">Loan Balance</span>
          <span class="stat-value" data-stat="loan-balance">0.00</span>
          <span class="stat-sub">Outstanding loan amount</span>
        </div>
      </div>

      <!-- Quick Actions: 6 cards -->
      <div class="quick-actions-row quick-actions-row--6">
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
        <button class="action-card" type="button" onclick="openModal('modal-create-savings')" aria-label="Create savings plan">
          <i class="ph ph-piggy-bank" aria-hidden="true"></i>
          <span class="action-card-label">Savings</span>
        </button>
        <button class="action-card" type="button" onclick="openModal('modal-fixed-deposit')" aria-label="Open fixed deposit">
          <i class="ph ph-vault" aria-hidden="true"></i>
          <span class="action-card-label">Fixed Deposit</span>
        </button>
        <button class="action-card" type="button" onclick="openModal('modal-loan')" aria-label="Apply for loan">
          <i class="ph ph-hand-coins" aria-hidden="true"></i>
          <span class="action-card-label">Loans</span>
        </button>
      </div>

      <!-- Products & Rates -->
      <div class="table-card" id="ratesCard">
        <div class="table-card-header">
          <h3>Products &amp; Rates</h3>
          <span class="badge badge-info">Qblockx </span>
        </div>
        <div class="rates-tabs" id="ratesTabs">
          <button class="rates-tab active" data-rates-filter="savings" type="button">Savings</button>
          <button class="rates-tab" data-rates-filter="fixed_deposit" type="button">Fixed Deposits</button>
          <button class="rates-tab" data-rates-filter="loan" type="button">Loans</button>
        </div>
        <div class="rates-grid" id="ratesGrid">
          <p class="empty-text">Loading…</p>
        </div>
      </div>

      <!-- Savings Progress -->
      <div class="table-card">
        <div class="table-card-header">
          <h3>Active Savings Plans</h3>
          <button type="button" class="btn-sm btn-outline"
                  onclick="document.querySelector('[data-nav=savings]').click()">
            View All
          </button>
        </div>
        <div class="table-scroll">
          <table class="db-table">
            <thead>
              <tr>
                <th>Plan Name</th>
                <th>Target</th>
                <th>Saved</th>
                <th>Rate</th>
                <th>Progress</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody data-table="savings-overview">
              <tr><td colspan="6" class="empty-row">Loading…</td></tr>
            </tbody>
          </table>
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

      <!-- Upcoming Loan Payments -->
      <div class="table-card">
        <div class="table-card-header">
          <h3>Upcoming Loan Payments</h3>
        </div>
        <div class="table-responsive">
          <table class="data-table">
            <thead>
              <tr>
                <th>Purpose</th>
                <th>Monthly Payment</th>
                <th>Outstanding Balance</th>
                <th>Duration</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody data-list="upcoming-payments">
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
        </div>
      </div>

      <!-- Quick Product Actions -->
      <div class="wallet-actions-grid">
        <button class="wallet-action-card" type="button" onclick="openModal('modal-transfer')" aria-label="Transfer funds">
          <i class="ph ph-arrows-left-right" aria-hidden="true"></i>
          <span>Transfer</span>
        </button>
        <button class="wallet-action-card" type="button" onclick="openModal('modal-create-savings')" aria-label="Create savings plan">
          <i class="ph ph-piggy-bank" aria-hidden="true"></i>
          <span>Savings</span>
        </button>
        <button class="wallet-action-card" type="button" onclick="openModal('modal-fixed-deposit')" aria-label="Open fixed deposit">
          <i class="ph ph-vault" aria-hidden="true"></i>
          <span>Fixed Deposit</span>
        </button>
        <button class="wallet-action-card" type="button" onclick="openModal('modal-loan')" aria-label="Apply for loan">
          <i class="ph ph-hand-coins" aria-hidden="true"></i>
          <span>Loans</span>
        </button>
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
                <th>Status</th>
                <th>Description</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody data-table="wallet-transactions">
              <tr><td colspan="5" class="empty-row">Loading…</td></tr>
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
         SECTION 3 — Savings Plans
         ════════════════════════════════════════════════════════ -->
    <section data-section="savings" class="dashboard-section">

      <!-- Stats -->
      <div class="stats-row stats-row--3">
        <div class="stat-card">
          <span class="stat-label">Total Saved</span>
          <span class="stat-value" data-stat="total-saved">0.00</span>
          <span class="stat-sub">Across all active plans</span>
        </div>
        <div class="stat-card stat-card--locked">
          <span class="stat-label"><i class="ph ph-lock-simple" style="vertical-align:middle;margin-right:.25rem;"></i>Locked in Savings</span>
          <span class="stat-value" data-stat="locked-in-savings">0.00</span>
          <span class="stat-sub">Deducted from your wallet balance</span>
        </div>
        <div class="stat-card">
          <span class="stat-label">Active Plans</span>
          <span class="stat-value" data-stat="savings-count">0</span>
          <span class="stat-sub">Currently running</span>
        </div>
      </div>

      <!-- Create Plan CTA -->
      <div class="section-heading-row">
        <div>
          <h2 class="section-heading">My Savings Plans</h2>
          <p class="section-sub">Set a savings goal and earn up to 7<i class="ph ph-percent"></i>&thinsp;p.a. interest. Funds are contributed from your wallet.</p>
        </div>
        <button class="btn-primary" type="button" onclick="openModal('modal-create-savings')" aria-label="Create new savings plan">
          <i class="ph ph-plus" aria-hidden="true"></i>
          Create Plan
        </button>
      </div>

      <!-- Savings Plans Table -->
      <div class="table-card">
        <div class="table-card-header">
          <h3>Savings Plans</h3>
        </div>
        <div class="table-scroll">
          <table class="db-table">
            <thead>
              <tr>
                <th>Plan Name</th>
                <th>Target</th>
                <th>Saved</th>
                <th>Rate</th>
                <th>Progress</th>
                <th>Duration</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody data-table="savings-plans">
              <tr><td colspan="8" class="empty-row">Loading…</td></tr>
            </tbody>
          </table>
        </div>
      </div>

    </section><!-- /savings -->


    <!-- ════════════════════════════════════════════════════════
         SECTION 4 — Fixed Deposits
         ════════════════════════════════════════════════════════ -->
    <section data-section="deposits" class="dashboard-section">

      <!-- Stats -->
      <div class="stats-row stats-row--2">
        <div class="stat-card">
          <span class="stat-label">Total Invested</span>
          <span class="stat-value" data-stat="total-deposited">0.00</span>
          <span class="stat-sub">Across all deposits</span>
        </div>
        <div class="stat-card">
          <span class="stat-label">Expected Returns</span>
          <span class="stat-value" data-stat="total-expected-return">0.00</span>
          <span class="stat-sub">Principal + interest at maturity</span>
        </div>
      </div>

      <!-- Open Deposit CTA -->
      <div class="section-heading-row">
        <div>
          <h2 class="section-heading">My Fixed Deposits</h2>
          <p class="section-sub">Lock funds for 6–24 months and earn up to 16<i class="ph ph-percent"></i>&thinsp;p.a. Principal and interest are returned at maturity.</p>
        </div>
        <button class="btn-primary" type="button" onclick="openModal('modal-fixed-deposit')" aria-label="Open new fixed deposit">
          <i class="ph ph-plus" aria-hidden="true"></i>
          Open Deposit
        </button>
      </div>


      <!-- Deposits Table -->
      <div class="table-card">
        <div class="table-card-header">
          <h3>Fixed Deposits</h3>
        </div>
        <div class="table-scroll">
          <table class="db-table">
            <thead>
              <tr>
                <th>Amount</th>
                <th>Rate</th>
                <th>Duration</th>
                <th>Start Date</th>
                <th>Maturity Date</th>
                <th>Expected Return</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody data-table="fixed-deposits">
              <tr><td colspan="7" class="empty-row">Loading…</td></tr>
            </tbody>
          </table>
        </div>
      </div>

    </section><!-- /deposits -->


    <!-- ════════════════════════════════════════════════════════
         SECTION 5 — Loans
         ════════════════════════════════════════════════════════ -->
    <section data-section="loans" class="dashboard-section">

      <!-- Stats -->
      <div class="stats-row stats-row--2">
        <div class="stat-card">
          <span class="stat-label">Total Borrowed</span>
          <span class="stat-value" data-stat="total-borrowed">0.00</span>
          <span class="stat-sub">Principal amount disbursed</span>
        </div>
        <div class="stat-card">
          <span class="stat-label">Total Outstanding</span>
          <span class="stat-value" data-stat="remaining-balance">0.00</span>
          <span class="stat-sub">Principal + interest still owed</span>
        </div>
      </div>

      <!-- Apply for Loan CTA -->
      <div class="section-heading-row">
        <div>
          <h2 class="section-heading">My Loans</h2>
          <p class="section-sub">Apply for a loan, track repayments, and manage your outstanding balance.</p>
        </div>
        <button class="btn-primary" type="button" onclick="openModal('modal-loan')" aria-label="Apply for a loan">
          <i class="ph ph-plus" aria-hidden="true"></i>
          Apply for Loan
        </button>
      </div>

      <!-- Active Loans Table -->
      <div class="table-card">
        <div class="table-card-header">
          <h3>Active Loans</h3>
        </div>
        <div class="table-scroll">
          <table class="db-table">
            <thead>
              <tr>
                <th>Loan Amount</th>
                <th>Remaining</th>
                <th>Monthly Payment</th>
                <th>Rate</th>
                <th>Duration</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody data-table="active-loans">
              <tr><td colspan="7" class="empty-row">Loading…</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pending Applications Table -->
      <div class="table-card">
        <div class="table-card-header">
          <h3>Pending Applications</h3>
        </div>
        <div class="table-scroll">
          <table class="db-table">
            <thead>
              <tr>
                <th>Amount</th>
                <th>Duration</th>
                <th>Applied</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody data-table="pending-loans">
              <tr><td colspan="4" class="empty-row">Loading…</td></tr>
            </tbody>
          </table>
        </div>
      </div>

    </section><!-- /loans -->


    <!-- ════════════════════════════════════════════════════════
         SECTION 6 — Profile
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

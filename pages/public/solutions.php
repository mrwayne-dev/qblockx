<?php
/**
 * Project: crestvalebank
 * Page: Products (solutions.php serves /products and /solutions)
 */
$pageTitle       = 'Products';
$pageDescription = 'Explore CrestVale Bank\'s products — goal-based savings plans, fixed deposits with up to 12% p.a., flexible loans, and instant wallet-to-wallet transfers.';
require_once '../../includes/head.php';
?>

<?php require_once '../../includes/header.php'; ?>

<main>

  <!-- ── Hero ──────────────────────────────────────────────────── -->
  <section class="hero hero--static" role="region" aria-label="Products hero">
    <div class="hero-split">
      <div class="hero-left">
        <div class="hero-content active" data-slide="0">
          <span class="hero-tag">Products</span>
          <h1 class="hero-heading">
            <span class="hero-line">Everything you need</span>
            <span class="hero-line">to grow your money</span>
          </h1>
          <p class="hero-subtext">
            CrestVale Bank offers a complete suite of banking products — savings plans,
            fixed deposits, loans, and instant transfers — all connected through your
            central wallet and managed in one place.
          </p>
          <div class="hero-actions">
            <a href="/register" class="btn-hero-outline">
              Open Account <i class="ph ph-arrow-right" aria-hidden="true"></i>
            </a>
            <a href="/contact" class="btn-hero-ghost">
              Contact Us <i class="ph ph-arrow-right" aria-hidden="true"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ── Savings Plans ──────────────────────────────────────────── -->
  <section class="use-cases section" id="savings" role="region" aria-labelledby="savings-title">
    <div class="use-cases-container container" data-appear>
      <div class="use-cases-content">
        <span class="section-label">PRODUCT 01 — SAVINGS PLANS</span>
        <h2 id="savings-title" class="use-cases-title">Goal-Based Savings Accounts</h2>
        <p>Set a savings target, choose a duration, and contribute regularly.
           Interest accumulates over time using the formula:
           <strong>Interest = Principal × Rate × Time</strong>.
           Plans complete when the target is reached or the duration ends.</p>
      </div>
      <div class="use-cases-cards">
        <div class="use-cases-card glass">
          <i class="ph ph-target use-cases-icon" aria-hidden="true"></i>
          <h3 class="use-cases-card-title">Goal-Based</h3>
          <p class="use-cases-card-description">
            Name your plan, set a target amount, and contribute at your own pace.
            CrestVale tracks your progress every step of the way.
          </p>
        </div>
        <div class="use-cases-card glass">
          <i class="ph ph-percent use-cases-icon" aria-hidden="true"></i>
          <h3 class="use-cases-card-title">5.5<i class="ph ph-percent"></i> p.a. Interest</h3>
          <p class="use-cases-card-description">
            Earn competitive interest on your saved balance. Interest accumulates
            monthly and is credited at plan completion or withdrawal.
          </p>
        </div>
        <div class="use-cases-card glass">
          <i class="ph ph-arrows-in use-cases-icon" aria-hidden="true"></i>
          <h3 class="use-cases-card-title">Flexible Contributions</h3>
          <p class="use-cases-card-description">
            Add funds from your wallet whenever you're ready — no fixed contribution
            schedule required. Withdraw anytime (subject to terms).
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- ── Fixed Deposits ─────────────────────────────────────────── -->
  <section class="use-cases section" id="deposits" role="region" aria-labelledby="deposits-title">
    <div class="use-cases-container container" data-appear>
      <div class="use-cases-content">
        <span class="section-label">PRODUCT 02 — FIXED DEPOSITS</span>
        <h2 id="deposits-title" class="use-cases-title">Locked Returns at Higher Rates</h2>
        <p>Deposit a lump sum for a fixed term and earn higher interest than savings.
           Funds remain locked until maturity. At maturity, you receive your
           <strong>principal + interest</strong> in full.
           Return = Principal + (Principal × Rate × Time).</p>
      </div>
      <div class="use-cases-cards">
        <div class="use-cases-card glass">
          <i class="ph ph-vault use-cases-icon" aria-hidden="true"></i>
          <h3 class="use-cases-card-title">6-Month Term</h3>
          <p class="use-cases-card-description">
            Lock your funds for 6 months and earn 8<i class="ph ph-percent"></i>&thinsp;p.a. — a competitive rate
            for medium-term wealth preservation.
          </p>
        </div>
        <div class="use-cases-card glass">
          <i class="ph ph-chart-line-up use-cases-icon" aria-hidden="true"></i>
          <h3 class="use-cases-card-title">12-Month Term</h3>
          <p class="use-cases-card-description">
            Maximise your return with a 12-month deposit at 12<i class="ph ph-percent"></i>&thinsp;p.a. — ideal for
            funds you won't need in the short term.
          </p>
        </div>
        <div class="use-cases-card glass">
          <i class="ph ph-calendar-check use-cases-icon" aria-hidden="true"></i>
          <h3 class="use-cases-card-title">Maturity Payout</h3>
          <p class="use-cases-card-description">
            Your principal and all accumulated interest are automatically returned
            to your wallet on the maturity date.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- ── Loans ──────────────────────────────────────────────────── -->
  <section class="use-cases section" id="loans" role="region" aria-labelledby="loans-title">
    <div class="use-cases-container container" data-appear>
      <div class="use-cases-content">
        <span class="section-label">PRODUCT 03 — LOANS</span>
        <h2 id="loans-title" class="use-cases-title">Flexible Borrowing Solutions</h2>
        <p>Apply for a loan, receive admin approval, and have funds credited directly
           to your wallet. Repay monthly at a fixed rate.
           <strong>Monthly Payment = Total Loan ÷ Duration (months)</strong>.</p>
      </div>
      <div class="use-cases-cards">
        <div class="use-cases-card glass">
          <i class="ph ph-hand-coins use-cases-icon" aria-hidden="true"></i>
          <h3 class="use-cases-card-title">Apply Online</h3>
          <p class="use-cases-card-description">
            Submit a loan application directly from your dashboard. Specify the amount
            and preferred repayment duration.
          </p>
        </div>
        <div class="use-cases-card glass">
          <i class="ph ph-clock use-cases-icon" aria-hidden="true"></i>
          <h3 class="use-cases-card-title">Fast Approval</h3>
          <p class="use-cases-card-description">
            Our admin team reviews applications promptly. Approved loans are credited
            to your wallet and active immediately.
          </p>
        </div>
        <div class="use-cases-card glass">
          <i class="ph ph-calendar use-cases-icon" aria-hidden="true"></i>
          <h3 class="use-cases-card-title">Monthly Repayments</h3>
          <p class="use-cases-card-description">
            Fixed monthly payments make budgeting simple. Track your remaining balance
            and upcoming due dates from your loans dashboard.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- ── Transfers ──────────────────────────────────────────────── -->
  <section class="use-cases section" id="transfers" role="region" aria-labelledby="transfers-title">
    <div class="use-cases-container container" data-appear>
      <div class="use-cases-content">
        <span class="section-label">PRODUCT 04 — TRANSFERS</span>
        <h2 id="transfers-title" class="use-cases-title">Instant Wallet-to-Wallet Transfers</h2>
        <p>Move money between CrestVale wallets instantly. No fees, no delays.
           Every transfer is recorded in your transaction history for full transparency.</p>
      </div>
      <div class="use-cases-cards">
        <div class="use-cases-card glass">
          <i class="ph ph-lightning use-cases-icon" aria-hidden="true"></i>
          <h3 class="use-cases-card-title">Instant Settlement</h3>
          <p class="use-cases-card-description">
            Funds are credited to the recipient's wallet immediately — no waiting
            periods, no holds.
          </p>
        </div>
        <div class="use-cases-card glass">
          <i class="ph ph-receipt use-cases-icon" aria-hidden="true"></i>
          <h3 class="use-cases-card-title">Full Audit Trail</h3>
          <p class="use-cases-card-description">
            Every transaction is logged with type, amount, date, and status.
            Download your statement anytime from the wallet page.
          </p>
        </div>
        <div class="use-cases-card glass">
          <i class="ph ph-shield-check use-cases-icon" aria-hidden="true"></i>
          <h3 class="use-cases-card-title">Secure by Design</h3>
          <p class="use-cases-card-description">
            Balance checks and session authentication protect every transfer.
            You can only send funds you have available.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- ── Rates Table ────────────────────────────────────────────── -->
  <section class="pricing section" role="region" aria-labelledby="rates-title" id="rates">
    <div class="pricing-container container" data-appear>
      <div class="section-header">
        <span class="section-label">INTEREST RATES</span>
        <h2 id="rates-title" class="pricing-header">Current Rates</h2>
        <p class="section-subtitle">Rates are set by our admin team and may be updated periodically. Always check your dashboard for the latest applicable rates.</p>
      </div>
      <div class="pricing-cards">

        <div class="pricing-card glass">
          <p class="pricing-subtitle">Grow toward your goals.</p>
          <h3 class="pricing-plan">Savings Plans</h3>
          <p class="pricing-price">5.5<i class="ph ph-percent"></i> <span class="pricing-rate">p.a.</span></p>
          <ul class="pricing-features">
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Duration: 12 months</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Flexible contributions</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Monthly interest accrual</li>
          </ul>
          <a href="/register" class="btn-primary pricing-button">Start Saving</a>
        </div>

        <div class="pricing-card glass">
          <p class="pricing-subtitle">Medium-term lump sum investment.</p>
          <h3 class="pricing-plan">Fixed Deposit — 6M</h3>
          <p class="pricing-price">8<i class="ph ph-percent"></i> <span class="pricing-rate">p.a.</span></p>
          <ul class="pricing-features">
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Duration: 6 months</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Funds locked at term</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Principal + interest at maturity</li>
          </ul>
          <a href="/register" class="btn-primary pricing-button">Open Deposit</a>
        </div>

        <div class="pricing-card glass">
          <p class="pricing-subtitle">Maximise your returns.</p>
          <h3 class="pricing-plan">Fixed Deposit — 12M</h3>
          <p class="pricing-price">12<i class="ph ph-percent"></i> <span class="pricing-rate">p.a.</span></p>
          <ul class="pricing-features">
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Duration: 12 months</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Highest available rate</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Principal + interest at maturity</li>
          </ul>
          <a href="/register" class="btn-shiny pricing-button">Open Deposit</a>
        </div>

        <div class="pricing-card glass">
          <p class="pricing-subtitle">Access capital when you need it.</p>
          <h3 class="pricing-plan">Loans</h3>
          <p class="pricing-price">12<i class="ph ph-percent"></i> <span class="pricing-rate">p.a.</span></p>
          <ul class="pricing-features">
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Duration: 12 months</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Admin approval required</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Simple monthly repayments</li>
          </ul>
          <a href="/register" class="btn-primary pricing-button">Apply Now</a>
        </div>

      </div>
      <p class="pricing-disclaimer">Rates are indicative and subject to change. See the <a href="/risk">Risk Disclosure</a> for full terms.</p>
    </div>
  </section>

</main>

<?php require_once '../../includes/footer.php'; ?>
</body>
</html>

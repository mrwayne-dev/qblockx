<?php
/**
 * Project: arqoracapital
 * Page: Solutions
 */
$pageTitle       = 'Solutions';
$pageDescription = 'Discover ArqoraCapital\'s investment solutions — automated wealth optimisation, flexible 5-day contracts, cash management, and brokerage services.';
require_once '../../includes/head.php';
?>

<?php require_once '../../includes/header.php'; ?>

<main>

  <!-- ── Hero ──────────────────────────────────────────────────── -->
  <section class="hero" role="region" aria-label="Solutions hero">
    <img
      src="/assets/images/background/arqorabgimage.png"
      alt=""
      class="hero-bg"
      aria-hidden="true"
      draggable="false"
    >

    <div class="hero-container" data-appear>
      <span class="hero-tag">Solutions</span>
      <h1 class="hero-heading">Digital wealth<br>is our solution</h1>
      <p class="hero-subtext">
        ArqoraCapital delivers cutting-edge digital wealth solutions designed to
        empower financial institutions, advisors, and individual investors. Whether
        optimising savings, retirement strategies, or institutional portfolios —
        we provide the tools to build trust, retention, and long-term growth.
      </p>
      <div class="hero-actions">
        <a href="/login" class="btn-hero-outline">
          Get Started <i class="ph ph-arrow-right" aria-hidden="true"></i>
        </a>
        <a href="/contact" class="btn-hero-ghost">
          Contact Us <i class="ph ph-arrow-right" aria-hidden="true"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- ── Use Cases: Cash Management ────────────────────────────── -->
  <section class="use-cases section" role="region" aria-labelledby="cash-mgmt-title">
    <div class="use-cases-container container" data-appear>
      <div class="use-cases-content">
        <span class="section-label">USE CASES — CASH MANAGEMENT</span>
        <h2 id="cash-mgmt-title" class="use-cases-title">Launch your cash management offering in record time</h2>
      </div>
      <div class="use-cases-cards">
        <div class="use-cases-card glass">
          <i class="ph ph-bank use-cases-icon" aria-hidden="true"></i>
          <h3 class="use-cases-card-title">Cash Management</h3>
          <p class="use-cases-card-description">
            We grant finance teams access to treasury bills, money market funds, and
            FDIC-insured cash sweeps directly within your platform.
          </p>
        </div>
        <div class="use-cases-card glass">
          <i class="ph ph-chart-line use-cases-icon" aria-hidden="true"></i>
          <h3 class="use-cases-card-title">High-Yield Cash</h3>
          <p class="use-cases-card-description">
            Help individuals make the most of their cash with competitive interest rates
            and no liquidity restrictions.
          </p>
        </div>
        <div class="use-cases-card glass">
          <i class="ph ph-currency-circle-dollar use-cases-icon" aria-hidden="true"></i>
          <h3 class="use-cases-card-title">Multi-Currency Yield</h3>
          <p class="use-cases-card-description">
            Differentiate your offering by enabling clients to hold multiple currencies
            in a single account while earning yield on their balances.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- ── Use Cases: Investment Services ────────────────────────── -->
  <section class="use-cases section" role="region" aria-labelledby="brokerage-title">
    <div class="use-cases-container container" data-appear>
      <div class="use-cases-content">
        <span class="section-label">USE CASES — INVESTMENT SERVICES</span>
        <h2 id="brokerage-title" class="use-cases-title">Enabling you to invest in a matter of weeks</h2>
      </div>
      <div class="use-cases-cards">
        <div class="use-cases-card glass">
          <i class="ph ph-coin use-cases-icon" aria-hidden="true"></i>
          <h3 class="use-cases-card-title">Crypto &amp; Digital Assets</h3>
          <p class="use-cases-card-description">
            We provide clients the ability to invest in leading cryptocurrencies across
            all major global markets.
          </p>
        </div>
        <div class="use-cases-card glass">
          <i class="ph ph-receipt use-cases-icon" aria-hidden="true"></i>
          <h3 class="use-cases-card-title">Fixed Income</h3>
          <p class="use-cases-card-description">
            We offer fractional access to fixed income securities, including treasury
            bills, agencies, corporate bonds, and municipals.
          </p>
        </div>
        <div class="use-cases-card glass">
          <i class="ph ph-stack use-cases-icon" aria-hidden="true"></i>
          <h3 class="use-cases-card-title">Diversified Funds</h3>
          <p class="use-cases-card-description">
            Deliver a diverse array of investment funds to help clients achieve their
            unique wealth goals with managed risk.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- ── Implementation ─────────────────────────────────────────── -->
  <section class="implement section" role="region" aria-labelledby="implement-title">
    <div class="implement-container container" data-appear>
      <div class="implement-content">
        <span class="section-label">IMPLEMENTATIONS</span>
        <h2 id="implement-title" class="implement-title">Intuitively designed pre-built experiences</h2>
        <p>
          ArqoraCapital's platform comes with a complete suite of pre-built investment
          tools and dashboards — ready to use from day one.
        </p>
      </div>
      <div class="implement-image">
        <div class="feature-image-placeholder glass">
        <img loading="lazy" src="/assets/images/background/pre-built.svg" alt="Platform preview" class="solutions-image"></div>
      </div>
    </div>
  </section>

  <!-- ── Pricing ────────────────────────────────────────────────── -->
  <section class="pricing section" role="region" aria-labelledby="sol-pricing-title" id="pricing">
    <div class="pricing-container container" data-appear>
      <div class="section-header">
        <span class="section-label">PLANS</span>
        <h2 id="sol-pricing-title" class="pricing-header">Investment Plans</h2>
        <p class="section-subtitle">All plans run for 5 days with daily payouts and full capital return.</p>
      </div>
      <div class="pricing-cards">
        <div class="pricing-card glass">
          <p class="pricing-subtitle">For investors getting started.</p>
          <h3 class="pricing-plan">Starter</h3>
          <p class="pricing-price">2% <span class="pricing-rate">/ day</span></p>
          <ul class="pricing-features">
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Min: $100 — Max: $499</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Duration: 5 Days</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Return of Capital: Yes</li>
          </ul>
          <a href="/login" class="btn-primary pricing-button">Get Started</a>
        </div>
        <div class="pricing-card glass">
          <p class="pricing-subtitle">For investors scaling up.</p>
          <h3 class="pricing-plan">Bronze</h3>
          <p class="pricing-price">4% <span class="pricing-rate">/ day</span></p>
          <ul class="pricing-features">
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Min: $500 — Max: $2,999</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Duration: 5 Days</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Return of Capital: Yes</li>
          </ul>
          <a href="/login" class="btn-shiny pricing-button">Get Started</a>
        </div>
        <div class="pricing-card glass">
          <p class="pricing-subtitle">For advanced investors.</p>
          <h3 class="pricing-plan">Silver</h3>
          <p class="pricing-price">6% <span class="pricing-rate">/ day</span></p>
          <ul class="pricing-features">
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Min: $3,000 — Max: $4,999</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Duration: 5 Days</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Return of Capital: Yes</li>
          </ul>
          <a href="/login" class="btn-primary pricing-button">Get Started</a>
        </div>
        <div class="pricing-card glass">
          <p class="pricing-subtitle">For elite investors.</p>
          <h3 class="pricing-plan">Platinum</h3>
          <p class="pricing-price">8% <span class="pricing-rate">/ day</span></p>
          <ul class="pricing-features">
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Min: $5,000 — Unlimited</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Duration: 5 Days</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Return of Capital: Yes</li>
          </ul>
          <a href="/login" class="btn-primary pricing-button">Get Started</a>
        </div>
      </div>
    </div>
  </section>

</main>

<?php require_once '../../includes/footer.php'; ?>
</body>
</html>

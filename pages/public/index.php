<?php
/**
 * Project: crestvalebank
 * Page: Homepage
 */
$pageTitle       = 'Home';
$pageDescription = 'CrestVale Bank is a modern fintech banking platform. Open a savings plan, invest in fixed deposits, apply for loans, and make instant transfers — all in one place.';
$pageKeywords    = 'CrestVale Bank, fintech banking, savings plans, fixed deposits, loans, transfers, online banking';
require_once '../../includes/head.php';
?>

<?php require_once '../../includes/header.php'; ?>

<main>

  <!-- ── Hero ───────────────────────────────────────────────────── -->
  <section class="hero" id="heroCarousel" role="region" aria-label="Hero">

    <!-- ── Split layout: left text / right widget ── -->
    <div class="hero-split">

      <!-- Left: animated text slides -->
      <div class="hero-left">

        <!-- Slide 0 — Banking -->
        <div class="hero-content active" data-slide="0">
          <span class="hero-tag">Banking</span>
          <h1 class="hero-heading">
            <span class="hero-line">Smarter</span>
            <span class="hero-line">online</span>
            <span class="hero-line">banking.</span>
          </h1>
          <p class="hero-subtext">
            CrestVale Bank is a modern fintech platform — savings, deposits,
            loans, and instant transfers, all under one roof.
          </p>
          <div class="hero-actions">
            <a href="/register" class="btn-hero-outline">Open Account <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
            <a href="/products" class="btn-hero-ghost">Our Products <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
          </div>
        </div>

        <!-- Slide 1 — Savings -->
        <div class="hero-content" data-slide="1">
          <span class="hero-tag">Savings</span>
          <h1 class="hero-heading">
            <span class="hero-line">Grow your</span>
            <span class="hero-line">savings</span>
            <span class="hero-line">daily.</span>
          </h1>
          <p class="hero-subtext">
            Set a goal and watch interest accumulate automatically. Competitive
            rates up to 6.5<i class="ph ph-percent"></i> p.a. — no lock-in, full flexibility.
          </p>
          <div class="hero-actions">
            <a href="/register" class="btn-hero-outline">Start Saving <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
            <a href="/products" class="btn-hero-ghost">View Plans <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
          </div>
        </div>

        <!-- Slide 2 — Deposits -->
        <div class="hero-content" data-slide="2">
          <span class="hero-tag">Fixed Deposits</span>
          <h1 class="hero-heading">
            <span class="hero-line">Higher</span>
            <span class="hero-line">guaranteed</span>
            <span class="hero-line">returns.</span>
          </h1>
          <p class="hero-subtext">
            Lock in your money for a fixed term and earn up to 15<i class="ph ph-percent"></i> p.a.
            Principal and interest returned in full at maturity.
          </p>
          <div class="hero-actions">
            <a href="/register" class="btn-hero-outline">Start Deposit <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
            <a href="/products" class="btn-hero-ghost">View Rates <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
          </div>
        </div>

        <!-- Slide 3 — Loans -->
        <div class="hero-content" data-slide="3">
          <span class="hero-tag">Loans</span>
          <h1 class="hero-heading">
            <span class="hero-line">Flexible</span>
            <span class="hero-line">loans</span>
            <span class="hero-line">anytime.</span>
          </h1>
          <p class="hero-subtext">
            Personal and business loans from 8<i class="ph ph-percent"></i> p.a. Fast approval, simple
            monthly repayments, and funds credited directly to your wallet.
          </p>
          <div class="hero-actions">
            <a href="/register" class="btn-hero-outline">Apply Now <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
            <a href="/products" class="btn-hero-ghost">Learn More <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
          </div>
        </div>

      </div><!-- .hero-left -->

      <!-- Right: glass banking widget (one per slide) -->
      <div class="hero-right" aria-hidden="true">

        <!-- Widget 0: Wallet Balance -->
        <div class="hero-widget active" data-slide="0">
          <div class="hw-card">
            <div class="hw-header">
              <span class="hw-label">Total Balance</span>
              <i class="ph ph-wallet hw-icon"></i>
            </div>
            <div class="hw-amount">$12,450<span class="hw-cents">.00</span></div>
            <div class="hw-divider"></div>
            <div class="hw-rows">
              <div class="hw-row">
                <span class="hw-row-label">
                  <span class="hw-tx-dot hw-tx-dot--in"></span>Deposit received
                </span>
                <span class="hw-row-value hw-positive">+$1,000.00</span>
              </div>
              <div class="hw-row">
                <span class="hw-row-label">
                  <span class="hw-tx-dot hw-tx-dot--out"></span>Transfer sent
                </span>
                <span class="hw-row-value hw-negative">−$250.00</span>
              </div>
              <div class="hw-row">
                <span class="hw-row-label">
                  <span class="hw-tx-dot hw-tx-dot--in"></span>Interest credit
                </span>
                <span class="hw-row-value hw-positive">+$45.20</span>
              </div>
            </div>
            <a href="/register" class="hw-cta">Open Your Wallet <i class="ph ph-arrow-right"></i></a>
          </div>
        </div>

        <!-- Widget 1: Savings Goal -->
        <div class="hero-widget" data-slide="1">
          <div class="hw-card">
            <div class="hw-header">
              <span class="hw-label">Savings Goal</span>
              <i class="ph ph-piggy-bank hw-icon"></i>
            </div>
            <div class="hw-plan-name">Emergency Fund</div>
            <div class="hw-amount">$8,200<span class="hw-target"> / $10,000</span></div>
            <div class="hw-progress">
              <div class="hw-progress-bar">
                <div class="hw-progress-fill" style="width:82%"></div>
              </div>
              <span class="hw-progress-label">82<i class="ph ph-percent"></i> complete</span>
            </div>
            <div class="hw-rows">
              <div class="hw-row">
                <span class="hw-row-label">Interest rate</span>
                <span class="hw-row-value">5.5<i class="ph ph-percent"></i> p.a.</span>
              </div>
              <div class="hw-row">
                <span class="hw-row-label">Monthly gain</span>
                <span class="hw-row-value hw-positive">+$37.58</span>
              </div>
            </div>
            <a href="/register" class="hw-cta">Start Saving <i class="ph ph-arrow-right"></i></a>
          </div>
        </div>

        <!-- Widget 2: Fixed Deposit -->
        <div class="hero-widget" data-slide="2">
          <div class="hw-card">
            <div class="hw-header">
              <span class="hw-label">Fixed Deposit</span>
              <i class="ph ph-chart-line-up hw-icon"></i>
            </div>
            <div class="hw-plan-name">Standard Deposit · 12 months</div>
            <div class="hw-amount">$5,000<span class="hw-cents">.00</span></div>
            <div class="hw-rows">
              <div class="hw-row">
                <span class="hw-row-label">Interest rate</span>
                <span class="hw-row-value">12.0<i class="ph ph-percent"></i> p.a.</span>
              </div>
              <div class="hw-row">
                <span class="hw-row-label">Expected return</span>
                <span class="hw-row-value hw-positive">+$600.00</span>
              </div>
              <div class="hw-row">
                <span class="hw-row-label">Matures</span>
                <span class="hw-row-value">Dec 2025</span>
              </div>
            </div>
            <a href="/register" class="hw-cta">Open a Deposit <i class="ph ph-arrow-right"></i></a>
          </div>
        </div>

        <!-- Widget 3: Active Loan -->
        <div class="hero-widget" data-slide="3">
          <div class="hw-card">
            <div class="hw-header">
              <span class="hw-label">Active Loan</span>
              <i class="ph ph-hand-coins hw-icon"></i>
            </div>
            <div class="hw-plan-name">Personal Loan · 24 months</div>
            <div class="hw-amount">$15,000<span class="hw-cents">.00</span></div>
            <div class="hw-progress">
              <div class="hw-progress-bar">
                <div class="hw-progress-fill hw-progress-fill--loan" style="width:25%"></div>
              </div>
              <span class="hw-progress-label">$3,800 repaid of $15,000</span>
            </div>
            <div class="hw-rows">
              <div class="hw-row">
                <span class="hw-row-label">Monthly payment</span>
                <span class="hw-row-value">$420.00</span>
              </div>
              <div class="hw-row">
                <span class="hw-row-label">Interest rate</span>
                <span class="hw-row-value">10.0<i class="ph ph-percent"></i> p.a.</span>
              </div>
            </div>
            <a href="/register" class="hw-cta">Apply for a Loan <i class="ph ph-arrow-right"></i></a>
          </div>
        </div>

      </div><!-- .hero-right -->

    </div><!-- .hero-split -->

    <!-- ── Bottom step indicators ── -->
    <div class="hero-steps" role="tablist" aria-label="Product categories">
      <button class="hero-step active" data-step="0" role="tab" aria-selected="true">
        <span class="step-num">01</span>
        <span class="step-name">Banking</span>
        <div class="step-bar"><div class="step-bar-fill"></div></div>
      </button>
      <button class="hero-step" data-step="1" role="tab" aria-selected="false">
        <span class="step-num">02</span>
        <span class="step-name">Savings</span>
        <div class="step-bar"><div class="step-bar-fill"></div></div>
      </button>
      <button class="hero-step" data-step="2" role="tab" aria-selected="false">
        <span class="step-num">03</span>
        <span class="step-name">Deposits</span>
        <div class="step-bar"><div class="step-bar-fill"></div></div>
      </button>
      <button class="hero-step" data-step="3" role="tab" aria-selected="false">
        <span class="step-num">04</span>
        <span class="step-name">Loans</span>
        <div class="step-bar"><div class="step-bar-fill"></div></div>
      </button>
    </div>

  </section>

  <!-- ── Bento Stats ────────────────────────────────────────────── -->
  <section class="bento-section section" role="region" aria-label="Platform statistics">
    <div class="container">
      <div class="bento-grid">

        <!-- Large left card — spans 2 rows on desktop -->
        <div class="bento-card bento-card--md" style="grid-row: span 2;" data-appear>
          <p class="bento-eyebrow">Platform Scale</p>
          <p class="bento-stat-value" data-counter="50000">0</p>
          <p class="bento-stat-label">Active customers banking with CrestVale across the globe.</p>
        </div>

        <!-- Top-right row -->
        <div class="bento-card bento-card--sm" data-appear>
          <p class="bento-eyebrow">Assets Managed</p>
          <p class="bento-stat-value" data-counter="200" data-counter-prefix="$" data-counter-suffix="M+">0</p>
          <p class="bento-stat-label">In customer funds</p>
        </div>

        <div class="bento-card bento-card--sm" data-appear>
          <p class="bento-eyebrow">Max Annual Rate</p>
          <p class="bento-stat-value" data-counter="12" data-counter-suffix="<i class="ph ph-percent"></i>">0</p>
          <p class="bento-stat-label">On fixed deposits</p>
        </div>

        <!-- Bottom-right row -->
        <div class="bento-card bento-card--sm" data-appear>
          <p class="bento-eyebrow">Uptime</p>
          <p class="bento-stat-value" data-counter="99.9" data-counter-suffix="<i class="ph ph-percent"></i>">0</p>
          <p class="bento-stat-label">Platform reliability</p>
        </div>

        <div class="bento-card bento-card--sm" data-appear>
          <p class="bento-eyebrow">Customer Rating</p>
          <p class="bento-stat-value" data-counter="4.8" data-counter-suffix="★">0</p>
          <p class="bento-stat-label">Average satisfaction score</p>
        </div>

      </div>
    </div>
  </section>

  <!-- ── Feature Sections ───────────────────────────────────────── -->
  <section class="features section" role="region" aria-labelledby="feat1-title">
    <div class="feature-container container" data-appear>
      <div class="feature-content">
        <span class="section-label">SAVINGS</span>
        <h2 id="feat1-title" class="feature-title">Goal-Based Savings, Automated</h2>
        <p class="feature-description">
          Create personalised savings plans for any goal — a home, a car, an emergency
          fund, or your future. Set a target, contribute regularly, and watch interest
          accumulate. CrestVale Bank takes care of the rest.
        </p>
        <a href="/products#savings" class="btn-learn-more" aria-label="Learn more about savings plans">
          Explore Savings <i class="ph ph-arrow-right" aria-hidden="true"></i>
        </a>
      </div>
      <div class="feature-image">
        <div class="feature-image-placeholder glass"><img loading="lazy" src="/assets/images/background/goal.svg" alt="Goal-based savings plans"></div>
      </div>
    </div>
  </section>

  <section class="features section" role="region" aria-labelledby="feat2-title">
    <div class="feature-container container" data-appear>
      <div class="feature-image">
        <div class="feature-image-placeholder glass">
        <img loading="lazy" src="/assets/images/background/deposit.svg" alt="Fixed deposits"></div>
      </div>
      <div class="feature-content">
        <span class="section-label">FIXED DEPOSITS</span>
        <h2 id="feat2-title" class="feature-title">Lock In Higher Returns</h2>
        <p class="feature-description">
          Fixed deposits offer competitive rates for customers willing to commit
          for a set duration. Choose from 3, 6, or 12-month terms and earn up to
          12<i class="ph ph-percent"></i> per annum. Your principal and interest are returned at maturity.
        </p>
        <a href="/products#deposits" class="btn-learn-more" aria-label="Learn more about fixed deposits">
          View Deposit Rates <i class="ph ph-arrow-right" aria-hidden="true"></i>
        </a>
      </div>
    </div>
  </section>

  <section class="features section" role="region" aria-labelledby="feat3-title">
    <div class="feature-container container" data-appear>
      <div class="feature-content">
        <span class="section-label">SECURITY</span>
        <h2 id="feat3-title" class="feature-title">Bank-Grade Security, Always On</h2>
        <p class="feature-description">
          Every transaction is protected with AES-256 encryption. Two-factor
          authentication, 24/7 fraud monitoring, and real-time alerts keep your
          account and funds safe at every step.
        </p>
        <a href="/security" class="btn-learn-more" aria-label="Learn more about security">
          Our Security <i class="ph ph-arrow-right" aria-hidden="true"></i>
        </a>
      </div>
      <div class="feature-image">
        <div class="feature-image-placeholder glass"><img loading="lazy" src="/assets/images/background/security.svg" alt="Secure banking infrastructure"></div>
      </div>
    </div>
  </section>

  <!-- ── Why Us ──────────────────────────────────────────────────── -->
  <section class="why-us section" role="region" aria-labelledby="why-us-title">
    <div class="why-us-container container" data-appear>
      <div class="why-us-image">
        <div class="feature-image-placeholder glass"><img loading="lazy" src="/assets/images/background/returns.svg" alt="Why choose CrestVale Bank"></div>
      </div>
      <div class="why-us-content">
        <span class="section-label">WHY US</span>
        <h2 id="why-us-title" class="why-us-title">We handle the complexity so you don't have to</h2>
        <p class="why-us-description">
          CrestVale Bank's end-to-end banking solution removes the friction — letting
          you focus entirely on reaching your financial <strong>goals</strong>.
        </p>
      </div>
    </div>
  </section>

  <!-- ── Reliable Partner ───────────────────────────────────────── -->
  <section class="reliable section" role="region" aria-labelledby="reliable-title">
    <div class="reliable-container container" data-appear>
      <div class="reliable-content">
        <span class="section-label">RELIABLE PARTNER</span>
        <h2 id="reliable-title" class="reliable-title">
          Safety, security, and compliance at the core of everything we do
        </h2>
      </div>
      <div class="reliable-list">
        <div class="reliable-item">
          <span class="reliable-number">01</span>
          <h3 class="reliable-item-title">Regulated</h3>
          <p class="reliable-item-description">Fully compliant operations with robust KYC and AML procedures in place.</p>
        </div>
        <div class="reliable-item">
          <span class="reliable-number">02</span>
          <h3 class="reliable-item-title">Secure Infrastructure</h3>
          <p class="reliable-item-description">AES-256 encryption and institutional-grade security ensure your funds remain protected.</p>
        </div>
        <div class="reliable-item">
          <span class="reliable-number">03</span>
          <h3 class="reliable-item-title">Transparent Operations</h3>
          <p class="reliable-item-description">Full visibility into all transactions, rates, and account activity — no surprises.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ── For Who ────────────────────────────────────────────────── -->
  <section class="for-who section" role="region" aria-labelledby="for-who-title">
    <div class="for-who-container container" data-appear>
      <div class="for-who-content">
        <span class="section-label">FOR WHO</span>
        <h2 id="for-who-title" class="for-who-title">Built for every kind of customer</h2>
      </div>
      <div class="for-who-cards">
        <div class="for-who-card glass">
          <i class="ph ph-user-circle for-who-icon" aria-hidden="true"></i>
          <h3 class="for-who-card-title">Individual Customers</h3>
          <p class="for-who-card-description">
            Build wealth, save smarter, and access credit — all from a single,
            easy-to-use banking platform designed for your goals.
          </p>
        </div>
        <div class="for-who-card glass">
          <i class="ph ph-buildings for-who-icon" aria-hidden="true"></i>
          <h3 class="for-who-card-title">Small Businesses</h3>
          <p class="for-who-card-description">
            Manage business funds, access working capital loans, and make instant
            transfers to partners and suppliers with zero friction.
          </p>
        </div>
        <div class="for-who-card glass">
          <i class="ph ph-handshake for-who-icon" aria-hidden="true"></i>
          <h3 class="for-who-card-title">Financial Partners</h3>
          <p class="for-who-card-description">
            Offer your customers access to competitive savings and deposit products
            by partnering with CrestVale Bank's fintech infrastructure.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- ── Reviews ────────────────────────────────────────────────── -->
  <section class="review section" role="region" aria-labelledby="reviews-title">
    <div class="review-container container" data-appear>
      <div class="review-content">
        <span class="section-label">REVIEWS</span>
        <h2 id="reviews-title" class="review-title">What our customers say</h2>
      </div>
      <div class="review-cards">
        <div class="review-card glass">
          <div class="review-stars" aria-label="5 stars">
            <i class="ph ph-star-fill" aria-hidden="true"></i>
            <i class="ph ph-star-fill" aria-hidden="true"></i>
            <i class="ph ph-star-fill" aria-hidden="true"></i>
            <i class="ph ph-star-fill" aria-hidden="true"></i>
            <i class="ph ph-star-fill" aria-hidden="true"></i>
          </div>
          <p class="review-card-description">
            "CrestVale Bank made saving effortless. I set up a goal, link contributions,
            and the interest just accumulates. I hit my home deposit target six months
            ahead of schedule."
          </p>
          <h3 class="review-card-title">Jane McMahon</h3>
        </div>
        <div class="review-card glass">
          <div class="review-stars" aria-label="5 stars">
            <i class="ph ph-star-fill" aria-hidden="true"></i>
            <i class="ph ph-star-fill" aria-hidden="true"></i>
            <i class="ph ph-star-fill" aria-hidden="true"></i>
            <i class="ph ph-star-fill" aria-hidden="true"></i>
            <i class="ph ph-star-fill" aria-hidden="true"></i>
          </div>
          <p class="review-card-description">
            "The fixed deposit rates are genuinely competitive. I locked in a 12-month
            deposit and the returns at maturity were exactly as promised. Fully transparent,
            no hidden fees."
          </p>
          <h3 class="review-card-title">Kelvin Matthews</h3>
        </div>
        <div class="review-card glass">
          <div class="review-stars" aria-label="5 stars">
            <i class="ph ph-star-fill" aria-hidden="true"></i>
            <i class="ph ph-star-fill" aria-hidden="true"></i>
            <i class="ph ph-star-fill" aria-hidden="true"></i>
            <i class="ph ph-star-fill" aria-hidden="true"></i>
            <i class="ph ph-star-fill" aria-hidden="true"></i>
          </div>
          <p class="review-card-description">
            "Got approved for a loan within 24 hours. The process was simple, the
            monthly payment was fair, and the team was responsive throughout. I've
            recommended CrestVale to everyone I know."
          </p>
          <h3 class="review-card-title">Sandra Kelly</h3>
        </div>
      </div>
    </div>
  </section>

  <!-- ── Products CTA ───────────────────────────────────────────── -->
  <section class="pricing section" role="region" aria-labelledby="products-cta-title" id="products">
    <div class="pricing-container container" data-appear>
      <div class="section-header">
        <span class="section-label">PRODUCTS</span>
        <h2 id="products-cta-title" class="pricing-header">Everything you need, in one place</h2>
        <p class="section-subtitle">Savings, fixed deposits, loans, and transfers — all connected through your CrestVale wallet.</p>
      </div>
      <div class="pricing-cards">

        <!-- Savings -->
        <div class="pricing-card glass">
          <p class="pricing-subtitle">Build your financial future.</p>
          <h3 class="pricing-plan">Savings Plans</h3>
          <p class="pricing-price">5.5<i class="ph ph-percent"></i> <span class="pricing-rate">p.a.</span></p>
          <ul class="pricing-features">
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Goal-based savings</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Flexible contributions</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Interest accumulates monthly</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Track progress in real time</li>
          </ul>
          <a href="/register" class="btn-primary pricing-button" aria-label="Open a savings plan">
            Open a Plan
          </a>
        </div>

        <!-- Fixed Deposits -->
        <div class="pricing-card glass">
          <p class="pricing-subtitle">Lock in higher guaranteed returns.</p>
          <h3 class="pricing-plan">Fixed Deposits</h3>
          <p class="pricing-price">16<i class="ph ph-percent"></i> <span class="pricing-rate">p.a.</span></p>
          <ul class="pricing-features">
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> 6 or 24-month terms</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Higher rates than savings</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Principal returned at maturity</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Interest credited at term end</li>
          </ul>
          <a href="/register" class="btn-primary pricing-button" aria-label="Open a fixed deposit">
            Start Deposit
          </a>
        </div>

        <!-- Loans -->
        <div class="pricing-card glass">
          <p class="pricing-subtitle">Access capital when you need it.</p>
          <h3 class="pricing-plan">Loans</h3>
          <p class="pricing-price">12<i class="ph ph-percent"></i> <span class="pricing-rate">p.a.</span></p>
          <ul class="pricing-features">
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Flexible loan amounts</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Simple monthly repayments</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Fast admin approval</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Funds credited to wallet</li>
          </ul>
          <a href="/register" class="btn-primary pricing-button" aria-label="Apply for a loan">
            Apply Now
          </a>
        </div>

        <!-- Transfers -->
        <div class="pricing-card glass">
          <p class="pricing-subtitle">Move money instantly.</p>
          <h3 class="pricing-plan">Transfers</h3>
          <p class="pricing-price">Instant</p>
          <ul class="pricing-features">
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Wallet-to-wallet transfers</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Instant settlement</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Full transaction history</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> No transfer fees</li>
          </ul>
          <a href="/register" class="btn-primary pricing-button" aria-label="Start transferring">
            Get Started
          </a>
        </div>

      </div>
      <p class="pricing-disclaimer">All products are subject to our Terms of Service. Rates may vary. See our <a href="/products">Products page</a> for full details.</p>
    </div>
  </section>

  <!-- ── CTA ────────────────────────────────────────────────────── -->
  <section class="cta-section section" role="region" aria-labelledby="cta-title">
    <div class="cta-container container" data-appear>
      <h2 id="cta-title" class="cta-header">Start banking smarter today</h2>
      <p class="cta-subtext">Join thousands of customers growing their money with CrestVale Bank.</p>
      <div class="cta-actions">
        <a href="/register" class="btn-primary" aria-label="Open your account">
          Open Your Account
          <i class="ph ph-arrow-right" aria-hidden="true"></i>
        </a>
        <a href="/help" class="btn-outline-white" aria-label="Learn how it works">
          How It Works
        </a>
      </div>
    </div>
  </section>

</main>

<?php require_once '../../includes/footer.php'; ?>
</body>
</html>

<?php
/**
 * Project: arqoracapital
 * Page: Homepage
 */
$pageTitle       = 'Home';
$pageDescription = 'ArqoraCapital is a globally trusted crypto investment platform. Earn daily returns through automated investment contracts — starting from 2% daily.';
$pageKeywords    = 'ArqoraCapital, crypto investment, daily returns, investment plans, cryptocurrency, bitcoin, ethereum, passive income';
require_once '../../includes/head.php';
?>

<?php require_once '../../includes/header.php'; ?>

<main>

  <!-- ── Hero Carousel ─────────────────────────────────────────── -->
  <section class="hero" id="heroCarousel" role="region" aria-label="Hero">

    <!-- ── Background slides ── -->
    <div class="hero-slides" aria-hidden="true">
      <div class="hero-slide active">
        <img loading="lazy" src="/assets/images/background/arqorabgimage.png" alt="" draggable="false">
      </div>
      <div class="hero-slide">
        <img loading="lazy" src="/assets/images/background/arqorabgimage2.png" alt="" draggable="false">
      </div>
      <div class="hero-slide">
        <img loading="lazy" src="/assets/images/background/arqorabgimage3.png" alt="" draggable="false">
      </div>
    </div>

    <!-- ── Text content ── -->
    <div class="hero-container">

      <!-- Slide 1 -->
      <div class="hero-content active" data-slide="0">
        <span class="hero-tag">Platform</span>
        <h1 class="hero-heading">
          Grow wealth<br>while you invest
        </h1>
        <p class="hero-subtext">
          ArqoraCapital is a globally trusted platform designed to help individuals
          and businesses maximise returns through automated crypto investment contracts
          with daily payouts.
        </p>
        <div class="hero-actions">
          <a href="/login" class="btn-hero-outline">
            Get Started <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
          <a href="/learnmore" class="btn-hero-ghost">
            Learn More <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
        </div>
      </div>

      <!-- Slide 2 -->
      <div class="hero-content" data-slide="1">
        <span class="hero-tag">Daily Returns</span>
        <h1 class="hero-heading">
          Earn up to 8%<br>daily returns
        </h1>
        <p class="hero-subtext">
          Choose from Starter, Bronze, Silver, or Platinum plans — all with
          5-day contracts, daily profit payouts, and full principal return on completion.
        </p>
        <div class="hero-actions">
          <a href="#pricing" class="btn-hero-outline">
            View Plans <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
          <a href="/learnmore" class="btn-hero-ghost">
            How It Works <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
        </div>
      </div>

      <!-- Slide 3 -->
      <div class="hero-content" data-slide="2">
        <span class="hero-tag">Community</span>
        <h1 class="hero-heading">
          Trusted by investors<br>worldwide
        </h1>
        <p class="hero-subtext">
          Join over 48,000 active investors across 130 countries who rely on
          ArqoraCapital for secure, transparent, automated daily crypto returns.
        </p>
        <div class="hero-actions">
          <a href="/login" class="btn-hero-outline">
            Join Now <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
          <a href="/contact" class="btn-hero-ghost">
            Contact Us <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
        </div>
      </div>

    </div>

    <!-- ── Dot indicators ── -->
    <div class="hero-dots" role="tablist" aria-label="Carousel slides">
      <button class="hero-dot active" role="tab" aria-selected="true"  aria-label="Slide 1"></button>
      <button class="hero-dot"        role="tab" aria-selected="false" aria-label="Slide 2"></button>
      <button class="hero-dot"        role="tab" aria-selected="false" aria-label="Slide 3"></button>
    </div>

  </section>

  <!-- ── Metrics Ticker ─────────────────────────────────────────── -->
  <div class="metrics-ticker" aria-label="Live market prices" role="marquee">
    <div class="ticker-track" id="tickerTrack">
      <!-- Populated by main.js with live CoinGecko prices -->
      <span class="ticker-item">
        <span class="ticker-label">BTC</span>
        <span class="ticker-value" data-coin="bitcoin">$—</span>
      </span>
      <span class="ticker-item">
        <span class="ticker-label">ETH</span>
        <span class="ticker-value" data-coin="ethereum">$—</span>
      </span>
      <span class="ticker-item">
        <span class="ticker-label">BNB</span>
        <span class="ticker-value" data-coin="binancecoin">$—</span>
      </span>
      <span class="ticker-item">
        <span class="ticker-label">USDC</span>
        <span class="ticker-value" data-coin="usd-coin">$—</span>
      </span>
      <span class="ticker-item">
        <span class="ticker-label">SOL</span>
        <span class="ticker-value" data-coin="solana">$—</span>
      </span>
      <span class="ticker-item">
        <span class="ticker-label">XRP</span>
        <span class="ticker-value" data-coin="ripple">$—</span>
      </span>
      <!-- Duplicated for seamless loop -->
      <span class="ticker-item" aria-hidden="true">
        <span class="ticker-label">BTC</span>
        <span class="ticker-value">$—</span>
      </span>
      <span class="ticker-item" aria-hidden="true">
        <span class="ticker-label">ETH</span>
        <span class="ticker-value">$—</span>
      </span>
      <span class="ticker-item" aria-hidden="true">
        <span class="ticker-label">BNB</span>
        <span class="ticker-value">$—</span>
      </span>
      <span class="ticker-item" aria-hidden="true">
        <span class="ticker-label">USDC</span>
        <span class="ticker-value">$—</span>
      </span>
      <span class="ticker-item" aria-hidden="true">
        <span class="ticker-label">SOL</span>
        <span class="ticker-value">$—</span>
      </span>
      <span class="ticker-item" aria-hidden="true">
        <span class="ticker-label">XRP</span>
        <span class="ticker-value">$—</span>
      </span>
    </div>
  </div>

  <!-- ── Bento Stats ────────────────────────────────────────────── -->
  <section class="bento-section section" role="region" aria-label="Platform statistics">
    <div class="container">
      <div class="bento-grid">

        <!-- Large left card — spans 2 rows on desktop -->
        <div class="bento-card bento-card--md" style="grid-row: span 2;" data-appear>
          <p class="bento-eyebrow">Platform Scale</p>
          <p class="bento-stat-value" data-counter="48000">0</p>
          <p class="bento-stat-label">Active investors worldwide trusting ArqoraCapital with their capital.</p>
        </div>

        <!-- Top-right row -->
        <div class="bento-card bento-card--sm" data-appear>
          <p class="bento-eyebrow">Total Paid Out</p>
          <p class="bento-stat-value" data-counter="124" data-counter-prefix="$" data-counter-suffix="M">0</p>
          <p class="bento-stat-label">In investor returns</p>
        </div>

        <div class="bento-card bento-card--sm" data-appear>
          <p class="bento-eyebrow">Max Daily Yield</p>
          <p class="bento-stat-value" data-counter="8" data-counter-suffix="%">0</p>
          <p class="bento-stat-label">On the Platinum plan</p>
        </div>

        <!-- Bottom-right row -->
        <div class="bento-card bento-card--sm" data-appear>
          <p class="bento-eyebrow">Uptime</p>
          <p class="bento-stat-value" data-counter="99.9" data-counter-suffix="%">0</p>
          <p class="bento-stat-label">Platform reliability</p>
        </div>

        <div class="bento-card bento-card--sm" data-appear>
          <p class="bento-eyebrow">Countries</p>
          <p class="bento-stat-value" data-counter="130">0</p>
          <p class="bento-stat-label">Supported worldwide</p>
        </div>

      </div>
    </div>
  </section>

  <!-- ── Feature Sections ───────────────────────────────────────── -->
  <section class="features section" role="region" aria-labelledby="feat1-title">
    <div class="feature-container container" data-appear>
      <div class="feature-content">
        <span class="section-label">SOLUTIONS</span>
        <h2 id="feat1-title" class="feature-title">Automated Wealth Optimization</h2>
        <p class="feature-description">
          ArqoraCapital uses a data-driven system that dynamically adjusts investment
          allocations based on real-time market trends. This ensures users maximise
          their returns by automatically optimising capital deployment across Bitcoin,
          Ethereum, and other leading cryptocurrencies.
        </p>
        <a href="/solutions" class="btn-learn-more" aria-label="Learn more about solutions">
          Learn More <i class="ph ph-arrow-right" aria-hidden="true"></i>
        </a>
      </div>
      <div class="feature-image">
        <div class="feature-image-placeholder glass"><img loading="lazy" src="/assets/images/background/automate-wealth.svg" alt="Automated wealth optimization"></div>
      </div>
    </div>
  </section>

  <section class="features section" role="region" aria-labelledby="feat2-title">
    <div class="feature-container container" data-appear>
      <div class="feature-image">
        <div class="feature-image-placeholder glass">
        <img loading="lazy" src="/assets/images/background/flexinvest.svg" alt="Flexible investment contracts"></div>
      </div>
      <div class="feature-content">
        <span class="section-label">SOLUTIONS</span>
        <h2 id="feat2-title" class="feature-title">Flexible Investment Contracts</h2>
        <p class="feature-description">
          Choose from a range of investment plans starting from $100. All contracts
          run for 5 days with daily profit payouts and full principal return on
          completion. From Starter to Platinum — there is a plan for every investor.
        </p>
        <a href="/solutions" class="btn-learn-more" aria-label="Learn more about investment contracts">
          Learn More <i class="ph ph-arrow-right" aria-hidden="true"></i>
        </a>
      </div>
    </div>
  </section>

  <section class="features section" role="region" aria-labelledby="feat3-title">
    <div class="feature-container container" data-appear>
      <div class="feature-content">
        <span class="section-label">SOLUTIONS</span>
        <h2 id="feat3-title" class="feature-title">Secure, Transparent Infrastructure</h2>
        <p class="feature-description">
          Every transaction is recorded and auditable. ArqoraCapital operates with
          institutional-grade security standards — ensuring your capital and earnings
          are protected at every step.
        </p>
        <a href="/solutions" class="btn-learn-more" aria-label="Learn more about security">
          Learn More <i class="ph ph-arrow-right" aria-hidden="true"></i>
        </a>
      </div>
      <div class="feature-image">
        <div class="feature-image-placeholder glass"><img loading="lazy" src="/assets/images/background/secure.svg" alt="Secure infrastructure"></div>
      </div>
    </div>
  </section>

  <!-- ── Why Us ──────────────────────────────────────────────────── -->
  <section class="why-us section" role="region" aria-labelledby="why-us-title">
    <div class="why-us-container container" data-appear>
      <div class="why-us-image">
        <div class="feature-image-placeholder glass"><img loading="lazy" src="/assets/images/background/heavy-lifting.svg" alt="Why choose ArqoraCapital"></div>
      </div>
      <div class="why-us-content">
        <span class="section-label">WHY US</span>
        <h2 id="why-us-title" class="why-us-title">We've done all the heavy lifting for you</h2>
        <p class="why-us-description">
          ArqoraCapital's end-to-end investment solution removes the complexity —
          letting you focus entirely on growing your <strong>wealth</strong>.
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
          Safety, security, and compliance are fundamental to our offering
        </h2>
      </div>
      <div class="reliable-list">
        <div class="reliable-item">
          <span class="reliable-number">01</span>
          <h3 class="reliable-item-title">Regulated</h3>
          <p class="reliable-item-description">Fully compliant operations with robust KYC and AML procedures.</p>
        </div>
        <div class="reliable-item">
          <span class="reliable-number">02</span>
          <h3 class="reliable-item-title">Secure Custody</h3>
          <p class="reliable-item-description">Institutional custody partners ensure assets remain protected at all times.</p>
        </div>
        <div class="reliable-item">
          <span class="reliable-number">03</span>
          <h3 class="reliable-item-title">SOC-2 Certified</h3>
          <p class="reliable-item-description">Annual Type II assessment, upholding the highest security standards.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ── For Who ────────────────────────────────────────────────── -->
  <section class="for-who section" role="region" aria-labelledby="for-who-title">
    <div class="for-who-container container" data-appear>
      <div class="for-who-content">
        <span class="section-label">FOR WHO</span>
        <h2 id="for-who-title" class="for-who-title">Tailored to your use case</h2>
      </div>
      <div class="for-who-cards">
        <div class="for-who-card glass">
          <i class="ph ph-buildings for-who-icon" aria-hidden="true"></i>
          <h3 class="for-who-card-title">Financial Institutions</h3>
          <p class="for-who-card-description">
            Retain deposits and attract the next generation of members with self-service
            digital wealth solutions.
          </p>
        </div>
        <div class="for-who-card glass">
          <i class="ph ph-user-circle for-who-icon" aria-hidden="true"></i>
          <h3 class="for-who-card-title">Individual Investors</h3>
          <p class="for-who-card-description">
            Drive engagement and build wealth on a platform designed for daily returns
            and full transparency.
          </p>
        </div>
        <div class="for-who-card glass">
          <i class="ph ph-handshake for-who-icon" aria-hidden="true"></i>
          <h3 class="for-who-card-title">B2B Fintechs</h3>
          <p class="for-who-card-description">
            Acquire new customers and expand wallet share with existing ones by offering
            access to premium treasury services.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- ── Live Market Section ────────────────────────────────────── -->
  <section class="stock-section section" role="region" aria-labelledby="market-title">
    <div class="stock-container container" data-appear>
      <div class="stock-content">
        <span class="section-label">LIVE MARKETS</span>
        <h2 id="market-title" class="stock-header">Real-Time Market Insights</h2>
        <p class="stock-subtext">Stay ahead with live updates on top cryptocurrencies.</p>
        <ul class="stock-stats" role="list" id="crypto-list">
          <li>Bitcoin (BTC): <span class="price" data-coin="bitcoin">$0.00</span></li>
          <li>Ethereum (ETH): <span class="price" data-coin="ethereum">$0.00</span></li>
          <li>Binance Coin (BNB): <span class="price" data-coin="binancecoin">$0.00</span></li>
          <li>USDC: <span class="price" data-coin="usd-coin">$0.00</span></li>
        </ul>
      </div>
      <div class="stock-chart">
        <div class="tradingview-widget-container">
          <div id="tradingview_arqora"></div>
          <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
          <script type="text/javascript">
            new TradingView.widget({
              "width": "100%",
              "height": 500,
              "symbol": "COINBASE:BTCUSD",
              "interval": "D",
              "timezone": "Etc/UTC",
              "theme": "light",
              "style": "1",
              "locale": "en",
              "toolbar_bg": "#FFFFFF",
              "enable_publishing": false,
              "allow_symbol_change": true,
              "container_id": "tradingview_arqora"
            });
          </script>
        </div>
      </div>
    </div>
  </section>

  <!-- ── Reviews ────────────────────────────────────────────────── -->
  <section class="review section" role="region" aria-labelledby="reviews-title">
    <div class="review-container container" data-appear>
      <div class="review-content">
        <span class="section-label">REVIEWS</span>
        <h2 id="reviews-title" class="review-title">What do our investors say?</h2>
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
            "They pay good attention to me. They also provide quality and timely service
            which has, in no small measure, enabled me to achieve the much-needed objective
            of good returns on investment."
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
            "I've had a very good experience with ArqoraCapital. The company has related
            with me very well and has really added value to my portfolio. They take pride
            in putting your interests first."
          </p>
          <h3 class="review-card-title">Mr Kelvin Matthews</h3>
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
            "I've invested with ArqoraCapital for a while now, and I've been telling
            others to join. If they weren't exceptional at what they do, I wouldn't
            have been transacting with them for years."
          </p>
          <h3 class="review-card-title">Engr Sandra Kelly</h3>
        </div>
      </div>
    </div>
  </section>

  <!-- ── Pricing ────────────────────────────────────────────────── -->
  <section class="pricing section" role="region" aria-labelledby="pricing-title" id="pricing">
    <div class="pricing-container container" data-appear>
      <div class="section-header">
        <span class="section-label">PLANS</span>
        <h2 id="pricing-title" class="pricing-header">Choose Your Plan</h2>
        <p class="section-subtitle">All plans include daily payouts + full principal return after 5 days.</p>
      </div>
      <div class="pricing-cards">

        <!-- Starter -->
        <div class="pricing-card glass">
          <p class="pricing-subtitle">For investors getting started.</p>
          <h3 class="pricing-plan">Starter</h3>
          <p class="pricing-price">2% <span class="pricing-rate">/ day</span></p>
          <ul class="pricing-features">
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Min: $100 — Max: $499</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Duration: 5 Days</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Return of Capital: Yes</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Daily Payouts</li>
          </ul>
          <a href="/login" class="btn-primary pricing-button" aria-label="Get started with Starter Plan">
            Get Started
          </a>
        </div>

        <!-- Bronze (popular) -->
        <div class="pricing-card glass">
          <p class="pricing-subtitle">For investors scaling up.</p>
          <h3 class="pricing-plan">Bronze</h3>
          <p class="pricing-price">4% <span class="pricing-rate">/ day</span></p>
          <ul class="pricing-features">
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Min: $500 — Max: $2,999</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Duration: 5 Days</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Return of Capital: Yes</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Priority Support</li>
          </ul>
          <a href="/login" class="btn-primary pricing-button" aria-label="Get started with Bronze Plan">
            Get Started
          </a>
        </div>

        <!-- Silver -->
        <div class="pricing-card glass">
          <p class="pricing-subtitle">For investors with advanced needs.</p>
          <h3 class="pricing-plan">Silver</h3>
          <p class="pricing-price">6% <span class="pricing-rate">/ day</span></p>
          <ul class="pricing-features">
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Min: $3,000 — Max: $4,999</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Duration: 5 Days</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Return of Capital: Yes</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Premium Support</li>
          </ul>
          <a href="/login" class="btn-primary pricing-button" aria-label="Get started with Silver Plan">
            Get Started
          </a>
        </div>

        <!-- Platinum -->
        <div class="pricing-card glass">
          <p class="pricing-subtitle">For elite investors.</p>
          <h3 class="pricing-plan">Platinum</h3>
          <p class="pricing-price">8% <span class="pricing-rate">/ day</span></p>
          <ul class="pricing-features">
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Min: $5,000 — Unlimited</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Duration: 5 Days</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Return of Capital: Yes</li>
            <li><i class="ph ph-check-circle" aria-hidden="true"></i> Dedicated Manager</li>
          </ul>
          <a href="/login" class="btn-primary pricing-button" aria-label="Get started with Platinum Plan">
            Get Started
          </a>
        </div>

      </div>
      <p class="pricing-disclaimer">All plans are subject to our Fair Use Policy. Crypto investments carry risk.</p>
    </div>
  </section>

  <!-- ── CTA ────────────────────────────────────────────────────── -->
  <section class="cta-section section" role="region" aria-labelledby="cta-title">
    <div class="cta-container container" data-appear>
      <h2 id="cta-title" class="cta-header">Ready to grow your wealth?</h2>
      <p class="cta-subtext">Join thousands of investors earning daily returns on ArqoraCapital.</p>
      <div class="cta-actions">
        <a href="/login" class="btn-primary" aria-label="Open your account">
          Open Your Account
          <i class="ph ph-arrow-right" aria-hidden="true"></i>
        </a>
        <a href="/learnmore" class="btn-outline-white" aria-label="Learn how it works">
          How It Works
        </a>
      </div>
    </div>
  </section>

</main>

<?php require_once '../../includes/footer.php'; ?>
</body>
</html>

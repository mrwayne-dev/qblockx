<?php
/**
 * Project: arqoracapital
 * Page: Learn More
 */
$pageTitle       = 'Learn More';
$pageDescription = 'Discover how ArqoraCapital works. Learn about our investment platform, crypto strategies, daily returns, and how to get started.';
require_once '../../includes/head.php';
?>

<?php require_once '../../includes/header.php'; ?>

<main>

  <!-- ── Hero ──────────────────────────────────────────────────── -->
  <section class="hero" role="region" aria-label="Learn More hero">
    <img
      src="/assets/images/background/arqorabgimage2.png"
      alt=""
      class="hero-bg"
      aria-hidden="true"
      draggable="false"
    >

    <div class="hero-container" data-appear>
      <span class="hero-tag">How It Works</span>
      <h1 class="hero-heading">How we do it</h1>
      <p class="hero-subtext">
        Discover how ArqoraCapital empowers your financial growth with innovative
        crypto investment solutions. Explore our platform, tools, and opportunities
        to maximise your potential.
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

  <!-- ── What is ArqoraCapital ──────────────────────────────────── -->
  <section class="features section" role="region" aria-labelledby="what-is-title">
    <div class="feature-container container" data-appear>
      <div class="feature-content">
        <span class="section-label">OUR PLATFORM</span>
        <h2 id="what-is-title" class="feature-title">What is ArqoraCapital?</h2>
        <p class="feature-description">
          ArqoraCapital is a cutting-edge investment platform that combines advanced
          technology with expert financial strategies to help you navigate the world
          of cryptocurrency and digital assets. We've grown into a global leader,
          offering tools, automated contracts, and personalised solutions tailored
          to your financial goals.
        </p>
        <a href="/login" class="btn-learn-more">
          Start Investing <i class="ph ph-arrow-right" aria-hidden="true"></i>
        </a>
      </div>
      <div class="feature-image">
        <div class="feature-image-placeholder glass"> <img src="/assets/images/logo/5.png" alt="ArqoraCapital platform overview"></div>
      </div>
    </div>
  </section>

  <!-- ── Investment Options ─────────────────────────────────────── -->
  <section class="why-us section" id="investment-options" role="region" aria-labelledby="invest-opts-title">
    <div class="why-us-container container" data-appear>
      <div class="why-us-content">
        <span class="section-label">INVESTMENT OPPORTUNITIES</span>
        <h2 id="invest-opts-title" class="why-us-title">Explore Your Options</h2>
        <p class="why-us-description">
          From Bitcoin and Ethereum to alternative digital assets, ArqoraCapital
          provides a diverse range of investment options. Our platform supports
          automated daily returns, portfolio management, and access to emerging
          markets — all backed by real-time data.
        </p>
        <ul class="feature-list">
          <li><i class="ph ph-check-circle" aria-hidden="true"></i> Cryptocurrency Contracts (BTC, ETH, BNB, XRP)</li>
          <li><i class="ph ph-check-circle" aria-hidden="true"></i> Automated Daily Payout Strategies</li>
          <li><i class="ph ph-check-circle" aria-hidden="true"></i> Portfolio Diversification Tools</li>
          <li><i class="ph ph-check-circle" aria-hidden="true"></i> Full Principal Return on Contract Completion</li>
        </ul>
      </div>
      <div class="why-us-image">
        <!-- <img src="/assets/images/investmentopportunites.png" alt="Investment options"> -->
        <div class="feature-image-placeholder glass"></div>
      </div>
    </div>
  </section>

  <!-- ── Education / Resources ──────────────────────────────────── -->
  <section class="reliable section" role="region" aria-labelledby="education-title">
    <div class="reliable-container container" data-appear>
      <div class="reliable-content">
        <span class="section-label">EDUCATION</span>
        <h2 id="education-title" class="reliable-title">Empower Your Knowledge</h2>
        <p>
          Stay informed with our comprehensive educational resources. Whether you're
          new to investing or a seasoned trader, we provide the tools to enhance your
          financial literacy and investment confidence.
        </p>
      </div>
      <div class="reliable-list">
        <div class="reliable-item">
          <span class="reliable-number">01</span>
          <h3 class="reliable-item-title">Webinars &amp; Tutorials</h3>
          <p class="reliable-item-description">Live sessions with industry experts covering market trends and strategy.</p>
        </div>
        <div class="reliable-item">
          <span class="reliable-number">02</span>
          <h3 class="reliable-item-title">Market Insights</h3>
          <p class="reliable-item-description">Daily updates on crypto trends and investment opportunities.</p>
        </div>
        <div class="reliable-item">
          <span class="reliable-number">03</span>
          <h3 class="reliable-item-title">Guides &amp; Resources</h3>
          <p class="reliable-item-description">Free educational resources for all skill levels and experience.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ── CTA ────────────────────────────────────────────────────── -->
  <section class="cta-section section" role="region" aria-labelledby="learnmore-cta-title">
    <div class="cta-container container" data-appear>
      <h2 id="learnmore-cta-title" class="cta-header">Ready to start your journey?</h2>
      <p class="cta-subtext">Join ArqoraCapital today and unlock your financial potential.</p>
      <div class="cta-actions">
        <a href="/login" class="btn-shiny">
          Get Started <i class="ph ph-arrow-right" aria-hidden="true"></i>
        </a>
        <a href="/solutions" class="btn-outline">View Plans</a>
      </div>
    </div>
  </section>

</main>

<?php require_once '../../includes/footer.php'; ?>
</body>
</html>

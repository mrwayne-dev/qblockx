<?php
/**
 * Project: Qblockx
 * Page: About Us
 */
$pageTitle       = 'About Us';
$pageDescription = 'Learn about Qblockx — a multi-asset investment platform built to give individuals access to high-yield investment plans, commodities, and real estate with full transparency.';
$pageKeywords    = 'Qblockx about, investment platform, who we are, mission, values';
require_once '../../includes/head.php';
?>

<?php require_once '../../includes/header.php'; ?>

<main>

  <!-- ── 1. Hero ──────────────────────────────────────────────────── -->
  <div class="hero-outer">
    <div class="hero-panel">
      <div class="hero-content">

        <div class="badge-outer">
          <div class="badge-ring"></div>
          <div class="badge-ring" style="animation-delay:-1s;"></div>
          <div class="badge-ring" style="animation-delay:-2s;"></div>
          <div class="badge-inner">
            <i class="ph ph-info" aria-hidden="true" style="margin-right:6px;"></i>
            About Qblockx
          </div>
        </div>

        <h1 class="hero-h1">Built for serious<br>investors.</h1>

        <p class="hero-subtext">
          Qblockx is a multi-asset investment platform giving individuals access to structured, high-yield opportunities — from tiered investment plans to commodities and real estate.
        </p>

        <div class="hero-actions">
          <a href="/register" class="btn-primary">
            Start Investing <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
          <a href="/contact" class="btn-outline-white">
            Contact Us
          </a>
        </div>

      </div>
    </div>
  </div>


  <!-- ── 2. Mission ───────────────────────────────────────────────── -->
  <section class="section" id="mission" role="region" aria-labelledby="mission-title">
    <div class="container">

      <div class="section-header" data-appear>
        <span class="section-label">OUR MISSION</span>
        <h2 id="mission-title" class="section-title">
          Making high-yield investing accessible
        </h2>
        <p class="section-subtitle">
          We believe structured, transparent investment opportunities should not be reserved for institutional players. Qblockx opens the door for every serious investor.
        </p>
      </div>

      <!-- 2-card feature row -->
      <div class="feature-grid" style="grid-template-columns:1fr 1fr;" data-appear>

        <div class="feature-outer" style="flex-direction:column;">
          <div class="feature-inner">
            <div class="feature-icon">
              <i class="ph ph-eye" aria-hidden="true"></i>
            </div>
            <h3 class="feature-title">Radical Transparency</h3>
            <p class="feature-body">
              Every plan's return, duration, and terms are disclosed upfront — no hidden fees, no ambiguity. You know exactly what you're investing in before you commit a single dollar.
            </p>
          </div>
        </div>

        <div class="feature-outer" style="flex-direction:column;">
          <div class="feature-inner">
            <div class="feature-icon">
              <i class="ph ph-lock-key" aria-hidden="true"></i>
            </div>
            <h3 class="feature-title">Security First</h3>
            <p class="feature-body">
              Your funds and account data are protected by AES-256 encryption, two-factor authentication, and 24/7 activity monitoring. Security is built into every layer of the platform.
            </p>
          </div>
        </div>

      </div>

    </div>
  </section>


  <!-- ── 3. Values — dark panel ───────────────────────────────────── -->
  <section style="background:var(--color-bg); padding:0 0 var(--space-8);"
           id="values" role="region" aria-labelledby="values-title">
    <div class="section-dark">
      <div class="dark-section-inner">

        <div style="text-align:center; margin-bottom:var(--space-12);" data-appear>
          <span class="section-label" style="color:rgba(211,216,233,0.70);">CORE VALUES</span>
          <h2 id="values-title" class="section-title" style="color:#FFFFFF; margin-top:var(--space-3);">
            What drives us
          </h2>
        </div>

        <div class="asset-cards" data-appear>

          <div class="asset-card">
            <div class="asset-card-icon">
              <i class="ph ph-chart-line-up" aria-hidden="true"></i>
            </div>
            <h3 class="asset-card-title">Growth for Everyone</h3>
            <p class="asset-card-body">
              Whether you're starting with $1,000 or $1,000,000, Qblockx has a structured plan that fits your capital and timeline.
            </p>
          </div>

          <div class="asset-card">
            <div class="asset-card-icon">
              <i class="ph ph-shield-check" aria-hidden="true"></i>
            </div>
            <h3 class="asset-card-title">Trust Through Clarity</h3>
            <p class="asset-card-body">
              We publish every plan's parameters — minimum investment, duration, and expected return — so there are zero surprises at maturity.
            </p>
          </div>

          <div class="asset-card">
            <div class="asset-card-icon">
              <i class="ph ph-globe" aria-hidden="true"></i>
            </div>
            <h3 class="asset-card-title">Global Access</h3>
            <p class="asset-card-body">
              Investors from across the world can participate in plans, commodities, and real estate from a single unified dashboard.
            </p>
          </div>

        </div>

      </div>
    </div>
  </section>


  <!-- ── 4. How to Get Started ────────────────────────────────────── -->
  <section class="section" id="get-started" role="region" aria-labelledby="gs-title">
    <div class="container">

      <div style="text-align:center; margin-bottom:var(--space-16);" data-appear>
        <span class="section-label">GET STARTED</span>
        <h2 id="gs-title" class="section-title" style="margin-top:var(--space-3);">
          Three steps to your first investment
        </h2>
      </div>

      <div class="how-steps" data-appear>

        <div class="how-step">
          <div class="how-step-num" aria-hidden="true">01</div>
          <h3 class="how-step-title">Create your account</h3>
          <p class="how-step-body">Sign up with your email and personal details. Verify your identity to unlock full platform access.</p>
          <a href="/register" class="how-step-link">Register Now <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
        </div>

        <div class="how-step">
          <div class="how-step-num" aria-hidden="true">02</div>
          <h3 class="how-step-title">Fund your wallet</h3>
          <p class="how-step-body">Deposit funds into your Qblockx wallet. All investments, returns, and withdrawals flow through your central balance.</p>
        </div>

        <div class="how-step">
          <div class="how-step-num" aria-hidden="true">03</div>
          <h3 class="how-step-title">Choose a plan &amp; earn</h3>
          <p class="how-step-body">Browse Starter or Elite tiers, activate your plan, and watch returns credited automatically at maturity.</p>
          <a href="/#plans" class="how-step-link">View Plans <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
        </div>

      </div>

    </div>
  </section>


  <!-- ── 5. Partners ──────────────────────────────────────────────── -->
  <section class="section" style="padding-top:0;" role="region" aria-label="Partners">
    <div class="container">
      <p class="partners-label" data-appear>Trusted ecosystem partners</p>
      <div class="partners-grid" data-appear>
        <span class="partner-logo">Visa</span>
        <span class="partner-logo">Mastercard</span>
        <span class="partner-logo">Swift</span>
        <span class="partner-logo">Stripe</span>
        <span class="partner-logo">Plaid</span>
      </div>
    </div>
  </section>


  <!-- ── 6. CTA ────────────────────────────────────────────────────── -->
  <section style="background:var(--color-bg); padding:0 0 var(--space-8);"
           role="region" aria-labelledby="about-cta-title">
    <div class="section-dark" style="padding:var(--space-20) var(--space-6); text-align:center;">
      <div style="max-width:600px; margin:0 auto;" data-appear>
        <h2 id="about-cta-title" class="section-title" style="color:#FFFFFF; margin-bottom:var(--space-5);">
          Ready to start investing with Qblockx?
        </h2>
        <p class="section-subtitle" style="color:rgba(255,255,255,0.55); margin-bottom:var(--space-10);">
          Join thousands of investors already earning across our tiered plans, commodities, and real estate.
        </p>
        <div class="cta-actions">
          <a href="/register" class="btn-primary">
            Create Free Account <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
          <a href="/contact" class="btn-outline-white">Talk to Us</a>
        </div>
      </div>
    </div>
  </section>

</main>

<?php require_once '../../includes/footer.php'; ?>
</body>
</html>

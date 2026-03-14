<?php
/**
 * Project: crestvalebank
 * Page: About Us
 */
$pageTitle       = 'About Us';
$pageDescription = 'Learn about CrestVale Bank — a modern fintech banking platform committed to helping individuals and businesses grow their money through savings, deposits, loans, and transfers.';
require_once '../../includes/head.php';
?>

<?php require_once '../../includes/header.php'; ?>

<main>

  <!-- ── Hero ──────────────────────────────────────────────────── -->
  <section class="hero hero--static" role="region" aria-label="About hero">
    <div class="hero-split">
      <div class="hero-left">
        <div class="hero-content active" data-slide="0">
          <span class="hero-tag">About Us</span>
          <h1 class="hero-heading">
            <span class="hero-line">Who We Are</span>
          </h1>
          <p class="hero-subtext">
            CrestVale Bank is a modern fintech banking platform built to make financial
            growth accessible to everyone. We combine smart technology with competitive
            banking products to help individuals and businesses build lasting wealth.
          </p>
          <div class="hero-actions">
            <a href="/register" class="btn-hero-outline">
              Open Account <i class="ph ph-arrow-right" aria-hidden="true"></i>
            </a>
            <a href="/help" class="btn-hero-ghost">
              Learn More <i class="ph ph-arrow-right" aria-hidden="true"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ── Mission & Values ───────────────────────────────────────── -->
  <section class="legal-doc section" role="region" aria-labelledby="mission-title">
    <div class="legal-doc-container container" data-appear>
      <div class="legal-doc-content">
        <span class="section-label">MISSION</span>
        <h2 id="mission-title" class="legal-doc-title">Our Mission</h2>
        <p class="legal-doc-description">
          At CrestVale Bank, our mission is simple: make financial growth accessible,
          transparent, and secure for everyone. We believe that building wealth
          shouldn't require expert knowledge or large starting capital — just the
          right tools and a platform you can trust.
        </p>
        <p class="legal-doc-description">
          We provide goal-based savings plans, competitive fixed deposits, flexible
          loans, and instant transfers — all managed through a single, easy-to-use
          digital wallet. Every product is designed with clarity and fairness at its core.
        </p>
        <h2 class="legal-doc-title">Our Values</h2>
        <p class="legal-doc-description">
          <strong>Transparency:</strong> We believe customers deserve to know exactly
          how their money works. Rates, terms, and fees are always clearly disclosed —
          no hidden charges, no surprises.
        </p>
        <p class="legal-doc-description">
          <strong>Security:</strong> Every account is protected with AES-256 encryption,
          two-factor authentication, and 24/7 fraud monitoring. Your funds and personal
          data are safeguarded at every layer.
        </p>
        <p class="legal-doc-description">
          <strong>Accessibility:</strong> CrestVale Bank is designed for everyone —
          whether you're saving your first $100 or managing a business float. Our
          platform is intuitive, mobile-first, and always available.
        </p>
      </div>
    </div>
  </section>

  <!-- ── How It Works ───────────────────────────────────────────── -->
  <section class="legal-doc section" role="region" aria-labelledby="how-to-title">
    <div class="legal-doc-container container" data-appear>
      <div class="legal-doc-content">
        <span class="section-label">GET STARTED</span>
        <h2 id="how-to-title" class="legal-doc-title">How to Get Started</h2>

        <h3 class="legal-doc-subtitle">Step 1: Create Your Account</h3>
        <p class="legal-doc-description">
          Sign up in minutes with your basic personal details. Once registered and
          verified, you'll get access to your personalised dashboard and central wallet.
        </p>

        <h3 class="legal-doc-subtitle">Step 2: Fund Your Wallet</h3>
        <p class="legal-doc-description">
          Deposit funds into your CrestVale wallet. Your wallet is the central hub — all
          savings contributions, fixed deposit locks, loan credits, and transfers flow
          through it.
        </p>

        <h3 class="legal-doc-subtitle">Step 3: Choose Your Product</h3>
        <p class="legal-doc-description">
          Create a savings plan, open a fixed deposit, apply for a loan, or make an
          instant transfer. Each product is clearly explained with rates, durations,
          and expected returns upfront.
        </p>

        <h3 class="legal-doc-subtitle">Step 4: Track and Grow</h3>
        <p class="legal-doc-description">
          Monitor your savings progress, upcoming loan payments, deposit maturity dates,
          and full transaction history — all from your dashboard in real time.
        </p>
      </div>
    </div>
  </section>

  <!-- ── Partners ───────────────────────────────────────────────── -->
  <section class="clients section" role="region" aria-labelledby="partners-title">
    <div class="clients-container container" data-appear>
      <div class="clients-content">
        <span class="section-label">ECOSYSTEM</span>
        <h2 id="partners-title" class="clients-title">Our Partners</h2>
      </div>
      <div class="clients-logos">
        <div class="clients-logo">
          <span class="clients-logo-placeholder">Visa</span>
        </div>
        <div class="clients-logo">
          <span class="clients-logo-placeholder">Mastercard</span>
        </div>
        <div class="clients-logo">
          <span class="clients-logo-placeholder">Swift</span>
        </div>
        <div class="clients-logo">
          <span class="clients-logo-placeholder">Stripe</span>
        </div>
        <div class="clients-logo">
          <span class="clients-logo-placeholder">Plaid</span>
        </div>
      </div>
    </div>
  </section>

  <!-- ── Compliance ─────────────────────────────────────────────── -->
  <section class="implement section" role="region" aria-labelledby="cert-title">
    <div class="implement-container container" data-appear>
      <div class="implement-content">
        <span class="section-label">COMPLIANCE</span>
        <h2 id="cert-title" class="implement-title">Regulated &amp; Compliant</h2>
        <p>CrestVale Bank operates with full regulatory compliance, including KYC
           (Know Your Customer) and AML (Anti-Money Laundering) procedures, to protect
           customers and ensure a safe banking environment.</p>
      </div>
      <div class="implement-image">
        <div class="feature-image-placeholder glass">
          <img loading="lazy" src="/assets/images/background/2f2d8620-41b2-4f09-816d-9e565f3c7b40.png" alt="CrestVale Bank Compliance">
        </div>
      </div>
    </div>
  </section>

  <!-- ── CTA ────────────────────────────────────────────────────── -->
  <section class="cta-section section" role="region" aria-labelledby="about-cta-title">
    <div class="cta-container container" data-appear>
      <h2 id="about-cta-title" class="cta-header">Ready to join CrestVale Bank?</h2>
      <p class="cta-subtext">Open an account today and start building your financial future.</p>
      <div class="cta-actions">
        <a href="/register" class="btn-cta">
          Get Started <i class="ph ph-arrow-right" aria-hidden="true"></i>
        </a>
        <a href="/contact" class="btn-cta-ghost">
          Contact Us <i class="ph ph-arrow-right" aria-hidden="true"></i>
        </a>
      </div>
    </div>
  </section>

</main>

<?php require_once '../../includes/footer.php'; ?>
</body>
</html>

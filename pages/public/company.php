<?php
/**
 * Project: arqoracapital
 * Page: Company (Legal Documents)
 */
$pageTitle       = 'Company';
$pageDescription = 'Explore ArqoraCapital\'s legal documents including general disclosures, privacy policy, terms of use, and form ADV information.';
require_once '../../includes/head.php';
?>

<?php require_once '../../includes/header.php'; ?>

<main>

  <!-- ── Hero ──────────────────────────────────────────────────── -->
  <section class="hero" role="region" aria-label="Company hero">
    <img
      src="/assets/images/background/arqorabgimage2.png"
      alt=""
      class="hero-bg"
      aria-hidden="true"
      draggable="false"
    >

    <div class="hero-container" data-appear>
      <span class="hero-tag">Company</span>
      <h1 class="hero-heading">Legal Documents</h1>
      <p class="hero-subtext">
        Explore the key legal documents governing ArqoraCapital's operations,
        including agreements, disclosures, and regulatory information. These
        resources support our commitment to transparency.
      </p>
      <div class="hero-actions">
        <a href="/contact" class="btn-hero-outline">
          Contact Us <i class="ph ph-arrow-right" aria-hidden="true"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- ── General Disclosures ────────────────────────────────────── -->
  <section class="legal-doc section" role="region" aria-labelledby="general-disc-title">
    <div class="legal-doc-container container" data-appear>
      <div class="legal-doc-content">
        <span class="section-label">LEGAL DOCUMENTS</span>
        <h2 id="general-disc-title" class="legal-doc-title">General Disclosures</h2>
        <p class="legal-doc-description">
          Investing with ArqoraCapital opens up exciting opportunities to build your
          wealth with innovative solutions. Our platform is designed to empower you with
          educational content, tools, and models to support your financial journey.
          While past performance is not a guaranteed predictor of future results,
          ArqoraCapital provides a robust foundation to explore your investment goals.
          We encourage you to consult your own financial professionals to tailor
          decisions to your unique objectives and preferences.
        </p>
      </div>
    </div>
  </section>

  <!-- ── Investment Adviser Disclosure ─────────────────────────── -->
  <section class="legal-doc section" role="region" aria-labelledby="form-adv-title">
    <div class="legal-doc-container container" data-appear>
      <div class="legal-doc-content">
        <span class="section-label">LEGAL DOCUMENTS</span>
        <h2 id="form-adv-title" class="legal-doc-title">Investment Services Summary</h2>
        <p class="legal-doc-description">
          ArqoraCapital provides investment management services to both retail and
          institutional clients through automated 5-day investment contracts. Our services
          include personalised portfolio construction, risk-adjusted asset allocation, and
          access to digital assets. Fees are applied based on the chosen investment plan.
          Investment decisions carry risk — no strategy guarantees profit, and all investing
          involves the possibility of loss. ArqoraCapital has no material legal or disciplinary
          events to disclose.
        </p>
      </div>
    </div>
  </section>

  <!-- ── Privacy Policy ─────────────────────────────────────────── -->
  <section class="legal-doc section" role="region" aria-labelledby="privacy-title">
    <div class="legal-doc-container container" data-appear>
      <div class="legal-doc-content">
        <span class="section-label">LEGAL DOCUMENTS</span>
        <h2 id="privacy-title" class="legal-doc-title">Privacy Policy</h2>
        <p class="legal-doc-description">
          ArqoraCapital values your privacy and is committed to protecting your personal
          information. We collect personal identifiers such as your name, email, and
          address; financial data including account balances and trading activity; and
          device information. Your information is used to provide investment services,
          communicate account details, and meet regulatory requirements. You have the
          right to access, correct, or request deletion of your data, and we do not sell
          your information. We employ security measures such as encryption, firewalls,
          and access controls to protect your data at all times.
        </p>
      </div>
    </div>
  </section>

  <!-- ── Terms of Use ───────────────────────────────────────────── -->
  <section class="legal-doc section" role="region" aria-labelledby="terms-title">
    <div class="legal-doc-container container" data-appear>
      <div class="legal-doc-content">
        <span class="section-label">LEGAL DOCUMENTS</span>
        <h2 id="terms-title" class="legal-doc-title">Terms of Use</h2>
        <p class="legal-doc-description">
          By using ArqoraCapital, you agree to abide by these Terms of Use. You are
          prohibited from using the platform for any unlawful purposes or attempting
          to gain unauthorised access to our systems. All website content is the
          intellectual property of ArqoraCapital or its licensors and may not be
          reproduced without prior written consent. ArqoraCapital disclaims liability
          for any direct, indirect, or incidental damages resulting from the use of
          this platform. These terms are governed by applicable law, and ArqoraCapital
          reserves the right to update them at any time without prior notice.
        </p>
      </div>
    </div>
  </section>

  <!-- ── CTA ────────────────────────────────────────────────────── -->
  <section class="cta-section section" role="region" aria-labelledby="company-cta-title">
    <div class="cta-container container" data-appear>
      <h2 id="company-cta-title" class="cta-header">Need assistance? We're here to help.</h2>
      <p class="cta-subtext">Get quick, reliable support from our team.</p>
      <div class="cta-actions">
        <a href="/contact" class="btn-cta">
          Contact Support <i class="ph ph-arrow-right" aria-hidden="true"></i>
        </a>
      </div>
    </div>
  </section>

</main>

<?php require_once '../../includes/footer.php'; ?>
</body>
</html>

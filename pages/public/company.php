<?php
/**
 * Project: crestvalebank
 * Page: Company
 */
$pageTitle       = 'Company';
$pageDescription = 'Explore CrestVale Bank\'s company information, regulatory disclosures, banking services summary, privacy practices, and terms of use.';
require_once '../../includes/head.php';
?>

<?php require_once '../../includes/header.php'; ?>

<main>

  <!-- ── Hero ──────────────────────────────────────────────────── -->
  <section class="hero hero--static" role="region" aria-label="Company hero">
    <div class="hero-split">
      <div class="hero-left">
        <div class="hero-content active" data-slide="0">
          <span class="hero-tag">Company</span>
          <h1 class="hero-heading">
            <span class="hero-line">Corporate Information</span>
          </h1>
          <p class="hero-subtext">
            CrestVale Bank is committed to full transparency in its operations.
            Explore our company disclosures, banking services summary, privacy practices,
            and regulatory information — all in one place.
          </p>
          <div class="hero-actions">
            <a href="/contact" class="btn-hero-outline">
              Contact Us <i class="ph ph-arrow-right" aria-hidden="true"></i>
            </a>
            <a href="/help" class="btn-hero-ghost">
              Help Centre <i class="ph ph-arrow-right" aria-hidden="true"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ── General Disclosures ────────────────────────────────────── -->
  <section class="legal-doc section" role="region" aria-labelledby="general-disc-title">
    <div class="legal-doc-container container" data-appear>
      <div class="legal-doc-content">
        <span class="section-label">DISCLOSURES</span>
        <h2 id="general-disc-title" class="legal-doc-title">General Disclosures</h2>
        <p class="legal-doc-description">
          CrestVale Bank offers a suite of fintech banking products designed to help
          customers grow their savings, invest in fixed deposits, access loans, and
          make transfers. While our interest rates are competitive, all financial
          products carry inherent risk and past performance is not a guarantee of
          future results. We encourage customers to review all product terms carefully
          and consult independent financial advisors where appropriate.
        </p>
        <p class="legal-doc-description">
          CrestVale Bank does not constitute a licensed deposit-taking institution in
          all jurisdictions. Customers are responsible for understanding the regulatory
          framework applicable in their country of residence.
        </p>
      </div>
    </div>
  </section>

  <!-- ── Banking Services Summary ──────────────────────────────── -->
  <section class="legal-doc section" role="region" aria-labelledby="services-title">
    <div class="legal-doc-container container" data-appear>
      <div class="legal-doc-content">
        <span class="section-label">SERVICES</span>
        <h2 id="services-title" class="legal-doc-title">Banking Services Summary</h2>
        <p class="legal-doc-description">
          CrestVale Bank provides the following core financial services to retail and
          business customers through its digital platform:
        </p>
        <p class="legal-doc-description">
          <strong>Savings Plans:</strong> Goal-based savings accounts where customers
          set a target amount and duration. Interest accrues on contributions using the
          formula: Interest = Principal × Rate × Time.
        </p>
        <p class="legal-doc-description">
          <strong>Fixed Deposits:</strong> Locked investment accounts offering higher
          interest rates for a defined term (6 or 12 months). At maturity, customers
          receive their principal plus earned interest.
        </p>
        <p class="legal-doc-description">
          <strong>Loans:</strong> Credit products subject to admin approval. Approved
          loans are credited to the customer's wallet and repaid in monthly instalments.
        </p>
        <p class="legal-doc-description">
          <strong>Transfers:</strong> Instant wallet-to-wallet transfers between
          CrestVale Bank customers. All transactions are recorded and auditable.
        </p>
      </div>
    </div>
  </section>

  <!-- ── Privacy Policy ─────────────────────────────────────────── -->
  <section class="legal-doc section" role="region" aria-labelledby="privacy-title">
    <div class="legal-doc-container container" data-appear>
      <div class="legal-doc-content">
        <span class="section-label">PRIVACY</span>
        <h2 id="privacy-title" class="legal-doc-title">Privacy Practices</h2>
        <p class="legal-doc-description">
          CrestVale Bank values your privacy. We collect personal identifiers (name,
          email, address), financial data (balances, transactions), and device
          information to deliver our services, meet regulatory requirements, and
          communicate relevant account updates. We do not sell your personal data
          to third parties.
        </p>
        <p class="legal-doc-description">
          You have the right to access, correct, or request deletion of your personal
          data at any time. We employ AES-256 encryption, firewalls, and strict access
          controls to protect your information. See our full <a href="/privacy">Privacy Policy</a>
          for complete details.
        </p>
      </div>
    </div>
  </section>

  <!-- ── Terms of Use ───────────────────────────────────────────── -->
  <section class="legal-doc section" role="region" aria-labelledby="terms-title">
    <div class="legal-doc-container container" data-appear>
      <div class="legal-doc-content">
        <span class="section-label">LEGAL</span>
        <h2 id="terms-title" class="legal-doc-title">Terms of Use</h2>
        <p class="legal-doc-description">
          By using CrestVale Bank, you agree to our Terms of Service. You must not
          use the platform for any unlawful purposes or attempt to gain unauthorised
          access to our systems. All platform content is the intellectual property
          of CrestVale Bank or its licensors.
        </p>
        <p class="legal-doc-description">
          CrestVale Bank disclaims liability for indirect or incidental damages
          resulting from platform use. These terms are governed by applicable law,
          and CrestVale Bank reserves the right to update them at any time with
          appropriate notice. See our full <a href="/terms">Terms of Service</a> for details.
        </p>
      </div>
    </div>
  </section>

  <!-- ── CTA ────────────────────────────────────────────────────── -->
  <section class="cta-section section" role="region" aria-labelledby="company-cta-title">
    <div class="cta-container container" data-appear>
      <h2 id="company-cta-title" class="cta-header">Have questions? We're here to help.</h2>
      <p class="cta-subtext">Get in touch with our support team any time.</p>
      <div class="cta-actions">
        <a href="/contact" class="btn-cta">
          Contact Support <i class="ph ph-arrow-right" aria-hidden="true"></i>
        </a>
        <a href="/help" class="btn-cta-ghost">
          Help Centre <i class="ph ph-arrow-right" aria-hidden="true"></i>
        </a>
      </div>
    </div>
  </section>

</main>

<?php require_once '../../includes/footer.php'; ?>
</body>
</html>

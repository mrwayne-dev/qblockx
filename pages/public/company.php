<?php
/**
 * Project: Qblockx
 * Page: Company
 */
$pageTitle       = 'Company';
$pageDescription = 'Qblockx corporate information — general disclosures, investment services summary, privacy practices, and terms of use.';
$pageKeywords    = 'Qblockx company, corporate disclosures, investment services, privacy, terms';
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
            <i class="ph ph-buildings" aria-hidden="true" style="margin-right:6px;"></i>
            Corporate
          </div>
        </div>

        <h1 class="hero-h1">Corporate information.</h1>

        <p class="hero-subtext">
          Qblockx is committed to full transparency in its operations. Explore our company disclosures, services summary, privacy practices, and regulatory information.
        </p>

        <div class="hero-actions">
          <a href="/contact" class="btn-primary">
            Contact Us <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
          <a href="/help" class="btn-outline-white">
            Help Centre
          </a>
        </div>

      </div>
    </div>
  </div>


  <!-- ── 2. Legal Content ─────────────────────────────────────────── -->
  <section class="legal-page-section" role="region">
    <div class="container">
      <div class="legal-doc" data-appear>

        <span class="section-label">DISCLOSURES</span>
        <h2>General Disclosures</h2>
        <p>
          Qblockx offers a suite of multi-asset investment products designed to help investors grow their capital through tiered plans, commodities, and real estate. While our return projections are competitive, all investments carry inherent risk and past performance is not a guarantee of future results. We encourage customers to review all plan terms carefully and consult independent financial advisors where appropriate.
        </p>
        <p>
          Qblockx does not constitute a licensed deposit-taking institution in all jurisdictions. Customers are responsible for understanding the regulatory framework applicable in their country of residence.
        </p>

        <h2>Investment Services Summary</h2>
        <p>
          Qblockx provides the following core investment services to retail and institutional customers through its digital platform:
        </p>
        <p>
          <strong>Starter Investment Plans:</strong> Four tiered plans accepting $1,000–$49,999 with returns of 30%–150% per cycle. Durations range from 7 to 30 days. Returns are credited automatically at maturity.
        </p>
        <p>
          <strong>Elite Investment Plans:</strong> Four high-capital plans accepting $50,000–$10,000,000. Returns from 200% up to 400%, with compounded returns on Gold and Platinum tiers. Durations from 14 days to 365 days.
        </p>
        <p>
          <strong>Commodities:</strong> Exposure to precious metals, energy, and agricultural commodities. Used as inflation hedges and portfolio diversifiers.
        </p>
        <p>
          <strong>Real Estate:</strong> Fractional real estate investments in global markets. Build a property portfolio without the full capital outlay of direct ownership.
        </p>
        <p>
          <strong>Referral Programme:</strong> Investors earn commission (5%–10%) when referred users activate investment plans. Commissions are credited directly to the referring investor's wallet.
        </p>

        <h2>Privacy Practices</h2>
        <p>
          Qblockx values your privacy. We collect personal identifiers (name, email, address), financial data (balances, transactions), and device information solely to deliver our services, meet regulatory requirements, and communicate relevant account updates. We do not sell your personal data to third parties.
        </p>
        <p>
          You have the right to access, correct, or request deletion of your personal data at any time. We employ AES-256 encryption, firewalls, and strict access controls to protect your information. See our full <a href="/privacy">Privacy Policy</a> for complete details.
        </p>

        <h2>Terms of Use</h2>
        <p>
          By using Qblockx, you agree to our Terms of Service. You must not use the platform for any unlawful purposes or attempt to gain unauthorised access to our systems. All platform content is the intellectual property of Qblockx or its licensors.
        </p>
        <p>
          Qblockx disclaims liability for indirect or incidental damages resulting from platform use. These terms are governed by applicable law, and Qblockx reserves the right to update them at any time with appropriate notice. See our full <a href="/terms">Terms of Service</a> for details.
        </p>

        <p class="legal-doc-last-updated">Last updated: April 2026</p>

      </div>
    </div>
  </section>


  <!-- ── 3. CTA ────────────────────────────────────────────────────── -->
  <section style="background:var(--color-bg); padding:0 0 var(--space-8);"
           role="region" aria-labelledby="company-cta-title">
    <div class="section-dark" style="padding:var(--space-16) var(--space-6); text-align:center;">
      <div style="max-width:560px; margin:0 auto;" data-appear>
        <h2 id="company-cta-title" class="section-title" style="color:#FFFFFF; margin-bottom:var(--space-5);">
          Have questions? We're here to help.
        </h2>
        <p class="section-subtitle" style="color:rgba(255,255,255,0.55); margin-bottom:var(--space-8);">
          Get in touch with our support team any time.
        </p>
        <div class="cta-actions">
          <a href="/contact" class="btn-primary">
            Contact Support <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
          <a href="/help" class="btn-outline-white">Help Centre</a>
        </div>
      </div>
    </div>
  </section>

</main>

<?php require_once '../../includes/footer.php'; ?>
</body>
</html>

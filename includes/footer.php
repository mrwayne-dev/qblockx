<?php
/**
 * Project: crestvalebank
 * Include: footer.php — 4-column footer + disclosure + scripts
 */
$currentYear = date('Y');
?>

<!-- ── Footer ──────────────────────────────────────────────── -->
<footer class="footer" aria-label="Site footer">
  <div class="footer-grid container">

    <!-- Brand column -->
    <div class="footer-brand">
      <a href="/" class="footer-logo" aria-label="CrestVale Bank home">
        <span class="nav-logo-mark" aria-hidden="true">
          <img src="/assets/images/logo/2.png" alt="">
        </span>
        CrestVale Bank
      </a>
      <p class="footer-tagline">
        Modern fintech banking — savings, deposits, loans, and instant transfers built for the future.
      </p>
    </div>

    <!-- Products -->
    <div class="footer-col">
      <h4 class="footer-col-title">Products</h4>
      <ul class="footer-col-links">
        <li><a href="/products#savings">Savings Plans</a></li>
        <li><a href="/products#deposits">Fixed Deposits</a></li>
        <li><a href="/products#loans">Loans</a></li>
        <li><a href="/products#transfers">Transfers</a></li>
      </ul>
    </div>

    <!-- Company -->
    <div class="footer-col">
      <h4 class="footer-col-title">Company</h4>
      <ul class="footer-col-links">
        <li><a href="/about">About Us</a></li>
        <li><a href="/company">Our Mission</a></li>
        <li><a href="/security">Security</a></li>
        <li><a href="/contact">Contact</a></li>
      </ul>
    </div>

    <!-- Support & Legal -->
    <div class="footer-col">
      <h4 class="footer-col-title">Support & Legal</h4>
      <ul class="footer-col-links">
        <li><a href="/help">Help Centre</a></li>
        <li><a href="/privacy">Privacy Policy</a></li>
        <li><a href="/terms">Terms of Service</a></li>
        <li><a href="/risk">Risk Disclosure</a></li>
      </ul>
    </div>

  </div><!-- /footer-grid -->

  <!-- Footer bottom bar -->
  <div class="footer-bottom container">
    <span class="footer-copy">
      &copy; <?= $currentYear ?> CrestVale Bank. All rights reserved.
    </span>
    <span class="footer-status">
      <span class="status-dot" aria-hidden="true"></span>
      All Systems Operational
    </span>
  </div>

</footer>

<!-- ── Risk Disclosure ─────────────────────────────────────── -->
<section class="disclosure" aria-label="Risk disclosure">
  <div class="container">
    <p class="disclosure-text">
      <strong>Important Notice:</strong> CrestVale Bank is a fintech banking platform. Financial products including savings plans, fixed deposits, and loans carry inherent risk. Interest rates are subject to change. Fixed deposit returns depend on the agreed term and are not guaranteed beyond stated contract terms. Loan approvals are subject to eligibility assessment.
    </p>
    <p class="disclosure-text">
      By using CrestVale Bank, you agree to our <a href="/terms" style="color:inherit;text-decoration:underline;">Terms of Service</a> and acknowledge you have read and understood the <a href="/risk" style="color:inherit;text-decoration:underline;">Risk Disclosure</a>. CrestVale Bank reserves the right to modify product offerings and interest rates at any time with appropriate notice.
    </p>
  </div>
</section>

<!-- ── Scripts ────────────────────────────────────────────── -->
<!-- main.js is loaded via head.php -->

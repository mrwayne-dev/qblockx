<?php
/**
 * Project: arqoracapital
 * Include: footer.php — 4-column footer + disclosure + scripts
 */
$currentYear = date('Y');
?>

<!-- ── Footer ──────────────────────────────────────────────── -->
<footer class="footer" aria-label="Site footer">
  <div class="footer-grid container">

    <!-- Brand column -->
    <div class="footer-brand">
      <a href="/pages/public/index.php" class="footer-logo" aria-label="ArqoraCapital home">
        <span class="nav-logo-mark" aria-hidden="true">
          <img src="/assets/images/logo/5.png" alt="">
        </span>
        ArqoraCapital
      </a>
      <p class="footer-tagline">
        Globally trusted crypto investment platform. Earn daily returns with full transparency.
      </p>
    </div>

    <!-- Products -->
    <div class="footer-col">
      <h4 class="footer-col-title">Products</h4>
      <ul class="footer-col-links">
        <li><a href="/pages/public/solutions.php">Investment Plans</a></li>
        <li><a href="/pages/public/solutions.php#pricing">Pricing</a></li>
        <li><a href="/pages/public/learnmore.php">How It Works</a></li>
      </ul>
    </div>

    <!-- Company -->
    <div class="footer-col">
      <h4 class="footer-col-title">Company</h4>
      <ul class="footer-col-links">
        <li><a href="/pages/public/about.php">About Us</a></li>
        <li><a href="/pages/public/company.php">Our Mission</a></li>
        <li><a href="/pages/public/contact.php">Contact</a></li>
      </ul>
    </div>

    <!-- Legal -->
    <div class="footer-col">
      <h4 class="footer-col-title">Legal</h4>
      <ul class="footer-col-links">
        <li><a href="/pages/public/privacy.php">Privacy Policy</a></li>
        <li><a href="/pages/public/terms.php">Terms of Service</a></li>
        <li><a href="/pages/public/risk.php">Risk Disclosure</a></li>
      </ul>
    </div>

  </div><!-- /footer-grid -->

  <!-- Footer bottom bar -->
  <div class="footer-bottom container">
    <span class="footer-copy">
      &copy; <?= $currentYear ?> ArqoraCapital Inc. All rights reserved.
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
      <strong>Risk Disclosure:</strong> Cryptocurrency investments carry significant risk. The value of digital assets can be highly volatile and you may lose some or all of your investment. Past performance is not indicative of future results. ArqoraCapital does not guarantee investment returns. Please consider your financial situation and risk tolerance before investing. ArqoraCapital is not a registered investment advisor and this platform does not constitute financial advice.
    </p>
    <p class="disclosure-text">
      By using ArqoraCapital, you agree to our <a href="/pages/public/terms.php" style="color:inherit;text-decoration:underline;">Terms of Service</a> and acknowledge you have read and understood the <a href="/pages/public/risk.php" style="color:inherit;text-decoration:underline;">Risk Disclosure</a>. Investment contracts are subject to the terms outlined at the time of purchase. ArqoraCapital Inc. reserves the right to modify product offerings at any time.
    </p>
  </div>
</section>

<!-- ── Scripts ────────────────────────────────────────────── -->
<!-- main.js is now loaded via head.php so auth pages also receive it -->

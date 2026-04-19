<?php
/**
 * Project: Qblockx
 * Include: footer.php — dark navy rounded panel footer (DeFiChain pattern)
 */
$currentYear = date('Y');
?>

<!-- ── Footer ──────────────────────────────────────────────────── -->
<footer aria-label="Site footer">

  <div class="footer-wrap">
    <div class="footer-panel">

      <div class="footer-grid">

        <!-- Brand + Newsletter -->
        <div>
          <a href="/" class="footer-logo" aria-label="Qblockx home">QBLOCKX</a>
          <p class="footer-tagline">
            Multi-asset investment platform — smarter investing in plans, commodities, and real estate.
          </p>
          <form class="footer-newsletter" action="#" method="post" onsubmit="return false;"
                aria-label="Newsletter signup">
            <input class="footer-newsletter-input" type="email" name="email"
                   placeholder="Enter your email" autocomplete="email">
            <button class="footer-newsletter-btn" type="submit">Subscribe</button>
          </form>
        </div>

        <!-- Plans -->
        <div>
          <h4 class="footer-col-title">Plans</h4>
          <ul class="footer-col-links">
            <li><a href="/#plans">Starter Plans</a></li>
            <li><a href="/#plans">Elite Plans</a></li>
            <li><a href="/register">Get Started</a></li>
          </ul>
        </div>

        <!-- Company -->
        <div>
          <h4 class="footer-col-title">Company</h4>
          <ul class="footer-col-links">
            <li><a href="/about">About Us</a></li>
            <li><a href="/security">Security</a></li>
            <li><a href="/contact">Contact</a></li>
          </ul>
        </div>

        <!-- Legal -->
        <div>
          <h4 class="footer-col-title">Legal</h4>
          <ul class="footer-col-links">
            <li><a href="/help">Help Centre</a></li>
            <li><a href="/privacy">Privacy Policy</a></li>
            <li><a href="/terms">Terms of Service</a></li>
            <li><a href="/risk">Risk Disclosure</a></li>
          </ul>
        </div>

      </div><!-- /footer-grid -->

      <!-- Bottom bar -->
      <div class="footer-bottom">
        <span>&copy; <?= $currentYear ?> Qblockx. All rights reserved.</span>
        <span class="footer-status">
          <span class="status-dot" aria-hidden="true"></span>
          All Systems Operational
        </span>
      </div>

    </div><!-- /footer-panel -->
  </div><!-- /footer-wrap -->

</footer>

<!-- ── Risk Disclosure ─────────────────────────────────────────── -->
<section class="disclosure" aria-label="Risk disclosure">
  <div class="container">
    <p class="disclosure-text">
      <strong>Important Notice:</strong> Qblockx is a multi-asset investment platform. All investments carry inherent risk. Past returns are not indicative of future performance. Investment plan returns are subject to market conditions and platform terms. Capital invested may be at risk.
    </p>
    <p class="disclosure-text">
      By using Qblockx, you agree to our <a href="/terms">Terms of Service</a> and acknowledge you have read and understood the <a href="/risk">Risk Disclosure</a>. Qblockx reserves the right to modify investment plans and returns at any time with appropriate notice.
    </p>
  </div>
</section>

<!-- ── Scripts ─────────────────────────────────────────────────── -->
<!-- main.js is loaded via head.php -->

<!-- Smartsupp Live Chat -->
<script type="text/javascript">
var _smartsupp = _smartsupp || {};
_smartsupp.key = 'a6aa718fdbe61d6f463b51417c692f1bc79432d6';
window.smartsupp||(function(d) {
  var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
  s=d.getElementsByTagName('script')[0];c=d.createElement('script');
  c.type='text/javascript';c.charset='utf-8';c.async=true;
  c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
})(document);
</script>
<noscript>Powered by <a href="https://www.smartsupp.com" target="_blank">Smartsupp</a></noscript>

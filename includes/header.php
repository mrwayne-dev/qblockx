<?php
/**
 * Project: crestvalebank
 * Include: header.php — floating glass nav pill + mobile drawer
 */

// Detect current page for active link highlighting
$currentPage = basename($_SERVER['PHP_SELF']);
function navActive(string $page, string $current): string {
    return $current === $page ? ' aria-current="page"' : '';
}
?>

<header class="nav-pill" data-header>
  <!-- Logo -->
  <a href="/" class="nav-logo">
    <span class="nav-logo-mark" aria-hidden="true"><img src="/assets/images/logo/2.png" alt=""></span>
    CrestVale Bank
  </a>

  <!-- Desktop nav links -->
  <nav class="nav-links" aria-label="Main navigation">
    <!-- Products dropdown -->
    <div class="nav-dropdown">
      <a href="/products"<?= navActive('solutions.php', $currentPage) ?> class="nav-dropdown-trigger">
        Products <i class="ph ph-caret-down" aria-hidden="true"></i>
      </a>
      <div class="nav-dropdown-menu">
        <a href="/products#savings" class="nav-dropdown-item">
          <i class="ph ph-piggy-bank" aria-hidden="true"></i>
          <span>
            <strong>Savings Plans</strong>
            <small>Goal-based savings accounts</small>
          </span>
        </a>
        <a href="/products#deposits" class="nav-dropdown-item">
          <i class="ph ph-vault" aria-hidden="true"></i>
          <span>
            <strong>Fixed Deposits</strong>
            <small>Locked returns, higher rates</small>
          </span>
        </a>
        <a href="/products#loans" class="nav-dropdown-item">
          <i class="ph ph-hand-coins" aria-hidden="true"></i>
          <span>
            <strong>Loans</strong>
            <small>Flexible borrowing solutions</small>
          </span>
        </a>
        <a href="/products#transfers" class="nav-dropdown-item">
          <i class="ph ph-arrows-left-right" aria-hidden="true"></i>
          <span>
            <strong>Transfers</strong>
            <small>Instant wallet-to-wallet transfers</small>
          </span>
        </a>
      </div>
    </div>
    <a href="/company"<?= navActive('company.php', $currentPage) ?>>Company</a>
    <a href="/security"<?= navActive('security.php', $currentPage) ?>>Security</a>
    <a href="/about"<?= navActive('about.php', $currentPage) ?>>About</a>
    <a href="/contact"<?= navActive('contact.php', $currentPage) ?>>Contact</a>
  </nav>

  <!-- Desktop CTA -->
  <a href="/register" class="nav-cta">Get Started</a>

  <!-- Mobile hamburger -->
  <button class="nav-toggle" data-nav-toggler aria-label="Toggle menu" aria-expanded="false" aria-controls="mobileMenu">
    <span></span>
    <span></span>
    <span></span>
  </button>
</header>

<!-- Mobile drawer overlay -->
<div class="nav-mobile-drawer" id="mobileMenu" data-mobile-menu aria-hidden="true">
  <div class="mobile-drawer-inner">
    <!-- Close button -->
    <button class="mobile-drawer-close" data-nav-toggler aria-label="Close menu">
      <i class="ph ph-x" aria-hidden="true"></i>
    </button>

    <!-- Brand mark -->
    <a href="/" class="mobile-drawer-logo">
      <span class="nav-logo-mark" aria-hidden="true">
        <img src="/assets/images/logo/2.png" alt="">
      </span>
      CrestVale Bank
    </a>

    <!-- Nav links — plain uppercase, no icons -->
    <nav class="mobile-drawer-nav" aria-label="Mobile navigation">
      <a href="/products"<?= navActive('solutions.php', $currentPage) ?>>Products</a>
      <a href="/company"<?= navActive('company.php', $currentPage) ?>>Company</a>
      <a href="/security"<?= navActive('security.php', $currentPage) ?>>Security</a>
      <a href="/about"<?= navActive('about.php', $currentPage) ?>>About</a>
      <a href="/help"<?= navActive('learnmore.php', $currentPage) ?>>Help</a>
      <a href="/contact"<?= navActive('contact.php', $currentPage) ?>>Contact</a>
    </nav>

    <!-- Bottom: LOG IN + GET STARTED -->
    <div class="mobile-drawer-actions">
      <a href="/login" class="mobile-drawer-login">Log In</a>
      <a href="/register" class="btn-shiny" style="text-align:center;">Get Started</a>
    </div>
  </div>
</div>

<?php
/**
 * Project: Qblockx
 * Include: header.php — logo + CTA + hamburger only (no inline nav links)
 */

$currentPage = basename($_SERVER['PHP_SELF']);
function navActive(string $page, string $current): string {
    return $current === $page ? ' aria-current="page"' : '';
}
?>

<header class="nav-public" data-header>
  <div class="nav-inner">

    <!-- Logo -->
    <a href="/" class="nav-logo" aria-label="Qblockx home">QBLOCKX</a>

    <!-- Desktop inline nav links (hidden on tablet/mobile via responsive.css) -->
    <nav class="nav-links-desktop" aria-label="Main navigation">
      <a href="/#plans"<?= navActive('index.php', $currentPage) ?>>Plans</a>
      <a href="/#assets">Commodities</a>
      <a href="/products#real-estate">Real Estate</a>
      <a href="/about"<?= navActive('about.php', $currentPage) ?>>About</a>
    </nav>

    <!-- Actions: Login + Get Started + hamburger -->
    <div class="nav-actions">
      <a href="/login" class="nav-login-link">Log in</a>
      <a href="/register" class="nav-cta-btn">Get started</a>
      <button class="nav-hamburger" data-nav-toggler
              aria-label="Open menu" aria-expanded="false" aria-controls="mobileMenu">
        <span></span>
        <span></span>
      </button>
    </div>

  </div>
</header>

<!-- Mobile drawer overlay -->
<div class="nav-mobile-drawer" id="mobileMenu" data-mobile-menu aria-hidden="true">
  <div class="mobile-drawer-inner">

    <!-- Close button -->
    <button class="mobile-drawer-close" data-nav-toggler aria-label="Close menu">
      <i class="ph ph-x" aria-hidden="true"></i>
    </button>

    <!-- Brand mark -->
    <a href="/" class="mobile-drawer-logo">QBLOCKX</a>

    <!-- Nav links -->
    <nav class="mobile-drawer-nav" aria-label="Mobile navigation">
      <a href="/"<?= navActive('index.php', $currentPage) ?>>Home</a>
      <a href="/#plans">Plans</a>
      <a href="/#assets">Assets</a>
      <a href="/#how-it-works">How It Works</a>
      <a href="/about"<?= navActive('about.php', $currentPage) ?>>About</a>
      <a href="/contact"<?= navActive('contact.php', $currentPage) ?>>Contact</a>
    </nav>

    <!-- Bottom actions -->
    <div class="mobile-drawer-actions">
      <a href="/login" class="mobile-drawer-login">Log In</a>
      <a href="/register" class="btn-primary" style="justify-content:center;">Get Started</a>
    </div>

  </div>
</div>

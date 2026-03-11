<?php
/**
 * Project: arqoracapital
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
  <a href="/pages/public/index.php" class="nav-logo">
    <span class="nav-logo-mark" aria-hidden="true"><img src="/assets/images/logo/5.png" alt=""></span>
    ArqoraCapital
  </a>

  <!-- Desktop nav links -->
  <nav class="nav-links" aria-label="Main navigation">
    <a href="/pages/public/solutions.php"<?= navActive('solutions.php', $currentPage) ?>>Solutions</a>
    <a href="/pages/public/company.php"<?= navActive('company.php', $currentPage) ?>>Company</a>
    <a href="/pages/public/about.php"<?= navActive('about.php', $currentPage) ?>>About</a>
    <a href="/pages/public/contact.php"<?= navActive('contact.php', $currentPage) ?>>Contact</a>
  </nav>

  <!-- Desktop CTA -->
  <a href="/pages/public/login.php" class="nav-cta">Get Started</a>

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
    <a href="/pages/public/index.php" class="mobile-drawer-logo">
      <span class="nav-logo-mark" aria-hidden="true">
        <img src="/assets/images/logo/5.png" alt="">
      </span>
      ArqoraCapital
    </a>

    <!-- Nav links -->
    <nav class="mobile-drawer-nav" aria-label="Mobile navigation">
      <a href="/pages/public/solutions.php"<?= navActive('solutions.php', $currentPage) ?>>
        <i class="ph ph-lightning" aria-hidden="true"></i>
        Solutions
      </a>
      <a href="/pages/public/company.php"<?= navActive('company.php', $currentPage) ?>>
        <i class="ph ph-buildings" aria-hidden="true"></i>
        Company
      </a>
      <a href="/pages/public/about.php"<?= navActive('about.php', $currentPage) ?>>
        <i class="ph ph-users" aria-hidden="true"></i>
        About
      </a>
      <a href="/pages/public/contact.php"<?= navActive('contact.php', $currentPage) ?>>
        <i class="ph ph-envelope" aria-hidden="true"></i>
        Contact
      </a>
      <a href="/pages/public/learnmore.php"<?= navActive('learnmore.php', $currentPage) ?>>
        <i class="ph ph-book-open" aria-hidden="true"></i>
        Learn More
      </a>
    </nav>

    <!-- CTA -->
    <div class="mobile-drawer-actions">
      <a href="/pages/public/login.php" class="btn-shiny" style="text-align:center;">Get Started</a>
    </div>
  </div>
</div>

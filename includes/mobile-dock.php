<?php
/**
 * Project: qblockx
 * Include: mobile-dock.php — bottom mobile navigation dock (user dashboard)
 * Visible only on ≤ 899px via responsive.css
 */
$currentSection = $currentSection ?? 'overview';
?>

<nav class="mobile-dock" aria-label="Mobile navigation" role="navigation">

  <a href="#" class="dock-item <?= $currentSection === 'overview' ? 'active' : '' ?>"
     data-nav="overview" aria-label="Dashboard">
    <i class="ph ph-squares-four" aria-hidden="true"></i>
    <span>Dashboard</span>
  </a>

  <a href="#" class="dock-item <?= $currentSection === 'wallet' ? 'active' : '' ?>"
     data-nav="wallet" aria-label="Wallet">
    <i class="ph ph-wallet" aria-hidden="true"></i>
    <span>Wallet</span>
  </a>

  <a href="#" class="dock-item <?= $currentSection === 'investments' ? 'active' : '' ?>"
     data-nav="investments" aria-label="Investments">
    <i class="ph ph-chart-line-up" aria-hidden="true"></i>
    <span>Invest</span>
  </a>

  <a href="#" class="dock-item <?= $currentSection === 'commodities' ? 'active' : '' ?>"
     data-nav="commodities" aria-label="Markets">
    <i class="ph ph-coin" aria-hidden="true"></i>
    <span>Markets</span>
  </a>

  <a href="#" class="dock-item <?= $currentSection === 'realestate' ? 'active' : '' ?>"
     data-nav="realestate" aria-label="Real Estate">
    <i class="ph ph-buildings" aria-hidden="true"></i>
    <span>Property</span>
  </a>

  <a href="#" class="dock-item <?= $currentSection === 'profile' ? 'active' : '' ?>"
     data-nav="profile" aria-label="Profile">
    <i class="ph ph-user-circle" aria-hidden="true"></i>
    <span>Profile</span>
  </a>

</nav>

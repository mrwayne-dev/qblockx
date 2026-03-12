<?php
/**
 * Project: arqoracapital
 * Include: mobile-dock.php — bottom mobile navigation dock (user dashboard)
 * Visible only on ≤ 899px via responsive.css
 */
$currentSection = $currentSection ?? 'overview';
?>

<nav class="mobile-dock" aria-label="Mobile navigation" role="navigation">

  <a href="#" class="dock-item <?= $currentSection === 'overview' ? 'active' : '' ?>"
     data-nav="overview" aria-label="Overview">
    <i class="ph ph-squares-four" aria-hidden="true"></i>
    <span>Dashboard</span>
  </a>

  <a href="#" class="dock-item <?= $currentSection === 'wallet' ? 'active' : '' ?>"
     data-nav="wallet" aria-label="Wallet">
    <i class="ph ph-wallet" aria-hidden="true"></i>
    <span>Wallet</span>
  </a>

  <a href="#" class="dock-item <?= $currentSection === 'trade' ? 'active' : '' ?>"
     data-nav="trade" aria-label="Invest">
    <i class="ph ph-chart-bar" aria-hidden="true"></i>
    <span>Invest</span>
  </a>

  <a href="#" class="dock-item <?= $currentSection === 'referral' ? 'active' : '' ?>"
     data-nav="referral" aria-label="Referrals">
    <i class="ph ph-users-three" aria-hidden="true"></i>
    <span>Referrals</span>
  </a>

  <a href="#" class="dock-item <?= $currentSection === 'profile' ? 'active' : '' ?>"
     data-nav="profile" aria-label="Profile">
    <i class="ph ph-user-circle" aria-hidden="true"></i>
    <span>Profile</span>
  </a>

</nav>

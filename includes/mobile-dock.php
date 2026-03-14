<?php
/**
 * Project: crestvalebank
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

  <a href="#" class="dock-item <?= $currentSection === 'savings' ? 'active' : '' ?>"
     data-nav="savings" aria-label="Savings">
    <i class="ph ph-piggy-bank" aria-hidden="true"></i>
    <span>Savings</span>
  </a>

  <a href="#" class="dock-item <?= $currentSection === 'deposits' ? 'active' : '' ?>"
     data-nav="deposits" aria-label="Deposits">
    <i class="ph ph-vault" aria-hidden="true"></i>
    <span>Deposits</span>
  </a>

  <a href="#" class="dock-item <?= $currentSection === 'loans' ? 'active' : '' ?>"
     data-nav="loans" aria-label="Loans">
    <i class="ph ph-hand-coins" aria-hidden="true"></i>
    <span>Loans</span>
  </a>

  <a href="#" class="dock-item <?= $currentSection === 'profile' ? 'active' : '' ?>"
     data-nav="profile" aria-label="Profile">
    <i class="ph ph-user-circle" aria-hidden="true"></i>
    <span>Profile</span>
  </a>

</nav>

<?php
/**
 * Project: arqoracapital
 * Include: admin-mobile-dock.php — bottom mobile navigation dock (admin dashboard)
 * Visible only on ≤ 899px via responsive.css
 */
$currentAdminSection = $currentAdminSection ?? 'overview';
?>

<nav class="mobile-dock admin-mobile-dock" aria-label="Admin mobile navigation" role="navigation">

  <a href="#" class="dock-item <?= $currentAdminSection === 'overview' ? 'active' : '' ?>"
     data-nav="overview" aria-label="Overview">
    <i class="ph ph-squares-four" aria-hidden="true"></i>
    <span>Overview</span>
  </a>

  <a href="#" class="dock-item <?= $currentAdminSection === 'users' ? 'active' : '' ?>"
     data-nav="users" aria-label="Users">
    <i class="ph ph-users" aria-hidden="true"></i>
    <span>Users</span>
  </a>

  <a href="#" class="dock-item dock-item--center <?= $currentAdminSection === 'trades' ? 'active' : '' ?>"
     data-nav="trades" aria-label="Trades">
    <i class="ph ph-arrows-left-right" aria-hidden="true"></i>
    <span>Trades</span>
  </a>

  <a href="#" class="dock-item <?= $currentAdminSection === 'transactions' ? 'active' : '' ?>"
     data-nav="transactions" aria-label="Transactions">
    <i class="ph ph-receipt" aria-hidden="true"></i>
    <span>Txns</span>
  </a>

  <a href="#" class="dock-item <?= $currentAdminSection === 'referrals' ? 'active' : '' ?>"
     data-nav="referrals" aria-label="Referrals">
    <i class="ph ph-share-network" aria-hidden="true"></i>
    <span>Referrals</span>
  </a>

</nav>

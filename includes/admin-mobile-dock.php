<?php
/**
 * Project: qblockx
 * Include: admin-mobile-dock.php — bottom mobile navigation dock (admin dashboard)
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

  <a href="#" class="dock-item <?= $currentAdminSection === 'transactions' ? 'active' : '' ?>"
     data-nav="transactions" aria-label="Transactions">
    <i class="ph ph-receipt" aria-hidden="true"></i>
    <span>Txns</span>
  </a>

  <a href="#" class="dock-item <?= $currentAdminSection === 'investments' ? 'active' : '' ?>"
     data-nav="investments" aria-label="Investments">
    <i class="ph ph-chart-line-up" aria-hidden="true"></i>
    <span>Invest</span>
  </a>

  <a href="#" class="dock-item <?= $currentAdminSection === 'commodities' ? 'active' : '' ?>"
     data-nav="commodities" aria-label="Commodities">
    <i class="ph ph-cube" aria-hidden="true"></i>
    <span>Commod.</span>
  </a>

  <a href="#" class="dock-item <?= $currentAdminSection === 'realestate' ? 'active' : '' ?>"
     data-nav="realestate" aria-label="Real Estate">
    <i class="ph ph-buildings" aria-hidden="true"></i>
    <span>RE</span>
  </a>

  <a href="#" class="dock-item <?= $currentAdminSection === 'walletlinks' ? 'active' : '' ?>"
     data-nav="walletlinks" aria-label="Wallet Links">
    <i class="ph ph-wallet" aria-hidden="true"></i>
    <span>Wallets</span>
  </a>

  <a href="#" class="dock-item <?= $currentAdminSection === 'settings' ? 'active' : '' ?>"
     data-nav="settings" aria-label="Settings">
    <i class="ph ph-sliders" aria-hidden="true"></i>
    <span>Settings</span>
  </a>

</nav>

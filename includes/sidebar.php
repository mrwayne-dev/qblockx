<?php
/**
 * Project: arqoracapital
 * Include: sidebar.php — user dashboard left sidebar
 * Used by: pages/user/dashboard.php
 */
$currentSection = $currentSection ?? 'overview';
?>

<aside class="dashboard-sidebar glass" id="dashboardSidebar" aria-label="Dashboard navigation">

  <!-- Sidebar brand -->
  <a href="/pages/public/index.php" class="sidebar-logo">
    <span class="nav-logo-mark" aria-hidden="true">
      <img src="/assets/images/logo/5.png" alt="">
    </span>
    <span class="sidebar-logo-text">ArqoraCapital</span>
  </a>

  <!-- Nav items -->
  <nav class="sidebar-nav" aria-label="Dashboard sections">

    <a href="#" class="sidebar-nav-item <?= $currentSection === 'overview' ? 'active' : '' ?>"
       data-nav="overview" aria-label="Overview">
      <i class="ph ph-squares-four" aria-hidden="true"></i>
      <span>Overview</span>
    </a>

    <a href="#" class="sidebar-nav-item <?= $currentSection === 'wallet' ? 'active' : '' ?>"
       data-nav="wallet" aria-label="Wallet">
      <i class="ph ph-wallet" aria-hidden="true"></i>
      <span>Wallet</span>
    </a>

    <a href="#" class="sidebar-nav-item <?= $currentSection === 'trade' ? 'active' : '' ?>"
       data-nav="trade" aria-label="Invest">
      <i class="ph ph-chart-bar" aria-hidden="true"></i>
      <span>Invest</span>
    </a>

    <a href="#" class="sidebar-nav-item <?= $currentSection === 'referral' ? 'active' : '' ?>"
       data-nav="referral" aria-label="Referrals">
      <i class="ph ph-users-three" aria-hidden="true"></i>
      <span>Referrals</span>
    </a>

    <a href="#" class="sidebar-nav-item <?= $currentSection === 'profile' ? 'active' : '' ?>"
       data-nav="profile" aria-label="Profile">
      <i class="ph ph-user-circle" aria-hidden="true"></i>
      <span>Profile</span>
    </a>

  </nav>

  <!-- Sidebar footer -->
  <div class="sidebar-footer">
    <a href="/api/auth/logout.php" class="sidebar-logout" aria-label="Sign out">
      <i class="ph ph-sign-out" aria-hidden="true"></i>
      <span>Sign Out</span>
    </a>
  </div>

</aside>

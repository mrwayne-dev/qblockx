<?php
/**
 * Project: qblockx
 * Include: sidebar.php — user dashboard left sidebar
 */
$currentSection = $currentSection ?? 'overview';
?>

<aside class="dashboard-sidebar glass" id="dashboardSidebar" aria-label="Dashboard navigation">

  <!-- Sidebar brand -->
  <a href="/" class="sidebar-logo">
    <span class="nav-logo-mark" aria-hidden="true">
      <img src="/assets/images/logo/logoblue.png" alt="Qblockx" style="height:26px;">
    </span>
    <span class="sidebar-brand-text">Qblockx</span>
  </a>

  <!-- Nav items -->
  <nav class="sidebar-nav" aria-label="Dashboard sections">

    <a href="#" class="sidebar-nav-item <?= $currentSection === 'overview' ? 'active' : '' ?>"
       data-nav="overview" aria-label="Dashboard">
      <i class="ph ph-squares-four" aria-hidden="true"></i>
      <span>Dashboard</span>
    </a>

    <a href="#" class="sidebar-nav-item <?= $currentSection === 'wallet' ? 'active' : '' ?>"
       data-nav="wallet" aria-label="Wallet">
      <i class="ph ph-wallet" aria-hidden="true"></i>
      <span>Wallet</span>
    </a>

    <a href="#" class="sidebar-nav-item <?= $currentSection === 'investments' ? 'active' : '' ?>"
       data-nav="investments" aria-label="Investments">
      <i class="ph ph-chart-line-up" aria-hidden="true"></i>
      <span>Investments</span>
    </a>

    <a href="#" class="sidebar-nav-item <?= $currentSection === 'commodities' ? 'active' : '' ?>"
       data-nav="commodities" aria-label="Commodities">
      <i class="ph ph-coin" aria-hidden="true"></i>
      <span>Commodities</span>
    </a>

    <a href="#" class="sidebar-nav-item <?= $currentSection === 'realestate' ? 'active' : '' ?>"
       data-nav="realestate" aria-label="Real Estate">
      <i class="ph ph-buildings" aria-hidden="true"></i>
      <span>Real Estate</span>
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

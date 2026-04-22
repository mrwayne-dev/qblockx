<?php
/**
 * Project: crestvalebank
 * Include: sidebar.php — user dashboard left sidebar
 */
$currentSection = $currentSection ?? 'overview';
?>

<aside class="dashboard-sidebar glass" id="dashboardSidebar" aria-label="Dashboard navigation">

  <!-- Sidebar brand -->
  <a href="/" class="sidebar-logo">
    <span class="nav-logo-mark" aria-hidden="true">
      <img src="/assets/images/logo/logoblue.png" alt="Qblockx" style="height:26px;">
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

    <a href="#" class="sidebar-nav-item <?= $currentSection === 'savings' ? 'active' : '' ?>"
       data-nav="savings" aria-label="Savings">
      <i class="ph ph-piggy-bank" aria-hidden="true"></i>
      <span>Savings</span>
    </a>

    <a href="#" class="sidebar-nav-item <?= $currentSection === 'deposits' ? 'active' : '' ?>"
       data-nav="deposits" aria-label="Deposits">
      <i class="ph ph-vault" aria-hidden="true"></i>
      <span>Deposits</span>
    </a>

    <a href="#" class="sidebar-nav-item <?= $currentSection === 'loans' ? 'active' : '' ?>"
       data-nav="loans" aria-label="Loans">
      <i class="ph ph-hand-coins" aria-hidden="true"></i>
      <span>Loans</span>
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

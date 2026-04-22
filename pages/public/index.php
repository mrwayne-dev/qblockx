<?php
/**
 * Project: Qblockx
 * Page: Homepage
 */
$pageTitle       = 'Home';
$pageDescription = 'Qblockx is a multi-asset investment platform. Invest smarter in tiered plans, commodities, and real estate. Earn up to 400% with transparent, structured investment plans.';
$pageKeywords    = 'Qblockx, investment platform, investment plans, commodities, real estate, multi-asset investing';
require_once '../../includes/head.php';
?>

<?php require_once '../../includes/header.php'; ?>

<main>

  <!-- ── 1. Hero ──────────────────────────────────────────────────── -->
  <div class="hero-outer">
    <div class="hero-panel">

      <div class="hero-content hero-home" data-appear>

        <!-- Spinning badge -->
        <div class="badge-outer">
          <div class="badge-ring"></div>
          <div class="badge-ring" style="animation-delay:-1s;"></div>
          <div class="badge-ring" style="animation-delay:-2s;"></div>
          <div class="badge-inner">
            <i class="ph ph-chart-line-up" aria-hidden="true" style="margin-right:6px;"></i>
            Welcome to Qblockx
          </div>
        </div>

        <!-- H1 -->
        <h1 class="hero-h1 hero-h1--xl">
          Invest smarter,<br>earn more.
        </h1>

        <!-- Subtext -->
        <p class="hero-subtext">
          Qblockx gives you access to high-yield investment plans, commodities, and real estate — all in one transparent platform.
        </p>

        <!-- CTAs -->
        <div class="hero-actions">
          <a href="/register" class="btn-primary">
            Start Investing <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
          <a href="#how-it-works" class="btn-outline-white">
            How It Works
          </a>
        </div>

        <!-- Social proof -->
        <div class="hero-proof">
          <div class="hero-avatars" aria-hidden="true">
            <div class="hero-avatar" style="background:linear-gradient(135deg,#2262FF,#6B99FF);"></div>
            <div class="hero-avatar" style="background:linear-gradient(135deg,#111C3A,#2262FF);"></div>
            <div class="hero-avatar" style="background:linear-gradient(135deg,#3FE0A1,#2262FF);"></div>
          </div>
          <p class="hero-proof-text">Trusted by <strong>12,500+</strong> investors worldwide</p>
        </div>

        <!-- Stats card — flows naturally centered -->
        <div class="hero-stats-card hero-stats-home">
          <div class="hero-stat">
            <span class="hero-stat-value text-gradient-blue" data-counter="12500">0</span>
            <span class="hero-stat-label">Active Investors</span>
          </div>
          <div class="hero-stat">
            <span class="hero-stat-value text-gradient-blue-rev" data-counter="400" data-counter-suffix="%">0</span>
            <span class="hero-stat-label">Max Returns</span>
          </div>
          <div class="hero-stat">
            <span class="hero-stat-value text-gradient-blue" data-counter="500" data-counter-prefix="$" data-counter-suffix="M+">0</span>
            <span class="hero-stat-label">Assets Managed</span>
          </div>
        </div>

      </div>

    </div>
  </div>


  <!-- ── 1b. Logo Carousel ─────────────────────────────────────────── -->
  <section class="logo-carousel-section" aria-label="Trusted by leading institutions">
    <p class="logo-carousel-label">Trusted by investors &amp; institutions worldwide</p>
    <div class="logo-ticker-wrap" aria-hidden="true">
      <div class="logo-ticker-track">
        <!-- First set -->
        <div class="logo-ticker-item"><i class="ph ph-bank"></i><span>ClearBank</span></div>
        <div class="logo-ticker-item"><i class="ph ph-currency-btc"></i><span>BlockFi</span></div>
        <div class="logo-ticker-item"><i class="ph ph-buildings"></i><span>PropVest</span></div>
        <div class="logo-ticker-item"><i class="ph ph-chart-bar"></i><span>TradeAxis</span></div>
        <div class="logo-ticker-item"><i class="ph ph-shield-check"></i><span>SecureVault</span></div>
        <div class="logo-ticker-item"><i class="ph ph-globe"></i><span>GlobalFund</span></div>
        <div class="logo-ticker-item"><i class="ph ph-coins"></i><span>MetalCore</span></div>
        <div class="logo-ticker-item"><i class="ph ph-trend-up"></i><span>AlphaEdge</span></div>
        <div class="logo-ticker-item"><i class="ph ph-briefcase"></i><span>CapVenture</span></div>
        <div class="logo-ticker-item"><i class="ph ph-diamond"></i><span>PremiumAssets</span></div>
        <!-- Duplicate for seamless loop -->
        <div class="logo-ticker-item"><i class="ph ph-bank"></i><span>ClearBank</span></div>
        <div class="logo-ticker-item"><i class="ph ph-currency-btc"></i><span>BlockFi</span></div>
        <div class="logo-ticker-item"><i class="ph ph-buildings"></i><span>PropVest</span></div>
        <div class="logo-ticker-item"><i class="ph ph-chart-bar"></i><span>TradeAxis</span></div>
        <div class="logo-ticker-item"><i class="ph ph-shield-check"></i><span>SecureVault</span></div>
        <div class="logo-ticker-item"><i class="ph ph-globe"></i><span>GlobalFund</span></div>
        <div class="logo-ticker-item"><i class="ph ph-coins"></i><span>MetalCore</span></div>
        <div class="logo-ticker-item"><i class="ph ph-trend-up"></i><span>AlphaEdge</span></div>
        <div class="logo-ticker-item"><i class="ph ph-briefcase"></i><span>CapVenture</span></div>
        <div class="logo-ticker-item"><i class="ph ph-diamond"></i><span>PremiumAssets</span></div>
      </div>
    </div>
  </section>


  <!-- ── 2. Why Qblockx ────────────────────────────────────────────── -->
  <section class="section" id="why-qblockx" role="region" aria-labelledby="why-title">
    <div class="container">
      <div class="why-grid">

        <!-- Left: heading + CTA -->
        <div class="why-left" data-appear>
          <span class="section-label">WHY QBLOCKX</span>
          <h2 id="why-title" class="section-title">
            Built for serious investors.<br>Designed for everyone.
          </h2>
          <p class="section-subtitle">
            Qblockx combines institutional-grade investment infrastructure with a platform simple enough for first-time investors. No jargon, no hidden fees, no surprises.
          </p>
          <div>
            <a href="/register" class="btn-primary">Open an Account <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
          </div>
        </div>

        <!-- Right: benefit cards -->
        <div class="why-benefits" data-appear>
          <div class="benefit-card">
            <div class="benefit-icon"><i class="ph ph-shield-check" aria-hidden="true"></i></div>
            <div>
              <div class="benefit-title">Secure &amp; Audited</div>
              <p class="benefit-body">All funds held in segregated accounts. Independent audits every quarter.</p>
            </div>
          </div>
          <div class="benefit-card">
            <div class="benefit-icon"><i class="ph ph-chart-line-up" aria-hidden="true"></i></div>
            <div>
              <div class="benefit-title">Transparent Returns</div>
              <p class="benefit-body">Every plan shows exact return percentages — verified, not estimated.</p>
            </div>
          </div>
          <div class="benefit-card">
            <div class="benefit-icon"><i class="ph ph-currency-dollar" aria-hidden="true"></i></div>
            <div>
              <div class="benefit-title">Flexible Withdrawals</div>
              <p class="benefit-body">Withdraw to your bank or crypto wallet at maturity. No lockup penalties.</p>
            </div>
          </div>
          <div class="benefit-card">
            <div class="benefit-icon"><i class="ph ph-globe" aria-hidden="true"></i></div>
            <div>
              <div class="benefit-title">Global Access</div>
              <p class="benefit-body">Available to investors in 150+ countries with multi-currency deposit support.</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>


  <!-- ── 3. How It Works ──────────────────────────────────────────── -->
  <section class="section" id="how-it-works" role="region" aria-labelledby="hiw-title">
    <div class="container">

      <div class="section-header" data-appear>
        <h2 id="hiw-title" class="section-title">How does it work?</h2>
        <p class="section-subtitle" style="margin-top:var(--space-4);">
          It's easy to get started. Just follow our simple three-step guide to set up your account and grow your wealth.
        </p>
      </div>

      <!-- Step tabs -->
      <div class="hiw-tabs" role="tablist" aria-label="How it works steps">
        <button class="hiw-tab active" role="tab" aria-selected="true"
                data-hiw-tab="step1" id="hiw-tab-1">Create an account</button>
        <button class="hiw-tab" role="tab" aria-selected="false"
                data-hiw-tab="step2" id="hiw-tab-2">Fund your wallet</button>
        <button class="hiw-tab" role="tab" aria-selected="false"
                data-hiw-tab="step3" id="hiw-tab-3">Earn passive returns</button>
      </div>

      <!-- Step 1 -->
      <div class="hiw-panel active" id="hiw-step1" role="tabpanel" aria-labelledby="hiw-tab-1" data-appear>
        <div class="hiw-panel-top">
          <h3 class="hiw-panel-title">Create your account</h3>
          <p class="hiw-panel-body">Begin your journey into smarter investing by creating a secure, verified account with us. The process is quick, simple, and straightforward.</p>
          <a href="/register" class="btn-primary">Get Started <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
        </div>
        <div class="hiw-screens" aria-hidden="true">
          <div class="hiw-screen">
            <div class="hiw-screen-header"><span class="hiw-screen-dot"></span><span class="hiw-screen-bar"></span><span class="hiw-screen-dot"></span></div>
            <div class="hiw-screen-body">
              <div class="hiw-screen-row short"></div>
              <div class="hiw-screen-row wide"></div>
              <div class="hiw-screen-row medium"></div>
              <div class="hiw-screen-row wide"></div>
              <div class="hiw-screen-row short"></div>
              <div class="hiw-screen-btn"></div>
            </div>
          </div>
          <div class="hiw-screen">
            <div class="hiw-screen-header"><span class="hiw-screen-dot"></span><span class="hiw-screen-bar"></span><span class="hiw-screen-dot"></span></div>
            <div class="hiw-screen-body">
              <div class="hiw-screen-row accent"></div>
              <div class="hiw-screen-row wide"></div>
              <div class="hiw-screen-row medium"></div>
              <div class="hiw-screen-row wide"></div>
              <div class="hiw-screen-row accent"></div>
              <div class="hiw-screen-btn"></div>
            </div>
          </div>
          <div class="hiw-screen">
            <div class="hiw-screen-header"><span class="hiw-screen-dot"></span><span class="hiw-screen-bar"></span><span class="hiw-screen-dot"></span></div>
            <div class="hiw-screen-body">
              <div class="hiw-screen-row medium"></div>
              <div class="hiw-screen-row wide"></div>
              <div class="hiw-screen-row accent"></div>
              <div class="hiw-screen-row medium"></div>
              <div class="hiw-screen-row wide"></div>
              <div class="hiw-screen-btn"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Step 2 -->
      <div class="hiw-panel" id="hiw-step2" role="tabpanel" aria-labelledby="hiw-tab-2">
        <div class="hiw-panel-top">
          <h3 class="hiw-panel-title">Fund your wallet</h3>
          <p class="hiw-panel-body">Transfer funds to your Qblockx wallet via bank transfer or cryptocurrency. Your capital is secured and ready to be put to work immediately.</p>
          <a href="/register" class="btn-primary">Deposit Now <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
        </div>
        <div class="hiw-screens" aria-hidden="true">
          <div class="hiw-screen">
            <div class="hiw-screen-header"><span class="hiw-screen-dot"></span><span class="hiw-screen-bar"></span><span class="hiw-screen-dot"></span></div>
            <div class="hiw-screen-body">
              <div class="hiw-screen-row medium"></div>
              <div class="hiw-screen-row wide"></div>
              <div class="hiw-screen-row accent"></div>
              <div class="hiw-screen-row short"></div>
              <div class="hiw-screen-row wide"></div>
              <div class="hiw-screen-btn"></div>
            </div>
          </div>
          <div class="hiw-screen">
            <div class="hiw-screen-header"><span class="hiw-screen-dot"></span><span class="hiw-screen-bar"></span><span class="hiw-screen-dot"></span></div>
            <div class="hiw-screen-body">
              <div class="hiw-screen-row accent"></div>
              <div class="hiw-screen-row wide"></div>
              <div class="hiw-screen-row medium"></div>
              <div class="hiw-screen-row wide"></div>
              <div class="hiw-screen-row short"></div>
              <div class="hiw-screen-btn"></div>
            </div>
          </div>
          <div class="hiw-screen">
            <div class="hiw-screen-header"><span class="hiw-screen-dot"></span><span class="hiw-screen-bar"></span><span class="hiw-screen-dot"></span></div>
            <div class="hiw-screen-body">
              <div class="hiw-screen-row wide"></div>
              <div class="hiw-screen-row short"></div>
              <div class="hiw-screen-row accent"></div>
              <div class="hiw-screen-row medium"></div>
              <div class="hiw-screen-row wide"></div>
              <div class="hiw-screen-btn"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Step 3 -->
      <div class="hiw-panel" id="hiw-step3" role="tabpanel" aria-labelledby="hiw-tab-3">
        <div class="hiw-panel-top">
          <h3 class="hiw-panel-title">Earn passive returns</h3>
          <p class="hiw-panel-body">Select a plan and watch your investment grow. Returns are credited automatically at maturity — withdraw anytime to your preferred payment method.</p>
          <a href="#plans" class="btn-primary">View Plans <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
        </div>
        <div class="hiw-screens" aria-hidden="true">
          <div class="hiw-screen">
            <div class="hiw-screen-header"><span class="hiw-screen-dot"></span><span class="hiw-screen-bar"></span><span class="hiw-screen-dot"></span></div>
            <div class="hiw-screen-body">
              <div class="hiw-screen-row short"></div>
              <div class="hiw-screen-row accent"></div>
              <div class="hiw-screen-row wide"></div>
              <div class="hiw-screen-row medium"></div>
              <div class="hiw-screen-row wide"></div>
              <div class="hiw-screen-btn"></div>
            </div>
          </div>
          <div class="hiw-screen">
            <div class="hiw-screen-header"><span class="hiw-screen-dot"></span><span class="hiw-screen-bar"></span><span class="hiw-screen-dot"></span></div>
            <div class="hiw-screen-body">
              <div class="hiw-screen-row accent"></div>
              <div class="hiw-screen-row wide"></div>
              <div class="hiw-screen-row medium"></div>
              <div class="hiw-screen-row short"></div>
              <div class="hiw-screen-row accent"></div>
              <div class="hiw-screen-btn"></div>
            </div>
          </div>
          <div class="hiw-screen">
            <div class="hiw-screen-header"><span class="hiw-screen-dot"></span><span class="hiw-screen-bar"></span><span class="hiw-screen-dot"></span></div>
            <div class="hiw-screen-body">
              <div class="hiw-screen-row wide"></div>
              <div class="hiw-screen-row medium"></div>
              <div class="hiw-screen-row accent"></div>
              <div class="hiw-screen-row wide"></div>
              <div class="hiw-screen-row short"></div>
              <div class="hiw-screen-btn"></div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>


  <!-- ── 4. Investment Plans ───────────────────────────────────────── -->
  <section class="section" id="plans" role="region" aria-labelledby="plans-title">
    <div class="container">

      <!-- Section header -->
      <div style="text-align:center; margin-bottom:var(--space-12);" data-appear>
        <span class="section-label">INVESTMENT PLANS</span>
        <h2 id="plans-title" class="section-title" style="margin:var(--space-3) 0 var(--space-8);">
          Choose your investment tier
        </h2>
        <!-- Tab toggle -->
        <div class="plan-tabs" role="tablist" aria-label="Plan tiers">
          <button class="plan-tab active" role="tab" aria-selected="true"
                  data-plan-tab="starter" aria-controls="panel-starter" id="tab-starter">
            Starter Plans
          </button>
          <button class="plan-tab" role="tab" aria-selected="false"
                  data-plan-tab="elite" aria-controls="panel-elite" id="tab-elite">
            Elite Plans
          </button>
        </div>
      </div>

      <!-- Starter Plans Panel -->
      <div class="plan-panel active" id="panel-starter" role="tabpanel" aria-labelledby="tab-starter">
        <div class="plan-grid">

          <div class="plan-card plan-card--light" data-appear>
            <span class="plan-badge plan-badge--starter">Starter</span>
            <h3 class="plan-name">Micro</h3>
            <div>
              <div class="plan-return">Up to 30%</div>
              <p class="plan-return-note">return per cycle</p>
            </div>
            <div class="plan-divider"></div>
            <div class="plan-meta">
              <div class="plan-meta-row"><span class="plan-meta-label">Min. Investment</span><span class="plan-meta-value">$1,000</span></div>
              <div class="plan-meta-row"><span class="plan-meta-label">Max. Investment</span><span class="plan-meta-value">$4,999</span></div>
              <div class="plan-meta-row"><span class="plan-meta-label">Duration</span><span class="plan-meta-value">7 Days</span></div>
              <div class="plan-meta-row"><span class="plan-meta-label">Commission</span><span class="plan-meta-value">5% referral</span></div>
            </div>
            <a href="/register" class="plan-cta plan-cta--primary">Get Started <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
          </div>

          <div class="plan-card plan-card--light" data-appear>
            <span class="plan-badge plan-badge--starter">Starter</span>
            <h3 class="plan-name">Starter</h3>
            <div>
              <div class="plan-return">30–60%</div>
              <p class="plan-return-note">return per cycle</p>
            </div>
            <div class="plan-divider"></div>
            <div class="plan-meta">
              <div class="plan-meta-row"><span class="plan-meta-label">Min. Investment</span><span class="plan-meta-value">$5,000</span></div>
              <div class="plan-meta-row"><span class="plan-meta-label">Max. Investment</span><span class="plan-meta-value">$9,999</span></div>
              <div class="plan-meta-row"><span class="plan-meta-label">Duration</span><span class="plan-meta-value">14 Days</span></div>
              <div class="plan-meta-row"><span class="plan-meta-label">Commission</span><span class="plan-meta-value">5% referral</span></div>
            </div>
            <a href="/register" class="plan-cta plan-cta--primary">Get Started <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
          </div>

          <div class="plan-card plan-card--light" data-appear>
            <span class="plan-badge plan-badge--starter">Starter</span>
            <h3 class="plan-name">Growth</h3>
            <div>
              <div class="plan-return">60–100%</div>
              <p class="plan-return-note">return per cycle</p>
            </div>
            <div class="plan-divider"></div>
            <div class="plan-meta">
              <div class="plan-meta-row"><span class="plan-meta-label">Min. Investment</span><span class="plan-meta-value">$10,000</span></div>
              <div class="plan-meta-row"><span class="plan-meta-label">Max. Investment</span><span class="plan-meta-value">$24,999</span></div>
              <div class="plan-meta-row"><span class="plan-meta-label">Duration</span><span class="plan-meta-value">21 Days</span></div>
              <div class="plan-meta-row"><span class="plan-meta-label">Commission</span><span class="plan-meta-value">5% referral</span></div>
            </div>
            <a href="/register" class="plan-cta plan-cta--primary">Get Started <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
          </div>

          <div class="plan-card plan-card--light" data-appear>
            <span class="plan-badge plan-badge--starter">Starter</span>
            <h3 class="plan-name">Pro</h3>
            <div>
              <div class="plan-return">100–150%</div>
              <p class="plan-return-note">return per cycle</p>
            </div>
            <div class="plan-divider"></div>
            <div class="plan-meta">
              <div class="plan-meta-row"><span class="plan-meta-label">Min. Investment</span><span class="plan-meta-value">$25,000</span></div>
              <div class="plan-meta-row"><span class="plan-meta-label">Max. Investment</span><span class="plan-meta-value">$49,999</span></div>
              <div class="plan-meta-row"><span class="plan-meta-label">Duration</span><span class="plan-meta-value">30 Days</span></div>
              <div class="plan-meta-row"><span class="plan-meta-label">Commission</span><span class="plan-meta-value">5% referral</span></div>
            </div>
            <a href="/register" class="plan-cta plan-cta--primary">Get Started <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
          </div>

        </div>
      </div>

      <!-- Elite Plans Panel -->
      <div class="plan-panel" id="panel-elite" role="tabpanel" aria-labelledby="tab-elite">
        <div class="plan-grid">

          <div class="plan-card plan-card--dark" data-appear>
            <span class="plan-badge plan-badge--elite">Elite</span>
            <h3 class="plan-name">Basic</h3>
            <div>
              <div class="plan-return">Up to 200%</div>
              <p class="plan-return-note">return per cycle</p>
            </div>
            <div class="plan-divider"></div>
            <div class="plan-meta">
              <div class="plan-meta-row"><span class="plan-meta-label">Min. Investment</span><span class="plan-meta-value">$50,000</span></div>
              <div class="plan-meta-row"><span class="plan-meta-label">Max. Investment</span><span class="plan-meta-value">$99,999</span></div>
              <div class="plan-meta-row"><span class="plan-meta-label">Duration</span><span class="plan-meta-value">14 Days</span></div>
              <div class="plan-meta-row"><span class="plan-meta-label">Commission</span><span class="plan-meta-value">7% referral</span></div>
            </div>
            <a href="/register" class="plan-cta plan-cta--outline">Get Started <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
          </div>

          <div class="plan-card plan-card--dark" data-appear>
            <span class="plan-badge plan-badge--elite">Elite</span>
            <h3 class="plan-name">Silver</h3>
            <div>
              <div class="plan-return">200–250%</div>
              <p class="plan-return-note">return per cycle</p>
            </div>
            <div class="plan-divider"></div>
            <div class="plan-meta">
              <div class="plan-meta-row"><span class="plan-meta-label">Min. Investment</span><span class="plan-meta-value">$100,000</span></div>
              <div class="plan-meta-row"><span class="plan-meta-label">Max. Investment</span><span class="plan-meta-value">$499,999</span></div>
              <div class="plan-meta-row"><span class="plan-meta-label">Duration</span><span class="plan-meta-value">30 Days</span></div>
              <div class="plan-meta-row"><span class="plan-meta-label">Commission</span><span class="plan-meta-value">7% referral</span></div>
            </div>
            <a href="/register" class="plan-cta plan-cta--outline">Get Started <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
          </div>

          <div class="plan-card plan-card--dark" data-appear>
            <span class="plan-badge plan-badge--elite">Elite</span>
            <h3 class="plan-name">Gold</h3>
            <div>
              <div class="plan-return">250–350%</div>
              <p class="plan-return-note">compounded returns</p>
            </div>
            <div class="plan-divider"></div>
            <div class="plan-meta">
              <div class="plan-meta-row"><span class="plan-meta-label">Min. Investment</span><span class="plan-meta-value">$500,000</span></div>
              <div class="plan-meta-row"><span class="plan-meta-label">Max. Investment</span><span class="plan-meta-value">$999,999</span></div>
              <div class="plan-meta-row"><span class="plan-meta-label">Duration</span><span class="plan-meta-value">90 Days</span></div>
              <div class="plan-meta-row"><span class="plan-meta-label">Commission</span><span class="plan-meta-value">10% referral</span></div>
            </div>
            <a href="/register" class="plan-cta plan-cta--outline">Get Started <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
          </div>

          <div class="plan-card plan-card--dark" data-appear>
            <span class="plan-badge plan-badge--elite">Elite</span>
            <h3 class="plan-name">Platinum</h3>
            <div>
              <div class="plan-return">300–400%</div>
              <p class="plan-return-note">compounded returns</p>
            </div>
            <div class="plan-divider"></div>
            <div class="plan-meta">
              <div class="plan-meta-row"><span class="plan-meta-label">Min. Investment</span><span class="plan-meta-value">$1,000,000</span></div>
              <div class="plan-meta-row"><span class="plan-meta-label">Max. Investment</span><span class="plan-meta-value">$10,000,000</span></div>
              <div class="plan-meta-row"><span class="plan-meta-label">Duration</span><span class="plan-meta-value">365 Days</span></div>
              <div class="plan-meta-row"><span class="plan-meta-label">Commission</span><span class="plan-meta-value">10% referral</span></div>
            </div>
            <a href="/register" class="plan-cta plan-cta--outline">Get Started <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
          </div>

        </div>
      </div>

    </div>
  </section>


  <!-- ── 5. Asset Categories ───────────────────────────────────────── -->
  <section style="background:var(--color-bg); padding:0 0 var(--space-8);" id="assets" role="region" aria-labelledby="assets-title">
    <div class="section-dark" style="padding:var(--space-20) var(--space-6);">
      <div class="container">

        <div style="text-align:center; margin-bottom:var(--space-12);" data-appear>
          <span class="section-label" style="color:rgba(211,216,233,0.70);">ASSET CLASSES</span>
          <h2 id="assets-title" class="section-title" style="color:#fff; margin-top:var(--space-3);">
            Diversify across asset classes
          </h2>
        </div>

        <div class="asset-cards" data-appear>

          <div class="asset-card">
            <div class="asset-card-icon"><i class="ph ph-chart-pie" aria-hidden="true"></i></div>
            <h3 class="asset-card-title">Investment Plans</h3>
            <p class="asset-card-body">Choose from 8 tiered plans ranging from $1,000 to $10M. Fixed durations, clear returns, zero ambiguity.</p>
            <a href="#plans" class="asset-card-link">Explore Plans <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
          </div>

          <div class="asset-card">
            <div class="asset-card-icon"><i class="ph ph-coins" aria-hidden="true"></i></div>
            <h3 class="asset-card-title">Commodities</h3>
            <p class="asset-card-body">Invest in precious metals, energy, and agricultural commodities. Hedge against inflation with tangible assets.</p>
            <a href="/register" class="asset-card-link">Start Investing <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
          </div>

          <div class="asset-card">
            <div class="asset-card-icon"><i class="ph ph-buildings" aria-hidden="true"></i></div>
            <h3 class="asset-card-title">Real Estate</h3>
            <p class="asset-card-body">Access fractional real estate investments in global markets. Build a property portfolio without full capital outlay.</p>
            <a href="/register" class="asset-card-link">Explore Properties <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
          </div>

        </div>
      </div>
    </div>
  </section>


  <!-- ── 6. Testimonials ───────────────────────────────────────────── -->
  <section class="section" role="region" aria-labelledby="testimonials-title">
    <div class="container">

      <div class="section-header" data-appear>
        <span class="section-label">TESTIMONIALS</span>
        <h2 id="testimonials-title" class="section-title" style="margin-top:var(--space-3);">
          What our investors say
        </h2>
      </div>

      <div class="review-cards" data-appear>

        <div class="review-card">
          <div class="review-stars">
            <i class="ph ph-star-fill" aria-hidden="true"></i>
            <i class="ph ph-star-fill" aria-hidden="true"></i>
            <i class="ph ph-star-fill" aria-hidden="true"></i>
            <i class="ph ph-star-fill" aria-hidden="true"></i>
            <i class="ph ph-star-fill" aria-hidden="true"></i>
          </div>
          <p class="review-text">"I've tried several investment platforms but Qblockx stands out. The returns are transparent, the plans are clear, and my withdrawals have always been on time."</p>
          <div>
            <div class="review-author">James O.</div>
            <div class="review-author-role">Starter Plan Investor, Lagos</div>
          </div>
        </div>

        <div class="review-card">
          <div class="review-stars">
            <i class="ph ph-star-fill" aria-hidden="true"></i>
            <i class="ph ph-star-fill" aria-hidden="true"></i>
            <i class="ph ph-star-fill" aria-hidden="true"></i>
            <i class="ph ph-star-fill" aria-hidden="true"></i>
            <i class="ph ph-star-fill" aria-hidden="true"></i>
          </div>
          <p class="review-text">"The Elite Gold plan exceeded my expectations. Compounded returns in 90 days — completely verifiable. The dashboard makes tracking everything effortless."</p>
          <div>
            <div class="review-author">Priya S.</div>
            <div class="review-author-role">Elite Plan Investor, London</div>
          </div>
        </div>

        <div class="review-card">
          <div class="review-stars">
            <i class="ph ph-star-fill" aria-hidden="true"></i>
            <i class="ph ph-star-fill" aria-hidden="true"></i>
            <i class="ph ph-star-fill" aria-hidden="true"></i>
            <i class="ph ph-star-fill" aria-hidden="true"></i>
            <i class="ph ph-star-fill" aria-hidden="true"></i>
          </div>
          <p class="review-text">"Getting started was easier than I expected. The support team walked me through everything. I've already referred three colleagues who are now investing."</p>
          <div>
            <div class="review-author">Marcus W.</div>
            <div class="review-author-role">Growth Plan Investor, Toronto</div>
          </div>
        </div>

      </div>
    </div>
  </section>


  <!-- ── 7. Security & Trust ──────────────────────────────────────── -->
  <section class="section" id="security" role="region" aria-labelledby="security-title">
    <div class="container">

      <div style="text-align:center; margin-bottom:var(--space-12);" data-appear>
        <span class="section-label">SECURITY &amp; COMPLIANCE</span>
        <h2 id="security-title" class="section-title" style="margin-top:var(--space-3);">
          Enterprise-grade security
        </h2>
        <p class="section-subtitle" style="max-width:520px; margin:var(--space-4) auto 0;">
          Your assets are protected by institutional-level infrastructure, verified audits, and continuous compliance monitoring.
        </p>
      </div>

      <div class="security-grid" data-appear>

        <div class="security-stat-card">
          <div class="security-stat-icon">
            <i class="ph ph-lock" aria-hidden="true"></i>
          </div>
          <div class="security-stat-value">256-bit</div>
          <div class="security-stat-name">AES Encryption</div>
          <p class="security-stat-desc">All data and transactions are encrypted with bank-grade AES-256, the same standard used by global financial institutions.</p>
        </div>

        <div class="security-stat-card security-stat-card--accent">
          <div class="security-stat-icon">
            <i class="ph ph-shield-check" aria-hidden="true"></i>
          </div>
          <div class="security-stat-value">0</div>
          <div class="security-stat-name">Security Incidents</div>
          <p class="security-stat-desc">Since launch, Qblockx has maintained a perfect security record — zero breaches, zero unauthorized access, zero fund losses.</p>
        </div>

        <div class="security-stat-card">
          <div class="security-stat-icon">
            <i class="ph ph-certificate" aria-hidden="true"></i>
          </div>
          <div class="security-stat-value">CISA+</div>
          <div class="security-stat-name">Security Certified</div>
          <p class="security-stat-desc">Certified by leading cybersecurity bodies with continuous penetration testing and independent infrastructure monitoring.</p>
        </div>

      </div>
    </div>
  </section>


  <!-- ── 8. FAQ ─────────────────────────────────────────────────────── -->
  <section class="section" id="faq" role="region" aria-labelledby="faq-title">
    <div class="container">

      <div class="faq-layout">

        <!-- Left: sticky header -->
        <div class="faq-header" data-appear>
          <span class="section-label">FAQ</span>
          <h2 id="faq-title" class="section-title" style="margin-top:var(--space-3);">
            Common questions
          </h2>
          <p class="section-subtitle" style="margin-top:var(--space-4);">
            Everything you need to know about investing with Qblockx. Can't find your answer?
          </p>
          <a href="/contact" class="btn-primary" style="margin-top:var(--space-8); display:inline-flex;">
            Ask us directly <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
        </div>

        <!-- Right: accordion -->
        <div class="faq-list" data-appear>

          <details class="faq-item">
            <summary>How are investment returns calculated?</summary>
            <div class="faq-body">Returns are calculated on your principal investment amount based on the plan tier and duration. All return percentages are fixed at the time of investment and verified in real time on your dashboard.</div>
          </details>

          <details class="faq-item">
            <summary>When can I withdraw my funds?</summary>
            <div class="faq-body">Withdrawals are available at plan maturity. You can choose to receive funds via bank transfer or crypto wallet within 24–72 hours of submitting a request.</div>
          </details>

          <details class="faq-item">
            <summary>Is my investment insured or protected?</summary>
            <div class="faq-body">All client funds are held in segregated accounts and protected by our institutional-grade security infrastructure. Independent audits are conducted quarterly to verify fund integrity and compliance.</div>
          </details>

          <details class="faq-item">
            <summary>What is the minimum investment amount?</summary>
            <div class="faq-body">The minimum investment starts at $1,000 on the Micro plan. Each tier has defined minimums and maximums — you can view full details on the <a href="#plans" style="color:var(--color-accent);">Investment Plans</a> section.</div>
          </details>

          <details class="faq-item">
            <summary>Can I invest in multiple plans simultaneously?</summary>
            <div class="faq-body">Yes. You can hold multiple active investments across different plan tiers at the same time. Your dashboard consolidates all active plans, returns, and withdrawal history in a single unified view.</div>
          </details>

          <details class="faq-item">
            <summary>How do I get started?</summary>
            <div class="faq-body">Create a free account, complete identity verification, fund your wallet via bank transfer or cryptocurrency, and select an investment plan. The entire setup takes under 10 minutes.</div>
          </details>

        </div>

      </div>
    </div>
  </section>


  <!-- ── 9. CTA ────────────────────────────────────────────────────── -->
  <section style="background:var(--color-bg); padding:0 0 var(--space-8);" role="region" aria-labelledby="cta-title">
    <div class="section-dark" style="padding:var(--space-20) var(--space-6); text-align:center;">
      <div style="max-width:600px; margin:0 auto;" data-appear>
        <h2 id="cta-title" class="section-title" style="color:#fff; margin-bottom:var(--space-5);">
          Start growing your wealth today
        </h2>
        <p class="section-subtitle" style="color:rgba(255,255,255,0.55); margin-bottom:var(--space-10);">
          Join thousands of investors already earning with Qblockx.
        </p>
        <div class="cta-actions">
          <a href="/register" class="btn-primary">Create Free Account <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
          <a href="/contact" class="btn-outline-white">Talk to Us</a>
        </div>
      </div>
    </div>
  </section>

</main>

<?php require_once '../../includes/footer.php'; ?>
</body>
</html>

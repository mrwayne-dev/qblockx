<?php
/**
 * Project: Qblockx
 * Page: Investment Products (solutions.php serves /products and /solutions)
 */
$pageTitle       = 'Investment Products';
$pageDescription = 'Explore Qblockx investment products — tiered investment plans from $1,000 to $10M, commodities, and fractional real estate. Transparent returns, structured durations.';
$pageKeywords    = 'Qblockx products, investment plans, commodities investing, real estate, high yield';
require_once '../../includes/head.php';
?>

<?php require_once '../../includes/header.php'; ?>

<main>

  <!-- ── 1. Hero ──────────────────────────────────────────────────── -->
  <div class="hero-outer">
    <div class="hero-panel">
      <div class="hero-content">

        <div class="badge-outer">
          <div class="badge-ring"></div>
          <div class="badge-ring" style="animation-delay:-1s;"></div>
          <div class="badge-ring" style="animation-delay:-2s;"></div>
          <div class="badge-inner">
            <i class="ph ph-briefcase" aria-hidden="true" style="margin-right:6px;"></i>
            Investment Products
          </div>
        </div>

        <h1 class="hero-h1">Every asset class,<br>one platform.</h1>

        <p class="hero-subtext">
          Qblockx gives you access to structured investment plans, commodity markets, and fractional real estate — all with transparent returns and clear terms.
        </p>

        <div class="hero-actions">
          <a href="/register" class="btn-primary">
            Start Investing <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
          <a href="#plans" class="btn-outline-white">
            View Plans
          </a>
        </div>

      </div>
    </div>
  </div>


  <!-- ── 2. Investment Plans Feature ─────────────────────────────── -->
  <section class="section" id="plans" role="region" aria-labelledby="plans-title">
    <div class="container">

      <div class="section-header" data-appear>
        <span class="section-label">PRODUCT 01</span>
        <h2 id="plans-title" class="section-title">
          Tiered investment plans
        </h2>
        <p class="section-subtitle">
          Eight structured plans across two tiers — Starter and Elite. Fixed durations, clear returns, zero ambiguity.
        </p>
      </div>

      <div class="feature-outer" data-appear>
        <div class="feature-inner">
          <div class="feature-icon">
            <i class="ph ph-chart-pie" aria-hidden="true"></i>
          </div>
          <h3 class="feature-title">Starter Plans — $1,000 to $49,999</h3>
          <p class="feature-body">
            Four entry-level plans for investors growing their portfolio. Returns range from 30% to 150% per cycle, with durations of 7 to 30 days. Simple structure, fast turnaround.
          </p>
          <a href="/#plans" class="feature-arrow" aria-label="View Starter Plans">
            <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
        </div>
        <div class="feature-image-side" aria-hidden="true"
             style="background:linear-gradient(135deg,#050F26,#111C3A); display:flex; align-items:center; justify-content:center;">
          <i class="ph ph-chart-bar" style="font-size:64px; color:rgba(34,98,255,0.40);"></i>
        </div>
      </div>

      <div class="feature-outer" style="margin-top:20px;" data-appear>
        <div class="feature-image-side" aria-hidden="true"
             style="background:linear-gradient(135deg,#050F26,#111C3A); display:flex; align-items:center; justify-content:center;">
          <i class="ph ph-crown" style="font-size:64px; color:rgba(34,98,255,0.40);"></i>
        </div>
        <div class="feature-inner">
          <div class="feature-icon">
            <i class="ph ph-crown" aria-hidden="true"></i>
          </div>
          <h3 class="feature-title">Elite Plans — $50,000 to $10,000,000</h3>
          <p class="feature-body">
            Four high-capital plans for serious investors. Returns from 200% up to 400%, with durations of 14 days to 365 days. Compounded returns on Gold and Platinum tiers.
          </p>
          <a href="/#plans" class="feature-arrow" aria-label="View Elite Plans">
            <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
        </div>
      </div>

    </div>
  </section>


  <!-- ── 3. Commodities & Real Estate — dark panel ─────────────────── -->
  <section style="background:var(--color-bg); padding:0 0 var(--space-8);"
           id="assets" role="region" aria-labelledby="assets-title">
    <div class="section-dark">
      <div class="dark-section-inner">

        <div style="text-align:center; margin-bottom:var(--space-12);" data-appear>
          <span class="section-label" style="color:rgba(211,216,233,0.70);">PRODUCTS 02 & 03</span>
          <h2 id="assets-title" class="section-title" style="color:#FFFFFF; margin-top:var(--space-3);">
            Diversify your portfolio
          </h2>
          <p class="section-subtitle" style="color:rgba(255,255,255,0.55); max-width:520px; margin:var(--space-4) auto 0;">
            Beyond investment plans, Qblockx gives you access to tangible asset classes that protect and grow your wealth.
          </p>
        </div>

        <div class="asset-cards" data-appear>

          <div class="asset-card">
            <div class="asset-card-icon">
              <i class="ph ph-coins" aria-hidden="true"></i>
            </div>
            <h3 class="asset-card-title">Commodities</h3>
            <p class="asset-card-body">
              Invest in precious metals (gold, silver), energy (oil, gas), and agricultural commodities. Hedge your portfolio against inflation with real-world assets that hold value long-term.
            </p>
            <a href="/register" class="asset-card-link">
              Start Investing <i class="ph ph-arrow-right" aria-hidden="true"></i>
            </a>
          </div>

          <div class="asset-card">
            <div class="asset-card-icon">
              <i class="ph ph-buildings" aria-hidden="true"></i>
            </div>
            <h3 class="asset-card-title">Real Estate</h3>
            <p class="asset-card-body">
              Access fractional real estate investments in global markets. Build a diversified property portfolio without the full capital outlay of direct ownership.
            </p>
            <a href="/register" class="asset-card-link">
              Explore Properties <i class="ph ph-arrow-right" aria-hidden="true"></i>
            </a>
          </div>

          <div class="asset-card">
            <div class="asset-card-icon">
              <i class="ph ph-wallet" aria-hidden="true"></i>
            </div>
            <h3 class="asset-card-title">Central Wallet</h3>
            <p class="asset-card-body">
              One wallet manages all your investments, deposits, returns, and withdrawals. Full transaction history, real-time balance, and instant access to your funds at maturity.
            </p>
            <a href="/register" class="asset-card-link">
              Open Account <i class="ph ph-arrow-right" aria-hidden="true"></i>
            </a>
          </div>

        </div>

      </div>
    </div>
  </section>


  <!-- ── 4. How It Works ──────────────────────────────────────────── -->
  <section class="section" id="how-it-works" role="region" aria-labelledby="hiw-title">
    <div class="container">

      <div style="text-align:center; margin-bottom:var(--space-16);" data-appear>
        <span class="section-label">HOW IT WORKS</span>
        <h2 id="hiw-title" class="section-title" style="margin-top:var(--space-3);">
          Three steps to your first return
        </h2>
      </div>

      <div class="how-steps" data-appear>

        <div class="how-step">
          <div class="how-step-num" aria-hidden="true">01</div>
          <h3 class="how-step-title">Create your account</h3>
          <p class="how-step-body">Sign up in minutes. Verify your identity and fund your wallet to unlock access to all investment plans and asset classes.</p>
          <a href="/register" class="how-step-link">Register Now <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
        </div>

        <div class="how-step">
          <div class="how-step-num" aria-hidden="true">02</div>
          <h3 class="how-step-title">Choose your product</h3>
          <p class="how-step-body">Select a Starter or Elite plan, or allocate to commodities and real estate. All products are available from your dashboard.</p>
          <a href="/#plans" class="how-step-link">View Plans <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
        </div>

        <div class="how-step">
          <div class="how-step-num" aria-hidden="true">03</div>
          <h3 class="how-step-title">Earn and withdraw</h3>
          <p class="how-step-body">At maturity, returns are credited automatically to your wallet. Withdraw anytime to your preferred payment method.</p>
          <a href="/login" class="how-step-link">Go to Dashboard <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
        </div>

      </div>

    </div>
  </section>


  <!-- ── 5. CTA ────────────────────────────────────────────────────── -->
  <section style="background:var(--color-bg); padding:0 0 var(--space-8);"
           role="region" aria-labelledby="products-cta-title">
    <div class="section-dark" style="padding:var(--space-20) var(--space-6); text-align:center;">
      <div style="max-width:600px; margin:0 auto;" data-appear>
        <h2 id="products-cta-title" class="section-title" style="color:#FFFFFF; margin-bottom:var(--space-5);">
          Ready to put your capital to work?
        </h2>
        <p class="section-subtitle" style="color:rgba(255,255,255,0.55); margin-bottom:var(--space-10);">
          Open a free account and start investing across all Qblockx asset classes today.
        </p>
        <div class="cta-actions">
          <a href="/register" class="btn-primary">
            Create Free Account <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
          <a href="/contact" class="btn-outline-white">Talk to Us</a>
        </div>
      </div>
    </div>
  </section>

</main>

<?php require_once '../../includes/footer.php'; ?>
</body>
</html>

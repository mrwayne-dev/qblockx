<?php
/**
 * Project: Qblockx
 * Page: Real Estate
 */
$pageTitle       = 'Real Estate';
$pageDescription = 'Invest in fractional real estate through Qblockx. Access global property markets — residential, commercial, and industrial — without the full capital outlay of direct ownership.';
$pageKeywords    = 'Qblockx real estate, fractional real estate, property investment, commercial real estate, passive income, global property';
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
            <i class="ph ph-buildings" aria-hidden="true" style="margin-right:6px;"></i>
            Real Estate
          </div>
        </div>

        <h1 class="hero-h1">Own a slice of<br>global property.</h1>

        <p class="hero-subtext">
          Fractional real estate investing gives you access to residential, commercial, and industrial properties worldwide — starting from a fraction of the full purchase price.
        </p>

        <div class="hero-actions">
          <a href="/register" class="btn-primary">
            Explore Properties <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
          <a href="#categories" class="btn-outline-white">View Categories</a>
        </div>

        <div class="hero-stats-card" data-appear>
          <div class="hero-stat">
            <span class="hero-stat-value text-gradient-blue">3</span>
            <span class="hero-stat-label">Property types</span>
          </div>
          <div class="hero-stat">
            <span class="hero-stat-value text-gradient-blue-rev">Global</span>
            <span class="hero-stat-label">Market access</span>
          </div>
          <div class="hero-stat">
            <span class="hero-stat-value text-gradient-blue">Fractional</span>
            <span class="hero-stat-label">Ownership model</span>
          </div>
        </div>

      </div>
    </div>
  </div>


  <!-- ── 2. What is Fractional Real Estate ───────────────────────── -->
  <section class="section" role="region" aria-labelledby="fractional-title">
    <div class="container">

      <div class="section-header" data-appear>
        <span class="section-label">HOW IT WORKS</span>
        <h2 id="fractional-title" class="section-title">
          What is fractional real estate?
        </h2>
        <p class="section-subtitle">
          Traditional real estate requires large capital, active management, and illiquid positions. Fractional investing removes all three barriers.
        </p>
      </div>

      <div class="why-grid" data-appear>

        <div>
          <h3 class="section-title--sm" style="margin-bottom:var(--space-6);">The traditional problem</h3>
          <div style="display:flex; flex-direction:column; gap:var(--space-5);">
            <div style="display:flex; gap:var(--space-4); align-items:flex-start;">
              <div style="width:40px; height:40px; border-radius:50%; background:rgba(220,38,38,0.1); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="ph ph-x" style="color:#DC2626; font-size:18px;" aria-hidden="true"></i>
              </div>
              <div>
                <strong style="display:block; margin-bottom:4px; color:var(--color-text);">High capital requirements</strong>
                <p style="color:var(--color-text-body); font-size:var(--text-sm); line-height:22px;">Buying a commercial property outright requires hundreds of thousands to millions of dollars in capital — out of reach for most investors.</p>
              </div>
            </div>
            <div style="display:flex; gap:var(--space-4); align-items:flex-start;">
              <div style="width:40px; height:40px; border-radius:50%; background:rgba(220,38,38,0.1); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="ph ph-x" style="color:#DC2626; font-size:18px;" aria-hidden="true"></i>
              </div>
              <div>
                <strong style="display:block; margin-bottom:4px; color:var(--color-text);">Active management burden</strong>
                <p style="color:var(--color-text-body); font-size:var(--text-sm); line-height:22px;">Direct ownership means tenants, maintenance, legal fees, and tax filings — a full-time responsibility most investors don't want.</p>
              </div>
            </div>
            <div style="display:flex; gap:var(--space-4); align-items:flex-start;">
              <div style="width:40px; height:40px; border-radius:50%; background:rgba(220,38,38,0.1); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="ph ph-x" style="color:#DC2626; font-size:18px;" aria-hidden="true"></i>
              </div>
              <div>
                <strong style="display:block; margin-bottom:4px; color:var(--color-text);">Illiquid positions</strong>
                <p style="color:var(--color-text-body); font-size:var(--text-sm); line-height:22px;">Traditional property takes weeks or months to sell. Your capital is locked until you find a buyer at the right price.</p>
              </div>
            </div>
          </div>
        </div>

        <div>
          <h3 class="section-title--sm" style="margin-bottom:var(--space-6);">The Qblockx solution</h3>
          <div style="display:flex; flex-direction:column; gap:var(--space-5);">
            <div style="display:flex; gap:var(--space-4); align-items:flex-start;">
              <div style="width:40px; height:40px; border-radius:50%; background:rgba(34,98,255,0.1); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="ph ph-check" style="color:var(--color-accent); font-size:18px;" aria-hidden="true"></i>
              </div>
              <div>
                <strong style="display:block; margin-bottom:4px; color:var(--color-text);">Low minimum entry</strong>
                <p style="color:var(--color-text-body); font-size:var(--text-sm); line-height:22px;">Pool capital with other Qblockx investors to own a fraction of premium properties. Access markets that were previously reserved for institutional buyers.</p>
              </div>
            </div>
            <div style="display:flex; gap:var(--space-4); align-items:flex-start;">
              <div style="width:40px; height:40px; border-radius:50%; background:rgba(34,98,255,0.1); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="ph ph-check" style="color:var(--color-accent); font-size:18px;" aria-hidden="true"></i>
              </div>
              <div>
                <strong style="display:block; margin-bottom:4px; color:var(--color-text);">Fully passive income</strong>
                <p style="color:var(--color-text-body); font-size:var(--text-sm); line-height:22px;">Qblockx handles all property management, tenant relations, and legal compliance. You own the return, we handle everything else.</p>
              </div>
            </div>
            <div style="display:flex; gap:var(--space-4); align-items:flex-start;">
              <div style="width:40px; height:40px; border-radius:50%; background:rgba(34,98,255,0.1); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="ph ph-check" style="color:var(--color-accent); font-size:18px;" aria-hidden="true"></i>
              </div>
              <div>
                <strong style="display:block; margin-bottom:4px; color:var(--color-text);">Structured exit terms</strong>
                <p style="color:var(--color-text-body); font-size:var(--text-sm); line-height:22px;">Each real estate position has defined duration and exit terms. Your capital is returned on schedule — no reliance on finding a secondary buyer.</p>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>
  </section>


  <!-- ── 3. Property Categories ───────────────────────────────────── -->
  <section style="background:var(--color-bg); padding:0 0 var(--space-8);"
           id="categories" role="region" aria-labelledby="prop-categories-title">
    <div class="section-dark">
      <div class="dark-section-inner">

        <div style="text-align:center; margin-bottom:var(--space-12);" data-appear>
          <span class="section-label" style="color:rgba(211,216,233,0.70);">PROPERTY TYPES</span>
          <h2 id="prop-categories-title" class="section-title" style="color:#FFFFFF; margin-top:var(--space-3);">
            Three categories of property
          </h2>
          <p class="section-subtitle" style="color:rgba(255,255,255,0.55); max-width:520px; margin:var(--space-4) auto 0;">
            Each property type carries different yield profiles, risk characteristics, and market drivers.
          </p>
        </div>

        <div class="asset-cards" data-appear>

          <div class="asset-card">
            <div class="asset-card-icon">
              <i class="ph ph-house" aria-hidden="true"></i>
            </div>
            <h3 class="asset-card-title">Residential</h3>
            <p class="asset-card-body">
              Single-family homes, multi-family apartments, and student housing in high-demand urban markets. Residential real estate generates consistent rental yield driven by long-term housing demand.
            </p>
            <a href="/register" class="asset-card-link">
              Invest Now <i class="ph ph-arrow-right" aria-hidden="true"></i>
            </a>
          </div>

          <div class="asset-card">
            <div class="asset-card-icon">
              <i class="ph ph-office-chair" aria-hidden="true"></i>
            </div>
            <h3 class="asset-card-title">Commercial</h3>
            <p class="asset-card-body">
              Office buildings, retail spaces, and mixed-use developments in prime business districts. Commercial leases run longer than residential, providing stable income streams for investors seeking predictability.
            </p>
            <a href="/register" class="asset-card-link">
              Invest Now <i class="ph ph-arrow-right" aria-hidden="true"></i>
            </a>
          </div>

          <div class="asset-card">
            <div class="asset-card-icon">
              <i class="ph ph-warehouse" aria-hidden="true"></i>
            </div>
            <h3 class="asset-card-title">Industrial</h3>
            <p class="asset-card-body">
              Warehouses, logistics hubs, and manufacturing facilities — the backbone of global e-commerce. Industrial real estate has outperformed other property types in recent years, driven by rising demand for last-mile delivery infrastructure.
            </p>
            <a href="/register" class="asset-card-link">
              Invest Now <i class="ph ph-arrow-right" aria-hidden="true"></i>
            </a>
          </div>

        </div>

      </div>
    </div>
  </section>


  <!-- ── 4. Benefits ──────────────────────────────────────────────── -->
  <section class="section" role="region" aria-labelledby="re-benefits-title">
    <div class="container">

      <div class="section-header" data-appear>
        <span class="section-label">BENEFITS</span>
        <h2 id="re-benefits-title" class="section-title">
          Why invest in real estate through Qblockx
        </h2>
        <p class="section-subtitle">
          All the advantages of property ownership, none of the headaches.
        </p>
      </div>

      <div class="feature-grid" data-appear>

        <div class="feature-card">
          <div class="feature-card-icon"><i class="ph ph-money" aria-hidden="true"></i></div>
          <div class="feature-card-content">
            <h3 class="feature-card-title">Passive rental income</h3>
            <p class="feature-card-body">Earn a proportional share of rental income from your property fractions. Income is credited to your wallet on each distribution cycle.</p>
          </div>
        </div>

        <div class="feature-card">
          <div class="feature-card-icon"><i class="ph ph-chart-line-up" aria-hidden="true"></i></div>
          <div class="feature-card-content">
            <h3 class="feature-card-title">Capital appreciation</h3>
            <p class="feature-card-body">In addition to rental yield, your fraction value grows as the underlying property appreciates. At exit, you receive both the income earned and the appreciation gain.</p>
          </div>
        </div>

        <div class="feature-card">
          <div class="feature-card-icon"><i class="ph ph-globe-hemisphere-west" aria-hidden="true"></i></div>
          <div class="feature-card-content">
            <h3 class="feature-card-title">Global diversification</h3>
            <p class="feature-card-body">Access property markets across multiple regions and economies. Spread exposure across residential, commercial, and industrial properties without geographic concentration risk.</p>
          </div>
        </div>

        <div class="feature-card">
          <div class="feature-card-icon"><i class="ph ph-hammer" aria-hidden="true"></i></div>
          <div class="feature-card-content">
            <h3 class="feature-card-title">Zero management burden</h3>
            <p class="feature-card-body">No landlord duties. No tenant calls. No maintenance bills. Qblockx manages all operational aspects of each property on behalf of fraction holders.</p>
          </div>
        </div>

        <div class="feature-card">
          <div class="feature-card-icon"><i class="ph ph-file-text" aria-hidden="true"></i></div>
          <div class="feature-card-content">
            <h3 class="feature-card-title">Transparent documentation</h3>
            <p class="feature-card-body">Every property investment comes with full documentation — valuation reports, rental agreements, ownership structure, and projected return schedule.</p>
          </div>
        </div>

        <div class="feature-card">
          <div class="feature-card-icon"><i class="ph ph-shield-check" aria-hidden="true"></i></div>
          <div class="feature-card-content">
            <h3 class="feature-card-title">Segregated investor funds</h3>
            <p class="feature-card-body">Real estate investment funds are held separately from Qblockx operational capital. Your property allocation is ring-fenced and auditable at all times.</p>
          </div>
        </div>

      </div>

    </div>
  </section>


  <!-- ── 5. How It Works ──────────────────────────────────────────── -->
  <section style="background:var(--color-bg); padding:0 0 var(--space-8);"
           id="how-it-works" role="region" aria-labelledby="hiw-re-title">
    <div class="section-dark">
      <div class="dark-section-inner">

        <div style="text-align:center; margin-bottom:var(--space-16);" data-appear>
          <span class="section-label" style="color:rgba(211,216,233,0.70);">HOW IT WORKS</span>
          <h2 id="hiw-re-title" class="section-title" style="color:#FFFFFF; margin-top:var(--space-3);">
            From wallet to property in minutes
          </h2>
        </div>

        <div class="how-steps how-steps--bento" data-appear>

          <div class="how-step" data-step="01">
            <div class="how-step-num" aria-hidden="true">01</div>
            <h3 class="how-step-title">Fund your wallet</h3>
            <p class="how-step-body">Register on Qblockx, verify your account, and deposit capital into your central wallet via bank transfer or crypto. Your wallet is your hub for all property investments — one balance, multiple properties.</p>
            <a href="/register" class="how-step-link">Register <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
          </div>

          <div class="how-step" data-step="02">
            <div class="how-step-num" aria-hidden="true">02</div>
            <h3 class="how-step-title">Select a property</h3>
            <p class="how-step-body">Browse available property fractions from your dashboard. Review location, category, yield projection, and duration before committing capital.</p>
            <a href="/login" class="how-step-link">Browse Properties <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
          </div>

          <div class="how-step" data-step="03">
            <div class="how-step-num" aria-hidden="true">03</div>
            <h3 class="how-step-title">Earn and exit</h3>
            <p class="how-step-body">Receive rental distributions during the investment period. At term end, receive your capital plus appreciation return directly to your wallet.</p>
            <a href="/login" class="how-step-link">View Dashboard <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
          </div>

        </div>

      </div>
    </div>
  </section>


  <!-- ── 6. CTA ────────────────────────────────────────────────────── -->
  <section style="padding:var(--space-20) var(--space-6); text-align:center; background:var(--color-bg);"
           role="region" aria-labelledby="re-cta-title">
    <div style="max-width:600px; margin:0 auto;" data-appear>
      <span class="section-label">GET STARTED</span>
      <h2 id="re-cta-title" class="section-title" style="margin:var(--space-3) 0 var(--space-5);">
        Your first property fraction starts here
      </h2>
      <p class="section-subtitle" style="margin-bottom:var(--space-10);">
        Open a Qblockx account, fund your wallet, and start building a passive real estate income stream today.
      </p>
      <div class="cta-actions">
        <a href="/register" class="btn-primary">
          Create Free Account <i class="ph ph-arrow-right" aria-hidden="true"></i>
        </a>
        <a href="/commodities" class="btn-outline">Explore Commodities</a>
      </div>
    </div>
  </section>

</main>

<?php require_once '../../includes/footer.php'; ?>
</body>
</html>

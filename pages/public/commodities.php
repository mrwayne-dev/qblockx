<?php
/**
 * Project: Qblockx
 * Page: Commodities
 */
$pageTitle       = 'Commodities';
$pageDescription = 'Invest in global commodities through Qblockx — precious metals, energy, and agricultural assets. Hedge against inflation with tangible, real-world investments.';
$pageKeywords    = 'Qblockx commodities, gold investing, silver, oil, energy commodities, agricultural investment, inflation hedge';
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
            <i class="ph ph-coins" aria-hidden="true" style="margin-right:6px;"></i>
            Commodities
          </div>
        </div>

        <h1 class="hero-h1">Real assets.<br>Real returns.</h1>

        <p class="hero-subtext">
          Invest in precious metals, energy, and agricultural commodities. Tangible assets that hold value through market cycles — the original portfolio hedge.
        </p>

        <div class="hero-actions">
          <a href="/register" class="btn-primary">
            Start Investing <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
          <a href="#markets" class="btn-outline-white">Explore Markets</a>
        </div>

        <div class="hero-stats-card" data-appear>
          <div class="hero-stat">
            <span class="hero-stat-value text-gradient-blue">3</span>
            <span class="hero-stat-label">Asset categories</span>
          </div>
          <div class="hero-stat">
            <span class="hero-stat-value text-gradient-blue-rev">Global</span>
            <span class="hero-stat-label">Market access</span>
          </div>
          <div class="hero-stat">
            <span class="hero-stat-value text-gradient-blue">24/7</span>
            <span class="hero-stat-label">Portfolio tracking</span>
          </div>
        </div>

      </div>
    </div>
  </div>


  <!-- ── 2. Asset Categories ──────────────────────────────────────── -->
  <section class="section" id="markets" role="region" aria-labelledby="markets-title">
    <div class="container">

      <div class="section-header" data-appear>
        <span class="section-label">MARKETS</span>
        <h2 id="markets-title" class="section-title">
          Three commodity categories
        </h2>
        <p class="section-subtitle">
          Each category provides distinct diversification benefits and responds differently to macroeconomic conditions.
        </p>
      </div>

      <div class="feature-outer" data-appear>
        <div class="feature-inner">
          <div class="feature-icon">
            <i class="ph ph-crown" aria-hidden="true"></i>
          </div>
          <h3 class="feature-title">Precious Metals</h3>
          <p class="feature-body">
            Gold and silver are the world's oldest stores of value. They perform best during inflationary periods, currency devaluation, and geopolitical uncertainty — acting as a safe-haven anchor for your portfolio.
          </p>
          <ul class="feature-body" style="margin-top:var(--space-4); padding-left:var(--space-5);">
            <li>Gold (XAU) — global reserve metal</li>
            <li>Silver (XAG) — industrial + monetary value</li>
            <li>Platinum &amp; Palladium — high-demand industrial metals</li>
          </ul>
          <a href="/register" class="feature-arrow" aria-label="Invest in Precious Metals">
            <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
        </div>
        <div class="feature-image-side" aria-hidden="true"
             style="background:linear-gradient(135deg,#050F26,#111C3A); display:flex; align-items:center; justify-content:center;">
          <i class="ph ph-crown" style="font-size:72px; color:rgba(34,98,255,0.40);"></i>
        </div>
      </div>

      <div class="feature-outer" style="margin-top:20px;" data-appear>
        <div class="feature-image-side" aria-hidden="true"
             style="background:linear-gradient(135deg,#050F26,#111C3A); display:flex; align-items:center; justify-content:center;">
          <i class="ph ph-flame" style="font-size:72px; color:rgba(34,98,255,0.40);"></i>
        </div>
        <div class="feature-inner">
          <div class="feature-icon">
            <i class="ph ph-flame" aria-hidden="true"></i>
          </div>
          <h3 class="feature-title">Energy Commodities</h3>
          <p class="feature-body">
            Crude oil and natural gas underpin global industrial output. Energy commodities are among the most actively traded assets worldwide, offering liquidity and exposure to global demand cycles.
          </p>
          <ul class="feature-body" style="margin-top:var(--space-4); padding-left:var(--space-5);">
            <li>Crude Oil (WTI &amp; Brent) — global benchmark prices</li>
            <li>Natural Gas — infrastructure and heating demand</li>
            <li>Renewable energy futures — emerging asset class</li>
          </ul>
          <a href="/register" class="feature-arrow" aria-label="Invest in Energy Commodities">
            <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
        </div>
      </div>

      <div class="feature-outer" style="margin-top:20px;" data-appear>
        <div class="feature-inner">
          <div class="feature-icon">
            <i class="ph ph-plant" aria-hidden="true"></i>
          </div>
          <h3 class="feature-title">Agricultural Commodities</h3>
          <p class="feature-body">
            Food and fiber commodities are driven by supply-chain dynamics, climate patterns, and global population growth. Agricultural assets provide uncorrelated returns relative to equities and bonds.
          </p>
          <ul class="feature-body" style="margin-top:var(--space-4); padding-left:var(--space-5);">
            <li>Wheat &amp; Corn — essential food staples</li>
            <li>Coffee &amp; Cocoa — high-demand soft commodities</li>
            <li>Cotton &amp; Soybeans — global industrial supply chains</li>
          </ul>
          <a href="/register" class="feature-arrow" aria-label="Invest in Agricultural Commodities">
            <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
        </div>
        <div class="feature-image-side" aria-hidden="true"
             style="background:linear-gradient(135deg,#050F26,#111C3A); display:flex; align-items:center; justify-content:center;">
          <i class="ph ph-plant" style="font-size:72px; color:rgba(34,98,255,0.40);"></i>
        </div>
      </div>

    </div>
  </section>


  <!-- ── 3. Why Commodities ───────────────────────────────────────── -->
  <section style="background:var(--color-bg); padding:0 0 var(--space-8);"
           role="region" aria-labelledby="why-commodities-title">
    <div class="section-dark">
      <div class="dark-section-inner">

        <div style="text-align:center; margin-bottom:var(--space-12);" data-appear>
          <span class="section-label" style="color:rgba(211,216,233,0.70);">WHY COMMODITIES</span>
          <h2 id="why-commodities-title" class="section-title" style="color:#FFFFFF; margin-top:var(--space-3);">
            The case for tangible assets
          </h2>
          <p class="section-subtitle" style="color:rgba(255,255,255,0.55); max-width:520px; margin:var(--space-4) auto 0;">
            In a world of volatile equities and low-yield bonds, commodities offer a distinct risk-return profile.
          </p>
        </div>

        <div class="feature-grid" data-appear>

          <div class="feature-card">
            <div class="feature-card-icon"><i class="ph ph-trend-up" aria-hidden="true"></i></div>
            <div class="feature-card-content">
              <h3 class="feature-card-title">Inflation hedge</h3>
              <p class="feature-card-body">Commodity prices historically rise with inflation. When the purchasing power of cash falls, tangible assets tend to hold or increase their real value.</p>
            </div>
          </div>

          <div class="feature-card">
            <div class="feature-card-icon"><i class="ph ph-shuffle" aria-hidden="true"></i></div>
            <div class="feature-card-content">
              <h3 class="feature-card-title">Low correlation to equities</h3>
              <p class="feature-card-body">Commodity returns often move independently of stock markets. Adding them to a portfolio reduces overall volatility and smooths drawdowns during equity sell-offs.</p>
            </div>
          </div>

          <div class="feature-card">
            <div class="feature-card-icon"><i class="ph ph-globe" aria-hidden="true"></i></div>
            <div class="feature-card-content">
              <h3 class="feature-card-title">Global supply-demand dynamics</h3>
              <p class="feature-card-body">Commodity prices are driven by fundamental supply and demand — not sentiment or speculation. Informed investors can position around structural trends in energy, food, and metals.</p>
            </div>
          </div>

          <div class="feature-card">
            <div class="feature-card-icon"><i class="ph ph-lock" aria-hidden="true"></i></div>
            <div class="feature-card-content">
              <h3 class="feature-card-title">Store of value</h3>
              <p class="feature-card-body">Precious metals have preserved wealth across centuries and civilisations. Unlike paper currencies, gold and silver cannot be printed into existence or defaulted on.</p>
            </div>
          </div>

          <div class="feature-card">
            <div class="feature-card-icon"><i class="ph ph-chart-bar" aria-hidden="true"></i></div>
            <div class="feature-card-content">
              <h3 class="feature-card-title">Portfolio diversification</h3>
              <p class="feature-card-body">Three distinct commodity categories — metals, energy, agriculture — each responding to different macroeconomic drivers, giving you layered diversification in a single asset class.</p>
            </div>
          </div>

          <div class="feature-card">
            <div class="feature-card-icon"><i class="ph ph-currency-dollar" aria-hidden="true"></i></div>
            <div class="feature-card-content">
              <h3 class="feature-card-title">Accessible entry point</h3>
              <p class="feature-card-body">Through Qblockx, commodity exposure starts at the same minimum as investment plans. No need for futures accounts, brokerage accounts, or physical storage.</p>
            </div>
          </div>

        </div>

      </div>
    </div>
  </section>


  <!-- ── 4. How It Works ──────────────────────────────────────────── -->
  <section class="section" id="how-it-works" role="region" aria-labelledby="hiw-commodities-title">
    <div class="container">

      <div style="text-align:center; margin-bottom:var(--space-16);" data-appear>
        <span class="section-label">HOW IT WORKS</span>
        <h2 id="hiw-commodities-title" class="section-title" style="margin-top:var(--space-3);">
          Invest in commodities in three steps
        </h2>
      </div>

      <div class="how-steps how-steps--bento" data-appear>

        <div class="how-step" data-step="01">
          <div class="how-step-num" aria-hidden="true">01</div>
          <h3 class="how-step-title">Open your account</h3>
          <p class="how-step-body">Register on Qblockx, complete identity verification, and fund your central wallet. Your wallet is your single entry point to all commodity markets — no separate brokerage accounts required.</p>
          <a href="/register" class="how-step-link">Get Started <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
        </div>

        <div class="how-step" data-step="02">
          <div class="how-step-num" aria-hidden="true">02</div>
          <h3 class="how-step-title">Choose your commodity</h3>
          <p class="how-step-body">Select from precious metals, energy, or agricultural categories. Each position is sized according to your investment amount and current market rates.</p>
          <a href="/register" class="how-step-link">View Markets <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
        </div>

        <div class="how-step" data-step="03">
          <div class="how-step-num" aria-hidden="true">03</div>
          <h3 class="how-step-title">Track and exit</h3>
          <p class="how-step-body">Monitor your commodity positions in real-time from your dashboard. Exit when your target is reached and receive funds directly in your wallet.</p>
          <a href="/login" class="how-step-link">View Dashboard <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
        </div>

      </div>

    </div>
  </section>


  <!-- ── 5. FAQ ────────────────────────────────────────────────────── -->
  <section class="section" id="faq" role="region" aria-labelledby="faq-commodities-title">
    <div class="container">

      <div class="faq-layout">

        <div class="faq-header" data-appear>
          <span class="section-label">FAQ</span>
          <h2 id="faq-commodities-title" class="section-title" style="margin-top:var(--space-3);">
            Commodity questions
          </h2>
          <p class="section-subtitle" style="margin-top:var(--space-4);">
            Answers to the most common questions about investing in commodities on Qblockx.
          </p>
          <a href="/contact" class="btn-outline" style="margin-top:var(--space-8); display:inline-flex; align-items:center; gap:var(--space-2);">
            Ask a question <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
        </div>

        <div class="faq-group" data-appear>

          <details class="faq-item">
            <summary>Do I take physical delivery of commodities?</summary>
            <div class="faq-answer">
              <p>No. Qblockx provides financial exposure to commodity markets through structured positions. You invest in the price performance of the commodity, not the physical asset itself — no storage or logistics required.</p>
            </div>
          </details>

          <details class="faq-item">
            <summary>What is the minimum investment for commodities?</summary>
            <div class="faq-answer">
              <p>Commodity investments follow the same minimum thresholds as our investment plans. The exact minimum for your commodity allocation is displayed at the point of investment in your dashboard.</p>
            </div>
          </details>

          <details class="faq-item">
            <summary>How are commodity prices tracked?</summary>
            <div class="faq-answer">
              <p>Prices are sourced from global benchmark markets — LME for metals, ICE and CME for energy and agricultural futures. Your portfolio is marked to market daily.</p>
            </div>
          </details>

          <details class="faq-item">
            <summary>Can I hold commodity positions alongside investment plans?</summary>
            <div class="faq-answer">
              <p>Yes. Your Qblockx wallet supports simultaneous positions across investment plans and commodity holdings. Diversifying between asset classes is encouraged.</p>
            </div>
          </details>

          <details class="faq-item">
            <summary>Are commodity investments affected by currency risk?</summary>
            <div class="faq-answer">
              <p>Most global commodities are priced in USD. If your base currency differs, exchange rate movements can affect returns. Qblockx displays all values in your selected account currency.</p>
            </div>
          </details>

        </div>

      </div>

    </div>
  </section>


  <!-- ── 6. CTA ────────────────────────────────────────────────────── -->
  <section style="background:var(--color-bg); padding:0 0 var(--space-8);"
           role="region" aria-labelledby="commodities-cta-title">
    <div class="section-dark" style="padding:var(--space-20) var(--space-6); text-align:center;">
      <div style="max-width:600px; margin:0 auto;" data-appear>
        <h2 id="commodities-cta-title" class="section-title" style="color:#FFFFFF; margin-bottom:var(--space-5);">
          Protect your wealth with real assets
        </h2>
        <p class="section-subtitle" style="color:rgba(255,255,255,0.55); margin-bottom:var(--space-10);">
          Add commodities to your Qblockx portfolio today. Gold, oil, wheat — real markets, real returns.
        </p>
        <div class="cta-actions">
          <a href="/register" class="btn-primary">
            Start Investing <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
          <a href="/plans" class="btn-outline-white">View Investment Plans</a>
        </div>
      </div>
    </div>
  </section>

</main>

<?php require_once '../../includes/footer.php'; ?>
</body>
</html>

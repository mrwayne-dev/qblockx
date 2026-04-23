<?php
/**
 * Project: Qblockx
 * Page: Investment Plans
 */
$pageTitle       = 'Investment Plans';
$pageDescription = 'Explore all Qblockx investment plans. Eight tiered plans from $1,000 to $10M across Starter and Elite tiers. Fixed durations, transparent returns up to 400%.';
$pageKeywords    = 'Qblockx plans, investment plans, starter plans, elite plans, high yield investing, fixed returns';
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
            <i class="ph ph-chart-pie" aria-hidden="true" style="margin-right:6px;"></i>
            Investment Plans
          </div>
        </div>

        <h1 class="hero-h1">Eight plans.<br>One clear goal.</h1>

        <p class="hero-subtext">
          Starter to Elite — structured investment plans with fixed durations, transparent returns, and no hidden fees. Pick a tier that matches your capital and goals.
        </p>

        <div class="hero-actions">
          <a href="/register" class="btn-primary">
            Start Investing <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
          <a href="#plans" class="btn-outline-white">Compare Plans</a>
        </div>


      </div>
    </div>
  </div>


  <!-- ── 2. Plans Grid ────────────────────────────────────────────── -->
  <section class="section" id="plans" role="region" aria-labelledby="plans-title">
    <div class="container">

      <div style="text-align:center; margin-bottom:var(--space-12);" data-appear>
        <span class="section-label">INVESTMENT PLANS</span>
        <h2 id="plans-title" class="section-title" style="margin:var(--space-3) 0 var(--space-8);">
          Choose your investment tier
        </h2>
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


  <!-- ── 3. Plan Features ─────────────────────────────────────────── -->
  <section style="background:var(--color-bg); padding:0 0 var(--space-8);"
           role="region" aria-labelledby="features-title">
    <div class="section-dark">
      <div class="dark-section-inner">

        <div style="text-align:center; margin-bottom:var(--space-12);" data-appear>
          <span class="section-label" style="color:rgba(211,216,233,0.70);">WHY QBLOCKX PLANS</span>
          <h2 id="features-title" class="section-title" style="color:#FFFFFF; margin-top:var(--space-3);">
            Built for serious investors
          </h2>
          <p class="section-subtitle" style="color:rgba(255,255,255,0.55); max-width:520px; margin:var(--space-4) auto 0;">
            Every plan is structured for clarity. No lock-in surprises, no ambiguous terms — just capital in, returns out.
          </p>
        </div>

        <div class="plans-feat-bento" data-appear>

          <div class="pfb-cell">
            <div class="pfb-icon"><i class="ph ph-lock-simple-open" aria-hidden="true"></i></div>
            <div class="pfb-title">Fixed durations</div>
            <p class="pfb-body">Each plan has a defined cycle length — 7 to 365 days. Know exactly when your capital matures before you invest.</p>
          </div>

          <div class="pfb-cell pfb-cell--wide">
            <div class="pfb-icon"><i class="ph ph-chart-line-up" aria-hidden="true"></i></div>
            <div class="pfb-title">Transparent returns</div>
            <p class="pfb-body">Return ranges are published upfront and never adjusted mid-cycle. What you see on the plan card is exactly what you earn — verified on your dashboard in real time.</p>
          </div>

          <div class="pfb-cell pfb-cell--wide">
            <div class="pfb-icon"><i class="ph ph-arrows-clockwise" aria-hidden="true"></i></div>
            <div class="pfb-title">Auto-reinvest option</div>
            <p class="pfb-body">Enable compounding from your dashboard. Matured returns roll directly into the next cycle, growing your portfolio without any manual action required.</p>
          </div>

          <div class="pfb-cell">
            <div class="pfb-icon"><i class="ph ph-shield-check" aria-hidden="true"></i></div>
            <div class="pfb-title">Capital protection</div>
            <p class="pfb-body">Your principal is ring-fenced for the plan duration. Client funds are never commingled with operational capital.</p>
          </div>

          <div class="pfb-cell">
            <div class="pfb-icon"><i class="ph ph-users-three" aria-hidden="true"></i></div>
            <div class="pfb-title">Referral rewards</div>
            <p class="pfb-body">Earn 5–10% commission on every investor you refer. Credited instantly on plan activation.</p>
          </div>

          <div class="pfb-cell pfb-cell--wide">
            <div class="pfb-icon"><i class="ph ph-wallet" aria-hidden="true"></i></div>
            <div class="pfb-title">Instant withdrawal</div>
            <p class="pfb-body">Matured returns and principal are released instantly at the end of your cycle. No waiting periods, no redemption queues — funds in your wallet when the cycle ends.</p>
          </div>

        </div>

      </div>
    </div>
  </section>


  <!-- ── 4. How It Works ──────────────────────────────────────────── -->
  <section class="section" id="how-it-works" role="region" aria-labelledby="hiw-plans-title">
    <div class="container">

      <div style="text-align:center; margin-bottom:var(--space-16);" data-appear>
        <span class="section-label">HOW IT WORKS</span>
        <h2 id="hiw-plans-title" class="section-title" style="margin-top:var(--space-3);">
          Three steps to your first return
        </h2>
        <p class="section-subtitle" style="margin-top:var(--space-4); max-width:480px; margin-inline:auto;">
          From registration to payout — the full journey takes less than 5 minutes to set up.
        </p>
      </div>

      <div class="how-steps how-steps--bento" data-appear>

        <div class="how-step" data-step="01">
          <div class="how-step-num" aria-hidden="true">01</div>
          <h3 class="how-step-title">Create &amp; fund your account</h3>
          <p class="how-step-body">Sign up in minutes. Verify your identity, fund your central wallet via bank transfer or crypto, and you're ready to invest. No minimums to open — capital requirements only apply when you activate a plan.</p>
          <a href="/register" class="how-step-link">Register Now <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
        </div>

        <div class="how-step" data-step="02">
          <div class="how-step-num" aria-hidden="true">02</div>
          <h3 class="how-step-title">Select your plan</h3>
          <p class="how-step-body">Choose a Starter or Elite plan that fits your capital. Review the return range, duration, and terms — then activate with one click.</p>
          <a href="#plans" class="how-step-link">View Plans <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
        </div>

        <div class="how-step" data-step="03">
          <div class="how-step-num" aria-hidden="true">03</div>
          <h3 class="how-step-title">Collect your returns</h3>
          <p class="how-step-body">At cycle maturity, your principal plus returns are credited automatically. Withdraw to your bank or reinvest into the next cycle.</p>
          <a href="/login" class="how-step-link">Go to Dashboard <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
        </div>

      </div>

    </div>
  </section>


  <!-- ── 5. FAQ ────────────────────────────────────────────────────── -->
  <section class="section" id="faq" role="region" aria-labelledby="faq-plans-title">
    <div class="container">

      <div class="faq-layout">

        <div class="faq-header" data-appear>
          <span class="section-label">FAQ</span>
          <h2 id="faq-plans-title" class="section-title" style="margin-top:var(--space-3);">
            Common questions
          </h2>
          <p class="section-subtitle" style="margin-top:var(--space-4);">
            Everything you need to know before activating your first plan.
          </p>
          <a href="/contact" class="btn-outline" style="margin-top:var(--space-8); display:inline-flex; align-items:center; gap:var(--space-2);">
            Ask a question <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
        </div>

        <div class="faq-group" data-appear>

          <details class="faq-item">
            <summary>Can I run multiple plans at the same time?</summary>
            <div class="faq-answer">
              <p>Yes. You can activate multiple plans simultaneously across both Starter and Elite tiers, as long as your wallet has sufficient balance for each plan's minimum investment.</p>
            </div>
          </details>

          <details class="faq-item">
            <summary>What happens if I want to exit a plan early?</summary>
            <div class="faq-answer">
              <p>Plans run for a fixed cycle. Early withdrawal is not available once a plan is active. Your capital and returns are released in full at maturity.</p>
            </div>
          </details>

          <details class="faq-item">
            <summary>How are returns calculated?</summary>
            <div class="faq-answer">
              <p>Returns are calculated on your invested principal for the cycle duration. Elite Gold and Platinum tiers use compound returns, where gains are added to the principal each sub-period.</p>
            </div>
          </details>

          <details class="faq-item">
            <summary>Is there a minimum investment amount?</summary>
            <div class="faq-answer">
              <p>The minimum investment is $1,000 on the Micro plan. Each plan has its own minimum and maximum range — review the plan card before activating.</p>
            </div>
          </details>

          <details class="faq-item">
            <summary>How does the referral commission work?</summary>
            <div class="faq-answer">
              <p>When someone you refer activates a plan, you earn 5% (Starter) or 10% (Elite) of their invested amount as a commission, credited immediately to your wallet.</p>
            </div>
          </details>

          <details class="faq-item">
            <summary>Are returns guaranteed?</summary>
            <div class="faq-answer">
              <p>Returns are published as a range and reflect historical performance across our managed portfolio. While past performance doesn&rsquo;t guarantee future results, our structured approach aims to deliver within the stated range consistently.</p>
            </div>
          </details>

        </div>

      </div>

    </div>
  </section>


  <!-- ── 6. CTA ────────────────────────────────────────────────────── -->
  <section style="background:var(--color-bg); padding:0 0 var(--space-8);"
           role="region" aria-labelledby="plans-cta-title">
    <div class="section-dark" style="padding:var(--space-20) var(--space-6); text-align:center;">
      <div style="max-width:600px; margin:0 auto;" data-appear>
        <h2 id="plans-cta-title" class="section-title" style="color:#FFFFFF; margin-bottom:var(--space-5);">
          Ready to grow your capital?
        </h2>
        <p class="section-subtitle" style="color:rgba(255,255,255,0.55); margin-bottom:var(--space-10);">
          Open a free account, fund your wallet, and activate your first plan in minutes.
        </p>
        <div class="cta-actions">
          <a href="/register" class="btn-primary">
            Create Free Account <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
          <a href="/contact" class="btn-outline-white">Talk to an Advisor</a>
        </div>
      </div>
    </div>
  </section>

</main>

<?php require_once '../../includes/footer.php'; ?>
</body>
</html>

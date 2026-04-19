<?php
/**
 * Project: Qblockx
 * Page: Help Centre / FAQ (learnmore.php serves /help and /learnmore)
 */
$pageTitle       = 'Help Centre';
$pageDescription = 'Find answers to your questions about Qblockx — investment plans, withdrawals, account security, referral commissions, and getting started.';
$pageKeywords    = 'Qblockx help, FAQ, investment questions, support, getting started';
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
            <i class="ph ph-question" aria-hidden="true" style="margin-right:6px;"></i>
            Help Centre
          </div>
        </div>

        <h1 class="hero-h1">How can we help?</h1>

        <p class="hero-subtext">
          Find answers to the most common questions about Qblockx — investment plans, account management, withdrawals, and security.
        </p>

        <div class="hero-actions">
          <a href="/contact" class="btn-primary">
            Contact Support <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
          <a href="/#plans" class="btn-outline-white">
            View Plans
          </a>
        </div>

      </div>
    </div>
  </div>


  <!-- ── 2. FAQ ────────────────────────────────────────────────────── -->
  <section class="section" role="region" aria-labelledby="faq-title">
    <div class="container">

      <div class="section-header" data-appear>
        <span class="section-label">FAQ</span>
        <h2 id="faq-title" class="section-title">Frequently asked questions</h2>
        <p class="section-subtitle">
          Everything you need to know about getting started, investing, and managing your account.
        </p>
      </div>

      <!-- Getting Started -->
      <div class="faq-group" data-appear>
        <h3 class="faq-group-title">Getting Started</h3>

        <details class="faq-item">
          <summary>How do I open a Qblockx account?</summary>
          <div class="faq-answer">
            Click "Get Started" or "Open Account" from any page, fill in your name, email, and password, then verify your email address. Once verified you'll have immediate access to your dashboard and wallet.
          </div>
        </details>

        <details class="faq-item">
          <summary>What information do I need to register?</summary>
          <div class="faq-answer">
            You'll need a valid email address, your full name, and a secure password. KYC (identity) verification may be required before certain features are unlocked.
          </div>
        </details>

        <details class="faq-item">
          <summary>How do I fund my wallet?</summary>
          <div class="faq-answer">
            Go to your Wallet page and select "Deposit". Follow the instructions to add funds. Your wallet is the central hub — all investments, returns, and withdrawals flow through it.
          </div>
        </details>

        <details class="faq-item">
          <summary>What currencies are supported?</summary>
          <div class="faq-answer">
            Qblockx supports USD as the primary account currency. You can select your preferred display currency during registration.
          </div>
        </details>

      </div>

      <!-- Investment Plans -->
      <div class="faq-group" data-appear>
        <h3 class="faq-group-title">Investment Plans</h3>

        <details class="faq-item">
          <summary>How do investment plans work?</summary>
          <div class="faq-answer">
            You choose a plan tier (Starter or Elite), invest the required amount, and the plan runs for a fixed duration. At maturity, your principal plus the stated return percentage is credited back to your wallet automatically.
          </div>
        </details>

        <details class="faq-item">
          <summary>What is the difference between Starter and Elite plans?</summary>
          <div class="faq-answer">
            Starter plans accept $1,000 – $49,999 with returns of 30%–150% per cycle over 7–30 days. Elite plans accept $50,000 – $10,000,000 with returns of 200%–400%, including compounded returns on Gold and Platinum tiers.
          </div>
        </details>

        <details class="faq-item">
          <summary>Can I have multiple active plans at the same time?</summary>
          <div class="faq-answer">
            Yes. You can invest in multiple plans simultaneously across different tiers, subject to your available wallet balance.
          </div>
        </details>

        <details class="faq-item">
          <summary>What happens when a plan matures?</summary>
          <div class="faq-answer">
            At maturity, your principal plus the full return for that plan is automatically credited to your wallet. You can then reinvest or withdraw at any time.
          </div>
        </details>

        <details class="faq-item">
          <summary>Can I exit a plan early?</summary>
          <div class="faq-answer">
            Investment plans are locked for their stated duration. Early exit may not be available. Contact our support team if your circumstances change and you need to discuss your options.
          </div>
        </details>

      </div>

      <!-- Withdrawals & Deposits -->
      <div class="faq-group" data-appear>
        <h3 class="faq-group-title">Withdrawals &amp; Deposits</h3>

        <details class="faq-item">
          <summary>How do I withdraw funds?</summary>
          <div class="faq-answer">
            Go to your Wallet page and select "Withdraw". Enter the amount and your preferred payment details. Withdrawal requests are processed and approved by the platform team, typically within 24–48 hours.
          </div>
        </details>

        <details class="faq-item">
          <summary>Are there any withdrawal fees?</summary>
          <div class="faq-answer">
            A small processing fee may apply to certain withdrawal methods. The fee will be displayed before you confirm the request.
          </div>
        </details>

        <details class="faq-item">
          <summary>How long do deposits take to reflect?</summary>
          <div class="faq-answer">
            Deposits are typically reflected in your wallet within a few minutes to a few hours depending on the payment method. Contact support if your deposit has not appeared after 24 hours.
          </div>
        </details>

      </div>

      <!-- Referrals -->
      <div class="faq-group" data-appear>
        <h3 class="faq-group-title">Referral Commission</h3>

        <details class="faq-item">
          <summary>How does the referral programme work?</summary>
          <div class="faq-answer">
            Each user receives a unique referral link. When someone registers using your link and activates an investment plan, you earn a referral commission credited directly to your wallet. Starter plans pay 5% and Elite plans pay 7%–10% commission.
          </div>
        </details>

        <details class="faq-item">
          <summary>When is my referral commission paid?</summary>
          <div class="faq-answer">
            Referral commissions are credited to your wallet when the referred investor's plan is activated. You can find all referral credits in your transaction history.
          </div>
        </details>

      </div>

      <!-- Account & Security -->
      <div class="faq-group" data-appear>
        <h3 class="faq-group-title">Account &amp; Security</h3>

        <details class="faq-item">
          <summary>How do I enable two-factor authentication?</summary>
          <div class="faq-answer">
            Go to your Profile → Security Settings and toggle on Two-Factor Authentication. You'll be prompted to link an authenticator app. We strongly recommend enabling 2FA for all accounts.
          </div>
        </details>

        <details class="faq-item">
          <summary>What do I do if I forget my password?</summary>
          <div class="faq-answer">
            On the login page, click "Forgot Password" and enter your email address. You'll receive a password reset link valid for 60 minutes.
          </div>
        </details>

        <details class="faq-item">
          <summary>How is my data protected?</summary>
          <div class="faq-answer">
            All data is encrypted using AES-256 at rest and in transit. Session tokens expire automatically after inactivity. We conduct 24/7 fraud monitoring and will alert you to any suspicious activity.
          </div>
        </details>

        <details class="faq-item">
          <summary>How do I close my account?</summary>
          <div class="faq-answer">
            To close your account, ensure your wallet balance is zero and all active plans are settled, then contact our support team. Account closure requests are processed within 5 business days.
          </div>
        </details>

      </div>

    </div>
  </section>


  <!-- ── 3. CTA ────────────────────────────────────────────────────── -->
  <section style="background:var(--color-bg); padding:0 0 var(--space-8);"
           role="region" aria-labelledby="help-cta-title">
    <div class="section-dark" style="padding:var(--space-20) var(--space-6); text-align:center;">
      <div style="max-width:560px; margin:0 auto;" data-appear>
        <h2 id="help-cta-title" class="section-title" style="color:#FFFFFF; margin-bottom:var(--space-5);">
          Still have questions?
        </h2>
        <p class="section-subtitle" style="color:rgba(255,255,255,0.55); margin-bottom:var(--space-10);">
          Our support team is ready to help. Reach out and we'll get back to you within a few hours.
        </p>
        <div class="cta-actions">
          <a href="/contact" class="btn-primary">
            Contact Us <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
          <a href="/products" class="btn-outline-white">View Investment Products</a>
        </div>
      </div>
    </div>
  </section>

</main>

<?php require_once '../../includes/footer.php'; ?>
</body>
</html>

<?php
/**
 * Project: crestvalebank
 * Page: Help / FAQ (learnmore.php serves /help and /learnmore)
 */
$pageTitle       = 'Help Centre';
$pageDescription = 'Find answers to your questions about CrestVale Bank — savings plans, fixed deposits, loans, transfers, account security, and getting started.';
require_once '../../includes/head.php';
?>

<?php require_once '../../includes/header.php'; ?>

<main>

  <!-- ── Hero ──────────────────────────────────────────────────── -->
  <section class="hero hero--static" role="region" aria-label="Help Centre hero">
    <div class="hero-split">
      <div class="hero-left">
        <div class="hero-content active" data-slide="0">
          <span class="hero-tag">Help Centre</span>
          <h1 class="hero-heading">
            <span class="hero-line">How can we help?</span>
          </h1>
          <p class="hero-subtext">
            Find answers to the most common questions about CrestVale Bank's products,
            account management, security, and more.
          </p>
          <div class="hero-actions">
            <a href="/contact" class="btn-hero-outline">
              Contact Support <i class="ph ph-arrow-right" aria-hidden="true"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ── FAQ Sections ───────────────────────────────────────────── -->
  <section class="legal-doc section" role="region" aria-labelledby="faq-title">
    <div class="legal-doc-container container" data-appear>
      <div class="legal-doc-content">

        <span class="section-label">FAQ</span>
        <h2 id="faq-title" class="legal-doc-title">Frequently Asked Questions</h2>

        <!-- Getting Started -->
        <h3 class="legal-doc-subtitle">Getting Started</h3>
        <div class="faq-accordion">

          <details class="faq-item">
            <summary>How do I open a CrestVale Bank account?</summary>
            <div class="faq-body">
              Click "Get Started" or "Open Account" from any page, fill in your details, and
              verify your email address. Once verified, you'll have immediate access to your
              dashboard and wallet.
            </div>
          </details>

          <details class="faq-item">
            <summary>What information do I need to register?</summary>
            <div class="faq-body">
              You'll need a valid email address, your full name, and a secure password.
              KYC (identity) verification may be required before certain features are unlocked.
            </div>
          </details>

          <details class="faq-item">
            <summary>How do I fund my wallet?</summary>
            <div class="faq-body">
              Go to your Wallet page and select "Deposit". Follow the instructions to add
              funds. All financial products (savings, deposits, loans, transfers) operate
              through your central wallet balance.
            </div>
          </details>

        </div>

        <!-- Savings Plans -->
        <h3 class="legal-doc-subtitle">Savings Plans</h3>
        <div class="faq-accordion">

          <details class="faq-item">
            <summary>How do savings plans work?</summary>
            <div class="faq-body">
              You create a named plan, set a target amount and duration, then contribute
              funds from your wallet. Interest accumulates over the plan period. The plan
              completes when the target is reached or the duration ends.
            </div>
          </details>

          <details class="faq-item">
            <summary>What interest rate do savings plans earn?</summary>
            <div class="faq-body">
              Current savings plans earn up to 5.5<i class="ph ph-percent"></i>&thinsp;per annum. Rates are set by Crestvale Bank and
              may change — always check your dashboard for your plan's specific rate.
            </div>
          </details>

          <details class="faq-item">
            <summary>Can I withdraw from a savings plan early?</summary>
            <div class="faq-body">
              Early withdrawal may be possible depending on your plan's terms. Contact support
              if you need to access funds before the plan matures.
            </div>
          </details>

        </div>

        <!-- Fixed Deposits -->
        <h3 class="legal-doc-subtitle">Fixed Deposits</h3>
        <div class="faq-accordion">

          <details class="faq-item">
            <summary>How do fixed deposits work?</summary>
            <div class="faq-body">
              You select a deposit amount and term (6 or 12 months), and funds are moved
              from your wallet to the deposit. The funds are locked until the maturity date,
              at which point your principal plus all earned interest are returned to your wallet.
            </div>
          </details>

          <details class="faq-item">
            <summary>What are the available deposit rates?</summary>
            <div class="faq-body">
              6-month deposits earn 8<i class="ph ph-percent"></i>&thinsp;p.a. and 12-month deposits earn 12<i class="ph ph-percent"></i>&thinsp;p.a.
              Your expected return is displayed before you confirm the deposit.
            </div>
          </details>

          <details class="faq-item">
            <summary>Can I exit a fixed deposit early?</summary>
            <div class="faq-body">
              Fixed deposits are locked for their full term. Early exit may not be available.
              Contact support to discuss your options if circumstances change.
            </div>
          </details>

        </div>

        <!-- Loans -->
        <h3 class="legal-doc-subtitle">Loans</h3>
        <div class="faq-accordion">

          <details class="faq-item">
            <summary>How do I apply for a loan?</summary>
            <div class="faq-body">
              Go to your Loans dashboard and click "Apply for Loan". Enter the amount and
              preferred duration. Your application will be reviewed by Crestvale Bank, who
              will approve, reject, or modify the terms.
            </div>
          </details>

          <details class="faq-item">
            <summary>How long does loan approval take?</summary>
            <div class="faq-body">
              Most applications are reviewed within 24 hours. You'll be notified by email
              when a decision is made.
            </div>
          </details>

          <details class="faq-item">
            <summary>How do I repay my loan?</summary>
            <div class="faq-body">
              Go to your Loans page and select "Make Repayment". Monthly payments are
              calculated as: Total Loan ÷ Duration in months. You can also make early
              repayments to reduce your outstanding balance.
            </div>
          </details>

        </div>

        <!-- Transfers -->
        <h3 class="legal-doc-subtitle">Transfers</h3>
        <div class="faq-accordion">

          <details class="faq-item">
            <summary>How do transfers work?</summary>
            <div class="faq-body">
              Go to your Wallet page and select "Transfer". Enter the recipient's details and
              the amount. Funds are debited from your wallet and credited to the recipient
              instantly — no fees, no delays.
            </div>
          </details>

          <details class="faq-item">
            <summary>Are there transfer limits?</summary>
            <div class="faq-body">
              Transfers are limited to your available wallet balance. No artificial limits
              are applied beyond your account's current balance.
            </div>
          </details>

        </div>

        <!-- Account & Security -->
        <h3 class="legal-doc-subtitle">Account &amp; Security</h3>
        <div class="faq-accordion">

          <details class="faq-item">
            <summary>How do I enable two-factor authentication?</summary>
            <div class="faq-body">
              Go to your Profile → Security Settings and toggle on Two-Factor Authentication.
              You'll be prompted to link an authenticator app. We strongly recommend enabling 2FA.
            </div>
          </details>

          <details class="faq-item">
            <summary>What do I do if I forget my password?</summary>
            <div class="faq-body">
              On the login page, click "Forgot Password" and enter your email address.
              You'll receive a password reset link valid for 60 minutes.
            </div>
          </details>

          <details class="faq-item">
            <summary>How is my data protected?</summary>
            <div class="faq-body">
              All data is encrypted using AES-256 at rest and in transit. Session tokens
              expire automatically after inactivity. We conduct 24/7 fraud monitoring and
              will alert you to any suspicious activity.
            </div>
          </details>

          <details class="faq-item">
            <summary>How do I close my account?</summary>
            <div class="faq-body">
              To close your account, ensure your wallet balance is zero and all products are
              settled, then contact our support team. Account closure requests are processed
              within 5 business days.
            </div>
          </details>

        </div>

      </div>
    </div>
  </section>

  <!-- ── CTA ────────────────────────────────────────────────────── -->
  <section class="cta-section section" role="region" aria-labelledby="help-cta-title">
    <div class="cta-container container" data-appear>
      <h2 id="help-cta-title" class="cta-header">Still have questions?</h2>
      <p class="cta-subtext">Our support team is ready to help you.</p>
      <div class="cta-actions">
        <a href="/contact" class="btn-shiny">
          Contact Us <i class="ph ph-arrow-right" aria-hidden="true"></i>
        </a>
        <a href="/products" class="btn-outline">View Products</a>
      </div>
    </div>
  </section>

</main>

<?php require_once '../../includes/footer.php'; ?>
</body>
</html>

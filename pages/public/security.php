<?php
/**
 * Project: Qblockx
 * Page: Security
 */
$pageTitle       = 'Security';
$pageDescription = 'Qblockx uses AES-256 encryption, two-factor authentication, 24/7 fraud monitoring, and full KYC compliance to keep your investments and personal data protected.';
$pageKeywords    = 'Qblockx security, investment platform security, AES-256, 2FA, data protection';
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
            <i class="ph ph-shield-check" aria-hidden="true" style="margin-right:6px;"></i>
            Security
          </div>
        </div>

        <h1 class="hero-h1">Your investments,<br>fully protected.</h1>

        <p class="hero-subtext">
          Qblockx is built with security at every layer. From account creation to every transaction — your funds and personal data are guarded by bank-grade technology and 24/7 monitoring.
        </p>

        <div class="hero-actions">
          <a href="/register" class="btn-primary">
            Open Account <i class="ph ph-arrow-right" aria-hidden="true"></i>
          </a>
          <a href="/help" class="btn-outline-white">
            Security FAQs
          </a>
        </div>

        <!-- Stats card -->
        <div class="hero-stats-card" data-appear>
          <div class="hero-stat">
            <span class="hero-stat-value text-gradient-blue">256-bit</span>
            <span class="hero-stat-label">AES Encryption</span>
          </div>
          <div class="hero-stat">
            <span class="hero-stat-value text-gradient-blue-rev">24/7</span>
            <span class="hero-stat-label">Fraud Monitoring</span>
          </div>
          <div class="hero-stat">
            <span class="hero-stat-value text-gradient-blue">KYC+</span>
            <span class="hero-stat-label">Identity Verified</span>
          </div>
        </div>

      </div>
    </div>
  </div>


  <!-- ── 2. Core Security Features ────────────────────────────────── -->
  <section class="section" role="region" aria-labelledby="security-features-title">
    <div class="container">

      <div class="section-header" data-appear>
        <span class="section-label">SECURITY</span>
        <h2 id="security-features-title" class="section-title">
          Bank-grade protection at every layer
        </h2>
        <p class="section-subtitle">
          Every component of the Qblockx platform is designed with security-first principles.
        </p>
      </div>

      <div class="feature-grid" data-appear>

        <div class="feature-outer" style="flex-direction:column;">
          <div class="feature-inner">
            <div class="feature-icon">
              <i class="ph ph-lock-key" aria-hidden="true"></i>
            </div>
            <h3 class="feature-title">AES-256 Encryption</h3>
            <p class="feature-body">
              All data — in transit and at rest — is encrypted using AES-256, the same standard used by financial institutions and government agencies worldwide.
            </p>
          </div>
        </div>

        <div class="feature-outer" style="flex-direction:column;">
          <div class="feature-inner">
            <div class="feature-icon">
              <i class="ph ph-shield-check" aria-hidden="true"></i>
            </div>
            <h3 class="feature-title">Two-Factor Authentication</h3>
            <p class="feature-body">
              Protect your login with 2FA via an authenticator app. Even if your password is compromised, your account and investments remain secure.
            </p>
          </div>
        </div>

        <div class="feature-outer" style="flex-direction:column;">
          <div class="feature-inner">
            <div class="feature-icon">
              <i class="ph ph-eye" aria-hidden="true"></i>
            </div>
            <h3 class="feature-title">24/7 Fraud Monitoring</h3>
            <p class="feature-body">
              Our systems monitor all account activity around the clock. Suspicious login attempts or unusual transactions trigger immediate alerts and protective action.
            </p>
          </div>
        </div>

        <div class="feature-outer" style="flex-direction:column;">
          <div class="feature-inner">
            <div class="feature-icon">
              <i class="ph ph-certificate" aria-hidden="true"></i>
            </div>
            <h3 class="feature-title">KYC / AML Compliance</h3>
            <p class="feature-body">
              Full Know Your Customer and Anti-Money Laundering compliance ensures only verified, legitimate users can access and transact on the platform.
            </p>
          </div>
        </div>

        <div class="feature-outer" style="flex-direction:column;">
          <div class="feature-inner">
            <div class="feature-icon">
              <i class="ph ph-database" aria-hidden="true"></i>
            </div>
            <h3 class="feature-title">Secure Infrastructure</h3>
            <p class="feature-body">
              Data is stored in hardened, access-controlled environments with regular security audits, automated backups, and zero single points of failure.
            </p>
          </div>
        </div>

        <div class="feature-outer" style="flex-direction:column;">
          <div class="feature-inner">
            <div class="feature-icon">
              <i class="ph ph-user-check" aria-hidden="true"></i>
            </div>
            <h3 class="feature-title">Identity Verification</h3>
            <p class="feature-body">
              Multi-layer identity verification ensures only the rightful account holder can initiate investments, withdrawals, and account changes.
            </p>
          </div>
        </div>

      </div>

    </div>
  </section>


  <!-- ── 3. Commitments — dark panel ──────────────────────────────── -->
  <section style="background:var(--color-bg); padding:0 0 var(--space-8);"
           role="region" aria-labelledby="commitments-title">
    <div class="section-dark">
      <div class="dark-section-inner">

        <div style="text-align:center; margin-bottom:var(--space-12);" data-appear>
          <span class="section-label" style="color:rgba(211,216,233,0.70);">OUR COMMITMENTS</span>
          <h2 id="commitments-title" class="section-title" style="color:#FFFFFF; margin-top:var(--space-3);">
            Security is not a feature — it's the foundation
          </h2>
        </div>

        <div class="how-steps" data-appear>

          <div class="how-step">
            <div class="how-step-num" aria-hidden="true"
                 style="background:rgba(34,98,255,0.20); color:#7BA4FF; box-shadow:0 0 0 8px rgba(34,98,255,0.06);">01</div>
            <h3 class="how-step-title" style="color:#FFFFFF;">Zero Tolerance for Breaches</h3>
            <p class="how-step-body" style="color:rgba(211,216,233,0.70);">
              We conduct regular penetration testing and security reviews to identify and close vulnerabilities before they can be exploited.
            </p>
          </div>

          <div class="how-step">
            <div class="how-step-num" aria-hidden="true"
                 style="background:rgba(34,98,255,0.20); color:#7BA4FF; box-shadow:0 0 0 8px rgba(34,98,255,0.06);">02</div>
            <h3 class="how-step-title" style="color:#FFFFFF;">Data Minimisation</h3>
            <p class="how-step-body" style="color:rgba(211,216,233,0.70);">
              We only collect the data we need to operate your account. We never sell or share your personal information with third parties.
            </p>
          </div>

          <div class="how-step">
            <div class="how-step-num" aria-hidden="true"
                 style="background:rgba(34,98,255,0.20); color:#7BA4FF; box-shadow:0 0 0 8px rgba(34,98,255,0.06);">03</div>
            <h3 class="how-step-title" style="color:#FFFFFF;">Incident Response</h3>
            <p class="how-step-body" style="color:rgba(211,216,233,0.70);">
              In the unlikely event of a security incident, our rapid response protocol contains, investigates, and notifies affected users promptly.
            </p>
          </div>

        </div>

      </div>
    </div>
  </section>


  <!-- ── 4. CTA ────────────────────────────────────────────────────── -->
  <section class="section" role="region" aria-labelledby="security-cta-title">
    <div class="container" style="text-align:center;" data-appear>
      <span class="section-label">INVEST WITH CONFIDENCE</span>
      <h2 id="security-cta-title" class="section-title" style="margin:var(--space-3) 0 var(--space-5);">
        Your security is our priority
      </h2>
      <p class="section-subtitle" style="margin-bottom:var(--space-8); max-width:480px; margin-inline:auto;">
        Open a Qblockx account and invest with the confidence that your funds and data are protected at every layer.
      </p>
      <div class="cta-actions">
        <a href="/register" class="btn-primary">
          Open Account <i class="ph ph-arrow-right" aria-hidden="true"></i>
        </a>
        <a href="/help" class="btn-outline">Security FAQs</a>
      </div>
    </div>
  </section>

</main>

<?php require_once '../../includes/footer.php'; ?>
</body>
</html>

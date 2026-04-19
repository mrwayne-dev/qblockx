<?php
/**
 * Project: Qblockx
 * Page: Contact
 */
$pageTitle       = 'Contact';
$pageDescription = 'Get in touch with Qblockx. Our team is available to assist with account questions, investment enquiries, and support.';
$pageKeywords    = 'Qblockx contact, support, help, investment enquiry';
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
            <i class="ph ph-headset" aria-hidden="true" style="margin-right:6px;"></i>
            Support
          </div>
        </div>

        <h1 class="hero-h1">Get in touch.</h1>

        <p class="hero-subtext">
          Have a question about your account or a plan? Our team is available 24/7 — drop us a message and we'll get back to you promptly.
        </p>

      </div>
    </div>
  </div>


  <!-- ── 2. Contact Section ───────────────────────────────────────── -->
  <section class="section" role="region" aria-labelledby="contact-title">
    <div class="container">

      <div class="contact-section" data-appear>

        <!-- Left: Form -->
        <div>
          <div style="margin-bottom:var(--space-8);">
            <span class="section-label">CONTACT FORM</span>
            <h2 id="contact-title" class="section-title section-title--sm" style="margin-top:var(--space-3); margin-bottom:var(--space-4);">
              Tell us how we can help
            </h2>
            <p class="section-subtitle">
              Please provide as much detail as possible so we can direct your request to the right team.
            </p>
          </div>

          <?php if (!empty($_GET['success'])): ?>
            <div class="auth-msg auth-msg--success show" role="alert" style="margin-bottom:var(--space-5);">
              <i class="ph ph-check-circle" aria-hidden="true"></i>
              Your message has been received. We'll be in touch shortly.
            </div>
          <?php elseif (!empty($_GET['error'])): ?>
            <div class="auth-msg auth-msg--error show" role="alert" style="margin-bottom:var(--space-5);">
              <i class="ph ph-warning-circle" aria-hidden="true"></i>
              <?php echo $_GET['error'] === 'missing_fields' ? 'Please fill in all required fields.' : 'Something went wrong. Please try again or email us directly.'; ?>
            </div>
          <?php endif; ?>

          <form action="/api/utilities/contact.php" method="POST"
                style="display:flex; flex-direction:column; gap:var(--space-5);">

            <div style="display:flex; gap:var(--space-4);">
              <div class="form-group" style="flex:1;">
                <label for="first-name">First name <span aria-hidden="true">*</span></label>
                <input type="text" id="first-name" name="first_name" required
                       placeholder="John" autocomplete="given-name">
              </div>
              <div class="form-group" style="flex:1;">
                <label for="last-name">Last name <span aria-hidden="true">*</span></label>
                <input type="text" id="last-name" name="last_name" required
                       placeholder="Smith" autocomplete="family-name">
              </div>
            </div>

            <div class="form-group">
              <label for="email">Email address <span aria-hidden="true">*</span></label>
              <input type="email" id="email" name="email" required
                     placeholder="you@example.com" autocomplete="email">
            </div>

            <div class="form-group">
              <label for="problem-type">What do you need help with? <span aria-hidden="true">*</span></label>
              <select id="problem-type" name="problem_type" required>
                <option value="">Select a topic</option>
                <option value="account-access">I can't access my account</option>
                <option value="investment-query">Investment plan query</option>
                <option value="withdrawal">Withdrawal or deposit issue</option>
                <option value="payment-issue">Payment or transaction issue</option>
                <option value="data-correction">Incorrect or missing information</option>
                <option value="security">Security concern</option>
                <option value="other">Other</option>
              </select>
            </div>

            <div class="form-group">
              <label for="description">Describe the situation <span aria-hidden="true">*</span></label>
              <textarea id="description" name="description" rows="5" required
                placeholder="Include any details that can help us better understand your experience."></textarea>
            </div>

            <button type="submit" class="btn-primary" style="justify-content:center;">
              <i class="ph ph-paper-plane-tilt" aria-hidden="true"></i>
              Send Message
            </button>

          </form>
        </div>

        <!-- Right: Contact Details -->
        <div class="contact-details">

          <div style="margin-bottom:var(--space-6);">
            <span class="section-label">REACH US</span>
            <h3 class="section-title section-title--sm" style="font-size:22px; margin-top:var(--space-3);">
              Direct contact
            </h3>
          </div>

          <div class="contact-card">
            <div class="contact-card-icon">
              <i class="ph ph-map-pin" aria-hidden="true"></i>
            </div>
            <div>
              <p class="contact-card-label">Office</p>
              <p class="contact-card-value">36 Springfield Road, Guildford GU52 0DM, United Kingdom</p>
            </div>
          </div>

          <div class="contact-card">
            <div class="contact-card-icon">
              <i class="ph ph-envelope" aria-hidden="true"></i>
            </div>
            <div>
              <p class="contact-card-label">Email</p>
              <p class="contact-card-value">
                <a href="mailto:support@qblockx.com" style="color:var(--color-accent);">support@qblockx.com</a>
              </p>
            </div>
          </div>

          <div class="contact-card">
            <div class="contact-card-icon">
              <i class="ph ph-clock" aria-hidden="true"></i>
            </div>
            <div>
              <p class="contact-card-label">Response Time</p>
              <p class="contact-card-value">Available 24/7 — typically within 4 hours</p>
            </div>
          </div>

          <div class="contact-card">
            <div class="contact-card-icon">
              <i class="ph ph-question" aria-hidden="true"></i>
            </div>
            <div>
              <p class="contact-card-label">Help Centre</p>
              <p class="contact-card-value">
                <a href="/help" style="color:var(--color-accent);">Browse our FAQ</a> for quick answers to common questions.
              </p>
            </div>
          </div>

        </div>

      </div>
    </div>
  </section>

</main>

<?php require_once '../../includes/footer.php'; ?>
</body>
</html>

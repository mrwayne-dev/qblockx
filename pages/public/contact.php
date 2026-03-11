<?php
/**
 * Project: arqoracapital
 * Page: Contact
 */
$pageTitle       = 'Contact';
$pageDescription = 'Get in touch with ArqoraCapital. We\'re available 24/7 to assist with account questions, payment issues, and investment enquiries.';
require_once '../../includes/head.php';
?>

<?php require_once '../../includes/header.php'; ?>

<main>

  <!-- ── Hero ──────────────────────────────────────────────────── -->
  <section class="hero" role="region" aria-label="Contact hero">
    <img
      src="/assets/images/background/arqorabgimage.png"
      alt=""
      class="hero-bg"
      aria-hidden="true"
      draggable="false"
    >

    <div class="hero-container" data-appear>
      <span class="hero-tag">Support</span>
      <h1 class="hero-heading">Get in touch</h1>
      <p class="hero-subtext">
        If you have any questions or would like to find out more about our services,
        please get in touch. Our team is available 24/7.
      </p>
    </div>
  </section>

  <!-- ── Contact Form ───────────────────────────────────────────── -->
  <section class="support-form-section section" role="region" aria-labelledby="contact-form-title">
    <div class="support-form-container container" data-appear>
      <div class="section-header">
        <span class="section-label">CONTACT</span>
        <h2 id="contact-form-title" class="section-title">Tell us how we can help</h2>
        <p class="section-subtitle">
          Please provide as much detail as possible so we can direct your request to the right team.
        </p>
      </div>

      <?php if (!empty($_GET['success'])): ?>
        <div class="alert alert-success glass" role="alert">
          <i class="ph ph-check-circle" aria-hidden="true"></i>
          Your message has been received. We'll be in touch shortly.
        </div>
      <?php endif; ?>

      <form class="support-form" action="/api/utilities/contact.php" method="POST">

        <div class="form-row">
          <div class="form-group">
            <label for="first-name">First name <span aria-hidden="true">*</span></label>
            <input type="text" id="first-name" name="first_name" required
                   placeholder="John" autocomplete="given-name">
          </div>
          <div class="form-group">
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
            <option value="">Select problem type</option>
            <option value="account-access">I can't access my account</option>
            <option value="payment-issue">Payment or transaction issue</option>
            <option value="data-correction">Incorrect or missing information</option>
            <option value="password-issue">Password issue</option>
            <option value="security">Security concern</option>
            <option value="other">Other</option>
          </select>
        </div>

        <div class="form-group">
          <label for="description">Describe the situation <span aria-hidden="true">*</span></label>
          <textarea id="description" name="description" rows="5" required
            placeholder="Include any details that can help us better understand your experience."></textarea>
        </div>

        <button type="submit" class="btn-primary full-width">
          <i class="ph ph-paper-plane-tilt" aria-hidden="true"></i>
          Send Message
        </button>

      </form>
    </div>
  </section>

  <!-- ── Contact Details ────────────────────────────────────────── -->
  <section class="reach-out section" role="region" aria-labelledby="contact-details-title">
    <div class="reach-out-container container" data-appear>
      <div class="reach-out-cards">

        <div class="reach-out-card glass">
          <i class="ph ph-map-pin reach-out-icon" aria-hidden="true"></i>
          <h3 id="contact-details-title">Our Office</h3>
          <p>36 Springfield Road, Guildford GU52 0DM, United Kingdom</p>
        </div>

        <div class="reach-out-card glass">
          <i class="ph ph-envelope reach-out-icon" aria-hidden="true"></i>
          <h3>Email</h3>
          <p><a href="mailto:support@arqoracapital.com" class="contact-link">support@arqoracapital.com</a></p>
        </div>

      </div>
    </div>
  </section>

</main>

<?php require_once '../../includes/footer.php'; ?>
</body>
</html>

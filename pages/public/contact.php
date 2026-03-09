<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Standard Meta -->
    <meta charset="UTF-8">
    <meta name="description" content="Growth Mining is one of the fastest growing investment platforms in the world.">
    <meta name="keywords" content="GrowthMining, Mining, Trade, Mining, Miningtrade, Alphamining, Alphatrade, alpha mining, alpha trade, mining trade, invest, growth, investment, platform, crypto, cryptocurrency, bitcoin, ethereum, altcoin">
    <meta name="author" content="GrowthMining">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Site Properties -->
    <title>Contact - Growth Mining</title>

    <!-- Important Preload -->
    <link rel="preload" href="assets/css/style.css" as="style">

    <!-- Font Preload -->
    <link rel="preload" href="assets/fonts/BricolageGrotesque-Regular.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/fonts/BricolageGrotesque-Bold.woff2" as="font" type="font/woff2" crossorigin>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="assets/favicon/favicon.svg" />
    <link rel="shortcut icon" href="assets/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="GrowthMining" />
    <link rel="manifest" href="assets/favicon/site.webmanifest" />
</head>
<body>
     <header class="header" data-header>
  <div class="container">
    <a href="index.php" class="logo">
      <img src="assets/images/logogreen.png" loading="lazy" width="60" height="60" alt="Growth Mining logo">
    </a>
    <nav class="navbar" data-navbar>
      <ul class="navbar-list">
        <li class="navbar-item">
          <a href="solutions.html" class="navbar-link active" aria-label="View solutions">Solutions</a>
        </li>
        <li class="navbar-item">
          <a href="company.html" class="navbar-link" aria-label="About the company">Company</a>
        </li>
        <li class="navbar-item">
          <a href="contact.html" class="navbar-link" aria-label="Contact us">Contact Us</a>
        </li>
        <li class="navbar-item">
          <a href="about.html" class="navbar-link" aria-label="About us">About Us</a>
        </li>
      </ul>

      <div class="mobile-navbar">
        <!-- Duplicate list for mobile toggle -->
        <ul class="navbar-list">
          <li class="navbar-item">
            <a href="solutions.html" class="navbar-link active" aria-label="View solutions">Solutions</a>
          </li>
          <li class="navbar-item">
            <a href="company.html" class="navbar-link" aria-label="About the company">Company</a>
          </li>
          <li class="navbar-item">
            <a href="contact.html" class="navbar-link" aria-label="Contact us">Contact Us</a>
          </li>
          <li class="navbar-item">
            <a href="about.html" class="navbar-link" aria-label="About us">About Us</a>
          </li>
          <li class="navbar-item">
            <a href="auth.php" class="navbar-link" aria-label="Get Started">Get Started</a>
          </li>
        </ul>
      </div>
    </nav>
    <a href="auth.php" class="btn btn-outline get-started" aria-label="Get started">Get Started</a>
    <button class="nav-toggle-btn" aria-label="Toggle menu" data-nav-toggler>
      <span class="line line-1"></span>
      <span class="line line-2"></span>
      <span class="line line-3"></span>
    </button>
  </div>
</header>

    <main>
        <!-- Hero Section -->
        <section class="hero" role="region" aria-label="Hero Section">
            <div class="hero-container" data-appear>
                <div class="hero-content">
                    <h1>Get in touch!</h1>
                    <p>If you have any questions or you'd like to find out more about out services, please get in touch.</p>
                    <div class="hero-buttons">
                        <!-- <a href="#contact" class="btn" aria-label="Get started">Get Started</a> -->
                        <!-- <a href="#more" class="btn-secondary" aria-label="Learn more">Learn More</a> -->
                    </div>
                </div>
                <div class="hero-image full-width-image">
                    <img src="assets/images/contact.png" alt="Mobile app screenshot" class="contact-image">
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="support-form-section">
  <div class="support-form-container" data-appear>
    <h2 class="section-title">Tell us how we can help you</h2>
    <p class="section-subtitle">
      Please provide as much detail as possible so we can direct your request to the right team.
    </p>

    <form class="support-form" action="backend/contact.php" method="POST">

      <div class="form-group">
        <label for="first-name">First name*</label>
        <input type="text" id="first-name" name="first-name" required />
      </div>

      <div class="form-group">
        <label for="last-name">Last name*</label>
        <input type="text" id="last-name" name="last-name" required />
      </div>

      <div class="form-group">
        <label for="email">Email connected to your Growth Mining account*</label>
        <input type="email" id="email" name="email" required />
      </div>

      <div class="form-group">
        <label for="problem-type">What do you need help with?*</label>
        <select id="problem-type" name="problem-type" required>
          <option value="">Select problem type</option>
          <option value="account-access">I can't access my account</option>
          <option value="payment-issue">Payment or transaction issue</option>
          <option value="data-correction">Incorrect or missing information</option>
          <option value="integration">Password issue</option>
          <option value="security">Security concern</option>
          <option value="other">Other</option>
        </select>
      </div>

      <div class="form-group">
        <label for="description">Describe the situation*</label>
        <textarea id="description" name="description" rows="4" required
          placeholder="Include any details that can help us better understand your experience."></textarea>
      </div>

      <button type="submit" class="btn btn-secondary full-width">Submit</button>

    </form>
  </div>
</section>

<section class="reach-out" role="region" aria-label="reach-out Section">
            <div class="reach-out-container" data-appear>
                <div class="reach-out-content">
                    <h1>Contact Details</h1>
                    <p>Located at <b>36 Springfield Road, Guildford GU52 ODM, United Kingdom</b>, you can reach Growth Mining by phone at <b><a class="contact-link" href="tel:+447984647562">+44-7984647562</a></b> or via email at <b><a href="mailto:support@growthmining.org" class="contact-link">support@growthmining.org</a></b></p>
                </div>
            </div>
        </section>



        
     

        
    </main>

    <!-- Footer Section -->
    <footer class="footer" role="region" aria-label="Footer Section">
        <div class="footer-bottom">
            <p class="footer-copyright"><b>© 2025 Growthmining Inc. All rights reserved.</b></p>
        </div>
    </footer>

    <!-- Disclosure Section -->
    <section class="disclosure" role="region" aria-label="Disclosure Section">
        <section class="disclosure" role="region" aria-label="Disclosure Section">
    <div class="disclosure-container">
        <p class="disclosure-text">
            Growth Mining Inc. (“Growth Mining”), founded in 2016, is a leading technology platform company headquartered in the United Kingdom. We provide an integrated platform connecting companies and individuals with Growth Mining Investment LLC (“Growth Mining Investment”), an SEC-registered investment adviser, and Growth Mining Mining LLC (“Growth Mining Mining”), a FINRA-registered broker-dealer and member of SIPC. As accredited subsidiaries, Growth Mining Investment and Growth Mining Mining operate under Growth Mining’s innovative vision. Growth Mining itself focuses on delivering cutting-edge technology services to enhance your investment journey.
        </p>
        <p class="disclosure-text">
            Recognized as a top investment platform for nearly a decade, neither Growth Mining Investment nor Growth Mining Mining, nor any of their affiliates, is a bank. We are committed to empowering your financial growth with tailored solutions. We encourage you to review your investment objectives and the associated fees and expenses to maximize your experience.
        </p>
        <p class="disclosure-text">
            This website offers an inspiring overview of Growth Mining, designed for informational purposes to showcase our innovative approach. It is not intended as investment, accounting, tax, or legal advice. By using this website, you agree to our <a href="company.html">Terms of Use</a> and <a href="company.html">Privacy Policy</a>, embracing the opportunities we provide.
        </p>
        <p class="disclosure-text">
            Growth Mining and its affiliates proudly serve a global community, ensuring our materials and services are tailored to various jurisdictions where available. While transactions, securities products, instruments, or services may not suit all investors or regions, those accessing this website do so with the freedom to explore, taking responsibility for compliance with local laws and regulations.
        </p>
        <p class="disclosure-text">
            All designs and performance figures on this site are presented for illustrative purposes, reflecting our commitment to transparency and innovation in showcasing Growth Mining’s potential.
        </p>
    </div>
</section>

    <!-- Floating Translation Widget -->
    <div id="google-translate-element" class="translate-floating"></div>

    <script>
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success')) {
        alert("✅ Your issue has been received! We'll be in touch shortly.");
    }
</script>

    <script src="assets/js/script.js" defer></script>

    <!-- IonIcons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
<?php
/**
 * Project: arqoracapital
 * Include: head.php — shared <head> + opening <body>
 * Set $pageTitle before requiring this file.
 * Set $pageDescription, $pageKeywords to override defaults.
 */
$pageTitle       = $pageTitle       ?? 'ArqoraCapital';
$pageDescription = $pageDescription ?? 'ArqoraCapital is a globally trusted crypto investment platform. Earn daily returns through automated investment contracts, with full transparency and security.';
$pageKeywords    = $pageKeywords    ?? 'crypto investment, daily returns, ArqoraCapital, cryptocurrency platform, investment contracts, passive income';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- SEO -->
  <meta name="description"  content="<?= htmlspecialchars($pageDescription) ?>">
  <meta name="keywords"     content="<?= htmlspecialchars($pageKeywords) ?>">
  <meta name="author"       content="ArqoraCapital">
  <meta name="robots"       content="index, follow">

  <!-- Open Graph -->
  <meta property="og:type"        content="website">
  <meta property="og:site_name"   content="ArqoraCapital">
  <meta property="og:title"       content="<?= htmlspecialchars($pageTitle) ?> — ArqoraCapital">
  <meta property="og:description" content="<?= htmlspecialchars($pageDescription) ?>">

  <title><?= htmlspecialchars($pageTitle) ?> — ArqoraCapital</title>

  <!-- Preload critical assets -->
  <link rel="preload" href="/assets/css/main.css"                   as="style">
  <link rel="preload" href="/assets/fonts/DMSans-Regular.woff2"     as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="/assets/fonts/DMSans-Bold.woff2"        as="font" type="font/woff2" crossorigin>

  <!-- Styles -->
  <link rel="stylesheet" href="/assets/css/main.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
  <link rel="stylesheet" href="/assets/icons/style.css">

  <!-- LightRays WebGL -->
  <script src="/assets/js/light-rays.js" defer></script>

  <!-- Main JS -->
  <script src="/assets/js/main.js" defer></script>

  <!-- Favicon -->
  <link rel="icon"             type="image/x-icon"   href="/assets/favicon/favicon.ico">
  <link rel="apple-touch-icon" sizes="180x180"        href="/assets/favicon/apple-touch-icon.png">
  <link rel="icon"             type="image/png" sizes="32x32" href="/assets/favicon/favicon-32x32.png">
  <link rel="icon"             type="image/png" sizes="16x16" href="/assets/favicon/favicon-16x16.png">

  <!-- Page-specific stylesheets (set $extraHeadLinks before requiring this file) -->
  <?php foreach ($extraHeadLinks ?? [] as $href): ?>
  <link rel="stylesheet" href="<?= htmlspecialchars($href) ?>">
  <?php endforeach; ?>

  <!-- Page-specific deferred scripts (set $extraHeadScripts before requiring this file) -->
  <?php foreach ($extraHeadScripts ?? [] as $src): ?>
  <script src="<?= htmlspecialchars($src) ?>" defer></script>
  <?php endforeach; ?>
</head>
<body class="<?= htmlspecialchars($bodyClass ?? '') ?>">

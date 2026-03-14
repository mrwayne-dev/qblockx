<?php
/**
 * Project: crestvalebank
 * Include: head.php — shared <head> + opening <body>
 * Set $pageTitle before requiring this file.
 * Set $pageDescription, $pageKeywords to override defaults.
 */
$pageTitle       = $pageTitle       ?? 'CrestVale Bank';
$pageDescription = $pageDescription ?? 'CrestVale Bank is a modern fintech banking platform. Grow your money with goal-based savings, fixed deposits, flexible loans, and instant transfers.';
$pageKeywords    = $pageKeywords    ?? 'fintech bank, savings plans, fixed deposits, loans, transfers, CrestVale Bank, online banking, banking platform';
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
  <meta name="author"       content="CrestVale Bank">
  <meta name="robots"       content="index, follow">

  <!-- Open Graph -->
  <meta property="og:type"        content="website">
  <meta property="og:site_name"   content="CrestVale Bank">
  <meta property="og:title"       content="<?= htmlspecialchars($pageTitle) ?> — CrestVale Bank">
  <meta property="og:description" content="<?= htmlspecialchars($pageDescription) ?>">

  <title><?= htmlspecialchars($pageTitle) ?> — CrestVale Bank</title>

  <!-- Preload critical assets -->
  <link rel="preload" href="/assets/css/main.css"                   as="style">
  <link rel="preload" href="/assets/fonts/Recoleta-RegularDEMO.woff2" as="font" type="font/woff2" crossorigin>

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

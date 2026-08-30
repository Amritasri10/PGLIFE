<?php
/**
 * SEO Head Include
 * Variables to set before including this file:
 *   $seo_title       - Page title (max ~60 chars)
 *   $seo_description - Meta description (max ~155 chars)
 *   $seo_keywords    - Meta keywords (optional)
 *   $seo_url         - Canonical URL (full URL)
 *   $seo_image       - OG image full URL (optional, defaults to logo)
 *   $seo_type        - OG type: "website" or "article" (default: website)
 */
require_once __DIR__ . "/config.php";

$seo_title       = isset($seo_title)       ? $seo_title       : "PG Life | Find Your Perfect Paying Guest Home";
$seo_description = isset($seo_description) ? $seo_description : "PG Life helps students and working professionals find verified, affordable paying guest accommodations near colleges and workplaces across India.";
$seo_keywords    = isset($seo_keywords)    ? $seo_keywords    : "PG, paying guest, PG accommodation, student PG, PG near college, affordable PG India, PG rooms Delhi Mumbai Bengaluru Hyderabad";
$seo_url         = isset($seo_url)         ? $seo_url         : BASE_URL . "/index.php";
$seo_image       = isset($seo_image)       ? $seo_image       : BASE_URL . "/img/logo.png";
$seo_type        = isset($seo_type)        ? $seo_type        : "website";
$seo_site_name   = "PG Life";
?>
<!-- Primary SEO -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo htmlspecialchars($seo_title); ?></title>
<meta name="description" content="<?php echo htmlspecialchars($seo_description); ?>">
<meta name="keywords" content="<?php echo htmlspecialchars($seo_keywords); ?>">
<meta name="robots" content="index, follow">
<meta name="author" content="PG Life">
<link rel="canonical" href="<?php echo htmlspecialchars($seo_url); ?>">

<!-- Open Graph (Facebook, WhatsApp, LinkedIn preview) -->
<meta property="og:type"        content="<?php echo htmlspecialchars($seo_type); ?>">
<meta property="og:title"       content="<?php echo htmlspecialchars($seo_title); ?>">
<meta property="og:description" content="<?php echo htmlspecialchars($seo_description); ?>">
<meta property="og:url"         content="<?php echo htmlspecialchars($seo_url); ?>">
<meta property="og:image"       content="<?php echo htmlspecialchars($seo_image); ?>">
<meta property="og:site_name"   content="<?php echo htmlspecialchars($seo_site_name); ?>">
<meta property="og:locale"      content="en_IN">

<!-- Twitter Card -->
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="<?php echo htmlspecialchars($seo_title); ?>">
<meta name="twitter:description" content="<?php echo htmlspecialchars($seo_description); ?>">
<meta name="twitter:image"       content="<?php echo htmlspecialchars($seo_image); ?>">

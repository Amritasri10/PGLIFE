<?php
/**
 * Dynamic XML Sitemap — Google Search Console me submit karo
 * URL: https://yourdomain.free.je/sitemap.php
 */
require_once "./includes/config.php";
require_once "./includes/database_connect_hide_error.php";

header("Content-Type: application/xml; charset=utf-8");
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

  <!-- Static pages -->
  <url>
    <loc><?php echo BASE_URL; ?>/index.php</loc>
    <changefreq>daily</changefreq>
    <priority>1.0</priority>
  </url>
  <url>
    <loc><?php echo BASE_URL; ?>/about.php</loc>
    <changefreq>monthly</changefreq>
    <priority>0.6</priority>
  </url>
  <url>
    <loc><?php echo BASE_URL; ?>/contact.php</loc>
    <changefreq>monthly</changefreq>
    <priority>0.5</priority>
  </url>

  <?php if (isset($con) && $con): ?>

  <!-- City listing pages -->
  <?php
    $cities = mysqli_query($con, "SELECT name FROM cities ORDER BY name");
    while ($cities && $city = mysqli_fetch_assoc($cities)):
  ?>
  <url>
    <loc><?php echo BASE_URL; ?>/property_list.php?city=<?php echo urlencode($city["name"]); ?></loc>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
  <?php endwhile; ?>

  <!-- Individual property pages -->
  <?php
    $props = mysqli_query($con, "SELECT id, updated_at FROM properties ORDER BY id");
    while ($props && $prop = mysqli_fetch_assoc($props)):
      $lastmod = !empty($prop["updated_at"]) ? date("Y-m-d", strtotime($prop["updated_at"])) : date("Y-m-d");
  ?>
  <url>
    <loc><?php echo BASE_URL; ?>/property_detail.php?property_id=<?php echo intval($prop["id"]); ?></loc>
    <lastmod><?php echo $lastmod; ?></lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.9</priority>
  </url>
  <?php endwhile; ?>

  <?php endif; ?>

</urlset>

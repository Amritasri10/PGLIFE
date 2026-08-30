<?php
/**
 * Central config — defines BASE_URL that works on both localhost/PGLIFE and
 * production root domains (InfinityFree, etc.)
 */
if (!defined("BASE_URL")) {
    $protocol = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off") ? "https" : "http";
    $host     = $_SERVER["HTTP_HOST"];

    // If SCRIPT_NAME contains /PGLIFE, we are on localhost sub-folder
    if (strpos($_SERVER["SCRIPT_NAME"], "/PGLIFE") !== false) {
        define("BASE_URL", $protocol . "://" . $host . "/PGLIFE");
    } else {
        // Production: site lives at the domain root
        define("BASE_URL", $protocol . "://" . $host);
    }
}

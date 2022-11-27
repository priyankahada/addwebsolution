<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define( 'WP_USE_THEMES', true );
// Report all errors except E_NOTICE and E_WARNING
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

/** Loads the WordPress Environment and Template */
require __DIR__ . '/wp-blog-header.php';

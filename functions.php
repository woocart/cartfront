<?php
/**
 * Theme functions and definitions.
 *
 * @package cartfront
 * @author WooCart
 * @link https://woocart.com/
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Parent theme name.
 */
$parent_theme    = 'storefront';

/**
 * Child theme name.
 */
$theme_name      = 'cartfront';

/**
 * The version number.
 */
$theme_version   = '1.0.0';

/**
 * URL of the template directory.
 */
$storefront_url  = get_template_directory_uri();

/**
 * Path of the child theme.
 */
$cartfront_path  = get_stylesheet_directory();

/**
 * URL of the child theme.
 */
$cartfront_url   = get_stylesheet_directory_uri();

/**
 * Main class.
 */
require_once $cartfront_path . '/framework/classes/class-cartfront.php';

/**
 * Include theme addons.
 */
require_once $cartfront_path . '/framework/classes/class-cartfront_footer_bar.php';
require_once $cartfront_path . '/framework/classes/class-cartfront_hamburger_menu.php';
require_once $cartfront_path . '/framework/classes/class-cartfront_blog_customiser.php';
require_once $cartfront_path . '/framework/classes/class-cartfront_homepage_control.php';
require_once $cartfront_path . '/framework/classes/class-cartfront_simple_slider.php';
require_once $cartfront_path . '/framework/classes/class-cartfront_link_boxes.php';

/**
 * Other required files.
 */
require_once $cartfront_path . '/framework/include/sanitize.php';
require_once $cartfront_path . '/framework/admin/customizer/re-arrange.php';

/**
 * Initialize theme.
 */
$cartfront = new Cartfront();

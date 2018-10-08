<?php
/**
 * Theme functions and definitions.
 *
 * @package cartfront
 * @author WooCart
 * @link https://woocart.com/
 */

namespace Niteo\WooCart\CartFront {

	if ( ! defined( 'ABSPATH' ) ) {
	    exit;
	}

	global $cartfront_path;

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
	$theme_version   = '1.1.0';

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
	 * Composer Autoloader.
	 */
	require_once __DIR__ . '/vendor/autoload.php';

	/**
	 * Initialize theme.
	 */
	$cartfront = new Cartfront();

}

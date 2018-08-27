<?php
/**
 * Basic setup for the theme.
 *
 * @package cartfront
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'cartfront_setup' ) ) :
function cartfront_setup() {
	global $theme_name;

	/**
	 * Load translations for the theme from the /langs directory.
	 */
	load_theme_textdomain( 'cartfront', get_template_directory() . '/framework/langs' );

	/**
	 * Some more image sizes we will need.
	 */
	add_image_size( $theme_name . '-medium', 600, 400, true );
	add_image_size( $theme_name . '-square', 600, 600, true );
	add_image_size( $theme_name . '-full', 1200, 9999, false );
}
add_action( 'after_setup_theme', 'cartfront_setup' );
endif;
<?php
/**
 * Main class for the theme.
 *
 * @package cartfront
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Cartfront' ) ) :
class Cartfront {

    /**
     * Constructor function.
     *
     * @access  public
     * @since   1.0.0
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( &$this, 'scripts' ), PHP_INT_MAX );
        add_action( 'customize_preview_init', array( &$this, 'customize_preview_js' ) );

        /**
         * Initialize classes.
         */
        $footer_bar = new Cartfront_Footer_Bar();
    }

    /**
     * Add styles & scripts for the theme.
     */
    public function scripts() {
        global $theme_name, $cartfront_url;

        wp_enqueue_style( $theme_name . '-public', $cartfront_url . '/framework/css/public.css' );
    }

    /**
     * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
     *
     * @access  public
     * @since   1.0.0
     */
    public function customize_preview_js() {
        global $theme_name, $theme_version, $cartfront_url;

        wp_enqueue_script( $theme_name . '-customizer', $cartfront_url . '/framework/js/customizer.js', array( 'customize-preview' ), $theme_version, true );
    }

}
endif;

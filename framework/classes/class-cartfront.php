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

        // Hide the 'More' section in the customizer.
        add_filter( 'storefront_customizer_more', '__return_false' );

        /**
         * Initialize classes.
         */
        $hamburger_menu = new Cartfront_Hamburger_Menu();
    }

    /**
     * Add styles & scripts for the theme.
     */
    public function scripts() {
        global $theme_name, $theme_version, $cartfront_url;

        wp_enqueue_style( $theme_name . '-public', $cartfront_url . '/framework/css/public.css' );
        wp_enqueue_script( $theme_name . '-public', $cartfront_url . '/framework/js/public.js', array( 'jquery' ), $theme_version, true );

        /**
         * Localization
         */
        $localization = array(
            'close' => esc_html__( 'Close', 'cartfront' )
        );

        wp_localize_script( $theme_name . '-public', 'cartfront_localize', $localization );
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

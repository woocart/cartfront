<?php
/**
 * Hamburger menu class for the theme.
 *
 * @package cartfront
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Cartfront_Hamburger_Menu' ) ) :
class Cartfront_Hamburger_Menu {

    /**
     * Constructor function.
     *
     * @access  public
     * @since   1.0.0
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( &$this, 'add_styles' ), PHP_INT_MAX );
        add_filter( 'body_class', array( $this, 'body_class' ) );
    }

    /**
     * Add custom CSS styles.
     */
    public function add_styles() {
        global $theme_name;

        $background_color    = sanitize_text_field( get_theme_mod( 'storefront_header_background_color', apply_filters( 'storefront_default_header_background_color', '#2c2d33' ) ) );
        $link_color          = sanitize_text_field( get_theme_mod( 'storefront_header_link_color', apply_filters( 'storefront_default_header_link_color', '#cccccc' ) ) );

        $style = '
            @media screen and (max-width: 768px) {
                .menu-toggle {
                    color: ' . $link_color . ';
                }

                .menu-toggle:hover {
                    color: ' . storefront_adjust_color_brightness( $link_color, -100 ) . ';
                }

                .main-navigation div.menu,
                .main-navigation .handheld-navigation {
                    background-color: ' . $background_color . ';
                }

                .main-navigation ul li a,
                ul.menu li a,
                .cartfront-hamburger-menu-active .cfhm-close {
                    color: ' . $link_color . ';
                }
            }
        ';

        wp_add_inline_style( $theme_name . '-public', $style );
    }

    /**
     * Adds a class to the <body> element.
     */
    public function body_class( $classes ) {
        $classes[] = 'cartfront-hamburger-menu-active';

        return $classes;
    }

}
endif;

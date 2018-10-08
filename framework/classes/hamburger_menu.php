<?php
/**
 * Hamburger menu class for the theme.
 *
 * @package cartfront
 */

namespace Niteo\WooCart\CartFront {

    use WP_Customize_Control;

    class Hamburger_Menu {

        /**
         * Constructor function.
         *
         * @access  public
         * @since   1.0.0
         */
        public function __construct() {
            add_action( 'wp_enqueue_scripts', array( &$this, 'add_styles' ), PHP_INT_MAX );
            add_action( 'customize_register', array( &$this, 'customize_register' ) );

            if ( get_theme_mod( 'cf_hm_enable' ) ) {
                add_filter( 'body_class', array( $this, 'body_class' ) );
            }
        }

        /**
         * Add custom CSS styles.
         */
        public function add_styles() {
            global $theme_name;

            $background_color    = sanitize_text_field( get_theme_mod( 'storefront_header_background_color', apply_filters( 'storefront_default_header_background_color', '#222222' ) ) );
            $link_color          = sanitize_text_field( get_theme_mod( 'storefront_header_link_color', apply_filters( 'storefront_default_header_link_color', '#dddddd' ) ) );

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
         * Customizer controls and settings.
         *
         * @param WP_Customize_Manager $wp_customize Theme Customizer object.
         */
        public function customize_register( $wp_customize ) {
            /**
             * Add a new section.
             */
            $wp_customize->add_section( 'cf_hm_section', array(
                'title'    => esc_html__( 'Hamburger Menu', 'cartfront' ),
                'priority' => 60
            ) );

            /**
             * Blog archive layout.
             */
            $wp_customize->add_setting( 'cf_hm_enable', array(
                'default'           => false,
                'sanitize_callback' => 'storefront_sanitize_checkbox',
                'transport'         => 'postMessage'
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_hm_enable', array(
                'label'         => esc_attr__( 'Enable Hamburger Menu', 'cartfront' ),
                'description'   => esc_html__( 'Check this box to enable the Hamburger Menu for smaller size devices.', 'cartfront' ),
                'section'       => 'cf_hm_section',
                'settings'      => 'cf_hm_enable',
                'type'          => 'checkbox',
                'priority'      => 10
            ) ) );
        }

        /**
         * Adds a class to the <body> element.
         */
        public function body_class( $classes ) {
            $classes[] = 'cartfront-hamburger-menu-active';

            return $classes;
        }

    }

}

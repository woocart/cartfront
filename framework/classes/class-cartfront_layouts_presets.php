<?php
/**
 * Layouts & presets for the theme.
 *
 * @package cartfront
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Cartfront_Layouts_Presets' ) ) :
class Cartfront_Layouts_Presets {

    /**
     * Constructor function.
     *
     * @access  public
     * @since   1.0.0
     */
    public function __construct() {
        add_action( 'customize_register', array( &$this, 'customize_register' ) );
        add_action( 'get_header', array( &$this, 'presets_header' ) );
    }

    /**
     * Customizer controls and settings
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    public function customize_register( $wp_customize ) {
        /**
         * Add a new section.
         */
        $wp_customize->add_section( 'cf_lp_presets' , array(
            'title'         => esc_html__( 'Cartfront Presets', 'cartfront' ),
            'priority'      => 15,
            'description'   => esc_html__( 'Select preset of your choice for the theme.', 'cartfront' )
        ) );

        /**
         * Presets.
         */
        $wp_customize->add_setting( 'cf_lp_presets', array(
            'default'           => 'default',
            'sanitize_callback' => 'storefront_sanitize_choices'
        ) );

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_lp_presets', array(
            'label'    => esc_attr__( 'Post meta display', 'cartfront' ),
            'section'  => 'cf_lp_presets',
            'settings' => 'cf_lp_presets',
            'type'     => 'select',
            'priority' => 10,
            'choices'  => array(
                'default'   => esc_html__( 'Default Layout', 'cartfront' ),
                'toys'      => esc_html__( 'Toys Store', 'cartfront' )
            )
        ) ) );
    }

    /**
     * Modifications for the presets.
     */

    /**
         * Functions hooked into storefront_header action
         *
         * @hooked storefront_header_container                 - 0
         * @hooked storefront_skip_links                       - 5
         * @hooked storefront_social_icons                     - 10
         * @hooked storefront_site_branding                    - 20
         * @hooked storefront_secondary_navigation             - 30
         * @hooked storefront_product_search                   - 40
         * @hooked storefront_header_container_close           - 41
         * @hooked storefront_primary_navigation_wrapper       - 42
         * @hooked storefront_primary_navigation               - 50
         * @hooked storefront_header_cart                      - 60
         * @hooked storefront_primary_navigation_wrapper_close - 68
         */
    public function presets_header() {
        remove_action( 'storefront_header', 'storefront_secondary_navigation', 30 );
        remove_action( 'storefront_header', 'storefront_product_search', 40 );
        remove_action( 'storefront_header', 'storefront_header_cart', 60 );

        add_action( 'storefront_header', 'storefront_product_search', 30 );
        add_action( 'storefront_header', 'storefront_header_cart', 40 );
    }

}
endif;

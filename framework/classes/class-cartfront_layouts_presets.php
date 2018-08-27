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
         * Layout.
         */
        $wp_customize->add_setting( 'cf_lp_layout', array(
            'default'           => 'default',
            'sanitize_callback' => 'storefront_sanitize_choices'
        ) );

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_lp_layout', array(
            'label'         => esc_html__( 'Select Layout', 'cartfront' ),
            'description'   => esc_html__( 'From the below options, select the one that fits best for your store.', 'cartfront' ),
            'section'       => 'cf_lp_presets',
            'settings'      => 'cf_lp_layout',
            'type'          => 'select',
            'priority'      => 10,
            'choices'       => array(
                'default'   => esc_html__( 'Default Layout', 'cartfront' ),
                'toys'      => esc_html__( 'Toys Store', 'cartfront' )
            )
        ) ) );

        /**
         * Color scheme.
         */
        $wp_customize->add_setting( 'cf_lp_color_scheme', array(
            'default'           => 'default',
            'sanitize_callback' => 'storefront_sanitize_choices'
        ) );

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_lp_color_scheme', array(
            'label'         => esc_html__( 'Select Color Scheme', 'cartfront' ),
            'description'   => esc_html__( 'Changing color scheme will override the settings you have set manually.', 'cartfront' ),
            'section'       => 'cf_lp_presets',
            'settings'      => 'cf_lp_color_scheme',
            'type'          => 'select',
            'priority'      => 15,
            'choices'       => array(
                'default'   => esc_html__( 'Default Color Scheme', 'cartfront' ),
                'toys'      => esc_html__( 'Toys Store Color Scheme', 'cartfront' )
            )
        ) ) );
    }

    /**
     * Modifications for the presets.
     */
    public function presets_header() {
        
    }

}
endif;

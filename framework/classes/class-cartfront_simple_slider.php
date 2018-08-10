<?php
/**
 * Simple slider class for the theme.
 *
 * @package cartfront
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Cartfront_Simple_Slider' ) ) :
class Cartfront_Simple_Slider {

    /**
     * Constructor function.
     *
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function __construct() {
        add_action( 'customize_register', array( &$this, 'customize_register' ) );
    }

    /**
     * Customizer controls and settings
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    public function customize_register( $wp_customize ) {
        /**
         * Add the panel.
         */
        $wp_customize->add_panel( 'cf_ss_panel', array(
            'priority'          => 60,
            'capability'        => 'edit_theme_options',
            'theme_supports'    => '',
            'title'             => esc_html__( 'Slider', 'cartfront' ),
            'description'       => esc_html__( 'Configure the slider to be shown on the homepage.', 'cartfront' )
        ) );

        /**
         * General section.
         */
        $wp_customize->add_section( 'cf_ss_general' , array(
            'title'         => esc_html__( 'General Settings', 'cartfront' ),
            'priority'      => 10,
            'description'   => esc_html__( 'Configure general settings for the slider section.', 'cartfront' ),
            'panel'         => 'cf_ss_panel'
        ) );

        /**
         * Posts slider section.
         */
        $wp_customize->add_section( 'cf_ss_posts' , array(
            'title'         => esc_html__( 'Posts Slider', 'cartfront' ),
            'priority'      => 20,
            'description'   => esc_html__( 'Configure posts slider settings.', 'cartfront' ),
            'panel'         => 'cf_ss_panel'
        ) );

        /**
         * Products slider section.
         */
        $wp_customize->add_section( 'cf_ss_products' , array(
            'title'         => esc_html__( 'Products Slider', 'cartfront' ),
            'priority'      => 30,
            'description'   => esc_html__( 'Configure products slider settings.', 'cartfront' ),
            'panel'         => 'cf_ss_panel'
        ) );

        /**
         * Custom slider section.
         */
        $wp_customize->add_section( 'cf_ss_custom' , array(
            'title'         => esc_html__( 'Custom Slider', 'cartfront' ),
            'priority'      => 40,
            'description'   => esc_html__( 'Configure custom slider settings.', 'cartfront' ),
            'panel'         => 'cf_ss_panel'
        ) );

        /**
         * Select slider.
         */
        $wp_customize->add_setting( 'cf_ss_choice', array(
            'default'           => 'posts',
            'sanitize_callback' => 'storefront_sanitize_choices'
        ) );

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_ss_choice', array(
            'label'    => esc_attr__( 'Select Slider', 'cartfront' ),
            'section'  => 'cf_ss_general',
            'settings' => 'cf_ss_choice',
            'type'     => 'select',
            'priority' => 10,
            'choices'  => array(
                'posts'     => esc_html__( 'Posts Slider', 'cartfront' ),
                'products'  => esc_html__( 'Products Slider', 'cartfront' ),
                'custom'    => esc_html__( 'Custom Slider', 'cartfront' )
            )
        ) ) );
    }

}
endif;

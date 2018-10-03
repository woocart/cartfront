<?php
/**
 * Re-arrange customizer sections in desired panels.
 *
 * @package cartfront
 */

namespace Niteo\WooCart\CartFront {

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    if ( ! function_exists( 'cartfront_rearrange_sections' ) ) :
    function cartfront_rearrange_sections( $wp_customize ) {
    	/**
    	 * Add wordpress panel.
    	 */
    	$wp_customize->add_panel( 'cf_wordpress_settings', array(
            'priority'          => 10,
            'capability'        => 'edit_theme_options',
            'theme_supports'    => '',
            'title'             => esc_html__( 'WordPress Settings', 'cartfront' ),
            'description'       => esc_html__( 'Configure all basic settings for the WordPress within this section.', 'cartfront' )
        ) );

    	/**
    	 * Add storefront panel.
    	 */
    	$wp_customize->add_panel( 'cf_storefront_settings', array(
            'priority'          => 15,
            'capability'        => 'edit_theme_options',
            'theme_supports'    => '',
            'title'             => esc_html__( 'Storefront Settings', 'cartfront' ),
            'description'       => esc_html__( 'Configure all the settings for the storefront theme within this section.', 'cartfront' )
        ) );

        /**
    	 * Add cartfront panel.
    	 */
    	$wp_customize->add_panel( 'cf_cartfront_settings', array(
            'priority'          => 20,
            'capability'        => 'edit_theme_options',
            'theme_supports'    => '',
            'title'             => esc_html__( 'Cartfront Settings', 'cartfront' ),
            'description'       => esc_html__( 'Configure all the settings for the cartfront theme within this section.', 'cartfront' )
        ) );

        /**
         * Sections added by WordPress.
         */
        $cf_wordpress_sections = array(
        	'title_tagline' 		=> 5,
        	'colors' 				=> 10,
        	'header_image' 			=> 15,
        	'background_image' 		=> 20,
        	'static_front_page' 	=> 25,
        	'custom_css' 			=> 30
        );

        /**
         * Sections added by storefront.
         */
        $cf_storefront_sections = array(
        	'storefront_layout' 				=> 5,
        	'storefront_typography' 			=> 10,
        	'storefront_buttons' 				=> 15,
        	'storefront_single_product_page' 	=> 20,
        	'storefront_footer' 				=> 25
        );

        /**
         * Sections added by cartfront.
         */
        $cf_cartfront_sections 	= array(
        	'cf_hc_section' => 5,
    		'cf_lb_section' => 10,
        	'cf_hm_section' => 15,
        	'cf_fb_section' => 20
        );

        /**
    	 * Re-arrange wordpress sections to it's panel.
    	 */
    	foreach ( $cf_wordpress_sections as $cf_wp_key => $cf_wp_value ) {
    		$wp_customize->get_section( $cf_wp_key )->panel 	= 'cf_wordpress_settings';
    		$wp_customize->get_section( $cf_wp_key )->priority 	= $cf_wp_value;
    	}

    	/**
    	 * Re-arrange storefront sections to it's panel.
    	 */
    	foreach ( $cf_storefront_sections as $cf_sf_key => $cf_sf_value ) {
    		$wp_customize->get_section( $cf_sf_key )->panel 	= 'cf_storefront_settings';
    		$wp_customize->get_section( $cf_sf_key )->priority 	= $cf_sf_value;
    	}

    	/**
    	 * Re-arrange cartfront sections to it's panel.
    	 */
    	foreach ( $cf_cartfront_sections as $cf_cf_key => $cf_cf_value ) {
    		$wp_customize->get_section( $cf_cf_key )->panel 	= 'cf_cartfront_settings';
    		$wp_customize->get_section( $cf_cf_key )->priority 	= $cf_cf_value;
    	}
    }
    add_action( 'customize_register', 'Niteo\WooCart\CartFront\cartfront_rearrange_sections', PHP_INT_MAX );
    endif;

}

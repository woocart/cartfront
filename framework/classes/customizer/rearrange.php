<?php
/**
 * Re-arrange customizer sections in desired panels.
 *
 * @package cartfront
 */

namespace Niteo\WooCart\CartFront\Customizer {

    class Rearrange {

        /**
         * Constructor function.
         *
         * @access  public
         * @since   1.0.0
         */
        public function __construct() {
            add_action( 'customize_register', array( &$this, 'rearrange_sections' ), PHP_INT_MAX );
        }

        /**
         * Customizer controls and settings.
         *
         * @param WP_Customize_Manager $wp_customize Theme Customizer object.
         */
        public function rearrange_sections( $wp_customize ) {
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
             * Default WordPress sections.
             *
             * 1. Site Title & Tagline
             * 2. Header Image
             * 3. Background Image
             * 4. Static Front Page
             * 5. Additional CSS
             */
            $cf_wordpress_sections = array(
            	'title_tagline' 		=> 5,   // 1
            	'header_image' 			=> 10,  // 2
            	'background_image' 		=> 15,  // 3
            	'static_front_page' 	=> 20,  // 4
            	'custom_css' 			=> 25   // 5
            );

            /**
             * Sections added by storefront.
             *
             * 1. Storefront Layout Section
             * 2. Storefront Typography Section
             * 3. Storefront Buttons Styling Section
             * 4. Storefront Single Product Page Section
             * 5. Storefront Footer Section
             */
            $cf_storefront_sections = array(
            	'storefront_layout' 				=> 5,   // 1
            	'storefront_typography' 			=> 10,  // 2
            	'storefront_buttons' 				=> 15,  // 3
            	'storefront_single_product_page' 	=> 20,  // 4
            	'storefront_footer' 				=> 25   // 5
            );

            /**
             * Sections added by cartfront.
             *
             * 1. Cartfront Homapge Control Section
             * 2. Cartfront Link Boxes Section
             * 3. Cartfront Hamburger Menu Section
             * 4. Cartfront Footer Bar Section
             */
            $cf_cartfront_sections 	= array(
            	'cf_hc_section' => 5,   // 1
        		'cf_lb_section' => 10,  // 2
            	'cf_hm_section' => 15,  // 3
            	'cf_fb_section' => 20   // 4
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

    }

}

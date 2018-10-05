<?php
/**
 * Customizer class for the homepage control addon.
 *
 * @package cartfront
 */

namespace Niteo\WooCart\CartFront {

	if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

	if ( ! class_exists( 'Homepage_Control_Customizer' ) ) :
	class Homepage_Control_Customizer {

		/**
		 * Constructor function.
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function __construct() {
			add_action( 'customize_controls_enqueue_scripts', array( &$this, 'add_styles' ) );
			add_action( 'customize_controls_print_footer_scripts', array( &$this, 'add_scripts' ) );

			add_filter( 'customize_register', array( &$this, 'customize_register' ) );
		}

		/**
		 * Add section, setting and load custom customizer control.
		 *
		 * @access public
		 */
		public function customize_register( $wp_customize ) {
			global $cartfront_path;

			$wp_customize->add_section( 'cf_hc_section', array(
				'title'          => esc_html__( 'Homepage Control', 'cartfront' ),
				'priority'       => 70
			) );

			$wp_customize->add_setting( 'cf_hc_data', array(
				'default' 		=> $this->format_defaults(),
				'type' 			=> 'theme_mod',
				'capability' 	=> 'edit_theme_options'
			) );

			$wp_customize->add_control( new Homepage_Control_Customizer_Control( $wp_customize, 'cf_hc_data', array(
				'description'       => esc_html__( 'Re-order the homepage components.', 'cartfront' ),
				'section'           => 'cf_hc_section',
				'settings'          => 'cf_hc_data',
				'choices'           => $this->get_hooked_functions(),
				'priority'          => 10,
				'type'				=> 'hidden',
				'sanitize_callback'	=> array( &$this, 'sanitize_components' )
			) ) );
		}

		/**
		 * Enqueue scripts.
		 *
		 * @access public
		 */
		public function add_scripts() {
			global $theme_name, $theme_version, $cartfront_url;

			wp_enqueue_script( $theme_name . '-sortables', esc_url( $cartfront_url . '/framework/js/sortables.js' ), array( 'jquery', 'jquery-ui-sortable' ), $theme_version );
		}

		/**
		 * Enqueue styles.
		 *
		 * @access public
		 */
		public function add_styles() {
			global $theme_name, $theme_version, $cartfront_url;

			wp_enqueue_style( $theme_name . '-customizer',  esc_url( $cartfront_url . '/framework/css/customizer.css' ), '', $theme_version );
		}

		/**
		 * Ensures only array keys matching the original settings specified in add_control() are valid.
		 *
		 * @access  public
		 * @return  string The valid component.
		*/
		public function sanitize_components( $input ) {
			$valid = $this->get_hooked_functions();

			if ( array_key_exists( $input, $valid ) || array_key_exists( str_replace( '[disabled]', '', $input ), $valid ) ) {
				return $input;
			} else {
				return '';
			}
		}

		/**
		 * Retrive the functions hooked on to the "woo_homepage" hook.
		 *
		 * @access  private
		 * @return  array An array of the functions, grouped by function name, with a formatted title.
		 */
		private function get_hooked_functions() {
			global $wp_filter;

			$response 	= array();
			$class 		= new Homepage_Control();

			if ( isset( $wp_filter[$class->hook] ) && 0 < iterator_count( $wp_filter[$class->hook] ) ) {
				foreach ( $wp_filter[$class->hook] as $k => $v ) {
					if ( is_array( $v ) ) {
						foreach ( $v as $i => $j ) {
							if ( is_array( $j['function'] ) ) {
								$i = get_class( $j['function'][0] ) . '@' . $j['function'][1];
								$response[$i] = $this->format_title( $j['function'][1] );
							} else {
								$response[$i] = $this->format_title( $i );
							}
						}
					}
				}
			}

			return $response;
		}

		/**
		 * Format a given key into a title.
		 *
		 * @access  private
		 * @return  string A formatted title. If no formatting is possible, return the key.
		 */
		private function format_title( $key ) {
			$prefix = (string) apply_filters( 'cf_hc_prefix', 'cf_hc_' );
			$title = $key;

			$title = str_replace( $prefix, '', $title );
			$title = str_replace( '_', ' ', $title );
			$title = ucwords( $title );

			return $title;
		}

		/**
		 * Format an array of components as a comma separated list.
		 *
		 * @access  private
		 * @return  string A list of components separated by a comma.
		 */
		private function format_defaults() {
			$components = $this->get_hooked_functions();
			$defaults 	= array();

			foreach ( $components as $k => $v ) {
				if ( apply_filters( 'homepage_control_hide_' . $k, false ) ) {
					$defaults[] = '[disabled]' . $k;
				} else {
					$defaults[] = $k;
				}
			}

			return join( ',', $defaults );
		}

	}
	endif;

}

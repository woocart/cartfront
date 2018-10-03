<?php
/**
 * Sanitization for controls used in the theme.
 *
 * @package cartfront
 */

namespace Niteo\WooCart\CartFront {

	if ( ! defined( 'ABSPATH' ) ) {
	    exit;
	}

	/**
	 * Sanitize input for the multi-select control.
	 */
	if ( ! function_exists( 'sanitize_multiselect' ) ) :
	function sanitize_multiselect( $input ) {
		if ( is_array( $input ) ) {
			foreach ( $input as $key => $value ) {
				$input[$key] = sanitize_text_field( $value );
			}
		} else {
			$input = '';
		}

		return $input;
	}
	endif;

	/**
	 * Sanitize input for the repeater control.
	 */
	if ( ! function_exists( 'sanitize_repeater' ) ) :
	function sanitize_repeater( $input ) {
		$input_decoded = json_decode( $input, true );

		if ( ! empty( $input_decoded ) ) {
			foreach ( $input_decoded as $boxk => $box ) {
				foreach ( $box as $key => $value ) {
					$input_decoded[$boxk][$key] = wp_kses_post( force_balance_tags( $value ) );
				}
			}

			return json_encode( $input_decoded );
		}

		return $input;
	}
	endif;

}

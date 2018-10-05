<?php
/**
 * Class autoloader.
 *
 * @package cartfront
 */

namespace Niteo\WooCart\CartFront {

	if ( ! defined( 'ABSPATH' ) ) {
	    exit;
	}

	/**
	 * Function for auto-loading classes.
	 * @codeCoverageIgnore
	 */
	function autoloader( $class_name ) {
		global $cartfront_path;

		$class_name = str_replace( 'Niteo\WooCart\CartFront\\', '', $class_name );
		$class_name = str_replace( '\\', '/', $class_name );

	    if ( file_exists( $cartfront_path . '/framework/classes/' . strtolower( $class_name ) . '.php' ) ) {
	        require_once( $cartfront_path . '/framework/classes/' . strtolower( $class_name ) . '.php' );

	        return true;
	    }

	    return false;
	}

	spl_autoload_register( 'Niteo\WooCart\CartFront\autoloader' );

}

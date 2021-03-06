<?php
/**
 * Setup tests.
 */

define( 'ABSPATH', true );

$root_dir = dirname( dirname(__FILE__) );
require_once "$root_dir/vendor/autoload.php";

/* WP_Customize_Control for custom theme controls. */
class WP_Customize_Control {}

WP_Mock::setUsePatchwork(true);
WP_Mock::bootstrap();

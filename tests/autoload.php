<?php
/**
 * Setup tests.
 */

define( 'ABSPATH', true );

$root_dir = dirname( dirname(__FILE__) );
require_once "$root_dir/vendor/autoload.php";
require_once "$root_dir/framework/include/autoloader.php";

WP_Mock::setUsePatchwork(true);
WP_Mock::bootstrap();

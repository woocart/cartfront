<?php
/**
 * Theme functions and definitions.
 *
 * @package cartfront
 * @author WooCart
 * @link https://woocart.com/
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CartFront {

    public $parent_theme    = 'storefront';
    public $theme_name      = 'cartfront';
    public $theme_version   = '1.0.0';

    public $storefront_url  = '';
    public $cartfront_url   = '';

    public function __construct() {
        // Set required values.
        $this->storefront_url   = get_template_directory_uri();
        $this->cartfront_url    = get_stylesheet_directory_uri();
    }

}

// Initialize main engine.
$cart_front = new CartFront();
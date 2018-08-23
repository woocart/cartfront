<?php
/**
 * Mega menus class for the theme.
 *
 * @package cartfront
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Cartfront_Mega_Menus' ) ) :
class Cartfront_Mega_Menus {

    /**
     * Constructor function.
     *
     * @access  public
     * @since   1.0.0
     */
    public function __construct() {
        add_action( 'admin_init', array( &$this, 'check_settings' ) );
        add_action( 'widgets_init', array( &$this, 'register_widget' ) );
        add_action( 'after_setup_theme', array( &$this, 'register_nav_menus' ) );
        add_filter( 'wp_nav_menu_args', array( &$this, 'modify_nav_menu_args' ), PHP_INT_MAX );
        add_filter( 'wp_nav_menu', array( &$this, 'add_responsive_toggle' ), 10, 2 );
    }

    /**
     * Check for the menu settings and if not present, add defaults.
     *
     * @access public
     */
    public function check_settings() {
        $settings = get_option( 'cf_menu_settings' );

        if ( ! $settings ) {
            $settings['prefix']         = 'disabled';
            $settings['descriptions']   = 'enabled';

            add_option( 'cf_menu_settings', $settings );
        }
    }

    /**
     * Array of classes 
     *
     * @return array
     * @codeCoverageIgnore
     */
    private function classes() {
        $classes = array(
            'cf_menu_walker'            => 'class-cartfront_menu_walker.php',
            'cf_menu_widget_manager'    => 'class-cartfront_menu_widget_manager.php',
            'cf_menu_item_manager'      => 'class-cartfront_menu_item_manager.php',
            'cf_menu_nav'               => 'class-cartfront_menu_nav.php',
            'cf_menu_style_manager'     => 'class-cartfront_menu_style_manager.php',
            'cf_menu_settings'          => 'class-cartfront_menu_settings.php',
            'cf_menu_widget'            => 'class-cartfront_menu_widget.php',
            'cf_menu_blocks'            => 'class-cartfront_menu_blocks.php'
        );

        return $classes;
    }

}
endif;

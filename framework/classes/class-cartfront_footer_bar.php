<?php
/**
 * Footer bar class for the theme.
 *
 * @package cartfront
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Cartfront_Footer_Bar' ) ) :
class Cartfront_Footer_Bar {

    /**
     * Constructor function.
     *
     * @access  public
     * @since   1.0.0
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( &$this, 'add_styles' ), PHP_INT_MAX );
        add_action( 'customize_register', array( &$this, 'customize_register' ) );
        add_action( 'storefront_before_footer', array( &$this, 'footer_bar' ) );
        add_action( 'init', array( $this, 'default_settings' ) );
        add_action( 'customize_register', array( &$this, 'edit_default_settings' ) );
        add_action( 'widgets_init', array( &$this, 'register_widget_area' ), 99 );
    }

    /**
     * Returns an array of the default settings.
     *
     * @return array
     */
    public function get_default_settings() {
        return apply_filters( 'cartfront_fb_default_settings', $args = array(
            'cf_fb_background_image' => '',
            'cf_fb_background_color' => '#2c2d33',
            'cf_fb_heading_color'    => '#ffffff',
            'cf_fb_text_color'       => '#9aa0a7',
            'cf_fb_link_color'       => '#ffffff',
        ) );
    }

    /**
     * Adds a value to each setting if one isn't already present.
     *
     * @uses get_default_settings()
     * @return void
     */
    public function default_settings() {
        foreach ( $this->get_default_settings() as $mod => $val ) {
            add_filter( 'theme_mod_' . $mod, function( $setting ) use ( $val ) {
                return $setting ? $setting : $val;
            } );
        }
    }

    /**
     * Set default Customizer settings.
     *
     * @param  array $wp_customize the Customizer object.
     * @uses   get_default_settings()
     * @return void
     */
    public function edit_default_settings( $wp_customize ) {
        foreach ( $this->get_default_settings() as $mod => $val ) {
            $wp_customize->get_setting( $mod )->default = $val;
        }
    }

    /**
     * Add custom CSS styles.
     */
    public function add_styles() {
        global $theme_name;

        $style = '
        .cartfront-footer-bar {
            background-color: ' . sanitize_text_field( get_theme_mod( 'cf_fb_background_color' ) ) . ';
            background-image: url(' . esc_url_raw( get_theme_mod( 'cf_fb_background_image' ) ) . ');
        }

        .cartfront-footer-bar .widget {
            color: ' . sanitize_text_field( get_theme_mod( 'cf_fb_text_color' ) ) . ';
        }

        .cartfront-footer-bar .widget h1,
        .cartfront-footer-bar .widget h2,
        .cartfront-footer-bar .widget h3,
        .cartfront-footer-bar .widget h4,
        .cartfront-footer-bar .widget h5,
        .cartfront-footer-bar .widget h6 {
            color: ' . sanitize_text_field( get_theme_mod( 'cf_fb_heading_color' ) ) . ';
        }

        .cartfront-footer-bar .widget a {
            color: ' . sanitize_text_field( get_theme_mod( 'cf_fb_link_color' ) ) . ';
        }';

        wp_add_inline_style( $theme_name . '-public', $style );
    }

    /**
     * Register the footer bar area.
     */
    public function register_widget_area() {
        register_sidebar( array(
            'name'          => esc_html__( 'Footer Bar', 'cartfront' ),
            'id'            => 'cartfront-footer-bar',
            'description'   => esc_html__( 'Place widgets to be shown in the footer bar.', 'cartfront' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ) );
    }

    /**
     * Customizer controls and settings.
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    public function customize_register( $wp_customize ) {
        /**
         * Add a new section.
         */
        $wp_customize->add_section( 'cf_fb_section' , array(
            'title'    => esc_html__( 'Footer Bar', 'cartfront' ),
            'priority' => 55
        ) );

        /**
         * Background image.
         */
        $wp_customize->add_setting( 'cf_fb_background_image', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw'
        ) );

        $wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, 'cf_fb_background_image', array(
            'label'    => esc_attr__( 'Background image', 'cartfront' ),
            'section'  => 'cf_fb_section',
            'settings' => 'cf_fb_background_image',
            'priority' => 10
        ) ) );

        /**
         * Background color.
         */
        $wp_customize->add_setting( 'cf_fb_background_color', array(
            'default'           => '#2c2d33',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cf_fb_background_color', array(
            'label'    => esc_attr__( 'Background color', 'cartfront' ),
            'section'  => 'cf_fb_section',
            'settings' => 'cf_fb_background_color',
            'priority' => 15
        ) ) );

        /**
         * Heading color.
         */
        $wp_customize->add_setting( 'cf_fb_heading_color', array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cf_fb_heading_color', array(
            'label'    => esc_attr__( 'Heading color', 'cartfront' ),
            'section'  => 'cf_fb_section',
            'settings' => 'cf_fb_heading_color',
            'priority' => 20
        ) ) );

        /**
         * Text color.
         */
        $wp_customize->add_setting( 'cf_fb_text_color', array(
            'default'           => '#9aa0a7',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cf_fb_text_color', array(
            'label'    => esc_attr__( 'Text color', 'cartfront' ),
            'section'  => 'cf_fb_section',
            'settings' => 'cf_fb_text_color',
            'priority' => 25
        ) ) );

        /**
         * Link color.
         */
        $wp_customize->add_setting( 'cf_fb_link_color', array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cf_fb_link_color', array(
            'label'    => esc_attr__( 'Link color', 'cartfront' ),
            'section'  => 'cf_fb_section',
            'settings' => 'cf_fb_link_color',
            'priority' => 30
        ) ) );
    }

    /**
     * Let's display the footer bar.
     */
    public function footer_bar() {
        if ( is_active_sidebar( 'cartfront-footer-bar' ) ) {
            echo '<div class="cartfront-footer-bar"><div class="col-full">';
                dynamic_sidebar( 'cartfront-footer-bar' );
            echo '</div></div>';
        }
    }

}
endif;

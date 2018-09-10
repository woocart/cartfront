<?php
/**
 * Layouts & presets for the theme.
 *
 * @package cartfront
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Cartfront_Layouts_Presets' ) ) :
class Cartfront_Layouts_Presets {

    /**
     * Store value.
     */
    private $store = 'default';

    /**
     * Constructor function.
     *
     * @access  public
     * @since   1.0.0
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( &$this, 'add_styles' ), PHP_INT_MAX );
        add_action( 'customize_register', array( &$this, 'customize_register' ) );
        add_action( 'get_header', array( &$this, 'presets_header' ) );

        add_filter( 'body_class', array( &$this, 'body_class' ) );
        add_action( 'customize_save_after', array( &$this, 'change_modules' ) );
        add_action( 'customize_save_after', array( &$this, 'color_scheme' ) );
    }

    /**
     * Grab values from the database.
     *
     * @access private
     */
    private function get_values() {
        $this->store = esc_html( get_theme_mod( 'cf_lp_layout', 'default' ) );
    }

    /**
     * Customizer controls and settings
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    public function customize_register( $wp_customize ) {
        /**
         * Presets section.
         */
        $wp_customize->add_section( 'cf_lp_presets' , array(
            'title'         => esc_html__( 'Cartfront Presets', 'cartfront' ),
            'priority'      => 15,
            'description'   => esc_html__( 'Select preset of your choice for the theme.', 'cartfront' )
        ) );

        /**
         * Layout.
         */
        $wp_customize->add_setting( 'cf_lp_layout', array(
            'default'           => 'default',
            'sanitize_callback' => 'storefront_sanitize_choices'
        ) );

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_lp_layout', array(
            'label'         => esc_html__( 'Select Layout', 'cartfront' ),
            'description'   => esc_html__( 'From the below options, select the one that fits best for your store.', 'cartfront' ),
            'section'       => 'cf_lp_presets',
            'settings'      => 'cf_lp_layout',
            'type'          => 'select',
            'priority'      => 10,
            'choices'       => array(
                'default'       => esc_html__( 'Default Layout', 'cartfront' ),
                'toys'          => esc_html__( 'Toy Store', 'cartfront' ),
                'books'         => esc_html__( 'Book Store', 'cartfront' ),
                'jewellery'     => esc_html__( 'Jewellery Store', 'cartfront' )
            )
        ) ) );

        /**
         * Color scheme.
         */
        $wp_customize->add_setting( 'cf_lp_color_scheme', array(
            'default'           => 'default',
            'sanitize_callback' => 'storefront_sanitize_choices'
        ) );

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_lp_color_scheme', array(
            'label'         => esc_html__( 'Select Color Scheme', 'cartfront' ),
            'description'   => esc_html__( 'Changing color scheme will override the settings you have set manually.', 'cartfront' ),
            'section'       => 'cf_lp_presets',
            'settings'      => 'cf_lp_color_scheme',
            'type'          => 'select',
            'priority'      => 15,
            'choices'       => array(
                'default'   => esc_html__( 'Default Color Scheme', 'cartfront' ),
                'toys'          => esc_html__( 'Toy Store Color Scheme', 'cartfront' ),
                'books'         => esc_html__( 'Book Store Color Scheme', 'cartfront' ),
                'jewellery'     => esc_html__( 'Jewellery Store Color Scheme', 'cartfront' ),
                'electronics'   => esc_html__( 'Electronics Color Scheme', 'cartfront' )
            )
        ) ) );

        /**
         * Adding section and options for the navigation menu.
         */
        $wp_customize->add_section( 'cf_lp_nav_section' , array(
            'title'         => esc_html__( 'Menu Colors', 'cartfront' ),
            'priority'      => 5,
            'description'   => esc_html__( 'Select colors for the primary navigation menu.', 'cartfront' ),
            'panel'         => 'nav_menus'
        ) );

        /**
         * Background color.
         */
        $wp_customize->add_setting( 'cf_nav_bg_color', array(
            'default'               => apply_filters( 'cartfront_default_nav_bg_color', '#ffffff' ),
            'sanitize_callback'     => 'sanitize_hex_color',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cf_nav_bg_color', array(
            'label'         => esc_html__( 'Background Color', 'cartfront' ),
            'section'       => 'cf_lp_nav_section',
            'settings'      => 'cf_nav_bg_color',
            'priority'      => 10
        ) ) );

        /**
         * Text color.
         */
        $wp_customize->add_setting( 'cf_nav_text_color', array(
            'default'               => apply_filters( 'cartfront_default_nav_text_color', '#43454b' ),
            'sanitize_callback'     => 'sanitize_hex_color',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cf_nav_text_color', array(
            'label'         => esc_html__( 'Text Color', 'cartfront' ),
            'section'       => 'cf_lp_nav_section',
            'settings'      => 'cf_nav_text_color',
            'priority'      => 15
        ) ) );

        /**
         * Link color.
         */
        $wp_customize->add_setting( 'cf_nav_link_color', array(
            'default'               => apply_filters( 'cartfront_default_nav_link_color', '#333333' ),
            'sanitize_callback'     => 'sanitize_hex_color',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cf_nav_link_color', array(
            'label'         => esc_html__( 'Link Color', 'cartfront' ),
            'section'       => 'cf_lp_nav_section',
            'settings'      => 'cf_nav_link_color',
            'priority'      => 20
        ) ) );

        /**
         * Sub-menu background color.
         */
        $wp_customize->add_setting( 'cf_sub_nav_bg_color', array(
            'default'               => apply_filters( 'cartfront_default_sub_nav_bg_color', '#eeeeee' ),
            'sanitize_callback'     => 'sanitize_hex_color',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cf_sub_nav_bg_color', array(
            'label'         => esc_html__( 'Sub Menu Background Color', 'cartfront' ),
            'section'       => 'cf_lp_nav_section',
            'settings'      => 'cf_sub_nav_bg_color',
            'priority'      => 25
        ) ) );

        /**
         * Sub-menu text color.
         */
        $wp_customize->add_setting( 'cf_sub_nav_text_color', array(
            'default'               => apply_filters( 'cartfront_default_sub_nav_text_color', '#43454b' ),
            'sanitize_callback'     => 'sanitize_hex_color',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cf_sub_nav_text_color', array(
            'label'         => esc_html__( 'Sub Menu Text Color', 'cartfront' ),
            'section'       => 'cf_lp_nav_section',
            'settings'      => 'cf_sub_nav_text_color',
            'priority'      => 30
        ) ) );

        /**
         * Sub-menu link color.
         */
        $wp_customize->add_setting( 'cf_sub_nav_link_color', array(
            'default'               => apply_filters( 'cartfront_default_sub_nav_link_color', '#333333' ),
            'sanitize_callback'     => 'sanitize_hex_color',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cf_sub_nav_link_color', array(
            'label'         => esc_html__( 'Sub Menu Link Color', 'cartfront' ),
            'section'       => 'cf_lp_nav_section',
            'settings'      => 'cf_sub_nav_link_color',
            'priority'      => 35
        ) ) );
    }

    /**
     * Add dynamic CSS.
     */
    public function add_styles() {
        global $theme_name;

        $style = '@media screen and (min-width: 768px) {
            .storefront-primary-navigation {
                background-color: ' . sanitize_hex_color( get_theme_mod( 'cf_nav_bg_color', '#ffffff' ) ) . ';
            }

            .storefront-primary-navigation .main-navigation .primary-navigation > ul > li {
                color: ' . sanitize_hex_color( get_theme_mod( 'cf_nav_text_color', '#43454b' ) ) . ';
            }

            .storefront-primary-navigation .main-navigation .primary-navigation > ul > li a,
            .storefront-primary-navigation .site-header-cart > li > a,
            .storefront-primary-navigation a.cart-contents:hover {
                color: ' . sanitize_hex_color( get_theme_mod( 'cf_nav_link_color', '#333333' ) ) . ';
            }

            .storefront-primary-navigation .main-navigation .primary-navigation ul.sub-menu {
                background-color: ' . sanitize_hex_color( get_theme_mod( 'cf_sub_nav_bg_color', '#ffffff' ) ) . ';
            }

            .storefront-primary-navigation .main-navigation .primary-navigation ul.sub-menu li {
                color: ' . sanitize_hex_color( get_theme_mod( 'cf_sub_nav_text_color', '#43454b' ) ) . ';
            }

            .storefront-primary-navigation .main-navigation .primary-navigation ul.sub-menu li a {
                color: ' . sanitize_hex_color( get_theme_mod( 'cf_sub_nav_link_color', '#333333' ) ) . ';
            }
        }';

        wp_add_inline_style( $theme_name . '-public', $style );
    }

    /**
     * Modifications for the presets.
     *
     * @access public
     */
    public function presets_header() {
        $layout = esc_html( get_theme_mod( 'cf_lp_layout', 'default' ) );

        switch( $layout ) {
            case 'toys' :
                remove_action( 'storefront_header', 'storefront_secondary_navigation', 30 );
                remove_action( 'storefront_header', 'storefront_product_search', 40 );
                remove_action( 'storefront_header', 'storefront_header_cart', 60 );
                add_action( 'storefront_header', 'storefront_product_search', 30 );
                add_action( 'storefront_header', 'storefront_header_cart', 40 );
                break;
            case 'books' :
                remove_action( 'storefront_header', 'storefront_secondary_navigation', 30 );
                remove_action( 'storefront_header', 'storefront_product_search', 40 );
                remove_action( 'storefront_header', 'storefront_header_cart', 60 );
                add_action( 'storefront_header', 'storefront_header_cart', 40 );
                add_action( 'storefront_header', 'storefront_product_search', 60 );
                break;
            case 'jewellery' :
                remove_action( 'storefront_header', 'storefront_header_container', 0 );
                remove_action( 'storefront_header', 'storefront_secondary_navigation', 30 );
                remove_action( 'storefront_header', 'storefront_product_search', 40 );
                remove_action( 'storefront_header', 'storefront_header_cart', 60 );
                add_action( 'storefront_header', array( &$this, 'header_top_container' ), 0 );
                add_action( 'storefront_header', 'storefront_header_cart', 2 );
                add_action( 'storefront_header', array( &$this, 'header_top_container_close' ), 3 );
                add_action( 'storefront_header', 'storefront_header_container', 4 );
                break;
        }
    }

    /**
     * Add to the `body_class` filter.
     *
     * @access public
     */
    public function body_class( $classes ) {
        $this->get_values();
        $classes[] = $this->store . '-store';

        return $classes;
    }

    /**
     * Top bar container.
     *
     * @access public
     */
    public function header_top_container() {
        echo '<div class="cartfront-header-top">';
        echo '<div class="col-full">';
    }

    /**
     * Change modules.
     *
     * @access public
     */
    public function change_modules() {
        global $cartfront_path;

        // Refresh values.
        $this->get_values();

        if ( 'default' !== $this->store ) {
            $json_data  = file_get_contents( $cartfront_path . '/framework/layouts/data/' . $this->store . '.json' );
            $data_array = json_decode( $json_data, true );

            // Switch the homepage modules.
            $components = $data_array['cf_hc_data'];

            // Change theme mod value.
            set_theme_mod( 'cf_hc_data', $components );
        }
    }

    /**
     * Color scheme.
     *
     * @access public
     */
    public function color_scheme() {
        global $cartfront_path;

        // Refresh values.
        $color_scheme = get_theme_mod( 'cf_lp_color_scheme', 'default' );

        if ( 'default' !== $color_scheme ) {
            $json_data  = file_get_contents( $cartfront_path . '/framework/layouts/data/' . $color_scheme . '.json' );
            $data_array = json_decode( $json_data, true );

            /**
             * Store-specific color schemes.
             */
            $settings = array(
                'storefront_header_background_color',
                'storefront_header_text_color',
                'storefront_header_link_color',
                'cf_fb_background_color',
                'storefront_footer_background_color',
                'storefront_footer_text_color',
                'storefront_footer_link_color',
                'storefront_button_background_color',
                'storefront_button_text_color',
                'storefront_heading_color',
                'storefront_accent_color',
                'storefront_button_alt_background_color',
                'cf_fb_background_image',
                'storefront_text_color',
                'cf_lp_layout',
                'cf_nav_bg_color',
                'cf_nav_text_color',
                'cf_nav_link_color'
            );

            foreach ( $settings as $setting ) {
                if ( isset( $data_array[$setting] ) ) {
                    if ( ! empty( $data_array[$setting] ) ) {
                        set_theme_mod( $setting, $data_array[$setting] );
                    }
                }
            }
        }
    }

    /**
     * Top bar container close.
     *
     * @access public
     */
    public function header_top_container_close() {
        echo '</div>';
        echo '</div><!-- .cartfront-header-top -->';
    }

}
endif;

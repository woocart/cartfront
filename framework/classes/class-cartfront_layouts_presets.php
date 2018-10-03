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
        add_action( 'init', array( &$this, 'add_footer' ) );
        add_action( 'init', array( &$this, 'check_layout' ) );

        add_filter( 'body_class', array( &$this, 'body_class' ) );
        add_action( 'wp_ajax_change_layout', array( &$this, 'change_options' ) );
        add_action( 'wp_ajax_change_color_scheme', array( &$this, 'change_color_scheme' ) );
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
            'sanitize_callback' => 'storefront_sanitize_choices',
            'transport'         => 'postMessage'
        ) );

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_lp_layout', array(
            'label'         => esc_html__( 'Select Layout', 'cartfront' ),
            'description'   => esc_html__( 'From the below options, select the one that fits best for your store.', 'cartfront' ),
            'section'       => 'cf_lp_presets',
            'settings'      => 'cf_lp_layout',
            'type'          => 'select',
            'priority'      => 10,
            'choices'       => array(
                'default'       => esc_html__( 'Default', 'cartfront' ),
                'toys'          => esc_html__( 'Toy Store', 'cartfront' ),
                'books'         => esc_html__( 'Book Store', 'cartfront' ),
                'jewellery'     => esc_html__( 'Jewellery Store', 'cartfront' ),
                'electronics'   => esc_html__( 'Electronics Store', 'cartfront' ),
            )
        ) ) );

        /**
         * Color scheme.
         */
        $wp_customize->add_setting( 'cf_lp_color_scheme', array(
            'default'           => 'default',
            'sanitize_callback' => 'storefront_sanitize_choices',
            'transport'         => 'postMessage'
        ) );

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_lp_color_scheme', array(
            'label'         => esc_html__( 'Select Color Scheme', 'cartfront' ),
            'description'   => esc_html__( 'Changing color scheme will override the settings you have set manually.', 'cartfront' ),
            'section'       => 'cf_lp_presets',
            'settings'      => 'cf_lp_color_scheme',
            'type'          => 'select',
            'priority'      => 15,
            'choices'       => array(
                'default'       => esc_html__( 'Default', 'cartfront' ),
                'toys'          => esc_html__( 'Toy Store', 'cartfront' ),
                'books'         => esc_html__( 'Book Store', 'cartfront' ),
                'jewellery'     => esc_html__( 'Jewellery Store', 'cartfront' ),
                'electronics'   => esc_html__( 'Electronics Store', 'cartfront' )
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

            .storefront-primary-navigation .main-navigation ul > li {
                color: ' . sanitize_hex_color( get_theme_mod( 'cf_nav_text_color', '#43454b' ) ) . ';
            }

            .storefront-primary-navigation .main-navigation ul > li a,
            .storefront-primary-navigation .site-header-cart > li > a,
            .storefront-primary-navigation a.cart-contents:hover {
                color: ' . sanitize_hex_color( get_theme_mod( 'cf_nav_link_color', '#333333' ) ) . ';
            }

            .storefront-primary-navigation .main-navigation ul.sub-menu {
                background-color: ' . sanitize_hex_color( get_theme_mod( 'cf_sub_nav_bg_color', '#ffffff' ) ) . ';
            }

            .storefront-primary-navigation .main-navigation ul.sub-menu li {
                color: ' . sanitize_hex_color( get_theme_mod( 'cf_sub_nav_text_color', '#43454b' ) ) . ';
            }

            .storefront-primary-navigation .main-navigation ul.sub-menu li a {
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
                remove_action( 'storefront_header', 'storefront_primary_navigation', 50 );
                remove_action( 'storefront_header', 'storefront_product_search', 40 );
                remove_action( 'storefront_header', 'storefront_header_cart', 60 );

                add_action( 'storefront_header', 'storefront_product_search', 30 );
                add_action( 'storefront_header', 'storefront_header_cart', 40 );
                add_action( 'storefront_header', array( &$this, 'primary_nav_menu' ), 50 );
                break;
            case 'books' :
                remove_action( 'storefront_header', 'storefront_primary_navigation', 50 );
                remove_action( 'storefront_header', 'storefront_product_search', 40 );
                remove_action( 'storefront_header', 'storefront_header_cart', 60 );

                add_action( 'storefront_header', 'storefront_header_cart', 40 );
                add_action( 'storefront_header', array( &$this, 'primary_nav_menu' ), 50 );
                add_action( 'storefront_header', 'storefront_product_search', 60 );
                break;
            case 'jewellery' :
                remove_action( 'storefront_header', 'storefront_header_container', 0 );
                remove_action( 'storefront_header', 'storefront_primary_navigation', 50 );
                remove_action( 'storefront_header', 'storefront_product_search', 40 );
                remove_action( 'storefront_header', 'storefront_header_cart', 60 );

                add_action( 'storefront_header', array( &$this, 'header_top_container' ), 0 );
                add_action( 'storefront_header', 'storefront_header_cart', 2 );
                add_action( 'storefront_header', array( &$this, 'header_top_container_close' ), 3 );
                add_action( 'storefront_header', 'storefront_header_container', 4 );
                add_action( 'storefront_header', array( &$this, 'primary_nav_menu' ), 50 );
                break;
            case 'electronics' :
                remove_action( 'storefront_header', 'storefront_primary_navigation', 50 );

                add_action( 'storefront_header', array( &$this, 'primary_nav_menu' ), 50 );
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
     * Change modules.
     *
     * @access public
     */
    public function change_options() {
        global $cartfront_path;

        // Get layout value.
        $store = sanitize_text_field( $_POST['layout'] );

        // Default response.
        $data = array(
            'status'    => 100,
            'layout'    => $store
        );

        if ( in_array( $store, array( 'toys', 'books', 'jewellery', 'electronics' ) ) ) {
            $json_data  = file_get_contents( $cartfront_path . '/framework/layouts/data/' . $store . '.json' );
            $data_array = json_decode( $json_data, true );

            // Settings to be modified.
            $settings = array(
                'storefront_layout',
                'storefront_sticky_add_to_cart',
                'storefront_product_pagination',
                'cf_hc_data',
                'cf_lb_section_title',
                'cf_lb_items_row',
                'cf_hm_enable',
                'cf_bc_post_layout_archive',
                'cf_bc_blog_archive_layout',
                'cf_bc_magazine_layout',
                'cf_bc_post_layout_single',
                'cf_bc_blog_single_layout',
                'cf_bc_homepage_blog_toggle',
                'cf_bc_homepage_blog_title',
                'cf_bc_post_layout_homepage',
                'cf_bc_homepage_blog_columns',
                'cf_bc_homepage_blog_limit',
                'cf_ss_choice',
                'cf_ss_section_title',
                'cf_ss_items_row',
                'cf_ss_count',
                'cf_ss_posts_order',
                'cf_ss_products_type'
            );

            // Loop through the settings and add to filter.
            foreach ( $settings as $setting ) {
                if ( isset( $data_array[$setting] ) ) {
                    if ( ! empty( $data_array[$setting] ) ) {
                        set_theme_mod( $setting, $data_array[$setting] );
                    }
                }
            }

            // Change status.
            $data['status'] = 200;
        }

        // Update the wizard option.
        update_option( 'cartfront_theme', $store );

        echo json_encode( $data );
        exit;
    }

    /**
     * Color scheme.
     *
     * @access public
     */
    public function change_color_scheme() {
        global $cartfront_path;

        // Get color scheme.
        $color_scheme = sanitize_text_field( $_POST['color_scheme'] );

        // Default response.
        $data = array(
            'status'            => 100,
            'color_scheme'      => $color_scheme
        );

         if ( in_array( $color_scheme, array( 'toys', 'books', 'jewellery', 'electronics' ) ) ) {
            $json_data  = file_get_contents( $cartfront_path . '/framework/layouts/data/' . $color_scheme . '.json' );
            $data_array = json_decode( $json_data, true );

             /**
              * Store-specific color schemes.
              */
            $settings = array(
                'storefront_heading_color',
                'storefront_text_color',
                'storefront_accent_color',
                'storefront_hero_heading_color',
                'storefront_hero_text_color',
                'storefront_header_background_color',
                'storefront_header_text_color',
                'storefront_header_link_color',
                'storefront_footer_background_color',
                'storefront_footer_heading_color',
                'storefront_footer_text_color',
                'storefront_footer_link_color',
                'storefront_button_background_color',
                'storefront_button_text_color',
                'storefront_button_alt_background_color',
                'storefront_button_alt_text_color',
                'cf_fb_background_color',
                'cf_fb_heading_color',
                'cf_fb_text_color',
                'cf_fb_link_color',
                'cf_nav_bg_color',
                'cf_nav_text_color',
                'cf_nav_link_color',
                'cf_sub_nav_bg_color',
                'cf_sub_nav_text_color',
                'cf_sub_nav_link_color'
            );

            foreach ( $settings as $setting ) {
                if ( isset( $data_array[$setting] ) ) {
                    if ( ! empty( $data_array[$setting] ) ) {
                        set_theme_mod( $setting, $data_array[$setting] );
                    }
                }
            }

            // Change status.
            $data['status'] = 200;
        }
    }

    /**
     * Check for the layout and change settings according to the `cartfront_theme_option`.
     *
     * @access public
     */
    public function check_layout() {
        global $cartfront_path;

        // Option set by the wizard.
        $option = get_option( 'cartfront_theme' );

        // Currently set theme_mod
        $layout = get_theme_mod( 'cf_lp_layout' );

        // Change settings if they don't match.
        if ( $option !== $layout ) {
            // Check for layout values.
            if ( in_array( $option, array( 'toys', 'books', 'jewellery', 'electronics' ) ) ) {
                $json_data  = file_get_contents( $cartfront_path . '/framework/layouts/data/' . $option . '.json' );
                $data_array = json_decode( $json_data, true );

                foreach ( $data_array as $k => $v ) {
                    if ( ! empty( $v ) ) {
                        set_theme_mod( $k, $v );
                    }
                }

                // Set layout to $option
                set_theme_mod( 'cf_lp_layout', $option );

                // Set color_scheme to $option
                set_theme_mod( 'cf_lp_color_scheme', $option );
            }
        }
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
     * Top bar container close.
     *
     * @access public
     */
    public function header_top_container_close() {
        echo '</div>';
        echo '</div><!-- .cartfront-header-top -->';
    }

    /**
     * Footer credit container.
     *
     * @access public
     */
    public function footer_credit_container() {
        echo '<div class="cartfront-footer-credits">';
    }

    /**
     * Footer credit container close.
     *
     * @access public
     */
    public function footer_credit_container_close() {
        echo '</div><!-- .cartfront-footer-credits -->';
    }

    /**
     * Footer credit container close.
     *
     * @access public
     */
    public function footer_nav_menu() {
        echo '<div class="cartfront-footer-menu">';

        // Navigation menu.
        wp_nav_menu(
            array(
                'theme_location'    => 'footer',
                'menu_class'        => 'footer-navigation',
                'depth'             => 1,
                'fallback_cb'       => array( &$this, 'footer_nav_menu_fallback' )
            )
        );

        echo '</div><!-- .cartfront-footer-menu -->';
    }

    /**
     * Adding fallback to the primary navigation.
     *
     * @access public
     */
    public function primary_nav_menu() {
        ?>
        <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_html_e( 'Primary Navigation', 'storefront' ); ?>">
        <button class="menu-toggle" aria-controls="site-navigation" aria-expanded="false"><span><?php echo esc_attr( apply_filters( 'storefront_menu_toggle_text', __( 'Menu', 'storefront' ) ) ); ?></span></button>
            <?php
            wp_nav_menu(
                array(
                    'theme_location'    => 'primary',
                    'container_class'   => 'primary-navigation',
                    'fallback_cb'       => array( &$this, 'primary_nav_menu_fallback' )
                )
            );

            wp_nav_menu(
                array(
                    'theme_location'    => 'handheld',
                    'container_class'   => 'handheld-navigation',
                    'fallback_cb'       => array( &$this, 'primary_nav_menu_fallback' )
                )
            );
            ?>
        </nav><!-- #site-navigation -->
        <?php
    }

    /**
     * Primary nav menu fallback.
     *
     * @access public
     */
    public function primary_nav_menu_fallback() {
        $items = array(
            'home'      => array(
                'title'     => esc_html__( 'Home', 'cartfront' ),
                'option'    => 'siteurl'
            ),
            'about'     => array(
                'title'     => esc_html__( 'About', 'cartfront' )
            ),
            'contact'   => array(
                'title'     => esc_html__( 'Contact', 'cartfront' )
            )
        );

        echo '<div class="primary-menu-fallback">' . "\n";
        echo '<ul>' . "\n";

        foreach ( $items as $key => $option ) {
            if ( 'home' === $key ) {
                $fetch = get_option( $option['option'] );
            } else {
                $fetch = $this->get_id_by_slug( $key );
            }

            if ( $fetch ) {
                // Get permalink.
                if ( 'home' === $key ) {
                    $link = esc_url( $fetch );
                } else {
                    $link = esc_url( get_permalink( $fetch ) );
                }

                if ( $link ) {
                    echo '<li>' . "\n";
                    echo '<a href="' . $link . '">' . $option['title'] . '</a>' . "\n";
                    echo '</li>' . "\n";
                }
            }
        }

        echo '</ul>' . "\n";
    }

    /**
     * Footer nav menu fallback.
     *
     * @access public
     */
    public function footer_nav_menu_fallback() {
        $items = array(
            'privacy'   => array(
                'title'     => esc_html__( 'Privacy Policy', 'cartfront' ),
                'option'    => 'wp_page_for_privacy_policy'
            ),
            'cookies'   => array(
                'title'     => esc_html__( 'Cookie Policy', 'cartfront' ),
                'option'    => 'wp_page_for_cookie_policy'
            ),
            'returns'   => array(
                'title'     => esc_html__( 'Returns and Refunds', 'cartfront' ),
                'option'    => 'woocommerce_returns_page_id'
            ),
            'terms'     => array(
                'title'     => esc_html__( 'Terms and Conditions', 'cartfront' ),
                'option'    => 'woocommerce_terms_page_id'
            ),
            'contact'   => array(
                'title'     => esc_html__( 'Contact', 'cartfront' )
            )
        );

        echo '<div class="footer-menu-fallback">' . "\n";
        echo '<ul>' . "\n";

        foreach ( $items as $key => $option ) {
            if ( 'contact' === $key ) {
                $fetch = $this->get_id_by_slug( $key );
            } else {
                $fetch = get_option( $option['option'] );
            }

            if ( $fetch ) {
                // Get permalink.
                $link = esc_url( get_permalink( $fetch ) );

                if ( $link ) {
                    echo '<li>' . "\n";
                    echo '<a href="' . $link . '">' . $option['title'] . '</a>' . "\n";
                    echo '</li>' . "\n";
                }
            }
        }

        echo '</ul>' . "\n";
    }

    /**
     * Add to `storefront_footer` hook.
     *
     * @access public
     */
    public function add_footer() {
        add_action( 'storefront_footer', array( &$this, 'footer_credit_container' ), 15 );
        add_action( 'storefront_footer', array( &$this, 'footer_nav_menu' ), 25 );
        add_action( 'storefront_footer', array( &$this, 'footer_credit_container_close' ), 30 );
    }

    /**
     * Get page ID by slug.
     *
     * @param $page_slug
     * @return string|null
     */
    public function get_id_by_slug( string $page_slug ) {
        $page = get_page_by_path( $page_slug );

        if ( $page ) {
            return $page->ID;
        } else {
            return false;
        }
    }

}
endif;

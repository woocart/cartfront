<?php
/**
 * Blog customiser class for the theme.
 *
 * @package cartfront
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Cartfront_Blog_Customiser' ) ) :
class Cartfront_Blog_Customiser {

    /**
     * Constructor function.
     *
     * @access  public
     * @since   1.0.0
     */
    public function __construct() {
        add_action( 'customize_register', array( &$this, 'customize_register' ) );
        add_filter( 'body_class', array( &$this, 'body_class' ) );
        add_filter( 'post_class', array( &$this, 'post_class' ) );
        add_action( 'homepage', array( &$this, 'homepage_blog' ), 80 );
        add_action( 'wp', array( &$this, 'layout' ), PHP_INT_MAX );
    }

    /**
     * Customizer controls and settings
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    public function customize_register( $wp_customize ) {
        /**
         * Add the panel.
         */
        $wp_customize->add_panel( 'cf_bc_panel', array(
            'priority'          => 60,
            'capability'        => 'edit_theme_options',
            'theme_supports'    => '',
            'title'             => esc_html__( 'Blog', 'cartfront' ),
            'description'       => esc_html__( 'Customise the appearance of your blog posts and archives.', 'cartfront' )
        ) );

        /**
         * Blog archives section.
         */
        $wp_customize->add_section( 'cf_bc_blog_archive' , array(
            'title'         => esc_html__( 'Archives', 'cartfront' ),
            'priority'      => 10,
            'description'   => esc_html__( 'Customise the look & feel of the blog archives', 'cartfront' ),
            'panel'         => 'cf_bc_panel'
        ) );

        /**
         * Single blog post section.
         */
        $wp_customize->add_section( 'cf_bc_blog_single' , array(
            'title'         => esc_html__( 'Single posts', 'cartfront' ),
            'priority'      => 20,
            'description'   => esc_html__( 'Customise the look & feel of the blog post pages', 'cartfront' ),
            'panel'         => 'cf_bc_panel'
        ) );

        /**
         * Homepage blog section.
         */
        $wp_customize->add_section( 'cf_bc_blog_homepage' , array(
            'title'         => esc_html__( 'Homepage', 'cartfront' ),
            'priority'      => 30,
            'description'   => esc_html__( 'Configure the display of blog posts on the homepage template', 'cartfront' ),
            'panel'         => 'cf_bc_panel'
        ) );

        /**
         * Post layout.
         */
        $wp_customize->add_setting( 'cf_bc_post_layout_archive', array(
            'default'           => 'default',
            'sanitize_callback' => 'storefront_sanitize_choices'
        ) );

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_bc_post_layout_archive', array(
            'label'    => esc_attr__( 'Post meta display', 'cartfront' ),
            'section'  => 'cf_bc_blog_archive',
            'settings' => 'cf_bc_post_layout_archive',
            'type'     => 'select',
            'priority' => 10,
            'choices'  => array(
                'default'            => esc_html__( 'Left of content', 'cartfront' ),
                'meta-right'         => esc_html__( 'Right of content', 'cartfront' ),
                'meta-inline-top'    => esc_html__( 'Above content', 'cartfront' ),
                'meta-inline-bottom' => esc_html__( 'Beneath content', 'cartfront' ),
                'meta-hidden'        => esc_html__( 'Hidden', 'cartfront' )
            )
        ) ) );

        /**
         * Blog archive layout.
         */
        $wp_customize->add_setting( 'cf_bc_blog_archive_layout', array(
            'default'           => false,
            'sanitize_callback' => 'storefront_sanitize_checkbox'
        ) );

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_bc_blog_archive_layout', array(
            'label'         => esc_attr__( 'Full width', 'cartfront' ),
            'description'   => esc_html__( 'Display blog archives in a full width layout.', 'cartfront' ),
            'section'       => 'cf_bc_blog_archive',
            'settings'      => 'cf_bc_blog_archive_layout',
            'type'          => 'checkbox',
            'priority'      => 20
        ) ) );

        /**
         * Magazine layout.
         */
        $wp_customize->add_setting( 'cf_bc_magazine_layout', array(
            'default'           => false,
            'sanitize_callback' => 'storefront_sanitize_checkbox'
        ) );

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_bc_magazine_layout', array(
            'label'         => esc_attr__( 'Magazine layout', 'cartfront' ),
            'description'   => esc_html__( 'Apply a "magazine" layout to blog archives.', 'cartfront' ),
            'section'       => 'cf_bc_blog_archive',
            'settings'      => 'cf_bc_magazine_layout',
            'type'          => 'checkbox',
            'priority'      => 30
        ) ) );

        /**
         * Single post layout.
         */
        $wp_customize->add_setting( 'cf_bc_post_layout_single', array(
            'default'           => 'default',
            'sanitize_callback' => 'storefront_sanitize_choices'
        ) );

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_bc_post_layout_single', array(
            'label'    => esc_attr__( 'Post meta display', 'cartfront' ),
            'section'  => 'cf_bc_blog_single',
            'settings' => 'cf_bc_post_layout_single',
            'type'     => 'select',
            'priority' => 10,
            'choices'  => array(
                'default'            => esc_html__( 'Left of content', 'cartfront' ),
                'meta-right'         => esc_html__( 'Right of content', 'cartfront' ),
                'meta-inline-top'    => esc_html__( 'Above content', 'cartfront' ),
                'meta-inline-bottom' => esc_html__( 'Beneath content', 'cartfront' ),
                'meta-hidden'        => esc_html__( 'Hidden', 'cartfront' )
            )
        ) ) );

        /**
         * Blog single full-width.
         */
        $wp_customize->add_setting( 'cf_bc_blog_single_layout', array(
            'default'           => false,
            'sanitize_callback' => 'storefront_sanitize_checkbox'
        ) );

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_bc_blog_single_layout', array(
            'label'         => esc_attr__( 'Full width', 'cartfront' ),
            'description'   => esc_html__( 'Give the single blog post pages a full width layout.', 'cartfront' ),
            'section'       => 'cf_bc_blog_single',
            'settings'      => 'cf_bc_blog_single_layout',
            'type'          => 'checkbox',
            'priority'      => 20
        ) ) );

        /**
         * Homepage blog toggle.
         */
        $wp_customize->add_setting( 'cf_bc_homepage_blog_toggle', array(
            'default'           => false,
            'sanitize_callback' => 'storefront_sanitize_checkbox'
        ) );

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_bc_homepage_blog_toggle', array(
            'label'         => esc_attr__( 'Display blog posts', 'cartfront' ),
            'description'   => esc_html__( 'Toggle the display of blog posts on the homepage.', 'cartfront' ),
            'section'       => 'cf_bc_blog_homepage',
            'settings'      => 'cf_bc_homepage_blog_toggle',
            'type'          => 'checkbox',
            'priority'      => 10
        ) ) );

        /**
         * Homepage blog title.
         */
        $wp_customize->add_setting( 'cf_bc_homepage_blog_title', array(
            'default'           => esc_html__( 'Recent Blog Posts', 'cartfront' ),
            'sanitize_callback' => 'sanitize_text_field'
        ) );

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_bc_homepage_blog_title', array(
            'label'             => esc_attr__( 'Blog post title', 'cartfront' ),
            'section'           => 'cf_bc_blog_homepage',
            'settings'          => 'cf_bc_homepage_blog_title',
            'type'              => 'text',
            'priority'          => 20
        ) ) );

        /**
         * Homepage post layout.
         */
        $wp_customize->add_setting( 'cf_bc_post_layout_homepage', array(
            'default'           => 'default',
            'sanitize_callback' => 'storefront_sanitize_choices'
        ) );

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_bc_post_layout_homepage', array(
            'label'    => esc_attr__( 'Post meta display', 'cartfront' ),
            'section'  => 'cf_bc_blog_homepage',
            'settings' => 'cf_bc_post_layout_homepage',
            'type'     => 'select',
            'priority' => 25,
            'choices'  => array(
                'default'            => esc_html__( 'Left of content', 'cartfront' ),
                'meta-right'         => esc_html__( 'Right of content', 'cartfront' ),
                'meta-inline-top'    => esc_html__( 'Above content', 'cartfront' ),
                'meta-inline-bottom' => esc_html__( 'Beneath content', 'cartfront' ),
                'meta-hidden'        => esc_html__( 'Hidden', 'cartfront' )
            )
        ) ) );

        /**
         * Homepage blog Columns.
         */
        $wp_customize->add_setting( 'cf_bc_homepage_blog_columns', array(
            'default'           => '2',
            'sanitize_callback' => 'storefront_sanitize_choices'
        ) );

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_bc_homepage_blog_columns', array(
            'label'    => esc_attr__( 'Blog post columns', 'cartfront' ),
            'section'  => 'cf_bc_blog_homepage',
            'settings' => 'cf_bc_homepage_blog_columns',
            'type'     => 'select',
            'priority' => 30,
            'choices'  => array(
                '1' => '1',
                '2' => '2',
                '3' => '3'
            )
        ) ) );

        /**
         * Homepage blog limit.
         */
        $wp_customize->add_setting( 'cf_bc_homepage_blog_limit', array(
            'default'           => 2,
            'sanitize_callback' => 'storefront_sanitize_choices'
        ) );

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_bc_homepage_blog_limit', array(
            'label'    => esc_attr__( 'Number of posts to display', 'cartfront' ),
            'section'  => 'cf_bc_blog_homepage',
            'settings' => 'cf_bc_homepage_blog_limit',
            'type'     => 'select',
            'priority' => 40,
            'choices'  => array(
                '1'  => '1',
                '2'  => '2',
                '3'  => '3',
                '4'  => '4',
                '5'  => '5',
                '6'  => '6',
                '7'  => '7',
                '8'  => '8',
                '9'  => '9',
                '10' => '10',
                '11' => '11',
                '12' => '12'
            )
        ) ) );
    }

    /**
     * Body Class.
     *
     * @param array $classes the classes applied to the body tag.
     */
    public function body_class( $classes ) {
        $post_layout_archive     = get_theme_mod( 'cf_bc_post_layout_archive', 'default' );
        $post_layout_single      = get_theme_mod( 'cf_bc_post_layout_single', 'default' );
        $post_layout_homepage    = get_theme_mod( 'cf_bc_post_layout_homepage', 'default' );
        $blog_archive_full_width = get_theme_mod( 'cf_bc_blog_archive_layout', false );
        $blog_single_full_width  = get_theme_mod( 'cf_bc_blog_single_layout', false );
        $magazine                = get_theme_mod( 'cf_bc_magazine_layout', false );

        // Archives.
        if ( $this->is_blog_archive() ) {
            $classes[] = 'cf-bc-' . $post_layout_archive;
        }

        if ( $this->is_blog_archive() && true === (bool) $blog_archive_full_width ) {
            $classes[] = 'cartfront-full-width-content';
        }

        if ( $this->is_blog_archive() && true === (bool) $magazine ) {
            $classes[] = 'cf-bc-magazine';
        }

        // Single.
        if ( is_singular( 'post' ) ) {
            $classes[] = 'cf-bc-' . $post_layout_single;
        }

        if ( is_singular( 'post' ) && true === (bool) $blog_single_full_width ) {
            $classes[] = 'cartfront-full-width-content';
        }

        // Homepage.
        if ( is_page_template( 'template-homepage.php' ) ) {
            $classes[] = 'cf-bc-' . $post_layout_homepage;
        }

        return $classes;
    }

    /**
     * Tweaks layout based on settings.
     */
    public function layout() {
        $post_layout_archive     = get_theme_mod( 'cf_bc_post_layout_archive', 'default' );
        $post_layout_single      = get_theme_mod( 'cf_bc_post_layout_single', 'default' );
        $post_layout_homepage    = get_theme_mod( 'cf_bc_post_layout_homepage', 'default' );
        $blog_archive_full_width = get_theme_mod( 'cf_bc_blog_archive_layout', false );
        $blog_single_full_width  = get_theme_mod( 'cf_bc_blog_single_layout', false );

        // Archives.
        if ( 'meta-inline-bottom' === $post_layout_archive && $this->is_blog_archive() ) {
            remove_action( 'storefront_loop_post', 'storefront_post_meta', 20 );
            add_action( 'storefront_loop_post',    'storefront_post_meta', 35 );
        }

        if ( 'meta-hidden' === $post_layout_archive && $this->is_blog_archive() ) {
            remove_action( 'storefront_loop_post', 'storefront_post_meta', 20 );
        }

        // Single posts.
        if ( 'meta-inline-bottom' === $post_layout_single && is_singular( 'post' ) ) {
            remove_action( 'storefront_single_post', 'storefront_post_meta', 20 );
            add_action( 'storefront_single_post',    'storefront_post_meta', 35 );
        }

        if ( 'meta-hidden' === $post_layout_single && is_singular( 'post' ) ) {
            remove_action( 'storefront_single_post', 'storefront_post_meta', 20 );
        }

        if ( 'meta-inline-bottom' === $post_layout_homepage && is_page_template( 'template-homepage.php' ) ) {
            remove_action( 'storefront_loop_post', 'storefront_post_meta', 20 );
            add_action( 'storefront_loop_post',    'storefront_post_meta', 35 );
        }

        if ( 'meta-hidden' === $post_layout_homepage && is_page_template( 'template-homepage.php' ) ) {
            remove_action( 'storefront_loop_post', 'storefront_post_meta', 20 );
        }

        if ( $this->is_blog_archive() && true === $blog_archive_full_width ) {
            remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
        }

        if ( is_singular( 'post' ) && true === $blog_single_full_width ) {
            remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
        }
    }

    /**
     * Applies classes to the post tag.
     *
     * @param  array $classes The classes.
     * @return array $classes The classes.
     */
    public function post_class( $classes ) {
        $magazine = get_theme_mod( 'cf_bc_magazine_layout', false );

        if ( true === $magazine && $this->is_blog_archive() ) {
            global $wp_query;

            // Set "odd" or "even" class if is not single.
            $classes[] = $wp_query->current_post % 2 == 0 ? 'cf-bc-even' : 'cf-bc-odd';
        }

        return $classes;
    }

    /**
     * Display the blog posts on the homepage
     *
     * @return void
     */
    public static function homepage_blog() {
        $display_homepage_blog = get_theme_mod( 'cf_bc_homepage_blog_toggle', false );
        $title                 = get_theme_mod( 'cf_bc_homepage_blog_title', esc_html__( 'Recent Blog Posts', 'cartfront' ) );
        $homepage_blog_columns = get_theme_mod( 'cf_bc_homepage_blog_columns', '2' );
        $homepage_blog_limit   = get_theme_mod( 'cf_bc_homepage_blog_limit', 2 );

        if ( true === $display_homepage_blog ) {
            $args   = array(
                'post_type'           => 'post',
                'posts_per_page'      => absint( $homepage_blog_limit ),
                'ignore_sticky_posts' => true,
            );

            $query  = new WP_Query( $args );

            echo '<div class="storefront-product-section storefront-blog columns-' . esc_attr( $homepage_blog_columns ) . '">';

            echo apply_filters( 'storefront_homepage_blog_section_title_html', $blog_section_title = '<h2 class="section-title">' . esc_attr( $title ) . '</h2>', $title );

            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    get_template_part( 'content' );
                }

                wp_reset_postdata();
            } else {
                echo '<p>' . esc_attr__( 'Sorry, no posts matched your criteria.', 'cartfront' ) . '</p>';
            }

            echo '</div>';
        }
    }

    /**
     * Returns true when viewing a non WooCommerce archive.
     *
     * @return bool
     */
    private function is_blog_archive() {
        return ! ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) && ( is_archive() || is_search() || is_category() || is_tag() || ( is_home() && ! is_page_template( 'template-homepage.php' ) ) );
    }

}
endif;

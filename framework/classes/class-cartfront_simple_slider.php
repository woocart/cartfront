<?php
/**
 * Simple slider class for the theme.
 *
 * @package cartfront
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Cartfront_Simple_Slider' ) ) :
class Cartfront_Simple_Slider {

    /**
     * Constructor function.
     *
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function __construct() {
        add_action( 'customize_register', array( &$this, 'customize_register' ) );
    }

    /**
     * Customizer controls and settings
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    public function customize_register( $wp_customize ) {
        global $cartfront_path;

        /**
         * Customizer controls.
         */
        require_once $cartfront_path . '/framework/admin/customizer/posts.php';
        require_once $cartfront_path . '/framework/admin/customizer/repeater.php';

        /**
         * Add the panel.
         */
        $wp_customize->add_panel( 'cf_ss_panel', array(
            'priority'          => 60,
            'capability'        => 'edit_theme_options',
            'theme_supports'    => '',
            'title'             => esc_html__( 'Slider', 'cartfront' ),
            'description'       => esc_html__( 'Configure the slider to be shown on the homepage.', 'cartfront' )
        ) );

        /**
         * General section.
         */
        $wp_customize->add_section( 'cf_ss_general' , array(
            'title'         => esc_html__( 'General Settings', 'cartfront' ),
            'priority'      => 10,
            'description'   => esc_html__( 'Configure general settings for the slider section.', 'cartfront' ),
            'panel'         => 'cf_ss_panel'
        ) );

        /**
         * Posts slider section.
         */
        $wp_customize->add_section( 'cf_ss_posts' , array(
            'title'         => esc_html__( 'Posts Slider', 'cartfront' ),
            'priority'      => 20,
            'description'   => esc_html__( 'Configure posts slider settings.', 'cartfront' ),
            'panel'         => 'cf_ss_panel'
        ) );

        /**
         * Products slider section.
         */
        $wp_customize->add_section( 'cf_ss_products' , array(
            'title'         => esc_html__( 'Products Slider', 'cartfront' ),
            'priority'      => 30,
            'description'   => esc_html__( 'Configure products slider settings.', 'cartfront' ),
            'panel'         => 'cf_ss_panel'
        ) );

        /**
         * Custom slider section.
         */
        $wp_customize->add_section( 'cf_ss_custom' , array(
            'title'         => esc_html__( 'Custom Slider', 'cartfront' ),
            'priority'      => 40,
            'description'   => esc_html__( 'Configure custom slider settings.', 'cartfront' ),
            'panel'         => 'cf_ss_panel'
        ) );

        /**
         * Select slider.
         */
        $wp_customize->add_setting( 'cf_ss_choice', array(
            'default'           => 'posts',
            'sanitize_callback' => 'storefront_sanitize_choices'
        ) );

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_ss_choice', array(
            'label'    => esc_html__( 'Select Slider', 'cartfront' ),
            'description'   => esc_html__( 'Select the slider you would like to display.', 'cartfront' ),
            'section'  => 'cf_ss_general',
            'settings' => 'cf_ss_choice',
            'type'     => 'select',
            'priority' => 10,
            'choices'  => array(
                'posts'     => esc_html__( 'Posts Slider', 'cartfront' ),
                'products'  => esc_html__( 'Products Slider', 'cartfront' ),
                'custom'    => esc_html__( 'Custom Slider', 'cartfront' )
            )
        ) ) );

        /**
         * Number of posts.
         */
        $wp_customize->add_setting( 'cf_ss_count', array(
            'default'           => '5',
            'sanitize_callback' => 'storefront_sanitize_choices'
        ) );

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_ss_count', array(
            'label'    => esc_html__( 'Number of Slider Items', 'cartfront' ),
            'section'  => 'cf_ss_general',
            'settings' => 'cf_ss_count',
            'type'     => 'select',
            'priority' => 15,
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
                '12' => '12',
                '13' => '13',
                '14' => '14',
                '15' => '15'
            )
        ) ) );


        /**
         * Slider direction.
         */
        $wp_customize->add_setting( 'cf_ss_direction', array(
            'default'           => 'left',
            'sanitize_callback' => 'storefront_sanitize_choices'
        ) );

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_ss_direction', array(
            'label'    => esc_html__( 'Slider Direction', 'cartfront' ),
            'section'  => 'cf_ss_general',
            'settings' => 'cf_ss_direction',
            'type'     => 'select',
            'priority' => 25,
            'choices'  => array(
                'left'  => esc_html__( 'Left-to-Right', 'cartfront' ),
                'right' => esc_html__( 'Right-to-Left', 'cartfront' )
            )
        ) ) );

        /**
         * Slider animation.
         */
        $wp_customize->add_setting( 'cf_ss_animation', array(
            'default'           => 'slide',
            'sanitize_callback' => 'storefront_sanitize_choices'
        ) );

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_ss_animation', array(
            'label'         => esc_html__( 'Slider Animation', 'cartfront' ),
            'description'   => esc_html__( 'Select the animation when slide transition occurs.', 'cartfront' ),
            'section'       => 'cf_ss_general',
            'settings'      => 'cf_ss_animation',
            'type'          => 'select',
            'priority'      => 30,
            'choices'       => array(
                'slide' => esc_html__( 'Slide In', 'cartfront' ),
                'fade'  => esc_html__( 'Fade In', 'cartfront' )
            )
        ) ) );

        /**
         * Posts.
         */
        $wp_customize->add_setting( 'cf_ss_posts', array(
            'default'           => '',
            'sanitize_callback' => 'cartfront_sanitize_multiselect'
        ) );

        $wp_customize->add_control( new WP_Customize_Posts_Control( $wp_customize, 'cf_ss_posts', array(
            'label'         => esc_html__( 'Posts', 'cartfront' ),
            'description'   => esc_html__( 'Select the posts to be shown in the slider.', 'cartfront' ),
            'section'       => 'cf_ss_posts',
            'settings'      => 'cf_ss_posts',
            'priority'      => 10
        ) ) );

        /**
         * Posts order.
         */
        $wp_customize->add_setting( 'cf_ss_posts_order', array(
            'default'           => 'ASC',
            'sanitize_callback' => 'storefront_sanitize_choices'
        ) );

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_ss_posts_order', array(
            'label'         => esc_html__( 'Posts Order', 'cartfront' ),
            'description'   => esc_html__( 'Select the order in which you would like the posts to appear in the slider.' ),
            'section'       => 'cf_ss_posts',
            'settings'      => 'cf_ss_posts_order',
            'type'          => 'select',
            'priority'      => 15,
            'choices'       => array(
                'DESC' => esc_html__( 'Descending', 'cartfront' ),
                'ASC'  => esc_html__( 'Ascending', 'cartfront' ),
                'rand' => esc_html__( 'Random', 'cartfront' )
            )
        ) ) );

        /**
         * Products type.
         */
        $wp_customize->add_setting( 'cf_ss_products_type', array(
            'default'           => 'recent',
            'sanitize_callback' => 'storefront_sanitize_choices'
        ) );

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_ss_products_type', array(
            'label'    => esc_html__( 'Products Type to Show', 'cartfront' ),
            'section'  => 'cf_ss_products',
            'settings' => 'cf_ss_products_type',
            'type'     => 'select',
            'priority' => 10,
            'choices'  => array(
                'featured'      => esc_html__( 'Featured Products', 'cartfront' ),
                'total_sales'   => esc_html__( 'Best Selling Products', 'cartfront' ),
                'recent'        => esc_html__( 'Recent Products', 'cartfront' ),
                'top_rated'     => esc_html__( 'Top Rated Products', 'cartfront' ),
                'sale'          => esc_html__( 'On Sale Products', 'cartfront' )
            )
        ) ) );

        /**
         * Custom slider (using repeater control).
         */
        $wp_customize->add_setting( 'cf_ss_custom_items', array(
            'default'           => '',
            'sanitize_callback' => 'cartfront_sanitize_repeater'
        ) );

        $wp_customize->add_control( new WP_Customize_Repeater_Control( $wp_customize, 'cf_ss_custom_items', array(
            'label'     => esc_html__( 'Slides', 'cartfront' ),
            'description'   => esc_html__( 'Add slides to the custom slider using this option.', 'cartfront' ),
            'section'       => 'cf_ss_custom',
            'settings'      => 'cf_ss_custom_items',
            'priority'      => 10
        ) ) );
    }

}
endif;

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
        add_action( 'homepage', array( &$this, 'cartfront_slider' ), 20 );
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
         * Section title.
         */
        $wp_customize->add_setting( 'cf_ss_section_title', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field'
        ) );

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_ss_section_title', array(
            'label'         => esc_html__( 'Section Title', 'cartfront' ),
            'description'   => esc_html__( 'Provide a title to the slider section to be shown on the homepage.', 'cartfront' ),
            'section'       => 'cf_ss_general',
            'settings'      => 'cf_ss_section_title',
            'type'          => 'text',
            'priority'      => 15
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
            'description'   => esc_html__( 'Total number of items to be shown in the slider.', 'cartfront' ),
            'section'  => 'cf_ss_general',
            'settings' => 'cf_ss_count',
            'type'     => 'select',
            'priority' => 20,
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
         * Items in a row.
         */
        $wp_customize->add_setting( 'cf_ss_items_row', array(
            'default'           => '3',
            'sanitize_callback' => 'storefront_sanitize_choices'
        ) );

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_ss_items_row', array(
            'label'         => esc_html__( 'Items in a Row', 'cartfront' ),
            'description'   => esc_html__( 'Select the number of items to be shown in a row.', 'cartfront' ),
            'section'       => 'cf_ss_general',
            'settings'      => 'cf_ss_items_row',
            'type'          => 'select',
            'priority'      => 15,
            'choices'       => array(
                '1'  => '1',
                '2'  => '2',
                '3'  => '3',
                '4'  => '4'
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
                'asc'   => esc_html__( 'Descending', 'cartfront' ),
                'desc'  => esc_html__( 'Ascending', 'cartfront' ),
                'rand'  => esc_html__( 'Random', 'cartfront' )
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

    public static function cartfront_slider() {
        $slider_type    = esc_html( get_theme_mod( 'cf_ss_choice', 'posts' ) );
        $posts_count    = absint( get_theme_mod( 'cf_ss_count', 5 ) );
        $posts_row      = absint( get_theme_mod( 'cf_ss_items_row' ), 3 );

        // Posts.
        if ( 'posts' === $slider_type ) {
            $cf_ss_posts = get_theme_mod( 'cf_ss_posts' );

            if ( ! empty( $cf_ss_posts ) && is_array( $cf_ss_posts ) ) {
                // Posts order.
                $posts_order = esc_html( get_theme_mod( 'cf_ss_posts_order', 'asc' ) );


                // Sorting posts.
                if ( 'asc' === $posts_order ) {
                    sort( $cf_ss_posts );
                } elseif ( 'desc' === $posts_order ) {
                    rsort( $cf_ss_posts );
                } else {
                    shuffle( $cf_ss_posts );
                }

        ?>
            <section class="storefront-product-section cartfront-featured-section cartfront-columns-<?php echo $posts_row; ?>">
                <?php

                    // Section title.
                    if ( get_theme_mod( 'cf_ss_section_title' ) ) {
                        echo '<h2 class="section-title">' . esc_html( get_theme_mod( 'cf_ss_section_title' ) ) . '</h2>';
                    }

                ?>
                <div class="cartfront-featured-container">
                    <?php

                        $cf_i = 0;

                        foreach ( $cf_ss_posts as $cf_ss_post ) {
                            if ( $cf_i >= $posts_count ) {
                                break;
                            }

                            $cf_ss_ft_post  = absint( $cf_ss_post );
                            $cf_ss_ft_data  = get_post( $cf_ss_post );
                            $cf_ss_ft_tid   = get_post_thumbnail_id( $cf_ss_post );
                            if ( 1 === $posts_row ) {
                                $cf_ss_ft_img = wp_get_attachment_image_src( $cf_ss_ft_tid, 'full' );
                            } else {
                                $cf_ss_ft_img = wp_get_attachment_image_src( $cf_ss_ft_tid, 'medium' );
                            }

                            // URL Exists?
                            if ( isset( $cf_ss_ft_img[0] ) ) {

                    ?>
                            <div class="cartfront-featured-wrapper">
                                <a href="<?php echo esc_url( get_permalink( $cf_ss_post ) ); ?>">
                                    <img src="<?php echo $cf_ss_ft_img[0]; ?>" alt="<?php echo $cf_ss_ft_data->post_title; ?>">

                                    <h2><?php echo $cf_ss_ft_data->post_title; ?></h2>
                                 </a>
                            </div><!-- .cartfront-featured-wrapper -->
                    <?php

                                // Increment
                                ++$cf_i;
                            }
                        }

                    ?>
                </div><!-- .cartfront-featured-container -->
            </section><!-- .cartfront-featured-section -->
        <?php

            }
        } elseif ( 'products' === $slider_type ) {
            // Verify that WooCommerce is active.
            if ( class_exists( 'WooCommerce' ) ) {
                $products_type  = esc_html( get_theme_mod( 'cf_ss_products_type' ) );

                if ( 'top_rated' === $products_type ) {
                    add_filter( 'posts_clauses', array( 'WC_Shortcodes', 'order_by_rating_post_clauses' ) );

                    $args = array(
                        'post_type'         => 'product',
                        'posts_per_page'    => $posts_count,
                        'no_found_rows'     => 1,
                        'post_status'       => 'publish'
                    );

                    $args['meta_query'] = WC()->query->get_meta_query();
                } elseif ( 'featured' === $products_type ) {
                    $args = array(
                        'post_type'         => 'product',
                        'posts_per_page'    => $posts_count,
                        'meta_key'          => '_featured',
                        'meta_value'        => 'yes'
                    );
                } elseif ( 'sale' === $products_type ) {
                    $args = array(
                        'post_type'         => 'product',
                        'posts_per_page'    => $posts_count,
                        'meta_query'        => array(
                            'relation' => 'OR',
                            array(
                                'key'       => '_sale_price',
                                'value'     => 0,
                                'compare'   => '>',
                                'type'      => 'numeric'
                            ),
                            array(
                                'key'       => '_min_variation_sale_price',
                                'value'     => 0,
                                'compare'   => '>',
                                'type'      => 'numeric'
                            )
                        )
                    );
                } elseif ( 'total_sales' === $products_type ) {
                    $args = array(
                        'post_type'         => 'product',
                        'posts_per_page'    => $posts_count,
                        'meta_key'          => 'total_sales',
                        'orderby'           => 'meta_value_num'
                    );
                } elseif ( 'recent' === $products_type ) {
                    $args = array(
                        'post_type'         => 'product',
                        'posts_per_page'    => $posts_count,
                        'stock'             => 1,
                        'orderby'           =>'date',
                        'order'             => 'DESC'
                    );
                } else {
                    $args = array(
                        'post_type'         => 'product',
                        'posts_per_page'    => $posts_count,
                        'stock'             => 1,
                        'orderby'           =>'date',
                        'order'             => 'DESC'
                    );
                }

                $cf_ss_products = new WP_Query( $args );

            ?>
                <section class="storefront-product-section cartfront-featured-section cartfront-columns-<?php echo $posts_row; ?>">
                    <?php

                        // Section title.
                        if ( get_theme_mod( 'cf_ss_section_title' ) ) {
                            echo '<h2 class="section-title">' . esc_html( get_theme_mod( 'cf_ss_section_title' ) ) . '</h2>';
                        }

                    ?>
                    <div class="cartfront-featured-container">
                    <?php

                        while ( $cf_ss_products->have_posts() ) {
                            $cf_ss_products->the_post();

                            global $product, $post;

                    ?>
                        <div class="cartfront-featured-wrapper">
                            <?php

                                if ( 1 === $posts_row ) {
                            
                            ?>
                                    <div class="cartfront-fw-thumbnail">
                                        <?php

                                            if ( has_post_thumbnail( $cf_ss_products->post->ID ) ) {
                                                echo get_the_post_thumbnail( $cf_ss_products->post->ID, 'full' );
                                            } else {
                                                echo wc_placeholder_img( 'full' );
                                            }

                                        ?>
                                    </div><!-- .cartfront-fw-thumbnail -->
                                    <div class="cartfront-fw-content">
                                        <h2><?php echo get_the_title(); ?></h2>
                                        <p class="excerpt"><?php echo get_the_excerpt(); ?></p>
                                        <p class="price"><?php echo $product->get_price_html(); ?></p>

                                        <a href="<?php echo esc_url( get_permalink( $cf_ss_products->post->ID ) ); ?>" class="button" title="<?php echo esc_attr( $cf_ss_products->post->post_title ? $cf_ss_products->post->post_title : $cf_ss_products->post->ID ); ?>"><?php esc_html_e( 'View Product', 'cartfront' ); ?></a>
                                    </div><!-- .cartfront-fw-content -->
                            <?php

                                } else {

                            ?>
                                    <a href="<?php echo esc_url( get_permalink( $cf_ss_products->post->ID ) ); ?>">
                                        <?php

                                            if ( has_post_thumbnail( $cf_ss_products->post->ID ) ) {
                                                echo get_the_post_thumbnail( $cf_ss_products->post->ID, 'woocommerce_thumbnail' );
                                            } else {
                                                echo wc_placeholder_img( 'woocommerce_thumbnail' );
                                            }

                                        ?>
                                        <h2><?php echo get_the_title(); ?></h2>
                                        <p class="price"><?php echo $product->get_price_html(); ?></p>
                                    </a>

                                    <a href="<?php echo esc_url( get_permalink( $cf_ss_products->post->ID ) ); ?>" class="button" title="<?php echo esc_attr( $cf_ss_products->post->post_title ? $cf_ss_products->post->post_title : $cf_ss_products->post->ID ); ?>"><?php esc_html_e( 'View Product', 'cartfront' ); ?></a>
                            <?php

                                }

                            ?>
                        </div><!-- .cartfront-featured-wrapper -->
                    <?php

                        }

                    ?>
                    </div><!-- .cartfront-featured-container -->
                </section><!-- .cartfront-featured-section -->
        <?php

            } else {
                echo '<p>' . esc_html__( 'WooCommerce needs to be enabled in order to showcase products in the slider.', 'cartfront' ) . '</p>';
            }
        } else {
            $cf_ss_custom_posts = get_theme_mod( 'cf_ss_custom_items' );
            $cf_ss_custom_posts = json_decode( $cf_ss_custom_posts, true );

            if ( ! empty( $cf_ss_custom_posts ) && is_array( $cf_ss_custom_posts ) ) {

            ?>
                <section class="storefront-product-section cartfront-featured-section cartfront-custom-section cartfront-columns-<?php echo $posts_row; ?>">
                <?php

                    // Section title.
                    if ( get_theme_mod( 'cf_ss_section_title' ) ) {
                        echo '<h2 class="section-title">' . esc_html( get_theme_mod( 'cf_ss_section_title' ) ) . '</h2>';
                    }

                ?>
                <div class="cartfront-featured-container">
                    <?php

                        $cf_i = 0;

                        foreach ( $cf_ss_custom_posts as $cf_ss_custom_post ) {
                            if ( $cf_i >= $posts_count ) {
                                break;
                            }

                            if ( ! empty( $cf_ss_custom_post['link'] ) && ! empty( $cf_ss_custom_post['title'] && ! empty( $cf_ss_custom_post['image_url'] ) ) ) {

                    ?>
                                <div class="cartfront-featured-wrapper">
                                    <a href="<?php echo esc_url( $cf_ss_custom_post['link'] ); ?>">
                                        <img src="<?php echo esc_url( $cf_ss_custom_post['image_url'] ); ?>" alt="<?php echo esc_attr( $cf_ss_custom_post['title'] ); ?>">
                                        <h2><?php echo esc_html( $cf_ss_custom_post['title'] ); ?></h2>
                                    </a>
                                </div><!-- .cartfront-featured-wrapper -->
                    <?php

                            }

                            // Increment
                            ++$cf_i;
                        }

                    ?>
                </div><!-- .cartfront-featured-container -->
            </section><!-- .cartfront-featured-section -->
        <?php

            }
        }
    }

}
endif;

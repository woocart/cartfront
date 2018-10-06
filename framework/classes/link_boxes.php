<?php
/**
 * Link boxes class for the theme.
 *
 * @package cartfront
 */

namespace Niteo\WooCart\CartFront {

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    use Niteo\WooCart\CartFront\Customizer\Repeater_Control;
    use WP_Customize_Control;

    if ( ! class_exists( 'Link_Boxes' ) ) :
    class Link_Boxes {

        /**
         * Constructor function.
         *
         * @access  public
         * @since   1.0.0
         */
        public function __construct() {
        	add_action( 'customize_register', array( &$this, 'customize_register' ) );
        	add_action( 'homepage', array( &$this, 'cartfront_link_boxes' ), 25 );
        }

        /**
         * Customizer controls and settings
         *
         * @param WP_Customize_Manager $wp_customize Theme Customizer object.
         */
        public function customize_register( $wp_customize ) {
            global $cartfront_path;

            /**
             * Add a new section.
             */
            $wp_customize->add_section( 'cf_lb_section' , array(
                'title'         => esc_html__( 'Link Boxes', 'cartfront' ),
                'priority'      => 65,
                'description'   => esc_html__( 'Configure settings for link boxes.', 'cartfront' )
            ) );

            /**
             * Section title.
             */
            $wp_customize->add_setting( 'cf_lb_section_title', array(
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field'
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_lb_section_title', array(
                'label'     	=> esc_html__( 'Section Title', 'cartfront' ),
                'description'   => esc_html__( 'Provide a title to the boxes section to be shown on the homepage.', 'cartfront' ),
                'section'       => 'cf_lb_section',
                'settings'      => 'cf_lb_section_title',
                'type' 			=> 'text',
                'priority'      => 10
            ) ) );

            /**
             * Items in a row.
             */
            $wp_customize->add_setting( 'cf_lb_items_row', array(
                'default'           => '4',
                'sanitize_callback' => 'storefront_sanitize_choices'
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'cf_lb_items_row', array(
                'label'    		=> esc_html__( 'Items in a Row', 'cartfront' ),
                'description' 	=> esc_html__( 'Select the number of items to be shown in a row.', 'cartfront' ),
                'section'  		=> 'cf_lb_section',
                'settings' 		=> 'cf_lb_items_row',
                'type'     		=> 'select',
                'priority' 		=> 15,
                'choices'  		=> array(
                    '2'  => '2',
                    '3'  => '3',
                    '4'  => '4'
                )
            ) ) );

            /**
             * Link boxes (using repeater control).
             */
            $wp_customize->add_setting( 'cf_lb_items', array(
                'default'           => '',
                'sanitize_callback' => 'Niteo\WooCart\CartFront\sanitize_repeater'
            ) );

            $wp_customize->add_control( new Repeater_Control( $wp_customize, 'cf_lb_items', array(
                'label'     => esc_html__( 'Boxes', 'cartfront' ),
                'description'   => esc_html__( 'Add boxes to showcase important links on the homepage.', 'cartfront' ),
                'section'       => 'cf_lb_section',
                'settings'      => 'cf_lb_items',
                'priority'      => 20
            ) ) );
        }

        /**
    	 * Render view for boxes on the homepage.
         * @codeCoverageIgnore
    	 */
        public static function cartfront_link_boxes() {
        	$cf_lb_items = get_theme_mod( 'cf_lb_items' );
    		$cf_lb_items = json_decode( $cf_lb_items, true );

            if ( ! empty( $cf_lb_items ) && is_array( $cf_lb_items ) ) {

    	?>
    			<section class="storefront-product-section cartfront-boxes-section">
    	        <?php

    	        	// Section title.
    	        	if ( get_theme_mod( 'cf_lb_section_title' ) ) {
                    	echo '<h2 class="section-title">' . esc_html( get_theme_mod( 'cf_lb_section_title' ) ) . '</h2>';
                    }

                ?>
    		    <div class="cartfront-boxes-container cartfront-columns-<?php echo absint( get_theme_mod( 'cf_lb_items_row', 4 ) ); ?>">
    		    	<?php

    	            	foreach ( $cf_lb_items as $cf_lb_item ) {
                            if ( ! empty( $cf_lb_item['link'] ) && ! empty( $cf_lb_item['title'] && ! empty( $cf_lb_item['image_url'] ) ) ) {

                    ?>
    		        			<div class="cartfront-boxes-wrapper">
    	                        	<a href="<?php echo esc_url( $cf_lb_item['link'] ); ?>">
    			            			<img src="<?php echo esc_url( $cf_lb_item['image_url'] ); ?>" alt="<?php echo esc_attr( $cf_lb_item['title'] ); ?>">
    			            			<h2><?php echo esc_html( $cf_lb_item['title'] ); ?></h2>
    		                        </a>
    				    		</div><!-- .cartfront-boxes-wrapper -->
    	            <?php

                            }
    		            }

    	        	?>
    	    	</div><!-- .cartfront-boxes-container -->
    		</section><!-- .cartfront-boxes-section -->
    	<?php

    		}
        }

    }
    endif;

}

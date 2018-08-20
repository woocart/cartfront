<?php
/**
 * Functions used in the theme.
 *
 * @package cartfront
 */

/**
 * 
 */
if ( ! function_exists( 'cartfront_frontend_slider' ) ) :
function cartfront_frontend_slider() {
	$slider_type = get_theme_mod( 'cf_ss_choice', 'posts' );

	// Posts.
	if ( 'posts' === $slider_type ) {
		$cf_ss_posts = get_theme_mod( 'cf_ss_posts' );

		if ( ! empty( $cf_ss_posts ) && is_array( $cf_ss_posts ) ) {
			$cf_ss_items = count( $cf_ss_posts );
	?>
		<section class="storefront-product-section cartfront-featured-section">
	        <?php

	        	// Section title.
                echo '<h2 class="section-title">' . esc_html( get_theme_mod( 'cf_ss_section_title', __( 'Featured Posts', 'cartfront' ) ) ) . '</h2>';

            ?>
		    <div class="cartfront-featured-container">
		        <?php

		            foreach ( $cf_ss_posts as $cf_ss_post ) {
		                $cf_ss_ft_post 	= absint( $cf_ss_post );
		                $cf_ss_ft_data 	= get_post( $cf_ss_post );
		                $cf_ss_ft_tid 	= get_post_thumbnail_id( $cf_ss_post );
		                $cf_ss_ft_img 	= wp_get_attachment_image_src( $cf_ss_ft_tid, 'medium' );

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
			$products_type 	= get_theme_mod( 'cf_ss_products_type' );
			$products_count = get_theme_mod( 'cf_ss_count', 5 );

			if ( 'top_rated' === $products_type ) {
				add_filter( 'posts_clauses', array( WC()->query, 'order_by_rating_post_clauses' ) );

				$args = array(
					'post_type' 		=> 'product',
					'posts_per_page' 	=> $products_count,
					'no_found_rows' 	=> 1,
					'post_status' 		=> 'publish'
				);

				$args['meta_query'] = WC()->query->get_meta_query();
			} elseif ( 'featured' === $products_type ) {
				$args = array(
					'post_type' 		=> 'product',
					'posts_per_page' 	=> $products_count,
					'meta_key' 			=> '_featured',
					'meta_value' 		=> 'yes'
				);
			} elseif ( 'sale' === $products_type ) {
				$args = array(
					'post_type' 		=> 'product',
					'posts_per_page' 	=> $products_count,
					'meta_query' 		=> array(
						'relation' => 'OR',
						array(
							'key' 		=> '_sale_price',
							'value' 	=> 0,
							'compare' 	=> '>',
							'type' 		=> 'numeric'
						),
						array(
							'key' 		=> '_min_variation_sale_price',
							'value' 	=> 0,
							'compare' 	=> '>',
							'type' 		=> 'numeric'
						)
					)
				);
			} elseif ( 'total_sales' === $products_type ) {
				$args = array(
					'post_type' 		=> 'product',
					'posts_per_page' 	=> $products_count,
					'meta_key' 			=> 'total_sales',
					'orderby' 			=> 'meta_value_num'
				);
			} elseif ( 'recent' === $products_type ) {
				$args = array(
					'post_type' 		=> 'product',
					'posts_per_page' 	=> $products_count,
					'stock' 			=> 1,
					'orderby' 			=>'date',
					'order' 			=> 'DESC'
				);
			} else {
				$args = array(
					'post_type' 		=> 'product',
					'posts_per_page' 	=> $products_count,
					'stock' 			=> 1,
					'orderby' 			=>'date',
					'order' 			=> 'DESC'
				);
			}

			$cf_ss_products = new WP_Query( $args );

		?>
			<section class="storefront-product-section cartfront-featured-section">
		        <?php

		        	// Section title.
	                echo '<h2 class="section-title">' . esc_html( get_theme_mod( 'cf_ss_section_title', __( 'Featured Posts', 'cartfront' ) ) ) . '</h2>';

	            ?>
			    <div class="cartfront-featured-container">
				<?php

					while ( $cf_ss_products->have_posts() ) {
						$cf_ss_products->the_post();

						global $product, $post;

				?>
					<div class="cartfront-featured-wrapper">
                        <a href="<?php echo esc_url( get_permalink( $cf_ss_products->post->ID ) ); ?>">
                        	<?php

                        		if ( has_post_thumbnail( $cf_ss_products->post->ID ) ) {
                        			echo get_the_post_thumbnail( $cf_ss_products->post->ID, 'shop_catalog' );
                        		} else {
                        			echo wc_placeholder_img( 'woocommerce_thumbnail' );
                        		}

                        	?>
							<h2><?php echo get_the_title(); ?></h2>
							<p class="price"><?php echo $product->get_price_html(); ?></p>
                        </a>

						<a href="<?php echo esc_url( get_permalink( $cf_ss_products->post->ID ) ); ?>" class="button" title="<?php echo esc_attr( $cf_ss_products->post->post_title ? $cf_ss_products->post->post_title : $cf_ss_products->post->ID ); ?>"><?php esc_html_e( 'View Product', 'cartfront' ); ?></a>
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

	}
}
add_action( 'homepage', 'cartfront_frontend_slider', 20 );
endif;
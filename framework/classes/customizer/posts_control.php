<?php

/**
 * Customizer: Posts Control (Multi-Select)
 *
 * @package cartfront
 */

namespace Niteo\WooCart\CartFront\Customizer {

    use WP_Customize_Control;

    if ( class_exists( 'WP_Customize_Control' ) ) :
		class Posts_Control extends WP_Customize_Control {

			public $type = '';

			/**
			 * Constructor function.
			 *
			 * @access public
			 * @since  1.0.0
			 * @codeCoverageIgnore
			 */
			public function __construct( $manager, $id, $args = array(), $options = array() ) {

				// Parent constructor.
				parent::__construct( $manager, $id, $args );
			}

			/**
			 * Enqueue control dependencies.
			 *
			 * @access public
			 */
			public function enqueue() {
				global $theme_name, $theme_version, $cartfront_url;

				wp_enqueue_style( $theme_name . '-customizer-select2', $cartfront_url . '/framework/admin/customizer/vendor/css/select2.min.css', array(), $theme_version );

				wp_enqueue_script( $theme_name . '-customizer-select2', $cartfront_url . '/framework/admin/customizer/vendor/js/select2.min.js', array( 'jquery' ), $theme_version, true );

				wp_enqueue_script( $theme_name . '-customizer-posts', $cartfront_url . '/framework/admin/customizer/js/posts.js', array( $theme_name . '-customizer-select2' ), $theme_version, true );
			}

			/**
			 * Let's render the content.
			 *
			 * @access  public
			 * @codeCoverageIgnore
			 */
			public function render_content() {

			?>
				<div class="dropdown_select2_control">
					<?php if ( ! empty( $this->label ) ) : ?>
						<label for="<?php echo esc_attr( $this->id ); ?>" class="customize-control-title">
							<?php echo esc_html( $this->label ); ?>
						</label>
					<?php endif; ?>

					<?php if ( ! empty( $this->description ) ) : ?>
						<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
					<?php endif; ?>

					<select name="<?php echo $this->id . '[]'; ?>" id="<?php echo $this->id; ?>" class="dropdown_select2_select" multiple="multiple" <?php $this->link(); ?>>
						<?php

							/* Get posts */
							$args = array(
								'posts_per_page'   => -1,
								'orderby'          => 'date',
								'order'            => 'DESC',
								'post_type'        => 'post',
								'post_status'      => 'publish',
								'suppress_filters' => true
							);

							$posts = get_posts( $args );

							/* Check */
							if ( ! empty( $posts ) && is_array( $posts ) ) {
								foreach ( $posts as $post ) {
									echo '<option value="' . esc_attr( $post->ID ) . '">' . esc_attr( $post->post_title ) . '</option>';
								}
							}

		 				?>
					</select>
				</div>
			<?php

			}

		}
	endif;

}

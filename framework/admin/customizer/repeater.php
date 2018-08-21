<?php

/**
 * Customizer: Repeater Control
 *
 * @package cartfront
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

class WP_Customize_Repeater_Control extends WP_Customize_Control {

	public $id;

	private $boxtitle;
	private $add_field_label;
	private $allowed_html = array();

	/**
	 * Constructor function.
	 *
	 * @access public
	 * @since  1.0.0
	 */
	public function __construct( $manager, $id, $args = array() ) {
		// Parent constructor.
		parent::__construct( $manager, $id, $args );

		// Default values.
		$this->add_field_label 	= esc_html__( 'Add Slide', 'cartfront' );
		$this->boxtitle 		= esc_html__( 'Slide', 'cartfront' );

		// Set id.
		if ( ! empty( $id ) ) {
			$this->id = $id;
		}

		// Allowed content.
		$allowed_array1 = wp_kses_allowed_html( 'post' );
		$allowed_array2 = array(
			'input' => array(
				'type'        => array(),
				'class'       => array(),
				'placeholder' => array()
			)
		);

		$this->allowed_html = array_merge( $allowed_array1, $allowed_array2 );
	}

	/**
	 * Enqueue control dependencies.
	 *
	 * @access  public
	 */
	public function enqueue() {
		global $theme_name, $theme_version, $cartfront_url;

		wp_enqueue_style( $theme_name . '-customizer-repeater', $cartfront_url . '/framework/admin/customizer/css/repeater.css', array(), $theme_version );

		wp_enqueue_script( $theme_name . '-customizer-repeater', $cartfront_url . '/framework/admin/customizer/js/repeater.js', array( 'jquery', 'jquery-ui-draggable' ), $theme_version, true );
	}

	/**
	 * Let's render the content.
	 *
	 * @access  public
	 */
	public function render_content() {
		// Default options.
		$this_default = json_decode( $this->setting->default );

		// Values - json format.
		$values = $this->value();

		// Decode values.
		$json = json_decode( $values );

		if ( ! is_array( $json ) ) {
			$json = array( $values );
		}

	?>
		<span class="customize-control-title">
			<?php echo esc_html( $this->label ); ?>
		</span><!-- .customize-control-title -->

		<div class="cf-cr-repeater cf-cr-droppable">
			<?php

				if ( ( 1 === count( $json ) && '' === $json[0] ) || empty( $json ) ) {
					if ( ! empty( $this_default ) ) {
						$this->iterate_array( $this_default );

			?>
						<input type="hidden" id="cf-cr-<?php echo esc_attr( $this->id ); ?>-data" <?php esc_attr( $this->link() ); ?> class="cf-cr-data" value="<?php echo esc_textarea( json_encode( $this_default ) ); ?>">
			<?php

					} else {
						$this->iterate_array();

			?>
						<input type="hidden" id="cf-cr-<?php echo esc_attr( $this->id ); ?>-data" <?php esc_attr( $this->link() ); ?> class="cf-cr-data">
			<?php

					}
				} else {
					$this->iterate_array( $json );

			?>
					<input type="hidden" id="cf-cr-<?php echo esc_attr( $this->id ); ?>-data" <?php esc_attr( $this->link() ); ?> class="cf-cr-data" value="<?php echo esc_textarea( $this->value() ); ?>">

			<?php
			
				}

			?>
		</div><!-- .cf-cr-repeater -->

		<button type="button" class="button cf-cr-new-field add_field">
			<?php echo esc_html( $this->add_field_label ); ?>
		</button><!-- .cf-cr-new-field -->

	<?php

	}

	/**
	 * Iterate over all the controls in the array.
	 *
	 * @access  private
	 */
	private function iterate_array( $array = array() ) {
		$it = 0;

		if ( ! empty( $array ) ) {
			foreach ( $array as $single ) {
			?>
				<div class="cf-cr-container cf-cr-draggable">
					<div class="cf-cr-title">
						<?php echo esc_html( $this->boxtitle ) ?>
					</div>

					<div class="cf-cr-content-hidden">
					<?php

						// Empty values.
						$image_url 	= '';
						$title 		= '';
						$link 		= '';

						if ( ! empty( $single->id ) ) {
							$id = $single->id;
						}

						if ( ! empty( $single->image_url ) ) {
							$image_url = $single->image_url;
						}

						if ( ! empty( $single->title ) ) {
							$title = $single->title;
						}

						if ( ! empty( $single->link ) ) {
							$link = $single->link;
						}

						/**
						 * Image control.
						 */
						$this->image_control( $image_url );

						/**
						 * Title control.
						 */
						$this->input_control(
							array(
								'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Title', 'cartfront' ), $this->id, 'customizer_repeater_title_control' ),
								'class' => 'cf-cr--title',
								'type'  => apply_filters( 'customizer_repeater_input_types_filter', '', $this->id, 'customizer_repeater_title_control' )
							), $title
						);

						/**
						 * Link control.
						 */
						$this->input_control(
							array(
								'label' 			=> apply_filters( 'repeater_input_labels_filter', esc_html__( 'Link', 'cartfront' ), $this->id, 'customizer_repeater_link_control' ),
								'class' 			=> 'cf-cr-link',
								'sanitize_callback' => 'esc_url_raw',
								'type' 				=> apply_filters( 'customizer_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link_control' )
							), $link
						);

					?>
						<input type="hidden" class="cf-cr-box-id" value="<?php if ( ! empty( $id ) ) { echo esc_attr( $id ); } ?>">
						<button type="button" class="cf-cr-remove-field button" <?php if ( $it == 0 ) { echo 'style="display:none;"'; } ?>>
							<?php esc_html_e( 'Delete', 'cartfront' ); ?>
						</button><!-- .cf-cr-remove-field -->
					</div><!-- .cf-cr-content-hidden -->
				</div><!-- .cf-cr-container -->
		<?php

				$it++;
			}
		} else {

		?>
			<div class="cf-cr-container">
				<div class="cf-cr-title">
					<?php echo esc_html( $this->boxtitle ) ?>
				</div><!-- .cf-cr-title -->

				<div class="cf-cr-content-hidden">
					<?php

						/**
						 * Image control.
						 */
						$this->image_control();

						/**
						 * Title control.
						 */
						$this->input_control( array(
							'label' => apply_filters( 'customizer_repeater_input_labels_filter', esc_html__( 'Title', 'cartfront' ), $this->id, 'customizer_repeater_title_control' ),
							'class' => 'cf-cr--title',
							'type'  => apply_filters( 'customizer_repeater_input_types_filter', '', $this->id, 'customizer_repeater_title_control' )
						) );

						/**
						 * Link control.
						 */
						$this->input_control( array(
							'label' => apply_filters( 'customizer_repeater_input_labels_filter', esc_html__( 'Link','cartfront' ), $this->id, 'customizer_repeater_link_control' ),
							'class' => 'cf-cr-link',
							'type'  => apply_filters( 'customizer_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link_control' )
						) );

					?>
					<input type="hidden" class="cf-cr-box-id">
					<button type="button" class="cf-cr-remove-field button" style="display:none;">
						<?php esc_html_e( 'Delete', 'cartfront' ); ?>
					</button><!-- .cf-cr-remove-field -->
				</div><!-- .cf-cr-content-hidden -->
			</div><!-- .cf-cr-container -->

<?php

		}
	}

	/**
	 * Input control function.
	 *
	 * @access  private
	 */
	private function input_control( $options, $value = '' ) {
		if ( ! empty( $options['type'] ) ) {
			if ( 'textarea' == $options['type'] ) {

	?>
				<span class="cf-cr-control-title">
					<?php echo esc_html( $options['label'] ); ?>
				</span><!-- .cf-cr-control-title -->

				<textarea class="<?php echo esc_attr( $options['class'] ); ?>"><?php echo esc_attr( $value ); ?></textarea>
	<?php

			}
		} else {

	?>
			<span class="cf-cr-control-title">
				<?php echo esc_html( $options['label'] ); ?>
			</span><!-- .cf-cr-control-title -->

			<input type="text" value="<?php echo esc_attr( $value ); ?>" class="<?php echo esc_attr( $options['class'] ); ?>">
	<?php

		}
	}

	/**
	 * Image control function.
	 *
	 * @access  private
	 */
	private function image_control( $value = '' ) {
	?>
		<div class="cf-cr-image-control">
			<span class="cf-cr-control-title">
				<?php esc_html_e( 'Image', 'cartfront' ); ?>
			</span><!-- .cf-cr-control-title -->

			<input type="text" class="widefat cf-cr-media-url" value="<?php echo esc_attr( $value ); ?>" style="display:none;">
			<div class="cf-cr-media-box" data-lang="<?php esc_html_e( 'Select Background Image', 'cartfront' ); ?>">
				<?php if ( ! empty( $value ) ) : ?>
					<img src="<?php echo esc_attr( $value ); ?>" />
				<?php else : ?>
					<span><?php esc_html_e( 'Select Background Image', 'cartfront' ) ?></span>
				<?php endif; ?>
			</div><!-- .cf-cr-media-box -->

			<input type="button" class="button button-secondary cf-cr-media-button" value="<?php esc_attr_e( 'Upload Image', 'cartfront' ); ?>">
			<input type="button" class="button button-link cf-cr-media-remove" value="<?php esc_attr_e( 'Remove', 'cartfront' ); ?>"<?php if ( empty( $value ) ) { echo ' style="display:none;"'; } ?>>
		</div><!-- .cf-cr-image-control -->
	<?php

	}

}
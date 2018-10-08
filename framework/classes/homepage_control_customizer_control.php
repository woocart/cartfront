<?php
/**
 * The control for the draggable + toggable homepage components.
 *
 * @package cartfront
 */

namespace Niteo\WooCart\CartFront {

    use WP_Customize_Control;

	if ( class_exists( 'WP_Customize_Control' ) ) :
		class Homepage_Control_Customizer_Control extends WP_Customize_Control {

			/**
			 * Enqueue jQuery Sortable and its dependencies.
			 *
			 * @access public
			 */
			public function enqueue() {
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'jquery-ui-sortable' );
			}

			/**
			 * Display list of ordered components.
			 *
			 * @access public
			 */
			public function render_content() {
				if ( ! is_array( $this->choices ) || ! count( $this->choices ) ) {
					esc_html__( 'No homepage components found for the theme. Please contact WooCart to get help on this topic.', 'cartfront' );
				} else {
					$components         = $this->choices;
					$order              = $this->value();
					$disabled			= $this->get_disabled_components( $this->value(), $components );
					?>
					<label>
					<?php
						if ( ! empty( $this->label ) ) : ?>
							<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
						<?php endif;
						if ( ! empty( $this->description ) ) : ?>
							<span class="description customize-control-description"><?php echo $this->description ; ?></span>
						<?php endif;
					?>
						<ul class="cf-hc-components">
							<?php $components = $this->reorder_components( $components, $order ); ?>
							<?php foreach ( $components as $id => $title ) : ?>
								<?php
									$class = '';
									if ( in_array( $id, $disabled ) ) {
										$class = 'disabled';
									}

									/**
									 * Filter the control title.
									 *
									 * @param string $title The control title.
									 * @param string $id The action hook function ID.
									 */
									$title = apply_filters( 'cf_hc_title', $title, $id );

									// Nothing to display.
									if ( empty( $title ) ) {
										continue;
									}
								?>
								<li id="<?php echo esc_attr( $id ); ?>" class="<?php echo $class; ?>"><span class="visibility"></span><?php echo esc_attr( $title ); ?></li>
							<?php endforeach; ?>
						</ul>

						<input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( $this->value() ); ?>"/>
					</label>
					<?php
				}
			}

			/**
			 * Re-order the components in the given array, based on the stored order.
			 *
			 * @access  private
			 * @return  array An array of components, in the correct order.
			 */
			private function reorder_components( $components, $order ) {
				$order_entries = array();

				if ( '' !== $order ) {
					$order_entries = explode( ',', $order );
				}

				// Re-order the components according to the stored order.
				if ( 0 < count( $order_entries ) ) {
					// Make a backup before we overwrite.
					$original_components = $components;
					$components = array();

					foreach ( $order_entries as $k => $v ) {
						if ( $this->is_component_disabled( $v ) ) {
							$v = str_replace( '[disabled]', '', $v );
						}

						// Only add to array if component still exists.
						if ( isset( $original_components[ $v ] ) ) {
							$components[ $v ] = $original_components[ $v ];
							unset( $original_components[ $v ] );
						}
					}

					if ( 0 < count( $original_components ) ) {
						$components = array_merge( $components, $original_components );
					}
				}

				return $components;
			}

			/**
			 * Check if a component is disabled.
			 *
			 * @access  private
			 * @return  boolean True if a component if disabled.
			 */
			private function is_component_disabled( $component ) {
				if ( false !== strpos( $component, '[disabled]' ) ) {
					return true;
				}

				return false;
			}

			/**
			 * Return the disabled components in the given array, based on the format of the key.
			 *
			 * @access  private
			 * @return  array An array of disabled components.
			 */
			private function get_disabled_components( $saved_components, $all_components ) {
				$disabled = array();

				if ( '' !== $saved_components ) {
					$saved_components = explode( ',', $saved_components );

					if ( 0 < count( $saved_components ) ) {
						foreach ( $saved_components as $k => $v ) {
							if ( $this->is_component_disabled( $v ) ) {
								$v = str_replace( '[disabled]', '', $v );
								$disabled[] = $v;
							}
							unset( $all_components[ $v ] );
						}
					}

					// Disable new components.
					if ( 0 < count( $all_components ) ) {
						foreach ( $all_components as $k => $v ) {
							$disabled[] = $k;
						}
					}
				}

				return $disabled;
			}

		}
	endif;

}

<?php
/**
 * Homepage control class for the theme.
 *
 * @package cartfront
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Cartfront_Homepage_Control' ) ) :
class Cartfront_Homepage_Control {

    /**
     * The name of the hook for all the actions.
     *
     * @var     string
     * @access  public
     */
    public $hook;

    /**
     * Constructor function.
     *
     * @access  public
     * @since   1.0.0
     */
    public function __construct() {
        $this->hook = (string) apply_filters( 'cf_hc_hook', 'homepage' );

        /**
         * Re-structure components.
         */
        if ( ! is_admin() ) {
            add_action( 'get_header', array( &$this, 'restructure_components' ) );
        }

        /**
         * Initialize customizer.
         */
        $homepage_control_customizer = new Cartfront_Homepage_Control_Customizer();
    }

    /**
     * Work through the stored data and display the components in the desired order, without the disabled components.
     *
     * @access  public
     * @since   1.0.0
     */
    public function restructure_components () {
        $data       = get_theme_mod( 'cf_hc_data' );
        $components = array();

        if ( isset( $data ) && '' != $data ) {
            $components = explode( ',', $data );

            // Remove all existing actions on `homepage` hook.
            remove_all_actions( $this->hook );

            // Remove disabled components.
            $components = $this->remove_disabled_items( $components );

            // Re-order the components.
            if ( 0 < count( $components ) ) {
                $count = 5;
                foreach ( $components as $k => $v ) {
                    if ( false !== strpos( $v, '@' ) ) {
                        $obj_v = explode( '@' , $v );
                        if ( class_exists( $obj_v[0] ) && method_exists( $obj_v[0], $obj_v[1] ) ) {
                            add_action( $this->hook, array( $obj_v[0], $obj_v[1] ), $count );
                        }
                    } else {
                        if ( function_exists( $v ) ) {
                            add_action( $this->hook, esc_attr( $v ), $count );
                        }
                    }

                    $count + 5;
                }
            }
        }
    }

    /**
     * Maybe remove disabled items from the main ordered array.
     *
     * @access  private
     * @since   1.0.0
     * @param   array $components   Array with components order.
     * @return  array               Re-ordered components with disabled components removed.
     */
    private function remove_disabled_items( $components ) {
        if ( 0 < count( $components ) ) {
            foreach ( $components as $k => $v ) {
                if ( false !== strpos( $v, '[disabled]' ) ) {
                    unset( $components[ $k ] );
                }
            }
        }

        return $components;
    }

}
endif;

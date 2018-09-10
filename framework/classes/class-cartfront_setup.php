<?php
/**
 * Basic setup for the theme.
 *
 * @package cartfront
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Cartfront_Setup' ) ) :
class Cartfront_Setup {

	/**
     * Constructor function.
     *
     * @access  public
     * @since   1.0.0
     */
	public function __construct() {
		add_action( 'after_setup_theme', array( &$this, 'setup' ) );
		add_action( 'init', array( &$this, 'change_branding' ), PHP_INT_MAX );
		add_action( 'admin_menu', array( &$this, 'change_menu' ), 20 );
		add_action( 'admin_enqueue_scripts', array( &$this, 'add_scripts' ) );
		add_action( 'admin_init', array( &$this, 'theme_activation_redirect' ) );
	}

	/**
	 * Basic setup we are doing over here.
	 *
	 * @access public
	 */
	public function setup() {
		global $theme_name;

		/**
		 * Load translations for the theme from the /langs directory.
		 */
		load_theme_textdomain( 'cartfront', get_template_directory() . '/framework/langs' );

		/**
		 * Some more image sizes we will need.
		 */
		add_image_size( $theme_name . '-medium', 600, 400, true );
		add_image_size( $theme_name . '-square', 600, 600, true );
		add_image_size( $theme_name . '-full', 1200, 9999, false );
	}

	/**
	 * Change branding.
	 *
	 * @access public
	 */
	public function change_branding() {
		remove_action( 'storefront_footer', 'storefront_credit', 20 );
		add_action( 'storefront_footer', array( &$this, 'cartfront_credit' ), 20 );
	}

	/**
	 * Cartfront footer credit.
	 *
	 * @access public
	 */
	public function cartfront_credit() {
		?>
		<div class="site-info">
			<?php echo esc_html( apply_filters( 'storefront_copyright_text', $content = '&copy; ' . get_bloginfo( 'name' ) . ' ' . date( 'Y' ) ) ); ?>
			<br />
			<?php echo '<a href="https://woocart.com" target="_blank" title="' . esc_attr__( 'WooCart - Hosted WooCommerce Platform', 'cartfront' ) . '" rel="author">' . esc_html__( 'Built with Cartfront by WooCart', 'cartfront' ) . '</a>.' ?>
		</div><!-- .site-info -->
		<?php
	}

	/**
	 * Change the `storefront` menu to `cartfront` in the admin menu.
	 *
	 * @access public
	 */
	public function change_menu() {
		remove_submenu_page( 'themes.php', 'storefront-welcome' );

		// Add `cartfront` to the themes menu.
		add_theme_page( 'Cartfront', 'Cartfront', 'activate_plugins', 'cartfront-welcome', array( &$this, 'welcome_screen' ) );
	}

	/**
	 * Load admin css.
	 *
	 * @param string $hook_suffix the current page hook suffix.
	 * @return void
	 */
	public function add_scripts( $hook_suffix ) {
		global $theme_name, $theme_version, $cartfront_url;

		if ( 'appearance_page_cartfront-welcome' === $hook_suffix ) {
			wp_enqueue_style( $theme_name . '-welcome-screen', $cartfront_url . '/framework/css/admin.css', $theme_version );
		}
	}

	/**
	 * Welcome screen to be shown on theme activation.
	 *
	 * @access public
	 */
	public function welcome_screen() {
		require_once( ABSPATH . 'wp-load.php' );
		require_once( ABSPATH . 'wp-admin/admin.php' );
		require_once( ABSPATH . 'wp-admin/admin-header.php' );

		global $theme_version, $cartfront_url;
		?>

		<div class="cartfront-wrap">
			<section class="cartfront-welcome-nav">
				<span class="cartfront-welcome-nav__version">Cartfront <?php echo esc_attr( $theme_version ); ?></span>
				<ul>
					<li><a href="#" target="_blank"><?php esc_attr_e( 'Support', 'cartfront' ); ?></a></li>
					<li><a href="#" target="_blank"><?php esc_attr_e( 'Documentation', 'cartfront' ); ?></a></li>
					<li><a href="#" target="_blank"><?php esc_attr_e( 'Blog', 'cartfront' ); ?></a></li>
				</ul>
			</section>

			<div class="cartfront-logo">
				<img src="<?php echo esc_url( $cartfront_url ); ?>/framework/img/woocart.svg" alt="Cartfront" />
			</div>

			<div class="cartfront-intro">
				<p><?php echo sprintf( esc_attr__( 'Hello! Thank you for selecting %sCartfront%s. You can browse through the following links to get acquainted with the product.', 'cartfront' ), '<strong>', '</strong>' ); ?></p>
			</div>

			<div class="cartfront-enhance">
				<div class="cartfront-enhance__column cartfront-bundle">
					<h3><?php esc_attr_e( 'Cartfront Documentation', 'cartfront' ); ?></h3>
					<span class="bundle-image">
						<img src="<?php echo esc_url( $cartfront_url ); ?>/framework/img/admin/documentation.png" alt="<?php esc_attr_e( 'Cartfront Documentation', 'cartfront' ); ?>" />
					</span>

					<p><?php esc_attr_e( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis tincidunt euismod consequat. Praesent nisi leo, posuere ac augue eu, ultrices viverra leo. Donec sapien erat, cursus sit amet velit vel, porttitor pretium magna. Suspendisse quis tellus non tellus mollis rutrum. Curabitur tempor sodales eros in fermentum. Quisque vel felis iaculis, ullamcorper justo eget, malesuada turpis. Fusce tincidunt ipsum nec mauris maximus, id porta erat tristique.', 'cartfront' ); ?></p>

					<p><a href="#" class="cartfront-button" target="_blank"><?php esc_attr_e( 'Browse Documentation', 'cartfront' ); ?></a></p>
				</div>

				<div class="cartfront-enhance__column cartfront-child-themes">
					<h3><?php esc_attr_e( 'WooCart Support', 'cartfront' ); ?></h3>
					<img src="<?php echo esc_url( $cartfront_url ); ?>/framework/img/admin/help.png" alt="cartfront Powerpack" />

					<p><?php esc_attr_e( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis tincidunt euismod consequat. Praesent nisi leo, posuere ac augue eu, ultrices viverra leo. Donec sapien erat, cursus sit amet velit vel, porttitor pretium magna. Suspendisse quis tellus non tellus mollis rutrum. Curabitur tempor sodales eros in fermentum. Quisque vel felis iaculis, ullamcorper justo eget, malesuada turpis. Fusce tincidunt ipsum nec mauris maximus, id porta erat tristique.', 'cartfront' ); ?></p>

					<p><a href="#" class="cartfront-button" target="_blank"><?php esc_attr_e( 'Visit Help Center', 'cartfront' ); ?></a></p>
				</div>
			</div>
		</div>
		<?php
	} 
 
	/**
	 * Redirect to welcome page on activation.
	 */
	public function theme_activation_redirect() {
	    global $pagenow;

	    if ( 'themes.php' == $pagenow && is_admin() && isset( $_GET['activated'] ) ) {
	        wp_redirect( esc_url_raw( add_query_arg( 'page', 'cartfront-welcome', admin_url( 'themes.php' ) ) ) );
	    }
	}

}
endif;
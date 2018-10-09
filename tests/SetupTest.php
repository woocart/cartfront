<?php
/**
 * Tests the setup class.
 *
 * @package cartfront
 */

use Niteo\WooCart\CartFront\Setup;
use PHPUnit\Framework\TestCase;

class SetupTest extends TestCase {

	function setUp() {
		\WP_Mock::setUsePatchwork( true );
		\WP_Mock::setUp();
	}

	function tearDown() {
		$this->addToAssertionCount(
			\Mockery::getContainer()->mockery_getExpectationCount()
		);

		\WP_Mock::tearDown();
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Setup::__construct
	 */	 
	public function testConstructor() {
		$setup = new Setup();

		\WP_Mock::expectActionAdded( 'after_setup_theme', [ $setup, 'setup' ] );
		\WP_Mock::expectActionAdded( 'init', [ $setup, 'change_branding' ], PHP_INT_MAX );
		\WP_Mock::expectActionAdded( 'admin_menu', [ $setup, 'change_menu' ], 20 );
		\WP_Mock::expectActionAdded( 'admin_enqueue_scripts', [ $setup, 'add_scripts' ] );
		\WP_Mock::expectActionAdded( 'admin_init', [ $setup, 'theme_activation_redirect' ] );

        $setup->__construct();
		\WP_Mock::assertHooksAdded();
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Setup::__construct
	 * @covers \Niteo\WooCart\CartFront\Setup::setup
	 */
	public function testSetup() {
		$setup = new Setup();

		\WP_Mock::userFunction(
			'get_template_directory', [
				'return' => 'directory'
			]
		);
		\WP_Mock::userFunction(
			'load_theme_textdomain', [
				'args' => [
					'cartfront',
					'directory/framework/langs'
				],
				'return' => true
			]
		);
		\WP_Mock::userFunction(
			'add_image_size', [
				'args' => [
					'-medium',
					600,
					400,
					true
				]
			]
		);
		\WP_Mock::userFunction(
			'add_image_size', [
				'args' => [
					'-square',
					600,
					600,
					true
				]
			]
		);
		\WP_Mock::userFunction(
			'add_image_size', [
				'args' => [
					'-full',
					1200,
					9999,
					false
				]
			]
		);
		\WP_Mock::userFunction(
			'unregister_nav_menu', [
				'args' => [ 'secondary' ]
			]
		);
		\WP_Mock::userFunction(
			'register_nav_menus', [
				'args' => [
					[ 'footer' => 'Footer Menu' ]
				]
			]
		);

		$setup->setup();
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Setup::__construct
	 * @covers \Niteo\WooCart\CartFront\Setup::change_branding
	 */
	public function testChangeBranding() {
		$setup = new Setup();

		\WP_Mock::userFunction(
			'remove_action', [
				'args' => [
					'storefront_footer',
					'storefront_credit',
					20
				]
			]
		);
		\WP_Mock::expectActionAdded( 'storefront_footer', [ $setup, 'cartfront_credit' ], 20 );

		$setup->change_branding();
		\WP_Mock::assertHooksAdded();
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Setup::__construct
	 * @covers \Niteo\WooCart\CartFront\Setup::change_menu
	 */
	public function testChangeMenu() {
		$setup = new Setup();

		\WP_Mock::userFunction(
			'remove_submenu_page', [
				'args' => [
					'themes.php',
					'storefront-welcome'
				]
			]
		);
		\WP_Mock::userFunction(
			'add_theme_page', [
				'args' => [
					'Cartfront',
					'Cartfront',
					'activate_plugins',
					'cartfront-welcome',
					[
						$setup,
						'welcome_screen'
					]
				]
			]
		);

		$setup->change_menu();
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Setup::__construct
	 * @covers \Niteo\WooCart\CartFront\Setup::add_scripts
	 */
	public function testAddScripts() {
		$setup = new Setup();

		\WP_Mock::userFunction(
			'wp_enqueue_style', [
				'args' => [
					'-welcome-screen',
					'/framework/css/admin.css',
					''
				]
			]
		);

		$setup->add_scripts( 'appearance_page_cartfront-welcome' );
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Setup::__construct
	 * @covers \Niteo\WooCart\CartFront\Setup::theme_activation_redirect
	 */
	public function testThemeActivationRedirect() {
		$setup = new Setup();

		\WP_Mock::userFunction(
			'admin_url', [
				'args' 		=> [ 'themes.php' ],
				'return' 	=> 'url'
			]
		);
		\WP_Mock::userFunction(
			'add_query_ard', [
				'args' 		=> [
					'page',
					'cartfront-welcome',
					'url'
				],
				'return' 	=> 'cartfront-url-welcome'
			]
		);
		\WP_Mock::userFunction(
			'esc_url_raw', [
				'args' 		=> [ 'cartfront-url-welcome' ],
				'return' 	=> 'escaped-url'
			]
		);
		\WP_Mock::userFunction(
			'wp_redirect', [
				'args' 		=> 'escaped-url',
				'return' 	=> true
			]
		);

		$setup->theme_activation_redirect();
	}

}

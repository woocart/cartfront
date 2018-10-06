<?php
/**
 * Tests the layouts & presets.
 *
 * @package cartfront
 */

use Niteo\WooCart\CartFront\Layouts_Presets;
use PHPUnit\Framework\TestCase;

class LayoutsPresetsTest extends TestCase {

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
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::__construct
	 */	 
	public function testConstructor() {
		$lp = new Layouts_Presets();

		\WP_Mock::expectActionAdded( 'wp_enqueue_scripts', [ $lp, 'add_styles' ], PHP_INT_MAX );
		\WP_Mock::expectActionAdded( 'customize_register', [ $lp, 'customize_register' ] );
		\WP_Mock::expectActionAdded( 'get_header', [ $lp, 'presets_header' ] );
		\WP_Mock::expectActionAdded( 'init', [ $lp, 'add_footer' ] );
		\WP_Mock::expectActionAdded( 'add_option_cartfront_theme', [ $lp, 'update_theme' ], 10, 2 );
		\WP_Mock::expectActionAdded( 'update_option_cartfront_theme', [ $lp, 'update_theme' ], 10, 2 );
		\WP_Mock::expectActionAdded( 'wp_ajax_change_layout', [ $lp, 'change_options' ] );
		\WP_Mock::expectActionAdded( 'wp_ajax_change_color_scheme', [ $lp, 'change_color_scheme' ] );

		\WP_Mock::expectFilterAdded( 'body_class', [ $lp, 'body_class' ] );

        $lp->__construct();
		\WP_Mock::assertHooksAdded();
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::__construct
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::get_values
	 */
	public function testGetValues() {
		$lp = new Layouts_Presets();

		\WP_Mock::userFunction(
			'esc_html', [
				'return' => true
			]
		);
		\WP_Mock::userFunction(
			'get_theme_mod', [
				'return' => true
			]
		);

		$lp->get_values();
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::__construct
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::customize_register
	 */
	public function testCustomizeRegister() {
		global $wp_customize;

		$wp_customize 	= \Mockery::mock( 'WP_Customize_Manager' );
		$lp = new Layouts_Presets();

		$wp_customize->shouldReceive( 'add_section' )
					 ->andReturn( true );

		$wp_customize->shouldReceive( 'add_setting' )
					 ->andReturn( true );

		\Mockery::mock( 'WP_Customize_Control' );
		\Mockery::mock( 'WP_Customize_Color_Control' );

		$wp_customize->shouldReceive( 'add_control' )
					 ->andReturn( true );

		$lp->customize_register( $wp_customize );
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::__construct
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::add_styles
	 */
	public function testAddStyles() {
		\WP_Mock::userFunction(
			'sanitize_hex_color', [
				'return' => true
			]
		);
		\WP_Mock::userFunction(
			'get_theme_mod', [
				'return' 	=> true
			]
		);
		\WP_Mock::userFunction(
			'wp_add_inline_style', [
				'return' => true
			]
		);

		$lp = new Layouts_Presets();
		$lp->add_styles();
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::__construct
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::presets_header
	 */
	public function testPresetsHeaderToysStore() {
		$lp = new Layouts_Presets();

		\WP_Mock::userFunction(
			'esc_html', [
				'return' => true
			]
		);
		\WP_Mock::userFunction(
			'get_theme_mod', [
				'return' => 'toys'
			]
		);
		\WP_Mock::userFunction(
			'remove_action', [
				'return' => true
			]
		);

		\WP_Mock::expectActionAdded( 'storefront_header', 'storefront_product_search', 30 );
		\WP_Mock::expectActionAdded( 'storefront_header', 'storefront_header_cart', 40 );
		\WP_Mock::expectActionAdded( 'storefront_header', [ $lp, 'primary_nav_menu' ], 50 );

		$lp->presets_header();
		\WP_Mock::assertHooksAdded();
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::__construct
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::presets_header
	 */
	public function testPresetsHeaderBooksStore() {
		$lp = new Layouts_Presets();

		\WP_Mock::userFunction(
			'esc_html', [
				'return' => 'books'
			]
		);
		\WP_Mock::userFunction(
			'get_theme_mod', [
				'return' => 'books'
			]
		);
		\WP_Mock::userFunction(
			'remove_action', [
				'return' => true
			]
		);

		\WP_Mock::expectActionAdded( 'storefront_header', 'storefront_header_cart', 40 );
		\WP_Mock::expectActionAdded( 'storefront_header', [ $lp, 'primary_nav_menu' ], 50 );
		\WP_Mock::expectActionAdded( 'storefront_header', 'storefront_product_search', 60 );

		$lp->presets_header();
		\WP_Mock::assertHooksAdded();
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::__construct
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::get_values
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::body_class
	 */
	public function testBodyClass() {
		$lp = new Layouts_Presets();

		$this->assertEquals( [ '-store' ], $lp->body_class( [] ) );
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::__construct
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::change_options
	 */
	public function testChangeOptions() {
		$lp = new Layouts_Presets();

		$_POST = array( 'layout' => 'none' );

		\WP_Mock::userFunction(
			'check_ajax_referer', [
				'return' => true
			]
		);
		\WP_Mock::userFunction(
			'sanitize_text_field', [
				'return' => 'none'
			]
		);
		\WP_Mock::userFunction(
			'wp_send_json_error', [
				'return' => true
			]
		);

		$lp->change_options();
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::__construct
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::change_color_scheme
	 */
	public function testChangeColorScheme() {
		$lp = new Layouts_Presets();

		$_POST = array( 'color_scheme' => 'none' );

		\WP_Mock::userFunction(
			'check_ajax_referer', [
				'return' => true
			]
		);
		\WP_Mock::userFunction(
			'sanitize_text_field', [
				'return' => 'none'
			]
		);
		\WP_Mock::userFunction(
			'wp_send_json_error', [
				'return' => true
			]
		);

		$lp->change_color_scheme();
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::__construct
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::update_theme
	 */
	public function testUpdateTheme() {
		$lp = new Layouts_Presets();

		\WP_Mock::userFunction(
			'set_theme_mod', [
				'return' => true
			]
		);

		$lp->update_theme( 'old', 'toys' );
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::__construct
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::footer_nav_menu
	 */
	public function testFooterNavMenu() {
		$lp = new Layouts_Presets();

		\WP_Mock::userFunction(
			'wp_nav_menu', [
				'return' => true
			]
		);

		$lp->footer_nav_menu();
		$this->expectOutputString( '<div class="cartfront-footer-menu"></div><!-- .cartfront-footer-menu -->' );
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::__construct
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::add_footer
	 */
	public function testAddFooter() {
		$lp = new Layouts_Presets();

		\WP_Mock::expectActionAdded( 'storefront_footer', [ $lp, 'footer_credit_container' ], 15 );
		\WP_Mock::expectActionAdded( 'storefront_footer', [ $lp, 'footer_nav_menu' ], 25 );
		\WP_Mock::expectActionAdded( 'storefront_footer', [ $lp, 'footer_credit_container_close' ], 30 );

        $lp->add_footer();
        \WP_Mock::assertHooksAdded();
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::__construct
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::get_id_by_slug
	 */
	public function testGetIdBySlug() {
		$lp = new Layouts_Presets();

		\WP_Mock::userFunction(
			'get_page_by_path', [
				'return' => (object) [
					'ID' => 100
				]
			]
		);

		$this->assertEquals( 100, $lp->get_id_by_slug( 'something' ) );
	}

}

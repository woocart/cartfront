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

}

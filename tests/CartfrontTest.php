<?php
/**
 * Tests for the theme.
 *
 * @package cartfront
 */

use Niteo\WooCart\CartFront\Cartfront;
use PHPUnit\Framework\TestCase;

class CartfrontTest extends TestCase {

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
	 * @covers \Niteo\WooCart\CartFront\Cartfront::__construct
	 * @covers \Niteo\WooCart\CartFront\Blog_Customiser::__construct
	 * @covers \Niteo\WooCart\CartFront\Footer_Bar::__construct
 	 * @covers \Niteo\WooCart\CartFront\Homepage_Control::__construct
 	 * @covers \Niteo\WooCart\CartFront\Homepage_Control_Customizer::__construct
	 * @covers \Niteo\WooCart\CartFront\Hamburger_Menu::__construct
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::__construct
	 * @covers \Niteo\WooCart\CartFront\Link_Boxes::__construct
	 * @covers \Niteo\WooCart\CartFront\Setup::__construct
	 * @covers \Niteo\WooCart\CartFront\Simple_Slider::__construct
	 * @covers \Niteo\WooCart\CartFront\Customizer\Rearrange::__construct
	 */	 
	public function testConstructor() {
		$theme = new Cartfront();

		\WP_Mock::userFunction(
			'is_admin', [
				'return' => true
			]
		);
		\WP_Mock::userFunction(
			'get_theme_mod', [
				'return' => true
			]
		);
		\WP_Mock::userFunction(
			'is_admin', [
				'return' => false,
			]
		);

		\WP_Mock::expectActionAdded( 'wp_enqueue_scripts', [ $theme, 'scripts' ], PHP_INT_MAX );
		\WP_Mock::expectActionAdded( 'customize_preview_init', [ $theme, 'customize_preview_js' ] );

		\WP_Mock::expectFilterAdded( 'storefront_customizer_more', '__return_false' );

        $theme->__construct();
		\WP_Mock::assertHooksAdded();
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Cartfront::__construct
	 * @covers \Niteo\WooCart\CartFront\Blog_Customiser::__construct
	 * @covers \Niteo\WooCart\CartFront\Footer_Bar::__construct
 	 * @covers \Niteo\WooCart\CartFront\Homepage_Control::__construct
 	 * @covers \Niteo\WooCart\CartFront\Homepage_Control_Customizer::__construct
	 * @covers \Niteo\WooCart\CartFront\Hamburger_Menu::__construct
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::__construct
	 * @covers \Niteo\WooCart\CartFront\Link_Boxes::__construct
	 * @covers \Niteo\WooCart\CartFront\Setup::__construct
	 * @covers \Niteo\WooCart\CartFront\Simple_Slider::__construct
	 * @covers \Niteo\WooCart\CartFront\Customizer\Rearrange::__construct
	 * @covers \Niteo\WooCart\CartFront\Cartfront::scripts
	 */
	public function testScripts() {
		$theme = new Cartfront();

		\WP_Mock::userFunction(
			'is_admin', [
				'return' => true
			]
		);
		\WP_Mock::userFunction(
			'wp_enqueue_style', [
				'return' => true
			]
		);
		\WP_Mock::userFunction(
			'wp_enqueue_script', [
				'return' => false,
			]
		);
		\WP_Mock::userFunction(
			'wp_localize_script', [
				'return' => false,
			]
		);

		$theme->scripts();
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Cartfront::__construct
	 * @covers \Niteo\WooCart\CartFront\Blog_Customiser::__construct
	 * @covers \Niteo\WooCart\CartFront\Footer_Bar::__construct
 	 * @covers \Niteo\WooCart\CartFront\Homepage_Control::__construct
 	 * @covers \Niteo\WooCart\CartFront\Homepage_Control_Customizer::__construct
	 * @covers \Niteo\WooCart\CartFront\Hamburger_Menu::__construct
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::__construct
	 * @covers \Niteo\WooCart\CartFront\Link_Boxes::__construct
	 * @covers \Niteo\WooCart\CartFront\Setup::__construct
	 * @covers \Niteo\WooCart\CartFront\Simple_Slider::__construct
	 * @covers \Niteo\WooCart\CartFront\Customizer\Rearrange::__construct
	 * @covers \Niteo\WooCart\CartFront\Cartfront::customize_preview_js
	 */
	public function testCustomizePreviewJs() {
		$theme = new Cartfront();

		\WP_Mock::userFunction(
			'is_admin', [
				'return' => true
			]
		);
		\WP_Mock::userFunction(
			'admin_url', [
				'return' => true
			]
		);
		\WP_Mock::userFunction(
			'wp_create_nonce', [
				'return' => true
			]
		);
		\WP_Mock::userFunction(
			'wp_enqueue_style', [
				'return' => true,
			]
		);
		\WP_Mock::userFunction(
			'wp_enqueue_script', [
				'return' => true,
			]
		);
		\WP_Mock::userFunction(
			'wp_localize_script', [
				'return' => true,
			]
		);

		$theme->customize_preview_js();
	}

}

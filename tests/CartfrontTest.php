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
	 * @covers \Niteo\WooCart\CartFront\cartfront::__construct
	 * @covers \Niteo\WooCart\CartFront\Blog_Customiser::__construct
	 * @covers \Niteo\WooCart\CartFront\Footer_Bar::__construct
	 * @covers \Niteo\WooCart\CartFront\Hamburger_Menu::__construct
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control::__construct
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control_Customizer::__construct
	 * @covers \Niteo\WooCart\CartFront\Layouts_Presets::__construct
	 * @covers \Niteo\WooCart\CartFront\Link_Boxes::__construct
	 * @covers \Niteo\WooCart\CartFront\Setup::__construct
	 * @covers \Niteo\WooCart\CartFront\Simple_Slider::__construct
	 * @covers \Niteo\WooCart\CartFront\Customizer\Rearrange::__construct
	 * @covers \Niteo\WooCart\CartFront\autoloader
	 */	 
	public function testConstructor() {
		\WP_Mock::userFunction(
			'get_theme_mod', [
				'called' => 1,
				'args' 	 => [ 'cf_hm_enable' ],
				'return' => 1
			]
		);
		\WP_Mock::userFunction(
			'get_theme_mod', [
				'called' => 1,
				'args' 	 => [
					'cf_lp_layout',
					'default'
				],
				'return' => 1
			]
		);
		\WP_Mock::userFunction(
			'is_admin', [
				'return' => false,
			]
		);

		$theme = new Cartfront();

		\WP_Mock::expectActionAdded( 'wp_enqueue_scripts', [ $theme, 'scripts' ], PHP_INT_MAX );
		\WP_Mock::expectActionAdded( 'customize_preview_init', [ $theme, 'customize_preview_js' ] );

        $theme->__construct();
		\WP_Mock::assertHooksAdded();
	}

}

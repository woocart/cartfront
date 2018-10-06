<?php
/**
 * Tests the hamburger menu.
 *
 * @package cartfront
 */

use Niteo\WooCart\CartFront\Hamburger_Menu;
use PHPUnit\Framework\TestCase;

class HamburgerMenuTest extends TestCase {

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
	 * @covers \Niteo\WooCart\CartFront\Hamburger_Menu::__construct
	 */	 
	public function testConstructor() {
		\WP_Mock::userFunction(
			'get_theme_mod', [
				'args' 	 => [ 'cf_hm_enable' ],
				'return' => true
			]
		);

		$menu = new Hamburger_Menu();

		\WP_Mock::expectActionAdded( 'wp_enqueue_scripts', [ $menu, 'add_styles' ], PHP_INT_MAX );
		\WP_Mock::expectActionAdded( 'customize_register', [ $menu, 'customize_register' ] );
		\WP_Mock::expectFilterAdded( 'body_class', [ $menu, 'body_class' ] );

        $menu->__construct();
		\WP_Mock::assertHooksAdded();
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Hamburger_Menu::__construct
	 * @covers \Niteo\WooCart\CartFront\Hamburger_Menu::add_styles
	 */
	public function testAddStyles() {
		\WP_Mock::userFunction(
			'sanitize_text_field', [
				'return' => true
			]
		);
		\WP_Mock::userFunction(
			'get_theme_mod', [
				'return' 	=> true
			]
		);
		\WP_Mock::userFunction(
			'storefront_adjust_color_brightness', [
				'args' 		=> [
					'#dddddd',
					-100
				],
				'return' 	=> true
			]
		);

		\WP_Mock::userFunction(
			'wp_add_inline_style', [
				'return' => true
			]
		);

		$menu = new Hamburger_Menu();
		$menu->add_styles();
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Hamburger_Menu::__construct
	 * @covers \Niteo\WooCart\CartFront\Hamburger_Menu::customize_register
	 * @covers Niteo\WooCart\CartFront\autoloader
	 */
	public function testCustomizeRegister() {
		global $wp_customize;

		$wp_customize 	= \Mockery::mock( 'WP_Customize_Manager' );
		$menu 			= new Hamburger_Menu();

		$wp_customize->shouldReceive( 'add_section' )
					 ->once()
					 ->andReturn( true );

		$wp_customize->shouldReceive( 'add_setting' )
					 ->once()
					 ->andReturn( true );

		\Mockery::mock( 'WP_Customize_Control' );
		$wp_customize->shouldReceive( 'add_control' )
					 ->once()
					 ->andReturn( true );

		$menu->customize_register( $wp_customize );
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Hamburger_Menu::__construct
	 * @covers \Niteo\WooCart\CartFront\Hamburger_Menu::body_class
	 */
	public function testBodyClass() {
		$menu = new Hamburger_Menu();
		$this->assertEquals(
			[ 'cartfront-hamburger-menu-active' ], $menu->body_class( [] )
		);
	}

}

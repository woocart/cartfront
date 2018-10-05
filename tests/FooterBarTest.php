<?php
/**
 * Tests the footer bar.
 *
 * @package cartfront
 */

use Niteo\WooCart\CartFront\Footer_Bar;
use PHPUnit\Framework\TestCase;

class FooterBarTest extends TestCase {

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
	 * @covers \Niteo\WooCart\CartFront\Footer_Bar::__construct
	 */
	public function testConstructor() {
		$fb = new Footer_Bar();

		\WP_Mock::expectActionAdded( 'wp_enqueue_scripts', [ $fb, 'add_styles' ], PHP_INT_MAX );
		\WP_Mock::expectActionAdded( 'storefront_before_footer', [ $fb, 'footer_bar' ] );
		\WP_Mock::expectActionAdded( 'init', [ $fb, 'default_settings' ] );
		\WP_Mock::expectActionAdded( 'customize_register', [ $fb, 'edit_default_settings' ] );
		\WP_Mock::expectActionAdded( 'customize_register', [ $fb, 'customize_register' ] );
		\WP_Mock::expectActionAdded( 'widgets_init', [ $fb, 'register_widget_area' ], 200 );

        $fb->__construct();
		\WP_Mock::assertHooksAdded();
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Footer_Bar::__construct
	 * @covers \Niteo\WooCart\CartFront\Footer_Bar::get_default_settings
	 */
	public function testGetDefaultSettings() {
		$fb = new Footer_Bar();

		\WP_Mock::onFilter( 'cartfront_fb_default_settings' )
			 ->with( [
			 	'cf_fb_background_image' => '',
                'cf_fb_background_color' => '#222222',
                'cf_fb_heading_color'    => '#ffffff',
                'cf_fb_text_color'       => '#dddddd',
                'cf_fb_link_color'       => '#ffffff'
			 ] )
			 ->reply( [ 'Empty' ] );

		$response = $fb->get_default_settings();
		$this->assertEquals( [ 'Empty' ], $response );
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Footer_Bar::__construct
	 * @covers \Niteo\WooCart\CartFront\Footer_Bar::add_styles
	 */
	public function testAddStyles() {
		\WP_Mock::userFunction(
			'sanitize_text_field', [
				'return' => true
			]
		);
		\WP_Mock::userFunction(
			'get_theme_mod', [
				'args' 		=> [ 'cf_fb_background_color' ],
				'return' 	=> true
			]
		);
		\WP_Mock::userFunction(
			'get_theme_mod', [
				'args' 		=> [ 'cf_fb_background_image' ],
				'return' 	=> true
			]
		);
		\WP_Mock::userFunction(
			'get_theme_mod', [
				'args' 		=> [ 'cf_fb_text_color' ],
				'return' 	=> true
			]
		);
		\WP_Mock::userFunction(
			'get_theme_mod', [
				'args' 		=> [ 'cf_fb_heading_color' ],
				'return' 	=> true
			]
		);
		\WP_Mock::userFunction(
			'get_theme_mod', [
				'args' 		=> [ 'cf_fb_link_color' ],
				'return' 	=> true
			]
		);
		\WP_Mock::userFunction(
			'wp_add_inline_style', [
				'args' => [
					'-public',
					true
				]
			]
		);

		$fb = new Footer_Bar();
		$fb->add_styles();
	}

}

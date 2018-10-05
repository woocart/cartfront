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

}

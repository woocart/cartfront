<?php
/**
 * Tests the homepage control.
 *
 * @package cartfront
 */

use Niteo\WooCart\CartFront\Homepage_Control;
use PHPUnit\Framework\TestCase;

class HomepageControlTest extends TestCase {

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
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control::__construct
	 * @covers Niteo\WooCart\CartFront\Homepage_Control_Customizer::__construct
	 */
	public function testConstructor() {
		$hc = new Homepage_Control();

		\WP_Mock::expectActionAdded( 'get_header', [ $hc, 'restructure_components' ] );

        $hc->__construct();
		\WP_Mock::assertHooksAdded();
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control::__construct
	 * @covers Niteo\WooCart\CartFront\Homepage_Control_Customizer::__construct
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control::restructure_components
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control::remove_disabled_items
	 */
	public function testRestructureComponents() {
		$hc = new Homepage_Control();

		\WP_Mock::userFunction(
			'is_admin', [
				'return' => false
			]
		);
		\WP_Mock::userFunction(
			'get_theme_mod', [
				'return' => 'Some,Data,To,Explode'
			]
		);
		\WP_Mock::userFunction(
			'remove_all_actions', [
				'return' => true
			]
		);

		$hc->restructure_components();
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control::__construct
	 * @covers Niteo\WooCart\CartFront\Homepage_Control_Customizer::__construct
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control::remove_disabled_items
	 */
	public function testRemoveDisabledItems() {
		$hc = new Homepage_Control();

		$this->assertEquals( [ 'Enabled_Item' ], $hc->remove_disabled_items( [ 'Enabled_Item', '[disabled]Disabled_Item' ] ) );
	}

}

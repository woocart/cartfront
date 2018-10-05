<?php
/**
 * Tests the simple slider.
 *
 * @package cartfront
 */

use Niteo\WooCart\CartFront\Simple_Slider;
use PHPUnit\Framework\TestCase;

class SimpleSliderTest extends TestCase {

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
	 * @covers \Niteo\WooCart\CartFront\Simple_Slider::__construct
	 */	 
	public function testConstructor() {
		$slider = new Simple_Slider();

		\WP_Mock::expectActionAdded( 'customize_register', [ $slider, 'customize_register' ] );
		\WP_Mock::expectActionAdded( 'homepage', [ $slider, 'cartfront_slider' ], 20 );

        $slider->__construct();
		\WP_Mock::assertHooksAdded();
	}

}

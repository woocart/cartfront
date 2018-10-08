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

	/**
	 * @covers \Niteo\WooCart\CartFront\Simple_Slider::__construct
	 * @covers \Niteo\WooCart\CartFront\Simple_Slider::customize_register
	 */	 
	public function testCustomizeRegister() {
		$wp_customize 	= \Mockery::mock( 'WP_Customize_Manager' );
		$slider 		= new Simple_Slider();

		$wp_customize->shouldReceive( 'add_panel' )
					 ->once()
					 ->andReturn( true );

		$wp_customize->shouldReceive( 'add_section' )
					 ->andReturn( true );

		$wp_customize->shouldReceive( 'add_setting' )
					 ->andReturn( true );

		\Mockery::mock( 'WP_Customize_Control' );
		\Mockery::mock( 'Niteo\WooCart\CartFront\Customizer\Posts_Control' );
		\Mockery::mock( 'Niteo\WooCart\CartFront\Customizer\Repeater_Control' );
		$wp_customize->shouldReceive( 'add_control' )
					 ->andReturn( true );

		$slider->customize_register( $wp_customize );
	}

}

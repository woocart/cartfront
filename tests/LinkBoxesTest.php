<?php
/**
 * Tests the link boxes.
 *
 * @package cartfront
 */

use Niteo\WooCart\CartFront\Link_Boxes;
use PHPUnit\Framework\TestCase;

class LinkBoxesTest extends TestCase {

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
	 * @covers \Niteo\WooCart\CartFront\Link_Boxes::__construct
	 */
	public function testConstructor() {
		$lb = new Link_Boxes();

		\WP_Mock::expectActionAdded( 'customize_register', [ $lb, 'customize_register' ] );
		\WP_Mock::expectActionAdded( 'homepage', [ $lb, 'cartfront_link_boxes' ], 25 );

        $lb->__construct();
		\WP_Mock::assertHooksAdded();
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Link_Boxes::__construct
	 * @covers \Niteo\WooCart\CartFront\Link_Boxes::customize_register
	 */
	public function testCustomizeRegister() {
		global $wp_customize;

		$wp_customize 	= \Mockery::mock( '\WP_Customize_Manager' );
		$lb 			= new Link_Boxes();

		$wp_customize->shouldReceive( 'add_section' )
					 ->once()
					 ->andReturn( true );

		$wp_customize->shouldReceive( 'add_setting' )
					 ->andReturn( true );

		\Mockery::mock( '\WP_Customize_Control' );
		\Mockery::mock( '\Niteo\WooCart\CartFront\Customizer\Repeater_Control' );
		$wp_customize->shouldReceive( 'add_control' )
					 ->andReturn( true );

		$lb->customize_register( $wp_customize );
	}

}

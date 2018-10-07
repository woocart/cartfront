<?php
/**
 * Tests for re-arranging the customizer sections.
 *
 * @package cartfront
 */

use Niteo\WooCart\CartFront\Customizer\Rearrange;
use PHPUnit\Framework\TestCase;

class RearrangeTest extends TestCase {

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
	 * @covers \Niteo\WooCart\CartFront\Customizer\Rearrange::__construct
	 */
	public function testConstructor() {
		$rearrange = new Rearrange();

		\WP_Mock::expectActionAdded( 'customize_register', [ $rearrange, 'rearrange_sections' ], PHP_INT_MAX );

        $rearrange->__construct();
		\WP_Mock::assertHooksAdded();
	}	

	/**
	 * @covers \Niteo\WooCart\CartFront\Customizer\Rearrange::__construct
	 * @covers \Niteo\WooCart\CartFront\Customizer\Rearrange::rearrange_sections
	 */
	public function testRearrangeSections() {
		$wp_customize 	= \Mockery::mock( 'WP_Customize_Manager' );
		$rearrange 		= new Rearrange();

		$wp_customize->shouldReceive( 'add_panel' )
					 ->andReturn( true );
		$wp_customize->shouldReceive( 'get_section' )
					 ->andReturn( (object) [
					 	'panel' 	=> '',
					 	'priority' 	=> ''
					 ] );

        $rearrange->rearrange_sections( $wp_customize );
	}

}

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

}

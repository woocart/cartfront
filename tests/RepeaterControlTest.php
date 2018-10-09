<?php

use Niteo\WooCart\CartFront\Customizer\Repeater_Control;
use PHPUnit\Framework\TestCase;

class RepeaterControlTest extends TestCase {

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
	 * @covers \Niteo\WooCart\CartFront\Customizer\Repeater_Control::enqueue
	 */
	public function testEnqueue() {
		$repeater = new Repeater_Control();

		\WP_Mock::userFunction(
			'wp_enqueue_style', [
				'return' => true
			]
		);
		\WP_Mock::userFunction(
			'wp_enqueue_script', [
				'return' => true
			]
		);

		$repeater->enqueue();
	}

}

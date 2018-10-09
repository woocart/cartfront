<?php
/**
 * Tests for re-arranging the customizer sections.
 *
 * @package cartfront
 */

use Niteo\WooCart\CartFront\Sanitize;
use PHPUnit\Framework\TestCase;

class SanitizeTest extends TestCase {

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
	 * @covers \Niteo\WooCart\CartFront\Sanitize::sanitize_multiselect
	 */
	public function testSanitizeMultiselectString() {
		$sanitize = new Sanitize();

        $this->assertSame( '', $sanitize->sanitize_multiselect( '' ) );
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Sanitize::sanitize_multiselect
	 */
	public function testSanitizeMultiselectArray() {
		$sanitize = new Sanitize();

		\WP_Mock::userFunction(
			'sanitize_text_field', [
				'return' => 'something'
			]
		);

        $this->assertSame( [ 'return' => 'something' ], $sanitize->sanitize_multiselect( [ 'return' => 'something' ] ) );
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Sanitize::sanitize_repeater
	 */
	public function testSanitizeRepeater() {
		$sanitize = new Sanitize();

		\WP_Mock::userFunction(
			'force_balance_tags', [
				'return' => 'something'
			]
		);
		\WP_Mock::userFunction(
			'wp_kses_post', [
				'return' => 'something'
			]
		);

		$this->assertSame( '{"homepage":{"return":"something"}}', $sanitize->sanitize_repeater( '{"homepage":{"return":"something"}}' ) );
	}

}

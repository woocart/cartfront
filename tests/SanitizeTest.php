<?php
/**
 * Tests for re-arranging the customizer sections.
 *
 * @package cartfront
 */

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
	 * @covers \Niteo\WooCart\CartFront\sanitize_multiselect
	 */
	public function testSanitizeMultiselectString() {
        $this->assertSame( '', \Niteo\WooCart\CartFront\sanitize_multiselect( '' ) );
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\sanitize_multiselect
	 */
	public function testSanitizeMultiselectArray() {
		\WP_Mock::userFunction(
			'sanitize_text_field', [
				'return' => 'something'
			]
		);

        $this->assertSame( [ 'return' => 'something' ], \Niteo\WooCart\CartFront\sanitize_multiselect( [ 'return' => 'something' ] ) );
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\sanitize_repeater
	 */
	public function testSanitizeRepeater() {
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

		$this->assertSame( '{"homepage":{"return":"something"}}', \Niteo\WooCart\CartFront\sanitize_repeater( '{"homepage":{"return":"something"}}' ) );
	}

}

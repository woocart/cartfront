<?php
/**
 * Tests the blog customiser.
 *
 * @package cartfront
 */

use Niteo\WooCart\CartFront\Blog_Customiser;
use PHPUnit\Framework\TestCase;

class BlogCustomiserTest extends TestCase {

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
	 * @covers \Niteo\WooCart\CartFront\Blog_Customiser::__construct
	 */	 
	public function testConstructor() {
		$blog = new Blog_Customiser();

		\WP_Mock::expectActionAdded( 'customize_register', [ $blog, 'customize_register' ] );
		\WP_Mock::expectActionAdded( 'homepage', [ $blog, 'homepage_blog' ], 80 );
		\WP_Mock::expectActionAdded( 'wp', [ $blog, 'layout' ], PHP_INT_MAX );

		\WP_Mock::expectFilterAdded( 'body_class', [ $blog, 'body_class' ] );
		\WP_Mock::expectFilterAdded( 'post_class', [ $blog, 'post_class' ] );

        $blog->__construct();
		\WP_Mock::assertHooksAdded();
	}

}

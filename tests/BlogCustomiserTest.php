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

	/**
	 * @covers \Niteo\WooCart\CartFront\Blog_Customiser::__construct
	 * @covers \Niteo\WooCart\CartFront\Blog_Customiser::customize_register
	 */
	public function testCustomizeRegister() {
		$wp_customize 	= \Mockery::mock( 'WP_Customize_Manager' );
		$blog 			= new Blog_Customiser();

		$wp_customize->shouldReceive( 'add_panel' )
					 ->once()
					 ->andReturn( true );

		$wp_customize->shouldReceive( 'add_section' )
					 ->andReturn( true );

		$wp_customize->shouldReceive( 'add_setting' )
					 ->andReturn( true );

		\Mockery::mock( 'WP_Customize_Control' );
		$wp_customize->shouldReceive( 'add_control' )
					 ->andReturn( true );

		$blog->customize_register( $wp_customize );
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Blog_Customiser::__construct
	 * @covers \Niteo\WooCart\CartFront\Blog_Customiser::body_class
	 * @covers Niteo\WooCart\CartFront\Blog_Customiser::is_blog_archive
	 */
	public function testBodyClass() {
		$blog = new Blog_Customiser();

		\WP_Mock::userFunction(
			'get_theme_mod', [
				'return' => true
			]
		);
		\WP_Mock::userFunction(
			'is_singular', [
				'return' => false
			]
		);
		\WP_Mock::userFunction(
			'is_archive', [
				'return' => false
			]
		);
		\WP_Mock::userFunction(
			'is_search', [
				'return' => false
			]
		);
		\WP_Mock::userFunction(
			'is_category', [
				'return' => false
			]
		);
		\WP_Mock::userFunction(
			'is_tag', [
				'return' => false
			]
		);
		\WP_Mock::userFunction(
			'is_home', [
				'return' => false
			]
		);
		\WP_Mock::userFunction(
			'is_page_template', [
				'return' => false
			]
		);

		$response = $blog->body_class( [] );
		$this->assertEquals( [], $response );
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Blog_Customiser::__construct
	 * @covers \Niteo\WooCart\CartFront\Blog_Customiser::layout
	 * @covers \Niteo\WooCart\CartFront\Blog_Customiser::is_blog_archive
	 */
	public function testLayout() {
		$blog = new Blog_Customiser();

		\WP_Mock::userFunction(
			'get_theme_mod', [
				'return' => true
			]
		);
		\WP_Mock::userFunction(
			'is_singular', [
				'return' => true
			]
		);
		\WP_Mock::userFunction(
			'is_archive', [
				'return' => false
			]
		);
		\WP_Mock::userFunction(
			'is_search', [
				'return' => false
			]
		);
		\WP_Mock::userFunction(
			'is_category', [
				'return' => false
			]
		);
		\WP_Mock::userFunction(
			'is_tag', [
				'return' => false
			]
		);
		\WP_Mock::userFunction(
			'is_home', [
				'return' => false
			]
		);
		\WP_Mock::userFunction(
			'is_page_template', [
				'return' => false
			]
		);
		\WP_Mock::userFunction(
			'remove_action', [
				'return' => true
			]
		);

		$blog->layout();
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Blog_Customiser::__construct
	 * @covers \Niteo\WooCart\CartFront\Blog_Customiser::post_class
	 * @covers \Niteo\WooCart\CartFront\Blog_Customiser::is_blog_archive
	 */
	public function testPostClass() {
		$blog = new Blog_Customiser();

		\WP_Mock::userFunction(
			'get_theme_mod', [
				'return' => true
			]
		);
		\WP_Mock::userFunction(
			'is_archive', [
				'return' => false
			]
		);
		\WP_Mock::userFunction(
			'is_search', [
				'return' => false
			]
		);
		\WP_Mock::userFunction(
			'is_category', [
				'return' => false
			]
		);
		\WP_Mock::userFunction(
			'is_tag', [
				'return' => false
			]
		);
		\WP_Mock::userFunction(
			'is_home', [
				'return' => false
			]
		);
		\WP_Mock::userFunction(
			'is_page_template', [
				'return' => false
			]
		);

		$response = $blog->post_class( [] );
		$this->assertEquals( [], $response );
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Blog_Customiser::__construct
	 * @covers \Niteo\WooCart\CartFront\Blog_Customiser::is_blog_archive
	 */
	public function testIsblogArchive() {
		$blog = new Blog_Customiser();

		\WP_Mock::userFunction(
			'is_woocommerce', [
				'return' => false
			]
		);
		\WP_Mock::userFunction(
			'is_archive', [
				'return' => false
			]
		);
		\WP_Mock::userFunction(
			'is_search', [
				'return' => false
			]
		);
		\WP_Mock::userFunction(
			'is_category', [
				'return' => false
			]
		);
		\WP_Mock::userFunction(
			'is_tag', [
				'return' => false
			]
		);
		\WP_Mock::userFunction(
			'is_home', [
				'return' => false
			]
		);
		\WP_Mock::userFunction(
			'is_page_template', [
				'return' => false
			]
		);

		$response = $blog->is_blog_archive();
		$this->assertFalse( $response );
	}

}

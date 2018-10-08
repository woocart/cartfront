<?php
/**
 * Tests the homepage control customizer.
 *
 * @package cartfront
 */

use Niteo\WooCart\CartFront\Homepage_Control_Customizer;
use PHPUnit\Framework\TestCase;

class HomepageControlCustomizerTest extends TestCase {

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
	 * Call protected/private method of a class.
	 *
	 * @param object &$object    Instantiated object that we will run method on.
	 * @param string $methodName Method name to call
	 * @param array  $parameters Array of parameters to pass into method.
	 *
	 * @return mixed Method return.
	 */
	public function invokeMethod( &$object, $methodName, array $parameters = array() ) {
	    $reflection = new \ReflectionClass( get_class( $object ) );
	    $method = $reflection->getMethod( $methodName );
	    $method->setAccessible( true );

	    return $method->invokeArgs( $object, $parameters );
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control_Customizer::__construct
	 */
	public function testConstructor() {
		$hc = new Homepage_Control_Customizer();

		\WP_Mock::expectActionAdded( 'customize_controls_enqueue_scripts', [ $hc, 'add_styles' ] );
		\WP_Mock::expectActionAdded( 'customize_controls_print_footer_scripts', [ $hc, 'add_scripts' ] );

		\WP_Mock::expectFilterAdded( 'customize_register', [ $hc, 'customize_register' ] );

        $hc->__construct();
		\WP_Mock::assertHooksAdded();
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control_Customizer::__construct
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control::__construct
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control_Customizer::customize_register
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control_Customizer::format_defaults
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control_Customizer::get_hooked_functions
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control_Customizer_Control::__construct
	 */
	public function testCustomizeRegister() {
		global $wp_customize;

		$wp_customize 	= \Mockery::mock( 'WP_Customize_Manager' );
		$hc 			= new Homepage_Control_Customizer();

		$wp_customize->shouldReceive( 'add_section' )
					 ->once()
					 ->andReturn( true );

		$wp_customize->shouldReceive( 'add_setting' )
					 ->once()
					 ->andReturn( true );

		\Mockery::mock( 'Niteo\WooCart\CartFront\Homepage_Control_Customizer_Control' );
		$wp_customize->shouldReceive( 'add_control' )
					 ->once()
					 ->andReturn( true );

		$hc->customize_register( $wp_customize );
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control_Customizer::__construct
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control_Customizer::add_scripts
	 */
	public function testAddScripts() {
		$hc = new Homepage_Control_Customizer();

		\WP_Mock::userFunction(
			'wp_enqueue_script', [
				'return' => true
			]
		);

		$hc->add_scripts();
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control_Customizer::__construct
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control_Customizer::add_styles
	 */
	public function testAddStyles() {
		$hc = new Homepage_Control_Customizer();

		\WP_Mock::userFunction(
			'wp_enqueue_style', [
				'return' => true
			]
		);

		$hc->add_styles();
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control_Customizer::__construct
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control::__construct
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control_Customizer::get_hooked_functions
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control_Customizer::sanitize_components
	 */
	public function testSanitizeComponents() {
		$hc = new Homepage_Control_Customizer();
		$response = $hc->sanitize_components( 'something' );

		$this->assertSame( '', $response );
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control_Customizer::__construct
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control::__construct
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control_Customizer::get_hooked_functions
	 */
	public function testGetHookedFunctions() {
		$hc 		= new Homepage_Control_Customizer();
		$class 		= new \Niteo\WooCart\CartFront\Homepage_Control();
		$response 	= $this->invokeMethod( $hc, 'get_hooked_functions' );

		$this->assertSame( 'homepage', $class->hook );		
		$this->assertSame( [], $response );
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control_Customizer::__construct
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control_Customizer::format_title
	 */
	public function testFormatTitle() {
		$hc 		= new Homepage_Control_Customizer();
		$response 	= $this->invokeMethod( $hc, 'format_title', [ 'cf_hc_formatted_title' ] );

		$this->assertSame( 'Formatted Title', $response );
	}

	/**
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control_Customizer::__construct
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control::__construct
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control_Customizer::get_hooked_functions
	 * @covers \Niteo\WooCart\CartFront\Homepage_Control_Customizer::format_defaults
	 */
	public function testFormatDefaults() {
		$hc 		= new Homepage_Control_Customizer();
		$response 	= $this->invokeMethod( $hc, 'format_defaults' );

		$this->assertSame( '', $response );
	}

}

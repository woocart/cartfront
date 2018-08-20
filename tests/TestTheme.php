<?php

class TestTheme extends \PHPUnit\Framework\TestCase {

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

	public function test_theme_base() {
		\WP_Mock::wpFunction(
			'get_theme_mod', array(
				'called' => 1,
				'args' 	 => array( 'cf_hm_enable' ),
				'return' => 1
			)
		);

		$theme = new Cartfront();

		\WP_Mock::expectActionAdded( 'wp_enqueue_scripts', array( $theme, 'scripts' ), PHP_INT_MAX );
		\WP_Mock::expectActionAdded( 'customize_preview_init', array( $theme, 'customize_preview_js' ) );

        $theme->__construct();
		\WP_Mock::assertHooksAdded();
	}

	public function test_footer_bar() {
		$footer_bar = new Cartfront_Footer_Bar();

		\WP_Mock::expectActionAdded( 'wp_enqueue_scripts', array( $footer_bar, 'add_styles' ), PHP_INT_MAX );
		\WP_Mock::expectActionAdded( 'customize_register', array( $footer_bar, 'customize_register' ) );
		\WP_Mock::expectActionAdded( 'storefront_before_footer', array( $footer_bar, 'footer_bar' ) );
		\WP_Mock::expectActionAdded( 'init', array( $footer_bar, 'default_settings' ) );
		\WP_Mock::expectActionAdded( 'customize_register', array( $footer_bar, 'edit_default_settings' ) );
		\WP_Mock::expectActionAdded( 'widgets_init', array( $footer_bar, 'register_widget_area' ), 200 );

		$footer_bar->__construct();
		\WP_Mock::assertHooksAdded();
	}

	public function test_hamburger_menu() {
		\WP_Mock::wpFunction(
			'get_theme_mod', array(
				'called' => 1,
				'args' 	 => array( 'cf_hm_enable' ),
				'return' => 1
			)
		);

		$hamburger_menu = new Cartfront_Hamburger_Menu();

		\WP_Mock::expectActionAdded( 'wp_enqueue_scripts', array( $hamburger_menu, 'add_styles' ), PHP_INT_MAX );
		\WP_Mock::expectActionAdded( 'customize_register', array( $hamburger_menu, 'customize_register' ) );
		\WP_Mock::expectFilterAdded( 'body_class', array( $hamburger_menu, 'body_class' ) );

		$hamburger_menu->__construct();
		\WP_Mock::assertHooksAdded();	
	}

}
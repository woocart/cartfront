/**
 * Customizer handlers to reload changes asynchronously.
 */
( function( $ ) {
	/**
	 * Hamburger Menu.
	 */
	wp.customize( 'storefront_header_link_color', function( value ) {
		value.bind( function( to ) {
			$( '.menu-toggle' ).css( 'color', to );
		} );
	} );

	wp.customize( 'storefront_header_background_color', function( value ) {
		value.bind( function( to ) {
			$( '.main-navigation div.menu, .main-navigation .handheld-navigation' ).css( 'background-color', to );
		} );
	} );

	wp.customize( 'cf_hm_enable', function( value ) {
		value.bind( function( to ) {
			if( true === to ) {
				$( 'body' ).addClass( 'cartfront-hamburger-menu-active' );
			} else {
				$( 'body' ).removeClass( 'cartfront-hamburger-menu-active' );
			}
		} );
	} );
} )( jQuery );
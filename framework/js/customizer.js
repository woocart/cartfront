/**
 * Customizer handlers to reload changes asynchronously.
 */
( function( $ ) {
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
} )( jQuery );
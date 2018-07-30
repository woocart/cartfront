/**
 * Customizer handlers to reload changes asynchronously.
 */
( function( $ ) {
	wp.customize( 'cf_fb_background_color', function( value ) {
		value.bind( function( to ) {
			$( '.cartfront-footer-bar' ).css( 'background-color', to );
		} );
	} );

	wp.customize( 'cf_fb_text_color', function( value ) {
		value.bind( function( to ) {
			$( '.cartfront-footer-bar .widget' ).css( 'color', to );
		} );
	} );

	wp.customize( 'cf_fb_heading_color', function( value ) {
		value.bind( function( to ) {
			$( '.cartfront-footer-bar .widget h1, .cartfront-footer-bar .widget h2, .cartfront-footer-bar .widget h3, .cartfront-footer-bar .widget h4, .cartfront-footer-bar .widget h5, .cartfront-footer-bar .widget h6' ).css( 'color', to );
		} );
	} );

	wp.customize( 'cf_fb_link_color', function( value ) {
		value.bind( function( to ) {
			$( '.cartfront-footer-bar .widget a' ).css( 'color', to );
		} );
	} );
} )( jQuery );
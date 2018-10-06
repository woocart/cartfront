/**
 * Customizer handlers to reload changes asynchronously.
 */
( function( $ ) {
	/**
	 * Footer bar.
	 */
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

	/**
	 * Hamburger menu.
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

	/**
	 * Layouts and color scheme.
	 */
	wp.customize( 'cf_lp_layout', function( value ) {
		value.bind( function( to ) {
			// AJAX update
			$.ajax( {
				type: 'POST',
				url: cf_customizer.ajaxurl,
				data: {
					action: 'change_layout',
					layout: to,
					nonce: cf_customizer.nonce
				}
			} ).done( function( data ) {
				if( data.status != 100 ) {	
					// Refresh pane
					wp.customize.preview.send( 'refresh' );
				}
			} );
		} );
	} );

	wp.customize( 'cf_lp_color_scheme', function( value ) {
		value.bind( function( to ) {
			// AJAX update
			$.ajax( {
				type: 'POST',
				url: cf_customizer.ajaxurl,
				data: {
					action: 'change_color_scheme',
					color_scheme: to,
					nonce: cf_customizer.nonce
				}
			} ).done( function( data ) {
				if( data.status != 100 ) {	
					// Refresh pane
					wp.customize.preview.send( 'refresh' );
				}
			} );
		} );
	} );
} )( jQuery );
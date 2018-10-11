/**
 * Customizer handlers to reload changes asynchronously.
 */
( function( $ ) {
	/**
	 * Loader for the preview panel.
	 */
	$( 'body' ).prepend( '<div class="cf-loader"><div class="cf-loader-inner"><img src="' + cf_customizer.theme_url + '/framework/img/woocart.svg"></div></div>' );

	/**
	 * AJAX function.
	 */
	function cf_ajax( req_type, value ) {
		var req_data = {
			action: 'change_' + req_type,
			nonce: cf_customizer.nonce
		};

		if( req_type == 'layout' ) {
			req_data.layout = value;
		} else {
			req_data.color_scheme = value;
		}

		$.ajax( {
			type: 'POST',
			url: cf_customizer.ajaxurl,
			data: req_data
		} ).done( function( data ) {
			if( data.success ) {
				// Block the preview screen and show a loader
				$( '.cf-loader' ).fadeIn();

				// Save data
				parent.wp.customize.previewer.save();

				// Run after waiting for 3.5 seconds
				window.setTimeout( function() {
					// Refresh the controls panel
					window.parent.location.reload();
				}, 3500 );
			}
		} );
	}

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
			cf_ajax( 'layout', to );
		} );
	} );

	wp.customize( 'cf_lp_color_scheme', function( value ) {
		value.bind( function( to ) {
			// AJAX update
			cf_ajax( 'color_scheme', to );
		} );
	} );
} )( jQuery );
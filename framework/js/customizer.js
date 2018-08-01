/**
 * Customizer handlers to reload changes asynchronously.
 */
( function( $ ) {
	// Background color
	if ( $(window).width() > 768 ) {
		wp.customize( 'cf_bc_fixed_width', function( value ) {
			value.bind( function() {
				$( 'body' ).toggleClass( 'sbc-fixed-width' );
			} );
		} );

		wp.customize( 'cf_bc_max_width', function( value ) {
			value.bind( function() {
				$( 'body' ).toggleClass( 'sbc-max-width' );
			} );
		} );

		wp.customize( 'cf_bc_scale', function( value ) {
			value.bind( function( to ) {
				$( 'body' ).removeClass( 'sbc-scale-smaller' ).removeClass( 'sbc-scale-larger' );
				$( 'body' ).addClass( 'sbc-scale-' + to );
			} );
		} );

		wp.customize( 'cf_bc_button_flat', function( value ) {
			value.bind( function( to ) {
				if ( to == true ) {
					$( 'body' ).addClass( 'sbc-buttons-flat' );
				} else {
					$( 'body' ).removeClass( 'sbc-buttons-flat' );
				}
			} );
		} );
	}
} )( jQuery );
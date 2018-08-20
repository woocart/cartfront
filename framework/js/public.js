/**
 * Hamburger menu js for the front-end.
 */
( function( $ ) {
	$( function() {
		$( '.main-navigation .handheld-navigation, .main-navigation div.menu' ).prepend( '<span class="cfhm-close">' + cartfront_localize.close + '</span>' );

		$( '.cfhm-close' ).on( 'click', function() {
			$( '.menu-toggle' ).trigger( 'click' );
		} );

		$( document ).click( function( event ) {
			var menuContainer = $( '.main-navigation' );

			if ( $( '.main-navigation' ).hasClass( 'toggled' ) ) {
				if ( ! menuContainer.is( event.target ) && 0 === menuContainer.has( event.target ).length ) {
					event.preventDefault();
					event.stopPropagation();
					$( '.menu-toggle' ).trigger( 'click' );
				}
			}
		} );
	} );
} )( jQuery );
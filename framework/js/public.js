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
(function( $ ) {
	'use strict';

	$( document ).ready( function() {
		if( cartfront_localize.ss_items_row !== '1' ) {
			var cf_ss_responsive = [{
				breakpoint: 768,
				settings: {
					slidesToShow: 2
				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 1
				}
			}]
		} else {
			var cf_ss_responsive = [];
		}

		/* Posts */
		$( '.cartfront-featured-container' ).slick( {
			infinite: true,
			dots: false,
			arrows: false,
			speed: 1500,
			slidesToShow: cartfront_localize.ss_items_row,
			swipeToSlide: true,
			autoplay: true,
			autoplaySpeed: 4000,
			adaptiveHeight: true,
			responsive: cf_ss_responsive
		} );
	} );
} )( jQuery );
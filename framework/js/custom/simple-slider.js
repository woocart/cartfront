(function( $ ) {
	'use strict';

	$( document ).ready( function() {
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
			responsive: [{
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
		} );
	} );
} )( jQuery );
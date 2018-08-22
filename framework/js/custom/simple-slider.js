(function( $ ) {
	'use strict';

	$( document ).ready( function() {
		/* Posts */
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
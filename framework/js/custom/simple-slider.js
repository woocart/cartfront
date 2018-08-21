(function( $ ) {
	'use strict';

	$( document ).ready( function() {
		/* Posts */
		$( '.cartfront-featured-container' ).slick( {
			infinite: true,
			dots: false,
			arrows: false,
			speed: 2500,
			slidesToShow: 3,
			autoplay: true,
			autoplaySpeed: 4000,
			adaptiveHeight: true,
			responsive: [{
				breakpoint: 640,
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
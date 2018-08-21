/**
 * Sorting the homepage control components :)
 */
( function( $ ) {
	'use strict';

	$( document ).ready( function() {

		$( '.cf-hc-components' ).sortable();
		$( '.cf-hc-components' ).disableSelection();
		$( '.cf-hc-components' ).bind( 'sortstop', function( e, ui ) {
			var components 	= new Array();
			var disabled 	= '[disabled]';

			$( e.target ).find( 'li' ).each( function( i, e ) {
				if( $( this ).hasClass( 'disabled' ) ) {
					components.push( disabled + $( this ).attr( 'id' ) );
				} else {
					components.push( $( this ).attr( 'id' ) );
				}
			} );

			components = components.join( ',' );

			$( 'input[data-customize-setting-link="cf_hc_data"]' ).attr( 'value', components ).trigger( 'change' );
		} );

		$( '.cf-hc-components .visibility' ).bind( 'click', function( e ) {
			var components 	= new Array();
			var disabled 	= '[disabled]';

			$( this ).parent( 'li' ).toggleClass( 'disabled' );
			$( this ).parents( '.cf-hc-components' ).find( 'li' ).each( function ( i, e ) {
				if ( $( this ).hasClass( 'disabled' ) ) {
					components.push( disabled + $( this ).attr( 'id' ) );
				} else {
					components.push( $( this ).attr( 'id' ) );
				}
			} );

			components = components.join( ',' );

			$( 'input[data-customize-setting-link="cf_hc_data"]' ).attr( 'value', components ).trigger( 'change' );
		} );
	} );
} )( jQuery );
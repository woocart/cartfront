/**
 * Customizer control for repeater field.
 */
( function( $ ) {
    'use strict';

    function media_upload( button_class ) {
        $( 'body' ).on( 'click', button_class, function() {
            var button_id       = '#' + $( this ).attr( 'id' );
            var display_field   = $( this ).parent().children( 'input:text' );
            var _custom_media   = true;

            wp.media.editor.send.attachment = function( props, attachment ) {
                if( _custom_media ) {
                    if( typeof display_field !== 'undefined' ) {
                        switch( props.size ) {
                            case 'full':
                                display_field.val( attachment.sizes.full.url );
                                display_field.trigger( 'change' );
                                break;
                            case 'medium':
                                display_field.val( attachment.sizes.medium.url );
                                display_field.trigger( 'change' );
                                break;
                            case 'thumbnail':
                                display_field.val( attachment.sizes.thumbnail.url );
                                display_field.trigger( 'change' );
                                break;
                            default:
                                display_field.val( attachment.url );
                                display_field.trigger( 'change' );
                        }
                    }

                    _custom_media = false;
                } else {
                    return wp.media.editor.send.attachment( button_id, [props, attachment] );
                }
            };

            wp.media.editor.open( button_class );
            window.send_to_editor = function( html ) {};

            return false;
        } );
    }

    /**
     * Generate unique id.
     */
    function cf_cr_uniqid( prefix, more_entropy ) {
        if( typeof prefix === 'undefined' ) {
            prefix = '';
        }

        var retId;
        var php_js;

        var formatSeed = function( seed, reqWidth ) {
            // to hex str
            seed = parseInt( seed, 10 ).toString( 16 );

            // so long we split
            if( reqWidth < seed.length ) {
                return seed.slice( seed.length - reqWidth );
            }

            // so short we pad
            if( reqWidth > seed.length ) {
                return new Array( 1 + ( reqWidth - seed.length ) ).join( '0' ) + seed;
            }

            return seed;
        };

        // Begin Redundant
        if( ! php_js ) {
            php_js = {};
        }

        // End Redundant.
        if( ! php_js.uniqidSeed ) {
            // Init seed with a big random number.
            php_js.uniqidSeed = Math.floor( Math.random() * 0x75bcd15 );
        }

        php_js.uniqidSeed++;

        // Start with prefix, add current milliseconds hex string.
        retId = prefix;
        retId += formatSeed( parseInt( new Date().getTime() / 1000, 10 ), 8 );

        // Add seed hex string
        retId += formatSeed( php_js.uniqidSeed, 5 ); 

        if( more_entropy ) {
            // For more entropy we add a float lower to 10
            retId += ( Math.random() * 10 ).toFixed( 8 ).toString();
        }

        return retId;

    }

    /**
     * General repeater.
     */
    function cf_cr_refresh_values() {
        $( '.cf-cr-repeater' ).each( function() {
            var values  = [];
            var th      = $( this );

            th.find( '.cf-cr-container' ).each( function() {
                console.log( $( this ) );
                var link        = $( this ).find( '.cf-cr-link' ).val();
                var image_url   = $( this ).find( '.cf-cr-media-url' ).val();
                var title       = $( this ).find( '.cf-cr--title' ).val();
                var subtitle    = $( this ).find( '.cf-cr-subtitle' ).val();

                if( image_url !== '' || title !== '' || subtitle !== '' || link !== '' ) {
                    values.push( {
                        'link': link,
                        'image_url': image_url,
                        'title': escapeHtml( title ),
                        'subtitle': escapeHtml( subtitle )
                    } );

                    // Add Image to Container
                    if( image_url !== '' ) {
                        $( this ).find( '.cf-cr-media-box' ).html( '<img src="' + image_url + '" />' );
                        $( this ).find( '.cf-cr-media-remove' ).show();
                    } else {
                        $( this ).find( '.cf-cr-media-box' ).html( '<span>' + $( this ).find( '.cf-cr-media-box' ).data( 'lang' ) + '</span>' );
                        $( this ).find( '.cf-cr-media-remove' ).hide();
                    }
                }
            } );

            th.find( '.cf-cr-data' ).val( JSON.stringify( values ) );
            th.find( '.cf-cr-data' ).trigger( 'change' );
        } );
    }

    /**
     * Let's modify DOM.
     */
    $( document ).ready( function() {
        var theme_conrols = $( '#customize-theme-controls' );

        theme_conrols.on( 'click', '.cf-cr-title', function() {
            $( this ).next().slideToggle( 'medium', function () {
                if( $( this ).is( ':visible' ) ) {
                    $( this ).prev().addClass( 'repeater-expanded' );
                    $( this ).css( 'display', 'block' );
                } else {
                    $( this ).prev().removeClass( 'repeater-expanded' );
                }
            } );
        } );

        media_upload( '.cf-cr-media-button' );

        $( '.cf-cr-media-url' ).on( 'change', function() {
            cf_cr_refresh_values();
            return false;
        } );

        $( '.cf-cr-media-remove' ).on( 'click', function() {
            var parent = $( this ).parent();
            parent.find( '.cf-cr-media-url' ).val( '' );

            // Remove
            parent.find( '.cf-cr-media-box' ).html( '<span>' + parent.find( '.cf-cr-media-box' ).data( 'lang' ) + '</span>' );
            parent.find( '.cf-cr-media-remove' ).hide();

            // Refresh
            cf_cr_refresh_values();
            return false; 
        } );

        /**
         * Add box.
         */
        theme_conrols.on( 'click', '.cf-cr-new-field', function() {
            var th = $( this ).parent();
            var id = 'cf-cr-' + cf_cr_uniqid();

            if( typeof th !== 'undefined' ) {
                // Clone Box.
                var field = th.find( '.cf-cr-container:first' ).clone( true, true );

                if( typeof field !== 'undefined' ) {
                    // Show Delete Button.
                    field.find( '.cf-cr-remove-field' ).show();

                    // Remove Link Value
                    field.find( '.cf-cr-link' ).val( '' );

                    // Set Box ID
                    field.find( '.cf-cr-box-id' ).val( id );

                    // Remove Media Value
                    field.find( '.cf-cr-media-url' ).val( '' );

                    // Media Box (default)
                    field.find( '.cf-cr-media-box' ).html( '<span>' + field.find( '.cf-cr-media-box' ).data( 'lang' ) + '</span>' );

                    // Hide Remove Image Button
                    field.find( '.cf-cr-media-remove' ).hide();

                    // Remove Title Value
                    field.find( '.cf-cr--title' ).val( '' );

                    // Remove Subtitle Value
                    field.find( '.cf-cr-subtitle' ).val( '' );

                    // Append New Box
                    th.find( '.cf-cr-container:first' ).parent().append( field );

                    // Refresh Values
                    cf_cr_refresh_values();
                }

            }

            return false;
        });

        /**
         * Remove field.
         */
        theme_conrols.on( 'click', '.cf-cr-remove-field', function() {
            if( typeof $( this ).parent() !== 'undefined' ) {
                $( this ).parent().hide( 500, function() {
                    $( this ).parent().remove();
                    cf_cr_refresh_values();
                } );
            }

            return false;
        } );

        // Title.
        theme_conrols.on( 'keyup', '.cf-cr--title', function() {
            cf_cr_refresh_values();
        } );

        // Subtitle.
        theme_conrols.on( 'keyup', '.cf-cr-subtitle', function() {
            cf_cr_refresh_values();
        } );

        // Link.
        theme_conrols.on( 'keyup', '.cf-cr-link', function() {
            cf_cr_refresh_values();
        } );

        /**
         * Drag n Drop.
         */
        $( '.cf-cr-droppable' ).sortable( {
            axis: 'y',
            update: function () {
                cf_cr_refresh_values();
            }
        } );
    } );

    var entityMap = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        '\'': '&#39;',
        '/': '&#x2F;'
    };

    function escapeHtml( string ) {
        // JSUnresolvedFunction
        string = String( string ).replace( new RegExp( '\r?\n', 'g' ), '<br />' );
        string = String( string ).replace( /\\/g, '&#92;' );

        return String( string ).replace( /[&<>"'\/]/g, function( s ) {
            return entityMap[s];
        } );
    }
} )( jQuery );
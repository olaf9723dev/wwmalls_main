( function($) {
    'use strict';

    $( function() {
        if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {

            $( document ).on( 'click', '.js-ef-media-button', function( e ) {
                e.preventDefault();
                var $button = $( this );
                var $input = $button.closest( '.js-ef-input__media' ).find( '.js-ef-media-input' );
                var $container = $button.closest( '.js-ef-input__media' ).find( '.js-ef-media-container' );
                var is_multiple = $input.data( 'multiple' );

                var frame = wp.media( { multiple: is_multiple } );

                frame.open();

                // When an image is selected in the media frame...
                frame.on( 'select', function() {

                    // Get media attachment details from the frame state
                    var attachments = frame.state().get('selection').toJSON();

                    var ids = [];
                    var src;

                    if ( is_multiple && $input.val() ) {
                        ids = $input.val().split( ',' );
                    }

                    for ( var i in attachments ) {
                        ids.push( attachments[i].id );

                        console.log(attachments[i]);

                        if ( typeof attachments[i].sizes.full.url !== 'undefined' ) {
                            src = attachments[i].sizes.full.url;
                        } else {
                            src = attachments[i].url;
                        }

                        var img = "<div class='ef-media-item js-ef-media-item'>" +
                            "<img src='" + src + "'/>" +
                            "<a href='#' class='ef-media-item__remove js-ef-media-item__remove' data-id='" + attachments[i].id + "'>×</a>" +
                            "</div>";

                        if ( is_multiple ) {
                            $container.append( img );
                        } else {
                            $container.html( img );
                        }
                    }

                    $input.val( ids );

                    $input.closest( '.ef-input__field' ).find( '.hidden' ).removeClass( 'hidden' );
                });

                return false;
            } );
        }

        $( document ).on( 'click', '.js-ef-media-remove', function() {
            $( this ).closest( '.ef-input__field' ).find( '.js-ef-media-item__remove' ).trigger( 'click' );

            return false;
        } );

        $( document ).on( 'click', '.js-ef-media-item__remove', function() {
            var $el = $( this );
            var id = $el.data( 'id' );
            var $input = $el.closest( '.js-ef-input__media' ).find( '.js-ef-media-input' );

            var ids = $input.val().split( ',' );

            var index = ids.indexOf( id.toString() );

            if (index !== -1) {
                ids.splice( index, 1 );
                $input.val( ids );

                $el.closest( '.js-ef-media-item' ).remove();
            }

            return false;
        } );
    } );
} )(jQuery);

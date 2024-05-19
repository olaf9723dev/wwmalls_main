( function( $ ) {
    'use strict';

    $( function() {
        $( '.js-ef-tabs' ).each( function() {
            var $wrapper = $( this );
            var $tabsContentContainer = $wrapper.find( '.ef-tab-content__item' );

            $wrapper.find( '.ef-tab-link__item a' ).click( function() {
                var containerID = $( this ).attr( 'href' );
                window.location.hash = containerID;
                $tabsContentContainer.removeClass( 'active' );
                $wrapper.find( containerID ).addClass( 'active' );
                $( this ).closest( 'ul' ).find( 'li' ).removeClass( 'active' );
                $( this ).closest( 'li' ).addClass( 'active' );

                return false;
            } );

            $wrapper.find( '.ef-tab-link__item:first-child a' ).trigger( 'click' );
        } );
    } );
} )( jQuery );
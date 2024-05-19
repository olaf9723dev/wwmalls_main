( function( $ ) {
    'use strict';

    $( function() {
        $( '[data-help]' ).each( function() {
            $( this ).tooltipster( {
                content: $( this ).data( 'help' ),
                side: ['right'],
                interactive: true,
                theme: ['tooltipster-noir', 'tooltipster-noir-customized']
            } );
        } );

        $( '.js-ef-input__field-inner' ).click( function() {
            $( this ).parent().find( '.js-ef-input__field-inner' ).removeClass( 'active' );
            $( this ).addClass( 'active' );
        } );

        $( '.ef-input input[name*="color"]' ).wpColorPicker();
    } );
} )( jQuery );
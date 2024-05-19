jQuery( document ).ready( function( $ ) {
	'use strict';

	var wp = window.wp,
		data = {},
		$body = $( document.body ),
		template = wp.template( 'motta-icon-box' );

	// Toggle a filter's fields.
	$body.on( 'click', '.motta-icon-box__field-top', function( event ) {
		event.preventDefault();

		$( this )
			.closest( '.motta-icon-box__field' )
			.toggleClass( 'open' )
			.children( '.motta-icon-box__field-options' )
			.toggle();
	} );

	// Add a new filter.
	$body.on( 'click', '.motta-icon-box__add-new', function( event ) {
		event.preventDefault();

		var $button = $( this ),
			$box = $button.closest( '.motta-icon-box__section' ).children( '.motta-icon-box__fields' ),
			$title = $button.closest( '.widget-content' ).find( 'input' ).first();

		data.name = $button.data( 'name' );
		data.count = $button.data( 'count' );

		$button.data( 'count', data.count + 1 );
		$box.append( template( data ) );
		$box.trigger( 'runColor' );
		$title.trigger( 'change' ); // Support customize preview.
	} );

	// Remove a filter.
	$body.on( 'click', '.motta-icon-box__remove', function( event ) {
		event.preventDefault();

		var $button = $( this ),
			$boxs = $button.closest( '.motta-icon-box__fields' );

		$button
			.closest( '.motta-icon-box__field' )
			.hide()
			.remove();

		$boxs
			.closest( '.widget-content' )
			.find( 'input' )
			.first()
			.trigger( 'change' );
	});

	// Live update for the title.
	$body.on( 'input', '.motta-icon-box__field-option[data-option="box:text"] input', function() {
		$( this ).closest( '.motta-icon-box__field' ).find( '.motta-icon-box__field-title' ).text( this.value );
	} );

	// ColorPicker
	function initColorPicker( widget ) {
		widget?.find( '.motta-color-widget' ).wpColorPicker();
	}

	function onUpdate( event, widget ) {
		initColorPicker( widget );
	}

	$( document ).on( 'widget-added widget-updated runColor', onUpdate );

	$( '.widget', '.wp-block-legacy-widget' ).each( function () {
		initColorPicker( $( this ) );
	} );

	$body.on( 'runColor', function() {
		$( '.widget', '.wp-block-legacy-widget' ).each( function () {
			initColorPicker( $( this ).find( '.motta-icon-box__section' ) );
		} );
	} );
} );

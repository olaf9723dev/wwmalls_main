(function($) {
	$(function() {
		// Uploading files
		var file_frame_bg,
			$icon_type   	= $('#motta_help_article_icon_type'),
			$image_id   	= $('#motta_help_article_icon_image_id'),
			$icon_image   	= $('#motta_help_article_icon_type_image'),
			$icon_image_box = $icon_image.find('.motta-cat-icon-image');;

		$icon_image.on('click', '.upload_images_button', function (event) {
			var $el = $(this);

			event.preventDefault();

			// If the media frame already exists, reopen it.
			if (file_frame_bg) {
				file_frame_bg.open();
				return;
			}

			// Create the media frame.
			file_frame_bg = wp.media.frames.downloadable_file = wp.media({
				multiple: false
			});

			// When an image is selected, run a callback.
			file_frame_bg.on('select', function () {
				var selection = file_frame_bg.state().get('selection'),
					attachment_ids = $image_id.val();

				selection.map(function (attachment) {
					attachment = attachment.toJSON();

					if (attachment.id) {
						attachment_ids = attachment.id;

						$icon_image_box.html('<li class="image" data-attachment_id="' + attachment.id + '"><img src="' + attachment.url + '" width="auto" height="100px" /><ul class="actions"><li><a href="#" class="delete" title="' + $el.data('delete') + '">' + $el.data('text') + '</a></li></ul></li>');
					}

				});
				$image_id.val(attachment_ids);
			});


			// Finally, open the modal.
			file_frame_bg.open();
		});

		// Remove images.
		$icon_image.on('click', 'a.delete', function () {
			$(this).closest('li.image').remove();

			var attachment_ids = '';

			$icon_image_box.find('li.image').css('cursor', 'default').each(function () {
				var attachment_id = $(this).attr('data-attachment_id');
				attachment_ids = attachment_ids + attachment_id + ',';
			});

			$image_id.val(attachment_ids);

			return false;
		});

		// Icon Type
		if( $icon_type.val() == 'image' ) {
			$icon_type.closest( '.form-table' ).find( '#motta_help_article_icon_type_field_image' ).show();
			$icon_type.closest( '.form-table' ).find( '#motta_help_article_icon_type_field_svg' ).hide();
		} else if ( $icon_type.val() == 'svg' ) {
			$icon_type.closest( '.form-table' ).find( '#motta_help_article_icon_type_field_image' ).hide();
			$icon_type.closest( '.form-table' ).find( '#motta_help_article_icon_type_field_svg' ).show();
		}

		$icon_type.on( 'change', function () {
			if( $(this).val() == 'image' ) {
				$(this).closest( '.form-table' ).find( '#motta_help_article_icon_type_field_image' ).show();
				$(this).closest( '.form-table' ).find( '#motta_help_article_icon_type_field_svg' ).hide();
			} else if( $(this).val() == 'svg' ) {
				$(this).closest( '.form-table' ).find( '#motta_help_article_icon_type_field_image' ).hide();
				$(this).closest( '.form-table' ).find( '#motta_help_article_icon_type_field_svg' ).show();
			}
		});
	});
})(jQuery);
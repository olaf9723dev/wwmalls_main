<?php wp_enqueue_style( 'es-slick-style' ); $es_property = es_get_property( get_the_ID() ); ?>

<div class="col-12 es-gallery__wrapper">
	<div class="es-gallery">
		<?php do_action( 'es_property_gallery_before_inner', $es_property ); ?>
		<?php if ( $gallery = $es_property->gallery ) : ?>
			<div class="es-gallery-inner">
				<div class="ert-gallery-image">
					<?php foreach ( $gallery as $value ) : ?>
						<div style="background: url(<?php echo wp_get_attachment_image_url( $value, 'full' ); ?>) center center / cover no-repeat;"></div>
					<?php endforeach; ?>
				</div>

				<div class="es-gallery-image-pager-wrap">
					<div class="ert-gallery-image-pager">
						<?php foreach ( $gallery as $value ) : ?>
							<div style="background: url(<?php echo wp_get_attachment_image_url( $value, 'medium' ); ?>) center center / cover no-repeat;"></div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		<?php elseif ( $image = es_get_default_thumbnail( 'es-image-size-archive' ) ): ?>
			<?php echo $image; ?>
		<?php endif; ?>
	</div>
</div>
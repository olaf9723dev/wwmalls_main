<?php global $es_settings;
$es_property = es_get_property( get_the_ID() );
$categories = wp_get_post_terms( get_the_ID(), 'es_category' );
$default_image_class = ! empty( $es_property->gallery ) ? '' : 'ert-no-image'; ?>

<div class="ert-property-item__inner">
	<?php include locate_template( 'estatik/content-archive-image.php' ); ?>

	<div class="ert-property-item__content-wrap">

		<div class="ert-property-item__content">
			<?php es_the_title( '<h3><a href="' . get_the_permalink() . '">', '</a></h3>' ); ?>
			<?php es_the_address( '<span class="ert-address">', '</span>' ); ?>

            <?php if ( empty( $slick_config ) ) : ?>
			    <div class="ert-excerpt"><?php the_excerpt(); ?></div>
            <?php endif; ?>

			<?php do_action( 'ert_property_item_fields', $es_property ); ?>

			<?php es_the_types( '<span class="ert-property-types">', ' ', '</span>' ); ?>
		</div>


		<div class="ert-property-item__footer">
			<?php if ( $es_property->latitude && $es_property->longitude && $es_settings->google_api_key ) : ?>
				<a href="#" class="es-map-view-link es-hover-show" data-longitude="<?php echo $es_property->longitude; ?>"
				   data-latitude="<?php echo $es_property->latitude; ?>"><?php _e( 'View on map', 'es-plugin' ); ?></a>
			<?php endif; ?>

			<a href="<?php the_permalink(); ?>" class="btn btn-light"><?php _e( 'View Details', 'ert' ); ?></a>
		</div>
	</div>
</div>

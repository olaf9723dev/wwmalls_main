<?php global $es_settings;
$es_property = es_get_property( get_the_ID() );
$categories = wp_get_post_terms( get_the_ID(), 'es_category' );
$default_image_class = ! empty( $es_property->gallery ) ? '' : 'ert-no-image'; ?>

<div class="ert-listing ert-layout-col">
	<div class="ert-property-item">
        <a href="" class="es-overlay__close"><i class="fa fa-times" aria-hidden="true"></i></a>
		<div class="ert-property-item__inner">
			<?php include locate_template( 'estatik/content-archive-image.php' ); ?>

			<div class="ert-property-item__content-wrap">
				<div class="ert-property-item__content">
					<?php es_the_title( '<h3><a href="' . get_the_permalink() . '">', '</a></h3>' ); ?>
					<?php es_the_address( '<span class="ert-address">', '</span>' ); ?>

					<?php do_action( 'ert_property_item_fields', $es_property ); ?>

					<?php es_the_types( '<span class="ert-property-types">', ' ', '</span>' ); ?>
				</div>
			</div>
		</div>
	</div>
</div>
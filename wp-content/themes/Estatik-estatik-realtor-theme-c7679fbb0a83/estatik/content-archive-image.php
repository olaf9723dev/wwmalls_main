<?php

/**
 * @var $default_image_class string
 */

$es_property = es_get_property( get_the_ID() ); ?>
<div class="ert-property-item__image <?php echo $default_image_class; ?>">
	<div class="ert-property-item__image-inner" style="background-image: url(<?php echo es_get_the_post_thumbnail_url( 'es-image-size-archive' ); ?>); background-size: cover;">
		<a href="<?php the_permalink(); ?>">
            <div class="ert-property-item__image-overlay"></div>
		</a>

		<div class="erp-property-item__image-badges erp-property-item__image-badges--left-top">
			<?php do_action( 'es_property_labels_badges' ); ?>
		</div>

		<div class="erp-property-item__image-badges erp-property-item__image-badges--right-top">
			<?php do_action( 'es_property_categories_badges' ); ?>
		</div>

		<div class="ert-property-item__image-price">
			<?php es_the_formatted_price(); ?>
			<?php es_the_property_field( 'price_note', '<span class="es-price-note">', '</span>' ); ?>
		</div>

		<div class="ert-property-control">
			<?php do_action( 'es_wishlist_add_button', get_the_ID() ) ?>
			<a href="<?php the_permalink(); ?>"><i class="fa fa-mail-forward"></i></a>
			<?php if ( $es_property->latitude && $es_property->longitude && $es_settings->google_api_key ) : ?>
				<a href="#" class="es-map-view-link es-hover-show" data-longitude="<?php echo $es_property->longitude; ?>"
				   data-latitude="<?php echo $es_property->latitude; ?>"><i class="fa fa-map-marker"></i></a>
			<?php endif; ?>
		</div>

	</div>
</div>

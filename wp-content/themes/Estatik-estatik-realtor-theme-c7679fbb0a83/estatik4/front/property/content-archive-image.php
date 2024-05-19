<?php

/**
 * @var $wishlist_confirm bool
 */

$property = es_get_the_property();
$default_image_class = has_post_thumbnail() ? '' : 'ert-no-image'; ?>
<div class="ert-property-item__image <?php echo $default_image_class; ?>">
    <div class="ert-property-item__image-inner" style="background-image: url(<?php echo es_get_the_featured_image_url( 'medium' ); ?>); background-size: cover;">
        <a href="<?php echo es_get_the_permalink(); ?>">
            <div class="ert-property-item__image-overlay"></div>
        </a>

        <div class="erp-property-item__image-badges erp-property-item__image-badges--left-top">
            <?php do_action( 'es_property_badges' ); ?>
        </div>

        <div class="erp-property-item__image-badges erp-property-item__image-badges--right-top">
            <?php do_action( 'es_property_categories_badges' ); ?>
        </div>

        <div class="ert-property-item__image-price">
            <?php es_the_price(); ?>
            <?php es_the_property_field( 'price_note', '<span class="es-price-note">', '</span>' ); ?>
        </div>

        <div class="ert-property-control ert-property-control--property-image">
            <?php do_action( 'es_property_control', array(
                'context' => 'property-image',
                'wishlist_confirm' => $wishlist_confirm,
            ) ); ?>
        </div>
    </div>
</div>

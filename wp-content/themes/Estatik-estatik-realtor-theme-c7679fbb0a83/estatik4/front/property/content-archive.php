<div class="js-es-listing ert-property-item properties es-listing--<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>">
    <div class="ert-property-item__inner">
        <?php $property = es_get_the_property();

        es_load_template( 'front/property/content-archive-image.php', array(
            'wishlist_confirm' => ! empty( $wishlist_confirm ) ? $wishlist_confirm : null,
        ) ); ?>

        <div class="ert-property-item__content-wrap">
            <div class="ert-property-item__content">
                <?php es_the_title( '<h3><a href="' . get_the_permalink() . '">', '</a></h3>' ); ?>
                <?php es_the_address( '<span class="ert-address">', '</span>' );

                if ( empty( $slick_config ) ) : ?>
                    <div class="ert-excerpt"><?php the_excerpt(); ?></div>
                <?php endif;

                do_action( 'es_property_meta', array( 'use_icons' => true ) );

                the_terms( 0, 'es_type', '<span class="ert-property-types">', ' ', '</span>' );  ?>
            </div>

            <div class="ert-property-item__footer">
                <?php if ( $property->latitude && $property->longitude && ests( 'google_api_key' ) ) : ?>
                    <a href="#es-map-popup" class="js-es-popup-link es-map-view-link es-hover-show" data-longitude="<?php echo $property->longitude; ?>"
                       data-latitude="<?php echo $property->latitude; ?>"><?php _e( 'View on map', 'ert' ); ?></a>
                <?php endif;?>

                <a href="<?php echo es_get_the_permalink(); ?>" class="btn btn-light"><?php _e( 'View Details', 'ert' ); ?></a>
            </div>
        </div>
    </div>
</div>

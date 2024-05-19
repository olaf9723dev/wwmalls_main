<?php

/**
 * @var $images
 */

wp_enqueue_style( 'es-slick-style' );
$es_property = es_get_property( get_the_ID() ); ?>

<div class="col-12 es-gallery__wrapper">
    <div class="es-gallery">
        <div class="es-control-wrap">
            <?php do_action( 'es_property_control', array(
                'show_sharing' => true,
                'is_full' => true,
                'icon_size' => 'big'
            ) ) ; ?>
        </div>

        <?php do_action( 'es_property_gallery_before_inner', $es_property ); ?>
        <?php if ( $images ) : ?>
            <div class="es-gallery-inner">
                <div class="ert-gallery-image">
                    <?php foreach ( $images as $value ) : ?>
                        <div style="background: url(<?php echo wp_get_attachment_image_url( $value, 'full' ); ?>) center center / cover no-repeat;"></div>
                    <?php endforeach; ?>
                </div>

                <div class="es-gallery-image-pager-wrap">
                    <div class="ert-gallery-image-pager">
                        <?php foreach ( $images as $value ) : ?>
                            <div style="background: url(<?php echo wp_get_attachment_image_url( $value, 'medium' ); ?>) center center / cover no-repeat;"></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php elseif ( $image = es_get_the_featured_image_url( 'es-image-size-archive' ) ): ?>
            <img src="<?php echo $image; ?>" alt="<?php the_title(); ?>"/>
        <?php endif; ?>
    </div>
</div>
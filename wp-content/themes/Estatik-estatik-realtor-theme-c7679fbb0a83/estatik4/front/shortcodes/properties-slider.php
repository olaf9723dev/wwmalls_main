<?php

/**
 * @var $query WP_Query
 * @var $item_template WP_Query
 * @var $slider_config array
 * @var $attributes array
 * @var $container_classes string
 */

if ( empty( $attributes['slider_view'] ) ) {
    $attributes['slider_view'] = 'v2';
}

$slider_config['prevArrow'] = "#{$attributes['id']} .slick-prev";
$slider_config['nextArrow'] = "#{$attributes['id']} .slick-next";

if ( $query->have_posts() ):
    if ( ! empty( ! empty( $attributes['space_between_slides'] ) ) ) : ?>
        <style><?php $margin = $attributes['space_between_slides']; ?>
            #<?php echo $attributes['id']; ?>>.es-properties-slider>.slick-list {
                margin: 0 -<?php echo $margin / 2; ?>px;
            }

            #<?php echo $attributes['id']; ?>>.es-properties-slider>.slick-list>.slick-track>.slick-slide {
                 padding: 0 <?php echo $margin / 2; ?>px;
            }
        </style>
    <?php endif; ?>
    <div id="<?php echo $attributes['id']; ?>" class="ert-properties-slider__wrapper ert-properties-slider__wrapper--<?php echo $attributes['slider_view']; ?>">
        <?php if ( $attributes['slider_view'] == 'v1' ) : ?>
            <div class="ert-properties-slider__arrow">
                <a href="#" class="slick-arrow slick-prev"><?php _e( 'Prev', 'ert' ); ?></a>
                <span>|</span>
                <a href="#" class="slick-arrow slick-next"><?php _e( 'Next', 'ert' ); ?></a>
            </div>
        <?php else: ?>
            <div class="ert-properties-slider__arrow--v2">
                <a href="#" class="slick-arrow slick-prev"><i class="fa fa-angle-left"></i></a>
                <a href="#" class="slick-arrow slick-next"><i class="fa fa-angle-right"></i></a>
            </div>
        <?php endif; ?>

        <div class="es-properties-slider ert-listing <?php echo $container_classes; ?>" data-slick="<?php echo es_esc_json_attr( $slider_config ); ?>"><?php
            while ( $query->have_posts() ) : $query->the_post();
                ?><div class="ert-property-item">
                    <?php if ( $attributes['slider_view'] == 'v1' ) : ?>
                        <?php es_load_template( $item_template, array(
                            'ignore_wrapper' => true,
                        ) ); ?>
                    <?php else : ?>
                        <?php es_load_template( 'front/property/content-archive-image.php', array(
                        'wishlist_confirm' => ! empty( $wishlist_confirm ) ? $wishlist_confirm : null,
                    ) ); ?>
                    <?php endif; ?>
                </div><?php
            endwhile;
        ?></div>
    </div>
    <?php wp_reset_postdata();
endif;

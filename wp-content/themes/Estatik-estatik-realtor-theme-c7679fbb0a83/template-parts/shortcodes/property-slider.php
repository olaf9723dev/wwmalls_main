<?php

/**
 * @var $atts array
 * @var $query WP_Query
 * @var $slick_config WP_Query
 */

global $es_settings;

if ( $query->have_posts() ) :

	wp_enqueue_style( 'es-slick-style' ); ?>

    <div class="ert-properties-slider__wrapper ert-properties-slider__wrapper--<?php echo $atts['view']; ?>" id="ert-properties-slider__wrapper-<?php echo $atts['uid']; ?>">

        <?php if ( $atts['view'] == 'v1' ) : ?>
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

        <div class="ert-properties-slider js-ert-slick ert-listing ert-layout-col" data-slick='<?php echo $slick_config; ?>'>
		    <?php while ( $query->have_posts() ) : $query->the_post(); $property = es_get_property( get_the_ID() );
			    $default_image_class = empty( $property->gallery ) ? 'ert-no-image' : ''; ?>
                <div class="ert-property-item">
	                <?php if ( $atts['view'] == 'v1' && ! is_estatik4() ) : ?>
				        <?php include locate_template( 'estatik/content-archive-inner.php' ); ?>
                    <?php else: ?>
                        <div class="ert-property-item__inner">
                            <?php if ( is_estatik4() ) : ?>
                                <?php include locate_template( 'estatik4/front/property/content-archive-image.php' ); ?>
                            <?php else : ?>
                                <?php include locate_template( 'estatik/content-archive-image.php' ); ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
		    <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
<?php endif;

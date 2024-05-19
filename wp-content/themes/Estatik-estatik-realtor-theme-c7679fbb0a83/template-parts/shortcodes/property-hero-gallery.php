<?php

/**
 * @var WP_Query $properties_query
 */

if ( $properties_query->have_posts() ) : ?>
	<div class="ert-hero-gallery">
		<div class="ert-hero-gallery__inner js-ert-hero-gallery">
			<?php while ( $properties_query->have_posts() ) : $properties_query->the_post(); ?>
				<?php if ( has_post_thumbnail() ) : $es_property = es_get_property( get_the_ID() ); ?>
					<div class="ert-hero-gallery__slide" style="background: url('<?php echo get_the_post_thumbnail_url( null, 'full' ); ?>') center / cover no-repeat;">
                        <div class="ert-property-item">

                            <div class="erp-property-item__image-badges">
		                        <?php do_action( 'es_property_labels_hero_badges' ); ?>
                            </div>

                            <div class="erp-property-item__image-badges">
		                        <?php do_action( 'es_property_categories_hero_badges' ); ?>
                            </div>

                            <div class="ert-property-item__inner">
                                <div class="ert-property-item__content-wrap">
                                    <div class="ert-property-item__content">
                                        <?php es_the_title( '<h3><a href="' . get_the_permalink() . '">', '</a></h3>' );
                                        es_the_address( '<span class="ert-address">', '</span>' );

                                        if ( is_estatik4() ) {
                                            do_action( 'es_property_meta', array( 'use_icons' => true ) );
                                            es_the_formatted_field( 'es_type', '<span class="ert-property-types">', '</span>' );
                                        } else {
                                            do_action( 'ert_property_item_fields', $es_property );
                                            es_the_types( '<span class="ert-property-types">', ' ', '</span>' );
                                        } ?>
                                    </div>

                                    <div class="ert-property-item__footer">
                                        <?php if ( is_estatik4() ) :
                                            es_the_formatted_field( 'price' );
                                        else :
                                            es_the_formatted_price();
                                        endif; ?>

                                        <a href="<?php the_permalink(); ?>" class="btn btn-light"><?php _e( 'View Details', 'ert' ); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
				<?php endif;
            endwhile; wp_reset_postdata(); ?>
		</div>
	</div>
<?php endif;

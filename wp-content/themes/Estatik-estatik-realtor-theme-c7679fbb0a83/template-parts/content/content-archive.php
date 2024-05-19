<?php

global $ef_options;
$post_class = ! empty( $post_class ) ? $post_class : $ef_options->get( 'blog_items_per_row' ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $post_class . ' ert-post-item' ); ?>>
	<div class="ert-post-item__inner">
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="ert-post-item__image" style="background-image: url(<?php the_post_thumbnail_url(); ?>); background-size: cover; background-position: center;"></div>
        <?php endif; ?>
        <div class="ert-post-item__content-wrap">
            <div class="ert-post-item__content">
                <?php the_title( '<h3><a href="' . get_the_permalink() . '">', '</a></h3>' ); ?>
                <div class="ert-date"><?php the_date( 'F d, Y' ); ?></div>

                <div class="ert-excerpt"><?php the_excerpt(); ?></div>
            </div>

            <div class="ert-post-item__footer">
                <?php ert_post_author( get_the_author_meta('ID'), false ); ?>
                <a href="<?php the_permalink(); ?>" class="btn btn-light"><?php _e( 'Read more', 'ert' ); ?></a>
            </div>
        </div>
	</div>
</article>

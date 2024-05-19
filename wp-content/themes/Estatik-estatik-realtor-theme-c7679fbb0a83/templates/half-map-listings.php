<?php

/**
 * Template Name: Half Map Listings
 * Template Post Type: page
 */

get_header(); ?>

<?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 ert-search-map"><?php echo do_shortcode( '[es_search_map]' ); ?></div>
                <div class="col-lg-6 ert-scrolled-content">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
<?php endif; ?>

<?php get_footer();

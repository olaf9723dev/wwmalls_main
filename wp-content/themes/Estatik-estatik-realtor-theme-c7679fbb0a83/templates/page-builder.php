<?php

/**
 * Template Name: Page Builder Template
 * Template Post Type: post, page
 */

get_header(); ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div id="primary">
                <main id="main" class="site-main" role="main">
                    <?php while ( have_posts() ) : the_post();
                        the_content();
                    endwhile; ?>
                </main>
            </div>
        </div>
    </div>
</div>

<?php get_footer();

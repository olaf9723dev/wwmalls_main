<?php

/**
 *	Blog posts loop
 */

if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="row">
	<?php if ( have_posts() ) :

		/**
		 * Before blog loop
		 */
		do_action( 'ept_blog_loop_before' );

        while ( have_posts() ) : the_post();
            get_template_part( 'template-parts/content/content-archive', get_post_format() );
        endwhile;

		/**
		 * After blog loop
		 */
		do_action( 'ept_blog_loop_after' );

	else :

		/**
		 * No posts to show
		 *
		 * @hooked ept_blog_no_posts_found_message - 10
		 */
		do_action( 'ept_blog_no_posts_found' );
    endif; ?>
</div>

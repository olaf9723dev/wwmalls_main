<?php

/**
 *	Properties posts loop.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( have_posts() ) :

	/**
	 * Before blog loop
	 */
	do_action( 'ert_property_loop_before' );

	while ( have_posts() ) : the_post();
		the_content();
	endwhile;

	/**
	 * After blog loop
	 */
	do_action( 'ert_property_loop_after' );

else :

	/**
	 * No posts to show
	 *
	 * @hooked ept_blog_no_posts_found_message - 10
	 */
	do_action( 'ert_blog_no_posts_found' );
endif;

<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

get_header();

do_action( 'ert_blog_archive_before_content' ); ?>

    <div class="container wrap-content">

        <div class="row main-container">

			<?php

			/**
			 * ert_blog_archive_content hook
			 *
			 * @hooked ert_blog_breadcrumbs - 10
			 * @hooked ert_blog_posts_before - 20
			 * @hooked ert_blog_posts_loop - 30
			 * @hooked ert_blog_archive_posts_pagination - 40
			 * @hooked ert_blog_posts_after - 50
			 * @hooked ert_blog_sidebar_loop - 60
			 **/
			do_action( 'ert_blog_archive_content' ); ?>

        </div>

    </div>

<?php

/**
 * ert_blog_archive_after_content
 **/
do_action( 'ert_blog_archive_after_content' );

get_footer();

<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

get_header();

do_action( 'ert_properties_archive_before_content' ); ?>

	<div class="container wrap-content">

		<div class="row main-container">

			<?php

			/**
			 * ert_properties_archive_content hook
			 *
			 * @hooked ert_properties_posts_before - 20
			 * @hooked ert_properties_posts_loop - 30
			 * @hooked ert_properties_archive_posts_pagination - 40
			 * @hooked ert_properties_posts_after - 50
			 * @hooked ert_properties_sidebar_loop - 60
			 **/
			do_action( 'ert_properties_archive_content' ); ?>

		</div>

	</div>

<?php

/**
 * ert_properties_archive_after_content
 **/
do_action( 'ert_properties_archive_after_content' );

get_footer();

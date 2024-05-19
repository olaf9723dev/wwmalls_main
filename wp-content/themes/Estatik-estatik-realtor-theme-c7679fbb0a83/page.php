<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

get_header();

do_action( 'ert_page_before_content' ); ?>

	<div class="container wrap-content">

		<div class="row main-container">

			<?php

			/**
			 * ert_page_archive_content hook
			 *
			 * @hooked ert_page_breadcrumbs - 10
			 * @hooked ert_page_before - 20
			 * @hooked ert_page_loop - 30
			 * @hooked ert_page_after - 40
			 * @hooked ert_page_sidebar_loop - 50
			 **/
			do_action( 'ert_page_content' ); ?>

		</div>

	</div>

<?php

/**
 * ert_page_archive_after_content
 **/
do_action( 'ert_page_after_content' );

get_footer();

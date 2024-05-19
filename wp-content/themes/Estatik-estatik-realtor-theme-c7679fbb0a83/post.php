<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

get_header();

/**
 * @hooked ert_post_gallery
 */
do_action( 'ert_post_before_content' ); ?>

	<div class="container wrap-content">

		<div class="row">

			<?php

			/**
			 * ert_post_content hook
			 *
			 * @hooked ert_post_before_widget_area - 10
			 * @hooked ert_post_header - 20
			 * @hooked ert_post_gallery - 30
			 * @hooked ert_post_sidebar_loop - 40
			 * @hooked ert_post_before - 50
			 * @hooked ert_post_loop - 60
			 * @hooked ert_post_after - 70
			 **/
			do_action( 'ert_post_content' ); ?>

		</div>

	</div>

<?php

do_action( 'ert_post_after_content' );

get_footer();

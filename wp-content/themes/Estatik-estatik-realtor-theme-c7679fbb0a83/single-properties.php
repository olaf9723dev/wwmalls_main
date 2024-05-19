<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

get_header();

/**
 * @hooked ert_property_gallery
 */
do_action( 'ert_property_before_content' ); ?>

	<div class="container wrap-content">

		<div class="row">

			<?php

			/**
			 * ert_property_content hook
			 *
             * @hooked ert_property_before_widget_area - 10
             * @hooked ert_property_header - 20
             * @hooked ert_property_gallery - 30
             * @hooked ert_property_sidebar_loop - 40
             * @hooked ert_property_before - 50
             * @hooked ert_property_loop - 60
             * @hooked ert_property_after - 70
			 **/
			do_action( 'ert_property_content' ); ?>

		</div>

	</div>

<?php

do_action( 'ert_property_after_content' );

get_footer();

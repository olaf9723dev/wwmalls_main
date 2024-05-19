<?php

/**
 * Render map popup block for estatik 4.
 *
 * @return void
 */
function es4_map_popup() {
    echo apply_filters( 'es_map_popup', '<style>.mfp-hide{display: none;}</style><div id="es-map-popup" class="mfp-hide">
            <div id="es-map-inner" class="mfp-with-anim"></div></div>' );
}
add_action( 'wp_footer', 'es4_map_popup' );

/**
 * Properties archive sidebar.
 */
if ( ! function_exists( 'ert_properties_sidebar_loop' ) ) {

	function ert_properties_sidebar_loop() {

	    global $ef_options;

		if ( is_active_sidebar( 'sidebar-properties' ) && ! $ef_options->get( 'property_disabled_sidebar' ) ) {
			get_sidebar();
		}
	}
}
add_action( 'ert_properties_archive_content', 'ert_properties_sidebar_loop', 60 );
add_action( 'ert_properties_template_archive_content', 'ert_properties_sidebar_loop', 60 );

/**
 * Blog archive container.
 */
if ( ! function_exists( 'ert_properties_posts_before' ) ) {

	function ert_properties_posts_before() {

	    global $ef_options;

		$container_class = is_active_sidebar( 'sidebar-properties' )
            && ! $ef_options->get( 'property_disabled_sidebar' ) ? 'col-md-8' : 'col-md-12';

		echo "<div id='primary' class='{$container_class}'>
                <div class='ert-listing__wrapper'>";
	}
}
add_action( 'ert_properties_archive_content', 'ert_properties_posts_before', 20 );
add_action( 'ert_properties_template_archive_content', 'ert_properties_posts_before', 20 );

/**
 * Blog archive close container.
 */
if ( ! function_exists( 'ert_properties_posts_after' ) ) {

	function ert_properties_posts_after() {

		echo "</div></div>";
	}
}
add_action( 'ert_properties_archive_content', 'ert_properties_posts_after', 50 );
add_action( 'ert_properties_template_archive_content', 'ert_properties_posts_after', 50 );

/**
 * Blog posts loop.
 */
if ( ! function_exists( 'ert_properties_posts_loop' ) ) {

	function ert_properties_posts_loop() {

		$layout = ert_get_property_layout(); global $wp_query; ?>

            <?php if ( is_estatik4() ) : ?>
                <div class="ert-listing ert-layout-<?php echo $layout; ?>">
            <?php else : ?>
                <div class="row ert-listing ert-layout-<?php echo $layout; ?>">
            <?php endif; ?>

			<?php if ( have_posts() ) {

				/**
				 * Before blog loop
				 */
				do_action( 'ert_properties_loop_before' );

				while ( have_posts() ) : the_post();
					if ( is_estatik4() ) : ?>
                        <?php include es_locate_template( 'front/property/content-archive.php' ); ?>
                    <?php else : ?>
                        <?php include es_locate_template( 'content-archive.php' ); ?>
                    <?php endif;
				endwhile;

				/**
				 * After blog loop
				 */
				do_action( 'ert_properties_loop_after' );

			} else {

				/**
				 * No posts to show
				 *
				 * @hooked ert_properties_no_posts_found_message - 10
				 */
				do_action( 'ert_properties_no_posts_found' );
			} ?>
		</div>
		<?php echo es_the_pagination( $wp_query, array(
			'type' => 'list',
			'prev_text'    => __( 'Prev', 'ert' ),
			'next_text'    => __( 'Next', 'ert' ),
		) );

	}
}
add_action( 'ert_properties_archive_content', 'ert_properties_posts_loop', 30 );
add_action( 'ert_properties_template_archive_content', 'ert_properties_posts_loop', 30 );

/**
 * Display properties sort bar.
 *
 * @return void
 */
function ert_properties_bar() {
	do_action( 'ert_archive_sorting_dropdown', false, false );
}
add_action( 'ert_properties_archive_content', 'ert_properties_bar', 25 );

/**
 * @param $query WP_Query
 */
function ept_properties_get_posts( $query ) {

	if ( $query->is_post_type_archive( 'properties' ) && ! is_admin() && empty( $query->query_vars['ignore_filter'] ) ) {

		if ( ! empty( $_GET['filter']['category'] ) ) {
			$query->set( 'tax_query', array(
				array(
					'taxonomy' => 'es_category',
					'field'    => 'slug',
					'terms'    => $_GET['filter']['category'],
				),
			) );
		}
	}
}
add_action( 'pre_get_posts', 'ept_properties_get_posts', 101 );

/**
 * Archive page title
 *
 * @return void
 */
if ( ! function_exists( 'ert_properties_archive_title' ) ) {

	function ert_properties_archive_title() {
	    global $ef_options;
        if ( is_tax() ) {
            echo "<h2 class='page-title'>" . get_the_archive_title() . "</h2>";
        } else {
            echo "<h2 class='page-title'>" . __( $ef_options->get( 'property_archive_page_name' ), 'ert' ) . "</h2>";
        }
	}
}
add_action( 'ert_properties_archive_before_content', 'ert_properties_archive_title' );

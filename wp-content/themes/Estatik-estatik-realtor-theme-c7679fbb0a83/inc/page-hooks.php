<?php

/*
* @hooked ert_page_breadcrumbs - 10
* @hooked ert_page_before - 30
* @hooked ert_page_loop - 40
* @hooked ert_page_after - 50
* @hooked ert_page_sidebar_loop - 60
 *
 */


add_action( 'ert_page_content', 'ert_page_breadcrumbs', 10 );

if ( ! function_exists( 'ert_page_breadcrumbs' ) ) {

	/**
	 * Page Breadcrumbs.
	 *
	 * @return void
	 */
	function ert_page_breadcrumbs() {
		ert_the_breadcrumbs();
	}
}

add_action( 'ert_page_content', 'ert_page_title', 20 );

if ( ! function_exists( 'ert_page_title' ) ) {

	/**
	 * Page content.
	 *
	 * @return void
	 */
	function ert_page_title() {

		if ( have_posts() ) :
			while( have_posts() ) : the_post(); ?>

			<header class="entry-header col-12">
				<h2 class="entry-title"><?php the_title(); ?></h2>
			</header>

			<?php endwhile;
		endif;
	}

}

add_action( 'ert_page_content', 'ert_page_before', 30 );

if ( ! function_exists( 'ert_page_before' ) ) {

	/**
	 * Before page container.
	 *
	 * @return void
	 */
	function ert_page_before() {
		$css_class = is_active_sidebar( 'sidebar-page' ) ? 'col-md-8' : 'col-12'; ?>
		<div class="<?php echo $css_class; ?> site-content" id='primary'>
		<?php
	}
}

add_action( 'ert_page_content', 'ert_page_loop', 40 );

if ( ! function_exists( 'ert_page_loop' ) ) {

	/**
	 * Page content.
	 *
	 * @return void
	 */
	function ert_page_loop() {

		if ( have_posts() ) :
			while( have_posts() ) : the_post(); ?>

			<div class="entry-content">
				<?php the_content(); ?>
			</div>

			<?php endwhile;
		endif;
	}

}

add_action( 'ert_page_content', 'ert_page_after', 50 );

if ( ! function_exists( 'ert_page_after' ) ) {

	/**
	 * Before page container.
	 *
	 * @return void
	 */
	function ert_page_after() {
		echo "</div>";
	}
}

add_action( 'ert_page_content', 'ert_page_sidebar_loop', 60 );

if ( ! function_exists( 'ert_page_sidebar_loop' ) ) {

	/**
	 * Page Sidebar.
	 *
	 * @return void
	 */
	function ert_page_sidebar_loop() {

		if ( is_active_sidebar( 'sidebar-page' ) ) {
			get_sidebar( 'sidebar-page' );
		}
	}
}
<?php

/**
 * Blog archive container.
 */
if ( ! function_exists( 'ert_blog_posts_before' ) ) {

	function ert_blog_posts_before() {

		global $ef_options;

		$container_class = ! $ef_options->get( 'blog_disable_sidebar' ) && is_active_sidebar( 'sidebar-blog' ) ? 'col-md-8' : 'col-md-12';

		echo "<div id='primary' class='{$container_class} blog-archive'>";
	}
}
add_action( 'ert_blog_archive_content', 'ert_blog_posts_before', 10 );

/**
 * Blog archive close container.
 */
if ( ! function_exists( 'ert_blog_posts_after' ) ) {

	function ert_blog_posts_after() {

		echo "</div>";
	}
}
add_action( 'ert_blog_archive_content', 'ert_blog_posts_after', 40 );

/**
 * Add pagination to the posts archive page.
 */
if ( ! function_exists( 'ert_blog_archive_posts_pagination' ) ) {

	function ert_blog_archive_posts_pagination() {

		ert_the_posts_pagination();
	}
}
add_action( 'ert_blog_archive_content', 'ert_blog_archive_posts_pagination', 30 );

/**
 * Blog posts loop.
 */
if ( ! function_exists( 'ert_blog_posts_loop' ) ) {

	function ert_blog_posts_loop() {

		get_template_part( 'template-parts/content/posts-loop' );
	}
}
add_action( 'ert_blog_archive_content', 'ert_blog_posts_loop', 20 );

/**
 * Blog archive sidebar.
 */
if ( ! function_exists( 'ert_blog_sidebar_loop' ) ) {

	function ert_blog_sidebar_loop() {
		global $ef_options;

		if ( ! $ef_options->get( 'blog_disable_sidebar' ) && is_active_sidebar( 'sidebar-blog' ) ) {
			get_sidebar( 'blog' );
		}
	}
}
add_action( 'ert_blog_archive_content', 'ert_blog_sidebar_loop', 50 );

/**
 * No results found.
 */
if ( ! function_exists( 'ert_blog_no_posts_found_message' ) ) {

	function ert_blog_no_posts_found_message() { ?>
		<h3 class="no-posts-found col-md-12"><?php _e( 'There are no posts to show', 'ert' ); ?></h3>
		<?php
	}
}
add_action( 'ert_blog_no_posts_found', 'ert_blog_no_posts_found_message' );

/**
 * Display breadcrumbs.
 */
if ( ! function_exists( 'ert_breadcrumbs' ) ) {

	function ert_breadcrumbs() {
	    echo "<div class='container title-container'><div class='row'>";
		ert_the_breadcrumbs();
		echo "</div></div>";
	}
}
add_action( 'ert_blog_archive_before_content', 'ert_breadcrumbs', 5 );

add_action( 'ert_blog_archive_before_content', 'ert_blog_archive_title', 8 );

/**
 * Archive page title.
 */
if ( ! function_exists( 'ert_blog_archive_title' ) ) {

	function ert_blog_archive_title() {

	}
}

if ( ! function_exists( 'ert_blog_archive_before_widget_area' ) ) {

	/**
	 * Register widget area after content.
	 */
	function ert_blog_archive_before_widget_area() {
		if ( is_active_sidebar( 'before_blog_archive' ) ) {
			echo "<div class='container'><div class='row'><div class='col-12'>";
			dynamic_sidebar( 'before_blog_archive' );
			echo "</div></div></div>";
        }
	}
}

add_action( 'ert_blog_archive_before_content', 'ert_blog_archive_before_widget_area' );

if ( ! function_exists( 'ert_blog_archive_after_widget_area' ) ) {

	/**
	 * Register widget area after content.
	 */
	function ert_blog_archive_after_widget_area() {
		if ( is_active_sidebar( 'after_blog_archive' ) ) {
			echo "<div class='container'><div class='row'><div class='col-12'>";
			dynamic_sidebar( 'after_blog_archive' );
			echo "</div></div></div>";
        }
	}
}
add_action( 'ert_blog_archive_after_content', 'ert_blog_archive_after_widget_area' );

if ( ! function_exists( 'ert_the_posts_pagination' ) ) {

	/**
	 * Posts pagination function.
	 *
	 * @return void
	 */
	function ert_the_posts_pagination() {

		the_posts_pagination( array(
			'type' => 'list',
			'screen_reader_text' => ' ',
			'prev_text'    => __( 'Prev', 'ert' ),
			'next_text'    => __( 'Next', 'ert' ),
		) );
	}
}
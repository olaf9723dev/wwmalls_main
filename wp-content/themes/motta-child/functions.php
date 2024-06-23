<?php
/**
 * Motta functions and definitions.
 *
 * @link    https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Motta Child
 */

add_action( 'wp_enqueue_scripts', 'motta_child_enqueue_scripts', 20 );

function motta_child_enqueue_scripts() {
	wp_enqueue_style( 'motta-child', get_stylesheet_uri() );

	if ( is_rtl() ) {
		wp_enqueue_style( 'motta-rtl', get_template_directory_uri() . '/rtl.css' );
	}
}

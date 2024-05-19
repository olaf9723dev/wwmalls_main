<?php
/**
 * EmallShop back compat functionality
 *
 * Prevents EmallShop from running on WordPress versions prior to 4.1,
 * since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 4.1.
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */

/**
 * Prevent switching to EmallShop on old versions of WordPress.
 *
 * Switches to the default theme.
 *
 * @since EmallShop 1.0
 */
function emallshop_switch_theme() {
	switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
	unset( $_GET['activated'] );
	add_action( 'admin_notices', 'emallshop_upgrade_notice' );
}
add_action( 'after_switch_theme', 'emallshop_switch_theme' );

/**
 * Add message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * EmallShop on WordPress versions prior to 4.1.
 *
 * @since EmallShop 1.0
 */
function emallshop_upgrade_notice() {
	$message = sprintf( esc_html__( 'EmallShop requires at least WordPress version 4.1. You are running version %s. Please upgrade and try again.', 'emallshop' ), $GLOBALS['wp_version'] );
	printf( '<div class="error"><p>%s</p></div>', $message ); 
}

/**
 * Prevent the Customizer from being loaded on WordPress versions prior to 4.1.
 *
 * @since EmallShop 1.0
 */
function emallshop_customize() {
	wp_die( sprintf( esc_html__( 'EmallShop requires at least WordPress version 4.1. You are running version %s. Please upgrade and try again.', 'emallshop' ), $GLOBALS['wp_version'] ), '', array(
		'back_link' => true,
	) );
}
add_action( 'load-customize.php', 'emallshop_customize' );

/**
 * Prevent the Theme Preview from being loaded on WordPress versions prior to 4.1.
 *
 * @since EmallShop 1.0
 */
function emallshop_preview() {
	if ( isset( $_GET['preview'] ) ) {
		wp_die( sprintf( esc_html__( 'EmallShop requires at least WordPress version 4.1. You are running version %s. Please upgrade and try again.', 'emallshop' ), $GLOBALS['wp_version'] ) );
	}
}
add_action( 'template_redirect', 'emallshop_preview' );
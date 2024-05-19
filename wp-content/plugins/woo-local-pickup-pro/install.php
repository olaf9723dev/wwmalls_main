<?php
/*
Plugin Name:  Local Pickup Pro for WooCommerce
Description:  The powerful Local Pickup Pro for WooCommerce plugin is shipping method for WooCommerce allows your customers to come to pick up their purchased products at store location.
Version:      2.0.0
Author: Team Midriff
Author URI: http://www.midriffinfosolution.org/
Text Domain:  woo-local-pickup
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if WooCommerce is active
**/

if( !function_exists('wlpp_woocommerce_missing_wc_notice') ) {
function wlpp_woocommerce_missing_wc_notice() {
	echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'Local Pickup Pro for WooCommerce requires WooCommerce to be installed and active. You can download %s here.', 'woo-local-pickup' ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) . '</strong></p></div>';
}
}
add_action( 'plugins_loaded', 'wlpp_alert_init' );

if( !function_exists('wlpp_alert_init') ) {
function wlpp_alert_init() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', 'wlpp_woocommerce_missing_wc_notice' );
		return;
	}
}
}

// Version of the plugin
if (!defined('WOO_LOCAL_PICKUP_PRO_VERSION')) {
define('WOO_LOCAL_PICKUP_PRO_VERSION', '2.0.0' );
}

// Directory of the plugin
if (!defined('WOO_LOCAL_PICKUP_PRO_DIR')) {
define( 'WOO_LOCAL_PICKUP_PRO_DIR', plugin_dir_path( __FILE__ ) );
}

if (!defined('WPLL_PLUGIN_FILE')) {
	define('WPLL_PLUGIN_FILE', plugin_basename(__FILE__));
}

include WOO_LOCAL_PICKUP_PRO_DIR . '/includes/wlpp-post-type.php';
include WOO_LOCAL_PICKUP_PRO_DIR . '/includes/wlpp-ship-method.php';
include WOO_LOCAL_PICKUP_PRO_DIR . '/includes/wlpp-checkout.php';
include WOO_LOCAL_PICKUP_PRO_DIR . '/includes/wlpp-data.php';
include WOO_LOCAL_PICKUP_PRO_DIR . '/includes/wlpp-admin.php';
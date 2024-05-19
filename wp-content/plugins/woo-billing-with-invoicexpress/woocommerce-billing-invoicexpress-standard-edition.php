<?php
/*
 * Plugin Name: Invoicing with InvoiceXpress for WooCommerce - Free
 * Plugin URI: https://invoicewoo.com
 * Version: 5.6
 * Description: WooCommerce legal invoicing made easy with InvoiceXpress integration.
 * Author: PT Woo Plugins (by Webdados)
 * Author URI: https://ptwooplugins.com
 * Text Domain: woo-billing-with-invoicexpress
 * Requires at least: 5.6
 * Tested up to: 6.5
 * Requires PHP: 7.0
 * WC requires at least: 6.0
 * WC tested up to: 8.8
 * Requires Plugins: woocommerce
 */

namespace Webdados\InvoiceXpressWooCommerce;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

$composer_autoloader = __DIR__ . '/vendor/autoload.php';
if ( is_readable( $composer_autoloader ) ) {
	require $composer_autoloader;
}

if ( ! defined( 'INVOICEXPRESS_WOOCOMMERCE_PLUGIN_EDITION' ) ) {
	define( 'INVOICEXPRESS_WOOCOMMERCE_PLUGIN_EDITION', 'Free' );
}

if ( ! defined( 'INVOICEXPRESS_WOOCOMMERCE_PLUGIN_BASENAME' ) ) {
	define( 'INVOICEXPRESS_WOOCOMMERCE_PLUGIN_BASENAME', 'Invoicing with InvoiceXpress for WooCommerce' );
}

if ( ! defined( 'INVOICEXPRESS_WOOCOMMERCE_PLUGIN_NAME' ) ) {
	define( 'INVOICEXPRESS_WOOCOMMERCE_PLUGIN_NAME', INVOICEXPRESS_WOOCOMMERCE_PLUGIN_BASENAME . ' - ' . INVOICEXPRESS_WOOCOMMERCE_PLUGIN_EDITION );
}

if ( ! defined( 'INVOICEXPRESS_WOOCOMMERCE_PLUGIN_FILE' ) ) {
	define( 'INVOICEXPRESS_WOOCOMMERCE_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'INVOICEXPRESS_WOOCOMMERCE_PLUGIN_PATH' ) ) {
	define( 'INVOICEXPRESS_WOOCOMMERCE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'INVOICEXPRESS_WOOCOMMERCE_BASENAME' ) ) {
	define( 'INVOICEXPRESS_WOOCOMMERCE_BASENAME', plugin_basename( __FILE__ ) );
}

if ( ! function_exists('get_plugin_data') ) require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
$ix_plugin_data = get_plugin_data( __FILE__ );
if ( ! defined( 'INVOICEXPRESS_WOOCOMMERCE_VERSION' ) ) {
	define( 'INVOICEXPRESS_WOOCOMMERCE_VERSION', $ix_plugin_data['Version'] );
}

// Fields
define( 'INVOICEXPRESS_WOOCOMMERCE_VAT_ORDER_FIELD', '_billing_VAT_code' );
define( 'INVOICEXPRESS_WOOCOMMERCE_VAT_USER_FIELD', 'billing_VAT_code' );
define( 'INVOICEXPRESS_WOOCOMMERCE_VAT_CHECKOUT_FIELD', 'billing_VAT_code' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/Activator.php
 */
register_activation_hook( __FILE__, '\Webdados\InvoiceXpressWooCommerce\Activator::activate' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in lib/Deactivator.php
 */
register_deactivation_hook( __FILE__, '\Webdados\InvoiceXpressWooCommerce\Deactivator::deactivate' );

/**
 * Begins execution of the plugin.
 *
 * @since 1.0.0
 */
function invoicexpress_woocommerce_init() {
	( new Plugin() )->run();
}

\add_action( 'plugins_loaded', __NAMESPACE__ . '\\invoicexpress_woocommerce_init', 1 );


/* HPOS & Checkout Blocks Compatible */
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
	}
} );

/* If you're reading this you must know what you're doing ;-) Greetings from sunny Portugal! */

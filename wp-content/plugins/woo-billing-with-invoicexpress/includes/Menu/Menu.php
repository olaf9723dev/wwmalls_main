<?php

namespace Webdados\InvoiceXpressWooCommerce\Menu;

use \Webdados\InvoiceXpressWooCommerce\Settings\Settings;

/* WooCommerce HPOS ready 2023-07-12 - Except maybe notices - check https://github.com/woocommerce/woocommerce/pull/39193 and remove the hack if implemented */

/**
 * Register menu.
 *
 * @package InvoiceXpressWooCommerce
 * @since   2.0.0
 */
class Menu extends \Webdados\InvoiceXpressWooCommerce\BaseMenu {

	/**
	 * Register hooks.
	 *
	 * @since 2.0.0
	 */
	public function register_hooks() {

		//We need to allow the theme to hook into filters
		add_action( 'after_setup_theme', function() {

			add_action( 'admin_menu', array( $this, 'admin_page' ), 90 );
			add_action( 'admin_notices', array( $this, 'show_admin_notices' ), 20 );
			// Hack to show admin notices on HPOS, after the notices actually are shown, until this PR is accepted: https://github.com/woocommerce/woocommerce/pull/39193
			if ( $this->plugin->hpos_enabled && version_compare( \WC_VERSION, '9999', '<=' ) ) {
				add_action( 'woocommerce_process_shop_order_meta', array( $this, 'show_admin_notices' ), 9999 ); // After automatic invoices
			}
			add_filter( 'plugin_action_links_' . INVOICEXPRESS_WOOCOMMERCE_BASENAME, array( $this, 'add_action_link' ), 10, 2 );

			add_action( 'init', array( $this, 'invoicexpress_api_rewrite_rule' ) );
			add_filter( 'query_vars', array( $this, 'invoicexpress_api_query_var' ) );
			add_action( 'parse_request', array( $this, 'invoicexpress_api_parse_request' ) );

		} );

	}

}

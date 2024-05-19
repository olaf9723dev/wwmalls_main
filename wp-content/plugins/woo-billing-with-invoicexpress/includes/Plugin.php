<?php
// phpcs:disable WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
// phpcs:disable WordPress.NamingConventions.ValidVariableName.MemberNotSnakeCase

namespace Webdados\InvoiceXpressWooCommerce;

/* WooCommerce CRUD ready */
/* WooCommerce HPOS ready 2023-07-13 */

class Plugin {

	/**
	 * Integrations active or not
	 *
	 * @since  2.0.7
	 * @var    string
	 */
	public $wpml_active  = false;
	public $hpos_enabled = false;
	public $invoicexpress_referal_link = 'https://invoicexpress.com?fpr=webdados10';
	public $invoicexpress_plugin_id    = 'f608b455-90b7-47b3-b246-7c8c422e03b0';

	/**
	 * Other variables
	 */
	public $type_names;
	public $scheduled_docs_table;
	public $allow = array();

	/**
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since 2.0.0
	 */
	public function run() {
		$this->set_locale();

		$this->type_names = array(
			'invoice'              => __( 'Invoice', 'woo-billing-with-invoicexpress' ),
			'simplified_invoice'   => __( 'Simplified invoice', 'woo-billing-with-invoicexpress' ),
			'invoice_receipt'      => __( 'Invoice-receipt', 'woo-billing-with-invoicexpress' ),
			'vat_moss_invoice'     => __( 'VAT MOSS invoice', 'woo-billing-with-invoicexpress' ), // For old documents
			'credit_note'          => __( 'Credit note', 'woo-billing-with-invoicexpress' ),
			'vat_moss_credit_note' => __( 'VAT MOSS credit note', 'woo-billing-with-invoicexpress' ), // For old documents
			'quote'                => __( 'Quote', 'woo-billing-with-invoicexpress' ),
			'proforma'             => __( 'Proforma', 'woo-billing-with-invoicexpress' ),
			'transport_guide'      => __( 'Delivery note', 'woo-billing-with-invoicexpress' ),
			'devolution_guide'     => __( 'Return delivery note', 'woo-billing-with-invoicexpress' ),
			'receipt'              => __( 'Receipt', 'woo-billing-with-invoicexpress' ),
		);

		$this->define_hooks();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * @since 2.0.0
	 */
	public function set_locale() {
		load_plugin_textdomain( 'woo-billing-with-invoicexpress' );
	}

	/**
	 * Get the "create_document" options
	 *
	 * @since 2.8.2
	 */
	public function get_create_documents_options() {
		foreach ( $this->type_names as $key => $name ) {
			//Deal with inconsistencies on the database options
			switch( $key ) {
				case 'invoice_receipt':
				case 'quote':
				case 'devolution_guide':
					$option = 'hd_wc_ie_plus_'.$key;
					break;
				case 'receipt':
					$option = 'hd_wc_ie_plus_invoice_payment';
					break;
				default:
					$option = 'hd_wc_ie_plus_create_'.$key;
					break;
			}
			$this->allow[ $key ] = get_option( $option ) ? true : false;
		}
	}

	/**
	 * Register all of the hooks related to the functionality
	 * of the plugin.
	 *
	 * @since 2.0.0
	 */
	public function define_hooks() {
		$settings = new Settings\Settings( $this );
		$modules = array(
			$settings,
			new Menu\Menu( $settings, $this ),
			new Modules\Invoice\InvoiceController( $this ),
			new Modules\SimplifiedInvoice\SimplifiedInvoiceController( $this ),
			new Modules\Taxes\TaxController( $this ),
			new Modules\Vat\VatController( $this ),
		);
		add_action( 'plugins_loaded', array( $this, 'init_integrations_status' ), 8 );
		add_action( 'plugins_loaded', array( $this, 'database_version_upgrade' ), 30 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_register_scripts_and_styles' ) );
		add_filter( 'woocommerce_screen_ids', array( $this, 'woocommerce_screen_ids' ) );
		//We need to allow the theme to hook into filters
		add_action( 'after_setup_theme', array( $this, 'get_create_documents_options' ) );
		foreach ( $modules as $module ) {
			$module->register_hooks();
		}
	}

	/**
	 * Init integration status for third party plugins
	 *
	 * @since 2.0.7
	 */
	public function init_integrations_status() {
		//HPOS
		if ( defined( 'WC_VERSION' ) && version_compare( \WC_VERSION, '7.1', '>=' ) ) {
			if ( wc_get_container()->get( \Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled() ) {
				$this->hpos_enabled = true;
			}
		}
	}

	/**
	 * Register admin scripts and styles
	 *
	 * @since 2.4.10
	 */
	public function admin_register_scripts_and_styles() {
		//WooCommerce Admin Notices compatibility
		if ( function_exists( 'wc_admin_url' ) ) {
			if ( version_compare( \WC_ADMIN_VERSION_NUMBER, '0.23.2', '>=' ) ) {
				if ( class_exists( 'Automattic\WooCommerce\Admin\Loader' ) ) {
					$register = version_compare( \WC_VERSION, '6.5', '>=' ) ? ( \Automattic\WooCommerce\Admin\PageController::is_admin_page() || \Automattic\WooCommerce\Admin\PageController::is_embed_page() ) : ( \Automattic\WooCommerce\Admin\Loader::is_admin_page() || \Automattic\WooCommerce\Admin\Loader::is_embed_page() );
					if ( $register ) {
						wp_register_script( 'hd_wc_ie_woocommerce_admin_notices', plugins_url( 'assets/js/woocommerce-admin-notices.js', INVOICEXPRESS_WOOCOMMERCE_PLUGIN_FILE ), array( 'wp-hooks' ), INVOICEXPRESS_WOOCOMMERCE_VERSION.rand(0,999), true );
						wp_enqueue_script( 'hd_wc_ie_woocommerce_admin_notices' );
					}
				}
			}
		}
	}

	/**
	 * Handle database version upgrade
	 *
	 * @since  2.0.0
	 * @return void
	 */
	public function database_version_upgrade() {
		if ( ! is_admin() ) {
			return;
		}
		include( 'UpgradeFunctions.php' );
		$upgradeFunctions = new UpgradeFunctions( $this );
	}

	/**
	 * Create scheduled_docs_table
	 *
	 * @since 2.5
	 */
	public function create_scheduled_docs_table() {
		//Create table for scheduled automatic documents
		global $wpdb;
		$table_name = $wpdb->prefix.$this->scheduled_docs_table;
		$wpdb_collate = $wpdb->collate;
		$sql =
			"CREATE TABLE {$table_name} (
				task_id bigint(20) UNSIGNED NOT NULL auto_increment,
				order_id  bigint(20) UNSIGNED NOT NULL,
				date_time datetime NOT NULL,
				document_type varchar(30) NOT NULL,
				PRIMARY KEY (task_id)
			)
			COLLATE {$wpdb_collate}";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		do_action( 'invoicexpress_woocommerce_debug', "Created {$table_name} table" );
	}
	public function maybe_create_scheduled_docs_table() {
		global $wpdb;
		$table = $wpdb->prefix.$this->scheduled_docs_table;
		$query = "SHOW TABLES LIKE '{$table}'";
		if ( ! $wpdb->get_row( $query ) ) {
			$this->create_scheduled_docs_table();
		}
	}

	/**
	 * Get possible status.
	 *
	 * @since  2.0.4
	 * @return array
	 */
	public function get_possible_status() {
		return apply_filters( 'invoicexpress_woocommerce_automatic_invoice_possible_status', array( 'wc-pending', 'wc-on-hold', 'wc-processing', 'wc-completed' ) );
	}

	/**
	 * Get not recommended status.
	 *
	 * @since  2.0.4
	 * @return array
	 */
	public function get_not_recommended_status() {
		return apply_filters( 'invoicexpress_woocommerce_automatic_invoice_not_recommended_status', array( 'wc-pending', 'wc-on-hold' ) );
	}

	/**
	 * Get plugin translated option - Not on the free version, tho
	 *
	 * @since  2.0.7
	 * @return string
	 */
	public function get_translated_option( $option, $lang = null, $order_object = null ) {
		return get_option( $option );
	}

	/**
	 * Add our screen to WooCommerce screens so that the correct CSS is loaded
	 *
	 * @since  2.4.2
	 * @return array
	 */
	public function woocommerce_screen_ids( $screens ) {
		$screens[] = 'woocommerce_page_invoicexpress_woocommerce';
		return $screens;
	}

	/**
	 * Check if order type is valid for invoicing
	 *
	 * @since  2.5.2
	 * @return array
	 */
	public function is_valid_order_type( $order_object ) {
		return apply_filters( 'invoicexpress_woocommerce_is_valid_order_type', true, $order_object );
	}

	/**
	 * Convert document type to endpoint, as some InovoiceXpress endpoints are not coherent
	 *
	 * @since  3.0.0
	 * @return array
	 */
	public function document_type_to_endpoint( $document_type, $convert_receipt = false ) {
		$endpoint = $document_type;
		switch( $document_type ) {
			case 'transport_guide':
				$endpoint = 'transport';
				break;
			case 'devolution_guide':
				$endpoint = 'devolution';
				break;
			case 'receipt':
				//Support email 2019-11-05
				if ( $for_email_sending ) $endpoint = 'invoice'; // ??? Not even declared
				break;
		}
		return $endpoint;
	}

	/**
	 * Send the error by email - Moved from BaseController - Does nothing on the Free version
	 */
	public function sendErrorEmail( $order_object, $error_message, $document_type ) {
	}

	/**
	 * Format email to HTML - Moved from BaseController
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function set_email_to_html() {
		return 'text/html';
	}

	/**
	 * Helper to get $order on order edit screens
	 * 
	 * @since  4.0
	 * @return object
	 */
	public function get_order_object_edit_screen( $post_or_order_object ) {
		if ( $post_or_order_object ) {
			return is_a( $post_or_order_object, 'WP_Post' ) ? wc_get_order( $post_or_order_object->ID ) : $post_or_order_object;	
		} else {
			global $post_type, $post;
			if ( $this->hpos_enabled ) {
				$screen = get_current_screen();
				if ( $screen->base == wc_get_page_screen_id( 'shop-order' ) ) {
					global $theorder;
					return $theorder;
				}
			} elseif ( isset( $post_type ) && $post_type && isset( $post ) && $post && $post_type == 'shop_order' ) { //non-hpos
				return wc_get_order( $post->ID );
			}
		}
		return null;
	}

	/**
	 * Get WordPress blog name - Moved from BaseController
	 *
	 * @since  2.0.0
	 * @return string
	 */
	public function get_blogname() {
		return wp_specialchars_decode( $this->get_translated_option( 'blogname' ), ENT_QUOTES ); // WPML translation not working
	}

}

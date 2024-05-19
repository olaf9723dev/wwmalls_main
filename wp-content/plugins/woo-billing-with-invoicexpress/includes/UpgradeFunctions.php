<?php

namespace Webdados\InvoiceXpressWooCommerce;

/* WooCommerce HPOS ready 2023-07-13 */

class UpgradeFunctions {

	/**
	 * The plugin's instance.
	 *
	 * @since  2.0.4
	 * @access protected
	 * @var    Plugin
	 */
	protected $plugin;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 2.3.0
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		$db_version  = get_option( 'hd_wc_ie_plus_db_version', '0' );
		if ( version_compare( $db_version, INVOICEXPRESS_WOOCOMMERCE_VERSION, '<' ) ) {
			if ( version_compare( $db_version, '3.0.0', '<' ) ) {
				$this->upgrade_3_0_0();
			}
			if ( version_compare( $db_version, '3.1.0', '<' ) ) {
				$this->upgrade_3_1_0();
			}
			if ( version_compare( $db_version, '3.3.1', '<' ) ) {
				$this->upgrade_3_3_1();
			}
			if ( version_compare( $db_version, '4.2', '<' ) ) {
				$this->upgrade_4_2();
			}
			// Create table for scheduled automatic documents, if it doesn't exists yet
			$this->plugin->maybe_create_scheduled_docs_table();
			// Upgrade the database version
			update_option( 'hd_wc_ie_plus_db_version', INVOICEXPRESS_WOOCOMMERCE_VERSION );
			do_action( 'invoicexpress_woocommerce_debug', 'Database upgraded to '.INVOICEXPRESS_WOOCOMMERCE_VERSION );
		}

	}

	/**
	 * 3.0.0 Upgrade routines
	 *
	 * @since 3.0.0
	 */
	public function upgrade_3_0_0() {
		//If sending method === '' (and not false) set it to the new value 'woocommerce'
		if ( get_option( 'hd_wc_ie_plus_email_method' ) === '' ) {
			update_option( 'hd_wc_ie_plus_email_method', 'woocommerce' );
		} elseif ( get_option( 'hd_wc_ie_plus_email_method' ) === false ) {
			//If not set (weird), set it to the new default method
			update_option( 'hd_wc_ie_plus_email_method', 'hybrid' );
		}
		//Clear hd_wc_ie_plus_invoice_id meta from simplified and vat moss invoices
		global $wpdb;
		$types = array(
			'simplified_invoice',
			'vat_moss_invoice'
		);
		foreach ( $types as $type ) {
			if ( $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = 'hd_wc_ie_plus_invoice_type' AND meta_value = '{$type}'" ) ) {
				foreach ( $results as $result ) {
					if ( $invoice_id = get_post_meta( $result->post_id, 'hd_wc_ie_plus_invoice_id', true ) ) {
						if ( intval( $invoice_id ) > 0 ) {
							delete_post_meta( $result->post_id, 'hd_wc_ie_plus_invoice_id' );
							update_post_meta( $result->post_id, 'hd_wc_ie_plus_'.$type.'_id', $invoice_id );
						}
					}
				}
			}
		}
	}

	/**
	 * 3.1.0 Upgrade routines
	 *
	 * @since 3.1.0
	 */
	public function upgrade_3_1_0() {
		//Add country prefix as default
		update_option( 'hd_wc_ie_plus_vat_field_prefix', 1 );
	}

	/**
	 * 3.3.1 Upgrade routines
	 * Fix old filenames
	 *
	 * @since 3.3.1
	 */
	public function upgrade_3_3_1() {
		global $wpdb;
		$wp_upload_path = wp_upload_dir();
		$plugin_path    = $wp_upload_path['basedir'];
		foreach( $this->plugin->type_names as $type => $name )  {
			$sql = "SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key LIKE 'hd_wc_ie_plus_".$type."_pdf' AND meta_value LIKE '%.pdf'";
			if ( $results = $wpdb->get_results( $sql ) ) {
				foreach( $results as $result ) {
					if ( trim( $result->meta_value ) != '' ) {
						$parts = explode( '/', $result->meta_value );
						$filename = $parts[count($parts)-1];
						$temp = explode( '-', str_replace( '.pdf', '', $filename ) );
						$lastpart = $temp[count($temp)-1];
						if ( strlen( $lastpart ) == 5 && ! is_numeric( $lastpart ) ) {
							//Already randomized
						} else {
							$newfilename = str_replace( '.pdf', '-'.substr( md5( $filename.time() ), 0, 5 ).'.pdf', $filename );
							@rename( $plugin_path.'/invoicexpress/documents/'.$filename, $plugin_path.'/invoicexpress/documents/'.$newfilename );
							update_post_meta( $result->post_id, $result->meta_key, $wp_upload_path['baseurl'].'/invoicexpress/documents/'.$newfilename );
						}
					}
				}
			}
		}
	}

	/**
	 * 4.2 Upgrade routines
	 * Set filename prefix
	 *
	 * @since 4.3
	 */
	public function upgrade_4_2() {
		update_option( 'hd_wc_ie_plus_filename_prefix', substr( sanitize_title( preg_replace( '/\s+/', '', get_option( 'blogname' ) ) ), 0, 10 ) );
	}

}

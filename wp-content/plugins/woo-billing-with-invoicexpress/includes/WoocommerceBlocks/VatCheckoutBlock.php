<?php
namespace Webdados\InvoiceXpressWooCommerce\WoocommerceBlocks;

/**
 * WooCommerce Checkout Blocks compatibility
 *
 * @package Webdados
 * @since   5.0
 */
class VatCheckoutBlock {

	/**
	 * Variables
	 */
	public $vat_controler;
	public $store_endpoint;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 5.0
	 * @param Plugin $plugin This plugin's instance.
	 */
	public function __construct( \Webdados\InvoiceXpressWooCommerce\Modules\Vat\VatController $vat_controler ) {
		$this->vat_controler = $vat_controler;
	}

	/**
	 * Register hooks.
	 *
	 * @since 5.0
	 */
	public function register_hooks() {
		add_action( 'woocommerce_blocks_loaded', array( $this, 'register_vat_block' ) );
	}

	/**
	 * Register VAT Field Block.
	 *
	 * @since 5.0
	 */
	public function register_vat_block() {
		require_once __DIR__ . '/invoicexpress-vat-blocks-integration.php';
		require_once __DIR__ . '/invoicexpress-vat-extend-store-endpoint.php';
		add_action(
			'woocommerce_blocks_checkout_block_registration',
			function( $integration_registry ) {
				$integration_registry->register( new InvoiceXpress_VAT_Blocks_Integration() );
			}
		);
		$this->store_endpoint = new InvoiceXpress_VAT_Extend_Store_Endpoint( $this->vat_controler );
		$this->store_endpoint->initialize();
	}

}

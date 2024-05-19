<?php
namespace Webdados\InvoiceXpressWooCommerce\WoocommerceBlocks;
use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for integrating with WooCommerce Blocks
 */
class InvoiceXpress_VAT_Blocks_Integration implements IntegrationInterface {

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct() {
	}

	/**
	 * The name of the integration.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'invoicexpress_vat';
	}

	/**
	 * When called invokes any initialization/setup for the integration.
	 */
	public function initialize() {
		$this->register_block_frontend_scripts();
		$this->register_block_editor_scripts();
	}

	/**
	 * Returns an array of script handles to enqueue in the frontend context.
	 *
	 * @return string[]
	 */
	public function get_script_handles() {
		return array( 'invoicexpress-vat-block-frontend' );
	}

	/**
	 * Returns an array of script handles to enqueue in the editor context.
	 *
	 * @return string[]
	 */
	public function get_editor_script_handles() {
		return array( 'invoicexpress-vat-block-editor' );
	}

	/**
	 * An array of key, value pairs of data made available to the block on the client side.
	 *
	 * @return array
	 */
	public function get_script_data() {

		$data = array(
			'defaultLabel'            => __( 'VAT number', 'woo-billing-with-invoicexpress' ),
			'defaultIsRequired'       => ( get_option( 'hd_wc_ie_plus_vat_field_mandatory' ) == 1 ),
			'defaultValidate'         => 1,
		);

		return $data;
	}

	/**
	 * Register block editor scripts.
	 *
	 * @return void
	 */
	public function register_block_editor_scripts() {
		$script_url        = plugins_url( 'includes/WoocommerceBlocks/build/invoicexpress-vat-block.js', INVOICEXPRESS_WOOCOMMERCE_PLUGIN_FILE );
		$script_asset_path = INVOICEXPRESS_WOOCOMMERCE_PLUGIN_PATH . 'includes/WoocommerceBlocks/build/invoicexpress-vat-block.asset.php';
		$script_asset      = file_exists( $script_asset_path )
			? require $script_asset_path
			: array(
				'dependencies' => array(),
				'version'      => $this->get_file_version( $script_asset_path ),
			);

		wp_register_script(
			'invoicexpress-vat-block-editor',
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		wp_set_script_translations(
			'invoicexpress-vat-block-editor',
			'woo-billing-with-invoicexpress'
		);
	}

	/**
	 * Register block frontend scripts.
	 *
	 * @return void
	 */
	public function register_block_frontend_scripts() {
		$script_url        = plugins_url( 'includes/WoocommerceBlocks/build/invoicexpress-vat-block-frontend.js', INVOICEXPRESS_WOOCOMMERCE_PLUGIN_FILE );
		$script_asset_path = INVOICEXPRESS_WOOCOMMERCE_PLUGIN_PATH . 'includes/WoocommerceBlocks/build/invoicexpress-vat-block-frontend.asset.php';
		$script_asset      = file_exists( $script_asset_path )
			? require $script_asset_path
			: array(
				'dependencies' => array(),
				'version'      => $this->get_file_version( $script_asset_path ),
			);

		wp_register_script(
			'invoicexpress-vat-block-frontend',
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		wp_set_script_translations(
			'invoicexpress-vat-block-frontend',
			'woo-billing-with-invoicexpress'
		);
	}

	/**
	 * Get the file modified time as a cache buster if we're in dev mode.
	 *
	 * @param string $file Local path to the file.
	 * @return string The cache buster value to use for the given file.
	 */
	protected function get_file_version( $file ) {
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG && file_exists( $file ) ) {
			return filemtime( $file );
		}
		return INVOICEXPRESS_WOOCOMMERCE_VERSION;
	}
}

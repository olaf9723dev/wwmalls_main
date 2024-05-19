<?php
namespace Webdados\InvoiceXpressWooCommerce\WoocommerceBlocks;
use Automattic\WooCommerce\StoreApi\Schemas\V1\CartSchema;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for extending the WooCommerce Store API
 */
class InvoiceXpress_VAT_Extend_Store_Endpoint {

	/**
	 * Variables
	 */
	public $vat_controler;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param Plugin $plugin This plugin's instance.
	 */
	public function __construct( \Webdados\InvoiceXpressWooCommerce\Modules\Vat\VatController $vat_controler ) {
		$this->vat_controler = $vat_controler;
	}

	/**
	 * The name of the extension.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'invoicexpress_vat';
	}

	/**
	 * When called invokes any initialization/setup for the extension.
	 */
	public function initialize() {
		woocommerce_store_api_register_endpoint_data(
			array(
				'endpoint'        => CartSchema::IDENTIFIER,
				'namespace'       => $this->get_name(),
				'schema_callback' => array( $this, 'store_api_schema_callback' ),
				'data_callback'   => array( $this, 'store_api_data_callback' ),
				'schema_type'     => ARRAY_A,
			)
		);

		woocommerce_store_api_register_update_callback(
			array(
				'namespace' => $this->get_name(),
				'callback'  => array( $this, 'store_api_update_callback' ),
			)
		);

		add_action( 'woocommerce_store_api_checkout_order_processed', array( $this, 'process_order' ) );
	}

	/**
	 * Add Store API schema data.
	 *
	 * @return array
	 */
	public function store_api_schema_callback() {
		return array(
			'InvoiceXpressVat' => array(
				'description' => __( 'VAT number', 'woo-billing-with-invoicexpress' ),
				'type'        => 'string',
				'context'     => array( 'view', 'edit' ),
				'readonly'    => true,
				'optional'    => ! intval( get_option( 'hd_wc_ie_plus_vat_field_mandatory' ) ) == 1,
			),
		);
	}

	/**
	 * Add Store API endpoint data.
	 *
	 * @return array
	 */
	public function store_api_data_callback() {
		$customer     = WC()->customer;
		$session_data = $this->get_session_data();
		$vat_number   = $session_data['InvoiceXpressVat'];

		if ( null === $vat_number ) {
			// Fallback to customer VAT (meta) if there's no VAT in session.
			if ( $customer instanceof \WC_Customer ) {
				$vat_number = $customer->get_meta( INVOICEXPRESS_WOOCOMMERCE_VAT_USER_FIELD );
			}
		}

		$data = array(
			'InvoiceXpressVat' => $vat_number,
			'isValid'          => $this->is_vat_valid(),
		);

		return $data;
	}

	/**
	 * Update callback to be executed by the Store API.
	 *
	 * @param  array $data Extension data.
	 * @return void
	 */
	public function store_api_update_callback( $data ) {
		// Sets the WC customer session if one is not set.
		if ( ! ( isset( WC()->session ) && WC()->session->has_session() ) ) {
			WC()->session->set_customer_session_cookie( true );
		}
		WC()->session->set( $this->get_name(), $data );
	}

	/**
	 * Process order - We need to tak a look at the VatController behaviour and mimic, also, make sure we can override it only on the Pro version (see Pro\Vat)
	 *
	 * @param  \WC_Order $order Order object.
	 * @return void
	 */
	public function process_order( $order ) {
		if ( ! $order instanceof \WC_Order ) {
			return;
		}

		// If Pro and/or another plugin is active, we should look into it - (Pro) Vat.php
		if ( apply_filters( 'invoicexpress_woocommerce_process_vat_vatcontroller', true ) && ! apply_filters( 'invoicexpress_woocommerce_external_vat_blocks', false ) ) {

			$session_data = $this->get_session_data();
			if ( empty( $session_data ) ) {
				return;
			}

			$vat_number = $this->vat_controler->sanitize_vat_field( $session_data['InvoiceXpressVat'] );
	
			// Store VAT in order meta.
			$order->update_meta_data( INVOICEXPRESS_WOOCOMMERCE_VAT_ORDER_FIELD, $vat_number );
			$order->save();

			// Store VAT in customer meta, if logged in.
			$customer_id = $order->get_customer_id();
			if ( ! empty( $customer_id ) ) {
				$customer = new \WC_Customer( $customer_id );
				$customer->update_meta_data( INVOICEXPRESS_WOOCOMMERCE_VAT_USER_FIELD, $vat_number );
				$customer->save_meta_data();
			}

			// Clear the extension's session data.
			WC()->session->__unset( $this->get_name() );

			
			// Anything else?
			do_action( 'invoicexpress_woocommerce_after_update_order_meta_frontend', $order, 'blocks' );
		}

	}

	/**
	 * Retrieve session data.
	 *
	 * @return array
	 */
	public function get_session_data() {
		$data = WC()->session->get( $this->get_name() );
		if ( isset( $data['InvoiceXpressVat'] ) ) {
			$data['InvoiceXpressVat'] = sanitize_text_field( $data['InvoiceXpressVat'] );
		} else {
			$data['InvoiceXpressVat'] = null;
		}
		if ( isset( $data['isRequired'] ) ) {
			$data['isRequired'] = boolval( $data['isRequired'] );
		} else {
			$data['isRequired'] = false;
		}
		if ( isset( $data['validate'] ) ) {
			$data['validate'] = boolval( $data['validate'] );
		} else {
			$data['validate'] = false;
		}
		return $data;
	}

	/**
	 * Determine if the VAT is valid.
	 *
	 * @return boolean
	 */
	public function is_vat_valid() {
		$session_data     = $this->get_session_data();
		$vat_number       = $session_data['InvoiceXpressVat'];
		$is_required      = $session_data['isRequired'];
		$needs_validation = $session_data['validate'];
		// Skip if VAT is not required, needs validation but it's empty.
		if ( ! $is_required && $needs_validation && empty( $vat_number ) ) {
			return true;
		}
		// Check if VAT is required and not provided.
		if ( $is_required && empty( $vat_number ) ) {
			return false;
		}
		// If validation is not needed, consider it valid.
		if ( ! $needs_validation ) {
			return true;
		}
		$customer = WC()->customer;
		if ( $customer instanceof \WC_Customer && 'PT' === $customer->get_billing_country() ) {
			return $this->vat_controler->validate_portuguese_vat( $vat_number );
		}
		return true;
	}
}

<?php
namespace Webdados\InvoiceXpressWooCommerce\Modules\Taxes;

use Webdados\InvoiceXpressWooCommerce\BaseController as BaseController;
use Webdados\InvoiceXpressWooCommerce\Modules\Vat\VatController as VatController;

/* WooCommerce CRUD ready */
/* WooCommerce HPOS ready 2023-01-17 */

class TaxController extends BaseController {

	/**
	 * Register hooks.
	 *
	 * @since 2.0.0
	 */
	public function register_hooks() {
		//Save exemption - Frontend
		add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'taxExemptionFieldUpdateOrderMetaFrontend' ) );
		//Save exemption - Backend
		add_action( 'woocommerce_admin_order_data_after_billing_address', array( $this, 'order_edit_tax_exemption_field' ) );
		add_action( 'woocommerce_process_shop_order_meta', array( $this, 'order_edit_tax_exemption_save' ), 45, 2 );
	}

	public function order_edit_tax_exemption_field( $order_object ) {
		//We only invoice regular orders, not subscriptions or other special types of orders
		if ( ! $this->plugin->is_valid_order_type( $order_object ) ) return;
		$exempt = false;

		if ( $this->order_should_be_exempt( $order_object ) ) {
			$selected_exemption_reason = $order_object->get_meta( '_billing_tax_exemption_reason' );
			$exemption_reasons = VatController::get_exemption_reasons( $selected_exemption_reason );
			?>
			<div class="options_group">
				<p class='form-field form-field-wide'>
					<label for='_billing_tax_exemption_reason'><?php _e( 'Tax Exemption', 'woo-billing-with-invoicexpress' ); ?>:</label>
					<select id='_billing_tax_exemption_reason' name='_billing_tax_exemption_reason'>
						<option value="" <?php selected( '', $selected_exemption_reason ); ?>><?php _e( 'No exemption applicable', 'woo-billing-with-invoicexpress' ); ?></option>
						<?php foreach ( $exemption_reasons as $key => $value ) { ?>
							<option value="<?php echo $key; ?>" <?php selected( $key, $selected_exemption_reason ); ?>><?php echo $value; ?></option>
						<?php } ?>
					</select>
				</p>
			</div>
			<?php
		} else {
			// Is this even needed?
			if ( ! empty( $order_object->get_meta( '_billing_tax_exemption_reason' ) ) ) {
				$order_object->delete_meta_data( '_billing_tax_exemption_reason' );
				$order_object->save();
			}
		}
	}

	public function order_should_be_exempt( $order_object ) {
		if (
			// Order has value
			floatval( $order_object->get_total() ) > 0
			&&
			// Store is Portuguese.
			get_option( 'hd_wc_ie_plus_tax_country' ) == '1'
		) {
			if ( floatval( $order_object->get_total_tax() ) == 0 ) {
				//Full exemption
				return true;
			} elseif ( apply_filters( 'invoicexpress_woocommerce_set_partial_exemption_motive', true ) ) { // 3rd party can now disable it
				//Products
				foreach ( $order_object->get_items() as $key => $item ) {
					if ( floatval( $item->get_total_tax() ) == 0 && floatval( $item->get_total() ) > 0 ) {
						return apply_filters( 'invoicexpress_woocommerce_order_should_be_vat_exempt', true, $order_object );
					}
				}
				//Shipping
				foreach ( $order_object->get_shipping_methods() as $key => $item ) {
					if ( floatval( $item->get_total_tax() ) == 0 && floatval( $item->get_total() ) > 0 ) {
						return apply_filters( 'invoicexpress_woocommerce_order_should_be_vat_exempt', true, $order_object );
					}
				}
				//Fees
				foreach ( $order_object->get_fees() as $key => $item ) {
					if ( floatval( $item->get_total_tax() ) == 0 && floatval( $item->get_total() ) > 0 ) {
						return apply_filters( 'invoicexpress_woocommerce_order_should_be_vat_exempt', true, $order_object );
					}
				}
			}
		}
		return apply_filters( 'invoicexpress_woocommerce_order_should_be_vat_exempt', false, $order_object );
	}

	public function taxExemptionFieldUpdateOrderMetaFrontend( $order_id ) {
		$order_object = wc_get_order( $order_id );
		// Apply exemption?
		if (
			// Default exemption is set
			( get_option( 'hd_wc_ie_plus_exemption_reason' ) != '' )
			&&
			$this->order_should_be_exempt( $order_object )
		) {
			$order_object->update_meta_data( '_billing_tax_exemption_reason', get_option( 'hd_wc_ie_plus_exemption_reason' ) );
			$order_object->save();
		}
	}

	public function order_edit_tax_exemption_save( $id_post_or_order, $post_or_order_object ) {
		//Get order object from post (non-HPOS) or $order (HPOS)
		$order_object = $this->plugin->get_order_object_edit_screen( $post_or_order_object );
		//Only orders
		if ( ! $this->plugin->is_valid_order_type( $order_object ) ) return;
		//Do it
		if ( isset( $_POST['_billing_tax_exemption_reason'] ) ) {
			$order_object->update_meta_data( '_billing_tax_exemption_reason',  sanitize_text_field( $_POST['_billing_tax_exemption_reason'] ) );
			$order_object->save();
		}
	}

}

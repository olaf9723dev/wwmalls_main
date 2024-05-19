<?php
namespace Webdados\InvoiceXpressWooCommerce\Modules\SimplifiedInvoice;

use Webdados\InvoiceXpressWooCommerce\BaseController as BaseController;
use Webdados\InvoiceXpressWooCommerce\JsonRequest as JsonRequest;
use Webdados\InvoiceXpressWooCommerce\ClientChecker as ClientChecker;
use Webdados\InvoiceXpressWooCommerce\Notices as Notices;

/* WooCommerce CRUD ready */
/* JSON API ready */
/* WooCommerce HPOS ready 2023-07-13 */

class SimplifiedInvoiceController extends BaseController {

	public $document_type = 'simplified_invoice';

	/**
	 * Register hooks.
	 *
	 * @since 2.0.0
	 */
	public function register_hooks() {

		//We need to allow the theme to hook into filters
		add_action( 'after_setup_theme', function() {

			if ( $this->plugin->allow[ $this->document_type ] ) {
				//Simplified invoices
				add_filter( 'woocommerce_order_actions', array( $this, 'order_actions' ), 10, 2 );
				add_action( 'woocommerce_order_action_hd_wc_ie_plus_generate_'.$this->document_type, array( $this, 'doAction', ), 10, 2 );
				add_action( 'woocommerce_order_action_hd_wc_ie_plus_finalize_'.$this->document_type, array( $this, 'doActionFinalize', ), 10, 2 );
				add_action( 'woocommerce_order_action_hd_wc_ie_plus_email_'.$this->document_type, array( $this, 'doActionEmail', ), 10, 2 );
			}

		} );

	}

	/**
	 * Add order action.
	 *
	 * @since  2.0.0 Code review.
	 * @since  1.0.0
	 * @param  array $actions Order actions.
	 * @param  WC_Order $order_object Order - Can be null because of AutomateWoo error when setting "Trigger Order Action" on the backend.
	 * @return array
	 */
	public function order_actions( $actions, $order_object = null ) {

		//We only invoice regular orders, not subscriptions or other special types of orders
		if ( ! $this->plugin->is_valid_order_type( $order_object ) ) return $actions;

		$generate_simplified_invoice = esc_html( sprintf(
			'%1$s (%2$s)',
			sprintf(
				/* translators: %s: document type */
				__( 'Issue %s', 'woo-billing-with-invoicexpress' ),
				$this->plugin->type_names[$this->document_type]
			),
			__( 'PDF', 'woo-billing-with-invoicexpress' )
		) );

		$generate_simplified_invoice = apply_filters( 'invoicexpress_woocommerce_order_action_title', $generate_simplified_invoice, $order_object, $this->document_type, 'hd_wc_ie_plus_generate_simplified_invoice' );

		$invoice_id            = $order_object->get_meta( 'hd_wc_ie_plus_invoice_id' );
		$simplified_invoice_id = $order_object->get_meta( 'hd_wc_ie_plus_simplified_invoice_id' );
		$invoice_receipt_id    = $order_object->get_meta( 'hd_wc_ie_plus_invoice_receipt_id' );
		$vat_moss_invoice_id   = $order_object->get_meta( 'hd_wc_ie_plus_vat_moss_invoice_id' );
		$credit_note_id        = $order_object->get_meta( 'hd_wc_ie_plus_credit_note_id' );
		$has_scheduled         = apply_filters( 'invoicexpress_woocommerce_has_pending_scheduled_invoicing_document', false, $order_object->get_id() );

		if ( $has_scheduled ) {
			if ( apply_filters( 'invoicexpress_woocommerce_check_pending_scheduled_document', false, $order_object->get_id(), array( $this->document_type ) ) ) {
				//Has Simplified invoice scheduled - Clock
				$symbol = '&#x1f550;';
			} else {
				//Has another invoicing document scheduled - Cross
				$symbol = '&#xd7;';
			}
		} else {
			if ( empty( $invoice_id ) && empty( $simplified_invoice_id ) && empty( $invoice_receipt_id ) && empty( $vat_moss_invoice_id ) ) {
				//Can be invoiced
				$symbol = '';
			} else {
				//There's already a invoicing document - Cross
				$symbol = '&#xd7;';
				if ( ! empty( $simplified_invoice_id ) ) {
					//There's already a Simplified invoice - Check
					$symbol = '&#x2713;';
				}
			}
		}

		$actions['hd_wc_ie_plus_generate_'.$this->document_type] = trim( sprintf(
			'%s %s: %s',
			$symbol,
			esc_html__( 'InvoiceXpress', 'woo-billing-with-invoicexpress' ),
			$generate_simplified_invoice
		) );

		$actions = apply_filters( 'invoicexpress_woocommerce_order_actions', $actions, $order_object, $this->document_type );

		return $actions;
	}

	public function doAction( $order_object, $mode = 'manual' ) {

		//We only invoice regular orders, not subscriptions or other special types of orders
		if ( ! $this->plugin->is_valid_order_type( $order_object ) ) return;

		//Not euro?
		if ( ! $this->check_order_is_euro( $order_object, $this->document_type, $mode ) ) return;

		$invoice_id            = $order_object->get_meta( 'hd_wc_ie_plus_invoice_id' );
		$simplified_invoice_id = $order_object->get_meta( 'hd_wc_ie_plus_simplified_invoice_id' );
		$invoice_receipt_id    = $order_object->get_meta( 'hd_wc_ie_plus_invoice_receipt_id' );
		$vat_moss_invoice_id   = $order_object->get_meta( 'hd_wc_ie_plus_vat_moss_invoice_id' );
		$credit_note_id        = $order_object->get_meta( 'hd_wc_ie_plus_credit_note_id' );
		$has_scheduled         = apply_filters( 'invoicexpress_woocommerce_has_pending_scheduled_invoicing_document', false, $order_object->get_id() );

		$debug = 'Checking if Simplified invoice document should be issued';
		do_action( 'invoicexpress_woocommerce_debug', $debug, $order_object, array(
			'hd_wc_ie_plus_invoice_id'            => $invoice_id,
			'hd_wc_ie_plus_simplified_invoice_id' => $simplified_invoice_id,
			'hd_wc_ie_plus_invoice_receipt_id'    => $invoice_receipt_id,
			'hd_wc_ie_plus_vat_moss_invoice_id'   => $vat_moss_invoice_id,
			'hd_wc_ie_plus_credit_note_id'        => $credit_note_id,
			'has_scheduled'                       => $has_scheduled,
		) );

		if (
			(
				empty( $invoice_id )
				&&
				empty( $simplified_invoice_id )
				&&
				empty( $invoice_receipt_id )
				&&
				empty( $vat_moss_invoice_id )
				&&
				( ( ! $has_scheduled ) || $mode == 'scheduled' )
			)
			//2.3.1 - Should we really allow to issue an invoicing document after a credit note?
			//||
			//! empty( $credit_note_id )
		) {

			$vat = $order_object->get_meta( INVOICEXPRESS_WOOCOMMERCE_VAT_ORDER_FIELD );
			// Check for VAT number.
			if ( get_option( 'hd_wc_ie_plus_vat_field_mandatory' ) && empty( $vat ) ) {
				/* Add notice */
				$error_notice = sprintf(
					'<strong>%s:</strong> %s',
					__( 'InvoiceXpress error', 'woo-billing-with-invoicexpress' ),
					__( 'The VAT number is a required field.', 'woo-billing-with-invoicexpress' )
				);
				if ( $mode == 'manual' ) {
					Notices::add_notice(
						$error_notice,
						'error'
					);
				} else {
					if ( get_option( 'hd_wc_ie_plus_automatic_email_errors' ) && ( $mode == 'automatic' || $mode == 'scheduled' ) && $error_notice ) {
						$this->plugin->sendErrorEmail( $order_object, $error_notice, $this->document_type );
					}
				}
				do_action( 'invoicexpress_woocommerce_error', $error_notice, $order_object );
				return;
			}

			$client_name = $this->get_document_client_name( $order_object );
			$checker = new ClientChecker();
			$client_info = $checker->maybeCreateClient( $client_name, $order_object );

			$client_data = array(
				'name' => $client_name,
				'code' => $client_info['client_code'],
			);

			$items_data = $this->getOrderItemsForDocument( $order_object, $this->document_type );

			$invoice_data = array(
				'date'             => date_i18n( 'd/m/Y' ),
				'due_date'         => $this->get_due_date( $this->document_type, $order_object ),
				'reference'        => $this->get_order_po_reference( $order_object ),
				'client'           => $client_data,
				'items'            => $items_data,
				'sequence_id'      => $this->find_sequence_id( $order_object->get_id(), $this->document_type ),
				'owner_invoice_id' => $order_object->get_meta( 'hd_wc_ie_plus_transport_guide_id' ),
				'observations'     => $order_object->get_meta( '_document_observations' ),
			);

			$tax_exemption = $order_object->get_meta( '_billing_tax_exemption_reason' );
			if ( ! empty( $tax_exemption ) ) {
				$invoice_data['tax_exemption'] = $tax_exemption;
			}

			$invoice_data = $this->process_items( $invoice_data, $order_object, $this->document_type );

			$invoice_data = apply_filters( 'invoicexpress_woocommerce_'.$this->document_type.'_data', $invoice_data, $order_object );

			//Prevent issuing?
			$prevent = $this->preventDocumentIssuing( $order_object, $this->document_type, $invoice_data, $mode );
			if ( isset( $prevent['prevent'] ) && $prevent['prevent'] ) {
				$this->preventDocumentIssuingLogger( $prevent, $this->document_type, $order_object, $mode );
				return;
			}

			//Final processing of invoice data before sending to the API, without any filter after it
			$invoice_data = $this->process_before_issuing( $invoice_data, $order_object, $this->document_type  );

			//Do it
			$params = array(
				'request' => $this->document_type.'s.json',
				'args'    => array(
					$this->document_type => $invoice_data
				),
			);
			$json_request = new JsonRequest( $params );
			$return = $json_request->postRequest();
			if ( ! $return['success'] ) {
				/* Error creating invoice */
				if ( intval( $return['error_code'] ) == 502 ) {
					/* Add notice */
					$error_notice = sprintf(
						'<strong>%s:</strong> %s',
						__( 'InvoiceXpress error', 'woo-billing-with-invoicexpress' ),
						sprintf(
							/* translators: %s: document type */
							__( 'The %s wasn’t created due to InvoiceXpress service being temporarily down.<br/>Try generating it again in a few minutes.', 'woo-billing-with-invoicexpress' ),
							$this->plugin->type_names[$this->document_type]
						)
					);
					if ( $mode == 'manual' ) {
						Notices::add_notice(
							$error_notice,
							'error'
						);
					}
				} else {
					$codeStr    = __( 'Code', 'woo-billing-with-invoicexpress' );
					$messageStr = __( 'Message', 'woo-billing-with-invoicexpress' );
					/* Add notice */
					$error_notice = sprintf(
						'<strong>%s:</strong> %s',
						__( 'InvoiceXpress error', 'woo-billing-with-invoicexpress' ),
						$codeStr . ': ' . $return['error_code'] . " - " . $messageStr . ': ' . $return['error_message']
					);
					if ( $mode == 'manual' ) {
						Notices::add_notice(
							$error_notice,
							'error'
						);
					}
				}
				if ( get_option( 'hd_wc_ie_plus_automatic_email_errors' ) && ( $mode == 'automatic' || $mode == 'scheduled' ) && $error_notice ) {
					$this->plugin->sendErrorEmail( $order_object, $error_notice, $this->document_type );
				}
				do_action( 'invoicexpress_woocommerce_error', 'Issue Simplified invoice: '.$error_notice, $order_object );
				return;
			}
			
			$order_id_invoicexpress = $return['object']->simplified_invoice->id;

			//Update client data
			$order_object->update_meta_data( 'hd_wc_ie_plus_client_id', $client_info['client_id'] );
			$order_object->update_meta_data( 'hd_wc_ie_plus_client_code', $client_info['client_code'] );
			//Update invoice data
			$order_object->update_meta_data( 'hd_wc_ie_plus_'.$this->document_type.'_id', $order_id_invoicexpress );
			$order_object->update_meta_data( 'hd_wc_ie_plus_'.$this->document_type.'_permalink', $return['object']->simplified_invoice->permalink );
			$order_object->update_meta_data( 'hd_wc_ie_plus_invoice_type', $this->document_type );
			$order_object->save();

			do_action( 'invoicexpress_woocommerce_after_document_issue', $order_object->get_id(), $this->document_type );

			do_action( 'invoicexpress_woocommerce_debug', 'Simplified invoice issued', $order_object, array(
				'hd_wc_ie_plus_'.$this->document_type.'_id' => $order_id_invoicexpress,
			) );

			//Get order again because it may have changed on the action above
			$order_object = wc_get_order( $order_object->get_id() );

			if ( get_option( 'hd_wc_ie_plus_leave_as_draft' ) ) {

				/* Leave as draft */
				$this->draft_document_note( $order_object, $this->plugin->type_names[$this->document_type] );

				/* Add notice */
				$notice = sprintf(
					'<strong>%s:</strong> %s',
					__( 'InvoiceXpress', 'woo-billing-with-invoicexpress' ),
					sprintf(
						/* translators: %s: document type */
						__( 'Successfully created %s as draft', 'woo-billing-with-invoicexpress' ),
						$this->plugin->type_names[$this->document_type]
					)
				);
				if ( $mode == 'manual' ) {
					Notices::add_notice( $notice );
				}
				do_action( 'invoicexpress_woocommerce_debug', $notice, $order_object );

				return;

			} else {

				$return = $this->finalize_document( $order_object, $this->document_type, $mode );
				if ( ! $return ) {
					return;
				}

			}

		} else {
			/* Add notice */
			$error_notice = sprintf(
				'<strong>%s:</strong> %s',
				__( 'InvoiceXpress error', 'woo-billing-with-invoicexpress' ),
				sprintf(
					/* translators: %s: document type */
					__( 'The %s wasn’t created because this order already has an invoice type document or one is scheduled to be issued.', 'woo-billing-with-invoicexpress' ),
					$this->plugin->type_names[$this->document_type]
				)
			);
			if ( $mode == 'manual' ) {
				Notices::add_notice(
					$error_notice,
					'error'
				);
			} else {
				if ( get_option( 'hd_wc_ie_plus_automatic_email_errors' ) ) {
					$this->plugin->sendErrorEmail( $order_object, $error_notice, $this->document_type );
				}
			}
			do_action( 'invoicexpress_woocommerce_error', $error_notice, $order_object );
			return;
		}
	}

}

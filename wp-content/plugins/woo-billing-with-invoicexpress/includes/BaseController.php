<?php

namespace Webdados\InvoiceXpressWooCommerce;

use Webdados\InvoiceXpressWooCommerce\JsonRequest as JsonRequest;

/* WooCommerce CRUD ready */
/* JSON API ready */
/* WooCommerce HPOS ready 2023-07-13 */

class BaseController {

	/**
	 * The plugin's instance.
	 *
	 * @since  2.0.4
	 * @access protected
	 * @var    Plugin
	 */
	protected $plugin;

	/**
	 * Strings to find/replace in subjects/headings.
	 *
	 * @var array
	 */
	protected $placeholders = array();

	/**
	 * Documents validity in days.
	 *
	 * @var int
	 */
	protected $validity_invoicing_docs = 30;
	protected $validity_guides_docs    = 30;
	protected $validity_quotes_docs    = 30;

	/**
	 * Documents with pending processing
	 *
	 * @var array
	 */
	protected $pending_processing_types = array(
		'invoice_receipt',
		'invoice',
		'simplified_invoice',
		'transport_guide',
		'proforma',
		'quote',
		'devolution_guide',
		'vat_moss_invoice',
		//'credit_note',
		//'receipt',
	);

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 2.0.4 Add plugin instance parameter.
	 * @since 1.0.0
	 * @param Plugin $plugin This plugin's instance.
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		$this->placeholders = array(
			'{site_title}'    => trim( $this->plugin->get_blogname() ),
			'{order_date}'    => '',
			'{order_number}'  => '',
			'{customer_name}' => '',
		);
	}

	/**
	 * Gets order items for the document
	 *
	 * @since 2.1.0
	 * @param WC_Order $order_object The order.
	 * @param string $document_type Document type - not used for the moment.
	 * @param array $args Additional arguments / options.
	 */
	public function getOrderItemsForDocument( $order_object, $document_type, $args = array() ) {
		//Arguments
		$no_values = false;
		if ( isset( $args['no_values'] ) && $args['no_values']) {
			$no_values = true;
		}
		$items  = array();
		//Products
		foreach ( $order_object->get_items() as $key => $item ) {
			if ( $item->get_variation_id() ) {
				$pid = $item->get_variation_id();
			} else {
				$pid = $item->get_product_id();
			}
			$quantity = $item->get_quantity();
			if ( $document_type != 'credit_note' ) $quantity = $item->get_quantity() - abs( $order_object->get_qty_refunded_for_item( $key ) );
			$quantity = abs( $quantity ); //Always positive
			if ( $quantity > 0 ) {
				if ( $no_values ) {
					$unit_price = 0;
				} else {
					$unit_price = (double) $item->get_total() / $quantity;
				}
				$vat = $this->get_vat_name( $item, $unit_price );
				/* Issue #108 */
				$name = '#' . $pid;
				if ( $product = wc_get_product( $pid ) ) {
					$product_code = get_option( 'hd_wc_ie_plus_product_code' );
					if ( $product->get_sku() && $product_code != 'id' ) {
						$name = $product->get_sku();
					}
				}
				/* End of Issue #108 */
				$item_data = array(
					'ixwc'        => array(
						'type'       => 'item',
						'key'        => $key,
						'product_id' => $pid,
						'unit_price' => $no_values ? 0 : (double) $item->get_total() / $quantity,
					), //Removed later by process_items
					'name'        => $name,
					'description' => $this->order_item_title( $item, $product, $order_object, $document_type ),
					'unit_price'  => $unit_price,
					'quantity'    => $quantity,
					'unit'        => apply_filters( 'invoicexpress_woocommerce_document_item_unit', 'unit', $item, $product, $order_object, $document_type, $args ), //We should deprecate this filter because we have the global one below
				);
				if ( ! empty( $vat ) ) {
					$item_data['tax'] = array(
						'name' => $vat,
					);
				}
				//Allow developers to manipulate the $item_data or exclude from the invoice if false is returned
				$item_data = apply_filters( 'invoicexpress_woocommerce_document_item', $item_data, $item, $product, $order_object, $document_type, $args );
				//Still an array? Add it
				if ( is_array( $item_data ) ) {
					$items[] = $item_data;
					//Allow developers do add other items based on this one
					$items = apply_filters( 'invoicexpress_woocommerce_items_after_document_item_add', $items, $item_data, $item, $product, $order_object, $document_type, $args );
				}
			}
		}
		if ( ! $no_values ) {
			//Fees
			foreach ( $order_object->get_fees() as $key => $item ) {
				if ( $no_values ) {
					$fee_price = 0;
				} else {
					$fee_price = abs( (double) $item['line_total'] ); //Refunds store fee values in negative
					//Fee refunds?
					if ( $document_type != 'credit_note' ) {
						foreach ( $order_object->get_refunds() as $refund ) {
							foreach ( $refund->get_fees() as $refund_item ) {
								if ( (string) $refund_item['item_meta']['_refunded_item_id'] == (string) $key ) {
									$fee_price -= abs( $refund_item['line_total'] );
								}
							}
						}
					}
				}
				//if ( $fee_price > 0 ) { //We will prevent it later
					$vat = $this->get_vat_name( $item, $fee_price );
					$ref = 'FEE';
					$item_data = array(
						'ixwc'        => array(
							'type'       => 'fee',
							'key'        => $key,
							'unit_price' => (double) $item['line_total'],
						), //Removed later by process_items
						'name'        => $ref,
						'description' => $item['name'],
						'unit_price'  => $fee_price,
						'quantity'    => 1,
						'unit'        => apply_filters( 'invoicexpress_woocommerce_document_fee_unit', 'service', $item, $order_object, $document_type, $args ), //We should deprecate this filter because we have the global one below
					);
					if ( ! empty( $vat ) ) {
						$item_data['tax'] = array(
							'name' => $vat,
						);
					}
					//Allow developers to manipulate the $item_data or exclude from the invoice if false is returned
					$item_data = apply_filters( 'invoicexpress_woocommerce_document_fee', $item_data, $item, $order_object, $document_type, $args );
					//Still an array? Add it
					if ( is_array( $item_data ) ) {
						$items[] = $item_data;
						//Allow developers do add other items based on this one
						$items = apply_filters( 'invoicexpress_woocommerce_items_after_document_fee_add', $items, $item_data, $item, $order_object, $document_type, $args );
					}
				//}
			}
			//Shipping
			$shipping_method = $order_object->get_shipping_method();
			if ( ! empty( $shipping_method ) ) {
				foreach ( $order_object->get_shipping_methods() as $key => $item ) {
					if ( $no_values ) {
						$cost = 0;
					} else {
						$cost = abs( (double) $item['cost'] ); //Refunds store fee values in negative
						//Shipping refunds?
						if ( $document_type != 'credit_note' ) {
							foreach ( $order_object->get_refunds() as $refund ) {
								foreach ( $refund->get_shipping_methods() as $refund_item ) {
									if ( (string) $refund_item['item_meta']['_refunded_item_id'] == (string) $key ) {
										$cost -= abs( $refund_item['cost'] );
									}
								}
							}
						}
					}
					//if ( $cost > 0 ) { //We will prevent it later
						$vat = $this->get_vat_name( $item, $cost );
						$ref = 'SHIP';
						$item_data = array(
							'ixwc'        => array(
								'type'       => 'shipping',
								'key'        => $key,
								'unit_price' => $no_values ? 0 : (double) $item['cost'],
							), //Removed later by process_items
							'name'        => $ref,
							'description' => $item['name'],
							'unit_price'  => $cost,
							'quantity'    => 1,
							'unit'        => apply_filters( 'invoicexpress_woocommerce_document_shipping_unit', 'other', $item, $order_object, $document_type, $args ), //We should deprecate this filter because we have the global one below
						);
						if ( ! empty( $vat ) ) {
								$item_data['tax'] = array(
									'name' => $vat,
								);
						}
						//Allow developers to manipulate the $item_data or exclude from the invoice if false is returned
						$item_data = apply_filters( 'invoicexpress_woocommerce_document_shipping', $item_data, $item, $order_object, $document_type, $args );
						//Still an array? Add it
						if ( is_array( $item_data ) ) {
							$items[] = $item_data;
							//Allow developers do add other items based on this one
							$items = apply_filters( 'invoicexpress_woocommerce_items_after_document_shipping_add', $items, $item_data, $item, $order_object, $document_type, $args );
						}
					//}
				}
			}
		}
		return $items;
	}

	/*
	 * Get tax name from order item
	 *
	 * @since  3.0.1
	 * @param  object $item The order item
	 * @return double $unit_price The item unitary value
	 */
	public function get_vat_name( $item, $unit_price ) {
		$vat = '';
		$taxes_per_line = $item->get_taxes();
		$tax_ids = array();
		if ( isset( $taxes_per_line['subtotal'] ) ) {
			foreach ( $taxes_per_line['subtotal'] as $key2 => $value ) {
				if ( $value != '' ) {
					$tax_ids[] = $key2;
				}
			}
		} elseif( isset( $taxes_per_line['total'] ) ) {
			foreach ( $taxes_per_line['total'] as $key2 => $value ) {
				if ( $value != '' ) {
					$tax_ids[] = $key2;
				}
			}
		}
		if ( isset( $tax_ids[0] ) ) {
			$vat = \WC_Tax::get_rate_label( $tax_ids[0] );
		}
		if ( $unit_price != 0 && $item->get_total_tax() == 0 ) {
			  $vat = get_option( 'hd_wc_ie_plus_exemption_name' );
		}
		return $vat;
	}

	/*
	 * Order item title
	 *
	 * @since  2.1.4.2
	 * @param  object $item The order item
	 * @param  object $product The product (or false)
	 * @param  object $order_object The order
	 * @param  string $document_type The document type
	 * @return string The item title
	 */
	public function order_item_title( $item, $product, $order_object, $document_type ) {
		$title = $item->get_name();
		return apply_filters( 'invoicexpress_woocommerce_document_item_title', $title, $item, $product, $order_object, $document_type );
	}

	/**
	 * Fix invoice data items: remove our type and apply exemption if needed
	 *
	 * @since 2.1.7
	 * @param array $invoice_data The invoice data
	 * @param object $order_object The order
	 * @param string $document_type The document type
	 */
	public function process_items( $invoice_data, $order_object, $document_type ) {
		//Each item
		foreach ( $invoice_data['items'] as $key => $item ) {
			//Negative values?
			if (
				$item['unit_price'] < 0
				||
				( $item['ixwc']['unit_price'] < 0 && $document_type != 'credit_note' ) //We'll allow on credit notes for now
			) {
				$invoice_data['_prevent']         = true;
				$invoice_data['_prevent_message'] = __( 'InvoiceXpress does not support, and it is not legal, to issue documents with negative items', 'woo-billing-with-invoicexpress' );
				//We can get out now
				return $invoice_data;
			}
			//Partial exemption - WHY? Who's using this?
			if ( isset( $item['ixwc']['type'] ) ) {
				//Set partial exemption if global exemption is not set - Really? http://contabilistas.info/index.php?topic=8818.0
				if ( empty( $invoice_data['tax_exemption'] ) && apply_filters( 'invoicexpress_woocommerce_partial_exemption', false ) ) {
					switch( $item['ixwc']['type'] ) {
						case 'item':
						case 'shipping':
						case 'fee':
							if ( isset( $item['tax']['name'] ) && trim( $item['tax']['name'] ) != '' && trim( $item['tax']['name'] ) == get_option( 'hd_wc_ie_plus_exemption_name' ) ) {
								$exemption = get_option( 'hd_wc_ie_plus_exemption_reason' );
								$invoice_data['tax_exemption'] = apply_filters( 'invoicexpress_woocommerce_partial_exemption_reason', $exemption, $item, $invoice_data, $order_object->get_id(), $document_type );
								do_action( 'invoicexpress_woocommerce_partial_exemption_applied', $item, $invoice_data, $order_object->get_id(), $document_type );
								break; //No need to keep going because we can only set one exemption reason per document (Maybe InvoiceXpress should look into that...)
							}
							break;
						default:
							break;
					}
				}
				//Other stuff??
				//...
			}
		}
		//Clear our extra information
		foreach ( $invoice_data['items'] as $key => $item ) {
			//Important: Clear our data or InvoiceXpress will throw an error
			if ( isset( $item['ixwc'] ) ) unset( $invoice_data['items'][$key]['ixwc'] );
		}
		//Remove eventual HTML on titles and replace some problematic caracters
		$search = array(
			'&euro;',
			'&nbsp;',
			'&amp;nbsp;',
		);
		$replace = array(
			'€',
			' ',
			' ',
		);
		foreach ( $invoice_data['items'] as $key => $item ) {
			$invoice_data['items'][$key]['description'] = trim( str_replace( $search, $replace, strip_tags( $item['description'] ) ) ); 
		}
		//Clear observations if empty - NO https://3.basecamp.com/3078239/buckets/20370269/messages/6022419606#__recording_6031501959
		//if ( isset( $invoice_data['observations'] ) ) {
		//	$invoice_data['observations'] = trim( $invoice_data['observations'] );
		//	if ( empty( $invoice_data['observations'] ) ) {
		//		unset( $invoice_data['observations'] );
		//	}
		//}
		return $invoice_data;
	}

	/**
	 * Final processing of invoice data before sending to the API, without any filter after it
	 *
	 * @since 5.2
	 * @param array $invoice_data The invoice data
	 * @param object $order_object The order
	 * @param string $document_type The document type
	 */
	public function process_before_issuing( $invoice_data, $order_object, $document_type ) {
		// Add our plugin id on InvoiceXpress
		$invoice_data['plugin_id'] = $this->plugin->invoicexpress_plugin_id;
		// Return
		return $invoice_data;
	}

	/*
	 * Finds the sequence id for the provided document type.
	 *
	 * @since  2.0.0 Return the default sequence.
	 * @since  1.0.0
	 * @param  int    $order_id The order ID.
	 * @param  string $document_type     The document type.
	 * @return string The document sequence ID.
	 */
	public function find_sequence_id( $order_id, $document_type ) {

		$order_object = wc_get_order( $order_id );

		// VAT MOSS DISABLED 2023
		//switch( $document_type ) {
		//	case 'vat_moss_invoice':
		//	case 'vat_moss_credit_note':
		//		$order_sequence_id = get_option( 'hd_wc_ie_plus_vat_moss_sequence' );
		//		break;
		//	default:
				$order_sequence_id = $order_object->get_meta( '_billing_sequence_id' );
				if ( empty( $order_sequence_id ) ) {
					$order_sequence_id = apply_filters( 'invoicexpress_woocommerce_default_sequence', '' );
				}
				//VAT MOSS Credit notes - VAT MOSS DISABLED 2023
				//if ( $document_type == 'credit_note' && $order_object->get_meta( 'hd_wc_ie_plus_invoice_type' ) == 'vat_moss_invoice' ) {
				//	$order_sequence_id = get_option( 'hd_wc_ie_plus_vat_moss_sequence' );
				//}
		//		break;
		//}

		//Get from sequences cache
		$cache = get_option( 'hd_wc_ie_plus_sequences_cache' );
		if ( is_array( $cache ) && count( $cache ) > 0 ) {
			if ( isset( $cache[$order_sequence_id]['current_' . $document_type . '_sequence_id'] ) ) {
				//Found in cache
				do_action( 'invoicexpress_woocommerce_debug', 'find_sequence_id '.$document_type.' '.$order_sequence_id.' '.$cache[$order_sequence_id]['current_' . $document_type . '_sequence_id'], $order_object );
				return $cache[$order_sequence_id]['current_' . $document_type . '_sequence_id'];
			}
		}
		
		return '';
	}

	/*
	 * Stores the document as a order note and a custom field
	 */
	public function storeAndNoteDocument( $order_object, $document_url, $document_type, $invoicexpress_id, $another_doc = '' ) {
		//Legacy XML support
		if ( is_array( $document_url ) && isset( $document_url['value'] ) ) {
			$value = $document_url['value'];

			foreach ( $value as $v ) {
				if ( $v['name'] == '{}pdfUrl' ) {
					$document_url = $v['value'];
					break;
				}
			}
		}
		//Legacy XML support - END

		$wp_upload_path = wp_upload_dir();
		$plugin_path    = $wp_upload_path['basedir'];

		if ( ! file_exists( $wp_upload_path['basedir'] . '/invoicexpress/documents/' ) ) {
			mkdir( $wp_upload_path['basedir'] . '/invoicexpress/documents/', 0755, true );
		}

		if ( ! file_exists( $wp_upload_path['basedir'] . '/invoicexpress/index.php' ) ) {
			touch( $wp_upload_path['basedir'] . '/invoicexpress/index.php' );
		}

		if ( ! file_exists( $wp_upload_path['basedir'] . '/invoicexpress/documents/index.php' ) ) {
			touch( $wp_upload_path['basedir'] . '/invoicexpress/documents/index.php' );
		}

		$document_type_name = isset( $this->plugin->type_names[$document_type] ) ? $this->plugin->type_names[$document_type] : $document_type;

		$file_name = $document_type_name.'-'.$order_object->get_meta( 'hd_wc_ie_plus_'.$document_type.'_sequence_number' );
		if ( $prefix = get_option( 'hd_wc_ie_plus_filename_prefix' ) ) {
			$file_name = $prefix.'-'.$file_name;
		}
		$file_name .= '-'.substr( md5( $file_name.time() ), 0, 5 );
		$file_name = sanitize_title( $file_name ).'.pdf';

		$file_name = apply_filters( 'invoicexpress_woocommerce_document_filename', $file_name, $order_object, $document_url, $document_type, $invoicexpress_id, $another_doc );

		$response = wp_remote_get( $document_url );
		if ( is_wp_error( $response ) ) {
			//We should deal with this...
		} else {
			file_put_contents( $plugin_path . '/invoicexpress/documents/' . $file_name, wp_remote_retrieve_body( $response ) );
		}

		$url_local = $wp_upload_path['baseurl'] . '/invoicexpress/documents/' . $file_name;

		// If it as a valid URL
		if ( ! empty( $document_url ) ) {
			$site_url = get_site_url() . '/invoicexpress/download_pdf' . "?order_id={$order_object->get_id()}&document_id=$invoicexpress_id&document_type=$document_type";
			$order_object->update_meta_data( 'hd_wc_ie_plus_' . $document_type . '_id' . $another_doc, $invoicexpress_id ); //Why are we doing this again?
			$order_object->update_meta_data( 'hd_wc_ie_plus_' . $document_type . '_pdf' . $another_doc, $url_local );
			$note = sprintf(
				'%1$s<br/>%2$s',
				sprintf(
					/* translators: %1$s: document name, %2$s document number, %3$s: PDF string, %4$s: PDF download link */
					__( 'Download %1$s %2$s (%3$s): %4$s.', 'woo-billing-with-invoicexpress' ),
					$document_type_name,
					$order_object->get_meta( 'hd_wc_ie_plus_'.$document_type.'_sequence_number' ),
					__( 'PDF', 'woo-billing-with-invoicexpress' ),
					sprintf(
						'<a target="_blank" href="%1$s">%2$s</a>',
						esc_url( $url_local ),
						__( 'click here', 'woo-billing-with-invoicexpress' )
					)
				),
				sprintf(
					/* translators: %1$s: document name, %2$s: download link */
					__( 'Problem accessing the %1$s PDF? Download again: %2$s.', 'woo-billing-with-invoicexpress' ),
					$this->plugin->type_names[$document_type],
					sprintf(
						'<a href="%1$s">%2$s</a>',
						esc_url( $site_url ),
						__( 'click here', 'woo-billing-with-invoicexpress' )
					)
				)
			);
			$order_object->save();
			$order_object->add_order_note( $note );
			//Send it
			if ( get_option( 'hd_wc_ie_plus_send_'.$document_type ) ) {
				switch( $document_type ) {
					case 'transport_guide':
						$email = get_option( 'hd_wc_ie_plus_transport_guide_email_address' );
						break;
					default:
						$email = $order_object->get_billing_email();
						break;
				}
				if ( ! empty( $email ) ) {
					$attachment = $order_object->get_meta( 'hd_wc_ie_plus_'.$document_type.'_pdf' );
					do_action( 'invoicexpress_woocommerce_debug', 'storeAndNoteDocument will now send the invoice email', $order_object );
					$this->send_invoice_email( $email, $invoicexpress_id, $order_object->get_id(), $order_object, $attachment, $document_type );
				} else {
					do_action( 'invoicexpress_woocommerce_error', 'storeAndNoteDocument '.$this->plugin->type_names[$document_type].' PDF: No email address', $order_object );
				}
			}
		} else {
			do_action( 'invoicexpress_woocommerce_error', 'storeAndNoteDocument '.$this->plugin->type_names[$document_type].' PDF: No document URL', $order_object );
		}
	}

	/*
	 * Creates a order note with the possibility of redownloading the PDF
	 *
	 * @since  2.4.0
	 * @param  WC_Order $order_object     The order object.
	 * @param  string   $document_type The type of document
	 * @param  int      $invoicexpress_id The InvoiceXpress document ID.
	 */
	public function noteDocumentFailedPDF( $order_object, $document_type, $invoicexpress_id ) {
		$site_url = get_site_url() . '/invoicexpress/download_pdf?order_id='.$order_object->get_id().'&document_id='.$invoicexpress_id.'&document_type='.$document_type;
		$note = sprintf(
			/* translators: %1$s: document name, %2$s: download link */
			__( 'Problem accessing the %1$s PDF? Download again: %2$s.', 'woo-billing-with-invoicexpress' ),
			$this->plugin->type_names[$document_type],
			sprintf(
				'<a href="%1$s">%2$s</a>',
				esc_url( $site_url ),
				__( 'click here', 'woo-billing-with-invoicexpress' )
			)
		);
		$order_object->add_order_note( $note );
	}

	/*
	 * Use Invoicexpress API to return a PDF of a document
	 *
	 * @since  2.4.0
	 * @param  WC_Order $order_object     The order object.
	 * @param  string   $document_type The type of document
	 * @param  int      $order_id_invoicexpress The InvoiceXpress document ID.
	 * @param  string   $mode Issuing mode: manual or automatic
	 */
	public function getAndSendPDF( $order_object, $document_type, $order_id_invoicexpress, $mode = 'manual', $receipt_count = 1 ) {
		do_action( 'invoicexpress_woocommerce_before_document_email', $order_object->get_id(), $document_type );
		if ( $this->can_send_non_woocommerce_email( get_option( 'hd_wc_ie_plus_email_method' ), $order_object ) ) {
			//Hybrid or InvoiceXpress - with pending processing only for the InvoiceXpress method (not Hybrid)
			do_action( 'invoicexpress_woocommerce_debug', $this->plugin->type_names[ $document_type ].' email method: '.get_option( 'hd_wc_ie_plus_email_method' ), $order_object );
			//Send it
			if ( get_option( 'hd_wc_ie_plus_send_'.$document_type ) ) {
				switch( $document_type ) {
					case 'transport_guide':
						$email = get_option( 'hd_wc_ie_plus_transport_guide_email_address' );
						break;
					default:
						$email = $order_object->get_billing_email();
						break;
				}
				if ( ! empty( $email ) ) {

					do_action( 'invoicexpress_woocommerce_debug', 'getAndSendPDF will now send the invoice email', $order_object );
					$this->send_invoice_email(
						$email,
						$order_id_invoicexpress,
						$order_object->get_id(),
						$order_object,
						false,
						$document_type,
						$mode
					);

				} else {
					do_action( 'invoicexpress_woocommerce_error', 'getAndSendPDF '.$this->plugin->type_names[$document_type].' PDF: No email address', $order_object );
				}
			}
			//Note it
			$note = sprintf(
				/* translators: %1$s: document name, %2$s document number, %3$s: PDF string, %4$s: PDF download link */
				__( 'Download %1$s %2$s (%3$s): %4$s.', 'woo-billing-with-invoicexpress' ),
				$this->plugin->type_names[$document_type],
				$order_object->get_meta( 'hd_wc_ie_plus_'.$document_type.'_sequence_number' ),
				__( 'PDF', 'woo-billing-with-invoicexpress' ),
				sprintf(
					'<a target="_blank" href="%1$s">%2$s</a>',
					esc_url( $order_object->get_meta( 'hd_wc_ie_plus_'.$document_type.'_permalink' ) ),
					__( 'click here', 'woo-billing-with-invoicexpress' )
				)
			);
			$order_object->add_order_note( $note );
			return true;
		} else {
			//WooCommerce method - with pending processing
			do_action( 'invoicexpress_woocommerce_debug', 'getAndSendPDF '.$this->plugin->type_names[ $document_type ].' email method: WooCommerce', $order_object );
			
			//Force pdf/email fail to test pending documents screen
			//$order_id_invoicexpress = intval( $order_id_invoicexpress ) + 999;
			
			do_action( 'invoicexpress_woocommerce_debug', 'getAndSendPDF will now get the document PDF', $order_object );
			$return = $this->getDocumentPDF( $order_id_invoicexpress );
			$site_url = get_site_url() . '/invoicexpress/download_pdf?order_id='.$order_object->get_id().'&document_id='.$order_id_invoicexpress.'&document_type='.$document_type;
			if ( ! $return['success'] ) {
				$error_notice = false;
				if ( intval( $return['error_code'] ) == 502 ) {
					/* Add notice */
					$error_notice = sprintf(
						'<strong>%s:</strong> %s<br/>%s',
						__( 'InvoiceXpress error while getting PDF', 'woo-billing-with-invoicexpress' ),
						sprintf(
							/* translators: %s: document name */
							__( 'The %s PDF wasn’t created due to InvoiceXpress service being temporarily down.', 'woo-billing-with-invoicexpress' ),
							$this->plugin->type_names[$document_type]
						),
						sprintf(
							/* translators: %1$s: document name, %2$s: download link */
							__( 'Problem accessing the %1$s PDF? Download again: %2$s.', 'woo-billing-with-invoicexpress' ),
							$this->plugin->type_names[$document_type],
							sprintf(
								'<a href="%1$s">%2$s</a>',
								esc_url( $site_url ),
								__( 'click here', 'woo-billing-with-invoicexpress' )
							)
						)
					);
					if ( $mode == 'manual' ) {
						Notices::add_notice(
							$error_notice,
							'error'
						);
					}
				} elseif ( intval( $return['error_code'] ) == 202 ) {
					//Stil processing
					$codeStr    = __( 'Code', 'woo-billing-with-invoicexpress' );
					$messageStr = __( 'Message', 'woo-billing-with-invoicexpress' );
					/* Add notice */
					$error_notice = sprintf(
						'<strong>%s:</strong> %s<br/>%s',
						__( 'InvoiceXpress error while getting PDF', 'woo-billing-with-invoicexpress' ),
						sprintf(
							/* translators: %s: document name */
							__( "The %s PDF was not created due to the fact that InvoiceXpress has not yet been able to process the request.", 'woo-billing-with-invoicexpress' ),
							$this->plugin->type_names[$document_type]
						),
						sprintf(
							/* translators: %1$s: document name, %2$s: download link */
							__( 'Problem accessing the %1$s PDF? Download again: %2$s.', 'woo-billing-with-invoicexpress' ),
							$this->plugin->type_names[$document_type],
							sprintf(
								'<a href="%1$s">%2$s</a>',
								esc_url( $site_url ),
								__( 'click here', 'woo-billing-with-invoicexpress' )
							)
						)
					);
					if ( $mode == 'manual' ) {
						Notices::add_notice(
							$error_notice,
							'error'
						);
					}
				} else {
					//Other error
					$codeStr    = __( 'Code', 'woo-billing-with-invoicexpress' );
					$messageStr = __( 'Message', 'woo-billing-with-invoicexpress' );
					/* Add notice */
					$error_notice = sprintf(
						'<strong>%s:</strong> %s<br/>%s',
						__( 'InvoiceXpress error while getting PDF', 'woo-billing-with-invoicexpress' ),
						$codeStr . ': ' . $return['error_code'] . " - " . $messageStr . ': ' . $return['error_message'],
						sprintf(
							/* translators: %1$s: document name, %2$s: download link */
							__( 'Problem accessing the %1$s PDF? Download again: %2$s.', 'woo-billing-with-invoicexpress' ),
							$this->plugin->type_names[$document_type],
							sprintf(
								'<a href="%1$s">%2$s</a>',
								esc_url( $site_url ),
								__( 'click here', 'woo-billing-with-invoicexpress' )
							)
						)
					);
					if ( $mode == 'manual' ) {
						Notices::add_notice(
							$error_notice,
							'error'
						);
					}
				}
				if ( $error_notice ) {
					/* Set as not sent to client for later processing */
					/* This is now done only for some documents with Pending errors processing implemented, but it should be done for all */
					if ( in_array( $document_type, $this->pending_processing_types ) ) {
						$not_finalized = get_option( 'hd_wc_ie_plus_pending_email', array() );
						if ( ! isset( $not_finalized[$order_object->get_id()] ) ) {
							$not_finalized[$order_object->get_id()] = array();
						}
						$not_finalized[$order_object->get_id()][$document_type] = 1;
						update_option( 'hd_wc_ie_plus_pending_email', $not_finalized );
					}

					do_action( 'invoicexpress_woocommerce_error', 'Get '.$this->plugin->type_names[$document_type].' PDF: '.$error_notice, $order_object );
					$this->noteDocumentFailedPDF( $order_object, $document_type, $order_id_invoicexpress );
					if ( get_option( 'hd_wc_ie_plus_automatic_email_errors' ) && ( $mode == 'automatic' || $mode == 'scheduled' ) ) {
						$this->plugin->sendErrorEmail( $order_object, $error_notice, $document_type );
					}
				}
				return false;
			} else {

				/* Remove pending sent to client */
				$not_finalized = get_option( 'hd_wc_ie_plus_pending_email', array() );
				if ( isset( $not_finalized[$order_object->get_id()][$document_type] ) ) {
					unset( $not_finalized[$order_object->get_id()][$document_type] );
					if ( count( $not_finalized[$order_object->get_id()] ) == 0 ) {
						unset( $not_finalized[$order_object->get_id()] );
					}
				}
				update_option( 'hd_wc_ie_plus_pending_email', $not_finalized );

				$document_url = $return['object']->output->pdfUrl;
				if ( $document_type == 'receipt' && $receipt_count > 1 ) {
					$this->storeAndNoteDocument( $order_object, $document_url, $document_type, $order_id_invoicexpress, '_2' );
				} else {
					$this->storeAndNoteDocument( $order_object, $document_url, $document_type, $order_id_invoicexpress );
				}

				return true;
			}
		}
	}
	public function getDocumentPDF( $invoicexpress_id, $second_copy = 'false' ) {
		$params = array(
			'request' => 'api/pdf/'.$invoicexpress_id.'.json',
			'args'    => array(
				'second_copy' => $second_copy,
			),
		);
		$json_request = new JsonRequest( $params );
		return $json_request->getRequestWhileStatusCode( 200 );
	}

	/**
	 * Method to register an array of settings to a page.
	 *
	 * @param array $options
	 */
	public function registerSettingsOptions( $options, $section ) {
		foreach ( $options as $option_name => $option_value ) {

			add_settings_field(
				$option_name,
				$option_value,
				array( $this, $option_name ),
				'invoicexpress_woocommerce',
				$section
			);

			register_setting( $section, $option_name );
		}
	}

	public function registerSettingsOptionsValidation( $options, $section, $type ) {
		foreach ( $options as $option_name => $option_value ) {

			add_settings_field(
				$option_name,
				$option_value,
				array(
					$this,
					$option_name,
				),
				'invoicexpress_woocommerce',
				$section
			);

			register_setting( $section, $option_name, array( $this, $type ) );
		}
	}

	/**
	 * Format email placeholders.
	 *
	 * @since  2.0.0
	 * @param  mixed $string Text to replace placeholders in.
	 * @param  array $placeholders The email placeholders
	 * @return string
	 */
	public function format_string( $string, $placeholders = [] ) {

		if ( empty( $placeholders ) ) {
			$placeholders = $this->get_email_placeholders();
		}

		$find    = array_keys( $placeholders );
		$replace = array_map( 'trim', array_values( $placeholders ) );
		return str_replace( $find, $replace, $string );
	}

	/**
	 * Get email placeholders.
	 *
	 * @since  2.0.0
	 * @param  string $document_type The type of document:
	 *                      - invoice
	*                       - invoice_receipt
	 *                      - credit_note
	 *                      - quote
	 *                      - proforma
	 *                      The default value is invoice.
	 * @return array
	 */
	public function get_email_placeholders( $document_type = 'invoice' ) {
		return apply_filters( 'invoicexpress_woocommerce_email_placeholders', $this->placeholders, $document_type );
	}

	private function can_send_non_woocommerce_email( $email_method, $order_object ) {
		return get_option( 'hd_wc_ie_plus_email_method' ) != '' && get_option( 'hd_wc_ie_plus_email_method' ) != 'woocommerce' && apply_filters( 'invoicexpress_woocommerce_allow_'.$email_method.'_email', false );
	}

	/**
	 * Send the invoice by email - The default way
	 *
	 * @since  2.0.0 New email subject, body and heading fields.
	 *               Email placeholders.
	 *               Code review
	 * @since  1.0.0
	 * @param  string   $email            The email address.
	 * @param  int      $invoicexpress_id The InvoiceXpress document ID.
	 * @param  int      $order_id         The order ID.
	 * @param  WC_Order $order_object     The order object.
	 * @param  string   $attachment_url   The attachment url.
	 * @param  string   $document_type    The type of document:
	 *                                    - invoice
	 *                                    - invoice_receipt
	 *                                    - credit_note
	 *                                    - quote
	 *                                    - proforma
	 *                                    The default value is invoice.
	 * @return void
	 */
	public function send_invoice_email( $email, $invoicexpress_id, $order_id, $order_object, $attachment_url, $document_type = 'invoice', $mode = 'manual' ) {

		$placeholders = $this->get_email_placeholders( $document_type );

		$placeholders['{order_date}']    = apply_filters( 'invoicexpress_woocommerce_email_order_date', trim( wc_format_datetime( $order_object->get_date_created() ) ), $order_object );
		$placeholders['{order_number}']  = $this->get_order_number( $order_object );
		$placeholders['{customer_name}'] = trim( sprintf(
			'%s %s',
			$order_object->get_billing_first_name(),
			$order_object->get_billing_last_name()
		) );

		$subject = $this->plugin->get_translated_option( "hd_wc_ie_plus_{$document_type}_email_subject", null, $order_object );
		if ( $subject === false ) { // Backwards compatibility
			$subject = get_option( 'hd_wc_ie_plus_send_invoice_subject' );
		}

		$subject = apply_filters( "invoicexpress_woocommerce_{$document_type}_email_subject", $this->format_string( $subject, $placeholders ), $order_object );

		$heading = $this->plugin->get_translated_option( "hd_wc_ie_plus_{$document_type}_email_heading", null, $order_object );
		if ( $heading === false ) { // Backwards compatibility
			$heading = get_option( 'hd_wc_ie_plus_send_invoice_heading' );
		}

		$heading = apply_filters( "invoicexpress_woocommerce_{$document_type}_email_heading", $this->format_string( $heading, $placeholders ), $order_object );

		$body = $this->plugin->get_translated_option( "hd_wc_ie_plus_{$document_type}_email_body", null, $order_object );
		if ( $body === false ) { // Backwards compatibility
			$body = get_option( 'hd_wc_ie_plus_send_invoice_body' );
		}

		$body = apply_filters( "invoicexpress_woocommerce_{$document_type}_email_body", $this->format_string( $body, $placeholders ), $order_object );

		if ( $this->can_send_non_woocommerce_email( get_option( 'hd_wc_ie_plus_email_method' ), $order_object ) ) {
			do_action( 'invoicexpress_woocommerce_debug', $this->plugin->type_names[ $document_type ].' email method: '.get_option( 'hd_wc_ie_plus_email_method' ), $order_object );
			do_action( 'invoicexpress_woocommerce_'.get_option( 'hd_wc_ie_plus_email_method' ).'_email', $document_type, $order_object, $invoicexpress_id, $email, $subject, $heading, $body, $mode );
			if ( get_option( 'hd_wc_ie_plus_email_method' ) == 'ix' ) return;
		} else {
			do_action( 'invoicexpress_woocommerce_debug', 'send_invoice_email '.$this->plugin->type_names[ $document_type ].' email method: WooCommerce', $order_object );
		}

		if ( $attachment_url ) {
			$url_explode = explode( '/', $attachment_url );
			$wp_upload_path = wp_upload_dir();
			$plugin_path    = $wp_upload_path['basedir'];
			$attachment     = $plugin_path . '/invoicexpress/documents/' . end( $url_explode );
		} else {
			$attachment = false;
		}
		do_action( 'invoicexpress_woocommerce_debug', 'Attachment: '.( $attachment ? $attachment : 'false' ), $order_object );
		
		$headers[] = sprintf(
			'From: %1$s <%2$s>',
			$this->plugin->get_translated_option( 'woocommerce_email_from_name' ),
			$this->plugin->get_translated_option( 'woocommerce_email_from_address' )
		);

		add_filter( 'wp_mail_content_type', array( $this->plugin, 'set_email_to_html' ) );

		$message = nl2br( sprintf( $body ) );

		ob_start();

		wc_get_template( 'emails/email-header.php', array( 'email_heading' => $heading ) );

		do_action( 'invoicexpress_woocommerce_before_email_body', $order_object, $document_type, $invoicexpress_id );

		echo $message; // WPCS: XSS Ok.

		do_action( 'invoicexpress_woocommerce_after_email_body', $order_object, $document_type, $invoicexpress_id );

		wc_get_template( 'emails/email-footer.php' );

		$message = ob_get_clean();
		$message = str_replace( '{site_title}', trim( $this->plugin->get_blogname() ), $message );

		$headers = apply_filters( 'invoicexpress_woocommerce_email_headers', $headers, $order_object, $document_type );

		$status = wc_mail( $email, $subject, $message, $headers, $attachment );

		remove_filter( 'wp_mail_content_type', array( $this->plugin, 'set_email_to_html' ) );

		do_action( 'invoicexpress_woocommerce_debug', $this->plugin->type_names[ $document_type ].' email sent: '.( $status ? 'true' : 'false' ), $order_object );
		if ( ! $status ) {
			$note = sprintf(
				'<strong>%1$s</strong> %2$s',
				__( 'InvoiceXpress error', 'woo-billing-with-invoicexpress' ),
				sprintf(
					/* translators: %s: document type */
					__( 'An error occured while sending the %s email', 'woo-billing-with-invoicexpress' ),
					$this->plugin->type_names[ $document_type ]
				)
			);
			$order_object->add_order_note( $note );
			do_action( 'invoicexpress_woocommerce_error', $note, $order_object );
		}
	}

	/**
	 * Get document client name.
	 *
	 * @since  2.0.0
	 * @param  WC_Order $order_object The order object.
	 * @return string The document client name.
	 */
	public function get_document_client_name( $order_object ) {
		$entity = get_option( 'hd_wc_ie_plus_document_entity', 'company' );

		// Set client name.
		if ( $entity === 'company' && ! empty( trim( $order_object->get_billing_company() ) ) ) {
			$client_name = $order_object->get_billing_company();
		} else {
			$client_name = sprintf(
				'%s %s',
				$order_object->get_billing_first_name(),
				$order_object->get_billing_last_name()
			);
		}

		return apply_filters( 'invoicexpress_woocommerce_document_client_name', $client_name, $order_object, $entity );
	}

	/**
	 * Gets the document due_date.
	 *
	 * @since  2.0.0
	 * @param  $document_type The document type.
	 * @return string The document client name.
	 */
	public function get_due_date( $document_type, $order_object ) {
		switch ( $document_type ) {
			// Invoicing documents (except Invoice-receipt)
			case 'invoice':
			case 'simplified_invoice':
			//case 'vat_moss_invoice': // VAT MOSS DISABLED 2023
			case 'credit_note':
				$validity = apply_filters( "invoicexpress_woocommerce_{$document_type}_validity", $this->validity_invoicing_docs );
				break;
			// Quotes and proformas
			case 'quote':
			case 'proforma':
				$validity = apply_filters( "invoicexpress_woocommerce_{$document_type}_validity", $this->validity_quotes_docs );
				break;
			// Guides
			case 'devolution_guide':
			case 'transport_guide':
				$validity = apply_filters( "invoicexpress_woocommerce_{$document_type}_validity", $this->validity_guides_docs );
				break;
			// Default - No validity
			case 'invoice_receipt':
			default:
				$validity = 0;
				break;
		}
		if ( $validity > 0 ) {
			$d = date_create( date_i18n( \DateTime::ISO8601 ) );
			date_add( $d, date_interval_create_from_date_string( $validity . ' days' ) );
			return date_format( $d, 'd/m/Y' );
		}
		return date_i18n( 'd/m/Y' );
	}

	/**
	 * Add draft document note to order.
	 *
	 * @param  WC_Order $order_object The order object.
	 * @param  string   $document_type
	 * @return void
	 */
	public function draft_document_note( $order_object, $document_type ) {

		$message         = esc_html__( 'Message', 'woo-billing-with-invoicexpress' );
		$message_content = sprintf(
			/* translators: %s: document type */
			__( 'The document (%s) was created as draft on InvoiceXpress and you should finalize it there.', 'woo-billing-with-invoicexpress' ),
			$document_type
		);

		$note = "<strong>InvoiceXpress:</strong>\n" . $message . ': ' . $message_content;
		$order_object->add_order_note( $note );
	}

	/**
	 * Change document state
	 *
	 * @param  int    $document_id_invoicexpress
	 * @param  string $state
	 * @param  string $document_type
	 * @return array
	 */
	public function changeOrderState( $document_id_invoicexpress, $state, $document_type, $message = '' ) {	
		$endpoint     = $this->plugin->document_type_to_endpoint( $document_type, true ); //Should we really convert the receipt here? We don't even call this function for receipts
		$root_element = $endpoint;
		// VAT MOSS DISABLED 2023
		//if ( $document_type == 'vat_moss_receipt' ) {
		//	$endpoint     = 'receipt';
		//	$root_element = 'vat_moss_receipt';
		//}
		$params = array(
			'request' => $endpoint.'s/' . $document_id_invoicexpress . '/change-state.json',
			'args'    => array(
				$root_element => array(
					'state' => $state
				),
			),
		);
		if ( ! empty( $message ) ) {
			$params['args'][$root_element]['message'] = $message;
		}
		$json_request = new JsonRequest( $params );
		//2022-05-24 InvoiceXpress changes
		//if ( in_array( $state, array( 'canceled' ) ) ) {
			return $json_request->putRequest();
		//} else {
		//	return $json_request->postRequest();
		//}
	}

	/**
	 * Finalize and send document via PDF
	 *
	 * @param  object $order_object
	 * @param  string $document_type
	 * @param  string $mode
	 * @return array
	 */
	public function finalize_document( $order_object, $document_type, $mode ) {

		do_action( 'invoicexpress_woocommerce_debug', 'Entering finalize_document', $order_object );

		if ( $order_id_invoicexpress = $order_object->get_meta( 'hd_wc_ie_plus_'.$document_type.'_id' ) ) { //Check if $document_type is valid for all...
			
			do_action( 'invoicexpress_woocommerce_debug', 'Document found on the database: '.$document_type, $order_object, array(
				'order_id_invoicexpress' => $order_id_invoicexpress,
			) );

			//Force finalize fail to test pending documents screen
			//$order_id_invoicexpress = intval( $order_id_invoicexpress ) + 999;
		
			$return = $this->changeOrderState( $order_id_invoicexpress, 'finalized', $document_type );
			
			if ( ! $return['success'] ) {

				$codeStr    = __( 'Code', 'woo-billing-with-invoicexpress' );
				$messageStr = __( 'Message', 'woo-billing-with-invoicexpress' );
				
				/* Set as not finalized for later processing */
				/* This is now done only for some documents with Pending errors processing implemented, but it should be done for all */
				if ( in_array( $document_type, $this->pending_processing_types ) ) {
					$not_finalized = get_option( 'hd_wc_ie_plus_pending_finalize', array() );
					if ( ! isset( $not_finalized[$order_object->get_id()] ) ) {
						$not_finalized[$order_object->get_id()] = array();
					}
					$not_finalized[$order_object->get_id()][$document_type] = 1;
					update_option( 'hd_wc_ie_plus_pending_finalize', $not_finalized );
				}
				
				/* Add notice */
				$error_notice = sprintf(
					'<strong>%s - %s:</strong> %s',
					__( 'InvoiceXpress error', 'woo-billing-with-invoicexpress' ),
					sprintf(
						/* translators: %s: Document name */
						__( '%s issued but not finalized', 'woo-billing-with-invoicexpress' ),
						$this->plugin->type_names[$document_type]
					),
					$codeStr . ': ' . $return['error_code'] . " - " . $messageStr . ': ' . $return['error_message']
				);
				if ( $mode == 'manual' ) {
					Notices::add_notice(
						$error_notice,
						'error'
					);
				}
				if ( get_option( 'hd_wc_ie_plus_automatic_email_errors' ) && ( $mode == 'automatic' || $mode == 'scheduled' ) && $error_notice ) {
					$this->plugin->sendErrorEmail( $order_object, $error_notice, $document_type );
				}
				do_action( 'invoicexpress_woocommerce_error', 'Change '.$this->plugin->type_names[$document_type].' state to finalized: '.$error_notice, $order_object );
				
				return false;

			}

			//Success
			$notice = sprintf(
				'<strong>%s:</strong> %s',
				__( 'InvoiceXpress', 'woo-billing-with-invoicexpress' ),
				sprintf(
					/* translators: %s: document type */
					__( 'Successfully finalized %s', 'woo-billing-with-invoicexpress' ),
					$this->plugin->type_names[$document_type]
				)
			);
			do_action( 'invoicexpress_woocommerce_debug', $notice, $order_object );

			/* Remove pending finalize */
			$not_finalized = get_option( 'hd_wc_ie_plus_pending_finalize', array() );
			if ( isset( $not_finalized[$order_object->get_id()][$document_type] ) ) {
				unset( $not_finalized[$order_object->get_id()][$document_type] );
				if ( count( $not_finalized[$order_object->get_id()] ) == 0 ) {
					unset( $not_finalized[$order_object->get_id()] );
				}
			}
			update_option( 'hd_wc_ie_plus_pending_finalize', $not_finalized );
			
			/* Endpoint for inverted document */
			$endpoint = $this->plugin->document_type_to_endpoint( $document_type );

			//$response = json_decode( $return['object'] );
			$response = $return['object']; //Already an object?!?

			/* Save */
			$sequence_number = $response->$endpoint->inverted_sequence_number; //Check if $document_type is valid for all...
			$order_object->update_meta_data( 'hd_wc_ie_plus_'.$document_type.'_sequence_number', $sequence_number ); //Check if $document_type is valid for all...
			$saft_hash = $response->$endpoint->saft_hash; //Check if $document_type is valid for all...
			$order_object->update_meta_data( 'hd_wc_ie_plus_'.$document_type.'_saft_hash', $saft_hash );
			$order_object->save();
	
			/* Add notice */
			$notice = sprintf(
				'<strong>%s:</strong> %s',
				__( 'InvoiceXpress', 'woo-billing-with-invoicexpress' ),
				trim(
					sprintf(
						/* translators: %1$s: document name, %2$s: document number */
						__( 'Successfully created %1$s %2$s', 'woo-billing-with-invoicexpress' ),
						$this->plugin->type_names[$document_type],
						! empty( $sequence_number ) ? $sequence_number : '' 
					)
				)
			);
			if ( $mode == 'manual' ) {
				Notices::add_notice( $notice );
			}
			do_action( 'invoicexpress_woocommerce_debug', $notice, $order_object );

			/* Delivery note AT Code - This still can't be restarted from pending */
			if ( $document_type == 'transport_guide' ) {
				switch ( get_option( 'hd_wc_ie_plus_guide_get_at_code' ) ) {
					case 'yes':
						/*  Now */
						do_action( 'hd_wc_ie_refetch_at_code', $order_object, $order_id_invoicexpress, $mode );
						break;
					case 'delayed':
						/*  Delayed 1 minute - WordPress cron */
						$minutes = 1;
						wp_schedule_single_event( time() + ( $minutes * 60 ), 'invoicexpress_woocommerce_fetch_at_code', array( $order_object->get_id() ) );
						break;
					default:
						/* Add note to get it manually */
						$url = get_site_url() . '/invoicexpress/get_at_code?order_id='.$order_object->get_id().'&document_id='.$order_id_invoicexpress.'&document_type=transport_guide';
						$note = sprintf(
							/* translators: %1$s: document name (delivery note), %2$s: HTML link code */
							__( 'To fetch the %1$s AT Code %2$s.', 'woo-billing-with-invoicexpress' ),
							__( 'Delivery note', 'woo-billing-with-invoicexpress' ),
							sprintf(
								'<a href="%1$s">%2$s</a>',
								esc_url( $url ),
								__( 'click here', 'woo-billing-with-invoicexpress' )
							)
						);
						$order_object->add_order_note( $note );
						break;
				}
			}
	
			/* Get and send the PDF - For delivery notes, we could also be delaying this if the AT Code fetch is delayed */
			if ( $document_type == 'transport_guide' && get_option( 'hd_wc_ie_plus_guide_get_at_code' ) == 'delayed' && get_option( 'hd_wc_ie_plus_guide_get_at_code_delay_email' ) ) {
				//Delivery notes with delayed AT Code and email sending - Do it later
				$url = get_site_url() . '/invoicexpress/download_pdf?order_id='.$order_object->get_id().'&document_id='.$order_id_invoicexpress.'&document_type=transport_guide';
				$note = sprintf(
					'%1$s<br/>%2$s',
					sprintf(
						__( 'The %s was issued and will be sent by email (if that option is set) after the AT Code is fetched.' ),
						__( 'Delivery note', 'woo-billing-with-invoicexpress' )
					),
					sprintf(
						/* translators: %1$s: document name, %2$s: download link */
						__( 'Need to get it now? Then: %s.', 'woo-billing-with-invoicexpress' ),
						sprintf(
							'<a href="%1$s">%2$s</a>',
							esc_url( $url ),
							__( 'click here', 'woo-billing-with-invoicexpress' )
						)
					)
				);
				$order_object->add_order_note( $note );
			} else {
				if ( ! $this->getAndSendPDF( $order_object, $document_type, $order_id_invoicexpress, $mode ) ) {
					//We really should set meta data saying that the document was not sent
					return false; //Really false?
				}
			}
	
			do_action( 'invoicexpress_woocommerce_after_document_finish', $order_object->get_id(), $document_type );

		} else {
			//No document found (??)
			do_action( 'invoicexpress_woocommerce_debug', 'Document NOT found on the database: '.$document_type, $order_object, array(
				'document_type' => $document_type,
			) );
			return false;
		}

	}

	/**
	 * Get order number or id
	 *
	 * @since 2.3.0
	 *
	 * @param  object  $order_object
	 * @return string
	 */
	public function get_order_number( $order_object ) {
		return (string) trim( $order_object->get_order_number() ) != '' ? trim( $order_object->get_order_number() ) : trim( $order_object->get_id() );
	}

	/**
	 * Get order PO reference
	 *
	 * @since 3.7.0
	 *
	 * @param  object  $order_object
	 * @return string
	 */
	public function get_order_po_reference( $order_object ) {
		return (string) apply_filters( 'invoicexpress_woocommerce_get_order_po_reference', $this->get_order_number( $order_object ), $order_object );
	}

	/**
	 * Prevent document issuing
	 *
	 * @since 2.1.4
	 * @param  WC_Order $order_object The order.
	 * @param  string $document_type Document type
	 * @param  array $data Document data to send to InvoiceXpress
	 * @param  string $mode Manual or Automatic
	 * @return array
	 */
	public function preventDocumentIssuing( $order_object, $document_type, $data, $mode = 'manual' ) {
		$prevent = false;
		$message = '';
		//Maybe some external plugin decided to prevent and did it by adding that to the document data with a filter
		if (
			isset( $data['_prevent'] )
			&&
			isset( $data['_prevent_message'] )
			&&
			$data['_prevent']
			&&
			$data['_prevent_message']
		) {
			$prevent = true;
			$message = $data['_prevent_message'];
		}
		return apply_filters( 'invoicexpress_woocommerce_prevent_document_issuing', array(
			'prevent'       => $prevent,
			'message'       => $message,
			'supress_error' => false,
		), $order_object, $document_type, $data, $mode );
	}

	/**
	 * Prevent document issuing error and logger
	 *
	 * @since 2.4.4
	 * @param array    $prevent Prevent data
	 * @param string   $document_type Document type
	 * @param WC_Order $order_object
	 * @param string   $mode Document issuing mode
	 */
	public function preventDocumentIssuingLogger( $prevent, $document_type, $order_object, $mode = 'manual' ) {
		//Some implementations may choose not to error log the document issuing prevention because it's the expected behavior
		if ( ! isset( $prevent['supress_error'] ) || ! $prevent['supress_error'] ) {
			$error_notice = sprintf(
				'<strong>%s:</strong> %s',
				sprintf(
					/* translators: %s: document type */
					__( '%s not issued', 'woo-billing-with-invoicexpress' ),
					$this->plugin->type_names[$document_type]
				),
				isset( $prevent['message'] ) && trim( $prevent['message'] ) != '' ? trim( $prevent['message'] ) : __( 'Reason unknown', 'woo-billing-with-invoicexpress' )
			);
			if ( $mode == 'manual' ) {
				Notices::add_notice(
					$error_notice,
					'error'
				);
			} else {
				if ( get_option( 'hd_wc_ie_plus_automatic_email_errors' ) && ( $mode == 'automatic' || $mode == 'scheduled' ) && $error_notice ) {
					$this->plugin->sendErrorEmail( $order_object, $error_notice, $document_type );
				}
			}
			do_action( 'invoicexpress_woocommerce_error', $error_notice, $order_object );
		}
		//But we should add it to the order notes anyway
		if ( isset( $prevent['message'] ) && trim( $prevent['message'] ) != '' ) {
			$order_object->add_order_note( $prevent['message'] );
		}
	}

	/**
	 * Finalize document that failed to do so
	 *
	 * @since  3.0.0
	 * @param  object $order_object
	 */
	public function doActionFinalize( $order_object, $mode = 'manual' ) {
		do_action( 'invoicexpress_woocommerce_debug', 'Finalize pending '.$this->plugin->type_names[$this->document_type], $order_object );
		$this->finalize_document( $order_object, $this->document_type, $mode );
	}

	/**
	 * Send document PDF that failed to do so
	 *
	 * @since  3.0.0
	 * @param  object $order_object
	 */
	public function doActionEmail( $order_object, $mode = 'manual' ) {
		do_action( 'invoicexpress_woocommerce_debug', 'Sending email PDF of pending '.$this->plugin->type_names[$this->document_type], $order_object );
		if ( $order_id_invoicexpress = $order_object->get_meta( 'hd_wc_ie_plus_'.$this->document_type.'_id' ) ) {
			$this->getAndSendPDF( $order_object, $this->document_type, $order_id_invoicexpress, $mode );
		}
	}

	/**
	 * Check if order is in Euros and if not, avoid issuing
	 *
	 * @since  3.5.0
	 * @param  object $order_object
	 */
	public function check_order_is_euro( $order_object, $document_type, $mode ) {
		if ( $order_object->get_currency() != 'EUR' ) {
			if ( apply_filters( 'invoicexpress_woocommerce_allow_non_euro', false ) ) {
				do_action( 'invoicexpress_woocommerce_debug', 'Order is NOT in Euros but issuing as been allowed by a 3rd party', $order_object );
			} else {
				/* Add notice */
				$error_notice = sprintf(
					'<strong>%s:</strong> %s',
					__( 'InvoiceXpress error', 'woo-billing-with-invoicexpress' ),
					sprintf(
						/* translators: %1$s: document type, %2$s: link opening tag, %3$s: link closing tag */
						__( 'The %1$s wasn’t created because this order is not in Euros. If you’re using the Pro version, you can use our %2$sMulti-currency extension%3$s to invoice non-euro orders.', 'woo-billing-with-invoicexpress' ),
						$this->plugin->type_names[$document_type],
						'<a href="https://invoicewoo.com/extensions/" target="_blank">',
						'</a>'
					)
				);
				if ( $mode == 'manual' ) {
					Notices::add_notice(
						$error_notice,
						'error'
					);
				} else {
					if ( get_option( 'hd_wc_ie_plus_automatic_email_errors' ) ) {
						$this->plugin->sendErrorEmail( $order_object, $error_notice, $document_type );
					}
				}
				do_action( 'invoicexpress_woocommerce_error', $error_notice, $order_object );
				return false;
			}
		} else {
			do_action( 'invoicexpress_woocommerce_debug', 'Order is in Euros', $order_object );
		}
		return true;
	}

}

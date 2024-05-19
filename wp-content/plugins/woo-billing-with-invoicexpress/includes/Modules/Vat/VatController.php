<?php
namespace Webdados\InvoiceXpressWooCommerce\Modules\Vat;

/* WooCommerce CRUD ready */
/* WooCommerce HPOS ready 2023-07-13 */

class VatController {

	/**
	 * The plugin's instance.
	 *
	 * @since  2.3.2
	 * @access protected
	 * @var    Plugin
	 */
	protected $plugin;

	/**
	 * Variables
	 */
	public $VatCheckoutBlock;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 2.3.2
	 * @param Plugin $plugin This plugin's instance.
	 */
	public function __construct( \Webdados\InvoiceXpressWooCommerce\Plugin $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Register hooks.
	 *
	 * @since 2.0.0
	 */
	public function register_hooks() {
		// Frontend (blocks checkout) - Initialize - We need to go with plugins_loaded or it's too late (we need to check if we have no problems with eventual hooks called in the theme)
		// If field active and there's no external block enabled plugin
		add_action( 'plugins_loaded', function() {
			if ( get_option( 'hd_wc_ie_plus_vat_field' ) && ! apply_filters( 'invoicexpress_woocommerce_external_vat_blocks', false ) ) {
				$this->VatCheckoutBlock = new \Webdados\InvoiceXpressWooCommerce\WoocommerceBlocks\VatCheckoutBlock( $this );
				$this->VatCheckoutBlock->register_hooks();
			}
		}, 9 ); // Before 'woocommerce_blocks_loaded'

		//We need to allow the theme to hook into filters
		add_action( 'after_setup_theme', function() {
			//Scripts and styles
			//add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) ); // Not needed
			//VAT Field related stuff
			if ( get_option( 'hd_wc_ie_plus_vat_field' ) ) {

				// Frontend (classic checkout) - Checkout field
				add_filter( 'woocommerce_checkout_fields', array( $this, 'woocommerce_checkout_fields' ), 50 ); //After AELIA
				// Frontend (classic checkout) - Validate VAT
				add_action( 'woocommerce_checkout_process', array( $this, 'validate_vat_frontend' ), 1000 );
				// Frontend (classic checkout) - Save VAT field
				add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'woocommerce_checkout_update_order_meta' ), 100, 1 );

				// My account billing address field
				add_action( 'woocommerce_after_edit_address_form_billing', array( $this, 'woocommerce_after_edit_address_form_billing' ) );
				// My account billing address field - validation and save
				add_action( 'woocommerce_customer_save_address', array( $this, 'woocommerce_customer_save_address', ), 10, 2 );

				// WP Admin - User profile field
				add_filter( 'woocommerce_customer_meta_fields', array( $this, 'woocommerce_customer_meta_fields' ), 10, 1 );
				// WP Admin - Order billing address
				add_filter( 'woocommerce_admin_billing_fields', array( $this, 'order_edit_vat_field' ), 60, 1 ); //yith-woocommerce-checkout-manager uses 50
				// WP Admin - Order billing address - Load billing address from user
				add_filter( 'woocommerce_ajax_get_customer_details', array( $this, 'ajax_get_customer_details' ), 10, 3 );
				
				//Emails - Add VAT number
				add_filter( 'woocommerce_email_order_meta_fields', array( $this, 'woocommerce_email_order_meta_fields' ), 10, 3 );

				//Thank you - Add VAT number
				add_action( 'woocommerce_order_details_after_customer_details', array( $this, 'woocommerce_order_details_after_customer_details' ) );

			}

			//WP Admin - Order observations.
			add_action( 'woocommerce_admin_order_data_after_shipping_address', array( $this, 'order_edit_observations_field' ), 20 );
			//WP Admin - Save VAT field and Observations
			add_action( 'woocommerce_process_shop_order_meta', array( $this, 'order_edit_vat_observations_save' ), 45, 2 );

		} );

	}

	/* Admin javascript */
	//public function enqueue_scripts() {
	//	if ( $order_object = $this->plugin->get_order_object_edit_screen( null ) ) {
	//		wp_register_script( 'hd_wc_ie_order', plugins_url( 'assets/js/order.js', INVOICEXPRESS_WOOCOMMERCE_PLUGIN_FILE ), array( 'jquery' ), INVOICEXPRESS_WOOCOMMERCE_VERSION, true );
	//		wp_localize_script( 'hd_wc_ie_order', 'hd_wc_ie_order', array(
	//			'default_refund_reason' => $this->plugin->get_translated_option( 'hd_wc_ie_plus_refund_automatic_message', null, $order_object ),
	//		) );
	//		wp_enqueue_script( 'hd_wc_ie_order' );
	//	}
	//}

	/* Sanitize VAT field */
	public function sanitize_vat_field( $vat_number ) {
		$vat_number = sanitize_text_field( $vat_number );
		$vat_number = trim( $vat_number );
		$vat_number = str_replace( ' ', '', $vat_number );
		return $vat_number;
	}

	/**
	 * Show the VAT field in the checkout according to the settings.
	 *
	 * @since  2.0.0 Code review and fix support to EU VAT Assistant.
	 * @since  1.0.0
	 * @param  array $fields The checkout fields.
	 * @return array
	 */
	public function woocommerce_checkout_fields( $fields ) {
		global $current_user;

		if ( apply_filters( 'invoicexpress_woocommerce_external_vat', false ) ) { //Pro
			//Aelia WooCommerce EU VAT Assistant active? make it required if needed
			if ( isset( $fields['billing']['vat_number'] ) && get_option( 'hd_wc_ie_plus_vat_field_mandatory' ) ) {
				$fields['billing']['vat_number']['required'] = true;
				return $fields;
			}
			//WooCommerce EU VAT Field active? make it required if needed - Not working because "WooCommerce EU VAT Field" doesn't use the right way of adding fields
			//if ( isset( $fields['billing']['_vat_number'] ) && get_option( 'hd_wc_ie_plus_vat_field_mandatory' ) ) {
			//	$fields['billing']['_vat_number']['required'] = true;
			//	return $fields;
			//}
			//EU/UK VAT Compliance Assistant for WooCommerce (Premium)
			//Not implemented as they have their own settings
			return $fields;
		}

		$fields['billing'][ INVOICEXPRESS_WOOCOMMERCE_VAT_CHECKOUT_FIELD ] = array(
			'label'    => __( 'VAT number', 'woo-billing-with-invoicexpress' ),
			'required' => ( get_option( 'hd_wc_ie_plus_vat_field_mandatory' ) == 1 ),
			'type'     => 'text',
			'class'    => array(
				'form-row-wide',
			),
			'clear'    => true,
			'default'  => get_user_meta( $current_user->ID, INVOICEXPRESS_WOOCOMMERCE_VAT_USER_FIELD, true ),
			'priority' => apply_filters( 'invoicexpress_woocommerce_vat_field_priority', 120 ),
		);

		return $fields;
	}

	/* My account - edit address VAT */
	public function woocommerce_after_edit_address_form_billing() {
		global $current_user;
		echo sprintf(
			'<p class="form-row form-row-wide"><label for="_billing_VAT_code">%1$s:</label><span class="woocommerce-input-wrapper"><input id="%2$s" name="%2$s" type="text" value="%3$s" class="input-text"/></span><p>',
			__( 'VAT number', 'woo-billing-with-invoicexpress' ),
			INVOICEXPRESS_WOOCOMMERCE_VAT_USER_FIELD,
			esc_attr( get_user_meta( $current_user->ID, INVOICEXPRESS_WOOCOMMERCE_VAT_USER_FIELD, true ) )
		);
	}

	/* My account - validate and save VAT */
	public function woocommerce_customer_save_address( $user_id, $load_address ) {
		if ( $load_address == 'billing' && isset( $_POST[INVOICEXPRESS_WOOCOMMERCE_VAT_USER_FIELD] ) ) {
			$vat_number = $this->sanitize_vat_field( $_POST[INVOICEXPRESS_WOOCOMMERCE_VAT_USER_FIELD] );
			if ( isset( $_POST['billing_country'] ) && $_POST['billing_country'] == 'PT' && ! empty( $vat_number ) ) {
				if ( ! self::validate_portuguese_vat( $vat_number ) ) {
					\wc_add_notice( __( 'Invalid Portuguese VAT number.', 'woo-billing-with-invoicexpress' ), 'error' );
					return;
				}
			}
			update_user_meta( $user_id, INVOICEXPRESS_WOOCOMMERCE_VAT_USER_FIELD, $vat_number );
		}
	}

	/**
	 * Show the VAT field in the customer meta fields according to the settings. (wp-admin edit user)
	 *
	 * @since  2.0.0 Code review.
	 * @since  1.0.0
	 * @param  array $fields The fields.
	 * @return array
	 */
	public function woocommerce_customer_meta_fields( $fields ) {
		$fields['billing']['fields'][INVOICEXPRESS_WOOCOMMERCE_VAT_USER_FIELD] = array(
			'label'       => __( 'InvoiceXpress', 'woo-billing-with-invoicexpress' ).' - '.__( 'VAT number', 'woo-billing-with-invoicexpress' ),
			'required'    => ( get_option( 'hd_wc_ie_plus_vat_field_mandatory' ) == 1 ),
			'type'        => 'text',
			'description' => __( 'User VAT number', 'woo-billing-with-invoicexpress' ),
		);
		return $fields;
	}

	/*  wp-admin edit order VAT Field - SHOULD BE REPLACED WITH OUR OWN METABOX */
	public function order_edit_vat_field( $fields ) {
		if ( $order_object = $this->plugin->get_order_object_edit_screen( null ) ) {
			$custom_attributes = array();
			if ( ( $client_id = $order_object->get_meta( 'hd_wc_ie_plus_client_id' ) ) && ( $client_code = $order_object->get_meta( 'hd_wc_ie_plus_client_code' ) ) ) {
				//Read only if user already has contact assigned on InvoiceXpress (why?)
				$custom_attributes['readonly'] = 'readonly';
			}
			$fields['VAT_code'] = array(
				'label'    => __( 'InvoiceXpress', 'woo-billing-with-invoicexpress' ).' - '.__( 'VAT number', 'woo-billing-with-invoicexpress' ),
				'required' => ( get_option( 'hd_wc_ie_plus_vat_field_mandatory' ) == 1 ),
				'type'     => 'text',
				'class'    => 'short',
				'wrapper_class' => 'form-field form-field-wide',
				'custom_attributes' => $custom_attributes,
			);
		}
		return $fields;
	}

	/*  wp-admin edit order - load billing address from user */
	public function ajax_get_customer_details( $data, $customer, $user_id ) {
		$data['billing']['VAT_code'] = get_user_meta( $user_id, INVOICEXPRESS_WOOCOMMERCE_VAT_USER_FIELD, true );
		return $data;
	}

	/* Frontend - Save VAT number */
	public function woocommerce_checkout_update_order_meta( $order_id ) {
		if ( apply_filters( 'invoicexpress_woocommerce_process_vat_vatcontroller', true ) ) { //Pro?
			$updated = false;
			$order_object = wc_get_order( $order_id );
			if ( isset( $_POST[INVOICEXPRESS_WOOCOMMERCE_VAT_USER_FIELD] ) && ! empty( $_POST[INVOICEXPRESS_WOOCOMMERCE_VAT_USER_FIELD] ) ) {
				$vat_number = $this->sanitize_vat_field( $_POST[INVOICEXPRESS_WOOCOMMERCE_VAT_USER_FIELD] );
				$order_object->update_meta_data( INVOICEXPRESS_WOOCOMMERCE_VAT_ORDER_FIELD, $vat_number );
				$updated = true;
			}
			// NIF (Num. de Contribuinte Português) for WooCommerce.
			if ( ! $updated && isset( $_POST['billing_nif'] ) && ! empty( $_POST['billing_nif'] ) ) {
				$vat_number = $this->sanitize_vat_field( $_POST['billing_nif'] );
				$order_object->update_meta_data( INVOICEXPRESS_WOOCOMMERCE_VAT_ORDER_FIELD, $vat_number );
				$updated = true;
			}
			// Save
			$order_object->save();
			// Anything else?
			do_action( 'invoicexpress_woocommerce_after_update_order_meta_frontend', $order_object, 'classic' );
		}
	}

	/* WP Admin - Save VAT number and Observations */
	public function order_edit_vat_observations_save( $id_post_or_order, $post_or_order_object ) {
		//Get order object from post (non-HPOS) or $order (HPOS)
		$order_object = $this->plugin->get_order_object_edit_screen( $post_or_order_object );
		//Only orders
		if ( ! $this->plugin->is_valid_order_type( $order_object ) ) return;
		//Do it
		$updated = false;
		//VAT
		if ( isset( $_POST[INVOICEXPRESS_WOOCOMMERCE_VAT_ORDER_FIELD] ) ) {
			$updated = true;
			$vat_number = $this->sanitize_vat_field( $_POST[INVOICEXPRESS_WOOCOMMERCE_VAT_ORDER_FIELD] );
			$order_object->update_meta_data( INVOICEXPRESS_WOOCOMMERCE_VAT_ORDER_FIELD, $vat_number );
		}
		//Observations
		if ( isset( $_POST['_document_observations'] ) ) {
			$updated = true;
			$order_object->update_meta_data( '_document_observations', sanitize_textarea_field( $_POST['_document_observations'] ) );
		}
		if ( $updated ) $order_object->save();
	}

	/* Add VAT number to emails */
	public function woocommerce_email_order_meta_fields( $fields, $sent_to_admin, $order ) {
		if ( apply_filters( 'invoicexpress_woocommerce_add_vat_to_email', true ) ) {
			if ( $vat_number = $order->get_meta( INVOICEXPRESS_WOOCOMMERCE_VAT_ORDER_FIELD ) ) {
				$fields[INVOICEXPRESS_WOOCOMMERCE_VAT_ORDER_FIELD] = array(
					'label' => __( 'VAT number', 'woo-billing-with-invoicexpress' ),
					'value' => $vat_number
				);
			}
			return $fields;
		}
	}
	/**
	 * Add field to customer details on the Thank You page
	 *
	 * @param object $order The WooCommerce order.
	 */
	public function woocommerce_order_details_after_customer_details( $order ) {
		if ( apply_filters( 'invoicexpress_woocommerce_add_vat_to_thank_you', true ) ) {
			if ( $vat_number = $order->get_meta( INVOICEXPRESS_WOOCOMMERCE_VAT_ORDER_FIELD ) ) {
				?>
				<p id="woocommerce_nif_info">
					<span id="invoicexpress_vat_info_label">
						<?php _e( 'VAT number', 'woo-billing-with-invoicexpress' ); ?>:
					</span>
					<span id="invoicexpress_vat_info_value">
						<?php echo esc_html( $vat_number ); ?>
					</span>
				</p>
				<?php
			}
		}
	}

	/* Frontend - Validate VAT */
	public function validate_vat_frontend() {
		//Validate portuguese VAT number
		if ( isset( $_POST[INVOICEXPRESS_WOOCOMMERCE_VAT_CHECKOUT_FIELD] ) && ! empty( $_POST[INVOICEXPRESS_WOOCOMMERCE_VAT_CHECKOUT_FIELD] ) && isset( $_POST['billing_country'] ) && $_POST['billing_country'] == 'PT' ) {
			$vat_number = $this->sanitize_vat_field( $_POST[INVOICEXPRESS_WOOCOMMERCE_VAT_CHECKOUT_FIELD] );
			if ( ! self::validate_portuguese_vat( $vat_number ) ) {
				\wc_add_notice( __( 'Invalid Portuguese VAT number.', 'woo-billing-with-invoicexpress' ), 'error' );
			}
		}
		//Required?
		if ( get_option( 'hd_wc_ie_plus_vat_field_mandatory' ) && isset( $_POST[INVOICEXPRESS_WOOCOMMERCE_VAT_CHECKOUT_FIELD] ) && ! $_POST[INVOICEXPRESS_WOOCOMMERCE_VAT_CHECKOUT_FIELD] && $required ) {
			\wc_add_notice( __( 'The VAT number is a required field.', 'woo-billing-with-invoicexpress' ), 'error' );
		}
	}
	/* Validate Portuguese VAT numbers */
	public static function validate_portuguese_vat( $vat ) {
		/*
		 * Based on this rules (in portuguese):
		 * https://pt.wikipedia.org/wiki/N%C3%BAmero_de_identifica%C3%A7%C3%A3o_fiscal
		 */
		$vat = preg_replace( '/\s+/', '', str_replace( 'PT', '', $vat ) ); //Remove PT prefix for validation only
		if ( ! is_numeric( $vat ) ) {
			return false;
		}
		if ( strlen( $vat ) != 9 ) {
			return false;
		}
		$error = 0;
		if (
			substr( $vat, 0, 1 ) != '1' &&
			substr( $vat, 0, 1 ) != '2' &&
			substr( $vat, 0, 1 ) != '3' &&
			substr( $vat, 0, 2 ) != '45' &&
			substr( $vat, 0, 1 ) != '5' &&
			substr( $vat, 0, 1 ) != '6' &&
			substr( $vat, 0, 2 ) != '70' &&
			substr( $vat, 0, 2 ) != '71' &&
			substr( $vat, 0, 2 ) != '72' &&
			substr( $vat, 0, 2 ) != '74' &&
			substr( $vat, 0, 2 ) != '75' &&
			substr( $vat, 0, 2 ) != '77' &&
			substr( $vat, 0, 2 ) != '78' &&
			substr( $vat, 0, 2 ) != '79' &&
			substr( $vat, 0, 1 ) != '8' &&
			substr( $vat, 0, 2 ) != '90' &&
			substr( $vat, 0, 2 ) != '91' &&
			substr( $vat, 0, 2 ) != '98' &&
			substr( $vat, 0, 2 ) != '99'
		) {
			$error = 1;
		}
		$check1 = substr( $vat, 0, 1 ) * 9;
		$check2 = substr( $vat, 1, 1 ) * 8;
		$check3 = substr( $vat, 2, 1 ) * 7;
		$check4 = substr( $vat, 3, 1 ) * 6;
		$check5 = substr( $vat, 4, 1 ) * 5;
		$check6 = substr( $vat, 5, 1 ) * 4;
		$check7 = substr( $vat, 6, 1 ) * 3;
		$check8 = substr( $vat, 7, 1 ) * 2;
		$total = $check1 + $check2 + $check3 + $check4 + $check5 + $check6 + $check7 + $check8;
		$totalDiv11  = $total / 11;
		$modulusOf11 = $total - intval( $totalDiv11 ) * 11;
		if ( $modulusOf11 == 1 || $modulusOf11 == 0 ) {
			$check = 0;
		} else {
			$check = 11 - $modulusOf11;
		}
		$lastDigit = substr( $vat, 8, 1 ) * 1;
		if ( $lastDigit != $check ) {
			$error = 1;
		}
		if ( $error == 1 ) {
			return false;
		}
		return true;
	}

	/* WP Admin - Observations field - SHOULD BE REPLACED WITH OUR OWN METABOX */
	public function order_edit_observations_field( $order ) {
		//We only invoice regular orders, not subscriptions or other special types of orders
		if ( ! $this->plugin->is_valid_order_type( $order ) ) return;
		woocommerce_wp_textarea_input(
			array(
				'id'            => '_document_observations',
				'label'         => __( 'InvoiceXpress', 'woo-billing-with-invoicexpress' ).' - '.__( 'Document observations', 'woo-billing-with-invoicexpress' ),
				'placeholder'   => __( 'Observations to be inserted into InvoiceXpress documents', 'woo-billing-with-invoicexpress' ),
				'class'         => 'widefat',
				'wrapper_class' => 'form-field form-field-wide',
			),
			$order
		);
	}


	/**
	 * Get VAT exemption reasons
	 *
	 * @since 2.6.2
	 */
	public static function get_exemption_reasons( $selected_exemption_reason = '' ) {
		//From 2023
		$reasons =  array(
			'M01'   => 'M01 - Artigo 16.º n.º 6 alínea c) do CIVA',
			'M02'   => 'M02 - Artigo 6.º do Decreto‐Lei n.º 198/90, de 19 de Junho',
			'M04'   => 'M04 - Isento - Artigo 13.º do CIVA',
			'M05'   => 'M05 - Isento - Artigo 14.º do CIVA',
			'M06'   => 'M06 - Isento - Artigo 15.º do CIVA',
			'M07'   => 'M07 - Isento - Artigo 9.º do CIVA',
			'M09'   => 'M09 - IVA - Não confere direito a dedução',
			'M10'   => 'M10 - Regime de isenção de IVA - Artigo 53.º do CIVA',
			'M11'   => 'M11 - Não tributado',
			'M12'   => 'M12 - Regime da margem de lucro – Agências de Viagens',
			'M13'   => 'M13 - Regime da margem de lucro – Bens em segunda mão',
			'M14'   => 'M14 - Regime da margem de lucro – Objetos de arte',
			'M15'   => 'M15 - Regime da margem de lucro – Objetos de coleção e antiguidades',
			'M16'   => 'M16 - Isento - Artigo 14.º do RITI',
			'M19'   => 'M19 - Outras isenções - Isenções temporárias determinadas em diploma próprio',
			'M20'   => 'M20 - IVA - Regime forfetário - Artigo 59.º-D n.º2 do CIVA',
			'M21'   => 'M21 - IVA – Não confere direito à dedução - Artigo 72.º n.º 4 do CIVA',
			'M25'   => 'M25 - Mercadorias à consignação - Artigo 38.º n.º 1 alínea a) do CIVA',
			'M26'   => 'M26 - Isenção de IVA com direito à dedução no cabaz alimentar - Lei n.º 17/2023, de 14 de abril',
			'M30'   => 'M30 - IVA - Autoliquidação - Artigo 2.º n.º 1 alínea i) do CIVA',
			'M31'   => 'M31 - IVA - Autoliquidação - Artigo 2.º n.º 1 alínea j) do CIVA',
			'M32'   => 'M32 - IVA - Autoliquidação - Artigo 2.º n.º 1 alínea l) do CIVA',
			'M33'   => 'M33 - IVA - Autoliquidação - Artigo 2.º n.º 1 alínea m) do CIVA',
			'M40'   => 'M40 - IVA - Autoliquidação - Artigo 6.º n.º 6 alínea a) do CIVA, a contrário',
			'M41'   => 'M41 - IVA - Autoliquidação - Artigo 8.º n.º 3 do RITI',
			'M42'   => 'M42 - IVA - Autoliquidação - Decreto-Lei n.º 21/2007, de 29 de janeiro',
			'M43'   => 'M43 - IVA - Autoliquidação - Decreto-Lei n.º 362/99, de 16 de setembro',
			'M99'   => 'M99 - Não sujeito; não tributado (ou similar)',
		);
		//Older orders might have older exemptions
		switch ( $selected_exemption_reason ) {
			case 'M03':
				$reasons['M03'] = 'M03 - Exigibilidade de caixa ⚠️ '.__( 'NOT VALID SINCE 2023', 'woo-billing-with-invoicexpress' );
				break;
			case 'M08':
				$reasons['M08'] = 'M08 - IVA - Autoliquidação ⚠️ '.__( 'NOT VALID SINCE 2023', 'woo-billing-with-invoicexpress' );
				break;
			case 'M99-2':
				$reasons['M99-2'] = 'M99-2 - Lei n.º 13/2020 de 7 de Maio 2020 - Artigo 1.º alínea a) ⚠️ '.__( 'NOT VALID SINCE 2023', 'woo-billing-with-invoicexpress' );
				break;
			default:
				break;
		}
		return $reasons;
	}

}

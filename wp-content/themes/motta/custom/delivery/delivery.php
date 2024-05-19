<?php 

function wwmall_delivery_method_init() {
    if ( ! class_exists( 'WC_Wwmall_Delivery_Method' ) ) {
        class WC_Wwmall_Delivery_Method extends WC_Shipping_Method {
            /**
        	 * Shipping method cost.
        	 *
        	 * @var string
        	 */
        	public $cost;
        	
        	public $member_cost;
            
        	/**
        	 * Constructor.
        	 *
        	 * @param int $instance_id Shipping method instance.
        	 */
            public function __construct( $instance_id = 0 ) {
                $this->id = 'wwmall_delivery_method'; // Id for your shipping method. Should be uunique.
                $this->instance_id        = absint( $instance_id );
                $this->method_title = __( 'Wwmall Delivery', 'woocommerce' ); // Title shown in admi
                $this->method_description = __( 'Wwmall Devlivery Method' ); // Description shown in admin
                $this->supports = array(
        			'shipping-zones',
        			'instance-settings',
        			'instance-settings-modal',
        		);
                // $this->enabled = "yes"; // This can be added as an setting but for this example its forced enabled
                // $this->title = "Wwmall"; // This can be added as an setting but for this example its forced.
                $this->init();
                // add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
            }
                    
            function init() {
                // Load the settings API
                $this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
                $this->init_settings(); // This is part of the settings API. Loads settings you previously init.
                
                $this->title  = $this->get_option( 'title' );
                $this->cost  = $this->get_option( 'cost' );
                $this->member_cost = $this->get_option( 'member_cost' );
		        // Save settings in admin if you have any defined
                add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
                // add_filter( 'woocommerce_shipping_' . $this->id . '_instance_settings_values', array( $this, 'sanitize_settings' ), 12, 2 );
            }
                        
            function init_form_fields() {
                $this->instance_form_fields = array(
                    'title' => array(
                        'title' => __( 'Title', 'woocommerce' ),
                        'type' => 'text',
                        'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
                        'default' => __( 'Delivery', 'woocommerce' ),
                        'placeholder' => __( 'e.g. Delivery', 'woocommerce' ),
        				'desc_tip'    => true,
                    ),
                    'cost'       => array(
        				'title'             => __( 'Normal Cost', 'woocommerce' ),
        				'type'              => 'text',
        				'class'             => 'wc-shipping-modal-price',
        				'placeholder'       => wc_format_localized_price( 0 ),
        				'description'       => __( 'Delivery cost for normal users.', 'woocommerce' ),
        				'default'           => '',
        				'desc_tip'          => true,
        				'sanitize_callback' => array( $this, 'sanitize_cost' ),
        			),
        			'member_cost'       => array(
        				'title'             => __( 'Member Cost', 'woocommerce' ),
        				'type'              => 'text',
        				'class'             => 'wc-shipping-modal-price',
        				'placeholder'       => wc_format_localized_price( 0 ),
        				'description'       => __( 'Delivery cost for member users.', 'woocommerce' ),
        				'default'           => '',
        				'desc_tip'          => true,
        				'sanitize_callback' => array( $this, 'sanitize_cost' ),
        			),
        		);
            }
            
            	/**
        	 * Sanitize the cost field.
        	 *
        	 * @since 8.3.0
        	 * @param string $value Unsanitized value.
        	 * @throws Exception Last error triggered.
        	 * @return string
        	 */
        	public function sanitize_cost( $value ) {
        		$value = is_null( $value ) ? '' : $value;
        		$value = wp_kses_post( trim( wp_unslash( $value ) ) );
        		$value = str_replace( array( get_woocommerce_currency_symbol(), html_entity_decode( get_woocommerce_currency_symbol() ) ), '', $value );
        
        		$test_value = str_replace( wc_get_price_decimal_separator(), '.', $value );
        		$test_value = str_replace( array( get_woocommerce_currency_symbol(), html_entity_decode( get_woocommerce_currency_symbol() ), wc_get_price_thousand_separator() ), '', $test_value );
        
        		if ( $test_value && ! is_numeric( $test_value ) ) {
        			throw new Exception( __( 'Please enter a valid number', 'woocommerce' ) );
        		}
        
        		return $value;
        	}
        	
        
            public function calculate_shipping( $package = array() ) {
                $cost = $this->cost;
                $user_id = get_current_user_id();
                global $wpdb;
                // $is_member = get_user_meta( $user_id, 'is_member', true );
                $is_member = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}usermeta WHERE meta_key = 'is_member' AND user_id = %d", $user_id))[0]->meta_value;
                if ( 'member' == $is_member ) {
                    $cost = $this->member_cost;
                }
                $rate = array(
                    'id' => $this->id,
                    'label' => $this->title,
                    'package' => $package,
                    // 'cost' => $cost,
                    'cost' => $cost,
                    'calc_tax' => 'per_order'
                );
                // Register the rate, 
                $this->add_rate( $rate );
            }
            
            // public function sanitize_settings( $settings, $shipping_method ) {
            //     var_dump($settings);
            //     return $settings;
            // }
        }
        
    }
    if ( ! class_exists( 'WC_Wwmall_Expedited_Delivery_Method' ) ) {
        class WC_Wwmall_Expedited_Delivery_Method extends WC_Shipping_Method {
            /**
        	 * Shipping method cost.
        	 *
        	 * @var string
        	 */
        	public $cost;
        	
        	public $member_cost;
            
        	/**
        	 * Constructor.
        	 *
        	 * @param int $instance_id Shipping method instance.
        	 */
            public function __construct( $instance_id = 0 ) {
                $this->id = 'wwmall_expedited_delivery_method'; // Id for your shipping method. Should be uunique.
                $this->instance_id        = absint( $instance_id );
                $this->method_title = __( 'Wwmall Expedited Delivery', 'woocommerce' ); // Title shown in admi
                $this->method_description = __( 'Wwmall Expedited Devlivery Method' ); // Description shown in admin
                $this->supports = array(
        			'shipping-zones',
        			'instance-settings',
        			'instance-settings-modal',
        		);
                $this->init();
            }
                    
            function init() {
                // Load the settings API
                $this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
                $this->init_settings(); // This is part of the settings API. Loads settings you previously init.
                
                $this->title  = $this->get_option( 'title' );
                $this->cost  = $this->get_option( 'cost' );
                $this->member_cost = $this->get_option( 'member_cost' );
		        // Save settings in admin if you have any defined
                add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
            }
                        
            function init_form_fields() {
                $this->instance_form_fields = array(
                    'title' => array(
                        'title' => __( 'Title', 'woocommerce' ),
                        'type' => 'text',
                        'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
                        'default' => __( 'Expedited Delivery', 'woocommerce' ),
                        'placeholder' => __( 'e.g. Expedited Delivery', 'woocommerce' ),
        				'desc_tip'    => true,
                    ),
                    'cost'       => array(
        				'title'             => __( 'Normal Cost', 'woocommerce' ),
        				'type'              => 'text',
        				'class'             => 'wc-shipping-modal-price',
        				'placeholder'       => wc_format_localized_price( 0 ),
        				'description'       => __( 'Delivery cost for normal users.', 'woocommerce' ),
        				'default'           => '',
        				'desc_tip'          => true,
        				'sanitize_callback' => array( $this, 'sanitize_cost' ),
        			),
        			'member_cost'       => array(
        				'title'             => __( 'Member Cost', 'woocommerce' ),
        				'type'              => 'text',
        				'class'             => 'wc-shipping-modal-price',
        				'placeholder'       => wc_format_localized_price( 0 ),
        				'description'       => __( 'Delivery cost for member users.', 'woocommerce' ),
        				'default'           => '',
        				'desc_tip'          => true,
        				'sanitize_callback' => array( $this, 'sanitize_cost' ),
        			),
        		);
            }
            
            	/**
        	 * Sanitize the cost field.
        	 *
        	 * @since 8.3.0
        	 * @param string $value Unsanitized value.
        	 * @throws Exception Last error triggered.
        	 * @return string
        	 */
        	public function sanitize_cost( $value ) {
        		$value = is_null( $value ) ? '' : $value;
        		$value = wp_kses_post( trim( wp_unslash( $value ) ) );
        		$value = str_replace( array( get_woocommerce_currency_symbol(), html_entity_decode( get_woocommerce_currency_symbol() ) ), '', $value );
        
        		$test_value = str_replace( wc_get_price_decimal_separator(), '.', $value );
        		$test_value = str_replace( array( get_woocommerce_currency_symbol(), html_entity_decode( get_woocommerce_currency_symbol() ), wc_get_price_thousand_separator() ), '', $test_value );
        
        		if ( $test_value && ! is_numeric( $test_value ) ) {
        			throw new Exception( __( 'Please enter a valid number', 'woocommerce' ) );
        		}
        
        		return $value;
        	}
        	
        
            public function calculate_shipping( $package = array() ) {
                $cost = $this->cost;
                $user_id = get_current_user_id();
                global $wpdb;
                // $is_member = get_user_meta( $user_id, 'is_member', true );
                $is_member = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}usermeta WHERE meta_key = 'is_member' AND user_id = %d", $user_id))[0]->meta_value;
                if ( 'member' == $is_member ) {
                    $cost = $this->member_cost;
                }
                $rate = array(
                    'id' => $this->id,
                    'label' => $this->title,
                    'package' => $package,
                    'cost' => $cost,
                    'calc_tax' => 'per_order'
                );
                // Register the rate, 
                $this->add_rate( $rate );
            }
            
            // public function sanitize_settings( $settings, $shipping_method ) {
            //     var_dump($settings);
            //     return $settings;
            // }
        }
        
    }

}

add_action( 'woocommerce_shipping_init', 'wwmall_delivery_method_init' );

function wwmall_shipping_method( $methods ) {
	$methods['wwmall_delivery_method'] = 'WC_Wwmall_Delivery_Method';
	$methods['wwmall_expedited_delivery_method'] = 'WC_Wwmall_Expedited_Delivery_Method';
	return $methods;
}

add_filter( 'woocommerce_shipping_methods', 'wwmall_shipping_method' );

add_filter( 'woocommerce_thankyou_order_received_text', 'custom_thankyou_message' );

function custom_thankyou_message( $message ) {
    $new_message = "Your order has been successfully placed. As soon as one of our delivery drivers is assigned, you will receive a notification of their name, a picture of them and their cell phone number in case something comes up and you need to contact them.";
    return $new_message;
}


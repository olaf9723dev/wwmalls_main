<?php 

/* Add new shipping method*/

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

function wpll_shipping_method_init() {
if ( ! class_exists( 'Wc_wpll_shipping_method' ) ) {
class Wc_wpll_shipping_method extends WC_Shipping_Method {
	public function __construct() {
		$this->id = 'wpll-shipping-method'; // Id for your shipping method. Should be uunique.
		$this->method_title = __( 'Local Pickup Pro Shipping' ); // Title shown in admi
		$this->method_description = __( 'Local Pickup Pro Shipping Method' ); // Description shown in admin
		$this->enabled = "yes"; // This can be added as an setting but for this example its forced enabled
		$this->title = "Local Pickup Pro"; // This can be added as an setting but for this example its forced.
		$this->init();
	}

function init() {
	// Load the settings API
	$this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
	$this->init_settings(); // This is part of the settings API. Loads settings you previously init.
	$this->cost  = $this->get_option( 'cost' );
	$this->title  = $this->get_option( 'title' );
	// Save settings in admin if you have any defined
	add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
}

function init_form_fields() {
     $this->form_fields = array(
		'enabled' => array(
        'title'         => __( 'Enable/Disable', 'woocommerce' ),
        'type'          => 'checkbox',
        'label'         => __( 'Enable Local Pickup Pro Shipping', 'woocommerce' ),
        'default'       => 'yes'
        ),
       'title' => array(
        'title' => __( 'Title', 'woocommerce' ),
        'type' => 'text',
        'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
        'default' => __( 'Local Pickup', 'woocommerce' )
        ),
		'schedule_appointment'=> array(
        'title' => __( 'Schedule pickup appointment', 'woocommerce' ),
        'type' => 'checkbox',
		'label'         => __( 'Enable Schedule Pickup Appointment On Checkout Page', 'woocommerce' ),
		'default'       => 'yes'
        )
		
		);
		
}

public function is_available( $package ) {
	$is_available = ($this->settings['enabled'] == 'yes') ? true : false;
	return apply_filters( 'woocommerce_shipping_' . $this->id . '_is_available', $is_available, $package, $this );
}

public function calculate_shipping( $package = array() ) {
			$rate = array(
				'id' => $this->id,
				'label' => $this->title,
				'cost' => (!empty($this->costs) && $this->costs_per_store != 'yes') ? apply_filters('wps_shipping_costs', $this->costs) : 0,
				'package' => $package,
				'calc_tax' => 'per_order'
			);
			// Register the rate
			$this->add_rate( $rate );
			}
		}
	}
}
add_action( 'woocommerce_shipping_init', 'wpll_shipping_method_init' );

function wpll_shipping_method( $methods ) {
	$methods[] = 'Wc_wpll_shipping_method';
	return $methods;
}
add_filter( 'woocommerce_shipping_methods', 'wpll_shipping_method' );
}

function WPLL() {
	return new Wc_wpll_shipping_method();
} 
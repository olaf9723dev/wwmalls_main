<?php

namespace Motta\Addons\Modules\Sticky_Add_To_Cart;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main class of plugin for admin
 */
class Settings {

	/**
	 * Instance
	 *
	 * @var $instance
	 */
	private static $instance;


	/**
	 * Initiator
	 *
	 * @since 1.0.0
	 * @return object
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'woocommerce_get_sections_products', array( $this, 'sticky_add_to_cart_section' ), 10, 1 );
		add_filter( 'woocommerce_get_settings_products', array( $this, 'sticky_add_to_cart_settings' ), 10, 2 );
	}

	/**
	 * Buy Now section
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function sticky_add_to_cart_section( $sections ) {
		$sections['motta_sticky_add_to_cart'] = esc_html__( 'Sticky Add To Cart', 'motta-addons' );

		return $sections;
	}

	/**
	 * Adds settings to product display settings
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings
	 * @param string $section
	 *
	 * @return array
	 */
	public function sticky_add_to_cart_settings( $settings, $section ) {
		if ( 'motta_sticky_add_to_cart' == $section ) {
			$settings = array();

			$settings[] = array(
				'id'    => 'motta_sticky_add_to_cart_options',
				'title' => esc_html__( 'Sticky Add To Cart', 'motta-addons' ),
				'type'  => 'title',
			);

			$settings[] = array(
				'id'      => 'motta_sticky_add_to_cart_toggle',
				'title'   => esc_html__( 'Sticky Add To Cart', 'motta-addons' ),
				'desc'    => esc_html__( 'Enable Sticky Add To Cart', 'motta-addons' ),
				'type'    => 'checkbox',
				'default' => 'yes',
			);

			$settings[] = array(
				'id'      => 'motta_sticky_add_to_cart_product_tabs_toggle',
				'title'   => esc_html__( 'Enable Product Tabs', 'motta-addons' ),
				'desc'    => esc_html__( 'Enable Product Tabs on Sticky Add To Cart', 'motta-addons' ),
				'type'    => 'checkbox',
				'default' => 'no',
			);

			$settings[] = array(
				'id'   => 'motta_sticky_add_to_cart_options',
				'type' => 'sectionend',
			);
		}

		return $settings;
	}
}
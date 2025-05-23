<?php

namespace Motta\Addons\Modules\Catalog_Mode;

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
		add_filter( 'woocommerce_get_sections_products', array( $this, 'catalog_mode_section' ), 20, 2 );
		add_filter( 'woocommerce_get_settings_products', array( $this, 'catalog_mode_settings' ), 20, 2 );

		if ( get_option( 'motta_catalog_mode' ) != 'yes' ) {
			return;
		}

	}

	/**
	 * Catalog mode section
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function catalog_mode_section( $sections ) {
		$sections['motta_catalog_mode'] = esc_html__( 'Catalog Mode', 'motta-addons' );

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
	public function catalog_mode_settings( $settings, $section ) {
		if ( 'motta_catalog_mode' == $section ) {
			$settings = array();

			$settings[] = array(
				'id'    => 'motta_catalog_mode_options',
				'title' => esc_html__( 'Catalog Mode', 'motta-addons' ),
				'type'  => 'title',
			);

			$settings[] = array(
				'id'      => 'motta_catalog_mode',
				'title'   => esc_html__( 'Catalog Mode', 'motta-addons' ),
				'desc'    => esc_html__( 'Enable Catalog Mode', 'motta-addons' ),
				'type'    => 'checkbox',
				'default' => 'no',
			);

			// Price
			$settings[] = array(
				'name'          => esc_html__( 'Price', 'motta-addons' ),
				'desc'          => esc_html__( 'Hide in the product card', 'motta-addons' ),
				'id'            => 'motta_product_card_hide_price',
				'default'       => 'yes',
				'type'          => 'checkbox',
				'checkboxgroup' => 'start',
			);

			if ( function_exists( 'wcboost_wishlist' ) ) {
				$settings[] = array(
					'desc'          => esc_html__( 'Hide in the wishlist page', 'motta-addons' ),
					'id'            => 'motta_wishlist_hide_price',
					'default'       => 'yes',
					'type'          => 'checkbox',
					'checkboxgroup' => '',
				);
			}

			$settings[] = array(
				'desc'          => esc_html__( 'Hide in the product page', 'motta-addons' ),
				'id'            => 'motta_product_hide_price',
				'default'       => 'yes',
				'type'          => 'checkbox',
				'checkboxgroup' => 'end',
			);

			// Add to Cart
			$settings[] = array(
				'name'          => esc_html__( 'Add to Cart', 'motta-addons' ),
				'desc'          => esc_html__( 'Hide in the product card', 'motta-addons' ),
				'id'            => 'motta_product_card_hide_atc',
				'default'       => 'yes',
				'type'          => 'checkbox',
				'checkboxgroup' => 'start',
			);

			if ( function_exists( 'wcboost_wishlist' ) ) {
				$settings[] = array(
					'desc'          => esc_html__( 'Hide in the wishlist page', 'motta-addons' ),
					'id'            => 'motta_wishlist_hide_atc',
					'default'       => 'yes',
					'type'          => 'checkbox',
					'checkboxgroup' => '',
				);
			}

			$settings[] = array(
				'desc'          => esc_html__( 'Hide in the product page', 'motta-addons' ),
				'id'            => 'motta_product_hide_atc',
				'default'       => 'yes',
				'type'          => 'checkbox',
				'checkboxgroup' => 'end',
			);

			// Page
			$settings[] = array(
				'name'          => esc_html__( 'Page', 'motta-addons' ),
				'desc'          => esc_html__( 'Hide in the woocommerce cart page', 'motta-addons' ),
				'id'            => 'motta_hide_cart_page',
				'default'       => 'yes',
				'type'          => 'checkbox',
				'checkboxgroup' => 'start',
			);

			$settings[] = array(
				'desc'          => esc_html__( 'Hide in the woocommerce checkout page', 'motta-addons' ),
				'id'            => 'motta_hide_checkout_page',
				'default'       => 'yes',
				'type'          => 'checkbox',
				'checkboxgroup' => 'end',
			);

			// User
			$settings[] = array(
				'name'    => esc_html__( 'Apply catalog mode to', 'motta-addons' ),
				'id'      => 'motta_catalog_mode_user',
				'default' => 'all_user',
				'type'    => 'radio',
				'options' => array(
					'all_user'   => esc_html__( 'All User', 'motta-addons' ),
					'guest_user' => esc_html__( 'Only guest user', 'motta-addons' ),
				),
			);

			$settings[] = array(
				'id'   => 'motta_catalog_mode_options',
				'type' => 'sectionend',
			);
		}

		return $settings;
	}
}
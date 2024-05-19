<?php

namespace Motta\Addons\Modules\Buy_Now;

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
		add_filter( 'woocommerce_get_sections_products', array( $this, 'buy_now_section' ), 20, 2 );
		add_filter( 'woocommerce_get_settings_products', array( $this, 'buy_now_settings' ), 20, 2 );
	}

	/**
	 * Buy Now section
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function buy_now_section( $sections ) {
		$sections['motta_buy_now'] = esc_html__( 'Buy Now', 'motta-addons' );

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
	public function buy_now_settings( $settings, $section ) {
		if ( 'motta_buy_now' == $section ) {
			$settings = array();

			$settings[] = array(
				'id'    => 'motta_buy_now_options',
				'title' => esc_html__( 'Buy Now', 'motta-addons' ),
				'type'  => 'title',
			);

			$settings[] = array(
				'id'      => 'motta_buy_now',
				'title'   => esc_html__( 'Buy Now', 'motta-addons' ),
				'desc'    => esc_html__( 'Enable Buy Now', 'motta-addons' ),
				'type'    => 'checkbox',
				'default' => 'yes',
			);

			$settings[] = array(
				'name'    => esc_html__( 'Button Text', 'motta-addons' ),
				'id'      => 'motta_buy_now_button_text',
				'type'    => 'text',
				'default' => esc_html__( 'Buy Now', 'motta-addons' ),
			);

			$settings[] = array(
				'name'    => esc_html__( 'Redirect Location', 'motta-addons' ),
				'id'      => 'motta_buy_now_redirect_location',
				'type'    => 'single_select_page',
				'default' => '',
				'args'    => array( 'post_status' => 'publish,private' ),
				'class'   => 'wc-enhanced-select-nostd',
				'css'     => 'min-width:300px;',
				'desc_tip' => esc_html__( 'Select the page where to redirect after buy now button pressed.', 'motta-addons' ),
			);


			$settings[] = array(
				'id'      => 'motta_buy_now_reset_cart',
				'title'   => esc_html__( 'Reset Cart before Buy Now', 'motta-addons' ),
				'type'    => 'checkbox',
				'default' => 'no',
			);

			$settings[] = array(
				'id'   => 'motta_buy_now_options',
				'type' => 'sectionend',
			);
		}

		return $settings;
	}
}
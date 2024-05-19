<?php

namespace Motta\Addons\Modules\Product_Deals;

use Motta\Addons\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Frontend {

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
		add_filter( 'woocommerce_quantity_input_args', array( $this, 'quantity_input_args' ), 10, 2 );

		/* add_action( 'woocommerce_single_product_summary', array( $this, 'single_product_template' ), 25 );
		add_action( 'motta_woocommerce_product_quickview_summary', array( $this, 'single_product_template' ), 45 ); */

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Enqueue scripts
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'motta-deals', MOTTA_ADDONS_URL . 'modules/product-deals/assets/deals.css', array(), '1.0.0' );
	}

	/**
	 * Change the "max" attribute of quantity input
	 *
	 * @since 1.0.0
	 *
	 * @param array $args
	 * @param object $product
	 *
	 * @return array
	 */
	public function quantity_input_args( $args, $product ) {
		if ( ! Helper::is_product_deal( $product ) ) {
			return $args;
		}

		$args['max_value'] = $this->get_max_purchase_quantity( $product );

		return $args;
	}

	/**
	 * Get max value of quantity input for a deal product
	 *
	 * @since 1.0.0
	 *
	 * @param object $product
	 *
	 * @return int
	 */
	public function get_max_purchase_quantity( $product ) {
		$limit = get_post_meta( $product->get_id(), '_deal_quantity', true );
		$sold  = intval( get_post_meta( $product->get_id(), '_deal_sales_counts', true ) );

		$max          = $limit - $sold;
		$original_max = $product->is_sold_individually() ? 1 : ( $product->backorders_allowed() || ! $product->managing_stock() ? - 1 : $product->get_stock_quantity() );

		if ( $original_max < 0 ) {
			return $max;
		}

		return min( $max, $original_max );
	}

	/**
	 * Display countdown and sold items in single product page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function single_product_template() {
		global $product;

		if ( ! Helper::is_product_deal( $product ) ) {
			return;
		}

		$expire_date = ! empty( $product->get_date_on_sale_to() ) ? $product->get_date_on_sale_to()->getOffsetTimestamp() : '';
		$expire_date = apply_filters( 'motta_product_deals_expire_timestamp', $expire_date, $product );

		if ( empty( $expire_date ) ) {
			return;
		}

		$now = strtotime( current_time( 'Y-m-d H:i:s' ) );

		$expire = $expire_date - $now;

		if ( $expire < 0 ) {
			return;
		}

		wc_get_template(
			'single-product/deal.php',
			array(
				'expire'          => $expire,
				'limit'           => get_post_meta( $product->get_id(), '_deal_quantity', true ),
				'sold'            => intval( get_post_meta( $product->get_id(), '_deal_sales_counts', true ) ),
				'expire_text'     => str_replace( '/', '<br>', get_option( 'motta_product_deals_expire_text' ) ),
				'sold_items_text' => get_option( 'motta_product_deals_sold_items_text' ),
				'sold_text'       => get_option( 'motta_product_deals_sold_text' ),
				'countdown_texts' => \Motta\Addons\Helper::get_countdown_texts()
			),
			'',
			MOTTA_ADDONS_DIR . 'modules/product-deals/templates/'
		);
	}
}
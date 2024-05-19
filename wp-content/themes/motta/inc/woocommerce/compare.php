<?php
/**
 * Hooks of Compare.
 *
 * @package Motta
 */

namespace Motta\WooCommerce;

use \Motta\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of QuickView template.
 */
class Compare {
	/**
	 * Instance
	 *
	 * @var $instance
	 */
	protected static $instance = null;

	/**
	 * Initiator
	 *
	 * @since 1.0.0
	 * @return object
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
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
		add_filter( 'wcboost_products_compare_button_template_args', array( $this, 'products_compare_button_template_args' ), 10, 2 );
		add_filter( 'wcboost_products_compare_add_to_compare_fragments', array( $this, 'products_compare_add_to_compare_fragments' ), 10, 1 );

		add_filter('wcboost_products_compare_button_add_text', array( $this, 'compare_button_add_text' ) );
		add_filter('wcboost_products_compare_button_remove_text', array( $this, 'compare_button_remove_text' ) );
		add_filter('wcboost_products_compare_button_view_text', array( $this, 'compare_button_view_text' ) );

		add_filter('wcboost_products_compare_loop_add_to_compare_link', array( $this, 'add_to_compare_link' ), 20, 2 );
		add_filter('wcboost_products_compare_single_add_to_compare_link', array( $this, 'add_to_compare_link' ), 20, 2 );

		// Compare button.
		$compare = \WCBoost\ProductsCompare\Frontend::instance();
		remove_action( 'woocommerce_after_add_to_cart_form', [ $compare, 'single_add_to_compare_button' ] );
		remove_action( 'woocommerce_after_shop_loop_item', [ $compare, 'loop_add_to_compare_button' ], 15 );

	}


	/**
	 * Show button compare.
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function products_compare_button_template_args( $args, $product ) {
		$args['class'][] = 'motta-button motta-button--text';

		switch ( get_option( 'wcboost_products_compare_exists_item_button_behaviour', 'remove' ) ) {
			case 'remove':
				$args['class'][] = 'motta-button-compare--remove';
				break;

			case 'view':
				$args['class'][] = 'motta-button-compare--view';
				break;

			case 'popup':
				$args['class'][] = 'motta-button-compare--view';
				break;
		}

		return $args;
	}

	/**
	 * Ajaxify update count compare
	 *
	 * @since 1.0
	 *
	 * @param array $fragments
	 *
	 * @return array
	 */
	public static function products_compare_add_to_compare_fragments( $data ) {
		$compare_counter = intval(\WCBoost\ProductsCompare\Plugin::instance()->list->count_items());
		$compare_class = $compare_counter == 0 ? ' hidden' : '';
		$data['.header-compare .header-compare__counter'] = '<span class="header-counter header-compare__counter' . $compare_class . '">'. $compare_counter . '</span>';
		$data['.motta-mobile-navigation-bar__icon .compare-counter'] = '<span class="counter compare-counter' . $compare_class . '">'. $compare_counter . '</span>';

		return $data;
	}


	/**
	 * Update compare text
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function compare_button_add_text() {
		return esc_html__('Compare', 'motta');
	}

	/**
	 * Update compare text
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function compare_button_remove_text() {
		return esc_html__('Remove', 'motta');
	}

	/**
	 * Update compare text
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function compare_button_view_text() {
		return esc_html__('Browse', 'motta');
	}


	/**
	 * Change compare link
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_to_compare_link($html, $args) {
		global $product;
		return sprintf(
			'<a href="%s" data-product_id="%d" class="%s" aria-label="%s" role="button">
				%s
				<span class="wcboost-products-compare-button__text" data-add="%s" data-remove="%s" data-view="%s">%s</span>
			</a>',
			esc_url( isset( $args['url'] ) ? $args['url'] : add_query_arg( [ 'add-to-compare' => $product->get_id() ] ) ),
			esc_attr( isset( $args['product_id'] ) ? $args['product_id'] : $product->get_id() ),
			esc_attr( isset( $args['class'] ) ? $args['class'] : 'wcboost-products-compare-button wcboost-products-compare-button--loop motta-button motta-button--text' ),
			esc_attr( isset( $args['aria-label'] ) ? $args['aria-label'] : sprintf( __( 'Compare %s', 'motta' ), '&ldquo;' . $product->get_title() . '&rdquo;' ) ),
			'<span class="wcboost-products-compare-button__icon">' . \Motta\Icon::get_svg( 'compare' ) . '</span>',
			esc_attr__('Add To Compare', 'motta'),
			esc_attr__('Remove Compare', 'motta'),
			esc_attr__('Browse Compare', 'motta'),
			esc_html( isset( $args['label'] ) ? $args['label'] : __( 'Compare', 'motta' ) )
		);
	}

}

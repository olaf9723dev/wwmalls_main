<?php
namespace Motta\Addons\Elementor\Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Motta\Addons\Elementor\Utils;

abstract class Products_Widget_Base extends Widget_Base {
	/**
	 * Register controls for products query
	 *
	 * @param array $controls
	 */
	protected function register_products_controls( $controls = [] ) {
		$supported_controls = [
			'limit'    				=> 10,
			'type'     				=> 'recent_products',
			'ids'    				=> '',
			'category' 				=> '',
			'tag'      				=> '',
			'brand'    				=> '',
			'orderby'  				=> '',
			'order'    				=> '',
			'product_outofstock'    => 'yes',
		];

		$controls = 'all' == $controls ? $supported_controls : $controls;

		foreach ( $controls as $option => $default ) {
			switch ( $option ) {
				case 'limit':
					$this->add_control(
						'limit',
						[
							'label'     => __( 'Number of Products', 'motta-addons' ),
							'type'      => Controls_Manager::NUMBER,
							'min'       => -1,
							'max'       => 100,
							'step'      => 1,
							'default'   => $default,
						]
					);
					break;

				case 'type':
					$this->add_control(
						'type',
						[
							'label' => __( 'Type', 'motta-addons' ),
							'type' => Controls_Manager::SELECT,
							'options' => $this->get_options_product_type(),
							'default' => $default,
						]
					);
					break;

				case 'product_outofstock':
					$this->add_control(
						'product_outofstock',
						[
							'label'        => esc_html__( 'Show Out Of Stock Products', 'motta-addons' ),
							'type'         => Controls_Manager::SWITCHER,
							'label_on'     => esc_html__( 'Show', 'motta-addons' ),
							'label_off'    => esc_html__( 'Hide', 'motta-addons' ),
							'return_value' => 'yes',
							'default'      => $default,
						]
					);
					break;

				case 'category':
					$this->add_control(
						'category',
						[
							'label' => __( 'Product Category', 'motta-addons' ),
							'type' => 'motta-autocomplete',
							'default' => $default,
							'multiple'    => true,
							'source'      => 'product_cat',
							'sortable'    => true,
							'label_block' => true,
						]
					);
					break;

				case 'tag':
					$this->add_control(
						'tag',
						[
							'label' => __( 'Product Tag', 'motta-addons' ),
							'type' => Controls_Manager::SELECT2,
							'type' => 'motta-autocomplete',
							'default' => $default,
							'multiple'    => true,
							'source'      => 'product_tag',
							'sortable'    => true,
							'label_block' => true,
						]
					);
					break;

				case 'brand':
					$this->add_control(
						'brand',
						[
							'label' => __( 'Product Brand', 'motta-addons' ),
							'type' => Controls_Manager::SELECT2,
							'type' => 'motta-autocomplete',
							'default' => $default,
							'multiple'    => true,
							'source'      => 'product_brand',
							'sortable'    => true,
							'label_block' => true,
						]
					);
					break;

				case 'ids':
					$this->add_control(
						'ids',
						[
							'label' => __( 'Products', 'motta-addons' ),
							'type' => 'motta-autocomplete',
							'default' => $default,
							'multiple'    => true,
							'source'      => 'product',
							'sortable'    => true,
							'label_block' => true,
							'condition' => [
								'type' => ['custom_products']
							],
						]
					);
					break;

				case 'orderby':
					$this->add_control(
						'orderby',
						[
							'label' => __( 'Order By', 'motta-addons' ),
							'type' => Controls_Manager::SELECT,
							'options' => $this->get_options_product_orderby(),
							'default' => $default,
							'condition' => [
								'type' => ['featured_products', 'sale_products']
							],
						]
					);
					break;

				case 'order':
					$this->add_control(
						'order',
						[
							'label' => __( 'Order', 'motta-addons' ),
							'type' => Controls_Manager::SELECT,
							'options' => [
								'ASC'  => __( 'Ascending', 'motta-addons' ),
								'DESC' => __( 'Descending', 'motta-addons' ),
							],
							'default' => $default,
							'condition' => [
								'type' => ['featured_products', 'sale_products'],
								'orderby!' => ['', 'rand'],
							],
						]
					);
					break;
			}
		}
	}

	/**
	 * Get products loop content for shortcode.
	 *
	 * @param array $settings Shortcode attributes
	 * @return string
	 */
	protected function get_products_loop_content( $settings = false ) {
		global $motta_soldbar;
		$motta_soldbar = false;

		$output = [];
		$settings  = $this->parse_settings( $settings );
		$shortcode = new \WC_Shortcode_Products( $settings, $settings['type'] );

		$output[] = $shortcode->get_content();
		$output[] = Utils::get_pagination( $settings );

		return implode( '', $output );
	}

	/**
	 * Render products loop content for shortcode.
	 *
	 * @param array $settings Shortcode attributes
	 */
	protected function render_products( $settings = false ) {
		return $this->get_products_loop_content( $settings );
	}

	/**
	 * Parase shortcode attributes
	 *
	 * @param array $settings
	 * @return array
	 */
	protected function parse_settings( $settings = false ) {
		$settings = $settings ? $settings : $this->get_settings_for_display();

		// Ensure the product type is correct.
		$type  = ! empty( $settings['type'] ) && $settings['type'] !== 'brand' ? $settings['type'] : 'recent_products';

		if( in_array( $type, array( 'day', 'week', 'month' ) ) ) {
			$settings['type'] = $type;
		} else {
			$types = $this->get_options_product_type();
			$type  = isset( $settings['type'] ) && array_key_exists( $settings['type'], $types ) ? $settings['type'] : 'recent_products';
			$settings['type'] = $type;
		}

		switch ( $type ) {
			case 'recent_products':
				$settings['order']        = 'DESC';
				$settings['orderby']      = 'date';
				$settings['cat_operator'] = 'IN';
				break;

			case 'top_rated_products':
				$settings['orderby']      = 'title';
				$settings['order']        = 'ASC';
				$settings['cat_operator'] = 'IN';
				break;

			case 'sale_products':
			case 'best_selling_products':
				$settings['cat_operator'] = 'IN';
				break;

			case 'featured_products':
				$settings['cat_operator'] = 'IN';
				$settings['visibility']   = 'featured';
				break;

			case 'custom_products':
				$settings['orderby']      = 'post__in';
				break;

			case 'product':
				$settings['skus']  = isset( $settings['sku'] ) ? $settings['sku'] : '';
				$settings['ids']   = isset( $settings['id'] ) ? $settings['id'] : '';
				$settings['limit'] = '1';
				break;
		}

		if( ! empty( $settings['slides_to_show'] ) ) {
			$settings['columns'] = $settings['slides_to_show'];
		}

		// Use the default product order setting.
		if ( empty( $settings['orderby'] ) ) {
			$orderby_value = apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) );
			$orderby_value = is_array( $orderby_value ) ? $orderby_value : explode( '-', $orderby_value );
			$orderby       = esc_attr( $orderby_value[0] );
			$order         = ! empty( $orderby_value[1] ) ? $orderby_value[1] : 'DESC';

			if ( in_array( $orderby, array( 'menu_order', 'price' ) ) ) {
				$order = 'ASC';
			}

			$settings['orderby'] = strtolower( $orderby );
			$settings['order'] = strtoupper( $order );
		}

		// Convert category list to string.
		if ( ! empty( $settings['category'] ) && is_array( $settings['category'] ) ) {
			$settings['category'] = implode( ',', $settings['category'] );
		}

		// Convert tag list to string.
		if ( ! empty( $settings['tag'] ) && is_array( $settings['tag'] ) ) {
			$settings['tag'] = implode( ',', $settings['tag'] );
		}

		$settings['class'] = '';

		// Convert brand list to string.
		if ( ! empty( $settings['brand'] ) && is_array( $settings['brand'] ) ) {
			$settings['class'] = 'sc_brand,' . implode( ',', $settings['brand'] );
		}


		if ( empty( $settings['product_outofstock'] ) ) {
			$settings['class'] .= empty( $settings['class'] ) ? '' : ' ,';
			$settings['class'] .= 'sc_outofstock';
		}

		// Remove Elementor's ID keys.
		if ( isset( $settings['_id'] ) ) {
			unset( $settings['_id'] );
		}

		return $settings;
	}

	/**
	 * Get all available orderby options.
	 *
	 * @return array
	 */
	protected function get_options_product_orderby() {
		return [
			''           => __( 'Default', 'motta-addons' ),
			'menu_order' => __( 'Menu Order', 'motta-addons' ),
			'date'       => __( 'Date', 'motta-addons' ),
			'id'         => __( 'Product ID', 'motta-addons' ),
			'title'      => __( 'Product Title', 'motta-addons' ),
			'rand'       => __( 'Random', 'motta-addons' ),
			'price'      => __( 'Price', 'motta-addons' ),
			'popularity' => __( 'Popularity (Sales)', 'motta-addons' ),
			'rating'     => __( 'Rating', 'motta-addons' ),
		];
	}

	/**
	 * Get all supported product type options.
	 *
	 * @return array
	 */
	protected function get_options_product_type() {
		return [
			'recent_products'       => __( 'Recent Products', 'motta-addons' ),
			'featured_products'     => __( 'Featured Products', 'motta-addons' ),
			'sale_products'         => __( 'Sale Products', 'motta-addons' ),
			'best_selling_products' => __( 'Best Selling Products', 'motta-addons' ),
			'top_rated_products'    => __( 'Top Rated Products', 'motta-addons' ),
			'custom_products'    => __( 'Custom Products', 'motta-addons' ),
		];
	}
}
<?php

namespace Motta\Addons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Motta\Addons\Elementor\Base\Products_Widget_Base;
use Motta\Addons\Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Product Deals Grid widget
 */
class Product_Deals_Grid extends Products_Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-product-deals-grid';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( '[Motta] Product Deals Grid', 'motta-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'motta-addons' ];
	}

	public function get_script_depends() {
		return [
			'motta-coundown',
			'motta-elementor-widgets'
		];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_controls() {
		$this->section_content();
		$this->section_style();
	}

	// Tab Content
	protected function section_content() {
		$this->section_products_settings_controls();
		$this->section_pagination_settings_controls();
	}

	// Tab Style
	protected function section_style() {
		$this->section_product_style_controls();
	}

	protected function section_products_settings_controls() {
		$this->start_controls_section(
			'section_products',
			[ 'label' => esc_html__( 'Products', 'motta-addons' ) ]
		);

		$this->add_control(
			'products_divider',
			[
				'label' => esc_html__( 'Products', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'limit',
			[
				'label'   => esc_html__( 'Total Products', 'motta-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 16,
				'min'     => 1,
				'max'     => 50,
				'step'    => 1,
			]
		);

		$this->add_control(
			'columns',
			[
				'label'     => esc_html__( 'Column', 'motta-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'1' => esc_html__( '1', 'motta-addons' ),
					'2' => esc_html__( '2', 'motta-addons' ),
					'3' => esc_html__( '3', 'motta-addons' ),
					'4' => esc_html__( '4', 'motta-addons' ),
				],
				'default'   => '4',
			]
		);

		$this->add_control(
			'type',
			[
				'label'     => esc_html__( 'Products', 'motta-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'day'   => esc_html__( 'Deals of the day', 'motta-addons' ),
					'week'  => esc_html__( 'Deals of the week', 'motta-addons' ),
					'month' => esc_html__( 'Deals of the month', 'motta-addons' ),
					'sale' => esc_html__( 'On Sale', 'motta-addons' ),
					'deals' => esc_html__( 'Product Deals', 'motta-addons' ),
					'recent' => esc_html__( 'Recent Products', 'motta-addons' ),
				],
				'default'   => 'day',
				'toggle'    => false,
				'prefix_class' => 'motta-product-deals__type-',
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'     => esc_html__( 'Order By', 'motta-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'menu_order' => __( 'Menu Order', 'motta-addons' ),
					'date'       => __( 'Date', 'motta-addons' ),
					'title'      => __( 'Title', 'motta-addons' ),
					'price'      => __( 'Price', 'motta-addons' ),
				],
				'default'   => 'menu_order',
			]
		);

		$this->add_control(
			'order',
			[
				'label'     => esc_html__( 'Order', 'motta-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''     => esc_html__( 'Default', 'motta-addons' ),
					'asc'  => esc_html__( 'Ascending', 'motta-addons' ),
					'desc' => esc_html__( 'Descending', 'motta-addons' ),
				],
				'default'   => '',
			]
		);

		$this->add_control(
			'product_outofstock',
			[
				'label'        => esc_html__( 'Show Out Of Stock Products', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'motta-addons' ),
				'label_off'    => esc_html__( 'Hide', 'motta-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'product_cat',
			[
				'label'       => esc_html__( 'Product Categories', 'motta-addons' ),
				'placeholder' => esc_html__( 'Click here and start typing...', 'motta-addons' ),
				'type'        => 'motta-autocomplete',
				'default'     => '',
				'label_block' => true,
				'multiple'    => true,
				'source'      => 'product_cat',
				'sortable'    => true,

			]
		);

		$this->add_control(
			'product_tag',
			[
				'label'       => esc_html__( 'Product Tags', 'motta-addons' ),
				'placeholder' => esc_html__( 'Click here and start typing...', 'motta-addons' ),
				'type'        => 'motta-autocomplete',
				'default'     => '',
				'label_block' => true,
				'multiple'    => true,
				'source'      => 'product_tag',
				'sortable'    => true,

			]
		);

		$this->add_control(
			'product_brand',
			[
				'label'       => esc_html__( 'Product Brands', 'motta-addons' ),
				'placeholder' => esc_html__( 'Click here and start typing...', 'motta-addons' ),
				'type'        => 'motta-autocomplete',
				'default'     => '',
				'label_block' => true,
				'multiple'    => true,
				'source'      => 'product_brand',
				'sortable'    => true,

			]
		);

		$this->end_controls_section();
	}

	protected function section_pagination_settings_controls() {
		$this->start_controls_section(
			'section_pagination_settings',
			[ 'label' => esc_html__( 'Pagination', 'motta-addons' ) ]
		);

		$this->add_control(
			'pagination',
			[
				'label'   => esc_html__( 'Pagination', 'motta-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'none'     => esc_html__( 'None', 'motta-addons' ),
					'numberic' => esc_html__( 'Numberic', 'motta-addons' ),
				],
				'default'            => 'none',
			]
		);

		$this->end_controls_section();
	}

	protected function section_product_style_controls() {
		$this->start_controls_section(
			'section_style_product',
			[
				'label' => esc_html__( 'Product', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'hide_progress_bar',
			[
				'label'        => esc_html__( 'Hide Progress Bar', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => __( 'Off', 'motta-addons' ),
				'label_on'     => __( 'On', 'motta-addons' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render icon box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$classes = [
			'motta-product-deals-grid motta-product-deals',
		];

		$attr = [
			'type' 			=> $settings['type'],
			'orderby'  			=> $settings['orderby'],
			'order'    			=> $settings['order'],
			'category'    		=> $settings['product_cat'],
			'tag'    			=> $settings['product_tag'],
			'product_brands'    => $settings['product_brand'],
			'limit'    			=> $settings['limit'],
			'columns'    		=> $settings['columns'],
			'paginate'			=> true,
		];

		if ( isset( $settings['product_outofstock'] ) && empty( $settings['product_outofstock'] ) ) {
			$attr['product_outofstock'] = $settings['product_outofstock'];
		}

		$results = Utils::products_shortcode( $attr );
		if ( ! $results ) {
			return;
		}

		$results_ids = ! empty($results['ids']) ? $results['ids'] : 0;

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		echo '<div ' . $this->get_render_attribute_string( 'wrapper' ) .'>';

		wc_setup_loop(
			array(
				'columns'      => $settings['columns']
			)
		);

		Utils::get_template_loop( $results_ids );

		echo '</div>';
		if ( $settings['pagination'] == 'numberic' ) {
			self::get_pagination( $results['total_pages'], $results['current_page'] );
		}
	}

	/**
	 * Products pagination.
	 */
	public static function get_pagination( $total_pages, $current_page ) {
		echo '<nav class="woocommerce-pagination">';
		echo paginate_links(
			array( // WPCS: XSS ok.
				'base'      => esc_url_raw( add_query_arg( 'product-page', '%#%', false ) ),
				'format'    => '?product-page=%#%',
				'add_args'  => false,
				'current'   => max( 1, $current_page ),
				'total'     => $total_pages,
				'prev_text' => \Motta\Addons\Helper::get_svg( 'left', 'ui', 'class=motta-pagination__arrow' ),
				'next_text' =>\Motta\Addons\Helper::get_svg( 'right', 'ui', 'class=motta-pagination__arrow' ),
				'type'      => 'list',
			)
		);
		echo '</nav>';
	}


}
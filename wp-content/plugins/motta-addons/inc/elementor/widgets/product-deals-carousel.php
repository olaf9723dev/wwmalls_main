<?php

namespace Motta\Addons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Motta\Addons\Elementor\Base\Products_Widget_Base;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Motta\Addons\Helper;

use Motta\Addons\Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Product Deals Carousel widget
 */
class Product_Deals_Carousel extends Products_Widget_Base {
	use \Motta\Addons\Elementor\Widgets\Traits\Button_Trait;

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-product-deals';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( '[Motta] Product Deals Carousel', 'motta-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-countdown';
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
		$this->section_content_settings_controls();
		$this->section_products_settings_controls();
		$this->section_carousel_settings_controls();
	}

	// Tab Style
	protected function section_style() {
		$this->section_content_style_controls();
		$this->section_product_style_controls();
		$this->section_carousel_style_controls();
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
				'default' => 10,
				'min'     => 1,
				'max'     => 50,
				'step'    => 1,
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

		$this->add_control(
			'hide_vendor',
			[
				'label'     => esc_html__( 'Hide Vendor Name', 'motta-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Show', 'motta-addons' ),
				'label_on'  => __( 'Hide', 'motta-addons' ),
				'return_value' => 'none',
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} ul.products li.product .sold-by-meta' => 'display: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function section_content_settings_controls() {
		$this->start_controls_section(
			'section_content_settings',
			[ 'label' => esc_html__( 'Heading', 'motta-addons' ) ]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'motta-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'Enter your title', 'motta-addons' ),
				'default' => __( 'This is Title', 'motta-addons' ),
			]
		);

		$this->add_control(
			'after_title',
			[
				'label' => __( 'After Title', 'motta-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '',
			]
		);

		$this->add_control(
			'sale_text',
			[
				'label' => __( 'Sale Text', 'motta-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'Ends in', 'motta-addons' ),
			]
		);

		$controls = [
			'button_text_label' => __( 'Button Text', 'motta-addons' ),
			'button_text_label_mobile'     => __( 'Button Text Mobile', 'motta-addons'),
		];

		$this->register_button_content_controls( $controls );

		$this->end_controls_section();
	}

	protected function section_carousel_settings_controls() {
		$this->start_controls_section(
			'section_carousel_settings',
			[ 'label' => esc_html__( 'Carousel Settings', 'motta-addons' ) ]
		);

		$this->add_responsive_control(
			'slides_to_show',
			[
				'label'              => esc_html__( 'Slides to show', 'motta-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 1,
				'max'                => 10,
				'default'            => 5,
				'frontend_available' => true,
				'separator'          => 'before',
			]
		);

		$this->add_responsive_control(
			'slides_to_scroll',
			[
				'label'              => esc_html__( 'Slides to scroll', 'motta-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 1,
				'max'                => 10,
				'default'            => 5,
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'navigation',
			[
				'label'   => esc_html__( 'Navigation', 'motta-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'arrows'    => esc_html__( 'Arrows', 'motta-addons' ),
					'dots'      => esc_html__( 'Dots', 'motta-addons' ),
					'scrollbar' => esc_html__( 'Scrollbar', 'motta-addons' ),
					'none'      => esc_html__( 'None', 'motta-addons' ),
				],
				'default'            => 'arrows',
				'toggle'             => false,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'infinite',
			[
				'label'     => __( 'Infinite Loop', 'motta-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'motta-addons' ),
				'label_on'  => __( 'On', 'motta-addons' ),
				'default'   => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'     => __( 'Autoplay', 'motta-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'motta-addons' ),
				'label_on'  => __( 'On', 'motta-addons' ),
				'default'   => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'speed',
			[
				'label'       => __( 'Speed', 'motta-addons' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 2000,
				'min'         => 100,
				'step'        => 100,
				'description' => esc_html__( 'Slide animation speed (in ms)', 'motta-addons' ),
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function section_content_style_controls() {
		// Content Style
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Heading', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_position',
			[
				'label' => esc_html__( 'Heading Position', 'motta-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'top',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'motta-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'top' => [
						'title' => esc_html__( 'Top', 'motta-addons' ),
						'icon' => 'eicon-v-align-top',
					],
				],
				'prefix_class' => 'motta-product-deals-position--',
				'toggle' => false,
			]
		);

		$this->add_responsive_control(
			'heading_width',
			[
				'label'     => esc_html__( 'Width', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'     => [
					'px' => [
						'min' => 100,
						'max' => 600,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.motta-product-deals-position--left .motta-product-deals__content' => 'width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.motta-product-deals-position--left .motta-product-deals__products' => 'width: calc( 100% - {{SIZE}}{{UNIT}} ); min-width: calc( 100% - {{SIZE}}{{UNIT}} )',
				],
				'condition' => [
					'heading_position' => 'left'
				]
			]
		);

		$this->add_responsive_control(
			'heading_padding',
			[
				'label'      => esc_html__( 'Padding', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}}.motta-product-deals-position--left .motta-product-deals__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'heading_position' => 'left'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'heading_border',
				'label' => esc_html__( 'Border', 'motta-addons' ),
				'selector' => '{{WRAPPER}}.motta-product-deals-position--left .motta-product-deals__content',
				'condition' => [
					'heading_position' => 'left'
				]
			]
		);

		$this->add_control(
			'heading_title',
			[
				'label'     => esc_html__( 'Title', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-product-deals__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .motta-product-deals__title',
			]
		);

		$this->add_control(
			'heading_after_title',
			[
				'label'     => esc_html__( 'After Title', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'after_title_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-product-deals__aftertitle' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'after_title_typography',
				'selector' => '{{WRAPPER}} .motta-product-deals__aftertitle',
			]
		);

		$this->add_control(
			'heading_sale_text',
			[
				'label'     => esc_html__( 'Sale text', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'sale_text_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-product-deals__sale-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sale_text_typography',
				'selector' => '{{WRAPPER}} .motta-product-deals__sale-text',
			]
		);

		$this->add_control(
			'heading_time',
			[
				'label'     => esc_html__( 'Time', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'time_bg_color',
			[
				'label' => __( 'Background Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-countdown .digits' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'time_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-countdown .digits' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'time_text_color',
			[
				'label' => __( 'Text Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-countdown .text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_button',
			[
				'label'     => esc_html__( 'Button', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$controls = [
			'skin'     => 'subtle',
			'size'      => 'medium',
		];

		$this->register_button_style_controls($controls);

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
				'prefix_class' => 'motta-product-deals__hide-progress-bar-',
			]
		);

		$this->end_controls_section();
	}

	protected function section_carousel_style_controls() {
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Carousel', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Arrows
		$this->add_control(
			'arrow_style_heading',
			[
				'label' => esc_html__( 'Arrows', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sliders_arrow_style',
			[
				'label'        => __( 'Option', 'motta-addons' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'motta-addons' ),
				'label_on'     => __( 'Custom', 'motta-addons' ),
				'return_value' => 'yes',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'sliders_arrows_size',
			[
				'label'     => __( 'Size', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-product-deals .motta-swiper-button' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sliders_arrows_width',
			[
				'label'     => __( 'Width', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
						'min' => 0,
					],
				],
				'default' => [ 'size' => 44, 'unit' => 'px' ],
				'selectors' => [
					'{{WRAPPER}} .motta-product-deals .motta-swiper-button' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sliders_arrows_height',
			[
				'label'     => __( 'Height', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-product-deals .motta-swiper-button' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrows_spacing_horizontal',
			[
				'label'      => esc_html__( 'Horizontal Position', 'motta-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => -100,
						'max' => 1000,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-product-deals .motta-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} .motta-product-deals .motta-swiper-button-next' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
					'{{WRAPPER}} .motta-product-deals .motta-swiper-button-prev' => 'left: calc( 220px + {{SIZE}}{{UNIT}} );',
					'.rtl {{WRAPPER}} .motta-product-deals .motta-swiper-button-prev' => 'right: calc( 220px + {{SIZE}}{{UNIT}} ); left: auto;',
				],
			]
		);

		$this->add_responsive_control(
			'arrows_spacing_vertical',
			[
				'label'      => esc_html__( 'Vertical Position', 'motta-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => -100,
						'max' => 1000,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-product-deals .motta-swiper-button-prev' => 'top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .motta-product-deals .motta-swiper-button-next' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_popover();

		$this->add_control(
			'sliders_arrow_color',
			[
				'label'     => esc_html__( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-product-deals .motta-swiper-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sliders_arrow_bgcolor',
			[
				'label'     => esc_html__( 'Background Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-product-deals .motta-swiper-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'sliders_arrow_box_shadow',
				'label' => __( 'Box Shadow', 'motta-addons' ),
				'selector' => '{{WRAPPER}} .motta-swiper-button',
			]
		);

		// Dots
		$this->add_control(
			'dots_style_heading',
			[
				'label' => esc_html__( 'Dots', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sliders_dots_style',
			[
				'label'        => __( 'Option', 'motta-addons' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'motta-addons' ),
				'label_on'     => __( 'Custom', 'motta-addons' ),
				'return_value' => 'yes',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'sliders_dots_gap',
			[
				'label'     => __( 'Gap', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-product-deals .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'sliders_dots_size',
			[
				'label'     => __( 'Size', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-product-deals .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sliders_dots_offset_ver',
			[
				'label'     => esc_html__( 'Spacing Top', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 1000,
						'min' => -100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-product-deals .swiper-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_popover();

		$this->add_control(
			'sliders_dots_bgcolor',
			[
				'label'     => esc_html__( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-product-deals .swiper-pagination-bullet' => 'background-color : {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'sliders_dots_ac_bgcolor',
			[
				'label'     => esc_html__( 'Color Active', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-product-deals .swiper-pagination-bullet-active' => 'background-color : {{VALUE}};',
				],
			]
		);

		// Scrollbar
		$this->add_control(
			'scrollbar_style_heading',
			[
				'label' => esc_html__( 'Scrollbar', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'scrollbar_color',
			[
				'label'     => esc_html__( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-product-deals .swiper-scrollbar' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'scrollbar_active_color',
			[
				'label'     => esc_html__( 'Active Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-product-deals .swiper-scrollbar-drag' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'scrollbar_spacing',
			[
				'label'     => __( 'Spacing', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 150,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-product-deals .swiper-scrollbar' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
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

		$nav        = $settings['navigation'];
		$nav_tablet = empty( $settings['navigation_tablet'] ) ? $nav : $settings['navigation_tablet'];
		$nav_mobile = empty( $settings['navigation_mobile'] ) ? $nav : $settings['navigation_mobile'];

		$classes = [
			'motta-product-deals motta-swiper-carousel-elementor motta-product-carousel',
			'navigation-' . $nav,
			'navigation-tablet-' . $nav_tablet,
			'navigation-mobile-' . $nav_mobile,
		];

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		if ( ! empty( $settings['primary_mobile_button_text'] ) ) {
			$this->add_render_attribute( 'wrapper', 'class', [ 'motta-product-deals__button-mobile-on' ] );
		}

		echo '<div ' . $this->get_render_attribute_string( 'wrapper' ) .'>';

		$sale_text = ! empty( $settings['sale_text'] ) ? '<div class="motta-product-deals__sale-text">' . $settings['sale_text'] . '</div>' : '';

		//time
		$dataText = $this->get_countdown_texts();

		$now         = strtotime( current_time( 'Y-m-d H:i:s' ) );
		$expire_date = strtotime( '00:00 +1 day', $now );
		if ( $settings['type'] == 'week' ) {
			$expire_date = strtotime( '00:00 next monday', $now );
		} elseif ( $settings['type'] == 'month' ) {
			$expire_date = strtotime( '00:00 first day of next month', $now );
		}
		$expire            = $expire_date - $now;


		$this->add_render_attribute( 'countdown', 'data-expire', $expire );
		$this->add_render_attribute( 'countdown', 'data-text', wp_json_encode( $dataText ) );

		$countdown = sprintf( '<div class="motta-product-deals__countdown" >' . $sale_text . '<div class="motta-countdown" %s></div></div>', $this->get_render_attribute_string( 'countdown' ) ) ;

		$output_pagination =  \Motta\Addons\Helper::get_svg( 'left', 'ui' , [ 'class' => 'motta-swiper-button-prev swiper-button motta-swiper-button' ]  );
		$output_pagination .=  \Motta\Addons\Helper::get_svg( 'right', 'ui' , [ 'class' => 'motta-swiper-button-next swiper-button motta-swiper-button' ] );
		$output_pagination .= '<div class="motta-swiper-carousel__paginations swiper-pagination"></div>';

		if ( ! empty( $settings['title'] ) || ! empty( $settings['after_title'] ) || ! empty( $countdown ) ||  ! empty( $settings['primary_button_text'] ) ||  ! empty( $settings['primary_mobile_button_text'] ) ){
			?>
			<div class="motta-product-deals__content">
				<?php echo ! empty( $settings['title'] ) ? '<div class="motta-product-deals__title">' . $settings['title'] . '</div>' : ''; ?>
				<?php echo ! empty( $settings['after_title'] ) ? '<div class="motta-product-deals__aftertitle">' . $settings['after_title'] . '</div>' : ''; ?>
				<div class="motta-product-deals__group-heading">
					<?php echo $countdown; ?>
					<span class="motta-product-deals__button">
						<?php $this->render_button(); ?>
					</span>
				</div>
			</div>
			<?php
		}

		$atts = array(
			'type'           => isset( $settings['type'] ) ?  $settings['type']  : '',
			'columns'        => isset( $settings['slides_to_show'] ) ? intval( $settings['slides_to_show'] ). ' swiper-wrapper': '',
			'category'       => isset( $settings['product_cat'] ) ? $settings['product_cat'] : '',
			'tag'            => isset( $settings['product_tag'] ) ? $settings['product_tag'] : '',
			'products'       => isset( $settings['products'] ) ? $settings['products'] : '',
			'order'          => isset( $settings['order'] ) ? $settings['order'] : '',
			'orderby'        => isset( $settings['orderby'] ) ? $settings['orderby'] : '',
			'per_page'       => isset( $settings['limit'] ) ? intval( $settings['limit'] ) : '',
			'product_brands' => isset( $settings['product_brand'] ) ? $settings['product_brand'] : '',
			'motta_soldbar'	 => isset( $settings['hide_progress_bar'] ) && $settings['hide_progress_bar'] == 'yes'  ?  false  : true,
		);

		if ( isset( $settings['product_outofstock'] ) && empty( $settings['product_outofstock'] ) ) {
			$atts['product_outofstock'] = $settings['product_outofstock'];
		}

		$product_ids = Utils::products_shortcode( $atts );

		$product_ids = ! empty($product_ids) ? $product_ids['ids'] : 0;

		if( ! $product_ids ) {
			return;
		}

		ob_start();

		wc_setup_loop(
			array(
				'columns' => $atts['columns']
			)
		);

		$this->get_template_loop( $product_ids );

		$products = ob_get_clean();

		echo sprintf(
			'<div class="motta-product-deals__products swiper"> %s </div> %s',
			$products,
			$output_pagination,
		);

		echo '</div>';
	}

	/**
	 * Loop over products
	 *
	 * @since 1.0.0
	 *
	 * @param string
	 */
	protected function get_template_loop( $products_ids, $template = 'product' ) {
		update_meta_cache( 'post', $products_ids );
		update_object_term_cache( $products_ids, 'product' );

		$original_post = $GLOBALS['post'];

		woocommerce_product_loop_start();

		foreach ( $products_ids as $product_id ) {
			$GLOBALS['post'] = get_post( $product_id ); // WPCS: override ok.
			setup_postdata( $GLOBALS['post'] );
			wc_get_template_part( 'content', $template );
		}

		$GLOBALS['post'] = $original_post; // WPCS: override ok.

		woocommerce_product_loop_end();

		wp_reset_postdata();
		wc_reset_loop();
	}

	/**
	 * Functions that used to get coutndown texts
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_countdown_texts() {
		return apply_filters( 'motta_get_countdown_texts', array(
			'weeks'    => esc_html__( 'Weeks', 'motta-addons' ),
			'days'    => esc_html__( 'Days', 'motta-addons' ),
			'hours'   => esc_html__( 'Hours', 'motta-addons' ),
			'minutes' => esc_html__( 'Mins', 'motta-addons' ),
			'seconds' => esc_html__( 'Secs', 'motta-addons' )
		) );
	}

}
<?php
namespace Motta\Addons\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Box_Shadow;

use \Motta\Addons\Helper;
use \Motta\Addons\Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Products Recently Viewed Carousel widget
 */
class Products_Recently_Viewed_Carousel extends Widget_Base {
	use \Motta\Addons\Elementor\Widgets\Traits\Button_Trait;

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-products-recently-viewed-carousel';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( '[Motta] Products Recently Viewed Carousel', 'motta-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-posts-carousel';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'motta-addons' ];
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
		$this->section_carousel_settings_controls();
	}

	// Tab Style
	protected function section_style() {
		$this->section_products_style_controls();
		$this->section_product_item_style();
		$this->section_carousel_style_controls();
	}

	protected function section_products_settings_controls() {
		$this->start_controls_section(
			'section_products',
			[ 'label' => esc_html__( 'Products', 'motta-addons' ) ]
		);

		$this->add_control(
			'limit',
			[
				'label'   => esc_html__( 'Limit', 'motta-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 8,
				'min'     => 2,
				'max'     => 50,
				'step'    => 1,
			]
		);

		$this->add_control(
			'load_ajax',
			[
				'label'        => __( 'Load With Ajax', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => __( 'Off', 'motta-addons' ),
				'label_on'     => __( 'On', 'motta-addons' ),
				'default'      => '',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'hide_empty',
			[
				'label'              => __( 'Hide Recently Viewed Empty', 'motta-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_off'          => __( 'Off', 'motta-addons' ),
				'label_on'           => __( 'On', 'motta-addons' ),
				'return_value'       => 'yes',
				'default'            => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'section_empty_heading',
			[
				'label'     => esc_html__( 'Empty Product', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'empty_product_description',
			[
				'label'       => esc_html__( 'Description', 'motta-addons' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Enter your text', 'motta-addons' ),
				'label_block' => true,
				'default'     => esc_html__( 'Recently Viewed Products is a function which helps you keep track of your recent viewing history.', 'motta-addons' ),
			]
		);

		$this->add_control(
			'empty_product_text',
			[
				'label'       => esc_html__( 'Button Text', 'motta-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your text', 'motta-addons' ),
				'label_block' => true,
				'default'     => esc_html__( 'Shop Now', 'motta-addons' ),
			]
		);

		$this->add_control(
			'empty_product_link',
			[
				'label'       => esc_html__( 'Button Link', 'motta-addons' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'Enter your link', 'motta-addons' ),
				'label_block' => true,
				'default'     => [
					'url'         => '#',
					'is_external' => false,
					'nofollow'    => false,
				],
			]
		);

		$this->add_control(
			'attributes_divider',
			[
				'label' => esc_html__( 'Attributes', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'hide_category',
			[
				'label'     => esc_html__( 'Hide Category', 'motta-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Show', 'motta-addons' ),
				'label_on'  => __( 'Hide', 'motta-addons' ),
				'return_value' => 'none',
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} ul.products li.product .meta-wrapper' => 'display: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hide_rating',
			[
				'label'     => esc_html__( 'Hide Rating', 'motta-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Show', 'motta-addons' ),
				'label_on'  => __( 'Hide', 'motta-addons' ),
				'return_value' => 'none',
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} ul.products li.product .motta-rating' => 'display: {{VALUE}}',
					'{{WRAPPER}} ul.products.product-card-layout-4 li.product .product-inner:hover .motta-rating + .product-actions' => 'transform: none;',
				],
			]
		);

		$this->add_control(
			'hide_attributes',
			[
				'label'     => esc_html__( 'Hide Attributes', 'motta-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Show', 'motta-addons' ),
				'label_on'  => __( 'Hide', 'motta-addons' ),
				'return_value' => 'none',
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} ul.products li.product .product-variation-items' => 'display: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hide_badge',
			[
				'label'     => esc_html__( 'Hide Badge', 'motta-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Show', 'motta-addons' ),
				'label_on'  => __( 'Hide', 'motta-addons' ),
				'return_value' => 'none',
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} ul.products li.product .woocommerce-badges' => 'display: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hide_featured_icons',
			[
				'label'     => esc_html__( 'Hide Featured Icons', 'motta-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Show', 'motta-addons' ),
				'label_on'  => __( 'Hide', 'motta-addons' ),
				'return_value' => 'none',
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} ul.products li.product .product-thumbnail .product-featured-icons' => 'display: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hide_buttons',
			[
				'label'     => esc_html__( 'Hide Featured Buttons', 'motta-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Show', 'motta-addons' ),
				'label_on'  => __( 'Hide', 'motta-addons' ),
				'return_value' => 'none',
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} ul.products:not(.product-card-layout-6) li.product .product-actions' => 'display: {{VALUE}}',
					'{{WRAPPER}} ul.products.product-card-layout-6 li.product .product-actions > a.button' => 'display: {{VALUE}}',
				],
				'prefix_class' => 'motta-product-carousel__hide-featured-buttons-',
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

		$this->section_content_heading();

		$this->end_controls_section();
	}

	protected function section_content_heading() {
		$this->add_control(
			'heading_block',
			[
				'label' => __( 'Heading Block', 'motta-addons' ),
				'description' => __( 'Add a heading block as the the first item', 'motta-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title',
			[
				'label'   => esc_html__( 'Title', 'motta-addons' ),
				'type'    => Controls_Manager::TEXT,
				'conditions' => [
					'terms' => [
						[
							'name'  => 'heading_block',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$controls = [
			'button_text_label' => __( 'Button Text', 'motta-addons' ),
			'button_text_label_mobile'     => __( 'Button Text Mobile', 'motta-addons'),
			'section_condition' => [
				'heading_block' => 'yes',
			]
		];

		$this->register_button_content_controls( $controls );
	}

	protected function section_product_item_style() {
		$this->start_controls_section(
			'section_product_item_style',
			[
				'label' => esc_html__( 'Product Item', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'product_featured_icons_spacing',
			[
				'label'      		=> esc_html__( 'Featured Icons Spacing', 'motta-addons' ),
				'type'      		=> Controls_Manager::DIMENSIONS,
				'size_units' 		=> [ 'px', 'em', '%' ],
				'allowed_dimensions' => [ 'top', 'right' ],
				'selectors'  		=> [
					'{{WRAPPER}} ul.products:not(.product-card-layout-2) li.product .product-thumbnail .product-featured-icons' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'product_badge_spacing',
			[
				'label'      => esc_html__( 'Badge Spacing', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
						'min' => 0,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} ul.products li.product .woocommerce-badges' => 'top: {{SIZE}}{{UNIT}}; left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'product_item_padding',
			[
				'label'      => esc_html__( 'Summary Spacing', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
						'min' => 0,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} ul.products li.product .product-summary' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'content_button_padding',
			[
				'label'      => esc_html__( 'Buttons Spacing', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
						'min' => 0,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} ul.products li.product .product-actions' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'thumbnail_arrows_horizontal_spacing',
			[
				'label'      => esc_html__( 'Thumbnail Arrows Horizontal Spacing', 'motta-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => -100,
						'max' => 1170,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} ul.products li.product .product-thumbnails--slider .motta-product-card-swiper-prev' => 'left: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} ul.products li.product .product-thumbnails--slider .motta-product-card-swiper-prev' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
					'{{WRAPPER}} ul.products li.product .product-thumbnails--slider .motta-product-card-swiper-next' => 'right: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} ul.products li.product .product-thumbnails--slider .motta-product-card-swiper-next' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
				],
			]
		);

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
				'label'   => esc_html__( 'Slides to show', 'motta-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'1'    => __( '1', 'motta-addons' ),
					'2'    => __( '2', 'motta-addons' ),
					'3'    => __( '3', 'motta-addons' ),
					'4'    => __( '4', 'motta-addons' ),
					'5'    => __( '5', 'motta-addons' ),
					'6'    => __( '6', 'motta-addons' ),
					'7'    => __( '7', 'motta-addons' ),
					'auto' => __( 'Auto', 'motta-addons' ),
				],
				'default'            => 5,
				'frontend_available' => true,
				'toggle'             => false,
				'separator'          => 'before',
			]
		);

		$this->add_responsive_control(
			'slides_width',
			[
				'label'     => __( 'Width', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'     => [
					'px' => [
						'max' => 1000,
						'min' => 0,
					],
					'%' => [
						'max' => 100,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-product-carousel ul.products li.product' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'slides_to_show' => [ 'auto' ],
				],
				'required' => true,
			]
		);

		$this->add_responsive_control(
			'slides_to_scroll',
			[
				'label'              => esc_html__( 'Slides to scroll', 'motta-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 1,
				'max'                => 10,
				'default'            => 1,
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'navigation',
			[
				'label'   => esc_html__( 'Navigation', 'motta-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'arrows'           => esc_html__( 'Arrows', 'motta-addons' ),
					'dots'             => esc_html__( 'Dots', 'motta-addons' ),
					'scrollbar'        => esc_html__( 'Scrollbar', 'motta-addons' ),
					'arrows-scrollbar' => esc_html__( 'Arrows & Scrollbar', 'motta-addons' ),
					'none'             => esc_html__( 'None', 'motta-addons' ),
				],
				'default'            => 'arrows',
				'toggle'             => false,
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
				'default'   => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label'   => __( 'Autoplay Speed', 'motta-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3000,
				'min'     => 100,
				'step'    => 100,
				'frontend_available' => true,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label'   => __( 'Pause on Hover', 'motta-addons' ),
				'type'    => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'motta-addons' ),
				'label_on'  => __( 'On', 'motta-addons' ),
				'default'   => 'yes',
				'frontend_available' => true,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'speed',
			[
				'label'       => __( 'Animation Speed', 'motta-addons' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 800,
				'min'         => 100,
				'step'        => 50,
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function section_products_style_controls() {
		$this->start_controls_section(
			'section_style_products',
			[
				'label' => esc_html__( 'Products', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'show_border',
			[
				'label'        => esc_html__( 'Border between products', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => __( 'Hide', 'motta-addons' ),
				'label_on'     => __( 'Show', 'motta-addons' ),
				'return_value' => 'yes',
				'default'      => '',
				'prefix_class' => 'motta-product-carousel__border-',
			]
		);

		$this->add_responsive_control(
			'border_height',
			[
				'label'     => __( 'Border Height', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}}.motta-product-carousel__border-yes ul.products li.product' => '--motta-product-carousel-border-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_border' => 'yes',
				],

			]
		);

		$this->add_responsive_control(
			'spaceBetween',
			[
				'label'     => __( 'Spacing between products', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} ul.products li.product' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .motta-product-carousel.motta-product-carousel__has-heading .swiper-container-initialized.woocommerce' => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}}; padding-left: 0; padding-right: 0;',
					'{{WRAPPER}} .motta-product-carousel.motta-product-carousel__has-heading .swiper-initialized.woocommerce' => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}}; padding-left: 0; padding-right: 0;',
					'{{WRAPPER}} .motta-product-carousel.motta-carousel-spacing-empty .swiper-container-initialized.woocommerce' => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .motta-product-carousel.motta-carousel-spacing-empty .swiper-initialized.woocommerce' => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',
				],

			]
		);

		$this->section_style_heading();

		$this->end_controls_section();

	}

	protected function section_style_heading() {
		$this->add_control(
			'style_heading',
			[
				'label' => esc_html__( 'Heading', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'heading_block' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'      => esc_html__( 'Color', 'motta-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .motta-products-recently-viewed__title' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'heading_block',
							'value' => 'yes',
						],
					]
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .motta-products-recently-viewed__title',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'heading_block',
							'value' => 'yes',
						],
					]
				]
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-products-recently-viewed__heading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'heading_block' => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_buton',
			[
				'label'     => esc_html__( 'Button', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
				'condition' => [
					'heading_block' => 'yes',
				],
			]
		);

		$controls = [
			'button_text_label' => __( 'Button Text', 'motta-addons' ),
			'size'      => 'medium',
			'skin'      => 'subtle',
			'section_condition' => [
				'heading_block' => 'yes',
			]
		];

		$this->register_button_style_controls($controls);
	}

	protected function section_carousel_style_controls() {
		$this->start_controls_section(
			'section_carousel_style',
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
					'{{WRAPPER}} .motta-product-carousel .motta-swiper-button' => 'font-size: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-product-carousel .motta-swiper-button' => 'width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-product-carousel .motta-swiper-button' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrows_spacing',
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
					'{{WRAPPER}} .motta-product-carousel .motta-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} .motta-product-carousel .motta-swiper-button-next' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
					'{{WRAPPER}} .motta-product-carousel .motta-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} .motta-product-carousel .motta-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
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
					'{{WRAPPER}} .motta-product-carousel .motta-swiper-button' => 'top: {{SIZE}}{{UNIT}}; transform: translateY(0);',
					'.rtl {{WRAPPER}} .motta-product-carousel .motta-swiper-button' => 'top: {{SIZE}}{{UNIT}}; transform: translateY(0) rotateY(180deg);',
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
					'{{WRAPPER}} .motta-product-carousel .motta-swiper-button' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .motta-product-carousel .motta-swiper-button' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .motta-product-carousel .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .motta-product-carousel .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sliders_dots_spacing_top',
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
					'{{WRAPPER}} .motta-product-carousel .swiper-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'sliders_dots_offset_ver',
			[
				'label'     => esc_html__( 'Position Y', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 1000,
						'min' => -100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-product-carousel .swiper-pagination' => 'bottom: {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .motta-product-carousel .swiper-pagination-bullet' => 'background-color : {{VALUE}};',
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
					'{{WRAPPER}} .motta-product-carousel .swiper-pagination-bullet-active' => 'background-color : {{VALUE}};',
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
					'{{WRAPPER}} .motta-product-carousel .swiper-scrollbar' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .motta-product-carousel .swiper-scrollbar-drag' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .motta-product-carousel .swiper-scrollbar' => 'margin-top: {{SIZE}}{{UNIT}};',
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
			'motta-products-recently-viewed-carousel',
		];

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		$products_class = [
			'motta-product-carousel',
			'motta-swiper-carousel-elementor',
			'navigation-' . $nav,
			'navigation-tablet-' . $nav_tablet,
			'navigation-mobile-' . $nav_mobile,
		];

		$heading_class[] = 'motta-products-recently-viewed__heading';
		$products_class[] = $settings['load_ajax'] ? 'has-ajax' : 'no-ajax';

		$product_ids     = Utils::get_product_recently_viewed_ids();

		if( empty( $product_ids ) ) {
			$heading_class[] = $settings['hide_empty'] ? 'hide-empty' : '';
			$products_class[] =  $settings['hide_empty'] ? 'hide-empty' : '';
		}

		$this->add_render_attribute( 'heading', 'class', $heading_class );

		$this->add_render_attribute( 'products_class', 'class', $products_class );

		$atts = array(
			'limit'       => isset( $settings['limit'] ) ? intval( $settings['limit'] ) : '',
			'desc'        => isset( $settings['empty_product_description'] ) ? $settings['empty_product_description'] : '',
			'button_text' => isset( $settings['empty_product_text'] ) ? $settings['empty_product_text'] : '',
			'button_link' => isset( $settings['empty_product_link'] ) ? $settings['empty_product_link'] : '',
			'load_ajax'   => isset( $settings['load_ajax'] ) ? $settings['load_ajax'] : '',
		);

		$this->add_render_attribute( 'products', 'data-settings', wp_json_encode( $atts ) );

		?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php if( ! empty( $settings['heading_block'] ) ) : ?>
				<div <?php echo $this->get_render_attribute_string( 'heading' ); ?>>
					<?php if( ! empty( $settings['title'] ) ) : ?>
						<div class="motta-products-recently-viewed__title"><?php echo $settings['title']; ?></div>
					<?php endif; ?>

					<?php $this->render_button(); ?>
				</div>
			<?php endif; ?>
            <div <?php echo $this->get_render_attribute_string( 'products_class' ); ?> <?php echo $this->get_render_attribute_string( 'products' ); ?>>
				<div class="motta-products-recently-viewed__products swiper-container woocommerce">
					<?php
					if ( empty( $settings['load_ajax'] ) ) {
						Utils::get_recently_viewed_products( $atts );
					} else {
						?>
						<div class="motta-posts__loading">
							<div class="motta-loading"></div>
						</div>
						<?php
					}
					?>
				</div>
				<?php
				if( ! empty($product_ids) ) { ?>
					<?php echo Helper::get_svg( 'left', 'ui' , [ 'class' => 'swiper-button motta-swiper-button-prev motta-swiper-button' ]  ); ?>
					<?php echo Helper::get_svg( 'right', 'ui' , [ 'class' => 'swiper-button motta-swiper-button-next motta-swiper-button' ] ); ?>
					<div class="swiper-pagination"></div>
				<?php } ?>
            </div>
        </div>
		<?php
	}
}

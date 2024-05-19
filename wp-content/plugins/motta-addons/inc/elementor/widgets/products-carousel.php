<?php
namespace Motta\Addons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Motta\Addons\Elementor\Base\Products_Widget_Base;
use Elementor\Controls_Stack;
use Elementor\Icons_Manager;
use Motta\Addons\Elementor;
use Motta\Addons\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Products Carousel
 */
class Products_Carousel extends Products_Widget_Base {
	use \Motta\Addons\Elementor\Widgets\Traits\Button_Trait;

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-products-carousel';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( '[Motta] Products Carousel', 'motta-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-carousel';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return ['motta-addons'];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'products carousel', 'products', 'carousel', 'woocommerce', 'motta-addons' ];
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
		$this->section_content_products();
		$this->section_content_carousel();
	}

	protected function section_content_products() {
		$this->start_controls_section(
			'section_products',
			[
				'label' => __( 'Products', 'motta-addons' ),
			]
		);

		$this->register_products_controls( 'all' );

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
					'{{WRAPPER}} ul.products.product-card-layout-3 li.product.swiper-slide-visible:before ' => 'height: calc( 100% - 25px );',
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
			'title_type',
			[
				'label'       => esc_html__( 'Title Type', 'motta-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'text'   => esc_html__( 'Text', 'motta-addons' ),
					'svg' 	 => esc_html__( 'SVG Icon', 'motta-addons' ),
					'image'  => esc_html__( 'Image', 'motta-addons' ),
					'external' 	=> esc_html__( 'External', 'motta-addons' ),
				],
				'default' => 'text',
				'condition' => [
					'heading_block' => 'yes',
				],
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
						[
							'name'  => 'title_type',
							'value' => 'text',
						],
					],
				],
			]
		);

		$this->add_control(
			'title_icon',
			[
				'label' => __( 'Title', 'motta-addons' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fa fa-star',
					'library' => 'fa-solid',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'heading_block',
							'value' => 'yes',
						],
						[
							'name'  => 'title_type',
							'value' => 'svg',
						],
					],
				],
			]
		);

		$this->add_control(
			'title_image',
			[
				'label' => __( 'Title', 'motta-addons' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'heading_block',
							'value' => 'yes',
						],
						[
							'name'  => 'title_type',
							'value' => 'image',
						],
					],
				],
			]
		);

		$this->add_control(
			'external_url',
			[
				'label' => esc_html__( 'External URL', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'title_type',
							'value' => 'external',
						],
					],
				],
			]
		);

		$this->add_control(
			'description',
			[
				'label'   => esc_html__( 'Description', 'motta-addons' ),
				'type'    => Controls_Manager::TEXTAREA,
				'condition' => [
					'heading_block' => 'yes',
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

	protected function section_content_carousel() {
		$this->start_controls_section(
			'section_products_carousel',
			[
				'label' => __( 'Carousel Settings', 'motta-addons' ),
			]
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
					'arrows-dots' => esc_html__( 'Arrows & Dots', 'motta-addons' ),
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

	// Tab Content
	protected function section_style() {
		$this->section_style_content();
		$this->section_style_carousel();
	}

	protected function section_style_content() {
		$this->start_controls_section(
			'section_style_content',
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

		$this->add_control(
			'heading_product_item',
			[
				'label' => __( 'Product Item', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'product_padding',
			[
				'label'      => esc_html__( 'Padding', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} ul.products li.product .product-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_border' => '',
				],
			]
		);

		$this->add_responsive_control(
			'product_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} ul.products li.product .product-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_border' => '',
				],
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
					'{{WRAPPER}} ul.products li.product .product-thumbnails--slider .motta-product-card-swiper-next' => 'right: {{SIZE}}{{UNIT}};',
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

		$this->add_responsive_control(
			'heading__text_align',
			[
				'label'       => esc_html__( 'Text Align', 'motta-addons' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'left'   => [
						'title' => esc_html__( 'Left', 'motta-addons' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'motta-addons' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'motta-addons' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'     => 'center',
				'selectors'   => [
					'{{WRAPPER}} .motta-product-carousel__heading' => 'text-align: {{VALUE}}',
				],
				'condition' => [
					'heading_block' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'heading_width',
			[
				'label'      => esc_html__( 'Max Width', 'motta-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1900,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-product-carousel__heading' => 'max-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .motta-product-carousel__has-heading > .swiper-container-initialized.woocommerce ' => 'max-width: calc( 100% - {{SIZE}}{{UNIT}} );',
					'{{WRAPPER}} .motta-product-carousel__has-heading > .swiper-initialized.woocommerce ' => 'max-width: calc( 100% - {{SIZE}}{{UNIT}} );',
					'{{WRAPPER}} .motta-product-carousel__has-heading > .motta-product-carousel__container ' => 'max-width: calc( 100% - {{SIZE}}{{UNIT}} );',
					'(mobile){{WRAPPER}} .motta-product-carousel__has-heading > .swiper-container-initialized.woocommerce ' => 'max-width: 100%;',
					'(mobile){{WRAPPER}} .motta-product-carousel__has-heading > .swiper-initialized.woocommerce ' => 'max-width: 100%;',
					'(mobile){{WRAPPER}} .motta-product-carousel__has-heading > .motta-product-carousel__container ' => 'max-width: 100%;',
				],
				'condition' => [
					'heading_block' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'heading_padding',
			[
				'label'      => esc_html__( 'Padding', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-product-carousel__heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.motta-rtl-smart {{WRAPPER}} .motta-product-carousel__heading' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				],
				'condition' => [
					'heading_block' => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_before_title',
			[
				'label'     => esc_html__( 'Title', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
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
					'{{WRAPPER}} .motta-product-carousel__heading-title' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'heading_block',
							'value' => 'yes',
						],
						[
							'name' => 'title_type',
							'operator' => '===',
							'value' => 'text'
						],
					]
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .motta-product-carousel__heading-title--text, {{WRAPPER}} .motta-product-carousel__heading-title--icon .motta-svg-icon',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'heading_block',
							'value' => 'yes',
						],
						[
							'name' => 'title_type',
							'operator' => '===',
							'value' => 'text'
						],
					]
				]
			]
		);

		$this->add_responsive_control(
			'title_size',
			[
				'label' => __( 'Size', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-product-carousel__heading-title svg' => 'width: {{size}}{{UNIT}};height: auto;',
					'{{WRAPPER}} .motta-product-carousel__heading-title img' => 'max-width: {{size}}{{UNIT}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'heading_block',
							'value' => 'yes',
						],
						[
							'name' => 'title_type',
							'operator' => '!==',
							'value' => 'text'
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
					'{{WRAPPER}} .motta-product-carousel__heading-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}} .motta-product-carousel__heading-title' => 'margin-right: {{SIZE}}{{UNIT}}; margin-bottom: 0;',
				],
				'condition' => [
					'heading_block' => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_description',
			[
				'label'     => esc_html__( 'Description', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
				'condition' => [
					'heading_block' => 'yes',
				],
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'     => esc_html__( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-product-carousel__heading-description' => 'color: {{VALUE}}',

				],
				'condition' => [
					'heading_block' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .motta-product-carousel__heading-description',
				'condition' => [
					'heading_block' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'description_spacing',
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
					'{{WRAPPER}} .motta-product-carousel__heading-description' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'(mobile){{WRAPPER}} .motta-product-carousel__heading-description' => 'margin-right: {{SIZE}}{{UNIT}}; margin-bottom: 0;',
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
			'section_condition' => [
				'heading_block' => 'yes',
			]
		];

		$this->register_button_style_controls($controls);
	}

	protected function section_style_carousel() {
		$this->start_controls_section(
			'section_style_carousel',
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
			'arrows_style',
			[
				'label'     => esc_html__( 'Arrows Style', 'motta-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '1',
				'options'   => [
					'1'   	=> esc_html__( 'Style 1', 'motta-addons' ),
					'2' 	=> esc_html__( 'Style 2', 'motta-addons' ),
				],
				'prefix_class' => 'motta-product-carousel__arrows-style-',
			]
		);

		$this->add_control(
			'arrows_icon',
			[
				'label'     => esc_html__( 'Icon Style', 'motta-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'regular',
				'options'   => [
					'regular'   	=> esc_html__( 'Regular', 'motta-addons' ),
					'thin' 	=> esc_html__( 'Thin', 'motta-addons' ),
				],
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
					'{{WRAPPER}} .motta-product-carousel--elementor .motta-swiper-button' => 'font-size: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-product-carousel--elementor .motta-swiper-button' => 'width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-product-carousel--elementor .motta-swiper-button' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'sliders_arrows_radius',
			[
				'label'      => __( 'Border Radius', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-product-carousel--elementor .motta-swiper-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'sliders_arrow_color',
			[
				'label'     => esc_html__( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-product-carousel--elementor .motta-swiper-button' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .motta-product-carousel--elementor .motta-swiper-button' => 'background-color: {{VALUE}};',
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

		$this->end_popover();

		$this->add_responsive_control(
			'sliders_arrows_horizontal_spacing',
			[
				'label'      => esc_html__( 'Horizontal Spacing', 'motta-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => -100,
						'max' => 1170,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-product-carousel--elementor .motta-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} .motta-product-carousel--elementor .motta-swiper-button-prev' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
					'{{WRAPPER}} .motta-product-carousel--elementor .motta-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} .motta-product-carousel--elementor .motta-swiper-button-next' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
				],
			]
		);

		$this->add_responsive_control(
			'sliders_arrows_vertical_spacing',
			[
				'label'      => esc_html__( 'Vertical Spacing', 'motta-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1170,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-product-carousel--elementor .motta-swiper-button' => 'top: {{SIZE}}{{UNIT}} ;',
				],
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
					'{{WRAPPER}} .motta-product-carousel--elementor .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .motta-product-carousel--elementor .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'sliders_dot_item_color',
			[
				'label'     => esc_html__( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-product-carousel--elementor .swiper-pagination-bullet' => 'background-color : {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'sliders_dot_item_active_color',
			[
				'label'     => esc_html__( 'Color Active', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-product-carousel--elementor .swiper-pagination-bullet-active' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .motta-product-carousel--elementor .swiper-pagination-bullet:hover' => 'background-color : {{VALUE}};',
				],
			]
		);

		$this->end_popover();

		$this->add_responsive_control(
			'sliders_dots_vertical_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 1000,
						'min' => 0,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .motta-product-carousel--elementor .swiper-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .motta-product-carousel--elementor .swiper-scrollbar' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .motta-product-carousel--elementor .swiper-scrollbar-drag' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'scrollbar_width',
			[
				'label'     => __( 'Width', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'     => [
					'px' => [
						'max' => 1900,
						'min' => 0,
					],
					'%' => [
						'max' => 100,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-product-carousel--elementor .swiper-scrollbar' => 'width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-product-carousel--elementor .swiper-scrollbar' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$nav        = $settings['navigation'];
		$nav_tablet = empty( $settings['navigation_tablet'] ) ? $nav : $settings['navigation_tablet'];
		$nav_mobile = empty( $settings['navigation_mobile'] ) ? $nav : $settings['navigation_mobile'];

		$this->add_render_attribute( 'wrapper', 'class', [
			'motta-products',
			'motta-product-carousel',
			'motta-product-carousel--elementor',
			'motta-carousel--elementor',
			'motta-swiper-carousel-elementor',
			'motta-carousel--swiper',
			'navigation-' . $nav,
			'navigation-tablet-' . $nav_tablet,
			'navigation-mobile-' . $nav_mobile,
			'woocommerce',
			! empty( $settings['title'] ) || ! empty( $settings['description'] ) || ! empty( $settings['primary_button_link']['url'] ) ? 'motta-product-carousel__has-heading' : '',
			! empty( $settings['hide_button'] ) ? 'product-no-button' : '',
		] );
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ) ?>>
			<?php if( ! empty( $settings['heading_block'] ) ) : ?>
				<div class="motta-product-carousel__heading">

					<?php if( $settings['title_type'] == 'text' ) { ?>
						<?php if( ! empty( $settings['title'] ) ) : ?>
							<div class="motta-product-carousel__heading-title motta-product-carousel__heading-title--text"><?php echo $settings['title']; ?></div>
						<?php endif; ?>
					<?php } elseif ( $settings['title_type'] == 'image' ) { ?>
						<?php if( ! empty( $settings['title_image']['url'] ) ) : ?>
							<div class="motta-product-carousel__heading-title motta-product-carousel__heading-title--image">
								<img alt="<?php echo $settings['title'] ?>" src="<?php echo esc_url( $settings['title_image']['url'] ); ?>">
							</div>
						<?php endif; ?>
					<?php } elseif ( $settings['title_type'] == 'external' ) { ?>
						<?php if( ! empty( $settings['external_url'] ) ) : ?>
							<div class="motta-product-carousel__heading-title motta-product-carousel__heading-title--image">
								<img alt="<?php echo $settings['title'] ?>" src="<?php echo esc_url( $settings['external_url'] ); ?>">
							</div>
						<?php endif; ?>
					<?php } else { ?>
						<?php if( ! empty( $settings['title_icon']['value'] ) ) : ?>
							<div class="motta-product-carousel__heading-title motta-product-carousel__heading-title--icon">
								<?php Icons_Manager::render_icon( $settings['title_icon'], [ 'aria-hidden' => 'true' ] ); ?>
							</div>
						<?php endif; ?>
					<?php } ?>

					<?php if( ! empty( $settings['description'] ) ) : ?>
						<div class="motta-product-carousel__heading-description"><?php echo $settings['description']; ?></div>
					<?php endif; ?>
						<?php $this->render_button(); ?>
				</div>
			<?php endif; ?>
			<?php
				if( $settings['arrows_style'] == '2' ) {
					?><div class="motta-product-carousel__container"><?php
				}

				printf( '%s', $this->render_products() );

				$icon_left = $settings['arrows_icon'] == 'regular' ?  'left' : 'arrow-left-long';
				$icon_right = $settings['arrows_icon'] == 'regular' ?  'right' : 'arrow-right-long';
				echo \Motta\Addons\Helper::get_svg( $icon_left, 'ui' , [ 'class' => 'motta-swiper-button-prev swiper-button motta-swiper-button' ]  );
				echo \Motta\Addons\Helper::get_svg( $icon_right, 'ui' , [ 'class' => 'motta-swiper-button-next swiper-button motta-swiper-button' ] );
				echo '<div class="swiper-pagination"></div>';

				if( $settings['arrows_style'] == '2' ) {
					?></div><?php
				}
			?>
		</div>
		<?php
	}
}

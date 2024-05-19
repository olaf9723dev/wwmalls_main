<?php
namespace Motta\Addons\Elementor\Widgets;

use Motta\Addons\Elementor\Base\Products_Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Motta\Addons\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Pricing Table widget
 */
class Products_Tabs_Carousel extends Products_Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-products-tabs-carousel';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( '[Motta] Products Tabs Carousel', 'motta-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-product-tabs';
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
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'product tabs', 'products', 'tabs', 'carousel', 'woocommerce', 'motta-addons' ];
	}

	/**
	 * Register the widget controls.
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
		$this->start_controls_section(
			'section_product_tabs',
			[ 'label' => __( 'Product Tabs', 'motta-addons' ) ]
		);

		$this->register_products_controls( [
			'limit' => 10,
		] );

		$this->add_control(
			'tabs_type',
			[
				'label'   => esc_html__( 'Tabs Type', 'motta-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'category' => esc_html__( 'Categories', 'motta-addons' ),
					'tag'      => esc_html__( 'Tags', 'motta-addons' ),
					'brand'    => esc_html__( 'Brands', 'motta-addons' ),
					'groups'   => esc_html__( 'Groups', 'motta-addons' )
				],
				'default'   => 'groups',
				'toggle'    => false,
				'separator' => 'before',
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'motta-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'This is heading', 'motta-addons' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'type',
			[
				'label'   => esc_html__( 'Products', 'motta-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_options_product_type(),
				'default' => 'recent_products',
				'toggle'  => false,
			]
		);

		$repeater->add_control(
			'orderby',
			[
				'label' => __( 'Order By', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_options_product_orderby(),
				'default' => 'menu_order',
				'condition' => [
					'type' => ['featured', 'sale']
				],
			]
		);

		$repeater->add_control(
			'order',
			[
				'label' => __( 'Order', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'ASC'  => __( 'Ascending', 'motta-addons' ),
					'DESC' => __( 'Descending', 'motta-addons' ),
				],
				'default' => 'ASC',
				'condition' => [
					'type' => ['featured', 'sale'],
					'orderby!' => ['', 'rand'],
				],
			]
		);

		$repeater->add_control(
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
			'groups',
			[
				'label'         => '',
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [
					[
						'title' => esc_html__( 'New Arrivals', 'motta-addons' ),
						'type'  => 'recent_products'
					],
					[
						'title' => esc_html__( 'Best Sellers', 'motta-addons' ),
						'type'  => 'best_selling_products'
					],
					[
						'title' => esc_html__( 'Sale Products', 'motta-addons' ),
						'type'  => 'sale_products'
					]
				],
				'title_field'   => '{{{ title }}}',
				'prevent_empty' => false,
				'condition'     => [
					'tabs_type' => 'groups',
				],
			]
		);

		// Product Cats
		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'category', [
				'label'       => esc_html__( 'Category', 'motta-addons' ),
				'type'        => Controls_Manager::SELECT2,
				'options' 	  => \Motta\Addons\Elementor\Utils::get_terms_options( 'product_cat' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'category_tabs',
			[
				'label'         => esc_html__( 'Categories', 'motta-addons' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [ ],
				'prevent_empty' => false,
				'condition'     => [
					'tabs_type' => 'category',
				],
				'title_field'   => '{{{ category }}}',
			]
		);

		// Product Tag
		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'tag', [
				'label'       => esc_html__( 'Tag', 'motta-addons' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => \Motta\Addons\Elementor\Utils::get_terms_options( 'product_tag' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'tag_tabs',
			[
				'label'         => esc_html__( 'Tags', 'motta-addons' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [ ],
				'prevent_empty' => false,
				'condition'     => [
					'tabs_type' => 'tag',
				],
				'title_field'   => '{{{ tag }}}',
			]
		);

		// Product Brands
		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'brand', [
				'label'       => esc_html__( 'Brand', 'motta-addons' ),
				'type'        => Controls_Manager::SELECT2,
				'options' 	  => \Motta\Addons\Elementor\Utils::get_terms_options( 'product_brand' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'brand_tabs',
			[
				'label'         => esc_html__( 'Brands', 'motta-addons' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [ ],
				'prevent_empty' => false,
				'condition'     => [
					'tabs_type' => 'brand',
				],
				'title_field'   => '{{{ brand }}}',
			]
		);

		$this->end_controls_section();

		// Carousel Settings
		$this->section_content_carousel();
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
					'none'             => esc_html__( 'None', 'motta-addons' ),
					'arrows'           => esc_html__( 'Arrows', 'motta-addons' ),
					'dots'             => esc_html__( 'Dots', 'motta-addons' ),
					'both'             => esc_html__( 'Arrows and Dots', 'motta-addons' ),
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

	// Tab Style
	protected function section_style() {
		$this->start_controls_section(
			'section_style_tabs',
			[
				'label' => esc_html__( 'Tabs', 'motta-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'tabs_style_heading',
			[
				'label'     => __( 'Tabs', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'tab_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-tabs__nav li' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_color_active',
			[
				'label' => __( 'Color Active', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-tabs__nav li.active' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tab_typography',
				'selector' => '{{WRAPPER}} .motta-tabs__nav li',
			]
		);

		$this->add_responsive_control(
			'tab_spacing',
			[
				'label' => __( 'Gap', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-tabs__nav li' => 'margin: 0 {{size}}{{UNIT}} ;',
				],
			]
		);

		$this->add_responsive_control(
			'tab_spacing_bottom',
			[
				'label' => __( 'Spacing Bottom', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-product-tabs__tabs' => 'margin-bottom: {{size}}{{UNIT}} ;',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_products',
			[
				'label' => esc_html__( 'Products', 'motta-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'products_border',
			[
				'label'     => esc_html__( 'Border', 'motta-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''                  => esc_html__( 'No Border', 'motta-addons' ),
					'has-border'        => esc_html__( 'Border', 'motta-addons' ),
					'has-border-bottom' => esc_html__( 'Border Bottom Only', 'motta-addons' ),
				],
			]
		);

		$this->end_controls_section();

		// Style carousel
		$this->section_style_carousel();
	}

	protected function section_style_carousel() {
		$this->start_controls_section(
			'section_style',
			[
				'label'     => __( 'Carousel', 'motta-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
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
					'{{WRAPPER}} .motta-product-tabs-carousel--elementor .motta-swiper-button' => 'font-size: {{SIZE}}{{UNIT}};',
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
				'default' => [],
				'selectors' => [
					'{{WRAPPER}} .motta-product-tabs-carousel--elementor .motta-swiper-button' => 'width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-product-tabs-carousel--elementor .motta-swiper-button' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-product-tabs-carousel--elementor .motta-swiper-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-product-tabs-carousel--elementor .motta-swiper-button' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .motta-product-tabs-carousel--elementor .motta-swiper-button' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .motta-product-tabs-carousel--elementor .motta-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} .motta-product-tabs-carousel--elementor .motta-swiper-button-prev' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
					'{{WRAPPER}} .motta-product-tabs-carousel--elementor .motta-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} .motta-product-tabs-carousel--elementor .motta-swiper-button-next' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
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
					'{{WRAPPER}} .motta-product-tabs-carousel--elementor .motta-swiper-button' => 'top: {{SIZE}}{{UNIT}} ;',
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
					'{{WRAPPER}} .motta-product-tabs-carousel--elementor .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .motta-product-tabs-carousel--elementor .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'sliders_dots_bgcolor',
			[
				'label'     => esc_html__( 'Background Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullets.swiper-pagination--background' => 'background-color : {{VALUE}};',
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
					'{{WRAPPER}} .motta-product-tabs-carousel--elementor .swiper-pagination-bullet' => 'background-color : {{VALUE}};',
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
					'{{WRAPPER}} .motta-product-tabs-carousel--elementor .swiper-pagination-bullet-active' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .motta-product-tabs-carousel--elementor .swiper-pagination-bullet:hover' => 'background-color : {{VALUE}};',
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
					'{{WRAPPER}} .motta-product-tabs-carousel--elementor .swiper-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
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
			'motta-product-tabs',
			'motta-product-tabs--elementor',
			'motta-product-tabs-carousel--elementor',
			'motta-product-tabs__' . $settings['tabs_type'],
			'motta-tabs',
			'motta-tabs--elementor',
			'motta-product-carousel',
			'motta-carousel--elementor',
			'motta-swiper-carousel-elementor',
			'motta-carousel--swiper',
			'navigation-' . $nav,
			'navigation-tablet-' . $nav_tablet,
			'navigation-mobile-' . $nav_mobile,
		] );

		$tabs = $this->get_tabs_data();
		$query_args = [];
		$query_args['per_page'] = $settings['limit'];

		if ( empty( $tabs ) ) {
			return;
		}

		$this->add_render_attribute( 'panel', 'class', [
			'motta-product-grid',
			'motta-products',
			'motta-product-tabs__panel',
			'motta-tabs__panel',
			'active'
		] );

		$this->add_render_attribute( 'panel', 'data-panel', '1' );

		$products_border = ! empty( $settings['products_border'] ) ? 'catalog-grid--' . $settings['products_border'] : '';
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<ul class="motta-product-tabs__tabs motta-tabs__nav">
				<?php foreach ( $tabs as $key => $tab ) : ?>
					<?php
					$tab_key = $this->get_repeater_setting_key( 'tab', 'products_tab', $key );
					$tab['args']['per_page']   = $settings['limit'];
					$tab['args']['pagination'] = false;
					$this->add_render_attribute( $tab_key, [
						'data-target' => $tab['index'],
						'data-atts'   => json_encode( $tab['args'] ),
					] );

					if ( 1 === $tab['index'] ) {
						$this->add_render_attribute( $tab_key, 'class', 'active' );
						$query_args = $tab['args'];
					}
					?>
					<li <?php echo $this->get_render_attribute_string( $tab_key ) ?>><?php echo esc_html( $tab['title'] ); ?></li>
				<?php endforeach; ?>
			</ul>
			<div class="motta-product-tabs__panels motta-tabs__panels <?php echo esc_attr( $products_border ); ?>">
					<div class="motta-product-tabs__panels-loading">
						<div class="motta-pagination--loading-dots">
							<span></span>
							<span></span>
							<span></span>
							<span></span>
						</div>
					</div>
				<div <?php echo $this->get_render_attribute_string( 'panel' ) ?>>
					<?php
						echo $this->render_products( $query_args );
					?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Get the tabs data.
	 *
	 * @return array
	 */
	protected function get_tabs_data() {
		$settings = $this->get_settings_for_display();
		$index = 1;
		$tabs  = [];

		switch ( $settings['tabs_type'] ) {
			case 'category' :
			case 'tag':
				$tabs_type = $settings[ 'tabs_type' ];
				$taxonomy  = 'category' == $tabs_type ? 'product_cat' : 'product_tag';
				$tabs_key  = $tabs_type . '_tabs';

				if ( empty( $settings[ $tabs_key ] ) ) {
					break;
				}

				foreach( $settings[ $tabs_key ] as $i => $tab ) {
					if ( empty( $tab[ $tabs_type ] ) ) {
						continue;
					}

					$term = get_term_by( 'slug', $tab[ $tabs_type ], $taxonomy );

					if ( ! $term || is_wp_error( $term ) ) {
						continue;
					}

					$args = $this->parse_settings( $tab );
					$args['limit'] = $settings['limit'];
					$args['columns'] = isset( $settings['columns'] ) ? $settings['columns'] : 5;
					unset( $args['title'] );

					$tabs[ $term->slug ] = [
						'index' => $index++,
						'args'  => $args,
						'title' => $term->name,
					];
				}

				break;

			case 'brand':
				$tabs_type = $settings[ 'tabs_type' ];
				$taxonomy  = 'product_brand';
				$tabs_key  = $tabs_type . '_tabs';

				if ( empty( $settings[ $tabs_key ] ) ) {
					break;
				}

				foreach( $settings[ $tabs_key ] as $i => $tab ) {
					if ( empty( $tab[ $tabs_type ] ) ) {
						continue;
					}

					$term = get_term_by( 'slug', $tab[ $tabs_type ], $taxonomy );

					if ( ! $term || is_wp_error( $term ) ) {
						continue;
					}

					$args = $this->parse_settings( $tab );
					$args['limit'] = $settings['limit'];
					$args['columns'] = isset( $settings['columns'] ) ? $settings['columns'] : 5;
					$args['class'] =  'sc_brand,' . $tab[ $tabs_type ];
					unset( $args['title'] );

					$tabs[ $term->slug ] = [
						'index' => $index++,
						'args'  => $args,
						'title' => $term->name,
					];
				}

				break;

			case 'groups' :
				if ( empty( $settings['groups'] ) ) {
					break;
				}

				foreach( $settings['groups'] as $i => $tab ) {
					$args = $this->parse_settings( $tab );
					$args['limit'] = $settings['limit'];
					$args['columns'] = isset( $settings['columns'] ) ? $settings['columns'] : 5;
					unset( $args['title'] );

					$tabs[ $tab['type'] . $i ] = [
						'index' => $index++,
						'args'  => $args,
						'title' => $tab['title'],
					];
				}

				break;
		}

		return $tabs;
	}
}
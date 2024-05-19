<?php
namespace Motta\Addons\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

use Motta\Addons\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Icon Box widget
 */
class Icons_Box_Carousel extends Widget_Base {
	use \Motta\Addons\Elementor\Widgets\Traits\Button_Trait;

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-icons-box-carousel';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( '[Motta] Icons Box Carousel', 'motta-addons' );
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
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 */
   	public function get_keywords() {
	   return [ 'icons box carousel', 'icon', 'icons', 'box', 'carousel', 'motta-addons' ];
   	}


	/**
	 * Register the widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_controls() {
		$this->content_sections();
		$this->style_sections();
	}

	protected function content_sections() {
		$this->start_controls_section(
			'section_icon',
			[ 'label' => __( 'Icon Box', 'motta-addons' ) ]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'icon_type',
			[
				'label' => __( 'Icon Type', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'icon' => __( 'Icon', 'motta-addons' ),
					'image' => __( 'Image', 'motta-addons' ),
					'external' => __( 'External', 'motta-addons' ),
				],
				'default' => 'icon',
			]
		);

		$repeater->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'motta-addons' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fa fa-star',
					'library' => 'fa-solid',
				],
				'condition' => [
					'icon_type' => 'icon',
				],
			]
		);

		$repeater->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'motta-addons' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'icon_type' => 'image',
				],
			]
		);

		$repeater->add_control(
			'icon_url',
			[
				'label' => __( 'External Icon URL', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'icon_type' => 'external',
				],
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => __( 'Title & Description', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'This is the heading', 'motta-addons' ),
				'placeholder' => __( 'Enter your title', 'motta-addons' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'description',
			[
				'label' => '',
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'motta-addons' ),
				'placeholder' => __( 'Enter your description', 'motta-addons' ),
				'rows' => 10,
				'separator' => 'none',
				'show_label' => false,
			]
		);

		$controls = [
			'button_text_label' => __( 'Button', 'motta-addons' )
		];

		$this->register_button_content_controls( $controls, $repeater );

		$repeater->add_control(
			'button_link_type',
			[
				'label'   => esc_html__( 'Apply Button Link On', 'motta-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'only' => esc_html__( 'Button Only', 'motta-addons' ),
					'all'  => esc_html__( 'Whole Icon Box', 'motta-addons' ),
				],
				'default' => 'only',
				'toggle'  => false,
			]
		);

		$repeater->add_responsive_control(
			'item_background_color',
			[
				'label'      => esc_html__( 'Background Color', 'motta-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .motta-icons-box-carousel--elementor {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
				],
			]
		);

		$repeater->add_responsive_control(
			'item_color',
			[
				'label'      => esc_html__( 'Text Color', 'motta-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .motta-icons-box-carousel--elementor {{CURRENT_ITEM}} .motta-icon-box-carousel__icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .motta-icons-box-carousel--elementor {{CURRENT_ITEM}} .motta-icon-box-carousel__title' => 'color: {{VALUE}}',
					'{{WRAPPER}} .motta-icons-box-carousel--elementor {{CURRENT_ITEM}} .motta-icon-box-carousel__content' => 'color: {{VALUE}}',
					'{{WRAPPER}} .motta-icons-box-carousel--elementor {{CURRENT_ITEM}} .motta-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icons',
			[
				'label'       => __( 'Icons Box', 'motta-addons' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default' => [
					[
						'title'    => __( 'Name #1', 'motta-addons' ),
					],
					[
						'title'    => __( 'Name #2', 'motta-addons' ),
					],
					[
						'title'    => __( 'Name #3', 'motta-addons' ),
					],
					[
						'title'    => __( 'Name #4', 'motta-addons' ),
					]
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'position',
			[
				'label' => esc_html__( 'Icon Position', 'motta-addons' ),
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
					'right' => [
						'title' => esc_html__( 'Right', 'motta-addons' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'motta%s-icon-box__icon-position--',
				'toggle' => false,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_size',
			[
				'label' => __( 'Title HTML Tag', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h6',
			]
		);

		$this->end_controls_section();

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
				'default'            => 3,
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
					'arrows'           => esc_html__( 'Arrows', 'motta-addons' ),
					'dots'             => esc_html__( 'Dots', 'motta-addons' ),
					'both'             => esc_html__( 'Arrows and Dots', 'motta-addons' ),
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

	protected function style_sections() {
		$this->content_style_sections();
		$this->icon_style_sections();
		$this->section_style_carousel();
	}

	protected function icon_style_sections() {
		// Style Icon
		$this->start_controls_section(
			'section_style_icon',
			[
				'label'     => __( 'Icon', 'motta-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Primary Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-icon-box-carousel__icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label' => __( 'Hover Primary Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-icon-box-carousel__icon:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label' => __( 'Spacing', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					]
				],
				'default' => [],
				'selectors' => [
					'{{WRAPPER}} .motta-icons-box-carousel--elementor' => '--motta-icon-box-margin: {{SIZE}}{{UNIT}};',

				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Size', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					]
				],
				'default' => [],
				'selectors' => [
					'{{WRAPPER}} .icon-type-icon .motta-icon-box-carousel__icon' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .icon-type-external .motta-icon-box-carousel__icon' => 'width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .icon-type-image .motta-icon-box-carousel__icon' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}



	protected function content_style_sections() {
		// Content style
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => __( 'Content', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'alignment',
			[
				'label'                => esc_html__( 'Alignment', 'motta-addons' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => [
					'left'   => [
						'title' => esc_html__( 'Left', 'motta-addons' ),
						'icon' 	=> 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'motta-addons' ),
						'icon' 	=> 'fa fa-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'motta-addons' ),
						'icon' 	=> 'fa fa-align-right',
					],
				],
				'default'              => '',
				'prefix_class' => 'motta%s-icon-box__icon-alignment--',
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => esc_html__( 'Padding', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-icons-box-carousel__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.motta-rtl-smart {{WRAPPER}} .motta-icons-box-carousel__item' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_bg_color',
			[
				'label' => __( 'Background Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-icons-box-carousel__item' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'icon_box_shadow',
				'label' => __( 'Box Shadow', 'motta-addons' ),
				'selector' => '{{WRAPPER}} .motta-icons-box-carousel__item',
			]
		);

		$this->add_responsive_control(
			'space_between',
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
				'default' => [],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'title_style_heading',
			[
				'label' => __( 'Title', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-icon-box-carousel__title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .motta-icon-box-carousel__title',
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label' => __( 'Spacing', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					]
				],
				'default' => [],
				'selectors' => [
					'{{WRAPPER}} .motta-icon-box-carousel__title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'description_style_heading',
			[
				'label' => __( 'Description', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-icon-box-carousel__content' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .motta-icon-box-carousel__content',
			]
		);

		$this->add_responsive_control(
			'description_spacing',
			[
				'label' => __( 'Spacing', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					]
				],
				'default' => [],
				'selectors' => [
					'{{WRAPPER}} .motta-icon-box-carousel__content' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'button_style_heading',
			[
				'label' => __( 'Button', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$controls = [
			'skin'     => 'subtle',
			'size'      => 'medium',
		];

		$this->register_button_style_controls($controls);

		$this->end_controls_section();
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
					'{{WRAPPER}} .motta-icons-box-carousel--elementor .motta-swiper-button' => 'font-size: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-icons-box-carousel--elementor .motta-swiper-button' => 'width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-icons-box-carousel--elementor .motta-swiper-button' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-icons-box-carousel--elementor .motta-swiper-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-icons-box-carousel--elementor .motta-swiper-button' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .motta-icons-box-carousel--elementor .motta-swiper-button' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .motta-icons-box-carousel--elementor .motta-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} .motta-icons-box-carousel--elementor .motta-swiper-button-prev' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
					'{{WRAPPER}} .motta-icons-box-carousel--elementor .motta-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} .motta-icons-box-carousel--elementor .motta-swiper-button-next' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
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
					'{{WRAPPER}} .motta-icons-box-carousel--elementor .motta-swiper-button' => 'top: {{SIZE}}{{UNIT}} ;',
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
					'{{WRAPPER}} .motta-icons-box-carousel--elementor .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .motta-icons-box-carousel--elementor .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-icons-box-carousel--elementor .swiper-pagination-bullet' => 'background-color : {{VALUE}};',
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
					'{{WRAPPER}} .motta-icons-box-carousel--elementor .swiper-pagination-bullet-active' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .motta-icons-box-carousel--elementor .swiper-pagination-bullet:hover' => 'background-color : {{VALUE}};',
				],
			]
		);

		$this->end_popover();

		$this->add_responsive_control(
			'sliders_dots_vertical_spacing',
			[
				'label'     => esc_html__( 'Vertical Spacing', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 1000,
						'min' => 0,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .motta-icons-box-carousel--elementor .swiper-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render icon box widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$nav        = $settings['navigation'];
		$nav_tablet = empty( $settings['navigation_tablet'] ) ? $nav : $settings['navigation_tablet'];
		$nav_mobile = empty( $settings['navigation_mobile'] ) ? $nav : $settings['navigation_mobile'];

		$classes = [
			'motta-icons-box-carousel--elementor',
			'motta-carousel--elementor',
			'motta-swiper-carousel-elementor',
			'motta-carousel--swiper',
			'navigation-' . $nav,
			'navigation-tablet-' . $nav_tablet,
			'navigation-mobile-' . $nav_mobile
		];

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		$output = array();

		foreach( $settings['icons'] as $index => $slide ) {
			$wrapper_key = $this->get_repeater_setting_key( 'slide_wrapper', 'icons', $index );
			$icon_key = $this->get_repeater_setting_key( 'icon', 'icons', $index );
			$title_key = $this->get_repeater_setting_key( 'title', 'icons', $index );
			$description_key = $this->get_repeater_setting_key( 'description', 'icons', $index );

			$this->add_render_attribute( $wrapper_key, 'class', [ 'motta-icons-box-carousel__item', 'icon-type-' . $slide['icon_type'], 'elementor-repeater-item-' . $slide['_id'] , 'swiper-slide' ] );
			$this->add_render_attribute( $icon_key, 'class', [ 'motta-icon-box__icon', 'motta-icon-box-carousel__icon' ] );
			$this->add_render_attribute( $title_key, 'class', [ 'motta-icon-box__title', 'motta-icon-box-carousel__title' ] );
			$this->add_render_attribute( $description_key, 'class', [ 'motta-icon-box__content', 'motta-icon-box-carousel__content' ] );

			$this->add_inline_editing_attributes( 'title', 'none' );
			$this->add_inline_editing_attributes( 'description', 'basic' );

			$icon_exist = true;

			if ( 'image' == $slide['icon_type'] ) {
				$icon_exist = ! empty($slide['image']) ? true : false;
			} elseif ( 'external' == $slide['icon_type'] ) {
				$icon_exist = ! empty($slide['icon_url']) ? true : false;
			} else {
				$icon_exist = ! empty($slide['icon']) && ! empty($slide['icon']['value']) ? true : false;
			}

			if ( $icon_exist ) {
				$icon = '<div ' . $this->get_render_attribute_string( $icon_key ) . '>';

				if ( 'image' == $slide['icon_type'] ) {
					$icon .= $slide['image'] ? sprintf( '<img alt="%s" src="%s">', esc_attr( $slide['title'] ), esc_url( $slide['image']['url'] ) ) : '';
				} elseif ( 'external' == $slide['icon_type'] ) {
					$icon .= $slide['icon_url'] ? sprintf( '<img alt="%s" src="%s">', esc_attr( $slide['title'] ), esc_url( $slide['icon_url'] ) ) : '';
				} else {
					$icon .= '<span class="motta-svg-icon">';
					$icon .= Icons_Manager::try_get_icon_html( $slide['icon'], [ 'aria-hidden' => 'true' ] );
					$icon .= '</span>';
				}

				$icon .= '</div>';
			}

			$title 		= $slide['title'] ? '<'. Utils::validate_html_tag( $settings['title_size'] ) . ' ' . $this->get_render_attribute_string( $title_key ) . '>' . wp_kses_post( $slide['title'] ) . '</' . Utils::validate_html_tag( $settings['title_size'] ) .'>' : '';
			$content 	= $slide['description'] ? '<div ' . $this->get_render_attribute_string( $description_key ) . '>'. wp_kses_post( $slide['description'] ) .'</div>' : '';

			$output[] = sprintf(
				'<div %s>
					%s
					%s
					<div class="motta-icon-box-carousel__wrapper">%s%s%s</div>
					%s
				</div>',
				$this->get_render_attribute_string( $wrapper_key ),
				$slide['button_link_type'] == 'all' ? Helper::render_control_link_open( 'btn_full', $slide['primary_button_link'], [ 'class' => 'motta-icon-box-carousel__button-link' ] ) : '',
				$icon,
				$title,
				$content,
				! empty( $slide['primary_button_link']['url'] ) ? $this->icon_box_render_button( $slide, $index ) : '',
				$slide['button_link_type'] == 'all' ? Helper::render_control_link_close( $slide['primary_button_link'] ) : '',
			);
		}

		echo sprintf(
			'<div %s>
				<div class="motta-icon-box-carousel__list swiper-container">
					<div class="motta-icon-box-carousel__inner swiper-wrapper">
						%s
					</div>
				</div>
				%s%s
				<div class="swiper-pagination"></div>
			</div>',
			$this->get_render_attribute_string( 'wrapper' ),
			implode( '', $output ),
			Helper::get_svg('left', 'ui' , [ 'class' => 'motta-swiper-button-prev swiper-button motta-swiper-button' ] ),
			Helper::get_svg('right', 'ui' , [ 'class' => 'motta-swiper-button-next swiper-button motta-swiper-button' ] )
		);
	}


	protected function icon_box_render_button ( $slide, $index ) {
		$settings = $this->get_settings_for_display();

		$link_key = 'primary_button_' . $index;

		$this->add_render_attribute( $link_key , 'class', [ 'motta-button', 'motta-button-primary' ] );
		if( isset( $settings['classes'] ) ) {
			$this->add_render_attribute( $link_key , 'class', $settings['classes'] );
		}
		$this->add_render_attribute( $link_key , 'class', ' motta-button--' . $settings['primary_button_skin'] );

		if( in_array( $settings['primary_button_skin'], array('subtle', 'text', 'ghost') ) ) {
			$this->add_render_attribute( $link_key , 'class', ' motta-button--color-black');
		}

		if( $settings['primary_button_size'] !== 'normal' ) {
			$this->add_render_attribute( $link_key, 'class', ' motta-button--' . $settings['primary_button_size'] );
		}

		if( $settings['primary_button_shape'] !== 'default' ) {
			$this->add_render_attribute( $link_key , 'class', ' motta-shape--' . $settings['primary_button_shape'] );
		}

		$tag = 'a';

		if ( isset( $slide['button_link_type'] ) && $slide['button_link_type'] == 'all' ) {
			$tag = 'span';
		} else {
			if ( ! empty( $slide['primary_button_link']['url'] ) ) {
				$this->add_link_attributes( $link_key, $slide['primary_button_link'] );
			}
		}

		return sprintf(
			'<%s %s><span class="motta-button__text">%s</span></%s>',
			esc_attr( $tag ),
			$this->get_render_attribute_string( $link_key ),
			$slide['primary_button_text'],
			esc_attr( $tag )
		);
	}
}
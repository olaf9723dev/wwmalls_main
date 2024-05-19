<?php
namespace Motta\Addons\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Motta\Addons\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Image Box Grid widget.
 *
 * Elementor widget that displays an eye-catching headlines.
 *
 * @since 1.0.0
 */
class Testimonial_Carousel extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve Image Box widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-testimonial-carousel';
	}

	/**
	 * Get widget title
	 *
	 * Retrieve Image Box widget title
	 *
	 * @return string Widget title
	 */
	public function get_title() {
		return __( '[Motta] Testimonial Carousel', 'motta-addons' );
	}

	/**
	 * Get widget icon
	 *
	 * Retrieve Image Box widget icon
	 *
	 * @return string Widget icon
	 */
	public function get_icon() {
		return 'eicon-testimonial-carousel';
	}

	/**
	 * Get widget categories
	 *
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @return string Widget categories
	 */
	public function get_categories() {
		return [ 'motta-addons' ];
	}

	/**
	 * Get widget keywords.
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'testimonial carousel', 'carousel', 'testimonial', 'motta' ];
	}


	/**
	 * Register heading widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->section_content();
		$this->section_style();
	}

	// Tab Content
	protected function section_content() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'motta-addons' ),
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'testimonial_content',
			[
				'label' => __( 'Content', 'motta-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'rows' => '10',
				'default' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'motta-addons' ),
			]
		);

		$repeater->add_control(
			'testimonial_image',
			[
				'label' => __( 'Choose Image', 'motta-addons' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => MOTTA_ADDONS_URL . '/assets/images/person.jpg',
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'testimonial_image',
				'default'   => 'full',
				'separator' => 'none',
			]
		);

		$repeater->add_control(
			'testimonial_name',
			[
				'label' => __( 'Name', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'John Doe', 'motta-addons' ),
				'label_block' => true,
				'separator' => 'before',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'testimonial_company',
			[
				'label' => __( 'Company/Title', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Company Name', 'motta-addons' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'testimonial_brand',
			[
				'label' => __( 'Choose Brand', 'motta-addons' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [],
			]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'testimonial_brand',
				'default'   => 'full',
				'separator' => 'none',
			]
		);

		$repeater->add_control(
			'testimonial_rating',
			[
				'label'   => esc_html__( 'Rating', 'motta-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'0'    => __( 'None', 'motta-addons' ),
					'1'    => __( '1 Star', 'motta-addons' ),
					'2'    => __( '2 Stars', 'motta-addons' ),
					'3'    => __( '3 Stars', 'motta-addons' ),
					'4'    => __( '4 Stars', 'motta-addons' ),
					'5'    => __( '5 Stars', 'motta-addons' ),
				],
				'default'            => 5,
			]
		);

		$this->add_control(
			'testimonials',
			[
				'label'       => __( 'Testimonials', 'motta-addons' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ testimonial_name }}}',
				'default' => [
					[
						'testimonial_name'    => __( 'Name #1', 'motta-addons' ),
						'testimonial_company' => __( 'Company #1', 'motta-addons' ),
					],
					[
						'testimonial_name'    => __( 'Name #2', 'motta-addons' ),
						'testimonial_company' => __( 'Company #2', 'motta-addons' ),
					],
					[
						'testimonial_name'    => __( 'Name #3', 'motta-addons' ),
						'testimonial_company' => __( 'Company #3', 'motta-addons' ),
					],
					[
						'testimonial_name'    => __( 'Name #4', 'motta-addons' ),
						'testimonial_company' => __( 'Company #4', 'motta-addons' ),
					]
				],
				'separator' => 'before',
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

	// Tab Style
	protected function section_style() {
		$this->section_style_general();
		$this->section_style_item();
		$this->section_style_carousel();
	}

	protected function section_style_general() {
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => __( 'Content', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_position',
			[
				'label'   => esc_html__( 'Position Image', 'motta-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'top'           => esc_html__( 'Top', 'motta-addons' ),
					'middle'        => esc_html__( 'Middle', 'motta-addons' ),
					'left'           => esc_html__( 'Left', 'motta-addons' ),
				],
				'default'            => 'middle',
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
				'default' => [
					'size' => 75
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function section_style_item() {
		$this->start_controls_section(
			'section_style_item',
			[
				'label' => __( 'Testimonial Item', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_item_padding',
			[
				'label'      => esc_html__( 'Padding', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-testimonial__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.motta-rtl-smart {{WRAPPER}} .motta-testimonial__item' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_item_color',
			[
				'label'     => esc_html__( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-testimonial__item' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_item_box_shadow',
				'label' => __( 'Box Shadow', 'motta-addons' ),
				'selector' => '{{WRAPPER}} .motta-testimonial__item',
			]
		);

		$this->add_control(
			'content_item_border-radius',
			[
				'label'      => __( 'Border Radius', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-testimonial__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_item_heading',
			[
				'label' => esc_html__( 'Image', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'image_item_width',
			[
				'label'     => __( 'Width(px)', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 200,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-testimonial__photo img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_item_border_radius',
			[
				'label'      => __( 'Border Radius', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-testimonial__photo img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_item_content_heading',
			[
				'label' => esc_html__( 'Content', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_item_content_color',
			[
				'label'     => esc_html__( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-testimonial__content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_item_content_typo',
				'selector' => '{{WRAPPER}} .motta-testimonial__content',
			]
		);

		$this->add_responsive_control(
			'content_item_content_spacing',
			[
				'label'     => __( 'Spacing', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 200,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-testimonial__content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_item_name_heading',
			[
				'label' => esc_html__( 'Name', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_item_name_color',
			[
				'label'     => esc_html__( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-testimonial__name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_item_name_typo',
				'selector' => '{{WRAPPER}} .motta-testimonial__name',
			]
		);

		$this->add_responsive_control(
			'content_item_name_spacing',
			[
				'label'     => __( 'Spacing', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 200,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-testimonial__name' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.motta-testimonial__image-position--left .motta-testimonial__name' => 'margin-left: {{SIZE}}{{UNIT}}; margin-top: 0;',
					'.rtl {{WRAPPER}}.motta-testimonial__image-position--left .motta-testimonial__name' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_item_company_heading',
			[
				'label' => esc_html__( 'Company', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_item_company_color',
			[
				'label'     => esc_html__( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-testimonial__company' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_item_company_typo',
				'selector' => '{{WRAPPER}} .motta-testimonial__company',
			]
		);

		$this->add_responsive_control(
			'content_item_company_spacing',
			[
				'label'     => __( 'Spacing', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 200,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-testimonial__company' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.motta-testimonial__image-position--left .motta-testimonial__company' => 'margin-left: {{SIZE}}{{UNIT}}; margin-top: 0;',
					'.rtl {{WRAPPER}}.motta-testimonial__image-position--left .motta-testimonial__company' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_item_brand_heading',
			[
				'label' => esc_html__( 'Brand', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'content_item_brand_spacing',
			[
				'label'     => __( 'Spacing', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 200,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-testimonial__brand' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_item_rating_heading',
			[
				'label' => esc_html__( 'Rating', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'content_item_rating_size',
			[
				'label'     => __( 'Size', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 200,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-testimonial__rating' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_item_rating_color',
			[
				'label'     => esc_html__( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-testimonial__rating .user-rating' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_item_rating_spacing',
			[
				'label'              => esc_html__( 'Spacing', 'motta-addons' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'allowed_dimensions' => [ 'top', 'bottom' ],
				'size_units'         => [ 'px', '%' ],
				'selectors'          => [
					'{{WRAPPER}} .motta-testimonial__rating' => 'margin-top: {{TOP}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				],
			]
		);

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
					'{{WRAPPER}} .motta-testimonial-carousel--elementor .motta-swiper-button' => 'font-size: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-testimonial-carousel--elementor .motta-swiper-button' => 'width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-testimonial-carousel--elementor .motta-swiper-button' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-testimonial-carousel--elementor .motta-swiper-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-testimonial-carousel--elementor .motta-swiper-button' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .motta-testimonial-carousel--elementor .motta-swiper-button' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .motta-testimonial-carousel--elementor .motta-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} .motta-testimonial-carousel--elementor .motta-swiper-button-prev' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
					'{{WRAPPER}} .motta-testimonial-carousel--elementor .motta-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} .motta-testimonial-carousel--elementor .motta-swiper-button-next' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
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
					'{{WRAPPER}} .motta-testimonial-carousel--elementor .motta-swiper-button' => 'top: {{SIZE}}{{UNIT}} ;',
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
					'{{WRAPPER}} .motta-testimonial-carousel--elementor .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .motta-testimonial-carousel--elementor .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-testimonial-carousel--elementor .swiper-pagination-bullet' => 'background-color : {{VALUE}};',
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
					'{{WRAPPER}} .motta-testimonial-carousel--elementor .swiper-pagination-bullet-active' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .motta-testimonial-carousel--elementor .swiper-pagination-bullet:hover' => 'background-color : {{VALUE}};',
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
					'{{WRAPPER}} .motta-testimonial-carousel--elementor .swiper-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render heading widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$nav        = $settings['navigation'];
		$nav_tablet = empty( $settings['navigation_tablet'] ) ? $nav : $settings['navigation_tablet'];
		$nav_mobile = empty( $settings['navigation_mobile'] ) ? $nav : $settings['navigation_mobile'];

		$classes = [
			'motta-testimonial-carousel--elementor',
			'motta-carousel--elementor',
			'motta-swiper-carousel-elementor',
			'motta-carousel--swiper',
			'navigation-' . $nav,
			'navigation-tablet-' . $nav_tablet,
			'navigation-mobile-' . $nav_mobile,
			'motta-testimonial__image-position--' . $settings['content_position']
		];

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		$output = array();

		foreach( $settings['testimonials'] as $index => $slide ) {
			$wrapper_key = $this->get_repeater_setting_key( 'slide_wrapper', 'testimonial', $index );
			$img_key     = $this->get_repeater_setting_key( 'image', 'testimonial', $index );
			$name_key    = $this->get_repeater_setting_key( 'name', 'testimonial', $index );
			$company_key = $this->get_repeater_setting_key( 'company', 'testimonial', $index );
			$desc_key    = $this->get_repeater_setting_key( 'desc', 'testimonial', $index );
			$brand_key    = $this->get_repeater_setting_key( 'brand', 'testimonial', $index );
			$rating_key    = $this->get_repeater_setting_key( 'rating', 'testimonial', $index );

			$this->add_render_attribute( $wrapper_key, 'class', [ 'motta-testimonial__item', 'swiper-slide' ] );
			$this->add_render_attribute( $img_key, 'class', 'motta-testimonial__photo' );
			$this->add_render_attribute( $name_key, 'class', 'motta-testimonial__name' );
			$this->add_render_attribute( $company_key, 'class', 'motta-testimonial__company' );
			$this->add_render_attribute( $desc_key, 'class', 'motta-testimonial__content' );
			$this->add_render_attribute( $brand_key, 'class', 'motta-testimonial__brand' );
			$this->add_render_attribute( $rating_key, 'class', [ 'motta-testimonial__rating', 'star-rating' ] );

			$image = '<div ' . $this->get_render_attribute_string( $img_key ) . '>';
			if ( $slide['testimonial_image']['url'] ) {
				$image .= Group_Control_Image_Size::get_attachment_image_html( $slide, 'testimonial_image' );
			} else {
				$image .= '<img src="' . MOTTA_ADDONS_URL . '/assets/images/person.jpg" alt="' . esc_attr( $slide['testimonial_name'] ) . '">';
			}
			$image .= '</div>';

			$name 		= $slide['testimonial_name'] ? '<div ' . $this->get_render_attribute_string( $name_key ) . '>'. esc_html( $slide['testimonial_name'] ) .'</div>' : '';
			$content 	= $slide['testimonial_content'] ? '<div ' . $this->get_render_attribute_string( $desc_key ) . '>'. wp_kses_post( $slide['testimonial_content'] ) .'</div>' : '';
			$rating 	= $slide['testimonial_rating'] ? '<div ' . $this->get_render_attribute_string( $rating_key ) . '>'. $this->star_rating_html( $slide['testimonial_rating'] ) .'</div>' : '';
			$company 	= $slide['testimonial_company'] ? '<div ' . $this->get_render_attribute_string( $company_key ) . '>'. $slide['testimonial_company'] .'</div>' : '';
			$brand 		= $slide['testimonial_brand']['url'] ? '<div ' . $this->get_render_attribute_string( $brand_key ) . '>'. Group_Control_Image_Size::get_attachment_image_html( $slide, 'testimonial_brand' ) .'</div>' : '';

			$output[] = sprintf(
				'<div %s>
					%s
					%s
					%s
					%s
				</div>',
				$this->get_render_attribute_string( $wrapper_key ),
				$this->position_top( $settings, $rating, $image, $name, $company, $brand ),
				$content,
				$this->position_middle( $settings, $image ),
				$this->position_bottom( $settings, $name, $company, $brand )
			);
		}

		echo sprintf(
			'<div %s>
				<div class="motta-testimonial__list swiper-container">
					<div class="motta-testimonial__inner swiper-wrapper">
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

	public function star_rating_html( $count ) {
		$html = '<span class="max-rating rating-stars">'
		        . \Motta\Addons\Helper::get_svg( 'star' )
		        . \Motta\Addons\Helper::get_svg( 'star' )
		        . \Motta\Addons\Helper::get_svg( 'star' )
		        . \Motta\Addons\Helper::get_svg( 'star' )
		        . \Motta\Addons\Helper::get_svg( 'star' )
		        . '</span>';
		$html .= '<span class="user-rating rating-stars" style="width:' . ( ( $count / 5 ) * 100 ) . '%">'
				. \Motta\Addons\Helper::get_svg( 'star' )
				. \Motta\Addons\Helper::get_svg( 'star' )
				. \Motta\Addons\Helper::get_svg( 'star' )
				. \Motta\Addons\Helper::get_svg( 'star' )
				. \Motta\Addons\Helper::get_svg( 'star' )
		         . '</span>';

		$html .= '<span class="screen-reader-text">';

		$html .= '</span>';

		return $html;
	}

	public function position_top( $settings, $rating, $image, $name, $company, $brand ) {
		$html = '';

		if ( $settings['content_position'] == 'top' ) {
			$html = $image . $rating;
		} elseif ( $settings['content_position'] == 'left' ) {
			$html = sprintf(
					'<div class="motta-testimonial__header">
						<div class="motta-testimonial__left">
						%s
						<div class="motta-testimonial__author">%s%s</div>
						</div>
						<div class="motta-testimonial__right">%s</div>
					</div>',
					$image,
					$name,
					$company,
					$brand
				);
			$html .= $rating;
		} else {
			$html = $rating;
		}

		return $html;
	}

	public function position_middle( $settings, $image ) {
		if ( $settings['content_position'] != 'middle' ) {
			return;
		}

		return $image;
	}

	public function position_bottom( $settings, $name, $company, $brand ) {
		if ( $settings['content_position'] == 'left' ) {
			return;
		}

		return $html = $name . $company . $brand;

		return $html;
	}
}
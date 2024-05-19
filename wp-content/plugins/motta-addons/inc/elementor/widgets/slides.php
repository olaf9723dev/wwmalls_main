<?php

namespace Motta\Addons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Widget_Base;
use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;
use Elementor\Utils;
use Elementor\Icons_Manager;
use \Motta\Addons\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Slides widget
 */
class Slides extends Widget_Base {
	use \Motta\Addons\Elementor\Widgets\Traits\Button_Trait;

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-slides';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( '[Motta] Slides', 'motta-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-post-slider';
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
		$this->section_content_slides();
		$this->section_content_option();
	}

	protected function section_content_slides() {
		$this->start_controls_section(
			'section_slides',
			[
				'label' => esc_html__( 'Slides', 'motta-addons' ),
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->start_controls_tabs( 'slides_repeater' );


		$repeater->start_controls_tab( 'text_content', [ 'label' => esc_html__( 'Content', 'motta-addons' ) ] );

		$repeater->add_control(
			'subtitle_type',
			[
				'label'       => esc_html__( 'SubTitle Type', 'motta-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'text'   => esc_html__( 'Text', 'motta-addons' ),
					'svg' 	 => esc_html__( 'SVG Icon', 'motta-addons' ),
					'image'  => esc_html__( 'Image', 'motta-addons' ),
					'external' 	=> esc_html__( 'External', 'motta-addons' ),
				],
				'default' => 'text',
			]
		);

		$repeater->add_control(
			'subtitle',
			[
				'label'       => esc_html__( 'SubTitle', 'motta-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'conditions' => [
					'terms' => [
						[
							'name'  => 'subtitle_type',
							'value' => 'text',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'subtitle_icon',
			[
				'label' => __( 'SubTitle', 'motta-addons' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fa fa-star',
					'library' => 'fa-solid',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'subtitle_type',
							'value' => 'svg',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'subtitle_image',
			[
				'label' => __( 'SubTitle', 'motta-addons' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'subtitle_type',
							'value' => 'image',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'subtitle_external_url',
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
							'name' => 'subtitle_type',
							'value' => 'external',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'motta-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Slide Title', 'motta-addons' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'before_description',
			[
				'label'       => esc_html__( 'Before Description', 'motta-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'description',
			[
				'label'       => esc_html__( 'Description', 'motta-addons' ),
				'type'    => Controls_Manager::TEXTAREA,
			]
		);

		$repeater->add_control(
			'after_description',
			[
				'label'       => esc_html__( 'After Description', 'motta-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$controls = [
			'button_text_default' => __( 'Shop Now', 'motta-addons' ),
			'button_text_label' => __( 'Primary Button Text', 'motta-addons' ),
			'button_text_link' => __( 'Primary Button Link', 'motta-addons' )
		];

		$this->register_button_content_controls( $controls, $repeater );

		$repeater->add_control(
			'button_link_type',
			[
				'label'   => esc_html__( 'Apply Primary Link On', 'motta-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'only' => esc_html__( 'Button Only', 'motta-addons' ),
					'slide'  => esc_html__( 'Whole Slide', 'motta-addons' ),
				],
				'default' => 'only',
				'conditions' => [
					'terms' => [
						[
							'name' => 'primary_button_link[url]',
							'operator' => '!=',
							'value' => '',
						],
					],
				],
			]
		);

		$controls_second = [
			'prefix'			=> 'second',
			'button_text_label' => __( 'Secondary Button Text', 'motta-addons' ),
			'button_text_link' => __( 'Secondary Button Link', 'motta-addons' )
		];

		$this->register_button_content_controls( $controls_second, $repeater );

		$repeater->add_control(
			'after_button',
			[
				'label'       => esc_html__( 'After Button', 'motta-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$repeater->add_responsive_control(
			'image',
			[
				'label' => __( 'Image', 'motta-addons' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .motta-slides-elementor {{CURRENT_ITEM}} .motta-slide__image' => 'background-image: url("{{URL}}");',
				],
			]
		);

		$repeater->add_responsive_control(
			'image_background_size',
			[
				'label'     => esc_html__( 'Background Size', 'motta-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					'' => esc_html__( 'Contain', 'motta-addons' ),
					'cover'   => esc_html__( 'Cover', 'motta-addons' ),
					'auto'    => esc_html__( 'Auto', 'motta-addons' ),
				],
				'selectors' => [
					'{{WRAPPER}} .motta-slides-elementor {{CURRENT_ITEM}} .motta-slide__image' => 'background-size: {{VALUE}}',
				],
			]
		);

		$repeater->add_responsive_control(
			'image_background_position',
			[
				'label'     => esc_html__( 'Background Position', 'motta-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''              => esc_html__( 'Default', 'motta-addons' ),
					'left top'      => esc_html__( 'Left Top', 'motta-addons' ),
					'left center'   => esc_html__( 'Left Center', 'motta-addons' ),
					'left bottom'   => esc_html__( 'Left Bottom', 'motta-addons' ),
					'right top'     => esc_html__( 'Right Top', 'motta-addons' ),
					'right center'  => esc_html__( 'Right Center', 'motta-addons' ),
					'right bottom'  => esc_html__( 'Right Bottom', 'motta-addons' ),
					'center top'    => esc_html__( 'Center Top', 'motta-addons' ),
					'center center' => esc_html__( 'Center Center', 'motta-addons' ),
					'center bottom' => esc_html__( 'Center Bottom', 'motta-addons' ),
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-slides-elementor {{CURRENT_ITEM}} .motta-slide__image' => 'background-position: {{VALUE}};',
				],

			]
		);

		$repeater->add_responsive_control(
			'image_background_repeat',
			[
				'label'     => esc_html__( 'Background Repeat', 'motta-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'' => esc_html__( 'No Repeat', 'motta-addons' ),
					'repeat'    => esc_html__( 'Repeat', 'motta-addons' ),
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-slides-elementor {{CURRENT_ITEM}} .motta-slide__image' => 'background-repeat: {{VALUE}};',
				],

			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'background', [ 'label' => esc_html__( 'Background', 'motta-addons' ) ] );

		$repeater->add_responsive_control(
			'banner_background_img',
			[
				'label'    => __( 'Background Image', 'motta-addons' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => 'https://via.placeholder.com/800x400/f1f1f1?text=image',
				],
				'selectors' => [
					'{{WRAPPER}} .motta-slides-elementor {{CURRENT_ITEM}}:not(.swiper-lazy)' => 'background-image: url("{{URL}}");',
				],
			]
		);

		$repeater->add_responsive_control(
			'background_size',
			[
				'label'     => esc_html__( 'Background Size', 'motta-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'cover',
				'options'   => [
					'cover'   => esc_html__( 'Cover', 'motta-addons' ),
					'contain' => esc_html__( 'Contain', 'motta-addons' ),
					'auto'    => esc_html__( 'Auto', 'motta-addons' ),
				],
				'selectors' => [
					'{{WRAPPER}} .motta-slides-elementor {{CURRENT_ITEM}}' => 'background-size: {{VALUE}}',
				],
			]
		);

		$repeater->add_responsive_control(
			'background_position',
			[
				'label'     => esc_html__( 'Background Position', 'motta-addons' ),
				'type'      => Controls_Manager::SELECT,
				'responsive' => true,
				'options'   => [
					''              => esc_html__( 'Default', 'motta-addons' ),
					'left top'      => esc_html__( 'Left Top', 'motta-addons' ),
					'left center'   => esc_html__( 'Left Center', 'motta-addons' ),
					'left bottom'   => esc_html__( 'Left Bottom', 'motta-addons' ),
					'right top'     => esc_html__( 'Right Top', 'motta-addons' ),
					'right center'  => esc_html__( 'Right Center', 'motta-addons' ),
					'right bottom'  => esc_html__( 'Right Bottom', 'motta-addons' ),
					'center top'    => esc_html__( 'Center Top', 'motta-addons' ),
					'center center' => esc_html__( 'Center Center', 'motta-addons' ),
					'center bottom' => esc_html__( 'Center Bottom', 'motta-addons' ),
					'initial' 		=> esc_html__( 'Custom', 'motta-addons' ),
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-slides-elementor {{CURRENT_ITEM}}' => 'background-position: {{VALUE}};',
				],

			]
		);

		$repeater->add_responsive_control(
			'background_position_x',
			[
				'label' => esc_html__( 'X Position', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'responsive' => true,
				'size_units' => [ 'px', 'em', '%', 'vw' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'tablet_default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => -800,
						'max' => 800,
					],
					'em' => [
						'min' => -100,
						'max' => 100,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
					'vw' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-slides-elementor {{CURRENT_ITEM}}' => 'background-position: {{SIZE}}{{UNIT}} {{background_position_y.SIZE}}{{background_position_y.UNIT}}',
				],
				'condition' => [
					'background_position' => [ 'initial' ],
				],
				'required' => true,
			]
		);

		$repeater->add_responsive_control(
			'background_position_y',
			[
				'label' => esc_html__( 'Y Position', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'responsive' => true,
				'size_units' => [ 'px', 'em', '%', 'vh' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'tablet_default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => -800,
						'max' => 800,
					],
					'em' => [
						'min' => -100,
						'max' => 100,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
					'vh' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-slides-elementor {{CURRENT_ITEM}}' => 'background-position: {{background_position_x.SIZE}}{{background_position_x.UNIT}} {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'background_position' => [ 'initial' ],
				],
				'required' => true,
			]
		);

		$repeater->add_responsive_control(
			'background_repeat',
			[
				'label'     => esc_html__( 'Background Repeat', 'motta-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'repeat'    => esc_html__( 'Repeat', 'motta-addons' ),
					'no-repeat' => esc_html__( 'No Repeat', 'motta-addons' ),
				],
				'default'   => 'repeat',
				'selectors' => [
					'{{WRAPPER}} .motta-slides-elementor {{CURRENT_ITEM}}' => 'background-repeat: {{VALUE}};',
				],

			]
		);

		$repeater->add_responsive_control(
			'background_color',
			[
				'label'      => esc_html__( 'Background Color', 'motta-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .motta-slides-elementor {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'style', [ 'label' => esc_html__( 'Style', 'motta-addons' ) ] );

		$repeater->add_control(
			'custom_style',
			[
				'label'       => esc_html__( 'Custom', 'motta-addons' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'Set custom style that will only affect this specific slide.', 'motta-addons' ),
			]
		);

		$repeater->add_control(
			'subtitle_heading_name',
			[
				'label' => esc_html__( 'Subtitle', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'subtitle_image_mobile',
			[
				'label'       => esc_html__( 'Hide On Mobile', 'motta-addons' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Hide', 'motta-addons' ),
				'label_off'    => __( 'Show', 'motta-addons' ),
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				]
			]
		);

		$repeater->add_control(
			'subtitle_custom_color',
			[
				'label'      => esc_html__( 'Color', 'motta-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .motta-slides-elementor {{CURRENT_ITEM}} .slick-slide-inner .motta-slide__subtitle' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'subtitle_type',
							'value' => 'text',
						],
					],
				]
			]
		);

		$repeater->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'subtitle_custom_typography',
				'selector' => '{{WRAPPER}} .motta-slides-elementor {{CURRENT_ITEM}} .slick-slide-inner .motta-slide__subtitle',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name'  => 'subtitle_type',
							'value' => 'text',
						],
					],
				],
			]
		);

		$repeater->add_responsive_control(
			'subtitle_custom_size',
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
					'{{WRAPPER}} .motta-slides-elementor {{CURRENT_ITEM}} .slick-slide-inner .motta-slide__subtitle svg' => 'width: {{size}}{{UNIT}};height: auto;',
					'{{WRAPPER}} .motta-slides-elementor {{CURRENT_ITEM}} .slick-slide-inner .motta-slide__subtitle img' => 'max-width: {{size}}{{UNIT}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
						[
							'name' => 'subtitle_type',
							'operator' => '!=',
							'value' => 'text'
						],
					]
				]
			]
		);

		$repeater->add_control(
			'title_heading_name',
			[
				'label' => esc_html__( 'Title', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'title_custom_color',
			[
				'label'      => esc_html__( 'Color', 'motta-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .motta-slides-elementor {{CURRENT_ITEM}} .slick-slide-inner .motta-slide__title' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'before_desc_heading_name',
			[
				'label' => esc_html__( 'Before Description', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'before_desc_custom_color',
			[
				'label'      => esc_html__( 'Color', 'motta-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .motta-slides-elementor {{CURRENT_ITEM}} .motta-slide__before-description' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'desc_heading_name',
			[
				'label' => esc_html__( 'Description', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'content_custom_color',
			[
				'label'      => esc_html__( 'Color', 'motta-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .motta-slides-elementor {{CURRENT_ITEM}} .slick-slide-inner .motta-slide__description' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				]
			]
		);

		$repeater->add_control(
			'after_desc_heading_name',
			[
				'label' => esc_html__( 'After Description', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'after_desc_custom_color',
			[
				'label'      => esc_html__( 'Color', 'motta-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .motta-slides-elementor {{CURRENT_ITEM}} .motta-slide__after-description' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'custom_button_options',
			[
				'label'        => __( 'Primary Button', 'motta-addons' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'motta-addons' ),
				'label_on'     => __( 'Custom', 'motta-addons' ),
				'return_value' => 'yes',
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->start_popover();

		$repeater->add_control(
			'custom_button_style_normal_heading',
			[
				'label' => esc_html__( 'Normal', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'custom_button_background_color',
			[
				'label'      => esc_html__( 'Background Color', 'motta-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .motta-slides-elementor  {{CURRENT_ITEM}} .motta-button-primary' => 'background-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'custom_button_color',
			[
				'label'      => esc_html__( 'Color', 'motta-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .motta-slides-elementor  {{CURRENT_ITEM}} .motta-button-primary' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'custom_button_box_shadow_color',
			[
				'label' => __( 'Box Shadow Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-slides-elementor  {{CURRENT_ITEM}} .motta-button-primary' => '--mt-color__primary--box-shadow: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'custom_button_border_color',
			[
				'label' => __( 'Border Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-slides-elementor  {{CURRENT_ITEM}} .motta-button-primary' => 'border-color: {{VALUE}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'custom_button_style_hover_heading',
			[
				'label' => esc_html__( 'Hover', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

			$repeater->add_control(
				'custom_button_hover_background_color',
				[
					'label' => __( 'Background Color', 'motta-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .motta-slides-elementor  {{CURRENT_ITEM}} .motta-button-primary:hover' => 'background-color: {{VALUE}};',
					],
					'conditions' => [
						'terms' => [
							[
								'name'  => 'custom_style',
								'value' => 'yes',
							],
						],
					],
				]
			);

			$repeater->add_control(
				'custom_button_hover_color',
				[
					'label' => __( 'Color', 'motta-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .motta-slides-elementor  {{CURRENT_ITEM}} .motta-button-primary:hover' => 'color: {{VALUE}};',
					],
					'conditions' => [
						'terms' => [
							[
								'name'  => 'custom_style',
								'value' => 'yes',
							],
						],
					],
				]
			);

			$repeater->add_control(
				'custom_button_box_shadow_color_hover',
				[
					'label' => __( 'Box Shadow Hover Color', 'motta-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .motta-slides-elementor  {{CURRENT_ITEM}} .motta-button-primary:hover' => '--mt-color__primary--box-shadow: {{VALUE}}',
					],
					'conditions' => [
						'terms' => [
							[
								'name'  => 'custom_style',
								'value' => 'yes',
							],
						],
					],
				]
			);

			$repeater->add_control(
				'custom_button_hover_border_color',
				[
					'label' => __( 'Border Color', 'motta-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .motta-slides-elementor  {{CURRENT_ITEM}} .motta-button-primary:hover' => 'border-color: {{VALUE}};',
					],
					'conditions' => [
						'terms' => [
							[
								'name'  => 'custom_style',
								'value' => 'yes',
							],
						],
					],
				]
			);

		$repeater->end_popover();

		$repeater->add_control(
			'custom_button_second_options',
			[
				'label'        => __( 'Secondary Button', 'motta-addons' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'motta-addons' ),
				'label_on'     => __( 'Custom', 'motta-addons' ),
				'return_value' => 'yes',
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->start_popover();

		$repeater->add_control(
			'custom_button_second_style_normal_heading',
			[
				'label' => esc_html__( 'Normal', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'custom_button_second_background_color',
			[
				'label'      => esc_html__( 'Background Color', 'motta-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .motta-slides-elementor  {{CURRENT_ITEM}} .motta-button-second' => 'background-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'custom_button_second_color',
			[
				'label'      => esc_html__( 'Color', 'motta-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .motta-slides-elementor  {{CURRENT_ITEM}} .motta-button-second' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'custom_button_second_box_shadow_color',
			[
				'label' => __( 'Box Shadow Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-slides-elementor  {{CURRENT_ITEM}} .motta-button-second' => '--mt-color__primary--box-shadow: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'custom_button_second_border_color',
			[
				'label' => __( 'Border Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-slides-elementor  {{CURRENT_ITEM}} .motta-button-second' => 'border-color: {{VALUE}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'custom_button_second_style_hover_heading',
			[
				'label' => esc_html__( 'Hover', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

			$repeater->add_control(
				'custom_button_second_hover_background_color',
				[
					'label' => __( 'Background Color', 'motta-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .motta-slides-elementor  {{CURRENT_ITEM}} .motta-button-second:hover' => 'background-color: {{VALUE}};',
					],
					'conditions' => [
						'terms' => [
							[
								'name'  => 'custom_style',
								'value' => 'yes',
							],
						],
					],
				]
			);

			$repeater->add_control(
				'custom_button_second_hover_color',
				[
					'label' => __( 'Color', 'motta-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .motta-slides-elementor  {{CURRENT_ITEM}} .motta-button-second:hover' => 'color: {{VALUE}};',
					],
					'conditions' => [
						'terms' => [
							[
								'name'  => 'custom_style',
								'value' => 'yes',
							],
						],
					],
				]
			);

			$repeater->add_control(
				'custom_button_second_box_shadow_color_hover',
				[
					'label' => __( 'Box Shadow Hover Color', 'motta-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .motta-slides-elementor  {{CURRENT_ITEM}} .motta-button-second:hover' => '--mt-color__primary--box-shadow: {{VALUE}}',
					],
					'conditions' => [
						'terms' => [
							[
								'name'  => 'custom_style',
								'value' => 'yes',
							],
						],
					],
				]
			);

			$repeater->add_control(
				'custom_button_second_hover_border_color',
				[
					'label' => __( 'Border Color', 'motta-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .motta-slides-elementor  {{CURRENT_ITEM}} .motta-button-second:hover' => 'border-color: {{VALUE}};',
					],
					'conditions' => [
						'terms' => [
							[
								'name'  => 'custom_style',
								'value' => 'yes',
							],
						],
					],
				]
			);

		$repeater->end_popover();

		$repeater->add_control(
			'after_button_heading_name',
			[
				'label' => esc_html__( 'After Button', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'after_button_mobile',
			[
				'label'       => esc_html__( 'Hide On Mobile', 'motta-addons' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Hide', 'motta-addons' ),
				'label_off'    => __( 'Show', 'motta-addons' ),
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				]
			]
		);

		$repeater->add_control(
			'after_button_custom_color',
			[
				'label'      => esc_html__( 'Color', 'motta-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .motta-slides-elementor {{CURRENT_ITEM}} .motta-slide__after-button' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'custom_navigation_heading',
			[
				'label'        => __( 'Navigation', 'motta-addons' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'motta-addons' ),
				'label_on'     => __( 'Custom', 'motta-addons' ),
				'return_value' => 'yes',
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->start_popover();

		$repeater->add_control(
			'arrow_heading_name',
			[
				'label' => esc_html__( 'Arrow', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'sliders_arrow_color',
			[
				'label'     => esc_html__( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'sliders_arrow_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'dot_heading_name',
			[
				'label' => esc_html__( 'Dots', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'sliders_dots_bgcolor',
			[
				'label'     => esc_html__( 'Background Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
				'frontend_available' => true,
			]
		);

		$repeater->add_control(
			'sliders_dots_active_bgcolor',
			[
				'label'     => esc_html__( 'Background Color Active', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
				'frontend_available' => true,
			]
		);

		$repeater->end_popover();

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'slides',
			[
				'label'      => esc_html__( 'Slides', 'motta-addons' ),
				'type'       => Controls_Manager::REPEATER,
				'show_label' => true,
				'fields'     => $repeater->get_controls(),
				'default'    => [
					[
						'title'            => esc_html__( 'Slide 1 Title', 'motta-addons' ),
						'description'      => esc_html__( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'motta-addons' ),
						'button_text'      => esc_html__( 'Click Here', 'motta-addons' ),
					],
					[
						'title'          => esc_html__( 'Slide 2 Title', 'motta-addons' ),
						'description'      => esc_html__( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'motta-addons' ),
						'button_text'      => esc_html__( 'Click Here', 'motta-addons' ),
					],
					[
						'title'          => esc_html__( 'Slide 3 Title', 'motta-addons' ),
						'description'      => esc_html__( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'motta-addons' ),
						'button_text'      => esc_html__( 'Click Here', 'motta-addons' ),
					],
				],
			]
		);

		$this->add_responsive_control(
			'slides_height',
			[
				'label'     => esc_html__( 'Height', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-slides-elementor .item-slider' => 'height: {{SIZE}}{{UNIT}}',
				],
				'separator'  => 'before',
			]
		);

		$this->end_controls_section();
	}

	protected function section_content_option() {
		$this->start_controls_section(
			'section_slider_options',
			[
				'label' => esc_html__( 'Slider Options', 'motta-addons' ),
				'type'  => Controls_Manager::SECTION,
			]
		);

		$this->add_responsive_control(
			'slides_to_show',
			[
				'label'              => esc_html__( 'Slides to show', 'motta-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 1,
				'max'                => 10,
				'default'            => 1,
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

		$this->add_control(
			'effect',
			[
				'label'   => esc_html__( 'Effect', 'motta-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'fade'   	 => esc_html__( 'Fade', 'motta-addons' ),
					'slide' 	 => esc_html__( 'Slide', 'motta-addons' ),
					'coverflow'	 => esc_html__( 'Coverflow', 'motta-addons' ),
				],
				'default' => 'fade',
				'toggle'  => false,
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'navigation',
			[
				'label'     => esc_html__( 'Navigation', 'motta-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options' => [
					'none'   => esc_html__( 'None', 'motta-addons' ),
					'arrows' => esc_html__( 'Arrows', 'motta-addons' ),
					'dots' 	 => esc_html__( 'Dots', 'motta-addons' ),
					'both'   => esc_html__( 'Arrows and Dots', 'motta-addons' ),
				],
				'default' => 'arrows',
				'toggle'             => false,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'   => esc_html__( 'Autoplay', 'motta-addons' ),
				'type'    => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'motta-addons' ),
				'label_off'    => __( 'No', 'motta-addons' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'delay',
			[
				'label'     => esc_html__( 'Delay', 'motta-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 3000,
				'description' => esc_html__('Delay between transitions (in ms). If this parameter is not specified, auto play will be disabled', 'motta-addons'),
				'conditions' => [
					'terms' => [
						[
							'name'  => 'autoplay',
							'value' => 'yes',
						]
					],
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label'     => esc_html__( 'Autoplay Speed', 'motta-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1000,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'infinite',
			[
				'label'   => esc_html__( 'Infinite Loop', 'motta-addons' ),
				'type'    => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'motta-addons' ),
				'label_off'    => __( 'No', 'motta-addons' ),
				'return_value' => 'yes',
				'default'      => '',
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

	}

	// Tab Style
	protected function section_style() {
		$this->section_style_content();
		$this->section_style_carousel();
	}

	// Els
	protected function section_style_title() {

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
				'label'      => esc_html__( 'Color', 'motta-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .motta-slides-elementor .slick-slide-inner .motta-slide__title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .motta-slides-elementor .slick-slide-inner .motta-slide__title',
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
					'{{WRAPPER}} .motta-slides-elementor .slick-slide-inner .motta-slide__title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
	}

	protected function section_style_subtitle() {

		$this->add_control(
			'heading_subtitle',
			[
				'label'     => esc_html__( 'Subtitle', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'subtitle_color',
			[
				'label'     => esc_html__( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-slides-elementor .motta-slide__subtitle' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'subtitle_typography',
				'selector' => '{{WRAPPER}} .motta-slides-elementor .motta-slide__subtitle',
			]
		);

		$this->add_responsive_control(
			'subtitle_spacing',
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
					'{{WRAPPER}} .motta-slides-elementor .slick-slide-inner .motta-slide__subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

	}

	protected function section_style_before_desc() {

		$this->add_control(
			'heading_before_description',
			[
				'label'     => esc_html__( 'Before Description', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'before_desc_color',
			[
				'label'      => esc_html__( 'Color', 'motta-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .motta-slides-elementor .motta-slide__before-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'before_desc_typography',
				'selector' => '{{WRAPPER}} .motta-slides-elementor .motta-slide__before-description',
			]
		);

		$this->add_responsive_control(
			'before_desc_spacing',
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
					'{{WRAPPER}} .motta-slides-elementor .motta-slide__before-description' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
	}

	protected function section_style_desc() {
		// Description
		$this->add_control(
			'heading_description',
			[
				'label'     => esc_html__( 'Description', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'     => esc_html__( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-slides-elementor .motta-slide__description' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .motta-slides-elementor .motta-slide__description',
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
					'{{WRAPPER}} .motta-slides-elementor .slick-slide-inner .motta-slide__description' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
	}

	protected function section_style_after_desc() {

		$this->add_control(
			'heading_after_description',
			[
				'label'     => esc_html__( 'After Description', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'after_desc_color',
			[
				'label'      => esc_html__( 'Color', 'motta-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .motta-slides-elementor .motta-slide__after-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'after_desc_typography',
				'selector' => '{{WRAPPER}} .motta-slides-elementor .motta-slide__after-description',
			]
		);

		$this->add_responsive_control(
			'after_desc_spacing',
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
					'{{WRAPPER}} .motta-slides-elementor .motta-slide__after-description' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
	}

	protected function section_style_button() {

		$this->add_control(
			'heading_buton',
			[
				'label'     => esc_html__( 'Primary Button', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'hide_button_mobile',
			[
				'label'        => esc_html__( 'Hide Button On Mobile', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => __( 'Off', 'motta-addons' ),
				'label_on'     => __( 'On', 'motta-addons' ),
				'return_value' => 'yes',
				'default'      => 'no'
			]
		);

		$controls = [
			'size'      => 'medium',
		];

		$this->register_button_style_controls($controls);

		$this->add_control(
			'button_2_heading',
			[
				'label' => esc_html__( 'Secondary Button', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$controls_second = [
			'prefix'   => 'second',
			'skin'     => 'subtle',
			'size'     => 'medium',
		];

		$this->register_button_style_controls( $controls_second );

		$this->add_responsive_control(
			'spacing_between_button',
			[
				'label'     => __( 'Spacing', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-slides-elementor .motta-button-second' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_after_button',
			[
				'label'     => esc_html__( 'After Button', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'after_button_color',
			[
				'label'      => esc_html__( 'Color', 'motta-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .motta-slides-elementor .motta-slide__after-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'after_button_typography',
				'selector' => '{{WRAPPER}} .motta-slides-elementor .motta-slide__after-button',
			]
		);

		$this->add_responsive_control(
			'after_button_spacing',
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
					'{{WRAPPER}} .motta-slides-elementor .motta-slide__after-button' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);
	}

	protected function section_style_image() {
		$this->add_control(
			'heading_image',
			[
				'label'     => esc_html__( 'Image', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);


		$this->add_responsive_control(
			'image_custom_height',
			[
				'label'     => __( 'Height', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 1000,
						'min' => 0,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .motta-slides-elementor .motta-slide__image' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_custom_position',
			[
				'label'                => esc_html__( 'Position Mobile', 'motta-addons' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => [
					'top'   => [
						'title' => esc_html__( 'Top Content', 'motta-addons' ),
						'icon'  => 'eicon-v-align-top',
					],
					'bottom'  => [
						'title' => esc_html__( 'Bottom Content', 'motta-addons' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'selectors'            => [
					'(mobile){{WRAPPER}} .motta-slides-elementor .motta-slide__image' => 'order: {{VALUE}}',
				],
				'selectors_dictionary' => [
					'top'   => '0',
					'bottom'  => '2',
				],
				'devices' => [ 'mobile'],
			]
		);
	}

	protected function section_style_content() {
		$this->start_controls_section(
			'section_style_slides',
			[
				'label' => esc_html__( 'Slides', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
			'slides_container_width',
			[
				'label'      => esc_html__( 'Container Width', 'motta-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1900,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-slides-elementor .slick-slide-inner' => 'max-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .motta-slides-elementor .motta-swiper-arrows-inner' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'slides_padding',
			[
				'label'      => esc_html__( 'Padding', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-slides-elementor .slick-slide-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.motta-rtl-smart {{WRAPPER}} .motta-slides-elementor .slick-slide-inner' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'slides_radius',
			[
				'label'      => __( 'Border Radius', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-slides-elementor .item-slider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .motta-slides-elementor .motta-slides-elementor__wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'slides_horizontal_position',
			[
				'label'                => esc_html__( 'Horizontal Position', 'motta-addons' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => [
					'left'   => [
						'title' => esc_html__( 'Left', 'motta-addons' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'motta-addons' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'motta-addons' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'selectors'            => [
					'{{WRAPPER}} .motta-slides-elementor .slick-slide-inner' => 'justify-content: {{VALUE}}',
				],
				'selectors_dictionary' => [
					'left'   => 'flex-start',
					'center' => 'center',
					'right'  => 'flex-end',
				],
			]
		);

		$this->add_responsive_control(
			'slides_vertical_position',
			[
				'label'                => esc_html__( 'Vertical Position', 'motta-addons' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => [
					'top'   => [
						'title' => esc_html__( 'Top', 'motta-addons' ),
						'icon'  => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'motta-addons' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'bottom'  => [
						'title' => esc_html__( 'Bottom', 'motta-addons' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'selectors'            => [
					'{{WRAPPER}} .motta-slides-elementor .slick-slide-inner' => 'align-items: {{VALUE}}',
				],
				'selectors_dictionary' => [
					'top'   => 'flex-start',
					'middle' => 'center',
					'bottom'  => 'flex-end',
				],
			]
		);

		$this->add_responsive_control(
			'slides_text_align',
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
				'default'     => 'left',
				'selectors'   => [
					'{{WRAPPER}} .motta-slides-elementor .slick-slide-inner' => 'text-align: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'slides_content_width',
			[
				'label'      => esc_html__( 'Width', 'motta-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1900,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-slides-elementor .motta-slide__content' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .motta-slides-elementor .motta-slide__image' => 'width: calc(100% - {{SIZE}}{{UNIT}} );',
					'(mobile){{WRAPPER}} .motta-slides-elementor .motta-slide__image' => 'width: 100%;',
				],
			]
		);

		$this->add_responsive_control(
			'slides_content_padding',
			[
				'label'      => esc_html__( 'Padding', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'placeholder' => [
					'top' => '38',
					'right' => '48',
					'bottom' => '48',
					'left' => '48',
				],
				'selectors'  => [
					'{{WRAPPER}} .motta-slides-elementor .motta-slide__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.motta-rtl-smart {{WRAPPER}} .motta-slides-elementor .motta-slide__content' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				],
			]
		);

		$this->section_style_subtitle();

		$this->section_style_title();

		$this->section_style_before_desc();

		$this->section_style_desc();

		$this->section_style_after_desc();

		$this->section_style_button();

		$this->section_style_image();

		$this->end_controls_section();

	}

	protected function section_style_carousel() {
		// Arrows
		$this->start_controls_section(
			'section_style_arrows',
			[
				'label' => esc_html__( 'Slider Options', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'centeredSlides',
			[
				'label'     => __( 'Center Mode on Mobile', 'motta-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'motta-addons' ),
				'label_on'  => __( 'On', 'motta-addons' ),
				'default'   => '',
				'frontend_available' => true,
				'prefix_class' => 'motta-slides__centeredslides-'
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
			'arrows_position',
			[
				'label'     => esc_html__( 'Position', 'motta-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'center-center',
				'options'   => [
					'center-center'   	=> esc_html__( 'Center Center', 'motta-addons' ),
					'right-bottom' 	=> esc_html__( 'Right Bottom', 'motta-addons' ),
					'left-bottom' 	=> esc_html__( 'Left Bottom', 'motta-addons' ),
				],
				'prefix_class' => 'motta-slides__arrow-position-',
			]
		);

		$this->add_control(
			'sliders_arrow_style',
			[
				'label'        => __( 'Item Options', 'motta-addons' ),
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
					'{{WRAPPER}} .motta-slides-elementor .motta-swiper-button' => 'font-size: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-slides-elementor .motta-swiper-button' => 'width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-slides-elementor .motta-swiper-button' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-slides-elementor .motta-swiper-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-slides-elementor .motta-swiper-button' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .motta-slides-elementor .motta-swiper-button' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}}.motta-slides__arrow-position-center-center .motta-slides-elementor .motta-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}}.motta-slides__arrow-position-center-center .motta-slides-elementor .motta-swiper-button-prev' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
					'{{WRAPPER}}.motta-slides__arrow-position-center-center .motta-slides-elementor .motta-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}}.motta-slides__arrow-position-center-center .motta-slides-elementor .motta-swiper-button-next' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
					'{{WRAPPER}}.motta-slides__arrow-position-right-bottom .motta-slides-elementor .motta-swiper-button-prev' => 'right:  calc( 12px + {{sliders_arrows_width.SIZE}}{{sliders_arrows_width.UNIT}} + {{SIZE}}{{UNIT}} );',
					'.rtl {{WRAPPER}}.motta-slides__arrow-position-right-bottom .motta-slides-elementor .motta-swiper-button-prev' => 'left:  calc( 12px + {{sliders_arrows_width.SIZE}}{{sliders_arrows_width.UNIT}} + {{SIZE}}{{UNIT}} ); right: auto;',
					'{{WRAPPER}}.motta-slides__arrow-position-right-bottom .motta-slides-elementor .motta-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}}.motta-slides__arrow-position-right-bottom .motta-slides-elementor .motta-swiper-button-next' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
					'{{WRAPPER}}.motta-slides__arrow-position-left-bottom .motta-slides-elementor .motta-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}}.motta-slides__arrow-position-left-bottom .motta-slides-elementor .motta-swiper-button-next' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
					'{{WRAPPER}}.motta-slides__arrow-position-left-bottom .motta-slides-elementor .motta-swiper-button-next' => 'left: calc( 12px + {{sliders_arrows_width.SIZE}}{{sliders_arrows_width.UNIT}} + {{SIZE}}{{UNIT}} );',
					'.rtl {{WRAPPER}}.motta-slides__arrow-position-left-bottom .motta-slides-elementor .motta-swiper-button-prev' => 'right: calc( 12px + {{sliders_arrows_width.SIZE}}{{sliders_arrows_width.UNIT}} + {{SIZE}}{{UNIT}} ); left: auto;',
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
					'{{WRAPPER}}.motta-slides__arrow-position-center-center .motta-swiper-button' => 'top: {{SIZE}}{{UNIT}} ;',
					'{{WRAPPER}}.motta-slides__arrow-position-right-bottom .motta-swiper-button' => 'bottom: {{SIZE}}{{UNIT}} ;',
					'{{WRAPPER}}.motta-slides__arrow-position-left-bottom .motta-swiper-button' => 'bottom: {{SIZE}}{{UNIT}} ;',
				],
			]
		);


		// Dots
		$this->add_control(
			'sliders_dots_style_heading',
			[
				'label' => esc_html__( 'Dots', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sliders_dots_position',
			[
				'label'     => esc_html__( 'Position', 'motta-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'center',
				'options'   => [
					'center'   	=> esc_html__( 'Center', 'motta-addons' ),
					'left' 	=> esc_html__( 'Left', 'motta-addons' ),
					'right' 	=> esc_html__( 'Right', 'motta-addons' ),
				],
				'prefix_class' => 'motta-slides__dots-position-',
			]
		);

		$this->add_control(
			'sliders_dots_bg_overlay',
			[
				'label'     => __( 'Background Overlay', 'motta-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'motta-addons' ),
				'label_on'  => __( 'On', 'motta-addons' ),
				'default'   => '',
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
				'condition' => [
					'sliders_dots_bg_overlay' => 'yes',
				],
			]
		);

		$this->add_control(
			'sliders_dots_style',
			[
				'label'        => __( 'Item Options', 'motta-addons' ),
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
					'{{WRAPPER}} .motta-slides-elementor .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .motta-slides-elementor .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-slides-elementor .swiper-pagination-bullet' => 'background-color : {{VALUE}};',
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
					'{{WRAPPER}} .motta-slides-elementor .swiper-pagination-bullet-active, {{WRAPPER}} .motta-slides-elementor .swiper-pagination-bullet:hover' => 'background-color : {{VALUE}};',
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
						'min' => -200,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .motta-slides-elementor .swiper-pagination' => 'bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'sliders_dots_horizontal_spacing',
			[
				'label'      => esc_html__( 'Horizontal Spacing', 'motta-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}}.motta-slides__dots-position-center  .motta-slides-elementor .swiper-pagination' => 'left: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}}.motta-slides__dots-position-center  .motta-slides-elementor .swiper-pagination' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
					'{{WRAPPER}}.motta-slides__dots-position-right  .motta-slides-elementor .swiper-pagination' => 'right: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}}.motta-slides__dots-position-right  .motta-slides-elementor .swiper-pagination' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
					'{{WRAPPER}}.motta-slides__dots-position-left  .motta-slides-elementor .swiper-pagination' => 'left: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}}.motta-slides__dots-position-left  .motta-slides-elementor .swiper-pagination' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
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

		if ( empty( $settings['slides'] ) ) {
			return;
		}

		$nav        = $settings['navigation'];
		$nav_tablet = empty( $settings['navigation_tablet'] ) ? $nav : $settings['navigation_tablet'];
		$nav_mobile = empty( $settings['navigation_mobile'] ) ? $nav : $settings['navigation_mobile'];

		$classes = [
			'motta-slides-elementor',
			'motta-swiper-carousel-elementor',
			'motta-swiper-slider-elementor',
			'navigation-' . $nav,
			'navigation-tablet-' . $nav_tablet,
			'navigation-mobile-' . $nav_mobile,
		];

		$slide_count = 0;
		$button_slide_class = '';

		if( $settings['hide_button_mobile'] == 'yes' ){
			$button_slide_class .= 'hidden-xs';
		}

		$icon_left  = $settings['arrows_icon'] =='regular' ?  'left' : 'arrow-left-long';
		$icon_right = $settings['arrows_icon'] =='regular' ?  'right' : 'arrow-right-long';
		$dots_bg    = $settings['sliders_dots_bg_overlay'] == 'yes' ? 'swiper-pagination--background' : '';

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		// Button
		$button_primary_class = 'motta-button motta-button-primary';

		if( $settings['primary_button_size'] !== 'normal' ) {
			$button_primary_class .= ' motta-button--' . $settings['primary_button_size'] . ' ';
		}

		$button_primary_class .= ' motta-button--' . $settings['primary_button_skin'] . ' ';

		if( $settings['primary_button_shape'] !== 'default' ) {
			$button_primary_class .= ' motta-shape--' . $settings['primary_button_shape'] . ' ';
		}

		$button_second_class = 'motta-button motta-button-second';

		if( $settings['second_button_size'] !== 'normal' ) {
			$button_second_class .= ' motta-button--' . $settings['second_button_size'] . ' ';
		}

		$button_second_class .= ' motta-button--' . $settings['second_button_skin'] . ' ';

		if( $settings['second_button_shape'] !== 'default' ) {
			$button_second_class .= ' motta-shape--' . $settings['second_button_shape'] . ' ';
		}

		?>
			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
				<div class="motta-slides-elementor__wrapper swiper-container">
					<div class="motta-slides-elementor__inner swiper-wrapper">
						<?php
							foreach ( $settings['slides'] as $slide ) {
								$subtitle_image_mobile = ! empty( $slide['subtitle_image_mobile'] ) ? 'hidden-xs' : '';
								$data_arrow		  = $data_dots	= array();
								$slide_classes = ! empty( $slide['image']['url'] ) ? 'slide-has-image' : '';

								if( ! empty( $slide['sliders_arrow_color'] ) ) {
									$data_arrow['color'] = $slide['sliders_arrow_color'];
								}

								if( ! empty( $slide['sliders_arrow_background_color'] ) ) {
									$data_arrow['background_color'] = $slide['sliders_arrow_background_color'];
								}

								if( ! empty( $slide['sliders_dots_bgcolor'] ) ) {
									$data_dots['color'] = $slide['sliders_dots_bgcolor'];
								}

								if( ! empty( $slide['sliders_dots_active_bgcolor'] ) ) {
									$data_dots['color_active'] = $slide['sliders_dots_active_bgcolor'];
								}

								$key_primary_button = 'primary_button_' . $slide_count;
								$key_second_button = 'second_button_' . $slide_count;

								?>
									<div class="elementor-repeater-item-<?php echo esc_attr( $slide['_id'] ); ?> item-slider swiper-slide" data-dots="<?php echo htmlspecialchars( json_encode( $data_dots ) ); ?>" data-arrow="<?php echo htmlspecialchars( json_encode( $data_arrow ) ); ?>">
										<div class="slick-slide-inner <?php echo esc_attr( $slide_classes ); ?>">
											<div class="motta-slide__content">
												<?php
													if( $slide['subtitle_type'] == 'text' ) {
														echo ! empty( $slide['subtitle'] ) ? '<div class="motta-slide__subtitle ' . esc_attr( $subtitle_image_mobile ) . '">' . $slide['subtitle'] . '</div>' : '';
													} elseif ( $slide['subtitle_type'] == 'image' ) {
														echo ! empty( $slide['subtitle_image']['url'] ) ? '<div class="motta-slide__subtitle motta-slide__subtitle-image ' . esc_attr( $subtitle_image_mobile ) . '"><img alt="'. $slide['subtitle'] .'" src="' . esc_url( $slide['subtitle_image']['url'] ) .'"></div>' : '';
													} elseif ( $slide['subtitle_type'] == 'external' ) {
														echo ! empty( $slide['subtitle_external_url'] ) ? '<div class="motta-slide__subtitle motta-slide__subtitle-image ' . esc_attr( $subtitle_image_mobile ) . '"><img alt="'. $slide['subtitle'] .'" src="' . esc_url( $slide['subtitle_external_url'] ) .'"></div>' : '';
													} else {
														if( ! empty( $slide['subtitle_icon']['value'] ) ) {
															echo '<div class="motta-slide__subtitle motta-slide__subtitle-icon ' . esc_attr( $subtitle_image_mobile ) . '">';
																if ( 'svg' === $slide['subtitle_icon']['library'] ) {
																	echo Icons_Manager::render_uploaded_svg_icon( $slide['subtitle_icon']['value'] );
																} else {
																	echo Icons_Manager::render_font_icon( $slide['subtitle_icon'], [ 'aria-hidden' => 'true' ], 'i' );
																}
															echo '</div>';
														}
													}
												?>
												<?php if ( $slide['title'] ) : ?>
													<div class="motta-slide__title"><?php echo $slide['title']; ?></div>
												<?php endif; ?>

												<?php if ( $slide['before_description'] ) : ?>
													<div class="motta-slide__before-description"><?php echo $slide['before_description']; ?></div>
												<?php endif; ?>

												<?php if ( $slide['description'] ) : ?>
													<div class="motta-slide__description"><?php echo $slide['description']; ?></div>
												<?php endif; ?>

												<?php if ( $slide['after_description'] ) : ?>
													<div class="motta-slide__after-description"><?php echo $slide['after_description']; ?></div>
												<?php endif; ?>
												<div class="motta-slide-button <?php echo esc_attr( $button_slide_class ); ?>">
													<?php echo $slide['primary_button_text'] ? Helper::control_url( $key_primary_button, $slide['primary_button_link'], '<span class="motta-button__text">' . $slide['primary_button_text'] . '</span>', ['class' => esc_attr($button_primary_class)] ) : ''; ?>
													<?php echo $slide['second_button_text'] ? Helper::control_url( $key_second_button, $slide['second_button_link'], '<span class="motta-button__text">' . $slide['second_button_text'] . '</span>', ['class' => esc_attr($button_second_class)] ) : ''; ?>
												</div>
												<?php if ( $slide['after_button'] ) : ?>
													<div class="motta-slide__after-button <?php echo ! empty( $slide['after_button_mobile'] ) ? 'hidden-xs' : ''; ?>"><?php echo $slide['after_button']; ?></div>
												<?php endif; ?>

											</div>

											<?php echo ! empty( $slide['image']['url'] ) ? '<div class="motta-slide__image"></div>' : ''; ?>
										</div>
										<?php echo ! empty( $slide['primary_button_link']['url'] ) && $slide['button_link_type'] == 'slide' ? Helper::control_url( 'btn_all', $slide['primary_button_link'], '', ['class' => 'button-link-all'] ) : ''; ?>
									</div>
								<?php

								$slide_count ++;
							}
						?>
					</div>
				</div>
				<div class="motta-swiper-arrows-inner">
					<?php
						echo \Motta\Addons\Helper::get_svg( esc_attr( $icon_left ), 'ui' , [ 'class' => 'motta-swiper-button-prev swiper-button motta-swiper-button' ]  );
						echo \Motta\Addons\Helper::get_svg( esc_attr( $icon_right ), 'ui' , [ 'class' => 'motta-swiper-button-next swiper-button motta-swiper-button' ] );
					?>
				</div>
				<div class="swiper-pagination <?php echo esc_attr( $dots_bg ); ?>"></div>
			</div>
		<?php
	}
}
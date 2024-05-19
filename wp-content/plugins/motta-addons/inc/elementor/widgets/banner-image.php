<?php

namespace Motta\Addons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Embed;
use Motta\Addons\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Banner widget
 */
class Banner_Image extends Widget_Base {
	use \Motta\Addons\Elementor\Widgets\Traits\Button_Trait;

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-banner';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( '[Motta] Banner Image', 'motta-addons' );
	}

	/**
	 * Retrieve the widget circle.
	 *
	 * @return string Widget circle.
	 */
	public function get_icon() {
		return 'eicon-banner';
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

	protected function section_content() {
		$this->start_controls_section(
			'section_content',
			[ 'label' => esc_html__( 'Banner', 'motta-addons' ) ]
		);

		$this->start_controls_tabs( 'banner_tabs' );

		$this->start_controls_tab( 'banner_tabs_content', [ 'label' => esc_html__( 'Content', 'motta-addons' ) ] );

		$this->add_control(
			'before_title',
			[
				'label'   => esc_html__( 'Before Title', 'motta-addons' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => '',
			]
		);

		$this->add_control(
			'title_type',
			[
				'label' => esc_html__( 'Title Type', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'text'   => esc_html__( 'Text', 'motta-addons' ),
					'svg' 	 => esc_html__( 'SVG Icon', 'motta-addons' ),
					'image'  => esc_html__( 'Image', 'motta-addons' ),
					'external' 	=> esc_html__( 'External Link', 'motta-addons' ),
				],
				'default' => 'text',
			]
		);

		$this->add_control(
			'title',
			[
				'label'   => esc_html__( 'Title', 'motta-addons' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'This is title', 'motta-addons' ),
				'conditions' => [
					'terms' => [
						[
							'name' => 'title_type',
							'operator' => '==',
							'value' => 'text'
						],
					]
				]
			]
		);

		$this->add_control(
			'title_icon',
			[
				'label' => __( 'SVG Icon', 'motta-addons' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fa fa-star',
					'library' => 'fa-solid',
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'title_type',
							'operator' => '==',
							'value' => 'svg',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'title_image',
			[
				'label'   => esc_html__( 'Title Image', 'motta-addons' ),
				'type'    => Controls_Manager::MEDIA,
				'conditions' => [
					'terms' => [
						[
							'name' => 'title_type',
							'operator' => '==',
							'value' => 'image'
						],
					],
				],
			]
		);

		$this->add_control(
			'title_external_url',
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
							'operator' => '==',
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
				'default' => '',
			]
		);

		$controls = [
			'button_text_default' => __( 'Shop Now', 'motta-addons' ),
			'button_text_label' => __( 'Primary Button Text', 'motta-addons' ),
			'button_text_link' => __( 'Primary Button Link', 'motta-addons' )
		];

		$this->register_button_content_controls( $controls );

		$this->add_control(
			'button_link_type',
			[
				'label'   => esc_html__( 'Apply Primary Button Link On', 'motta-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'only' => esc_html__( 'Button Only', 'motta-addons' ),
					'all'  => esc_html__( 'Whole Banner', 'motta-addons' ),
				],
				'default' => 'all',
				'toggle'  => false,
			]
		);

		$controls_second = [
			'prefix'			=> 'second',
			'button_text_label' => __( 'Secondary Button Text', 'motta-addons' ),
			'button_text_link' => __( 'Secondary Button Link', 'motta-addons' )
		];

		$this->register_button_content_controls( $controls_second );

		$this->end_controls_tab();

		$this->start_controls_tab( 'banner_tabs_image', [ 'label' => esc_html__( 'Media', 'motta-addons' ) ] );

		$this->add_control(
			'image_source',
			[
				'label' => esc_html__( 'Media Source', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'library' => esc_html__( 'Media Library', 'motta-addons' ),
					'external' 	=> esc_html__( 'External Image', 'motta-addons' ),
					'youtube' 	=> esc_html__( 'Youtube', 'motta-addons' ),
					'hosted' 	=> esc_html__( 'Self Hosted', 'motta-addons' ),
				],
				'default' => 'library',
			]
		);

		$this->add_responsive_control(
			'image',
			[
				'label'   => esc_html__( 'Image', 'motta-addons' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => 'https://via.placeholder.com/1140x400/f1f1f1?text=BannerImage',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'image_source',
							'value' => 'library',
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-banner .motta-banner__featured-image' => 'background-image: url("{{URL}}");',
				],
			]
		);

		$this->add_control(
			'youtube_url',
			[
				'label' => esc_html__( 'Youtube URL', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => 'https://www.youtube.com/watch?v=XHOmBV4js_E',
				'conditions' => [
					'terms' => [
						[
							'name' => 'image_source',
							'value' => 'youtube',
						],
					],
				],
			]
		);

		$this->add_control(
			'hosted_url',
			[
				'label' => esc_html__( 'URL', 'motta-addons' ),
				'type' => Controls_Manager::URL,
				'autocomplete' => false,
				'options' => false,
				'label_block' => true,
				'show_label' => false,
				'media_type' => 'video',
				'placeholder' => esc_html__( 'Enter your URL', 'motta-addons' ),
				'condition' => [
					'image_source' => 'hosted',
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
							'name' => 'image_source',
							'value' => 'external',
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-banner .motta-banner__featured-image' => 'background-image: url("{{URL}}");',
				],
			]
		);

		$this->add_control(
			'banner_background_color',
			[
				'label' => __( 'Background Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-banner__featured-image' => 'background-color: {{VALUE}}',
				],
				'separator' => 'before',
				'condition' => [
					'image_source' => ['library', 'external'],
				],
			]
		);

		$this->add_responsive_control(
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
					'{{WRAPPER}} .motta-banner__featured-image' => 'background-position: {{VALUE}};',
				],
				'condition' => [
					'image_source' => ['library', 'external'],
				],
			]
		);

		$this->add_responsive_control(
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
						'min' => -1000,
						'max' => 1000,
					],
					'vw' => [
						'min' => -1000,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-banner__featured-image' => 'background-position: {{SIZE}}{{UNIT}} {{background_position_y.SIZE}}{{background_position_y.UNIT}}',
				],
				'condition' => [
					'background_position' => [ 'initial' ],
					'image_source' => ['library', 'external'],
				],

				'required' => true,
			]
		);

		$this->add_responsive_control(
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
					'{{WRAPPER}} .motta-banner__featured-image' => 'background-position: {{background_position_x.SIZE}}{{background_position_x.UNIT}} {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'background_position' => [ 'initial' ],
					'image_source' => ['library', 'external'],
				],
				'required' => true,
			]
		);

		$this->add_responsive_control(
			'background_repeat',
			[
				'label'     => esc_html__( 'Background Repeat', 'motta-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'no-repeat',
				'options'   => [
					'no-repeat' => esc_html__( 'No-repeat', 'motta-addons' ),
					'repeat' 	=> esc_html__( 'Repeat', 'motta-addons' ),
					'repeat-x'  => esc_html__( 'Repeat-x', 'motta-addons' ),
					'repeat-y'  => esc_html__( 'Repeat-y', 'motta-addons' ),
				],
				'selectors' => [
					'{{WRAPPER}} .motta-banner__featured-image' => 'background-repeat: {{VALUE}}',
				],
				'condition' => [
					'image_source' => ['library', 'external'],
				],
			]
		);

		$this->add_responsive_control(
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
					'{{WRAPPER}} .motta-banner__featured-image' => 'background-size: {{VALUE}}',
				],
				'condition' => [
					'image_source' => ['library', 'external'],
				],
			]
		);

		$this->add_control(
			'video_options',
			[
				'label' => esc_html__( 'Video Options', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'image_source' => ['youtube', 'vimeo', 'hosted'],
				]
			]
		);


		$this->add_control(
			'play_on_mobile',
			[
				'label' => esc_html__( 'Play On Mobile', 'motta-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'image_source' => ['youtube', 'vimeo', 'hosted'],
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'video_mute',
			[
				'label' => esc_html__( 'Mute', 'motta-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'condition' => [
					'image_source' => ['youtube', 'vimeo', 'hosted'],
				]
			]
		);

		$this->add_control(
			'video_loop',
			[
				'label' => esc_html__( 'Loop', 'motta-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'image_source' => ['youtube', 'vimeo', 'hosted'],
				],
			]
		);


		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_countdown',
			[ 'label'   => esc_html__( 'CountDown', 'motta-addons' ) ]
		);

		$this->add_control(
			'countdown_text',
			[
				'label'   => esc_html__( 'CountDown Text', 'motta-addons' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => '',
			]
		);

		$this->add_control(
			'countdown_date',
			[
				'label'   => esc_html__( 'CountDown Date', 'motta-addons' ),
				'type'    => Controls_Manager::DATE_TIME,
				'default' => '',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_sale',
			[ 'label'   => esc_html__( 'Sale', 'motta-addons' ) ]
		);

		$this->add_control(
			'sale_type',
			[
				'label' => __( 'Icon Type', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'text' => __( 'Text', 'motta-addons' ),
					'icon' => __( 'Icon', 'motta-addons' ),
					'image' => __( 'Image', 'motta-addons' ),
					'external' => __( 'External', 'motta-addons' ),
				],
				'default' => 'text',
			]
		);

		$this->add_control(
			'sale_icon',
			[
				'label' => __( 'Icon', 'motta-addons' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fa fa-star',
					'library' => 'fa-solid',
				],
				'condition' => [
					'sale_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'sale_image',
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
					'sale_type' => 'image',
				],
			]
		);

		$this->add_control(
			'sale_url',
			[
				'label' => __( 'External Icon URL', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'sale_type' => 'external',
				],
			]
		);

		$this->add_control(
			'sale_text',
			[
				'label'       => esc_html__( 'Sale Text', 'motta-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your text', 'motta-addons' ),
				'label_block' => true,
				'default'     => '',
				'condition' => [
					'sale_type' => 'text',
				],
			]
		);

		$this->add_control(
			'sale_unit',
			[
				'label'       => esc_html__( 'Sale Unit', 'motta-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your text', 'motta-addons' ),
				'label_block' => true,
				'default'     => '',
				'condition' => [
					'sale_type' => 'text',
				],
			]
		);

		$this->end_controls_section();
	}

	// Tab Style
	protected function section_style() {
		$this->section_style_banner();
		$this->section_style_content();
		$this->section_style_countdown();
		$this->section_style_sale();
	}

	protected function section_style_content() {
		// Content
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => __( 'Content', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_text_align',
			[
				'label'       => esc_html__( 'Alignment', 'motta-addons' ),
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
				'default'     => '',
				'selectors'   => [
					'{{WRAPPER}} .motta-banner .motta-banner__content' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'content_spacing',
			[
				'label'      => esc_html__( 'Padding', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-banner__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.motta-rtl-smart {{WRAPPER}} .motta-banner__content' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				],
			]
		);

		// before title
		$this->add_control(
			'content_style_beforetitle',
			[
				'label' => __( 'Before Title', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' =>  ['before_title!' => ''] ,
			]
		);

		$this->add_responsive_control(
			'before_title_spacing',
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
					'{{WRAPPER}} .motta-banner__before-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'condition' =>  ['before_title!' => ''] ,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'before_title_typography',
				'selector' => '{{WRAPPER}} .motta-banner__before-title',
				'condition' =>  ['before_title!' => ''] ,
			]
		);

		$this->add_control(
			'before_title_color',
			[
				'label'     => __( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-banner__before-title' => 'color: {{VALUE}};',
				],
				'condition' =>  ['before_title!' => ''] ,
			]
		);

		// title
		$this->add_control(
			'content_style_title',
			[
				'label' => __( 'Title', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
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
					'{{WRAPPER}} .motta-banner__title svg' => 'width: {{size}}{{UNIT}};height: auto;',
					'{{WRAPPER}} .motta-banner__title img' => 'max-width: {{size}}{{UNIT}};',
				],
				'conditions' => [
					'terms' => [
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
					'{{WRAPPER}} .motta-banner__title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .motta-banner__title',
				'conditions' => [
					'terms' => [
						[
							'name' => 'title_type',
							'operator' => '==',
							'value' => 'text'
						],
					],
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-banner__title' => 'color: {{VALUE}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'title_type',
							'operator' => '==',
							'value' => 'text'
						],
					],
				],
			]
		);

		// Description
		$this->add_control(
			'content_style_des',
			[
				'label' => __( 'Description', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_position',
			[
				'label'     => __( 'Description Position', 'motta-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'before',
				'options'   => [
					'before'  => __( 'Before Button', 'motta-addons' ),
					'after' => __( 'After Button', 'motta-addons' ),
				],
			]
		);

		$this->add_responsive_control(
			'desc_spacing',
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
					'{{WRAPPER}} .motta-banner__description' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'description_position',
							'operator' => '==',
							'value' => 'before'
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'desc_spacing_top',
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
					'{{WRAPPER}} .motta-banner__description' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'description_position',
							'operator' => '==',
							'value' => 'after'
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'desc_typography',
				'selector' => '{{WRAPPER}} .motta-banner__description',
			]
		);

		$this->add_control(
			'desc_color',
			[
				'label'     => __( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-banner__description' => 'color: {{VALUE}};',
				],
			]
		);

		// button
		$this->add_control(
			'content_style_button',
			[
				'label' => __( 'Primary Button', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_hide_on_mobile',
			[
				'label'        => esc_html__( 'Hide Button On Mobile', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => __( 'Off', 'motta-addons' ),
				'label_on'     => __( 'On', 'motta-addons' ),
				'return_value' => 'none',
				'default'      => '',
				'selectors' => [
					'(mobile){{WRAPPER}} .motta-button-primary' => 'display: {{VALUE}}',
				],
			]
		);

		$controls = [
			'size'      => 'medium',
		];

		$this->register_button_style_controls($controls);

		// button 2
		$this->add_control(
			'content_style_button_2',
			[
				'label' => __( 'Secondary Button', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' =>  ['second_button_text!' => ''] ,
			]
		);

		$this->add_control(
			'button_2_hide_on_mobile',
			[
				'label'        => esc_html__( 'Hide Button On Mobile', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => __( 'Off', 'motta-addons' ),
				'label_on'     => __( 'On', 'motta-addons' ),
				'return_value' => 'none',
				'default'      => '',
				'selectors' => [
					'(mobile){{WRAPPER}} .motta-button-second' => 'display: {{VALUE}}',
				],
				'condition' =>  ['second_button_text!' => ''] ,
			]
		);

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
					'{{WRAPPER}} .motta-button-second' => 'margin-left: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} .motta-button-second' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: 0;',
				],
				'condition' =>  ['second_button_text!' => ''] ,
			]
		);

		$controls_second = [
			'prefix'   => 'second',
			'skin'     => 'subtle',
			'size'     => 'medium',
			'section_condition' => ['second_button_text!' => '']
		];

		$this->register_button_style_controls( $controls_second );

		$this->end_controls_section();

	}

	protected function section_style_banner() {
		// Content
		$this->start_controls_section(
			'section_banner_style',
			[
				'label' => __( 'Banner', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_hover_effect',
			[
				'label' => esc_html__( 'Hover Effect', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'image-zoom',
				'options' => [
					'' => esc_html__( 'None', 'motta-addons' ),
					'image-zoom' 	  => esc_html__( 'Zoom', 'motta-addons' ),
					'zoom-fade' 	  => esc_html__( 'Zoom Fade', 'motta-addons' ),
					'fade-in' 	  	  => esc_html__( 'Fade In', 'motta-addons' ),
					'fade-out' 	  	  => esc_html__( 'Fade Out', 'motta-addons' ),
					'blur' 	  	  	  => esc_html__( 'Blur', 'motta-addons' ),
					'overlay' 	  	  => esc_html__( 'Overlay', 'motta-addons' ),
				],
				'prefix_class' => 'motta-banner__',
			]
		);

		$this->add_control(
			'image_duration_time',
			[
				'label'   => esc_html__( 'Effect duration time (s)', 'motta-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 1,
				'max'                => 20,
				'default'            => 0.3,
				'selectors' => [
					'{{WRAPPER}} .motta-banner__featured-image' => '--motta-banner__duration-time: {{SIZE}}s;',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'image_hover_effect',
							'operator' => '!=',
							'value' => ''
						],
					]
				]
			]
		);

		$this->add_control(
			'image_effect_overlay_bg_color',
			[
				'label'     => __( 'Overlay Background Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-banner__featured-image:before' => 'background-color: {{VALUE}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'image_hover_effect',
							'operator' => '==',
							'value' => 'overlay'
						],
					]
				]
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label'     => esc_html__( 'Height', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'desktop_default' => [
					'size' => 400,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 240,
					'unit' => 'px',
				],
				'range'     => [
					'px' => [
						'min' => 100,
						'max' => 600,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-banner__wrapper' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'banner_horizontal_position',
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
				'default'              => '',
				'selectors'            => [
					'{{WRAPPER}} .motta-banner .motta-banner__wrapper' => 'align-items: {{VALUE}}',
					'{{WRAPPER}} .motta-banner .motta-banner__countdown' => 'align-items: {{VALUE}}',
				],
				'selectors_dictionary' => [
					'left'   => 'flex-start',
					'center' => 'center',
					'right'  => 'flex-end',
				],
			]
		);

		$this->add_responsive_control(
			'banner_vertical_position',
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
					'{{WRAPPER}} .motta-banner .motta-banner__wrapper' => 'justify-content: {{VALUE}}',
					'{{WRAPPER}} .motta-banner .motta-banner__countdown' => 'justify-content: {{VALUE}}',
				],
				'selectors_dictionary' => [
					'top'   => 'flex-start',
					'middle' => 'center',
					'bottom'  => 'flex-end',
				],
			]
		);

		$this->add_responsive_control(
			'banner_text_align',
			[
				'label'       => esc_html__( 'Alignment', 'motta-addons' ),
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
				'default'     => '',
				'selectors'   => [
					'{{WRAPPER}} .motta-banner .motta-banner__wrapper' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'banner_spacing',
			[
				'label'      => esc_html__( 'Padding', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [],
				'size_units' => [ 'px', 'em', '%' ],
				'placeholder' => [
					'top' => '26',
					'right' => '32',
					'bottom' => '26',
					'left' => '32',
				],
				'selectors'  => [
					'{{WRAPPER}} .motta-banner__wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.motta-rtl-smart {{WRAPPER}} .motta-banner__wrapper' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'border_radius_img',
			[
				'label'      => __( 'Border Radius', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-banner, {{WRAPPER}} .motta-banner__featured-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function section_style_countdown() {
		// Countdown
		$this->start_controls_section(
			'section_countdown_style',
			[
				'label' => __( 'CountDown', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'section_countdown_position',
			[
				'label' => esc_html__( 'Position', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'before',
				'options' => [
					'before' => esc_html__( 'Before Content', 'motta-addons' ),
					'after_title' => esc_html__( 'After Title', 'motta-addons' ),
					'after'  => esc_html__( 'After Content', 'motta-addons' ),
				],
			]
		);

		$this->add_responsive_control(
			'section_countdown_flexible_items',
			[
				'label' => esc_html__( 'Flexible Items', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'column',
				'options' => [
					'column' => esc_html__( 'Column', 'motta-addons' ),
					'row' 	  => esc_html__( 'Row', 'motta-addons' ),
				],
				'selectors'            => [
					'{{WRAPPER}} .motta-banner__countdown' => 'flex-direction: {{VALUE}}',
				],
				'prefix_class' => 'motta-banner__countdown--',
			]
		);

		$this->add_responsive_control(
			'section_countdown_background_color',
			[
				'label'     => __( 'Background Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-banner__countdown' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'section_countdown_box_shadow_color',
			[
				'label' => __( 'Box Shadow Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-banner__countdown' => '--mt-input__box-shadow: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'section_countdown_border_radius',
			[
				'label'      => __( 'Border Radius', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-banner__countdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'section_countdown_padding',
			[
				'label'      => esc_html__( 'Padding', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-banner__countdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.motta-rtl-smart {{WRAPPER}} .motta-banner__countdown' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'section_countdown_spacing',
			[
				'label'     => esc_html__( 'Spacing Bottom', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-banner__countdown' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'section_countdown_spacing_top',
			[
				'label'     => esc_html__( 'Spacing Top', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'     => [
					'px' => [
						'min' => -100,
						'max' => 2000,
					],
				],
				'selectors' => [
					'{{WRAPPER}}  .motta-banner__countdown' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'style_countdown_text',
			[
				'label' => __( 'CountDown Text', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'countdown_text',
				'selector' => '{{WRAPPER}} .motta-banner__countdown-text',
			]
		);

		$this->add_control(
			'countdown_text_color',
			[
				'label'     => __( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-banner__countdown-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'countdown_text_spacing',
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
					'{{WRAPPER}} .motta-banner__countdown-text' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.motta-banner__countdown--row .motta-banner__countdown-text' => 'margin-right: {{SIZE}}{{UNIT}}; margin-bottom: 0;',
					'.rtl {{WRAPPER}}.motta-banner__countdown--row .motta-banner__countdown-text' => 'margin-left: {{SIZE}}{{UNIT}}; margin-bottom: 0;',
				],
			]
		);

		$this->add_control(
			'section_style_countdown',
			[
				'label' => __( 'CountDown Content', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'countdown_radius',
			[
				'label'      => __( 'Border Radius', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-banner .motta-countdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'countdown_bgcolor',
			[
				'label'     => __( 'Background Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-banner .motta-countdown' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'countdown_color',
			[
				'label'     => __( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-banner .timer .digits' => 'color: {{VALUE}};',
					'{{WRAPPER}} .motta-banner .timer .divider' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'countdown_box_shadow_color',
			[
				'label' => __( 'Box Shadow Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-banner .motta-countdown' => '--mt-input__box-shadow: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'countdown_padding',
			[
				'label'      => esc_html__( 'Padding', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-banner .motta-countdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.motta--time-text-enable-yes .motta-banner .motta-countdown .text' => 'top: calc(100% + {{BOTTOM}}{{UNIT}} + 10px);',
				],
			]
		);

		$this->add_control(
			'style_digits',
			[
				'label' => __( 'Digits', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'countdown_digits_size',
				'selector' => '{{WRAPPER}} .motta-banner .timer .digits',
			]
		);

		$this->add_responsive_control(
			'countdown_digits_spacing',
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
					'{{WRAPPER}} .motta-banner .motta-countdown .timer' => 'padding: 0 {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .motta-banner .motta-countdown' => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'countdown_digits_width',
			[
				'label'     => esc_html__( 'Min Width', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-banner .motta-countdown .digits' => 'min-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'style_divider',
			[
				'label' => __( 'Divider', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'countdown_divider_size',
				'selector' => '{{WRAPPER}} .motta-banner .timer .divider',
			]
		);

		$this->add_responsive_control(
			'countdown_divider_xy',
			[
				'label'              => esc_html__( 'Position', 'motta-addons' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'allowed_dimensions' => [ 'top', 'right' ],
				'size_units'         => [ 'px', '%' ],
				'selectors'          => [
					'{{WRAPPER}} .motta-banner .motta-countdown .divider' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}};',
					'.rtl {{WRAPPER}} .motta-banner .motta-countdown .divider' => 'top: {{TOP}}{{UNIT}}; left: {{RIGHT}}{{UNIT}}; right: auto',
				],
			]
		);

		$this->add_control(
			'style_time_text',
			[
				'label' => __( 'Time Text', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'enable_time_text',
			[
				'label'        => esc_html__( 'Enable Time Text', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => __( 'Off', 'motta-addons' ),
				'label_on'     => __( 'On', 'motta-addons' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'prefix_class' => 'motta--time-text-enable-',
			]
		);

		$this->add_control(
			'time_shorten_text',
			[
				'label'        => esc_html__( 'Shorten Text', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => __( 'Off', 'motta-addons' ),
				'label_on'     => __( 'On', 'motta-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'time_text_size',
				'selector' => '{{WRAPPER}} .motta-banner .motta-countdown .text',
			]
		);

		$this->add_control(
			'time_text_color',
			[
				'label'     => __( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-banner .motta-countdown .text' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'time_text_spacing',
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
					'{{WRAPPER}}.motta--time-text-enable-yes .motta-banner .motta-countdown .text' => 'top: calc(100% + {{SIZE}}{{UNIT}})',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function section_style_sale(){
		$this->start_controls_section(
			'section_content_sale',
			[
				'label' => __( 'Sale', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'sale_position_x',
			[
				'label'     => esc_html__( 'Position X', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .motta-banner__sale' => 'right: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} .motta-banner__sale' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
				],
			]
		);

		$this->add_responsive_control(
			'sale_position_y',
			[
				'label'     => esc_html__( 'Position Y', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .motta-banner__sale' => 'top: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .motta-banner' => 'overflow: inherit;',
				],
			]
		);

		// Sale Text
		$this->add_control(
			'content_style_saletext',
			[
				'label' => __( 'Sale Text', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sale_text_color',
			[
				'label'     => __( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-banner__sale--text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sale_text_typography',
				'selector' => '{{WRAPPER}} .motta-banner__sale--text',
			]
		);

		$this->add_responsive_control(
			'sale_text_spacing',
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
					'{{WRAPPER}} .motta-banner__sale--text' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		// Sale Unit
		$this->add_control(
			'content_style_saleunit',
			[
				'label' => __( 'Sale Unit', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sale_unit_color',
			[
				'label'     => __( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-banner__sale--unit' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sale_unit_typography',
				'selector' => '{{WRAPPER}} .motta-banner__sale--unit',
			]
		);

		$this->add_control(
			'sale_bgcolor',
			[
				'label'     => __( 'Background Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-banner__sale' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sale_color',
			[
				'label'     => __( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-banner__sale' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'sale_width',
			[
				'label'     => esc_html__( 'Width', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [],
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-banner__sale' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sale_height',
			[
				'label'     => esc_html__( 'Height', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [],
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-banner__sale' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}


	/**
	 * Render circle box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$classes = [
			'motta-banner',
		];

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		$second = 0;
		if ( $settings['countdown_date'] ) {
			$second_current  = strtotime( current_time( 'Y/m/d H:i:s' ) );
			$second_discount = strtotime( $this->get_settings( 'countdown_date' ) );

			if ( $second_discount > $second_current ) {
				$second = $second_discount - $second_current;
			}

			$second = apply_filters( 'motta_countdown_banner_second', $second );

		}


		if( $settings['time_shorten_text'] == 'yes' ) {
			$dataText = Helper::get_countdown_shorten_texts();
		} else {
			$dataText = Helper::get_countdown_texts();
		}


		$this->add_render_attribute( 'countdown', 'data-expire', [$second] );
		$this->add_render_attribute( 'countdown', 'data-text', wp_json_encode( $dataText ) );

		$countdown_text = ! empty( $settings['countdown_text'] ) ? '<div class="motta-banner__countdown-text">' . esc_html( $settings['countdown_text'] ) . '</div>' : '';
		$countdown_html = ! empty( $second ) ? sprintf( '<div class="motta-banner__countdown">%s<div class="motta-countdown" %s></div></div>', $countdown_text, $this->get_render_attribute_string( 'countdown' ) ) : '';
		// Sale
		$text_sale = $settings['sale_text'] ? sprintf( '<div class="motta-banner__sale--text">%s</div>', $settings['sale_text'] ) : '';
		$unit_sale = $settings['sale_unit'] ? sprintf( '<span class="motta-banner__sale--unit">%s</span>', $settings['sale_unit'] ) : '';

		$media = '';
		$image = ! empty( $settings['image'] ) ? $settings['image']['url'] : '';
		$image = $settings['image_source'] =='external' ? $settings['external_url'] : $image;
		if( $settings['image_source'] == 'library' || $settings['image_source'] =='external' ) {
			$media = ! empty( $media ) ? sprintf('<img alt="%s" src="%s" class="hidden"/>', esc_attr( $settings['title'] ), $image) : '';
		} else {
			$media = $this->get_video_html();
		}

		?>
			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php
				if ( $settings['button_link_type'] == 'all' && empty( $settings['second_button_text'] ) ) {
					echo Helper::render_control_link_open( 'btn_full', $settings['primary_button_link'], [ 'class' => 'motta-banner__button-link' ] );
				}
			?>
				<div class="motta-banner__featured-image"><?php echo $media; ?></div>
				<div class="motta-banner__wrapper countdown__position--<?php echo esc_attr( $settings['section_countdown_position'] ); ?>">
					<?php
						if( $settings['section_countdown_position'] != 'after_title' ) {
							echo $countdown_html;
						}
					 ?>
					<div class="motta-banner__content">
						<?php echo ! empty( $settings['before_title'] ) ? sprintf( '<div class="motta-banner__before-title">%s</div>', $settings['before_title'] ) : ''; ?>
						<?php
							if( $settings['title_type'] == 'text' ) {
								echo ! empty( $settings['title'] ) ? sprintf( '<h4 class="motta-banner__title">%s</h4>', $settings['title'] ) : '';
							} elseif ( $settings['title_type'] == 'image' ) {
								echo ! empty( $settings['title_image']['url'] ) ? sprintf( '<div class="motta-banner__title motta-banner__title--image"><img alt="%s" src="%s" /></div>', $settings['title'], $settings['title_image']['url'] ) : '';
							} elseif ( $settings['title_type'] == 'external' ) {
								echo ! empty( $settings['title_external_url'] ) ? '<div class="motta-banner__title motta-banner__title--external"><img alt="'. $settings['title'] .'" src="' . esc_url( $settings['title_external_url'] ) .'"></div>' : '';
							} else {
								if( ! empty( $settings['title_icon']['value'] ) ) {
									echo '<div class="motta-banner__title motta-banner__title--icon">';
										if ( 'svg' === $settings['title_icon']['library'] ) {
											echo Icons_Manager::render_uploaded_svg_icon( $settings['title_icon']['value'] );
										} else {
											echo Icons_Manager::render_font_icon( $settings['title_icon'], [ 'aria-hidden' => 'true' ], 'i' );
										}
									echo '</div>';
								}
							}

							if( $settings['section_countdown_position'] == 'after_title' ) {
								echo $countdown_html;
							}

							if ( $settings['description_position'] == 'before' ) {
								echo $settings['description'] ? sprintf( '<div class="motta-banner__description">%s</div>', $settings['description'] ) : '';
							}
						?>
						<div class="motta-banner-button">
							<?php $this->render_button( 'primary' ); ?>
							<?php $this->render_button( 'second' ); ?>
						</div>
						<?php
							if ( $settings['description_position'] == 'after' ) {
								echo $settings['description'] ? sprintf( '<div class="motta-banner__description">%s</div>', $settings['description'] ) : '';
							}
						?>
					</div>
				</div>
				<?php
					if( $settings['sale_type'] == 'text' ) {
						echo empty( $text_sale ) && empty( $unit_sale ) ? '' : sprintf( '<div class="motta-banner__sale">%s %s</div>', $text_sale, $unit_sale );
					} elseif ( $settings['sale_type'] == 'image' ) {
						echo ! empty( $settings['sale_image']['url'] ) ? sprintf( '<div class="motta-banner__sale motta-banner__sale--image"><img alt="%s" src="%s" /></div>', $settings['title'], $settings['sale_image']['url'] ) : '';
					} elseif ( $settings['sale_type'] == 'external' ) {
						echo ! empty( $settings['sale_url'] ) ? '<div class="motta-banner__sale motta-banner__sale--external"><img alt="'. $settings['title'] .'" src="' . esc_url( $settings['sale_url'] ) .'"></div>' : '';
					} else {
						if( ! empty( $settings['sale_icon']['value'] ) ) {
							echo '<div class="motta-banner__sale motta-banner__sale--icon">';
								if ( 'svg' === $settings['sale_icon']['library'] ) {
									echo Icons_Manager::render_uploaded_svg_icon( $settings['sale_icon']['value'] );
								} else {
									echo Icons_Manager::render_font_icon( $settings['sale_icon'], [ 'aria-hidden' => 'true' ], 'i' );
								}
							echo '</div>';
						}
					}

					if ( $settings['button_link_type'] == 'all' && empty( $settings['second_button_text'] ) ) {
						echo Helper::render_control_link_close( $settings['primary_button_link'] );
					}
				?>
			</div>
		<?php
	}

	/**
	 * Render video html.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_video_html() {
		$settings = $this->get_settings_for_display();
		$video_html = '';
		if ( $settings['image_source'] == 'youtube' ) {
			if( $settings['youtube_url'] ) {
				preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $settings['youtube_url'], $id_video );
				$loop = $settings['video_loop'] == 'yes' ? '&playlist='.$id_video[1] : '';
				$autoplay = '&autoplay=1';
				$sound = $settings['video_mute'] == 'yes' ? '&mute=1' : '';
				$video_html .= '<iframe id="motta-banner__video" class="motta-banner__video" data-type="youtube" frameborder="0" allowfullscreen="1" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" width="100%" height="100%" src="' . $settings['youtube_url'] . '?enablejsapi=1&playsinline=1&playerapiid=ytplayer&showinfo=0&fs=0&modestbranding=0&rel=0&loop=1'.$loop.'&controls=0&autohide=1&html5=1'.$sound.'&start=1'.$autoplay.'"></iframe>';
				if( $settings['play_on_mobile'] ) {
					$video_html .= sprintf( "<script type='text/javascript'>
					var tag = document.createElement('script');
					tag.src = 'https://www.youtube.com/iframe_api';
					var firstScriptTag = document.getElementsByTagName('script')[0];
					firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
					var player;
					function onYouTubeIframeAPIReady() {
					player = new YT.Player('motta-banner__video', {
						videoId: '%s',
						playerVars: { 'autoplay': 1, 'playsinline': 1 },
						events: {
							'onReady': onPlayerReady
						}
					});
					}
					function onPlayerReady(event) {
						event.target.playVideo();
					}
				</script>", $id_video[1] );
				}
			}
		} else {
			$video_url = $settings['hosted_url'];

			if( $video_url ) {
				$video_url = $video_url['url'];
				$loop = $settings['video_loop'] == 'yes' ? 'loop="true"' : '';
				$sound = $settings['video_mute'] == 'yes' ? 'muted="muted"' : '';
				$video_html .= '<video id="razzi-slide-banner__video" class="razzi-slide-banner__video" data-type="'.$settings['image_source'].'" src="'.esc_url($video_url).'" '.$sound.' preload="auto" '.$loop.' playsinline autoplay></video>';
			}
		}

		return $video_html;
	}

}
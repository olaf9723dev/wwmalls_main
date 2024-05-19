<?php
namespace Motta\Addons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Pricing_Table extends Widget_Base {
	use \Motta\Addons\Elementor\Widgets\Traits\Button_Trait;

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-pricing-table';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( '[Motta] Pricing Table', 'motta-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-price-table';
	}

	public function get_keywords() {
		return [ 'pricing', 'table', 'product', 'image', 'plan', 'button' ];
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'motta-addons' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_header',
			[
				'label' => esc_html__( 'Header', 'motta-addons' ),
			]
		);

		$this->add_control(
			'heading',
			[
				'label' => esc_html__( 'Title', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Enter your title', 'motta-addons' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'sub_heading',
			[
				'label' => esc_html__( 'Description', 'motta-addons' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Enter your description', 'motta-addons' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'heading_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				],
				'default' => 'h3',
			]
		);

		$this->add_control(
			'heading_position',
			[
				'label' => esc_html__( 'Title Position', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => [
					'below' => esc_html__( 'Below Price', 'motta-addons' ),
					'above' => esc_html__( 'Above Price', 'motta-addons' ),
				],
				'default' => 'below',
				'prefix_class' => 'motta-pricing-table--heading-',
			]
		);

		$this->add_control(
			'section_pricing',
			[
				'label' => esc_html__( 'Pricing', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'price',
			[
				'label' => esc_html__( 'Price', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '39.99',
			]
		);

		$this->add_control(
			'period',
			[
				'label' => esc_html__( 'Period', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Monthly', 'motta-addons' ),
			]
		);

		$this->add_control(
			'period_position',
			[
				'label' => esc_html__( 'Period Position', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => [
					'below' => esc_html__( 'Below', 'motta-addons' ),
					'beside' => esc_html__( 'Beside', 'motta-addons' ),
				],
				'default' => 'below'
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_features',
			[
				'label' => esc_html__( 'Features', 'motta-addons' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_text',
			[
				'label' => esc_html__( 'Text', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'List Item', 'motta-addons' ),
			]
		);

		$default_icon = [
			'value' => 'far fa-check-circle',
			'library' => 'fa-regular',
		];

		$repeater->add_control(
			'selected_item_icon',
			[
				'label' => esc_html__( 'Icon', 'motta-addons' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'item_icon',
				'default' => $default_icon,
			]
		);

		$repeater->add_control(
			'item_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} i' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'features_list',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'item_text' => esc_html__( 'List Item #1', 'motta-addons' ),
						'selected_item_icon' => $default_icon,
					],
					[
						'item_text' => esc_html__( 'List Item #2', 'motta-addons' ),
						'selected_item_icon' => $default_icon,
					],
					[
						'item_text' => esc_html__( 'List Item #3', 'motta-addons' ),
						'selected_item_icon' => $default_icon,
					],
				],
				'title_field' => '{{{ item_text }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_footer',
			[
				'label' => esc_html__( 'Footer', 'motta-addons' ),
			]
		);

		$controls = [
			'button_text_label' => __( 'Button', 'motta-addons' )
		];

		$this->register_button_content_controls( $controls );

		$this->add_control(
			'button_position',
			[
				'label' => esc_html__( 'Position', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => [
					'below' => esc_html__( 'Below Features', 'motta-addons' ),
					'above' => esc_html__( 'Above Features', 'motta-addons' ),
				],
				'default' => 'above',
				'prefix_class' => 'motta-pricing-table--button-',
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'section_ribbon',
			[
				'label' => esc_html__( 'Ribbon', 'motta-addons' ),
			]
		);

		$this->add_control(
			'show_ribbon',
			[
				'label' => esc_html__( 'Show', 'motta-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'ribbon_title',
			[
				'label' => esc_html__( 'Title', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Popular', 'motta-addons' ),
				'condition' => [
					'show_ribbon' => 'yes',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'ribbon_horizontal_position',
			[
				'label' => esc_html__( 'Position', 'motta-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'motta-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'motta-addons' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'motta-addons' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'condition' => [
					'show_ribbon' => 'yes',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_pricing_table_style',
			[
				'label' => esc_html__( 'Pricing Table', 'motta-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_responsive_control(
			'pricing_table_text_align',
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
					'{{WRAPPER}} .motta-pricing-table' => 'align-items: {{VALUE}}',
					'{{WRAPPER}} .motta-pricing-table .motta-pricing-table__header' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .motta-pricing-table .motta-pricing-table-period--beside' => 'justify-content: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pricing_table_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-pricing-table' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pricing_table_color',
			[
				'label' => esc_html__( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-pricing-table' => '--motta-pricing-table-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'pricing_table_padding',
			[
				'label' => esc_html__( 'Padding', 'motta-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .motta-pricing-table' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'pricing_table_border',
				'label' => esc_html__( 'Border', 'motta-addons' ),
				'selector' => '{{WRAPPER}} .motta-pricing-table',
			]
		);

		$this->add_control(
			'pricing_table_border_radius',
			[
				'label'      => __( 'Border Radius', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-pricing-table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_header_style',
			[
				'label' => esc_html__( 'Header', 'motta-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'heading_heading_style',
			[
				'label' => esc_html__( 'Title', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label' => esc_html__( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-pricing-table__heading' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_typography',
				'selector' => '{{WRAPPER}} .motta-pricing-table__heading',
			]
		);

		$this->add_responsive_control(
			'heading_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'motta-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => -100,
						'max' => 300,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-pricing-table__heading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_sub_heading_style',
			[
				'label' => esc_html__( 'Sub Title', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sub_heading_color',
			[
				'label' => esc_html__( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-pricing-table__subheading' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sub_heading_typography',
				'selector' => '{{WRAPPER}} .motta-pricing-table__subheading',
			]
		);

		$this->add_responsive_control(
			'subheading_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'motta-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => -100,
						'max' => 300,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-pricing-table__subheading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'price_heading_style',
			[
				'label' => esc_html__( 'Pricing', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'price_color',
			[
				'label' => esc_html__( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-pricing-table__integer-part' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_typography',
				// Targeting also the .motta-pricing-table class in order to get a higher specificity from the inline CSS.
				'selector' => '{{WRAPPER}} .motta-pricing-table .motta-pricing-table__integer-part',
			]
		);

		$this->add_responsive_control(
			'pricing_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'motta-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => -100,
						'max' => 300,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-pricing-table__price' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_period_style',
			[
				'label' => esc_html__( 'Period', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'period!' => '',
				],
			]
		);

		$this->add_control(
			'period_color',
			[
				'label' => esc_html__( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-pricing-table__period' => 'color: {{VALUE}}',
				],
				'condition' => [
					'period!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'period_typography',
				'selector' => '{{WRAPPER}} .motta-pricing-table__period',
				'condition' => [
					'period!' => '',
				],
			]
		);


		$this->add_responsive_control(
			'period_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'motta-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => -100,
						'max' => 300,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-pricing-table__period' => 'padding-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .motta-pricing-table-period--beside .motta-pricing-table__period' => 'padding-bottom: {{SIZE}}{{UNIT}};padding-top:0;',
				],

				'condition' => [
					'period!' => '',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_features_list_style',
			[
				'label' => esc_html__( 'Features', 'motta-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'features_list_color',
			[
				'label' => esc_html__( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-pricing-table__features-list' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'features_list_typography',
				'selector' => '{{WRAPPER}} .motta-pricing-table__features-list li',
			]
		);


		$this->add_responsive_control(
			'features_list_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'motta-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => -100,
						'max' => 300,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-pricing-table__features-list' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_options',
			[
				'label' => esc_html__( 'Icon Options', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'      => esc_html__( 'Size', 'motta-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-pricing-table__features-list .motta-repeater-item-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'motta-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-pricing-table__features-list .motta-repeater-item-icon' => 'padding-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_footer_style',
			[
				'label' => esc_html__( 'Footer', 'motta-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_responsive_control(
			'footer_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'motta-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => -100,
						'max' => 300,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-pricing-table__footer' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.motta-pricing-table--button-below .motta-pricing-table__footer' => 'margin-top: {{SIZE}}{{UNIT}};margin-bottom: 0',
				],
			]
		);

		$this->add_control(
			'footer_button_style',
			[
				'label' => esc_html__( 'Button', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$controls = [
			'skin'     => 'base',
			'size'      => 'medium',
		];

		$this->register_button_style_controls($controls);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_ribbon_style',
			[
				'label' => esc_html__( 'Ribbon', 'motta-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'ribbon_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-pricing-table__ribbon' => 'background-color: {{VALUE}}',
				],
			]
		);


		$this->add_control(
			'ribbon_text_color',
			[
				'label' => esc_html__( 'Text Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .motta-pricing-table__ribbon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ribbon_typography',
				'selector' => '{{WRAPPER}} .motta-pricing-table__ribbon',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'ribbon_box_shadow',
				'selector' => '{{WRAPPER}} .motta-pricing-table__ribbon',
			]
		);

		$this->add_responsive_control(
			'ribbon_horizontal_spacing',
			[
				'label'      => esc_html__( 'Horizontal Spacing', 'motta-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => -100,
						'max' => 300,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-pricing-table__ribbon' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ribbon_horizontal_position!' => 'center',
				],
			]
		);

		$this->add_responsive_control(
			'ribbon_vertical_spacing',
			[
				'label'      => esc_html__( 'Vertical Spacing', 'motta-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => -100,
						'max' => 300,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-pricing-table__ribbon' => 'top: {{SIZE}}{{UNIT}} ;',
				],
			]
		);

		$this->end_controls_section();
	}


	protected function render() {
		$settings = $this->get_settings_for_display();

		$price = $settings['price'];

		$this->add_render_attribute( 'heading', 'class', 'motta-pricing-table__heading' );
		$this->add_render_attribute( 'sub_heading', 'class', 'motta-pricing-table__subheading' );
		$this->add_render_attribute( 'period', 'class', 'motta-pricing-table__period');

		$this->add_inline_editing_attributes( 'heading', 'none' );
		$this->add_inline_editing_attributes( 'sub_heading', 'none' );
		$this->add_inline_editing_attributes( 'period', 'none' );

		$period_element = '<span ' . $this->get_render_attribute_string( 'period' ) . '>' . $settings['period'] . '</span>';
		$heading_tag = Utils::validate_html_tag( $settings['heading_tag'] );
		$pricing_class = 'motta-pricing-table-period--' .  $settings['period_position'];
		$migration_allowed = Icons_Manager::is_migration_allowed();
		?>

		<div class="motta-pricing-table">
			<?php if ( $settings['heading'] || $settings['sub_heading'] ) : ?>
				<div class="motta-pricing-table__header">
					<?php if ( ! empty( $settings['heading'] ) ) : ?>
						<<?php Utils::print_validated_html_tag( $heading_tag ); ?> <?php $this->print_render_attribute_string( 'heading' ); ?>>
						<?php $this->print_unescaped_setting( 'heading' ); ?>
						</<?php Utils::print_validated_html_tag( $heading_tag ); ?>>
					<?php endif; ?>

					<?php if ( ! empty( $settings['sub_heading'] ) ) : ?>
						<span <?php $this->print_render_attribute_string( 'sub_heading' ); ?>>
							<?php $this->print_unescaped_setting( 'sub_heading' ); ?>
						</span>
					<?php endif; ?>

					<div class="motta-pricing-table__price <?php  echo esc_attr($pricing_class); ?>">
						<?php if ( ! empty( $price ) ) : ?>
							<span class="motta-pricing-table__integer-part">
								<?php
									// PHPCS - the main text of a widget should not be escaped.
									echo $price; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								?>
							</span>
						<?php endif; ?>

						<?php if ( ! empty( $settings['period'] )) : ?>
							<?php
								// PHPCS - already escaped before
								echo $period_element; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $settings['features_list'] ) ) : ?>
				<ul class="motta-pricing-table__features-list">
					<?php
					foreach ( $settings['features_list'] as $index => $item ) :
						$repeater_setting_key = $this->get_repeater_setting_key( 'item_text', 'features_list', $index );
						$this->add_inline_editing_attributes( $repeater_setting_key );

						$migrated = isset( $item['__fa4_migrated']['selected_item_icon'] );
						// add old default
						if ( ! isset( $item['item_icon'] ) && ! $migration_allowed ) {
							$item['item_icon'] = 'fa fa-check-circle';
						}
						$is_new = ! isset( $item['item_icon'] ) && $migration_allowed;
						?>
						<li class="elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
							<?php if ( ! empty( $item['item_icon'] ) || ! empty( $item['selected_item_icon'] ) ) :?>
								<span class="motta-repeater-item-icon motta-svg-icon">
								<?php if ( $is_new || $migrated ) :
										Icons_Manager::render_icon( $item['selected_item_icon'], [ 'aria-hidden' => 'true' ] );
									else : ?>
										<i class="<?php echo esc_attr( $item['item_icon'] ); ?>" aria-hidden="true"></i>
										<?php
									endif; ?>
								</span>
								<?php
							endif; ?>
							<?php if ( ! empty( $item['item_text'] ) ) : ?>
								<span <?php $this->print_render_attribute_string( $repeater_setting_key ); ?>>
									<?php $this->print_unescaped_setting( 'item_text', 'features_list', $index ); ?>
								</span>
								<?php
							else :
								echo '&nbsp;';
							endif;
							?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<?php if( ! empty( $settings['primary_button_text'] ) ) : ?>
				<div class="motta-pricing-table__footer">
					<?php $this->render_button(); ?>
				</div>
			<?php endif; ?>

			<?php
		if ( 'yes' === $settings['show_ribbon'] && ! empty( $settings['ribbon_title'] ) ) :
			$this->add_render_attribute( 'ribbon-wrapper', 'class', 'motta-pricing-table__ribbon' );

			if ( ! empty( $settings['ribbon_horizontal_position'] ) ) :
				$this->add_render_attribute( 'ribbon-wrapper', 'class', 'motta-ribbon-' . $settings['ribbon_horizontal_position'] );
			endif;

			?>
			<div <?php $this->print_render_attribute_string( 'ribbon-wrapper' ); ?>>
				<?php $this->print_unescaped_setting( 'ribbon_title' ); ?>
			</div>
		<?php
		endif;
		?>
		</div>
		<?php

	}

	/**
	 * Render Price Table widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 * @access protected
	 */
	protected function content_template() {
	}
}

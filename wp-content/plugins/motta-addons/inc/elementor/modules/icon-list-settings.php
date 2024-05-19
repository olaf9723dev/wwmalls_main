<?php
namespace Motta\Addons\Elementor\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Core\Base\Module;
use Elementor\Controls_Manager;

class Icon_List_Settings extends Module {
	/**
	 * Get module name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'icon-list-settings';
	}

	/**
	 * Module constructor.
	 */
	public function __construct() {
		add_action( 'elementor/element/icon-list/section_icon_style/after_section_start', [ $this, 'register_icon_controls' ] );
		add_action( 'elementor/element/icon-list/section_text_style/after_section_start', [ $this, 'register_text_controls' ] );
		add_action( 'elementor/element/icon-list/section_icon_style/after_section_end', [ $this, 'update_controls' ] );
	}

	/**
	 * @param $element    Controls_Stack
	 */
	public function register_icon_controls( $element ) {
		$element->add_responsive_control(
			'icon_width',
			[
				'label' => esc_html__( 'Width', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'min' => 6,
					],
					'%' => [
						'min' => 6,
					],
					'vw' => [
						'min' => 6,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-list-icon'=>'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$element->add_responsive_control(
			'icon_height',
			[
				'label' => esc_html__( 'Height', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'min' => 6,
					],
					'%' => [
						'min' => 6,
					],
					'vw' => [
						'min' => 6,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-list-icon'=>'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$element->add_control(
			'icon_border_radius',
			[
				'label'      => __( 'Border Radius', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .elementor-icon-list-icon'=> 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$element->start_controls_tabs( 'motta_icon_colors' );

		$element->start_controls_tab(
			'motta_icon_colors_normal',
			[
				'label' => esc_html__( 'Normal', 'motta-addons' ),
			]
		);

		$element->add_control(
			'motta_bg_icon_color',
			[
				'label' => esc_html__( 'Background Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-list-icon' => 'background-color: {{VALUE}};',
				],
			]
		);

		$element->add_control(
			'motta_icon_color',
			[
				'label' => esc_html__( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-list-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-icon-list-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$element->end_controls_tab();

		$element->start_controls_tab(
			'motta_icon_colors_hover',
			[
				'label' => esc_html__( 'Hover', 'motta-addons' ),
			]
		);

		$element->add_control(
			'motta_bg_icon_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-list-item:hover .elementor-icon-list-icon' => 'background-color: {{VALUE}};',
				],
			]
		);

		$element->add_control(
			'motta_icon_color_hover',
			[
				'label' => esc_html__( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-list-item:hover .elementor-icon-list-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-icon-list-item:hover .elementor-icon-list-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$element->add_control(
			'motta_icon_color_hover_transition',
			[
				'label' => __( 'Transition Duration', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 's', 'ms', 'custom' ],
				'default' => [
					'unit' => 's',
					'size' => 0.3,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-list-icon i' => 'transition: color {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .elementor-icon-list-icon svg' => 'transition: fill {{SIZE}}{{UNIT}}',
				],
			]
		);

		$element->end_controls_tab();

		$element->end_controls_tabs();

	}

		/**
	 * @param $element    Controls_Stack
	 */
	public function register_text_controls( $element ) {
		$element->add_control(
			'text_spacing',
			[
				'label' => esc_html__( 'Spacing', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-list-text' => is_rtl() ? 'padding-right: {{SIZE}}{{UNIT}};' : 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

	}

	/**
	 * @param $element    Controls_Stack
	 */
	public function update_controls( $element ) {
		$element->remove_control( 'icon_color' );
		$element->remove_control( 'icon_colors_normal' );
		$element->remove_control( 'icon_colors_hover' );
		$element->remove_control( 'icon_color_hover' );
		$element->remove_control( 'icon_color_hover_transition' );
		$element->remove_control( 'text_indent' );
	}
}
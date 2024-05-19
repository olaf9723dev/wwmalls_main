<?php
namespace Motta\Addons\Elementor\Widgets\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Motta\Addons\Elementor\Utils;
use Motta\Addons\Helper;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;

trait Button_Trait {
	/**
	 * Register controls for button controls
	 *
	 * @param array $args
	 */
	protected function register_button_content_controls($args = [], \Elementor\Repeater $repeater = null) {
		$instance = empty( $repeater ) ? $this : $repeater;

		$default_args = [
			'prefix'    => 'primary',
			'button_text_default'     => '',
			'button_text_label'     => __( 'Text', 'motta-addons' ),
			'button_text_link'     => __( 'Link', 'motta-addons'),
			'button_icon' => false,
			'button_text_label_mobile'     => '',
			'section_condition' => [],
		];

		$args = wp_parse_args( $args, $default_args);
		$prefix = $args['prefix'];
		$instance->add_control(
			$prefix . '_button_text',
			[
				'label' => $args['button_text_label'],
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => $args['button_text_default'],
				'condition' => $args['section_condition'],
			]
		);

		if( $args['button_text_label_mobile'] ) {
			$instance->add_control(
				$prefix . '_mobile_button_text',
				[
					'label' => $args['button_text_label_mobile'],
					'type' => Controls_Manager::TEXT,
					'dynamic' => [
						'active' => true,
					],
					'default' => '',
					'condition' => $args['section_condition'],
				]
			);
		}

		$instance->add_control(
			$prefix . '_button_link',
			[
				'label' => $args['button_text_link'],
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => '',
				],
				'condition' => $args['section_condition'],
			]
		);

		if( $args['button_icon'] ) {
			$instance->add_control(
				$prefix . '_button_selected_icon',
				[
					'label' => esc_html__( 'Icon', 'motta-addons' ),
					'type' => Controls_Manager::ICONS,
					'fa4compatibility' => 'icon',
					'skin' => 'inline',
					'label_block' => false,
					'condition' => $args['section_condition'],
				]
			);

			$instance->add_control(
				$args['prefix'] .  '_button_icon_align',
				[
					'label' => esc_html__( 'Icon Position', 'motta-addons' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'right',
					'options' => [
						'left' => esc_html__( 'Before', 'motta-addons' ),
						'right' => esc_html__( 'After', 'motta-addons' ),
					],
					'condition' =>  wp_parse_args( [ $prefix .'_button_selected_icon[value]!' => '' ], $args['section_condition'] ),
				]
			);

			$instance->add_control(
				$args['prefix'] . '_button_icon_indent',
				[
					'label' => esc_html__( 'Icon Spacing', 'motta-addons' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'max' => 50,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .motta-button-' . $args['prefix'] . ' .motta-align-icon-left + .motta-button__text' => 'padding-left: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .motta-button-' . $args['prefix'] . ' .motta-align-icon-right + .motta-button__text' => 'padding-right: {{SIZE}}{{UNIT}};',
					],
					'condition' =>  wp_parse_args( [ $prefix .'_button_selected_icon[value]!' => '' ], $args['section_condition'] ),
				]
			);
		}

	}

	/**
	 * Register controls for button style
	 *
	 * @param array $args
	 */
	protected function register_button_style_controls( $args = [] ) {
		$default_args = [
			'prefix'    => 'primary',
			'skin'     => 'base',
			'shape' => 'default',
			'size'      => 'medium',
			'section_condition' => [],
		];

		$args = wp_parse_args( $args, $default_args);
		$this->add_control(
			$args['prefix'] . '_button_skin',
			[
				'label' => __( 'Skin', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => $args['skin'],
				'options' => [
					'base'   	=> __( 'Base', 'motta-addons' ),
					'raised' 	=> __( 'Raised', 'motta-addons' ),
					'smooth' 	=> __( 'Smooth', 'motta-addons' ),
					'ghost'  	=> __( 'Ghost', 'motta-addons' ),
					'subtle' 	=> __( 'Subtle', 'motta-addons' ),
					'text'   	=> __( 'Text', 'motta-addons' ),
				],
				'condition' => $args['section_condition'],
			]
		);

		$this->add_control(
			$args['prefix'] . '_button_shape',
			[
				'label' => __( 'Shape', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => $args['shape'],
				'options' => [
					'default' => __( 'Default', 'motta-addons' ),
					'sharp'   => __( 'Sharp', 'motta-addons' ),
					'round'   => __( 'Round', 'motta-addons' ),
					'circle'  => __( 'Circle', 'motta-addons' ),
				],
				'condition' => $args['section_condition'],
			]
		);

		$this->add_control(
			$args['prefix'] . '_button_size',
			[
				'label' => __( 'Size', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => $args['size'],
				'options' => [
					'small'  => __( 'Small', 'motta-addons' ),
					'medium' => __( 'Medium', 'motta-addons' ),
					'large'  => __( 'Large', 'motta-addons' ),
				],
				'condition' => $args['section_condition'],
			]
		);


		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => $args['prefix'] . '_button_typography',
				'selector' => '{{WRAPPER}} .motta-button-' . $args['prefix'],
				'condition' => $args['section_condition'],
			]
		);

		$this->add_control(
			$args['prefix'] . '_button_options',
			[
				'label'        => __( 'Extra Options', 'motta-addons' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'motta-addons' ),
				'label_on'     => __( 'Custom', 'motta-addons' ),
				'return_value' => 'yes',
				'condition' => $args['section_condition'],
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			$args['prefix'] . '_button_padding',
			[
				'label'      => esc_html__( 'Padding', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-button-' . $args['prefix'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			$args['prefix'] . '_button_width',
			[
				'label'     => esc_html__( 'Width', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'size_units' => [ 'px','%' ],
				'selectors' => [
					'{{WRAPPER}} .motta-button-' . $args['prefix'] => 'min-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => $args['section_condition'],
			]

		);

		$this->add_responsive_control(
			$args['prefix'] . '_button_height',
			[
				'label'     => esc_html__( 'Height', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-button-' . $args['prefix'] => 'line-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => $args['section_condition'],
			]

		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => $args['prefix'] . '_button_border',
				'label' => __( 'Border', 'motta-addons' ),
				'selector' => '{{WRAPPER}} .motta-button-' . $args['prefix'],
				'condition' => $args['section_condition'],
			]
		);

		$this->add_responsive_control(
			$args['prefix'] . '_button_border_radius',
			[
				'label'      => __( 'Border Radius', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-button-' . $args['prefix'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => $args['section_condition'],
			]
		);

		$this->start_controls_tabs(
			$args['prefix'] . '_button_style_tabs',
			[
				'condition' => $args['section_condition'],
			]
		);

		$this->start_controls_tab(
			$args['prefix'] . '_button_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'motta-addons' ),
				'condition' => $args['section_condition'],
			]
		);

		$this->add_responsive_control(
			$args['prefix'] . '_button_background_color',
			[
				'label'      => esc_html__( 'Background Color', 'motta-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .motta-button-'  . $args['prefix'] => 'background-color: {{VALUE}}',
				],
				'condition' => $args['section_condition'],
			]
		);

		$this->add_responsive_control(
			$args['prefix'] . '_button_color',
			[
				'label'      => esc_html__( 'Color', 'motta-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .motta-button-'  . $args['prefix'] => 'color: {{VALUE}}',
				],
				'condition' => $args['section_condition'],
			]
		);

		$this->add_responsive_control(
			$args['prefix'] . '_button_box_shadow_color',
			[
				'label' => __( 'Box Shadow Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-button-'  . $args['prefix'] => '--mt-color__primary--box-shadow: {{VALUE}}',
				],
				'condition' => $args['section_condition'],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			$args['prefix'] . '_button_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'motta-addons' ),
				'condition' => $args['section_condition'],
			]
		);

			$this->add_responsive_control(
				$args['prefix'] . '_button_hover_background_color',
				[
					'label' => __( 'Background Color', 'motta-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .motta-button-'  . $args['prefix'] .':hover' => 'background-color: {{VALUE}};',
					],
					'condition' => $args['section_condition'],
				]
			);

			$this->add_responsive_control(
				$args['prefix'] . '_button_hover_color',
				[
					'label' => __( 'Color', 'motta-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .motta-button-'  . $args['prefix'] .':hover' => 'color: {{VALUE}};',
					],
					'condition' => $args['section_condition'],
				]
			);

			$this->add_responsive_control(
				$args['prefix'] . '_button_box_shadow_color_hover',
				[
					'label' => __( 'Box Shadow Hover Color', 'motta-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .motta-button-'  . $args['prefix'] .':hover' => '--mt-color__primary--box-shadow: {{VALUE}}',
					],
					'condition' => $args['section_condition'],
				]
			);


		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_popover();

	}

	/**
	  * Render button widget output on the frontend.
	 *
	 * @param \Elementor\Widget_Base|null $instance
	 * @param array $settings
	 */
	protected function render_button ($prefix = 'primary', Widget_Base $instance = null, $fixed_tag = false) {
		if ( empty( $instance ) ) {
			$instance = $this;
		}

		$settings = $this->get_settings_for_display();
		$settings['prefix'] = $prefix;
		if( (empty($settings[$prefix. '_button_selected_icon']) || empty( $settings[$prefix. '_button_selected_icon']['value'] )) && empty( $settings[$prefix . '_button_text'] ) && empty( $settings[$prefix . '_mobile_button_text'] ) ) {
			return;
		}

		$atts_name = $prefix . '_button';

		$instance->add_render_attribute( $atts_name , 'class', 'motta-button' );
		$instance->add_render_attribute( $atts_name , 'class', 'motta-button-' . $prefix );
		if( isset( $settings['classes'] ) ) {
			$instance->add_render_attribute( $atts_name , 'class', $settings['classes'] );
		}
		$instance->add_render_attribute( $atts_name , 'class', ' motta-button--' . $settings[$prefix . '_button_skin'] );

		if( in_array( $settings[$prefix . '_button_skin'], array('subtle', 'text', 'ghost') ) ) {
			$instance->add_render_attribute( $atts_name , 'class', ' motta-button--color-black');
		}

		if( $settings[$prefix . '_button_size'] !== 'normal' ) {
			$instance->add_render_attribute( $atts_name, 'class', ' motta-button--' . $settings[$prefix . '_button_size'] );
		}

		if( $settings[$prefix . '_button_shape'] !== 'default' ) {
			$instance->add_render_attribute( $atts_name , 'class', ' motta-shape--' . $settings[$prefix . '_button_shape'] );
		}

		$tag = 'a';

		if ( isset( $settings['button_link_type'] ) && $settings['button_link_type'] == 'all' && empty( $settings['second_button_text'] ) ) {
			$tag = 'span';
		} else {
			if ( ! empty( $settings[$prefix . '_button_link']['url'] ) ) {
				$instance->add_link_attributes( $atts_name, $settings[$prefix . '_button_link'] );
			}
		}

		?>
		<<?php echo esc_attr( $tag ) ?> <?php $instance->print_render_attribute_string( $atts_name ); ?>>
			<?php $this->render_text( $settings, $instance  ); ?>
		</<?php echo esc_attr( $tag ) ?>>
		<?php

	}

	/**
	 * Render button text.
	 *
	 * Render button widget text.
	 *
	 * @param \Elementor\Widget_Base|null $instance
	 * @param array $settings
	 *
	 */
	protected function render_text($settings, $instance) {
		$prefix = $settings['prefix'];

		$instance->add_render_attribute( [
			$prefix . '_button_icon-align' => [
				'class' => [
					'motta-button__icon motta-svg-icon',
				],
			],
		] );

		if( isset($settings[$prefix . '_button_icon_align'] ) ) {
			$instance->add_render_attribute( [
				$prefix . '_button_icon-align' => [
					'class' => [
						'motta-align-icon-' . $settings[$prefix . '_button_icon_align'],
					],
				],
			] );
		}

		?>
		<?php if ( isset($settings[$prefix . '_button_selected_icon']) && ! empty( $settings[$prefix. '_button_selected_icon']['value'] ) ) : ?>
			<span <?php $instance->print_render_attribute_string( $prefix . '_button_icon-align' ); ?>>
				<?php Icons_Manager::render_icon( $settings[$prefix . '_button_selected_icon'], [ 'aria-hidden' => 'true' ] ); ?>
			</span>
		<?php endif; ?>
		<?php if ( isset($settings[$prefix . '_mobile_button_text']) && ! empty( $settings[$prefix . '_mobile_button_text']) ) : ?>
			<span class="motta-button__text motta-button__text_mobile"><?php echo $settings[$prefix . '_mobile_button_text']; ?></span>
		<?php endif; ?>
		<?php if ( isset($settings[$prefix . '_button_text']) && ! empty( $settings[$prefix . '_button_text']) ) : ?>
			<span class="motta-button__text"><?php echo $settings[$prefix . '_button_text']; ?></span>
		<?php endif; ?>
		<?php
	}
}
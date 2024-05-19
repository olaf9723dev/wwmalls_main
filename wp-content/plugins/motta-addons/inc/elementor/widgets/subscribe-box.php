<?php
namespace Motta\Addons\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor heading widget.
 *
 * Elementor widget that displays an eye-catching headlines.
 *
 * @since 1.0.0
 */
class Subscribe_Box extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-subscribe-box';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( '[Motta] Subscribe Box', 'motta-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-form-horizontal';
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
		return [ 'subscribe box', 'form', 'motta-addons' ];
	}

	/**
	 * Register the widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_controls() {
		// Content
		$this->start_controls_section(
			'section_subscribe_box',
			[ 'label' => __( 'Subscribe Box', 'motta-addons' ) ]
		);

		$this->add_control(
			'type',
			[
				'label' => esc_html__( 'Type', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'mailchimp'  => esc_html__( 'Mailchimp', 'motta-addons' ),
					'shortcode' => esc_html__( 'Use Shortcode', 'motta-addons' ),
				],
				'default' => 'mailchimp',
			]
		);

		$this->add_control(
			'form',
			[
				'label'   => esc_html__( 'Mailchimp Form', 'motta-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_contact_form(),
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => '==',
							'value' => 'mailchimp'
						],
					],
				],
			]
		);

		$this->add_control(
			'form_shortcode',
			[
				'label' => __( 'Enter your shortcode', 'motta-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '',
				'placeholder' => '[gallery id="123" size="medium"]',
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => '==',
							'value' => 'shortcode'
						],
					],
				],
			]
		);


		$this->end_controls_section();

		// Style Section
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => __( 'Subscribe Box', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'flexible_items',
			[
				'label' => __( 'Flexible Items', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'row',
				'options' => [
					'row' => __( 'Row', 'motta-addons' ),
					'column' => __( 'Column', 'motta-addons' ),
				],
				'prefix_class' => 'motta-subscribe-box-form-items--',
			]
		);

		$this->add_responsive_control(
			'form_height',
			[
				'label' => __( 'Form Height', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} input[type="submit"]' => 'line-height: {{size}}{{UNIT}} ;',
					'{{WRAPPER}} input[type="email"]' => 'line-height: {{size}}{{UNIT}} ;',
					'{{WRAPPER}} input[type="email"]' => 'height: {{size}}{{UNIT}} ;',
					'{{WRAPPER}} input[type="text"]' => 'line-height: {{size}}{{UNIT}} ;',
					'{{WRAPPER}} input[type="text"]' => 'height: {{size}}{{UNIT}} ;',
				],
			]
		);

		$this->add_control(
			'form_skin',
			[
				'label' => __( 'Form Skin', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'base',
				'options' => [
					'base' => __( 'Base', 'motta-addons' ),
					'raised' => __( 'Raised', 'motta-addons' ),
					'smooth' => __( 'Smooth', 'motta-addons' ),
					'ghost' => __( 'Ghost', 'motta-addons' ),
					'subtle' => __( 'Subtle', 'motta-addons' ),
					'text' => __( 'Text', 'motta-addons' ),
				],
				'prefix_class' => 'motta-skin--',
			]
		);

		$this->add_control(
			'form_shape',
			[
				'label' => __( 'Form Shape', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __( 'Default', 'motta-addons' ),
					'circle' => __( 'Circle', 'motta-addons' ),
					'round' => __( 'Round', 'motta-addons' ),
					'sharp' => __( 'Sharp', 'motta-addons' ),
				],
				'prefix_class' => 'motta-shape--',
			]
		);

		$this->add_responsive_control(
			'content_spacing',
			[
				'label'      => esc_html__( 'Padding', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-subscribe-box__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.motta-rtl-smart {{WRAPPER}} .motta-subscribe-box__content' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'style_button',
			[
				'label' => __( 'Button', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);


		$this->add_control(
			'button_position',
			[
				'label' => __( 'Button Position', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'outside',
				'options' => [
					'inside' => __( 'Inside', 'motta-addons' ),
					'outside' => __( 'Outside', 'motta-addons' ),
				],
				'prefix_class' => 'motta-subscribe-box-position--',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'flexible_items',
							'value' => 'row',
						]
					],
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} input[type="submit"]',
			]
		);

		$this->add_control(
			'button_options',
			[
				'label'        => __( 'Extra Options', 'motta-addons' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'motta-addons' ),
				'label_on'     => __( 'Custom', 'motta-addons' ),
				'return_value' => 'yes',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'button_width',
			[
				'label'     => esc_html__( 'Width', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} input[type="submit"]' => 'min-width: {{SIZE}}{{UNIT}};',
				],
			]

		);

		$this->add_responsive_control(
			'button_height',
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
					'{{WRAPPER}} input[type="submit"]' => 'line-height: {{SIZE}}{{UNIT}};',
				],
			]

		);

		$this->start_controls_tabs(
			'button_style_tabs'
		);

		$this->start_controls_tab(
			'button_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'motta-addons' ),
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label'      => esc_html__( 'Background Color', 'motta-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} input[type="submit"]' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_color',
			[
				'label'      => esc_html__( 'Color', 'motta-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} input[type="submit"]' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_box_shadow_color',
			[
				'label' => __( 'Box Shadow Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} input[type="submit"]' => '--mt-color__primary--box-shadow: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => __( 'Border Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} input[type="submit"]' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'motta-addons' ),
			]
		);

			$this->add_control(
				'button_hover_background_color',
				[
					'label' => __( 'Background Color', 'motta-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} input[type="submit"]:hover' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'button_hover_color',
				[
					'label' => __( 'Color', 'motta-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} input[type="submit"]:hover' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'button_box_shadow_color_hover',
				[
					'label' => __( 'Box Shadow Hover Color', 'motta-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} input[type="submit"]:hover' => '--mt-color__primary--box-shadow: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'button_hover_border_color',
				[
					'label' => __( 'Border Color', 'motta-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} input[type="submit"]:hover' => 'border-color: {{VALUE}};',
					],
				]
			);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_popover();

		$this->add_responsive_control(
			'button_spacing_top',
			[
				'label' => __( 'Spacing Top', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.motta-subscribe-box-form-items--column .mc4wp-form-fields button,
					{{WRAPPER}}.motta-subscribe-box-form-items--column .mc4wp-form-fields input[type="submit"]' => 'margin-top: {{size}}{{UNIT}} ;',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'flexible_items',
							'operator' => '==',
							'value' => 'column'
						],
					]
				]
			]
		);

		$this->add_control(
			'button_mobile_position',
			[
				'label' => __( 'Button Mobile Position', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'outside',
				'options' => [
					'inside' => __( 'Inside', 'motta-addons' ),
					'outside' => __( 'Outside', 'motta-addons' ),
				],
				'prefix_class' => 'motta-subscribe-box-mobile-position--',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'flexible_items',
							'value' => 'row',
						]
					],
				]
			]
		);


		$this->add_control(
			'style_field',
			[
				'label' => __( 'Field', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'field_spacing',
			[
				'label'      => esc_html__( 'Padding', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-subscribe-box input[type="email"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .motta-subscribe-box input[type="text"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'field_background',
			[
				'label' => __( 'Background Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-subscribe-box input[type="email"]' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .motta-subscribe-box input[type="text"]' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'field_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-subscribe-box input[type="email"]' => 'color: {{VALUE}};',
					'{{WRAPPER}} .motta-subscribe-box input[type="email"]::placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .motta-subscribe-box input[type="text"]' => 'color: {{VALUE}};',
					'{{WRAPPER}} .motta-subscribe-box input[type="text"]::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'field_border',
			[
				'label' => __( 'Border Type', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Default', 'motta-addons' ),
					'none' => esc_html__( 'None', 'motta-addons' ),
					'solid' => __( 'Solid', 'motta-addons' ),
					'double' => __( 'Double', 'motta-addons' ),
					'dotted' => __( 'Dotted', 'motta-addons' ),
					'dashed' => __( 'Dashed', 'motta-addons' ),
					'groove' => __( 'Groove', 'motta-addons' ),
				],
				'selectors' => [
					'{{WRAPPER}} .motta-subscribe-box input[type="email"]' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .motta-subscribe-box input[type="text"]' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'field_border_width',
			[
				'label' => __( 'Border Width', 'motta-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .motta-subscribe-box input[type="email"]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .motta-subscribe-box input[type="text"]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_border',
							'operator' => '!=',
							'value' => ''
						],
						[
							'name' => 'field_border',
							'operator' => '!=',
							'value' => 'none'
						],
					]
				]
			]
		);

		$this->add_control(
			'field_border_color',
			[
				'label' => __( 'Border Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-subscribe-box input[type="email"]' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .motta-subscribe-box input[type="text"]' => 'border-color: {{VALUE}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_border',
							'operator' => '!=',
							'value' => ''
						],
						[
							'name' => 'field_border',
							'operator' => '!=',
							'value' => 'none'
						],
					]
				]
			]
		);


		$this->add_responsive_control(
			'field_align',
			[
				'label' => __( 'Alignment', 'motta-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'motta-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'motta-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'motta-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .motta-subscribe-box .mc4wp-form-fields input' => 'text-align:{{VALUE}}',
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

		$classes = [
			'motta-subscribe-box',
			'motta-custom-button--skin',
		];

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		$output = sprintf(
			'<div class="motta-subscribe-box__content">%s</div>',
			do_shortcode( '[mc4wp_form id="' . esc_attr( $settings['form'] ) . '"]' ),
		);
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php
			if( $settings['type'] == 'mailchimp' ) {
				echo $output;
			} else {
				echo do_shortcode(  $settings['form_shortcode']  );
			}
			?>
		</div>
		<?php
	}

	/**
	 * Get Contact Form
	 */
	protected function get_contact_form() {
		$mail_forms    = get_posts( 'post_type=mc4wp-form&posts_per_page=-1' );
		$mail_form_ids = array(
			'' => esc_html__( 'Select Form', 'motta-addons' ),
		);
		foreach ( $mail_forms as $form ) {
			$mail_form_ids[$form->ID] = $form->post_title;
		}

		return $mail_form_ids;
	}
}
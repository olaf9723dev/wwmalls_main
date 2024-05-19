<?php
namespace Motta\Addons\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Motta\Addons\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Modal Popup widget
 */
class Modal_Popup extends Widget_Base {
	use \Motta\Addons\Elementor\Widgets\Traits\Button_Trait;

	/**
		 * Retrieve the widget name.
		 *
		 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-modal-popup';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( '[Motta] Modal Popup', 'motta-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-accordion';
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
	   return [ 'modal', 'popup', 'addons', 'motta' ];
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
			'section_modal_popup',
			[ 'label' => __( 'Modal Popup', 'motta-addons' ) ]
		);

		$this->add_control(
			'modal_content',
			[
				'label'   => esc_html__( 'Modal Content', 'motta-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_elementor_section(),
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title & Description', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your title', 'motta-addons' ),
				'default' => esc_html__( 'This is a Item', 'motta-addons' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'description',
			[
				'label' => esc_html__( 'Content', 'motta-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Enter your description', 'motta-addons' ),
				'default' => esc_html__( 'Duis aute irure dolor in reprehenderit voluptate velit esse cillum dolore.', 'motta-addons' ),
				'separator' => 'none',
				'show_label' => false,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Button Text', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Click Here', 'motta-addons' ),
			]
		);

		$this->end_controls_section();
	}

	protected function style_sections(){
		$this->section_style_content();
		$this->section_style_popup();
	}

	protected function section_style_content() {
		$this->start_controls_section(
			'style_content',
			[
				'label'     => __( 'Content', 'motta-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_spacing',
			[
				'label'      => esc_html__( 'Padding', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [],
				'size_units' => [ 'px', 'em', '%' ],
				'placeholder' => [
					'top' => '38',
					'right' => '40',
					'bottom' => '48',
					'left' => '40',
				],
				'selectors'  => [
					'{{WRAPPER}} .motta-modal-popup--elementor' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.motta-rtl-smart {{WRAPPER}} .motta-modal-popup--elementor' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_box_shadow',
				'label' => __( 'Box Shadow', 'motta-addons' ),
				'selector' => '{{WRAPPER}} .motta-modal-popup--elementor',
			]
		);

		$this->add_control(
			'content_style_title',
			[
				'label' => __( 'Title', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .motta-modal-popup__title',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-modal-popup__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_style_description',
			[
				'label' => __( 'Description', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .motta-modal-popup__description',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'     => __( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-modal-popup__description' => 'color: {{VALUE}};',
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
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-modal-popup__description' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'content_style_button',
			[
				'label' => __( 'Button', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$controls = [
			'size'      => 'large',
		];

		$this->register_button_style_controls($controls);

		$this->add_responsive_control(
			'button_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-modal-popup__button' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function section_style_popup() {
		$this->start_controls_section(
			'style_content_popup',
			[
				'label'     => __( 'Popup', 'motta-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'popup_header',
			[
				'label'        => esc_html__( 'Hide Header', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => __( 'Off', 'motta-addons' ),
				'label_on'     => __( 'On', 'motta-addons' ),
				'return_value' => 'none',
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} .motta-modal-popup__header .motta-modal-popup__title' => 'display: {{VALUE}}',
					'{{WRAPPER}} .motta-modal-popup__header' => 'padding: 0;',
				],
			]
		);

		$this->add_responsive_control(
			'popup_width',
			[
				'label'     => esc_html__( 'Width', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 1680,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-modal-popup__wrapper' => 'max-width: {{SIZE}}{{UNIT}}; width: 100%;',
				],
			]
		);

		$this->add_responsive_control(
			'popup_spacing',
			[
				'label'      => esc_html__( 'Padding', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [],
				'size_units' => [ 'px', 'em', '%' ],
				'placeholder' => [
					'top' => '20',
					'right' => '40',
					'bottom' => '50',
					'left' => '40',
				],
				'selectors'  => [
					'{{WRAPPER}} .motta-modal-popup__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.motta-rtl-smart {{WRAPPER}} .motta-modal-popup__inner' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
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
		$settings 	= $this->get_settings_for_display();
		$id 		= $this->get_id();

		$this->add_render_attribute( 'wrapper', 'class', 'motta-modal-popup--elementor' );
		$this->add_render_attribute( 'title', 'class', 'motta-modal-popup__title' );
		$this->add_render_attribute( 'description', 'class', 'motta-modal-popup__description' );
		$this->add_render_attribute( 'button', 'data-toggle', 'modal' );
		$this->add_render_attribute( 'button', 'data-target', 'popID_' . $id );
		$this->add_render_attribute( 'button', 'class', [ 'motta-button', 'motta-button-primary', 'motta-button--base', 'motta-button--large', 'motta-modal-popup__button' ] );

		$title = $settings['title'] ? '<div '. $this->get_render_attribute_string( 'title' ) .'>'. wp_kses_post( $settings['title'] ) . '</div>' : '';
		$description = $settings['description'] ? '<div '. $this->get_render_attribute_string( 'description' ) .'>'. wp_kses_post( $settings['description'] ) . '</div>' : '';

		$template_id = $settings['modal_content'];
		$template = $output = '';

		if ( $template_id ) {
			if ( class_exists( 'Elementor\Plugin' ) ) {
				$elementor_instance = \Elementor\Plugin::instance();
				$template = ! empty($elementor_instance) ? $elementor_instance->frontend->get_builder_content_for_display( $template_id ) : '';
			}

			$output = sprintf(
				'<div id="popID_%s" class="motta-modal-popup__content modal">
					<div class="modal__backdrop"></div>
					<div class="motta-modal-popup__wrapper">
						<div class="motta-modal-popup__header">%s%s</div>
						<div class="motta-modal-popup__inner">%s</div>
					</div>
				</div>',
				esc_attr($id),
				$title,
				Helper::get_svg('close', 'ui' , [ 'class' => 'popup-content__button-close modal__button-close' ] ),
				$template
			);
		}

		echo sprintf(
			'<div %s>
				%s%s%s%s
			</div>',
			$this->get_render_attribute_string( 'wrapper' ),
			$title,
			$description,
			$this->popup_render_button( $settings, $id ),
			$output
		);
	}

	protected function popup_render_button ( $settings, $id ) {
		$settings = $this->get_settings_for_display();

		$link_key = 'primary_button';

		$this->add_render_attribute( $link_key , 'class', [ 'motta-button', 'motta-button-primary', 'motta-modal-popup__button', 'motta-button--bg-color-black' ] );
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

		$this->add_render_attribute( $link_key, 'data-toggle', 'modal' );
		$this->add_render_attribute( $link_key, 'data-target', 'popID_' . $id );

		return sprintf(
			'<button %s><span class="motta-button__text">%s</span></button>',
			$this->get_render_attribute_string( $link_key ),
			$settings['button_text'],
		);
	}

	/**
	 * Get elementor section
	 */
	protected function get_elementor_section() {
		$mail_forms    = get_posts( 'post_type=elementor_library&tabs_group=library&elementor_library_type=section&posts_per_page=-1' );
		$mail_form_ids = array(
			'' => esc_html__( 'Select Content', 'motta-addons' ),
		);
		foreach ( $mail_forms as $form ) {
			$mail_form_ids[$form->ID] = $form->post_title;
		}

		return $mail_form_ids;
	}
}
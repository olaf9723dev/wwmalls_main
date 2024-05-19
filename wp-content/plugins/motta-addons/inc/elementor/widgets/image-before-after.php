<?php

namespace Motta\Addons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Image_Size;

use \Motta\Addons\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Before After Images widget
 */
class Image_Before_After extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-image-before-after';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( '[Motta] Image Before & After', 'motta-addons' );
	}

	/**
	 * Retrieve the widget circle.
	 *
	 * @return string Widget circle.
	 */
	public function get_icon() {
		return 'eicon-image-before-after';
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
			'image-slide',
			'eventmove',
		];
	}

	public function get_style_depends() {
		return [
			'image-slide-css'
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


	/**
	 * Section Content
	 */
	protected function section_content() {
		$this->start_controls_section(
			'section_content',
			[ 'label' => esc_html__( 'Content', 'motta-addons' ) ]
		);

		$this->add_control(
			'before_image',
			[
				'label'   => esc_html__( 'Before Image', 'motta-addons' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => 'https://via.placeholder.com/1170x450/f1f1f1?text=Image+Before',
				],
			]
		);

		$this->add_control(
			'after_image',
			[
				'label'   => esc_html__( 'After Image', 'motta-addons' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => 'https://via.placeholder.com/1170x450/f1f1f1?text=Image+After',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image',
				'default'   => 'full',
				'separator' => 'none',
			]
		);


		$this->end_controls_section();
	}

	/**
	 * Section Style
	 */

	protected function section_style() {
		// Content
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => __( 'Content', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'line_control_style',
			[
				'label' => esc_html__( 'Line Control', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'line_control_color',
			[
				'label'     => __( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-image-before-after .imageslide-container' => '--motta-image-slide-bg-control: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'line_control_icon_color',
			[
				'label'     => __( 'Icon Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-image-before-after .imageslide-handle .motta-svg-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render circle box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$classes = [
			'motta-image-before-after',
		];

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		$handler = '<div class="imageslide-handle">'. Helper::get_svg('move-left-right').'</div>';

		$before_image = $after_image ='';
		if ($settings['before_image']) {
			$settings['image']      = $settings['before_image'];
			$before_image = Group_Control_Image_Size::get_attachment_image_html( $settings );
		}

		if ($settings['after_image']) {
			$settings['image']      = $settings['after_image'];
			$after_image = Group_Control_Image_Size::get_attachment_image_html( $settings );
		}

		$image =  sprintf('<div class="box-thumbnail">%s%s%s</div>',$before_image, $after_image, $handler);

		echo sprintf(
			'<div %s>
				<div class="motta-image-before-after__inner"> %s</div>
			</div>',
			$this->get_render_attribute_string( 'wrapper' ),
			$image
		);
	}
}
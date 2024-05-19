<?php
namespace Motta\Addons\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Pricing Table widget
 */
class Instagram extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-instagram';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( '[Motta] Instagram', 'motta-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-instagram-gallery';
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
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'instagram', 'motta' ];
	}

	/**
	 * Register the widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_controls() {
	   	$this->start_controls_section(
			'section_instagram',
			[ 'label' => __( 'Instagram', 'motta-addons' ) ]
		);

		$this->add_control(
			'instagram_type',
			[
				'label' => esc_html__( 'Instagram type', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'token' 	=> __( 'Token', 'motta-addons' ),
					'custom' 	=> __( 'Custom', 'motta-addons' ),
				],
				'default' => 'token',
			]
		);

		$this->add_control(
			'instagram_images',
			[
				'label' => esc_html__( 'Add Images', 'motta-addons' ),
				'type' => Controls_Manager::GALLERY,
				'show_label' => false,
				'dynamic' => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'instagram_type',
							'value' => 'custom',
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'default'   => 'full',
				'separator' => 'none',
				'conditions' => [
					'terms' => [
						[
							'name' => 'instagram_type',
							'value' => 'custom',
						],
					],
				],
			]
		);

		$this->add_control(
			'instagram_link',
			[
				'label' => __( 'Link', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'conditions' => [
					'terms' => [
						[
							'name' => 'instagram_type',
							'value' => 'custom',
						],
					],
				],
			]
		);

		$this->add_control(
			'limit',
			[
				'label'   => __( 'Number of Photos', 'motta-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'default' => 12,
				'conditions' => [
					'terms' => [
						[
							'name' => 'instagram_type',
							'value' => 'token',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label'   => __( 'Columns', 'motta-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 8,
				'default' => 6,
				'selectors' => [
					'{{WRAPPER}} .motta-instagram__item' => 'width: calc( 100% / {{VALUE}} );',
				],
			]
		);

		$this->add_control(
			'image_shape',
			[
				'label'        => __( 'Image Size', 'motta-addons' ),
				'type'         => Controls_Manager::SELECT,
				'options' => [
					'cropped' => __( 'Square', 'motta-addons' ),
					'original' => __( 'Original', 'motta-addons' ),
				],
				'default' => 'cropped',
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

		if ( ! class_exists( '\Motta\Addons\Helper' ) && ! method_exists( '\Motta\Addons\Helper', 'motta_get_instagram_images' ) ) {
			return;
		}

		$this->add_render_attribute( 'wrapper', 'class', [
			'motta-instagram',
			'motta-instagram--elementor',
			'motta-instagram--' . $settings['image_shape'],
		] );

		$output = array();

		if ( $settings['instagram_type'] == 'custom' && $settings['instagram_images'] ) {
			foreach ( $settings['instagram_images'] as $image ) {
				$image_src = Group_Control_Image_Size::get_attachment_image_src( $image['id'], 'thumbnail', [
					'thumbnail_size' => $settings['thumbnail_size'],
					'thumbnail_custom_dimension' => $settings['thumbnail_custom_dimension'],
				] );

				$output[] = sprintf(
					'<li class="motta-instagram__item"><a href="%s"><img alt="%s" src="%s"/></a></li>',
					esc_url($settings['instagram_link']),
					$image['id'],
					$image_src
				);
			}
		} else {
			$medias = \Motta\Addons\Helper::motta_get_instagram_images( $settings['limit'] );

			if ( is_wp_error( $medias ) ) {
				echo $medias->get_error_message();
			} elseif ( is_array( $medias ) ) {
				$medias = array_slice( $medias, 0, $settings['limit'] );

				foreach ( $medias as $media ) {
					$output[] = sprintf(
						'<li class="motta-instagram__item">%s</li>',
						\Motta\Addons\Helper::motta_instagram_image( $media, $settings['image_shape'] )
					);
				}
			}
		}

		echo sprintf(
			'<div %s><ul class="motta-instagram__list columns-%s">%s</ul></div>',
			$this->get_render_attribute_string( 'wrapper' ),
			esc_attr( $settings['columns'] ),
			implode('', $output )
		);
	}
}
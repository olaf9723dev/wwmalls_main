<?php
namespace Motta\Addons\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
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
class Image_Grid extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve Image Box widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-image-grid';
	}

	/**
	 * Get widget title
	 *
	 * Retrieve Image Box widget title
	 *
	 * @return string Widget title
	 */
	public function get_title() {
		return __( '[Motta] Image Grid', 'motta-addons' );
	}

	/**
	 * Get widget icon
	 *
	 * Retrieve Image Box widget icon
	 *
	 * @return string Widget icon
	 */
	public function get_icon() {
		return 'eicon-gallery-grid';
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
		return [ 'image', 'grid', 'motta-addons' ];
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
			'image',
			[
				'label'   => esc_html__( 'Image', 'motta-addons' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => 'https://via.placeholder.com/100x100/f5f5f5?text=Image',
				],
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => __( 'Link', 'motta-addons' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => '',
				],
			]
		);

		$this->add_control(
			'images',
			[
				'label'      => esc_html__( 'Images', 'motta-addons' ),
				'type'       => Controls_Manager::REPEATER,
				'show_label' => true,
				'fields'     => $repeater->get_controls(),
				'default' => [
					[
						'image'   => ['url' => 'https://via.placeholder.com/100x100/f5f5f5?text=Image'],
						'link'    => ['url' => '#'],
					],
					[
						'image'   => ['url' => 'https://via.placeholder.com/100x100/f5f5f5?text=Image'],
						'link'    => ['url' => '#'],
					],
					[
						'image'   => ['url' => 'https://via.placeholder.com/100x100/f5f5f5?text=Image'],
						'link'    => ['url' => '#'],
					],
				],
			]
		);

		$this->end_controls_section();
	}

	// Tab Style
	protected function section_style() {
		// Style
		$this->start_controls_section(
			'section_style',
			[
				'label'     => __( 'Content', 'motta-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
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
					'justify' => [
						'title' => __( 'Justified', 'motta-addons' ),
						'icon' => 'eicon-justify-space-between-h',
					],
				],
				'selectors'            => [
					'{{WRAPPER}} .motta-image-grid' => 'justify-content: {{VALUE}}',
				],
				'selectors_dictionary' => [
					'left'   => 'flex-start',
					'center' => 'center',
					'right'  => 'flex-end',
					'justify'  => 'space-between',
				],
			]
		);

		$this->add_control(
			'image_heading',
			[
				'label' => __( 'Image', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image',
				'default'   => 'full',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-image-grid img' => 'border-radius: {{size}}{{UNIT}} ;',
				],
			]
		);
		$this->add_responsive_control(
			'spacing_image_bottom',
			[
				'label' => __( 'Spacing Bottom', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-image-grid__item' => 'margin-bottom: {{size}}{{UNIT}} ;',
				],
			]
		);

		$this->add_responsive_control(
			'spacing_image_item',
			[
				'label' => __( 'Spacing Item', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-image-grid__item' => 'padding: 0 {{size}}{{UNIT}} ;',
					'{{WRAPPER}} .motta-image-grid' => 'margin: 0 -{{size}}{{UNIT}} ;',
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
		?>
			<div class="motta-image-grid">
			<?php foreach( $settings['images'] as $item ) {
				?><div class="motta-image-grid__item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>"><?php

				echo Helper::render_control_link_open( 'btn_full', $item['link'], [ 'class' => 'motta-image-grid__link' ] );

				$settings['image'] = $item['image'];
				echo Group_Control_Image_Size::get_attachment_image_html( $settings );

				echo Helper::render_control_link_close( $item['link'] );
				?></div><?php
			} ?>
			</div>
		<?php
	}
}
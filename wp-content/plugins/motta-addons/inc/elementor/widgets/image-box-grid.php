<?php
namespace Motta\Addons\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
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
class Image_Box_Grid extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve Image Box widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-image-box-grid';
	}

	/**
	 * Get widget title
	 *
	 * Retrieve Image Box widget title
	 *
	 * @return string Widget title
	 */
	public function get_title() {
		return __( '[Motta] Image Box Grid', 'motta-addons' );
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
		return [ 'image', 'box', 'grid', 'motta-addons' ];
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

		$this->add_responsive_control(
			'horizontal_scrollable',
			[
				'label' => esc_html__( 'Horizontal Scrollable', 'motta-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => esc_html__( 'Yes', 'motta-addons' ),
				'label_off' => esc_html__( 'No', 'motta-addons' ),
				'prefix_class' => 'motta%s-image-box-grid-scroll--',
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label'              => esc_html__( 'Columns', 'motta-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 1,
				'max'                => 9,
				'step'				 => 0.5,
				'default'            => 6,
				'separator'          => 'after',
				'selectors' => [
					'{{WRAPPER}} .motta-image-box-grid .motta-image-box-grid__item' => 'max-width: calc( 100% / {{VALUE}} ); flex: 0 0 calc( 100% / {{VALUE}} );',
				],
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
			'title',
			[
				'label' => esc_html__( 'Title', 'motta-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Enter title text', 'motta-addons' ),
				'default' => esc_html__( 'This is title', 'motta-addons' ),
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

		$repeater->add_control(
			'image_box_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .motta-image-box-grid__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'image_box',
			[
				'label'      => esc_html__( 'Image Box', 'motta-addons' ),
				'type'       => Controls_Manager::REPEATER,
				'show_label' => true,
				'fields'     => $repeater->get_controls(),
				'default'    => [],
				'title_field' => '{{{ title }}}',
				'default' => [
					[
						'title'   => esc_html__( 'Item #1', 'motta-addons' ),
						'image'   => ['url' => 'https://via.placeholder.com/100x100/f5f5f5?text=Image'],
						'link'    => ['url' => '#'],
					],
					[
						'title'   => esc_html__( 'Item #2', 'motta-addons' ),
						'image'   => ['url' => 'https://via.placeholder.com/100x100/f5f5f5?text=Image'],
						'link'    => ['url' => '#'],
					],
					[
						'title'   => esc_html__( 'Item #3', 'motta-addons' ),
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
			'align',
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
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .motta-image-box-grid' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_spacing_box',
			[
				'label'      => esc_html__( 'Padding', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-image-box-grid' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.motta-rtl-smart {{WRAPPER}} .motta-image-box-grid' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_spacing',
			[
				'label'      => esc_html__( 'Padding Item', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-image-box-grid .motta-image-box-grid__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .motta-image-box-grid' => 'margin-right: -{{RIGHT}}{{UNIT}}; margin-left: -{{LEFT}}{{UNIT}};',
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
			'image_width',
			[
				'label' => __( 'Width', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-image-box-grid img' => 'width: {{size}}{{UNIT}} ;',
				],
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
					'{{WRAPPER}} .motta-image-box-grid img' => 'border-radius: {{size}}{{UNIT}} ;',
				],
			]
		);

		$this->add_responsive_control(
			'image_spacing',
			[
				'label' => __( 'Spacing', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-image-box-grid .motta-image-box-grid__title' => 'margin-top: {{size}}{{UNIT}} ;',
				],
			]
		);

		$this->add_control(
			'title_heading',
			[
				'label' => __( 'Title', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .motta-image-box-grid__title',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-image-box-grid__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label' => __( 'Spacing', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-image-box-grid .motta-image-box-grid__title' => 'margin-bottom: {{size}}{{UNIT}} ;',
				],
			]
		);

		$this->add_control(
			'title_ellipsis',
			[
				'label' => esc_html__( 'Text overflow ellipsis', 'motta-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'motta-addons' ),
				'label_off' => esc_html__( 'Off', 'motta-addons' ),
				'return_value' => 'yes',
				'default' => '',
				'prefix_class' => 'motta--title-ellipsis-',
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
			<div class="motta-image-box-grid">
			<?php foreach( $settings['image_box'] as $image_box => $item ) {
				?><div class="motta-image-box-grid__item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>"><?php

				echo Helper::render_control_link_open( 'btn_full', $item['link'], [ 'class' => 'motta-image-box-grid__link' ] );

				$settings['image'] = $item['image'];
				echo Group_Control_Image_Size::get_attachment_image_html( $settings );

				if( ! empty( $item['title'] ) ) :
					?><span class="motta-image-box-grid__title"><?php echo esc_html( $item['title'] ); ?></span> <?php
				endif;

				echo Helper::render_control_link_close( $item['link'] );

				?></div><?php
			} ?>
			</div>
		<?php
	}
}
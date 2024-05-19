<?php

namespace Motta\Addons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Embed;
use Motta\Addons\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Image Hotspot widget
 */
class Image_Hotspot extends Widget_Base {
	use \Motta\Addons\Elementor\Widgets\Traits\Button_Trait;

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-image-hotspot';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( '[Motta] Image Hotspot', 'motta-addons' );
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
			[ 'label' => esc_html__( 'Image Hotspot', 'motta-addons' ) ]
		);

		$this->add_responsive_control(
			'image',
			[
				'label'   => esc_html__( 'Image', 'motta-addons' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => 'https://via.placeholder.com/1170x450/f1f1f1?text=Image+HotspotImage',
				],
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'product_id',
			[
				'label'       => esc_html__( 'Product', 'motta-addons' ),
				'placeholder' => esc_html__( 'Click here and start typing...', 'motta-addons' ),
				'type'        => 'motta-autocomplete',
				'default'     => '',
				'label_block' => true,
				'multiple'    => false,
				'source'      => 'product',
				'sortable'    => true,
			]
		);

		$repeater->add_responsive_control(
			'point_position_x',
			[
				'label'      => esc_html__( 'Point Position X', 'motta-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
				],
				'size_units' => [ '%', 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-image-hotspot {{CURRENT_ITEM}} .motta-hotspot__point' => 'left: {{SIZE}}{{UNIT}};',
					'.motta-rtl-smart {{WRAPPER}} .motta-image-hotspot {{CURRENT_ITEM}} .motta-hotspot__point' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
				],
			]
		);

		$repeater->add_responsive_control(
			'point_position_y',
			[
				'label'      => esc_html__( 'Point Position Y', 'motta-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
				],
				'size_units' => [ '%', 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-image-hotspot {{CURRENT_ITEM}} .motta-hotspot__point' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$repeater->add_responsive_control(
			'product_content_position_x',
			[
				'label'      => esc_html__( 'Product Position X', 'motta-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => - 1000,
						'max' => 1000,
					],
					'%'  => [
						'min' => - 100,
						'max' => 100,
					],
				],
				'default'    => [],
				'size_units' => [ '%', 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-image-hotspot {{CURRENT_ITEM}} .motta-hotspot__product' => 'left: {{SIZE}}{{UNIT}};',
					'.motta-rtl-smart {{WRAPPER}} .motta-image-hotspot {{CURRENT_ITEM}} .motta-hotspot__product' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
				],
			]
		);

		$repeater->add_responsive_control(
			'product_content_position_y',
			[
				'label'      => esc_html__( 'Product Position Y', 'motta-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => - 1000,
						'max' => 1000,
					],
					'%'  => [
						'min' => - 100,
						'max' => 100,
					],
				],
				'default'    => [],
				'size_units' => [ '%', 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-image-hotspot {{CURRENT_ITEM}} .motta-hotspot__product' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hotspots',
			[
				'label' => esc_html__( 'Hotspots', 'motta-addons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
			]
		);


		$this->end_controls_section();

	}

	// Tab Style
	protected function section_style() {
		// Arrows
		$this->start_controls_section(
			'section_style_point',
			[
				'label' => esc_html__( 'Point', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'primary_bgcolor',
			[
				'label'     => esc_html__( 'Primary Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-image-hotspot .motta-hotspot__point' => ' --rz-point-color-primary: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'secondary_bgcolor',
			[
				'label'     => esc_html__( 'Secondary Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-image-hotspot .motta-hotspot__point' => ' --rz-point-color-secondary: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_product',
			[
				'label' => esc_html__( 'Product', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'product_title_heading',
			[
				'label'     => esc_html__( 'Title', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'lookbook_title_typography',
				'selector' => '{{WRAPPER}} .motta-image-hotspot .motta-hotspot__product .motta-hotspot__product-name',
			]
		);

		$this->add_control(
			'product_title_color',
			[
				'label'     => esc_html__( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-image-hotspot .motta-hotspot__product .motta-hotspot__product-name' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_price_heading',
			[
				'label'     => esc_html__( 'Price', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'product_price_typography',
				'selector' => '{{WRAPPER}} .motta-image-hotspot .motta-hotspot__product .motta-hotspot__product-price',
			]
		);

		$this->add_control(
			'product_price_color',
			[
				'label'     => esc_html__( 'Regular Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-image-hotspot .motta-hotspot__product .motta-hotspot__product-price' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_price_color_sale',
			[
				'label'     => esc_html__( 'Sale Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-image-hotspot .motta-hotspot__product motta-hotspot__product-price ins' => 'color: {{VALUE}}',
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
			'motta-image-hotspot',
		];

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		$image = ! empty( $settings['image'] ) ? $settings['image']['url'] : '';
		?>
			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
				<div class="motta-image-hotspot__featured-image"><?php echo sprintf('<img alt="%s" src="%s"/>', esc_html__( 'Image', 'motta-addons' ), esc_url($image)); ?></div>
				<div class="motta-image-hotspot__content">
				<?php
				$output = '';
				foreach( $settings['hotspots'] as $hotspot ) {
					if( empty( $hotspot['product_id'] ) ) {
						continue;
					}
					$product_id = $hotspot['product_id'];
					$product       = wc_get_product( $product_id );

					if ( empty( $product ) ) {
					  continue;
					}

					$rating_html = '';
					if( wc_review_ratings_enabled() ) {
						$rating_html = wc_get_rating_html( $product->get_average_rating() );
					}


					$output .= sprintf(
						'<div class="motta-hotspot-item elementor-repeater-item-%s">
						<div class="motta-hotspot__point"></div>
						<div class="motta-hotspot__product">
						  <div class="motta-hotspot__product-image">%s</div>
						  <div class="motta-hotspot__product-summary">
							<h6 class="motta-hotspot__product-name">%s</h6>
							%s
							<div class="motta-hotspot__product-price">%s</div>
						  </div>
						  <a class="motta-hotspot__product-link" href="%s"></a>
						</div>
						</div>',
						esc_attr($hotspot['_id']),
						$product->get_image( 'thumbnail' ),
						$product->get_name(),
						$rating_html,
						$product->get_price_html(),
						get_permalink( $product_id )
					);

				}

				echo $output;
				?>
				</div>
			</div>
		<?php
	}

}
<?php
namespace Motta\Addons\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use MailPoetVendor\Symfony\Component\Validator\Constraints\Length;
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
class Product_360_Viewer extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve Image Box widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-product-360-viewer';
	}

	/**
	 * Get widget title
	 *
	 * Retrieve Image Box widget title
	 *
	 * @return string Widget title
	 */
	public function get_title() {
		return __( '[Motta] 360 Degree Viewer', 'motta-addons' );
	}

	/**
	 * Get widget icon
	 *
	 * Retrieve Image Box widget icon
	 *
	 * @return string Widget icon
	 */
	public function get_icon() {
		return 'eicon-product-images';
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
		return [ '360', 'viewer', 'degree', 'motta-addons' ];
	}

	public function get_script_depends() {
		return [
			'threesixty'
		];
	}

	/**
	 * Register heading widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Images', 'motta-addons' ),
			]
		);

		$this->add_control(
			'images',
			[
				'label'   => esc_html__( 'Images', 'motta-addons' ),
				'type'    => Controls_Manager::GALLERY,
				'default' => [
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image',
				'default'   => 'full',
			]
		);

		$this->add_control(
			'width',
			[
				'label' => __( 'Width', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1500,
					],
				],
				'default' => [
					'size' => '1000',
				],
			]
		);

		$this->add_control(
			'height',
			[
				'label' => __( 'Height', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1500,
					],
				],
				'default' => [
					'size' => '830',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Images', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'nav_spacing',
			[
				'label'      => esc_html__( 'Navigation Spacing', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'range'     => [
					'px' => [
						'max' => 1000,
						'min' => 0,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .motta-360-degree-viewer .motta-gallery-degree__nav-bar' => 'bottom: {{SIZE}}{{UNIT}};',
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
		$this->add_render_attribute(
			'wrapper', 'class', [
				'motta-360-degree-viewer',
			]
		);
		if( empty( $settings['images'] ) ) {
			return;
		}
		$image_list = array();
		foreach( $settings['images'] as $image ) {
			if( empty( $image['id'] ) ) {
				continue;
			}

			$image_list[] = Group_Control_Image_Size::get_attachment_image_src( $image['id'], 'image', $settings );
		}
		$options = array();
		$options['total_frames'] = count($settings['images']);
		$options['images'] = $image_list;
		$options['width'] = $settings['width']['size'];
		$options['height'] =  $settings['height']['size'];

		$this->add_render_attribute(
			'wrapper', 'data-options', [
				wp_json_encode($options)
			]
		);
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<div class="motta-images-gallery-degree">
				<div class="motta-gallery-degree__nav-bar">
					<a href="#" class="nav-bar__prev"><?php echo \Motta\Addons\Helper::get_svg( 'left' ); ?></a>
					<a href="#" class="nav-bar__run"><?php echo \Motta\Addons\Helper::get_svg( 'video', '', array( 'class' => 'play' ) ); ?> <?php echo \Motta\Addons\Helper::get_svg( 'pause', '', array( 'class' => 'pause' ) ); ?></a>
					<a href="#" class="nav-bar__next"><?php echo \Motta\Addons\Helper::get_svg( 'right' ); ?></a>
				</div>
				<div class="motta-gallery-degree__spinner"></div>
				<ul class="product-degree__images"></ul>
			</div>
		</div>
		<?php
	}
}
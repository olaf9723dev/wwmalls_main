<?php
namespace Motta\Addons\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Text_Shadow;
use Motta\Addons\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor image gallery widget.
 *
 * Elementor widget that displays a set of images in an aligned grid.
 *
 * @since 1.0.0
 */
class Gallery extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve image gallery widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-gallery';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve image gallery widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( '[Motta] Gallery', 'motta-addons' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve image gallery widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
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
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'image', 'photo', 'visual', 'gallery' ];
	}

	public function get_script_depends() {
		return [
			'masonry',
			'motta-elementor-widgets'
		];
	}

	/**
	 * Register image gallery widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 3.1.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_gallery',
			[
				'label' => esc_html__( 'Image Gallery', 'motta-addons' ),
			]
		);

		$this->add_control(
			'wp_gallery',
			[
				'label' => esc_html__( 'Add Images', 'motta-addons' ),
				'type' => Controls_Manager::GALLERY,
				'show_label' => false,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'default'   => 'full',
				'separator' => 'none',
			]
		);

		$gallery_columns = range( 1, 10 );
		$gallery_columns = array_combine( $gallery_columns, $gallery_columns );

		$this->add_responsive_control(
			'gallery_columns',
			[
				'label' => esc_html__( 'Columns', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 3,
				'options' => $gallery_columns,
				'selectors' => [
					'{{WRAPPER}} .gallery' => 'grid-template-columns: repeat({{SIZE}}, 1fr);',
				],
			]
		);

		$this->add_control(
			'gallery_link',
			[
				'label' => esc_html__( 'Link', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'file',
				'options' => [
					'file' => esc_html__( 'Media File', 'motta-addons' ),
					'attachment' => esc_html__( 'Attachment Page', 'motta-addons' ),
					'none' => esc_html__( 'None', 'motta-addons' ),
				],
			]
		);

		$this->add_control(
			'open_lightbox',
			[
				'label' => esc_html__( 'Lightbox', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'motta-addons' ),
					'yes' => esc_html__( 'Yes', 'motta-addons' ),
					'no' => esc_html__( 'No', 'motta-addons' ),
				],
				'condition' => [
					'gallery_link' => 'file',
				],
			]
		);

		$this->add_control(
			'gallery_rand',
			[
				'label' => esc_html__( 'Order By', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Default', 'motta-addons' ),
					'rand' => esc_html__( 'Random', 'motta-addons' ),
				],
				'default' => '',
			]
		);

		$this->add_control(
			'view',
			[
				'label' => esc_html__( 'View', 'motta-addons' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->add_control(
			'gallery_masonry',
			[
				'label'        => esc_html__( 'Gallery Masonry', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => __( 'Off', 'motta-addons' ),
				'label_on'     => __( 'On', 'motta-addons' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'prefix_class' => 'motta-gallery__masonry--',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_gallery_images',
			[
				'label' => esc_html__( 'Images', 'motta-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_spacing',
			[
				'label' => esc_html__( 'Spacing', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Default', 'motta-addons' ),
					'custom' => esc_html__( 'Custom', 'motta-addons' ),
				],
				'prefix_class' => 'gallery-spacing-',
				'default' => '',
			]
		);

		$this->add_control(
			'image_spacing_custom',
			[
				'label' => esc_html__( 'Image Spacing', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'show_label' => false,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .gallery-item' => 'padding: 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .gallery' => 'margin: 0 -{{SIZE}}{{UNIT}} -{{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.motta-gallery__masonry--yes .motta-gallery--elementor' => 'margin: 0 -{{SIZE}}{{UNIT}} -{{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'image_spacing' => 'custom',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} .gallery-item img',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'motta-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .gallery-item img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_caption',
			[
				'label' => esc_html__( 'Caption', 'motta-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'gallery_display_caption',
			[
				'label' => esc_html__( 'Display', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Show', 'motta-addons' ),
					'none' => esc_html__( 'Hide', 'motta-addons' ),
				],
				'selectors' => [
					'{{WRAPPER}} .gallery-item .gallery-caption' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Alignment', 'motta-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'motta-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'motta-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'motta-addons' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'motta-addons' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .gallery-item .gallery-caption' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'gallery_display_caption' => '',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Text Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .gallery-item .gallery-caption' => 'color: {{VALUE}};',
				],
				'condition' => [
					'gallery_display_caption' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .gallery-item .gallery-caption',
				'condition' => [
					'gallery_display_caption' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'caption_shadow',
				'selector' => '{{WRAPPER}} .gallery-item .gallery-caption',
			]
		);

		$this->add_responsive_control(
			'caption_spacing',
			[
				'label' => __( 'Spacing', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					]
				],
				'default' => [],
				'selectors' => [
					'{{WRAPPER}} .gallery-item .gallery-caption' => 'margin-top: {{SIZE}}{{UNIT}};',

				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render image gallery widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( ! $settings['wp_gallery'] ) {
			return;
		}

		$ids = wp_list_pluck( $settings['wp_gallery'], 'id' );

		$this->add_render_attribute( 'shortcode', 'ids', implode( ',', $ids ) );
		$this->add_render_attribute( 'shortcode', 'size', $settings['thumbnail_size'] );

		if ( $settings['gallery_columns'] ) {
			$this->add_render_attribute( 'shortcode', 'columns', $settings['gallery_columns'] );
		}

		if ( $settings['gallery_link'] ) {
			$this->add_render_attribute( 'shortcode', 'link', $settings['gallery_link'] );
		}

		if ( ! empty( $settings['gallery_rand'] ) ) {
			$this->add_render_attribute( 'shortcode', 'orderby', $settings['gallery_rand'] );
		}
		?>
		<div class="elementor-image-gallery motta-gallery--elementor">
			<?php
			add_filter( 'wp_get_attachment_link', [ $this, 'add_lightbox_data_to_image_link' ], 10, 2 );

			echo do_shortcode( '[gallery ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );

			remove_filter( 'wp_get_attachment_link', [ $this, 'add_lightbox_data_to_image_link' ] );
			?>
		</div>
		<?php
	}
}

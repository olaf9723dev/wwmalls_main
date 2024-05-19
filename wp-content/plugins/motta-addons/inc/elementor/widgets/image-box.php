<?php
namespace Motta\Addons\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Motta\Addons\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Image Box widget.
 *
 * Elementor widget that displays an eye-catching headlines.
 *
 * @since 1.0.0
 */
class Image_Box extends Widget_Base {
	use \Motta\Addons\Elementor\Widgets\Traits\Button_Trait;

	/**
	 * Get widget name.
	 *
	 * Retrieve Image Box widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-image-box';
	}

	/**
	 * Get widget title
	 *
	 * Retrieve Image Box widget title
	 *
	 * @return string Widget title
	 */
	public function get_title() {
		return __( '[Motta] Image Box', 'motta-addons' );
	}

	/**
	 * Get widget icon
	 *
	 * Retrieve Image Box widget icon
	 *
	 * @return string Widget icon
	 */
	public function get_icon() {
		return 'eicon-image-box';
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
		return [ 'image', 'box', 'motta-addons' ];
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
				'label' => __( 'Content', 'motta-addons' ),
			]
		);

		$this->add_control(
			'image',
			[
				'label'   => esc_html__( 'Image', 'motta-addons' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => 'https://via.placeholder.com/100x100/f5f5f5?text=Image',
				],
			]
		);

		$this->add_control(
			'title_type',
			[
				'label' => esc_html__( 'Title Type', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'text'  => esc_html__( 'Text', 'motta-addons' ),
					'svg' 	=> esc_html__( 'SVG Icon', 'motta-addons' ),
					'image' => esc_html__( 'Image', 'motta-addons' ),
					'external' 	=> esc_html__( 'External', 'motta-addons' ),
				],
				'default' => 'text',
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'motta-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Enter title text', 'motta-addons' ),
				'default' => esc_html__( 'This is title', 'motta-addons' ),
				'conditions' => [
					'terms' => [
						[
							'name' => 'title_type',
							'operator' => '==',
							'value' => 'text'
						],
					]
				]
			]
		);

		$this->add_control(
			'title_icon',
			[
				'label' => __( 'Title Icon', 'motta-addons' ),
				'type' => Controls_Manager::ICONS,
				'default' => [],
				'conditions' => [
					'terms' => [
						[
							'name' => 'title_type',
							'operator' => '==',
							'value' => 'svg'
						],
					]
				],
			]
		);

		$this->add_responsive_control(
			'title_image',
			[
				'label'   => esc_html__( 'Title Image', 'motta-addons' ),
				'type'    => Controls_Manager::MEDIA,
				'conditions' => [
					'terms' => [
						[
							'name' => 'title_type',
							'operator' => '==',
							'value' => 'image'
						],
					],
				],
			]
		);

		$this->add_control(
			'external_url',
			[
				'label' => esc_html__( 'External URL', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'title_type',
							'value' => 'external',
						],
					],
				],
			]
		);

		$this->add_control(
			'description', [
				'label' => esc_html__( 'Description', 'motta-addons' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
			]
		);

		$controls = [
			'button_text_label' => __( 'Button Text', 'motta-addons' ),
			'button_icon'	    => true
		];

		$this->add_control(
			'button_link_type',
			[
				'label'   => esc_html__( 'Link Apply', 'motta-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'button' 		=> esc_html__( 'Only Button Text', 'motta-addons' ),
					'image-title'  	=> esc_html__( 'Image and Title', 'motta-addons' ),
					'all'  	 		=> esc_html__( 'Whole Image', 'motta-addons' ),
				],
				'default' => 'image-title',
				'toggle'  => false,
			]
		);

		$this->register_button_content_controls( $controls );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_sale',
			[
				'label' => __( 'Sale', 'motta-addons' ),
			]
		);

		$this->add_control(
			'sale_text',
			[
				'label'       => esc_html__( 'Sale Text', 'motta-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your text', 'motta-addons' ),
				'label_block' => true,
				'default'     => '',
			]
		);

		$this->add_control(
			'sale_unit',
			[
				'label'       => esc_html__( 'Sale Unit', 'motta-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your text', 'motta-addons' ),
				'label_block' => true,
				'default'     => '',
			]
		);

		$this->end_controls_section();

		// Style
		$this->start_controls_section(
			'section_style',
			[
				'label'     => __( 'Content', 'motta-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'flexible_items',
			[
				'label' => esc_html__( 'Flexible Items', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Column', 'motta-addons' ),
					'flex' 	  => esc_html__( 'Row', 'motta-addons' ),
				],
				'selectors'            => [
					'{{WRAPPER}} .motta-image-box, {{WRAPPER}} .motta-image-box .motta-image-box__link-wrapper' => 'display: {{VALUE}}; justify-content: space-between; align-items: center;',
				],
				'prefix_class' => 'motta-image-box__flex--',
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
					'{{WRAPPER}} .motta-image-box, {{WRAPPER}} .motta-image-box' => 'text-align: {{VALUE}};',
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

		$this->add_control(
			'image_position',
			[
				'label' => esc_html__( 'Position', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'before',
				'options' => [
					'before' => esc_html__( 'Before Content', 'motta-addons' ),
					'after'  => esc_html__( 'After Content', 'motta-addons' ),
				],
				'prefix_class' => 'motta-image-box__image--',
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
					'{{WRAPPER}} .motta-image-box img' => 'border-radius: {{size}}{{UNIT}} ;',
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
					'{{WRAPPER}} .motta-image-box .motta-image-box__title' => 'margin-top: {{size}}{{UNIT}};',
					'{{WRAPPER}}.motta-image-box__flex--flex .motta-image-box .motta-image-box__title' => 'margin-top: 0;',
					'{{WRAPPER}}.motta-image-box__flex--flex.motta-image-box__image--before .motta-image-box .motta-image-box__summary' => 'margin-left: {{size}}{{UNIT}}; margin-right: 0;',
					'.motta-rtl-smart {{WRAPPER}}.motta-image-box__flex--flex.motta-image-box__image--before .motta-image-box .motta-image-box__summary' => 'margin-right: {{size}}{{UNIT}}; margin-left: 0;',
					'{{WRAPPER}}.motta-image-box__flex--flex.motta-image-box__image--after .motta-image-box .motta-image-box__summary' => 'margin-right: {{size}}{{UNIT}}; margin-left: 0;',
					'.motta-rtl-smart {{WRAPPER}}.motta-image-box__flex--flex.motta-image-box__image--after .motta-image-box .motta-image-box__summary' => 'margin-left: {{size}}{{UNIT}}; margin-right: 0;',
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
				'selector' => '{{WRAPPER}} .motta-image-box__title',
			]
		);

		$this->add_responsive_control(
			'title_size',
			[
				'label' => __( 'Size', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1170,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-image-box .motta-image-box__title svg' => 'width: {{size}}{{UNIT}};height: auto;',
					'{{WRAPPER}} .motta-image-box .motta-image-box__title img' => 'max-width: {{size}}{{UNIT}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'title_type',
							'operator' => '!=',
							'value' => 'text'
						],
					]
				]
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-image-box__title' => 'color: {{VALUE}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'title_type',
							'operator' => '==',
							'value' => 'text'
						],
					]
				]
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
					'{{WRAPPER}} .motta-image-box .motta-image-box__title' => 'margin-bottom: {{size}}{{UNIT}} ;',
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
				'conditions' => [
					'terms' => [
						[
							'name' => 'title_type',
							'operator' => '==',
							'value' => 'text'
						],
					]
				]
			]
		);

		$this->add_control(
			'description_heading',
			[
				'label' => __( 'Description', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .motta-image-box__description',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-image-box__description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_heading',
			[
				'label' => __( 'Button', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$controls = [
			'skin'     => 'subtle',
			'size'      => 'medium',
		];

		$this->register_button_style_controls($controls);

		$this->end_controls_section();

		$this->section_style_sale();
	}

	protected function section_style_sale(){
		$this->start_controls_section(
			'section_content_sale',
			[
				'label' => __( 'Sale', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'sale_position_x',
			[
				'label'     => esc_html__( 'Position X', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .motta-image-box__sale' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sale_position_y',
			[
				'label'     => esc_html__( 'Position Y', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .motta-image-box__sale' => 'top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'sale_width',
			[
				'label'     => esc_html__( 'Width', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [],
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-image-box__sale' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sale_height',
			[
				'label'     => esc_html__( 'Height', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [],
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-image-box__sale' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'sale_border_radius',
			[
				'label'      => __( 'Border Radius', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-image-box__sale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'sale_bgcolor',
			[
				'label'     => __( 'Background Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-image-box__sale' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sale_color',
			[
				'label'     => __( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-image-box__sale' => 'color: {{VALUE}};',
				],
			]
		);

		// Sale Text
		$this->add_control(
			'content_style_saletext',
			[
				'label' => __( 'Sale Text', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sale_text_color',
			[
				'label'     => __( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-image-box__sale-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sale_text_typography',
				'selector' => '{{WRAPPER}} .motta-image-box__sale-text',
			]
		);

		$this->add_responsive_control(
			'sale_text_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-image-box__sale-text' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		// Sale Unit
		$this->add_control(
			'content_style_saleunit',
			[
				'label' => __( 'Sale Unit', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sale_unit_color',
			[
				'label'     => __( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-image-box__sale-unit' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sale_unit_typography',
				'selector' => '{{WRAPPER}} .motta-image-box__sale-unit',
			]
		);
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
		<div class="motta-image-box">
			<?php
				if ( $settings['primary_button_link']['url'] && $settings['button_link_type'] == 'all' ) {
					echo Helper::render_control_link_open( 'btn_full', $settings['primary_button_link'],  [ 'class' => 'motta-image-box__link-wrapper motta-image-box__link' ] );
				}
			?>
				<?php $this->render_image($settings, 'before'); ?>
				<div class="motta-image-box__summary">
					<?php
						if ( $settings['primary_button_link']['url'] && $settings['button_link_type'] == 'image-title' ) {
							echo Helper::render_control_link_open( 'btn_full', $settings['primary_button_link'],  [ 'class' => 'motta-image-box__link' ] );
						}
						if( $settings['title_type'] == 'text' ) {
							echo $settings['title'] ? '<div class="motta-image-box__title">' . $settings['title'] . '</div>' : '';
						} elseif( $settings['title_type'] == 'image' ) {
							echo ! empty( $settings['title_image']['url'] ) ? sprintf( '<div class="motta-image-box__title motta-image-box__title--image"><img alt="%s" src="%s" /></div>', $settings['title'], esc_url( $settings['title_image']['url'] ) ) : '';
						} elseif( $settings['title_type'] == 'external' ) {
							echo ! empty( $settings['external_url'] ) ? sprintf( '<div class="motta-image-box__title motta-image-box__title--image"><img alt="%s" src="%s" /></div>', $settings['title'], esc_url( $settings['external_url'] ) ) : '';
						} elseif (! empty( $settings['title_icon']['value'] ) ) {
							echo '<div class="motta-image-box__title motta-image-box__title--icon">';
							Icons_Manager::render_icon( $settings['title_icon'], [ 'aria-hidden' => 'true' ] );
							echo '</div>';
						}
						if ( $settings['primary_button_link']['url'] && $settings['button_link_type'] == 'image-title' ) {
							echo Helper::render_control_link_close(  $settings['primary_button_link'] );
						}
					?>
					<?php echo ! empty( $settings['description'] ) ? '<div class="motta-image-box__description">' . $settings['description'] . '</div>' : ''; ?>
				</div>
				<?php $this->render_image($settings, 'after'); ?>

				<?php
					if( ! empty( $settings['sale_text'] ) || ! empty( $settings['sale_unit'] ) ) {
						echo sprintf(
									'<div class="motta-image-box__sale">%s %s</div>',
									! empty( $settings['sale_text'] ) ? '<div class="motta-image-box__sale-text">' . esc_html( $settings['sale_text'] ) . '</div>' : '',
									! empty( $settings['sale_unit'] ) ? '<div class="motta-image-box__sale-unit">' . esc_html( $settings['sale_unit'] ) . '</div>' : ''
								);
					}
				?>

			<?php $this->render_button(); ?>
			<?php
				if ( $settings['primary_button_link']['url'] && $settings['button_link_type'] == 'all' ) {
					echo Helper::render_control_link_close(  $settings['primary_button_link'] );
				}
			?>
		</div>
		<?php
	}

	function render_image( $settings, $position = 'before' ) {
		$image = Group_Control_Image_Size::get_attachment_image_html( $settings );
		if( $settings['image_position'] != $position ) {
			return;
		}

		if ( $settings['primary_button_link']['url'] && $settings['button_link_type'] == 'image-title' ) {
			echo Helper::control_url( 'btn_full', $settings['primary_button_link'],  $image, [ 'class' => 'motta-image-box__link' ] );
		} else {
			echo $image;
		}
	}
}
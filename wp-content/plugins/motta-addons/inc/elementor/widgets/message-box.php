<?php
namespace Motta\Addons\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor alert widget.
 *
 * Elementor widget that displays a collapsible display of content in an toggle
 * style, allowing the user to open multiple items.
 *
 * @since 1.0.0
 */
class Message_Box extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve alert widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-alert';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve alert widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( '[Motta] Message Box', 'motta-addons' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve alert widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-alert';
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
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'alert', 'notice', 'message', 'box', 'motta' ];
	}

	/**
	 * Register alert widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 3.1.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_alert',
			[
				'label' => esc_html__( 'Message', 'motta-addons' ),
			]
		);

		$this->add_control(
			'alert_type',
			[
				'label' => esc_html__( 'Type', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'info',
				'options' => [
					'info' => esc_html__( 'Info', 'motta-addons' ),
					'success' => esc_html__( 'Success', 'motta-addons' ),
					'warning' => esc_html__( 'Warning', 'motta-addons' ),
					'danger' => esc_html__( 'Danger', 'motta-addons' ),
				],
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'alert_title',
			[
				'label' => esc_html__( 'Title & Description', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your title', 'motta-addons' ),
				'default' => esc_html__( 'This is an Alert', 'motta-addons' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'alert_description',
			[
				'label' => esc_html__( 'Content', 'motta-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Enter your description', 'motta-addons' ),
				'default' => esc_html__( 'I am a description. Click the edit button to change this text.', 'motta-addons' ),
				'separator' => 'none',
				'show_label' => false,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'show_alert_icon',
			[
				'label' => esc_html__( 'Alert Icon', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hide',
				'options' => [
					'show' => esc_html__( 'Show', 'motta-addons' ),
					'hide' => esc_html__( 'Hide', 'motta-addons' ),
				],
			]
		);

		$this->add_control(
			'alert_icon',
			[
				'label' => esc_html__( 'Icon', 'motta-addons' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin' => 'inline',
				'label_block' => false,
				'render_type' => 'template',
				'skin_settings' => [
					'inline' => [
						'none' => [
							'label' => 'Default',
							'icon' => 'eicon-close',
						],
						'icon' => [
							'icon' => 'eicon-star',
						],
					],
				],
				'recommended' => [
					'fa-regular' => [
						'times-circle',
					],
					'fa-solid' => [
						'times',
						'times-circle',
					],
				],
				'condition' => [
					'show_alert_icon' => 'show',
				],
			]
		);

		$this->add_control(
			'show_dismiss',
			[
				'label' => esc_html__( 'Dismiss Icon', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hide',
				'options' => [
					'show' => esc_html__( 'Show', 'motta-addons' ),
					'hide' => esc_html__( 'Hide', 'motta-addons' ),
				],
			]
		);

		$this->add_control(
			'dismiss_icon',
			[
				'label' => esc_html__( 'Icon', 'motta-addons' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin' => 'inline',
				'label_block' => false,
				'render_type' => 'template',
				'skin_settings' => [
					'inline' => [
						'none' => [
							'label' => 'Default',
							'icon' => 'eicon-close',
						],
						'icon' => [
							'icon' => 'eicon-star',
						],
					],
				],
				'recommended' => [
					'fa-regular' => [
						'times-circle',
					],
					'fa-solid' => [
						'times',
						'times-circle',
					],
				],
				'condition' => [
					'show_dismiss' => 'show',
				],
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

		$this->end_controls_section();

		$this->start_controls_section(
			'section_type',
			[
				'label' => esc_html__( 'Alert', 'motta-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'background',
			[
				'label' => esc_html__( 'Background Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-alert' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_box_shadow',
				'label' => __( 'Box Shadow', 'motta-addons' ),
				'selector' => '{{WRAPPER}} .motta-alert',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'Title', 'motta-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Text Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-alert__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'alert_title',
				'selector' => '{{WRAPPER}} .motta-alert__title'
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_shadow',
				'selector' => '{{WRAPPER}} .motta-alert__title',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_description',
			[
				'label' => esc_html__( 'Description', 'motta-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Text Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-alert__description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'alert_description',
				'selector' => '{{WRAPPER}} .motta-alert__description'
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'description_shadow',
				'selector' => '{{WRAPPER}} .motta-alert__description',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_dismiss_icon',
			[
				'label' => esc_html__( 'Dismiss Icon', 'motta-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_dismiss' => 'show',
				],
			]
		);

		$this->add_responsive_control(
			'dismiss_icon_size',
			[
				'label' => esc_html__( 'Size', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--dismiss-icon-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dismiss_icon_vertical_position',
			[
				'label' => esc_html__( 'Vertical Position', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}' => '--dismiss-icon-vertical-position: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dismiss_icon_horizontal_position',
			[
				'label' => esc_html__( 'Horizontal Position', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}' => '--dismiss-icon-horizontal-position: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'dismiss_icon_colors' );

		$this->start_controls_tab( 'dismiss_icon_normal_colors', [
			'label' => esc_html__( 'Normal', 'motta-addons' ),
		] );

		$this->add_control(
			'dismiss_icon_normal_color',
			[
				'label' => esc_html__( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--dismiss-icon-normal-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'dismiss_icon_hover_colors', [
			'label' => esc_html__( 'Hover', 'motta-addons' ),
		] );

		$this->add_control(
			'dismiss_icon_hover_color',
			[
				'label' => esc_html__( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--dismiss-icon-hover-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dismiss_icon_hover_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--dismiss-icon-hover-transition-duration: {{SIZE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Render alert widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( ! empty( $settings['alert_type'] ) ) {
			$classes = [
				'motta-alert',
				'motta-alert--' . $settings['alert_type'],
				'show' === $settings['show_alert_icon'] ? 'motta-alert-icon' : ''
			];

			$this->add_render_attribute( 'wrapper', 'class', $classes );
		}

		$this->add_render_attribute( 'wrapper', 'role', 'alert' );

		$this->add_render_attribute( 'alert_title', 'class', 'motta-alert__title' );

		$this->add_inline_editing_attributes( 'alert_title', 'none' );

		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<span <?php $this->print_render_attribute_string( 'alert_title' ); ?>><?php $this->print_unescaped_setting( 'alert_title' ); ?></span>
			<?php
			if ( ! Utils::is_empty( $settings['alert_description'] ) ) :
				$this->add_render_attribute( 'alert_description', 'class', 'motta-alert__description' );

				$this->add_inline_editing_attributes( 'alert_description' );
				?>
				<span <?php $this->print_render_attribute_string( 'alert_description' ); ?>><?php $this->print_unescaped_setting( 'alert_description' ); ?></span>
			<?php endif; ?>
			<?php if ( 'show' === $settings['show_dismiss'] ) : ?>
				<button type="button" class="motta-button motta-button--text motta-alert__dismiss">
					<?php
					if ( ! empty( $settings['dismiss_icon']['value'] ) ) {
						Icons_Manager::render_icon( $settings['dismiss_icon'], [
							'aria-hidden' => 'true',
						] );
					} else { ?>
						<span aria-hidden="true">&times;</span>
					<?php } ?>
					<span class="elementor-screen-only"><?php echo esc_html__( 'Dismiss this alert.', 'motta-addons' ); ?></span>
				</button>
			<?php endif; ?>
			<?php if ( 'show' === $settings['show_alert_icon'] ) : ?>
				<div class="motta-alert__icons motta-svg-icon ">
					<?php
					if ( ! empty( $settings['alert_icon']['value'] ) ) {
						Icons_Manager::render_icon( $settings['alert_icon'], [
							'aria-hidden' => 'true',
						] );
					} else { ?>
						<span aria-hidden="true">&times;</span>
					<?php } ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

}

<?php
namespace Motta\Addons\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Motta\Addons\Helper;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor checklist widget.
 *
 * Elementor widget that displays an eye-catching headlines.
 *
 * @since 1.0.0
 */
class CheckList extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve checklist widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-checklist';
	}

	/**
	 * Get widget title
	 *
	 * Retrieve checklist widget title
	 *
	 * @return string Widget title
	 */
	public function get_title() {
		return __( '[Motta] Timeline', 'motta-addons' );
	}

	/**
	 * Get widget icon
	 *
	 * Retrieve checklist widget icon
	 *
	 * @return string Widget icon
	 */
	public function get_icon() {
		return 'eicon-checkbox';
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
		return [ 'check', 'list', 'motta-addons' ];
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
			'section_list',
			[
				'label' => __( 'List', 'motta-addons' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'motta-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Enter title text', 'motta-addons' ),
			]
		);

		$repeater->add_control(
			'description', [
				'label' => esc_html__( 'Description', 'motta-addons' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
			]
		);

		$repeater->add_control(
			'check',
			[
				'label' => esc_html__( 'Check', 'motta-addons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'motta-addons' ),
				'label_off' => esc_html__( 'Off', 'motta-addons' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'list',
			[
				'label' => esc_html__( 'List', 'motta-addons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default' => [
					[
						'title'   		=> esc_html__( 'Item #1', 'motta-addons' ),
						'description'   => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
					],
					[
						'title'   		=> esc_html__( 'Item #2', 'motta-addons' ),
						'description'   => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
					],
					[
						'title'   		=> esc_html__( 'Item #3', 'motta-addons' ),
						'description'   => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
					],
				],
			]
		);

		$this->end_controls_section();

		// Style
		$this->start_controls_section(
			'section_style_list',
			[
				'label'     => __( 'List', 'motta-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'items_spacing',
			[
				'label' => __( 'Items Spacing', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-checklist__item' => 'margin-bottom: {{size}}{{UNIT}} ;',
				],
			]
		);

		$this->start_controls_tabs(
			'style_tabs'
		);

		$this->start_controls_tab(
			'style_checked_tab',
			[
				'label' => esc_html__( 'Checked', 'motta-addons' ),
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

			$this->add_control(
				'title_color',
				[
					'label' => __( 'Color', 'motta-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .motta-checklist__item:not(.none) .motta-checklist__title' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'title_typography',
					'selector' => '{{WRAPPER}} .motta-checklist__item:not(.none) .motta-checklist__title',
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
							'max' => 500,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .motta-checklist__item:not(.none) .motta-checklist__title' => 'margin-bottom: {{size}}{{UNIT}} ;',
					],
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

			$this->add_control(
				'description_color',
				[
					'label' => __( 'Color', 'motta-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .motta-checklist__item:not(.none) .motta-checklist__description' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'description_typography',
					'selector' => '{{WRAPPER}} .motta-checklist__item:not(.none) .motta-checklist__description',
				]
			);

			$this->add_control(
				'icon',
				[
					'label' => __( 'Icon', 'motta-addons' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'icon_background_color',
				[
					'label' => __( 'Background Color', 'motta-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .motta-checklist__item:not(.none) .motta-checklist__icon' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'icon_color',
				[
					'label' => __( 'Color', 'motta-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .motta-checklist__item:not(.none) .motta-checklist__icon' => 'color: {{VALUE}};',
					],
				]
			);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_non_checked_tab',
			[
				'label' => esc_html__( 'Non Check', 'motta-addons' ),
			]
		);

			$this->add_control(
				'non_title_heading',
				[
					'label' => __( 'Title', 'motta-addons' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'non_title_color',
				[
					'label' => __( 'Color', 'motta-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .motta-checklist__item.none .motta-checklist__title' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'non_title_typography',
					'selector' => '{{WRAPPER}} .motta-checklist__item.none .motta-checklist__title',
				]
			);

			$this->add_responsive_control(
				'non_title_spacing',
				[
					'label' => __( 'Spacing', 'motta-addons' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 500,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .motta-checklist__item.none .motta-checklist__title' => 'margin-bottom: {{size}}{{UNIT}} ;',
					],
				]
			);

			$this->add_control(
				'non_description_heading',
				[
					'label' => __( 'Description', 'motta-addons' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'non_description_color',
				[
					'label' => __( 'Color', 'motta-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .motta-checklist__item.none .motta-checklist__description' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'non_description_typography',
					'selector' => '{{WRAPPER}} .motta-checklist__item.none .motta-checklist__description',
				]
			);

			$this->add_control(
				'non_icon',
				[
					'label' => __( 'Icon', 'motta-addons' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'non_icon_background_color',
				[
					'label' => __( 'Background Color', 'motta-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .motta-checklist__item.none .motta-checklist__icon' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'non_icon_color',
				[
					'label' => __( 'Color', 'motta-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .motta-checklist__item.none .motta-checklist__icon' => 'color: {{VALUE}};',
					],
				]
			);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'check_spacing',
			[
				'label' => __( 'Check Spacing', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-checklist__icon' => 'margin-right: {{size}}{{UNIT}} ;',
				],
				'separator' => 'before',
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

		$list_html = [];
		foreach( $settings['list'] as $list ) {
			$status = empty( $list['check'] ) ? 'none' : '';
			$list_html[] = '<div class="motta-checklist__item motta-checklist__item--' . $list['_id'] . ' ' . $status . '">';
				$list_html[] = '<div class="motta-checklist__icon">';
					$list_html[] = Helper::get_svg( 'check' );
				$list_html[] = '</div>';
				$list_html[] = '<div class="motta-checklist__wrapper">';
					$list_html[] = '<div class="motta-checklist__title">' . $list['title'] . '</div>';
					$list_html[] = '<div class="motta-checklist__description">' . $list['description'] . '</div>';
				$list_html[] = '</div>';
			$list_html[] = '</div>';
		}

		echo implode( '', $list_html );
	}
}
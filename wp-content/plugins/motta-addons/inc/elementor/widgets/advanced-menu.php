<?php
namespace Motta\Addons\Elementor\Widgets;

use Elementor\Conditions;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Controls_Stack;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Advanced Menu widget
 */
class Advanced_Menu extends Widget_Base {
/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-advanced-menu';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( '[Motta] Advanced Menu', 'motta-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-nav-menu';
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
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 */
   	public function get_keywords() {
	   return [ 'advanced', 'navigation', 'menu', 'motta-addons', 'addons' ];
   	}

	/**
	 * Register the widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */

	protected function register_controls() {
		$this->content_sections();
		$this->style_sections();
	}

	protected function content_sections() {
		$this->start_controls_section(
			'section_navigation_menu',
			[ 'label' => __( 'Navigation Menu', 'motta-addons' ) ]
		);

		$this->add_control(
			'list_menu',
			[
				'label' => __( 'Select Menu', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => \Motta\Addons\Helper::get_navigation_bar_get_menus(),
			]
		);

		$this->end_controls_section();
	}

	protected function style_sections(){
		$this->start_controls_section(
			'style_navigation_menu',
			[
				'label'     => __( 'Navigation Menu', 'motta-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_menu',
				'selector' => '{{WRAPPER}} .motta-advanced-menu__menu li a',
			]
		);

		$this->add_responsive_control(
			'spacing_right',
			[
				'label' => __( 'Spacing Right', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .motta-advanced-menu ul' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'spacing_menu',
			[
				'label' => __( 'Spacing Menu Items', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .motta-advanced-menu__menu li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .motta-advanced-menu__menu li:last-child' => 'margin-bottom: 0;',
				],
			]
		);

		$this->start_controls_tabs('style_button_tabs');

		$this->start_controls_tab(
			'tab_menu_normal',
			[
				'label' => __( 'Normal', 'motta-addons' ),
			]
		);

		$this->add_control(
			'menu_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-advanced-menu__menu a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_hover',
			[
				'label' => __( 'Hover', 'motta-addons' ),
			]
		);

		$this->add_control(
			'menu_hover_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-advanced-menu__menu a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'menu_hover_background_color',
			[
				'label' => __( 'Background Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-advanced-menu ul.menu > li:hover > a::before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();


	}

	/**
	 * Render icon box widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['list_menu'] ) ) {
			return;
		}

		?>
			<div class="motta-advanced-menu">
				<?php
					wp_nav_menu( array(
						'theme_location' 	=> '__no_such_location',
						'menu'           	=> $settings['list_menu'],
						'container'      	=> 'nav',
						'container_id'   	=> 'motta-advanced-menu',
						'container_class'   => 'motta-advanced-menu',
						'menu_class'     	=> 'menu motta-advanced-menu__menu',
						'walker' 			=>  new \Motta\Addons\Modules\Mega_Menu\Walker()
					) );
				?>
			</div>
		<?php

	}
}
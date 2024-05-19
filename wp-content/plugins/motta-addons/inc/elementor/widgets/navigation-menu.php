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
 * Icon Box widget
 */
class Navigation_Menu extends Widget_Base {
/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-navigation-menu';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( '[Motta] Navigation Menu', 'motta-addons' );
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
	   return [ 'navigation-menu', 'menu', 'motta-addons' ];
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
			'title',
			[
				'label' => __( 'Title', 'motta-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'Enter your title', 'motta-addons' ),
				'default' => __( 'Add Your Text Here', 'motta-addons' ),
			]
		);

		$this->add_control(
			'menu_type',
			[
				'label' => __( 'Type', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'wordpress',
				'options' => [
					'wordpress'  => esc_html__( 'WordPress Menu', 'motta-addons' ),
					'custom' => esc_html__( 'Custom', 'motta-addons' ),
				],
			]
		);

		$this->add_control(
			'list_menu',
			[
				'label' => __( 'Select Menu', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => \Motta\Addons\Helper::get_navigation_bar_get_menus(),
				'condition' => [
					'menu_type' => 'wordpress',
				],
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'title_menu',
			[
				'label'   => esc_html__( 'Text', 'motta-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Menu item', 'motta-addons' ),
			]
		);

		$repeater->add_control(
			'link_menu', [
				'label'         => esc_html__( 'Link', 'motta-addons' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'motta-addons' ),
				'show_external' => true,
				'default'       => [
					'url'         => '#',
					'is_external' => false,
					'nofollow'    => false,
				],
			]
		);

		$this->add_control(
			'menu_items',
			[
				'label'         => esc_html__( 'Menu Items', 'motta-addons' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [
					[
						'title_menu' => esc_html__( 'Menu item 1', 'motta-addons' ),
						'link_menu' => '#',
					],
					[
						'title_menu' => esc_html__( 'Menu item 2', 'motta-addons' ),
						'link_menu' => '#',
					],
					[
						'title_menu' => esc_html__( 'Menu item 3', 'motta-addons' ),
						'link_menu' => '#',
					],
				],
				'prevent_empty' => false,
				'condition' => [
					'menu_type' => 'custom',
				],
				'title_field'   => '{{{ title_menu }}}',
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

		$this->add_control(
			'menu_stype',
			[
				'label' => __( 'Style', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'column',
				'options' => [
					'column'  => esc_html__( 'Vertical', 'motta-addons' ),
					'row' => esc_html__( 'Horizontal', 'motta-addons' ),
				],
				'selectors' => [
					'{{WRAPPER}} .motta-navigation-menu ul' => 'flex-direction: {{VALUE}};',
				],

				'prefix_class' => 'motta-navigation-menu--',
			]
		);

		$this->add_responsive_control(
			'toggle_menu',
			[
				'label'        => __( 'Toggle Menu', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => __( 'Off', 'motta-addons' ),
				'label_on'     => __( 'On', 'motta-addons' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'toggle_status',
			[
				'label'        => __( 'Toggle Status', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => __( 'Off', 'motta-addons' ),
				'label_on'     => __( 'On', 'motta-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'style_align',
			[
				'label' => __( 'Alignment', 'motta-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => __( 'Left', 'motta-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'motta-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => __( 'Right', 'motta-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => 'flex-start',
				'tablet_default' => 'flex-start',
				'mobile_default' => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .motta-navigation-menu ul' => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'menu_stype' => 'row',
				],
			]
		);

		$this->add_control(
			'style_title',
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
					'{{WRAPPER}} .motta-navigation-menu__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .motta-navigation-menu__title',
			]
		);

		$this->add_responsive_control(
			'spacing',
			[
				'label' => __( 'Spacing', 'motta-addons' ),
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
					'{{WRAPPER}} .motta-navigation-menu' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'style_menu',
			[
				'label' => __( 'Menu', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_menu',
				'selector' => '{{WRAPPER}} .motta-navigation-menu__menu li a',
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
					'{{WRAPPER}} .motta-navigation-menu ul' => 'padding-left: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}}.motta-navigation-menu--row .motta-navigation-menu__menu li' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.motta-navigation-menu--row .motta-navigation-menu ul' => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.motta-navigation-menu--column .motta-navigation-menu__menu li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.motta-navigation-menu--column .motta-navigation-menu__menu li:last-child' => 'margin-bottom: 0;',
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
					'{{WRAPPER}} .motta-navigation-menu__menu a' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .motta-navigation-menu__menu a:hover' => 'color: {{VALUE}};',
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

		$this->add_render_attribute( 'container', 'class', 'motta-navigation-menu__container' );
		$this->add_render_attribute( 'title', 'class', 'motta-navigation-menu__title' );
		$this->add_inline_editing_attributes( 'title' );
		$title = $settings['title'];

		$menu_items = $settings['menu_items'];

		?>
			<div class="motta-navigation-menu-element motta-navigation-menu__container--group">
				<?php
				if ( ! empty( $settings['title'] ) ) {
					printf( '<div %1$s>%2$s</div>', $this->get_render_attribute_string( 'title' ), $title );
				}
				?>

				<?php
					if ( $settings['menu_type'] == "wordpress" ) {
						if ( empty( $settings['list_menu'] ) ) {
							return;
						}

						wp_nav_menu( array(
							'theme_location' 	=> '__no_such_location',
							'menu'           	=> $settings['list_menu'],
							'container'      	=> 'nav',
							'container_class'   => 'motta-navigation-menu',
							'menu_class'     	=> 'nav-menu motta-navigation-menu__menu',
							'depth'          	=> 1,
						) );
					} else {
						$menu = '<nav class="motta-navigation-menu">';
						$menu .= '<ul class="nav-menu motta-navigation-menu__menu">';
						if ( ! empty ( $menu_items ) ) {
							foreach ( $menu_items as $item ) {
								if ( !empty( $item['title_menu'] ) ) {
									$menu .= '<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home menu-item-' . $item['_id'] . '">';
										$menu .= '<a href="' . $item['link_menu']['url'] . '">' . $item['title_menu'] . '</a>';
									$menu .= '</li>';
								}
							}
						}
							$menu .= '</ul>';
						$menu .= '</nav>';
						echo $menu;
					}
				?>
			</div>
		<?php

	}
}
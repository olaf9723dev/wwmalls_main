<?php
namespace Motta\Addons\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Navigation Bar widget
 */
class Navigation_Bar extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-navigation-bar';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( '[Motta] Navigation Bar', 'motta-addons' );
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
		return [ 'navigation-bar', 'navigation', 'motta-addons' ];
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

	// Tab Content
	protected function section_content() {
		$this->section_content_settings_controls();
	}

	// Tab Style
	protected function section_style() {
		$this->section_content_style_controls();
	}

	protected function section_content_settings_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'motta-addons' ),
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
			'active',
			[
				'label' => esc_html__( 'Active', 'motta-addons' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'items',
			[
				'label' => esc_html__( 'Items', 'motta-addons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default' => [
					[
						'title'   => esc_html__( 'Item #1', 'motta-addons' ),
						'link'    => ['url' => '#'],
					],
					[
						'title'   => esc_html__( 'Item #2', 'motta-addons' ),
						'link'    => ['url' => '#'],
					],
					[
						'title'   => esc_html__( 'Item #3', 'motta-addons' ),
						'link'    => ['url' => '#'],
					],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function section_content_style_controls() {

	}

	/**
	 * Render icon box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', [ 'motta-navigation-bar' ] );
		$content_html = [];

		foreach( $settings['items'] as $items ) {

			if ( ! empty( $items['link']['url'] ) ) {
				$title = '<a href=' . $items['link']['url'] . '>' . $items['title'] . '</a>';
			}else{
				$title = $items['title'];
			}

			$classes = $items['active'] == 'yes' ? ' active' : '';

			$content_html[] = ! empty( $items['title'] ) ? '<div class="motta-navigation-bar__title motta-navigation-bar__title-item--' . $items['_id'] . $classes . '">' . $title . ' </div>' : '';

		}

		echo sprintf( '<div %s>%s</div>',
			$this->get_render_attribute_string( 'wrapper' ),
			implode( '', $content_html ),
			);
	}
}
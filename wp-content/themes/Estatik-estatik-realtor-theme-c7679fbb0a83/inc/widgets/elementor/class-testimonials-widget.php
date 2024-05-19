<?php

use Elementor\Controls_Manager;

/**
 * Class Es_Elementor_Search_Form_Widget.
 */
class Elementor_Es_Testimonials_Widget extends \Elementor\Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'es-testimonials-widget';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return _x( 'Estatik Testimonials', 'widget name', 'ert' );
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        $repeater = new \Elementor\Repeater();

        $repeater->add_control( 'name', array(
            'type' => Controls_Manager::TEXT,
            'label' => _x( 'Name', 'agent name', 'ert' ),
        ) );

        $repeater->add_control( 'sub_name', array(
            'type' => Controls_Manager::TEXT,
            'label' => _x( 'Sub-Name', 'agent sub name', 'ert' ),
        ) );

        $repeater->add_control( 'link_name', array(
            'type' => Controls_Manager::SWITCHER,
            'label' => __( 'Link name', 'ert' ),
        ) );

        $repeater->add_control( 'image', array(
            'type' => Controls_Manager::MEDIA,
            'label' => __( 'Image', 'ert' ),
        ) );

        $repeater->add_control( 'link_image', array(
            'type' => Controls_Manager::SWITCHER,
            'label' => __( 'Link image', 'ert' ),
        ) );

        $repeater->add_control( 'text', array(
            'type' => Controls_Manager::TEXTAREA,
            'label' => __( 'Text', 'ert' ),
        ) );

        $repeater->add_control( 'url', array(
            'type' => Controls_Manager::URL,
            'label' => __( 'URL', 'ert' ),
        ) );

        $this->add_control(
            'items',
            [
                'label' => __( 'Items', 'es' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @param array $instance
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
        $settings = $this->get_settings();
        if ( ! empty( $settings['items'] ) ) {
            get_template_part( 'template-parts/widgets/testimonials', null, array(
                'testimonials' => $settings['items'],
            ) );
        }
	}

    /**
     * @return array|string[]
     */
    public function get_categories() {
        return array( 'estatik-category' );
    }
}
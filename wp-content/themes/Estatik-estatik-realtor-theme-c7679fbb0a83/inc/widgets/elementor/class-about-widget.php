<?php

use Elementor\Controls_Manager;

/**
 * Class Es_Elementor_Search_Form_Widget.
 */
class Elementor_About_Block_Widget extends \Elementor\Widget_Base {

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
        return 'es-about-block-widget';
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
        return _x( 'Estatik About Block', 'widget name', 'ert' );
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

        $this->add_control( 'title', array(
            'type' => Controls_Manager::TEXT,
            'label' => _x( 'Block title', 'agent name', 'ert' ),
        ) );

        $this->add_control( 'image', array(
            'type' => Controls_Manager::MEDIA,
            'label' => _x( 'Content image', 'agent name', 'ert' ),
        ) );

        $this->add_control( 'content_title', array(
            'type' => Controls_Manager::TEXT,
            'label' => _x( 'Content title', 'agent name', 'ert' ),
        ) );

        $this->add_control( 'content_sub_title', array(
            'type' => Controls_Manager::TEXT,
            'label' => _x( 'Content Sub Title', 'agent name', 'ert' ),
        ) );

        $this->add_control( 'content', array(
            'type' => Controls_Manager::TEXTAREA,
            'label' => _x( 'Text', 'agent name', 'ert' ),
        ) );

        $this->add_control( 'link_name', array(
            'type' => Controls_Manager::TEXT,
            'label' => _x( 'Link name', 'agent name', 'ert' ),
        ) );

        $this->add_control( 'link', array(
            'type' => Controls_Manager::URL,
            'label' => _x( 'Link', 'agent name', 'ert' ),
        ) );

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
        $instance = $this->get_settings();

        echo "<div class='widget_ert-about-widget so-panel'>";

        if ( ! empty( $instance['title'] ) ) {
            echo "<h3  class='widget-title'>{$instance['title']}</h3>";
        }

        echo "<div class='triangle'></div>";

        echo "<div class='container'><div class='row'><div class='col-md-6 order-2 order-sm-2 order-md-1'>";

        if ( ! empty( $instance['content_title'] ) ) {
            echo "<h4>" . $instance['content_title'] . '</h4>';
        }

        if ( ! empty( $instance['content_sub_title'] ) ) {
            echo "<h5>" . $instance['content_sub_title'] . "</h5>";
        }

        if ( ! empty( $instance['content'] ) ) {
            echo "<div class='ert-about__content'>" . $instance['content'] . "</div>";
        }

        if ( ! empty( $instance['link'] ) && ! empty( $instance['link_name'] ) ) {
            echo "<a href='" . esc_url( $instance['link']['url'] ) . "' class='btn btn-light'>" . $instance['link_name'] . "</a>";
        }

        echo "</div><div class='col-md-6 ert-about__image order-1 order-sm-1 order-md-2'>";

        if ( ! empty( $instance['image'] ) ) {
            echo "<div class='ert-about__image--inner'>";
            echo "<img src='{$instance['image']['url']}' alt='" . esc_attr( $instance['content_title'] ) . "'/>";
            echo "</div>";
        }

        echo "</div></div></div>";
    }

    /**
     * @return array|string[]
     */
    public function get_categories() {
        return array( 'estatik-category' );
    }
}
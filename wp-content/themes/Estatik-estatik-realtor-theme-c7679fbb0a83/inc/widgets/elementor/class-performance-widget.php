<?php

use Elementor\Controls_Manager;

/**
 * Class Es_Elementor_Search_Form_Widget.
 */
class Elementor_Es_Performance_Blocks_Widget extends \Elementor\Widget_Base {

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
        return 'es-performance-widget';
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
        return _x( 'Estatik Performance Blocks', 'widget name', 'ert' );
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
            'label' => __( 'Block title', 'ert' ),
        ) );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control( 'title', array(
            'type' => Controls_Manager::TEXT,
            'label' => __( 'Name', 'ert' ),
        ) );

        $repeater->add_control( 'description', array(
            'type' => Controls_Manager::TEXTAREA,
            'label' => __( 'Text', 'ert' ),
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
        $settings = $this->get_settings(); ?>

        <div class="so-panel widget_ert-perfomance-widget">
            <?php if ( ! empty( $settings['title'] ) ) {
                echo "<h3  class='widget-title'>{$settings['title']}</h3>";
            } ?>

            <div class="container">
                <div class="row">
                    <?php foreach ( $settings['items'] as $block ) : ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg ert-perfomance__item">
                            <?php if ( ! empty( $block['title'] ) ) : ?>
                                <h4><?php echo $block['title']; ?></h4>
                            <?php endif; ?>
                            <?php if ( ! empty( $block['description'] ) ) : ?>
                                <p><?php echo $block['description']; ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * @return array|string[]
     */
    public function get_categories() {
        return array( 'estatik-category' );
    }
}
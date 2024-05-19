<?php

use Elementor\Controls_Manager;

/**
 * Class Es_Elementor_Search_Form_Widget.
 */
class Elementor_Es_Property_Types_Blocks_Widget extends \Elementor\Widget_Base {

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
        return 'es-property-types-widget';
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
        return _x( 'Estatik Property Types Blocks', 'widget name', 'ert' );
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
            'label' => _x( 'Block title', 'agent name', 'ert' ),
        ) );

        $repeater->add_control( 'link', array(
            'type' => Controls_Manager::URL,
            'label' => __( 'Link', 'ert' ),
        ) );

        $repeater->add_control( 'image', array(
            'type' => Controls_Manager::MEDIA,
            'label' => __( 'Image', 'ert' ),
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
        $settings = $this->get_settings(); $i = 0; ?>
        <div class="widget_ert-property-types-widget">
        <div class="row">
            <?php foreach ( $settings['items'] as $block ) : $i++; ?>
            <div class="col-md-6 ert-type__block <?php echo $i == 1 || $i == 4 ? 'ert-type__block--dark' : ''; ?>">

                <?php if ( ! empty( $block['link']['url'] ) ) : ?>
                    <a href="<?php echo esc_url( $block['link']['url'] ); ?>">
                <?php endif; ?>

                <?php if ( ! empty( $block['image']['url'] ) ) : ?>
                <div class="ert-type__block--inner" style="background: url(<?php echo $block['image']['url']; ?>); background-repeat:no-repeat; background-size: cover;">
                <?php else: ?>
                <div class="ert-type__block">
                <?php endif; ?>

                <?php if ( ! empty( $block['name'] ) ) : ?>
                    <h4><span><?php echo $block['name']; ?></span></h4>
                <?php endif; ?>

                </div>

                <?php if ( ! empty( $block['link'] ) ) : ?>
                    </a>
                <?php endif; ?>

                </div>
                <?php if ( $i >=4 ) $i = 0; ?>
            <?php endforeach; ?>
        </div></div><?php
    }

    /**
     * @return array|string[]
     */
    public function get_categories() {
        return array( 'estatik-category' );
    }
}
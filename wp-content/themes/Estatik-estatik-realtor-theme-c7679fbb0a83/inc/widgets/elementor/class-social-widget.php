<?php

use Elementor\Controls_Manager;

/**
 * Class Es_Elementor_Search_Form_Widget.
 */
class Elementor_Es_Social_Widget extends \Elementor\Widget_Base {

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
        return 'es-social-widget';
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
        return _x( 'Estatik Social Icons', 'widget name', 'ert' );
    }

    public static function get_social_networks() {
        return array(
            'facebook' => __( 'Facebook', 'ert' ),
            'twitter' => __( 'Twitter', 'ert' ),
            'instagram' => __( 'Instagram', 'ert' ),
            'linkedin' => __( 'Linkedin', 'ert' ),
            'youtube' => __( 'Youtube', 'ert' ),
        );
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
       $soc = static::get_social_networks();

        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'plugin-name' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        foreach ( $soc as $key => $name ) {
            $this->add_control( $key, array(
                'type' => Controls_Manager::URL,
                'label' => $name,
            ) );
        }

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
        $data = array();
        if ( ! empty( $settings ) ) {
            foreach ( static::get_social_networks() as $key => $name ) {
                if ( ! empty( $settings[ $key ] ) ) {
                    $data[ $key ] = $settings[ $key ];
                }
            }
        }
        include locate_template( 'template-parts/blocks/social-links.php' );
    }

    /**
     * @return array|string[]
     */
    public function get_categories() {
        return array( 'estatik-category' );
    }
}
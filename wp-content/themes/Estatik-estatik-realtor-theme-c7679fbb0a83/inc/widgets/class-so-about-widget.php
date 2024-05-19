<?php

/**
 * Class Ert_Testimonials_Widget.
 */
class Ert_About_Widget extends SiteOrigin_Widget {

	/**
	 * Ert_Testimonials_Widget constructor.
	 */
	function __construct() {
		parent::__construct(
			'ert-about-widget',
			__( 'Estatik About Block', 'ert' ),
			array(
				'description' => __( 'Uses for Agent Info.', 'ert'),
				'has_preview' => false,
			),
			array(

			),
			false,
			plugin_dir_path( __FILE__ )
		);
	}

	/**
	 * Return widget form settings.
	 *
	 * @return array
	 */
	function get_widget_form() {

		return array(
			'title' => array(
				'type' => 'text',
				'label' => __( 'Block Title', 'so-widgets-bundle' ),
			),
			'image' => array(
				'type' => 'media',
				'label' => __( 'Content Image' )
			),
            'content_title' => array(
                'type' => 'text',
                'label' => __( 'Content Title', 'ert' )
            ),
			'content_sub_title' => array(
				'type' => 'text',
				'label' => __( 'Content Sub Title', 'ert' )
			),
			'content' => array(
				'type' => 'tinymce',
				'label' => __( 'Text', 'ert' ),
			),
            'link_name' => array(
                'type' => 'text',
                'label' => __( 'Link Name', 'ert' ),
                'default' => __( 'About Company', 'ert' )
            ),
            'link' => array(
                'type' => 'link',
                'label' => __( 'More Link', 'ert' )
            ),
		);
	}

	/**
	 * @param $instance
	 * @param $args
	 * @param $template_vars
	 * @param $css_name
	 *
	 * @return string
	 */
	public function get_html_content( $instance, $args, $template_vars, $css_name ) {

		ob_start();

		$args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
		    echo $args['before_title'] . $instance['title'] . $args['after_title'];
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
		    echo "<a href='" . sow_esc_url( $instance['link'] ) . "' class='btn btn-light'>" . $instance['link_name'] . "</a>";
        }

        echo "</div><div class='col-md-6 ert-about__image order-1 order-sm-1 order-md-2'>";

        if ( ! empty( $instance['image'] ) ) {
        	echo "<div class='ert-about__image--inner'>";
            echo wp_get_attachment_image( $instance['image'], 'full' );
            echo "</div>";
        }

        echo "</div></div>";

		echo $args['after_widget'];

		return ob_get_clean();
	}
}

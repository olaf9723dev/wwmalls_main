<?php

/**
 * Class Ept_Title_Widget
 */
class Ert_Title_Widget extends SiteOrigin_Widget {

	/**
	 * Ert_Testimonials_Widget constructor.
	 */
	function __construct() {
		parent::__construct(
			'ert-title-widget',
			__( 'Title', 'ert' ),
			array(
				'description' => __( 'Display Styled Title.', 'ert'),
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
				'label' => __( 'Title', 'so-widgets-bundle' ),
			),

			'color' => array(
				'type' => 'color',
				'label' => __( 'Color', 'so-widgets-bundle' ),
				'default' => '#000'
			),
		);
	}

	/**
	 * Return content of the title widget.
	 *
	 * @param $instance
	 * @param $args
	 * @param $template_vars
	 * @param $css_name
	 *
	 * @return string
	 */
	public function get_html_content( $instance, $args, $template_vars, $css_name ) {

		$content = '';

		if ( ! empty( $instance['title'] ) ) {

			$content = "<style>
						#{$args['widget_id']} .widget-title {color: " . $instance['color'] . ";}
						#{$args['widget_id']} .widget-title:before, #{$args['widget_id']} .widget-title:after {border-top: 1px solid " . $instance['color'] . ";}
						</style>";

			$content .= sprintf( "<div id='%s'>", $args['widget_id'] );
			$content .= $args['before_widget'];
			$content .= $args['before_title'] . $instance['title'] . $args['after_title'];
			$content .= $args['after_widget'];
			$content .= "</div>";
		}

		return $content;
	}
}

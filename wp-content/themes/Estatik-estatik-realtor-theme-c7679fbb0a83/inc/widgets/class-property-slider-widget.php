<?php

/**
 * Class Ert_Property_Carousel_Widget.
 */
class Ert_Property_Slider_Widget extends Estatik_Framework_Property_Query_Widget {

	/**
	 * Ert_Testimonials_Widget constructor.
	 */
	function __construct() {
		parent::__construct(
			'ert-property-carousel-widget',
			__( 'Estatik Property Carousel', 'ert' ),
			array(
				'description' => __( 'Display Properties Carousel.', 'ert'),
				'has_preview' => false,
			),
			array(

			),
			false,
			plugin_dir_path( __FILE__ )
		);
	}

	/**
	 * Return layouts of this widget.
	 *
	 * @return array
	 */
	public static function get_layouts() {
		return apply_filters( 'es_get_property_slideshow_widget_layouts', array(
			'horizontal' => __( 'Horizontal', 'ert' ),
			'vertical'   => __( 'Vertical', 'ert' )
		) );
	}

	/**
	 * Return widget form settings.
	 *
	 * @return array
	 */
	function get_widget_form() {

		return array_merge( array(
			'title' => array(
				'type' => 'text',
				'label' => __( 'Title', 'so-widgets-bundle' ),
			),
			'dots' => array(
				'type' => 'checkbox',
				'label' => __( 'Show Dots?', 'ert' ),
				'default' => true
			),
			'slidesToShow' => array(
				'label' => __( 'Slides To Show', 'ert' ),
				'type' => 'slider',
				'default' => 3,
				'min' => 1,
				'max' => 6,
				'integer' => true
			),
			'slider_layout' => array(
				'label' => __( 'Carousel Layout', 'ert' ),
				'type' => 'select',
				'options' => static::get_layouts(),
			),
			'layout' => array(
				'label' => __( 'Carousel layout', 'ert' ),
				'type' => 'select',
				'options' => array(
					'v1' => __( 'v1', 'ert' ),
					'v2' => __( 'v2', 'ert' ),
				),
				'default' => 'v1'
			),
		), parent::get_widget_form() );
	}

	/**
	 * Display carousel.
	 *
	 * @param $instance
	 * @param $args
	 * @param $template_vars
	 * @param $css_name
	 *
	 * @return string
	 */
	public function get_html_content( $instance, $args, $template_vars, $css_name ) {

		ob_start();

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . $instance['title'] . $args['after_title'];
		}

		if ( ! empty( $instance['property_query']['taxonomies'] ) ) {
			$instance['property_query'] = array_merge( $instance['property_query'], $instance['property_query']['taxonomies'] );
			unset( $instance['property_query']['taxonomies'] );
		}

		if ( ! empty( $instance['property_query'] ) ) {
			$instance = array_merge( $instance, $instance['property_query'] );
			unset( $instance['property_query'] );
		}

		$view = $instance['layout'];
		$instance['view'] = $view;
		$instance['layout'] = $instance['slider_layout'];
		$instance['slides_to_show'] = $instance['slidesToShow'];

		echo ert_property_slider( $instance );

		return ob_get_clean();
	}
}

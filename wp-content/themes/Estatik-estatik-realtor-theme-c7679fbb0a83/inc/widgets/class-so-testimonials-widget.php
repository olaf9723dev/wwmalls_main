<?php

/**
 * Class Ert_Testimonials_Widget.
 */
class Ert_Testimonials_Widget extends SiteOrigin_Widget {

	/**
	 * Ert_Testimonials_Widget constructor.
	 */
	function __construct() {
		parent::__construct(
			'ert-testimonials',
			__( 'Estatik Testimonials', 'ert' ),
			array(
				'description' => __( 'Displays testimonials in a slider widget.', 'ert'),
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
			'testimonials' => array(
				'type' => 'repeater',
				'label' => __( 'Testimonials', 'so-widgets-bundle' ),
				'item_name'  => __( 'Testimonial', 'so-widgets-bundle' ),
				'item_label' => array(
					'selector'     => "[id*='testimonials-name']",
					'update_event' => 'change',
					'value_method' => 'val'
				),
				'fields' => array(
					'name' => array(
						'type' => 'text',
						'label' => __( 'Name', 'so-widgets-bundle' ),
						'description' => __( 'The author of the testimonial', 'so-widgets-bundle' ),
					),

					'sub_name' => array(
						'type' => 'text',
						'label' => __( 'Sub-name', 'so-widgets-bundle' ),
						'description' => __( 'Author vocation', 'so-widgets-bundle' ),
					),

					'link_name' => array(
						'type' => 'checkbox',
						'label' => __( 'Link name', 'so-widgets-bundle' ),
					),

					'image' => array(
						'type' => 'media',
						'label' => __( 'Image', 'so-widgets-bundle' ),
					),

					'link_image' => array(
						'type' => 'checkbox',
						'label' => __( 'Link image', 'so-widgets-bundle' ),
						'required' => true,
					),

					'text' => array(
						'type' => 'tinymce',
						'label' => __( 'Text', 'so-widgets-bundle' ),
						'description' => __( 'What your customer had to say', 'so-widgets-bundle' ),
					),

					'url' => array(
						'type' => 'text',
						'label' => __( 'URL', 'so-widgets-bundle' ),
					),

					'new_window' => array(
						'type' => 'checkbox',
						'label' => __( 'Open In New Window', 'so-widgets-bundle' ),
					),
				)
			),
		);
	}

	/**
	 * Return template variables.
	 *
	 * @param $instance
	 * @param $args
	 *
	 * @return array
	 */
	function get_template_variables( $instance, $args ) {

		return wp_parse_args( $instance, array(
			'testimonials' => array(),
		) );
	}

	/**
	 * Return testimonials user image.
	 *
	 * @param $image_id
	 * @param string $size
	 *
	 * @return string
	 */
	function testimonial_user_image( $image_id, $size = 'medium' ) {

		if ( ! empty( $image_id ) ) {
			return wp_get_attachment_image( $image_id, $size );
		}

		return false;
	}

	/**
	 * Return testimonials user image.
	 *
	 * @param $image_id
	 * @param string $size
	 *
	 * @return string
	 */
	function testimonial_user_image_url( $image_id, $size = 'medium' ) {

		if ( ! empty( $image_id ) ) {
			return wp_get_attachment_image_url( $image_id, $size );
		}

		return false;
	}
}

/**
 * Return testimonials template path.
 *
 * @return string
 */
function ert_testimonials_widget_template_file_path() {
	return locate_template( '/template-parts/widgets/testimonials.php' );
}

add_filter( 'siteorigin_widgets_template_file_ert-testimonials', 'ert_testimonials_widget_template_file_path', 10 );
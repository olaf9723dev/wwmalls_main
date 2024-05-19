<?php

/**
 * Class Ert_Testimonials_Widget.
 */
class Ert_Social_Widget extends SiteOrigin_Widget {

	/**
	 * Ert_Testimonials_Widget constructor.
	 */
	function __construct() {
		parent::__construct(
			'ert-social-widget',
			__( 'Estatik Social', 'ert' ),
			array(
				'description' => __( 'Display social icons.', 'ert'),
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
			'icons' => array(
				'type' => 'repeater',
				'label' => __( 'Icons', 'so-widgets-bundle' ),
				'item_name'  => __( 'Icon', 'so-widgets-bundle' ),
				'item_label' => array(
					'selector'     => "[id*='type-name']",
					'update_event' => 'change',
					'value_method' => 'val'
				),
				'fields' => array(
					'icon_class' => array(
						'type' => 'select',
						'label' => __( 'Social Link', 'so-widgets-bundle' ),
						'options' => array(
							'facebook' => __( 'Facebook', 'ert' ),
							'twitter' => __( 'Twitter', 'ert' ),
							'instagram' => __( 'Instagram', 'ert' ),
							'linkedin' => __( 'Linkedin', 'ert' ),
							'youtube' => __( 'Youtube', 'ert' ),
						)
					),
					'link' => array(
						'type' => 'link',
						'label' => __( 'Link', 'so-widgets-bundle' ),
					),
				)
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

		if ( ! empty( $instance['icons'] ) ) {

			foreach ( $instance['icons'] as $key => $value ) {
				$data[ $value['icon_class'] ] = $value['link'];
			}

			if ( ! empty( $data ) ) {

				ob_start();

				echo $args['before_widget'];

				if ( ! empty( $instance['title'] ) ) {
					echo $args['before_title'] . $instance['title'] . $args['after_title'];
				}

				include locate_template( 'template-parts/blocks/social-links.php' );

				echo $args['after_widget'];

				return ob_get_clean();
			}
		}
	}
}

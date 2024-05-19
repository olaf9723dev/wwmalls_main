<?php

/**
 * Class Ert_Testimonials_Widget.
 */
class Ert_Taxonomy_Widget extends SiteOrigin_Widget {

	/**
	 * Ert_Testimonials_Widget constructor.
	 */
	function __construct() {
		parent::__construct(
			'ert-taxonomy-widget',
			__( 'Estatik Taxonomies', 'ert' ),
			array(
				'classname' => 'es-widget',
				'description' => __( 'Display estatik taxonomy terms.', 'ert'),
				'has_preview' => false,
			),
			array(),
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

		$taxonomies = apply_filters( 'ert_taxonomy_widget_taxonomies_list', array(
			'es_feature' => __( 'Features', 'ert' ),
			'es_amenities' => __( 'Amenities', 'ert' ),
			'es_labels' => __( 'Labels', 'ert' ),
			'es_status' => __( 'Statuses', 'ert' ),
			'es_type' => __( 'Types', 'ert' ),
			'es_rent_period' => __( 'Rent Periods', 'ert' ),
			'es_category' => __( 'Categories', 'ert' ),
		) );

		return array(
			'title' => array(
				'type' => 'text',
				'label' => __( 'Title', 'so-widgets-bundle' ),
			),
			'taxonomy' => array(
				'type' => 'select',
				'options' => $taxonomies,
				'label' => __( 'Taxonomy to show', 'ert' )
			)
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

		if ( ! empty( $instance['taxonomy'] ) && $terms = get_terms( array( 'taxonomy' => $instance['taxonomy'], 'hide_empty' => true ) ) ) {

			if ( ! is_wp_error( $terms ) ) {

				ob_start();

				if ( ! empty( $instance['title'] ) ) {
					echo $args['before_title'] . $instance['title'] . $args['after_title'];
				}

				foreach ( $terms as $term ) : ?>
					<a href="<?php echo get_term_link( $term, $instance['taxonomy'] ); ?>" class="es-term">
						<span class="es-term__name"><?php echo $term->name; ?></span>
						<span class="es-term__count">(<?php echo $term->count; ?>)</span>
					</a>
				<?php endforeach;

				return ob_get_clean();
			}
		}
	}
}

<?php

/**
 * Class Ert_Property_Carousel_Widget.
 */
class Ert_Property_Item_Widget extends Estatik_Framework_Property_Query_Widget {

	/**
	 * Ert_Testimonials_Widget constructor.
	 */
	function __construct() {
		parent::__construct(
			'ert-property-item-widget',
			__( 'Estatik Property Item', 'ert' ),
			array(
				'description' => __( 'Display Property Item.', 'ert'),
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

		$form = array_merge( array(
			'title' => array(
				'type' => 'text',
				'label' => __( 'Title', 'so-widgets-bundle' ),
			),
		), parent::get_widget_form() );

		unset( $form['property_query']['fields']['limit'] );

		return $form;
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
			'title' => '',
		) );
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

		if ( ! empty( $instance['property_query']['taxonomies'] ) ) {
			$instance['property_query'] = array_merge( $instance['property_query'], $instance['property_query']['taxonomies'] );

            if ( ! is_estatik4() ) unset( $instance['property_query']['taxonomies'] );
		}

		if ( ! empty( $instance['property_query'] ) ) {
			$instance = array_merge( $instance, $instance['property_query'] );
			unset( $instance['property_query'] );
		}

        if ( is_estatik4() ) {
            $instance = ert4_prepare_wp_query_widget_args( $instance );

            $query_args = es_get_properties_query_args( array(
                'fields' => $instance,
                'query' => array(
                    'posts_per_page' => 1,
                )
            ) );
        } else {
            $listings_shortcode = new Es_My_Listing_Shortcode();
            $instance['limit'] = 1;
            $instance = $listings_shortcode->merge_shortcode_atts( $instance );
            $query_args = $listings_shortcode->build_query_args( $instance );
        }

		$query = new WP_Query( $query_args );

		if ( $query->have_posts() ) {

			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . $instance['title'] . $args['after_title'];
			} ?>

			<div class="ert-listing ert-layout-col">
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <?php if ( is_estatik4() ) : ?>
                        <?php include es_locate_template( 'front/property/content-archive.php' ); ?>
                    <?php else : ?>
                        <?php include es_locate_template( 'content-archive.php' ); ?>
                    <?php endif; ?>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>

		<?php }

		return ob_get_clean();
	}
}

<?php

/**
 * Class Estatik_Framework_Property_Query_Widget.
 */
abstract class Estatik_Framework_Property_Query_Widget extends SiteOrigin_Widget {

	/**
	 * Return widget form settings.
	 *
	 * @return array
	 */
	function get_widget_form() {
		$fields =  array(
			'property_query' => array(
				'type' => 'section',
				'label' => __( 'Properties Query Filter', 'estatik-framework' ),
				'fields' => array(

					'prop_id' => array(
						'label' => __( 'Property IDs', 'estatik-framework' ),
						'type' => 'text',
						'description' => __( 'Comma separated listings IDs', 'estatik-framework' ),
					),

					'price_min' => array(
						'type' => 'number',
						'label' => __( 'Min Price', 'estatik-framework' ),
					),

					'price_max' => array(
						'type' => 'number',
						'label' => __( 'Max Price', 'estatik-framework' ),
					),

					'sort' => array(
						'type' => 'select',
						'label' => __( 'Sort By', 'estatik-framework' ),
						'prompt' => __( 'Choose Property Sorting', 'estati-framework' ),
						'options' => apply_filters( 'es_get_sorting_dropdown_values', array(
                            'recent'          => __( 'Most recent',   'es-plugin' ),
                            'highest_price'   => __( 'Highest price', 'es-plugin' ),
                            'lowest_price'    => __( 'Lowest price',  'es-plugin' ),
                            'featured'        => __( 'Featured',  'es-plugin' ),
                        ) )
					),

					'address' => array(
						'type' => 'text',
						'label' => __( 'Search Address String', 'estatik-framework' ),
					),
					'limit' => array(
						'type' => 'number',
						'min' => 1,
						'label' => __( 'Limit', 'estatik-framework' ),
					)
				),
			)
		);

		$fields['property_query']['fields']['taxonomies'] = array(
			'type' => 'section',
			'label' => __( 'Property Taxonomies', 'estatik-framework' ),
		);

		$taxonomies = class_exists( 'Es_Taxonomy' ) ? Es_Taxonomy::get_taxonomies_list() : array();
        $taxonomies = function_exists( 'es_get_taxonomies_list' ) ? es_get_taxonomies_list() : $taxonomies;

		if ( ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy_name ) {
                $t_name = $taxonomy_name;

                if ( class_exists( 'Es_Taxonomy' ) ) {
                    $taxonomy = new Es_Taxonomy( $taxonomy_name );
                    $t_name = $taxonomy->get_name();
                } else if ( function_exists( 'es_get_taxonomies_list' ) ) {
                    $taxonomy = get_taxonomy( $taxonomy_name );
                    $t_name = $taxonomy ? $taxonomy->label : $taxonomy_name;
                }

                if ( is_estatik4() ) {
                    $terms = get_terms( array( 'taxonomy' => $taxonomy_name, 'fields' => 'id=>name', 'hide_empty' => false ) );

                    if ( $terms && ! is_wp_error( $terms ) ) {
                        $fields['property_query']['fields']['taxonomies']['fields'][ $taxonomy_name ] = array(
                            'label' => $t_name,
                            'type' => 'select',
                            'options' => $terms,
                            'multiple' => true,
                        );
                    }
                } else {
                    $terms = get_terms( array( 'taxonomy' => $taxonomy_name, 'fields' => 'names', 'hide_empty' => false ) );

                    if ( $terms && ! is_wp_error( $terms ) ) {
                        $fields['property_query']['fields']['taxonomies']['fields'][ str_replace( 'es_', '', $taxonomy_name ) ] = array(
                            'label' => $t_name,
                            'type' => 'select',
                            'options' => array_combine( $terms, $terms ),
                            'multiple' => true,
                        );
                    }
                }
			}
		}

        if ( is_estatik4() ) {
            $fields['property_query']['fields']['sort']['options'] = ests_selected( 'properties_sorting_options' );
        }

		return $fields;
	}
}

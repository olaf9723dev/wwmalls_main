<?php
/**
 * Widget Image
 */

namespace Motta\Addons\Modules\Mega_Menu\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Taxonomy List widget class
 */
class Taxonomy_List extends Widget_Base {

	/**
	 * Set the widget name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'taxonomy-list';
	}

	/**
	 * Set the widget label
	 *
	 * @return string
	 */
	public function get_label() {
		return esc_html__( 'Taxonomy List', 'motta-addons' );
	}

	/**
	 * Default widget options
	 *
	 * @return array
	 */
	public function get_defaults() {
		return array(
			'taxonomy'  	=> 'product_cat',
			'parent_cat'  	=> '',
			'limit'  		=> '',
			'order'         => '',
			'orderby'       => '',
			'offset'        => '',
			'hide_empty'    => '',
			'new'        	=> '',
			'sale'        	=> '',
			'all'        	=> '',
		);
	}

	/**
	 * Render widget content
	 */
	public function render() {
		$data = $this->get_data();

		$args = array(
			'taxonomy' 		=> $data['taxonomy'],
			'number' 		=> $data['limit'],
			'child_of' 		=> $data['parent_cat'],
		);

		$args['order'] 		= $data['order'] ? $data['order'] : 'asc';
		$args['offset'] 	= $data['offset'] ? $data['offset'] : '';
		$args['hide_empty'] = $data['hide_empty'] ? true : false;

		if ( $data['orderby'] ) {
			$args['orderby'] = $data['orderby'];

			if ( $data['orderby'] == 'count' ) {
				$args['order'] = 'desc';
			}
		} else {
			$args['orderby'] = 'title';
		}

		$args = apply_filters( 'motta_addons_menu_widget_taxonomy_list_args', $args );

		$terms = get_terms( $args );

		if ( empty( $terms ) ) {
			return;
		}

		$parent_link = ! empty( $data['parent_cat'] ) ? get_term_link( (int) $data['parent_cat'], 'product_cat' ) : '';

		$classes = $data['classes'] ? ' ' . $data['classes'] : '';

		echo '<ul class="menu-taxonomy-list-widget'. esc_attr( $classes ) .'">';

		if ( $data['all'] ) {
			echo '<li class="menu-item--widget menu-taxonomy-list-widget__item menu-item--type-bold">';
			echo '<a href="'. esc_url( $parent_link ) .'"><strong>'. esc_html__( 'All Products', 'motta-addons' ) .'</strong></a>';
			echo '</li>';
		}

		if ( $data['new'] ) {
			echo '<li class="menu-item--widget menu-taxonomy-list-widget__item menu-item--type-bold">';
			echo '<a href="'. esc_url( add_query_arg( array( 'products_group' => 'new' ), $parent_link, 'product_cat' ) ) .'"><strong>'. esc_html__( 'New Arrivals', 'motta-addons' ) .'</strong></a>';
			echo '</li>';
		}

		if ( $data['sale'] ) {
			echo '<li class="menu-item--widget menu-taxonomy-list-widget__item menu-item--type-bold">';
			echo '<a href="'. esc_url( add_query_arg( array( 'products_group' => 'sale' ), $parent_link, 'product_cat' ) ) .'"><strong>'. esc_html__( 'Sale', 'motta-addons' ) .'</strong></a>';
			echo '</li>';
		}
		$parent_id = ! empty( $data['parent_cat'] ) ? (int) $data['parent_cat'] : 0;
		foreach ( $terms as $term ) {
			if( $term->parent == $parent_id ) {
				echo '<li class="menu-item--widget menu-taxonomy-list-widget__item">';
				echo '<a href="'. get_term_link( $term->slug, $data['taxonomy'] ) .'">'. esc_html( $term->name ) .'</a>';
				echo '</li>';

				foreach( $terms as $subcategory ) {
					if($subcategory->parent == $term->term_id) {
						echo '<li class="menu-item--widget menu-taxonomy-list-widget__item menu-taxonomy-list-widget__subitem">';
						echo '<a href="'. get_term_link( $subcategory->slug, $data['taxonomy'] ) .'">'. esc_html( $subcategory->name ) .'</a>';
						echo '</li>';
					}
				}
			}
		}

		echo '</ul>';
	}

	/**
	 * Widget setting fields.
	 */
	public function add_controls() {
		$this->add_control( array(
			'type' => 'select',
			'name' => 'taxonomy',
			'label' => esc_html__( 'Taxonomy', 'motta-addons' ),
			'class' => 'motta-menu-item-taxonomy',
			'options' => self::get_taxonomy(),
		) );

		$this->add_control( array(
			'type' 	=> 'select',
			'name' 	=> 'parent_cat',
			'label' => esc_html__( 'Parent Category', 'motta-addons' ),
			'class' => 'motta-menu-item-taxonomy-category',
			'options' => self::get_categories(),
		) );

		$this->add_control( array(
			'type' => 'select',
			'name' => 'order',
			'label' => esc_html__( 'Order', 'motta-addons' ),
			'options' => array(
				'0'  	=> esc_html__( 'Default', 'motta-addons' ),
				'asc'  	=> esc_html__( 'Ascending', 'motta-addons' ),
				'desc' 	=> esc_html__( 'Descending', 'motta-addons' ),
			),
		) );

		$this->add_control( array(
			'type' => 'select',
			'name' => 'orderby',
			'label' => esc_html__( 'Order By', 'motta-addons' ),
			'options' => array(
				'0'  			=> esc_html__( 'Default', 'motta-addons' ),
				'id'  			=> esc_html__( 'ID', 'motta-addons' ),
				'title' 		=> esc_html__( 'Title', 'motta-addons' ),
				'menu_order' 	=> esc_html__( 'Menu Order', 'motta-addons' ),
				'count' 		=> esc_html__( 'Product Counts', 'motta-addons' ),
			),
		) );

		$this->add_control( array(
			'type' => 'text',
			'name' => 'limit',
			'label' => esc_html__( 'Limit', 'motta-addons' ),
		) );

		$this->add_control( array(
			'type' => 'text',
			'name' => 'offset',
			'label' => esc_html__( 'Offset', 'motta-addons' ),
		) );

		$this->add_control( array(
			'type' => 'checkbox',
			'name' => 'hide_empty',
			'options' => array(
				'1'  => esc_html__( 'Hide empty categories', 'motta-addons' ),
			),
		) );

		$this->add_control( array(
			'type' => 'checkbox',
			'name' => 'new',
			'class' => 'motta-menu-item-taxonomy-category',
			'options' => array(
				'1'  => esc_html__( 'New Arrivals', 'motta-addons' ),
			),
		) );

		$this->add_control( array(
			'type' => 'checkbox',
			'name' => 'sale',
			'class' => 'motta-menu-item-taxonomy-category',
			'options' => array(
				'1'  => esc_html__( 'Sale', 'motta-addons' ),
			),
		) );


		$this->add_control( array(
			'type' => 'checkbox',
			'name' => 'all',
			'class' => 'motta-menu-item-taxonomy-category',
			'options' => array(
				'1'  => esc_html__( 'All Products', 'motta-addons' ),
			),
		) );
	}

	/**
	 * Get categories
	 *
	 * @return option
	 */
	public function get_categories() {
		$terms = \Motta\Addons\Helper::get_terms_hierarchy( 'product_cat', '&#8212;', false );

		if ( empty( $terms ) ) {
			return;
		}

		$options = wp_list_pluck( $terms, 'name', 'term_id' );
		$options = array( '' => esc_html__( 'Choose a category', 'motta-addons' ) ) + $options;

		return $options;
	}

	/**
	 * Get categories
	 *
	 * @return option
	 */
	public function get_taxonomy() {
		$options = array( 'product_cat' => esc_html__( 'Product Category', 'motta-addons' ) );

		if ( get_option( 'motta_product_brand', 'yes' ) == 'yes' ) {
			$taxonomy_brand = array( 'product_brand' => esc_html__( 'Product Brand', 'motta-addons' ) );
			$options = $options + $taxonomy_brand;
		}

		return $options;
	}
}
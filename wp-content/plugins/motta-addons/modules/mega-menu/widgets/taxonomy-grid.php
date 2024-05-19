<?php
/**
 * Widget Image
 */

namespace Motta\Addons\Modules\Mega_Menu\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Taxonomy Grid widget class
 */
class Taxonomy_Grid extends Widget_Base {

	/**
	 * Set the widget name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'taxonomy-grid';
	}

	/**
	 * Set the widget label
	 *
	 * @return string
	 */
	public function get_label() {
		return esc_html__( 'Taxonomy Grid', 'motta-addons' );
	}

	/**
	 * Default widget options
	 *
	 * @return array
	 */
	public function get_defaults() {
		return array(
			'title'  		=> '',
			'taxonomy' 		=> 'product_cat',
			'order'     	=> '',
			'orderby'   	=> '',
			'limit'  		=> '',
			'include'   	=> '',
			'exclude'   	=> '',
			'hide_empty'    => '',
			'hide_title'  	=> '',
			'all_cats'  	=> '',
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
		);

		$args['order'] 		= $data['order'] ? $data['order'] : 'asc';
		$args['exclude']	= $data['exclude'] ? $data['exclude'] : '';
		$args['hide_empty'] = $data['hide_empty'] ? true : false;

		if ( $data['orderby'] ) {
			$args['orderby'] = $data['orderby'];

			if ( $data['orderby'] == 'count' ) {
				$args['order'] = 'desc';
			}
		} else {
			$args['orderby'] = 'title';
		}

		if ( $data['include'] ) {
			$args['include'] = explode( ',', $data['include'] );
			$args['orderby'] = 'include';
		}

		$args = apply_filters( 'motta_addons_menu_widget_taxonomy_grid_args', $args );

		$terms = get_terms( $args );

		if ( empty( $terms ) ) {
			return;
		}

		$classes = $data['classes'] ? ' ' . $data['classes'] : '';
		$classes .= $data['hide_title'] ? ' taxonomy-grid-hide-title' : '';

		if ( $data['title'] ) {
			echo '<div class="menu-taxonomy-grid-widget__heading">'. esc_html( $data['title'] ) .'</div>';
		}

		echo '<ul class="menu-taxonomy-grid-widget'. esc_attr( $classes ) .'">';

		foreach ( $terms as $term ) {
			$thumbnail_name = $data['taxonomy'] == 'product_brand' ? 'brand_thumbnail_id' : 'thumbnail_id';
			$thumbnail_id 	= get_term_meta( $term->term_id, $thumbnail_name, true );
			$image_url 		= wp_get_attachment_url( $thumbnail_id );
			$link_url 		= get_term_link( $term->slug, $data['taxonomy'] );

			$image = $image_url ? '<span class="menu-taxonomy-grid-widget__image"><img alt="'. esc_html( $term->name ) .'" src="'. esc_url( $image_url ) .'"/></span>' : '';
			$title = $data['hide_title'] ? '' : '<span class="menu-taxonomy-grid-widget__name">'. esc_html( $term->name ) .'</span>';

			echo sprintf(
				'<li class="menu-taxonomy-grid-widget__item">
					<a href="%s">%s%s</a>
				</li>',
				esc_url( $link_url ),
				$image,
				$title
			);
		}

		$page_id = get_option( 'motta_product_brand_page_id' );
		$text_all = '<span>'. esc_html__( 'See All', 'motta-addons' ) .'</span>';

		if ( $data['all_cats'] && $data['taxonomy'] == 'product_brand' ) {
			echo '<li class="menu-taxonomy-grid-widget__item menu-taxonomy-grid-widget__item--all-cats">';

			if ( $page_id && get_option( 'motta_product_brand', 'yes' ) == 'yes' ) {
				echo '<a href="'. get_page_link( $page_id ) .'">'. $text_all .'</a>';
			} else {
				echo $text_all;
			}

			echo '</li>';
		}

		echo '</ul>';
	}

	/**
	 * Widget setting fields.
	 */
	public function add_controls() {
		$this->add_control( array(
			'type' => 'text',
			'name' => 'title',
			'label' => esc_html__( 'Navigation Label', 'motta-addons' ),
		) );

		$this->add_control( array(
			'type' => 'select',
			'name' => 'taxonomy',
			'label' => esc_html__( 'Taxonomy', 'motta-addons' ),
			'class' => 'motta-menu-item-taxonomy',
			'options' => self::get_taxonomy(),
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
			'value' => ''
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
			'value' => ''
		) );

		$this->add_control( array(
			'type' => 'text',
			'name' => 'include',
			'label' => esc_html__( 'Include', 'motta-addons' ),
			'description' => esc_html__( 'Enter product category name, separate by commas.', 'motta-addons' ),
		) );

		$this->add_control( array(
			'type' => 'text',
			'name' => 'exclude',
			'label' => esc_html__( 'Exclude', 'motta-addons' ),
			'description' => esc_html__( 'Enter product category ids, separate by commas.', 'motta-addons' ),
		) );

		$this->add_control( array(
			'type' => 'text',
			'name' => 'limit',
			'label' => esc_html__( 'Limit', 'motta-addons' ),
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
			'name' => 'hide_title',
			'options' => array(
				'1'  => esc_html__( 'Hide Title', 'motta-addons' ),
			),
		) );

		$this->add_control( array(
			'type' => 'checkbox',
			'name' => 'all_cats',
			'class' => 'motta-menu-item-taxonomy-brand motta-hidden',
			'options' => array(
				'1'  => esc_html__( 'All Brands', 'motta-addons' ),
			),
		) );
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
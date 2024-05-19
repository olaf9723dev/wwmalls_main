<?php
/**
 * Widget Image
 */

namespace Motta\Addons\Modules\Mega_Menu\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Banner widget class
 */
class Banner extends Widget_Base {

	/**
	 * Set the widget name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'banner';
	}

	/**
	 * Set the widget label
	 *
	 * @return string
	 */
	public function get_label() {
		return esc_html__( 'Banner', 'motta-addons' );
	}

	/**
	 * Default widget options
	 *
	 * @return array
	 */
	public function get_defaults() {
		return array(
			'image'  => array( 'id' => '', 'url' => '' ),
			'link'   => array( 'url' => '', 'target' => '' ),
			'title'  => '',
			'button' => '',
		);
	}

	/**
	 * Render widget content
	 */
	public function render() {
		$data = $this->get_data();

		$classes = $data['classes'] ? ' ' . $data['classes'] : '';

		echo '<div class="menu-widget-banner'. esc_attr( $classes ) .'">';

		if ( $data['image'] ) {
			$this->render_image( $data['image'], 'full', array( 'alt' => $data['title'] ) );
		}

		echo '<div class="menu-widget-banner__content">';

		if ( $data['title'] ) {
			echo '<div class="menu-widget-banner__title">' . wp_kses_post( $data['title'] ) . '</div>';
		}

		if ( $data['button'] ) {
			echo '<button class="menu-widget-banner__button button motta-button--subtle">'. esc_html( $data['button'] ) . '</button>';
		}

		echo '</div>';

		if ( $data['button'] && $data['link']['url'] ) {
			$data['link']['class'] = 'menu-widget-banner__link';
			$this->render_link_open( $data['link'] );
			echo '<span class="screen-reader-text">'. esc_html__( 'Banner Link', 'motta-addons' ) .'</span>';
			$this->render_link_close( $data['link'] );
		}

		echo '</div>';
	}

	/**
	 * Widget setting fields.
	 */
	public function add_controls() {
		$this->add_control( array(
			'type' => 'image',
			'label' => __( 'Image', 'motta-addons' ),
			'name' => 'image',
		) );

		$this->add_control( array(
			'type' => 'text',
			'label' => __( 'Title', 'motta-addons' ),
			'name' => 'title',
		) );

		$this->add_control( array(
			'type' => 'text',
			'name' => 'button',
			'label' => __( 'Button', 'motta-addons' ),
		) );

		$this->add_control( array(
			'type' => 'link',
			'name' => 'link',
		) );
	}
}
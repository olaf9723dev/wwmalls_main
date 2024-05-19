<?php
/**
 * Widget Image
 */

namespace Motta\Addons\Modules\Mega_Menu\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Image widget class
 */
class Image extends Widget_Base {

	/**
	 * Set the widget name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'image';
	}

	/**
	 * Set the widget label
	 *
	 * @return string
	 */
	public function get_label() {
		return esc_html__( 'Image Box', 'motta-addons' );
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
			'desc'   => '',
			'button' => '',
			'align'  => '',
			'vertical' => '',
		);
	}

	/**
	 * Render widget content
	 */
	public function render() {
		$data = $this->get_data();

		$this->render_link_open( $data['link'] );
		$this->render_image( $data['image'], 'full', array( 'alt' => $data['desc'] ) );
		$this->render_link_close( $data['link'] );

		if ( $data['desc'] || ( $data['button'] && $data['link']['url'] ) ) {

			echo '<div class="menu-widget-image__content">';

			if ( $data['desc'] ) {
				echo '<div class="menu-widget-image__desc">' . wp_kses_post( $data['desc'] ) . '</div>';
			}

			if ( $data['button'] && $data['link']['url'] ) {
				$data['link']['class'] = $data['classes'] ? $data['classes'] : '';
				$data['link']['class'] .= ' menu-widget-image__button';

				$this->render_link_open( $data['link'] );
				echo esc_html( $data['button'] );
				$this->render_link_close( $data['link'] );
			}

			echo '</div>';

		}
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
			'type' => 'link',
			'name' => 'link',
		) );

		$this->add_control( array(
			'type' => 'textarea',
			'label' => __( 'Description', 'motta-addons' ),
			'name' => 'desc',
		) );

		$this->add_control( array(
			'type' => 'text',
			'name' => 'button',
			'label' => __( 'Button', 'motta-addons' ),
		) );

		$this->add_control( array(
			'type' => 'select',
			'name' => 'align',
			'label' => __( 'Text Align', 'motta-addons' ),
			'options' => array(
				'0' 		=> esc_html__( 'Default', 'motta-addons' ),
				'left' 	=> esc_html__( 'Left', 'motta-addons' ),
				'right' => esc_html__( 'Right', 'motta-addons' ),
			),
		) );

		$this->add_control( array(
			'type' => 'select',
			'name' => 'vertical',
			'label' => __( 'Vertical Image', 'motta-addons' ),
			'options' => array(
				'0' 		=> esc_html__( 'Default', 'motta-addons' ),
				'left' 	=> esc_html__( 'Left', 'motta-addons' ),
				'right' => esc_html__( 'Right', 'motta-addons' ),
			),
		) );
	}
}
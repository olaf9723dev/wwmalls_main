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
class Heading extends Widget_Base {

	/**
	 * Set the widget name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'heading';
	}

	/**
	 * Set the widget label
	 *
	 * @return string
	 */
	public function get_label() {
		return esc_html__( 'Heading', 'motta-addons' );
	}

	/**
	 * Default widget options
	 *
	 * @return array
	 */
	public function get_defaults() {
		return array(
			'title'  	=> '',
			'link'   	=> array( 'url' => '', 'target' => '' ),
			'type' 		=> ''
		);
	}

	/**
	 * Render widget content
	 */
	public function render() {
		$data = $this->get_data();

		$data['link']['class'] = $data['classes'] ? $data['classes'] : '';
		$data['link']['target'] = $data['link']['target'] ? $data['link']['target'] : '';

		$this->render_link_open( $data['link'] );

		if ( $data['type'] ) {
			if ( $data['type'] == 'label' ) {
				echo '<h6>'. $data['title'] .'</h6>';
			} elseif ( $data['type'] == 'bold' ) {
				echo '<strong>'. $data['title'] .'</strong>';
			} else {
				if ( empty( $data['link']['url'] ) ) {
					echo '<span>' . wp_kses_post( $data['title'] ) . '</span>';
				} else {
					echo wp_kses_post( $data['title'] );
				}
			}
		}

		$this->render_link_close( $data['link'] );
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
			'type' => 'link',
			'name' => 'link',
		) );

		$this->add_control( array(
			'type' => 'select',
			'name' => 'type',
			'options' => array(
				'0' 		=> esc_html__( 'Default', 'motta-addons' ),
				'label' => esc_html__( 'Label', 'motta-addons' ),
				'bold' 	=> esc_html__( 'Bold', 'motta-addons' ),
				'hidden' => esc_html__( 'Hidden', 'motta-addons' ),
				'empty' => esc_html__( 'Empty (keep spacing)', 'motta-addons' )
			),
		) );
	}
}
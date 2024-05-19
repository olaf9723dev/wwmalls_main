<?php

/**
 * Class Estatik_Framework_Form_View.
 */
class Estatik_Framework_Form_View extends Abstract_Estatik_Framework_View {

	/**
	 * Estatik_Framework_Form_View constructor.
	 *
	 * @param $args
	 */
	public function __construct( $args ) {

		parent::__construct( $args );

		$this->_args['base_field_name'] = ! empty( $this->_args['base_field_name'] ) ? $this->_args['base_field_name'] : $this->_args['framework_instance']->args['option_name'];

		if ( ! empty( $_REQUEST[ $this->_args['nonce_name'] ] ) && wp_verify_nonce( $_REQUEST[ $this->_args['nonce_name'] ], $this->_args['nonce_action'] ) ) {
			if ( ! empty( $_REQUEST[ 'base_field_name' ] ) ) {
				update_option( $_REQUEST[ 'base_field_name' ], $_REQUEST[ $_REQUEST[ 'base_field_name' ] ] );
			}
		}
	}

	/**
	 * Return default field attributes.
	 *
	 * @return mixed
	 */
	public static function get_default_attributes_names() {

		return apply_filters( 'ef/input/default_attributes', array(
			'name', 'id', 'class', 'data', 'enctype', 'method', 'action',
		) );
	}

	/**
	 * Return field attributes array.
	 *
	 * @return array
	 */
	public function get_form_attributes() {

		$default_attr = static::get_default_attributes_names();
		$result = array();

		foreach ( $this->_args as $attr => $value ) {
			if ( in_array( $attr, $default_attr ) ) {

				if ( is_array( $value ) && $attr == 'data' ) {
					foreach ( $value as $key => $subvalue ) {
						$result[ $attr . '-' . $key ] = $subvalue;
					}
				} else {
					if ( ! empty( $value ) ) {
						$result[ $attr ] = $value;
					}
				}
			}
		}

		return $result;
	}

	/**
	 * @param bool $_echo
	 *
	 * @return string|void
	 */
	public function render( $_echo = true ) {

		$content = $this->render_start( false );
		$content .= $this->render_views( $this->args, false );
		$content .= $this->render_end( false );

		if ( $_echo ) {
			echo $content;
		} else {
			return $content;
		}
	}

	/**
	 * @param bool $_echo
	 *
	 * @return string
	 */
	public function render_start( $_echo = true ) {

		$attributes = null;

		foreach ( $this->get_form_attributes() as $key => $value ) {
			$attributes .= " {$key}='$value'";
		}

		$content = "<form {$attributes}>{$this->_args['nonce']}";
		$framework = $this->get_framework();

		$content .= "<div class='ef-theme-header'>
			<div class='ef-theme-header__name'>";

		if ( $logo_url = $framework->get_logo_url() ) {
			$content .= "<img src='$logo_url'/>";
		}

		if ( $title = $framework->get_theme_name() ) {
			$content .= "<h1 class='ef-theme-name'>{$title}</h1>";
		}

		if ( $version = $framework->get_version() ) {
			$content .= "<span class='ef-version'>" . sprintf( __( 'Version %s', 'estatik-framework' ), $version ) . "</span>";
		}

		$content .= "</div><div class='ef-theme-header__control'>";
		$content .= ! empty( $framework->args['controls'] ) ? $framework->args['controls'] : '';
		$content .= "<input class='ef-btn ef-btn-primary' type='submit' value='" . __( 'Save Settings', 'estatik-framework' ) . "'>";
		
		$content .= "</div>";
		$content .= "</div>";

		$content .= "<input type='hidden' name='base_field_name' value='{$this->_args['base_field_name']}'>";

		if ( $_echo ) {
			echo $content;
		} else {
			return $content;
		}
	}

	/**
	 * @param bool $_echo
	 *
	 * @return string
	 */
	public function render_end( $_echo = true ) {}

	/**
	 * @return array
	 */
	public function get_default_args() {

		return array(
			'class' => 'js-ef-form',
			'nonce_action' => 'ef-save-form',
			'nonce_name' => 'ef-save-form',
			'nonce' => wp_nonce_field( 'ef-save-form', 'ef-save-form', true, false ),
			'base_field_name' => '',
			'method' => 'POST',
		);
	}
}
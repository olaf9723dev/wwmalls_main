<?php

/**
 * Class Estatik_Framework_Base_Input
 */
class Estatik_Framework_Selectbox_Input extends Estatik_Framework_Base_Input {

	/**
	 * Return html input.
	 *
	 * @return string
	 */
	public function get_html_input() {

		$attributes = $this->build_attributes();

		/** @var Estatik_Framework_Options_Container $options */
		$options = $this->_args['framework_instance']->options();
		$atts = $this->get_field_attributes();

		$default = ! empty( $this->_args['default_value'] ) ? $this->_args['default_value'] : '';
		$_value = $options->get( $atts['name'], $default );

		if ( ! empty( $this->_args['taxonomy'] ) ) {
			$this->_args['options'] = get_terms( $this->_args['taxonomy'], array(
				'fields' => 'id=>name'
			) );
		}

		$input = sprintf( "<select%s>", $attributes );
			if ( ! empty( $this->_args['options'] ) ) {
				foreach ( $this->_args['options'] as $value => $label ) {
					if ( is_array( $label ) ) {
						$input .= "<optgroup label='{$value}'>";
						foreach ( $label as $subvalue => $sublabel ) {

							$input .= "<option value='{$subvalue}' " . static::selected( $subvalue, $_value ) . ">{$sublabel}</option>";
						}
						$input .= "</optgroup>";
					} else {
						$input .= "<option value='{$value}' " . static::selected( $value, $_value ) . ">{$label}</option>";
					}
				}
			}
		$input .= "</select>";

		return $input;
	}

	/**
	 * @param $value string
	 * @param $check_value array|string
	 *
	 * @return string
	 */
	public static function selected( $value, $check_value ) {

		if ( is_array( $check_value ) ) {
			return in_array( $value, $check_value ) ? "selected='selected'" : '';
		} else {
			return $value == $check_value ? "selected='selected'" : '';
		}
	}

	/**
	 * Return default field attributes.
	 *
	 * @return mixed
	 */
	public static function get_default_attributes_names() {

		return apply_filters( 'ef/input/default_attributes', array(
			'name', 'id', 'class', 'placeholder', 'readonly', 'required', 'data', 'multiple'
		) );
	}

	/**
	 * @inheritdoc
	 */
	public function get_default_args() {

		return array_merge( parent::get_default_args(), array(
			'options' => array(),
		) );
	}
}

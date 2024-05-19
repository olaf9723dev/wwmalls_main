<?php

/**
 * Class Estatik_Framework_Textarea_Input.
 */
class Estatik_Framework_Textarea_Input extends Estatik_Framework_Base_Input {

	/**
	 * Return default field attributes.
	 *
	 * @return mixed
	 */
	public static function get_default_attributes_names() {

		return apply_filters( 'ef/input/default_attributes', array(
			'name', 'id', 'class', 'placeholder', 'readonly', 'required', 'data',
		) );
	}

	/**
	 * Return html input.
	 *
	 * @return string
	 */
	public function get_html_input() {

		$attributes = $this->build_attributes();

		/** @var Estatik_Framework_Options_Container $options */
		$options = $this->_args['framework_instance']->options();

		$default = ! empty( $this->_args['default_value'] ) ? $this->_args['default_value'] : '';
		$value = $options->get( $this->_args['name'], $default );

		return sprintf( "<textarea%s>%s</textarea>", $attributes, $value );
	}
}


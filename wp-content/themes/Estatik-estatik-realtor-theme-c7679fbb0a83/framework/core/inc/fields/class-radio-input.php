<?php

/**
 * Class Estatik_Framework_Multiple_Radio_Input.
 */
class Estatik_Framework_Radio_Input extends Estatik_Framework_Base_Input {

	/**
	 * @inheritdoc
	 */
	public function get_default_args() {

		return array_merge( parent::get_default_args(), array(
			'options' => array(),
			'type' => 'radio',
			'input_item_wrap_class' => 'ef-input__field-inner ef-input__field-inner__inline',
			'input_item_wrap_label_class' => 'ef-input__field-inner-label',
		) );
	}

	/**
	 * @inheritdoc
	 */
	public function get_html_label() {

		return $this->_args[ 'label' ] ? sprintf( "<span class='%s'>%s</span>",
			$this->_args['label_class'],
			$this->_args['label']
		) : null;
	}

	/**
	 * @inheritdoc
	 */
	public function get_html_input() {

		$attributes = null;
		$inputs = null;

		$attributes = $this->build_attributes();

		/** @var Estatik_Framework_Options_Container $options */
		$options = $this->_args['framework_instance']->options();

		$default = ! empty( $this->_args['default_value'] ) ? $this->_args['default_value'] : '';
		$_value = $options->get( $this->_args['name'], $default );

		foreach ( $this->_args['options'] as $value => $label ) {

			$_attributes = $attributes . " " . checked( $_value, $value, false );

			$inputs .= sprintf( "<div class='%s'><label><input%s %s><span class='%s'>%s</span></label></div>",
				$this->_args['input_item_wrap_class'],
				$_attributes,
				"value='{$value}'",
				$this->_args['input_item_wrap_label_class'],
				$label
			);
		}

		return $inputs;
	}
}

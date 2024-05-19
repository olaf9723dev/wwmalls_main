<?php

/**
 * Class Estatik_Framework_Multiple_Radio_Input.
 */
class Estatik_Framework_Radio_Image_Input extends Estatik_Framework_Radio_Input {

	/**
	 * @inheritdoc
	 */
	public function get_default_args() {

		return array_merge( parent::get_default_args(), array(
			'options' => array(),
			'type' => 'radio',
			'images' => array(),
			'input_item_wrap_class' => 'ef-input__field-inner js-ef-input__field-inner',
			'input_item_wrap_label_class' => 'ef-input__field-inner-label',
			'input_wrap_class' => 'ef-input__field ef-input__field--radio-image-wrap',
			'img_width' => '50px',
			'img_height' => 'auto',
			'img_alt' => 'auto',
			'img_class' => 'ef-input__field--radio-image',
		) );
	}

	/**
	 * @inheritdoc
	 */
	public function get_html_input() {

		$attributes = $this->build_attributes();
		$inputs = null;

		/** @var Estatik_Framework_Options_Container $options */
		$options = $this->_args['framework_instance']->options();

		$default = ! empty( $this->_args['default_value'] ) ? $this->_args['default_value'] : '';
		$_value = $options->get( $this->_args['name'], $default );

		foreach ( $this->_args['options'] as $value => $label ) {

			$_attributes = $attributes;
			$input_item_wrap_class = $this->_args['input_item_wrap_class'];

			if ( $value == $_value ) {
				$_attributes = $attributes . " checked='checked'";
				$input_item_wrap_class .= " active";
			}

			$image = ! empty( $this->_args['images'][ $value ] ) ?
				sprintf( "<img 
									src='%s' alt='%s' class='%s' 
									style='width: %s; height: %s;'>",
					$this->_args['images'][ $value ],
					$this->_args['img_alt'],
					$this->_args['img_class'],
					$this->_args['img_width'],
					$this->_args['img_height'] ) : null;

			$inputs .= sprintf( "<div class='%s'><label>%s<span class='%s'>%s</span><input%s %s></label></div>",
				$input_item_wrap_class,
				$image,
				$this->_args['input_item_wrap_label_class'],
				$label,
				$_attributes,
				"value='{$value}'"
			);
		}

		return $inputs;
	}
}

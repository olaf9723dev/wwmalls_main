<?php

/**
 * Class Estatik_Framework_Base_Input
 */
class Estatik_Framework_Base_Input {

	/**
	 * Field config array.
	 *
	 * @var array
	 */
	protected $_args;

	/**
	 * Estatik_Framework_Base_Input constructor.
	 *
	 * @param $args
	 */
	public function __construct( $args ) {

		$this->_args = wp_parse_args( $args, $this->get_default_args() );
	}

	/**
	 * Render built input.
	 *
	 * @param bool $_echo
	 *
	 * @return void|string
	 */
	public function render( $_echo = true ) {

		$content = sprintf( "<div class='{$this->_args['wrap_class']}'>
					<div class='{$this->_args['label_wrap_class']}'>
						%s
						%s
					</div>
					<div class='{$this->_args['input_wrap_class']}'>
						%s
						%s
					</div>
				</div>",
			$this->get_html_label(),
			$this->get_html_label_description(),
			$this->get_html_input(),
			$this->get_html_input_description()
		);

		if ( $_echo ) {
			echo $content;
		} else {
			return $content;
		}
	}

	/**
	 * Return label description html.
	 *
	 * @return string
	 */
	public function get_html_input_description() {

		return $this->_args['input_description'] ? sprintf( "<div class='%s'>%s</div>",
			$this->_args['input_description_class'],
			$this->_args['input_description']
		) : null;
	}

	/**
	 * Return label description html.
	 *
	 * @return string
	 */
	public function get_html_label_description() {

		return $this->_args['label_description'] ? sprintf( "<div class='%s'>%s</div>",
			$this->_args['label_description_class'],
			$this->_args['label_description']
		) : null;
	}

	/**
	 * Return html label.
	 *
	 * @return null|string
	 */
	public function get_html_label() {

		return $this->_args[ 'label' ] ? sprintf( "<label for='%s' class='%s'>%s %s</label>",
			$this->_args['id'],
			$this->_args['label_class'],
			$this->_args['label'],
			$this->_args['help'] ? "<i class='fa fa-info-circle' data-help='" . $this->_args['help'] . "'></i>" : ''
		) : null;
	}

	/**
	 * Build input attributes string.
	 *
	 * @return null|string
	 */
	public function build_attributes() {

		$attributes = null;

		$atts = $this->get_field_attributes();

        if ( ! empty( $this->_args['multiple'] ) && $this->_args['type'] == 'select' ) {
            unset( $atts['value'] );
        }

		foreach ( $atts as $key => $value ) {

			if ( $key == 'name' && ! empty( $this->_args['framework_instance']->args['option_name'] ) ) {
				$value = $this->_args['framework_instance']->args['option_name'] . "[{$value}]";

				if ( ! empty( $this->_args['multiple'] ) ) {
					$value = $value . '[]';
				}
			}
			$attributes .= " {$key}='" . esc_attr( $value ) . "'";
		}

		/** @var Estatik_Framework_Options_Container $options */
		$options = $this->_args['framework_instance']->options();

		$value = $options->get( $atts['name'] );

		if ( in_array( $this->_args['type'], array( 'radio', 'checkbox' ) ) ) {
			if ( empty( $this->_args['options'] ) || count( $this->_args['options'] ) == 1 ) {
				$attributes .= " " . checked( boolval( $value ), true, false );
			}
		} else {
            if ( $this->_args['type'] != 'select'  ) {
                $attributes .= " value='" . stripslashes( esc_attr( $value ) ) . "'";
            }
		}

		if ( $this->_args['type'] == 'color' && ( $default_value = $options->get_default_value( $atts['name'] ) ) ) {
			$attributes .= " data-default-color='$default_value'";
		}

		return $attributes;
	}

	/**
	 * Return html input.
	 *
	 * @return string
	 */
	public function get_html_input() {

		$attributes = $this->build_attributes();

		return sprintf( "<input%s>", $attributes );
	}

	/**
	 * Return field attributes array.
	 *
	 * @return array
	 */
	public function get_field_attributes() {

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
	 * Return default field attributes.
	 *
	 * @return mixed
	 */
	public static function get_default_attributes_names() {

		return apply_filters( 'ef/input/default_attributes', array(
			'name', 'id', 'class', 'placeholder', 'type', 'readonly', 'required', 'data', 'min', 'max', 'step', 'checked', 'value',
		) );
	}

	/**
	 * Return field default args.
	 *
	 * @return array
	 */
	public function get_default_args() {

		return apply_filters( 'ef/input/default_args', array(
			'label' => '',
			'label_description' => '',
			'label_description_class' => 'ef-input__label-description',
			'input_description' => '',
			'input_description_class' => 'ef-input__input-description',
			'id' => '',
			'input_wrap_class' => 'ef-input__field',
			'label_class' => 'ef-input__label',
			'wrap_class' => 'ef-input',
			'label_wrap_class' => 'ef-input__label-wrap',
			'value' => '',
			'type' => 'text',
			'help' => '',
			'option_name' => '',
		), $this->_args, $this );
	}
}

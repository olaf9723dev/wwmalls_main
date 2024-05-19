<?php

/**
 * Class Estatik_Framework_Media_Input
 */
class Estatik_Framework_Media_Input extends Estatik_Framework_Base_Input {

	/**
	 * Estatik_Framework_Media_Input constructor.
	 *
	 * @param $args
	 */
	public function __construct( $args ) {

		parent::__construct( $args );
		$this->enqueue_media();
	}

	/**
	 * Return html input.
	 *
	 * @return string
	 */
	public function get_html_input() {

		$attributes = $this->build_attributes();
		$images = null;

		$input = sprintf( "<input%s>", $attributes );

		/** @var Estatik_Framework_Options_Container $options */
		$options = $this->_args['framework_instance']->options();

		$default = ! empty( $this->_args['default_value'] ) ? $this->_args['default_value'] : '';
		$value = $options->get( $this->_args['name'], $default );

		$hidden = 'hidden';

		if ( ! empty( $value ) && ( $ids = explode( ',', $value ) ) ) {

			$hidden = '';

			foreach ( $ids as $attachment_id ) {
				$attachment_src = wp_get_attachment_image( $attachment_id );
				$images .= "<div class='ef-media-item js-ef-media-item'>{$attachment_src}
								<a href='#' class='ef-media-item__remove js-ef-media-item__remove' data-id='{$attachment_id}'>×</a>
							</div>";
			}
		}

		$content = "<div class='js-ef-media-container ef-media-container {$this->_args['multiple']}'>{$images}</div>
			<button class='js-ef-media-button ef-btn ef-btn-primary'>{$this->_args['button_text']}</button>{$input}
			<button class='js-ef-media-remove ef-btn ef-btn-secondary {$hidden}'>{$this->_args['button_remove_text']}</button>{$input}";


		return $content;
	}

	/**
	 * Return field attributes array.
	 *
	 * @return array
	 */
	public function get_field_attributes() {

		$result = parent::get_field_attributes();
		$result['type'] = 'hidden';
		$result['data-multiple'] = $this->_args['multiple'];
		$classes = ! empty( $result['class'] ) ? $result['class'] : '';
		$result['class'] = $classes . ' js-ef-media-input';

		return $result;
	}

	/**
	 * Return field default args.
	 *
	 * @return array
	 */
	public function get_default_args() {

		return array_merge( parent::get_default_args(), array(
			'wrap_class' => 'ef-input js-ef-input__media',
			'button_text' => __( 'Upload Media', 'estatik-framework' ),
			'multiple' => false,
			'button_remove_text' => __( 'Remove All', 'estatik-framework' ),
		) );
	}

	/**
	 * Enqueue media scripts.
	 */
	public function enqueue_media() {

		wp_enqueue_media();
		wp_enqueue_script( 'ef-media-input', get_template_directory_uri() . '/framework/assets/js/media.jquery.js', array( 'jquery' ) );
	}
}

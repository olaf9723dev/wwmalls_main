<?php

/**
 * Class Estatik_Framework_View_Factory
 */
class Estatik_Framework_Fields_Factory {

	/**
	 * @var
	 */
	protected $_framework_instance;

	/**
	 * Estatik_Framework_Fields_Factory constructor.
	 *
	 * @param $framework_instance
	 */
	public function __construct( $framework_instance ) {

		$this->_framework_instance = $framework_instance;
	}

	/**
	 * @return array
	 */
	public function get_fields_list() {

		return apply_filters( 'ef/views/list', array(
			'media' => 'Estatik_Framework_Media_Input',
			'radio-image' => 'Estatik_Framework_Radio_Image_Input',
			'checkbox-image' => 'Estatik_Framework_Radio_Image_Input',
			'radio' => 'Estatik_Framework_Radio_Input',
			'checkbox' => 'Estatik_Framework_Radio_Input',
			'select' => 'Estatik_Framework_Selectbox_Input',
			'textarea' => 'Estatik_Framework_Textarea_Input',
			'input' => 'Estatik_Framework_Base_Input',
		) );
	}

	/**
	 * @param $view_name
	 * @param $args
	 *
	 * @return mixed
	 */
	public function get_field( $view_name, $args ) {

		$views = $this->get_fields_list();
		$args['framework_instance'] = $this->_framework_instance;

		return $view_name ? new $views[ $view_name ]( $args ) : null;
	}
}
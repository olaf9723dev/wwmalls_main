<?php

/**
 * Class Estatik_Framework_View_Factory
 */
class Estatik_Framework_Views_Factory {

	/**
	 * @var array
	 */
	protected $_framework_instance;

	/**
	 * Estatik_Framework_Views_Factory constructor.
	 *
	 * @param $framework_instance
	 */
	public function __construct( $framework_instance ) {

		$this->_framework_instance = $framework_instance;
	}

	/**
	 * @return array
	 */
	public function get_views_list() {

		return apply_filters( 'ef/views/list', array(
			'form' => 'Estatik_Framework_Form_View',
			'section' => 'Estatik_Framework_Section_View',
			'tab' => 'Estatik_Framework_Tab_View',
		) );
	}

	/**
	 * @param $view_name
	 * @param $args
	 *
	 * @return Abstract_Estatik_Framework_View
	 */
	public function get_view( $view_name, $args ) {

		$views = $this->get_views_list();
		$args['framework_instance'] = $this->_framework_instance;

		return new $views[ $view_name ]( $args );
	}
}
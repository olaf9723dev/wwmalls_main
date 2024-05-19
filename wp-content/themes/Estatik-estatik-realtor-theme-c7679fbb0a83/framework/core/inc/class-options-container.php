<?php

/**
 * Class Estatik_Framework_Options_Container.
 */
class Estatik_Framework_Options_Container {

	/**
	 * Use base_field_name variable of Form View Component. Used in update_option function as 1st argument.
	 *
	 * @var string Container name.
	 */
	protected $_container;
	protected $_options;

	/**
	 * Estatik_Framework_Options_Container constructor.
	 *
	 * @param null $container
	 * @param array $options
	 */
	public function __construct( $container, $options ) {

		$this->set_container( $container );
		$this->_options = $options;
	}

	/**
	 * Container setter.
	 *
	 * @param $container
	 */
	public function set_container( $container ) {

		$this->_container = $container;
	}

	/**
	 * Return field value.
	 *
	 * @param $container
	 *    Use base_field_name variable of Form View Component. Used in update_option function as 1st argument.
	 *
	 * @param $field
	 * @param null $default
	 *
	 * @return mixed|null
	 */
	public function get( $field, $default = null, $container = null ) {

		$container = $container ? $container : $this->_container;
		$options = $this->get_all( $container );

//		$def_val = isset( $this->_options[ $field ]['default_value'] ) ? $this->_options[ $field ]['default_value'] : null;

		return isset( $options[ $field ] ) ? $options[ $field ] : $default;
	}

	/**
	 * Save field value.
	 *
	 * @param $container
	 *    Use base_field_name variable of Form View Component. Used in update_option function as 1st argument.
	 *
	 * @param $field
	 * @param $value
	 */
	public function set( $field, $value, $container = null ) {

		$container = $container ? $container : $this->_container;
		$options = $this->get_all( $container );
		$options[ $field ] = $value;

		update_option( $container, $options );
	}

	/**
	 * Return all of container options.
	 *
	 * @param null $container
	 *
	 * @return array
	 */
	public function get_all( $container = null ) {

		$container = $container ? $container : $this->_container;

		return get_option( $container );
	}

	/**
	 * @param $field
	 *
	 * @return null
	 */
	public function get_default_value( $field ) {
		return ! empty( $this->_options[ $field ]['default_value'] ) ? $this->_options[ $field ]['default_value'] : null;
	}

	/**
	 * @return array
	 */
	public function get_options() {

		return $this->_options;
	}
}

<?php

/**
 * Class Abstract_Estatik_Framework_View.
 */
abstract class Abstract_Estatik_Framework_View {

	/**
	 * @var array
	 */
	protected $_args = array();

	/**
	 * Abstract_Estatik_Framework_View constructor.
	 *
	 * @param $args
	 */
	public function __construct( $args ) {

		$this->_args = wp_parse_args( $args, $this->get_default_args() );
	}

	/**
	 * @return array
	 */
	public function get_default_args() {

		return array( 'form' => null );
	}

	/**
	 * Render views.
	 *
	 * @param $args
	 * @param bool $_echo
	 *
	 * @return null|string
	 */
	public function render_views( $args, $_echo = true ) {

		$views_factory = new Estatik_Framework_Views_Factory( $this->_args['framework_instance'] );
		$fields_factory = new Estatik_Framework_Fields_Factory( $this->_args['framework_instance'] );
		$content = null;

		if ( ! empty( $args['sections'] ) ) {
			foreach ( $args['sections'] as $section ) {
				$section = $views_factory->get_view( 'section', $section );
				$content .= $section->render( false );
			}
		}

		if ( ! empty( $args['fields'] ) ) {
			foreach ( $args['fields'] as $field ) {
				$field = $this->_args['framework_instance']->args['options'][ $field ];
				$field = $fields_factory->get_field( $field['field_type'], $field );
				$content .= $field->render( false );
			}
		}

		if ( ! empty( $args['tab'] ) ) {
			foreach ( $args['tab'] as $tab ) {
				$tab = $views_factory->get_view( 'tab', $tab );
				$content .= $tab->render( false );
			}
		}

		if ( ! empty( $args['action'] ) ) {
			ob_start();
			do_action( $args['action'], $args );
			$content .= ob_get_clean();
		}

		if ( ! empty( $args['content'] ) ) {
			$content = $args['content'];
		}

		if ( $_echo ) {
			echo $content;
		} else {
			return $content;
		}
	}

	/**
	 * @return mixed
	 */
	public function before_view() {

		$content = null;

		if ( isset( $this->_args['form'] ) ) {
			$view_factory = new Estatik_Framework_Views_Factory( $this->_args['framework_instance'] );

			$form = $view_factory->get_view( 'form', $this->_args['form'] );

			$content .= $form->render_start( false );
		}

		return $content;
	}

	/**
	 * @return null|string
	 */
	public function after_view() {

		$content = null;

		if ( isset( $this->_args['form'] ) ) {
			$view_factory = new Estatik_Framework_Views_Factory( $this->_args['framework_instance'] );

			$form = $view_factory->get_view( 'form', $this->_args['form'] );

			$content .= $form->render_end( false);
		}

		return $content;
	}

	/**
	 * @return Estatik_Framework
	 */
	public function get_framework() {

		return $this->_args['framework_instance'];
	}

	/**
	 * @inheritdoc
	 */
	abstract public function render( $_echo = true );
}

<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

namespace TCB\Integrations\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Main
 *
 * @package TCB\Integrations\Automator
 */
class Main {

	/**
	 * Add WooCommerce support
	 * process trigger callback
	 *
	 */
	public static function init() {
		if ( defined( 'THRIVE_AUTOMATOR_RUNNING' )
		     && ( ( defined( 'TVE_DEBUG' ) && TVE_DEBUG )
		          || ( defined( 'TAP_VERSION' ) && version_compare( TAP_VERSION, '1.0', '>=' ) ) ) ) {
			static::add_hooks();
		}
	}

	/**
	 * @param string $subpath
	 *
	 * @return string
	 */
	public static function get_integration_path( $subpath = '' ) {
		return TVE_TCB_ROOT_PATH . 'inc/automator/' . $subpath;
	}

	public static function add_hooks() {
		static::load_apps();
		static::load_data_objects();
		static::load_fields();
		static::load_action_fields();
		static::load_actions();
		static::load_trigger_fields();
		static::load_triggers();
		add_action( 'tap_output_extra_svg', [ 'TCB\Integrations\Automator\Main', 'display_icons' ] );

		add_filter( 'tvd_automator_api_data_sets', [ 'TCB\Integrations\Automator\Main', 'dashboard_sets' ], 1, 1 );

		add_filter( 'tve_automator_should_use_form', [ 'TCB\Integrations\Automator\Main', 'filter_lgs' ], 10, 4 );
	}

	public static function load_apps() {
		foreach ( static::load_files( 'apps' ) as $app ) {
			\thrive_automator_register_app( new $app() );
		}
	}

	public static function load_triggers() {
		foreach ( static::load_files( 'triggers' ) as $trigger ) {
			\thrive_automator_register_trigger( new $trigger() );
		}
	}

	public static function load_actions() {
		foreach ( static::load_files( 'actions' ) as $action ) {
			\thrive_automator_register_action( new $action() );
		}
	}

	public static function load_action_fields() {
		foreach ( static::load_files( 'action-fields' ) as $field ) {
			\thrive_automator_register_action_field( new $field() );
		}
	}

	public static function load_trigger_fields() {
		foreach ( static::load_files( 'trigger-fields' ) as $field ) {
			\thrive_automator_register_trigger_field( new $field() );
		}
	}

	public static function load_fields() {
		foreach ( static::load_files( 'fields' ) as $field ) {
			\thrive_automator_register_data_field( new $field() );
		}
	}

	public static function load_data_objects() {
		foreach ( static::load_files( 'data-objects' ) as $data_object ) {
			\thrive_automator_register_data_object( new $data_object() );
		}
	}

	public static function display_icons() {
		include_once static::get_integration_path( 'icons.svg' );
	}

	public static function load_files( $type ) {
		$integration_path = static::get_integration_path( $type );

		$local_classes = [];
		foreach ( glob( $integration_path . '/*.php' ) as $file ) {
			require_once $file;
			$class = 'TCB\Integrations\Automator\\' . static::get_class_name_from_filename( $file );
			if ( class_exists( $class ) ) {
				$local_classes[] = $class;
			}
		}

		return $local_classes;
	}

	public static function get_class_name_from_filename( $filename ) {
		$name = str_replace( 'class-', '', basename( $filename, '.php' ) );

		return str_replace( '-', '_', ucwords( $name, '-' ) );
	}

	/**
	 * Enroll form_data as data that can be used in TD for Automator actions
	 *
	 * @param $sets
	 *
	 * @return mixed
	 */
	public static function dashboard_sets( $sets ) {
		$sets[] = 'form_data';

		return $sets;
	}

	public static function filter_lgs( $allow, $lg_post, $trigger_id, $trigger_data ) {
		$form_type = $lg_post->form_type;
		if ( $trigger_id === Register_Form_Submit::get_id() && ( empty( $form_type ) || $form_type !== 'registration_form' ) ) {
			$allow = false;
		}

		return $allow;
	}
}

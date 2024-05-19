<?php
/**
 * Motta Addons Modules functions and definitions.
 *
 * @package Motta
 */

namespace Motta\Addons\Modules\Catalog_Mode;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Addons Modules
 */
class Module {

	/**
	 * Instance
	 *
	 * @var $instance
	 */
	private static $instance;


	/**
	 * Initiator
	 *
	 * @since 1.0.0
	 * @return object
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->includes();
		$this->add_actions();
	}

	/**
	 * Includes files
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function includes() {
		\Motta\Addons\Auto_Loader::register( [
			'Motta\Addons\Modules\Catalog_Mode\Frontend'        => MOTTA_ADDONS_DIR . 'modules/catalog-mode/frontend.php',
			'Motta\Addons\Modules\Catalog_Mode\Settings'    	=> MOTTA_ADDONS_DIR . 'modules/catalog-mode/settings.php',
		] );
	}


	/**
	 * Add Actions
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function add_actions() {
		if ( is_admin() ) {
			\Motta\Addons\Modules\Catalog_Mode\Settings::instance();
		}

		if ( get_option( 'motta_catalog_mode' ) == 'yes' ) {
			\Motta\Addons\Modules\Catalog_Mode\Frontend::instance();
		}
	}

}

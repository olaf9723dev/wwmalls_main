<?php
/**
 * Motta Addons Modules functions and definitions.
 *
 * @package Motta
 */

namespace Motta\Addons\Modules\Product_Bought_Together;

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
		$this->actions();
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
			'Motta\Addons\Modules\Product_Bought_Together\Frontend'        => MOTTA_ADDONS_DIR . 'modules/product-bought-together/frontend.php',
			'Motta\Addons\Modules\Product_Bought_Together\Settings'    	=> MOTTA_ADDONS_DIR . 'modules/product-bought-together/settings.php',
			'Motta\Addons\Modules\Product_Bought_Together\Product_Meta'    => MOTTA_ADDONS_DIR . 'modules/product-bought-together/product-meta.php',
		] );
	}


	/**
	 * Add Actions
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function actions() {
		if ( is_admin() ) {
			\Motta\Addons\Modules\Product_Bought_Together\Settings::instance();
		}

		if ( get_option( 'motta_product_bought_together', 'yes' ) == 'yes' ) {
			\Motta\Addons\Modules\Product_Bought_Together\Frontend::instance();

			if ( is_admin() ) {
				\Motta\Addons\Modules\Product_Bought_Together\Product_Meta::instance();
			}
		}

	}

}

<?php
/**
 * Motta Addons Modules functions and definitions.
 *
 * @package Motta
 */

namespace Motta\Addons\Modules\Sticky_Add_To_Cart;


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

		add_action('template_redirect', array( $this, 'product_single'));

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
			'Motta\Addons\Modules\Sticky_Add_To_Cart\Frontend'      => MOTTA_ADDONS_DIR . 'modules/sticky-add-to-cart/frontend.php',
			'Motta\Addons\Modules\Sticky_Add_To_Cart\Settings'    	=> MOTTA_ADDONS_DIR . 'modules/sticky-add-to-cart/settings.php',
		] );
	}

	/**
	 * Single Product
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_single() {
		if ( get_option( 'motta_sticky_add_to_cart_toggle', 'yes' ) == 'yes' && is_singular('product') ) {
			\Motta\Addons\Modules\Sticky_Add_To_Cart\Frontend::instance();
		}
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
			\Motta\Addons\Modules\Sticky_Add_To_Cart\Settings::instance();
		}
	}

}

<?php

namespace Motta\Addons\Modules\Products_Filter;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main class of plugin for admin
 */
class Settings {

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
		// Include plugin files
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );

	}

	/**
	 * Register widgets
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function register_widgets() {
		\Motta\Addons\Auto_Loader::register( [
			'Motta\Addons\Modules\Products_Filter\Widget'    => MOTTA_ADDONS_DIR . 'modules/products-filter/widget.php',
		] );

		if ( class_exists( 'WooCommerce' ) ) {
			register_widget( new \Motta\Addons\Modules\Products_Filter\Widget() );
		}
	}
}
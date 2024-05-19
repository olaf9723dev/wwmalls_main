<?php
/**
 * Motta Addons init
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Motta
 */

namespace Motta;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Motta Addons init
 *
 * @since 1.0.0
 */
class Addons {

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
		add_action( 'plugins_loaded', array( $this, 'load_templates' ) );
	}

	/**
	 * Load Templates
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function load_templates() {
		$this->includes();
		spl_autoload_register( '\Motta\Addons\Auto_Loader::load' );

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
		// Auto Loader
		require_once MOTTA_ADDONS_DIR . 'autoloader.php';
		\Motta\Addons\Auto_Loader::register( [
			'Motta\Addons\Helper'         => MOTTA_ADDONS_DIR . 'inc/helper.php',
			'Motta\Addons\Theme_Builder'  => MOTTA_ADDONS_DIR . 'inc/backend/theme-builder.php',
			'Motta\Addons\Theme_Settings' => MOTTA_ADDONS_DIR . 'inc/backend/theme-settings.php',
			'Motta\Addons\User'       	  => MOTTA_ADDONS_DIR . 'inc/backend/user.php',
			'Motta\Addons\Importer'      => MOTTA_ADDONS_DIR . 'inc/backend/importer.php',
			'Motta\Addons\Product_Brands' => MOTTA_ADDONS_DIR . 'inc/backend/product-brand.php',
			'Motta\Addons\Widgets'        => MOTTA_ADDONS_DIR . 'inc/widgets/widgets.php',
			'Motta\Addons\Modules'        => MOTTA_ADDONS_DIR . 'modules/modules.php',
			'Motta\Addons\Elementor'      => MOTTA_ADDONS_DIR . 'inc/elementor/elementor.php',
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
		// Before init action.
		do_action( 'before_motta_init' );

		$this->get( 'theme_builder' );
		$this->get( 'theme_settings' );
		$this->get( 'user' );
		$this->get( 'product-brand' );

		// Widgets
		$this->get( 'widgets' );

		// Modules
		$this->get( 'modules' );

		// Elementor
		$this->get( 'elementor' );

		$this->get( 'importer' );

		add_action( 'after_setup_theme', array( $this, 'addons_init' ), 20 );

		// Init action.
		do_action( 'after_motta_init' );
	}

	/**
	 * Get Motta Addons Class instance
	 *
	 * @since 1.0.0
	 *
	 * @return object
	 */
	public function get( $class ) {
		switch ( $class ) {
			case 'theme_builder':
				if ( defined( 'ELEMENTOR_VERSION' ) ) {
					return \Motta\Addons\Theme_Builder::instance();
				}
				break;

			case 'theme_settings':
				return \Motta\Addons\Theme_Settings::instance();
				break;

			case 'user':
				return \Motta\Addons\User::instance();
				break;

			case 'product-brand':
				return \Motta\Addons\Product_Brands::instance();
				break;

			case 'importer':
				if( is_admin() ) {
					return \Motta\Addons\Importer::instance();
				}
				break;

			case 'widgets':
				return \Motta\Addons\Widgets::instance();
				break;

			case 'modules':
				return \Motta\Addons\Modules::instance();
				break;

			case 'elementor':
				if ( defined( 'ELEMENTOR_VERSION' ) ) {
					return \Motta\Addons\Elementor::instance();
				}
				break;

			default:
				break;
		}
	}

	/**
	 * Get Motta Addons Language
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function addons_init() {
		load_plugin_textdomain( 'motta-addons', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
}

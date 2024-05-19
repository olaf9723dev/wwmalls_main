<?php
/**
 * Motta Addons Modules functions and definitions.
 *
 * @package Motta
 */

namespace Motta\Addons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Addons Modules
 */
class Modules {

	/**
	 * Instance
	 *
	 * @var $instance
	 */
	private static $instance;

	/**
	 * Registered modules.
	 *
	 * Holds the list of all the registered modules.
	 *
	 * @var array
	 */
	private $modules = [];

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
		$this->register( 'mega-menu' );

		$this->includes();
		$this->add_actions();

		add_action( 'init', [ $this, 'activate' ] );

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

			//Size-Guide
			'Motta\Addons\Modules\Size_Guide\Module' 			    => MOTTA_ADDONS_DIR . 'modules/size-guide/module.php',

			//Product-Tab
			'Motta\Addons\Modules\Product_Tabs\Module' 			    => MOTTA_ADDONS_DIR . 'modules/product-tabs/module.php',

			//Buy Now
			'Motta\Addons\Modules\Buy_Now\Module' 				    => MOTTA_ADDONS_DIR . 'modules/buy-now/module.php',

			//Sticky Add To Cart
			'Motta\Addons\Modules\Sticky_Add_To_Cart\Module' 	    => MOTTA_ADDONS_DIR . 'modules/sticky-add-to-cart/module.php',

			// Filter
			'Motta\Addons\Modules\Products_Filter\Module' 		    => MOTTA_ADDONS_DIR . 'modules/products-filter/module.php',

			// Shortcodes
			'Motta\Addons\Modules\Shortcodes' 				        => MOTTA_ADDONS_DIR . 'modules/shortcodes.php',
			'Motta\Addons\Modules\Product_Deals' 			        => MOTTA_ADDONS_DIR . 'modules/product-deals/module.php',

			// Product Bought Together
			'Motta\Addons\Modules\Product_Bought_Together\Module' 	=> MOTTA_ADDONS_DIR . 'modules/product-bought-together/module.php',

			'Motta\Addons\Modules\Variation_Images\Module'    		=> MOTTA_ADDONS_DIR . 'modules/variation-images/module.php',
			'Motta\Addons\Modules\Free_Shipping_Bar\Module'    		=> MOTTA_ADDONS_DIR . 'modules/free-shipping-bar/module.php',
			'Motta\Addons\Modules\Catalog_Mode\Module'    		    => MOTTA_ADDONS_DIR . 'modules/catalog-mode/module.php',

			//Popup
			'Motta\Addons\Modules\Popup\Module' 			    	=> MOTTA_ADDONS_DIR . 'modules/popup/module.php',

			//Help Center
			'Motta\Addons\Modules\Help_Center\Module' 			    	=> MOTTA_ADDONS_DIR . 'modules/help-center/module.php',
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
		\Motta\Addons\Modules\Shortcodes::instance();
		\Motta\Addons\Modules\Product_Deals\Module::instance();
		\Motta\Addons\Modules\Size_Guide\Module::instance();
		\Motta\Addons\Modules\Product_Tabs\Module::instance();
		\Motta\Addons\Modules\Sticky_Add_To_Cart\Module::instance();
		\Motta\Addons\Modules\Buy_Now\Module::instance();
		\Motta\Addons\Modules\Products_Filter\Module::instance();
		\Motta\Addons\Modules\Product_Bought_Together\Module::instance();
		\Motta\Addons\Modules\Variation_Images\Module::instance();
		if( ! class_exists( 'WCFMmp' ) && ! class_exists( 'WeDevs_Dokan' )) {
			\Motta\Addons\Modules\Free_Shipping_Bar\Module::instance();
		}
		\Motta\Addons\Modules\Catalog_Mode\Module::instance();

		if( empty(get_option('motta_popup_disable')) ) {
			\Motta\Addons\Modules\Popup\Module::instance();
		}
		if( empty( get_option('help_center_disable') )) {
			\Motta\Addons\Modules\Help_Center\Module::instance();
		}
	}

	/**
	 * Register a module
	 *
	 * @param string $module_name
	 */
	public function register( $module_name ) {
		if ( ! array_key_exists( $module_name, $this->modules ) ) {
			$this->modules[ $module_name ] = null;
		}
	}

	/**
	 * Deregister a moudle.
	 * Only allow deregistering a module if it is not activated.
	 *
	 * @param string $module_name
	 */
	public function deregister( $module_name ) {
		if ( ! array_key_exists( $module_name, $this->modules ) && empty( $this->modules[ $module_name ] ) ) {
			unset( $this->modules[ $module_name ] );
		}
	}

	/**
	 * Active all registered modules
	 *
	 * @return void
	 */
	public function activate() {
		foreach ( $this->modules as $module_name => $instance ) {
			if ( ! empty( $instance ) ) {
				continue;
			}

			$classname = $this->get_module_classname( $module_name );

			if ( $classname ) {
				$this->modules[ $module_name ] = $classname::instance();
			}
		}

	}

	/**
	 * Get module class name
	 *
	 * @param string $module_name
	 * @return string
	 */
	public function get_module_classname( $module_name ) {
		$class_name = str_replace( '-', ' ', $module_name );
		$class_name = str_replace( ' ', '_', ucwords( $class_name ) );
		$class_name = 'Motta\\Addons\\Modules\\' . $class_name . '\\Module';

		return $class_name;
	}
}

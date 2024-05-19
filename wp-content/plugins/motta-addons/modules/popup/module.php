<?php
/**
 * Motta Addons Modules functions and definitions.
 *
 * @package Motta
 */

namespace Motta\Addons\Modules\Popup;

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
		add_action('init', array( $this, 'actions'));
		add_action('template_redirect', array( $this, 'content'));
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
			'Motta\Addons\Modules\Popup\FrontEnd'       => MOTTA_ADDONS_DIR . 'modules/popup/frontend.php',
			'Motta\Addons\Modules\Popup\Settings'    	=> MOTTA_ADDONS_DIR . 'modules/popup/settings.php',
			'Motta\Addons\Modules\Popup\Elementor_Settings'    	=> MOTTA_ADDONS_DIR . 'modules/popup/elementor-settings.php',
			'Motta\Addons\Modules\Popup\Post_Type'    	=> MOTTA_ADDONS_DIR . 'modules/popup/post-type.php',
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
		\Motta\Addons\Modules\Popup\Post_Type::instance();

		if( is_admin() ) {
			\Motta\Addons\Modules\Popup\Settings::instance();
			if( class_exists('Elementor\Core\Base\Module') ) {
				\Motta\Addons\Modules\Popup\Elementor_Settings::instance();
			}
		}

	}

	/**
	 * Single Product
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function content() {
		\Motta\Addons\Modules\Popup\FrontEnd::instance();
	}
}

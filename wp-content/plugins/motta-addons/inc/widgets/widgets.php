<?php
/**
 * Load and register widgets
 *
 * @package Motta
 */

namespace Motta\Addons;
/**
 * Motta theme init
 */
class Widgets {

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
		$this->includes();
		$this->add_actions();
	}

	/**
	 * Include Files
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function includes() {
		\Motta\Addons\Auto_Loader::register( [
			'Motta\Addons\Widgets\User_Bio'    => MOTTA_ADDONS_DIR . 'inc/widgets/user-bio.php',
			'Motta\Addons\Widgets\Posts_Slider'    => MOTTA_ADDONS_DIR . 'inc/widgets/posts-slider.php',
			'Motta\Addons\Widgets\Social_Links'    => MOTTA_ADDONS_DIR . 'inc/widgets/socials.php',
			'Motta\Addons\Widgets\Instagram_Widget'    => MOTTA_ADDONS_DIR . 'inc/widgets/instagram.php',
			'Motta\Addons\Widgets\Newsletter_Widget'    => MOTTA_ADDONS_DIR . 'inc/widgets/newsletter.php',
			'Motta\Addons\Widgets\Popular_Posts_Widget'    => MOTTA_ADDONS_DIR . 'inc/widgets/popular-posts.php',
			'Motta\Addons\Widgets\IconBox'    => MOTTA_ADDONS_DIR . 'inc/widgets/icon-box/icon-box.php',
		] );
	}

	/**
	 * Add Actions
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_actions() {
		register_widget( new \Motta\Addons\Widgets\User_Bio() );
		register_widget( new \Motta\Addons\Widgets\Posts_Slider() );
		register_widget( new \Motta\Addons\Widgets\Social_Links() );
		register_widget( new \Motta\Addons\Widgets\Instagram_Widget() );
		register_widget( new \Motta\Addons\Widgets\Newsletter_Widget() );
		register_widget( new \Motta\Addons\Widgets\Popular_Posts_Widget() );
		register_widget( new \Motta\Addons\Widgets\IconBox() );
	}
}
<?php
/**
 * Hooks for User
 *
 * @package Motta
 */

namespace Motta\Addons;


/**
 * Class Importter
 */
class User {

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
		add_filter( 'user_contactmethods', array( $this, 'user_contact_methods' ) );
	}

	/**
	 * Importer the demo content
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function user_contact_methods($methods) {
		$methods['facebook']  = esc_html__( 'Facebook', 'motta-addons' );
		$methods['twitter']   = esc_html__( 'Twitter', 'motta-addons' );
		$methods['linkedin']  = esc_html__( 'Linkedin', 'motta-addons' );
		$methods['pinterest'] = esc_html__( 'Pinterest', 'motta-addons' );
		$methods['instagram'] = esc_html__( 'Instagram', 'motta-addons' );

		return $methods;
	}
}

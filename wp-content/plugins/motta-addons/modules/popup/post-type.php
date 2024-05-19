<?php

namespace Motta\Addons\Modules\Popup;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main class of plugin for admin
 */
class Post_Type  {

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

	const POST_TYPE     = 'motta_popup';
	const TAXONOMY_TAB_TYPE     = 'motta_popup_type';


	/**
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
			// Make sure the post types are loaded for imports
		add_action( 'import_start', array( $this, 'register_post_type' ) );
		$this->register_post_type();

	}

	/**
	 * Register popup post type
     *
	 * @since 1.0.0
     *
     * @return void
	 */
	public function register_post_type() {
		if(post_type_exists(self::POST_TYPE)) {
			return;
		}

		register_post_type( self::POST_TYPE, array(
			'description'         => esc_html__( 'Theme Popup', 'motta-addons' ),
			'labels'              => array(
				'name'                  => esc_html__( 'Theme Popup', 'motta-addons' ),
				'singular_name'         => esc_html__( 'Theme Popup', 'motta-addons' ),
				'menu_name'             => esc_html__( 'Theme Popup', 'motta-addons' ),
				'all_items'             => esc_html__( 'Theme Popup', 'motta-addons' ),
				'add_new'               => esc_html__( 'Add New', 'motta-addons' ),
				'add_new_item'          => esc_html__( 'Add New Popup', 'motta-addons' ),
				'edit_item'             => esc_html__( 'Edit Popup', 'motta-addons' ),
				'new_item'              => esc_html__( 'New Popup', 'motta-addons' ),
				'view_item'             => esc_html__( 'View Popup', 'motta-addons' ),
				'search_items'          => esc_html__( 'Search popup', 'motta-addons' ),
				'not_found'             => esc_html__( 'No popup found', 'motta-addons' ),
				'not_found_in_trash'    => esc_html__( 'No popup found in Trash', 'motta-addons' ),
				'filter_items_list'     => esc_html__( 'Filter popups list', 'motta-addons' ),
				'items_list_navigation' => esc_html__( 'Popup list navigation', 'motta-addons' ),
				'items_list'            => esc_html__( 'Popup list', 'motta-addons' ),
			),
			'supports'            => array( 'title', 'editor', 'elementor' ),
			'public'              => true,
			'rewrite'             => false,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'exclude_from_search' => true,
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'show_in_menu'        => 'themes.php',
		) );

	}


}
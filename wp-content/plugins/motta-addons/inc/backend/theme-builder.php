<?php
/**
 * Register footer builder
 */

namespace Motta\Addons;

class Theme_Builder {

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
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_admin_menu' ), 50 );

		// Make sure the post types are loaded for imports
		add_action( 'import_start', array( $this, 'register_post_type' ) );

		// Register custom post type and custom taxonomy
		add_action( 'init', array( $this, 'register_post_type' ) );

		add_filter( 'single_template', array( $this, 'load_canvas_template' ) );

	}

	/**
	 * Register portfolio post type
	 */
	public function register_post_type() {
		// Footer Builder
		$labels = array(
			'name'               => esc_html__( 'Footer Builder Template', 'motta-addons' ),
			'singular_name'      => esc_html__( 'Elementor Footer', 'motta-addons' ),
			'menu_name'          => esc_html__( 'Footer Template', 'motta-addons' ),
			'name_admin_bar'     => esc_html__( 'Elementor Footer', 'motta-addons' ),
			'add_new'            => esc_html__( 'Add New', 'motta-addons' ),
			'add_new_item'       => esc_html__( 'Add New Footer', 'motta-addons' ),
			'new_item'           => esc_html__( 'New Footer Template', 'motta-addons' ),
			'edit_item'          => esc_html__( 'Edit Footer Template', 'motta-addons' ),
			'view_item'          => esc_html__( 'View Footer Template', 'motta-addons' ),
			'all_items'          => esc_html__( 'All Elementor Footer', 'motta-addons' ),
			'search_items'       => esc_html__( 'Search Footer Templates', 'motta-addons' ),
			'parent_item_colon'  => esc_html__( 'Parent Footer Templates:', 'motta-addons' ),
			'not_found'          => esc_html__( 'No Footer Templates found.', 'motta-addons' ),
			'not_found_in_trash' => esc_html__( 'No Footer Templates found in Trash.', 'motta-addons' ),
		);

		$args = array(
			'labels'              => $labels,
			'public'              => true,
			'rewrite'             => false,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'exclude_from_search' => true,
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'menu_icon'           => 'dashicons-editor-kitchensink',
			'supports'            => array( 'title', 'editor', 'elementor' ),
		);


		if ( ! post_type_exists( 'motta_footer' ) ) {
			register_post_type( 'motta_footer', $args );
		}
	}

	public function register_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=elementor_library',
			esc_html__( 'Footer Builder', 'motta-addons' ),
			esc_html__( 'Footer Builder', 'motta-addons' ),
			'edit_pages',
			'edit.php?post_type=motta_footer'
		);

	}


	function load_canvas_template( $single_template ) {
		global $post;

		if ( 'motta_footer' == $post->post_type ) {

			return ELEMENTOR_PATH . '/modules/page-templates/templates/canvas.php';
		}

		return $single_template;
	}
}
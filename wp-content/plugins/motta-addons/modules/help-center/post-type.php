<?php

namespace Motta\Addons\Modules\Help_Center;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main class of plugin for admin
 */
class Post_Type  {

	const POST_TYPE         = 'motta_help_article';
	const TAXONOMY_TAB_TYPE = 'motta_help_cat';

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
		// Make sure the post types are loaded for imports.
		add_action( 'import_start', array( $this, 'register_post_type' ) );
		$this->register_post_type();
	}

	/**
	 * Register article post type
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
			'description'         => esc_html__( 'Help Center', 'motta-addons' ),
			'labels'              => array(
				'name'                  => esc_html__( 'Help Center', 'motta-addons' ),
				'singular_name'         => esc_html__( 'Help Center', 'motta-addons' ),
				'menu_name'             => esc_html__( 'Help Center', 'motta-addons' ),
				'all_items'             => esc_html__( 'Articles', 'motta-addons' ),
				'add_new'               => esc_html__( 'Add New', 'motta-addons' ),
				'add_new_item'          => esc_html__( 'Add New Article', 'motta-addons' ),
				'edit_item'             => esc_html__( 'Edit Article', 'motta-addons' ),
				'new_item'              => esc_html__( 'New Article', 'motta-addons' ),
				'view_item'             => esc_html__( 'View Article', 'motta-addons' ),
				'search_items'          => esc_html__( 'Search Articles', 'motta-addons' ),
				'not_found'             => esc_html__( 'No articles found', 'motta-addons' ),
				'not_found_in_trash'    => esc_html__( 'No articles found in Trash', 'motta-addons' ),
				'filter_items_list'     => esc_html__( 'Filter articles list', 'motta-addons' ),
				'items_list_navigation' => esc_html__( 'Articles list navigation', 'motta-addons' ),
				'items_list'            => esc_html__( 'Articles list', 'motta-addons' ),
			),
			'supports'            => array( 'title', 'editor', 'elementor' ),
			'public'              => true,
			'menu_icon'           => 'dashicons-editor-help',
			'rewrite'           => array(
				'slug'         => 'help-article',
				'with_front'   => false,
			),
		) );

		register_taxonomy(
			self::TAXONOMY_TAB_TYPE,
			array( self::POST_TYPE ),
			array(
				'hierarchical' => true,
				'public'       => true,
				'query_var' => '',
				'label'        => _x( 'Categories', 'Taxonomy name', 'motta-addons' ),
				'rewrite'      => array(
					'slug' => 'help-category',
					'with_front' => false,
					'hierarchical' => true,
				),
			)
		);
	}
}
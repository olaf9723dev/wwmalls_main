<?php
/**
 * Hooks for importer
 *
 * @package Motta
 */

namespace Motta\Addons;


/**
 * Class Importter
 */
class Importer {

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
		add_filter( 'soo_demo_packages', array( $this, 'importer' ), 20 );
		add_action( 'soodi_before_import_content', array( $this,'import_product_attributes') );
		add_action( 'soodi_before_import_content', array( $this,'enable_svg_upload') );
		add_action( 'soodi_after_setup_pages', array( $this,'disable_svg_upload') );
		add_action('soodi_after_setup_pages', array( $this,'update_page_option') );
	}

	/**
	 * Importer the demo content
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function importer() {
		return array(
			array(
				'name'       => 'Home v1 - Marketplace',
				'content'     => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev1/demo-content.xml',
				'widgets'     => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev1/widgets.wie',
				'customizer' => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev1/customizer.dat',
				'preview'   => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev1/preview.jpg',
				'pages'      => array(
					'front_page' => 'Home v1',
					'blog'       => 'Blog',
					'help_center_page' => 'help-center',
					'order_tracking_page' => 'tracking-order'
				),
				'menus'      => array(
					'primary-menu' 		=> 'primary-menu',
					'secondary-menu' 	=> 'secondary-menu',
					'category-menu' 	=> 'category-menu',
					'socials' 		=> 'social-menu',
				),
				'options'    => array(
					'shop_catalog_image_size'   => array(
						'width'  => 370,
						'height' => 370,
						'crop'   => 1,
					),
					'shop_single_image_size'    => array(
						'width'  => 670,
						'height' => 670,
						'crop'   => 1,
					),
					'shop_thumbnail_image_size' => array(
						'width'  => 70,
						'height' => 70,
						'crop'   => 1,
					),
				),
			),
			array(
				'name'       => 'Home v2 - Retail',
				'content'     => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev2/demo-content.xml',
				'widgets'     => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev2/widgets.wie',
				'customizer' => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev2/customizer.dat',
				'preview'   => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev2/preview.jpg',
				'pages'      => array(
					'front_page' => 'Home v2',
					'blog'       => 'Blog',
					'help_center_page' => 'help-center',
					'order_tracking_page' => 'tracking-order'
				),
				'menus'      => array(
					'primary-menu' 		=> 'primary-menu-v2',
					'secondary-menu' 	=> 'secondary-menu',
					'category-menu' 	=> 'category-menu-v2',
					'socials' 		=> 'social-menu',
				),
				'options'    => array(
					'shop_catalog_image_size'   => array(
						'width'  => 370,
						'height' => 370,
						'crop'   => 1,
					),
					'shop_single_image_size'    => array(
						'width'  => 670,
						'height' => 670,
						'crop'   => 1,
					),
					'shop_thumbnail_image_size' => array(
						'width'  => 70,
						'height' => 70,
						'crop'   => 1,
					),
				),
			),
			array(
				'name'       => 'Home v3 - Mega Market',
				'content'     => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev3/demo-content.xml',
				'widgets'     => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev3/widgets.wie',
				'customizer' => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev3/customizer.dat',
				'preview'   => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev3/preview.jpg',
				'pages'      => array(
					'front_page' => 'Home v3',
					'blog'       => 'Blog',
					'help_center_page' => 'help-center',
					'order_tracking_page' => 'tracking-order'
				),
				'menus'      => array(
					'primary-menu' 		=> 'primary-menu',
					'secondary-menu' 	=> 'secondary-menu-home-3',
					'category-menu' 	=> 'category-menu-home-3',
					'socials' 		=> 'social-menu',
				),
				'options'    => array(
					'shop_catalog_image_size'   => array(
						'width'  => 370,
						'height' => 370,
						'crop'   => 1,
					),
					'shop_single_image_size'    => array(
						'width'  => 670,
						'height' => 670,
						'crop'   => 1,
					),
					'shop_thumbnail_image_size' => array(
						'width'  => 70,
						'height' => 70,
						'crop'   => 1,
					),
				),
			),
			array(
				'name'       => 'Home v4 - Multi vendor',
				'content'     => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev4/demo-content.xml',
				'widgets'     => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev4/widgets.wie',
				'customizer' => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev4/customizer.dat',
				'preview'   => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev4/preview.jpg',
				'pages'      => array(
					'front_page' => 'Home v4',
					'blog'       => 'Blog',
					'help_center_page' => 'help-center',
					'order_tracking_page' => 'tracking-order'
				),
				'menus'      => array(
					'primary-menu' 		=> 'primary-menu-home-4',
					'secondary-menu' 	=> 'secondary-menu',
					'category-menu' 	=> 'category-menu',
					'socials' 		=> 'social-menu',
				),
				'options'    => array(
					'shop_catalog_image_size'   => array(
						'width'  => 370,
						'height' => 370,
						'crop'   => 1,
					),
					'shop_single_image_size'    => array(
						'width'  => 670,
						'height' => 670,
						'crop'   => 1,
					),
					'shop_thumbnail_image_size' => array(
						'width'  => 70,
						'height' => 70,
						'crop'   => 1,
					),
				),
			),
			array(
				'name'       => 'Home v5 - Supper Market',
				'content'     => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev5/demo-content.xml',
				'widgets'     => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev5/widgets.wie',
				'customizer' => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev5/customizer.dat',
				'preview'   => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev5/preview.jpg',
				'pages'      => array(
					'front_page' => 'Home v5',
					'blog'       => 'Blog',
					'help_center_page' => 'help-center',
					'order_tracking_page' => 'tracking-order'
				),
				'menus'      => array(
					'primary-menu' 		=> 'primary-menu',
					'secondary-menu' 	=> 'secondary-menu-home-5',
					'category-menu' 	=> 'category-menu',
					'socials' 		=> 'social-menu',
				),
				'options'    => array(
					'shop_catalog_image_size'   => array(
						'width'  => 370,
						'height' => 370,
						'crop'   => 1,
					),
					'shop_single_image_size'    => array(
						'width'  => 670,
						'height' => 670,
						'crop'   => 1,
					),
					'shop_thumbnail_image_size' => array(
						'width'  => 70,
						'height' => 70,
						'crop'   => 1,
					),
				),
			),
			array(
				'name'       => 'Home v6 - Electronics',
				'content'     => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev6/demo-content.xml',
				'widgets'     => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev6/widgets.wie',
				'customizer' => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev6/customizer.dat',
				'preview'   => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev6/preview.jpg',
				'pages'      => array(
					'front_page' => 'home-v6',
					'blog'       => 'blog',
					'help_center_page' => 'help-center',
					'order_tracking_page' => 'tracking-order'
				),
				'menus'      => array(
					'primary-menu' 		=> 'primary-menu',
					'secondary-menu' 	=> 'secondary-menu',
					'category-menu' 	=> 'category-menu',
					'socials' 		=> 'social-menu',
				),
				'options'    => array(
					'shop_catalog_image_size'   => array(
						'width'  => 370,
						'height' => 370,
						'crop'   => 1,
					),
					'shop_single_image_size'    => array(
						'width'  => 670,
						'height' => 670,
						'crop'   => 1,
					),
					'shop_thumbnail_image_size' => array(
						'width'  => 70,
						'height' => 70,
						'crop'   => 1,
					),
				),
			),
			array(
				'name'       => 'Home v7 - Electronics',
				'content'     => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev7/demo-content.xml',
				'widgets'     => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev7/widgets.wie',
				'customizer' => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev7/customizer.dat',
				'preview'   => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev7/preview.jpg',
				'pages'      => array(
					'front_page' => 'home-v7',
					'blog'       => 'blog',
					'help_center_page' => 'help-center',
					'order_tracking_page' => 'tracking-order'
				),
				'menus'      => array(
					'primary-menu' 		=> 'primary-menu',
					'secondary-menu' 	=> 'secondary-menu',
					'category-menu' 	=> 'category-menu',
					'socials' 		=> 'social-menu',
				),
				'options'    => array(
					'shop_catalog_image_size'   => array(
						'width'  => 370,
						'height' => 370,
						'crop'   => 1,
					),
					'shop_single_image_size'    => array(
						'width'  => 670,
						'height' => 670,
						'crop'   => 1,
					),
					'shop_thumbnail_image_size' => array(
						'width'  => 70,
						'height' => 70,
						'crop'   => 1,
					),
				),
			),
			array(
				'name'       => 'Home v8 - Electronics',
				'content'     => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev8/demo-content.xml',
				'widgets'     => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev8/widgets.wie',
				'customizer' => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev8/customizer.dat',
				'preview'   => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev8/preview.jpg',
				'pages'      => array(
					'front_page' => 'home-v8',
					'blog'       => 'blog',
					'help_center_page' => 'help-center',
					'order_tracking_page' => 'tracking-order'
				),
				'menus'      => array(
					'primary-menu' 		=> 'primary-menu-header-v8',
					'secondary-menu' 	=> 'secondary-menu',
					'category-menu' 	=> 'category-menu',
					'socials' 		=> 'social-menu',
				),
				'options'    => array(
					'shop_catalog_image_size'   => array(
						'width'  => 370,
						'height' => 370,
						'crop'   => 1,
					),
					'shop_single_image_size'    => array(
						'width'  => 670,
						'height' => 670,
						'crop'   => 1,
					),
					'shop_thumbnail_image_size' => array(
						'width'  => 70,
						'height' => 70,
						'crop'   => 1,
					),
				),
			),
			array(
				'name'       => 'Home v9 - Electronics',
				'content'     => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev9/demo-content.xml',
				'widgets'     => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev9/widgets.wie',
				'customizer' => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev9/customizer.dat',
				'preview'   => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev9/preview.jpg',
				'pages'      => array(
					'front_page' => 'home-v9',
					'blog'       => 'blog',
					'help_center_page' => 'help-center',
					'order_tracking_page' => 'tracking-order'
				),
				'menus'      => array(
					'primary-menu' 		=> 'global-menu',
					'secondary-menu' 	=> 'secondary-menu',
					'category-menu' 	=> 'category-menu',
					'socials' 		=> 'social-menu',
				),
				'options'    => array(
					'shop_catalog_image_size'   => array(
						'width'  => 370,
						'height' => 370,
						'crop'   => 1,
					),
					'shop_single_image_size'    => array(
						'width'  => 670,
						'height' => 670,
						'crop'   => 1,
					),
					'shop_thumbnail_image_size' => array(
						'width'  => 70,
						'height' => 70,
						'crop'   => 1,
					),
				),
			),
			array(
				'name'       => 'Home v10 - Electronics',
				'content'     => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev10/demo-content.xml',
				'widgets'     => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev10/widgets.wie',
				'customizer' => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev10/customizer.dat',
				'preview'   => 'https://raw.githubusercontent.com/uixthemeswp/motta/main/impoter/homev10/preview.jpg',
				'pages'      => array(
					'front_page' => 'home-v10',
					'blog'       => 'blog',
					'help_center_page' => 'help-center',
					'order_tracking_page' => 'tracking-order'
				),
				'menus'      => array(
					'primary-menu' 		=> 'primary-menu',
					'secondary-menu' 	=> 'secondary-menu',
					'category-menu' 	=> 'category-menu',
					'socials' 		=> 'social-menu',
				),
				'options'    => array(
					'shop_catalog_image_size'   => array(
						'width'  => 370,
						'height' => 370,
						'crop'   => 1,
					),
					'shop_single_image_size'    => array(
						'width'  => 670,
						'height' => 670,
						'crop'   => 1,
					),
					'shop_thumbnail_image_size' => array(
						'width'  => 70,
						'height' => 70,
						'crop'   => 1,
					),
				),
			),
		);
	}

	/**
	 * Prepare product attributes before import demo content
	 *
	 * @param $file
	 */
	function import_product_attributes( $file ) {
		global $wpdb;

		if ( ! class_exists( 'WXR_Parser' ) ) {
			if ( ! file_exists( WP_PLUGIN_DIR . '/soo-demo-importer/includes/parsers.php' ) ) {
				return;
			}

			require_once WP_PLUGIN_DIR . '/soo-demo-importer/includes/parsers.php';
		}

		$parser      = new \WXR_Parser();
		$import_data = $parser->parse( $file );

		if ( empty( $import_data ) || is_wp_error( $import_data ) ) {
			return;
		}

		if ( isset( $import_data['posts'] ) ) {
			$posts = $import_data['posts'];

			if ( $posts && sizeof( $posts ) > 0 ) {
				foreach ( $posts as $post ) {
					if ( 'product' === $post['post_type'] ) {
						if ( ! empty( $post['terms'] ) ) {
							foreach ( $post['terms'] as $term ) {
								if ( strstr( $term['domain'], 'pa_' ) ) {
									if ( ! taxonomy_exists( $term['domain'] ) ) {
										$attribute_name = wc_sanitize_taxonomy_name( str_replace( 'pa_', '', $term['domain'] ) );

										// Create the taxonomy
										if ( ! in_array( $attribute_name, wc_get_attribute_taxonomies() ) ) {
											$attribute = array(
												'attribute_label'   => $attribute_name,
												'attribute_name'    => $attribute_name,
												'attribute_type'    => 'select',
												'attribute_orderby' => 'menu_order',
												'attribute_public'  => 0
											);
											$wpdb->insert( $wpdb->prefix . 'woocommerce_attribute_taxonomies', $attribute );
											delete_transient( 'wc_attribute_taxonomies' );
										}

										// Register the taxonomy now so that the import works!
										register_taxonomy(
											$term['domain'],
											apply_filters( 'woocommerce_taxonomy_objects_' . $term['domain'], array( 'product' ) ),
											apply_filters( 'woocommerce_taxonomy_args_' . $term['domain'], array(
												'hierarchical' => true,
												'show_ui'      => false,
												'query_var'    => true,
												'rewrite'      => false,
											) )
										);
									}
								}
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Enable svg upload
	 *
	 * @param $file
	 */
	function enable_svg_upload() {
		add_filter('upload_mimes', array($this, 'svg_upload_types'));
	}

	/**
	 * Enable svg upload
	 *
	 * @param $file
	 */
	function svg_upload_types($file_types) {
		$new_filetypes = array();
		$new_filetypes['svg'] = 'image/svg+xml';
		$file_types = array_merge($file_types, $new_filetypes );
		return $file_types;
	}

	/**
	 * Enable svg upload
	 *
	 * @param $file
	 */
	function disable_svg_upload() {
		remove_filter('upload_mimes', array($this, 'svg_upload_types'));
	}

	/**
	 * Update page option
	 *
	 * @param $file
	 */
	function update_page_option($demo) {
		if ( isset( $demo['help_center_page'] ) ) {
			$page = $this->get_page_by_slug( $demo['help_center_page'] );
			if ( $page ) {
				update_option( 'help_center_page_id', $page->ID );
			}
		}

		if ( isset( $demo['order_tracking_page'] ) ) {
			$page = $this->get_page_by_slug( $demo['order_tracking_page'] );
			if ( $page ) {
				update_option( 'order_tracking_page_id', $page->ID );
			}
		}
	}


	/**
	 * Get page by slug
	 *
	 * @param $page_slug
	 */
	public function get_page_by_slug($page_slug) {
		$args = array(
			'name'           => $page_slug,
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => 1
		);
		$posts = get_posts( $args );
		$post = $posts ? $posts[0] : '';
		wp_reset_postdata();

		return $post;
	}
}
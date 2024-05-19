<?php
/**
 * EmallShop functions and definitions
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 2.0
 */
 
/**
 * Define variables
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 2.0
 */
$theme = wp_get_theme('EmallShop');
define('EMALLSHOP_DIR',                  get_template_directory() );                  // Template directory
define('EMALLSHOP_URI',                  get_template_directory_uri() );              // Template directory uri
define('EMALLSHOP_FRAMEWORK',            EMALLSHOP_DIR . '/inc' );                    // Framework directory
define('EMALLSHOP_ADMIN',                EMALLSHOP_FRAMEWORK . '/admin' );            // Admin directory
define('EMALLSHOP_ADMIN_URI',            EMALLSHOP_URI . '/inc/admin' );              // Admin directory uri
define('EMALLSHOP_STYLES',               EMALLSHOP_URI . '/assets/css' );             // Css uri
define('EMALLSHOP_SCRIPTS',              EMALLSHOP_URI . '/assets/js' );              // Javascript uri
define('EMALLSHOP_IMAGES',               EMALLSHOP_URI . '/assets/images' );          // Image url
define('EMALLSHOP_ADMIN_IMAGES',         EMALLSHOP_ADMIN_URI . '/assets/images' );    // Admin images
define('EMALLSHOP_API', 				 'https://presslayouts.com/demo/api/' );
define('EMALLSHOP_VERSION',              $theme->get('Version') );                    // Get current version
define( 'EMALLSHOP_THEME_NAME', 		'EmallShop' );
define('EMALLSHOP_PREFIX', '_pl_' );

// Set the default content width.
$GLOBALS['content_width'] = 1200;

if ( ! function_exists( 'emallshop_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @since EmallShop 1.0
	 */
	function emallshop_setup() {
		
		load_theme_textdomain( 'emallshop', get_template_directory() . '/languages' );	
		load_theme_textdomain( 'emallshop', trailingslashit( WP_LANG_DIR ) . 'themes/' );
		load_theme_textdomain( 'emallshop', get_stylesheet_directory() . '/languages' );
		
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'editor-styles' );		
		add_theme_support( 'html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption') );
		add_theme_support( 'post-formats', array( 'image', 'video', 'quote', 'link', 'gallery', 'audio'	) );
		add_theme_support( 'post-thumbnails' );
		
		// Disable Widget block editor.
		if( apply_filters( 'emallshop_disable_widgets_block_editor', true ) ) {
			remove_theme_support( 'block-templates' );
			remove_theme_support( 'widgets-block-editor' );
		}
		
		add_editor_style( array( 'style.css') );
		set_post_thumbnail_size( 825, 510, true );
			
		
		register_nav_menus( array(
			'primary' => esc_html__( 'Primary Menu', 'emallshop' ),
			'vertical_menu' => esc_html__( 'Vertical Menu', 'emallshop' ),
			//'topbar_menu' => esc_html__( 'Topbar Menu', 'emallshop' ),
		) );

		add_editor_style( get_template_directory_uri() . '/assets/css/editor-style.css' );
	}
	add_action( 'after_setup_theme', 'emallshop_setup' );
endif;

/**
 * Register widget area.
 *
 * @since EmallShop 1.0
 */
function emallshop_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Widget Area', 'emallshop' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'emallshop' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Shop Page Widget Area', 'emallshop' ),
		'id'            => 'shop-page',
		'description'   => esc_html__( 'Add widgets here to appear in shop page sidebar.', 'emallshop' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Single Product Widget Area', 'emallshop' ),
		'id'            => 'single-product',
		'description'   => esc_html__( 'Add widgets here to appear in single product page sidebar.', 'emallshop' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Dokan Widget Area', 'emallshop' ),
		'id'            => 'dokan-widget-area',
		'description'   => esc_html__( 'Add widgets here to appear in dokan page sidebar.', 'emallshop' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Menu Widget Area 1', 'emallshop' ),
		'id'            => 'menu-widget-area-1',
		'description'   => esc_html__( 'Add widgets here to appear in your menu.', 'emallshop' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Menu Widget Area 2', 'emallshop' ),
		'id'            => 'menu-widget-area-2',
		'description'   => esc_html__( 'Add widgets here to appear in your menu.', 'emallshop' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Menu Widget Area 3', 'emallshop' ),
		'id'            => 'menu-widget-area-3',
		'description'   => esc_html__( 'Add widgets here to appear in your menu.', 'emallshop' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget Area 1', 'emallshop' ),
		'id'            => 'footer-widget-area-1',
		'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'emallshop' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget Area 2', 'emallshop' ),
		'id'            => 'footer-widget-area-2',
		'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'emallshop' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget Area 3', 'emallshop' ),
		'id'            => 'footer-widget-area-3',
		'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'emallshop' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget Area 4', 'emallshop' ),
		'id'            => 'footer-widget-area-4',
		'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'emallshop' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget Area 5', 'emallshop' ),
		'id'            => 'footer-widget-area-5',
		'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'emallshop' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	
}
add_action( 'widgets_init', 'emallshop_widgets_init' );

if ( ! function_exists( 'emallshop_enqueue_google_fonts' ) ) :
	/**
	 * Register Google fonts for EmallShop.
	 *
	 * @since EmallShop 2.0
	 */

	function emallshop_enqueue_google_fonts() {

		$default_google_fonts = 'Open Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800';

		if( ! class_exists('Redux') )
			wp_enqueue_style( 'emallshop-google-fonts', emallshop_get_fonts_url( $default_google_fonts ), array(), '2.0' );
	}

	add_action( 'wp_enqueue_scripts', 'emallshop_enqueue_google_fonts', 10000 );
	
endif;

/**
 * ------------------------------------------------------------------------------------------------
 * Get google fonts URL
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists(  'emallshop_get_fonts_url' ) ) {
	function emallshop_get_fonts_url( $fonts ) {
	    $font_url = '';
        $font_url = add_query_arg( 'family', urlencode( $fonts ), "//fonts.googleapis.com/css" );

	    return $font_url;
	}
}


if ( ! function_exists( 'emallshop_pagination_nav' ) ) :
	/**
	 * Add Post navigation.
	 *
	 * @since EmallShop 1.0
	 */
	function emallshop_pagination_nav() {
		global $wp_query;

		// Don't print empty markup if there's only one page.
		if ( $wp_query->max_num_pages < 2 )
			return;
		
		$big = 999999999; // need an unlikely integer
		$pages = paginate_links( array(
				'base' 			=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format' 		=> '?paged=%#%',
				'current' 		=> max( 1, get_query_var('paged') ),
				'total' 		=> $wp_query->max_num_pages,
				'prev_next'	 	=> true,
				'prev_text' 	=> '<i class="fa fa-chevron-left"></i>',
				'next_text' 	=> '<i class="fa fa-chevron-right"></i>',
				'type'  		=> 'array',
				'end_size'     	=> 2,
				'mid_size'     	=> 2
			) );
			if( is_array( $pages ) ) {
				$paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
				echo '<nav class="posts-navigation">';
				echo '<ul class="pagination">';
				foreach ( $pages as $page ) {
					echo "<li>".wp_kses_post($page)."</li>";
				}
			   echo '</ul></nav>';
			}
			?>
		<?php
	}
endif;

/**
 * Display descriptions in main navigation.
 *
 * @since EmallShop 1.0
 */
function emallshop_nav_description( $item_output, $item, $depth, $args ) {
	if ( 'primary' == $args->theme_location && $item->description ) {
		$item_output = str_replace( $args->link_after . '</a>', '<div class="menu-item-description">' . $item->description . '</div>' . $args->link_after . '</a>', $item_output );
	}

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'emallshop_nav_description', 10, 4 );

/**
 * Add a `screen-reader-text` class to the search form's submit button.
 *
 * @since EmallShop 1.0
 */
function emallshop_search_form_modify( $html ) {
	return str_replace( 'class="search-submit"', 'class="search-submit screen-reader-text"', $html );
}
add_filter( 'get_search_form', 'emallshop_search_form_modify' );

/**
 * Custom template tags for this theme.
 *
 * @since EmallShop 1.0
 */
require EMALLSHOP_DIR . '/inc/template-tags.php';
require EMALLSHOP_DIR . '/inc/customizer.php';
include EMALLSHOP_FRAMEWORK . '/theme-function.php';
require_once EMALLSHOP_FRAMEWORK . '/extras.php';
require_once EMALLSHOP_ADMIN.'/admin-function.php';
if (  file_exists ( EMALLSHOP_ADMIN.'/theme_options.php' )) {
	require_once EMALLSHOP_ADMIN.'/theme_options.php';
}
require_once EMALLSHOP_ADMIN.'/class-admin.php';
require_once EMALLSHOP_ADMIN.'/class-dashboard.php';
global $emallshop_options;

require_once EMALLSHOP_FRAMEWORK . '/classes/mega-menus.php';
require_once EMALLSHOP_FRAMEWORK . '/breadcrumbs.php';
require_once EMALLSHOP_FRAMEWORK . '/meta-boxes.php';
require_once EMALLSHOP_FRAMEWORK . '/classes/sidebar-generator-class.php';
new EMALLSHOP_SIDEBAR();
require_once EMALLSHOP_FRAMEWORK . '/thirdparty/tgm-plugin-activation/tgm-plugin-activation.php';
if( is_woocommerce_activated() ) {
	require_once EMALLSHOP_FRAMEWORK . '/integrations/woocommerce/hooks.php';
	require_once EMALLSHOP_FRAMEWORK . '/integrations/woocommerce/woo-functions.php';
	require_once EMALLSHOP_FRAMEWORK . '/integrations/woocommerce/template-tags.php';
}
if( is_dokan_activated() ) {
	require_once EMALLSHOP_FRAMEWORK . '/integrations/dokan/hooks.php';
	require_once EMALLSHOP_FRAMEWORK . '/integrations/dokan/dokan-functions.php';
}
require EMALLSHOP_DIR . '/inc/customize-style.php';
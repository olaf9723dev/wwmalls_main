<?php

require 'plugin-update-checker/plugin-update-checker.php';

$portalUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://estatik.net/wp-update-server/?action=get_metadata&slug=est-realtor',
	__FILE__, //Full path to the main plugin file or functions.php.
	'est-realtor',
    1
);

/**
 * Disable estatik major plugin update to version 4.
 *
 * @param $value
 * @return mixed
 */
//function es_disable_plugin_updates( $value ) {
//    if ( isset( $value ) && is_object( $value ) && ! empty( $value->response ) ) {
//        foreach ( $value->response as $plugin_path => $plugin_object ) {
//            if ( ! stristr( $plugin_path, 'estatik.php' ) ) continue;
//
//            if ( str_starts_with( $plugin_object->new_version, '4.' ) ) {
//                unset( $value->response[ $plugin_path ] );
//            }
//        }
//    }
//    return $value;
//}
//add_filter( 'site_transient_update_plugins', 'es_disable_plugin_updates' );

require_once 'inc/property-hooks.php';
require_once 'inc/listings-hooks.php';
require_once 'inc/page-hooks.php';
require_once 'inc/post-hooks.php';
require_once 'inc/blog-hooks.php';
require_once 'inc/lib/class-tgm-plugin-activation.php';

/**
 * Register plugin widgets.
 *
 * @return void
 */
function ert_register_widgets() {

	if ( class_exists( 'SiteOrigin_Widget' ) ) {

		if ( function_exists( 'es_get_property' ) ) {

            // Estatik4 slideshow implementation.
            if ( class_exists( 'Es_Properties_Slider_Widget' ) ) {
                require_once 'inc/widgets/estatik4/class-property-slider-widget.php';
                register_widget( 'Ert_Property_Slider_Widget' );
                unregister_widget( 'Es_Properties_Slider_Widget' );
            }

            // Estatik 3 slideshow implementation
			if ( class_exists( 'Es_Property_Slideshow_Widget' ) ) {
				require_once 'inc/widgets/class-property-slider-widget.php';
				register_widget( 'Ert_Property_Slider_Widget' );
				unregister_widget( 'Es_Property_Slideshow_Widget' );
			}

			require_once 'inc/widgets/class-so-taxonomy-widget.php';
			register_widget( 'Ert_Taxonomy_Widget' );
		}

		unregister_widget( 'SiteOrigin_Panels_Widgets_PostLoop' );
		unregister_widget( 'SiteOrigin_Widgets_Testimonials_Widget' );

		require_once 'inc/widgets/class-so-property-types-widget.php';
		register_widget( 'Ert_Property_Types_Widget' );

		require_once 'inc/widgets/class-so-performance-widget.php';
		register_widget( 'Ert_Performance_Widget' );

		require_once 'inc/widgets/class-so-testimonials-widget.php';
		register_widget( 'Ert_Testimonials_Widget' );

		require_once 'inc/widgets/class-title-widget.php';
		register_widget( 'Ert_Title_Widget' );

		require_once 'inc/widgets/class-so-about-widget.php';
		register_widget( 'Ert_About_Widget' );

		require_once 'inc/widgets/class-so-social-widget.php';
		register_widget( 'Ert_Social_Widget' );

		require_once 'inc/widgets/class-so-properties-sidebar-list-widget.php';
		register_widget( 'Ert_Properties_Sidebar_List_Widget' );

        require_once 'inc/widgets/class-so-property-item-widget.php';
        register_widget( 'Ert_Property_Item_Widget' );
	}

    require_once 'inc/widgets/class-social-widget.php';
    register_widget( 'Ert_WP_Social_Widget' );
}
add_action( 'widgets_init', 'ert_register_widgets' );

/**
 * Add admin theme menu item.
 *
 * @return void
 */
function ert_admin_menu() {

	add_menu_page(
		__( 'Theme Options', 'ert' ),
		__( 'Estatik Realtor Theme', 'ert' ),
		'manage_options',
		'ert-site-options',
		'ert_admin_page',
		get_template_directory_uri() . '/assets/images/estatik.svg',
		30
	);
}
add_action( 'admin_menu', 'ert_admin_menu' );

/**
 * Add inline styles for admin menu.
 *
 * @return void
 */
function ert_admin_menu_styles() { ?>
	<style>
		#toplevel_page_ert-site-options img {
			padding: 7px 0;
			width: 20px;
			height: auto;
		}

		#toplevel_page_ert-site-options.current img{
			opacity: 1;
		}
	</style>
<?php }

add_action( 'admin_head', 'ert_admin_menu_styles' );

/**
 * Add admin theme settings page.
 *
 * @return void
 */
function ert_admin_page() {

	global $ef;

	$ef->enqueue_scripts();

	$ef->views()->get_view( 'tab', array(
		'form' => array(),
		'tabs' => array(
			'ef-general' => array(
				'label' => __( 'General', 'ert' ),
				'sections' => array(
					array(
						'title' => __( 'General Settings', 'ert' ),
						'fields' => array(
							'favicon_attachment_id',
							'theme_color',
							'property_sidebar',
							'breadcrumbs_enabled',
						),
					),
				),
			),
			'ef-properties' => array(
				'label' => __( 'Properties', 'ert' ),
				'sections' => array(
					array(
						'title' => __( 'General Settings', 'ert' ),
						'fields' => array(
							'property_disabled_sidebar',
							'show_sort_by',
							'sort_bar_categories',
							'is_badges_enabled',
							'property_archive_page_name',
						),
					),
				)
			),
			'ef-blog' => array(
				'label' => __( 'Blog', 'ert' ),
				'sections' => array(
					array(
						'title' => __( 'Post Displays Settings', 'ert' ),
						'fields' => array(
							'show_author',
							'show_featured_image',
							'blog_items_per_row',
							'blog_disable_sidebar',
						),
					),
				)
			),
			'ef-header' => array(
				'label' => __( 'Header', 'ert' ),
				'sections' => array(
					array(
						'title' => __( 'Header Settings', 'ert' ),
						'fields' => array(
							'sticky_header',
							'logo_attachment_id',
							'logo_width',
							'logo_height',
							'header_menu_color_hover',
						),
					)
				),
			),
			'ef-footer' => array(
				'label' => __( 'Footer', 'ert' ),
				'sections' => array(
					array(
						'title' => __( 'Footer Settings', 'ert' ),
						'fields' => array(
							'footer_copyright',
						),
					)
				),
			),
		)
	) )->render();
}

/**
 * Register widgets areas.
 *
 * @return void
 */
function ert_widgets_init() {

	$widget_args = array(
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => "</div>",
		'before_title' => '<h3 class="widgettitle">',
		'after_title' => "</h3>",
	);

	$sidebars = array(
		array( 'name' => __( 'Footer Left Area', 'ert' ), 'id' => 'footer-left' ),
//		array( 'name' => __( 'Sidebar', 'ert' ), 'id' => 'sidebar' ),
		array( 'name' => __( 'Footer Center Area', 'ert' ), 'id' => 'footer-center' ),
		array( 'name' => __( 'Footer Right Area', 'ert' ), 'id' => 'footer-right' ),
		array( 'name' => __( 'Page Sidebar', 'ert' ), 'id' => 'sidebar-page', ),
		array( 'name' => __( 'Post Sidebar', 'ert' ), 'id' => 'sidebar-post' ),
		array( 'name' => __( 'Blog Sidebar', 'ert' ), 'id' => 'sidebar-blog' ),
		array( 'name' => __( 'Single Property Sidebar', 'ert' ), 'id' => 'sidebar-property' ),
		array( 'name' => __( 'After Blog Archive', 'ert' ), 'id' => 'after_blog_archive' ),
		array( 'name' => __( 'Before Blog Archive', 'ert' ), 'id' => 'before_blog_archive' ),
//		array( 'name' => __( 'Before Blog Single', 'ert' ), 'id' => 'before_blog_single' ),
//		array( 'name' => __( 'After Blog Single', 'ert' ), 'id' => 'after_blog_single' ),
//		array( 'name' => __( 'After Properties Archive', 'ert' ), 'id' => 'after_properties_archive' ),
//		array( 'name' => __( 'Before Properties Archive', 'ert' ), 'id' => 'before_properties_archive' ),
		array( 'name' => __( 'Before Property Single', 'ert' ), 'id' => 'before_property' ),
		array( 'name' => __( 'After Property Single', 'ert' ), 'id' => 'after_property' ),
		array( 'name' => __( 'Properties Archive Sidebar', 'ert' ), 'id' => 'sidebar-properties' ),
	);

    if ( is_estatik4() ) {
        $sidebars[] = array( 'name' => __( 'Single Agent Sidebar', 'ert' ), 'id'  => 'sidebar-agent' );
        $sidebars[] = array( 'name' => __( 'Single Agency Sidebar', 'ert' ), 'id'  => 'sidebar-agency' );
    }

	foreach ( $sidebars as $sidebar ) {
		register_sidebar( array_merge( $sidebar, $widget_args ) );
	}
}
add_action( 'widgets_init', 'ert_widgets_init' );

/**
 * Setup theme action.
 *
 * @return void
 */
function ert_setup() {

	register_nav_menu( 'header-menu', __( 'Header Menu', 'ert' ) );
	register_nav_menu( 'header-menu-unauthorized', __( 'Header Menu Unauthorized', 'ert' ) );
	register_nav_menu( 'header-menu-authorized', __( 'Header Menu Authorized', 'ert' ) );

	load_theme_textdomain( 'ert', get_template_directory() . '/languages' );

	add_theme_support( 'post-thumbnails' );

	// Post formats.
	add_theme_support(
		'post-formats',
		array( 'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery' )
	);

	add_theme_support( 'menus' );

	add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'ert_setup' );

/**
 * Add site favicon.
 *
 * @return void
 */
function ert_favicon() {
	global $ef_options;

	echo '<meta name="theme-color" content="' . $theme_color = $ef_options->get( 'theme_color', '#000000' ) . '">';

	if ( $favicon = $ef_options->get( 'favicon_attachment_id' ) ) {
		printf( "<link rel='shortcut icon' href='%s' />", wp_get_attachment_image_url( $favicon ) );
	} else {
		echo '<link rel="apple-touch-icon" sizes="144x144" href="' . get_template_directory_uri() . '/assets/favicon/apple-touch-icon.png">
              <link rel="icon" type="image/png" sizes="32x32" href="' . get_template_directory_uri() . '/assets/favicon/favicon-32x32.png">
              <link rel="icon" type="image/png" sizes="16x16" href="' . get_template_directory_uri() . '/assets/favicon/favicon-16x16.png">
              <link rel="manifest" href="' . get_template_directory_uri() . '/assets/favicon/site.webmanifest">
              <link rel="mask-icon" href="' . get_template_directory_uri() . '/assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
              <meta name="msapplication-TileColor" content="#da532c">';
	}
}
add_action( 'wp_head', 'ert_favicon' );

/**
 * @param $hex
 * @param bool $alpha
 *
 * @return mixed
 */
function ert_hex_to_rgb( $hex, $alpha = false ) {
	$hex      = str_replace('#', '', $hex);
	$length   = strlen($hex);
	$rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
	$rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
	$rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
	if ( $alpha ) {
		$rgb['a'] = $alpha;
	}
	return $rgb;
}

/**
 * @return void
 */
function ert_migrations() {
    global $ef_options;

	if ( taxonomy_exists( 'es_category' ) && ! get_option( 'ert_default_categories' ) ) {
		if ( ! $ef_options->get( 'sort_bar_categories' ) ) {
			$options = $ef_options->get_options();
			if ( ! empty( $options['sort_bar_categories']['default_terms'] ) ) {
				$values = array();

				foreach ( $options['sort_bar_categories']['default_terms'] as $term ) {
					$term_exists = get_term_by( 'name', $term, $options['sort_bar_categories']['taxonomy'] );
					if ( $term_exists instanceof WP_Term ) {
						$term_id = $term_exists->term_taxonomy_id;
					} else {
						$term = wp_insert_term( $term, $options['sort_bar_categories']['taxonomy'] );
						if ( ! $term instanceof WP_Error ) {
							$term_id = $term['term_id'];
						}
					}

					if ( ! empty( $term_id ) ) {
						$values[] = $term_id;
					}
				}

				$ef_options->set( 'sort_bar_categories', $values );
			}
		}
		update_option( 'ert_default_categories', 1 );
	}

	if ( ! get_option( 'ert_migration_badges' ) ) {
	    $ef_options->set( 'is_badges_enabled', 1 );
	    update_option( 'ert_migration_badges', 1 );
    }
}
add_action( 'init', 'ert_migrations', 11 );

/**
 * Enqueue theme scripts.
 *
 * @return void
 */
function ert_enqueue_scripts() {

	global $es_settings, $ef_options;

	// Bootstrap Framework Library.
	wp_register_style( 'bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css' );

	// Line Awesome Font.
	wp_enqueue_style( 'line-awesome', 'https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome-font-awesome.min.css' );

	$deps = array( 'bootstrap' );

    if ( defined( 'ES_PLUGIN_URL' ) ) {
	    $common = ES_PLUGIN_URL . 'common';
    }

	if ( is_singular( 'properties' ) ) {
        if ( defined( 'ESTATIK4' ) ) {
            wp_register_style( 'es-magnific', $common . '/magnific-popup/magnific-popup.min.css' );
            $deps[] = 'es-magnific';
        } else {
            $deps[] = 'es-magnific-style';
        }
	}

	// Theme main styles.
	wp_enqueue_style( 'ert-theme', get_template_directory_uri() . '/assets/css/style-min.css', $deps );

	wp_register_script( 'bootstrap-popper-vendor', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js', array( 'jquery' ) );
	wp_register_script( 'waypoints', 'https://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.5/waypoints.min.js', array( 'jquery' ) );
	wp_register_script( 'counterup', get_template_directory_uri() . '/assets/js/jquery.counterup.min.js', array( 'jquery', 'waypoints' ) );

	wp_enqueue_script( 'bootstrap-vendor-script', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js', array( 'jquery', 'bootstrap-popper-vendor' ) );

	if ( ! wp_script_is( 'es-slick-script', 'registered' ) ) {
		wp_register_style( 'es-slick-style', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css' );

		wp_register_script( 'es-slick-script', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js', array (
			'jquery',
		) );
	}

	$deps = array( 'jquery', 'es-slick-script', 'counterup' );

    if ( ! defined( 'ESTATIK4' ) ) {
        if ( ! empty( $es_settings->google_api_key ) ) {
            $deps[] = 'es-admin-map-script';
        }
    }

	if ( is_singular( 'properties' ) ) {
        if ( defined( 'ESTATIK4' ) ) {
            $common = ES_PLUGIN_URL . 'common';
            wp_register_style( 'es-magnific', $common . '/magnific-popup/magnific-popup.min.css' );
            $deps[] = 'es-magnific';
            wp_register_script( 'es-magnific-script', get_template_directory_uri() . '/assets/js/jquery.magnific-popup.min.js', array( 'jquery' ) );
        }

        $deps[] = 'es-magnific-script';
    }

	if ( ! wp_script_is( 'es-share-script' ) ) {
		wp_register_script( 'es-share-script', 'https://static.addtoany.com/menu/page.js' );
	}

	// on single blog post pages with comments open and threaded comments
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script( 'ert-theme', get_template_directory_uri() . '/assets/js/scripts-min.js', $deps );

	$theme_color = $ef_options->get( 'theme_color', '#000000' );
	$header_hover = $ef_options->get( 'header_menu_color_hover', '#555555' );
	$styles = "#header .navbar-nav a:hover {color: $header_hover}";

	$control_color = ert_hex_to_rgb( $theme_color, 0.8 );

	$styles .= "

	.home .so-widget-sow-hero .sow-slider-image-cover.cycle-slide-active {
	        background-position: 65% center !important;
	}

	#header .navbar-nav.ert-login-menu li:last-child a,
	.ert-link-btn, .btn.btn-light,
	a.btn.btn-light,
	.es-btn.es-btn-blue-bordered,
	.widget.es-widget .es-request-widget-wrap input.btn[type=submit], .single-properties .es-request-widget-wrap input.btn[type=submit], .es_calc_form input.es_calc_submit {
        border: 1px solid $theme_color;
        color: $theme_color;
	}
	
	a.btn.btn-light {
	    border: 1px solid $theme_color !important;
	}

	.es_calc_form input.es_calc_submit:hover,
	.es-btn.es-btn-blue-bordered:hover,#header
	.navbar-nav.ert-login-menu li:last-child a:hover,
	.ert-link-btn:hover, .btn.btn-light:hover,
	 a.btn.btn-light:hover, #searchsubmit,
	.single-properties .es-request-widget-wrap input.btn[type=submit]:hover,
	 .es-request-widget-wrap input[type=submit]:hover {
	    background: $theme_color;
	    color: #fff;
    }

    .btn-social .ert-icon, body .btn.btn-primary:hover, .es-btn.es-btn-green:hover, .ert-rating .fa, .checkbox label::after,
     body .es-manage-property__tab .nav-tab-menu .active a,
     body .es-manage-property__tab .nav-tab-menu .active a:link,
     body .es-manage-property__tab .nav-tab-menu .active a:visited {
        color: $theme_color;
    }

    .btn-social:hover, body .btn.btn-primary, .es-btn.es-btn-green {
        background: $theme_color;
        border: 1px solid {$theme_color};
    }

	.ert-property-control a, .badge.badge-dark, .panel-row-style.ert-overlay:before, .widget_ert-property-types-widget .ert-type__block.ert-type__block--dark .ert-type__block--inner:before {
	    background: rgba({$control_color['r']}, {$control_color['g']}, {$control_color['b']}, {$control_color['a']})
    }

    @media (max-width: 768px) {
        .ert-type__block:nth-child(odd) .ert-type__block--inner:before {
            background: rgba({$control_color['r']}, {$control_color['g']}, {$control_color['b']}, {$control_color['a']});
        }
    }

    .badge.badge-dark:hover,
    .ert-property-control a:hover,
    .ert-properties-slider .slick-dots li.slick-active,
    .ert-testimonials .slick-dots li.slick-active,
    .ert-hero-gallery .slick-arrow,
    .advanced-search-link .fa,
    .es-gallery .es-gallery-image .fa,
    .es-features-list .circle,
    .ert-profile h2, .es-agent-register__wrap h2,
    .es-login__wrap h2,
    body .es-table-wrap .nav-links .current,
    body .es-manage-property__tab .es-field .select2-selection__choice,
    body .es-manage-property__tab .es-field__wrapper .select2-selection__choice,
     .es-profile__tabs-wrapper ul li.active, .ert-contact-form .widget-title{
        background: rgba({$control_color['r']}, {$control_color['g']}, {$control_color['b']});
    }

    .ert-post-item .ert-post-item__content, .ert-post-item .ert-property-item__content, .ert-property-item .ert-post-item__content, .ert-property-item .ert-property-item__content {
        border-bottom: 1px solid {$theme_color};
    }

    .widget_ert-about-widget .ert-about__image--inner:before, .ert-properties-slider .slick-dots li, .ert-testimonials .slick-dots li, #searchsubmit {
        border: 1px solid {$theme_color};
    }

    .es_calc_option .irs-slider {
        border-bottom-color: {$theme_color};
    }
	";

	wp_add_inline_style( 'ert-theme', $styles );
}
add_action( 'wp_enqueue_scripts', 'ert_enqueue_scripts', 50 );

/**
 * Return Estatik Framework Instance.
 *
 * @return Estatik_Framework
 */
function ert_framework_instance() {

	if ( ! class_exists( 'Estatik_Framework' ) ) {
		require_once 'framework/core/class-estatik-framework.php';
	}
	$cnf = include 'inc/framework_config.php';
	$instance = Estatik_Framework::get_instance( $cnf );

	return $instance;
}

/** @var $ef Estatik_Framework */
global $ef, $ef_options; $ef = ert_framework_instance(); $ef_options = $ef->options();

/**
 * Return logo image.
 *
 * @return string
 */
function ert_get_header_logo_image() {

	global $ef_options;

	$attachment_id = $ef_options->get( 'logo_attachment_id' );

	$size = array( $ef_options->get( 'logo_width' ), $ef_options->get( 'logo_height' ) );

	if ( $attachment_id && ( $image = wp_get_attachment_image_url( $attachment_id, 'full' ) ) ) {
		return "<img src='{$image}' id='site-logo' style='width: {$size[0]}; height: {$size[1]};'>";
	}
}

/**
 * Allow xml files for upload.
 *
 * @param $mimes
 *
 * @return mixed
 */
function ert_mime_types( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', 'ert_mime_types' );

if ( ! function_exists( 'ert_property_item_fields' ) ) {

    /**
     * @param $property Es_Property
     */
    function ert_property_item_fields() {
        global $es_settings;
        $items = $es_settings->property_fields_icons;
        $items = array_filter( $items );

        if ( ! empty( $items ) ) : ?>
            <div class="row ert-property-item__fields">
                <?php foreach ( $items as $item ) :
                    if ( empty( $item['field'] ) ) continue;
                    $field = Es_Property::get_field_info( $item['field'] );
                    $value = ! empty( $field['formatter'] ) ?
                        es_get_the_formatted_field( $item['field'], $field['formatter'] ) : es_get_the_property_field( $item['field'] );

                    $value = is_array( $value ) ? implode( ', ', $value ) : $value;

                    if ( ! empty( $value ) && ( is_string( $value ) || is_numeric( $value ) ) ) : ?>
                        <div class="col-auto">
                            <?php if ( ! empty( $item['icon_url'] ) ) : ?>
                                <span class="es-meta-icon es-meta-icon--<?php echo $item['field']; ?>" style="background-image: url(<?php echo $item['icon_url']; ?>);"></span>
                            <?php endif; ?>
                            <span><?php echo $value; ?></span>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?></div>
        <?php endif;
    }
}
add_action( 'ert_property_item_fields', 'ert_property_item_fields', 10 );

/**
 * Return property archive item layout.
 *
 * @param $layout_type
 *
 * @return string
 */
function ert_property_item_layout( $layout_type = null ) {

	global $es_settings;

	$layout = 'col-12';

	$layout_type = $layout_type ? $layout_type : $es_settings->listing_layout;

	$layout_type = ! empty( $_GET['layout'] ) ? $_GET['layout'] : $layout_type;

	$layout = $layout_type == '2_col' ? 'col-lg-6' : $layout;
	$layout = $layout_type == '3_col' ? 'col-lg-4' : $layout;

	return $layout;
}

/**
 * @param $excerpt
 *
 * @return string
 */
function ert_property_excerpt( $excerpt ) {
	global $post;

	if ( $post && ! is_singular( 'properties' ) && $post->post_type == 'properties' ) {
		return wp_trim_words( $excerpt, 15 );
	}

	return $excerpt;
}
add_filter( 'the_excerpt', 'ert_property_excerpt' );

add_filter( 'es_load_inline_styles', '__return_false' );

/**
 * Customize estatik settings tabs.
 *
 * @param $tabs
 *
 * @return mixed
 */
function ert_settings_get_tabs( $tabs ) {

	unset( $tabs['layouts'], $tabs['color'] );

	return $tabs;
}
add_filter( 'es_settings_get_tabs', 'ert_settings_get_tabs' );

/**
 * Import demo data.
 *
 * @return array
 */
function ert_import_files() {
	return array(
        array(
            'import_file_name'           => 'Estatik 4 Demo Import',
            'local_import_file'            => trailingslashit( get_template_directory() ) . 'demo/demo-content-major.xml',
            'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'demo/widgets-major.wie',
        ),
		array(
			'import_file_name'           => 'Estatik 3 Demo Import',
			'local_import_file'            => trailingslashit( get_template_directory() ) . 'demo/demo-content.xml',
			'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'demo/widgets.wie',
		),
	);
}
add_filter( 'pt-ocdi/import_files', 'ert_import_files' );

/**
 * After Import Setup Handler.
 *
 * @return void
 */
function ert_after_import_setup() {

	// Assign menus to their locations.
	$header_menu = get_term_by( 'slug', 'header-menu', 'nav_menu' );
	$unauthorized = get_term_by( 'slug', 'login-menu', 'nav_menu' );
	$authorized = get_term_by( 'slug', 'authorized-menu', 'nav_menu' );

	set_theme_mod( 'nav_menu_locations', array(
			'header-menu' => $header_menu->term_id,
			'header-menu-unauthorized' => $unauthorized->term_id,
			'header-menu-authorized' => $authorized->term_id,
		)
	);

	$front_page_id = get_page_by_title( 'Homepage' );
	$blog = get_page_by_title( 'Our Articles' );

	update_option( 'show_on_front', 'page' );

	if ( ! empty( $front_page_id->ID ) ) {
		update_option( 'page_on_front', $front_page_id->ID );
    }

	if ( ! empty( $blog->ID ) ) {
		update_option( 'page_for_posts', $blog->ID );
	}
}
add_action( 'pt-ocdi/after_import', 'ert_after_import_setup' );

/**
 * @param $sections
 *
 * @return array
 */
function ert_property_sections( $sections ) {

    if ( empty( $_GET['es-pdf'] ) ) {
        $sections['es-features']['render_action'] = 'ert_single_features';
    }

//	$sections['ert-amenities'] = array(
//		'machine_name' => 'ert-amenities',
//		'label' => __( 'Amenities', 'es-plugin' ),
//		'render_action' => 'ert_single_amenities',
//	);
//
//	$sections['ert-features'] = array(
//		'machine_name' => 'ert-features',
//		'label' => __( 'Features', 'es-plugin' ),
//		'render_action' => 'ert_single_features',
//	);

	return $sections;
}
add_filter( 'es_property_sections', 'ert_property_sections', 15 );

/**
 * Render amenities block.
 *
 * @return void
 */
function ert_single_amenities() {

	$title = __( 'Amenities', 'ert' );
	$data = es_get_the_amenities();
	$class = 'amenities';

	if ( $class && $title && $data ) {
		include locate_template( 'estatik/property/features-list.php' );
	}
}
add_action( 'ert_single_features', 'ert_single_amenities' );

/**
 * Render features block.
 *
 * @return void
 */
function ert_single_features() {

	$title = __( 'Features', 'ert' );
	$data = es_get_the_features();
	$class = 'features';

	if ( $class && $title && $data ) {
		include locate_template( 'estatik/property/features-list.php' );
	}

	do_action( 'es_single_tabbed_content_after', 'es-features' );
}
add_action( 'ert_single_features', 'ert_single_features' );

/**
 * Return agent social network links.
 *
 * @param Es_Agent $agent
 *
 * @return array
 */
function ert_get_agent_social( $agent ) {

	return array(
		'facebook' => 'https://facebook.com',
		'twitter' => 'https://twitter.com',
		'instagram' => 'https://instagram.com',
		'linkedin' => 'https://linkedin.com',
	);
}

/**
 * Display social networks block.
 *
 * @param $data
 * @param bool $use_wrapper
 */
function ert_display_social_block( $agent, $use_wrapper = true ) {

	if ( ! $agent ) return;

	do_action( 'es_agent_social_links', $agent );
}
add_action( 'ert_social_block', 'ert_display_social_block', 10, 2 );

/**
 * Display social networks block.
 *
 * @param $data
 * @param bool $use_wrapper
 */
function ert_display_social_share_block() {
	include locate_template( 'template-parts/blocks/social-share.php' );
}
add_action( 'ert_social_share_block', 'ert_display_social_share_block' );

/**
 * Render rating stars.
 *
 * @param $rating
 *
 * @param bool $label
 *
 * @return void
 */
function ert_display_rating( $rating, $label = true ) {

	$img_en = '<i class="fa fa-star"></i>';
	$img_ds = '<i class="fa fa-star-o"></i>';

	if ( ! $rating ) return;

	echo '<div class="ert-rating">';

	if ( $label ) {
		echo "<span class='ert-rating__label'>" . __( 'Rating', 'ert' ) . ":</span>";
	}

	for ( $i = 1; $i <= 5; $i++ ) {
		$img = $i > $rating ? $img_ds : $img_en;
		echo $img;
	}

	echo '</div>';
}
add_action( 'ert_rating', 'ert_display_rating', 10, 2 );

function ert_recommended_plugins() {

	$plugins = array(
        array(
            'name'      => __( 'Estatik', 'ert' ),
            'slug'      => 'estatik',
            'required'  => true,
        ),
		array(
			'name'      => __( 'SiteOrigin Page Builder', 'ert' ),
			'slug'      => 'siteorigin-panels',
			'required'  => true,
		),
		array(
			'name'      => __( 'SiteOrigin Widgets Bundle', 'ert' ),
			'slug'      => 'so-widgets-bundle',
			'required'  => true,
		),
		array(
			'name'      => __( 'Easy Forms for MailChimp', 'ert' ),
			'slug'      => 'yikes-inc-easy-mailchimp-extender',
			'required'  => false,
		),
		array(
			'name'      => __( 'One Click Demo Import', 'ert' ),
			'slug'    => 'one-click-demo-import',
			'required'  => false,
		),
        array(
			'name'      => __( 'Contact Form 7', 'ert' ),
			'slug'    => 'contact-form-7',
			'required'  => false,
		),
	);

	$config = array(
		'id'           => 'tgmpa-project',         // Unique ID for hashing notices for multiple instances of TGMPA.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'ert_recommended_plugins' );

/**
 * Replace Estatik Plugin Shortcode callbacks.
 *
 * @return void
 */
function ert_detach_estatik_shortcodes() {

	remove_shortcode( 'es_property_slideshow' );
	add_shortcode( 'es_property_slideshow', 'ert_property_slider' );
}
add_action( 'init', 'ert_detach_estatik_shortcodes', 20 );

/**
 * @return mixed
 */
function ert_property_query_atts() {

	$atts = array( 'sort' => 'recent', 'limit' => 10, 'prop_id' => null, 'address' => '', 'price_min' => '',
        'price_max' => '', 'category' => '', 'status' => '', 'type' => '', 'strict_address' => '' );

    if ( class_exists( 'Es_Taxonomy' ) ) {
        foreach (Es_Taxonomy::get_taxonomies_list() as $tax) {
            $atts[str_replace('es_', '', $tax)] = null;
        }
    }

	return apply_filters( 'ert_property_query_atts', $atts );
}

/**
 * Add new callback for estatik slideshow shortcode.
 *
 * @param $args
 *
 * @return null|string
 */
function ert_property_slider( $atts = array() ) {

	$path = locate_template( 'template-parts/shortcodes/property-slider.php' );
	$uid = uniqid();

	$atts = shortcode_atts( array_merge( ert_property_query_atts(), array(
		'arrows' => true,
		'dots' => true,
		'slides_to_show' => 2,
		'slides_to_scroll' => 1,
		'view' => 'v1',
        'layout' => 'horizontal',
        'uid' => $uid,
	) ), $atts, 'ert_property_slider' );

	$responsive = null;

	if ( $atts['slides_to_show'] >= 2 ) {
		$responsive[] = array( 'breakpoint' => 960, 'settings' => array( 'slidesToShow' => 2 ) );
		$responsive[] = array( 'breakpoint' => 680, 'settings' => array( 'slidesToShow' => 1 ) );
	}

	$slick_config = json_encode( array(
		'slidesToShow' => intval( $atts['slides_to_show'] ),
		'slidesToScroll' => intval( $atts['slides_to_scroll'] ),
		'prevArrow' => "#ert-properties-slider__wrapper-{$atts['uid']} .slick-prev",
		'nextArrow' => "#ert-properties-slider__wrapper-{$atts['uid']} .slick-next",
		'dots' => boolval( $atts['dots'] ),
		'arrows' => true,
		'vertical' => $atts['layout'] == 'vertical' ? true : false,
		'verticalSwiping' => $atts['layout'] == 'vertical' ? true : false,
		'responsive' => $responsive,
	), JSON_HEX_QUOT | JSON_HEX_TAG );

    if ( is_estatik4() ) {
        $query_args = es_get_properties_query_args( array(
            'fields' => $atts,
        ) );
    } else {
        $listing = new Es_My_Listing_Shortcode();
        $atts_new = $listing->merge_shortcode_atts( $atts );
        $query_args = $listing->build_query_args( $atts_new );
    }

    $query = new WP_Query( $query_args );

	if ( $path ) {
		ob_start();
		include $path;
		return ob_get_clean();
	}

	unset( $slick_config );

	return null;
}
add_shortcode( 'ert_property_slider', 'ert_property_slider' );

/**
 * @return mixed|null|string
 */
function ert_get_property_layout() {

	global $ef_options;

	$layout = ! empty( $_GET['layout'] ) ? $_GET['layout'] : '';

	$layout = $layout ? $layout : $ef_options->get( 'property_archive_layout_version', 'list' );

	return ! empty( $_GET['layout'] ) ? $_GET['layout'] : $layout;
}

/**
 * Advanced Search shortcode hook.
 *
 * @param $atts
 *
 * @return string
 */
function ert_advanced_search( $atts = array() ) {

	if ( class_exists( 'Estatik' ) ) {

		global $es_settings;

		$atts = shortcode_atts( array(
			'visible_fields' => 'address,es_status,price',
			'advanced_fields' => 'area,bedrooms,bathrooms,es_type,es_category,lot_size,es_feature',
			'padding' => '25px',
			'page_id' => function_exists( 'ests' ) ? ests( 'search_results_page_id' ) : $es_settings->search_page_id,
			'width' => '80%',
			'max-width' => '1180px',
            'save_search_button' => false,
		), $atts );

		$atts['visible_fields'] = explode( ',', $atts['visible_fields'] );
		$atts['advanced_fields'] = explode( ',', $atts['advanced_fields'] );

        if ( is_estatik4() ) {
            $shortcode_instance = es_get_shortcode_instance( 'es_search_form', array(
                'main_fields' => $atts['visible_fields'],
                'collapsed_fields' => $atts['advanced_fields'],
                'search_type' => 'main',
                'atts' => $atts,
                'search_page_id' => $atts['page_id'],
                'enable_saved_search' => $atts['save_search_button'],
                'padding' => $atts['padding'],
                'layout' => 'horizontal',
            ) );

            return $shortcode_instance->get_content();
        } else {
            ob_start();
            include locate_template( 'template-parts/shortcodes/advanced-search.php' );
            return ob_get_clean();
        }
	}
}
add_shortcode( 'ert_advanced_search', 'ert_advanced_search' );

/**
 * Breadcrumbs.
 */
function ert_the_breadcrumbs() {
	global $ef_options;

	if ( is_front_page() || ! $ef_options->get( 'breadcrumbs_enabled' ) ) return;

	$sep = '<i class="fa fa-angle-right"></i>';

	// Start the breadcrumb with a link to your homepage
	echo '<div class="col-12 breadcrumbs">';
	echo '<a href="';
	echo get_option('home');
	echo '">';
	bloginfo('name');
	echo '</a>' . $sep;

	// Check if the current page is a category, an archive or a single page. If so show the category or archive name.
	if ( is_category() ) {
		the_category( '<i class="fa fa-angle-right"></i>' );
	} else if ( is_singular(array( 'properties' ) ) ) {
	    es_the_title();
		echo '</div>';
	    return;
	} elseif ( is_singular( array( 'agent', 'agency' ) ) ) {
        the_title();
        echo '</div>';
        return;
    } elseif (is_archive() || is_single()){
		if ( is_day() ) {
			printf( __( '%s', 'ert' ), get_the_date() );
		} elseif ( is_month() ) {
			printf( __( '%s', 'ert' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'ert' ) ) );
		} elseif ( is_year() ) {
			printf( __( '%s', 'ert' ), get_the_date( _x( 'Y', 'yearly archives date format', 'ert' ) ) );
		} else {
			_e( 'Blog Archives', 'ert' );
		}
	}

	// If the current page is a single post, show its title with the separator
	if (is_single()) {
		echo $sep;
		the_title();
	}

	// If the current page is a static page, show its title.
	if (is_page()) {
		echo the_title();
	}

	// if you have a static page assigned to be you posts list page. It will find the title of the static page and display it. i.e Home >> Blog
	if (is_home()){
		global $post;
		$page_for_posts_id = get_option( 'page_for_posts' );
		if ( $page_for_posts_id ) {
			$post = get_post( $page_for_posts_id );
			setup_postdata($post);
			the_title();
			rewind_posts();
		}
	}
	echo '</div>';
}

/**
 * @param $agent Es_Agent
 * @param string $size
 * @param bool $url
 *
 * @return string
 */
function es_get_agent_thumbnail( $agent, $size = 'es-agent-size', $url = true ) {
	global $es_settings;

	$meta = 'thumbnail_attachment_agent_id';
	$thumbnail = ES_PLUGIN_URL . 'assets/images/agent.png';

	$attachment_id = $es_settings->{$meta};

	if ( ! $es_settings->{$meta} ) {
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}

		if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
		}

		if ( ! function_exists( 'media_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/media.php' );
		}

		$upload_dir = wp_upload_dir();
		$file['name'] = basename( $thumbnail );
		$file['tmp_name'] = download_url( $thumbnail );

		$file = wp_handle_sideload( $file, array( 'test_form' => false ) );

		if ( empty( $file['error'] ) ) {
			$wp_filetype = wp_check_filetype( basename( $file['file'] ), null );

			$attachment = array(
				'guid' => $upload_dir['baseurl'] . ES_DS . _wp_relative_upload_path( $file['file'] ),
				'post_mime_type' => $wp_filetype['type'],
				'post_title' => preg_replace('/\.[^.]+$/', '', basename( $file['file'] ) ),
				'post_content' => '',
				'post_status' => 'inherit'
			);

			$attachment_id = wp_insert_attachment( $attachment, $file['file'] );

			$attach_data = wp_generate_attachment_metadata( $attachment_id,  get_attached_file( $attachment_id ) );
			wp_update_attachment_metadata( $attachment_id,  $attach_data );

			$es_settings->saveOne( $meta, $attachment_id );
			$attachment_id = $es_settings->{$meta};
		} else {
			$attachment_id = null;
		}
	}

	$attachment_id = $agent->profile_attachment_id ? $agent->profile_attachment_id : $attachment_id;

	return $url ? wp_get_attachment_image_url( $attachment_id, $size ) : wp_get_attachment_image( $attachment_id, $size );
}

/**
 * @param array $atts
 *
 * @return string
 */
function ert_property_hero_gallery( $atts = array() ) {

	wp_enqueue_style( 'es-slick-style' );
	$atts = (array) $atts;

    if ( is_estatik4() ) {
        $properties_query = es_get_properties_query_args( array(
            'fields' => $atts,
        ) );
    } else {
        $listing = new Es_My_Listing_Shortcode();
        $atts = $listing->merge_shortcode_atts( $atts );
        $properties_query = $listing->build_query_args( $atts );
    }

//	unset( $properties_query['paged'] );
//	$properties_query['nopaging'] = true;
//	$properties_query['ignore_filter'] = true;
	$properties_query = new WP_Query( $properties_query );

	$path = locate_template( 'template-parts/shortcodes/property-hero-gallery.php', false );

	ob_start();
	include $path;
	return ob_get_clean();
}
add_shortcode( 'ert_property_hero_gallery', 'ert_property_hero_gallery' );

/**
 * @param $comment
 * @param $args
 * @param $depth
 */
function ert_comment( $comment, $args, $depth ) {

	if ( 'div' === $args['style'] ) {
		$tag       = 'div';
		$add_below = 'comment';
	} else {
		$tag       = 'li';
		$add_below = 'div-comment';
	}

	$classes = ' ' . comment_class( empty( $args['has_children'] ) ? '' : 'parent', null, null, false );

	$path = locate_template( 'comment.php', false, false );

	include $path;
}

function ert_move_comment_field_to_bottom( $fields ) {
	$comment_field = $fields['comment'];
	unset( $fields['comment'] );
	$fields['comment'] = $comment_field;
	return $fields;
}

add_filter( 'comment_form_fields', 'ert_move_comment_field_to_bottom' );

add_filter( 'option_yikes_easy_mailchimp_extender_forms', function( $value ) {

	if ( is_array( $value ) && ! empty( $value ) ) {
		foreach ( $value as $id => $form_data ) {

			if ( empty( $form_data['fields'] ) ) continue;

			if ( ! empty( $form_data['form_settings']['yikes-easy-mc-form-class-names'] ) && $form_data['form_settings']['yikes-easy-mc-form-class-names'] == 'ert-subscribe-form' ) {
				foreach ( $form_data['fields'] as $field_id => $field ) {
					if ( in_array( $field['type'], array( 'email', 'text', 'number' ) ) ) {
						$value[ $id ]['fields'][ $field_id ]['placeholder'] = $value[ $id ]['fields'][ $field_id ]['label'];
						$value[ $id ]['fields'][ $field_id ]['hide-label'] = 1;
					}
				}
			}
		}
	}

	return $value;
} );

add_filter( 'es_management_get_properties_template', function() {
	return locate_template( 'template-parts/shortcodes/management/properties.php' );
} );


if ( ! function_exists( 'wp_body_open' ) ) {
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

/**
 * @param $widgets
 *
 * @return mixed
 */
function ert_activate_wb_widget( $widgets ) {
	$widgets['hero'] = true;

	return $widgets;
}
add_filter( 'siteorigin_widgets_active_widgets', 'ert_activate_wb_widget' );

/**
 * Add item to the wishlist.
 *
 * @return void
 */
function ert_ajax_wishlist() {

	if ( check_ajax_referer( 'es_wishlist_nonce', 'nonce' ) ) {
		$property_id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );
		$method = filter_input( INPUT_POST, 'method' );

		if ( $property_id ) {
			$property = es_get_property( $property_id );
			$entity = $property->get_entity();

			if ( $entity->post_type == $property::get_post_type_name() ) {
				$wishlist = es_get_wishlist_instance();

				if ( 'remove' == $method ) {
					$wishlist->remove( $property_id );
					$response = array( 'status' => 'success', 'data' => ert_wishlist_get_button( $property_id ) );

				} else if ( 'add' == $method ) {

					if ( $wishlist->add( $property_id ) ) {
						$response = array( 'status' => 'success', 'data' => ert_wishlist_get_button( $property_id, true ) );
					} else {
						$response = array( 'status' => 'error', 'data' => __( 'Item didn\'t added to the wishlist', 'es-plugin' ) );
					}
				} else {
					$response = array( 'status' => 'error', 'message' => __( 'Incorrect wishlist action.', 'es-plugin' ) );
				}
			} else {
				$response = array( 'status' => 'error', 'message' => __( 'Incorrect property type.', 'es-plugin' ) );
			}
		} else {
			$response = array( 'status' => 'error', 'message' => __( 'Incorrect property.', 'es-plugin' ) );
		}
	} else {
		$response = array( 'status' => 'error', 'message' => __( 'Invalid security nonce. Please, refresh the page.', 'es-plugin' ) );
	}

	wp_die( json_encode( $response ) );
}
remove_action( 'wp_ajax_nopriv_es_wishlist_add', 'es_ajax_wishlist' );
remove_action( 'wp_ajax_es_wishlist_add', 'es_ajax_wishlist' );

remove_action( 'wp_ajax_nopriv_es_wishlist_remove', 'es_ajax_wishlist' );
remove_action( 'wp_ajax_es_wishlist_remove', 'es_ajax_wishlist' );

add_action( 'wp_ajax_nopriv_es_wishlist_add', 'ert_ajax_wishlist' );
add_action( 'wp_ajax_es_wishlist_add', 'ert_ajax_wishlist' );

add_action( 'wp_ajax_nopriv_es_wishlist_remove', 'ert_ajax_wishlist' );
add_action( 'wp_ajax_es_wishlist_remove', 'ert_ajax_wishlist' );

/**
 * @param $post_id
 * @param $added
 *
 * @return string
 */
function ert_wishlist_get_button( $post_id, $added = false ) {
	global $es_settings;

	ob_start();
	if ( $es_settings && $es_settings->is_wishlist_enabled && function_exists( 'es_get_wishlist_instance' ) ) {
		$instance = es_get_wishlist_instance();

		if ( $instance->has( $post_id ) || $added ) {
			echo "<a href='#' class='js-es-wishlist-button active' data-id='{$post_id}' data-method='remove'><i class='fa fa-heart' aria-hidden='true'></i> <span>" . __( 'Remove from favourites', 'ert' ) . "</span></a>";
		} else {
			echo "<a href='#' class='js-es-wishlist-button' data-id='{$post_id}' data-method='add'><i class='fa fa-heart-o' aria-hidden='true'></i>  <span>" . __( 'Add to favorites', 'ert' ) . "</span></a>";
		}
	}
	return ob_get_clean();
}

/**
 * @param array $atts
 *
 * @return string
 */
function ert_button_shortcode( $atts = array() ) {

    $atts = shortcode_atts( array(
        'link' => '',
        'type' => 'primary',
        'name' => 'Read',
    ), $atts );

    return "<a href='" . esc_url( strip_tags( $atts['link'] ) ) . "' class='btn btn-" . $atts['type'] . "'>" . $atts['name'] . "</a>";
}
add_shortcode( 'ert_button', 'ert_button_shortcode' );

/**
 * @param $data
 * @return false|string
 */
function ert_social_links_shortcode( $data ) {
    $use_wrapper = true;
    ob_start();
	include locate_template( 'template-parts/blocks/social-links.php' );
	return ob_get_clean();
}
add_shortcode( 'ert_social_links', 'ert_social_links_shortcode' );

function ert_is_elementor_template_exists() {
    $has_term = (bool) get_posts( array(
        'posts_per_page' => 1,
        'post_type' => 'elementor_library',
        'post_status' => 'publish',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => '_elementor_template_type',
                'value' => 'single-properties',
            ),
            array(
                'key' => '_elementor_template_type',
                'value' => 'properties',
            ),
        ),
    ) );

    return $has_term && defined( 'ELEMENTOR_PRO_VERSION' );
}

/**
 * Check is plugin estatik4 version.
 *
 * @return bool
 */
function is_estatik4() {
    return defined( 'ESTATIK4' );
}

if ( is_estatik4() ) {
    include 'inc/estatik4-hooks.php';
}

function ert_elementor_register_widgets( $widgets_manager ) {
    $widget_path = get_template_directory() . '/inc/widgets/elementor/';
    require_once( $widget_path . 'class-testimonials-widget.php' );
    require_once( $widget_path . 'class-property-types-widget.php' );
    require_once( $widget_path . 'class-about-widget.php' );
    require_once( $widget_path . 'class-performance-widget.php' );
//    require_once( $widget_path . 'class-social-widget.php' );

    $widgets_manager->register( new Elementor_Es_Testimonials_Widget() );
    $widgets_manager->register( new Elementor_Es_Property_Types_Blocks_Widget() );
    $widgets_manager->register( new Elementor_About_Block_Widget() );
    $widgets_manager->register( new Elementor_Es_Performance_Blocks_Widget() );
//    $widgets_manager->register( new Elementor_Es_Social_Widget() );

}
add_action( 'elementor/widgets/register', 'ert_elementor_register_widgets' );

/**
 * @param $data
 * @param $meta
 * @param $comments
 * @param $terms
 *
 * @return mixed
 */
//function ert_wxr_pre_process_attachment( $data, $meta, $comments, $terms ) {
//    //attachment_url is preferred
//    if ( ! empty( $data['post_type'] ) && $data['post_type'] == 'attachment' ) {
//        if ( ! empty( $data['attachment_url'] ) ) {
//            $parsed_url = explode( '/', $data['attachment_url'] );
//            $len = count( $parsed_url );
//            $path = $parsed_url[ $len - 3 ] . '/' . $parsed_url[ $len - 2 ] . '/' . $parsed_url[ $len - 1 ];
//            $data['attachment_url'] = get_template_directory_uri() . '/demo/attachments/' . $path;
//            $data['guid'] = $data['attachment_url'];
//        }
//    }
//    return $data;
//}
//add_filter( 'wxr_importer.pre_process.post', 'ert_wxr_pre_process_attachment', 10, 4 );

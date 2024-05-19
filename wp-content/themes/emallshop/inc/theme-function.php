<?php
/**
 * EmallShop Include Customizer Function
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* 	EmallShop Body Classess
 *
 * @ since EmallShop 2.0
/* --------------------------------------------------------------------- */
function emallshop_body_classes( $classes ) {
	$emallshop_theme 	= wp_get_theme();
	$classes[] 			= 'emallshop-v-' . $emallshop_theme->get( 'Version' );
	
	if( emallshop_get_option('sticky-header', 0) == 1 ){
		$classes[]	= emallshop_get_option( 'sticky-header-part', 'sticky-navigation' );
		$classes[]	= ' sticky';
	}
	
	if( emallshop_get_option( 'categories-menu', 0 ) == 1 ){
		$classes[] 	= ' open-categories-menu';
	}
	
	if( emallshop_get_option( 'header-overlay', 0 ) && is_front_page() ){
		$classes[] 	= ' header-overlay';
	}
	
	if ( is_woocommerce_activated() && emallshop_get_option( 'sticky-add-to-cart-button', 1 ) && is_singular( 'product' ) ) {
		global $post;
		$product = wc_get_product( $post->ID );
		
		if( $product->is_in_stock() ){
			$classes[] = 'has-sticky-add-to-cart';
		}
		
	}
	
	if( emallshop_get_option( 'mobile-bottom-navbar', 1 ) ) {
		if( function_exists('is_product') && is_product() ) {
			if( emallshop_get_option( 'mobile-product-page-button', 1 ) ){
				$classes[] = 'has-mobile-bottom-navbar-single-page';
			}else{
				$classes[] = 'has-mobile-bottom-navbar';
			}
		}elseif( function_exists('is_cart') && is_cart() ){
			if( emallshop_get_option( 'mobile-cart-page-button', 1 ) ) {
				$classes[] = 'has-mobile-bottom-navbar-single-page';
			}else{
				$classes[] = 'has-mobile-bottom-navbar';
			}
		}elseif( function_exists('is_checkout') && is_checkout() ){
			if( emallshop_get_option( 'mobile-checkout-page-button', 1 ) ){
				$classes[] = 'has-mobile-bottom-navbar-single-page';
			}else{
				$classes[] = 'has-mobile-bottom-navbar';
			}
		}else{
			$classes[] = 'has-mobile-bottom-navbar';
		}		
	}
	
	if( emallshop_get_option( 'mobile-bottom-navbar', 1 )  && !emallshop_is_vendor_page() ){
		$mobile_elemets 	= (array) emallshop_get_option( 'mobile-navbar-elements',  array(
				'enabled'  => array(
				   'shop'  		=> esc_html__( 'Shop', 'emallshop' ),
					'sidebar'  		=> esc_html__( 'Sidebar/Filters', 'emallshop' ),
					'wishlist' 		=> esc_html__( 'Wishlist', 'emallshop' ),
					'cart'     		=> esc_html__( 'Cart', 'emallshop' ),
					'account'  		=> esc_html__( 'Account', 'emallshop' ),							
				) ) );
		if( !isset( $mobile_elemets['enabled'] ) ){
			$mobile_elemets['enabled'] =  array(
			   'shop'  		=> esc_html__( 'Shop', 'emallshop' ),
				'sidebar'  	=> esc_html__( 'Sidebar/Filters', 'emallshop' ),
				'wishlist' 	=> esc_html__( 'Wishlist', 'emallshop' ),
				'cart'     	=> esc_html__( 'Cart', 'emallshop' ),
				'account'  	=> esc_html__( 'Account', 'emallshop' ),							
			);
		}		
		if( array_key_exists('sidebar',$mobile_elemets['enabled']) ){
			if(function_exists('is_product') && is_product() && emallshop_get_option('mobile-product-page-button') ){
				/* do something*/
			}else{
				$classes[] = 'has-mobile-canvas-sidebar';
			}			
		}
	}
	
    return $classes;
}
add_filter( 'body_class', 'emallshop_body_classes' );

/* 
 * Enqueue Theme styles.
 * @ since EmallShop 2.0
 */
if ( ! function_exists( 'emallshop_theme_css' ) ) {
	function emallshop_theme_css() {
		
		// Remove font awesome style from plugins
		wp_deregister_style( 'fontawesome' );
		wp_deregister_style( 'font-awesome' );
		
		$style = ( is_rtl() ) ? EMALLSHOP_STYLES .'/style-rtl.css' : EMALLSHOP_STYLES.'/style.css';
		$bootstrap = ( is_rtl() ) ? EMALLSHOP_STYLES .'/bootstrap-rtl.min.css' : EMALLSHOP_STYLES.'/bootstrap.min.css';
		
		// Load our main stylesheet.
		wp_enqueue_style( 'emallshop-style', EMALLSHOP_URI.'/style.css', array(), EMALLSHOP_VERSION );
				
		wp_enqueue_style( 'bootstrap', EMALLSHOP_STYLES . '/bootstrap.min.css', array(), '3.4.1' );
		wp_enqueue_style( 'font-awesome', EMALLSHOP_STYLES . '/font-awesome.min.css', array(), '4.6.3' );
		wp_enqueue_style( 'emallshop-woocommerce', EMALLSHOP_STYLES . '/woocommerce.css', array(), '' );
		wp_enqueue_style( 'emallshop-woocommerce-layout', EMALLSHOP_STYLES . '/woocommerce-layout.css', array(), EMALLSHOP_VERSION );
		wp_enqueue_style( 'owl-carousel', EMALLSHOP_STYLES . '/owl.carousel.min.css', array(), '2.2.1' );
		wp_enqueue_style( 'owl-theme-default', EMALLSHOP_STYLES . '/owl.theme.default.min.css', array(), '2.2.1' );
		wp_enqueue_style( 'slick', EMALLSHOP_STYLES . '/slick.css', array(), '' );
		wp_enqueue_style( 'magnific-popup', EMALLSHOP_STYLES . '/magnific-popup.css', array(), '1.1.0' );
		wp_enqueue_style( 'animate', EMALLSHOP_STYLES . '/animate.min.css', array(), '3.7.2' );	
				
		// Theme basic stylesheet.
		wp_enqueue_style( 'emallshop-base', $style, array('emallshop-woocommerce'), EMALLSHOP_VERSION );
		
		if ( is_rtl() ) {
			wp_enqueue_style( 'bootstrap-rtl', EMALLSHOP_STYLES . '/bootstrap-rtl.min.css', array(), '3.3.7' );
		}
		/* if (is_rtl()) {
			wp_enqueue_style( 'emallshop-rtl', EMALLSHOP_URI . '/rtl.css', array('emallshop-base'), EMALLSHOP_VERSION );
		} */
		
		// Dynamic CSS
		wp_add_inline_style( 'emallshop-base', emallshop_theme_style() );
				
		// REMOVE WP EMOJI
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles');
		
	}	
}
add_action( 'wp_enqueue_scripts', 'emallshop_theme_css', 10000 );	

/* 
 * Enqueue Theme JS.
 * @since EmallShop 2.1.0
 */	
if ( ! function_exists( 'emallshop_theme_js' ) ) {
	function emallshop_theme_js() {
		
		wp_enqueue_script( 'bootstrap', EMALLSHOP_SCRIPTS . '/bootstrap.min.js', array( 'jquery' ), '3.4.1', true );
		wp_enqueue_script('masonry');
		wp_enqueue_script( 'countdown-plugin', EMALLSHOP_SCRIPTS . '/jquery.countdown.plugin.min.js', array( 'jquery' ), '1.0.1', true );
		wp_enqueue_script( 'countdown', EMALLSHOP_SCRIPTS . '/jquery.countdown.min.js', array( 'jquery' ), '2.1.0', true );
		wp_enqueue_script( 'magnific-popup', EMALLSHOP_SCRIPTS . '/jquery.magnific-popup.min.js', array( 'jquery' ), '1.1.0', true );
		wp_enqueue_script( 'lazyloadxt', EMALLSHOP_SCRIPTS . '/jquery.lazyloadxt.min.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'hideMaxListItem', EMALLSHOP_SCRIPTS . '/hideMaxListItem-min.js', array( 'jquery' ), '1.36', true );
		wp_enqueue_script( 'sticky-sidebar', EMALLSHOP_SCRIPTS . '/theia-sticky-sidebar.min.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'isotope', EMALLSHOP_SCRIPTS . '/isotope.pkgd.min.js', array( 'jquery' ), '3.0.4', true );
		wp_enqueue_script( 'cookie', EMALLSHOP_SCRIPTS . '/jquery.cookie.min.js', array( 'jquery' ), '1.4.1', true );
		wp_enqueue_script( 'cookiealert', EMALLSHOP_SCRIPTS . '/cookiealert.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'owl-carousel', EMALLSHOP_SCRIPTS . '/owl.carousel.min.js', array( 'jquery' ), '2.2.1', true );
		wp_enqueue_script( 'slick', EMALLSHOP_SCRIPTS . '/slick.min.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'modernizr', EMALLSHOP_SCRIPTS . '/modernizr.custom.js', array( 'jquery' ), '2.6.2', true );
		wp_enqueue_script( 'toucheffects', EMALLSHOP_SCRIPTS . '/toucheffects.js', array( 'jquery' ), '', true );
		
		if( emallshop_get_option( 'live-search', 1 ) ){
			wp_enqueue_script( 'emallshop-autocomplete', EMALLSHOP_SCRIPTS . '/jquery.autocomplete.min.js', array( 'jquery' ), '1.4.1', true );
		}
		if ( class_exists( 'WooCommerce' ) ) {
			wp_enqueue_script( 'wc-add-to-cart-variation' );
		}
		if ( ! wp_script_is( 'wc-cart-fragments', 'enqueued' ) && wp_script_is( 'wc-cart-fragments', 'registered' ) ) {
			wp_enqueue_script( 'wc-cart-fragments' );
		}
		wp_enqueue_script( 'emallshop-script', EMALLSHOP_SCRIPTS . '/functions.js', array( 'jquery' ), '', true );
		
		//product search ajax	
		$is_rtl = is_rtl() ? true : false ;
		
		$emallshop_settings = apply_filters( 'emallshop_localize_script_data', array( 
			'rtl' 							=> $is_rtl,
			'ajax_url' 						=> admin_url( 'admin-ajax.php' ),
			'ajax_nonce' 					=> esc_js( wp_create_nonce( 'emallshop-ajax-nonce' ) ),
			'enable_live_search'			=> emallshop_get_option( 'live-search', 1 ) ? true : false,
			'js_translate_text'				=> apply_filters( 'EMALLSHOP_SCRIPTS_text', array(
				'days_text'					=> esc_html__( 'Days', 'emallshop' ),
				'hours_text'				=> esc_html__( 'Hours', 'emallshop' ),
				'mins_text'					=> esc_html__( 'Mins', 'emallshop' ),
				'secs_text'					=> esc_html__( 'Secs', 'emallshop' ),
				'show_more'					=> esc_html__( '+ Show more', 'emallshop' ),
				'Show_less'					=> esc_html__( '- Show less', 'emallshop' ),
			) ),
			'typeahead_options'     		=> array( 'hint' => false, 'highlight' => true ),
			'nonce'                 		=> wp_create_nonce( '_emallshop_nonce' ),
						
			'product_image_hover_style'		=> emallshop_get_option('product-image-hover-style', 'product-image-style2' ),
			'enable_add_to_cart_ajax' 		=> emallshop_get_option('enable-add-to-cart-ajax', 1 ) ? true : false,
			'add_to_cart_popup'				=> emallshop_get_option('show-cart-popup', 1 ) ? true : false,
			'enable_product_image_zoom'		=> emallshop_get_option('enable-product-image-zoom', 1 ) ? true : false,
			'enable_product_image_lightbox'	=> emallshop_get_option('enable-product-image-lightbox', 1 ) ? true : false,
			'widget_toggle'					=> emallshop_get_option('enable-widget-toggle', 0 ) ? true : false,
			'widget_menu_toggle'			=> emallshop_get_option('enable-widget-menu-toggle', 0 ) ? true : false,
			'widget_hide_max_limit_item' 	=> emallshop_get_option('widget-items-hide-max-limit', 0 ) ? true : false,
			'number_of_show_widget_items'	=> emallshop_get_option('number-of-show-widget-items', 8 ),
			'sticky_header_mobile'			=> emallshop_get_option('sticky-header-mobile', 0 ) ? true : false,
			'sticky_image_wrapper'			=> emallshop_get_option('sticky-image-wrapper', 1 ) ? true : false,
			'sticky_summary_wrapper'		=> emallshop_get_option('sticky-summary-wrapper', 1 ) ? true : false,
			'sticky_add_to_cart_btn'		=> emallshop_get_option( 'sticky-add-to-cart-button', 1 ) ? true : false,
			'touch_slider_mobile'			=> emallshop_get_option('touch-slider-mobile', 0 ) ? true : false,
			'touch_slider'					=> emallshop_get_option('touch-slider', 0 ) ? true : false,
			'cart_auto_update'				=> emallshop_get_option( 'cart-auto-update', 1 ) ? true : false,
			'checkout_product_quantity'		=> emallshop_get_option( 'checkout-product-quantity', 0 ) ? true : false,
			
		));
		
		//general ajax
		wp_localize_script( 'emallshop-script', 'emallshop_options', ( array ) $emallshop_settings );			
		
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'emallshop_theme_js' );	

if ( ! function_exists( 'emallshop_admin_scripts' ) ) {
	function emallshop_admin_scripts($hook) {		
		//Admin css
		wp_enqueue_style('emallshop-admin',EMALLSHOP_ADMIN_URI .'/assets/css/admin_css.css');
		wp_enqueue_style( 'font-awesome', EMALLSHOP_STYLES . '/font-awesome.min.css', array(), '4.6.3' );
		
		if ( 'toplevel_page_emallshop-theme' == $hook ) {
			wp_enqueue_script( 'emallshop-activation-theme', EMALLSHOP_ADMIN_URI . '/assets/js/emallshop-activation.js');
		}		
		if ( 'emallshop_page_emallshop-system-status' == $hook ) {
			wp_enqueue_script( 'emallshop-system-status', EMALLSHOP_ADMIN_URI . '/assets/js/emallshop-system-status.js');
		}		
		if ( 'emallshop_page_emallshop-demo-import' == $hook ) {
			wp_enqueue_script( 'emallshop-system-status', EMALLSHOP_ADMIN_URI . '/assets/js/emallshop-import.js');
		}
		$dashboard_pages = array( 'toplevel_page_emallshop-theme', 'emallshop_page_emallshop-system-status', 'emallshop_page_emallshop-theme-option', 'emallshop_page_emallshop-demo-import' );
		
		if( in_array( $hook, $dashboard_pages ) ){
			wp_enqueue_script( 'magnific-popup', EMALLSHOP_SCRIPTS . '/jquery.magnific-popup.min.js');
			wp_enqueue_style( 'magnific-popup', EMALLSHOP_STYLES . '/magnific-popup.css', array(), '' );
		}
		
		//Admin js
		wp_enqueue_script( 'emallshop-admin' , EMALLSHOP_SCRIPTS . '/admin.js', array( 'jquery' ), '', true );
		
		wp_localize_script( 'emallshop-admin', 'emallshop_admin_vars', array(
			'import_options_msg' 	=> esc_html__('WARNING: Clicking this button will replace your current theme options, sliders.  It can also take a minute to complete. Importing data is recommended on fresh installs only once. Importing on sites with content or importing twice will duplicate menus, pages and all posts.', 'emallshop'),
			'theme_option_url' 		=> admin_url('admin.php?page=theme_options'),
			'ajaxurl'          		=> admin_url( 'admin-ajax.php' ),
			'nonce'            		=> wp_create_nonce( 'emallshop_nonce' ),
			'loading_text'      	=> esc_html__( 'Loading...', 'emallshop' ),
			'bindmessage'      		=> esc_html__( 'Are you sure you want to leave?', 'emallshop' ),
			'demo_success'      	=> esc_html__( 'Demo imported successfully.', 'emallshop' ),
			'enter_purchase_code'   => esc_html__( 'Please Enter Purchase Code', 'emallshop' ),
			'empty_purchase_code'   => esc_html__( 'Purchase code is empty', 'emallshop' ),
		) );
	}
}
add_action('admin_enqueue_scripts', 'emallshop_admin_scripts');

/**
 * Load custom js in footer
 * @since 1.0.0
 * @return void
 */
function emallshop_owl_footer() {
	global $emallshop_owlparam;
	wp_localize_script( 'emallshop-script', 'emallshopOwlArg', ( array ) $emallshop_owlparam );
}
add_action( 'wp_footer', 'emallshop_owl_footer' );

/*
* custome theme fucntion 
*/

/* 	Topbar Customer support
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_customer_support' ) ):
	function emallshop_customer_support(){?>
		<?php if(emallshop_get_option('show-topbar-email', 1) ==1 || emallshop_get_option('show-topbar-number', 1) ==1):?>
			<div class="customer-support">
				<?php if(emallshop_get_option('show-topbar-email', 1) ==1 ):?>
					<div class="customer-support-email"><i class="fa fa-envelope"></i><span><?php echo esc_html(emallshop_get_option('topbar-email','info@example.com') );?></span></div>
				<?php endif;?>
				<?php if(emallshop_get_option('show-topbar-number', 1) ==1):?>
					<div class="customer-support-call"><i class="fa fa-phone"></i><span><?php echo esc_html( emallshop_get_option('topbar-number','+81 59832452528') );?></span></div>
				<?php endif;?>
			</div>
		<?php endif;?>
	<?php }
endif;

/* 	Topbar notification
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_topbar_notification' ) ):
	function emallshop_topbar_notification(){
		
		if(!emallshop_get_option('show-topbar-news', 1)) return;?>
		
		<div class="topbar-notification">
			<div class="news-title">
				<i class="fa fa-rss"></i>
				<span><?php esc_html_e('News','emallshop');?></span>
			</div>
			<div class="news-text">
				<marquee behavior="scroll" direction="left"><span class="break-new"><?php echo wp_kses( emallshop_get_option("topbar-news","<a href='#'>Super Sale 50%</a><a href='#'>Big Promotion on Valentine days</a><a href='#'>Gift 15 Voucher for</a>"), emallshop_allowed_html( 'a','div','span','strong' ) );?> </span></marquee>
			</div>
		</div>
	<?php }
endif;

/* 	Topbar Social link
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_social_link' ) ):
	function emallshop_social_link(){
		
		if( !emallshop_get_option( 'show-topbar-social-link', 1 ) ) return;?>
		
		<ul class="social-link">
			<?php if( emallshop_get_option('facebook_link') !='' ):?>
				<li class="icon-facebook"><a target="_blank" href="<?php echo esc_url( emallshop_get_option('facebook_link') );?>"><i class="fa fa-facebook"></i></a></li>
			<?php endif;?>
			<?php if( emallshop_get_option('twitter_link') !='' ):?>
				<li class="icon-twitter"><a target="_blank" href="<?php echo esc_url( emallshop_get_option('twitter_link') );?>"><i class="fa fa-twitter"></i></a></li>
			<?php endif;?>
			<?php if( emallshop_get_option('instagram_link') !='' ):?>
				<li class="icon-instagram"><a target="_blank" href="<?php echo esc_url( emallshop_get_option('instagram_link') );?>"><i class="fa fa-instagram"></i></a></li>
			<?php endif;?>
			<?php if( emallshop_get_option('linkedin_link') !='' ):?>
				<li class="icon-linkedin"><a target="_blank" href="<?php echo esc_url( emallshop_get_option('linkedin_link') );?>"><i class="fa fa-linkedin"></i></a></li>
			<?php endif;?>
			<?php if( emallshop_get_option('flickr_link') !='' ):?>
				<li class="icon-flickr"><a target="_blank" href="<?php echo esc_url( emallshop_get_option('flickr_link') );?>"><i class="fa fa-flickr"></i></a></li>
			<?php endif;?>
			<?php if( emallshop_get_option('youtube_link') !='' ):?>
				<li class="icon-youtube"><a target="_blank" href="<?php echo esc_url( emallshop_get_option('youtube_link') );?>"><i class="fa fa-youtube"></i></a></li>
			<?php endif;?>
			<?php if( emallshop_get_option('rss_link') !='' ):?>
				<li class="icon-rss"><a target="_blank" href="<?php echo esc_url( emallshop_get_option('rss_link') );?>"><i class="fa fa-rss"></i></a></li>
			<?php endif;?>
			<?php if( emallshop_get_option('pinterest_link') !='' ):?>
				<li class="icon-pinterest"><a target="_blank" href="<?php echo esc_url( emallshop_get_option('pinterest_link') );?>"><i class="fa fa-pinterest"></i></a></li>
			<?php endif;?>
			<?php if( emallshop_get_option('github_link') !='' ):?>
				<li class="icon-github"><a target="_blank" href="<?php echo esc_url( emallshop_get_option('github_link') );?>"><i class="fa fa-github"></i></a></li>
			<?php endif;?>
			<?php if( emallshop_get_option('whatsapp_link') !='' ):?>
				<li class="icon-whatsapp"><a target="_blank" href="<?php echo esc_url( emallshop_get_option('whatsapp_link') );?>"><i class="fa fa-whatsapp"></i></a></li>
			<?php endif;?>
		</ul>
	<?php }
endif;

/* 	Topbar Welcome Message
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_welcome_message' ) ):
	function emallshop_welcome_message(){
		
		if(!emallshop_get_option('show-topbar-welcome-message', 0)) return;?>
		
		<div class="topbar-welcome-message">
			<span class="welcome-message-text"><?php echo wp_kses_post( emallshop_get_option( 'topbar-welcome-message', 'Welcome to my Shop' ) );?> </span>
		</div>
	<?php }
endif;

/* 	Topbar user login
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_login' ) ){
	function emallshop_login(){
		$myaccount_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );	?>
		<span class="user-login <?php echo esc_html( emallshop_get_option( 'show-login-register-popup', 1 ) && !is_user_logged_in() ) ? 'enable' : '';?>">
			<?php
			if(!is_user_logged_in()):?>
				<a href="<?php echo esc_url($myaccount_url);?>"><i class="fa fa-lock"></i><span><?php esc_html_e( 'Login/Register', 'emallshop' );?></span></a>
			<?php else:?>
				<a href="<?php echo esc_url( wp_logout_url( get_permalink() ) );?>"><i class="fa fa-unlock"></i><span><?php esc_html_e( 'Logout', 'emallshop' );?></span></a>
			<?php endif;?>
		</span>
	<?php }
}

/* 	EmallShop Registration
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_register' ) ){
	function emallshop_register(){
		if(!is_user_logged_in()):?>
			<span class="user-register">
				<a href="#">
					<i class="fa fa-user-plus"></i>
					<span class="user-register-text"><?php esc_html_e('Register','emallshop');?></span>
				</a>
			</span>
	<?php endif;
	}
}

/* 	woocommerce myaccount
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_all_myaccount' ) ):

	function emallshop_all_myaccount(){
		
		if( ! is_woocommerce_activated() ){ return false; }
		
		global $woocommerce;
		$myaccount_page_url = get_permalink( get_option('woocommerce_myaccount_page_id') );
		$cart_url = wc_get_cart_url();
		$checkout_url = wc_get_checkout_url();	?>
		<div class="wcaccount-topbar">
			
			<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ) ;?>"><i class="fa fa-user"></i><span><?php esc_html_e('My Account','emallshop');?> </span><i class="fa fa-caret-down"></i></a>
			
			<ul class="wcaccount-dropdown">
				<li><a href="<?php echo esc_url( $checkout_url );?>"><i class="fa fa-check-square-o"></i><span><?php esc_html_e('Checkout','emallshop');?></span></a></li>
				<li><a href="<?php echo esc_url( $cart_url );?>"><i class="fa fa-shopping-cart"></i><span><?php esc_html_e('Cart','emallshop');?></span></a></li>
				
				<?php if( function_exists( 'YITH_WCWL' ) ):
					$wishlist_url = YITH_WCWL()->get_wishlist_url(); ?>
					<li class=""><a href="<?php echo esc_url($wishlist_url);?>"><i class="fa fa-heart"></i><span><?php esc_html_e('My Wishlist','emallshop');?></span> (<samp class="wishlist-count"><?php echo YITH_WCWL()->count_products();?></samp>)</a></li>
				<?php endif; ?>
				
				<?php if(defined( 'YITH_WOOCOMPARE' )): 
					global $yith_woocompare; 
					$product_count = count($yith_woocompare->obj->products_list); ?>
					<li><a href="#" class="yith-woocompare-open"><i class="fa fa-refresh"></i><span><?php esc_html_e( "Compare", 'emallshop') ?></span> (<samp class="compare-count"><?php echo esc_html( $product_count ); ?></samp>)</a></li>
				<?php endif; ?>
				<?php if(is_user_logged_in()):?>
					<li><a href="<?php echo esc_url(wp_logout_url(get_permalink()));?>"><i class="fa fa-unlock"></i><span><?php esc_html_e('sign Out','emallshop');?></span></a></li>
				<?php endif;?>
			</ul>
		</div>
	<?php }
endif;

/* 	EmallShop My Account
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_myaccount' ) ){
	function emallshop_myaccount(){
		
		if(!is_woocommerce_activated()) return false;?>
		
		<span class="header-myaccount">
			<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) );?>">
				<i class="fa fa-user"></i>
				<span class="header-myaccount-text"><?php esc_html_e('My Account','emallshop');?></span>
			</a>
		</span>
		<?php 
	}
}

/* 	EmallShop Cart
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_cart' ) ){
	function emallshop_cart(){
		
		if(!is_woocommerce_activated()) return false;
		
		global $woocommerce;
		$cart_url = wc_get_cart_url();?>
		<span class="topbar-cart">
			<a href="<?php echo esc_url($cart_url);?>">
				<i class="fa fa-shopping-cart"></i>
				<samp class="mini-cart-count"><?php echo esc_attr($woocommerce->cart->cart_contents_count);?></samp>
				<span class="header-cart-text"><?php esc_html_e('Shopping Cart','emallshop');?></span>
			</a>
		</span><?php
	}
}

/* 	EmallShop Checkout
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_checkout' ) ){
	function emallshop_checkout(){
		
		if(!is_woocommerce_activated()) return false;
		
		$checkout_url = wc_get_checkout_url();?>
		<span class="header-checkout">
			<a href="<?php echo esc_url($checkout_url);?>">
				<i class="fa fa-check-square-o"></i>
				<span class="header-checkout-text"><?php esc_html_e('Checkout','emallshop');?></span>
			</a>
		</span><?php
	}
}

/* 	EmallShop Track Order
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_track_order' ) ){
	function emallshop_track_order(){
		
		if(!is_woocommerce_activated()) return false;?>
		
		<span class="header-track-order">
			<a href="<?php echo esc_url(emallshop_get_url_by_shortcode('[woocommerce_order_tracking]')); ?>">
				<i class="fa fa-truck"></i>
				<span class="header-track-order-text"><?php esc_html_e('Track Order','emallshop');?></span>
			</a>
		</span><?php
	}
}

/* 	EmallShop Wishlist
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_wishlist' ) ){
	function emallshop_wishlist(){
		
		if(!emallshop_get_option('show-header-wishlist', 1)) return; 
		
		if( function_exists( 'YITH_WCWL' ) ):
			$wishlist_url = YITH_WCWL()->get_wishlist_url(); ?>
			<span class="header-wishlist">
				<a href="<?php echo esc_url($wishlist_url);?>">
					<i class="fa fa-heart"></i>
					<samp class="wishlist-count"><?php echo esc_html( YITH_WCWL()->count_products() );?></samp>
					<span class="header-wishlist-text"><?php esc_html_e('Wishlist','emallshop');?></span>
				</a>
			</span>
		<?php endif;
	}
}

/* 	EmallShop Campare
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_campare' ) ){
	function emallshop_campare(){
		
		if(!emallshop_get_option('show-header-campare', 1)) return;
				
		if( defined( 'YITH_WOOCOMPARE' )): 
			global $yith_woocompare; 
			$product_count = count( $yith_woocompare->obj->products_list ); ?>
			<span class="header-compare">
				<a href="#" class="yith-woocompare-open">
					<i class="fa fa-refresh"></i>
					<samp class="compare-count"><?php echo esc_html( $product_count ); ?></samp>
					<span class="header-compare-text"><?php esc_html_e( "Compare", 'emallshop') ?></span>
				</a>
			</span>
		<?php endif;
	}
}

/* Topbar currency
 * @version 2.0
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_currency' ) ):
	function emallshop_currency(){
		
		if(!emallshop_get_option('show-currency-switcher',1)) return; 		
		
		if (class_exists('woocommerce_wpml')) { ?>
			<span class="currency-topbar">
				<?php echo(do_shortcode('[currency_switcher]')); ?>					
			</span>				
		<?php }elseif (class_exists('woocs')){?>
			<span class="currency-topbar">
				<?php echo(do_shortcode('[woocs]'));?>
			</span>
		<?php }			
	}
endif;

/* 	Topbar language
 *	@version 2.0
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_language' ) ):
	function emallshop_language(){
		
		if(!emallshop_get_option('show-language-switcher',1)) return; 		
		
		if (class_exists('SitePress')) {?>
			<span class="language-topbar">
				<?php do_action('wpml_add_language_selector'); ?>				
			</span>
		<?php }
	}
endif;

/* 	EmallShop help
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_help' ) ){
	function emallshop_help(){
		global $emallshop?>
		<span class="header-help">
			<a href="<?php echo esc_url(emallshop_get_option('topbar-help', '#')); ?>">
				<i class="fa fa-question-circle"></i>
				<span class="header-help-text"><?php esc_html_e('Help','emallshop');?></span>
			</a>
		</span><?php
	}
}

/* 	Header Logo
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_header_logo' ) ){
	function emallshop_header_logo(){?>	
		<div class="header-logo">
			<?php $header_logo 	= emallshop_get_option('header-logo', array( 'url' => EMALLSHOP_IMAGES.'/logo.png' ) );
			$sticky_header_logo = emallshop_get_option('sticky-header-logo', array( 'url' => EMALLSHOP_IMAGES.'/logo.png' ));?>
			<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"> <img src="<?php echo esc_url($header_logo['url']);?>" alt="<?php esc_attr_e('logo','emallshop');?>"></a>
			
			<a class="sticky-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"> <img src="<?php echo esc_url($sticky_header_logo['url']);?>" alt="<?php esc_attr_e('logo','emallshop');?>"></a>
			
		</div>
	<?php }
}

/* 	Header Services
 *
 * @since EmallShop 2.0
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_header_services' ) ):
	function emallshop_header_services(){?>
		
		<div class="header-services">
			<div class="box-service">
				<span class="icon-service">
					<i class="fa <?php echo esc_attr( emallshop_get_option('service-icon1', 'fa-phone') ); ?>"> </i>
				</span>
				<div class="content-service">
					<h6><?php echo esc_html( emallshop_get_option('service-title1', '08 143 456 753') );?></h6>
					<span><?php echo esc_html( emallshop_get_option('service-des1', 'lorem ipsum dolor.') );?></span>
				</div>
			</div>
			<div class="box-service">
				<span class="icon-service">
					<i class="fa <?php echo esc_html( emallshop_get_option('service-icon2', 'fa-truck') );?>"> </i>
				</span>
				<div class="content-service">
					<h6><?php echo esc_html( emallshop_get_option('service-title2', esc_html__('Free Shipping','emallshop') ) );?></h6>
					<span><?php echo esc_html( emallshop_get_option('service-des2', esc_html__('all order over $100.','emallshop')) );?></span>
				</div>
			</div>
			<div class="box-service">
				<span class="icon-service">
					<i class="fa <?php echo esc_attr( emallshop_get_option('service-icon3', 'fa-refresh') ); ?>"> </i>
				</span>
				<div class="content-service">
					<h6><?php echo esc_html( emallshop_get_option('service-title3', esc_html__('Free Shipping','emallshop')) );?></h6>
					<span><?php echo esc_html( emallshop_get_option('service-des3', esc_html__('Return & Exchange','emallshop')) );?></span>
				</div>
			</div>
		</div>
	<?php }
endif;

/* 	Header cart
 *
 * @since EmallShop 2.0
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_header_cart' ) ){
	function emallshop_header_cart(){
		
		if ( !class_exists( 'WooCommerce' ) || ! emallshop_get_option( 'show-header-cart', 1 ) ) return;
				
		global $woocommerce;
		$header_style = emallshop_get_option( 'header-layout', 'header-1' );
		$count 				= is_object( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;?>
		<div class="header-cart-content">
			<?php if( $header_style == 'header-5' || $header_style == 'header-10' ){?>			
				<div class="heading-cart cart-style-1">
					<a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>">
						<span class="cart-icon fa fa-shopping-cart"></span>
						<span class="mini-cart-count"><?php echo sprintf(_n('%d item', '%d item(s)', wp_kses_post( $count ), 'emallshop'), wp_kses_post( $count ) );?> - <?php echo wp_kses_post( WC()->cart->get_cart_subtotal() ); ?></span>
					</a>
				</div>				
			<?php }elseif( $header_style == 'header-1' || $header_style == 'header-2' || $header_style == 'header-3' || $header_style == 'header-11' ){?>
				<span class="header-cart cart-style-2">
					<a href="<?php echo esc_url(wc_get_cart_url()); ?>">
						<i class="fa fa-shopping-cart"></i>
						<samp class="mini-cart-count"><?php echo esc_attr($woocommerce->cart->cart_contents_count);?></samp>
						<span class="header-cart-text"><?php esc_html_e('Cart','emallshop');?></span>
					</a>
				</span>			
			<?php }elseif( $header_style == 'header-4' || $header_style == 'header-6' || $header_style == 'header-7' || $header_style == 'header-8' || $header_style == 'header-9' ){?>
				<div class="heading-cart cart-style-3">
					<i class="fa fa-shopping-cart"></i>
					<a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>">
						<h6><?php esc_html_e('Shopping Cart','emallshop');?></h6>
						<span class="mini-cart-count"><?php echo sprintf(_n('%d item', '%d item(s)', $woocommerce->cart->cart_contents_count, 'emallshop' ), $woocommerce->cart->cart_contents_count);?> - <?php echo wp_kses_post( $woocommerce->cart->get_cart_total() ); ?></span>
					</a>
				
				</div>
			<?php }?>
			<div class="header_shopping_cart woocommerce">
				<div class="widget_shopping_cart_content">
					<?php woocommerce_mini_cart();?>
				</div>
				<div class="pl-loading"></div>
			</div>
		</div>
	<?php 
	}
}

/* 	Header Default Search
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_default_search' ) ):
	function emallshop_default_search(){?>
		<div class="default-search-wrapper">
			<div class="search-toggle">
				<a href="#search-container" class="screen-reader-text" aria-expanded="false" aria-controls="search-container"><?php esc_html_e( 'Search', 'emallshop' ); ?></a>
			</div>
			<div id="search-container" class="search-box-wrapper hide">
				<div class="search-box">
					<?php get_search_form(); ?>
				</div>
			</div>
		</div>
	<?php }
endif;

/* 	Topbar menu
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_topbar_menu' ) ):
	function emallshop_topbar_menu(){
		
		if ( has_nav_menu( 'topbar_menu' ) ) { ?>
			<div class="topbar-menu" role="navigation">		
				<?php wp_nav_menu( array( 'theme_location' 	=> 'topbar_menu',
										'menu_class'      	=> 'topbar-navigation',
								  ) ); ?>
			</div>
	<?php }
	}
endif;

/* 	Header menu
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_header_menu' ) ):
	function emallshop_header_menu(){
		
		$admin_menu_link = get_admin_url( null, 'nav-menus.php' ); ?>
		
		<div class="header-main-navigation" role="navigation">
			<?php if ( has_nav_menu( 'primary' ) ) { ?>						
				<?php wp_nav_menu( array( 
					'theme_location' 	=> 'primary',
						'menu_class'      	=> 'emallshop-horizontal-menu main-navigation',
						'container_class'	=> 'emallshop-main-menu hidden-xs hidden-sm',
						'fallback_cb' 		=> 'EmallShopFrontendWalker::fallback',
						'walker' 			=> new EmallShopFrontendWalker(),
				) ); ?>
			<?php }else{ ?>
				<span class="add-navigation-message">
					<?php printf( wp_kses( __('Add your <a href="%s">navigation menu here</a>', 'emallshop' ),array( 'a' => array( 'href' => array() )	) )	, $admin_menu_link );	?>
				</span>
			<?php } ?>
		</div>
	<?php }
endif;

/* 	Header Category/Vertical menu
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_category_menu' ) ):
	function emallshop_category_menu( $title = null ){ 
	
		if ( ! emallshop_get_option('show-categories-menu', 1 ) ) return;
		
		if ( has_nav_menu( 'vertical_menu' ) ) { ?>
			<div class="category-menu open">
				<div class="category-menu-title">
					<h4><?php echo ( esc_html($title) != null ) ? esc_attr($title) : emallshop_get_option('shopping-categories-text', esc_html__('Shopping Categories','emallshop'));?></h4>
					<span class="down-up"><i class="fa fa-list"></i></span>
				</div>
				<div class="categories-list">
				<?php	wp_nav_menu( array( 
							'theme_location' 	=> 'vertical_menu',
							'menu_class'      	=> 'emallshop-vertical-menu main-navigation',
							'container_class'	=> 'emallshop-main-menu',
							'fallback_cb' 		=> 'EmallShopFrontendWalker::fallback',
							'walker' 			=> new EmallShopFrontendWalker(),
						) );  ?>
				</div>
			</div>
	<?php }
	}
endif;

/* 	Header Mobile Toggle Bar Icon
 *
 * @since EmallShop 2.0
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_header_mobile_toggle' ) ):
	function emallshop_header_mobile_toggle(){?>
		<div class="navbar-toggle">
			<span class="sr-only"><?php esc_html_e('Menu', 'emallshop'); ?></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</div>
	<?php }
endif;

/* 	Header Mobile menu
 *
 * @since EmallShop 2.0
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_header_mobile_menu' ) ):
	function emallshop_header_mobile_menu(){
		
		if( function_exists( 'emallshop_products_live_search_form' ) ) {
			emallshop_products_live_search_form( $arg="product_cat2" );
		}?>
		
		<div class="mobile-nav-tabs">
			<ul>
				<li class="primary-menu active" data-menu="primary"><span><?php esc_html_e('Menu','emallshop');?></span></li>
				<?php if ( emallshop_get_option('show-categories-menu', 1 ) && has_nav_menu( 'vertical_menu' ) ) {?>
					<li class=" categories-menu" data-menu="vertical"><span><?php esc_html_e('Categories','emallshop');?></span></li>
				<?php }?>
			</ul>
		</div>
		
		<?php 
		$admin_menu_link = get_admin_url( null, 'nav-menus.php' );
		if ( has_nav_menu( 'primary' ) ) {					
			wp_nav_menu( array( 'theme_location' 	=> 'primary',
				'menu_class'      	=> 'mobile-main-menu',
				'container_class'	=> 'mobile-primary-menu mobile-nav-content active',
			) ); 			
		}else{ ?>
			<div class="mobile-primary-menu mobile-nav-content active">
				<span class="add-navigation-message">
					<?php printf( wp_kses( __('Add your <a href="%s">navigation menu here</a>', 'emallshop' ),array( 'a' => array( 'href' => array() )	) )	, $admin_menu_link );	?>
				</span>
			</div>
		<?php }
	
		if ( emallshop_get_option('show-categories-menu', 1) && has_nav_menu( 'vertical_menu' ) ) {
			wp_nav_menu( array( 'theme_location' 	=> 'vertical_menu',
				'menu_class'      	=> 'mobile-main-menu',
				'container_class'	=> 'mobile-vertical-menu mobile-nav-content',
			) );
		}?>	
		
		<div class="mobile-topbar-wrapper">
			<?php if( function_exists( 'emallshop_dokan_header_user_menu' ) ) {
				emallshop_dokan_header_user_menu();
			}else{					
				if( function_exists( 'emallshop_myaccount' ) ) {
					emallshop_myaccount();
				}
				if( function_exists( 'emallshop_checkout' ) ) {
					emallshop_checkout();
				}
				if( function_exists( 'emallshop_wishlist' ) ) {
					emallshop_wishlist();
				}
				if( function_exists( 'emallshop_campare' ) ) {
					emallshop_campare();
				}
				if( function_exists( 'emallshop_login' ) ) {
					emallshop_login();
				}
			}
			if( function_exists( 'emallshop_currency' ) ) {
				emallshop_currency();
			}
			if( function_exists( 'emallshop_language' ) ) {
				emallshop_language();
			}?>
		</div>
		<div class="mobile-topbar-social">
			<?php if( function_exists( 'emallshop_social_link' ) ) {
				emallshop_social_link();
			}?>
		</div>
		
	<?php }
endif;

/* 	Sub Categories
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_sub_category' ) ):
	function emallshop_sub_category(){
		
		$categories = get_categories( array( 'parent' => $parent_arg , 'hide_empty' => 1, 'taxonomy' => 'product_cat' ) ); 
	}
endif;

/* 	Page / Post colunm class
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_getColumnClass')) {
	function emallshop_getColumnClass($sidebar_position){
		if ( (isset($sidebar_position) && $sidebar_position == 'none') || (function_exists('is_cart') && is_cart()) || (function_exists('is_checkout') && is_checkout()) || (function_exists('is_account_page') && is_account_page()) || (function_exists('is_order_tracking') && is_order_tracking()) || (is_dokan_activated() && is_page( 'dashboard' )) || (is_dokan_activated() && is_page( 'store-listing' )) || ( is_WC_Marketplace_activated() && is_vendor_page() )) :
				$column_classs="col-md-12";
		elseif(isset($sidebar_position) && $sidebar_position=='left'):
			if(is_rtl()){
				$column_classs="col-xs-12 col-sm-8 col-md-9 col-md-pull-3";
			}else{
					$column_classs="col-xs-12 col-sm-8 col-md-9 col-md-push-3";
			}
		else:
			$column_classs="col-xs-12 col-sm-8 col-md-9";
		endif;
		
		return $column_classs;
	}
}

/* 	Sidebar position
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_getSidebarPosition')) {
	function emallshop_getSidebarPosition($sidebar_position){
		
		return (isset($sidebar_position) && $sidebar_position !='') ? $sidebar_position : "right";
	}
}

/* 	Sidebar Widget
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_getSidebarWidget')) {
	function emallshop_getSidebarWidget($sidebar_widget){
		
		return (isset($sidebar_widget) && $sidebar_widget !='') ? $sidebar_widget : "sidebar-1";
	}
}

/* 	Show Breadcrumb
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_getShowBreadsrumb')) {
	function emallshop_getShowBreadsrumb($show_breadsrumb){
		
		return (isset($show_breadsrumb) && $show_breadsrumb !='') ? $show_breadsrumb : "yes";
	}
}

/* 	Sidebar Widget
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_getShowTitle')) {
	function emallshop_getShowTitle($show_title){
		
		return (isset($show_title) && $show_title !='') ? $show_title : "yes";
	}
}

/* 	Portfolio style of custom template
/* --------------------------------------------------------------------- */

if ( !function_exists('emallshop_getPortfolioStyle')) {
	function emallshop_getPortfolioStyle($grid_column)
	{		
		if(isset($grid_column)):
			$grid_column='portfolio_'.$grid_column.'_column';	
		else:
			$grid_column='portfolio_two_column';
		endif;
		return $grid_column;
	}
}

/* 	Set the number of a portfolio post type posts per page
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_portfolio_archive_query')) {
	function emallshop_portfolio_archive_query( $query ) {
		if( $query->is_main_query() && $query->is_post_type_archive('portfolio') ) {
			$query->set( 'posts_per_page', emallshop_get_option('portfolio-per-page', 10) );
		}
	}
}
add_filter( 'pre_get_posts', 'emallshop_portfolio_archive_query' );


/* 	Get post thumbnail
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_get_post_thumbnail')) {
	function emallshop_get_post_thumbnail($image_size){
		$prefix = 'es_';
		switch(get_post_format()) {
			
			case 'image' :
					$attachment_id=get_post_meta ( get_the_ID(), $prefix .'post_format_image', true )  ;
					if ( is_singular() ) : ?>
						<div class="post-thumbnail">
							<?php if(wp_get_attachment_url( $attachment_id )) :
								 echo wp_get_attachment_image( $attachment_id, 'large' );
							else:?>
								<img src="<?php echo esc_url(EMALLSHOP_IMAGES.'/blog-placeholder.jpg');?>"/>
							<?php endif;?>
						</div><!-- .post-thumbnail -->

						<?php else : ?>
						
						<div class="entry-thumbnail">
							<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
								<?php if(wp_get_attachment_url( $attachment_id )) :
									echo wp_get_attachment_image( $attachment_id, $image_size );
								else:?>
									<img src="<?php echo esc_url(EMALLSHOP_IMAGES.'/blog-placeholder.jpg');?>"/>
								<?php endif;?>
							</a>							
						</div>
					<?php endif; // End is_singular()
					break;			
			case 'gallery' :
					$attachment_ids=get_post_meta ( get_the_ID(), $prefix .'post_format_gallery' );
					$output = '';
					if (!empty($attachment_ids) && is_array($attachment_ids)) {
						$output .= "<div class='post-slider entry-media owl-carousel'>";
						foreach ($attachment_ids as $attachment_id) {
							if (is_single()) {
								$output .= "<div class='item'>";
									$output.= wp_get_attachment_image( $attachment_id, 'large' );
							$output .= "</div>";
							} else {
								$output .= "<div class='item'>";
								$output .= '<a data-group="entry-'. esc_attr(get_the_ID()) .'" class="single-image" href="'. esc_url( get_permalink(get_the_ID()) ) .'">';
									$output.= wp_get_attachment_image( $attachment_id,  $image_size );
								$output .= '</a>';
							$output .= "</div>";
							}				
						}
						$output .= "</div>";
						echo apply_filters( 'emallshop_post_gallery', $output ); // WPCS: XSS OK.
					}
					break;
			case 'video' :
						$video_url_embed=get_post_meta ( get_the_ID(), $prefix .'post_format_video', true ) ;
						$video_url_embed = preg_replace( '|^\s*(https?://[^\s"]+)\s*$|im', "[embed]$1[/embed]", strip_tags($video_url_embed));
						preg_match("!\[embed.+?\]|\[video.+?\]!", $video_url_embed, $match_video);
						$output = '';
						if (!empty($match_video)) {
							global $wp_embed;

							$image_size = emallshop_get_image_size($image_size);
							$video = $match_video[0];
							$video = str_replace('[embed]', '[embed width="'. $image_size['width'] .'" height="'. $image_size['height'] .'"]', $video);

							$output = "<div class='entry-media'>";
								$output .= do_shortcode($wp_embed->run_shortcode($video));
							$output .= "</div>";
						}
						echo apply_filters( 'emallshop_post_video', $output ); // WPCS: XSS OK.
					break;
					
			case 'audio' :
					$audio_url_embed=get_post_meta ( get_the_ID(), $prefix .'post_format_audio', true ) ;
					$output = '';					
					$audio_url_embed = preg_replace( '|^\s*(http?://[^\s"]+)\s*$|im', "[audio src='$1']", strip_tags($audio_url_embed ) );
					preg_match("!\[audio.+?\]!", $audio_url_embed, $match_audio);
					preg_match("!\[embed.+?\]!", $audio_url_embed, $match_embed);

					if (!empty($match_embed) && strpos($match_embed[0], 'soundcloud.com') !== false) {
						global $wp_embed;
						$image_size = emallshop_get_image_size($image_size);
						$embed = $match_embed[0];
						$embed = str_replace('[embed]', '[embed width="'. $image_size['width'] .'" height="250"]', $embed);

						$output = "<div class='entry-media'>";
							$output .= $wp_embed->run_shortcode($embed);
						$output .= "</div>";
						
					} else if (!empty($match_audio)) {
						$output = "<div class='entry-media'>";
							$output .= do_shortcode($match_audio[0]);
						$output .= "</div>";
					}
					echo apply_filters( 'emallshop_post_audio', $output ); // WPCS: XSS OK.
					break;
					
			case 'link' :
					$link_url=get_post_meta ( get_the_ID(), $prefix .'post_format_link_url', true ) ;
					$link_text=get_post_meta ( get_the_ID(), $prefix .'post_format_link_text', true ) ;
					$output = '';
					if(isset($link_url) && $link_url != ''):
						$output = '<div class="entry-media">';
							$output .= '<div class="post-link">';
								$output .= '<a href="'.esc_url( $link_url ).'" alt="'.esc_attr( $link_text).'">'.esc_html( $link_text ).'</a>';
							$output .= '</div>';
						$output .= '</div>';
					endif;
					
					echo apply_filters( 'emallshop_post_link', $output ); // WPCS: XSS OK.
					break;
					
			case 'quote' :	
					$quote=get_post_meta ( get_the_ID(), $prefix .'post_format_quote', true ) ;
					$quote_author=get_post_meta ( get_the_ID(), $prefix .'post_format_quote_author', true ) ;
					$quote_author_url=get_post_meta ( get_the_ID(), $prefix .'post_format_quote_author_url', true ) ;
					$output = '';
					if(isset($quote) && $quote !=''):
						$output = '<div class="entry-media">';
							$output .= '<blockquote>';
								$output .= $quote;
								$output .='<br><a href="'.esc_url( $quote_author_url ).'" alt="'.esc_attr( $quote_author ).'">'.esc_html( $quote_author ).'</a>';
							$output .= '</blockquote>';
						$output .= '</div>';
					endif;
					
					echo apply_filters( 'emallshop_post_quote', $output ); // WPCS: XSS OK.
					break;
			default:
				if( $image_size== 'medium' ):
					emallshop_small_post_thumbnail();
				else:
					emallshop_post_thumbnail();
				 endif;
				 
				 break;				 
		}		
	}
}

// Add SoundCloud oEmbed
function emallshop_add_oembed_soundcloud(){
	wp_oembed_add_provider( 'http://soundcloud.com/*', 'http://soundcloud.com/oembed' );
}
add_action('init','emallshop_add_oembed_soundcloud');

/* 	Get Excerpt content
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_excerpt')) {
	function emallshop_excerpt($limit) {
		$excerpt = explode(' ', get_the_excerpt(), $limit);
		if (count($excerpt)>=$limit) {
			array_pop($excerpt);
			$excerpt = implode(" ",$excerpt).'...';
		} else {
		$excerpt = implode(" ",$excerpt);
		}	
		$excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
		return $excerpt;
	}
}


/* 	Get related post
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_getRelatedPosts')) {
	function emallshop_getRelatedPosts($post_id) {
		
		$args = '';
	
		$item_cats = get_the_terms($post_id, 'category');
		if ($item_cats) :
			foreach($item_cats as $item_cat) {
				$item_array[] = $item_cat->term_id;
			}
		endif;
		
		$args = wp_parse_args($args, array(
			'showposts' => '10',
			'post__not_in' => array($post_id),
			'ignore_sticky_posts' => 0,
			'post_type' => 'post',
			'tax_query' => array(
				array(
					'taxonomy' => 'category',
					'field' => 'id',
					'terms' => $item_array
				)
			),
			'orderby' => 'DESC'
		));
	
		$query = new WP_Query($args);
		wp_reset_query();
		return $query;
	}
}

/* 	Get related portfolio
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_getRelatedPortfolios')) {
	function emallshop_getRelatedPortfolios($post_id) {
		$args = '';
	
		$item_cats = get_the_terms($post_id, 'portfolio_cat');
		if ($item_cats) :
			foreach($item_cats as $item_cat) {
				$item_array[] = $item_cat->term_id;
			}
		endif;
		
		$args = wp_parse_args($args, array(
			'showposts' => emallshop_get_option('show-related-Projects', 1),
			'post__not_in' => array($post_id),
			'ignore_sticky_posts' => 0,
			'post_type' => 'portfolio',
			'tax_query' => array(
				array(
					'taxonomy' => 'portfolio_cat',
					'field' => 'id',
					'terms' => $item_array
				)
			),
			'orderby' => 'DESC'
		));
	
		$query = new WP_Query($args);
		wp_reset_query();
		return $query;
	}
}

/* 	get post image size
/* ---------------------------------------------------------------------- */
if (!function_exists('emallshop_get_image_size')) {

	function emallshop_get_image_size( $image_type ) {
		
		if($image_type=='medium'){
			$image_size=array('width'=>'463', 'height'=>'348', 'image_type'=>$image_type);
		}elseif($image_type=='large'){
			$image_size=array('width'=>'870', 'height'=>'510', 'image_type'=>$image_type);
		}
		return $image_size;
	}
}

if( ! function_exists( 'emallshop_ajax_load_more_pagination' ) ){
	function emallshop_ajax_load_more_pagination(){
	
		$load_more_option=array();
		$ajax_pagination=array();
		
		if( emallshop_get_option('blogs-pagination-type','default_pagination')!="default_pagination" || ( isset($GLOBALS['blog_pagination']) && $GLOBALS['blog_pagination']!="default_pagination")):
			$load_more_options[]=emallshop_load_more_blogs();
		endif;
		
		if( emallshop_get_option('portfolio-pagination-type','default_pagination')!="default_pagination" || ( isset($GLOBALS['portfolio_pagination']) && $GLOBALS['portfolio_pagination']!="default_pagination")):
			$load_more_options[]=emallshop_load_more_portfolios();
		endif;
		
		if( is_woocommerce_activated() && emallshop_get_option('product-pagination-style','default_pagination')!="default_pagination" ):
			$load_more_options[]=emallshop_load_more_products();		
		endif;
		
		if(!empty($load_more_options)){
			foreach($load_more_options as $load_more_option){
				$ajax_pagination['pagination_options'][]=array(
					'type'          => $load_more_option['type'],
					'use_mobile'    => $load_more_option['use_mobile'],
					'mobile_type'   => $load_more_option['mobile_type'],
					'mobile_width'  => $load_more_option['mobile_width'],
					'is_AAPF'       => '',
					'buffer'        => 50,

					'load_image'    => $load_more_option['image'],
					'load_img_class'=> '.lmp_products_loading',

					'load_more'     => $load_more_option['load_more_button'],

					'lazy_load'     => $load_more_option['lazy_load'],
					'lazy_load_m'   => $load_more_option['lazy_load_m'],
					'LLanimation'   => $load_more_option['LLanimation'],
				
					'loading'       => $load_more_option['loading'],
					'loading_class' => '',

					'end_text'      => $load_more_option['end_text'],
					'end_text_class'=> '',

					//'javascript'    => $javascript_options,

					'products'      => $load_more_option['products_selector'],
					'item'          => $load_more_option['item_selector'],
					'pagination'    => $load_more_option['pagination_selector'],
					'next_page'     => $load_more_option['next_page_selector'],
				);
			}
		}
		
		wp_localize_script(
			'emallshop-script',
			'pagination_settings',
			( array ) $ajax_pagination
		);
	}
}
add_action ( 'wp_footer', 'emallshop_ajax_load_more_pagination' );

/* 	Ajax Blog Pagination Options
/* --------------------------------------------------------------------- */
function emallshop_load_more_blogs() {
	$load_more_options=array();
	
	if(isset($GLOBALS['blog_pagination'])):
		$load_more_options['type']=$GLOBALS['blog_pagination'];
	else:
		$load_more_options['type']= emallshop_get_option('blogs-pagination-type','default_pagination');
	endif;
	
	$load_more_options['use_mobile']=false;
	$load_more_options['mobile_type']='more_button';
	$load_more_options['mobile_width']=767;
	
	$load_more_text= emallshop_get_option('blog-load-more-button-text',esc_html__('Load More','emallshop'));
	$load_more_options['lazy_load']=false ;
	
	$load_more_options['lazy_load_m']=false;
	
	$load_more_options['LLanimation']='zoomInUp' ;
	
	$loading_image= EMALLSHOP_ADMIN_IMAGES.'/ajax-'.emallshop_get_option('pagination-loading-image','loader').'.gif';
	
	$load_more_options['loading']=esc_html__('Loading...','emallshop');
	$load_more_options['loading_class'] = '';
	$load_more_options['end_text'] = esc_html__('No more blog','emallshop');
	
	$load_more_options['products_selector'] = '.blog-posts';
	$load_more_options['item_selector'] = 'article.post';
	$load_more_options['pagination_selector'] = '.posts-navigation';
	$load_more_options['next_page_selector'] = '.posts-navigation a.next';
		
	$image_class = 'lmp_rotate';
	
	$image = '<div class="lmp_products_loading">';	
	$image .= '<img src="'.esc_url($loading_image).'">';
	$image .= '</div>';
	
	$load_more_options['image']=$image;

	$load_more_button = '<div class="lmp_load_more_button">';
	$load_more_button .= '<a class="lmp_button"';	
	$load_more_button .= ' href="#load_next_page">'.esc_html( $load_more_text ).'</a>';
	$load_more_button .= '</div>';
	
	$load_more_options['load_more_button']=$load_more_button;
	
	return $load_more_options;
	
}

/* 	Ajax Portfolio Pagination Options
/* --------------------------------------------------------------------- */
function emallshop_load_more_portfolios() {
	$load_more_options=array();
	if(isset($GLOBALS['portfolio_pagination'])):
		$load_more_options['type']=$GLOBALS['portfolio_pagination'];
	else:
		$load_more_options['type']= emallshop_get_option('portfolio-pagination-type','default_pagination');
	endif;
	
	$load_more_options['use_mobile']=false;
	$load_more_options['mobile_type']='more_button';
	$load_more_options['mobile_width']=767;
	
	$load_more_text= emallshop_get_option('blog-load-more-button-text',esc_html__('Load More','emallshop'));
	$load_more_options['lazy_load']=false ;
	
	$load_more_options['lazy_load_m']=false;
	
	$load_more_options['LLanimation']='zoomInUp' ;
	
	$loading_image= EMALLSHOP_ADMIN_IMAGES.'/ajax-'.emallshop_get_option('pagination-loading-image','loader').'.gif';
	
	$load_more_options['loading']=esc_html__('Loading...','emallshop');
	$load_more_options['loading_class'] = '';
	$load_more_options['end_text'] = esc_html__('No more blog','emallshop');
	
	$load_more_options['products_selector'] = '.portfolioContainer';
	$load_more_options['item_selector'] = '.portfolio-item';
	$load_more_options['pagination_selector'] = '.posts-navigation';
	$load_more_options['next_page_selector'] = '.posts-navigation a.next';
		
	$image_class = 'lmp_rotate';
	
	$image = '<div class="lmp_products_loading">';	
	$image .= '<img src="'.esc_url($loading_image).'">';
	$image .= '</div>';
	
	$load_more_options['image']=$image;

	$load_more_button = '<div class="lmp_load_more_button">';
	$load_more_button .= '<a class="lmp_button"';	
	$load_more_button .= ' href="#load_next_page">'.esc_html( $load_more_text ).'</a>';
	$load_more_button .= '</div>';
	
	$load_more_options['load_more_button']=$load_more_button;
	
	return $load_more_options;
	
}

/* 	Post navigation
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_post_navigation')) {
	function emallshop_post_navigation($atts, $content = null){
		$next_post = get_next_post();
		$prev_post = get_previous_post();
			if(!empty($prev_post) || !empty($next_post)):
		?>
			<div class="navigation" role="navigation">
				<h3><span><?php esc_html_e('Post Navigation','emallshop');?></span></h3>
				<div class="post-navigation">
					<div class="nav-links">
					<?php if(!empty($prev_post)) : ?>
						<div class="nav-previous">
							<a href="<?php echo esc_url( get_permalink($prev_post->ID) ); ?>" ><span class="meta-nav">&laquo; <?php echo esc_html( get_the_title($prev_post->ID) ); ?></span></a>
							<?php if( get_the_post_thumbnail( $prev_post->ID, 'thumbnail') ){ ?>
								<div class="post-nav-thumb">
									<?php echo get_the_post_thumbnail( $prev_post->ID, 'thumbnail'); ?>
								</div>
							<?php } ?>
						</div>
					<?php endif; ?>
					<?php if(!empty($next_post)) : ?>
						<div class="nav-next">
							<a href="<?php echo esc_url( get_permalink($next_post->ID) ); ?>"><span class="meta-nav"><?php echo get_the_title($next_post->ID); ?>  &raquo;</span></a>
							<?php if( get_the_post_thumbnail( $next_post->ID, 'thumbnail') ){ ?>
								<div class="post-nav-thumb">
									<?php echo get_the_post_thumbnail( $next_post->ID,'thumbnail' ); ?>
								</div>
							<?php } ?>
						</div>
					<?php endif; ?>
					</div>
				</div>
			</div>
		<?php
		endif;
	}
}

/* 	header post navigation
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_header_post_navigation')) {
	function emallshop_header_post_navigation($atts, $content = null){
		$next_post = get_next_post();
		$prev_post = get_previous_post();
			if(!empty($prev_post) || !empty($next_post)):
		?>
			<div class="header-post-navigation hidden-xs" role="navigation">
				<div class="navigation">
					<ul class="nav-links">
					<?php if(!empty($prev_post)) : ?>
						<li class="nav-previous">
							<a href="<?php echo esc_url( get_permalink($prev_post->ID) ); ?>" ><?php echo get_the_title($prev_post->ID); ?></a>
							<div class="post-nav-thumb">
								<?php echo get_the_post_thumbnail( $prev_post->ID, 'thumbnail'); ?>
							</div>
						</li>
					<?php endif; ?>
						<li class="archive-page">
							<a href="<?php echo esc_url( get_post_type_archive_link( $atts['archive_post'] ) ); ?>"></a>
						</li>
					<?php if(!empty($next_post)) : ?>
						<li class="nav-next">
							<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>"> <?php echo get_the_title($next_post->ID); ?> </a>
							<div class="post-nav-thumb">
								<?php echo get_the_post_thumbnail( $next_post->ID,'thumbnail' ); ?>
							</div>
						</li>
					<?php endif; ?>
					</ul>
				</div>
			</div>
		<?php
		endif;
	}
}

if( !function_exists('emallshop_related_post')){
	function emallshop_related_post($post_id){
		$related_posts = emallshop_getRelatedPosts($post_id);
		$id = uniqid();
		global $emallshop_owlparam;
		$emallshop_owlparam['productsCarousel']['section-'.$id] = array(
			'item_columns'     	=> emallshop_get_option('blog-per-row', 2),
			'autoplay'    		=> emallshop_get_option('blog-carousel-auto-play', 1) ? 'true' : 'false',
			'navigation'  		=> emallshop_get_option('blog-carousel-navigation', 1) ? 'true' : 'false',
			'loop'        		=> emallshop_get_option('blog-carousel-loop', 1) ? 'true' : 'false',
			'autoHeight'  		=> 'true',
			'rp_desktop'     	=> emallshop_get_option('blog-per-row', 2),
			'rp_small_desktop' 	=> 2,
			'rp_tablet'     	=> 2,
			'rp_mobile'     	=> 2,
			'rp_small_mobile' 	=> 1,
			
		);?>
		<?php if ( $related_posts->have_posts() ) : ?>
			<div id="section-<?php echo esc_attr($id);?>" class="blogs_carousel related-posts">
				<h3 class="related-posts-title"><span><?php esc_html_e('Related Post','emallshop');?></span></h3>		
				<?php $row=1;?>
				<div class="section-content">
					<div class="row">
						<ul class="product-carousel owl-carousel">
						<?php 
							add_filter('excerpt_length', 'emallshop_new_excerpt_length');	?>
						<?php while( $related_posts->have_posts() ): $related_posts->the_post();?>
							
							<?php if($row==1){?>
								<li class="slide-row">
									<ul>
							<?php }?>
								<li class="blog-entry">
									<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
										<?php // Post thumbnail.
											emallshop_get_post_thumbnail('medium');
										?>													
										<div class="blog-entry-content">
											<?php if( emallshop_get_option('show-postdate', 1) ==1):?>
												<div class="entry-date">
													<span class="entry-day"><?php  echo esc_html( get_the_time('d') );?></span>
													<span class="entry-month"><?php  echo esc_html( get_the_time('M') );?></span>
												</div>
											<?php endif; ?>
											<header class="entry-header">
												<?php the_title( sprintf( '<h4 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );?>
											</header><!-- .entry-header -->
											
											<div class="entry-content">
												<?php the_excerpt(); ?>												
											</div><!-- .entry-content -->
										</div>

									</article><!-- #post-## -->
								</li>
							<?php if($row==1 || $related_posts->current_post+1==$related_posts->post_count){ $row=0;?>
									</ul>
								</li>
							<?php } $row++;?>
						<?php endwhile; // end of the loop. ?>
						</ul>
					</div>
				</div>		
			</div>
		<?php endif;
		wp_reset_postdata();
	}
}

/* 	Regex
/* ---------------------------------------------------------------------- */
if (!function_exists('emallshop_regex')) {

	/*
	*	Regex for url: http://mathiasbynens.be/demo/url-regex
	*/
	function emallshop_regex($string, $pattern = false, $start = "^", $end = "") {
		if (!$pattern) return false;

		if ($pattern == "url") {
			$pattern = "!$start((https?|ftp)://(-\.)?([^\s/?\.#-]+\.?)+(/[^\s]*)?)$end!";
		} else if ($pattern == "mail") {
			$pattern = "!$start\w[\w|\.|\-]+@\w[\w|\.|\-]+\.[a-zA-Z]{2,4}$end!";
		} else if ($pattern == "image") {
			$pattern = "!$start(https?(?://([^/?#]*))?([^?#]*?\.(?:jpg|gif|png)))$end!";
		} else if ($pattern == "mp4") {
			$pattern = "!$start(https?(?://([^/?#]*))?([^?#]*?\.(?:mp4)))$end!";
		} else if (strpos($pattern,"<") === 0) {
			$pattern = str_replace('<',"",$pattern);
			$pattern = str_replace('>',"",$pattern);

			if (strpos($pattern,"/") !== 0) { $close = "\/>"; $pattern = str_replace('/',"",$pattern); }
			$pattern = trim($pattern);
			if (!isset($close)) $close = "<\/".$pattern.">";

			$pattern = "!$start\<$pattern.+?$close!";
		}

		preg_match($pattern, $string, $result);

		if (empty($result[0])) {
			return false;
		} else {
			return $result;
		}
	}
}

/* Admin Notice */
add_action( 'admin_notices',  'emallshop_check_theme_license_activate', 90);
function emallshop_check_theme_license_activate(){
	if(emallshop_is_activated()) {
		return;
	}
	if(isset( $_GET['page'] ) && $_GET['page'] == 'emallshop-theme' ) {
		return;
	}
	$theme_details = wp_get_theme();
	$activate_page_link = admin_url( 'admin.php?page=emallshop-theme' );
	?>
	<div class="notice notice-error is-dismissible">
			<p>
				<?php 
					echo sprintf( esc_html__( ' %1$s Theme is not activated! Please activate your theme and enjoy all features of the %2$s theme', 'emallshop'), 'Emallshop','Emallshop' );
					?>
			</p>
			<p>
				<strong style="color:red"><?php esc_html_e( 'Please activate the theme!', 'emallshop' ); ?></strong> -
				<a href="<?php echo esc_url( $activate_page_link ); ?>">
					<?php esc_html_e( 'Activate Now','emallshop' ); ?> 
				</a> 
			</p>
		</div>
	<?php
}

/*  Add google analytics code
/* --------------------------------------------------------------------- */ 

function emallshop_print_google_code() {
	
	$googleCode = emallshop_get_option( 'google-analytics', '' );
	$google_theme_color = emallshop_get_option('google-theme-color', 'transparent' );

	if( !empty( $googleCode ) ) {
		echo apply_filters( 'emallshop_google_analytics code_js', $googleCode ); // WPCS: XSS OK.
	}	
		
	if( 'transparent' != $google_theme_color){ ?>	
		<meta name="theme-color" content="<?php echo esc_attr( $google_theme_color ); ?>">
	<?php
	}
}
add_action( 'wp_head', 'emallshop_print_google_code' );

/* Change WP coockie notice position
/* --------------------------------------------------------------------*/
if( class_exists('Cookie_Notice') ) {
    remove_action( 'wp_footer', array( $cookie_notice, 'add_cookie_notice' ), 1000 );
    add_action( 'et_after_body', array( $cookie_notice, 'add_cookie_notice' ), 1000 );
}


if( !function_exists( 'emallshop_newsletter_popup' ) ){
	function emallshop_newsletter_popup(){
	
		if( ! emallshop_get_option( 'newsletter-enable', 0 ) ) return;?>
		
		<div id="newsletterPopup" class="modal fade newsletterPopup popup-modal">
			<div class="modal-dialog">
				<div class="newsletter-content modal-content modal-md">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<div class="newsletter-logo">
						<?php $newsletter_logo = emallshop_get_option( 'newsletter-logo', array( 'url'=>EMALLSHOP_IMAGES.'/logo-light.png' ) );
						if( !empty( $newsletter_logo)):?>
							<img src="<?php echo esc_url($newsletter_logo['url']);?>" alt="<?php esc_attr_e('logo','emallshop');?>">
						<?php endif;?>
					</div>
					<div class="newsletter-text">
						<h1><?php echo wp_kses_post(emallshop_get_option('newsletter-title', 'Join Us Now!'))?></h1>
						<p class="tag-line"><?php echo wp_kses_post(emallshop_get_option('newsletter-tag-line', 'Signup today for free and be the first to hear of special promotions, new arrivals and designer news.') )?></p>
					</div>
					<div class="newsletter-form">
						<?php if( function_exists( 'mc4wp_show_form' ) ) {
							mc4wp_show_form();
						}?>
						<div class="checkbox-group form-group-top clearfix">
						  <input type="checkbox" id="checkBox1">
						  <label for="checkBox1"> 
							<span class="check"></span>
							<span class="box"></span>
							<?php echo wp_kses_post( emallshop_get_option('newsletter-dont-show', 'Don\'t show this popup again') );?>
						  </label>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php }
}
add_action( 'emallshop_after_footer', 'emallshop_newsletter_popup', 5 );

add_action( 'emallshop_after_footer', 'emallshop_mobile_bottom_navbar', 10 );

if ( ! function_exists( 'emallshop_mobile_bottom_navbar' ) ) {	
	/**
	 * Mobile Bottom Navbar.
	 * @since EmallShop 2.3.0
	 */
	function emallshop_mobile_bottom_navbar(){
		
		if( ! apply_filters( 'emallshop_mobile_bottom_navbar', true ) || ! emallshop_get_option( 'mobile-bottom-navbar', 1 ) ) {
			return; 
		}
		
		$mobile_elemets = (array) emallshop_get_option( 'mobile-navbar-elements',  array(
                    'enabled'  => array(
                        'shop'  		=> esc_html__( 'Shop', 'emallshop' ),
						'sidebar'  		=> esc_html__( 'Sidebar/Filters', 'emallshop' ),
						'wishlist' 		=> esc_html__( 'Wishlist', 'emallshop' ),
						'cart'     		=> esc_html__( 'Cart', 'emallshop' ),
						'account'  		=> esc_html__( 'Account', 'emallshop' ),				
                    ) ) );
		
		if ( isset( $mobile_elemets['enabled']['placebo'] ) ) {
			unset( $mobile_elemets['enabled']['placebo'] );
		}
		
		if(!isset($mobile_elemets['enabled'])){
			$mobile_elemets['enabled'] =  array(
			   'shop'  		=> esc_html__( 'Shop', 'emallshop' ),
				'sidebar'  	=> esc_html__( 'Sidebar/Filters', 'emallshop' ),
				'wishlist' 	=> esc_html__( 'Wishlist', 'emallshop' ),
				'cart'     	=> esc_html__( 'Cart', 'emallshop' ),
				'account'  	=> esc_html__( 'Account', 'emallshop' ),							
			);
		}
		
		if( empty( $mobile_elemets['enabled'] ) ){
			return;
		}
		
		$args['navbar_class']	= ( !emallshop_get_option( 'mobile-navbar-label', 1 ) ) ? ' navbar-label-hide' : '';
		
		foreach ( $mobile_elemets['enabled'] as $element => $value ) {
			$element_args = array();
			switch ( $element ) {
				case 'shop':
					if ( ! function_exists( 'is_shop' ) ) {
						continue 2;
					}
					$element_args['link'] 	= get_permalink( get_option( 'woocommerce_shop_page_id' ) );
					$element_args['icon'] 	= emallshop_get_option( 'mobile-navbar-label-icon-shop', 'fa fa-home' );
					$element_args['label'] 	= emallshop_get_option( 'mobile-navbar-label-shop', esc_html__( 'shop', 'emallshop' ) );
					$element_args['class'] 	= 'item-shop';					
					break;
				case 'wishlist':
					if ( ! function_exists( 'YITH_WCWL' ) ) {
						continue 2;
					}		
					$wishlist_page_id 		= get_option( 'yith_wcwl_wishlist_page_id' );
					$wishlist_url 			= YITH_WCWL()->get_wishlist_url();
					$element_args['link'] 	= apply_filters('emallshop_myaccount_wishlist_url', $wishlist_url );
					$element_args['icon'] 	= emallshop_get_option( 'mobile-navbar-label-icon-wishlist', 'fa fa-heart' );
					$element_args['count'] 	= YITH_WCWL()->count_products();
					$element_args['label'] 	= emallshop_get_option('mobile-navbar-label-wishlist',esc_html__( 'Wishlist', 'emallshop' ) );
					$element_args['class'] 	= 'item-wishlist';					
					if ( is_page( $wishlist_page_id ) ) {
						$element_args['class'] .= ' active';
					}
					break;			
				case 'cart':
					if( ! is_woocommerce_activated() || ( ! is_user_logged_in() && emallshop_get_option( 'login-to-see-price',0 ) ) ){
						continue 2;
					}					
					$element_args['link'] 	= wc_get_cart_url();
					$element_args['icon'] 	= emallshop_get_option( 'mobile-navbar-label-icon-cart', 'fa fa-shopping-cart' );
					$element_args['count'] 	= is_object( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
					$element_args['label'] 	= emallshop_get_option('mobile-navbar-label-cart', esc_html__( 'Cart', 'emallshop' ) );
					$element_args['class'] 	= 'item-cart header-cart';
					if ( function_exists( 'is_cart' ) && is_cart() ) {
						$element_args['class'] .= ' active';
					}
					break;
				case 'account':
					if( ! is_woocommerce_activated() ){
						continue 2;
					}
					$element_args['link'] 	= get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
					$element_args['icon'] 	= emallshop_get_option( 'mobile-navbar-label-icon-account', 'fa fa-user' );
					$element_args['label'] 	= emallshop_get_option('mobile-navbar-label-account', esc_html__( 'Account', 'emallshop' ) );
					$element_args['class'] 	= 'item-account';	
					if( ! is_user_logged_in() ){
						$element_args['class'] 	.= ' user-login';
						if( emallshop_get_option( 'show-login-register-popup',1 ) ){
							$element_args['class'] 	.= ' enable';
						}
					}
					if ( is_account_page() ) {
						$element_args['class'] .= ' active';
					}
					break;
					
				case 'home':
					$element_args['link'] 	= home_url( '/' );
					$element_args['icon'] 	= emallshop_get_option( 'mobile-navbar-label-icon-home', 'fa fa-home' );
					$element_args['label'] 	= emallshop_get_option('mobile-navbar-label-home', esc_html__( 'Home', 'emallshop' ));
					$element_args['class'] 	= 'item-home';					
					if ( is_front_page() ) {
						$element_args['class'] .= ' active';
					}
					break;
				case 'menu':
					$element_args['link'] 	= '#';
					$element_args['icon'] 	= emallshop_get_option( 'mobile-navbar-label-icon-menu', 'fa fa-bars' );
					$element_args['label'] 	= emallshop_get_option('mobile-navbar-label-menu', esc_html__( 'Menu', 'emallshop' ) );
					$element_args['class'] 	= 'item-menu navbar-toggle';					
					break;
				case 'category':
					$element_args['link'] 	= '#';
					$element_args['icon'] 	= emallshop_get_option( 'mobile-navbar-label-icon-category', 'fa fa-categories' );
					$element_args['label'] 	= emallshop_get_option('mobile-navbar-label-category', esc_html__( 'Category', 'emallshop' ) );
					$element_args['class'] 	= 'item-category';					
					break;
				case 'compare':
					if ( ! class_exists( 'YITH_Woocompare' ) ) {
						continue 2;
					}
					global $yith_woocompare;
					$element_args['link'] 	= '#';
					$element_args['icon'] 	= emallshop_get_option( 'mobile-navbar-label-icon-compare', 'fa fa-refresh' );
					$element_args['count'] 	= count( $yith_woocompare->obj->products_list );
					$element_args['label'] 	= emallshop_get_option('mobile-navbar-label-compare', esc_html__( 'Compare', 'emallshop' ) );
					$element_args['class'] 	= 'yith-woocompare-open';					
					break;
				case 'sidebar':
					if( 'full-width' == emallshop_get_layout() || 'none' == emallshop_get_layout()  || ! emallshop_get_option( 'canvas-sidebar-mobile', 1 ) ) {
						continue 2;
					}
					if( function_exists('emallshop_is_vendor_page') && emallshop_is_vendor_page()){
						continue 2;
					}
					if( emallshop_is_catalog() ){												
						$element_args['link'] 	= '#';
						$element_args['icon'] 	= emallshop_get_option( 'mobile-navbar-label-icon-filter', 'fa fa-sliders' );
						$element_args['label'] 	= emallshop_get_option('mobile-navbar-label-filter', esc_html__( 'Filters', 'emallshop' ) );
						$element_args['class'] 	= 'item-sidebar es-canvas-sidebar';
					}else{						
						$element_args['link'] 	= '#';
						$element_args['icon'] 	= emallshop_get_option( 'mobile-navbar-label-icon-sidebar', 'fa fa-columns' );
						$element_args['label'] 	= emallshop_get_option('mobile-navbar-label-sidebar', esc_html__( 'Sidebar', 'emallshop' ) );
						$element_args['class'] 	= 'item-sidebar es-canvas-sidebar';
					}						
					break;
				case 'search':
					$element_args['link'] 	= '#';
					$element_args['icon'] 	= emallshop_get_option( 'mobile-navbar-label-icon-search', 'fa fa-pencil-square' );
					$element_args['label'] 	= emallshop_get_option('mobile-navbar-label-search', esc_html__( 'Search', 'emallshop' ) );
					$element_args['class'] 	= 'item-search';					
					break;
				case 'order':
					if( ! is_woocommerce_activated() ){
						continue 2;
					}
					$orders  = get_option( 'woocommerce_myaccount_orders_endpoint', 'orders' );
					$account_page_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
					if ( substr( $account_page_url, - 1, 1 ) != '/' ) {
						$account_page_url .= '/';
					}
					$orders_url   			= $account_page_url . $orders;					
					$element_args['link'] 	= apply_filters('emallshop_myaccount_orders_url', $orders_url  );
					$element_args['icon'] 	= emallshop_get_option( 'mobile-navbar-label-icon-order', 'fa fa-file-text-o' );
					$element_args['label'] 	= emallshop_get_option('mobile-navbar-label-order', esc_html__( 'Order', 'emallshop' ) );
					$element_args['class'] 	= 'item-order';	
					break;
				case 'order-tracking':
					if( ! is_woocommerce_activated() ){
						continue 2;
					}
					$tracking_pageid		= emallshop_get_option('order-tracking-page', '');
					if( empty( $tracking_pageid ) ){
						continue 2;
					}
					$order_tracking_url		= apply_filters('emallshop_myaccount_order_tracking_url', ( ! empty ( $tracking_pageid ) ) ? get_permalink( $tracking_pageid ) : '' );
					$element_args['link'] 	= $order_tracking_url;
					$element_args['icon'] 	= emallshop_get_option( 'mobile-navbar-label-icon-order-tracking', 'fa fa-plane' );
					$element_args['label'] 	= emallshop_get_option('mobile-navbar-label-order-tracking', esc_html__( 'Order Tracking', 'emallshop' ) );
					$element_args['class'] 	= 'item-order';					
					break;
				case 'blog':
					$element_args['link'] 	= get_permalink( get_option( 'page_for_posts' ) );
					$element_args['icon'] 	= emallshop_get_option( 'mobile-navbar-label-icon-blog', 'fa fa-pencil-square' );
					$element_args['label'] 	= emallshop_get_option('mobile-navbar-label-blog', esc_html__( 'Blog', 'emallshop' ) );
					$element_args['class'] 	= 'item-blog';					
					break;
				case 'custom_link1':
					$element_args['link'] 	= emallshop_get_option( 'mobile-navbar-custom-link1-url', '' );
					$element_args['icon'] 	= emallshop_get_option( 'mobile-navbar-custom-link1-icon', '' );
					$element_args['label'] 	= emallshop_get_option('mobile-navbar-custom-link1-label' );
					$element_args['class'] 	= 'item-custom-link1';					
					break;
				case 'custom_link2':
					$element_args['link'] 	= emallshop_get_option( 'mobile-navbar-custom-link2-url', '' );
					$element_args['icon'] 	= emallshop_get_option( 'mobile-navbar-custom-link2-icon', '' );
					$element_args['label'] 	= emallshop_get_option('mobile-navbar-custom-link2-label' );
					$element_args['class'] 	= 'item-custom-link1';					
					break;
				case 'custom_link3':
					$element_args['link'] 	= emallshop_get_option( 'mobile-navbar-custom-link3-url', '' );
					$element_args['icon'] 	= emallshop_get_option( 'mobile-navbar-custom-link3-icon', '' );
					$element_args['label'] 	= emallshop_get_option('mobile-navbar-custom-link3-label' );
					$element_args['class'] 	= 'item-custom-link1';					
					break;
			}
			$args['elements'][$element] = apply_filters( 'emallshop_mobile_bottom_navbar_element_'.$element, $element_args );
			
		}
		
		if( empty( $args['elements'] ) ) { 
			return;
		}
		
		get_template_part( 'templates/mobile/mobile-bottom','navbar', $args );			
	}
}

/*  Get Page URL By ShortCode
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_get_url_by_shortcode' )) {
	function emallshop_get_url_by_shortcode($shortcode) {
		global $wpdb;
		$url = '';
		$sql = 'SELECT ID
			FROM ' . $wpdb->posts . '
			WHERE
				post_type = "page"
				AND post_status="publish"
				AND post_content LIKE "%' . $shortcode . '%"';

		if ($id = $wpdb->get_var($sql)) {
			$url = get_permalink($id);
		}
		return $url;
	}
}

/* Get RGB color value
/* --------------------------------------------------------------------- */ 
if( ! function_exists( 'emallshop_hex2rgb_color' )) {
	function emallshop_hex2rgb_color($hex) {
	   $hex = str_replace("#", "", $hex);

	   if(strlen($hex) == 3) {
		  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
		  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
		  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
		  $r = hexdec(substr($hex,0,2));
		  $g = hexdec(substr($hex,2,2));
		  $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = array($r, $g, $b);
	   return implode(",", $rgb); // returns the rgb values separated by commas
	   //return $rgb; // returns an array with the rgb values
	}
}

/* Font-Awesome Icon
/* --------------------------------------------------------------------- */ 
function emallshop_font_awesome_icon(){
	return array('fa-glass','fa-music','fa-search','fa-envelope-o','fa-heart','fa-star','fa-star-o','fa-user','fa-film','fa-th-large','fa-th','fa-th-list','fa-check','fa-remove','fa-close','fa-times','fa-search-plus','fa-search-minus','fa-power-off','fa-signal','fa-gear','fa-cog','fa-trash-o','fa-home','fa-file-o','fa-clock-o','fa-road','fa-download','fa-arrow-circle-o-down','fa-arrow-circle-o-up','fa-inbox','fa-play-circle-o','fa-rotate-right','fa-repeat','fa-refresh','fa-list-alt','fa-lock','fa-flag','fa-headphones','fa-volume-off','fa-volume-down','fa-volume-up','fa-qrcode','fa-barcode','fa-tag','fa-tags','fa-book','fa-bookmark','fa-print','fa-camera','fa-font','fa-bold','fa-italic','fa-text-height','fa-text-width','fa-align-left','fa-align-center','fa-align-right','fa-align-justify','fa-list','fa-dedent','fa-outdent','fa-indent','fa-video-camera','fa-photo','fa-image','fa-picture-o','fa-pencil','fa-map-marker','fa-adjust','fa-tint','fa-edit','fa-pencil-square-o','fa-share-square-o','fa-check-square-o','fa-arrows','fa-step-backward','fa-fast-backward','fa-backward','fa-play','fa-pause','fa-stop','fa-forward','fa-fast-forward','fa-step-forward','fa-eject','fa-chevron-left','fa-chevron-right','fa-plus-circle','fa-minus-circle','fa-times-circle','fa-check-circle','fa-question-circle','fa-info-circle','fa-crosshairs','fa-times-circle-o','fa-check-circle-o','fa-ban','fa-arrow-left','fa-arrow-right','fa-arrow-up','fa-arrow-down','fa-mail-forward','fa-share','fa-expand','fa-compress','fa-plus','fa-minus','fa-asterisk','fa-exclamation-circle','fa-gift','fa-leaf','fa-fire','fa-eye','fa-eye-slash','fa-warning','fa-exclamation-triangle','fa-plane','fa-calendar','fa-random','fa-comment','fa-magnet','fa-chevron-up','fa-chevron-down','fa-retweet','fa-shopping-cart','fa-folder','fa-folder-open','fa-arrows-v','fa-arrows-h','fa-bar-chart-o','fa-bar-chart','fa-twitter-square','fa-facebook-square','fa-camera-retro','fa-key','fa-gears','fa-cogs','fa-comments','fa-thumbs-o-up','fa-thumbs-o-down','fa-star-half','fa-heart-o','fa-sign-out','fa-linkedin-square','fa-thumb-tack','fa-external-link','fa-sign-in','fa-trophy','fa-github-square','fa-upload','fa-lemon-o','fa-phone','fa-square-o','fa-bookmark-o','fa-phone-square','fa-twitter','fa-facebook-f','fa-facebook','fa-github','fa-unlock','fa-credit-card','fa-rss','fa-hdd-o','fa-bullhorn','fa-bell','fa-certificate','fa-hand-o-right','fa-hand-o-left','fa-hand-o-up','fa-hand-o-down','fa-arrow-circle-left','fa-arrow-circle-right','fa-arrow-circle-up','fa-arrow-circle-down','fa-globe','fa-wrench','fa-tasks','fa-filter','fa-briefcase','fa-arrows-alt','fa-group','fa-users','fa-chain','fa-link','fa-cloud','fa-flask','fa-cut','fa-scissors','fa-copy','fa-files-o','fa-paperclip','fa-save','fa-floppy-o','fa-square','fa-navicon','fa-reorder','fa-bars','fa-list-ul','fa-list-ol','fa-strikethrough','fa-underline','fa-table','fa-magic','fa-truck','fa-pinterest','fa-pinterest-square','fa-google-plus-square','fa-google-plus','fa-money','fa-caret-down','fa-caret-up','fa-caret-left','fa-caret-right','fa-columns','fa-unsorted','fa-sort','fa-sort-down','fa-sort-desc','fa-sort-up','fa-sort-asc','fa-envelope','fa-linkedin','fa-rotate-left','fa-undo','fa-legal','fa-gavel','fa-dashboard','fa-tachometer','fa-comment-o','fa-comments-o','fa-flash','fa-bolt','fa-sitemap','fa-umbrella','fa-paste','fa-clipboard','fa-lightbulb-o','fa-exchange','fa-cloud-download','fa-cloud-upload','fa-user-md','fa-stethoscope','fa-suitcase','fa-bell-o','fa-coffee','fa-cutlery','fa-file-text-o','fa-building-o','fa-hospital-o','fa-ambulance','fa-medkit','fa-fighter-jet','fa-beer','fa-h-square','fa-plus-square','fa-angle-double-left','fa-angle-double-right','fa-angle-double-up','fa-angle-double-down','fa-angle-left','fa-angle-right','fa-angle-up','fa-angle-down','fa-desktop','fa-laptop','fa-tablet','fa-mobile-phone','fa-mobile','fa-circle-o','fa-quote-left','fa-quote-right','fa-spinner','fa-circle','fa-mail-reply','fa-reply','fa-github-alt','fa-folder-o','fa-folder-open-o','fa-smile-o','fa-frown-o','fa-meh-o','fa-gamepad','fa-keyboard-o','fa-flag-o','fa-flag-checkered','fa-terminal','fa-code','fa-mail-reply-all','fa-reply-all','fa-star-half-empty','fa-star-half-full','fa-star-half-o','fa-location-arrow','fa-crop','fa-code-fork','fa-unlink','fa-chain-broken','fa-question','fa-info','fa-exclamation','fa-superscript','fa-subscript','fa-eraser','fa-puzzle-piece','fa-microphone','fa-microphone-slash','fa-shield','fa-calendar-o','fa-fire-extinguisher','fa-rocket','fa-maxcdn','fa-chevron-circle-left','fa-chevron-circle-right','fa-chevron-circle-up','fa-chevron-circle-down','fa-html5','fa-css3','fa-anchor','fa-unlock-alt','fa-bullseye','fa-ellipsis-h','fa-ellipsis-v','fa-rss-square','fa-play-circle','fa-ticket','fa-minus-square','fa-minus-square-o','fa-level-up','fa-level-down','fa-check-square','fa-pencil-square','fa-external-link-square','fa-share-square','fa-compass','fa-toggle-down','fa-caret-square-o-down','fa-toggle-up','fa-caret-square-o-up','fa-toggle-right','fa-caret-square-o-right','fa-euro','fa-eur','fa-gbp','fa-dollar','fa-usd','fa-rupee','fa-inr','fa-cny','fa-rmb','fa-yen','fa-jpy','fa-ruble','fa-rouble','fa-rub','fa-won','fa-krw','fa-bitcoin','fa-btc','fa-file','fa-file-text','fa-sort-alpha-asc','fa-sort-alpha-desc','fa-sort-amount-asc','fa-sort-amount-desc','fa-sort-numeric-asc','fa-sort-numeric-desc','fa-thumbs-up','fa-thumbs-down','fa-youtube-square','fa-youtube','fa-xing','fa-xing-square','fa-youtube-play','fa-dropbox','fa-stack-overflow','fa-instagram','fa-flickr','fa-adn','fa-bitbucket','fa-bitbucket-square','fa-tumblr','fa-tumblr-square','fa-long-arrow-down','fa-long-arrow-up','fa-long-arrow-left','fa-long-arrow-right','fa-apple','fa-windows','fa-android','fa-linux','fa-dribbble','fa-skype','fa-foursquare','fa-trello','fa-female','fa-male','fa-gittip','fa-gratipay','fa-sun-o','fa-moon-o','fa-archive','fa-bug','fa-vk','fa-weibo','fa-renren','fa-pagelines','fa-stack-exchange','fa-arrow-circle-o-right','fa-arrow-circle-o-left','fa-toggle-left','fa-caret-square-o-left','fa-dot-circle-o','fa-wheelchair','fa-vimeo-square','fa-turkish-lira','fa-try','fa-plus-square-o','fa-space-shuttle','fa-slack','fa-envelope-square','fa-wordpress','fa-openid','fa-institution','fa-bank','fa-university','fa-mortar-board','fa-graduation-cap','fa-yahoo','fa-google','fa-reddit','fa-reddit-square','fa-stumbleupon-circle','fa-stumbleupon','fa-delicious','fa-digg','fa-pied-piper','fa-pied-piper-alt','fa-drupal','fa-joomla','fa-language','fa-fax','fa-building','fa-child','fa-paw','fa-spoon','fa-cube','fa-cubes','fa-behance','fa-behance-square','fa-steam','fa-steam-square','fa-recycle','fa-automobile','fa-car','fa-cab','fa-taxi','fa-tree','fa-spotify','fa-deviantart','fa-soundcloud','fa-database','fa-file-pdf-o','fa-file-word-o','fa-file-excel-o','fa-file-powerpoint-o','fa-file-photo-o','fa-file-picture-o','fa-file-image-o','fa-file-zip-o','fa-file-archive-o','fa-file-sound-o','fa-file-audio-o','fa-file-movie-o','fa-file-video-o','fa-file-code-o','fa-vine','fa-codepen','fa-jsfiddle','fa-life-bouy','fa-life-buoy','fa-life-saver','fa-support','fa-life-ring','fa-circle-o-notch','fa-ra','fa-rebel','fa-ge','fa-empire','fa-git-square','fa-git','fa-hacker-news','fa-tencent-weibo','fa-qq','fa-wechat','fa-weixin','fa-send','fa-paper-plane','fa-send-o','fa-paper-plane-o','fa-history','fa-genderless','fa-circle-thin','fa-header','fa-paragraph','fa-sliders','fa-share-alt','fa-share-alt-square','fa-bomb','fa-soccer-ball-o','fa-futbol-o','fa-tty','fa-binoculars','fa-plug','fa-slideshare','fa-twitch','fa-yelp','fa-newspaper-o','fa-wifi','fa-calculator','fa-paypal','fa-google-wallet','fa-cc-visa','fa-cc-mastercard','fa-cc-discover','fa-cc-amex','fa-cc-paypal','fa-cc-stripe','fa-bell-slash','fa-bell-slash-o','fa-trash','fa-copyright','fa-at','fa-eyedropper','fa-paint-brush','fa-birthday-cake','fa-area-chart','fa-pie-chart','fa-line-chart','fa-lastfm','fa-lastfm-square','fa-toggle-off','fa-toggle-on','fa-bicycle','fa-bus','fa-ioxhost','fa-angellist','fa-cc','fa-shekel','fa-sheqel','fa-ils','fa-meanpath','fa-buysellads','fa-connectdevelop','fa-dashcube','fa-forumbee','fa-leanpub','fa-sellsy','fa-shirtsinbulk','fa-simplybuilt','fa-skyatlas','fa-cart-plus','fa-cart-arrow-down','fa-diamond','fa-ship','fa-user-secret','fa-motorcycle','fa-street-view','fa-heartbeat','fa-venus','fa-mars','fa-mercury','fa-transgender','fa-transgender-alt','fa-venus-double','fa-mars-double','fa-venus-mars','fa-mars-stroke','fa-mars-stroke-v','fa-mars-stroke-h','fa-neuter','fa-facebook-official','fa-pinterest-p','fa-whatsapp','fa-server','fa-user-plus','fa-user-times','fa-hotel','fa-bed','fa-viacoin','fa-train','fa-subway','fa-medium');
}
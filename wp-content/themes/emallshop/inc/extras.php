<?php 
/**
 * EmallShop Extras Functions
 *
 * @package PressLayouts
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* 	Get activated theme
/* --------------------------------------------------------------------- */
if(!function_exists('emallshop_activated_theme')) {
    function emallshop_activated_theme() {
        $activated_data = get_option( 'emallshop_activated_data' );
        $theme = ( isset( $activated_data['theme'] ) && ! empty( $activated_data['theme'] ) ) ? $activated_data['theme'] : false ;
        return $theme;
    }

}

/* 	Is theme activatd
/* --------------------------------------------------------------------- */
if(!function_exists('emallshop_is_activated')) {
    function emallshop_is_activated() {
		$purchase_code = get_option( 'envato_purchase_code_18513022' );
		if( $purchase_code ){
			return true;
		}
		
        if ( emallshop_activated_theme() != EMALLSHOP_PREFIX ) return false;
		if ( ! get_option( 'emallshop_is_activated' ) ) update_option( 'emallshop_is_activated', true );        
        return get_option( 'emallshop_is_activated', false );
    }
}

/* 	Get Purchase code
/* --------------------------------------------------------------------- */
if(!function_exists('emallshop_get_purchase_code')) {
    function emallshop_get_purchase_code() {
       $purchase_code = get_option( 'envato_purchase_code_18513022' );
	   $activated_data = get_option( 'emallshop_activated_data' );		
	   if($purchase_code){
		   return $purchase_code;
	   }elseif($activated_data){
			if(isset($activated_data['purchase'])){
				return $activated_data['purchase'];
			}
	   }
	   return '';	   
    }
}

/* 	Check WooCommerce is activated
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'is_woocommerce_activated' ) ) {
	function is_woocommerce_activated() {
		return class_exists( 'woocommerce' ) ? true : false;
	}
}

/* 	Check Dokan is activated
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'is_dokan_activated' ) ) {
	function is_dokan_activated() {
		return class_exists( 'WeDevs_Dokan' ) ? true : false;
	}
}

/* 	Check WC Marketplace is activated
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'is_WC_Marketplace_activated' ) ) {
	function is_WC_Marketplace_activated() {
		return class_exists( 'WCMp' ) ? true : false;
	}
}

/*Check WC Vendors is activated
/* --------------------------------------------------------------------- */
if( ! function_exists( 'is_wc_vendors_activated' ) ) {
	function is_wc_vendors_activated() {
		return class_exists( 'WC_Vendors' ) ? true : false;
	}
}

/*Check Visual Composer is activated
/* --------------------------------------------------------------------- */
if( ! function_exists( 'is_vc_activated' ) ) {
	function is_vc_activated() {
		return class_exists( 'WPBakeryVisualComposerAbstract' ) ? true : false;
	}
}

/**
 * Get options
 */
if ( ! function_exists( 'emallshop_get_option' ) ) {
	function emallshop_get_option( $name, $default = '' ) {
		global $emallshop_options;
		$emallshop_options = apply_filters('emallshop_theme_options',$emallshop_options);
		$value = $default;
		if ( isset( $emallshop_options[$name]  ) ) {
			if(  is_array( $emallshop_options[$name] ) && isset($emallshop_options[$name]['url']) && empty ( $emallshop_options[$name]['url'] ) ){
				$value = $default;
			}elseif(is_array( $emallshop_options[$name] ) && empty($emallshop_options[$name])){
				$value = $default;
			}else{
				$value =  $emallshop_options[$name];
			}			
		}
		$value = apply_filters( 'emallshop_get_option', $value, $name, $emallshop_options );
		return apply_filters( 'emallshop_get_option_' . $name, $value, $name, $emallshop_options) ;	
	}
}

/**
 * Get uniqid Id
 */
if ( ! function_exists( 'emallshop_uniqid' ) ) :
	function emallshop_uniqid( $prefix = '' ) {		
		return $prefix.rand(1000,100000);
	}
endif;

/**
 * Get blog meta
 *
 * @since  2.3.0
 *
 * @return string
 */
if ( ! function_exists( 'emallshop_get_post_meta' ) ) {
	function  emallshop_get_post_meta( $meta ) {
		
		if ( is_home() && ! is_front_page() ) {
			$post_id = get_queried_object_id();

			return get_post_meta( $post_id, $meta, true );
		}

		if ( function_exists( 'is_shop' ) && is_shop() ) {
			$post_id = intval( get_option( 'woocommerce_shop_page_id' ) );
			
			return get_post_meta( $post_id, $meta, true );
		}
		
		if ( ! is_singular() ) {
			return false;
		}

		$post_meta = get_post_meta( get_the_ID(), $meta, true );
		return apply_filters('emallshop_get_post_meta', $post_meta, $meta);
		
	}
}

/**
 * Check is plugin active
 */
if ( ! function_exists( 'emallshop_check_plugin_active' ) ) {
	function emallshop_check_plugin_active( $plugin ) {
		if( empty($plugin) ) return false;
		return in_array( $plugin, (array) get_option( 'active_plugins', array() ) ) || is_plugin_active_for_network( $plugin );
	}
}

if ( ! function_exists( 'emallshop_is_plugin_check_active' ) ) {
	/**
	 * Check tgmpa listed plugin active
	 */
	function emallshop_is_plugin_check_active( $slug ) {
		$instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );

		return ( ( ! empty( $instance->plugins[ $slug ]['is_callable'] ) && is_callable( $instance->plugins[ $slug ]['is_callable'] ) ) || emallshop_check_plugin_active( $instance->plugins[ $slug ]['file_path'] ) );
	}
}

if ( ! function_exists( 'emallshop_allowed_html' ) ) {
	/**
	 * Allowed html
	 */
	function emallshop_allowed_html( $allowed_els = '' ){

		// bail early if parameter is empty
		if( empty($allowed_els) ) return array();

		if( is_string($allowed_els) ){
			$allowed_els = explode(',', $allowed_els);
		}

		$allowed_html = array();

		$allowed_tags = wp_kses_allowed_html('post');

		foreach( $allowed_els as $el ){
			$el = trim($el);
			if( array_key_exists($el, $allowed_tags) ){
				$allowed_html[$el] = $allowed_tags[$el];
			}
		}

		return $allowed_html;
	}
}

if ( ! function_exists( 'emallshop_hex2rgb' ) ) {
	/**
	 * Convert HEX to RGB.
	 *
	 * @since EmallShop 1.0
	 */
	function emallshop_hex2rgb( $color ) {
		$color = trim( $color, '#' );

		if ( strlen( $color ) == 3 ) {
			$r = hexdec( substr( $color, 0, 1 ).substr( $color, 0, 1 ) );
			$g = hexdec( substr( $color, 1, 1 ).substr( $color, 1, 1 ) );
			$b = hexdec( substr( $color, 2, 1 ).substr( $color, 2, 1 ) );
		} else if ( strlen( $color ) == 6 ) {
			$r = hexdec( substr( $color, 0, 2 ) );
			$g = hexdec( substr( $color, 2, 2 ) );
			$b = hexdec( substr( $color, 4, 2 ) );
		} else {
			return array();
		}

		return array( 'red' => $r, 'green' => $g, 'blue' => $b );
	}
}

if ( ! function_exists( 'emallshop_new_excerpt_length' ) ) {
	function emallshop_new_excerpt_length($length) {
		return 20;
	}
}

if ( ! function_exists( 'emallshop_is_vendor_page' ) ) :
	function emallshop_is_vendor_page(){
		
		/* Dokan */
		if ( function_exists( 'dokan_is_store_page' ) && dokan_is_store_page() ) {
			return true;
		}

		/* WC Vendor */
		if ( emallshop_is_wc_vendor_page() ) {
			return true;
		}	
		
		/* WCMP plugin*/
		if ( function_exists( 'emallshop_is_wcmp_vendor_page' ) && emallshop_is_wcmp_vendor_page() ) {
			return true;
		}
		
		/* WCFM plugin*/
		if ( function_exists( 'wcfm_is_store_page' ) && wcfm_is_store_page() ) {
			return true;
		}
		return false;
			
	}
endif;

/**
 * Check is vendor page
 *
 * @return bool
 */
if ( ! function_exists( 'emallshop_is_wc_vendor_page' ) ) :
	/* WC Vendor */
	function emallshop_is_wc_vendor_page() {
	
		if ( class_exists( 'WCV_Vendors' ) && method_exists( 'WCV_Vendors', 'is_vendor_page' ) ) {
			return WCV_Vendors::is_vendor_page();
		}

		return false;
	}
endif;

if ( ! function_exists( 'emallshop_is_wcmp_vendor_page' ) ) {
	/**
	 * check is wcmp vendor page
	 *
	 * @since  2.3.0
	 *
	 * @return string size
	 */
	function emallshop_is_wcmp_vendor_page(){
		global $WCMp;
		if (isset( $WCMp->taxonomy->taxonomy_name ) && is_tax($WCMp->taxonomy->taxonomy_name)) {
			return true;
		}
		return false;
	}
}

if ( ! function_exists( 'emallshop_get_layout' ) ) {
	/**
	 * Get layout base on current page
	 *
	 * @return string
	 */
	function emallshop_get_layout() {
		$layout = emallshop_get_option( 'blog-page-layout', 'right' );

		if ( emallshop_get_post_meta( '_emallshop_sidebar_position' ) ) {		
			$layout = emallshop_get_post_meta( '_emallshop_sidebar_position' );
		} elseif ( emallshop_get_post_meta( '_emallshop_product_layout' )) {
			$layout = emallshop_get_post_meta( '_emallshop_product_layout' );
			if(!empty( $layout ) && ( $layout == 'none-left'  || $layout == 'none-right' || $layout == 'full-layout' ) ){
				$layout = emallshop_get_option( 'single-product-page-layout', 'none' );
			}
		}elseif ( is_singular( 'post' ) ) {
			$layout = emallshop_get_option( 'single-post-layout', 'right' );
		} elseif ( is_singular( 'portfolio' ) ) {
			$layout = emallshop_get_option( 'single-portfolio-page-layout', 'none' );
		} elseif( function_exists( 'emallshop_is_wcmp_vendor_page' ) && emallshop_is_wcmp_vendor_page() ) {
			$layout = emallshop_get_option( 'vendor-page-layout', 'left' );
		}elseif( emallshop_is_wc_vendor_page() ){
			$layout = emallshop_get_option( 'vendor-page-layout', 'left' );
		}elseif ( emallshop_is_catalog() ) {
			$layout = emallshop_get_option( 'shop-page-layout','left' );
			$product_columns = emallshop_get_option( 'products-per-row', 3 );
			if($product_columns > 4){
				$layout = 'none';
			}		
		} elseif( is_dokan_activated() && ( dokan_is_store_page() || dokan_is_product_edit_page() ) ){
			$layout = 'none';
		} elseif( function_exists( 'emallshop_is_wcmp_vendor_page' ) && emallshop_is_wcmp_vendor_page() ){
			$layout = 'none';
		} elseif ( function_exists('is_product') && is_product() )  {
			$layout = emallshop_get_option( 'single-product-page-layout', 'none' );
		} elseif ( function_exists('emallshop_full_pages') && emallshop_full_pages() )  {
			$layout = 'none';
		} elseif ( is_404() ) {
			$layout = 'none';
		}elseif ( emallshop_is_portfolio() ) {
			$layout = emallshop_get_option( 'portfolio-archive-page-layout', 'none' );		
		}elseif (  is_singular( 'page' ) ) { 
			$layout = emallshop_get_option( 'page-layout', 'none' );
		} 
		$layout = !empty( $layout ) ? $layout : 'none';
		return apply_filters( 'emallshop_site_layout', $layout );
	}
}

/**
 * Check is catalog
 *
 * @return bool
 */
if ( ! function_exists( 'emallshop_full_pages' ) ) :
	function emallshop_full_pages() {

		if ( ( function_exists( 'is_cart' )  && is_cart()) ||
			 ( function_exists( 'is_checkout' )  && is_checkout()) ||
			 ( function_exists( 'is_account_page' )  && is_account_page()) ||
			 ( function_exists( 'is_wc_endpoint_url' )  && is_wc_endpoint_url()) || emallshop_is_wishlist_page()) {
			return true;
		}

		return false;
	}
endif;

if ( ! function_exists( 'emallshop_is_catalog' ) ) {
	/**
	 * Check is catalog
	 *
	 * @return bool
	 */
	function emallshop_is_catalog() {

		if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product_tag() || is_tax( 'product_brand' ) ) ) {
			return true;
		}

		return false;
	}
}

/**
 * Check is portfolio
 *
 * @return bool
 */
if ( ! function_exists( 'emallshop_is_portfolio' ) ) :
	function emallshop_is_portfolio() {

		if (  is_post_type_archive( 'portfolio' ) || is_tax( array('portfolio_cat', 'portfolio_tag') ) ) {
			return true;
		}

		return false;
	}
endif;

/**
 * Check is wishlist page
 *
 * @return bool
 */
if ( ! function_exists( 'emallshop_is_wishlist_page' ) ) :
	function emallshop_is_wishlist_page() {
		if ( function_exists( 'YITH_WCWL' )) {
			$wishlist_pageid = get_option('yith_wcwl_wishlist_page_id',true);
			global $post;
			if($post){
				$page_id = $post->ID;
				if($page_id == $wishlist_pageid){
					return true;
				}
			}
			
		}
		return false;
	}
endif;
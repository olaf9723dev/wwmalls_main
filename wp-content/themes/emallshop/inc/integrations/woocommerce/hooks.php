<?php 
/**
 * EmallShop Woocommerce Hooks
 *
 * @package PressLayouts
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*
 * add theme support
 *-----------------------------------------------------------------------*/
add_theme_support( 'woocommerce' );

/*Disable woocommerce css
/* --------------------------------------------------------------------- */
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

/* 	woocommerce hook
/* --------------------------------------------------------------------- */
add_filter( 'body_class', 'emallshop_body_woocommerce_classes' );

/**
* Archive Product
*/
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );

add_action( 'woocommerce_before_main_content', 'emallshop_output_primary_wrapper', 20 );
add_action( 'woocommerce_after_main_content', 'emallshop_after_main_content', 10 );
add_action( 'emallshop_after_main_content', 'emallshop_output_wrapper_end', 15 );
add_action( 'woocommerce_archive_description', 'emallshop_caregory_banner', 15 );
add_action( 'woocommerce_archive_description', 'emallshop_sub_caregories', 20 );
add_action( 'woocommerce_archive_description', 'emallshop_caregory_brands', 25 );

/** Before Shop Loop **/
add_action( 'woocommerce_before_shop_loop', 'emallshop_before_shop_loop', 10 );
add_action( 'emallshop_before_shop_loop', 'emallshop_grid_list_view', 5 );
add_action( 'emallshop_before_shop_loop', 'woocommerce_catalog_ordering', 10 );
add_action( 'emallshop_before_shop_loop', 'emallshop_output_pagination_wrapper', 15 );
add_action( 'emallshop_before_shop_loop', 'emallshop_product_show_pager', 20);
add_action( 'emallshop_before_shop_loop', 'woocommerce_pagination', 25);
add_action( 'emallshop_before_shop_loop', 'emallshop_output_wrapper_end', 30 );

/** Before Shop Loop Item **/
add_action( 'woocommerce_before_shop_loop_item', 'emallshop_product_image_wrapper', 5 );
add_action( 'woocommerce_before_shop_loop_item_title', 'emallshop_before_shop_loop_item_title', 10 );
add_action( 'emallshop_before_shop_loop_item_title', 'emallshop_show_product_loop_sale_flash', 5 );
add_action( 'emallshop_before_shop_loop_item_title', 'emallshop_template_loop_product_thumbnail', 5 );
add_action( 'emallshop_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 10 );
add_action( 'emallshop_before_shop_loop_item_title', 'emallshop_loop_image_action_buttons', 15 );
add_action( 'emallshop_before_shop_loop_item_title', 'emallshop_sale_product_countdown', 20 ); 
add_action( 'emallshop_before_shop_loop_item_title', 'emallshop_output_wrapper_end', 25 ); 
add_action( 'woocommerce_shop_loop_item_title', 'emallshop_product_content_wrapper', 5 );
add_action( 'woocommerce_shop_loop_item_title', 'emallshop_shop_loop_item_title_rating', 10 );
add_action( 'emallshop_shop_loop_item_title_rating', 'emallshop_shop_loop_item_title', 10 ); 
add_action( 'emallshop_shop_loop_item_title_rating', 'emallshop_product_rating_html', 15 ); 

/** After Shop Loop Item **/
//add_action( 'woocommerce_after_shop_loop_item_title', 'emallshop_product_rating_html', 5 );
add_action( 'woocommerce_after_shop_loop_item_title', 'emallshop_product_short_description', 5 );
add_action( 'woocommerce_after_shop_loop_item_title', 'emallshop_template_loop_price', 10 );  
add_action( 'emallshop_template_loop_price', 'woocommerce_template_loop_price', 10 );
add_action( 'woocommerce_after_shop_loop_item', 'emallshop_output_product_buttons_wrapper', 5 ); 
add_action( 'woocommerce_after_shop_loop_item', 'emallshop_output_cart_button_wrapper', 10 ); 
add_action( 'emallshop_output_cart_button_wrapper', 'woocommerce_template_loop_add_to_cart', 5 ); 
add_action( 'woocommerce_after_shop_loop_item', 'emallshop_loop_content_action_buttons', 10 ); 
add_action( 'woocommerce_after_shop_loop_item', 'emallshop_output_wrapper_end', 15 ); 
add_action( 'woocommerce_after_shop_loop_item', 'emallshop_output_product_attr', 15 );
add_action( 'woocommerce_after_shop_loop_item', 'emallshop_output_wrapper_end', 15 );
add_action( 'woocommerce_after_shop_loop_item', 'emallshop_output_wrapper_end', 20 );

/**
* Single Product
*/
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

/** Before Single Product Summary**/
add_action( 'woocommerce_before_single_product_summary', 'emallshop_show_product_loop_sale_flash', 10 );
add_action( 'woocommerce_single_product_summary', 'emallshop_template_single_title', 5 );
add_action( 'emallshop_template_single_title', 'woocommerce_template_single_title', 5 );
add_action( 'emallshop_template_single_title', 'emallshop_single_product_pagination', 10 );
add_action( 'woocommerce_single_product_summary', 'emallshop_template_single_price', 10 );
add_action( 'emallshop_template_single_price', 'woocommerce_template_single_price', 5 );
add_action( 'emallshop_template_single_price', 'emallshop_template_single_availability', 10 );
add_action( 'woocommerce_single_product_summary', 'emallshop_sale_product_countdown', 14 );
add_action( 'woocommerce_single_product_summary', 'emallshop_single_product_sizeguide', 30 );
add_action( 'woocommerce_product_meta_start', 'emallshop_template_single_brand', 5 );
if(function_exists( 'emallshop_single_sharing' )){
	add_action( 'woocommerce_single_product_summary', 'emallshop_single_sharing', 50);
}


/**
* Cart
*/
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );


add_action( 'emallshop_cart_totals', 'woocommerce_cart_totals', 10 );

add_action( 'woocommerce_proceed_to_checkout', 'emallshop_free_shipping_bar', 10 );

/**
 * Quantity Buttons
 */
add_action( 'woocommerce_before_quantity_input_field', 'emallshop_quantity_button_minus', 10 );
add_action( 'woocommerce_after_quantity_input_field', 'emallshop_quantity_button_plus', 10 );

 /**
 * Mini Cart
 *
 * @see emallshop_free_shipping_bar()
 */
add_action( 'woocommerce_widget_shopping_cart_total', 'emallshop_free_shipping_bar', 20 );

/**
 * AJAX Hooks
 */ 
add_action( 'wp_ajax_nopriv_products_live_search', 'emallshop_products_live_search' );
add_action( 'wp_ajax_products_live_search', 'emallshop_products_live_search' );

/*
* Woocommerce Hook Options
* @version 2.0
*/
if ( ! function_exists( 'emallshop_woocommerce_hook_options' )){
	function emallshop_woocommerce_hook_options(){
		
		if( emallshop_get_option('show-product-category-description', 1) && emallshop_get_option('show-description-position', 'top') =='bottom'){
			remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
			remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );
			
			add_action( 'emallshop_after_main_content', 'woocommerce_taxonomy_archive_description', 10 );
			add_action( 'emallshop_after_main_content', 'woocommerce_product_archive_description', 10 );
		}elseif( !emallshop_get_option('show-product-category-description', 1)){
			remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
			remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );
		}
		
		//Single Product Meta Remove
		if( !emallshop_get_option('show-single-productmetas', 1) ){
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		}
		
		/*Woocommerce Gallery Hook 3.0.x
		/* --------------------------------------------------------------------- */
		
		add_theme_support( 'wc-product-gallery-lightbox' );
		if(emallshop_get_option('enable-product-image-zoom', 1)){
			add_theme_support( 'wc-product-gallery-zoom' );
		}
		remove_theme_support( 'wc-product-gallery-slider' );
		
	}
}
add_action( 'init', 'emallshop_woocommerce_hook_options' );

/*
* WC Vendors Hook Options
*/
if ( ! function_exists( 'emallshop_wc_vendors_hook' )){
	function emallshop_wc_vendors_hook(){
		
		if( is_wc_vendors_activated() && emallshop_get_option('show-wc-vendors-sold-by-label', 1) ){
			if(emallshop_get_option('product-layout-style','product-style1')=='product-style3'){
				remove_action( 'woocommerce_after_shop_loop_item', array('WCV_Vendor_Shop', 'template_loop_sold_by'), 9 );
				add_action( 'woocommerce_after_shop_loop_item', array('WCV_Vendor_Shop', 'template_loop_sold_by'), 16 );
			}
			else{
				remove_action( 'woocommerce_after_shop_loop_item', array('WCV_Vendor_Shop', 'template_loop_sold_by'), 9 );
				add_action( 'woocommerce_after_shop_loop_item', array('WCV_Vendor_Shop', 'template_loop_sold_by'), 4 );
			}
		}else{
			remove_action( 'woocommerce_after_shop_loop_item', array('WCV_Vendor_Shop', 'template_loop_sold_by'), 9 );
		}
	}
}
add_action( 'init', 'emallshop_wc_vendors_hook' );

add_action( 'emallshop_after_footer', 'emallshop_sticky_add_to_cart_button',20 );
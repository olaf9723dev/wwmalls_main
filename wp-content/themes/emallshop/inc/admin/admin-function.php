<?php
 /**
 * EmallShop Include Admin Customizer Function
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* 	Get Options Header Style Image
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_options_header_style' ) ){
	function emallshop_options_header_style(){
		return array(
			'header-1' => array('alt' => esc_html__('Header Style 1', 'emallshop'), 'img' => EMALLSHOP_ADMIN_IMAGES.'/header/header-1.png'),
			'header-2' => array('alt' => esc_html__('Header Style 2', 'emallshop'), 'img' => EMALLSHOP_ADMIN_IMAGES.'/header/header-2.png'),
			'header-3' => array('alt' => esc_html__('Header Style 3', 'emallshop'), 'img' => EMALLSHOP_ADMIN_IMAGES.'/header/header-3.png'),
			'header-4' => array('alt' => esc_html__('Header Style 4', 'emallshop'), 'img' => EMALLSHOP_ADMIN_IMAGES.'/header/header-4.png'),
			'header-5' => array('alt' => esc_html__('Header Style 5', 'emallshop'), 'img' => EMALLSHOP_ADMIN_IMAGES.'/header/header-5.png'),
			'header-6' => array('alt' => esc_html__('Header Style 6', 'emallshop'), 'img' => EMALLSHOP_ADMIN_IMAGES.'/header/header-6.png'),
			'header-7' => array('alt' => esc_html__('Header Style 7', 'emallshop'), 'img' => EMALLSHOP_ADMIN_IMAGES.'/header/header-7.png'),
			'header-8' => array('alt' => esc_html__('Header Style 8', 'emallshop'), 'img' => EMALLSHOP_ADMIN_IMAGES.'/header/header-8.png'),
			'header-9' => array('alt' => esc_html__('Header Style 9', 'emallshop'), 'img' => EMALLSHOP_ADMIN_IMAGES.'/header/header-9.png'),
			'header-10' => array('alt' => esc_html__('Header Style 10', 'emallshop'), 'img' => EMALLSHOP_ADMIN_IMAGES.'/header/header-10.png'),
			'header-11' => array('alt' => esc_html__('Header Style 11', 'emallshop'), 'img' => EMALLSHOP_ADMIN_IMAGES.'/header/header-11.png'),
		);
	}
}

/* 	Get Options Page Heading Style Image
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_options_page_heading_style' ) ){
	function emallshop_options_page_heading_style(){
		return array(
			'page-heading-1' => array('alt' => esc_html__('Page Heading Style 1', 'emallshop'), 'img' => EMALLSHOP_ADMIN_IMAGES.'/page-heading/page-heading-1.png'),
			'page-heading-2' => array('alt' => esc_html__('Page Heading Style 2', 'emallshop'), 'img' => EMALLSHOP_ADMIN_IMAGES.'/page-heading/page-heading-2.png'),
			'page-heading-3' => array('alt' => esc_html__('Page Heading Style 3', 'emallshop'), 'img' => EMALLSHOP_ADMIN_IMAGES.'/page-heading/page-heading-3.png'),
			'page-heading-4' => array('alt' => esc_html__('Page Heading Style 4', 'emallshop'), 'img' => EMALLSHOP_ADMIN_IMAGES.'/page-heading/page-heading-4.png'),
		);
	}
}

/* 	Get Options Footer Style Image
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_options_footer_style' ) ){
	function emallshop_options_footer_style(){
		return array(
			'footer-1' => array('alt' => esc_html__('Footer Style 1', 'emallshop'), 'img' => EMALLSHOP_ADMIN_IMAGES.'/footer/footer-1.png'),
			'footer-2' => array('alt' => esc_html__('Footer Style 2', 'emallshop'), 'img' => EMALLSHOP_ADMIN_IMAGES.'/footer/footer-2.png'),
			'footer-3' => array('alt' => esc_html__('Footer Style 3', 'emallshop'), 'img' => EMALLSHOP_ADMIN_IMAGES.'/footer/footer-3.png'),
		);
	}
}
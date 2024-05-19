<?php
/**
 * Customize theme style functionality for EmallShop
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.2.3
 */

 /**
 * Styles the theme body.
 *
 * @since EmallShop 1.2.3
 *
 */
if ( ! function_exists( 'emallshop_theme_style' ) ) :
function emallshop_theme_style() {
	
	global $emallshop_options;
	$style_options = array();
	
	// Add custom user CSS
	$theme_css 			= trim( emallshop_get_option( 'custom-css', '' ) );	
	$pre_page_width		= ( emallshop_get_option( 'use-predefined-page-width', 'pre-defined' ) == 'pre-defined' ) ? emallshop_get_option( 'predefined-page-width', '1170' ) : emallshop_get_option( 'custom-page-width', '1170' );
	
	/*Defaul Setting*/
	
	/* Typography */
	$theme_font		= emallshop_get_option( 'body-typography', array( 'font-family' => 'Open Sans', 'google' => true, 'font-weight'  => '400', 'font-style'  => '400', 'font-size' => '14px'));
	
	/*Color*/
	$style_options['site']['theme_color'] =  emallshop_get_option( 'theme-color', '#0ba2e8' );
	$style_options['site']['theme_secondary_color'] =  emallshop_get_option( 'theme-secondary-color', '#ff8400' );
	$style_options['site']['text_color'] =  emallshop_get_option( 'body-text-color', '#656565' );
	$style_options['site']['heading_color'] =  emallshop_get_option( 'body-heading-color', '#212121' );
	$style_options['site']['input_background'] =  emallshop_get_option( 'body-input-background', '#ffffff' );
	$style_options['site']['input_color'] =  emallshop_get_option( 'body-input-color', '#656565' );
	$style_options['site']['link_color'] =  emallshop_get_option( 'body-link-color', array(
		'regular'	=> '#212121', 
		'hover' 	=> '#ff8400',
		'active' 	=> '#ff8400'
	));
	$style_options['site']['border'] =  emallshop_get_option( 'theme-border', array( 
		'border-color' 	=> '#e9e9e9', 
		'border-style'  => 'solid', 
		'border-top' 	=> '1px', 
		'border-right' 	=> '1px', 
		'border-bottom' => '1px', 
		'border-left'   => '1px'
	));
	$style_options['site']['border_radius'] =  emallshop_get_option( 'theme-border-radius', 3 );
	$style_options['site']['wrapper_padding'] =  emallshop_get_option( 'body-wrapper-padding', array( 
		'padding-top'		=> '0px',
		'padding-bottom' 	=> '0px',
		'units' 			=> 'px'
	) );
		
	/* Header */
	$style_options['header']['text_color'] = emallshop_get_option( 'header-text-color', '#656565' );
	$style_options['header']['input_background'] = emallshop_get_option( 'header-input-background', '#ffffff' );
	$style_options['header']['input_color'] = emallshop_get_option( 'header-input-color', '#656565' );
	$style_options['header']['link_color'] = emallshop_get_option( 'header-link-color', array( 
		'regular' 	=> '#212121',
		'hover' 	=> '#ff8400',
		'active' 	=> '#ff8400'
	));
	$style_options['header']['border'] = emallshop_get_option( 'header-border', array( 
		'border-color' 	=> '#e9e9e9',
		'border-style'  => 'solid',
		'border-top' 	=> '1px',
		'border-right' 	=> '1px', 
		'border-bottom' => '1px',
		'border-left'   => '1px'
	));
	$style_options['header']['border_color_rgb'] = 'rgba('. emallshop_hex2rgb_color( $style_options['header']['border']['border-color'] ). ','.emallshop_get_option( 'header-opacity', 1 ).')';
	$style_options['header']['padding'] = emallshop_get_option( 'header-padding', array( 
		'padding-top' 		=> '24px',
		'padding-bottom' 	=> '24px',
		'units' 			=> 'px'
	));
	
	/* Sticky Header */
	$style_options['sticky_header']['text_color'] = emallshop_get_option( 'sticky-header-text-color', '#656565' );
	$style_options['sticky_header']['input_background'] = emallshop_get_option( 'sticky-header-input-background', '#ffffff' );
	$style_options['sticky_header']['input_color'] = emallshop_get_option( 'sticky-header-input-color', '#656565' );
	$style_options['sticky_header']['link_color'] = emallshop_get_option( 'sticky-header-link-color', array( 
		'regular' 	=> '#212121',
		'hover' 	=> '#ff8400',
		'active' 	=> '#ff8400'
	));
	$style_options['sticky_header']['border'] = emallshop_get_option( 'sticky-header-border', array( 
		'border-color' 	=> '#e9e9e9',
		'border-style'  => 'solid',
		'border-top' 	=> '1px',
		'border-right' 	=> '1px',
		'border-bottom' => '1px',
		'border-left'   => '1px'
	));
	$style_options['sticky_header']['border_color_rgb'] = 'rgba('.emallshop_hex2rgb_color( $style_options['sticky_header']['border']['border-color'] ). ','.emallshop_get_option( 'sticky-header-opacity', 1 ).')';
	
	/* Topbar */
	$style_options['topbar']['background'] = emallshop_get_option( 'topbar-input-background', '#ffffff' );
	$style_options['topbar']['input_color'] = emallshop_get_option( 'topbar-input-color', '#656565' );
	$style_options['topbar']['text_color'] = emallshop_get_option( 'topbar-text-color', '#656565' );
	$style_options['topbar']['link_color'] = emallshop_get_option('topbar-link-color', array( 
		'regular' 	=> '#212121', 
		'hover' 	=> '#ff8400', 
		'active' 	=> '#ff8400'
	));
	$style_options['topbar']['border'] = emallshop_get_option( 'topbar-border', array( 
		'border-color' 	=> '#e9e9e9', 
		'border-style'  => 'solid', 
		'border-top' 	=> '1px', 
		'border-right' 	=> '1px', 
		'border-bottom' => '1px', 
		'border-left'   => '1px'
	));
	$style_options['topbar']['border_color_rgb'] = 'rgba('.emallshop_hex2rgb_color( $style_options['topbar']['border']['border-color'] ). ','.emallshop_get_option( 'topbar-opacity',1 ).')';
	
	/* Navigation */
	$style_options['navigation']['secondary_color'] = emallshop_get_option('navigation-secondary-color','#ff8400');
	$style_options['navigation']['text_color'] = emallshop_get_option('navigation-text-color','#ffffff');
	$style_options['navigation']['input_background'] = emallshop_get_option('navigation-input-background','#ffffff');
	$style_options['navigation']['input_color'] = emallshop_get_option('navigation-input-color','#656565');
	$style_options['navigation']['link_color'] = emallshop_get_option( 'navigation-link-color', array( 
		'regular' 	=> '#ffffff', 
		'hover' 	=> '#ff8400',
		'active' 	=> '#ff8400'
	));
	$style_options['navigation']['border'] = emallshop_get_option( 'navigation-border', array( 
		'border-color' 	=> '#19b0f6',
		'border-style'  => 'solid', 
		'border-top' 	=> '1px',
		'border-right' 	=> '1px',
		'border-bottom' => '1px',
		'border-left'   => '1px'
	));
	$style_options['navigation']['border_color_rgb'] = 'rgba('.emallshop_hex2rgb_color( $style_options['navigation']['border']['border-color'] ). ','.emallshop_get_option('navigation-opacity',1).')';
	
	/* Menu */
	$style_options['menu']['background'] = emallshop_get_option( 'menu-background-color', '#ffffff' );
	$style_options['menu']['text_color'] = emallshop_get_option( 'menu-text-color', '#656565' );
	$style_options['menu']['link_color'] = emallshop_get_option( 'menu-link-color', array( 
		'regular' 	=> '#212121',
		'hover' 	=> '#ff8400',
		'active' 	=> '#ff8400'
	));
	$style_options['menu']['border'] = emallshop_get_option( 'menu-border', array( 
		'border-color' 	=> '#e9e9e9', 
		'border-style'  => 'solid', 
		'border-top' 	=> '1px', 
		'border-right' 	=> '1px', 
		'border-bottom' => '1px', 
		'border-left'   => '1px'
	));
		
	/* Breadcrumb/Heading */
	$style_options['page_heading']['color'] = emallshop_get_option( 'page-heading-heading-color', '#212121' );
	$style_options['page_heading']['text_color'] = emallshop_get_option( 'page-heading-text-color', '#656565' );
	$style_options['page_heading']['link_color'] = emallshop_get_option( 'page-heading-link-color', array( 
		'regular' 	=> '#212121', 
		'hover' 	=> '#ff8400', 
		'active' 	=> '#ff8400'
	));
	$style_options['page_heading']['border'] = emallshop_get_option( 'page-heading-border', array( 
		'border-color' 	=> '#f5f5f5',
		'border-style'  => 'solid', 
		'border-top' 	=> '1px', 
		'border-right' 	=> '1px',
		'border-bottom' => '1px',
		'border-left'   => '1px'
	));
	$style_options['page_heading']['border_color_rgb'] = 'rgba('.emallshop_hex2rgb_color( $style_options['page_heading']['border']['border-color']). ','.emallshop_get_option('page-heading-opacity', 1 ).')';
	$style_options['page_heading']['padding'] = emallshop_get_option( 'page-heading-padding', array( 
		'padding-top' => '10px',
		'padding-bottom' => '10px',
		'units' => 'px'
	));
	
	/* Footer */
	$style_options['footer']['background'] = emallshop_get_option( 'footer-background' ,array( 
		'background-color' => '#fcfcfc',
		'background-image'=> '',
		'background-repeat' => '',
		'background-size' => '',
		'background-attachment' => '',
		'background-position' => ''
	));
	$style_options['footer']['background_rgb'] = ( $style_options['footer']['background']['background-color'] != 'transparent' ) ? 'rgba('.emallshop_hex2rgb_color( $style_options['footer']['background']['background-color'] ). ','.emallshop_get_option( 'footer-opacity', 1 ).')' : $style_options['footer']['background']['background-color'];
	$style_options['footer']['heading_color'] = emallshop_get_option( 'footer-heading-color', '#212121' );
	$style_options['footer']['text_color'] = emallshop_get_option( 'footer-text-color', '#656565' );
	$style_options['footer']['input_background'] = emallshop_get_option( 'footer-input-background', '#ffffff' );
	$style_options['footer']['input_color'] = emallshop_get_option( 'footer-input-color', '#656565' );
	$style_options['footer']['link_color'] = emallshop_get_option( 'footer-link-color', array( 
		'regular' 	=> '#212121',
		'hover' 	=> '#ff8400',
		'active' 	=> '#ff8400'
	));
	$style_options['footer']['border'] = emallshop_get_option( 'footer-border', array( 
		'border-color' 	=> '#e9e9e9',
		'border-style'  => 'solid',
		'border-top' 	=> '1px',
		'border-right' 	=> '1px',
		'border-bottom' => '1px',
		'border-left'   => '1px'
	));
	$style_options['footer']['border_color_rgb'] = 'rgba('.emallshop_hex2rgb_color( $style_options['footer']['border']['border-color'] ). ','.emallshop_get_option( 'footer-opacity', 1 ).')';
	$style_options['footer']['padding'] = emallshop_get_option( 'footer-padding', array( 
		'padding-top' => '42px',
		'padding-bottom' => '42px',
		'units' => 'px'
	));
	
	/* Copyright */
	$style_options['copyright']['text_color'] = emallshop_get_option( 'copyright-text-color', '#656565' );
	$style_options['copyright']['link_color'] = emallshop_get_option( 'copyright-link-color', array( 
		'regular' 	=> '#212121',
		'hover' 	=> '#ff8400',
		'active' 	=> '#ff8400'
	));
	$style_options['copyright']['border'] = emallshop_get_option( 'copyright-border', array( 
		'border-color' 	=> '#e9e9e9',
		'border-style'  => 'solid',
		'border-top' 	=> '1px',
		'border-right' 	=> '1px',
		'border-bottom' => '1px',
		'border-left'   => '1px'
	));
	$style_options['copyright']['border_color_rgb'] = 'rgba('.emallshop_hex2rgb_color( $style_options['copyright']['border']['border-color'] ). ','.emallshop_get_option( 'copyright-opacity', 1 ).')';
	$style_options['copyright']['padding'] = emallshop_get_option( 'copyright-padding', array( 
		'padding-top' => '14px',
		'padding-bottom' => '14px',
		'units' => 'px'
	));
	
	/** Woocommece */
	$style_options['label_color']['sale'] = emallshop_get_option( 'sale-highlight-label-color', '#60BF79' );
	$style_options['label_color']['new'] = emallshop_get_option( 'new-highlight-label-color', '#48c2f5' );
	$style_options['label_color']['featured'] = emallshop_get_option( 'featured-highlight-label-color', '#ff781e' );
	$style_options['label_color']['outofstock'] = emallshop_get_option( 'outofstock-highlight-label-color', '#FF4557' );
	
	/* Free Shiping Button Color */
	$style_options['free_shipping']['background'] = emallshop_get_option('shipping-bar-bg-color','#efefef');
	$style_options['free_shipping']['color'] = emallshop_get_option('shipping-bar-color','#0ba2e8');
	
	//Newsletter Set Defaul Color
	$style_options['newsletter']['color'] = emallshop_get_option( 'newsletter-color', '#ffffff' );
	$style_options['newsletter']['button_color'] = emallshop_get_option( 'newsletter-button-color',' #FF8400' );
			
	$loading_image	= EMALLSHOP_ADMIN_IMAGES.'/ajax-'.emallshop_get_option('pagination-loading-image','loader').'.gif';
	$loading_image	= esc_url( $loading_image );	
	
	$theme_css.='
	/*
	* Theme Font
	*/
	body,
	button,
	input,
	select,
	textarea {
		font-family:'. $theme_font['font-family'].' !important;
		font-size:'. $theme_font['font-size'].';
		font-weight:'. $theme_font['font-weight'].';
	}
	::-webkit-input-placeholder {
		font-family:' .$theme_font['font-family'].';
	}
	:-moz-placeholder {
		font-family:' .$theme_font['font-family'].';
	}
	::-moz-placeholder {
		font-family:'. $theme_font['font-family'].';
	}
	:-ms-input-placeholder {
		font-family:'. $theme_font['font-family'].';
	}
	
	/* 
	* page width
	*/
	.wrapper.boxed-layout, .wrapper .container{
		width:'. $pre_page_width.'px;
	}
	
	/* 
	* Body color Scheme 
	*/
	body{
		color:'. $style_options['site']['text_color'].';
	}
	h1, h2, h3, h4, h5, h6{
		color:'. $style_options['site']['heading_color'].';
	}
	a, .woocommerce ul.cart_list li a, .emallshop-vertical-menu.main-navigation > li > a{
		color:'. $style_options['site']['link_color']['regular'].';
	}
	a:hover, a:focus, #header .header-cart-content .cart-item-detail a:hover, .category-entry:hover .category-content a, .entry-media .post-link:hover a, .woocommerce ul.cart_list li a:hover, .entry-footer a:hover, .entry-title a:hover, .emallshop-vertical-menu.main-navigation > li > a:hover, .header-navigation .emallshop-vertical-menu.main-navigation > li.menu-item-has-children:hover > a, .header-navigation .emallshop-vertical-menu.main-navigation li.current-menu-ancestor > a, .header-navigation .emallshop-vertical-menu.main-navigation li.current-page-ancestor > a, .header-navigation .emallshop-vertical-menu.main-navigation > li.current_page_item > a {
		color:'. $style_options['site']['link_color']['hover'].';
	}
	.header-cart-content .cart_list.product_list_widget .mini_cart_item_title, .header-cart-content .cart_list.product_list_widget .empty, .header-cart-content .total, .header-cart-content .header_shopping_cart, .wishlist_table.images_grid li .item-details table.item-details-table td.label, .wishlist_table.mobile li .item-details table.item-details-table td.label, .wishlist_table.mobile li table.additional-info td.label, .wishlist_table.modern_grid li .item-details table.item-details-table td.label, .woocommerce .single-product-entry ul.zoo-cw-variations li .label label{
		color:'.$style_options['site']['text_color'].';
	}
	.product_list_widget .mini_cart_item .quantity, .woocommerce.widget_shopping_cart .total .amount, .header-cart-content .header_shopping_cart p.total .amount, .woocommerce ul.products .product-entry .product-content .price, .widget .product-price, .widget .product-categories li.current-cat-parent > a, .widget .product-categories li.current-cat-parent > span, .widget .product-categories li.current-cat > a, .widget .product-categories li.current-cat > span, .woocommerce .single-product-entry .product-price .price, .woocommerce .single-product-entry .single_variation .price, .single-product-entry .entry-summary .product-title-price .amount, .single-product-entry .entry-summary .product-title-price del, .single-product-entry .entry-summary .product-title-price ins, .entry-content .more-link, .portfolio-content .more-link, .services ul.services .service-item i, .entry-footer a, .entry-title a, .woocommerce .woocommerce-pagination ul.page-numbers span,.woocommerce .woocommerce-pagination ul.page-numbers a, .wcv_pagination .page-numbers span, .wcv_pagination .page-numbers a, .woocommerce ul.products .product-buttons .quickview:before, .woocommerce  ul.products .product-image .quickview-button a, .category-content, .category_and_sub_category_box .show-all-cate a, .categories-slider-content.sub_category_box .show-all-cate a, .entry-day, .woocommerce .single-product-entry .entry-summary .product_meta .sku_wrapper span, .woocommerce .single-product-entry .entry-summary .product_meta .brand_in a, .woocommerce .single-product-entry .entry-summary .product_meta .posted_in a, .woocommerce .single-product-entry .entry-summary .product_meta .tagged_as a, article.post .entry-header h2, .comment-list .comment-reply-link, .portfolio-list .portfolioFilter a, .portfolio-skill a, .entry-information ul p i, .portfolio-list .effect4 .portfolio-content, .header-middle .customer-support, .posts-navigation .pagination > li > a, .posts-navigation .pagination > li > span, .live-search-results .search-product-price, .dokan-pagination-container .dokan-pagination li a, .widget .woocommerce-Price-amount, .woocommerce div.product p.price, .woocommerce div.product span.price, table.group_table .label, table.group_table .price, .price.user-login a{
		color:'.$style_options['site']['theme_color'].';
	}
	.product-toolbar .gridlist-toggle > a, .woocommerce ul.products .product-buttons .compare:before, .woocommerce ul.products .product-buttons .add_to_wishlist:before, .woocommerce ul.products .yith-wcwl-wishlistaddedbrowse a:before, .woocommerce ul.products .yith-wcwl-wishlistexistsbrowse a:before, .woocommerce ul.products .product-content .product-buttons .quickview:before, .owl-theme .owl-nav .owl-prev, .owl-theme .owl-nav .owl-next, .single-product-entry .slick-arrow:before, .woocommerce-product-gallery__trigger:before, .single-product-entry .entry-summary .product-navbar, .woocommerce .single-product-entry .entry-summary .compare, .woocommerce .single-product-entry .entry-summary .yith-wcwl-add-to-wishlist a, .single-product-entry .entry-summary .product_meta .brand_in a:hover, .single-product-entry .entry-summary .product_meta .posted_in a:hover, .single-product-entry .entry-summary .product_meta .tagged_as a:hover, .post-navigation a, .header-post-navigation .nav-links li a, .woocommerce .widget_layered_nav ul.yith-wcan-label li a, .woocommerce-page .widget_layered_nav ul.yith-wcan-label li a, .woocommerce .widget_layered_nav ul.yith-wcan-label li span, .woocommerce-page .widget_layered_nav ul.yith-wcan-label li span, .footer .widget  ul.services .service-icon, .woocommerce ul.products .product-entry .product-content a:hover h3, article .hover-overlay-btn a i{
		color:'.$style_options['site']['theme_secondary_color'].';
	}
	button, .button, input[type="button"], input[type="reset"], input[type="submit"], .wp-block-search__button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .widget .tagcloud a:hover, .woocommerce div.product .woocommerce-tabs ul.tabs li.active, .woocommerce  ul.products .product-content .product-buttons .product-cart a.added_to_cart, .pagination .page-numbers.current, .lmp_load_more_button .lmp_button, .cwallowcookies.button, .entry-content .more-link:hover, .entry-summary .more-link:hover, .portfolio-content .more-link:hover, .entry-media blockquote, .entry-media .post-link, .woocommerce .woocommerce-pagination ul.page-numbers span.current, .woocommerce .woocommerce-pagination ul.page-numbers a:hover, .wcv_pagination .page-numbers span.current, .wcv_pagination .page-numbers a:hover, .widget_price_filter .ui-slider .ui-slider-handle, .product-section .section-tab .nav-tabs li a:hover, .product-section .section-tab .nav-tabs li.active a, .testimonials .quote-content p, .testimonials-list .quote-content p, .entry-date .entry-month, .back-to-top, .portfolio-list .portfolioFilter a.current, .portfolio-list .portfolioFilter a:hover, .portfolio-list .effect1 .hentry:hover .portfolio-content, .portfolio-list .effect2 .hentry:hover .portfolio-content, .portfolio-list .effect3 .hentry:hover .portfolio-content, .woocommerce-MyAccount-navigation > ul li a, .header-cart-content .cart-style-1 .cart-icon, .header-cart-content .heading-cart.cart-style-3 > i, .posts-navigation .pagination > li:hover > a, .topbar-notification .news-title, .owl-nav .owl-prev:hover, .owl-nav .owl-next:hover, .product-items li.product .product-image .owl-theme .owl-controls .owl-dot.active span, .product-items li.product .product-image .owl-theme .owl-controls.clickable .owl-dot:hover span, .woocommerce  ul.products.product-style3.grid-view li.product:hover .product-buttons .product-cart a, .woocommerce  ul.products.product-style3.product-carousel li.product:hover .product-buttons .product-cart a, .dokan-dashboard .dokan-dash-sidebar ul.dokan-dashboard-menu, input.dokan-btn-theme[type="submit"], a.dokan-btn-theme, .dokan-btn-theme, .dokan-single-store .profile-frame, .pagination-wrap ul.pagination > li > a:hover, .pagination-wrap ul.pagination > li > span.current, .dokan-pagination-container .dokan-pagination li:hover a, .dokan-pagination-container .dokan-pagination li.active a, input.dokan-btn-default[type="submit"], a.dokan-btn-default, .dokan-btn-default, .search-box-wrapper .search-box, .mobile-nav-tabs li.active{
		background-color:'.$style_options['site']['theme_color'].';
	}
	.woocommerce .selectBox-options li.selectBox-selected a{
		background-color:'.$style_options['site']['theme_color'].' !important;
	}
	.category-menu .category-menu-title, button:hover, .button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover,  input[type="submit"]:focus, .woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .live-search-results .autocomplete-suggestion:hover, .live-search-results .autocomplete-suggestion.autocomplete-selected, .lmp_load_more_button .lmp_button:hover, .cwallowcookies.button:hover, .cwcookiesmoreinfo.button:hover, .product-toolbar .gridlist-toggle .grid-view.active, .product-toolbar .gridlist-toggle .grid-view:hover, .product-toolbar .gridlist-toggle .list-view.active, .product-toolbar .gridlist-toggle .list-view:hover, .woocommerce ul.products .product-buttons .compare:hover, .woocommerce ul.products .product-buttons .compare.added, .woocommerce ul.products .product-buttons .add_to_wishlist:hover, .woocommerce ul.products .yith-wcwl-wishlistaddedbrowse a, .woocommerce ul.products .yith-wcwl-wishlistexistsbrowse a, .woocommerce ul.products .product-content .product-buttons .quickview:hover, .owl-theme .owl-dots .owl-dot.active span, .owl-theme .owl-dots .owl-dot:hover span, .owl-theme .owl-nav .owl-prev:hover, .owl-theme .owl-nav .owl-next:hover, .woocommerce .widget_price_filter .ui-slider .ui-slider-range, .countdown .countdown-section, .single-product-entry .slick-slider .slick-prev:hover, .single-product-entry .slick-slider .slick-next:hover, .woocommerce-product-gallery__trigger:hover:before, .woocommerce .single-product-entry .entry-summary .yith-wcwl-add-to-wishlist:hover, .woocommerce .single-product-entry .entry-summary .yith-wcwl-add-to-wishlist:hover a, .woocommerce .single-product-entry .entry-summary .compare:hover, .single-product-entry .entry-summary .product-prev:hover .product-navbar, .single-product-entry .entry-summary .product-next:hover .product-navbar, .back-to-top:hover, .post-navigation .nav-previous:hover, .post-navigation .nav-next:hover, .header-post-navigation .nav-links li:hover, .portfolio-content .project-url a, .woocommerce-MyAccount-navigation > ul li a:hover, .topbar-cart .mini-cart-count, .header-cart-content .cart-style-2 .mini-cart-count,.header-wishlist .wishlist-count,.navbar-icon .wishlist-count,.navbar-icon .compare-count,.header-compare .compare-count, .navbar-icon .cart-count, .topbar-cart .mini-cart-count, .header-navigation .header-cart-content .cart-style-1 .cart-icon, .header-navigation .header-cart-content .heading-cart.cart-style-3 > i, .woocommerce .widget_layered_nav ul.yith-wcan-label li a:hover, .woocommerce-page .widget_layered_nav ul.yith-wcan-label li a:hover, .woocommerce .widget_layered_nav ul.yith-wcan-label li.chosen a, .woocommerce-page .widget_layered_nav ul.yith-wcan-label li.chosen a, .product-items li.product .product-image .owl-nav .owl-prev, .product-items li.product .product-image .owl-nav .owl-next, article .hover-overlay-btn a i:hover, .hover-overlay-buttons .icon-animation:hover, .dokan-dashboard .dokan-dash-sidebar ul.dokan-dashboard-menu li:hover, .dokan-dashboard .dokan-dash-sidebar ul.dokan-dashboard-menu li.active, .dokan-dashboard .dokan-dash-sidebar ul.dokan-dashboard-menu li.dokan-common-links a:hover, input.dokan-btn-theme[type="submit"]:hover, a.dokan-btn-theme:hover, .dokan-btn-theme:hover, input.dokan-btn-theme[type="submit"]:focus, a.dokan-btn-theme:focus, .dokan-btn-theme:focus, input.dokan-btn-theme[type="submit"]:active, a.dokan-btn-theme:active, .dokan-btn-theme:active, input.dokan-btn-theme.active[type="submit"], a.dokan-btn-theme.active, .dokan-btn-theme.active, .open .dropdown-toggleinput.dokan-btn-theme[type="submit"], .open .dropdown-togglea.dokan-btn-theme, .open .dropdown-toggle.dokan-btn-theme, .dokan-single-store .profile-frame .profile-info-box .profile-info-summery-wrapper .profile-info-summery, input.dokan-btn-default[type="submit"]:hover, a.dokan-btn-default:hover, .dokan-btn-default:hover{
		background-color:'.$style_options['site']['theme_secondary_color'].';
	}
	.woocommerce .selectBox-options li.selectBox-hover a{
		background-color:'.$style_options['site']['theme_secondary_color'].' !important;
	}
	.product-section .section-header .section-title h3:before, .widget-section .widget-title h3:before, .testimonials-section .section-header .section-title h3:before, .widget .tagcloud a:hover, .woocommerce div.product .woocommerce-tabs ul.tabs li.active, .woocommerce div.product div.products h2 span:before, .cart-collaterals .cross-sells h2 span:before, .social-share h3 span:before, .navigation h3 span:before, .related-posts h3 span:before,.related-portfolios h3 span:before , #yith-wcwl-popup-message, .pagination .page-numbers.current, .entry-content .more-link:hover, .entry-summary .more-link:hover, .woocommerce .woocommerce-pagination ul.page-numbers span.current, .woocommerce .woocommerce-pagination ul.page-numbers a:hover, .wcv_pagination .page-numbers span.current, .wcv_pagination .page-numbers a:hover, .product-section .section-tab .nav-tabs li a:hover, .product-section .section-tab .nav-tabs li.active a, .portfolio-list .portfolioFilter a.current, .portfolio-list .portfolioFilter a:hover, .portfolio-list .default_effect .portfolio-content, .related-portfolios .default_effect .portfolio-content, .posts-navigation .pagination > li:hover > a, .newsletter-section .section-header .section-title h3:before, .owl-nav .owl-prev:hover, .owl-nav .owl-next:hover, input.dokan-btn-theme[type="submit"], a.dokan-btn-theme, .dokan-btn-theme, input.dokan-btn-default[type="submit"], a.dokan-btn-default, .dokan-btn-default, .zoo-cw-active.zoo-cw-attribute-option .zoo-cw-attr-item{
		border-color:'.$style_options['site']['theme_color'].';
	}
	.single-product-entry .entry-summary .product-next .product-next-popup:before, .single-product-entry .entry-summary .product-prev:hover .product-prev-popup:before, .woocommerce div.product .woocommerce-tabs ul.tabs:before, .product-section.products_carousel .section-tab, .post-navigation .nav-next .post-nav-thumb, .header-post-navigation .nav-next .post-nav-thumb, .post-navigation .nav-previous .post-nav-thumb, .header-post-navigation .nav-previous .post-nav-thumb{
		border-bottom-color:'.$style_options['site']['theme_color'].' !important;
	}
	.widget-area .widget, .dokan-widget-area .widget, .single-product-entry .entry-summary .product-next .product-next-popup, .single-product-entry .entry-summary .product-prev .product-prev-popup, .post-navigation .nav-next .post-nav-thumb:before, .header-post-navigation .nav-next .post-nav-thumb:before, .post-navigation .nav-previous .post-nav-thumb:before, .header-post-navigation .nav-previous .post-nav-thumb:before, .emallshop-main-menu .sub-menu, .emallshop-main-menu .emallshop-megamenu-wrapper, .footer {
		border-top-color:'.$style_options['site']['theme_color'].';
	}
	.product-toolbar .gridlist-toggle .grid-view.active, .product-toolbar .gridlist-toggle .grid-view:hover, .product-toolbar .gridlist-toggle .list-view.active, .product-toolbar .gridlist-toggle .list-view:hover, .woocommerce ul.products .product-content .product-extra-info, .owl-theme .owl-nav .owl-prev:hover, .owl-theme .owl-nav .owl-next:hover, .blogs_carousel .blog-entry .hentry:hover, .woocommerce .single-product-entry .entry-summary .yith-wcwl-add-to-wishlist:hover, .woocommerce .single-product-entry .entry-summary .yith-wcwl-add-to-wishlist:hover a, .woocommerce .single-product-entry .entry-summary .compare:hover, .single-product-entry .entry-summary .product-prev:hover .product-navbar, .single-product-entry .entry-summary .product-next:hover .product-navbar, .woocommerce .widget_layered_nav ul.yith-wcan-label li a, .woocommerce-page .widget_layered_nav ul.yith-wcan-label li a, .woocommerce .widget_layered_nav ul.yith-wcan-label li span, .woocommerce-page .widget_layered_nav ul.yith-wcan-label li span, .woocommerce .widget_layered_nav ul.yith-wcan-label li a:hover, .woocommerce-page .widget_layered_nav ul.yith-wcan-label li a:hover, .woocommerce .widget_layered_nav ul.yith-wcan-label li.chosen a, .woocommerce-page .widget_layered_nav ul.yith-wcan-label li.chosen a,  input.dokan-btn-theme[type="submit"]:hover, a.dokan-btn-theme:hover, .dokan-btn-theme:hover, input.dokan-btn-theme[type="submit"]:focus, a.dokan-btn-theme:focus, .dokan-btn-theme:focus, input.dokan-btn-theme[type="submit"]:active, a.dokan-btn-theme:active, .dokan-btn-theme:active, input.dokan-btn-theme.active[type="submit"], a.dokan-btn-theme.active, .dokan-btn-theme.active, .open .dropdown-toggleinput.dokan-btn-theme[type="submit"], .open .dropdown-togglea.dokan-btn-theme, .open .dropdown-toggle.dokan-btn-theme, input.dokan-btn-default[type="submit"]:hover, a.dokan-btn-default:hover, .dokan-btn-default:hover, .zoo-cw-attribute-option.cw-active .zoo-cw-attr-item, .zoo-cw-attribute-option:not(.disabled):hover .zoo-cw-attr-item{
		border-color:'.$style_options['site']['theme_secondary_color'].';
	}
	ul.main-navigation .sub-menu,
	ul.main-navigation .emallshop-megamenu-wrapper,
	.header-cart-content .header_shopping_cart,
	.search-box-wrapper .search-box{
		border-top-color:'.$style_options['site']['theme_secondary_color'].';
	}
	.product-items .list-view li.product:hover,
	.header-cart-content .header_shopping_cart:before,
	.search-box-wrapper .search-box:before{
		border-bottom-color:'.$style_options['site']['theme_secondary_color'].';
	}
	.woocommerce a.selectBox, .woocommerce .selectBox-dropdown, .selectBox-dropdown-menu li a .woocommerce a.selectBox, .woocommerce a.selectBox:hover, .posts-navigation .pagination > li > a, .posts-navigation .pagination > li > span, ul.zoo-cw-variations .zoo-cw-attr-item{
		border-color:'.$style_options['site']['border']['border-color'].';
	}
	.woocommerce a.selectBox, .woocommerce a.selectBox:hover{
		border-bottom-color:'.$style_options['site']['border']['border-color'].' !important;
	}
	.product-items .list-view li.product{
		border-bottom-color:'.$style_options['site']['border']['border-color'].';
		border-bottom-style:'.$style_options['site']['border']['border-style'].';
	}
	.product-toolbar .gridlist-toggle > a, table, th, td,.emallshop-main-menu .emallshop-vertical-menu, .widget, .secondary .widget > select, .widget .tagcloud a, .widget.yith-woocompare-widget .clear-all, .dokan-form-control, .comment-area-wrap, .comment-avatar img, .woocommerce-tabs .panel, .product-section .section-inner, .woocommerce .col2-set .col-1, .woocommerce-page .col2-set .col-1, .woocommerce .col2-set .col-2,.woocommerce-page .col2-set .col-2, .woocommerce .woocommerce-pagination ul.page-numbers span, .woocommerce .woocommerce-pagination ul.page-numbers a, .wcv_pagination .page-numbers span, .wcv_pagination .page-numbers a, .related-posts .hentry, .services ul.services .service-item, .testimonials-section .quote-meta .client-image, .blogs_carousel .blog-entry .hentry, input, textarea, .wp-block-search__input, .woocommerce div.product .woocommerce-tabs ul.tabs li, .blog-posts .hentry, .portfolio-list .portfolioFilter a, .portfolio-list .hentry, .related-portfolios .hentry, .woocommerce form.checkout_coupon, .woocommerce form.login, .woocommerce form.register, .search-area, select, .emallshop-vertical-menu.main-navigation, .navigation.comment-navigation .nav-links, .search-control-group .tt-menu, .header-services .icon-service, .product-section .section-tab .nav-tabs li a, .single-product-entry .images #product-image, .single-product-entry .flex-viewport, .dokan-pagination-container .dokan-pagination li a, .pagination-wrap ul.pagination > li > a, ul.dokan-seller-wrap li.dokan-list-single-seller .dokan-store-thumbnail, .selectBox-dropdown, .woocommerce #reviews #comments ol.commentlist li .comment-text, .woocommerce #reviews #comments ol.commentlist li img.avatar, .product-thumbnails .slick-slide, .page-content .wcmp_main_page, .page-content .wcmp_regi_main .wcmp_regi_form_box,.quantity input[type="button"]{	
		border-top:'.$style_options['site']['border']['border-top'].';
		border-bottom:'.$style_options['site']['border']['border-bottom'].';
		border-left:'.$style_options['site']['border']['border-left'].';
		border-right:'.$style_options['site']['border']['border-right'].';
		border-style:'.$style_options['site']['border']['border-style'].';
		border-color:'.$style_options['site']['border']['border-color'].';
	}
	.dokan-pagination-container .dokan-pagination li:hover a, .dokan-pagination-container .dokan-pagination li.active a, .pagination-wrap ul.pagination > li > a:hover, .pagination-wrap ul.pagination > li > span.current{
		border-top:'.$style_options['site']['border']['border-top'].';
		border-bottom:'.$style_options['site']['border']['border-bottom'].';
		border-left:'.$style_options['site']['border']['border-left'].';
		border-right:'.$style_options['site']['border']['border-right'].';
		border-style:'.$style_options['site']['border']['border-style'].';
		border-color:'.$style_options['site']['theme_color'].';
	}
	.product-items li.product:hover, .product-items li.category-entry:hover, .product-section.products_brands .brands-carousel li.brand-item:hover, .woocommerce ul.products .product-buttons .add_to_wishlist, .woocommerce ul.products .yith-wcwl-wishlistaddedbrowse a, .woocommerce ul.products .yith-wcwl-wishlistexistsbrowse a, .woocommerce ul.products .product-content .product-buttons .quickview, .woocommerce ul.products .product-buttons .compare, .owl-theme .owl-nav .owl-prev, .owl-theme .owl-nav .owl-next, .single-product-entry .slick-arrow, .woocommerce-product-gallery__trigger:before, .single-product-entry .entry-summary .product-navbar, .woocommerce .single-product-entry .entry-summary .yith-wcwl-add-to-wishlist, .post-navigation .nav-previous, .post-navigation .nav-next, .header-post-navigation .nav-links li:hover, .header-post-navigation .nav-links li, article .hover-overlay-btn a i, .woocommerce  ul.products .product-content .product-extra-info{
		border-top:'.$style_options['site']['border']['border-top'].';
		border-bottom:'.$style_options['site']['border']['border-bottom'].';
		border-left:'.$style_options['site']['border']['border-left'].';
		border-right:'.$style_options['site']['border']['border-right'].';
		border-style:'.$style_options['site']['border']['border-style'].';
		border-color:'.$style_options['site']['theme_secondary_color'].';
	}
	.emallshop-main-menu > ul.emallshop-vertical-menu > li > a, .emallshop-main-menu ul.emallshop-vertical-menu .sub-menu li, .widget-title,.widget_rss li, .widget ul.post-list-widget li, .widget ul.product_list_widget li, .portfolio_one_column .entry-portfolio .portfolio-skill, .woocommerce .single-product-entry .entry-summary .cart, .woocommerce div.product div.products h2, .cart-collaterals .cross-sells h2, .social-share h3, .navigation h3, .related-posts h3, .related-portfolios h3, .product-section.categories_and_products .section-title > a h3, .product-section .section-tab .nav-tabs, .product-section .section-tab .nav-tabs li a, .product-section .section-header .section-title, .newsletter-section .section-header .section-title, .testimonials-section .section-header .section-title, .widget-section .widget-title, .woocommerce ul.cart_list li, .woocommerce ul.product_list_widget li, .woocommerce .wishlist_table.mobile li, .product-toolbar, .product .entry-summary .product-countdown, .portfolio-list .one_column_grid, .portfolio-list .one_column_grid .portfolio-skill, .emallshop-vertical-menu.main-navigation > li > a, .related-posts h3,.comment-list > li:not( :last-child ), .title_with_products_tab.product-section .section-header, ul.dokan-seller-wrap li.dokan-list-single-seller .dokan-store-banner-wrap, .live-search-results .autocomplete-suggestion,.wcmp-tab-header{
		border-bottom:'.$style_options['site']['border']['border-bottom'].';
		border-style:'.$style_options['site']['border']['border-style'].';
		border-color:'.$style_options['site']['border']['border-color'].';
	}
	.portfolio_one_column .entry-portfolio .portfolio-skill, .woocommerce .single-product-entry .entry-summary .cart, .product-items .category-entry, .woocommerce-pagination, .wcv_pagination, .portfolio-list .one_column_grid .portfolio-skill, .woocommerce  ul.products .product-content .product-attrs, .widget .maxlist-more,.comment-list .children{
		border-top:'.$style_options['site']['border']['border-top'].';
		border-style:'.$style_options['site']['border']['border-style'].';
		border-color:'.$style_options['site']['border']['border-color'].';
	}
	
	.loading .pl-loading:after,
	.header_shopping_cart .loading:before,
	.woocommerce #respond input#submit.loading:after,
	.woocommerce a.button.loading:after,
	.woocommerce button.button.loading:after,
	.woocommerce input.button.loading:after,
	.yith-wcwl-add-button.show_loading a:after,
	.woocommerce .blockUI.blockOverlay:after,
	.woocommerce .compare .blockUI.blockOverlay:after,
	.woocommerce .loader:after,
	.zoo-cw-gallery-loading .pl-loading:after{
		border-color: '.$style_options['site']['theme_color'].';
	}
	.loading .pl-loading:after,
	.header_shopping_cart .loading:before,
	.woocommerce #respond input#submit.loading:after,
	.woocommerce a.button.loading:after,
	.woocommerce button.button.loading:after,
	.woocommerce input.button.loading:after,
	.yith-wcwl-add-button.show_loading a:after,
	.woocommerce .blockUI.blockOverlay:after,
	.woocommerce .compare .blockUI.blockOverlay:after,
	.woocommerce .loader:after,
	.zoo-cw-gallery-loading .pl-loading:after{
		border-right-color: '.$style_options['site']['border']['border-color'].' !important;
		border-top-color: '.$style_options['site']['border']['border-color'].' !important;	
	}
	.loading .pl-loading:after,
	.header_shopping_cart .loading:before,
	.zoo-cw-gallery-loading .pl-loading:after,
	.woocommerce .blockUI.blockOverlay:after{
		border-bottom-color: '.$style_options['site']['border']['border-color'].' !important;
		border-right-color: '.$style_options['site']['border']['border-color'].' !important;
		border-top-color: '.$style_options['site']['border']['border-color'].' !important;		
	}
	.loading .pl-loading:after,
	.header_shopping_cart .loading:before,
	.zoo-cw-gallery-loading .pl-loading:after,
	.woocommerce .blockUI.blockOverlay:after{
		border-left-color: '.$style_options['site']['theme_color'].' !important;
	}
	
	button, input, select, textarea, .button, input[type="button"], input[type="reset"], input[type="submit"], .wp-block-search__input, .wp-block-search__button, .lmp_load_more_button .lmp_button, ul.main-navigation li a .emallshop-menu-label span, .post-navigation .nav-next .post-nav-thumb, .header-post-navigation .nav-next .post-nav-thumb, .post-navigation .nav-previous .post-nav-thumb, .header-post-navigation .nav-previous .post-nav-thumb, .posts-navigation, ul.social-link li a, .wcaccount-topbar .wcaccount-dropdown, .search-area, .navigation.comment-navigation .nav-links, .selectBox-dropdown-menu.categories-filter-selectBox-dropdown-menu, .search-control-group .tt-menu, .header-cart-content .heading-cart.cart-style-3 > i, .header-services .icon-service, .header-cart-content .header_shopping_cart, .widget .tagcloud a, .widget.yith-woocompare-widget .clear-all, .dokan-form-control, .blog-posts .hentry, .entry-thumbnail .hover-overlay-btn a i, .entry-content .more-link, .entry-summary .more-link, .portfolio-content .more-link, .blogs_carousel .blog-entry .hentry, .entry-date, .comment-area-wrap, .comment-avatar img, .woocommerce-tabs #reviews .comment-text, .comment-list, .woocommerce #content div.product #reviews .comment img, .woocommerce div.product #reviews .comment img, .woocommerce-page #content div.product #reviews .comment img, .woocommerce-page div.product #reviews .comment img, .related-posts .hentry, .testimonials-section .quote-content p, .testimonials-section .quote-meta .client-image, .testimonials .quote-content p, .testimonials-list .quote-content p, .product-section.products_brands .brands-carousel .slide-row li.brand-item, .countdown .countdown-section, .emallshop-notice-wrapper, .category-content, .woocommerce .selectBox-dropdown, .selectBox-dropdown-menu li a .woocommerce a.selectBox, .woocommerce a.selectBox:hover, .product-items li.product, .product-items li.category-entry, .woocommerce  ul.products.product-style2 .product-content .product-buttons .compare, .woocommerce  ul.products.product-style2 .product-content .product-buttons .add_to_wishlist, .woocommerce  ul.products.product-style2 .product-content .yith-wcwl-wishlistaddedbrowse a, .woocommerce  ul.products.product-style2 .product-content .yith-wcwl-wishlistexistsbrowse a, .woocommerce  ul.products.product-style2 .product-content .product-buttons .quickview, .woocommerce  ul.products.product-style1 .product-content .product-buttons .compare, .woocommerce  ul.products.product-style1 .product-content .product-buttons .add_to_wishlist, .woocommerce  ul.products.product-style1 .product-content .yith-wcwl-wishlistaddedbrowse a, .woocommerce  ul.products.product-style1 .product-content .yith-wcwl-wishlistexistsbrowse a, .woocommerce  ul.products.product-style1 .product-content .product-buttons .quickview, .woocommerce  ul.products.product-style2 .product-content .product-buttons .product-cart a, .woocommerce  ul.products.product-style1 .product-content .product-buttons .product-cart a, .woocommerce ul.products.product-style3.list-view .product-buttons .product-cart a, .woocommerce ul.products.product-style3.list-view .product-buttons .compare, .woocommerce ul.products.product-style3.list-view .product-buttons .add_to_wishlist, .woocommerce ul.products.product-style3.list-view .yith-wcwl-wishlistaddedbrowse a, .woocommerce ul.products.product-style3.list-view .yith-wcwl-wishlistexistsbrowse a, .woocommerce ul.products.product-style3.list-view .product-content .product-buttons .quickview, .woocommerce ul.products.product-style3.list-view .product-content .product-buttons .product-cart a, .single-product-entry .images #product-image, .single-product-entry .flex-viewport, .single-product-entry .entry-summary .product-next .product-next-popup, .single-product-entry .entry-summary .product-prev .product-prev-popup, .woocommerce table.shop_table, .woocommerce .cart_totals, .woocommerce-checkout .order_review, .order_details-area, .customer-details-area, .woocommerce .col2-set .col-1, .woocommerce-page .col2-set .col-1, .woocommerce .col2-set .col-2, .woocommerce-page .col2-set .col-2, .woocommerce form.checkout_coupon, .woocommerce form.login, .woocommerce form.register, .woocommerce-MyAccount-navigation > ul li a, .portfolio-list .hentry, .related-portfolios .hentry, .portfolio-content .project-url a, .woocommerce .single-product-entry .entry-summary .yith-wcwl-add-to-wishlist, .woocommerce .single-product-entry .entry-summary .compare, .portfolio-list .portfolioFilter a, .widget-area .widget, .dokan-widget-area .widget, .content-area .rev_slider_wrapper li.tp-revslider-slidesli, div.wpb_single_image .vc_single_image-wrapper img, .post-slider.owl-carousel .owl-nav .owl-prev, .post-slider.owl-carousel .owl-nav .owl-next, #cookie-notice.cn-bottom.box, .category-banner-content .category-banner, .newsletter-content.modal-content, .wpb_wrapper .vc_single_image-wrapper, .dashboard-widget, input.dokan-btn[type="submit"], a.dokan-btn, .dokan-btn, ul.dokan-seller-wrap li.dokan-list-single-seller .dokan-store-thumbnail, .search-box-wrapper .search-box, ul.main-navigation .sub-menu, ul.main-navigation .emallshop-megamenu-wrapper, .icon-animation:after, .default-search-wrapper .search-toggle, .product-thumbnails .slick-slide,.woocommerce-product-gallery__trigger:before, .page-content .wcmp_main_page, .page-content .wcmp_regi_main .wcmp_regi_form_box {
		border-radius: '.$style_options['site']['border_radius'].'px;
	}
	.back-to-top, .product-section .section-tab .nav-tabs li a, .woocommerce div.product .woocommerce-tabs ul.tabs li, .vertical-menu-section .category-menu .category-menu-title{
		border-radius:'.$style_options['site']['border_radius'].'px '.$style_options['site']['border_radius'].'px 0 0;
	}
	.woocommerce  ul.products .product-content .product-extra-info, .woocommerce div.product .woocommerce-tabs .panel{
		border-radius: 0 0 '.$style_options['site']['border_radius'].'px '.$style_options['site']['border_radius'].'px;
	}
	::-webkit-input-placeholder {
	   color:'.$style_options['site']['text_color'].';
	}
	:-moz-placeholder { /* Firefox 18- */
	  color:'.$style_options['site']['text_color'].';
	}
	::-moz-placeholder {  /* Firefox 19+ */
	   color:'.$style_options['site']['text_color'].';
	}
	:-ms-input-placeholder {  
	   color:'.$style_options['site']['text_color'].';
	}
	input, select, textarea, .woocommerce a.selectBox{
		background-color:'.$style_options['site']['input_background'].';
	}
	input, select, textarea, .woocommerce a.selectBox{
		color:'.$style_options['site']['input_color'].';
	}
	
	@media only screen and (max-width : 480px) {		
		.woocommerce-cart table.cart tr, .woocommerce table.wishlist_table tbody tr{
			border-top:'.$style_options['site']['border']['border-top'].';
			border-bottom:'.$style_options['site']['border']['border-bottom'].';
			border-left:'.$style_options['site']['border']['border-left'].';
			border-right:'.$style_options['site']['border']['border-right'].';
			border-style:'.$style_options['site']['border']['border-style'].';
			border-color:'.$style_options['site']['border']['border-color'].';
		}
		.woocommerce-cart table.cart tr:last-child, .woocommerce table.wishlist_table tbody tr:last-child{
			border-bottom:'.$style_options['site']['border']['border-bottom'].';
			border-style:'.$style_options['site']['border']['border-style'].';
			border-color:'.$style_options['site']['border']['border-color'].';
		}
	}
	@media (min-width:480px) and (max-width:620px){
		.woocommerce-cart table.cart tr, 
		.woocommerce table.wishlist_table tbody tr{
			border-top:'.$style_options['site']['border']['border-top'].';
			border-bottom:'.$style_options['site']['border']['border-bottom'].';
			border-left:'.$style_options['site']['border']['border-left'].';
			border-right:'.$style_options['site']['border']['border-right'].';
			border-style:'.$style_options['site']['border']['border-style'].';
			border-color:'.$style_options['site']['border']['border-color'].';
		}
		.woocommerce-cart table.cart tr:last-child, 
		.woocommerce table.wishlist_table tbody tr:last-child{
			border-bottom:'.$style_options['site']['border']['border-bottom'].';
			border-style:'.$style_options['site']['border']['border-style'].';
			border-color:'.$style_options['site']['border']['border-color'].';
		}
	}
	@media only screen and (max-width : 991px) {
		.section-sub-categories{
			border-top:'.$style_options['site']['border']['border-top'].';
			border-bottom:'.$style_options['site']['border']['border-bottom'].';
			border-left:'.$style_options['site']['border']['border-left'].';
			border-right:'.$style_options['site']['border']['border-right'].';
			border-style:'.$style_options['site']['border']['border-style'].';
			border-color:'.$style_options['site']['border']['border-color'].';
		}
		.section-sub-categories{
			border-radius:3px;
		}
	}';
	if( is_rtl() ){
		$theme_css.='
		.product-items .category-entry{
			border-left:'.$style_options['site']['border']['border-left'].';
			border-style:'.$style_options['site']['border']['border-style'].';
			border-color:'.$style_options['site']['border']['border-color'].';
		}
		.product-section.categories_and_products .section-tab, .categories_and_products.brands-products .section-content, .categories_and_products.only-categories .section-content, .categories_and_products.brands-categories .section-content, .product-section .section-brands .banner-img, .search-control-group .search-bar-controls, .wcmp_main_page .wcmp_side_menu{
			border-right:'.$style_options['site']['border']['border-right'].';
			border-style:'.$style_options['site']['border']['border-style'].';
			border-color:'.$style_options['site']['border']['border-color'].';
		}
		.widget li a::before, .topbar-notification .news-title::before{
			border-right-color:'.$style_options['site']['theme_color'].';
		}		
		.widget li a::before, .topbar-notification .news-title::before{
			border-right-color:'.$style_options['site']['theme_color'].';
		}
		.topbar-notification .news-title, .header-cart-content .cart-style-1 .cart-icon, .owl-theme .owl-nav .owl-prev, .services ul.services .service-item:first-child, .product-toolbar .gridlist-toggle > a:first-child, .woocommerce .woocommerce-pagination ul.page-numbers li:first-child .page-numbers, .wcv_pagination li:first-child .page-numbers, .product-items li.product .product-image .owl-nav .owl-prev, .single-product-entry .product-prev .product-navbar, .header-post-navigation .nav-links li:first-child{
			border-radius: 0 '.$style_options['site']['border_radius'].'px '.$style_options['site']['border_radius'].'px 0;
		}
		.search-area .input-search-btn .search-btn, .header-cart-content .cart-style-1 .mini-cart-count, .owl-theme .owl-nav .owl-next, .services ul.services .service-item:last-child, .product-toolbar .gridlist-toggle > a:last-child, .woocommerce .woocommerce-pagination ul.page-numbers li:last-child .page-numbers, .wcv_pagination li:last-child .page-numbers, .product-items li.product .product-image .owl-nav .owl-next, .single-product-entry .product-next .product-navbar, .header-post-navigation .nav-links li:last-child, .mobile-menu-wrapper #mobile-nav-close{
			border-radius:'.$style_options['site']['border_radius'].'px 0 0 '.$style_options['site']['border_radius'].'px;
		}
		.pagination > li:last-child > a, .pagination > li:last-child > span, .dokan-pagination > li:last-child > a{
			border-bottom-left-radius: '.$style_options['site']['border_radius'].'px;
			border-top-left-radius:'.$style_options['site']['border_radius'].'px;	
		}
		.pagination > li:first-child > a, .pagination > li:first-child > span, .dokan-pagination > li:first-child > a{
			border-bottom-right-radius:'.$style_options['site']['border_radius'].'px;
			border-top-right-radius:'.$style_options['site']['border_radius'].'px;
		}
		
		';
	}else{
		$theme_css.='
		.product-items .category-entry{
			border-right:'.$style_options['site']['border']['border-right'].';
			border-style:'.$style_options['site']['border']['border-style'].';
			border-color:'.$style_options['site']['border']['border-color'].';
		}
		.product-section.categories_and_products .section-tab, .categories_and_products.brands-products .section-content, .categories_and_products.only-categories .section-content, .categories_and_products.brands-categories .section-content, .product-section .section-brands .banner-img, .search-control-group .search-bar-controls, .wcmp_main_page .wcmp_side_menu{
			border-left:'.$style_options['site']['border']['border-left'].';
			border-style:'.$style_options['site']['border']['border-style'].';
			border-color:'.$style_options['site']['border']['border-color'].';
		}
		.widget li a::before, .topbar-notification .news-title::before{
			border-left-color:'.$style_options['site']['theme_color'].';
		}		
		.widget li a::before, .topbar-notification .news-title::before{
			border-left-color:'.$style_options['site']['theme_color'].';
		}
		.topbar-notification .news-title, .header-cart-content .cart-style-1 .cart-icon, .owl-theme .owl-nav .owl-prev, .services ul.services .service-item:first-child, .product-toolbar .gridlist-toggle > a:first-child, .woocommerce .woocommerce-pagination ul.page-numbers li:first-child .page-numbers, .wcv_pagination li:first-child .page-numbers, .product-items li.product .product-image .owl-nav .owl-prev, .single-product-entry .product-prev .product-navbar, .header-post-navigation .nav-links li:first-child{
			border-radius:'.$style_options['site']['border_radius'].'px 0 0 '.$style_options['site']['border_radius'].'px;
		}
		.search-area .input-search-btn .search-btn, .header-cart-content .cart-style-1 .mini-cart-count, .owl-theme .owl-nav .owl-next, .services ul.services .service-item:last-child, .product-toolbar .gridlist-toggle > a:last-child, .woocommerce .woocommerce-pagination ul.page-numbers li:last-child .page-numbers, .wcv_pagination li:last-child .page-numbers, .product-items li.product .product-image .owl-nav .owl-next, .single-product-entry .product-next .product-navbar, .header-post-navigation .nav-links li:last-child, .mobile-menu-wrapper #mobile-nav-close{
			border-radius:0 '.$style_options['site']['border_radius'].'px '.$style_options['site']['border_radius'].'px 0;
		}
		.pagination > li:last-child > a, .pagination > li:last-child > span, .dokan-pagination > li:last-child > a{
			border-bottom-right-radius: '.$style_options['site']['border_radius'].'px;
			border-top-right-radius:'.$style_options['site']['border_radius'].'px;	
		}
		.pagination > li:first-child > a, .pagination > li:first-child > span, .dokan-pagination > li:first-child > a{
			border-bottom-left-radius:'.$style_options['site']['border_radius'].'px;
			border-top-left-radius:'.$style_options['site']['border_radius'].'px;
		}
		
		';
	}
	
	$theme_css.='
	/*
	* header color scheme
	*/	
	.header-middle{
		color:'.$style_options['header']['text_color'].';
		padding: '.$style_options['header']['padding']['padding-top'].' 0 '.$style_options['header']['padding']['padding-bottom'].' 0;
	}
	.header-middle .header-right > span > a,
	.header-middle .header-cart.cart-style-2 > a,
	.header-middle .header-cart-content .heading-cart.cart-style-3 h6,
	.header-middle .header-cart-content .heading-cart.cart-style-3 a,
	.header-middle .header-cart-content .heading-cart.cart-style-3 h6,
	.header-services .content-service h6, .search-toggle::before,
	.header-middle .mobile-main-navigation .toggle-menu h4,
	.header-middle .customer-support-email,
	.header-middle .customer-support-call{
		color:'.$style_options['header']['text_color'].';
	}
	.navbar-toggle .icon-bar{
		background-color:'.$style_options['header']['text_color'].';
	}
	.header-middle .search-area,
	.woocommerce .header-middle .search-categories a.selectBox{
		background-color:'.$style_options['header']['input_background'].';
	}
	.header-middle .search-area .search-field,
	.header-middle .search-categories a.selectBox,
	.header-middle .search-categories .categories-filter{
		color:'.$style_options['header']['input_color'].';
	}
	.header-middle a{
		color:'.$style_options['header']['link_color']['regular'].';
	}
	.header-middle a:hover,
	.header-middle .header-right > span > a:hover,
	.header-middle .header-cart.cart-style-2 > a:hover,
	.header-middle ul.main-navigation > li.menu-item-has-children:hover > a,
	.header-middle ul.main-navigation li.current-menu-ancestor > a,
	.header-middle ul.main-navigation li.current-page-ancestor > a,
	.header-middle ul.main-navigation > li.current_page_item > a{
		color:'.$style_options['header']['link_color']['hover'].';
	}
	.header-middle a:active{
		color:'.$style_options['header']['link_color']['active'].';
	}
	.header-middle ::-webkit-input-placeholder {
	   color:'.$style_options['header']['input_color'].';
	}
	.header-middle :-moz-placeholder { /* Firefox 18- */
	  color:'.$style_options['header']['input_color'].';
	}
	.header-middle ::-moz-placeholder {  /* Firefox 19+ */
	   color:'.$style_options['header']['input_color'].';
	}
	.header-middle :-ms-input-placeholder {  
	   color:'.$style_options['header']['input_color'].';
	}
	.header-middle .search-area, .header-middle .header-cart-content .cart-style-1 .mini-cart-count, .header-services .icon-service{
		border-top:'.$style_options['header']['border']['border-top'].';
		border-bottom:'.$style_options['header']['border']['border-bottom'].';
		border-left:'.$style_options['header']['border']['border-left'].';
		border-right:'.$style_options['header']['border']['border-right'].';
		border-style:'.$style_options['header']['border']['border-style'].';
		border-color:'.$style_options['header']['border_color_rgb'].';
	}
	
	/*
	* topbar color scheme
	*/
	.header-topbar, .header-topbar .wcaccount-topbar .wcaccount-dropdown, .header-topbar .wpml-ls-statics-shortcode_actions .wpml-ls-sub-menu, .header-topbar .wcml-dropdown .wcml-cs-submenu, .header-topbar .demo-dropdown-sub-menu, .header-topbar .woocommerce-currency-switcher-form ul.dd-options, .header-topbar .dropdown-menu{
		color:'.$style_options['topbar']['text_color'].';
	}
	.header-topbar input[type="textbox"], .header-topbar input[type="email"], .header-topbar select, .header-topbar textarea{
		background-color:'.$style_options['topbar']['background'].';
		color:'.$style_options['topbar']['input_color'].';
	}
	.header-topbar a, .header-topbar .dropdown-menu > li > a{
		color:'.$style_options['topbar']['link_color']['regular'].';
	}
	.header-topbar a:hover{
		color:'.$style_options['topbar']['link_color']['hover'].';
	}
	.header-topbar a:active{
		color:'.$style_options['topbar']['link_color']['active'].';
	}
	.header-topbar ::-webkit-input-placeholder {
	   color:'.$style_options['topbar']['input_color'].';
	}
	.header-topbar :-moz-placeholder { /* Firefox 18- */
	  color:'.$style_options['topbar']['input_color'].';
	}
	.header-topbar ::-moz-placeholder {  /* Firefox 19+ */
	   color:'.$style_options['topbar']['input_color'].';
	}
	.header-topbar :-ms-input-placeholder {  
	   color:'.$style_options['topbar']['input_color'].';
	}
	.header-topbar input[type="textbox"], .header-topbar input[type="email"],
	.header-topbar select,
	.header-topbar textarea{
		border-top:'.$style_options['topbar']['border']['border-top'].';
		border-bottom:'.$style_options['topbar']['border']['border-bottom'].';
		border-left:'.$style_options['topbar']['border']['border-left'].';
		border-right:'.$style_options['topbar']['border']['border-right'].';
		border-style:'.$style_options['topbar']['border']['border-style'].';
		border-color:'.$style_options['topbar']['border_color_rgb'].';
	}
	.header-topbar,
	.wcaccount-topbar .wcaccount-dropdown > li {
		border-bottom:'.$style_options['topbar']['border']['border-bottom'].';
		border-style:'.$style_options['topbar']['border']['border-style'].';
		border-color:'.$style_options['topbar']['border_color_rgb'].';
	}
	
	/*
	* navigation bar color scheme
	*/
	.header-navigation{
		color:'.$style_options['navigation']['text_color'].';
	}
	.header-navigation .category-menu .category-menu-title h4,
	.header-navigation .mobile-main-navigation .toggle-menu h4,
	.header-navigation .category-menu .category-menu-title,
	.header-navigation .header-cart-content .heading-cart.cart-style-3 h6,
	.header-navigation .header-cart-content .heading-cart.cart-style-3 a{
		color:'.$style_options['navigation']['text_color'].';
	}
	.header-navigation .category-menu .category-menu-title,
	.header-navigation .search-area .input-search-btn .search-btn {
		background-color:'.$style_options['navigation']['secondary_color'].';
	}
	.header-navigation .search-area,
	.woocommerce .header-navigation .search-categories a.selectBox{
		background-color:'.$style_options['navigation']['input_background'].';
	}
	.header-navigation .search-area .search-field,
	.header-navigation .search-categories a.selectBox,
	.header-navigation .search-categories .categories-filter{
		color:'.$style_options['navigation']['input_color'].';
	}
	.header-navigation a{
		color:'.$style_options['navigation']['link_color']['regular'].';
	}
	.header-navigation a:hover,
	.header-navigation ul.main-navigation > li.menu-item-has-children:hover > a,
	.header-navigation ul.main-navigation li.current-menu-ancestor > a,
	.header-navigation ul.main-navigation li.current-page-ancestor > a,
	.header-navigation ul.main-navigation > li.current_page_item > a{
		color:'.$style_options['navigation']['link_color']['hover'].';
	}
	.header-navigation a:active{
		color:'.$style_options['navigation']['link_color']['active'].';
	}
	.header-navigation ::-webkit-input-placeholder {
	   color:'.$style_options['navigation']['input_color'].';
	}
	.header-navigation :-moz-placeholder { /* Firefox 18- */
	  color:'.$style_options['navigation']['input_color'].';
	}
	.header-navigation ::-moz-placeholder {  /* Firefox 19+ */
	   color:'.$style_options['navigation']['input_color'].';
	}
	.header-navigation :-ms-input-placeholder {  
	   color:'.$style_options['navigation']['input_color'].';
	}
	.header-navigation .search-area, 
	.header-navigation .header-cart-content .cart-style-1 .mini-cart-count{
		border-top:'.$style_options['navigation']['border']['border-top'].';
		border-bottom:'.$style_options['navigation']['border']['border-bottom'].';
		border-left:'.$style_options['navigation']['border']['border-left'].';
		border-right:'.$style_options['navigation']['border']['border-right'].';
		border-style:'.$style_options['navigation']['border']['border-style'].';
		border-color:'.$style_options['navigation']['border_color_rgb'].';
	}
	
	/*
	* sticky header, topbar and navigation color scheme
	*/
	.es-sticky .header-right > span > a,
	.es-sticky .header-cart.cart-style-2 > a,
	.es-sticky .header-cart-content .heading-cart.cart-style-3 h6,
	.es-sticky .header-cart-content .heading-cart.cart-style-3 a,
	.es-sticky .header-cart-content .heading-cart.cart-style-3 h6,
	.header-services .content-service h6, .search-toggle::before,
	.es-sticky .mobile-main-navigation .toggle-menu h4{
		color:'.$style_options['sticky_header']['text_color'].';
	}
	.es-sticky .navbar-toggle .icon-bar{
		background-color:'.$style_options['sticky_header']['text_color'].';
	}
	.es-sticky.search-area, .woocommerce .es-sticky .search-categories a.selectBox{
		background-color:'.$style_options['sticky_header']['input_background'].';
	}
	.es-sticky .search-area .search-field,
	.es-sticky .search-categories a.selectBox,
	.es-sticky .search-categories .categories-filter{
		color:'.$style_options['sticky_header']['input_color'].';
	}
	
	.es-sticky a{
		color:'.$style_options['sticky_header']['link_color']['regular'].';
	}
	.es-sticky a:hover,
	.header .es-sticky ul.main-navigation > li.menu-item-has-children:hover > a,
	.header .es-sticky ul.main-navigation li.current-menu-ancestor > a,
	.header .es-sticky ul.main-navigation li.current-page-ancestor > a,
	.header .es-sticky ul.main-navigation > li.current_page_item > a{
		color:'.$style_options['sticky_header']['link_color']['hover'].';
	}
	.es-sticky a:active{
		color:'.$style_options['sticky_header']['link_color']['active'].';
	}
	.es-sticky .search-area,
	.es-sticky .header-cart-content .cart-style-1 .mini-cart-count,
	.es-sticky .search-area, .es-sticky .header-services .icon-service,
	.es-sticky input[type="textbox"], .es-sticky input[type="email"],
	.es-sticky select, .es-sticky textarea{
		border-top:'.$style_options['sticky_header']['border']['border-top'].';
		border-bottom:'.$style_options['sticky_header']['border']['border-bottom'].';
		border-left:'.$style_options['sticky_header']['border']['border-left'].';
		border-right:'.$style_options['sticky_header']['border']['border-right'].';
		border-style:'.$style_options['sticky_header']['border']['border-style'].';
		border-color:'.$style_options['sticky_header']['border_color_rgb'].';
	}
	
	.header-topbar.es-sticky,
	.es-sticky .wcaccount-topbar .wcaccount-dropdown > li {
		border-bottom:'.$style_options['sticky_header']['border']['border-bottom'].';
		border-style:'.$style_options['sticky_header']['border']['border-style'].';
		border-color:'.$style_options['sticky_header']['border_color_rgb'].';
	}	
	
	/*
	* Menu color scheme
	*/
	ul.main-navigation .sub-menu,
	ul.main-navigation .emallshop-megamenu-wrapper,
	ul.main-navigation .toggle-submenu{
		background-color:'.$style_options['menu']['background'].';
		color:'.$style_options['menu']['text_color'].';
	}
	ul.main-navigation .sub-menu li a,
	ul.main-navigation .emallshop-megamenu-title a,
	.emallshop-megamenu-wrapper .emallshop-megamenu-submenu .widget li a{
		color:'.$style_options['menu']['link_color']['regular'].';
	}
	ul.main-navigation .sub-menu li a:hover,
	ul.main-navigation .emallshop-megamenu-title a:hover,
	.emallshop-megamenu-wrapper .emallshop-megamenu-submenu .widget li a:hover,
	ul.main-navigation ul.sub-menu li.menu-item-has-children:hover > a,
	ul.main-navigation ul.sub-menu li.current-page-ancestor > a,
	ul.main-navigation ul.sub-menu li.current-menu-ancestor > a,
	ul.main-navigation ul.sub-menu li.current-menu-item > a,
	ul.main-navigation ul.sub-menu li.current_page_item > a{
		color:'.$style_options['menu']['link_color']['hover'].';
	}
	ul.main-navigation .sub-menu li a:active,
	ul.main-navigation .emallshop-megamenu-title a:active,
	.emallshop-megamenu-wrapper .emallshop-megamenu-submenu .widget li a:active{
		color:'.$style_options['menu']['link_color']['active'].';
	}
	ul.main-navigation .sub-menu li,
	ul.main-navigation .widget_rss li,
	ul.main-navigation .widget ul.post-list-widget li{
		border-bottom:'.$style_options['menu']['border']['border-bottom'].';
		border-style:'.$style_options['menu']['border']['border-style'].';
		border-color:'.$style_options['menu']['border']['border-color'].';
	}
	
	/*
	* Page heading color scheme
	*/
	#header .page-heading{
		color:'.$style_options['page_heading']['text_color'].';
		padding: '.$style_options['page_heading']['padding']['padding-top'].' 0 '.$style_options['page_heading']['padding']['padding-bottom'].' 0;
	}
	.page-heading .page-header .page-title{
		color:'.$style_options['page_heading']['color'].';
	}
	#header .page-heading{
		border-bottom:'.$style_options['page_heading']['border']['border-bottom'].';
		border-style:'.$style_options['page_heading']['border']['border-style'].';
		border-color:'.$style_options['page_heading']['border_color_rgb'].';
	}
	.page-heading a{
		color:'.$style_options['page_heading']['link_color']['regular'].';
	}
	.page-heading a:hover{
		color:'.$style_options['page_heading']['link_color']['hover'].';
	}
	
	/*
	* footer color scheme
	*/
	.footer .footer-top, .footer .footer-middle{
		color:'.$style_options['footer']['text_color'].';
	}
	.footer .footer-middle{
		padding: '.$style_options['footer']['padding']['padding-top'].' 0 '.$style_options['footer']['padding']['padding-bottom'].' 0;
	}
	.footer .widget-title{
		color:'.$style_options['footer']['heading_color'].';
	}
	.footer input[type="textbox"], .footer input[type="email"], .footer select, .footer textarea{
		background-color:'.$style_options['footer']['input_background'].';
		color:'.$style_options['footer']['input_color'].';
	}
	.footer .footer-top a, .footer .footer-middle  a{
		color:'.$style_options['footer']['link_color']['regular'].';
	}
	.footer .footer-top a:hover, .footer .footer-middle a:hover{
		color:'.$style_options['footer']['link_color']['hover'].';
	}
	.footer .footer-top a:active, .footer .footer-middle a:active{
		color:'.$style_options['footer']['link_color']['active'].';
	}
	.footer ::-webkit-input-placeholder {
	   color:'.$style_options['footer']['input_color'].';
	}
	.footer :-moz-placeholder { /* Firefox 18- */
	  color:'.$style_options['footer']['input_color'].';
	}
	.footer ::-moz-placeholder {  /* Firefox 19+ */
	   color:'.$style_options['footer']['input_color'].';
	}
	.footer :-ms-input-placeholder {  
	   color:'.$style_options['footer']['input_color'].';
	}
	.footer input[type="textbox"],
	.footer input[type="email"],
	.footer select,
	.footer textarea{
		border-top:'.$style_options['footer']['border']['border-top'].';
		border-bottom:'.$style_options['footer']['border']['border-bottom'].';
		border-left:'.$style_options['footer']['border']['border-left'].';
		border-right:'.$style_options['footer']['border']['border-right'].';
		border-style:'.$style_options['footer']['border']['border-style'].';
		border-color:'.$style_options['footer']['border_color_rgb'].';
	}
	.footer .footer-top,
	.footer .footer-middle{
		border-bottom:'.$style_options['footer']['border']['border-bottom'].';
		border-style:'.$style_options['footer']['border']['border-style'].';
		border-color:'.$style_options['footer']['border_color_rgb'].';
	}
	
	/*
	* Copyright color scheme
	*/
	.footer-copyright{
		color:'.$style_options['copyright']['text_color'].';
		padding: '.$style_options['copyright']['padding']['padding-top'].' 0 '.$style_options['copyright']['padding']['padding-bottom'].' 0;
	}
	.footer-copyright a{
		color:'.$style_options['copyright']['link_color']['regular'].';
	}
	.footer-copyright a:hover{
		color:'.$style_options['copyright']['link_color']['hover'].';
	}
	.footer-copyright a:active{
		color:'.$style_options['copyright']['link_color']['active'].';
	}
	.footer-copyright{
		border-bottom:'.$style_options['copyright']['border']['border-bottom'].';
		border-style:'.$style_options['copyright']['border']['border-style'].';
		border-color:'.$style_options['copyright']['border_color_rgb'].';
	}
	
	/*
	* WooCommerce
	*/
	.product .product-highlight .out-of-stock span{
		background-color:'.$style_options['label_color']['outofstock'].';
	}
	.product .product-highlight .onsale span{
		background-color:'.$style_options['label_color']['sale'].';
	}
	.product .product-highlight .new span{
		background-color:'.$style_options['label_color']['new'].';
	}
	.product .product-highlight .featured span{
		background-color:'.$style_options['label_color']['featured'].';
	}		
	.freeshipping-bar {
		background-color:'.$style_options['free_shipping']['background'].';
	}
	.freeshipping-bar .progress-bar {
		background-color:'.$style_options['free_shipping']['color'].';
	}
	
	.yit-wcan-container .yith-wcan-loading {
		background: url('.$loading_image.') no-repeat center;
	}
	
	/*
	* Newsletter Color
	*/
	.newsletter-content.modal-content{
		color:'.$style_options['newsletter']['color'].';
	}
	.newsletter-content .close, 
	.newsletter-content .newsletter-text > h1{
		color:'.$style_options['newsletter']['color'].';
	}
	.newsletter-content .mc4wp-form-fields input[type="submit"]{
		background-color:'.$style_options['newsletter']['button_color'].';
	}';
	
	if( is_rtl() ){
		$theme_css.='
		.header-middle .search-control-group .search-categories {
			border-right:'.$style_options['header']['border']['border-right'].';
			border-style:'.$style_options['header']['border']['border-style'].';
			border-color:'.$style_options['header']['border_color_rgb'].';
		}
		.topbar-right > div:last-child,
		.topbar-right > span:last-child,
		.topbar-right .nav li:last-child{
			border-left:'.$style_options['topbar']['border']['border-left'].';
			border-style:'.$style_options['topbar']['border']['border-style'].';
			border-color:'.$style_options['topbar']['border_color_rgb'].';
		}
		.topbar-right > span,
		.topbar-right > div,
		.topbar-right .nav li{
			border-right:'.$style_options['topbar']['border']['border-right'].';
			border-style:'.$style_options['topbar']['border']['border-style'].';
			border-color:'.$style_options['topbar']['border_color_rgb'].';
		}
		.header-navigation ul.emallshop-horizontal-menu.main-navigation > li, 
		.header-navigation .search-control-group .search-categories{
			border-right:'.$style_options['navigation']['border']['border-right'].';
			border-style:'.$style_options['navigation']['border']['border-style'].';
			border-color:'.$style_options['navigation']['border_color_rgb'].';
		}
		.header-navigation ul.emallshop-horizontal-menu.main-navigation > li:last-child{
			border-left:'.$style_options['navigation']['border']['border-left'].';
			border-style:'.$style_options['navigation']['border']['border-style'].';
			border-color:'.$style_options['navigation']['border_color_rgb'].';
		}
		.es-sticky ul.emallshop-horizontal-menu.main-navigation > li,
		.es-sticky .search-control-group .search-categories,
		.es-sticky .topbar-right > span,
		.es-sticky .topbar-right > div,
		.es-sticky .topbar-right .nav li{
			border-right:'.$style_options['sticky_header']['border']['border-right'].';
			border-style:'.$style_options['sticky_header']['border']['border-style'].';
			border-color:'.$style_options['sticky_header']['border_color_rgb'].';
		}
		.es-sticky ul.emallshop-horizontal-menu.main-navigation > li:last-child,
		.es-sticky .topbar-right > span:last-child,
		.es-sticky .topbar-right > div:last-child,
		.es-sticky .topbar-right .nav li:last-child{
			border-left:'.$style_options['sticky_header']['border']['border-left'].';
			border-style:'.$style_options['sticky_header']['border']['border-style'].';
			border-color:'.$style_options['sticky_header']['border_color_rgb'].';
		}
		ul.main-navigation .emallshop-megamenu-wrapper .emallshop-megamenu > li{
			border-left:'.$style_options['menu']['border']['border-left'].';
			border-style:'.$style_options['menu']['border']['border-style'].';
			border-color:'.$style_options['menu']['border']['border-color'].';
		}
		.footer .popular-categories .categories-list li{
			border-left:'.$style_options['footer']['border']['border-left'].';
			border-style:'.$style_options['footer']['border']['border-style'].';
			border-color:'.$style_options['footer']['border_color_rgb'].';
		}';
	}else{
		$theme_css.='
		.header-middle .search-control-group .search-categories {
			border-left:'.$style_options['header']['border']['border-left'].';
			border-style:'.$style_options['header']['border']['border-style'].';
			border-color:'.$style_options['header']['border_color_rgb'].';
		}	
		.topbar-right > div:last-child,
		.topbar-right > span:last-child,
		.topbar-right .nav li:last-child{
			border-right:'.$style_options['topbar']['border']['border-right'].';
			border-style:'.$style_options['topbar']['border']['border-style'].';
			border-color:'.$style_options['topbar']['border_color_rgb'].';
		}
		.topbar-right > span,
		.topbar-right > div,
		.topbar-right .nav li{
			border-left:'.$style_options['topbar']['border']['border-left'].';
			border-style:'.$style_options['topbar']['border']['border-style'].';
			border-color:'.$style_options['topbar']['border_color_rgb'].';
		}
		.header-navigation ul.emallshop-horizontal-menu.main-navigation > li, 
		.header-navigation .search-control-group .search-categories{
			border-left:'.$style_options['navigation']['border']['border-left'].';
			border-style:'.$style_options['navigation']['border']['border-style'].';
			border-color:'.$style_options['navigation']['border_color_rgb'].';
		}
		.header-navigation ul.emallshop-horizontal-menu.main-navigation > li:last-child{
			border-right:'.$style_options['navigation']['border']['border-right'].';
			border-style:'.$style_options['navigation']['border']['border-style'].';
			border-color:'.$style_options['navigation']['border_color_rgb'].';
		}
		.es-sticky ul.emallshop-horizontal-menu.main-navigation > li,
		.es-sticky .search-control-group .search-categories,
		.es-sticky .topbar-right > span,
		.es-sticky .topbar-right > div,
		.es-sticky .topbar-right .nav li{
			border-left:'.$style_options['sticky_header']['border']['border-left'].';
			border-style:'.$style_options['sticky_header']['border']['border-style'].';
			border-color:'.$style_options['sticky_header']['border_color_rgb'].';
		}
		.es-sticky ul.emallshop-horizontal-menu.main-navigation > li:last-child,
		.es-sticky .topbar-right > span:last-child,
		.es-sticky .topbar-right > div:last-child,
		.es-sticky .topbar-right .nav li:last-child{
			border-right:'.$style_options['sticky_header']['border']['border-right'].';
			border-style:'.$style_options['sticky_header']['border']['border-style'].';
			border-color:'.$style_options['sticky_header']['border_color_rgb'].';
		}
		ul.main-navigation .emallshop-megamenu-wrapper .emallshop-megamenu > li{
			border-right:'.$style_options['menu']['border']['border-right'].';
			border-style:'.$style_options['menu']['border']['border-style'].';
			border-color:'.$style_options['menu']['border']['border-color'].';
		}
		.footer .popular-categories .categories-list li{
			border-right:'.$style_options['footer']['border']['border-right'].';
			border-style:'.$style_options['footer']['border']['border-style'].';
			border-color:'.$style_options['footer']['border_color_rgb'].';
		}';
	}
	
	// Remove prices everywhere
	if( emallshop_get_option( 'login-to-see-price', 0 ) && !is_user_logged_in() ){
		$theme_css.='
		.woocommerce span.amount, .woocommerce span.product-price, .woocommerce div.product p.price{
			display:none !important;
		}';
	}// End remove prices everywhere
	
	// Banner/ Image Hover Effect.
	if( emallshop_get_option( 'banner-hover-effect', 1 ) ){
		$theme_css.='
		.wpb_wrapper .vc_single_image-wrapper{
		  background-color: #fff;
		  overflow: hidden;
		  position: relative;
		}
		.wpb_wrapper .vc_single_image-wrapper:hover{
			background-color:#000;
		}
		.wpb_wrapper .vc_single_image-wrapper:before, .wpb_wrapper .vc_single_image-wrapper:after{
		  bottom: 10px;
		  content: "";
		  left: 10px;
		  opacity: 0;
		  position: absolute;
		  right: 10px;
		  top: 10px;
		  -webkit-transition: opacity 0.35s ease 0s, transform 0.35s ease 0s;
		  -o-transition: opacity 0.35s ease 0s, transform 0.35s ease 0s;
		  transition: opacity 0.35s ease 0s, transform 0.35s ease 0s;
		  z-index: 1;
		}
		.wpb_wrapper .vc_single_image-wrapper:before {
		  border-bottom: 1px solid #ffffff;
		  border-top: 1px solid #ffffff;
		  -webkit-transform: scale(0, 1);
		  -ms-transform: scale(0, 1);
		  -o-transform: scale(0, 1);
		  transform: scale(0, 1);
		}
		.wpb_wrapper .vc_single_image-wrapper:after {
		  border-left: 1px solid #ffffff;
		  border-right: 1px solid #ffffff;
		  -webkit-transform: scale(1, 0);
		  -ms-transform: scale(1, 0);
		  -o-transform: scale(1, 0);
		  transform: scale(1, 0);
		}
		.wpb_wrapper .vc_single_image-wrapper img {
		  opacity: 1;
		  filter: alpha(opacity=100);
		  -webkit-transition: opacity 0.55s ease 0s;
		  -o-transition: opacity 0.35s ease 0s;
		  transition: opacity 0.35s ease 0s;
		  width: 100%;
		}
		.wpb_wrapper .vc_single_image-wrapper:hover:before, .wpb_wrapper .vc_single_image-wrapper:hover:after {
		  opacity: 1;
		  filter: alpha(opacity=100);
		  -webkit-transform: scale(1);
		  -ms-transform: scale(1);
		  -o-transform: scale(1);
		  transform: scale(1);
		}
		.wpb_wrapper .vc_single_image-wrapper:hover img {
		  opacity: 0.7;
		  filter: alpha(opacity=70);
		}';
	}// Banner/Image Hover Effect.
	
	/*Custom Options*/ 
	if( emallshop_get_option( 'mobile-products-per-row', 2 ) == 1 ){
		$theme_css.='
		@media only screen and (max-width : 480px) {
			.product-items .products.grid-view li.type-product, .product-items .products.no-owl li.type-product{
				width:100%;
			}
		}';
	}
	
	$theme_css .= emallshop_vc_fullrow_css();
	
	$theme_css = apply_filters( 'emallshop_custom_css', $theme_css );
	return $theme_css;
}
endif; // emallshop_theme_style 
 
function emallshop_vc_fullrow_css(){
	if ( !defined( 'WPB_VC_VERSION' ) ) { return; }		
	
	$container_width	= ( "pre-defined" == emallshop_get_option( 'use-predefined-page-width', 'pre-defined' ) ) ? emallshop_get_option( 'predefined-page-width', '1170' ) : emallshop_get_option( 'custom-page-width', '1170' );
	
	ob_start();	?>
	[data-vc-full-width] {
		width: 100vw;
		left: -2.5vw; 
	}
	<?php if ( $container_width ){ ?>
	
		/* Site container width */		
		@media (min-width: <?php echo esc_attr( $container_width + 70 ); ?>px) {
			
			[data-vc-full-width] {
				<?php if ( is_rtl() ): ?>
					left: calc((100vw - <?php echo esc_attr( $container_width ); ?>px) / 2);
				<?php else: ?>
					left: calc((-100vw - -<?php echo esc_attr( $container_width ); ?>px) / 2);
				<?php endif; ?>
			}
			
			[data-vc-full-width]:not([data-vc-stretch-content]) {
				padding-left: calc((100vw - <?php echo esc_attr( $container_width ); ?>px) / 2);
				padding-right: calc((100vw - <?php echo esc_attr( $container_width ); ?>px) / 2);
			}
		}
	<?php } ?>
	<?php			
	$style =  ob_get_clean();
	return $style;
}

function emallshop_custom_javascript() {
	
	ob_start();	
	
	$product_image_hover_style=  emallshop_get_option('product-image-hover-style','product-image-style2');	
	if($product_image_hover_style=="product-image-style3" || $product_image_hover_style=="product-image-style4"):
		$navigation = ($product_image_hover_style == 'product-image-style3') ? true : false;
		$pagination = ($product_image_hover_style == 'product-image-style4') ? true : false;?>		
		
		<script type="text/javascript">			
			jQuery(document).ready(function($) {
				productGalleryCarousel();
				
				function productGalleryCarousel(){
					$('.product-items li.product').each(function(){
						var productGalleryCarousel = $(this).find('.product_image_gallery');
						var interval;
						productGalleryCarousel.owlCarousel({
							items:				1,
							loop:				true,
							autoplayTimeout:	1500,
							rtl:				<?php echo is_rtl() ? "true" : "false" ; ?>,
							smartSpeed:			450,
							mouseDrag:			false,
							touchDrag:			false,
							//nav:				true,
							navText: 			['',''],
							dots: 				<?php echo esc_html( $pagination ) ? "true" : "false" ; ?>,	
						});
						
						$(this).hover(function(){
							productGalleryCarousel.owlCarousel('invalidate', 'all').owlCarousel('refresh');
						});
						
						function stopOwlPropagation(element) {
							jQuery(element).on('to.owl.carousel', function(e) { e.stopPropagation(); });
							jQuery(element).on('next.owl.carousel', function(e) { e.stopPropagation(); });
							jQuery(element).on('prev.owl.carousel', function(e) { e.stopPropagation(); });
							jQuery(element).on('destroy.owl.carousel', function(e) { e.stopPropagation(); });
						}
						stopOwlPropagation('.owl-carousel');

						<?php if($pagination==1):?>
							$(this).hover(
								function(){
									interval = setInterval(function() {
										productGalleryCarousel.trigger('next.owl.carousel');
									}, 1500);								
								},
								function(){
									clearInterval(interval);
								}
							);
						<?php endif;?>
						
						<?php if($navigation==1):?>						
							var nextOwl = $(this).find('.product-slider-controls .owl-next');
							var prevOwl = $(this).find('.product-slider-controls .owl-prev');
							
							prevOwl.click(function(){
								productGalleryCarousel.trigger('prev.owl.carousel');
							});								
							nextOwl.click(function(){
								productGalleryCarousel.trigger('next.owl.carousel');
							});								
						<?php endif;?>
					});	
				}				
			});
		</script>
	<?php endif;
	
	$output= ob_get_clean();
	
	$output.="<script>";
	
	$output.=trim( emallshop_get_option('custom_js', '') );
	
	$output.="</script>";
	
	echo apply_filters( 'emallshop_custom_js', $output ); // WPCS: XSS OK.
}
add_action('wp_footer','emallshop_custom_javascript');
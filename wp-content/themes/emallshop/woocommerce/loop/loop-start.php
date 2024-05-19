<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $emallshop_owlparam;
		
$product_style= emallshop_get_option('product-layout-style','product-style1') ;
$product_image_hover_style= emallshop_get_option('product-image-hover-style','product-image-style2');
$navigation = ($product_image_hover_style == 'product-image-style3') ? 1 : 0;
$pagination = ($product_image_hover_style == 'product-image-style4') ? 1 : 0;

$default_view="";
if(emallshop_get_option('product-default-view-style','grid')=="grid"){
	$default_view="grid grid-view";
}elseif(emallshop_get_option('product-default-view-style','grid')=="expand-grid"){
	$default_view="grid-expand grid-view";
}elseif(emallshop_get_option('product-default-view-style','grid')=="list"){
	$default_view="list list-view";
}elseif(emallshop_get_option('product-default-view-style','grid')=="thin-list"){
	$default_view="list-thin list-view";
}

$classes=$product_style;
if((is_woocommerce() && !is_product()) || ( is_dokan_activated() && dokan_is_store_page())): 
	$classes.=' is_shop '.$default_view;
endif;

if(is_cart() || is_product()){
	$id = uniqid();
	$emallshop_owlparam['productsCarousel']['section-'.$id] = array(
		'autoplay'     => (emallshop_get_option('related-upsell-auto-play', 1)) ? 'true' : 'false',
		'loop'         => (  emallshop_get_option('related-upsell-loop', 1)) ? 'true' : 'false',
		'navigation'   => ( emallshop_get_option('related-upsell-navigation', 1)) ? 'true' : 'false',
		'dots'         => ( emallshop_get_option('related-upsell-product-dots', 0)) ? 'true' : 'false',
		'rp_desktop'   => emallshop_get_option('related-upsell-products-per-row', '4') ,
		'rp_small_desktop' => 3,
		'rp_tablet'    => 2,
		'rp_mobile'    => 2,
		'rp_small_mobile' => 1,
	);
}?>

<div class="product-items">		
	<?php if(is_cart() || is_product()){?>
		<div id="section-<?php echo esc_attr($id);?>">
			<ul class="products product-carousel owl-carousel <?php echo esc_attr($classes);?>">
	<?php }else{?>			
		<ul class="products <?php echo esc_attr($classes);?>" data-navigation="<?php echo esc_attr($navigation);?>" data-pagination="<?php echo esc_attr($pagination);?>">
	<?php }

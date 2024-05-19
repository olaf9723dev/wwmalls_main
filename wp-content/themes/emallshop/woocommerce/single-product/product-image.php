<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 */

defined( 'ABSPATH' ) || exit;

// Note: 'wc_get_gallery_image_html' was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

$page_layout = get_post_meta ( $product->get_id(), '_emallshop_product_layout', true ); //EmallShop 2.0
if( isset( $page_layout ) && $page_layout !='' ){
	$page_layout = $page_layout;
}else{
	$page_layout = emallshop_get_option( 'single-product-page-layout', 'none-left' );
}

if( is_rtl() ){
	$slider_attr = 'data-slick=\'{"slidesToShow": 1, "slidesToScroll": 1, "asNavFor": ".product-thumbnails", "fade":true, "rtl":true}\'';
}else{
	$slider_attr = 'data-slick=\'{"slidesToShow": 1, "slidesToScroll": 1, "asNavFor": ".product-thumbnails", "fade":true}\'';
}

$post_thumbnail_id = $product->get_image_id();
$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
	'woocommerce-product-gallery',
	'woocommerce-product-gallery--' . ( $product->get_image_id() ? 'with-images' : 'without-images' ),
	'images',
	$page_layout, //Emallshop
) );

?>
<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>">
	<div class="woocommerce-product-gallery__wrapper">
		<div id="product-image" class="emallshop-slick-carousel" <?php echo wp_kses_post( $slider_attr ); ?>>
			<?php			
			if ( $product->get_image_id() ) {
				$html  = wc_get_gallery_image_html( $post_thumbnail_id, true );
			} else {
				$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
				$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'emallshop' ) );
				$html .= '</div>';
			}

			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id ); 
			// phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
			
			$attachment_ids = $product->get_gallery_image_ids(); // Start EmallShop 2.0

			if ( $attachment_ids && has_post_thumbnail( $product->get_id() ) ) {
				foreach ( $attachment_ids as $attachment_id ) {
					echo wc_get_gallery_image_html( $attachment_id, true  );
				}
			} //End EmallShop ?>			
		</div>
		<?php do_action( 'woocommerce_product_thumbnails' ); ?>
	</div>
	<div class="pl-loading"></div>
</div>
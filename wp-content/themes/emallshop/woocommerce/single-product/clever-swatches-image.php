<?php
/**
 * Use for display image swatch.
 *
 * @version  2.0.0
 * @package  clever-swatches/templates
 * @category Templates
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

global $post, $product;

$general_settings = get_option('zoo-cw-settings', true);
$is_gallery_enabled = isset($general_settings['product_gallery']) ? intval($general_settings['product_gallery']) : 1;

if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
} else $product_id = get_the_ID();

if(isset($_GET['cart_item_key'])){
    $cart_item_key=$_GET['cart_item_key'];
    $cart_session=WC()->session->get('cart');
    if(isset($cart_session[$cart_item_key]['variation_id'])){
        $variation_id=$cart_session[$cart_item_key]['variation_id'];
    }
}

if (isset ($variation_id)) {
    if ($is_gallery_enabled) {
        $gallery_images_id = get_post_meta($variation_id, 'zoo-cw-variation-gallery', true);
        $attachment_ids = array_filter(explode(',', $gallery_images_id));
    } else {
        $attachment_ids = [];
    }
} else {
	global $post, $product;
	
	$default_active     = [];
    $variation_id       = 0;
	
    $default_attributes = $product->get_default_attributes();
	$attributes=$product->get_attributes();
	foreach ($attributes as $attribute){
        $attribute_name=$attribute['name'];
        if(isset($_REQUEST['attribute_' . sanitize_title($attribute_name)])){
            $default_active['attribute_' . sanitize_title($attribute_name)]=wc_clean(stripslashes(urldecode($_REQUEST['attribute_' . sanitize_title($attribute_name)])));
        }else{
            break;
        }
    }
	
	if($is_gallery_enabled) {
        if(count($default_active)){
            $data_store = WC_Data_Store::load('product');
            $variation_id = $data_store->find_matching_product_variation($product, $default_active);
        }
        elseif (count($default_attributes)) {
            foreach ($default_attributes as $key => $value) {
                $default_active['attribute_' . $key] = $value;
            }
            $data_store = WC_Data_Store::load('product');
            $variation_id = $data_store->find_matching_product_variation($product, $default_active);
        }
    }
    if ( $variation_id == 0 ) {
        $attachment_ids = $product->get_gallery_image_ids();
        $variation_id   = $product_id;
    } else {
        $gallery_images_id = get_post_meta( $variation_id, 'zoo-cw-variation-gallery', true );
        $attachment_ids    = array_filter( explode( ',', $gallery_images_id ) );
    }
}

$product_swatch_data_array = get_post_meta( $product_id, 'zoo_cw_product_swatch_data', true );
if ($product_swatch_data_array == '') {
    $is_gallery_enabled=0;
}

if ( $product->is_type('variable') && $is_gallery_enabled != 0 ):
	
	if ( empty( $attachment_ids ) ) { //EmallShop 2.0.3
		$attachment_ids = $product->get_gallery_image_ids();
	}
	if( ! has_post_thumbnail( $variation_id ) ) { //EmallShop 2.3.0
		$variation_id   = $product_id;
	}
	
	$page_layout = get_post_meta ( $product_id, '_emallshop_product_layout', true );
    if( isset($page_layout) && $page_layout != ''){
        $page_layout = $page_layout;
    }else{
        $page_layout= emallshop_get_option('single-product-page-layout','none-left');
	}
	
	if( is_rtl() )
		$slider_attr = 'data-slick=\'{"slidesToShow": 1, "slidesToScroll": 1, "asNavFor": ".product-thumbnails", "fade":true, "rtl":true}\'';
	else
		$slider_attr = 'data-slick=\'{"slidesToShow": 1, "slidesToScroll": 1, "asNavFor": ".product-thumbnails", "fade":true}\'';	
	//End Emallshop
	
    $post_thumbnail_id = get_post_thumbnail_id($variation_id);
    $wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
        'woocommerce-product-gallery',
        'woocommerce-product-gallery--' . ( has_post_thumbnail($variation_id) ? 'with-images' : 'without-images' ),
        'images',
		$page_layout, //Emallshop
    ) );
	
    ?>
	
    <div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>">
		<figure class="woocommerce-product-gallery__wrapper">
			<div id="product-image" class="emallshop-slick-carousel" <?php echo wp_kses_post( $slider_attr ); ?>>
				<?php
				$thumbnail_html='';
				if ( $product->get_image_id() ) {
					
					$html  = wc_get_gallery_image_html( $post_thumbnail_id, true );
					
					$thumbnail       = wp_get_attachment_image_src( $post_thumbnail_id, 'shop_thumbnail' );
					$attributes      = array(
						'title'                   => _wp_specialchars( get_post_field( 'post_title', $post_thumbnail_id ), ENT_QUOTES, 'UTF-8', true ),
						'data-caption'            => _wp_specialchars( get_post_field( 'post_excerpt', $post_thumbnail_id ), ENT_QUOTES, 'UTF-8', true ),
						'data-src'                => esc_url( $thumbnail[0] ),
						'data-large_image'        => esc_url( $thumbnail[0] ),
						'data-large_image_width'  => esc_attr( $thumbnail[1] ),
						'data-large_image_height' => esc_attr( $thumbnail[2] ),
					);
					$thumbnail_html .= '<div data-thumb="' . esc_url( $thumbnail[0] ) . '">';
					$thumbnail_html .= wp_get_attachment_image( $post_thumbnail_id, 'shop_thumbnail', false, $attributes );
					$thumbnail_html .= '</div>';
				} else {
					$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
					$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'emallshop' ) );
					$html .= '</div>';
				}
				if ( $attachment_ids && has_post_thumbnail($variation_id) ) {
					foreach ( $attachment_ids as $attachment_id ) {
						
						$html  .= wc_get_gallery_image_html( $attachment_id );
						
						$thumbnail       = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );
						$attributes      = array(
							'title'                   => _wp_specialchars( get_post_field( 'post_title', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
							'data-caption'            => _wp_specialchars( get_post_field( 'post_excerpt', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
							'data-src'                => esc_url( $thumbnail[0] ),
							'data-large_image'        => esc_url( $thumbnail[0] ),
							'data-large_image_width'  => esc_attr( $thumbnail[1] ),
							'data-large_image_height' => esc_attr( $thumbnail[2] ),
						);
						$thumbnail_html .= '<div data-thumb="' . esc_url( $thumbnail[0] ) . '">';
						$thumbnail_html .= wp_get_attachment_image( $attachment_id, 'shop_thumbnail', false, $attributes );
						$thumbnail_html .= '</div>';
								}
				}
				echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id ); 
				// phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
				//End EmallShop ?>			
			</div>
			
			<?php //Thumbnails
			if ( $attachment_ids ) {?>
				<div class="product-thumbnails emallshop-slick-carousel" data-slick='{"slidesToShow": <?php echo ( esc_attr( $page_layout ) == 'none-left' || $page_layout == 'none-right') ? "5": "4"; ?>,"slidesToScroll": 1,"asNavFor": "#product-image","arrows": true, "focusOnSelect": true, <?php if ( $page_layout == 'none-left' || $page_layout == 'none-right' ) echo '"vertical": true,'; ?> <?php if ( ( $page_layout != 'none-left' && $page_layout != 'none-right' ) && is_rtl()) echo '"rtl": true,'; ?> "responsive":[{"breakpoint": 639,"settings":{"slidesToShow": 4, "vertical":false <?php if ( is_rtl()) echo ',"rtl": true'; ?>}}]}'>
					<?php echo wp_kses_post( $thumbnail_html );?>
				</div>
			<?php }?>
        </figure>
		<div class="pl-loading"></div>
    </div>
    <?php
else:
    wc_get_template_part('single-product/product', 'image');
endif;
?>
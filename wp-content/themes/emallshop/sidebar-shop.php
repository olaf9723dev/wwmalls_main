<?php
/**
 * The sidebar containing the shop widget area
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */
$is_sidebar=0;

if(is_single()):		
	$page_layout = get_post_meta ( get_the_ID(), '_emallshop_product_layout', true );
	if(isset($page_layout) && $page_layout!="")
		$sidebar_position = emallshop_woo_page_sidebar_position($page_layout);
	else
		$sidebar_position = emallshop_woo_page_sidebar_position(emallshop_get_option('single-product-page-layout','none-left'));
else:
	$sidebar_position = emallshop_woo_page_sidebar_position(emallshop_get_option('shop-page-layout','left'));
endif;

if(is_single()):
	$widgetId = get_post_meta ( get_the_ID(), '_emallshop_product_sidebar_widget', true );
	if(isset($widgetId) && $widgetId!="")
		$shop_page_sidebar=$widgetId;
	else
		$shop_page_sidebar=emallshop_get_option('single-product-page-sidebar-widget', 'single-product');
elseif(!is_single()):
	$shop_page_sidebar=emallshop_get_option('shop-page-sidebar-widget', 'shop-page');
else:
	$shop_page_sidebar="woocommerce-widget";
endif;

if($sidebar_position !=''):?>
	<div id="sidebar" class="sidebar <?php echo esc_attr($sidebar_position);?>">
		<div id="secondary" class="secondary">

			<?php if ( is_active_sidebar( $shop_page_sidebar ) ) : ?>
				<div id="widget-area" class="widget-area" role="complementary">
					<?php dynamic_sidebar( $shop_page_sidebar ); ?>
				</div><!-- .widget-area -->
			<?php else: 
					esc_html_e('Add widget here','emallshop');
				endif; 
			?>

		</div><!-- .secondary -->
	</div>
<?php endif;?>

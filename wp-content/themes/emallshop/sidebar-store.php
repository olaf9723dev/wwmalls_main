<?php
/**
 * The sidebar containing the shop widget area
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */
$is_sidebar=0;

$is_sidebar			= ( emallshop_get_option( 'dokan-store-page-layout','left' ) == "full-layout" ) ? 0 : 1;	
$sidebar_position	= emallshop_woo_page_sidebar_position( emallshop_get_option( 'dokan-store-page-layout', 'left' ) );
$dokan_page_sidebar	= emallshop_get_option('dokan-store-page-sidebar-widget', 'dokan-widget-area');

if($is_sidebar):?>
<div id="sidebar" class="sidebar col-xs-12 col-sm-4 col-md-3 <?php echo esc_attr($sidebar_position);?>">
	<div id="secondary" class="secondary">

		<?php if ( is_active_sidebar( $dokan_page_sidebar ) ) : ?>
			<div id="widget-area" class="widget-area" role="complementary">
				<?php dynamic_sidebar( $dokan_page_sidebar ); ?>
			</div><!-- .widget-area -->
		<?php else: 
				esc_html_e('Add widget here','emallshop');
			endif; 
		?>

	</div><!-- .secondary -->
</div>
<?php endif;?>

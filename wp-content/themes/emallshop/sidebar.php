<?php
/**
 * The sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */

$sidebar_position	=( isset( $GLOBALS['sidebar_position'] ) ) ? $GLOBALS['sidebar_position'] : 'right';
$sidebar_widget		=( isset( $GLOBALS['sidebar_widget'] ) ) ? $GLOBALS['sidebar_widget'] : 'sidebar-1';

if ( isset($sidebar_position)):
	if($sidebar_position =='none' || (function_exists('is_cart') && is_cart()) || (function_exists('is_checkout') && is_checkout()) || (function_exists('is_account_page') && is_account_page()) || (function_exists('is_order_tracking') && is_order_tracking()) || (is_dokan_activated() && is_page( 'dashboard' )) || (is_dokan_activated() && is_page( 'store-listing' )) || (is_WC_Marketplace_activated() && is_vendor_page()) ) :
		return;
	else:
		if ( isset($sidebar_position) ) :
			
			//Set sidebar position
			if(isset($sidebar_position)):
				$sidebar_position=$sidebar_position;
			else:
				$sidebar_position='right';
			endif;	
			
			//set sidebar widget area
			if( isset($sidebar_widget) ):
				$sidebar_area=$sidebar_widget;
			else:
				$sidebar_area='sidebar-1';
			endif;
			
			if($sidebar_position=='left'):
				if(is_rtl()): 
					$s_position='col-md-push-9';
				else: 
					$s_position='col-md-pull-9';
				endif;
			else:
				$s_position='';
			endif;?>	
			
			<div id="sidebar" class="sidebar col-xs-12 col-sm-4 col-md-3 <?php echo esc_attr($s_position);?>">
				<div id="secondary" class="secondary">

					<?php if ( is_active_sidebar( $sidebar_area ) ) : ?>
						<div id="widget-area" class="widget-area" role="complementary">
							<?php dynamic_sidebar( $sidebar_area ); ?>
						</div><!-- .widget-area -->
					<?php else: 
						esc_html_e('Add widget here','emallshop');
					endif; ?>

				</div><!-- .secondary -->
			</div><?php 
		endif;
	endif;
else:
	$sidebar_position='right';
	$sidebar_area='sidebar-1';?>	
	
	<div id="sidebar" class="sidebar col-xs-12 col-sm-4 col-md-3">
		<div id="secondary" class="secondary">

			<?php if ( is_active_sidebar( $sidebar_area ) ) : ?>
				<div id="widget-area" class="widget-area" role="complementary">
					<?php dynamic_sidebar( $sidebar_area ); ?>
				</div><!-- .widget-area -->
			<?php else: 
					esc_html_e('Add widget here','emallshop');
				endif; 
			?>

		</div><!-- .secondary -->
	</div><?php 
endif;	
 
?>
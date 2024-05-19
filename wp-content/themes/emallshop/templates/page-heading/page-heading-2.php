<?php if(is_woocommerce_activated() && is_woocommerce()){
	$post_ID= get_option( 'woocommerce_shop_page_id' ); 
}else{
	$post_ID=get_the_ID();
}
$show_title = emallshop_getShowTitle( get_post_meta ( $post_ID, '_emallshop_show_title', true ) ); 
$show_breadsrumb = emallshop_getShowBreadsrumb( get_post_meta ( $post_ID, '_emallshop_show_breadsrumb', true ) );

if( emallshop_get_option('show-page-breadcrumb', 1)  || emallshop_get_option('show-page-title',1) ):
	if( $show_breadsrumb == 'yes' || $show_title == 'yes' ):?>
		<div class="page-heading page-heading-2">
			<div class="container">
				<div class="row">
					<div class="col-xs-12 ">
						<?php if( emallshop_get_option('show-page-title',1) && emallshop_get_option('show-title-breadcrumb-content','in-page-heading')=="in-page-heading" && !is_single()):
							if( $show_title == 'yes' ):?>	
								<div class="page-header">
								<?php if(is_woocommerce_activated() && is_woocommerce()){?>
									<h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
								<?php }elseif(is_home()){ ?>
									<h1 class="page-title"> <?php echo esc_html('Blog','emallshop')?> </h1>
								<?php } elseif(is_archive()){
									the_archive_title( '<h1 class="page-title">', '</h1>' );
								}elseif( is_dokan_activated() && dokan_is_store_page() ){?>
									<h1 class="page-title"><?php emallshop_dokan_user_name(); ?></h1>
								<?php }else{
									the_title( '<h1 class="page-title">', '</h1>' ); 
								}?>
								</div>
						<?php endif;
						endif;?>
						
						<?php if( emallshop_get_option('show-page-breadcrumb', 1)  ):
							if( $show_breadsrumb == 'yes' ):								
								echo emallshop_breadcrumbs(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							endif;
						endif;?>				
					</div>			
				</div>
			</div>
		</div>
<?php endif;
endif;?>
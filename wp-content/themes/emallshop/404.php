<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */

get_header(); ?>

	<div class="content-area col-md-12">
		<div class="error-404 not-found">
			<?php if( emallshop_get_option('404-use-image-text', '404-text') =='404-text' ) {?>
				<h1>404 <span><?php echo esc_html( emallshop_get_option('404-page-title', 'Oops! That page can&rsquo;t be found.') ); ?><span></h1>
				<?php if( emallshop_get_option('show-previous-link', 1) ) {?>
					<p><?php echo esc_html( emallshop_get_option('404-page-tagline', 'Try using the button below to go to back previous page.') ); ?></p>
					<?php if(wp_get_referer()): ?>
						<a class="button" href="<?php echo esc_url(wp_get_referer());?>"><i class="fa fa-reply"></i> <?php echo esc_html( emallshop_get_option('previous-link-title', 'Go to Back') );?></a>
					<?php endif;?>
				<?php }?>							
			<?php }else{ 
				$image_404=emallshop_get_option('404-page-image');?>
				<?php if(!empty($image_404['url'])){?>
					<img src="<?php echo esc_url($image_404['url']);?>" alt="<?php esc_attr_e('404 Image', 'emallshop'); ?>">	
				<?php }else{
					esc_html_e('Image Not Set', 'emallshop');
				}?>
			<?php } ?>
		</div><!-- .error-404 -->
	</div>

<?php get_footer(); ?>

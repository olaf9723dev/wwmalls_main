<?php
/**
 * The template for displaying the footer
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.3
 */

?>
<footer id="footer" class="footer">
	<div class="footer-top">
		<div class="container">
			<div class="row">
				<div class="col-sm-7 col-md-7 news-letter">
					<h3 class="widget-title"><?php esc_html_e('Newsletter','emallshop');?></h3> 
					<?php if( function_exists( 'mc4wp_show_form' ) ) { 
						mc4wp_show_form();
					}?>
				</div>
				<div class="col-sm-5 col-md-5 social-media style-1">
					<h3 class="widget-title"><?php esc_html_e('Stay Connected','emallshop');?></h3>
					<?php emallshop_social_link();?>
				</div>
			</div>
		</div>
	</div>
	<div class="footer-middle">
		<div class="container">
			<div class="row">
				<div class="col-sm-6 col-md-3">					
					<?php dynamic_sidebar('footer-widget-area-1');?>
				</div>
				<div class="col-sm-6 col-md-3">
					<?php dynamic_sidebar('footer-widget-area-2');?>
				</div>
				<div class="col-sm-6 col-md-3">
					<?php dynamic_sidebar('footer-widget-area-3');?>
				</div>
				<div class="col-sm-6 col-md-3">
					<?php dynamic_sidebar('footer-widget-area-4');?>					
				</div>
			</div>
		</div>
	</div>
	<div class="footer-copyright">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<p><?php 
						$copyright_text = emallshop_get_option('copyright-text','EmallShop &copy; {current_year} by <a href="https://presslayouts.com/" target="_blank">PressLayouts</a> All Rights Reserved.');
						$current_year = date("Y"); 
						$copyright_text = str_replace( '{current_year}', $current_year, $copyright_text );
						echo wp_kses_post( $copyright_text ); ?></p>
				</div>
				<div class="col-xs-12 col-sm-6 text-right">
					<?php if(emallshop_get_option('show-payments-logo',0)):?>
						<div class="payments-method">
							<?php $payments_url=emallshop_get_option('payments-logo',array( 'url' => EMALLSHOP_IMAGES.'/payments-method.png'));?>
							<img src="<?php echo esc_url($payments_url['url']);?>" alt="<?php esc_attr_e('Payments','emallshop');?>">
						</div>
					<?php endif;?>
				</div>
			</div>
		</div>
	</div>
</footer><!-- .site-footer -->
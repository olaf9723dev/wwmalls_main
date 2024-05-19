<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */
?>
				</div>
			</div><!-- .content-area -->
		</div><!-- .site-content -->
	</div><!-- .main-container -->
	<?php 
		get_template_part( 'templates/footer/'.emallshop_get_option( 'footer-layout', 'footer-1' ) );
	?>
	
	<?php if( emallshop_get_option( 'back-to-top', 1 ) ):?>
		<div class="back-to-top">
			<i class="fa  fa-arrow-up"></i>
		</div>
	<?php endif;?>
	
</div><!-- .site -->

<?php do_action( 'emallshop_after_footer' ); ?>
<?php wp_footer(); ?>
</body>
</html>

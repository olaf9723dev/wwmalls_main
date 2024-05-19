<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */
$sidebar_position 				= emallshop_getSidebarPosition( get_post_meta ( $post->ID, '_emallshop_sidebar_position', true ));
$sidebar_widget 				= emallshop_getSidebarWidget( get_post_meta ( $post->ID, '_emallshop_sidebar_widget', true ));
$column_classs					= emallshop_getColumnClass( $sidebar_position );  
$GLOBALS['sidebar_position']	= $sidebar_position;
$GLOBALS['sidebar_widget'] 		= $sidebar_widget;

get_header(); ?>

	<div class="container">
		<div class="row">
        	<div class="content-area <?php echo esc_attr($column_classs);?>">

				<?php
				// Start the loop.
				while ( have_posts() ) : the_post();

					/*
					 * Include the post format-specific template for the content. If you want to
					 * use this in a child theme, then include a file called called content-___.php
					 * (where ___ is the post format) and that will be used instead.
					 */
					
					get_template_part( 'content', get_post_format() );
					
					//share social post					
					if( emallshop_get_option('show-post-share-link', 1) && function_exists( 'emallshop_single_sharing' ) ):	?>
					<div class="social-share">
						<h3><span><?php esc_html_e('Share This Post','emallshop');?></span></h3>
						<?php emallshop_single_sharing();?>
					</div>
					<?php endif;
					
					// Previous/next post navigation.
					if( emallshop_get_option('show-single-post-navigation', 1) ):
						emallshop_post_navigation(array());
					endif;
					
					// Related post
					if( emallshop_get_option('show-related-post', 1) ):
						emallshop_related_post($post->ID);						
					endif;
					
					// If comments are open or we have at least one comment, load up the comment template.			
					if( emallshop_get_option('show-post-commnet', 1)):
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
					endif;

				// End the loop.
				endwhile;
				?>

			</div>
       	<?php get_sidebar(); ?>
		</div>
	</div><!-- .content-area -->

<?php get_footer(); ?>
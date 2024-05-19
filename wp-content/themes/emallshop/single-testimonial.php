<?php
/**
 * The template for displaying all single testimonial and attachments
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */

$sidebar_position				= emallshop_get_option('testimonial-page-layout', 'right');
$sidebar_widget					= emallshop_get_option('testimonial-page-sidebar-widget','sidebar-1');
$column_classs					= emallshop_getColumnClass( $sidebar_position );
$GLOBALS['sidebar_position'] 	= $sidebar_position;
$GLOBALS['sidebar_widget'] 		= $sidebar_widget;

$testimonial_meta 				= get_post_meta( get_the_ID());
get_header();?>

	<div class="content-area <?php echo esc_attr($column_classs);?> single-testimonial">				
		
		<div class="testimonial-title">
				
				<header class="entry-header">
					
					<?php if( emallshop_get_option('show-page-title', 1) && emallshop_get_option('show-title-breadcrumb-content','in-page-heading')=="in-page-content" ):
						if( emallshop_get_option('show-testimonialt-page-title', 1)):?>
							<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
					<?php endif;
					endif;?>
					
					<div class="designation">
						<?php if( !empty( $testimonial_meta['client_designation'] ) ):
							echo esc_attr($testimonial_meta['client_designation'][0]);
						endif;?>
					</div>	
				</header><!-- .entry-header -->
			<?php				
				
			//Previous/next testimonial navigation.
			emallshop_header_post_navigation(array('archive_post'=>'testimonial'));?>
			</div>
			
		<div class="entry-testimonials">
			<?php 
			if(have_posts()) : ?>
			
				<div class="row">
					<ul class="testimonials-list">
						<?php /*
						* Include the Custom Testimonial-Format-specific template for the content.
						*/								
						// Start the loop.
						while ( have_posts() ) : the_post();?>
							<li class="blockquote">
								<div class="quote-meta col-xs-3 col-md-2">
									<div class="client-image">
										<?php echo get_the_post_thumbnail( get_the_ID(), 'thumbnail' ); ?>
									</div>																
								</div>
								<div class="quote-content col-xs-9 col-md-10">
									<?php the_content();?>
								</div>										
							</li>
						
						<?php 
						// End the loop.
						endwhile; ?>
					</ul>					
				</div>
				
			<?php else :
				// If no content, include the "No posts found" template.
				get_template_part( 'content', 'none' ); 
			endif;?>
		</div>
	</div>
	<?php get_sidebar(); ?>

<?php 
get_footer();?>
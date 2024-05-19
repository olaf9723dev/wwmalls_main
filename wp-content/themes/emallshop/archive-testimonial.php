<?php
/**
 * The template for displaying testimonial archive pages.
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.0
 *
 */

$sidebar_position				= emallshop_get_option('testimonial-archive-page-layout', 'right');
$column_grid					= emallshop_get_option('testimonial-archive-page-show-column','two');
$sidebar_widget					= emallshop_get_option('testimonial-archive-page-sidebar-widget', 'sidebar-1');
$column_classs					= emallshop_getColumnClass( $sidebar_position );
$GLOBALS['sidebar_position'] 	= $sidebar_position;
$GLOBALS['sidebar_widget'] 		= $sidebar_widget;
 
if( $column_grid == "three" ) :
	$column_grid_class = "col-sm-6 col-md-4";
elseif( $column_grid == "four" ) :
	$column_grid_class = "col-sm-6 col-md-3";
else:
	$column_grid_class = "col-sm-6 col-md-6";
endif;
get_header();?>

	<div class="content-area <?php echo esc_attr($column_classs);?>">
		
		<?php if( emallshop_get_option('show-page-title', 1) && emallshop_get_option('show-title-breadcrumb-content','in-page-heading')=="in-page-content" ):
			if( emallshop_get_option('show-testimonialt-archive-page-title', 1)):?>
				<header class="entry-header">
					<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
				</header><!-- .entry-header -->
		<?php endif;
		endif;?>
		
		<div class="entry-testimonials">
			<?php 
			if(have_posts()) : ?>
			
				<div class="row">							
					<ul class="testimonials-list">
						<?php /*
						* Include the Custom Testimonial-Format-specific template for the content.
						*/
						
						// Start the loop.
						while ( have_posts() ) : the_post();
						
							$testimonial_meta = get_post_meta( get_the_ID());?>
							<li class="blockquote <?php echo esc_attr($column_grid_class);?>">
								<div class="quote-content">
									<?php the_content();?>
								</div>
								<div class="quote-meta">
									<div class="client-image">
										<?php echo get_the_post_thumbnail( get_the_ID(), 'thumbnail' ); ?>
									</div>
									<div class="name-designation">
										<a href="<?php echo esc_url(get_permalink());?>"> <h6 class="name"><?php the_title()?></h6></a>
										<div class="designation">
										<?php if( !empty( $testimonial_meta['es_client_designation'] ) ):
											echo esc_html($testimonial_meta['es_client_designation'][0]);
										endif;?>
										</div>								
									</div>							
								</div>
							</li>
						
						<?php 
						// End the loop.
						endwhile; ?>
					</ul>
					
					<?php 
					// Previous/next page navigation.
					emallshop_pagination_nav(); ?>
					
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
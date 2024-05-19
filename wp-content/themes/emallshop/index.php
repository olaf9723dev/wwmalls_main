<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */

$sidebar_position				= emallshop_get_option('blog-page-layout','right');
$blog_style						= emallshop_get_option('blog-page-style','large_image');
$sidebar_widget					= emallshop_get_option('blog-page-sidebar-widget','sidebar-1');
$column_classs					= emallshop_getColumnClass( $sidebar_position );
$GLOBALS['sidebar_position'] 	= $sidebar_position;
$GLOBALS['sidebar_widget'] 		= $sidebar_widget;

$blog_content_class = "post_".$blog_style;
if( $blog_style == 'small_image' ) :
	$blog_content_class .= " small-image";
elseif( $blog_style == 'grid_column' ) :
	$blog_content_class .= " row masonry-grid";
endif;
get_header(); ?>

	<div class="content-area <?php echo esc_attr($column_classs);?>">
	
		<?php if( emallshop_get_option('show-blog-page-title', 1)==1):?>
			<?php if ( is_home() && ! is_front_page() ) : ?>
				<header>
					<h1 class="page-title"><?php single_post_title(); ?></h1>
				</header>
			<?php endif; ?><!-- .entry-header -->
		<?php endif;?>
						
		<?php if ( have_posts() ) : ?>
			
			<div class="blog-posts <?php echo esc_attr($blog_content_class);?>">
			
			<?php
			// Start the loop.
			while ( have_posts() ) : the_post();
				 /*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'templates/blog/'.$blog_style ); 
				
			// End the loop.
			endwhile;?>
			
			</div>
			
			<?php // Previous/next page navigation.
			emallshop_pagination_nav();   
			
		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'content', 'none' );        
		endif;
		wp_reset_postdata(); ?>

	</div>
	<?php get_sidebar(); ?>

<?php get_footer(); ?>

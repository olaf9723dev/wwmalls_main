<?php
/**
 * The template for displaying search results pages.
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */

get_header(); ?>

		<div class="content-area col-sm-8 col-md-9">	
			<header class="entry-header">
				<h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'emallshop' ), get_search_query() ); ?></h1>
			</header><!-- .entry-header -->			
			<div class="blog-posts">
			<?php if ( have_posts() ) : 
				
				// Start the loop.
				while ( have_posts() ) : the_post(); ?>

					<?php
					/*
					 * Run the loop for the search to output the results.
					 * If you want to overload this in a child theme then include a file
					 * called content-search.php and that will be used instead.
					 */
					get_template_part( 'content', 'search' );

				// End the loop.
				endwhile;
			 
				// Previous/next page navigation.
				emallshop_pagination_nav(); 
				
			else :
				// If no content, include the "No posts found" template.
				get_template_part( 'content', 'none' );        
			endif;
			
			wp_reset_query(); ?>
			
			</div>
		</div>
		<?php get_sidebar(); ?>
			
<?php get_footer(); ?>
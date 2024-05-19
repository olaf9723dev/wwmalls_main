<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */
 
$show_title 					= emallshop_getShowTitle( get_post_meta ( get_the_ID(), '_emallshop_show_title', true ) );
$sidebar_position 				= emallshop_getSidebarPosition( get_post_meta ( get_the_ID(), '_emallshop_sidebar_position', true ));
$sidebar_widget 				= emallshop_getSidebarWidget( get_post_meta ( get_the_ID(), '_emallshop_sidebar_widget', true ));
$column_classs 					= emallshop_getColumnClass( $sidebar_position );
$GLOBALS['sidebar_position'] 	= $sidebar_position;
$GLOBALS['sidebar_widget'] 		= $sidebar_widget;

get_header(); ?>
		
	<div id="primary" class="content-area <?php echo esc_attr($column_classs);?>">

		<?php // Start the loop.
		while ( have_posts() ) : the_post();?>
		
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			
				<?php
					// Post thumbnail.
					emallshop_post_thumbnail();
				?>
				
				<?php if( emallshop_get_option('show-page-title', 1) && emallshop_get_option('show-title-breadcrumb-content','in-page-heading')=="in-page-content" ):
					if( $show_title == 'yes' && !is_front_page()):?>	
						<header class="entry-header">
							<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
						</header><!-- .entry-header -->
				<?php endif;
				endif;?>
				
				<div class="page-content">
				
					<?php the_content(); ?>
					
					<?php wp_link_pages( array(
							'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'emallshop' ) . '</span>',
							'after'       => '</div>',
							'link_before' => '<span>',
							'link_after'  => '</span>',
							'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'emallshop' ) . ' </span>%',
							'separator'   => '<span class="screen-reader-text">, </span>',
						) ); ?>
						
				</div><!-- .entry-content -->
				
			</div>	
			
			<?php // If comments are open or we have at least one comment, load up the comment template.
			if( emallshop_get_option('show-page-commnet', 1 ) ):
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			endif;

		// End the loop.
		endwhile; ?>

	</div>

	<?php // Get sidebar
	get_sidebar(); ?>			

<?php get_footer(); ?>
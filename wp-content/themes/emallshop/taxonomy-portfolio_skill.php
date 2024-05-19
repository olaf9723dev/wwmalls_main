<?php
/**
 * The template for displaying portfolio skills archive pages.
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.0
 *
 */

$sidebar_position			= emallshop_get_option('portfolio-archive-page-layout', 'right');
$portfolio_style			= emallshop_get_option('portfolio-archive-page-style','portfolio_grid');
$portfolio_grid_style		= emallshop_get_option('portfolio-grid-style','masonry_grid');
$portfolio_grid_column		= emallshop_get_option('portfolio-grid-column','two');
$grid_hover_effect			= emallshop_get_option('portfolio-grid-hover-effect','default_effect');
$sidebar_widget				= emallshop_get_option('portfolio-archive-page-sidebar-widget','sidebar-1');

$portfolio_content_class	="portfolio-item";
if( $portfolio_grid_column == 'one' ) :
	$portfolio_content_class	.= " one_column_grid";
elseif( $portfolio_grid_column == 'two' ) :
	$portfolio_content_class .= " col-xs-6 col-sm-6 multi_grid";
elseif( $portfolio_grid_column == 'three' ) :
	$portfolio_content_class .= " col-xs-6 col-sm-6 col-md-4 multi_grid";
elseif( $portfolio_grid_column == 'four' ) :
	$portfolio_content_class .=" col-xs-6 col-sm-6 col-md-3 multi_grid";
endif;

$gridMode 			= ( $portfolio_grid_style == 'masonry_grid' ) ? ' masorny-grid' : ' normal-grid';
$grid_hover_effect 	= ( $portfolio_style == 'portfolio_grid' && $portfolio_grid_column != 'one' ) ? $grid_hover_effect : '';
		
$column_classs					= emallshop_getColumnClass( $sidebar_position );
$GLOBALS['sidebar_position'] 	= $sidebar_position;
$GLOBALS['sidebar_widget'] 		= $sidebar_widget;

get_header();?>

	<div class="content-area <?php echo esc_attr($column_classs);?>">
		
		<?php if( emallshop_get_option('show-page-title', 1) && emallshop_get_option('show-title-breadcrumb-content','in-page-heading')=="in-page-content" ):
			if( emallshop_get_option('show-portfolio-archive-page-title', 1) ):?>
				<header class="entry-header">
					<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
				</header><!-- .entry-header -->
		<?php endif;
		endif;?>
		
		<div class="portfolio-list <?php echo esc_attr($portfolio_style);?>">
		
			<?php if(have_posts()) : 
				
				$portfolio_cats = get_categories(array(
					'taxonomy' => 'portfolio_cat'
				));
			
				if (is_array($portfolio_cats) && !empty($portfolio_cats)) :	?>
					<ul class="portfolioFilter">
						<li><a href="#" data-filter="*" class="current"><?php echo esc_html__('Show All', 'emallshop'); ?></a></li>
						<?php foreach ($portfolio_cats as $portfolio_cats) : ?>
						<li><a href="#" data-filter=".<?php echo esc_attr($portfolio_cats->slug);?>"><?php echo esc_html($portfolio_cats->name);?></a></li>
						<?php endforeach; ?>
					</ul> 
				<?php endif;?>
				
				<div class="row">
			
					<div class="portfolioContainer <?php echo esc_attr($grid_hover_effect.''.$gridMode);?>">

						<?php
						// Start the loop.
						while ( have_posts() ) : the_post();
							$item_class='';
							$item_cats = get_the_terms(get_the_ID(), 'portfolio_cat');
							if ($item_cats) :
								foreach ($item_cats as $item_cat) :
									$item_class .=' '. urldecode($item_cat->slug);
								endforeach;
							endif; ?>
							
							<div class="<?php echo esc_attr($item_class.' '.$portfolio_content_class);?>">
							
								<?php get_template_part( 'templates/portfolio/'.$portfolio_style );	?>
								
							</div>
							
						<?php // End the loop.
						endwhile;?>
						
					</div>

				</div>
				
				<?php // Previous/next page navigation.
				emallshop_pagination_nav();   
			
			else :
				// If no content, include the "No posts found" template.
				get_template_part( 'content', 'none' ); 
			endif;?>
		</div>
	</div>
	<?php get_sidebar(); ?>

<?php 
get_footer();?>
<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */
global $postid;
$postid						= $post->ID;
$sidebar_position 			= emallshop_getSidebarPosition( get_post_meta ( $post->ID, '_emallshop_sidebar_position', true ));
$sidebar_widget 			= emallshop_getSidebarWidget( get_post_meta ( $post->ID, '_emallshop_sidebar_widget', true ));
$show_title 				= emallshop_getShowTitle( get_post_meta ( $post->ID, '_emallshop_show_title', true ) );
$column_classs				= emallshop_getColumnClass( $sidebar_position ); 
$cat_lists 					= wp_get_object_terms($post->ID, 'portfolio_cat');
$skill_lists 				= wp_get_object_terms($post->ID, 'portfolio_skills'); 
$related_portfolios 		= emallshop_getRelatedPortfolios($post->ID);
$page_meta 					= get_post_meta ( get_the_ID() );
$GLOBALS['sidebar_position']= $sidebar_position;
$GLOBALS['sidebar_widget'] 	= $sidebar_widget;

get_header(); ?>

	<div class="content-area <?php echo esc_attr($column_classs);?>">
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class('portfolio-detail'); ?>>
			
			<div class="portfolio-content row">
				<div class="col-sm-7 col-md-6">
					<?php
						// Post thumbnail.
						emallshop_post_thumbnail();
					?>
				</div>
				<div class="col-sm-5 col-md-6">
					<div class="portfolio-title">
						<?php if( emallshop_get_option('show-page-title', 1)):
							if( $show_title == 'yes' ):?>	
								<header class="entry-header">
									<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
								</header><!-- .entry-header -->
						<?php endif;
						endif;
					
						if (emallshop_get_option('show-project-navigation', 1)):
							//Previous/next portfolio navigation.
							emallshop_header_post_navigation(array('archive_post'=>'portfolio'));
						endif;?>
					</div>
			
					<header class="entry-header">
					  <h4 class="project-title"><?php esc_html_e('Project Description','emallshop');?></h4>
					</header><!-- .entry-header -->
			   
					<div class="entry-content">
						<?php the_content(); ?>
					</div><!-- .entry-content -->
					
					<?php if(isset($page_meta['es_portfolio_project_url'])):?>
						<div class="project-url">
							<a href="<?php echo esc_url($page_meta['es_portfolio_project_url'][0]);?>" target="_blank">
								<i class="fa fa-hand-o-right fa-lg"></i> <?php esc_html_e('Live Preview','emallshop');?>
							</a>
						</div>
					<?php endif; ?>
					
					<?php if(emallshop_get_option('show-project-info', 1)):?>
					<div class="entry-information">
						<h4 class="project-title"><?php esc_html_e('Project Informations','emallshop');?></h4>
													
						<ul>
							<li>									
							<?php if(isset($page_meta['es_portfolio_client_name'])):?>				
								<p><strong><?php esc_html_e('Client','emallshop');?> : </strong></p>&nbsp;
								<p><?php echo esc_attr($page_meta['es_portfolio_client_name'][0]);?></p>
							<?php endif;?>									
							</li>
							<li>
								<p><strong><?php esc_html_e('Date','emallshop');?> : </strong></p>&nbsp;
								<p><?php echo get_the_date(); ?></p>
							</li>
							<li>
								<?php if ( !empty( $cat_lists ) ) : ?>
									<p><strong><?php esc_html_e('Category','emallshop');?> : </strong></p>&nbsp;
									<p><span class="cal-list">
											<?php  $i=0; $total = count($cat_lists);
											foreach( $cat_lists as $cat_list):  $i++;?>
												<i class="fa fa-check-square"></i> <a href="<?php echo esc_url( get_term_link ($cat_list->term_id) );?>"><?php echo esc_attr($cat_list->name);?></a><?php if ($i != $total) echo ',';?>
											<?php endforeach; ?>
										</span>									
									</p>
								<?php endif; ?>
							</li>
							<li>
								<?php if (!empty($skill_lists)) : ?>
									<p><strong><?php esc_html_e('Skills','emallshop');?> : </strong></p>&nbsp;
									<p><span class="skill-list">
											<?php  $i=0; $total = count($skill_lists);
											foreach( $skill_lists as $skill_list):  $i++;?>
											<i class="fa fa-check-circle"></i> <a href="<?php echo esc_url( get_tag_link($skill_list->term_id ) ); ?>"><?php echo esc_attr($skill_list->name);?></a> <?php if ($i != $total) echo ',';?>
											<?php endforeach; ?>
										</span>
									</p>  
								<?php endif;?>
							</li>
							<li>
								<?php if(emallshop_get_option('show-project-share-link', 1)):?>
									<p><strong><?php esc_html_e('Share','emallshop');?> :</strong></p>
									<?php emallshop_single_sharing();?>
								<?php endif;?>
							</li>
						</ul>
					</div><!-- .entry-detail -->
					<?php endif;?>
				</div>
			</div>
		</article><!-- #post-## -->		
		
	   
		<!--Related Projects -->
		<?php if(emallshop_get_option('show-related-projects', 1)):?>
			
			<?php $id = uniqid();
			$emallshop_owlparam['productsCarousel']['section-'.$id] = array(
				'autoplay'     => emallshop_get_option('related-portfolio-auto-play', 1) ? 'true' : 'false',
				'loop'         => emallshop_get_option('related-project-loop', 1) ? 'true' : 'false',
				'navigation'   => emallshop_get_option('related-project-navigation', 1) ? 'true' : 'false',
				'dots'         => emallshop_get_option('related-project-dots', 1) ? 'true' : 'false',
				'rp_desktop'     => emallshop_get_option('related-portfolio-per-row','3') ,
				'rp_small_desktop' => 3,
				'rp_tablet'     => 2,
				'rp_mobile'     => 2,
				'rp_small_mobile' => 1,
			);?>
			
			<?php if ( $related_portfolios->have_posts() ) :?>
				<div class="related-portfolios">
					<h3 class="related-portfolio-title"><span><?php esc_html_e('Related projects','emallshop');?></span></h3>
					<div id="section-<?php echo esc_attr($id);?>" class="row">
						<div class="product-carousel owl-carousel default_effect">
							<?php while ($related_portfolios->have_posts()) {
								$related_portfolios->the_post();?>
								<div class="item">
								<?php get_template_part('templates/portfolio/portfolio_content'); ?>
								</div>
							<?php }	?>                       
						</div>
					</div>
				</div>
			<?php endif;?>
		<?php endif;?>
		<?php 				
		// End the loop.
		endwhile;
		?>
</div>
<?php get_sidebar(); ?>

<?php get_footer(); ?>
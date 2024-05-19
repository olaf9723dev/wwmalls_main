<?php
/**
 * The portfolio template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */
 

$image_link = wp_get_attachment_url( get_post_thumbnail_id() ); 
$skill_lists = wp_get_object_terms(get_the_ID(), 'portfolio_skills');
?>	   
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if($GLOBALS['portfolio_grid_column']=='one'):?>
		<div class="entry-portfolio">
			<div class="col-sm-5">
				<div class="portfolio-thumbnail">
					<?php
						// Post thumbnail.
						emallshop_small_post_thumbnail();
					?>							
				</div>					
			</div>
			<div class="col-sm-7">
				<div class="portfolio-content">
					<a href="<?php echo esc_url(get_permalink());?>"><h4><?php the_title();?></h4></a>
					<?php if(!empty($skill_lists)):?>
					<div class="portfolio-skill">
						<strong><?php esc_html_e('Skill','emallshop');?> :</strong>
						<?php  $i=0; $total = count($skill_lists);
						if(!empty($skill_lists)):
						foreach( $skill_lists as $skill_list):  $i++;?>
						<a href="<?php echo esc_url( get_tag_link($skill_list->term_id) ); ?>"><?php echo esc_html($skill_list->name);?></a><?php if ($i != $total) echo ',';?>
						<?php endforeach; endif;?>
					</div>
					<?php endif;?>
					<?php the_excerpt(); ?>
				</div>
			</div>
		</div>
	<?php else:?>
		<div class="entry-portfolio">
			<?php
				// Post thumbnail.
				emallshop_post_thumbnail();
			?>				
			<div class="portfolio-content">
				<h4><?php the_title();?></h4>
				
				<div class="portfolio-skill">
					<ul>
						<?php  $i=0; $total = count($skill_lists);
						if(!empty($skill_lists)):
						foreach( $skill_lists as $skill_list):  $i++;?>
						<li><a href="<?php echo esc_url( get_tag_link($skill_list->term_id) ); ?>"><?php echo esc_html($skill_list->name);?></a><?php if ($i != $total) echo ',';?></li>
						<?php endforeach; endif;?>
					</ul>
				</div>
			</div>		
		</div>
	<?php endif;?>
</article><!-- #post-## -->			

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
	<div class="entry-portfolio">
		<div class="entry-thumbnail">
		<?php
			// Post thumbnail.
			emallshop_post_thumbnail();
		?>
			<div class="hover-overlay">
				<div class="hover-overlay-btn">
					<a href="<?php echo esc_url($image_link);?>" class="zoom-gallery"><i class="fa fa-search icon-animation"></i></a>
					<a href="<?php echo esc_url( get_permalink());?>" class="portfolio-detail"><i class="fa fa-link icon-animation"></i></a>
				</div>
			</div>
		</div>
		<div class="portfolio-content">
			<h4><?php the_title();?></h4>
			
			<div class="portfolio-skill">
				<ul>
					<?php  $i=0; $total = count($skill_lists);
					if(!empty($skill_lists)):
						foreach( $skill_lists as $skill_list):  $i++;?>
						<li><a href="<?php echo esc_url( get_tag_link($skill_list->term_id ) ); ?>"><?php echo esc_html($skill_list->name);?></a><?php if ($i != $total) echo ',';?></li>
						<?php endforeach; 
					endif;?>
				</ul>
			</div>
		</div>		
	</div>
	
</article><!-- #post-## -->
  
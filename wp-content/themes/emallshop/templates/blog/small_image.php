<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="col-sm-5">
	  
		<?php if( emallshop_get_option('show-blogs-thumbnail', 1) ==1):
			// Post thumbnail.
			emallshop_get_post_thumbnail('medium');
		endif; ?>
		
	</div>
	<div class="col-sm-7">
		<?php if( emallshop_get_option('show-postdate', 1) ==1):?>
			<div class="entry-date">
				<span class="entry-day"><?php  echo esc_html(get_the_time('d'));?></span>
				<span class="entry-month"><?php  echo esc_html(get_the_time('M'));?></span>
			</div>
		<?php endif; ?>
		<header class="entry-header">
			<?php
				the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			?>
		</header><!-- .entry-header -->
		
		<?php if(emallshop_get_option('show-postmeta', 1)==1):?>
			<footer class="entry-footer">
				<?php emallshop_entry_meta(); ?>
				<?php edit_post_link( esc_html__( 'Edit', 'emallshop' ), '<span class="edit-link">', '</span>' ); ?>
			</footer><!-- .entry-footer -->
		<?php endif;?>
		
		<div class="entry-content">
	
			<?php if( emallshop_get_option('show-blog-excerpt', 0 ) ==1):
				$length=emallshop_get_option('blog-excerpt-length', 75);
				echo emallshop_excerpt($length); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			else:
				/* translators: %s: Name of current post */
				the_content( sprintf(
					esc_html__( 'Read more %s', 'emallshop' ),
					the_title( '<span class="screen-reader-text">', '</span>', false )
				) );
			endif;?>
			
		</div><!-- .entry-content -->
	</div>	
</article><!-- #post-## -->
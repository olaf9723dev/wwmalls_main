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
	
	<?php if( (is_single() && emallshop_get_option('show-post-thumbnail', 1) ==1) || (!is_single() && emallshop_get_option('show-blogs-thumbnail', 1) ==1)):
		// Post thumbnail.
		emallshop_get_post_thumbnail('large');
	endif; ?>
	
	<?php if( emallshop_get_option('show-postdate', 1) ==1):?>
		<div class="entry-date">
			<span class="entry-day"><?php  echo esc_html( get_the_time('d') );?></span>
			<span class="entry-month"><?php  echo esc_html( get_the_time('M') );?></span>
		</div>
	<?php endif; ?>
	
	<header class="entry-header">
		<?php
			if ( is_single() ) :
				the_title( '<h2 class="entry-title">', '</h2>' );
			else :
				the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			endif;
		?>
	</header><!-- .entry-header -->
	
	<?php if( emallshop_get_option('show-postmeta', 1) ==1):?>
		<footer class="entry-footer">
			<?php emallshop_entry_meta(); ?>
			<?php edit_post_link( esc_html__( 'Edit', 'emallshop' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-footer -->
	<?php endif;?>
	
	<div class="entry-content">
		
		<?php if( ! is_single() && emallshop_get_option('show-blog-excerpt', 0 ) ==1):
				$length=emallshop_get_option('blog-excerpt-length', 75);
				echo esc_html( emallshop_excerpt($length) );
			else:
				/* translators: %s: Name of current post */
				the_content( sprintf(
					esc_html__( 'Read more %s', 'emallshop' ),
					the_title( '<span class="screen-reader-text">', '</span>', false )
				) );
			endif;					

			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'emallshop' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'emallshop' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
		?>
	</div><!-- .entry-content -->

	<?php
		// Author bio.
		if ( is_single() && get_the_author_meta( 'description' ) ) :
			get_template_part( 'author-bio' );
		endif;
	?>

</article><!-- #post-## -->

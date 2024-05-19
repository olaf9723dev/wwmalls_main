<?php
/**
 * The template for displaying image attachments
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */

get_header(); ?>

	<div class="content-area col-xs-12">

	<?php
		// Start the loop.
		while ( have_posts() ) : the_post();
	?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<nav id="image-navigation" class="navigation image-navigation">
				<div class="nav-links">
					<div class="nav-previous"><?php previous_image_link( false, esc_html__( 'Previous Image', 'emallshop' ) ); ?></div><div class="nav-next"><?php next_image_link( false, esc_html__( 'Next Image', 'emallshop' ) ); ?></div>
				</div><!-- .nav-links -->
			</nav><!-- .image-navigation -->

			<header class="entry-header">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</header><!-- .entry-header -->

			<div class="entry-content">

				<div class="entry-attachment">
					<?php
						/**
						 * Filter the default EmallShop image attachment size.
						 *
						 * @since EmallShop 1.0
						 *
						 * @param string $image_size Image size. Default 'large'.
						 */
						$image_size = apply_filters( 'emallshop_attachment_size', 'large' );

						echo wp_get_attachment_image( get_the_ID(), $image_size );
					?>

					<?php if ( has_excerpt() ) : ?>
						<div class="entry-caption">
							<?php the_excerpt(); ?>
						</div><!-- .entry-caption -->
					<?php endif; ?>

				</div><!-- .entry-attachment -->

				<?php
					the_content();
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

			<?php if(emallshop_get_option('show-postmeta', 1)==1):?>
				<footer class="entry-footer">
					<?php emallshop_entry_meta(); ?>
					<?php edit_post_link( esc_html__( 'Edit', 'emallshop' ), '<span class="edit-link">', '</span>' ); ?>
				</footer><!-- .entry-footer -->
			<?php endif;?>

		</article><!-- #post-## -->

		<?php
			// If comments are open or we have at least one comment, load up the comment template
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

			// Previous/next post navigation.
			the_post_navigation( array(
				'prev_text' => _x( '<span class="meta-nav">Published in</span><span class="post-title">%title</span>', 'Parent post link', 'emallshop' ),
			) );

		// End the loop.
		endwhile;
	?>

	</div>

<?php get_footer(); ?>
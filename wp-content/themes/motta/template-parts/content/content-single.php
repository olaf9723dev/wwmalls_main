<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Motta
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php \Motta\Blog\Post::category(); ?>
		<?php \Motta\Blog\Post::title('h1'); ?>
		<div class="entry-meta">
			<?php \Motta\Blog\Post::author(); ?>
			<?php \Motta\Blog\Post::date(); ?>
			<?php \Motta\Blog\Post::comment(); ?>
			<?php \Motta\Blog\Post::share(); ?>
		</div>
		<?php \Motta\Blog\Post::image(); ?>
	</header>
	<div class="entry-content">
		<?php the_content(); ?>
	</div>
	<footer class="entry-footer">
		<?php \Motta\Blog\Post::tags(); ?>
		<?php \Motta\Blog\Post::share_link(); ?>
	</footer>
</article>

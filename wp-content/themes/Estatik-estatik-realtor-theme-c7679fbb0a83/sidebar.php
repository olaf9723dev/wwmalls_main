<div id="secondary" class="col-md-4">
	<?php if ( is_active_sidebar( 'sidebar-' . get_post_type() ) ) : ?>
		<div id="widget-area" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'sidebar-' . get_post_type() ); ?>
		</div><!-- .widget-area -->
	<?php endif; ?>
</div>

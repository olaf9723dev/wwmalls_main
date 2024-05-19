<?php

global $ef_options;
$copyright = $ef_options->get( 'footer_copyright' ); ?>

<footer>
	<div class="container">
		<div class="container__inner">
			<div class="row">
				<div class="col-md-4">
					<?php if ( is_active_sidebar( 'footer-left' ) ) : ?>
						<?php dynamic_sidebar( 'footer-left' ); ?>
					<?php endif; ?>
				</div>
				<div class="col-md-4">
					<?php if ( is_active_sidebar( 'footer-center' ) ) : ?>
						<?php dynamic_sidebar( 'footer-center' ); ?>
					<?php endif; ?>
				</div>
				<div class="col-md-4">
					<?php if ( is_active_sidebar( 'footer-right' ) ) : ?>
						<?php dynamic_sidebar( 'footer-right' ); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
    <?php if ( ! empty( $copyright ) ) : ?>
        <div id="copyright">
            <small><?php echo stripslashes( $copyright ); ?></small>
        </div>
    <?php endif; ?>
</footer>

<?php wp_footer(); ?>

</body>
</html>

<?php

/**
 * @var $args array
 * @var $depth array
 */

global $es_settings; ?>

<<?php echo $tag, $classes; ?> id="comment-<?php comment_ID() ?>">
    <div class="row">
        <div class="col">
            <div class="row">
                <div class="col-auto comment-author-img">
	                <?php if ( $args['avatar_size'] != 0 ) : ?>
		                <?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
	                <?php endif; ?>
                </div>
                <div class="col-auto author-meta">
                    <div class="author-name">
                        <span class="comment-author"><?php comment_author(); ?></span>
	                    <?php if ( ! $es_settings->hide_agent_rating ) : ?>
	                        <?php do_action( 'ert_rating', 4, false ); ?>
                        <?php endif; ?>
                    </div>
                    <div class="comment-date"><?php comment_date( 'F d, Y' ); ?></div>
                </div>
            </div>
        </div>
        <div class="col-auto">
	        <?php comment_reply_link( array_merge( $args, array(
		        'depth'     => $depth,
		        'max_depth' => $args['max_depth']
	        ) ) ); ?>
        </div>

		<div class="comment-text col-12">
			<?php comment_text(); ?>
		</div>
	</div>
</<?php echo $tag; ?>>
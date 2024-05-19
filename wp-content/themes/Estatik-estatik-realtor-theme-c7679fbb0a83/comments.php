<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
$num_comments = get_comments_number(); ?>

<div id="comments" class="comments-area box-shadow content-box">

	<h3 class="comments-title">
        <?php if ( $num_comments ) : ?>
            <?php printf( esc_html( _n( '%d Review', '%d Reviews', $num_comments, 'ert'  ) ), $num_comments ); ?>
        <?php else: ?>
            <?php _e( 'Reviews', 'ert' ); ?>
        <?php endif;?>
	</h3>

	<ol class="comment-list">
		<?php
		wp_list_comments( array(
			'avatar_size' => 65,
			'type' => 'comment',
            'max_depth' => 2,
            'callback' => 'ert_comment',
		) );
		?>
	</ol><!-- .comment-list -->

	<?php
	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
		<p class="no-comments"><?php _e( 'Comments are closed.', 'twentyfifteen' ); ?></p>
	<?php endif; ?>

	<?php comment_form( array(
        'fields' => array(
            'author' => '<div class="form-row">
                            <div class="form-group col-md-6">
                                <input type="text" name="author" required class="form-control" placeholder="' . __( 'Your name', 'ert' ) . '">
                            </div>',

            'email' => '<div class="form-group col-md-6">
                            <input type="email" name="email" required class="form-control" placeholder="' . __( 'Your Email', 'ert' ) . '">
                        </div></div>',
        ),
		'comment_field' => '<div class="form-group"><textarea id="comment" name="comment" required class="form-control" placeholder="' . __( 'Your message', 'ert' ) . '"></textarea></div>',
        'submit_button'        => '<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />',
        'submit_field'         => '<div class="form-group text-right">%1$s %2$s</div>',
        'label_submit'         => __( 'Send Message', 'ert' ),
        'class_submit' => 'submit btn btn-light'
	) ); ?>

</div>

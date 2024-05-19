<?php

/*
* @hooked ert_post_breadcrumbs - 10
* @hooked ert_post_before - 30
* @hooked ert_post_loop - 40
* @hooked ert_post_after - 50
* @hooked ert_post_sidebar_loop - 60
 *
 */


add_action( 'ert_post_content', 'ert_page_breadcrumbs', 10 );
add_action( 'ert_post_content', 'ert_post_title', 20 );

if ( ! function_exists( 'ert_post_title' ) ) {

	/**
	 * Page content.
	 *
	 * @return void
	 */
	function ert_post_title() {

		global $ef_options;

		if ( have_posts() ) :
			while( have_posts() ) : the_post(); ?>

			<header class="entry-header col-12">
				<h2 class="entry-title"><?php the_title(); ?></h2>
				<?php if ( $ef_options->get( 'show_author' ) ) : ?>
					<?php do_action( 'ert_post_author', get_the_author_meta('ID') ); ?>
				<?php endif; ?>
			</header>

			<?php endwhile;
		endif;
	}

}

add_action( 'ert_post_content', 'ert_post_before', 20 );

if ( ! function_exists( 'ert_post_before' ) ) {

	/**
	 * Before page container.
	 *
	 * @return void
	 */
	function ert_post_before() {
		$css_class = is_active_sidebar( 'sidebar-' . get_post_type() ) ? 'col-md-8' : 'col-12'; ?>
		<div class="<?php echo $css_class; ?> site-content" id='primary'>
		<?php
	}
}

add_action( 'ert_post_content', 'ert_post_loop', 40 );

if ( ! function_exists( 'ert_post_loop' ) ) {

	/**
	 * Page content.
	 *
	 * @return void
	 */
	function ert_post_loop() {

		global $ef_options;

		wp_enqueue_script( 'es-share-script' );

		if ( have_posts() ) :
			while( have_posts() ) : the_post(); ?>

			<?php if ( $ef_options->get( 'show_featured_image' ) ) : ?>
                <div class="ert-post__featured-image">
                    <?php the_post_thumbnail( 'medium' ); ?>
                </div>
            <?php endif; ?>

			<div class="entry-content">
				<div class="content">
					<?php the_content(); ?>
				</div>
				<?php if ( is_singular( 'post' ) ) : ?>
                    <div class="meta">
                        <?php if ( get_the_tags() ) : ?>
                            <div class="ert-post-tags">
                                <?php the_tags( '<i class="fa fa-tags"></i>' ); ?>
                            </div>
                        <?php endif; ?>
                        <div class="row ert-post__bottom">
                            <div class="col ert-post-share">
                                <?php _e( 'Share', 'ert' ); ?>:
                                <?php do_action( 'ert_social_share_block' ); ?>
                            </div>
                            <div class="col text-right">
                                <?php if ( comments_open() ) : ?>
                                    <a href="#comments" class="btn btn-light"><?php _e( 'Write a comment', 'ert' ); ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
				<?php endif; ?>
			</div>

			<?php if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile;
		endif;
	}

}

add_action( 'ert_post_content', 'ert_page_after', 50 );

add_action( 'ert_post_content', 'ert_post_sidebar_loop', 60 );

if ( ! function_exists( 'ert_post_sidebar_loop' ) ) {

	/**
	 * Page Sidebar.
	 *
	 * @return void
	 */
	function ert_post_sidebar_loop() {
        $post_type = get_post_type();
        if ( $post_type && is_active_sidebar( 'sidebar-' . $post_type ) ) {
			get_sidebar( $post_type );
		}
	}
}

add_action( 'ert_post_author', 'ert_post_author' );

if ( ! function_exists( 'ert_post_author' ) ) {

    /**
      * @param $user_id
      * @param bool $show_date
      */
	function ert_post_author( $user_id, $show_date = true ) {

	    global $ef_options;

	    if ( ! $ef_options->get( 'show_author' ) || is_singular( 'agent' ) || is_singular( 'agency' ) ) return;

		echo "<div class='author'>";

		if ( ! is_estatik4() && function_exists( 'es_get_user_entity' ) && $entity = es_get_user_entity( $user_id ) ) {
		    echo "<img src='{$entity->get_image_url( 'thumbnail' )}' alt='" . __( 'User Image', 'ert' ) . "'/>";
		} else {
		    echo get_avatar( $user_id );
		}

		the_author();

		if ( $show_date ) {
		    echo "<div class='delimiter'></div>";
		    the_date( 'F d, Y' );
		}
		echo "</div>";
	}
}

add_action( 'ert_post_after_content', 'ert_post_pagination' );

if ( ! function_exists( 'ert_post_pagination' ) ) {

    function ert_post_pagination() {
        if ( ! is_singular( 'post' ) ) return;

        $next_post = get_next_post();
        $prev_post = get_previous_post();

        if ( $next_post || $prev_post ) : ?>
            <div class="ert-post__pagination">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6 pagination-item">
                            <?php if ( $prev_post ) : ?>
                            <div class="row">
                                <div class="col-auto my-auto">
                                    <a href="<?php echo get_permalink( $prev_post ); ?>"><?php echo get_the_post_thumbnail( $prev_post, 'thumbnail' ); ?></a>
                                </div>
                                <div class="col-auto my-auto">
                                    <span class="sup-title"><?php _e( 'Previous Post', 'ert' ); ?></span>
                                    <h4><a href="<?php echo get_permalink( $prev_post ); ?>"><?php echo get_the_title( $prev_post ); ?></a></h4>
                                </div>
                            </div>

                            <?php endif; ?>
                        </div>
                        <div class="col-sm-6 pagination-item">
                            <?php if ( $next_post ) : ?>
                            <div class="row pull-right">
                                <div class="col-auto my-auto align-right">
                                    <span class="sup-title"><?php _e( 'Next Post', 'ert' ); ?></span>
                                    <h4><a href="<?php echo get_permalink( $next_post ); ?>"><?php echo get_the_title( $next_post ); ?></a></h4>
                                </div>
                                <div class="col-auto my-auto">
                                    <a href="<?php echo get_permalink( $next_post ); ?>"><?php echo get_the_post_thumbnail( $next_post, 'thumbnail' ); ?></a>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif;
    }
}
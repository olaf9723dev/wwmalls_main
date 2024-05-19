<?php
/**
 * Custom template tags for EmallShop
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */

if ( ! function_exists( 'emallshop_comment_nav' ) ) :
/**
 * Display navigation to next/previous comments when applicable.
 *
 * @since EmallShop 1.0
 */
function emallshop_comment_nav() {
	// Are there comments to navigate through?
	if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
	?>
	<nav class="navigation comment-navigation" role="navigation">
		<h3><span><?php esc_html_e( 'Comment Navigation', 'emallshop' ); ?><span></h3>
		<div class="nav-links">
			<?php
				if ( $prev_link = get_previous_comments_link( esc_html__( 'Older Comments', 'emallshop' ) ) ) :
					printf( '<div class="nav-previous">%s</div>', $prev_link );
				endif;

				if ( $next_link = get_next_comments_link( esc_html__( 'Newer Comments', 'emallshop' ) ) ) :
					printf( '<div class="nav-next">%s</div>', $next_link );
				endif;
			?>
		</div><!-- .nav-links -->
	</nav><!-- .comment-navigation -->
	<?php
	endif;
}
endif;

if ( ! function_exists( 'emallshop_entry_meta' ) ) :
/**
 * Prints HTML with meta information for the categories, tags.
 *
 * @since EmallShop 2.0
 */
function emallshop_entry_meta() {
	
	if ( is_sticky() && is_home() && ! is_paged() ) {
		printf( '<span class="sticky-post">%s</span>', esc_html__( 'Featured', 'emallshop' ) );
	}
	
	$postmeta=emallshop_get_option('show-specific-postmeta', array('post-format','post-author','cat-links','tags-links','comments-link'));
	$format = get_post_format();
	if ( current_theme_supports( 'post-formats', $format ) && ( !empty($postmeta) && in_array('post-format', $postmeta ))) {
		printf( '<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
			sprintf( '<span class="screen-reader-text">%s </span>', _x( 'Format', 'Used before post format.', 'emallshop' ) ),
			esc_url( get_post_format_link( $format ) ),
			get_post_format_string( $format )
		);
	}

	if ( in_array( get_post_type(), array('attachment' ) ) ) {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			get_the_date(),
			esc_attr( get_the_modified_date( 'c' ) ),
			get_the_modified_date()
		);

		printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span><a href="%2$s" rel="bookmark">%3$s</a></span>',
			_x( 'Posted on', 'Used before publish date.', 'emallshop' ),
			esc_url( get_permalink() ),
			$time_string
		);
	}

	if ( 'post' == get_post_type() ) {
		if ( (is_singular() || is_multi_author()) && ( !empty($postmeta) && in_array('post-author', $postmeta ) ) ) {
			printf( '<span class="byline"><span class="author vcard"><span class="screen-reader-text">%1$s </span><a class="url fn n" href="%2$s">%3$s</a></span></span>',
				_x( 'Author', 'Used before post author name.', 'emallshop' ),
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				get_the_author()
			);
		}

		$categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'emallshop' ) );
		if ( $categories_list && emallshop_categorized_blog() && ( !empty($postmeta) && in_array('cat-links', $postmeta ))) {
			printf( '<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
				_x( 'Categories', 'Used before category names.', 'emallshop' ),
				$categories_list
			);
		}

		$tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'emallshop' ) );
		if ( $tags_list && ( !empty($postmeta) && in_array('tags-links', $postmeta ) ) ) {
			printf( '<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
				_x( 'Tags', 'Used before tag names.', 'emallshop' ),
				$tags_list
			);
		}
	}

	if ( is_attachment() && wp_attachment_is_image() ) {
		// Retrieve attachment metadata.
		$metadata = wp_get_attachment_metadata();

		printf( '<span class="full-size-link"><span class="screen-reader-text">%1$s </span><a href="%2$s">%3$s &times; %4$s</a></span>',
			_x( 'Full size', 'Used before full size attachment link.', 'emallshop' ),
			esc_url( wp_get_attachment_url() ),
			$metadata['width'],
			$metadata['height']
		);
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) && in_array('comments-link', emallshop_get_option('show-specific-postmeta', array('post-format','post-author','cat-links','tags-links','comments-link')))) {
		echo '<span class="comments-link">';
		/* translators: %s: post title */
		comments_popup_link( sprintf( __( 'Leave a comment<span class="screen-reader-text"> on %s</span>', 'emallshop' ), get_the_title() ) );
		echo '</span>';
	}
}
endif;

if ( ! function_exists( 'emallshop_categorized_blog' ) ) :
/**
 * Determine whether blog/site has more than one category.
 *
 * @since EmallShop 1.0
 *
 * @return bool True of there is more than one category, false otherwise.
 */
function emallshop_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'emallshop_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'emallshop_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so emallshop_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so emallshop_categorized_blog should return false.
		return false;
	}
}
endif;

/**
 * Flush out the transients used in {@see emallshop_categorized_blog()}.
 *
 * @since EmallShop 1.0
 */
function emallshop_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'emallshop_categories' );
}
add_action( 'edit_category', 'emallshop_category_transient_flusher' );
add_action( 'save_post',     'emallshop_category_transient_flusher' );

if ( ! function_exists( 'emallshop_post_thumbnail' ) ) :
/**
 * Display an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 *
 * @since EmallShop 1.0
 */
function emallshop_post_thumbnail() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	if ( is_singular() ) : ?>

	<div class="post-thumbnail">
		<?php if(wp_get_attachment_url( get_post_thumbnail_id() )) :
			 the_post_thumbnail('large');
		else:?>
			<img src="<?php echo esc_url(EMALLSHOP_IMAGES.'/blog-placeholder.jpg');?>"/>
		<?php endif;?>
	</div><!-- .post-thumbnail -->

	<?php else : ?>
	
	<div class="entry-thumbnail">
		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
			<?php if(wp_get_attachment_url( get_post_thumbnail_id() )) :
				the_post_thumbnail( 'large', array( 'alt' => get_the_title() ) );
			else:?>
				<img src="<?php echo esc_url(EMALLSHOP_IMAGES.'/blog-placeholder.jpg');?>"/>
			<?php endif;?>
		</a>
		<?php emallshop_image_overlay();?>
	</div>

	<?php endif; // End is_singular()
}
endif;

if ( ! function_exists( 'emallshop_small_post_thumbnail' ) ) :
/**
 * Display an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 *
 * @since EmallShop 1.0
 */
function emallshop_small_post_thumbnail() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}?>
	<div class="entry-thumbnail">
		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
			<?php if(wp_get_attachment_url( get_post_thumbnail_id() )) :
				the_post_thumbnail( 'medium', array( 'alt' => get_the_title() ) );
			else:?>
				<img src="<?php echo esc_url(EMALLSHOP_IMAGES.'/blog-placeholder.jpg');?>"/>
			<?php endif;?>
		</a>
		<?php emallshop_image_overlay();?>
	</div>
	<?php
}
endif;

if ( ! function_exists( 'emallshop_image_overlay' ) ) :
	function emallshop_image_overlay(){
		
		$image_link = wp_get_attachment_url( get_post_thumbnail_id() ); ?>
		<div class="hover-overlay">
			<div class="hover-overlay-btn">
				<a href="<?php echo esc_url($image_link);?>" class="zoom-gallery"><i class="fa  fa-search icon-animation"></i></a>
				<a href="<?php echo esc_url( get_permalink());?>" class="portfolio-detail"><i class="fa fa-link icon-animation"></i></a>
			</div>
		</div>
	<?php 
	}
endif;

if ( ! function_exists( 'emallshop_get_link_url' ) ) :
/**
 * Return the post URL.
 *
 * Falls back to the post permalink if no URL is found in the post.
 *
 * @since EmallShop 1.0
 *
 * @see get_url_in_content()
 *
 * @return string The Link format URL.
 */
function emallshop_get_link_url() {
	$has_url = get_url_in_content( get_the_content() );

	return $has_url ? $has_url : apply_filters( 'the_permalink', get_permalink() );
}
endif;

if ( ! function_exists( 'emallshop_excerpt_more' ) && ! is_admin() ) :
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... and a 'Continue reading' link.
 *
 * @since EmallShop 1.0
 *
 * @return string 'Continue reading' link prepended with an ellipsis.
 */
function emallshop_excerpt_more( $more ) {
	if( !emallshop_get_option('show-post-readmore', 0) ) return;
	
	$link = sprintf( '<a href="%1$s" class="more-link">%2$s</a>',
		esc_url( get_permalink( get_the_ID() ) ),
		/* translators: %s: Name of current post */
		sprintf( emallshop_get_option('post-readmore-text','Read more'), '<span class="screen-reader-text">' . get_the_title( get_the_ID() ) . '</span>' )
		);
	return ' &hellip; ' . $link;
}
add_filter( 'excerpt_more', 'emallshop_excerpt_more' );
endif;

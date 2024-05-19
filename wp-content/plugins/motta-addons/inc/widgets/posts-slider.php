<?php
/**
 * Posts Slider widget
 *
 * @package Motta
 */

namespace Motta\Addons\Widgets;

use \Motta\Addons\Helper;

/**
 * Class Posts_Slider
 */
class Posts_Slider extends \WP_Widget {
	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @var array
	 */
	protected $default;

	/**
	 * Class constructor
	 * Set up the widget
	 */
	public function __construct() {
		$this->defaults = array(
			'title' => esc_html__( 'Posts', 'motta-addons' ),
			'tag'   => 'editors-pick',
			'limit' => 3,
		);

		parent::__construct(
			'posts-slider-widget',
			esc_html__( 'Motta - Posts Slider', 'motta-addons' ),
			array(
				'classname'                   => 'posts-slider-widget',
				'description'                 => esc_html__( 'Display a posts slider', 'motta-addons' ),
				'customize_selective_refresh' => true,
			)
		);
	}

	/**
	 * Outputs the content for the current Archives widget instance.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Archives widget instance.
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );

		echo $args['before_widget'];

		if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		$query_args = array(
			'post_type'              => 'post',
			'post_status'            => 'publish',
			'posts_per_page'         => $instance['limit'],
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'cache_results'          => false,
			'ignore_sticky_posts'    => true,
		);

		if ( ! empty( $instance['tag'] ) ) {
			$query_args['tag'] = $instance['tag'];
		}

		$query = new \WP_Query( $query_args );

		if ( $query->have_posts() ) : ?>
			<div class="posts-slider swiper-container">
				<div class="swiper-wrapper">
					<?php while ( $query->have_posts() ) : $query->the_post(); ?>

						<div class="post swiper-slide">
							<?php $this->entry_thumbnail(); ?>

							<h2 class="entry-title__slider"><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></h2>

							<div class="entry-meta">
								<div class="entry-meta__date"><?php echo esc_html( get_the_date() ); ?></div>
								<div class="entry-meta__comments"><?php echo Helper::get_svg( 'comment-mini' ); echo get_comments_number(); ?></div>
							</div>
						</div>

					<?php endwhile; ?>
				</div>
				<div class="swiper-pagination mt-pagination__bullet"></div>
				<?php
					echo Helper::get_svg( 'left','ui', 'class=swiper-button motta-swiper-button-prev swiper-button--subtle' );
					echo Helper::get_svg( 'right','ui', 'class=swiper-button motta-swiper-button-next swiper-button--subtle' );
				?>
			</div>
			<?php
			wp_reset_postdata();
		endif;

		echo $args['after_widget'];
	}

	/**
	 * Outputs the settings form for the Archives widget.
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'motta-addons' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'tag' ); ?>"><?php esc_html_e( 'Tags:', 'motta-addons' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'tag' ); ?>" name="<?php echo $this->get_field_name( 'tag' ); ?>" type="text" value="<?php echo esc_attr( $instance['tag'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php esc_html_e( 'Number of posts:', 'motta-addons' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="text" value="<?php echo intval( $instance['limit'] ); ?>" />
		</p>

		<?php
	}

	public function entry_thumbnail() {
		if ( ! has_post_thumbnail() ) {
			return;
		}

		$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
		$get_image         = wp_get_attachment_image( $post_thumbnail_id, 'motta-post-slider-widget' );
		$icon			   = '';

		switch ( get_post_format() ) {
			case 'video':
				$icon = \Motta\Addons\Helper::get_svg( 'video', 'post-format-icon icon-video' );
				break;

			case 'gallery':
				$icon = \Motta\Addons\Helper::get_svg( 'gallery', 'post-format-icon icon-gallery' );
				break;
		}

		if ( empty( $get_image ) ) {
			return;
		}

		echo sprintf(
			'<a class="post-thumbnail" href="%s" aria-hidden="true" tabindex="-1">%s%s</a>',
			esc_url( get_permalink() ),
			$get_image,
			$icon
		);
	}
}

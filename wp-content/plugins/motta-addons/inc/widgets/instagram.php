<?php
/**
 * Instagram feed widget
 *
 * @package Motta
 */

namespace Motta\Addons\Widgets;

/**
 * Class Instagram Widget
 */
class Instagram_Widget extends \WP_Widget {
	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @var array
	 */
	protected $defaults;

	/**
	 * Class constructor
	 * Set up the widget
	 */
	public function __construct() {
		$this->defaults = array(
			'title'    => esc_html__( 'Instagram', 'motta-addons' ),
			'limit'    => 6,
			'columns'  => 3,
			'original' => false,
		);

		parent::__construct(
			'motta-instagram-widget',
			esc_html__( 'Motta - Instagram', 'motta-addons' ),
			array(
				'classname'                   => 'motta-instagram-widget',
				'description'                 => esc_html__( 'Displays your latest Instagram photos', 'motta-addons' ),
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

		if ( ! class_exists( '\Motta\Addons\Helper' ) && ! method_exists( '\Motta\Addons\Helper', 'motta_get_instagram_images' ) ) {
			esc_html_e( 'This widget works with the Motta theme only.', 'motta-addons' );
		} elseif ( 0 < $instance['limit'] ) {
			$medias  = \Motta\Addons\Helper::motta_get_instagram_images( $instance['limit'] );
			$current = 1;

			if ( is_wp_error( $medias ) ) {
				echo $medias->get_error_message();
			} else {
				echo '<div class="instagram-feed instagram-feed--' . ( $instance['original'] ? 'original' : 'cropped' ) . '"><ul class="motta-instagram__list columns-' . esc_attr( $instance['columns'] ) . '">';

				foreach ( $medias as $media ) {
					if ( $current > $instance['limit'] ) {
						break;
					}

					$this->display_image( $media, $instance['original'] );

					$current++;
				}

				echo '</ul></div>';
			}
		}

		echo $args['after_widget'];
	}

	/**
	 * Update widget
	 *
	 * @param array $new_instance New widget settings
	 * @param array $old_instance Old widget settings
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$new_instance['title']    = strip_tags( $new_instance['title'] );
		$new_instance['limit']    = intval( $new_instance['limit'] );
		$new_instance['columns']  = intval( $new_instance['columns'] );
		$new_instance['original'] = isset( $new_instance['original'] );

		return $new_instance;
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
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php esc_html_e( 'Number of photos:', 'motta-addons' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="text" value="<?php echo intval( $instance['limit'] ); ?>" />
		</p>

		<p>
			<input id="<?php echo $this->get_field_id( 'original' ); ?>" name="<?php echo $this->get_field_name( 'original' ); ?>" type="checkbox" value="1" <?php checked( 1, $instance['original'] ) ?> />
			<label for="<?php echo $this->get_field_id( 'original' ); ?>"><?php esc_html_e( 'Use original image size', 'motta-addons' ); ?></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'columns' ); ?>"><?php esc_html_e( 'Number of columns:', 'motta-addons' ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'columns' ); ?>" name="<?php echo $this->get_field_name( 'columns' ); ?>">
				<option value="2" <?php selected( 2, $instance['columns'] ) ?>><?php esc_attr_e( '2 Columns', 'motta-addons' ) ?></option>
				<option value="3" <?php selected( 3, $instance['columns'] ) ?>><?php esc_attr_e( '3 Columns', 'motta-addons' ) ?></option>
				<option value="4" <?php selected( 4, $instance['columns'] ) ?>><?php esc_attr_e( '4 Columns', 'motta-addons' ) ?></option>
			</select>
		</p>

		<?php
	}

	/**
	 * Display a single image
	 *
	 * @param array $media
	 * @param boolean $original
	 */
	public function display_image( $media, $original = false ) {

		if ( isset($media['images']['original']) ) {
			$srcset = array(
				$media['images']['thumbnail'] . ' 320w',
				$media['images']['original'] . ' 640w',
				$media['images']['original'] . ' 2x',
			);
		}

		$sizes  = array(
			'(max-width: 1400px) 320px',
			'320px',
		);
		$caption = is_array( $media['caption'] ) && isset( $media['caption']['text'] ) ? $media['caption']['text'] : $media['caption'];

		$image  = sprintf(
			'<img src="%s" alt="%s" srcset="%s" sizes="%s">',
			esc_url( $media['images']['thumbnail'] ),
			esc_attr( $caption ),
			isset($media['images']['original']) ? esc_attr( implode( ', ', $srcset ) ) : '',
			esc_attr( implode( ', ', $sizes ) )
		);

		$style = '';

		if ( ! $original ) {
			$style = 'style="background-image: url(' . esc_url( $media['images']['thumbnail'] ) . ')"';
		}

		printf(
			'<li class="motta-instagram__item"><a href="%s" target="_blank" rel="nofollow" %s>%s</a></li>',
			esc_url( $media['link'] ),
			$style,
			$image
		);
	}
}

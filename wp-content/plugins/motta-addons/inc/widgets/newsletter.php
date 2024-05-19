<?php
/**
 * Newsletter widget
 *
 * @package Motta
 */

namespace Motta\Addons\Widgets;

/**
 * Class Newsletter Widget
 */
class Newsletter_Widget extends \WP_Widget {
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
			'title'    => esc_html__( 'Newsletter', 'motta-addons' ),
			'desc'    => '',
			'form'  => '',
		);

		parent::__construct(
			'motta-newsletter-widget',
			esc_html__( 'Motta - Newsletter', 'motta-addons' ),
			array(
				'classname'                   => 'motta-newsletter-widget',
				'description'                 => esc_html__( 'Displays form newsletter', 'motta-addons' ),
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

		if( $instance['desc'] ) {
			echo '<div class="motta-newsletter__description">' . $instance['desc'] . '</div>';
		}

		if( $instance['form'] ) {
			echo '<div class="motta-newsletter__form">' . do_shortcode( '[mc4wp_form id="' . esc_attr( $instance['form'] ) . '"]' ) . '</div>';
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
		$new_instance['title']   = strip_tags( $new_instance['title'] );
		$new_instance['desc']    = wp_kses_post( $new_instance['desc'] );
		$new_instance['form']    = intval( $new_instance['form'] );

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
			<label for="<?php echo esc_attr( $this->get_field_id( 'desc' ) ); ?>"><?php esc_html_e( 'Description', 'motta-addons' ); ?></label>
			<textarea id="<?php echo esc_attr( $this->get_field_id( 'desc' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'desc' ) ); ?>" class="widefat" rows="5"><?php echo esc_textarea( $instance['desc'] ); ?></textarea>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'form' ); ?>"><?php esc_html_e( 'Mailchimp form:', 'motta-addons' ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'form' ); ?>" name="<?php echo $this->get_field_name( 'form' ); ?>">
				<?php
					$forms = $this->get_contact_form();
					foreach( $forms as $form => $index ) {
						echo sprintf( '<option value="%s" %s>%s</option>',
										$form,
										selected( $form, $instance['form'] ),
										$index
									);
					}
				?>
			</select>
		</p>

		<?php
	}

	/**
	 * Get Contact Form
	 */
	protected function get_contact_form() {
		$mail_forms    = get_posts( 'post_type=mc4wp-form&posts_per_page=-1' );
		$mail_form_ids = array(
			'' => esc_html__( 'Select Form', 'motta-addons' ),
		);
		foreach ( $mail_forms as $form ) {
			$mail_form_ids[$form->ID] = $form->post_title;
		}

		return $mail_form_ids;
	}
}

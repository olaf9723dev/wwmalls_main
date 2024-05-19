<?php
/**
 * User Bio widget
 *
 * @package Motta
 */

namespace Motta\Addons\Widgets;

use \Motta\Addons\Helper;

/**
 * Class User_Bio
 */
class User_Bio extends \WP_Widget {
	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @var array
	 */
	protected $default;

	/**
	 * List of supported socials
	 *
	 * @var array
	 */
	protected $socials;

	/**
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function __construct() {
		$this->default = array(
			'title' => esc_html__( 'About', 'motta-addons' ),
			'user' => '',
			'link' => '',
		);

		if( is_admin() ) {
			$this->hook_admin();
		}

		parent::__construct(
			'user-bio-widget',
			esc_html__( 'Motta - User Bio', 'motta-addons' ),
			array(
				'classname'                   => 'motta-widget__user-bio',
				'description'                 => esc_html__( 'Display content of about author.', 'motta-addons' ),
				'customize_selective_refresh' => true,
			)
		);
	}

	public function hook_admin() {
		add_action( 'show_user_profile', array( $this, 'motta_profile_fields' ), 5 );
		add_action( 'edit_user_profile', array( $this, 'motta_profile_fields' ), 5 );

		add_action( 'personal_options_update', array( $this, 'motta_update_profile_fields' ) );
		add_action( 'edit_user_profile_update', array( $this, 'motta_update_profile_fields' ) );
	}

	public function motta_profile_fields( $user ) {
		$job = get_the_author_meta( 'job', $user->ID );
		?>
		<table class="form-table">
			<tr>
				<th><label for="year_of_birth"><?php esc_html_e( 'Job', 'motta-addons' ); ?></label></th>
				<td>
					<input type="text" id="job" name="job" value="<?php echo esc_attr( $job ); ?>" class="regular-text" />
				</td>
			</tr>
		</table>
		<?php
	}

	public function motta_update_profile_fields( $user_id ) {
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		if ( ! empty( $_POST['job'] ) ) {
			update_user_meta( $user_id, 'job', $_POST['job'] );
		}
	}

	/**
	 * Outputs the HTML for this widget.
     *
	 * @since 1.0.0
	 *
	 * @param array $args     An array of standard parameters for widgets in this theme
	 * @param array $instance An array of settings for this widget instance
	 *
	 * @return void Echoes it's output
	 */
	function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->default );

		echo $args['before_widget'];

		if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		if( $instance['user'] ) {
			echo '<div class="user-bio-widget__header">';
				$user = get_user_meta( $instance['user'] );
				echo '<div class="user-bio-widget__content-avatar">' . get_avatar( $instance['user'] ) . '</div>';
				echo '<div class="user-bio-widget__content-name-social">';
					if( isset($user['nickname']) ) {
						echo '<div class="user-bio-widget__content-name">'.$user['nickname'][0].'</div>';
					}

					if( isset($user['job']) ) {
						echo '<div class="user-bio-widget__content-job">'.$user['job'][0].'</div>';
					}

					echo '<div class="user-bio-widget__content-social mt-socials">';
						if( isset($user['twitter']) ) {
							echo '<a class="mt-socials__item" href="'.esc_url($user['twitter'][0]).'">'. Helper::get_svg( 'twitter', '', 'social' ) .'</a>';
						}
						if( isset($user['facebook']) ) {
							echo '<a class="mt-socials__item" href="'.esc_url($user['facebook'][0]).'">'. Helper::get_svg( 'facebook', '', 'social_2' ) .'</a>';
						}
						if( isset($user['instagram'])) {
							echo '<a class="mt-socials__item" href="'.esc_url($user['instagram'][0]).'">'. Helper::get_svg( 'instagram', '', 'social' ) .'</a>';
						}
						if(isset( $user['pinterest']) ) {
							echo '<a class="mt-socials__item" href="'.esc_url($user['pinterest'][0]).'">'. Helper::get_svg( 'pinterest', '', 'social' ) .'</a>';
						}
					echo '</div>';
				echo '</div>';
			echo '</div>';
			if( isset($user['description'])) {
				echo '<div class="user-bio-widget__content"> ' . $user['description'][0] . '</div>';
			}
		}

		if( $instance['link'] ) {
			echo '<div class="user-bio-widget__footer"><a href="' . esc_url( $instance['link'] ) . '">' . esc_html__( 'About Me', 'motta-addons' ) . '</a></div>';
		}

		echo $args['after_widget'];
	}

	/**
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
     *
	 * @since 1.0.0
	 *
	 * @param array $instance
	 *
	 * @return string|void
	 */
	function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->default );
		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'motta-addons' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'user' ) ); ?>"><?php esc_html_e( 'User', 'motta-addons' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'user' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'user' ) ); ?>">
				<option value="">Select User</option>
				<?php
					$user_list = [];
					$users = get_users( array( 'fields' => array( 'ID' ) ) );
					foreach( $users as $user ) {
						$user_list[] = sprintf( '<option value="%s" %s>%s</option>', $user->ID, selected( $instance['user'], $user->ID ), get_user_meta( $user->ID )['nickname'][0]  );
					}
				?>
				<?php echo implode( '', $user_list ); ?>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php esc_html_e( 'Link', 'motta-addons' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link' ) ); ?>" value="<?php echo esc_attr( $instance['link'] ); ?>" />
		</p>

		<?php
	}
}

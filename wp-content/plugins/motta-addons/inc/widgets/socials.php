<?php
/**
 * Social links widget
 *
 * @package Motta
 */

namespace Motta\Addons\Widgets;
use \Motta\Addons\Helper;

/**
 * Class Social Links
 */
class Social_Links extends \WP_Widget {
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
	 * Constructor
	 */
	function __construct() {
		$socials = array(
			'twitter'     => esc_html__( 'Twitter', 'motta-addons' ),
			'facebook'    => esc_html__( 'Facebook', 'motta-addons' ),
			'pinterest'   => esc_html__( 'Pinterest', 'motta-addons' ),
			'tumblr'      => esc_html__( 'Tumblr', 'motta-addons' ),
			'tiktok'  	  => esc_html__( 'Tiktok', 'motta-addons' ),
			'linkedin'    => esc_html__( 'Linkedin', 'motta-addons' ),
			'flickr'      => esc_html__( 'Flickr', 'motta-addons' ),
			'instagram'   => esc_html__( 'Instagram', 'motta-addons' ),
			'youtube'     => esc_html__( 'Youtube', 'motta-addons' ),
			'vimeo'       => esc_html__( 'Vimeo', 'motta-addons' ),
			'google-plus' => esc_html__( 'Google Plus', 'motta-addons' ),
			'blogger' 	  => esc_html__( 'Blogger', 'motta-addons' ),
			'dribbble'    => esc_html__( 'Dribbble', 'motta-addons' ),
			'behance'     => esc_html__( 'Behance', 'motta-addons' ),
			'stumbleupon' => esc_html__( 'StumbleUpon', 'motta-addons' ),
			'github'      => esc_html__( 'Github', 'motta-addons' ),
			'houzz'       => esc_html__( 'Houzz', 'motta-addons' ),
			'rss'         => esc_html__( 'RSS', 'motta-addons' ),
		);

		$this->socials = apply_filters( 'motta_social_media', $socials );
		$this->default = array(
			'title' => '',
		);

		foreach ( $this->socials as $k => $v ) {
			$this->default["{$k}_title"] = $v;
			$this->default["{$k}_url"]   = '';
		}

		parent::__construct(
			'social-links-widget',
			esc_html__( 'Motta - Social Links', 'motta-addons' ),
			array(
				'classname'                   => 'social-links-widget',
				'description'                 => esc_html__( 'Display links to social media networks.', 'motta-addons' ),
				'customize_selective_refresh' => true,
			),
			array( 'width' => 600 )
		);
	}

	/**
	 * Outputs the HTML for this widget.
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

		echo '<div class="social-links">';

		foreach ( $this->socials as $social => $label ) {
			if ( empty( $instance[ $social . '_url' ] ) ) {
				continue;
			}

			$icon = $social;

			printf(
				'<a href="%s" class="mt-socials--bg mt-socials--%s social" rel="nofollow" title="%s" data-toggle="tooltip" data-placement="top" target="_blank">%s</a>',
				esc_url( $instance[ $social . '_url' ] ),
				esc_attr( $social ),
				esc_attr( $instance[ $social . '_title' ] ),
				Helper::get_svg( $icon, 'social', $icon)
			);
		}

		echo '</div>';

		echo $args['after_widget'];
	}

	/**
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
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

		<?php
		foreach ( $this->socials as $social => $label ) {
			printf(
				'<div style="width: 280px; float: left; margin-right: 10px;">
					<label>%s</label>
					<p><input type="text" class="widefat" name="%s" placeholder="%s" value="%s"></p>
				</div>',
				$label,
				$this->get_field_name( $social . '_url' ),
				esc_html__( 'URL', 'motta-addons' ),
				$instance[ $social . '_url' ]
			);
		}
	}
}

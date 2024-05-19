<?php
/**
 * Register footer builder
 */

namespace Motta\Addons;

class Theme_Settings {

	/**
	 * Instance
	 *
	 * @var $instance
	 */
	private static $instance;


	/**
	 * Initiator
	 *
	 * @since 1.0.0
	 * @return object
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_admin_menu' ), 50 );
		add_action('admin_init', array($this,'register_settings'));
	}

	public function theme_settings_page() {
		echo '<form action="options.php" method="post">';
		settings_fields("theme_settings");
		do_settings_sections( 'theme_settings' );
		submit_button();
		echo '</form>';
	}

	public function register_settings() {
		add_settings_section(
			'help_center_section',
			esc_html__( 'Help Center', 'motta-addons' ),
			'',
			'theme_settings'
		);
		register_setting(
			'theme_settings',
			'help_center_disable',
			'string'
		);
		add_settings_field(
			'help_center_disable',
			esc_html__( 'Help Center Disable', 'motta-addons' ),
			array($this,'help_center_disable_field_html'),
			'theme_settings',
			'help_center_section'
		);

		register_setting(
			'theme_settings',
			'help_center_page_id',
			'string'
		);
		add_settings_field(
			'help_center_page_id',
			esc_html__( 'Help Center Page', 'motta-addons' ),
			array($this,'help_center_page_field_html'),
			'theme_settings',
			'help_center_section'
		);

		register_setting(
			'theme_settings',
			'motta_popup_disable',
			'intval'
		);

		add_settings_section(
			'popup_section',
			'',
			array($this, 'popup_section_html'),
			'theme_settings'
		);

		add_settings_field(
			'popup_disable',
			esc_html__( 'Popup Disable', 'motta-addons' ),
			array($this,'popup_disable_field_html'),
			'theme_settings',
			'popup_section'
		);

	}

	public function register_admin_menu() {
		add_submenu_page(
			'themes.php',
			esc_html__( 'Theme Settings', 'motta-addons' ),
			esc_html__( 'Theme Settings', 'motta-addons' ),
			'manage_options',
			'theme_settings',
			array($this, 'theme_settings_page')
		);

	 }

	public function help_center_disable_field_html() {
		$hc_default = 1;
		$hc_disable = get_option( 'help_center_disable' );
		?>
		<input type="checkbox" name="help_center_disable" <?php checked( $hc_default, $hc_disable ); ?> value="<?php echo $hc_default; ?>">
		<label><?php esc_html_e('Yes', 'motta-addons'); ?></label>
		<?php
	}

	public function help_center_page_field_html() {
		$page_id = get_option( 'help_center_page_id' );
		$page_id = ! empty ( $page_id ) ? $page_id : '';
		wp_dropdown_pages( array(
			'selected'          => $page_id,
			'name'              => 'help_center_page_id',
			'show_option_none'  => esc_html__( '&mdash; Select &mdash;', 'motta-addons' ),
			'option_none_value' => 0,
		) );
	}

	public function popup_section_html() {
		echo '<hr/><h2>' . esc_html__('Popup', 'motta-addons') . '</h2>';
	}

	public function popup_disable_field_html() {
		$default = 1;
		$disable = get_option( 'motta_popup_disable' );
		?>
		<input type="checkbox" name="motta_popup_disable" <?php checked( $default, $disable ); ?> value="<?php echo $default; ?>">
		<label><?php esc_html_e('Yes', 'motta-addons'); ?></label>
		<?php
	}
}
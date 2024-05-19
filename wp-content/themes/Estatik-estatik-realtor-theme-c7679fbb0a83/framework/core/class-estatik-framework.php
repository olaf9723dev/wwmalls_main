<?php

/**
 * Class Estatik_Framework.
 */
class Estatik_Framework {

	/**
	 * @var Estatik_Framework_Fields_Factory
	 */
	protected $_fields_factory;

	/**
	 * @var Estatik_Framework_Views_Factory
	 */
	protected $_views_factory;

	/**
	 * Framework instance.
	 *
	 * @var static
	 */
	protected static $_instance;

	/**
	 * @var array
	 */
	public $args = array();

	/**
	 * Return framework instance.
	 *
	 * @return Estatik_Framework|static
	 */
	public static function get_instance( $args = array() ) {

		if ( ! static::$_instance instanceof static ) {
			static::$_instance = new static( $args );
			static::$_instance->init();
		}

		return static::$_instance;
	}

	/**
	 * Estatik_Framework constructor.
	 *
	 * @param array $args
	 */
	protected function __construct( $args = array() ) {

		$this->args = wp_parse_args( $args, array(
			'option_name' => 'estatik_theme_options',
		) );
	}

	/**
	 * Initialize framework.
	 *
	 * @return void
	 */
	protected function init() {

		require_once 'inc/class-options-container.php';
		require_once 'inc/views/class-view.php';
		require_once 'inc/fields/class-base-input.php';
		require_once 'inc/views/class-tab-view.php';
		require_once 'inc/views/class-form-view.php';
		require_once 'inc/views/class-section-view.php';
		require_once 'inc/fields/class-radio-input.php';
		require_once 'inc/fields/class-radio-image-input.php';
		require_once 'inc/fields/class-textarea-input.php';
		require_once 'inc/fields/class-selectbox-input.php';
		require_once 'inc/fields/class-media-input.php';
		require_once 'inc/class-fields-factory.php';
		require_once 'inc/class-views-factory.php';

		if ( class_exists( 'SiteOrigin_Widget' ) ) {
			require_once 'inc/widgets/abstract/class-property-query-widget.php';
		}

		$this->_fields_factory = new Estatik_Framework_Fields_Factory( $this );
		$this->_views_factory = new Estatik_Framework_Views_Factory( $this );

		add_action( 'after_setup_theme', array( $this, 'set_default_settings' ) );
		add_filter( 'siteorigin_widgets_field_class_prefixes', array( $this, 'siteorigin_fields_class_prefixes' ) );
		add_filter( 'siteorigin_widgets_field_class_paths', array( $this, 'siteorigin_fields_class_paths' ) );
	}

	/**
	 * @param $class_paths
	 *
	 * @return array
	 */
	function siteorigin_fields_class_paths( $class_paths ) {

		$class_paths[] = dirname( __FILE__ ) . '/inc/siteorigin/fields/';

		return $class_paths;
	}

	/**
	 * @param $class_prefixes
	 *
	 * @return array
	 */
	public function siteorigin_fields_class_prefixes( $class_prefixes ) {

		$class_prefixes[] = 'Estatik_Framework_Field_';

		return $class_prefixes;
	}

	/**
	 * Save default theme settings.
	 *
	 * @return void
	 */
	public function set_default_settings() {

		if ( ! empty( $this->args['options'] ) && ! get_option( $this->args['option_name'] ) ) {
			foreach ( $this->args['options'] as $key => $settings ) {
				if ( isset( $settings['default_value'] ) ) {
					$options[ $key ] = $settings['default_value'];
				}
			}

			if ( ! empty( $options ) ) {
				update_option( $this->args['option_name'], $options );
			}
		}
	}

	/**
	 * @return void
	 */
	public function enqueue_scripts() {

		wp_register_style( 'ef-select2-style', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/css/select2.min.css' );
		wp_register_script( 'ef-select2-script', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/select2.min.js', array( 'jquery' ) );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'ef-tabs', get_template_directory_uri() . '/framework/assets/js/tabs.jquery.js', array( 'jquery' ) );
		wp_register_script( 'ef-tooltipster-script', get_template_directory_uri() . '/framework/assets/js/tooltipster.bundle.min.js', array ( 'jquery' ) );
		wp_enqueue_script( 'ef-framework', get_template_directory_uri() . '/framework/assets/js/framework.jquery.js', array( 'jquery', 'ef-tooltipster-script', 'wp-color-picker', 'ef-select2-script' ) );
		wp_register_style( 'ef-lineawesome', 'https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome-font-awesome.min.css' );

		wp_enqueue_style( 'ef-tooltipster-style', get_template_directory_uri() . '/framework/assets/css/tooltipster.bundle.min.css' );
		wp_enqueue_style( 'ef-framework-style', get_template_directory_uri() . '/framework/assets/css/framework.css' );

		wp_localize_script( 'ef-framework', 'Estatik_Framework', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'security_nonce' => wp_create_nonce( 'es_admin_nonce' ),
		) );
	}

	/**
	 * Return views factory.
	 *
	 * @return Estatik_Framework_Views_Factory
	 */
	public function views() {

		return $this->_views_factory;
	}

	/**
	 * @return mixed|null
	 */
	public function get_version() {

		return ! empty( $this->args['version'] ) ? $this->args['version'] : null;
	}

	/**
	 * @return mixed|null
	 */
	public function get_logo_url() {

		return ! empty( $this->args['logo'] ) ? $this->args['logo'] : null;
	}

	/**
	 * @return mixed|null
	 */
	public function get_theme_name() {

		return ! empty( $this->args['theme_name'] ) ? $this->args['theme_name'] : null;
	}

	/**
	 * Return fields factory.
	 *
	 * @return Estatik_Framework_Fields_Factory
	 */
	public function fields() {

		return $this->_fields_factory;
	}

	/**
	 * Return options container.
	 *
	 * @param null $container
	 *
	 * @return Estatik_Framework_Options_Container
	 */
	public function options( $container = null ) {

		$container = ! $container ? $this->args['option_name'] : $container;
		return new Estatik_Framework_Options_Container( $container, $this->args['options'] );
	}

	/**
	 * Hide clone magic method.
	 */
	private function __clone() {}
}
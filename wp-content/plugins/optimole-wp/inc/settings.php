<?php

/**
 * Class Optml_Settings.
 */
class Optml_Settings {
	use Optml_Normalizer;

	const FILTER_EXT = 'extension';
	const FILTER_URL = 'page_url';
	const FILTER_URL_MATCH = 'page_url_match';
	const FILTER_FILENAME = 'filename';
	const FILTER_CLASS = 'class';
	const FILTER_TYPE_LAZYLOAD = 'lazyload';
	const FILTER_TYPE_OPTIMIZE = 'optimize';
	const OPTML_USER_EMAIL = 'optml_user_email';
	/**
	 * Holds an array of possible settings to alter via wp cli or wp-config constants.
	 *
	 * @var array Whitelisted settings.
	 */
	public static $whitelisted_settings = [
		'image_replacer'       => 'bool',
		'quality'              => 'int',
		'lazyload'             => 'bool',
		'lazyload_placeholder' => 'bool',
		'network_optimization' => 'bool',
		'autoquality'          => 'bool',
		'img_to_video'         => 'bool',
		'resize_smart'         => 'bool',
		'retina_images'        => 'bool',
		'native_lazyload'      => 'bool',
		'video_lazyload'       => 'bool',
		'bg_replacer'          => 'bool',
		'scale'                => 'bool',
		'cdn'                  => 'bool',
	];
	/**
	 * Holds the status of the auto connect hook.
	 *
	 * @var boolean Whether or not the auto connect action is hooked.
	 */
	private static $auto_connect_hooked = false;

	/**
	 * Default settings schema.
	 *
	 * @var array Settings schema.
	 */
	private $default_schema = [
		'api_key'                    => '',
		'service_data'               => '',
		'cache_buster'               => '',
		'cache_buster_assets'        => '',
		'cache_buster_images'        => '',
		'cdn'                        => 'disabled',
		'admin_bar_item'             => 'enabled',
		'lazyload'                   => 'disabled',
		'scale'                      => 'disabled',
		'network_optimization'       => 'disabled',
		'lazyload_placeholder'       => 'enabled',
		'bg_replacer'                => 'enabled',
		'video_lazyload'             => 'enabled',
		'retina_images'              => 'disabled',
		'limit_dimensions'           => 'enabled',
		'limit_height'               => 1080,
		'limit_width'                => 1920,
		'resize_smart'               => 'disabled',
		'no_script'                  => 'disabled',
		'filters'                    => [],
		'cloud_sites'                => [ 'all' => 'true' ],
		'watchers'                   => '',
		'quality'                    => 'auto',
		'wm_id'                      => - 1,
		'wm_opacity'                 => 1,
		'wm_position'                => Optml_Resize::GRAVITY_SOUTH_EAST,
		'wm_x'                       => 0,
		'wm_y'                       => 0,
		'wm_scale'                   => 0,
		'image_replacer'             => 'enabled',
		'img_to_video'               => 'disabled',
		'css_minify'                 => 'enabled',
		'js_minify'                  => 'disabled',
		'report_script'              => 'disabled',
		'avif'                       => 'enabled',
		'autoquality'                => 'enabled',
		'native_lazyload'            => 'disabled',
		'offload_media'              => 'disabled',
		'transfer_status'            => 'disabled',
		'cloud_images'               => 'enabled',
		'strip_metadata'             => 'enabled',
		'skip_lazyload_images'       => 3,
		'defined_image_sizes'        => [],
		'banner_frontend'            => 'disabled',
		'offloading_status'          => 'disabled',
		'rollback_status'            => 'disabled',
		'best_format'                => 'enabled',
		'offload_limit_reached'      => 'disabled',
		'offload_limit'              => 50000,
		'placeholder_color'          => '',
		'show_offload_finish_notice' => '',
	];
	/**
	 * Option key.
	 *
	 * @var string Option name.
	 */
	private $namespace;
	/**
	 * Holds all options from db.
	 *
	 * @var array All options.
	 */
	private $options;

	/**
	 * Optml_Settings constructor.
	 */
	public function __construct() {
		$this->default_schema['cloud_sites'] = $this->get_cloud_sites_whitelist_default();

		$this->namespace      = OPTML_NAMESPACE . '_settings';
		$this->default_schema = apply_filters( 'optml_default_settings', $this->default_schema );
		$this->options        = wp_parse_args( get_option( $this->namespace, $this->default_schema ), $this->default_schema );

		if ( defined( 'OPTIML_ENABLED_MU' ) && defined( 'OPTIML_MU_SITE_ID' ) && $this->to_boolean( constant( 'OPTIML_ENABLED_MU' ) ) && constant( 'OPTIML_MU_SITE_ID' ) ) {
			switch_to_blog( constant( 'OPTIML_MU_SITE_ID' ) );
			$this->options = wp_parse_args( get_option( $this->namespace, $this->default_schema ), $this->default_schema );
			restore_current_blog();
		}

		if ( defined( 'OPTIML_USE_ENV' ) && constant( 'OPTIML_USE_ENV' ) && $this->to_boolean( constant( 'OPTIML_USE_ENV' ) ) ) {

			if ( defined( 'OPTIML_API_KEY' )
				 && constant( 'OPTIML_API_KEY' ) !== ''
			) {
				if ( ! $this->is_connected() && ! self::$auto_connect_hooked ) {
					self::$auto_connect_hooked = true;
					add_action(
						'plugins_loaded',
						[ $this, 'auto_connect' ]
					);
				}
			}

			foreach ( self::$whitelisted_settings as $key => $type ) {
				$env_key = 'OPTIML_' . strtoupper( $key );
				if ( defined( $env_key ) && constant( $env_key ) ) {
					$value = constant( $env_key );
					if ( $type === 'bool' && ( (string) $value === '' || ! in_array(
						$value,
						[
							'on',
							'off',
						],
						true
					) ) ) {
						continue;
					}

					if ( $type === 'int' && ( (string) $value === '' || (int) $value > 100 || (int) $value < 0 ) ) {
						continue;
					}
					$sanitized_value       = ( $type === 'bool' ) ? ( $value === 'on' ? 'enabled' : 'disabled' ) : (int) $value;
					$this->options[ $key ] = $sanitized_value;
				}
			}
		}

		add_action( 'init', [ $this, 'register_settings' ] );
	}

	/**
	 * Check if the user is connected to Optimole.
	 *
	 * @return bool Connection status.
	 */
	public function is_connected() {
		$service_data = $this->get( 'service_data' );
		if ( ! isset( $service_data['cdn_key'] ) ) {
			return false;
		}
		if ( empty( $service_data ['cdn_key'] ) || empty( $service_data['cdn_secret'] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Get setting value by key.
	 *
	 * @param string $key Key to search against.
	 *
	 * @return mixed|null Setting value.
	 */
	public function get( $key ) {
		if ( ! $this->is_allowed( $key ) ) {
			return null;
		}

		return isset( $this->options[ $key ] ) ? $this->options[ $key ] : '';
	}

	/**
	 * Check if key is allowed.
	 *
	 * @param string $key Is key allowed or not.
	 *
	 * @return bool Is key allowed or not.
	 */
	private function is_allowed( $key ) {
		return isset( $this->default_schema[ $key ] );
	}

	/**
	 * Auto connect action.
	 */
	public function auto_connect() {
		$request = new WP_REST_Request( 'POST' );
		$request->set_param( 'api_key', constant( 'OPTIML_API_KEY' ) );
		Optml_Main::instance()->rest->connect( $request );

		remove_action( 'plugins_loaded', [ $this, 'auto_connect' ] );
		self::$auto_connect_hooked = false;
	}

	/**
	 * Process settings.
	 *
	 * @param array $new_settings List of settings.
	 *
	 * @return array
	 */
	public function parse_settings( $new_settings ) {
		$sanitized = [];
		foreach ( $new_settings as $key => $value ) {
			switch ( $key ) {
				case 'admin_bar_item':
				case 'lazyload':
				case 'scale':
				case 'image_replacer':
				case 'cdn':
				case 'network_optimization':
				case 'lazyload_placeholder':
				case 'retina_images':
				case 'limit_dimensions':
				case 'resize_smart':
				case 'bg_replacer':
				case 'video_lazyload':
				case 'report_script':
				case 'avif':
				case 'offload_media':
				case 'cloud_images':
				case 'autoquality':
				case 'img_to_video':
				case 'css_minify':
				case 'js_minify':
				case 'native_lazyload':
				case 'strip_metadata':
				case 'no_script':
				case 'banner_frontend':
				case 'offloading_status':
				case 'rollback_status':
				case 'best_format':
				case 'offload_limit_reached':
					$sanitized_value = $this->to_map_values( $value, [ 'enabled', 'disabled' ], 'enabled' );
					break;
				case 'offload_limit':
					$sanitized_value = absint( $value );
					break;
				case 'limit_height':
				case 'limit_width':
					$sanitized_value = $this->to_bound_integer( $value, 100, 5000 );
					break;
				case 'quality':
					$sanitized_value = $this->to_bound_integer( $value, 1, 100 );
					break;
				case 'wm_id':
					$sanitized_value = intval( $value );
					break;
				case 'show_offload_finish_notice':
					$sanitized_value = $this->to_map_values( $value, [ 'offload', 'rollback' ], '' );
					break;
				case 'cache_buster_assets':
				case 'cache_buster_images':
				case 'cache_buster':
					$sanitized_value = is_string( $value ) ? sanitize_text_field( $value ) : '';
					break;
				case 'cloud_sites':
					$current_sites   = $this->get( 'cloud_sites' );
					$sanitized_value = array_replace_recursive( $current_sites, $value );
					if ( isset( $value['all'] ) && $value['all'] === 'true' ) {
						$sanitized_value = [ 'all' => 'true' ];
					}
					break;
				case 'defined_image_sizes':
					$current_sizes = $this->get( 'defined_image_sizes' );
					foreach ( $value as $size_name => $size_value ) {
						if ( $size_value === 'remove' ) {
							unset( $current_sizes[ $size_name ] );
							unset( $value[ $size_name ] );
						}
					}
					$sanitized_value = array_replace_recursive( $current_sizes, $value );
					break;
				case 'filters':
					$current_filters = $this->get_filters();
					$sanitized_value = array_replace_recursive( $current_filters, $value );
					// Remove falsy vars.
					foreach ( $sanitized_value as $filter_type => $filter_values ) {
						foreach ( $filter_values as $filter_rule_type => $filter_rules_value ) {
							$sanitized_value[ $filter_type ][ $filter_rule_type ] = array_filter(
								$filter_rules_value,
								function ( $value ) {
									return ( $value !== 'false' && $value !== false );
								}
							);
						}
					}
					break;
				case 'watchers':
				case 'placeholder_color':
				case 'transfer_status':
					$sanitized_value = sanitize_text_field( $value );
					break;
				case 'skip_lazyload_images':
					$sanitized_value = $this->to_bound_integer( $value, 0, 100 );
					break;
				case 'wm_opacity':
				case 'wm_scale':
				case 'wm_x':
				case 'wm_y':
					$sanitized_value = floatval( $value );
					break;
				case 'wm_position':
					$sanitized_value = $this->to_map_values(
						$value,
						[
							Optml_Resize::GRAVITY_NORTH,
							Optml_Resize::GRAVITY_NORTH_EAST,
							Optml_Resize::GRAVITY_NORTH_WEST,
							Optml_Resize::GRAVITY_CENTER,
							Optml_Resize::GRAVITY_EAST,
							Optml_Resize::GRAVITY_WEST,
							Optml_Resize::GRAVITY_SOUTH_EAST,
							Optml_Resize::GRAVITY_SOUTH,
							Optml_Resize::GRAVITY_SOUTH_WEST,
						],
						Optml_Resize::GRAVITY_SOUTH_EAST
					);
					break;
				default:
					$sanitized_value = '';
					break;

			}

			$sanitized[ $key ] = $sanitized_value;
			$this->update( $key, $sanitized_value );
		}

		return $sanitized;
	}

	/**
	 * Return filter definitions.
	 *
	 * @return mixed|null Filter values.
	 */
	public function get_filters() {

		$filters = $this->get( 'filters' );
		if ( ! isset( $filters[ self::FILTER_TYPE_LAZYLOAD ] ) ) {
			$filters[ self::FILTER_TYPE_LAZYLOAD ] = [];
		}
		if ( ! isset( $filters[ self::FILTER_TYPE_OPTIMIZE ] ) ) {
			$filters[ self::FILTER_TYPE_OPTIMIZE ] = [];
		}
		foreach ( $filters as $filter_key => $filter_rules ) {
			if ( ! isset( $filter_rules[ self::FILTER_EXT ] ) ) {
				$filters[ $filter_key ][ self::FILTER_EXT ] = [];
			}
			if ( ! isset( $filter_rules[ self::FILTER_FILENAME ] ) ) {
				$filters[ $filter_key ][ self::FILTER_FILENAME ] = [];
			}
			if ( ! isset( $filter_rules[ self::FILTER_URL ] ) ) {
				$filters[ $filter_key ][ self::FILTER_URL ] = [];
			}
			if ( ! isset( $filter_rules[ self::FILTER_URL_MATCH ] ) ) {
				$filters[ $filter_key ][ self::FILTER_URL_MATCH ] = [];
			}
			if ( ! isset( $filter_rules[ self::FILTER_CLASS ] ) ) {
				$filters[ $filter_key ][ self::FILTER_CLASS ] = [];
			}
		}

		return $filters;
	}

	/**
	 * Update frontend banner setting from remote.
	 *
	 * @param bool $value Value.
	 *
	 * @return bool
	 */
	public function update_frontend_banner_from_remote( $value ) {
		if ( ! $this->is_main_mu_site() ) {
			return false;
		}

		$opts                    = $this->options;
		$opts['banner_frontend'] = $value ? 'enabled' : 'disabled';

		$update = update_option( $this->namespace, $opts, false );

		if ( $update ) {
			$this->options = $opts;
		}

		return $update;
	}

	/**
	 * Update settings.
	 *
	 * @param string $key Settings key.
	 * @param mixed  $value Settings value.
	 *
	 * @return bool Update result.
	 */
	public function update( $key, $value ) {
		if ( ! $this->is_allowed( $key ) ) {
			return false;
		}

		if ( ! $this->is_main_mu_site() ) {
			return false;
		}
		$opt = $this->options;

		if ( $key === 'banner_frontend' ) {
			$api          = new Optml_Api();
			$service_data = $this->get( 'service_data' );
			$application  = isset( $service_data['cdn_key'] ) ? $service_data['cdn_key'] : '';
			$response     = $api->update_extra_visits( $opt['api_key'], $value, $application );
		}

		$opt[ $key ] = $value;
		$update      = update_option( $this->namespace, $opt, false );
		if ( $update ) {
			$this->options = $opt;
		}
		if ( apply_filters( 'optml_dont_trigger_settings_updated', false ) === false ) {
			do_action( 'optml_settings_updated' );
		}

		return $update;
	}

	/**
	 * Check that we're on the main OPTML blog.
	 *
	 * @return bool
	 */
	private function is_main_mu_site() {
		// If we try to update from a website which is not the main OPTML blog, bail.
		if ( defined( 'OPTIML_ENABLED_MU' ) && constant( 'OPTIML_ENABLED_MU' ) && defined( 'OPTIML_MU_SITE_ID' ) && constant( 'OPTIML_MU_SITE_ID' ) &&
			 intval( constant( 'OPTIML_MU_SITE_ID' ) ) !== get_current_blog_id()
		) {
			return false;
		}

		return true;
	}

	/**
	 * Return site settings.
	 *
	 * @return array Site settings.
	 */
	public function get_site_settings() {
		$service_data = $this->get( 'service_data' );
		$whitelist    = [];
		if ( isset( $service_data['whitelist'] ) ) {
			$whitelist = $service_data['whitelist'];
		}

		return [
			'quality'                    => $this->get_quality(),
			'admin_bar_item'             => $this->get( 'admin_bar_item' ),
			'lazyload'                   => $this->get( 'lazyload' ),
			'network_optimization'       => $this->get( 'network_optimization' ),
			'retina_images'              => $this->get( 'retina_images' ),
			'limit_dimensions'           => $this->get( 'limit_dimensions' ),
			'limit_height'               => $this->get( 'limit_height' ),
			'limit_width'                => $this->get( 'limit_width' ),
			'lazyload_placeholder'       => $this->get( 'lazyload_placeholder' ),
			'skip_lazyload_images'       => $this->get( 'skip_lazyload_images' ),
			'bg_replacer'                => $this->get( 'bg_replacer' ),
			'video_lazyload'             => $this->get( 'video_lazyload' ),
			'resize_smart'               => $this->get( 'resize_smart' ),
			'no_script'                  => $this->get( 'no_script' ),
			'image_replacer'             => $this->get( 'image_replacer' ),
			'cdn'                        => $this->get( 'cdn' ),
			'filters'                    => $this->get_filters(),
			'cloud_sites'                => $this->get( 'cloud_sites' ),
			'defined_image_sizes'        => $this->get( 'defined_image_sizes' ),
			'watchers'                   => $this->get_watchers(),
			'watermark'                  => $this->get_watermark(),
			'img_to_video'               => $this->get( 'img_to_video' ),
			'scale'                      => $this->get( 'scale' ),
			'css_minify'                 => $this->get( 'css_minify' ),
			'js_minify'                  => $this->get( 'js_minify' ),
			'native_lazyload'            => $this->get( 'native_lazyload' ),
			'report_script'              => $this->get( 'report_script' ),
			'avif'                       => $this->get( 'avif' ),
			'autoquality'                => $this->get( 'autoquality' ),
			'offload_media'              => $this->get( 'offload_media' ),
			'cloud_images'               => $this->get( 'cloud_images' ),
			'strip_metadata'             => $this->get( 'strip_metadata' ),
			'whitelist_domains'          => $whitelist,
			'banner_frontend'            => $this->get( 'banner_frontend' ),
			'offloading_status'          => $this->get( 'offloading_status' ),
			'rollback_status'            => $this->get( 'rollback_status' ),
			'best_format'                => $this->get( 'best_format' ),
			'offload_limit_reached'      => $this->get( 'offload_limit_reached' ),
			'placeholder_color'          => $this->get( 'placeholder_color' ),
			'show_offload_finish_notice' => $this->get( 'show_offload_finish_notice' ),
		];
	}

	/**
	 * Return quality factor.
	 *
	 * @return string Quality factor.
	 */
	public function get_quality() {
		$quality = $this->get( 'quality' );
		if ( $this->get( 'autoquality' ) === 'enabled' ) {
			return 'mauto';
		}
		// Legacy compat.
		if ( $quality === 'low' ) {
			return 'high_c';
		}
		if ( $quality === 'medium' ) {
			return 'medium_c';
		}

		if ( $quality === 'high' ) {
			return 'low_c';
		}

		return $quality;
	}

	/**
	 * Return filter definitions.
	 *
	 * @return mixed|null Filter values.
	 */
	public function get_watchers() {

		return $this->get( 'watchers' );

	}

	/**
	 * Return an watermark array.
	 *
	 * @return array
	 */
	public function get_watermark() {
		return [
			'id'       => $this->get( 'wm_id' ),
			'opacity'  => $this->get( 'wm_opacity' ),
			'position' => $this->get( 'wm_position' ),
			'x_offset' => $this->get( 'wm_x' ),
			'y_offset' => $this->get( 'wm_y' ),
			'scale'    => $this->get( 'wm_scale' ),
		];
	}

	/**
	 * Check if smart cropping is enabled.
	 *
	 * @return bool Is smart cropping enabled.
	 */
	public function is_smart_cropping() {
		return $this->get( 'resize_smart' ) === 'enabled';
	}

	/**
	 * Check if best format is enabled.
	 *
	 * @return bool
	 */
	public function is_best_format() {
		return $this->get( 'best_format' ) === 'enabled';
	}

	/**
	 * Check if offload limit was reached.
	 *
	 * @return bool
	 */
	public function is_offload_limit_reached() {
		return $this->get( 'offload_limit_reached' ) === 'enabled';
	}

	/**
	 * Get numeric quality used by the service.
	 *
	 * @return int Numeric quality.
	 */
	public function get_numeric_quality() {
		$value = $this->get_quality();

		return (int) $this->to_accepted_quality( $value );
	}

	/**
	 * Check if replacer is enabled.
	 *
	 * @return bool Replacer enabled
	 */
	public function is_enabled() {
		$status       = $this->get( 'image_replacer' );
		$service_data = $this->get( 'service_data' );
		if ( empty( $service_data ) ) {
			return false;
		}
		if ( isset( $service_data['status'] ) && $service_data['status'] === 'inactive' ) {
			return false;
		}

		return $this->to_boolean( $status );
	}

	/**
	 * Check if lazyload is enabled.
	 *
	 * @return bool Lazyload enabled
	 */
	public function use_lazyload() {
		$status = $this->get( 'lazyload' );

		return $this->to_boolean( $status );
	}

	/**
	 * Check if replacer is enabled.
	 *
	 * @return bool Replacer enabled
	 */
	public function use_cdn() {
		$status = $this->get( 'cdn' );

		return $this->to_boolean( $status );
	}

	/**
	 * Return cdn url.
	 *
	 * @return string CDN url.
	 */
	public function get_cdn_url() {
		$service_data = $this->get( 'service_data' );
		if ( ! isset( $service_data['cdn_key'] ) ) {
			return '';
		}

		if ( isset( $service_data['is_cname_assigned'] ) && $service_data['is_cname_assigned'] === 'yes' && ! empty( $service_data['domain'] ) ) {
			return strtolower( $service_data['domain'] );
		}

		if ( defined( 'OPTML_CUSTOM_DOMAIN' ) && constant( 'OPTML_CUSTOM_DOMAIN' ) ) {
			return parse_url( strtolower( OPTML_CUSTOM_DOMAIN ), PHP_URL_HOST );
		}

		return sprintf(
			'%s.%s',
			strtolower( $service_data['cdn_key'] ),
			Optml_Config::$base_domain
		);
	}

	/**
	 * Reset options to defaults.
	 *
	 * @return bool Reset action status.
	 */
	public function reset() {
		$reset_schema            = $this->default_schema;
		$reset_schema['filters'] = $this->options['filters'];

		$update = update_option( $this->namespace, $reset_schema );
		if ( $update ) {
			$this->options = $reset_schema;
		}

		return $update;
	}

	/**
	 * Get raw settings value.
	 *
	 * @return  array
	 */
	public function get_raw_settings() {
		return get_option( $this->namespace, false );
	}

	/**
	 * Get settings for CSAT.
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting(
			'optml_settings',
			'optml_csat',
			[
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'show_in_rest'      => true,
				'default'           => '{}',
			]
		);
	}

	/**
	 * Clear cache.
	 *
	 * @param string $type Cache type.
	 *
	 * @return string|WP_Error
	 */
	public function clear_cache( $type = '' ) {
		$token        = $this->get( 'cache_buster' );
		$token_images = $this->get( 'cache_buster_images' );

		if ( ! empty( $token_images ) ) {
			$token = $token_images;
		}

		if ( ! empty( $type ) && $type === 'assets' ) {
			$token = $this->get( 'cache_buster_assets' );
		}

		$request = new Optml_Api();
		$data    = $request->get_cache_token( $token, $type );

		if ( $data === false || is_wp_error( $data ) || empty( $data ) || ! isset( $data['token'] ) ) {
			$extra = '';

			if ( is_wp_error( $data ) ) {
				/**
				 * Error from api.
				 *
				 * @var WP_Error $data Error object.
				 */
				$extra = sprintf( /* translators: Error details */ __( '. ERROR details: %s', 'optimole-wp' ), $data->get_error_message() );
			}

			return new WP_Error( 'optimole_cache_buster_error', __( 'Can not get new token from Optimole service', 'optimole-wp' ) . $extra );
		}

		if ( ! empty( $type ) && $type === 'assets' ) {
			set_transient( 'optml_cache_lock_assets', 'yes', 5 * MINUTE_IN_SECONDS );
			$this->update( 'cache_buster_assets', $data['token'] );
		} else {
			set_transient( 'optml_cache_lock', 'yes', 5 * MINUTE_IN_SECONDS );
			$this->update( 'cache_buster_images', $data['token'] );
		}

		return $data['token'];
	}

	/**
	 * Utility to check if offload is enabled.
	 *
	 * @return bool
	 */
	public function is_offload_enabled() {
		return $this->get( 'offload_media' ) === 'enabled' || $this->get( 'rollback_status' ) !== 'disabled';
	}

	/**
	 * Get cloud sites whitelist for current domain only.
	 *
	 * @return array
	 */
	public function get_cloud_sites_whitelist_default() {
		$site_url = get_site_url();

		$site_url = preg_replace( '/^https?:\/\//', '', $site_url );
		$site_url = trim( $site_url, '/' );

		return [
			'all'     => 'false',
			$site_url => 'true',
		];
	}
}

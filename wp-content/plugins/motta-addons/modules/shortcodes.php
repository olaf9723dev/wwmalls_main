<?php

namespace Motta\Addons\Modules;

/**
 * Class for shortcodes.
 */
class Shortcodes {
	/**
	 * Instance
	 *
	 * @var $instance
	 */
	protected static $instance = null;

	/**
	 * Initiator
	 *
	 * @since 1.0.0
	 * @return object
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Init shortcodes
	 */
	public static function init() {
		add_shortcode( 'motta_year', array( __CLASS__, 'year' ) );
		add_shortcode( 'motta_more', array( __CLASS__, 'motta_more_shortcode' ) );
		add_shortcode( 'motta_last_revised', array( __CLASS__, 'last_revised_date' ) );
	}

	/**
	 * Display current year
	 *
	 * @return void
	 */
	public static function year() {
		return date('Y');
	}

	/**
	 * Show more
	 *
	 * @return void
	 */
	public static function motta_more_shortcode( $args, $content ) {
		$default = array(
			'more'   => esc_html__( 'Show More', 'motta-addons' ),
			'less'   => esc_html__( 'Show Less', 'motta-addons' )
		);

		$atts = shortcode_atts( $default, $args );
   		$content = do_shortcode( $content );

		return sprintf(
			'<div class="motta-more" data-settings="%s">
				<div class="motta-more__content">%s</div>
				<button class="motta-more__button motta-button--subtle">%s</button>
			</div>',
			htmlspecialchars( json_encode( $default ) ),
			$content,
			$atts['more']
		);
	}

	/**
	 * Display last update date
	 *
	 * @return void
	 */
	public static function last_revised_date() {
		return get_the_date();
	}
}
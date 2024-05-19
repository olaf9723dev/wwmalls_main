<?php

namespace Motta\Addons\Modules\Popup;
use Elementor\Core\Files\CSS\Post as Post_CSS;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main class of plugin for admin
 */
class FrontEnd {

	/**
	 * Instance
	 *
	 * @var $instance
	 */
	private static $instance;

	/**
	 * Post IDs
	 *
	 * @var $post_ids
	 */
	private static $post_ids;

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

	const POST_TYPE     = 'motta_popup';

	/**
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'motta_after_site', array($this, 'popup' ) );

		add_action('motta_after_enqueue_style', array($this, 'popup_inline_style' ) );

	}

	/**
	 * Enqueue scripts
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'motta-popup', MOTTA_ADDONS_URL . 'modules/popup/assets/css/frontend.css', array(), '1.0.0' );
		wp_enqueue_script( 'motta-popup', MOTTA_ADDONS_URL . 'modules/popup/assets/js/frontend.js', array( 'jquery' ),'1.0', true );

	}

	/**
	 * Add the popup HTML to footer
	 *
	 * @since 2.0
	 */
	public function popup() {
		if( ! apply_filters( 'motta_get_popup', true ) ) {
			return;
		}

		if( is_singular('motta_popup') ) {
			return;
		}
		if( ! class_exists('\Elementor\Core\Settings\Manager') && ! method_exists('\Elementor\Core\Settings\Manager', 'get_settings_managers') ) {
			return;
		}

		$page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

		$popup_ids =(array) $this->get_popup_ids();

		if( empty($popup_ids) || empty($popup_ids[0]) ) {
			return;
		}

		foreach( $popup_ids as $popup_id) {
			// Get the settings model for current post
			$page_settings_model = $page_settings_manager->get_model( $popup_id );
			$page_settings = $page_settings_model->get_data( 'settings' );
			$frequency = isset($page_settings['popup_frequency']) ? $page_settings['popup_frequency'] : '1';

			$popup_cookie = !empty( $_COOKIE['motta_popup_' . $popup_id] ) ? $_COOKIE['motta_popup_' . $popup_id] : '';
			if( intval($frequency) > 0 && $popup_cookie ) {
				continue;
			}

			$post_content = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($popup_id);
			if( empty($post_content) ) {
				continue;
			}

			$data_options = array();

			$visible =  isset($page_settings['popup_visible']) ? $page_settings['popup_visible'] : 'loaded';

			$seconds =  isset($page_settings['popup_seconds']) ? $page_settings['popup_seconds'] : '5';

			$data_options['post_ID'] = $popup_id;
			$data_options['visiblle'] = $visible;
			$data_options['seconds'] = $seconds;
			$data_options['frequency'] = $frequency;

			$position =  isset($page_settings['popup_position']) ? $page_settings['popup_position'] : 'left-bottom';

			$hide_overlay =  isset($page_settings['hide_overlay']) ? $page_settings['hide_overlay'] : '';
			$hide_overlay =  isset($page_settings['hide_overlay_mobile']) ? $page_settings['hide_overlay_mobile'] : '';

			$css_classes = 'motta-popup';
			$css_classes .= ' motta-popup-' . $popup_id;
			$css_classes .= ' motta-popup-position--' .  $position;
			$css_classes .= ! empty( $hide_overlay ) ? ' hide-overlay' : '';
			$css_classes .= ! empty( $hide_overlay_mobile ) ? ' hide-overlay-mobile' : '';

			$html = '<div id="motta_popup_'. $popup_id .'" class="' . esc_attr( $css_classes ) . '" data-options="' . esc_attr(json_encode( $data_options )) . '">';
			$html .= '<div class="motta-popup__backdrop"></div>';
			$html .= '<div class="motta-popup__content">';
			$html .= \Motta\Addons\Helper::get_svg( 'close', 'ui', 'class=motta-popup__close' );
			$html .= $post_content;
			$html .= '</div>';
			$html .= '</div>';

			echo $html;
		}

	}

	/**
	 * Get product tab ids
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function get_popup_ids() {
		if( isset( self::$post_ids ) ) {
			return self::$post_ids;
		}
		$current_page = \Motta\Addons\Helper::get_post_ID();
		$posts = new \WP_Query( array(
			'post_type'      => self::POST_TYPE,
			'post_status'    => 'publish',
			'posts_per_page' => '10',
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'orderby' 		=> 'menu_order',
			'order' 		=> 'DESC',
			'suppress_filters'       => false,
			'meta_query' => array(
				'relation' => 'AND',
				array(
					array(
						'key' => 'enable_popup',
						'value' => 'yes',
						'compare' => '==',
					),
				),
				array(
					'relation' => 'OR',
					array(
						'key' => 'popup_include_pages',
						'value' => $current_page,
						'compare' => 'LIKE',
					),
					array(
						'key' => 'popup_include_pages',
						'value'   => array('0'),
						'compare' => 'IN',
					),
					array(
						'key' => 'popup_include_pages',
						'compare' => 'NOT EXISTS',
					)
				),
				array(
					'relation' => 'OR',
					array(
						'key' => 'popup_exclude_pages',
						'value' => $current_page,
						'compare' => 'NOT LIKE',
					),
					array(
						'key' => 'popup_exclude_pages',
						'compare' => 'NOT EXISTS',
					)
				),
			)
		) );
		wp_reset_postdata();
		self::$post_ids = $posts->posts;
		return self::$post_ids;
	}

	/**
	 * Enqueue styles and scripts.
	 */
	public function popup_inline_style() {
		if( ! apply_filters( 'motta_get_popup', true ) ) {
			return;
		}

		if( is_singular('motta_popup') ) {
			return;
		}

		if( ! class_exists('\Elementor\Core\Settings\Manager') && ! method_exists('\Elementor\Core\Settings\Manager', 'get_settings_managers') ) {
			return;
		}

		$page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

		$popup_ids = (array) $this->get_popup_ids();

		if( empty($popup_ids) || empty($popup_ids[0]) ) {
			return;
		}
		$css_content = '';
		foreach( $popup_ids as $popup_id) {
			// Get the settings model for current post
			$page_settings_model = $page_settings_manager->get_model( $popup_id );

			$page_settings = $page_settings_model->get_data( 'settings' );
			$frequency = isset($page_settings['popup_frequency']) ? $page_settings['popup_frequency'] : '1';

			$popup_cookie = !empty( $_COOKIE['motta_popup_' . $popup_id] ) ? $_COOKIE['motta_popup_' . $popup_id] : '';
			if( intval($frequency) > 0 && $popup_cookie ) {
				continue;
			}

			$width =  isset($page_settings['popup_width']) ? $page_settings['popup_width'] : '';
			$css_content .= ! empty( $width ) ? '.motta-popup-' . $popup_id . ' .motta-popup__content{max-width:' . $width['size'] . $width['unit'] . ';}' : '';

			$close_color =  isset($page_settings['popup_close_color']) ? $page_settings['popup_close_color'] : '';
			if( !empty($close_color ) ) {
				$css_content .=  '.motta-popup-' . $popup_id . ' .motta-popup__close{color:' . $close_color . ';}';
			}

			$css_file = '';
			if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
				$css_file = new \Elementor\Core\Files\CSS\Post( $popup_id );
			} elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
				$css_file = new \Elementor\Post_CSS_File( $popup_id );
			}

			if( $css_file ) {
				$css_file->enqueue();
			}

		}
		if( ! empty($css_content) ) {
			wp_add_inline_style( 'motta', $css_content );
		}

	}

}
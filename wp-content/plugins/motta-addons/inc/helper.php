<?php
/**
 * Motta Addons Helper init
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Motta
 */

namespace Motta\Addons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Helper
 */
class Helper {

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
	 * Get the sharing URL of a social
	 *
	 * @since 1.0.0
	 *
	 * @param string $social
	 *
	 * @return string
	 */
	public static function share_link( $social, $args ) {
		$url  = '';
		$text_default = apply_filters( 'motta_share_link_text', esc_html__( 'Share on', 'motta-addons' )  );
		if( empty($args[$social . '_title']) ) {
			$text =  $text_default . ' ' . ucfirst( $social );
		} else {
			$text = $args[$social . '_title'];
		}

		$icon = $social;

		switch ( $social ) {
			case 'facebook':
				$url = add_query_arg( array( 'u' => get_permalink() ), 'https://www.facebook.com/sharer.php' );
				break;

			case 'twitter':
				$url = add_query_arg( array( 'url' => get_permalink(), 'text' => get_the_title() ), 'https://twitter.com/intent/tweet' );
				break;

			case 'pinterest';
				$params         = array(
					'description' => get_the_title(),
					'media'       => get_the_post_thumbnail_url( null, 'full' ),
					'url'         => get_permalink(),
				);
				$url            = add_query_arg( $params, 'https://www.pinterest.com/pin/create/button/' );
				break;

			case 'googleplus':
				$url  = add_query_arg( array( 'url' => get_permalink() ), 'https://plus.google.com/share' );
				if( empty($args[$social . '_title']) ) {
					$text = $text_default . ' ' . esc_html__( 'Google+', 'motta-addons' );
				}
				$icon = 'google';
				break;

			case 'linkedin':
				$url = add_query_arg( array( 'url' => get_permalink(), 'title' => get_the_title() ), 'https://www.linkedin.com/shareArticle' );
				break;

			case 'tumblr':
				$url = add_query_arg( array( 'url' => get_permalink(), 'name' => get_the_title() ), 'https://www.tumblr.com/share/link' );
				break;

			case 'reddit':
				$url = add_query_arg( array( 'url' => get_permalink(), 'title' => get_the_title() ), 'https://reddit.com/submit' );
				break;

			case 'stumbleupon':
				$url = add_query_arg( array( 'url' => get_permalink(), 'title' => get_the_title() ), 'https://www.stumbleupon.com/submit' );
				if( empty($args[$social . '_title']) ) {
					$text = $text_default . ' ' . esc_html__( 'StumbleUpon', 'motta-addons' );
				}
				break;

			case 'telegram':
				$url = add_query_arg( array( 'url' => get_permalink() ), 'https://t.me/share/url' );
				break;

			case 'whatsapp':
				$params = array( 'text' => urlencode( get_permalink() ) );

				$url = 'https://wa.me/';

				if ( ! empty( $args['whatsapp_number'] ) ) {
					$url .= urlencode( $args['whatsapp_number'] );
				}

				$url = add_query_arg( $params, $url );
				break;

			case 'pocket':
				$url = add_query_arg( array( 'url' => get_permalink(), 'title' => get_the_title() ), 'https://getpocket.com/save' );
				if( empty($args[$social . '_title']) ) {
					$text = esc_html__( 'Save On Pocket', 'motta-addons' );
				}
				break;

			case 'digg':
				$url = add_query_arg( array( 'url' => get_permalink() ), 'https://digg.com/submit' );
				break;

			case 'vk':
				$url = add_query_arg( array( 'url' => get_permalink() ), 'https://vk.com/share.php' );
				break;

			case 'email':
				$url  = 'mailto:?subject=' . get_the_title() . '&body=' . __( 'Check out this site:', 'motta-addons' ) . ' ' . get_permalink();
				if( empty($args[$social . '_title']) ) {
					$text = esc_html__( 'Share Via Email', 'motta-addons' );
				}
				break;
		}

		if ( ! $url ) {
			return;
		}

		$icon = ( isset( $args[$icon]['icon'] ) && ! empty( $args[$icon]['icon'] ) ) ? $args[$icon]['icon'] : $icon;
		$class = ( isset( $args[$social]['class'] ) && ! empty( $args[$social]['class'] ) ) ? $args[$social]['class'] : '';
		if( empty ( $args[$social . '_icon_html'] )  ) {
			$icon = self::get_svg($icon, 'social', array( 'class' => $class ) );
		} else {
			$icon = '<span class="motta-svg-icon motta-svg-icon--twitter">' . $args[$social . '_icon_html'] . '</span>';
		}

		$repeat_class = ! empty ( $args['repeat_classes'] ) ? $args['repeat_classes'] : '';

		return sprintf(
			'<a href="%s" target="_blank" class="social-share-link mt-socials--bg mt-socials--%s %s">%s<span class="social-share__label">%s</span></a>',
			esc_url( $url ),
			esc_attr( $social ),
			esc_attr($repeat_class),
			$icon,
			$text
		);
	}

	/**
	 * Get Theme SVG.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function get_svg( $svg_name, $group = 'ui', $attr = array()  ) {
		if ( class_exists( '\Motta\Icon' ) && method_exists( '\Motta\Icon', 'get_svg' ) ) {
			return \Motta\Icon::get_svg( $svg_name, $group, $attr );
		}

		return '';
	}

		/**
	 * Get Theme SVG.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function sanitize_svg( $svg ) {
		if ( class_exists( '\Motta\Icon' ) && method_exists( '\Motta\Icon', 'sanitize_svg' ) ) {
			return \Motta\Icon::sanitize_svg( $svg );
		}

		return '';
	}

	/**
	 * Get is post ID
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function get_post_ID() {
		if ( class_exists( '\Motta\Helper' ) && method_exists( '\Motta\Helper', 'get_post_ID' ) ) {
			return \Motta\Helper::get_post_ID();
		}

		return '';
	}

	/**
	 * Get Posts
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function customizer_get_posts($args) {
		if ( class_exists( '\Motta\Helper' ) && method_exists( '\Motta\Helper', 'customizer_get_posts' ) ) {
			return \Motta\Helper::customizer_get_posts($args);
		}

		return '';
	}


	/**
	 * Get Instagram images
	 *
	 * @param int $limit
	 *
	 * @return array|WP_Error
	 */
	public static function motta_get_instagram_images( $limit = 12 ) {
		$access_token = \Motta\Helper::get_option( 'api_instagram_token' );

		if ( empty( $access_token ) ) {
			return new \WP_Error( 'instagram_no_access_token', esc_html__( 'No access token', 'motta-addons' ) );
		}

		$user = self::motta_get_instagram_user();

		if ( ! $user || is_wp_error( $user ) ) {
			return $user;
		}

		if ( isset( $user['error'] ) ) {
			return new \WP_Error( 'instagram_no_images', esc_html__( 'Instagram did not return any images. Please check your access token', 'motta-addons' ) );

		} else {
			$transient_key = 'motta_instagram_photos_' . sanitize_title_with_dashes( $user['username'] . '__' . $limit );
			$images = get_transient( $transient_key );
			$images = apply_filters( 'motta_get_instagram_photos', $images );

			if ( false === $images || empty( $images ) ) {
				$images = array();
				$next = false;

				while ( count( $images ) < $limit ) {
					if ( ! $next ) {
						$fetched = self::motta_fetch_instagram_media( $access_token );
					} else {
						$fetched = self::motta_fetch_instagram_media( $next );
					}

					if ( is_wp_error( $fetched ) ) {
						break;
					}

					$images = array_merge( $images, $fetched['images'] );
					$next = $fetched['paging'] ? $fetched['paging']['cursors']['after'] : false;

					if ( ! $next ) {
						break;
					}
				}

				if ( ! empty( $images ) ) {
					set_transient( $transient_key, $images, 2 * 3600 ); // Cache for 2 hours.
				}
			}

			if ( ! empty( $images ) ) {
				return $images;
			} else {
				return new \WP_Error( 'instagram_no_images', esc_html__( 'Instagram did not return any images.', 'motta-addons' ) );
			}
		}
	}

	/**
	 * Fetch photos from Instagram API
	 *
	 * @param  string $access_token
	 * @return array
	 */
	public static function motta_fetch_instagram_media( $access_token ) {
		$url = add_query_arg( array(
			'fields'       => 'id,caption,media_type,media_url,permalink,thumbnail_url',
			'access_token' => $access_token,
		), 'https://graph.instagram.com/me/media' );

		$remote = wp_remote_retrieve_body( wp_remote_get( $url ) );
		$data   = json_decode( $remote, true );
		$images = array();

		if ( isset( $data['error'] ) ) {
			return new \WP_Error( 'instagram_error', $data['error']['message'] );
		} else {
			foreach ( $data['data'] as $media ) {
				$images[] = array(
					'type'    => $media['media_type'],
					'caption' => isset( $media['caption'] ) ? $media['caption'] : $media['id'],
					'link'    => $media['permalink'],
					'images'  => array(
						'thumbnail' => ! empty( $media['thumbnail_url'] ) ? $media['thumbnail_url'] : $media['media_url'],
						'original'  => $media['media_url'],
					),
				);
			}
		}

		return array(
			'images' => $images,
			'paging' => isset( $data['paging'] ) ? $data['paging'] : false,
		);
	}

	/**
	 * Get user data
	 *
	 * @return bool|WP_Error|array
	 */
	public static function motta_get_instagram_user() {
		$access_token = \Motta\Helper::get_option( 'api_instagram_token' );

		if ( empty( $access_token ) ) {
			return new \WP_Error( 'no_access_token', esc_html__( 'No access token', 'motta-addons' ) );
		}

		$transient_key = 'motta_instagram_user_' . $access_token;

		$user = get_transient( $transient_key );

		$user = apply_filters( 'motta_get_instagram_user', $user );

		if ( false === $user ) {
			$url  = add_query_arg( array( 'fields' => 'id,username', 'access_token' => $access_token ), 'https://graph.instagram.com/me' );
			$data = wp_remote_get( $url );
			$data = wp_remote_retrieve_body( $data );

			if ( ! $data ) {
				return new \WP_Error( 'no_user_data', esc_html__( 'No user data received', 'motta-addons' ) );
			}

			$user = json_decode( $data, true );

			if ( ! empty( $data ) ) {
				set_transient( $transient_key, $user, 2592000 ); // Cache for one month.
			}
		}

		return $user;
	}

	/**
	 * Refresh Instagram Access Token
	 */
	public static function motta_refresh_instagram_access_token() {
		$access_token = \Motta\Helper::get_option( 'api_instagram_token' );

		if ( empty( $access_token ) ) {
			return new \WP_Error( 'no_access_token', esc_html__( 'No access token', 'motta-addons' ) );
		}

		$data = wp_remote_get( 'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=' . $access_token );
		$data = wp_remote_retrieve_body( $data );
		$data = json_decode( $data, true );

		if ( isset( $data['error'] ) ) {
			return new \WP_Error( 'access_token_refresh', $data['error']['message'] );
		}

		$new_access_token = $data['access_token'];

		set_theme_mod( 'api_instagram_token', $new_access_token );

		return $new_access_token;
	}

	/**
	 * Get the output of an Instagram photo
	 */
	public static function motta_instagram_image( $media, $size = 'original' ) {
		if ( ! is_array( $media ) ) {
			return;
		}

		$srcset = array(
			$media['images']['thumbnail'] . ' 320w',
			$media['images']['original'] . ' 640w',
			$media['images']['original'] . ' 2x',
		);
		$sizes  = array(
			'(max-width: 1400px) 320px',
			'320px',
		);
		$caption = is_array( $media['caption'] ) && isset( $media['caption']['text'] ) ? $media['caption']['text'] : $media['caption'];

		$image  = sprintf(
			'<img src="%s" alt="%s" srcset="%s" sizes="%s">',
			esc_url( $media['images']['thumbnail'] ),
			esc_attr( $caption ),
			esc_attr( implode( ', ', $srcset ) ),
			esc_attr( implode( ', ', $sizes ) )
		);

		$style = '';

		if ( 'original' != $size ) {
			$style = 'style="background-image: url(' . esc_url( $media['images']['thumbnail'] ) . ')"';
		}

		return sprintf(
			'<a href="%s" target="_blank" rel="nofollow" %s>%s</a>',
			esc_url( $media['link'] ),
			$style,
			$image
		);
	}

	/**
	 * Recursive merge user defined arguments into defaults array.
	 *
	 * @param array $args
	 * @param array $default
	 *
	 * @return array
	 */
	public static function motta_addons_recurse_parse_args( $args, $default = array() ) {
		$args   = (array) $args;
		$result = $default;

		foreach ( $args as $key => $value ) {
			if ( is_array( $value ) && isset( $result[ $key ] ) ) {
				$result[ $key ] = self::motta_addons_recurse_parse_args( $value, $result[ $key ] );
			} else {
				$result[ $key ] = $value;
			}
		}

		return $result;
	}

	/**
	 * Functions that used to get coutndown texts
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_countdown_texts() {
		return apply_filters( 'motta_get_countdown_texts', array(
			'weeks'    => esc_html__( 'Weeks', 'motta-addons' ),
			'days'    => esc_html__( 'Days', 'motta-addons' ),
			'hours'   => esc_html__( 'Hours', 'motta-addons' ),
			'minutes' => esc_html__( 'Minutes', 'motta-addons' ),
			'seconds' => esc_html__( 'Seconds', 'motta-addons' )
		) );
	}

	/**
	 * Functions that used to get coutndown texts
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_countdown_shorten_texts() {
		return apply_filters( 'motta_get_countdown_texts', array(
			'weeks'    => esc_html__( 'Weeks', 'motta-addons' ),
			'days'    => esc_html__( 'Days', 'motta-addons' ),
			'hours'   => esc_html__( 'Hours', 'motta-addons' ),
			'minutes' => esc_html__( 'Mins', 'motta-addons' ),
			'seconds' => esc_html__( 'Secs', 'motta-addons' )
		) );
	}

	/**
	 * Render link control output
	 *
	 * @since 1.0.0
	 *
	 * @param       $link_key
	 * @param       $url
	 * @param       $content
	 * @param array $attr
	 *
	 * @return string
	 */
	public static function control_url( $link_key, $url, $content, $attr = [], $tag = 'a' ) {
		$attr_default = [];
		if ( isset( $url['url'] ) && $url['url'] ) {
			$attr_default['href'] = $url['url'];
		}

		if ( isset( $url['is_external'] ) && $url['is_external'] ) {
			$attr_default['target'] = '_blank';
		}

		if ( isset( $url['nofollow'] ) && $url['nofollow'] ) {
			$attr_default['rel'] = 'nofollow';
		}

		$attr = wp_parse_args( $attr, $attr_default );

		if ( empty( $attr['href'] ) ) {
			$tag = 'span';
		}

		$attributes = [];

		foreach ( $attr as $name => $v ) {
			$attributes[] = $name . '="' . esc_attr( $v ) . '"';
		}

		return sprintf( '<%1$s %2$s>%3$s</%1$s>', $tag, implode( ' ', $attributes ), $content );
	}

	/**
	 * Render the link open
	 *
	 * @since 1.0.0
	 *
	 * @param       $link_key
	 * @param       $url
	 * @param       $content
	 * @param array $attr
	 *
	 * @return string
	 */
	public static function render_control_link_open( $link_key, $url, $attr = [] ) {
		if ( empty( $url['url'] ) ) {
			return;
		}

		$attr_default = [];

		if ( isset( $url['url'] ) && $url['url'] ) {
			$attr_default['href'] = $url['url'];
		}

		if ( isset( $url['is_external'] ) && $url['is_external'] ) {
			$attr_default['target'] = '_blank';
		}

		if ( isset( $url['nofollow'] ) && $url['nofollow'] ) {
			$attr_default['rel'] = 'nofollow';
		}

		$attr = wp_parse_args( $attr, $attr_default );

		$attributes = [];

		foreach ( $attr as $name => $v ) {
			$attributes[] = $name . '="' . esc_attr( $v ) . '"';
		}

		return sprintf( '<a %s>', implode( ' ', $attributes ) );
	}

	/**
	 * Render the link close
	 *
	 * @param array $link
	 */
	public static function render_control_link_close( $url ) {
		if ( empty( $url['url'] ) ) {
			return;
		}

		return '</a>';
	}

	/**
	 * Get nav menus
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_navigation_bar_get_menus() {
		if ( ! is_admin() ) {
			return [];
		}

		$menus = wp_get_nav_menus();
		if ( ! $menus ) {
			return [];
		}

		$output = array(
			0 => esc_html__( 'Select Menu', 'motta-addons' ),
		);
		foreach ( $menus as $menu ) {
			$output[ $menu->slug ] = $menu->name;
		}

		return $output;
	}

	/**
	 * Get terms array for select control
	 *
	 * @param string $taxonomy
	 * @return array
	 */
	public static function get_terms_hierarchy( $taxonomy = 'category', $separator = '-', $hide_empty = true, $child_of = false ) {
		$terms = get_terms( array(
			'taxonomy'   	=> $taxonomy,
			'hide_empty' 	=> $hide_empty,
			'child_of' 		=> $child_of,
			'update_term_meta_cache' => false,
		) );

		if ( ! $terms || is_wp_error( $terms ) ) {
			return array();
		}

		$taxonomy = get_taxonomy( $taxonomy );
		if ( $taxonomy->hierarchical ) {
			$terms = self::sort_terms_hierarchy( $terms );
			$terms = self::flatten_hierarchy_terms( $terms, $separator );
		}

		return $terms;
	}

	/**
	 * Recursively sort an array of taxonomy terms hierarchically.
	 *
	 * @param array $terms
	 * @param integer $parent_id
	 * @return array
	 */
	public static function sort_terms_hierarchy( $terms, $parent_id = 0 ) {
		$hierarchy = array();

		foreach ( $terms as $term ) {
			if ( $term->parent == $parent_id ) {
				$term->children = self::sort_terms_hierarchy( $terms, $term->term_id );
				$hierarchy[] = $term;
			}
		}

		return $hierarchy;
	}

	/**
	 * Flatten hierarchy terms
	 *
	 * @param array $terms
	 * @param integer $depth
	 * @return array
	 */
	public static function flatten_hierarchy_terms( $terms, $separator = '&mdash;', $depth = 0 ) {
		$flatted = array();


		foreach ( $terms as $term ) {
			$children = array();

			if ( ! empty( $term->children ) ) {
				$children = $term->children;
				$term->has_children = true;
				unset( $term->children );
			}

			$term->depth = $depth;
			$term->name = $depth && $separator ? str_repeat( $separator, $depth ) . ' ' . $term->name : $term->name;
			$flatted[] = $term;

			if ( ! empty( $children ) ) {
				$flatted = array_merge( $flatted, self::flatten_hierarchy_terms( $children, $separator, ++$depth ) );
				$depth--;
			}
		}

		return $flatted;
	}

		/**
	 * Check is product deals
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_product_deal( $product ) {
		$product = is_numeric( $product ) ? wc_get_product( $product ) : $product;

		// It must be a sale product first
		if ( ! $product->is_on_sale() ) {
			return false;
		}

		// Only support product type "simple" and "external"
		if ( ! $product->is_type( 'simple' ) && ! $product->is_type( 'external' ) ) {
			return false;
		}

		$deal_quantity = get_post_meta( $product->get_id(), '_deal_quantity', true );

		if ( $deal_quantity > 0 ) {
			return true;
		}

		return false;
	}
}

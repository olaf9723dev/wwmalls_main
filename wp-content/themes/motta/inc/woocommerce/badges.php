<?php
/**
 * Badges hooks.
 *
 * @package Motta
 */

namespace Motta\WooCommerce;

use Motta\Helper, Motta\WooCommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of Badges
 */
class Badges {
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
		// Change the markup of sale flash.
		add_filter( 'woocommerce_sale_flash', array( $this, 'sale_flash' ), 10, 3 );
	}

	/**
	 * Product badges.
	 */
	public static function badges() {
		global $product;
		$badges = array();
		$custom_badges       = maybe_unserialize( get_post_meta( $product->get_id(), 'custom_badges_text', true ) );
		if ( $custom_badges ) {
			$style    = '';
			$custom_badges_bg    = get_post_meta( $product->get_id(), 'custom_badges_bg', true );
			$custom_badges_color = get_post_meta( $product->get_id(), 'custom_badges_color', true );
			$bg_color = ! empty( $custom_badges_bg ) ? '--id--badge-custom-bg:' . $custom_badges_bg . ';' : '';
			$color    = ! empty( $custom_badges_color ) ? '--id--badge-custom-color:' . $custom_badges_color . ';' : '';

			if ( $bg_color || $color ) {
				$style = 'style="' . $color . $bg_color . '"';
			}

			$badges['custom'] = '<span class="custom woocommerce-badge"' . $style . '>' . esc_html( $custom_badges ) . '</span>';
		} else {
			$badges = self::get_badges();
		}

		if ( $badges ) {
			printf( '<span class="woocommerce-badges">%s</span>', implode( '', $badges ) );
		}
	}

	/**
	 * Get product badges.
	 *
	 * @return array
	 */
	public static function get_badges( $args = array() ) {
		if( empty( $args ) ) {
			global $product;
		} else {
			$product = $args;
		}

		$badges = array();

		if ( Helper::get_option( 'badges_soldout' ) && ! $product->is_in_stock() ) {
			$in_stock = false;

			// Double check if this is a variable product.
			if ( $product->is_type( 'variable' ) ) {
				$variations = $product->get_available_variations();

				foreach ( $variations as $variation ) {
					if( $variation['is_in_stock'] ) {
						$in_stock = true;
						break;
					}
				}
			}

			if ( ! $in_stock ) {
				$text               = ! empty( Helper::get_option( 'badges_soldout_text' ) ) ? Helper::get_option( 'badges_soldout_text' ) : esc_html__( 'Out Of Stock', 'motta' );
				$badges['sold-out'] = '<span class="sold-out woocommerce-badge">' . esc_html( $text ) . '</span>';
			}
		} else {
			if ( $product->is_on_sale() && Helper::get_option( 'badges_sale' ) ) {
				if( ! empty( $args ) ) {
					$original_product = $GLOBALS['product'];
					$GLOBALS['product'] = $args;
				}
				ob_start();
				woocommerce_show_product_sale_flash();
				$badges['sale'] = ob_get_clean();
				if( ! empty( $args ) ) {
					$GLOBALS['product'] = $original_product;
					wp_reset_postdata();
				}
			}

			else if ( Helper::get_option( 'badges_new' ) && in_array( $product->get_id(), WooCommerce\General::motta_woocommerce_get_new_product_ids() ) ) {
				$text          = Helper::get_option( 'badges_new_text' );
				$text          = empty( $text ) ? esc_html__( 'New', 'motta' ) : $text;
				$badges['new'] = '<span class="new woocommerce-badge">' . esc_html( $text ) . '</span>';
			}

			else if ( $product->is_featured() && Helper::get_option( 'badges_featured' ) ) {
				$text               = Helper::get_option( 'badges_featured_text' );
				$text               = empty( $text ) ? esc_html__( 'Hot', 'motta' ) : $text;
				$badges['featured'] = '<span class="featured woocommerce-badge">' . esc_html( $text ) . '</span>';
			}
		}

		$badges = apply_filters( 'motta_product_badges', $badges, $product );

		return $badges;
	}

	/**
	 * Sale badge.
	 *
	 * @param string $output  The sale flash HTML.
	 * @param object $post    The post object.
	 * @param object $product The product object.
	 *
	 * @return string
	 */
	public static function sale_flash( $output, $post, $product ) {
		if ( ! Helper::get_option( 'badges_sale' ) || 'grouped' == $product->get_type() ) {
			return '';
		}

		$type       = is_singular( 'product' ) ? 'percent' : Helper::get_option( 'badges_sale_type' );
		$text       = Helper::get_option( 'badges_sale_text' );
		$percentage = 0;
		$saved      = 0;
		$sale_date = get_post_meta( $product->get_id(), '_sale_price_dates_to', true );
		$sale_date = apply_filters( 'motta_product_sale_dates_to', $sale_date );
		$now = strtotime( current_time( 'Y-m-d H:i:s' ) );

		$sale = array(
			'weeks'   => esc_html__( 'w', 'motta' ),
			'days'    => esc_html__( 'd', 'motta' ),
			'hours'   => esc_html__( 'h', 'motta' ),
			'minutes' => esc_html__( 'm', 'motta' ),
			'seconds' => esc_html__( 's', 'motta' ),
		);

		if( is_singular( 'product' ) && $product->is_type( 'variable' ) ) {
			$sale_dates = array();
			$variation_ids = $product->get_visible_children();

			foreach( $variation_ids as $variation_id ) {
				$variation = wc_get_product( $variation_id );

				if ( $variation->is_on_sale() ) {
					$date_on_sale_to   = $variation->get_date_on_sale_to();

					if( ! empty($date_on_sale_to) ) {
						$sale_dates[] = $date_on_sale_to;
					}
				}
			}

			if( ! empty( $sale_dates) ) {
				$sale_date = strtotime( max( $sale_dates ) );
			}
		}

		if ( 'percent' == $type || 'text-price' == $type || 'text-countdown' == $type || false !== strpos( $text, '{%}' ) || false !== strpos( $text, '{$}' ) ) {
			if ( $product->get_type() == 'variable' ) {
				$available_variations = $product->get_available_variations();
				$max_percentage       = 0;
				$max_saved            = 0;
				$total_variations     = count( $available_variations );

				for ( $i = 0; $i < $total_variations; $i++ ) {
					$variation_id        = $available_variations[ $i ]['variation_id'];
					$variable_product    = new \WC_Product_Variation( $variation_id );
					$regular_price       = $variable_product->get_regular_price();
					$sales_price         = $variable_product->get_sale_price();
					$variable_saved      = $regular_price && $sales_price ? ( $regular_price - $sales_price ) : 0;
					$variable_percentage = $regular_price && $sales_price ? round( ( ( ( $regular_price - $sales_price ) / $regular_price ) * 100 ) ) : 0;

					if ( $variable_saved > $max_saved ) {
						$max_saved = $variable_saved;
					}

					if ( $variable_percentage > $max_percentage ) {
						$max_percentage = $variable_percentage;
					}
				}

				$saved      = $max_saved ? $max_saved : $saved;
				$percentage = $max_percentage ? $max_percentage : $percentage;
			} elseif ( $product->get_regular_price() != 0 ) {
				$saved      = $product->get_regular_price() - $product->get_sale_price();
				$percentage = round( ( $saved / $product->get_regular_price() ) * 100 );
			}
		}

		if ( 'percent' == $type ) {
			$output = '<span class="onsale woocommerce-badge">-' . $percentage . '%</span>';
		} elseif ( 'text-price' == $type || 'text-countdown' == $type ) {
			if ( 'text-countdown' == $type && $sale_date ) {
				$days = $sale_date - $now;

				if( $days > 0 ) {
					$output = '<span class="onsale woocommerce-badge"><span class="woocommerce-badge--label">' . esc_html__( 'Sale Ends in', 'motta' ) . '</span><span class="motta-countdown" data-expire="' . esc_attr( $days ) . '" data-text="' . esc_attr( wp_json_encode( $sale ) ) . '"></span>' . '</span>';
				}
			} else {
				$class = empty( $text ) ? 'no-text' : '';
				$output = '<span class="onsale woocommerce-badge ' . esc_attr( $class ) . '"><span class="woocommerce-badge--label">' . wp_kses_post( $text ) . '</span>'. wc_price( $saved ) . '</span>';
			}
		} else {
			$output = '<span class="onsale woocommerce-badge">' . wp_kses_post( $text ) . '</span>';
		}

		if ( $product->is_on_sale() && is_singular( 'product' ) && $sale_date ) {
			$days = $sale_date - $now;

			$day_text = date( 'd', $days ) . esc_html__( ' Day', 'motta' );
			if ( date( 'd', $days ) > 1 ) {
				$day_text = date( 'd', $days ) . esc_html__( ' Days', 'motta' );
			}

			if( $days > 0 ) {
				$output .= '<span class="woocommerce-badge--text">' . esc_html__( 'Sale Ends in ', 'motta' ) . esc_html( $day_text ) . ' ' . self::get_sold() . '</span>';
			}
		}

		return $output;
	}

	/**
	 * Get sold of product deal
	 */
	public static function get_sold() {
		global $product;

		$limit = get_post_meta( $product->get_id(), '_deal_quantity', true );
		$sold  = intval( get_post_meta( $product->get_id(), '_deal_sales_counts', true ) );

		$output = ! empty( $limit ) ? '(' . esc_html( $sold ) . '/' . esc_html( $limit ) . ' ' . esc_html__( 'sold', 'motta' ) . ')' : '';

		return $output;
	}
}

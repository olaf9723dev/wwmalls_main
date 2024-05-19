<?php
namespace Motta\Addons\Elementor;
use Motta\Addons\Helper;

class Utils {
	/**
	 * Get terms array for select control
	 *
	 * @param string $taxonomy
	 * @return array
	 */
	public static function get_terms_options( $taxonomy = 'category' ) {
		$terms = Helper::get_terms_hierarchy( $taxonomy, '&#8212;' );

		if ( empty( $terms ) ) {
			return [];
		}

		$options = wp_list_pluck( $terms, 'name', 'slug' );

		return $options;
	}

	/**
	 * Pagination product elementor
	 *
	 * @param array $settings Shortcode attributes
	 */
	public static function get_pagination( $settings ) {
		$paginated = ! empty( $settings['pagination'] ) ? true : false;

		if( ! $paginated ) {
			return;
		}

		$settings['paginate'] = true;

		$results = self::products_shortcode( $settings );

		if ( $results['current_page'] >= $results['total_pages']  ) {
			return;
		}
		$classes = array(
			'woocommerce-navigation',
			'woocommerce-navigation__products-tabs',
			'next-posts-navigation',
			'ajax-navigation',
			'ajax-loadmore',
		);
		$output = sprintf( '<a href="#" data-page="%s" class="nav-links motta-button motta-button--medium motta-shape--round motta-button--base">%s</a>
			<div class="motta-pagination--loading">
				<div class="motta-pagination--loading-dots">
					<span></span>
					<span></span>
					<span></span>
					<span></span>
				</div>
				<div class="motta-pagination--loading-text">%s</div>
			</div>',
			esc_attr( $results['current_page'] + 1 ),
			esc_html__( 'Load More Products', 'motta-addons' ),
			esc_html__( 'Loading more...', 'motta-addons' )
			);

		return '<nav class="'. esc_attr( implode( ' ', $classes ) ) . '">' . $output . '</nav>';
	}

	/**
	 * Get products loop content for shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts Shortcode attributes
	 *
	 * @return array
	 */
	public static function products_shortcode( $atts ) {
		if ( ! class_exists( 'WC_Shortcode_Products' ) ) {
			return;
		}
		global $motta_soldbar;

		$type = $atts['type'];

		$classes = '';
		if ( isset( $atts['product_brands'] ) && ! empty( $atts['product_brands'] ) ) {
			$classes = 'sc_brand,' . $atts['product_brands'];
		}

		if( isset( $atts['product_outofstock'] ) ) {
			$classes .= empty( $classes ) ? '' : ' ,';
			$classes .= 'sc_outofstock';
		}

		if( ! empty( $classes ) ) {
			$atts['class'] = $classes ;
		}

		if( isset( $atts[ 'type' ] ) && $atts[ 'type' ] == 'featured_products' ) {
			$atts[ 'visibility' ] = 'featured';
		}

		$shortcode  = new \WC_Shortcode_Products( $atts, $type );
		$query_args = $shortcode->get_query_args();

		$product_type = in_array( $type, array( 'day', 'week', 'month', 'deals' ) ) ? 'sale' : $type;
		if(strpos($product_type, 'products') === false){
			$product_type .= '_products';
		}

		self::{"set_{$product_type}_query_args"}( $query_args );

		if ( in_array( $type, array( 'day', 'week', 'month' ) ) ) {
			$date = '+1 day';
			if ( $type == 'week' ) {
				$date = '+7 day';
			} else if ( $type == 'month' ) {
				$date = '+1 month';
			}
			$query_args['meta_query'] = apply_filters(
				'motta_product_deals_meta_query', array_merge(
					WC()->query->get_meta_query(), array(
						array(
							'key'     => '_deal_quantity',
							'value'   => 0,
							'compare' => '>',
						),
						array(
							'key'     => '_sale_price_dates_to',
							'value'   => 0,
							'compare' => '>',
						),
						array(
							'key'     => '_sale_price_dates_to',
							'value'   => strtotime( $date ),
							'compare' => '<=',
						),
					)
				)
			);

			if( ! empty( $atts['motta_soldbar'] ) ) {
				$motta_soldbar = true;
			}
		} elseif ( $type == 'deals' ) {
			$query_args['meta_query'] = apply_filters(
				'motta_product_deals_meta_query', array_merge(
					WC()->query->get_meta_query(), array(
						array(
							'key'     => '_deal_quantity',
							'value'   => 0,
							'compare' => '>',
						)
					)
				)
			);

			if( ! empty( $atts['motta_soldbar'] ) ) {
				$motta_soldbar = true;
			}
		}

		if ( isset( $atts['page'] ) ) {
			$query_args['paged'] = isset( $atts['page'] ) ? absint( $atts['page'] ) : 1;
		}

		return self::get_query_results( $query_args, $type );
	}

	/**
	 * Run the query and return an array of data, including queried ids.
	 *
	 * @since 1.0.0
	 *
	 * @return array with the following props; ids
	 */
	public static function get_query_results( $query_args, $type ) {
		$transient_name       = self::get_transient_name( $query_args, $type );
		$transient_version    = \WC_Cache_Helper::get_transient_version( 'product_query' );
		$transient_value      = get_transient( $transient_name );

		if ( isset( $transient_value['value'], $transient_value['version'] ) && $transient_value['version'] === $transient_version ) {
			$results = $transient_value['value'];
		} else {
			$query = new \WP_Query( $query_args );

			$paginated = ! $query->get( 'no_found_rows' );

			$results = array(
				'ids'          => wp_parse_id_list( $query->posts ),
				'total'        => $paginated ? (int) $query->found_posts : count( $query->posts ),
				'total_pages'  => $paginated ? (int) $query->max_num_pages : 1,
				'current_page' => $paginated ? (int) max( 1, $query->get( 'paged', 1 ) ) : 1,
			);

			wp_reset_postdata();
		}

		// Remove ordering query arguments which may have been added by get_catalog_ordering_args.
		WC()->query->remove_ordering_args();

		return $results;
	}

	/**
	 * Generate and return the transient name for this shortcode based on the query args.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function get_transient_name( $query_args, $type ) {
		$transient_name = 'motta_product_loop_' . md5( wp_json_encode( $query_args ) . $type );

		if ( 'rand' === $query_args['orderby'] ) {
			// When using rand, we'll cache a number of random queries and pull those to avoid querying rand on each page load.
			$rand_index     = wp_rand( 0, max( 1, absint( apply_filters( 'woocommerce_product_query_max_rand_cache_count', 5 ) ) ) );
			$transient_name .= $rand_index;
		}

		return $transient_name;
	}

	/**
	 * Loop over products
	 *
	 * @since 1.0.0
	 *
	 * @param string
	 */
	public static function get_template_loop( $products_ids, $template = 'product' ) {
		if( empty( $products_ids ) ) {
			return;
		}
		update_meta_cache( 'post', $products_ids );
		update_object_term_cache( $products_ids, 'product' );

		$original_post = $GLOBALS['post'];

		woocommerce_product_loop_start();

		foreach ( $products_ids as $product_id ) {
			$GLOBALS['post'] = get_post( $product_id ); // WPCS: override ok.
			setup_postdata( $GLOBALS['post'] );
			wc_get_template_part( 'content', $template );
		}

		$GLOBALS['post'] = $original_post; // WPCS: override ok.

		woocommerce_product_loop_end();

		wp_reset_postdata();
		wc_reset_loop();
	}

	/**
	 * Set ids query args.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args Query args.
	 */
	protected static function set_recent_products_query_args( &$query_args ) {
		$query_args['order']   = 'DESC';
		$query_args['orderby'] = 'date';
	}

	/**
	 * Set ids query args.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args Query args.
	 */
	protected static function set_custom_products_query_args( &$query_args ) {
		$query_args['orderby'] = 'post__in';
	}

	/**
	 * Set sale products query args.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args Query args.
	 */
	protected static function set_sale_products_query_args( &$query_args ) {
		$query_args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
	}

	/**
	 * Set best selling products query args.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args Query args.
	 */
	protected static function set_best_selling_products_query_args( &$query_args ) {
		$query_args['meta_key'] = 'total_sales'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		$query_args['order']    = 'DESC';
		$query_args['orderby']  = 'meta_value_num';
	}

	/**
	 * Set top rated products query args.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args Query args.
	 */
	protected static function set_top_rated_products_query_args( &$query_args ) {
		$query_args['meta_key'] = '_wc_average_rating'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		$query_args['order']    = 'DESC';
		$query_args['orderby']  = 'meta_value_num';
	}

	/**
	 * Set visibility as featured.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args Query args.
	 */
	protected static function set_featured_products_query_args( &$query_args ) {
		$query_args['tax_query'] = array_merge( $query_args['tax_query'], WC()->query->get_tax_query() ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query

		$query_args['tax_query'][] = array(
			'taxonomy'         => 'product_visibility',
			'terms'            => 'featured',
			'field'            => 'name',
			'operator'         => 'IN',
			'include_children' => false,
		);
	}

	/**
	 * Get recently viewed ids
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_product_recently_viewed_ids() {
		$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] ) : array();

		return array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );
	}

	/**
	 * Get products recently viewed
	 *
	 * @since 1.0.0
	 *
	 * @param $settings
	 */
	public static function get_recently_viewed_products( $settings ) {
		$products_ids = self::get_product_recently_viewed_ids();

		if ( empty( $products_ids ) ) {
			?>
			<div class="no-products">
				<p><?php echo wp_kses( $settings['desc'], wp_kses_allowed_html( 'post' ) ); ?></p>
				<?php echo Helper::control_url( 'empty_button', $settings['button_link'], $settings['button_text'], [ 'class' => 'motta-button' ] ); ?>
			</div>
			<?php
		} else {
			update_meta_cache( 'post', $products_ids );
			update_object_term_cache( $products_ids, 'product' );

			$original_post = $GLOBALS['post'];

			woocommerce_product_loop_start();

			$index = 1;
			$exists_product = false;

			foreach ( $products_ids as $product_id ) {
				if ( $index > $settings['limit'] ) {
					break;
				}

				$index ++;

				$product = get_post( $product_id );
				if ( empty( $product ) ) {
					continue;
				}

				$exists_product = true;

				$GLOBALS['post'] = $product; // WPCS: override ok.
				setup_postdata( $GLOBALS['post'] );
				wc_get_template_part( 'content', 'product' );
			}

			if ( ! $exists_product ) {
				?>
					<li class="no-products">
						<p><?php echo esc_html__( 'No products in recent viewing history.', 'motta-addons' ) ?></p>
					</li>

				<?php
			}

			$GLOBALS['post'] = $original_post; // WPCS: override ok.

			woocommerce_product_loop_end();

			wp_reset_postdata();
			wc_reset_loop();
		}
	}

	/**
	 * Get coordinates
	 *
	 * @param string $address
	 * @param bool   $refresh
	 *
	 * @return array
	 */
	public static function get_coordinates( $address, $key = '', $refresh = false ) {
		$address_hash = md5( $address );
		$coordinates  = get_transient( $address_hash );
		$results      = array( 'lat' => '', 'lng' => '' );

		if ( $refresh || $coordinates === false ) {
			$args     = array( 'address' => urlencode( $address ), 'sensor' => 'false', 'key' => $key );
			$url      = add_query_arg( $args, 'https://maps.googleapis.com/maps/api/geocode/json' );
			$response = wp_remote_get( $url );

			if ( is_wp_error( $response ) ) {
				$results['error'] = esc_html__( 'Can not connect to Google Maps APIs.', 'motta-addons' ) . ' ' . $response->get_error_message();

				return $results;
			}

			$data = wp_remote_retrieve_body( $response );

			if ( is_wp_error( $data ) ) {
				$results['error'] = esc_html__( 'Can not connect to Google Maps APIs', 'motta-addons' );

				return $results;
			}

			if ( $response['response']['code'] == 200 ) {
				$data = json_decode( $data );

				if ( $data->status === 'OK' ) {
					$coordinates = $data->results[0]->geometry->location;

					$results['lat']     = $coordinates->lat;
					$results['lng']     = $coordinates->lng;
					$results['address'] = (string) $data->results[0]->formatted_address;

					// cache coordinates for 3 months
					set_transient( $address_hash, $results, 3600 * 24 * 30 * 3 );
				} elseif ( $data->status === 'ZERO_RESULTS' ) {
					$results['error'] = esc_html__( 'No location found for the entered address.', 'motta-addons' );
				} elseif ( $data->status === 'INVALID_REQUEST' ) {
					$results['error'] = esc_html__( 'Invalid request. Did you enter an address?', 'motta-addons' );
				} else {
					$results['error'] = $data->error_message;
				}
			} else {
				$results['error'] = esc_html__( 'Unable to contact Google API service.', 'motta-addons' );
			}
		} else {
			$results = $coordinates; // return cached results
		}

		return $results;
	}
}
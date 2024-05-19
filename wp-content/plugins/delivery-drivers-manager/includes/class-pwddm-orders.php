<?php

/**
 * Orders page.
 *
 * All the orders functions.
 *
 * @package    PWDDM
 * @subpackage PWDDM/includes
 * @author     powerfulwp <cs@powerfulwp.com>
 */
/**
 * Orders class.
 *
 * All the orders functions.
 *
 * @package    PWDDM
 * @subpackage PWDDM/includes
 * @author     powerfulwp <cs@powerfulwp.com>
 */
class PWDDM_Orders {
    /**
     * Dashboard claim report query.
     *
     * @since 1.0.0
     * @param int $driver_id driver user id.
     * @return html
     */
    public function pwddm_claim_orders_dashboard_report_query() {
        global $wpdb;
        if ( pwddm_is_hpos_enabled() ) {
            // Query for HPOS-enabled environments
            $query = $wpdb->get_results( $wpdb->prepare( 'SELECT o.status AS post_status, COUNT(*) AS orders
						FROM ' . $wpdb->prefix . 'wc_orders o
						LEFT JOIN ' . $wpdb->prefix . 'wc_orders_meta om ON o.id = om.order_id AND om.meta_key = \'lddfw_driverid\'
						LEFT JOIN ' . $wpdb->prefix . 'wc_orders_meta om1 ON o.id = om1.order_id AND om1.meta_key = \'lddfw_delivered_date\'
						WHERE o.type = \'shop_order\' AND ( om.meta_value IS NULL OR om.meta_value = \'-1\' OR om.meta_value = \'\' ) AND
						(
							o.status IN (%s, %s, %s, %s) OR
							( o.status = %s AND CAST( om1.meta_value AS DATE ) >= %s AND CAST( om1.meta_value AS DATE ) <= %s )
						)
						GROUP BY o.status', array(
                get_option( 'lddfw_processing_status', '' ),
                get_option( 'lddfw_driver_assigned_status', '' ),
                get_option( 'lddfw_out_for_delivery_status', '' ),
                get_option( 'lddfw_failed_attempt_status', '' ),
                get_option( 'lddfw_delivered_status', '' ),
                date_i18n( 'Y-m-d' ),
                date_i18n( 'Y-m-d' )
            ) ) );
        } else {
            // Non-HPOS environment query
            $query = $wpdb->get_results( $wpdb->prepare( 'select post_status, count(*) as orders from ' . $wpdb->prefix . 'posts p
				left join ' . $wpdb->prefix . 'postmeta pm on p.id=pm.post_id and pm.meta_key = \'lddfw_driverid\'
				left join ' . $wpdb->prefix . 'postmeta pm1 on p.id=pm1.post_id and pm1.meta_key = \'lddfw_delivered_date\'
				where post_type=\'shop_order\' and ( pm.meta_value is null or pm.meta_value = \'-1\' or pm.meta_value = \'\' ) and
				(
					post_status in (%s,%s,%s,%s) or
					( post_status = %s and CAST( pm1.meta_value AS DATE ) >= %s and CAST( pm1.meta_value AS DATE ) <= %s )
				)
				group by post_status', array(
                get_option( 'lddfw_processing_status', '' ),
                get_option( 'lddfw_driver_assigned_status', '' ),
                get_option( 'lddfw_out_for_delivery_status', '' ),
                get_option( 'lddfw_failed_attempt_status', '' ),
                get_option( 'lddfw_delivered_status', '' ),
                date_i18n( 'Y-m-d' ),
                date_i18n( 'Y-m-d' )
            ) ) );
            // db call ok; no-cache ok.
        }
        return $query;
    }

    /**
     * Drivers orders dashboard report query.
     *
     * @since 1.0.0
     * @return array
     */
    public function pwddm_drivers_orders_dashboard_report_query() {
        global $wpdb;
        if ( pwddm_is_hpos_enabled() ) {
            // Query adapted for HPOS-enabled environments.
            $query = $wpdb->get_results( $wpdb->prepare( 'select l.driver_id, wo.status as post_status , count(*) as orders
						from ' . $wpdb->prefix . 'wc_orders wo
						inner join ' . $wpdb->prefix . 'lddfw_orders l ON wo.id = l.order_id
						where wo.type=\'shop_order\' and l.driver_id > 0 and
						(
							wo.status in (%s,%s,%s) or
							( wo.status = %s AND CAST(l.delivered_date AS DATE) BETWEEN %s AND %s )
						)
						group by l.driver_id, wo.status
						order by l.driver_id', array(
                get_option( 'lddfw_driver_assigned_status', '' ),
                get_option( 'lddfw_out_for_delivery_status', '' ),
                get_option( 'lddfw_failed_attempt_status', '' ),
                get_option( 'lddfw_delivered_status', '' ),
                date_i18n( 'Y-m-d' ),
                date_i18n( 'Y-m-d' )
            ) ) );
            // db call ok; no-cache ok.
        } else {
            // Original query for non-HPOS environments.
            $query = $wpdb->get_results( $wpdb->prepare( 'select o.driver_id , post_status , count(*) as orders 
				from ' . $wpdb->prefix . 'posts p
				inner join ' . $wpdb->prefix . 'lddfw_orders o ON p.ID = o.order_id
				where post_type=\'shop_order\' and o.driver_id > 0 and
				(
					post_status in (%s,%s,%s) or
					( post_status = %s AND CAST(delivered_date AS DATE) BETWEEN %s AND %s )
				)
				group by o.driver_id, post_status
				order by o.driver_id ', array(
                get_option( 'lddfw_driver_assigned_status', '' ),
                get_option( 'lddfw_out_for_delivery_status', '' ),
                get_option( 'lddfw_failed_attempt_status', '' ),
                get_option( 'lddfw_delivered_status', '' ),
                date_i18n( 'Y-m-d' ),
                date_i18n( 'Y-m-d' )
            ) ) );
            // db call ok; no-cache ok.
        }
        return $query;
    }

    /**
     * Orders count query.
     *
     * @since 1.0.0
     * @param int $driver_id driver user id.
     * @return html
     */
    public function pwddm_orders_count_query( $driver_id ) {
        global $wpdb;
        if ( pwddm_is_hpos_enabled() ) {
            // Query adapted for HPOS-enabled environments.
            $query = $wpdb->get_results( $wpdb->prepare( 'select wo.status as post_status, count(*) as orders
					from ' . $wpdb->prefix . 'wc_orders wo
					inner join ' . $wpdb->prefix . 'wc_orders_meta wom on wo.id=wom.order_id and wom.meta_key = \'pwddm_managerid\' and wom.meta_value = %s
					left join ' . $wpdb->prefix . 'wc_orders_meta wom1 on wo.id=wom1.order_id and wom1.meta_key = \'pwddm_delivered_date\'
					where wo.type=\'shop_order\' and
					(
						wo.status in (%s,%s,%s) or
						( wo.status = %s and CAST( wom1.meta_value AS DATE ) >= %s and CAST( wom1.meta_value AS DATE ) <= %s )
					)
					group by wo.status', array(
                $driver_id,
                get_option( 'lddfw_driver_assigned_status', '' ),
                get_option( 'lddfw_out_for_delivery_status', '' ),
                get_option( 'lddfw_failed_attempt_status', '' ),
                get_option( 'lddfw_delivered_status', '' ),
                date_i18n( 'Y-m-d' ),
                date_i18n( 'Y-m-d' )
            ) ) );
            // db call ok; no-cache ok.
        } else {
            // Original query for non-HPOS environments.
            $query = $wpdb->get_results( $wpdb->prepare( 'select post_status , count(*) as orders from ' . $wpdb->prefix . 'posts p
				inner join ' . $wpdb->prefix . 'postmeta pm on p.id=pm.post_id and pm.meta_key = \'pwddm_managerid\' and pm.meta_value = %s
				left join ' . $wpdb->prefix . 'postmeta pm1 on p.id=pm1.post_id and pm1.meta_key = \'pwddm_delivered_date\'
				where post_type=\'shop_order\' and
				(
					post_status in (%s,%s,%s) or
					( post_status = %s and CAST( pm1.meta_value AS DATE ) >= %s and CAST( pm1.meta_value AS DATE ) <= %s )
				)
				group by post_status', array(
                $driver_id,
                get_option( 'lddfw_driver_assigned_status', '' ),
                get_option( 'lddfw_out_for_delivery_status', '' ),
                get_option( 'lddfw_failed_attempt_status', '' ),
                get_option( 'lddfw_delivered_status', '' ),
                date_i18n( 'Y-m-d' ),
                date_i18n( 'Y-m-d' )
            ) ) );
            // db call ok; no-cache ok.
        }
        return $query;
    }

    /**
     * Drivers orders dashboard report.
     *
     * @since 1.0.0
     * @return html
     */
    public function pwddm_managers_orders_dashboard_report_query() {
        global $wpdb;
        if ( pwddm_is_hpos_enabled() ) {
            // Query adapted for HPOS-enabled environments.
            $query = $wpdb->get_results( $wpdb->prepare( 'select wom.meta_value as driver_id, wo.status as post_status, u.display_name as driver_name, count(*) as orders
					from ' . $wpdb->prefix . 'wc_orders wo
					inner join ' . $wpdb->prefix . 'wc_orders_meta wom on wo.id=wom.order_id and wom.meta_key = \'pwddm_managerid\'
					inner join ' . $wpdb->base_prefix . 'users u on u.id = wom.meta_value
					left join ' . $wpdb->prefix . 'wc_orders_meta wom1 on wo.id=wom1.order_id and wom1.meta_key = \'pwddm_delivered_date\'
					where wo.type=\'shop_order\' and
					(
						wo.status in (%s,%s,%s) or
						( wo.status = %s and CAST( wom1.meta_value AS DATE ) >= %s and CAST( wom1.meta_value AS DATE ) <= %s )
					)
					group by wom.meta_value, wo.status
					order by wom.meta_value', array(
                get_option( 'lddfw_driver_assigned_status', '' ),
                get_option( 'lddfw_out_for_delivery_status', '' ),
                get_option( 'lddfw_failed_attempt_status', '' ),
                get_option( 'lddfw_delivered_status', '' ),
                date_i18n( 'Y-m-d' ),
                date_i18n( 'Y-m-d' )
            ) ) );
            // db call ok; no-cache ok.
        } else {
            // Original query for non-HPOS environments.
            $query = $wpdb->get_results( $wpdb->prepare( 'select pm.meta_value driver_id , post_status, u.display_name driver_name , count(*) as orders
				from ' . $wpdb->prefix . 'posts p
				inner join ' . $wpdb->prefix . 'postmeta pm on p.id=pm.post_id and pm.meta_key = \'pwddm_managerid\'
				inner join ' . $wpdb->base_prefix . 'users u on u.id = pm.meta_value
				left join ' . $wpdb->prefix . 'postmeta pm1 on p.id=pm1.post_id and pm1.meta_key = \'pwddm_delivered_date\'
				where post_type=\'shop_order\' and
				(
					post_status in (%s,%s,%s) or
					( post_status = %s and CAST( pm1.meta_value AS DATE ) >= %s and CAST( pm1.meta_value AS DATE ) <= %s )
				)
				group by pm.meta_value, post_status
				order by pm.meta_value ', array(
                get_option( 'lddfw_driver_assigned_status', '' ),
                get_option( 'lddfw_out_for_delivery_status', '' ),
                get_option( 'lddfw_failed_attempt_status', '' ),
                get_option( 'lddfw_delivered_status', '' ),
                date_i18n( 'Y-m-d' ),
                date_i18n( 'Y-m-d' )
            ) ) );
            // db call ok; no-cache ok.
        }
        return $query;
    }

    /**
     * Assign to driver count query.
     *
     * @since 1.0.0
     * @param int $driver_id driver user id.
     * @return array
     */
    public function pwddm_assign_to_driver_count_query( $driver_id ) {
        global $wpdb;
        if ( pwddm_is_hpos_enabled() ) {
            // Query adapted for HPOS-enabled environments.
            return $wpdb->get_results( $wpdb->prepare( 'select count(*) as orders 
					from ' . $wpdb->prefix . 'wc_orders wo
					inner join ' . $wpdb->prefix . 'wc_orders_meta wom on wo.id=wom.order_id and wom.meta_key = \'lddfw_driverid\'
					where wo.type=\'shop_order\' and wo.status in (%s)
					and wom.meta_value = %s group by wo.status', array(get_option( 'lddfw_driver_assigned_status', '' ), $driver_id) ) );
            // db call ok; no-cache ok.
        } else {
            // Original query for non-HPOS environments.
            return $wpdb->get_results( $wpdb->prepare( 'select count(*) as orders from ' . $wpdb->prefix . 'posts p
				inner join ' . $wpdb->prefix . 'postmeta pm on p.id=pm.post_id and pm.meta_key = \'lddfw_driverid\'
				where post_type=\'shop_order\' and post_status in (%s)
				and pm.meta_value = %s group by post_status', array(get_option( 'lddfw_driver_assigned_status', '' ), $driver_id) ) );
            // db call ok; no-cache ok.
        }
    }

    /**
     * Assign to driver count query.
     *
     * @since 1.0.0
     * @param int    $manager_id manager user id.
     * @param int    $status order status.
     * @param string $screen current screen.
     * @return object
     */
    public function pwddm_orders_query( $manager_id, $status, $screen = null ) {
        $posts_per_page = 20;
        $paged = 1;
        $sort_array = array(
            'sort_meta_not_exist'      => 'ASC',
            'sort_city_meta_not_exist' => 'ASC',
        );
        $relation_array = array(
            'relation' => 'or',
            array(
                'sort_city_meta_not_exist' => array(
                    'key'     => '_shipping_city',
                    'compare' => 'NOT EXISTS',
                ),
            ),
            array(
                'sort_city_meta_exist' => array(
                    'key'     => '_shipping_city',
                    'compare' => 'EXISTS',
                ),
            ),
            array(
                'sort_meta_exist' => array(
                    'key'     => 'lddfw_order_sort',
                    'compare' => 'EXISTS',
                    'type'    => 'NUMERIC',
                ),
            ),
            array(
                'sort_meta_not_exist' => array(
                    'key'     => 'lddfw_order_sort',
                    'compare' => 'NOT EXISTS',
                    'type'    => 'NUMERIC',
                ),
            ),
        );
        if ( 'claim_orders' === $screen ) {
            $sort_array = array();
            $relation_array = array();
            $array = array(array(
                'relation' => 'or',
                array(
                    'key'     => 'pwddm_managerid',
                    'value'   => '-1',
                    'compare' => '=',
                ),
                array(
                    'key'     => 'pwddm_managerid',
                    'value'   => '',
                    'compare' => '=',
                ),
                array(
                    'key'     => 'pwddm_managerid',
                    'compare' => 'NOT EXISTS',
                ),
            ));
        } elseif ( 'delivered' === $screen ) {
            global $pwddm_dates, $pwddm_page;
            $posts_per_page = 20;
            $paged = $pwddm_page;
            if ( '' === $pwddm_dates ) {
                $from_date = date_i18n( 'Y-m-d' );
                $to_date = date_i18n( 'Y-m-d' );
            } else {
                $pwddm_dates_array = explode( ',', $pwddm_dates );
                if ( 1 < count( $pwddm_dates_array ) ) {
                    if ( $pwddm_dates_array[0] === $pwddm_dates_array[1] ) {
                        $from_date = date_i18n( 'Y-m-d', strtotime( $pwddm_dates_array[0] ) );
                        $to_date = date_i18n( 'Y-m-d', strtotime( $pwddm_dates_array[0] ) );
                    } else {
                        $from_date = date_i18n( 'Y-m-d', strtotime( $pwddm_dates_array[0] ) );
                        $to_date = date_i18n( 'Y-m-d', strtotime( $pwddm_dates_array[1] ) );
                    }
                } else {
                    $from_date = date_i18n( 'Y-m-d', strtotime( $pwddm_dates_array[0] ) );
                    $to_date = date_i18n( 'Y-m-d', strtotime( $pwddm_dates_array[0] ) );
                }
            }
            $array = array(
                'relation' => 'and',
                array(
                    'key'     => 'pwddm_managerid',
                    'value'   => $manager_id,
                    'compare' => '=',
                ),
                array(
                    'key'     => 'pwddm_delivered_date',
                    'value'   => $from_date,
                    'compare' => '>=',
                    'type'    => 'DATE',
                ),
                array(
                    'key'     => 'pwddm_delivered_date',
                    'value'   => $to_date,
                    'compare' => '<=',
                    'type'    => 'DATE',
                ),
            );
        } else {
            $array = array(
                'key'     => pwddm_manager_role(),
                'value'   => $manager_id,
                'compare' => '=',
            );
        }
        $params = array(
            'limit'      => $posts_per_page,
            'page'       => $paged,
            'status'     => $status,
            'type'       => 'shop_order',
            'return'     => 'ids',
            'meta_query' => array(
                'relation' => 'AND',
                $relation_array,
                $array,
            ),
            'orderby'    => $sort_array,
        );
        $result = wc_get_orders( $params );
        return $result;
    }

    /**
     * Orders_form_post
     *
     * @since 1.0.0
     * @param int $manager_id manager user id.
     * @return void
     */
    public function pwddm_orders_form_post( $manager_id, $type ) {
        if ( isset( $_POST['pwddm_wpnonce'] ) ) {
            $nonce = sanitize_text_field( wp_unslash( $_POST['pwddm_wpnonce'] ) );
            if ( !wp_verify_nonce( $nonce, 'pwddm-nonce' ) ) {
                $error = __( 'Security Check Failure - This alert may occur when you are logged in as an administrator and as a delivery driver on the same browser and the same device. If you want to work on both panels please try to work with two different browsers.', 'pwddm' );
            } else {
                $pwddm_action = ( isset( $_POST['pwddm_action'] ) ? sanitize_text_field( wp_unslash( $_POST['pwddm_action'] ) ) : '' );
                if ( '' !== $pwddm_action && isset( $_POST['pwddm_order_id'] ) ) {
                    $driver = new LDDFW_Driver();
                    foreach ( $_POST['pwddm_order_id'] as $order_id ) {
                        $order_id = sanitize_text_field( wp_unslash( $order_id ) );
                        $order = wc_get_order( $order_id );
                        // Update order Status.
                        if ( 'mark_driver_assigned' === $pwddm_action ) {
                            $order->update_status( get_option( 'lddfw_driver_assigned_status' ), __( 'Order status has been changed by manager.', 'pwddm' ) );
                        } elseif ( 'mark_out_for_delivery' === $pwddm_action ) {
                            $order->update_status( get_option( 'lddfw_out_for_delivery_status' ), __( 'Order status has been changed by manager.', 'pwddm' ) );
                        } elseif ( 'mark_failed' === $pwddm_action ) {
                            $order->update_status( get_option( 'lddfw_failed_attempt_status' ), __( 'Order status has been changed by manager.', 'pwddm' ) );
                        } elseif ( 'mark_delivered' === $pwddm_action ) {
                            $order->update_status( get_option( 'lddfw_delivered_status' ), __( 'Order status has been changed by manager.', 'pwddm' ) );
                        } elseif ( 'mark_processing' === $pwddm_action ) {
                            $order->update_status( get_option( 'lddfw_processing_status', '' ), __( 'Order status has been changed by manager.', 'pwddm' ) );
                        } elseif ( 'remove_location_status' === $pwddm_action ) {
                        } else {
                            if ( '-1' === $pwddm_action ) {
                                // Delete drivers.
                                $order->delete_meta_data( 'lddfw_driverid' );
                                lddfw_update_sync_order( $order_id, 'lddfw_driverid', '0' );
                                $order->save();
                            } else {
                                // Assign a driver to order.
                                $driver->assign_delivery_driver( $order_id, $pwddm_action, 'store' );
                            }
                        }
                    }
                    if ( 'ajax' !== $type ) {
                        header( 'Location: ' . pwddm_manager_page_url( 'pwddm_screen=orders' ) );
                    }
                }
            }
        }
    }

    /**
     * Orders
     *
     * @since 1.0.0
     * @param int $manager_id manager user id.
     * @return html
     */
    public function pwddm_orders_page( $manager_id ) {
        global $wpdb;
        // Handle orders form post.
        $this->pwddm_orders_form_post( $manager_id, '' );
        global $pwddm_page;
        $pwddm_manager_drivers = get_user_meta( $manager_id, 'pwddm_manager_drivers', true );
        // Get url params.
        $pwddm_orders_filter = ( isset( $_GET['pwddm_orders_filter'] ) ? sanitize_text_field( wp_unslash( $_GET['pwddm_orders_filter'] ) ) : '' );
        $pwddm_from_date = ( isset( $_GET['pwddm_from_date'] ) ? sanitize_text_field( wp_unslash( $_GET['pwddm_from_date'] ) ) : '' );
        $pwddm_to_date = ( isset( $_GET['pwddm_to_date'] ) ? sanitize_text_field( wp_unslash( $_GET['pwddm_to_date'] ) ) : '' );
        $pwddm_orders_status = ( isset( $_GET['pwddm_orders_status'] ) ? sanitize_text_field( wp_unslash( $_GET['pwddm_orders_status'] ) ) : '' );
        $pwddm_dates = ( isset( $_GET['pwddm_dates_range'] ) ? sanitize_text_field( wp_unslash( $_GET['pwddm_dates_range'] ) ) : '' );
        /**
         * Set current status names
         */
        $pwddm_driver_assigned_status_name = esc_html( __( 'Driver assigned', 'pwddm' ) );
        $pwddm_out_for_delivery_status_name = esc_html( __( 'Out for delivery', 'pwddm' ) );
        $pwddm_failed_attempt_status_name = esc_html( __( 'Failed delivery', 'pwddm' ) );
        $pwddm_processing_status_name = esc_html( __( 'Processing', 'pwddm' ) );
        if ( function_exists( 'wc_get_order_statuses' ) ) {
            $result = wc_get_order_statuses();
            if ( !empty( $result ) ) {
                foreach ( $result as $key => $status ) {
                    switch ( $key ) {
                        case get_option( 'lddfw_out_for_delivery_status' ):
                            if ( $status !== $pwddm_out_for_delivery_status_name ) {
                                $pwddm_out_for_delivery_status_name = $status;
                            }
                            break;
                        case get_option( 'lddfw_failed_attempt_status' ):
                            if ( esc_html( __( 'Failed Delivery Attempt', 'pwddm' ) ) !== $status ) {
                                $pwddm_failed_attempt_status_name = $status;
                            }
                            break;
                        case get_option( 'lddfw_driver_assigned_status' ):
                            if ( $status !== $pwddm_driver_assigned_status_name ) {
                                $pwddm_driver_assigned_status_name = $status;
                            }
                            break;
                    }
                }
            }
        }
        $user_query = PWDDM_Driver::pwddm_get_drivers( $manager_id, 'all' );
        $drivers = $user_query->get_results();
        $html = '
		<form method="POST" name="pwddm_orders_form" id="pwddm_orders_form" action="' . pwddm_manager_page_url( 'pwddm_screen=orders' ) . '">
		<input type="hidden" name="pwddm_wpnonce" value="' . wp_create_nonce( 'pwddm-nonce' ) . '">
		<div class="row">


		<div class="col-12">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item" role="presentation">
				<a class="nav-link active" id="filter-tab" data-bs-toggle="tab" data-bs-target="#filter" type="button" role="tab" aria-controls="filter" aria-selected="true">' . esc_html( __( 'Filter', 'pwddm' ) ) . '</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" id="filter-tab" data-bs-toggle="tab" data-bs-target="#bulk-action" type="button" role="tab" aria-controls="bulk-action" aria-selected="true">' . pwddm_premium_feature( '' ) . esc_html( __( 'Bulk actions', 'pwddm' ) ) . '</a>
		    </li>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content" style="margin-top:10px;">
		 <div class="tab-pane " id="bulk-action" role="tabpanel" aria-labelledby="bulk-action-tab">
		 <div class="row">';
        if ( pwddm_is_free() ) {
            $content = '<div class="col-12 col-md" style="margin-bottom:10px">' . '<p>' . pwddm_premium_feature( '' ) . ' ' . esc_html( __( 'Bulk assign drivers to orders.', 'pwddm' ) ) . '</p>' . '<p>' . pwddm_premium_feature( '' ) . ' ' . esc_html( __( 'Bulk update order statuses.', 'pwddm' ) ) . '</p>';
            $html .= '<div class="container">' . pwddm_premium_feature_notice_content( $content ) . '</div></div>';
        }
        $html .= '</div>
</div>
<div class="tab-pane active" id="filter" role="tabpanel" aria-labelledby="filter-tab">
<div class="row">
		<div class="col-12 col-md" style="margin-bottom:10px">
			<select class="form-select" id="pwddm_orders_status" name="pwddm_orders_status">
				<option value="">' . esc_attr( __( 'All Statuses', 'pwddm' ) ) . '</option>
				<option ' . selected( get_option( 'lddfw_processing_status', '' ), $pwddm_orders_status, false ) . ' value="' . get_option( 'lddfw_processing_status', '' ) . '">' . $pwddm_processing_status_name . '</option>
				<option ' . selected( get_option( 'lddfw_driver_assigned_status', '' ), $pwddm_orders_status, false ) . ' value="' . get_option( 'lddfw_driver_assigned_status', '' ) . '">' . $pwddm_driver_assigned_status_name . '</option>
				<option ' . selected( get_option( 'lddfw_out_for_delivery_status', '' ), $pwddm_orders_status, false ) . ' value="' . get_option( 'lddfw_out_for_delivery_status', '' ) . '"> ' . $pwddm_out_for_delivery_status_name . ' </option>
				<option ' . selected( get_option( 'lddfw_failed_attempt_status', '' ), $pwddm_orders_status, false ) . ' value="' . get_option( 'lddfw_failed_attempt_status', '' ) . '">' . $pwddm_failed_attempt_status_name . '</option>
				<option ' . selected( get_option( 'lddfw_delivered_status', '' ), $pwddm_orders_status, false ) . ' value="' . esc_attr( get_option( 'lddfw_delivered_status' ) ) . '">' . esc_html( __( 'Delivered', 'pwddm' ) ) . '</option>
			</select>
		</div>';
        $date_style = ( get_option( 'lddfw_delivered_status', '' ) === $pwddm_orders_status ? '' : 'display:none' );
        $html .= '<div class="col-12 col-md pwddm_dates_range_col"   style="' . $date_style . ';margin-bottom:10px">
			<select class="form-select" id="pwddm_dates_range" name="pwddm_dates_range" >
				<option value="">' . esc_attr( __( 'All Dates', 'pwddm' ) ) . '</option>	
				<option ' . selected( date_i18n( 'Y-m-d' ) . ',' . date_i18n( 'Y-m-d' ), $pwddm_dates, false ) . ' value="' . date_i18n( 'Y-m-d' ) . ',' . date_i18n( 'Y-m-d' ) . '">' . esc_html( __( 'Today', 'pwddm' ) ) . '</option>
				<option ' . selected( date_i18n( 'Y-m-d', strtotime( '-1 days' ) ) . ',' . date_i18n( 'Y-m-d', strtotime( '-1 days' ) ), $pwddm_dates, false ) . ' value="' . date_i18n( 'Y-m-d', strtotime( '-1 days' ) ) . ',' . date_i18n( 'Y-m-d', strtotime( '-1 days' ) ) . '">' . esc_html( __( 'Yesterday', 'pwddm' ) ) . '</option>
				<option ' . selected( date_i18n( 'Y-m-d', strtotime( 'first day of this month' ) ) . ',' . date_i18n( 'Y-m-d', strtotime( 'last day of this month' ) ), $pwddm_dates, false ) . ' value="' . date_i18n( 'Y-m-d', strtotime( 'first day of this month' ) ) . ',' . date_i18n( 'Y-m-d', strtotime( 'last day of this month' ) ) . '">' . esc_html( __( 'This month', 'pwddm' ) ) . '</option>
				<option ' . selected( date_i18n( 'Y-m-d', strtotime( 'first day of last month' ) ) . ',' . date_i18n( 'Y-m-d', strtotime( 'last day of last month' ) ), $pwddm_dates, false ) . ' value="' . date_i18n( 'Y-m-d', strtotime( 'first day of last month' ) ) . ',' . date_i18n( 'Y-m-d', strtotime( 'last day of last month' ) ) . '">' . esc_html( __( 'Last month', 'pwddm' ) ) . '</option>
			</select>
		</div>

		<div class="col-12 col-md" style="margin-bottom:10px">
			<select name="pwddm_orders_filter"  id="pwddm_orders_filter" class="form-select">
				<option value="">' . __( 'Filter By', 'pwddm' ) . '</option>
				<option ' . selected( '-1', $pwddm_orders_filter, false ) . ' value="-1" ';
        $html .= ( '-1' === $pwddm_orders_filter ? 'selected' : '' );
        $html .= '>' . __( 'With drivers', 'pwddm' ) . '</option>
				<option ' . selected( '-2', $pwddm_orders_filter, false ) . ' value="-2" ';
        $html .= ( '-2' === $pwddm_orders_filter ? 'selected' : '' );
        $html .= '>' . __( 'Without drivers', 'pwddm' ) . '</option>
				';
        $html .= '<optgroup label="' . esc_attr( __( 'Drivers', 'pwddm' ) ) . '"></optgroup>';
        foreach ( $drivers as $driver ) {
            $driver_manager_id = get_user_meta( $driver->ID, 'pwddm_manager', true );
            if ( ('0' === $pwddm_manager_drivers || '' === strval( $pwddm_manager_drivers )) && '' === strval( $driver_manager_id ) || '2' === $pwddm_manager_drivers && strval( $driver_manager_id ) === strval( $manager_id ) || '1' === $pwddm_manager_drivers && (strval( $driver_manager_id ) === strval( $manager_id ) || '' === strval( $driver_manager_id )) ) {
                $driver_name = $driver->display_name;
                $selected = ( strval( $driver->ID ) === $pwddm_orders_filter ? 'selected' : '' );
                $html .= '<option ' . esc_attr( $selected ) . ' value="' . esc_attr( $driver->ID ) . '">' . esc_html( $driver_name ) . '</option>';
            }
        }
        $html .= '
			</select>
		</div>';
        $html .= '<div class="col-12 col-md-2"><button class="btn btn-block btn-primary" name="pwddm_orders_filter_btn" id="pwddm_orders_filter_btn" type="submit">' . esc_html( __( 'Filter', 'pwddm' ) ) . '</button></div>';
        $html .= '</div></div></div><div class="row"><div class="pwddm_date_range col-12">';
        if ( '' === $pwddm_dates ) {
            $html .= date_i18n( lddfw_date_format( 'date' ) );
            $from_date = date_i18n( 'Y-m-d' );
            $to_date = date_i18n( 'Y-m-d' );
        } else {
            $pwddm_dates_array = explode( ',', $pwddm_dates );
            if ( 1 < count( $pwddm_dates_array ) ) {
                if ( $pwddm_dates_array[0] === $pwddm_dates_array[1] ) {
                    $html .= date_i18n( lddfw_date_format( 'date' ), strtotime( $pwddm_dates_array[0] ) );
                    $from_date = date_i18n( 'Y-m-d', strtotime( $pwddm_dates_array[0] ) );
                    $to_date = date_i18n( 'Y-m-d', strtotime( $pwddm_dates_array[0] ) );
                } else {
                    $html .= date_i18n( lddfw_date_format( 'date' ), strtotime( $pwddm_dates_array[0] ) ) . ' - ' . date_i18n( lddfw_date_format( 'date' ), strtotime( $pwddm_dates_array[1] ) );
                    $from_date = date_i18n( 'Y-m-d', strtotime( $pwddm_dates_array[0] ) );
                    $to_date = date_i18n( 'Y-m-d', strtotime( $pwddm_dates_array[1] ) );
                }
            } else {
                $html .= date_i18n( lddfw_date_format( 'date' ), strtotime( $pwddm_dates_array[0] ) );
                $from_date = date_i18n( 'Y-m-d', strtotime( $pwddm_dates_array[0] ) );
                $to_date = date_i18n( 'Y-m-d', strtotime( $pwddm_dates_array[0] ) );
            }
        }
        $html .= '</div>';
        // Orders query.
        $orders_per_page = 50;
        $counter = ( $pwddm_page > 1 ? $orders_per_page * $pwddm_page - $orders_per_page + 1 : 1 );
        // Status.
        if ( '' !== $pwddm_orders_status ) {
            $status_array = array($pwddm_orders_status);
        } else {
            $status_array = array(
                get_option( 'lddfw_driver_assigned_status', '' ),
                get_option( 'lddfw_out_for_delivery_status', '' ),
                get_option( 'lddfw_failed_attempt_status', '' ),
                get_option( 'lddfw_delivered_status', '' ),
                get_option( 'lddfw_processing_status', '' )
            );
        }
        // Filter by orders without drivers.
        $no_driver_array = array();
        if ( '-2' === $pwddm_orders_filter ) {
            $no_driver_array = array(
                'relation' => 'or',
                array(
                    'key'     => 'lddfw_driverid',
                    'value'   => '-1',
                    'compare' => '=',
                ),
                array(
                    'key'     => 'lddfw_driverid',
                    'value'   => '',
                    'compare' => '=',
                ),
                array(
                    'key'     => 'lddfw_driverid',
                    'compare' => 'NOT EXISTS',
                ),
            );
        }
        // Filter by orders without drivers.
        $filter_array = array();
        if ( '-1' === $pwddm_orders_filter ) {
            $filter_array = array(
                'relation' => 'and',
                array(
                    'key'     => 'lddfw_driverid',
                    'value'   => '-1',
                    'compare' => '!=',
                ),
                array(
                    'key'     => 'lddfw_driverid',
                    'compare' => 'EXISTS',
                ),
            );
        }
        // Filter by driver id.
        $driver_array = array();
        if ( 0 < intval( $pwddm_orders_filter ) ) {
            $driver_array = array(
                'key'     => 'lddfw_driverid',
                'value'   => $pwddm_orders_filter,
                'compare' => '=',
            );
        }
        $date_array = array();
        // Filter for delivered date range.
        if ( '' !== $from_date && '' !== $to_date && '' !== $pwddm_dates ) {
            $date_array = array(
                'relation' => 'and',
                array(
                    'key'     => 'lddfw_delivered_date',
                    'value'   => $from_date,
                    'compare' => '>=',
                    'type'    => 'DATE',
                ),
                array(
                    'key'     => 'lddfw_delivered_date',
                    'value'   => $to_date,
                    'compare' => '<=',
                    'type'    => 'DATE',
                ),
            );
        }
        $params = array(
            'posts_per_page' => $orders_per_page,
            'post_status'    => $status_array,
            'post_type'      => 'shop_order',
            'paginate'       => true,
            'return'         => 'ids',
            'paged'          => $pwddm_page,
            'orderby'        => array(
                'ID' => 'DESC',
            ),
        );
        $params['meta_query'] = array(
            'relation' => 'AND',
            $date_array,
            $driver_array,
            $filter_array,
            $no_driver_array,
        );
        $orders = wc_get_orders( $params );
        $date_format = lddfw_date_format( 'date' );
        $time_format = lddfw_date_format( 'time' );
        $orders_array = $orders->orders;
        $html .= '
			</div>

		<div class="row">
		<div class="col-12">';
        if ( $orders ) {
            // Pagination.
            $base = pwddm_manager_page_url( 'pwddm_screen=orders&pwddm_orders_filter=' . $pwddm_orders_filter . '&pwddm_orders_status=' . $pwddm_orders_status . '&pwddm_from_date=' . $pwddm_from_date . '&pwddm_to_date=' . $pwddm_to_date ) . '&pwddm_page=%#%';
            $pagination = paginate_links( array(
                'base'         => $base,
                'total'        => $orders->max_num_pages,
                'current'      => $pwddm_page,
                'format'       => '&pwddm_page=%#%',
                'show_all'     => false,
                'type'         => 'array',
                'end_size'     => 2,
                'mid_size'     => 0,
                'prev_next'    => true,
                'prev_text'    => sprintf( '<i></i> %1$s', __( '<<', 'pwddm' ) ),
                'next_text'    => sprintf( '%1$s <i></i>', __( '>>', 'pwddm' ) ),
                'add_args'     => false,
                'add_fragment' => '',
            ) );
            if ( !empty( $pagination ) ) {
                $html .= '<div class="pagination text-sm-center"><nav aria-label="Page navigation" style="width:100%"><ul class="pagination justify-content-center">';
                foreach ( $pagination as $page ) {
                    $html .= "<li class='page-item ";
                    if ( strpos( $page, 'current' ) !== false ) {
                        $html .= ' active';
                    }
                    $html .= "'> " . str_replace( 'page-numbers', 'page-link', $page ) . '</li>';
                }
                $html .= '</nav></div>';
            }
            $html .= '	<div class="table-responsive"><table class="table table-striped table-hover">
				<thead class="table-dark">
					<tr>
						<th scope="row">
							<div class="form-check custom-checkbox">
								<input value="" id="pwddm_multi_checkbox" type="checkbox" data="order_checkbox" class="form-check-input" >
								<label class="custom-control-label" for="pwddm_multi_checkbox"></label>
							</div>
						</th>
					 
						<th scope="col">' . esc_html( __( 'Order', 'pwddm' ) ) . '</th>
						<th scope="col">' . esc_html( __( 'Date', 'pwddm' ) ) . '</th>
						<th scope="col">' . esc_html( __( 'Customer', 'pwddm' ) ) . '</th>
						<th scope="col">' . esc_html( __( 'Shipping address', 'pwddm' ) ) . '</th>
						<th scope="col">' . esc_html( __( 'Status', 'pwddm' ) ) . '</th>
						<th scope="col">' . esc_html( __( 'Driver', 'pwddm' ) ) . '</th>
					</tr>
				</thead>
				<tbody>';
            // Results.
            foreach ( $orders_array as $order_id ) {
                $order = wc_get_order( $order_id );
                $order_number = $order->get_order_number();
                $order_date = $order->get_date_created()->format( lddfw_date_format( 'date' ) );
                $order_status = $order->get_status();
                $order_status_name = wc_get_order_status_name( $order_status );
                $billing_address_1 = $order->get_billing_address_1();
                $billing_address_2 = $order->get_billing_address_2();
                $billing_city = $order->get_billing_city();
                $billing_state = $order->get_billing_state();
                $billing_postcode = $order->get_billing_postcode();
                $billing_country = $order->get_billing_country();
                $billing_first_name = $order->get_billing_first_name();
                $billing_last_name = $order->get_billing_last_name();
                $billing_company = $order->get_billing_company();
                $shipping_company = $order->get_shipping_company();
                $shipping_first_name = $order->get_shipping_first_name();
                $shipping_last_name = $order->get_shipping_last_name();
                $shipping_address_1 = $order->get_shipping_address_1();
                $shipping_address_2 = $order->get_shipping_address_2();
                $shipping_city = $order->get_shipping_city();
                $shipping_state = $order->get_shipping_state();
                $shipping_postcode = $order->get_shipping_postcode();
                $shipping_country = $order->get_shipping_country();
                /**
                 * If shipping info is missing if show the billing info
                 */
                if ( '' === $shipping_first_name && '' === $shipping_address_1 ) {
                    $shipping_first_name = $billing_first_name;
                    $shipping_last_name = $billing_last_name;
                    $shipping_address_1 = $billing_address_1;
                    $shipping_address_2 = $billing_address_2;
                    $shipping_city = $billing_city;
                    $shipping_state = $billing_state;
                    $shipping_postcode = $billing_postcode;
                    $shipping_country = $billing_country;
                    $shipping_company = $billing_company;
                }
                if ( '' !== $shipping_country ) {
                    $shipping_country = WC()->countries->countries[$shipping_country];
                }
                $array = array(
                    'first_name' => $shipping_first_name,
                    'last_name'  => $shipping_last_name,
                    'company'    => $shipping_company,
                    'street_1'   => $shipping_address_1,
                    'street_2'   => $shipping_address_2,
                    'city'       => $shipping_city,
                    'zip'        => $shipping_postcode,
                    'country'    => $shipping_country,
                    'state'      => $shipping_state,
                );
                // Format address.
                $shipping_address = $shipping_first_name . ' ' . $shipping_last_name . ', ' . lddfw_format_address( 'address_line', $array );
                $order_driverid = $order->get_meta( 'lddfw_driverid' );
                $driver = get_userdata( $order_driverid );
                $driver_name = ( !empty( $driver ) ? $driver->display_name : '' );
                $driver_manager_id = '';
                if ( '' !== $order_driverid && '-1' !== $order_driverid ) {
                    $driver_manager_id = get_user_meta( $order_driverid, 'pwddm_manager', true );
                }
                if ( '' === $order_driverid || '-1' === $order_driverid || (('0' === $pwddm_manager_drivers || '' === $pwddm_manager_drivers) && '' === strval( $driver_manager_id ) || '2' === $pwddm_manager_drivers && strval( $driver_manager_id ) === strval( $manager_id ) || '1' === $pwddm_manager_drivers && (strval( $driver_manager_id ) === strval( $manager_id ) || '' === strval( $driver_manager_id ))) ) {
                    $html .= '
				<tr>
				<th scope="row">
					<div class="custom-control custom-checkbox">
						<input name="pwddm_order_id[]" value="' . $order_id . '" id="pwddm_order_id_' . $counter . '" type="checkbox" class="order_checkbox form-check-input" order_number="' . $order_number . '" >
						<label class="custom-control-label" for="pwddm_order_id_' . $counter . '"></label>
					</div>
				</th>
				<td><a href="' . esc_attr( pwddm_manager_page_url( 'pwddm_screen=order&pwddm_orderid=' . $order_id ) ) . '" >' . esc_html( $order->get_order_number() ) . '</a></td>
				<td>' . esc_html( $order_date ) . '</td>
				<td>' . $billing_first_name . ' ' . $billing_last_name . '</td>
				<td>' . $shipping_address . '</td>
				<td>' . esc_html( $order_status_name ) . '</td>
				<td>' . $driver_name . '</td>
			  </tr>';
                    $counter++;
                }
            }
            // end while
            $html .= '</table></div></form>';
            if ( !empty( $pagination ) ) {
                $html .= '<div class="pagination text-sm-center"><nav aria-label="Page navigation" style="width:100%"><ul class="pagination justify-content-center">';
                foreach ( $pagination as $page ) {
                    $html .= "<li class='page-item ";
                    if ( strpos( $page, 'current' ) !== false ) {
                        $html .= ' active';
                    }
                    $html .= "'> " . str_replace( 'page-numbers', 'page-link', $page ) . '</li>';
                }
                $html .= '</nav></div>';
            }
        } else {
            $html .= '<div class="pwddm_box min pwddm_no_orders"><p>' . esc_html( __( 'There are no orders.', 'pwddm' ) ) . '</p></div>';
        }
        $html .= '</div></div>   ';
        return $html;
    }

}

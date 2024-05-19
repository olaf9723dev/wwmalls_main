<?php

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
if ( !class_exists( 'WooCommerce' ) ) {
    die( esc_html( __( 'Delivery drivers manager is a WooCommerce add-on, you must activate a WooCommerce on your site.', 'pwddm' ) ) );
}
if ( !pwddm_fs_is_parent_active_and_loaded() ) {
    die( esc_html( __( 'Delivery drivers manager is a local delivery drivers for woocommerce premium add-on, you must activate it on your site.', 'pwddm' ) ) );
}
/**
 * Get WordPress query_var.
 */
$pwddm_screen = ( '' !== get_query_var( 'pwddm_screen' ) ? get_query_var( 'pwddm_screen' ) : 'dashboard' );
$pwddm_order_id = get_query_var( 'pwddm_orderid' );
$pwddm_reset_key = get_query_var( 'pwddm_reset_key' );
$pwddm_page = ( '' !== get_query_var( 'pwddm_page' ) ? get_query_var( 'pwddm_page' ) : '1' );
$pwddm_reset_login = get_query_var( 'pwddm_reset_login' );
$pwddm_dates = get_query_var( 'pwddm_dates' );
$pwddm_driverid = get_query_var( 'pwddm_driverid' );
$pwddm_status = get_query_var( 'pwddm_status' );
/**
 * Set global variables.
*/
$pwddm_manager = new PWDDM_Manger();
$pwddm_screens = new PWDDM_Screens();
$pwddm_content = '';
$pwddm_manager_id = '';
$pwddm_user_is_manager = 0;
/**
 * Log out manager.
*/
if ( 'logout' === $pwddm_screen ) {
    PWDDM_Login::pwddm_logout();
}
/**
 * Check if user is logged in.
*/
if ( !is_user_logged_in() ) {
    $pwddm_content = $pwddm_screens->pwddm_home();
} else {
    // Check if user is a manager.
    $pwddm_user = wp_get_current_user();
    $pwddm_manager_id = $pwddm_user->ID;
    $pwddm_manager_account = get_user_meta( $pwddm_manager_id, 'pwddm_manager_account', true );
    $pwddm_manager_drivers = get_user_meta( $pwddm_manager_id, 'pwddm_manager_drivers', true );
    if ( !in_array( pwddm_manager_role(), (array) $pwddm_user->roles, true ) || '1' !== $pwddm_manager_account ) {
        // PWDDM_Login::pwddm_logout();
        // User is not a manager.
        $pwddm_content = $pwddm_screens->pwddm_home();
    } else {
        /**
         * User is a manager.
         */
        // Set global variables.
        $pwddm_user_is_manager = 1;
        $pwddm_manager_name = $pwddm_user->display_name;
        // Get the number of orders in each status.
        $pwddm_orders = new PWDDM_Orders();
        $pwddm_array = $pwddm_orders->pwddm_orders_count_query( $pwddm_manager_id );
        $pwddm_out_for_delivery_counter = 0;
        $pwddm_failed_attempt_counter = 0;
        $pwddm_delivered_counter = 0;
        $pwddm_assign_to_driver_counter = 0;
        $pwddm_claim_orders_counter = 0;
        /**
         * Set current status names
         */
        $pwddm_manager_assigned_status_name = esc_html( __( 'Driver assigned', 'pwddm' ) );
        $pwddm_out_for_delivery_status_name = esc_html( __( 'Out for delivery', 'pwddm' ) );
        $pwddm_failed_attempt_status_name = esc_html( __( 'Failed delivery', 'pwddm' ) );
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
                            if ( $status !== esc_html( __( 'Failed Delivery Attempt', 'pwddm' ) ) ) {
                                $pwddm_failed_attempt_status_name = $status;
                            }
                            break;
                        case get_option( 'lddfw_driver_assigned_status' ):
                            if ( $status !== $pwddm_manager_assigned_status_name ) {
                                $pwddm_manager_assigned_status_name = $status;
                            }
                            break;
                    }
                }
            }
        }
        foreach ( $pwddm_array as $row ) {
            switch ( $row->post_status ) {
                case get_option( 'lddfw_out_for_delivery_status' ):
                    $pwddm_out_for_delivery_counter = $row->orders;
                    break;
                case get_option( 'lddfw_failed_attempt_status' ):
                    $pwddm_failed_attempt_counter = $row->orders;
                    break;
                case get_option( 'lddfw_delivered_status' ):
                    $pwddm_delivered_counter = $row->orders;
                    break;
                case get_option( 'lddfw_driver_assigned_status' ):
                    $pwddm_assign_to_driver_counter = $row->orders;
                    break;
            }
        }
        /**
         * Manager screens.
         */
        if ( 'dashboard' === $pwddm_screen ) {
            $pwddm_content = $pwddm_screens->pwddm_dashboard_screen( $pwddm_manager_id );
        }
        if ( 'reports' === $pwddm_screen ) {
            $pwddm_content = $pwddm_screens->pwddm_reports_screen( $pwddm_manager_id );
        }
        if ( 'driver' === $pwddm_screen && '' !== $pwddm_driverid ) {
            $pwddm_content = $pwddm_screens->pwddm_driver_screen( $pwddm_manager_id, $pwddm_driverid );
        }
        if ( 'drivers' === $pwddm_screen ) {
            $pwddm_content = $pwddm_screens->pwddm_drivers_screen( $pwddm_manager_id );
        }
        if ( 'orders' === $pwddm_screen ) {
            $pwddm_content = $pwddm_screens->pwddm_orders_screen( $pwddm_manager_id );
        }
        if ( 'order' === $pwddm_screen && '' !== $pwddm_order_id ) {
            $pwddm_content = $pwddm_screens->pwddm_order_screen( $pwddm_manager_id );
        }
        if ( 'settings' === $pwddm_screen ) {
            $pwddm_content = $pwddm_screens->pwddm_settings_screen( $pwddm_manager_id );
        }
    }
}
/**
 * Register scripts and css files
 */
wp_register_script(
    'pwddm-jquery-validate',
    plugin_dir_url( __FILE__ ) . 'public/js/jquery.validate.min.js',
    array(
        'jquery',
        'jquery-ui-core',
        'jquery-ui-datepicker',
        'jquery-ui-sortable'
    ),
    PWDDM_VERSION,
    true
);
wp_register_script(
    'pwddm-bootstrap',
    plugin_dir_url( __FILE__ ) . 'public/js/bootstrap.min.js',
    array(),
    PWDDM_VERSION,
    false
);
wp_register_script(
    'pwddm-public',
    plugin_dir_url( __FILE__ ) . 'public/js/pwddm-public.js',
    array(),
    PWDDM_VERSION,
    false
);
wp_register_script(
    'wc-country-select',
    plugins_url() . '/woocommerce/assets/js/frontend/country-select.min.js',
    array('jquery'),
    false
);
wp_register_style(
    'pwddm-jquery-ui',
    plugin_dir_url( __FILE__ ) . 'public/css/jquery-ui.css',
    array(),
    PWDDM_VERSION,
    'all'
);
wp_register_style(
    'pwddm-bootstrap',
    plugin_dir_url( __FILE__ ) . 'public/css/bootstrap.min.css',
    array(),
    PWDDM_VERSION,
    'all'
);
wp_register_style(
    'pwddm-fonts',
    'https://fonts.googleapis.com/css?family=Open+Sans|Roboto&display=swap',
    array(),
    PWDDM_VERSION,
    'all'
);
wp_register_style(
    'pwddm-public',
    plugin_dir_url( __FILE__ ) . 'public/css/pwddm-public.css',
    array(),
    PWDDM_VERSION,
    'all'
);
?>
<!DOCTYPE html>
<html>
<head>
<?php 
echo '<title>' . esc_js( __( 'manager', 'pwddm' ) ) . '</title>';
?>
<meta name="robots" content="noindex" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="icon" href="<?php 
echo get_site_icon_url( 32, esc_url( plugin_dir_url( __FILE__ ) . 'public/images/favicon-32x32.png?ver=' . PWDDM_VERSION ) );
?>" >
<?php 
wp_print_styles( array(
    'pwddm-fonts',
    'pwddm-bootstrap',
    'pwddm-public',
    'pwddm-jquery-ui'
) );
if ( is_rtl() === true ) {
    wp_register_style(
        'pwddm-public-rtl',
        plugin_dir_url( __FILE__ ) . 'public/css/pwddm-public-rtl.css',
        array(),
        PWDDM_VERSION,
        'all'
    );
    wp_print_styles( array('pwddm-public-rtl') );
}
wp_print_scripts( array('pwddm-jquery-validate') );
echo '<script>
	var pwddm_manager_id = "' . esc_js( $pwddm_manager_id ) . '";
	var pwddm_ajax_url = "' . esc_url( admin_url( 'admin-ajax.php' ) ) . '";
	var pwddm_confirm_text = "' . esc_js( __( 'Are you sure?', 'pwddm' ) ) . '";
	var pwddm_nonce = "' . esc_js( wp_create_nonce( 'pwddm-nonce' ) ) . '";
	var pwddm_hour_text = "' . esc_js( __( 'hour', 'pwddm' ) ) . '";
	var pwddm_hours_text = "' . esc_js( __( 'hours', 'pwddm' ) ) . '";
	var pwddm_mins_text = "' . esc_js( __( 'mins', 'pwddm' ) ) . '";
	var pwddm_dates = "' . esc_js( $pwddm_dates ) . '";
</script>';
?>


</head>
<body>
	<div id="pwddm_page" class="<?php 
echo $pwddm_screen;
?>" >
	<?php 
if ( 'routes' === $pwddm_screen && 1 === $pwddm_user_is_manager ) {
    echo $pwddm_screens->pwddm_routes_screen( $pwddm_manager_id );
} else {
    echo $pwddm_content;
}
?>
	</div>
<?php 
wp_print_scripts( array(
    'wc-country-select',
    'pwddm-bootstrap',
    'pwddm-signature',
    'pwddm-public'
) );
?>
</body>
</html>

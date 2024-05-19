<?php
/*
Plugin Name:	Import and export users and customers - Allow no email
Plugin URI:		https://www.codection.com
Description:	Using this addon you will be able to import users without email
Version:		1.1.1
Author:			codection
Author URI: 	https://codection.com
License:     	GPL2
License URI: 	https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:    import-users-from-csv-with-meta
Domain Path:    /languages
*/

if ( ! defined( 'ABSPATH' ) ) 
	exit;

define( 'ACUI_ANE_PATH', plugin_dir_path( __FILE__ ) );
define( 'ACUI_ANE_URL', plugins_url('', __FILE__) );
define( 'ACUI_ANE_VERSION', '1.1.1' );
define( 'ACUI_ANE_WS_DB_VERSION', '1.1' );
define( 'ACUI_ANE_PRODUCT_ID', 'ACUI_ANE' );
define( 'ACUI_ANE_INSTANCE', str_replace(array ("https://" , "http://"), "", trim(network_site_url(), '/')) );
define( 'ACUI_ANE_UPDATE_API_URL', 'https://import-wp.com/index.php' );

include( 'class-admin.php' );
include( 'class-updates.php' );
include( 'class-filters.php' );
include( 'class-gui.php' );

function acui_ane_check_active() {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
	if( !is_plugin_active( 'import-users-from-csv-with-meta/import-users-from-csv-with-meta.php' ) ) {
        add_action( 'admin_notices', 'acui_ane_loader_no_active' );
    }    
}
add_action( 'admin_init', 'acui_ane_check_active' );

function acui_ane_loader_no_active() {
	?>
	<div class="error">
		<p><?php _e( '<strong>Import and export users and customers</strong> has to be active to use <strong>Allow no email</strong>', 'import-users-from-csv-with-meta' ); ?></p>
	</div>
	<?php
}

add_action( 'after_setup_theme', function(){
    $wp_plugin_auto_update = new ACUI_ANE_Updates( ACUI_ANE_UPDATE_API_URL, 'ACUI_EC', 'import-export-users-customers-allow-no-email/import-export-users-customers-allow-no-email.php' );

	add_filter( 'pre_set_site_transient_update_plugins', array( $wp_plugin_auto_update, 'check_for_plugin_update' ) );
	add_filter( 'plugins_api', array( $wp_plugin_auto_update, 'plugins_api_call' ), 10, 3 );

    new ACUI_ANE_Admin( "Import and Export Users and Customers - Allow No Email Addon", "acui_ane_license", 'acui_ane_' );
});
<?php
/**
 * Plugin Name: Motta Addons
 * Plugin URI: http://uixthemes.com/plugins/motta-addons.zip
 * Description: Extra elements for Elementor. It was built for Motta theme.
 * Version: 1.1.0
 * Author: Uixthemes
 * Author URI: http://uixthemes.com
 * License: GPL2+
 * Text Domain: motta-addons
 * Domain Path: /languages
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! defined( 'MOTTA_ADDONS_VER' ) ) {
	define( 'MOTTA_ADDONS_VER', '1.0.0' );
}

if ( ! defined( 'MOTTA_ADDONS_DIR' ) ) {
	define( 'MOTTA_ADDONS_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'MOTTA_ADDONS_URL' ) ) {
	define( 'MOTTA_ADDONS_URL', plugin_dir_url( __FILE__ ) );
}

require_once MOTTA_ADDONS_DIR . 'plugin.php';

\Motta\Addons::instance();
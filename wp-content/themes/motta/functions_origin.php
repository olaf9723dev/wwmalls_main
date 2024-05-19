<?php
/**
 * Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Motta
 */

require_once get_template_directory() . '/inc/theme.php';

\Motta\Theme::instance()->init();

if (!class_exists('My_Custom_Dokan_Admin')) {
   
}

//  add_action( 'admin_menu', 'my_plugin_override',10 );
// function my_plugin_override() {
   
//         add_submenu_page(
//                 'dokan', // Ana menü slug'ı
//                 'Alt Menü Başlığı', // Alt menü başlığı
//                 'Alt Menü', // Menü adı
//                 'manage_options', // Görüntüleme yetkisi
//                 'wwmalls-submenu'  // Alt menü slug'ı 
//             );
// }

 
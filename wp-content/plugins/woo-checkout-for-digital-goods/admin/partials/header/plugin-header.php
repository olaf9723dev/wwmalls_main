<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
global $wcfdg_fs;
$plugin_version = esc_html( 'v' . WCDG_PLUGIN_VERSION );
$version_label = 'Free';
$plugin_slug = 'basic_digital_goods';
$current_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
$general_setting = ( isset( $current_page ) && 'wcdg-general-setting' === $current_page ? 'active' : '' );
$quick_checkout = ( isset( $current_page ) && 'wcdg-quick-checkout' === $current_page ? 'active' : '' );
$wcdg_getting_started = ( isset( $current_page ) && 'wcdg-get-started' === $current_page ? 'active' : '' );
$wcdg_information = ( isset( $current_page ) && 'wcdg-information' === $current_page ? 'active' : '' );
$wcdg_import_export = ( isset( $current_page ) && 'wcdg-import-export' === $current_page ? 'active' : '' );
$wcdg_account_page = ( isset( $current_page ) && 'wcdg-general-setting-account' === $current_page ? 'active' : '' );
$wcdg_settings_menu = ( isset( $current_page ) && ('wcdg-import-export' === $current_page || 'wcdg-get-started' === $current_page || 'wcdg-information' === $current_page) || !(wcfdg_fs()->is__premium_only() && wcfdg_fs()->can_use_premium_code()) && 'wcdg-general-setting-account' === $current_page ? 'active' : '' );
if ( isset( $current_page ) && 'wcdg-get-started' === $current_page || isset( $current_page ) && 'wcdg-information' === $current_page ) {
    $fee_about = 'active';
} else {
    $fee_about = '';
}
$wcdg_display_submenu = ( !empty( $wcdg_settings_menu ) && 'active' === $wcdg_settings_menu ? 'display:inline-block' : 'display:none' );
$admin_object = new Woo_Checkout_For_Digital_Goods_Admin('', '');
?>
<div id="dotsstoremain">
    <div class="all-pad">
        <?php 
// Get Dynamic promotional bar
$admin_object->wcdg_get_promotional_bar( $plugin_slug );
?>
        <header class="dots-header">
            <div class="dots-plugin-details">
                <div class="dots-header-left">
                    <div class="dots-logo-main">
                        <img src="<?php 
echo esc_url( WCDG_PLUGIN_URL . 'admin/images/woo-digital-goods-checkout-icon.png' );
?>">
                    </div>
                    <div class="plugin-name">
                        <div class="title"><?php 
esc_html_e( 'Digital Goods For Checkout', 'woo-checkout-for-digital-goods' );
?></div>
                    </div>
                    <span class="version-label"><?php 
echo esc_html( $version_label );
?></span>
                    <span class="version-number"><?php 
echo esc_html( $plugin_version );
?></span>
                </div>
                <div class="dots-header-right">
                    <div class="button-dots">
                        <a target="_blank" href="<?php 
echo esc_url( 'http://www.thedotstore.com/support/' );
?>">
                            <?php 
esc_html_e( 'Support', 'woo-checkout-for-digital-goods' );
?>
                        </a>
                    </div>
                    <div class="button-dots">
                        <a target="_blank" href="<?php 
echo esc_url( 'https://www.thedotstore.com/feature-requests/' );
?>">
                            <?php 
esc_html_e( 'Suggest', 'woo-checkout-for-digital-goods' );
?>
                        </a>
                    </div>
                    <div class="button-dots">
                        <a target="_blank" href="<?php 
echo esc_url( 'https://docs.thedotstore.com/category/170-premium-plugin-settings' );
?>">
                            <?php 
esc_html_e( 'Help', 'woo-checkout-for-digital-goods' );
?>
                        </a>
                    </div>
                </div>
            </div>
            <div class="dots-menu-main">
                <nav>
                    <ul>
                        <li>
                            <a class="dotstore_plugin <?php 
echo esc_attr( $general_setting );
?>" href="<?php 
echo esc_url( add_query_arg( array(
    'page' => 'wcdg-general-setting',
), admin_url( 'admin.php' ) ) );
?>"><?php 
esc_html_e( 'General Setting', 'woo-checkout-for-digital-goods' );
?></a>
                        </li>
                        <?php 
$get_settings_page_url = '';
$get_settings_page_url = add_query_arg( array(
    'page' => 'wcdg-get-started',
), admin_url( 'admin.php' ) );
?>
                        <li>
                            <a class="dotstore_plugin <?php 
echo esc_attr( $wcdg_settings_menu );
?>" href="<?php 
echo esc_url( $get_settings_page_url );
?>"><?php 
esc_html_e( 'Settings', 'woo-checkout-for-digital-goods' );
?></a>
                        </li>
                        <?php 
if ( wcfdg_fs()->is__premium_only() && wcfdg_fs()->can_use_premium_code() ) {
    ?>
                            <li>
                                <a class="dotstore_plugin <?php 
    echo esc_attr( $wcdg_account_page );
    ?>" href="<?php 
    echo esc_url( $wcfdg_fs->get_account_url() );
    ?>"><?php 
    esc_html_e( 'License', 'woo-checkout-for-digital-goods' );
    ?></a>
                            </li>
                            <?php 
}
?>
                    </ul>
                </nav>
            </div>
        </header>
        <div class="dots-settings-inner-main">
            <div class="dots-settings-left-side">
                <div class="dots-submenu-items" style="<?php 
echo esc_attr( $wcdg_display_submenu );
?>">
                    <ul>
                        <?php 
?>
                        <li><a class="<?php 
echo esc_attr( $wcdg_getting_started );
?>" href="<?php 
echo esc_url( add_query_arg( array(
    'page' => 'wcdg-get-started',
), admin_url( 'admin.php' ) ) );
?>"><?php 
esc_html_e( 'About', 'woo-checkout-for-digital-goods' );
?></a></li>
                        <li><a class="<?php 
echo esc_attr( $wcdg_information );
?>" href="<?php 
echo esc_url( add_query_arg( array(
    'page' => 'wcdg-information',
), admin_url( 'admin.php' ) ) );
?>"><?php 
esc_html_e( 'Quick Info', 'woo-checkout-for-digital-goods' );
?></a></li>
                        <?php 
if ( !(wcfdg_fs()->is__premium_only() && wcfdg_fs()->can_use_premium_code()) ) {
    $check_account_page_exist = menu_page_url( 'wcdg-general-setting-account', false );
    if ( isset( $check_account_page_exist ) && !empty( $check_account_page_exist ) ) {
        ?>
                                <li>
                                    <a class="<?php 
        echo esc_attr( $wcdg_account_page );
        ?>" href="<?php 
        echo esc_url( $wcfdg_fs->get_account_url() );
        ?>"><?php 
        esc_html_e( 'Account', 'woo-checkout-for-digital-goods' );
        ?></a>
                                </li>
                                <?php 
    }
}
?>
                        <li><a href="<?php 
echo esc_url( 'https://www.thedotstore.com/plugins/' );
?>" target="_blank"><?php 
esc_html_e( 'Shop Plugins', 'woo-checkout-for-digital-goods' );
?></a></li>
                    </ul>
                </div>
                <hr class="wp-header-end" />
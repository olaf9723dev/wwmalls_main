<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

require_once(plugin_dir_path( __FILE__ ).'header/plugin-header.php');
?>
<div class="wcdg-main-left-section res-cl">
    <div class="wcdg-main-table wcdg-getting-started res-cl">
        <h2><?php esc_html_e('Getting Started', 'woo-checkout-for-digital-goods'); ?></h2>
        <table class="table-outer">
            <tbody>
                <tr>
                    <td class="fr-2">
                        <p><?php esc_html_e( 'Try this WooCommerce quick checkout plugin to eliminate overly-complicated billing/shipping details for virtual and downloadable products.', 'woo-checkout-for-digital-goods' ); ?></p>
                        <p class="block gettingstarted textgetting">
                            <strong><?php esc_html_e('Step 1:', 'woo-checkout-for-digital-goods'); ?></strong> <?php esc_html_e('Easily enable or disable quick checkout with just a click!', 'woo-checkout-for-digital-goods'); ?>
                            <span class="gettingstarted">
                                <img src="<?php echo esc_url(WCDG_PLUGIN_URL . 'admin/images/getting-started/Getting_Started_01.png'); ?>">
                            </span>
                        </p>
                        <p class="block gettingstarted textgetting">
                            <strong><?php esc_html_e('Step 2:', 'woo-checkout-for-digital-goods'); ?></strong> <?php esc_html_e('Customize checkout fields and choose what information you need from your customers.', 'woo-checkout-for-digital-goods'); ?>
                            <span class="gettingstarted">
                                <img src="<?php echo esc_url(WCDG_PLUGIN_URL . 'admin/images/getting-started/Getting_Started_02.png'); ?>">
                            </span>
                        </p>
                        <p class="block gettingstarted textgetting">
                            <strong><?php esc_html_e('Step 3:', 'woo-checkout-for-digital-goods'); ?></strong> <?php esc_html_e('Exclude order notes and keep your checkout clean.', 'woo-checkout-for-digital-goods'); ?>
                        </p>
                        <p class="block gettingstarted textgetting">
                            <strong><?php esc_html_e('Step 4:', 'woo-checkout-for-digital-goods'); ?></strong> <?php esc_html_e('Display quick checkout buttons on shop or product pages for digital goods.', 'woo-checkout-for-digital-goods'); ?>
                            <span class="gettingstarted">
                                <img src="<?php echo esc_url(WCDG_PLUGIN_URL . 'admin/images/getting-started/Getting_Started_03.png'); ?>">
                            </span>
                        </p>
                        <p class="block gettingstarted textgetting">
                            <strong><?php esc_html_e('Step 5:', 'woo-checkout-for-digital-goods'); ?></strong> <?php esc_html_e('Quick checkout for all downloadable and/or virtual products.', 'woo-checkout-for-digital-goods'); ?>
                        </p>
                        <p class="block gettingstarted textgetting">
                            <strong><?php esc_html_e('Step 6:', 'woo-checkout-for-digital-goods'); ?></strong> <?php esc_html_e('Customize your checkout page by excluding selected fields.', 'woo-checkout-for-digital-goods'); ?>
                            <span class="gettingstarted">
                                <img src="<?php echo esc_url(WCDG_PLUGIN_URL . 'admin/images/getting-started/Getting_Started_04.png'); ?>">
                            </span>
                        </p>
                        <p class="block gettingstarted textgetting"><strong><?php esc_html_e('Please note: ', 'woo-checkout-for-digital-goods'); ?></strong><?php esc_html_e('This plugin is compatible with WooCommerce version 2.4.0 and above.', 'woo-checkout-for-digital-goods'); ?></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?php 
require_once(plugin_dir_path( __FILE__ ).'header/plugin-footer.php'); 

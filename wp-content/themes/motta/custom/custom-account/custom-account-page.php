<?php 
    function dequeue_theme_styles_for_wc_account_page() {
        if (is_account_page()) { // Check if it's the WooCommerce account page
            wp_dequeue_style('motta-style-css'); // Replace 'theme-style-handle' with the actual handle used by your theme
        }
    }
    add_action('wp_enqueue_scripts', 'dequeue_theme_styles_for_wc_account_page', 100);
?>
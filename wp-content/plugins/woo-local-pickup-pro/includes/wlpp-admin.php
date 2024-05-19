<?php 
/**
** Add selected store to billing details, admin page
**/
function wpll_show_store_in_admin($order) {
	$order_id = $order->get_id();
	if(!empty($order_id)){
	$location_name = sanitize_text_field(get_post_meta($order_id, '_wpll_pickup_lcoation_name', true));
	$wpll_pickup_date = sanitize_text_field(get_post_meta($order_id, '_wpll_pickup_date', true));
	$location_id = sanitize_text_field(get_post_meta($order_id, '_wpll_pickup_lcoation_id', true));
	$location_address = sanitize_title(get_the_title($location_id));
    }
	if(!empty($location_name)) :
		?>
		<p>
			<strong class="title"><?php echo __('Pickup Location', 'woo-local-pickup') . ':' ?></strong>
			<span class="data"><?php echo $location_name; ?></span>
		</p>
		<?php
	endif;
 	if(!empty($wpll_pickup_date)) :
		?>
		<p>
			<strong class="title"><?php echo __('Pickup Date', 'woo-local-pickup') . ':' ?></strong>
			<span class="data"><?php echo $wpll_pickup_date; ?></span>
		</p>
		<?php
	endif; 
}
add_action('woocommerce_admin_order_data_after_billing_address', 'wpll_show_store_in_admin');

/**
** Add Settings action links
**/

function wpll_plugin_setting_links($links) {
	$id = "wpll-shipping-method";
	$plugin_links = array(
		'<a href="' . admin_url('admin.php?page=wc-settings&tab=shipping&section=' . $id) . '">' . __('Settings', 'woo-local-pickup') . '</a>',
	);
	// Merge our new link with the default ones
	return array_merge($plugin_links, $links);    
}
add_filter('plugin_action_links_' . WPLL_PLUGIN_FILE, 'wpll_plugin_setting_links');

/**
** Add Settings links to custom post
**/

function wpll_pickuplocation_admin_submenu() {
	$id = "wpll-shipping-method";
	add_submenu_page(
		'edit.php?post_type=local-pickup-pro', __('Settings', 'woo-local-pickup'), __('Settings', 'woo-local-pickup'), 'edit_posts', 'admin.php?page=wc-settings&tab=shipping&section=' . $id
	);
}
add_action('admin_menu', 'wpll_pickuplocation_admin_submenu');
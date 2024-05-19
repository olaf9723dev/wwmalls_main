<?php 
// Create woo local pickup pro Post Type
function wlpp_post_type_news() {
	$supports = array('title');
	$labels = array(
	'name' => _x('Local Pickup Location', 'plural'),
	'singular_name' => _x('Local Pickup Pro', 'singular'),
	'menu_name' => _x('Local Pickup Pro', 'admin menu'),
	'name_admin_bar' => _x('Location', 'admin bar'),
	'add_new' => _x('Add New', 'add new'),
	'add_new_item' => __('Add New Location'),
	'new_item' => __('New Location'),
	'edit_item' => __('Edit Location'),
	'view_item' => __('View Location'),
	'all_items' => __('All Locations'),
	'search_items' => __('Search Location'),
	'not_found' => __('No Location found.'),
	);

	$args = array(
	'supports' => $supports,
	'labels' => $labels,
	'public' => false,  // it's not public, it shouldn't have it's own permalink, and so on
	'publicly_queryable' => true,  // you should be able to query it
	'show_ui' => true,
	'query_var' => true,
	'rewrite' => false,
	'has_archive' => false,
	'show_in_nav_menus' => false,
	'exclude_from_search' => false,
	'hierarchical' => false,
    'menu_icon'    => 'dashicons-location',	
	);
	register_post_type('local-pickup-pro', $args);
}
add_action('init', 'wlpp_post_type_news');

function wpll_local_pickup_pro_id_columns($columns) {
	$new = array();
	foreach($columns as $key => $value) {
		if($key == 'title') {
			$new['local-pickup-pro-id'] = __('ID', 'woo-local-pickup');
		}
		$new[$key] = $value;
	}
	$new['checkout_visibility'] = __('Exclude in Checkout?', 'woo-local-pickup');
	return $new;
}
add_filter('manage_edit-local-pickup-pro_columns', 'wpll_local_pickup_pro_id_columns');

function wpll_local_pickup_pro_id_column_content($name, $post_id) {
	$exclude_store = get_post_meta($post_id, '_wlpp_exclude_location', true);
	switch ($name) {
		case 'local-pickup-pro-id':
			echo '<a href="' . get_edit_post_link($post_id) . '">' . $post_id . '</a>';
		break;
		case 'checkout_visibility':
			echo ($exclude_store == 1) ? __('Yes', 'woo-local-pickup') : __('No', 'woo-local-pickup');
		break;
	}
}
add_filter('manage_local-pickup-pro_posts_custom_column', 'wpll_local_pickup_pro_id_column_content', 10, 2);

add_filter('enter_title_here', 'wpll_my_title_place_holder' , 20 , 2 );

function wpll_my_title_place_holder($title , $post){
	if( $post->post_type == 'local-pickup-pro' ){
		$my_title = "Enter Location Title";
		return $my_title;
	}
	return $title;
}

function wlpp_post_meta_box() {
	add_meta_box('checkout-visibility', __( 'Location Visibility', 'woo-local-pickup' ), 'wlpp_post_location_visibility', 'local-pickup-pro', 'side', 'high');
	add_meta_box('store-fields', __( 'Location Details', 'woo-local-pickup' ), 'wlpp_metabox_details_content', 'local-pickup-pro', 'normal', 'high');
}
add_action('add_meta_boxes', 'wlpp_post_meta_box');

function wlpp_post_location_visibility($post) {
	// Display code/markup goes here. Don't forget to include nonces!
	$pid = $post->ID;	
	$exclude_location = get_post_meta( $pid, '_wlpp_exclude_location', true );
	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'wlpp_location_save_content', 'wlpp_location_metabox_nonce' );
	?>
	<div class="container_data_metabox">
		<div class="sub_data_poker">
			<p><strong><?php _e('Exclude Location in checkout.', 'woo-local-pickup'); ?></strong></p>
			<input type="checkbox" name="_wlpp_exclude_location" class="form-control" <?php checked($exclude_location, 1) ?> />		
		</div>
	</div>
	<?php
}

function wlpp_metabox_details_content($post){
	$pid = $post->ID;	
	$wlpp_city = get_post_meta( $pid, 'wlpp_city', true );
	$wlpp_phone = get_post_meta( $pid, 'wlpp_phone', true );	 
	$wlpp_address = get_post_meta( $pid, 'wlpp_address', true );
	$wlpp_shipping_cost = get_post_meta( $pid, 'wlpp_shipping_cost', true );
	$wlpp_location_order_email = get_post_meta( $pid, 'wlpp_location_order_email', true );
	$wlpp_enable_order_email = get_post_meta( $pid, 'wlpp_enable_order_email', true );	 
	$wlpp_ship_cost_category = get_post_meta( $pid, 'wlpp_ship_cost_category', true );	 
	$wlpp_ship_cost_type = get_post_meta( $pid, 'wlpp_ship_cost_type', true );	 
	?>
	<style>
	input.regular-text-cost {
    height: 28px;
    vertical-align: middle;
    }
	</style>
	<table class="form-table">
		<tr>
			<th><?php _e('Cost/Discount', 'woo-local-pickup') ?></th>
			<td>
				<select name='wlpp_ship_cost_category'>
					<option <?php if($wlpp_ship_cost_category){ if($wlpp_ship_cost_category=='cost'){ echo "selected"; } } ?> value="cost">Cost</option>
					<option <?php if($wlpp_ship_cost_category){ if($wlpp_ship_cost_category=='discount'){ echo "selected"; } } ?> value="discount">Discount</option>
				</select>
				<input type="text" name="wlpp_shipping_cost" size="5" placeholder="0" class="regular-text-cost" value="<?php echo $wlpp_shipping_cost; ?>">
				<select name='wlpp_ship_cost_type'>
					<option <?php if($wlpp_ship_cost_type){ if($wlpp_ship_cost_type=='percentege'){ echo "selected"; } } ?> value="percentege">%</option>
					<option <?php if($wlpp_ship_cost_type){ if($wlpp_ship_cost_type=='value'){ echo "selected"; } } ?> value="value"><?php echo get_woocommerce_currency_symbol(); ?></option>
				</select>				
		</tr>
		<tr>
			<th><?php _e('City', 'woo-local-pickup') ?></th>
			<td>
				<input type="text" name="wlpp_city" class="regular-text" value="<?php echo $wlpp_city; ?>">
			</td>
		</tr>
		<tr>
			<th><?php _e('Phone', 'woo-local-pickup') ?></th>
			<td>
				<input type="text" name="wlpp_phone" class="regular-text" value="<?php echo $wlpp_phone; ?>">
			</td>
		</tr>	
		<tr>
			<th><?php _e('Address', 'woo-local-pickup') ?></th>
			<td>
				<?php
					$settings = array('textarea_name' => 'wlpp_address', 'editor_height' => 75);
					wp_editor($wlpp_address, 'wlpp_address', $settings );
				?>
			</td>
		</tr>
	</table>	
	<?php 
}

function wlpp_location_save_post_content($post_id) {
	// Check if our nonce is set.
	if ( ! isset( $_POST['wlpp_location_metabox_nonce'] ) ) { return; }
	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['wlpp_location_metabox_nonce'], 'wlpp_location_save_content' ) ) { return; }
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	$checked = isset( $_POST['_wlpp_exclude_location'] ) ? 1 : 0;
	$checked_order_email = isset( $_POST['wlpp_enable_order_email'] ) ? 1 : 0;
	update_post_meta( $post_id, '_wlpp_exclude_location', $checked );
	update_post_meta( $post_id, 'wlpp_city', sanitize_text_field($_POST['wlpp_city']) );
	update_post_meta( $post_id, 'wlpp_phone', sanitize_text_field($_POST['wlpp_phone']) );
	update_post_meta( $post_id, 'wlpp_address', wp_kses_data($_POST['wlpp_address']));
	update_post_meta( $post_id, 'wlpp_ship_cost_category', wp_kses_data($_POST['wlpp_ship_cost_category']));
	update_post_meta( $post_id, 'wlpp_ship_cost_type', wp_kses_data($_POST['wlpp_ship_cost_type']));
	update_post_meta( $post_id, 'wlpp_location_order_email', sanitize_text_field($_POST['wlpp_location_order_email']) );
	update_post_meta( $post_id, 'wlpp_enable_order_email', sanitize_email($checked_order_email) );
	if(isset($_POST['wlpp_shipping_cost'])) {
		update_post_meta( $post_id, 'wlpp_shipping_cost', sanitize_text_field($_POST['wlpp_shipping_cost']));
	}
}
add_action('save_post', 'wlpp_location_save_post_content');

add_filter( 'post_updated_messages', 'rrw_post_updated_messages' );
function rrw_post_updated_messages( $messages ) {
$post             = get_post();
$post_type        = get_post_type( $post );
$post_type_object = get_post_type_object( $post_type );
$messages['local-pickup-pro'] = array(
    0  => '', // Unused. Messages start at index 1.
    1  => __( 'Location updated.' ),
    2  => __( 'Location updated.' ),
    3  => __( 'Location deleted.'),
    4  => __( 'Location updated.' ),
    /* translators: %s: date and time of the revision */
    5  => isset( $_GET['revision'] ) ? sprintf( __( 'Location restored to revision from %s' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6  => __( 'Location published.' ),
    7  => __( 'Location saved.' ),
    8  => __( 'Location submitted.' ),
    9  => sprintf(
        __( 'Location scheduled for: <strong>%1$s</strong>.' ),
        // translators: Publish box date format, see http://php.net/date
        date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) )
    ),
    10 => __( 'Location draft updated.' )
);
return $messages;
}
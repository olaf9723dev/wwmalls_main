<?php
/* Chechout page hook */

/**
** Get choosen shipping method
**/
function wpll_get_chosen_shipping_method() {
	$chosen_methods = WC()->session->get('chosen_shipping_methods');
	return $chosen_methods[0];
}

function wpll_location_row_layout(){   
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID; 
          		
		 if (WPLL()->settings['enabled'] == 'yes') :
		         
			 $wpll_chosen_shipping = wpll_get_chosen_shipping_method();	
	         $post_data = wpll_validate_ajax_request();
             if($wpll_chosen_shipping === WPLL()->id && is_cart() ):
			 ?>
			   <script>
					jQuery('.woocommerce-shipping-destination, .woocommerce-shipping-calculator').fadeOut();
			   </script> 
			 <?php			 
			 endif;			 
			 if($wpll_chosen_shipping === WPLL()->id && is_checkout() ):
			 $pickup_store = !empty($post_data['wpll-pickup-location']) ? $post_data['wpll-pickup-location'] : '';
             if(empty($pickup_store)):
				$wpll_pick_id=WC()->session->get( 'wpll_pickup_id'); 
				if($wpll_pick_id):
					$pickup_store = $wpll_pick_id;
				endif;				
			 endif;
			 if(empty($pickup_store)):
				$pickup_store = sanitize_text_field(get_user_meta($user_id, '_wpll_pickup_lcoation_id', true));
			 endif;
			 if(!empty($pickup_store)):
				 WC()->session->set( 'wpll_pickup_id', $pickup_store); 
			 endif;
			 ?>
			  <tr class="shipping-pickup-store">
				<th><strong><?php echo __('Local pickup', 'woo-local-pickup') ?></strong></th>
				<td>
					<select class='wpll-pickup-location-select' name='wpll-pickup-location'>
						<option value="" class='wpll-pickup-null' <?php if($pickup_store){ echo "disabled"; } ?> ><?php echo __('Select a store', 'woo-local-pickup'); ?></option>
										<?php
										foreach (wpll_location_get_store_admin(true) as $post_id => $store) {
											$pickup_ship_cost = get_post_meta($post_id, 'wlpp_shipping_cost', true);
											if(empty($pickup_ship_cost)){ $pickup_ship_cost==0; }
											$exclude_location = get_post_meta($post_id, '_wlpp_exclude_location', true);
											$selected = $post_id==$pickup_store ? ' selected="selected"' : '';
											if($exclude_location == 0):
												echo '<option data-cost="'.$pickup_ship_cost.'" value="'.$post_id.'" '.$selected.' data-address="'.wpll_location_get_address($post_id).'">'.$store.'</option>';
											endif;
										}
										?>			
					</select>
					</br>
					<?php if(!empty($pickup_store)): ?>
					<?php if(empty($post_data['wpll-pickup-location'])): ?>
						<script>
							jQuery('body').trigger('update_checkout');
						</script>
					<?php endif; ?>
					<div class='wpll-pickup-info'><span>
					 <?php 	echo wpll_location_get_address($pickup_store); ?>
					</span></div>
					
					<?php
                     if(!isset(WPLL()->settings['schedule_appointment']) || WPLL()->settings['schedule_appointment'] == 'yes'):
					?>
					<div class='wpll-pickup-appointment'><span class='wpll-appointment-head'><?php echo __('Schedule a pickup appointment', 'woo-local-pickup') ?></span></br><input readonly type='text' id='pickupdate' name='wpll-pickup-appointment-date'>
					</div>
				    <script>
					jQuery(document).ready(function($){
						var dates = $("#pickupdate").datepicker({
						minDate: "0",
						maxDate: "+2Y",
						defaultDate: "+1w",
						dateFormat: 'mm/dd/yy',
						numberOfMonths: 1,
						onSelect: function(date) {
								for(var i = 0; i < dates.length; ++i) {
								if(dates[i].id < this.id)
									$(dates[i]).datepicker('option', 'maxDate', date);
								else if(dates[i].id > this.id)
									$(dates[i]).datepicker('option', 'minDate', date);
								}
						    } 
					    });	
					});	
					</script>
					<?php endif; ?>
					
					
					<?php endif; ?>
				</td>
			  </tr>
			 <script>
				jQuery(document).ready(function($){
					$('.wpll-pickup-location-select').on('change', function() {
					 var loc_id=this.value;
					  if(loc_id){
						  jQuery('body').trigger('update_checkout');
					  }
					});				
				}); 
			 </script>		  
			 <?php 		 
			 endif;
		 endif;
} 
add_action('woocommerce_after_shipping_calculator', 'wpll_location_row_layout');
add_action('woocommerce_review_order_after_shipping', 'wpll_location_row_layout');

/*** Pickup Validation ****/
function wpll_location_validate_checkout($data) {
	if (isset($_POST['wpll-pickup-location']) && empty($_POST['wpll-pickup-location'])) {
		wc_add_notice(__('You must either choose a store or use other shipping method', 'woo-local-pickup'), 'error');
	}
	if (isset($_POST['wpll-pickup-appointment-date']) && empty($_POST['wpll-pickup-appointment-date'])) {
		wc_add_notice(__('You must choose a appointment date', 'woo-local-pickup'), 'error');
	}
}
add_action('woocommerce_after_checkout_validation', 'wpll_location_validate_checkout', 10, 2);

/**
** Add store shipping cost to cart amount
**/
//changes
function wpll_add_pickup_shipping_to_cart($cart) {
	global $woocommerce;
  	$post_data = wpll_validate_ajax_request();

  	$chosen_shipping = wpll_get_chosen_shipping_method();
	$pickup_ship_cost=0;	
	if(!empty($post_data['wpll-pickup-location'])):
		$pickup_ship_cost = get_post_meta($post_data['wpll-pickup-location'], 'wlpp_shipping_cost', true);

	endif;
	if(!empty($post_data['wpll-pickup-location'])):
		$pickup_ship_title = get_the_title($post_data['wpll-pickup-location']);
	endif;
	if (isset($pickup_ship_cost) && $pickup_ship_cost > 0 && $chosen_shipping === WPLL()->id):
        $amount = $pickup_ship_cost; 
    	//changes sani
		$ship_cost_cat = get_post_meta($post_data['wpll-pickup-location'], 'wlpp_ship_cost_category', true);
	    $ship_cost_type = get_post_meta($post_data['wpll-pickup-location'], 'wlpp_ship_cost_type', true);
		if($ship_cost_cat == 'cost'):
			 if($ship_cost_type =='value'):
				$woocommerce->cart->add_fee(
					apply_filters('wpll_store_pickup_cost_label', sprintf(__('Pickup Location Cost', 'woo-local-pickup'))),
					$amount,
					true,
					''
				); 		
			endif;	
			 if($ship_cost_type =='percentege'):
					$percent = $amount;
					$surcharge = ( $cart->cart_contents_total + $cart->shipping_total ) * $percent / 100;
					$cart->add_fee( __( 'Pickup Location Cost', 'woo-local-pickup')." ($percent%)", $surcharge, false );
			endif;			
		endif;
	    if($ship_cost_cat == 'discount'):
			 if($ship_cost_type =='value'):
				$woocommerce->cart->add_fee(
					apply_filters('wpll_store_pickup_cost_label', sprintf(__('Pickup Location Discount', 'woo-local-pickup'))),
					-$amount,
					true,
					''
				); 		
			endif;
			 if($ship_cost_type =='percentege'):
					$percent = $amount;
					$surcharge = ( $cart->cart_contents_total + $cart->shipping_total ) * $percent / 100;
					$cart->add_fee( __( 'Pickup Location Discount', 'woo-local-pickup')." ($percent%)", -$surcharge, false );
			endif;		
		endif;		   
	endif;
}
add_action('woocommerce_cart_calculate_fees', 'wpll_add_pickup_shipping_to_cart');

add_filter( 'woocommerce_get_order_item_totals', 'wpll_add_location', 10, 2 );
 
function wpll_add_location( $total_rows, $order) {
	$new_array = array();
	$order_id = $order->get_id();
	$location_name = sanitize_text_field(get_post_meta($order_id, '_wpll_pickup_lcoation_name', true));
	$date = sanitize_text_field(get_post_meta($order_id, '_wpll_pickup_date', true));

		$total_rows['location'] = array(
		   'label' => __( 'Location Name:', 'woocommerce' ),
		   'value'   =>  $location_name
		);
		$total_rows['date'] = array(
		   'label' => __( 'Date', 'woocommerce' ),
		   'value'   =>  $date
		);

	if(!empty($total_rows['date'])){
		$new_array['cart_subtotal'] =  $total_rows['cart_subtotal'];
		$new_array['shipping'] =  $total_rows['shipping'];
		$new_array['location'] =  $total_rows['location'];
		$new_array['date'] =  $total_rows['date'];
		$new_array['payment_method'] =  $total_rows['payment_method'];
		$new_array['order_total'] =  $total_rows['order_total'];
		$total_rows =  $new_array;
		}
 
return $total_rows;
}

//postion change order details

/**
** Save the custom field.
**/
function wpll_location_save_order_meta($order_id) {
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;
	$pickup_locaion_id = sanitize_text_field($_POST['wpll-pickup-location']);
	$pickup_locaion_name = get_the_title($pickup_locaion_id);
	$pickup_date = sanitize_text_field($_POST['wpll-pickup-appointment-date'] );

	if (!empty($pickup_locaion_id )):
		update_post_meta($order_id, '_wpll_pickup_lcoation_id', $pickup_locaion_id);
		update_user_meta($user_id, '_wpll_pickup_lcoation_id', $pickup_locaion_id);
	endif;
	if (!empty($pickup_locaion_name )):
		update_post_meta($order_id, '_wpll_pickup_lcoation_name', $pickup_locaion_name);
		update_user_meta($user_id, '_wpll_pickup_lcoation_name', $pickup_locaion_name);
	endif;
	if (!empty($pickup_date )):
		update_post_meta($order_id, '_wpll_pickup_date', $pickup_date);
		update_user_meta($user_id, '_wpll_pickup_date', $pickup_date);
	endif;
}
add_action('woocommerce_checkout_update_order_meta', 'wpll_location_save_order_meta');

/**
** Validate ajax request
**/
function wpll_validate_ajax_request() {
	if(!$_POST || (is_admin() && !is_ajax()))
		return;

   if(isset($_POST['post_data'])) {
	 	parse_str($_POST['post_data'], $post_data);
	   	return $post_data;
   }else{
   		return;
   }
}


/**** Add CSS/JS  ****/
function wpll_pickup_enqueue_styles(){	
	if (is_checkout()) {
         wp_register_script( 'wpll-js', plugin_dir_url( __FILE__ ).'assets/js/pickup.js', array(), '2.0.0', true );
         wp_enqueue_script( 'wpll-js' ); 	
		 wp_enqueue_style('wpll-styles', plugin_dir_url(__FILE__).'assets/css/pickup-style.css');
		 wp_enqueue_style('wpll-jquery-ui-css',plugin_dir_url(__FILE__).'assets/css/jquery-ui.css');	 
         wp_enqueue_script('jquery-ui-datepicker', array( 'jquery' ) );	 
	}
}
add_action('wp_enqueue_scripts', 'wpll_pickup_enqueue_styles');
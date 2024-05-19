<?php
/* Data Gathering function  */
function wpll_location_get_store_admin($return_id = false) {
	$location = array();
	$args = array(
		'post_type' => 'local-pickup-pro',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key' => '_exclude_store',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key' => '_exclude_store',
				'value' => '0',
			),
		),
	);
	$query = new WP_Query($args);
	if($query->have_posts()) {
		while ($query->have_posts()) : $query->the_post();
			if(!$return_id) {
				$location[$query->post->post_title] = $query->post->post_title;
			} else {
				$location[get_the_ID()] = $query->post->post_title;
			}
		endwhile;
		wp_reset_postdata();
	}
	return $location;
}

function wpll_location_get_address($id){
	$address='';
	if($id){
	 if(get_post_meta( $id, 'wlpp_address', true )){
		 $address .= " ".get_post_meta( $id, 'wlpp_address', true );
	 }
	return $address;
	}
}
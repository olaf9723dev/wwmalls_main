<?php 
add_action('save_post', 'sync_post_subdomain',11, 3);
function sync_post_subdomain($post_id,$post, $update){
    // params : $servername, $username, $password, $database 
    $restaurant_conn = restaurant_db_conn();
    if($update){
        $sql = "UPDATE wpdn_posts SET ID = '$post_id',post_author = '$post->post_author',  
        post_date = '$post->post_date', post_date_gmt = '$post->post_date_gmt', post_content = '$post->post_content',
        post_title = '$post->post_title', post_excerpt = '$post->post_excerpt', post_status = '$post->post_status', 
        comment_status = '$post->comment_status', ping_status = '$post->ping_status', post_password = '$post->post_password',
        post_name = '$post->post_name', to_ping = '$post->to_ping', pinged = '$post->pinged', post_modified = '$post->post_modified', 
        post_modified_gmt = '$post->post_modified_gmt', post_content_filtered = '$post->post_content_filtered', post_parent = '$post->post_parent',
        guid = '$post->guid', menu_order = '$post->menu_order', post_type = '$post->post_type', post_mime_type = '$post->post_mime_type', 
        comment_count = '$post->comment_count' WHERE ID = $post_id";
        $result = $restaurant_conn->query($sql);
    }else{
        $sql = "INSERT INTO `wpdn_posts` (ID, post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_password, post_name, 
        to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, guid, menu_order, post_type, post_mime_type, comment_count) VALUES ('$post_id', '$post->post_author', '$post->post_date',
        '$post->post_date_gmt', '$post->post_content', '$post->post_title', '$post->post_excerpt', '$post->post_status', '$post->comment_status', '$post->ping_status', '$post->post_password', '$post->post_name',
        '$post->to_ping', '$post->pinged', '$post->post_modified', '$post->post_modified_gmt', '$post->post_content_filtered', '$post->post_parent', '$post->guid', '$post->menu_order', '$post->post_type', '$post->post_mime_type',
        '$post->comment_count')";
        $result = $restaurant_conn->query($sql);
    }
    $restaurant_conn->close();
}
// This is not checked.
add_action('delete_post', 'sync_postdata_subdomain_del',11);
function sync_postdata_subdomain_del($post_id){
    $db_conn = restaurant_db_conn();
    
    $sql_restaurant1 = "DELETE FROM wpdn_posts WHERE ID = ?";
    
    $stmt = $db_conn->prepare($sql_restaurant1);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_results();
    $stmt->close();

    $db_conn->close();
}

add_action('added_post_meta', 'sync_postmeta_add_subdomain', 11,4);
function sync_postmeta_add_subdomain($mid, $object_id, $meta_key, $_meta_value){
    $restaurant_conn = restaurant_db_conn();
    $sql = "INSERT INTO `wpdn_postmeta` (meta_id, post_id, meta_key, meta_value) VALUES ('$mid', '$object_id', '$meta_key', '$_meta_value')";
    $result = $restaurant_conn->query($sql);
    $restaurant_conn->close();
}
add_action('updated_post_meta', 'sync_postmeta_update_subdomain', 11,4);
function sync_postmeta_update_subdomain($mid, $object_id, $meta_key, $_meta_value){
    $restaurant_conn = restaurant_db_conn();
    $sql = "UPDATE wpdn_postmeta SET meta_id = '$mid',post_id = '$object_id',  
        meta_key = '$meta_key', meta_value = '$_meta_value' WHERE meta_id = $mid";
    $result = $restaurant_conn->query($sql);
    $restaurant_conn->close();
}
add_action('deleted_post_meta', 'sync_postmeta_delete_subdomain', 11,4);
function sync_postmeta_delete_subdomain($meta_ids, $object_id, $meta_key, $_meta_value){
    $restaurant_conn = restaurant_db_conn();
    $sql = "DELETE FROM wpdn_postmeta WHERE post_id = ?";
    $stmt = $restaurant_conn->prepare($sql);
    $stmt->bind_param("i", $object_id);
    $stmt->execute();
    $result = $stmt->get_results();
    $stmt->close();
    $restaurant_conn->close();
}

add_action('added_option', 'sync_option_add_action', 10, 2);
function sync_option_add_action($option_name, $value){
    global $wpdb;
    $option_id = $wpdb->get_var( $wpdb->prepare( "SELECT option_id FROM {$wpdb->options} WHERE option_name = %s", $option_name ) );
    $autoload = "yes";
    $restaurant_conn = restaurant_db_conn();
    $sql = "INSERT INTO `wpdn_options` (option_id, option_name, option_value, autoload) VALUES ('$option_id', '$option_name', '$value', '$autoload')";
    $result = $restaurant_conn->query($sql);
    $restaurant_conn->close();
}
add_action( 'updated_option', 'sync_option_update_action', 10, 3 );
function sync_option_update_action( $option, $old_value, $value ){
    global $wpdb;
    $option_id = $wpdb->get_var( $wpdb->prepare( "SELECT option_id FROM {$wpdb->options} WHERE option_name = %s", $option_name ) );
    $restaurant_conn = restaurant_db_conn();
    $sql = "UPDATE wpdn_options SET option_value = '$value', WHERE option_name = $option";
    $result = $restaurant_conn->query($sql);
    $restaurant_conn->close();
}
add_action( 'deleted_option', 'sync_option_delete_action' );
function sync_option_delete_action( $option ){
    $restaurant_conn = restaurant_db_conn();
    $sql = "DELETE FROM wpdn_postmeta WHERE option_name = ?";
    $stmt = $restaurant_conn->prepare($sql);
    $stmt->bind_param("s", $option);
    $stmt->execute();
    $result = $stmt->get_results();
    $stmt->close();
    $restaurant_conn->close();
}


function restaurant_db_conn(){
    $conn = new mysqli("localhost", "wwmalls_wp412", "xN]p0E]S55", "wwmalls_wp412");
    return $conn;
}
?>
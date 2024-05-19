<?php 
    // user
    add_action( 'user_register', 'sync_user_register_action', 10, 2 );
    function sync_user_register_action($user_id, $userdata){
        var_dump($user_data);exit;
        $restaurant_conn = restaurant_db_conn();
        $sql = "INSERT INTO `wpdn_users` (ID, user_login, user_pass, user_nicename, user_email, user_url, user_registered, user_activation_key,
        user_status, display_name) VALUES ('$user_id', '$userdata->user_login', '$userdata->user_pass', '$userdata->user_nicename', '$userdata->user_email',
        '$userdata->user_url', '$userdata->user_registered', '$userdata->user_activation_key', '$userdata->user_status', '$userdata->display_name')";
        $result = $restaurant_conn->query($sql);
        $restaurant_conn->close();
    }
    add_action( 'profile_update','sync_user_update_action', 10, 3);
    function sync_user_update_action($user_id, $old_user_data, $userdata){
        $restaurant_conn = restaurant_db_conn();
        $sql = "UPDATE wpdn_users SET user_login = '$userdata->user_login',user_pass = '$userdata->user_pass', user_nicename = '$userdata->user_nicename',
        user_email = '$userdata->user_email', user_url = '$userdata->user_url', user_registered = '$userdata->user_registered', user_activation_key = '$userdata->user_activation_key',
        user_status = '$userdata->user_status', display_name = '$userdata->display_name' WHERE ID = $user_id";
        $result = $restaurant_conn->query($sql);
        $restaurant_conn->close();
    }
    add_action( 'deleted_user', 'sync_user_delete_action', 10, 3);
    function sync_user_delete_action($id, $reassign, $user){
        $restaurant_conn = restaurant_db_conn();
        $sql = "DELETE FROM wpdn_users WHERE ID = ?";
        $stmt = $restaurant_conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_results();
        $stmt->close();
        $restaurant_conn->close();
    }
    // usermeta data
    add_action( 'added_user_meta', 'sync_usermeta_add_action', 10, 4 );
    function sync_usermeta_add_action( $mid, $object_id, $meta_key, $meta_value ){
        $restaurant_conn = restaurant_db_conn();
        $sql = "INSERT INTO `wpdn_usermeta` (umeta_id, user_id, meta_key, meta_value) VALUES ('$mid', '$object_id', '$meta_key', '$meta_value')";
        $result = $restaurant_conn->query($sql);
        $restaurant_conn->close();
    }
    add_action( 'updated_user_meta', 'sync_usermeta_update_action', 10, 4 );
    function sync_usermeta_update_action( $meta_id, $object_id, $meta_key, $meta_value ){
        $restaurant_conn = restaurant_db_conn();
        $sql = "UPDATE wpdn_usermeta SET meta_key = '$meta_key', meta_value = '$meta_value' WHERE umeta_id = $meta_id";
        $result = $restaurant_conn->query($sql);
        $restaurant_conn->close();
    }
    add_action( 'deleted_user_meta', 'sync_usermeta_delete_action', 10, 4 );
    function sync_usermeta_delete_action( $meta_ids, $object_id, $meta_key, $meta_value ){
        $restaurant_conn = restaurant_db_conn();
        foreach ($meta_ids as $meta_id) {
            $sql = "DELETE FROM wpdn_usermeta WHERE umeta_id = ?";
            $stmt = $restaurant_conn->prepare($sql);
            $stmt->bind_param("i", $meta_id);
            $stmt->execute();
            $result = $stmt->get_results();
            $stmt->close();
        }
        $restaurant_conn->close();
    }
    
    function restaurant_db_conn(){
        $conn = new mysqli("localhost", "wwmalls_wp412", "xN]p0E]S55", "wwmalls_wp412");
        return $conn;
    }
?>
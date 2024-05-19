<?php 
    // term
    add_action( 'create_term', 'sync_create_term_action', 10, 2 );
    function sync_create_term_action( $term_id, $tt_id){
        $term = get_term($term_id);
        $restaurant_conn = restaurant_db_conn();
        $sql = "INSERT INTO `wpdn_terms` (term_id, name, slug) VALUES ('$term_id', '$term->name', '$term->slug')";
        $result = $restaurant_conn->query($sql);
        $sql2 = "INSERT INTO `wpdn_term_taxonomy` (term_taxonomy_id, term_id, taxonomy, description, parent, count) VALUES ('$tt_id', '$term_id', '$term->taxonomy',
        '$term->description', '$term->parent', '$term->count',)";
        $result = $restaurant_conn->query($sql2);
        $restaurant_conn->close();
    }
    add_action( 'edited_term', 'sync_edit_term_action', 10, 4 );
    function sync_edit_term_action( $term_id, $tt_id, $taxonomy, $args ){
        $term = get_term($term_id);
        $restaurant_conn = restaurant_db_conn();
        $sql = "UPDATE wpdn_terms SET name = '$term->name', slug = '$term->slug' WHERE term_id = $term_id";
        $result = $restaurant_conn->query($sql);
        $sql2 = "UPDATE wpdn_term_taxonomy SET taxonomy = '$term->taxonomy', description = '$term->description', parent = '$term->parent', 
        count = '$term->count' WHERE term_taxonomy_id = $tt_id";
        $result = $restaurant_conn->query($sql2);
        $restaurant_conn->close();
    }
    add_action( 'delete_term', 'sync_delete_term_action', 10, 5 );
    function sync_delete_term_action( $term_id, $tt_id, $taxonomy, $deleted_term, $object_ids ){
        $restaurant_conn = restaurant_db_conn();
        
        $sql = "DELETE FROM wpdn_terms WHERE post_id = ?";
        $stmt = $restaurant_conn->prepare($sql);
        $stmt->bind_param("i", $term_id);
        $stmt->execute();
        $result = $stmt->get_results();
        $stmt->close();
        
        $sql2 = "DELETE FROM wpdn_term_taxonomy WHERE term_taxonomy_id = ?";
        $stmt2 = $restaurant_conn->prepare($sql2);
        $stmt2->bind_param("i", $tt_id);
        $stmt2->execute();
        $result = $stmt2->get_results();
        $stmt2->close();
        
        $restaurant_conn->close();
    }

    add_action( 'added_term_meta', 'sync_add_term_meta_action', 10, 4 );
    function sync_add_term_meta_action( $mid, $object_id, $meta_key, $_meta_value ){
        $restaurant_conn = restaurant_db_conn();
        $sql = "INSERT INTO `wpdn_termmeta` (meta_id, term_id, meta_key, meta_value) VALUES ('$mid', '$object_id', '$meta_key', '$_meta_value')";
        $result = $restaurant_conn->query($sql);
        $restaurant_conn->close();
    }
    add_action( 'updated_term_meta', 'sync_update_term_meta_action', 10, 4 );
    function sync_update_term_meta_action( $meta_id, $object_id, $meta_key, $_meta_value ){
        $restaurant_conn = restaurant_db_conn();
        $sql = "UPDATE wpdn_termmeta SET term_id = '$object_id', meta_key = '$meta_key', meta_value = '$_meta_value' 
        WHERE meta_id = $meta_id";
        $result = $restaurant_conn->query($sql);
        $restaurant_conn->close();
    }
    add_action( 'deleted_term_meta', 'sync_delete_term_meta_action', 10, 4 );
    function sync_delete_term_meta_action( $meta_ids, $object_id, $meta_key, $_meta_value ){
        $restaurant_conn = restaurant_db_conn();
        foreach ($meta_ids as $meta_id) {
            $sql = "DELETE FROM wpdn_usermeta WHERE meta_id = ?";
            $stmt = $restaurant_conn->prepare($sql);
            $stmt->bind_param("i", $meta_id);
            $stmt->execute();
            $result = $stmt->get_results();
            $stmt->close();
        }
        $restaurant_conn->close();
    }
        
    add_action( 'added_term_relationship', 'sync_add_term_relationship_action', 10, 3 );
    function sync_add_term_relationship_action( $object_id, $tt_id, $taxonomy ){
        $restaurant_conn = restaurant_db_conn();
        $sql = "INSERT INTO `wpdn_term_relationships` (object_id, $tt_id) VALUES ('$object_id', '$tt_id')";
        $result = $restaurant_conn->query($sql);
        $restaurant_conn->close();
    }    
    add_action( 'deleted_term_relationships', 'sync_delete_term_relationship_action', 10, 3 );
    function sync_delete_term_relationship_action( $object_id, $tt_ids, $taxonomy ){
        $restaurant_conn = restaurant_db_conn();
        foreach ($tt_ids as $tt_id) {
            $sql = "DELETE FROM wpdn_term_relationships WHERE object_id = ? AND term_taxonomy_id = ?";
            $stmt = $restaurant_conn->prepare($sql);
            $stmt->bind_param("ii", $object_id, $tt_id);
            $stmt->execute();
            $result = $stmt->get_results();
            $stmt->close();
        }
        $restaurant_conn->close();
    }
    add_action( 'set_object_terms', 'sync_edit_term_relationship_action', 10, 6 );
    function sync_edit_term_relationship_action( $object_id, $terms, $tt_ids, $taxonomy, $append, $old_tt_ids ){
    
    }
    
    function restaurant_db_conn(){
        $conn = new mysqli("localhost", "wwmalls_wp412", "xN]p0E]S55", "wwmalls_wp412");
        return $conn;
    }
?>
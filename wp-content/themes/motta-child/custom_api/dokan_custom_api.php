<?php
    // API FUNCTIONS //
    add_action('wp_ajax_handle_products_by_category', 'handle_products_by_category_callback');
    add_action('wp_ajax_nopriv_handle_products_by_category', 'handle_products_by_category_callback'); // For non-logged in users
    function handle_products_by_category_callback() {
        $category_id = $_POST['category_id'];
        $product_data = get_product_data_by_category($category_id);
        // Return response to the client
        echo $product_data; // Send response as HTML
        
        wp_die(); // Always include wp_die() at the end to terminate script execution
    }
    
    function get_product_data_by_category($category_id){
        $product_data_html = '<option value="">--Select Category--</option>';
        global $wpdb;
                $query = $wpdb->prepare("SELECT * FROM wpt4_term_relationships RIGHT JOIN wpt4_posts ON wpt4_term_relationships.object_id = wpt4_posts.ID WHERE wpt4_term_relationships.term_taxonomy_id = %d", $category_id);
                $products = $wpdb->get_results($query);
                
                foreach ($products as $product) {
                    $product_data_html .= '<option value="' . esc_attr( $product->object_id ) . '">' . esc_html( $product->post_title ) . '</option>';
                }
        return $product_data_html;
    }
    
    add_action('init', 'handle_form_submission');
    function handle_form_submission(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['publish-ads'])) {
            $user_id = get_current_user_id();
            $title = sanitize_text_field($_POST['title']);
            $category = sanitize_text_field($_POST['product-category']);
            $name = sanitize_text_field($_POST['product-name']);
            $discount = isset($_POST['d-price']) ? intval($_POST['d-price']) : 0;
            $period = isset($_POST['period']) ? intval($_POST['period']) : 2;
            $discription = sanitize_textarea_field($_POST['description']);
            $payout = $period > 2 ? (25 + ($period-2)*5) : 25;
            
            $new_post = array(
                'post_author' => $user_id,
                'post_title' => $title,
                'post_status' => 'publish',
                'post_type' => 'ads',
                'post_excerpt' => 'This is advertising for dealing'
            );
            $post_id = wp_insert_post($new_post);
            
            $meta_key = 'ads_info';
            $meta_value = array(
                'title' => $title,
                'category' => $category,
                'name' => $name,
                'discount' => $discount,
                'period' => $period,
                'discription' => $discription,
                'payout' => $payout,
                'allowed date' => '',
                'status' => 'pending'
            );
            if ( $_FILES ) { 
                $files = $_FILES["image-input"];
                if ($files['error'] === UPLOAD_ERR_OK) {
                    $unique_filename = wp_unique_filename(wp_upload_dir()['path'], $files['name']);
                    $files['name'] = $unique_filename;
                    
                    $upload_overrides = array('test_form' => false);
                    $uploaded_file = wp_handle_upload($files, $upload_overrides);
                    $meta_value['image_url'] = $uploaded_file['url'];
                }
            }
            $result = add_post_meta($post_id, $meta_key, $meta_value, true);
        }
    }
?>
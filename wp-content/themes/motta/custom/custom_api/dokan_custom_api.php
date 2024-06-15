<?php
    // API FUNCTIONS On Vendor dashoboard => Advertisement//
    add_action('wp_ajax_handle_products_by_category', 'handle_products_by_category_callback');
    add_action('wp_ajax_nopriv_handle_products_by_category', 'handle_products_by_category_callback'); // For non-logged in users
    function handle_products_by_category_callback() {
        $category_id = $_POST['category_id'];
        $current_user_id = $_POST['user_id'];
        $product_data = get_product_data_by_category($category_id, $current_user_id);
        // Return response to the client
        echo $product_data; // Send response as HTML
        wp_die(); // Always include wp_die() at the end to terminate script execution
    }
    function get_product_data_by_category($category_id, $current_user_id){
        $product_data_html = '<option value="">--Select Product--</option>';
        global $wpdb;
            $query = $wpdb->prepare("SELECT * FROM wpt4_term_relationships INNER JOIN wpt4_posts ON wpt4_term_relationships.object_id = wpt4_posts.ID WHERE wpt4_term_relationships.term_taxonomy_id = %d AND wpt4_posts.post_author = %d", $category_id, $current_user_id);
            $products = $wpdb->get_results($query);
                
            foreach ($products as $product) {
                $product_data_html .= '<option value="' . esc_attr( $product->object_id ) . '">' . esc_html( $product->post_title ) . '</option>';
            }
        return $product_data_html;
    }
    
    add_action('wp_ajax_handle_product_info', 'handle_product_info_callback');
    function handle_product_info_callback(){
        $product_id = $_POST['product_id'];
        $current_user_id = $_POST['user_id'];
        $product_info = get_product_info($product_id, $current_user_id);
        echo json_encode($product_info);
        wp_die();
    }
    function get_product_info($product_id, $current_user_id){
        global $wpdb;
        $meta_key = '_regular_price';
        $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}postmeta WHERE post_id = %d AND meta_key = %s", $product_id, $meta_key );
        $product_info = $wpdb->get_results($query);
        
        return $product_info;
    }
    
    add_action('wp_ajax_handle_vendor_delete_ads', 'handle_vendor_delete_ads_callback');
    function handle_vendor_delete_ads_callback(){
        if (!current_user_can('seller')) {
            wp_send_json_error('You do not have permission to perform this action.');
            wp_die();
        }
        $delete_values = $_POST['deletes'];
        foreach ($delete_values as $value){
            wp_delete_post($value);
        }
        echo 'success';
        wp_die();
    }

    // Receive status change from Admin page -> dokan manage advertisement.
    add_action('wp_ajax_handle_admin_ads_status', 'handle_admin_ads_status_callback');
    add_action('wp_ajax_nopriv_handle_admin_ads_status', 'handle_admin_ads_status_callback');
    function handle_admin_ads_status_callback(){
        if (!current_user_can('administrator')) {
            wp_send_json_error('You do not have permission to perform this action.');
            wp_die();
        }
        $status = $_POST['status'];
        $post_id = $_POST['post_id'];
        $post = update_admin_ads_status($status, $post_id);
        echo $post;
        wp_die();
    }
    function update_admin_ads_status($status, $post_id){
        $old_post_metadata = get_post_meta($post_id, 'ads_info', true);
        $old_post_metadata['status'] = $status;
        $new_post_metadata = $old_post_metadata;
        global $wpdb;
        update_post_meta($post_id, 'ads_info', $new_post_metadata);
        wp_update_post(array('ID' => $post_id, 'post_status'   => $status));
        $user = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}users WHERE ID = %d", $old_post_metadata['creator']));
        $admin_email = ($wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}users WHERE ID = %d", 1)))[0]->user_email;
        
        $headers = array(
            'From: WWMalls <' . $admin_email . '>',
            'Reply-To: ' . $admin_email,
            'Content-Type: text/html; charset=UTF-8'
        );
        if($status=='pending'){
            $message = 'Dear '. $user[0]->user_login .',
                    This email is to notify you that your deals ad has been suspended due to information we
                    received from a user.
                    WWMall admin has stopped your Advertisement(<' . $new_post_metadata['title'] .'>).
                    Please email us at suspensions@wwmalls.com immediately in case
                    this was in error, so we can resolve it asap.
                    Thank you
                    Wwmalls Admin.';
            $sent = wp_mail($user[0]->user_email, 'From WWMall Inc', $message, $headers);
        }else{
            $message = 'Dear '. $user[0]->user_login .',
                    WWMall admin has allowed your Advertisement(<' . $new_post_metadata['title'] .'>).
                    If you wanna know about more detail, pls contact WWMalls
                    Thank you.
                    Wwmalls Admin.';
            $sent = wp_mail($user[0]->user_email, 'From WWMall Inc', $message, $headers);
        }
        global $wpdb;
        
        $ads_subscriptor_by_name = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}postmeta WHERE post_id = %d AND meta_key = %s", $new_post_metadata['name'], 'ads_subscriptor'));
        $ads_subscriptor_by_cat = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}termmeta WHERE term_id = %d AND meta_key = %s", $new_post_metadata['category'], 'ads_subscriptor'));
        
        if(count($ads_subscriptor_by_name)>0){
            foreach($ads_subscriptor_by_name as $subscriptor){
                $info = get_userdata($subscriptor->meta_value);
                $link='https://wwmalls.com/deal-page/?search=' . get_post($new_post_metadata['name'])->post_title . '&searchBy=0';
                $message = 'We have great news.Someone just posted a deal for an item you were looking for. Click here to view it' . $link;
                $sent = wp_mail($info->user_email, 'From WWMall Inc', $message, $headers);
            }
        }
        if(count($ads_subscriptor_by_cat)>0){
            foreach($ads_subscriptor_by_cat as $subscriptor){
                $info = get_userdata($subscriptor->meta_value);
                $slug = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}term WHERE term_id=%d", $new_post_metadata['category']))[0]->slug;
                $link='https://wwmalls.com/deal-page/?search=' . $slug . '&searchBy=1';
                $message = 'We have great news.Someone just posted a deal for an item you were looking for. Click here to view it' . $link;
                $sent = wp_mail($info->user_email, 'From WWMall Inc', $message, $headers);
            }
        }
        return $new_post_metadata['status'];
    }
    
    add_action('wp_ajax_handle_update_ads_fee', 'handle_update_ads_fee_callback');
    function handle_update_ads_fee_callback(){
        $new_def_fee = $_POST['new_def_fee'];
        $new_per_fee = $_POST['new_per_fee'];
        
        $post = get_page_by_title('ads_fee', OBJECT, 'post');
        if ($post) {
            $result = update_post_meta($post->ID, 'ads_def_fee', $new_def_fee);
            $result2 = update_post_meta($post->ID, 'ads_per_fee', $new_per_fee);
        } else {
            $post_data = array(
                'post_title' => 'ads_fee',
                'post_content' => 'This is for fee info for advertising',
                'post_status' => 'publish',
                'post_author' => 1,
                'post_type' => 'post'
            );
            $post_id = wp_insert_post($post_data);
            $result = update_post_meta($post_id, 'ads_def_fee', $new_def_fee);
            $result2 = update_post_meta($post_id, 'ads_per_fee', $new_per_fee);
        }
        echo 'success';
        wp_die();
    }
    
    // Receive msg from Deal page
    add_action('wp_ajax_handle_send_ads_comment', 'handle_send_ads_comment_callback');
    function handle_send_ads_comment_callback(){
        $user_id = $_POST['user_id'];
        $message = $_POST['message'];
        global $wpdb;
        $user = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}users WHERE ID = %d", $user_id));
        $user_email = $user[0]->user_email;
        $user_name = $user[0]->user_login;
        $admin_email = ($wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}users WHERE ID = %d", 1)))[0]->user_email;

        $headers = array(
            'From: ' . $user_name . '<' . $user_email . '>',
            'Reply-To: ' . $user_email,
            'Content-Type: text/html; charset=UTF-8'
        );
        $sent = wp_mail($admin_email, 'Comment from Customer', $message, $headers);
        
        echo $sent;
        wp_die();
    }
    
    add_action('wp_ajax_handle_send_ads_subscribe', 'handle_send_ads_subscribe_callback');
    function handle_send_ads_subscribe_callback(){
        $user_id = $_POST['user_id'];
        $search_text = $_POST['search_text'];
        $search_cat = $_POST['search_cat'];
        
        global $wpdb;
        if($search_cat == 0){
            $product_opts = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}posts WHERE post_title LIKE %s AND post_type='product'", '%' . $wpdb->esc_like($search_text) . '%'));
            if(count($product_opts)>0){
                foreach ($product_opts as $product){
                    update_post_meta($product->ID, 'ads_subscriptor', $user_id);
                }
            }
        }else{
            $product_opts =  $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}term_relationships INNER JOIN {$wpdb->prefix}term_taxonomy ON {$wpdb->prefix}term_relationships.term_taxonomy_id = {$wpdb->prefix}term_taxonomy.term_taxonomy_id
                        INNER JOIN {$wpdb->prefix}terms ON {$wpdb->prefix}term_taxonomy.term_id = {$wpdb->prefix}terms.term_id WHERE taxonomy = %s AND {$wpdb->prefix}terms.slug LIKE %s", 'product_cat', '%' . $wpdb->esc_like($search_text) . '%'));
            if(count($product_opts)>0){
                foreach ($product_opts as $product_cat){
                    update_term_meta($product_cat->term_id, 'ads_subscriptor', $user_id);
                }            
            }
        }
        
        if(count($product_opts)>0){
            $search_status = 'yes';
        }else{
            $search_status = 'no';
        }
        
        echo $search_status;
        wp_die();
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
            
            global $wpdb;
        	$query = "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = 'ads_def_fee'";
            $ads_def_fee = ($wpdb -> get_results($wpdb->prepare($query)))[0]->meta_value;
            $query = "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = 'ads_per_fee'";
            $ads_per_fee = ($wpdb -> get_results($wpdb->prepare($query)))[0]->meta_value;
            
            $regular_price = get_post_meta($name, '_regular_price', true);
            $net_price = floor(($regular_price * (100-$discount) /100)*100)/100;
            $payout = floor((((float)$ads_def_fee + ((float)$period-2)*(float)$ads_per_fee) * (100-(float)$discount) /100)*100)/100;
            $current_time = current_time('mysql');
            $gmt_time = get_gmt_from_date( $current_time );
            $new_post = array(
                'post_author' => $user_id,
                'post_date_gmt' => $gmt_time, 
                'post_title' => $title,
                'post_status' => 'pending',
                'post_type' => 'ads',
                'post_excerpt' => 'This is advertising for dealing'
            );
            $meta_key = 'ads_info';
            $meta_value = array(
                'title' => $title,
                'category' => $category,
                'name' => $name,
                'discount' => $discount,
                'period' => $period,
                'discription' => $discription,
                'allowed date' => '',
                'status' => 'pending',
                'image_url' =>'',
                'regular_price' => $regular_price,
                'net_price' => $net_price,
                'payout' => $payout,
                'paid' => 'no',
                'creator' => $user_id
            );
            if ( $_FILES ) { 
                $files = $_FILES["image-ads"];
                if ($files['error'] === UPLOAD_ERR_OK) {
                    $unique_filename = wp_unique_filename(wp_upload_dir()['path'], $files['name']);
                    $files['name'] = $unique_filename;
                    $upload_overrides = array('test_form' => false);
                    $uploaded_file = wp_handle_upload($files, $upload_overrides);
                    $meta_value['image_url'] = $uploaded_file['url'];
                }
            }
            
            $post_id = wp_insert_post($new_post);
            $result = add_post_meta($post_id, $meta_key, $meta_value, true);
            // update_post_meta($name, '_sale_price', $net_price);
            $product = wc_get_product($name);
            $product->set_sale_price($net_price);
            $product->set_price($net_price);
            $product->save();
        }
    }
    
    // On Admin Page -> NON- Compliant->get categories with site id
    add_action('rest_api_init', function (){
        register_rest_route('admin/non-compliant', '/get-categories', array(
            'methods' => 'POST',
            'callback' => 'get_categories_rest_endpoint_callback',
            'permission_callback' => 'admin_permission_callback',
        ));
    });
    function get_categories_rest_endpoint_callback(WP_REST_Request $request){
        global $wpdb;
        $site_id = sanitize_text_field($request->get_param('siteID'));
        $datas = $wpdb->get_results($wpdb->prepare("SELECT * FROM wp_categories WHERE site_id=%d", $site_id));
        return new WP_REST_Response(array('message' => 'success', 'data' => $datas), 200);
    }

    add_action('rest_api_init', function (){
        register_rest_route('admin/non-compliant', '/get-products-by-cat', array(
            'methods' => 'POST',
            'callback' => 'get_products_by_cat_rest_endpoint_callback',
            'permission_callback' => 'admin_permission_callback',
        ));
    });
    function get_products_by_cat_rest_endpoint_callback(WP_REST_Request $request){
        global $wpdb;
        $cat_id = $_POST['catID'];
        $sub_cats = [];
        $sub_cats = sub_leaf_cat_ids($cat_id, $sub_cats);
        $search_values = implode(',', array_fill(0, count($sub_cats), '%s'));
        
        $query = $wpdb->prepare("SELECT id, name, sku, sale_price, regular_price, status, fee_rate FROM wp_products WHERE category_id IN ($search_values)", ...$sub_cats);
        $products = $wpdb->get_results($query);    
        return new WP_REST_Response(array('message' => 'success', 'data' => $products), 200);
    }
    function sub_leaf_cat_ids($current_id, $sub_cats){
        global $wpdb;
        if (is_leaf($current_id)){
            array_push($sub_cats, $current_id);
        }else{
            $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM wp_categories WHERE parent_id = %d", $current_id));
            foreach($results as $result){
                $sub_cats = sub_leaf_cat_ids($result->id, $sub_cats);
            }
        }
        return $sub_cats;
    }
    function is_leaf($cat_id){
        global $wpdb;
        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM wp_categories WHERE id = %d", $cat_id));
        if(!empty($results) && $results[0]->role === "leaf"){
            return true;
        }else{
            return false;
        }
    }
    
    add_action('rest_api_init', function (){
        register_rest_route('admin/non-compliant', '/update-nonc-products', array(
            'methods' => 'POST',
            'callback' => 'update_nonc_products_rest_endpoint_callback',
            'permission_callback' => 'admin_permission_callback',
        ));
    });
    
    function update_nonc_products_rest_endpoint_callback(WP_REST_Request $request){
        
        if (!wp_verify_nonce($request->get_header('X-WP-Nonce'), 'wp_rest')) {
            return new WP_REST_Response(array('message' => 'Invalid nonce'), 403);
        }
        
        global $wpdb;
        
        $ext_product_ids = $request->get_param('extProductIds');
        $fee_rate = $request->get_param('feeRate');
        $cat_id = $request->get_param('catID');
        
        if (empty($ext_product_ids) || !is_array($ext_product_ids) || empty($fee_rate) || empty($cat_id)) {
            return new WP_REST_Response(array('message' => 'Missing or invalid parameters'), 400);
        }
        
        $product_infos = get_ext_product_infos($ext_product_ids);
        $updated_ext_datas = [];
        // foreach($ext_product_ids as $ext_product_id){

        //     if(!$product_info || !isset($product_info->wwmall_id)){
        //         // Create
        //         $values = create_new_product($product_info, $fee_rate); 
        //         $wwmall_product_id = $values[0]; 
        //         $wwmall_category_id = $values[1];
        //         $updated_row = update_status($ext_product_id, $wwmall_product_id, $wwmall_category_id, $fee_rate, 'create');
        //         array_push($updated_ext_datas, $updated_row);
        //     }else{
        //         // Update
        //         if (is_exist_product($product_info->wwmall_id)){
        //             $value = update_exist_product($product_info, $fee_rate);
        //             $updated_row = update_status($ext_product_id, $product_info->wwmall_id, $value, $fee_rate, 'update');
        //             array_push($updated_ext_datas, $updated_row);
        //         }else{
        //             $values = create_new_product($product_info, $fee_rate); 
        //             $wwmall_product_id = $values[0]; 
        //             $wwmall_category_id = $values[1];
        //             $updated_row = update_status($ext_product_id, $wwmall_product_id, $wwmall_category_id, $fee_rate, 'create');
        //             array_push($updated_ext_datas, $updated_row);
        //         }
        //     }
        // }
        return new WP_REST_Response(array('message' => 'success', 'data' => $product_infos), 200);
    }

    function get_ext_product_info($ext_product_ids){
        global $wpdb;
        $ids = implode(',', array_fill(0, count($ext_product_ids), '%d'));
        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM wp_products WHERE id IN ($ids)", ...$ext_product_ids));
        return $results;
    }
    
    function create_new_product($product_info, $fee_rate){
        
        $product = new WC_Product_Simple();
        
        $product->set_name($product_info->name);
        
        $product->set_description($product_info->description);
        
        $product->set_short_description($product_info->description);
        
        $product->set_sku($product_info->sku);
        
        $price = ($product_info->sale_price) * (1 + $fee_rate/100);
        
        $product->set_regular_price($price);
          
        $product->set_sale_price($price);
        
        $product->set_stock_status('instock');
        
        $category_id = get_term_by('name', 'Uncategorized', 'product_cat')->term_id;
        
        $product->set_category_ids(array($category_id));
        
        $image_urls = json_decode($product_info->images, true);
        
        $attachment_id = upload_image_to_media_library($image_urls[0]);
        $product->set_image_id($attachment_id);
        
        $gallery_image_ids = [];
        
        foreach ($image_urls as $url) {
            $gallery_image_ids[] = upload_image_to_media_library($url);
        }
    
        // Set the product gallery images
        $product->set_gallery_image_ids($gallery_image_ids);
        
        $product_id = $product->save();
        
        return [$product_id, $category_id];
    }
    
    function update_exist_product($product_info, $fee_rate){
       
        $product = wc_get_product($product_info->wwmall_id);
        
        if (!$product) {
            // echo 'Product not found.';
            return;
        }
        
        // $product->set_name($product_info->name);
        
        // $product->set_description($product_info->description);
        
        // $product->set_short_description($product_info->description);
        
        $price = ($product_info->sale_price) * (1 + $fee_rate/100);
        
        $product->set_regular_price($price); // Update the regular price
        
        $product->set_sale_price($price); // Update the sale price
        
        $category_id = get_term_by('name', 'Uncategorized', 'product_cat')->term_id;
        
        $product->set_category_ids(array($category_id));
        
        // $image_urls = json_decode($product_info->images, true);

        // $new_attachment_id = upload_image_to_media_library($image_urls[0]);
        // $product->set_image_id($new_attachment_id);

        // $new_gallery_image_ids = [];
        // foreach ($image_urls as $url) {
        //     $new_gallery_image_ids[] = upload_image_to_media_library($url);
        // }
        
        // $product->set_gallery_image_ids($new_gallery_image_ids);
        
        $product->set_status('publish');
        
        $product->save();
        
        return $category_id;
    }
    
    function upload_image_to_media_library($image_url) {
        $upload_dir = wp_upload_dir(); // Set upload folder
        $image_data = file_get_contents($image_url); // Get image data
        $filename = basename($image_url); // Create image file name
    
        if (wp_mkdir_p($upload_dir['path'])) {
            $file = $upload_dir['path'] . '/' . $filename;
        } else {
            $file = $upload_dir['basedir'] . '/' . $filename;
        }
    
        file_put_contents($file, $image_data);
    
        $wp_filetype = wp_check_filetype($filename, null );
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title'     => sanitize_file_name($filename),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );
    
        $attach_id = wp_insert_attachment($attachment, $file);
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $file);
        wp_update_attachment_metadata($attach_id, $attach_data);
    
        return $attach_id;
    }
    
    function update_status($ext_product_id, $wwmall_product_id, $wwmall_category_id, $fee_rate, $type){
        
        global $wpdb;
        
        $current_date = date('Y-m-d H:i:s');
        
        if($type == 'create'){
            $data = array(
                'wwmall_id' => $wwmall_product_id,
                'wwmall_category_id' => $wwmall_category_id,
                'fee_rate' => $fee_rate,
                'status' => 'on',
                'first_imported_to' => $current_date,
                'last_imported_to' => $current_date
            );
            $where = array('id' => $ext_product_id);   
            
            $updated = $wpdb->update('wp_products', $data, $where);
            
            if ($updated !== false) {
                $updated_row = $wpdb->get_row($wpdb->prepare("SELECT id, name, sku, sale_price, regular_price, status, fee_rate FROM wp_products WHERE id = %d", $ext_product_id), ARRAY_A);
                return $updated_row;
            } else {
                return false;
            }
        }
        
        if($type == 'update'){
            $data = array(
                'wwmall_id' => $wwmall_product_id,
                'wwmall_category_id' => $wwmall_category_id,
                'fee_rate' => $fee_rate,
                'status' => 'on',
                'last_imported_to' => $current_date
            );
            $where = array('id' => $ext_product_id);   
            
            $updated = $wpdb->update('wp_products', $data, $where);
            
            if ($updated !== false) {
                $updated_row = $wpdb->get_row($wpdb->prepare("SELECT id, name, sku, sale_price, regular_price, status, fee_rate FROM wp_products WHERE id = %d", $ext_product_id), ARRAY_A);
                return $updated_row;
            } else {
                return false;
            }
        }
        
    }
    
    function is_exist_product($wwmall_id){
        
        $product = wc_get_product($wwmall_id);

        if ($product) {
            return true;
        } else {
            return false;
        }
    }
    
    function updated_product($ext_product_id){
        global $wpdb;
        
        $results = $wpdb->get_results($wpdb->prepare("SELECT id, name, sku, sale_price, regular_price, status, fee_rate FROM wp_products WHERE id = %d", $ext_product_id));
        return $results[0];
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    add_action('wp_ajax_handle_disable_nonc_products', 'handle_disable_nonc_products_callback');
    function handle_disable_nonc_products_callback(){
        if (!current_user_can('administrator')) {
            wp_send_json_error('You do not have permission to perform this action.');
            wp_die();
        }
        global $wpdb;
        $ext_product_ids=$_POST['extProductIds'];
        $cat_id = $_POST['catID'];
        $products = [];
        foreach($ext_product_ids as $ext_product_id){
            $product_info = get_ext_product_info($ext_product_id);
            if(isset($product_info->wwmall_id)){
                if (is_exist_product($product_info->wwmall_id)){
                    disable_product(intval($product_info->wwmall_id));
                    $data = array('status' => 'off');
                    $where = array('id' => $ext_product_id);
                    $updated = $wpdb->update('wp_products', $data, $where);
                    array_push($products, updated_product($ext_product_id));
                }else{
                    $data = array(
                        'status' => 'off',
                        'wwmall_id' => null
                    );
                    $where = array('id' => $ext_product_id);
                    $format = array(
                        '%s' 
                    );
                    
                    $where_format = array(
                        '%d' 
                    );
                    $updated = $wpdb->update('wp_products', $data, $where, $format, $where_format);
                    array_push($products, updated_product($ext_product_id));
                }
            }
        }
        
         wp_send_json_success($products);
    }
    
    function disable_product($wwmall_id){
        
        // $product = wc_get_product($product_id);
        // $product->set_status('draft');
        // $product->save();
        $args = array(
            'p' => $wwmall_id,
            'post_type' => 'product',
        );
        
        $query = new WP_Query($args);
        if ($query->have_posts()) {
            wp_update_post(array(
                'ID' => $wwmall_id,
                'post_status' => 'draft',
            ));
        } 
        
        wp_reset_postdata();
    }
    
    
add_action('rest_api_init', function () {
    register_rest_route('admin/non-compliant', '/remove-duplicated-products', array(
        'methods' => 'POST',
        'callback' => 'remove_duplicated_products_rest_endpoint_callback',
        'permission_callback' => 'admin_permission_callback',
    ));
});

function remove_duplicated_products_rest_endpoint_callback(WP_REST_Request $request){
    global $wpdb;
    
    $query = "SELECT wpt4_posts.ID, meta_value FROM wpt4_posts LEFT JOIN wpt4_postmeta ON wpt4_posts.ID = wpt4_postmeta.post_id INNER JOIN wp_products ON meta_value = wp_products.sku  WHERE meta_key LIKE '%sku%' AND post_type = 'product'";
    $results = $wpdb->get_results($query);
    $duplicatedNames  = getDuplicatedValuesForKey($results, 'meta_value');
    // foreach($results as $result){
    //     // delete_product($result->ID);
    // }
    
    return new WP_REST_Response(array('message' => 'Success! Products data removed!', 'data' => $duplicatedNames), 200);
}

function admin_permission_callback(){
    return current_user_can('administrator');
}

function getDuplicatedValuesForKey($array, $key) {
    // Extract values for the specified key
    $values = array_column($array, $key);
    
    // Count the occurrences of each value
    $valueCounts = array_count_values($values);
    
    // Filter out the values that appear more than once
    $duplicates = array_filter($valueCounts, function($count) {
        return $count > 1;
    });
    
    // return array_keys($duplicates);
    return $valueCounts;
}

function delete_product($product_id){
    global $wpdb;
    
    wp_delete_post($product_id, true);

    // Delete product meta data
    $wpdb->delete($wpdb->postmeta, array('post_id' => $product_id));
    // Delete product taxonomy relationships
    $wpdb->delete($wpdb->term_relationships, array('object_id' => $product_id));
    // Delete product reviews (comments)
    $wpdb->delete($wpdb->comments, array('comment_post_ID' => $product_id));
}

add_action('rest_api_init', function () {
    register_rest_route('admin/non-compliant', '/remove-all-products', array(
        'methods' => 'POST',
        'callback' => 'remove_all_products_rest_endpoint_callback',
        'permission_callback' => 'admin_permission_callback',
    ));
});
function remove_all_products_rest_endpoint_callback(WP_REST_Request $request){
    $author_id = 1; 
    $product_ids = get_product_ids_by_author($author_id);
    foreach ($product_ids as $product_id){
        delete_product($product_id);
    }
    return new WP_REST_Response(array('message' => 'Success! All Products data removed!', 'data' => $product_ids), 200);
}   
function get_product_ids_by_author($author_id) {
    // Set up the query arguments
    $args = array(
        'post_type'      => 'product',
        'author'         => $author_id,
        'posts_per_page' => -1, // Retrieve all products by this author
        'fields'         => 'ids' // Only get the post IDs
    );

    // Execute the query
    $query = new WP_Query($args);
    // Check if there are any posts
    if ($query->have_posts()) {
        // Return the array of product IDs
        return $query->posts;
    } else {
        // No posts found
        return array();
    }
}

add_action('rest_api_init', function(){
    register_rest_route('admin/non-compliant', '/reset-all-nonc', array(
        'methods' => 'POST',
        'callback' => 'reset_all_nonc_rest_endpoint_callback',
        'permission_callback' => 'admin_permission_callback',
    ));
});
function reset_all_nonc_rest_endpoint_callback(WP_REST_Request $request){
    global $wpdb;
    $query = "UPDATE wp_products SET wwmall_id = NULL, wwmall_category_id = NULL, fee_rate = NULL, status = 'off', last_imported_to = Null, first_imported_to = NULL";
    $wpdb->get_results($query);
    return new WP_REST_Response(array('message' => 'success'), 200);
}

?>
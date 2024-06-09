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
    add_action('wp_ajax_handle_get_category_from_site', 'handle_get_category_from_site_callback');
    function handle_get_category_from_site_callback(){
        $site_id = $_POST['siteID'];
        global $wpdb;
        $datas = $wpdb->get_results($wpdb->prepare("SELECT * FROM wp_categories WHERE site_id=%d", $site_id));
        
        wp_send_json_success($datas);
    }
    
    // add_action('rest_api_init', function(){
    //     register_rest_route('non_compliant/manage', '/product/', array(
    //         'methods' => 'POST',
    //         'callback' => 'get_non_compliant_products_handler',
    //     ));
    // }, 113);
    // function get_non_compliant_products_handler($request){
    //     global $wpdb;
    //     $params = $request->get_json_params();
    //     $query = build_sql($params);
    //     $results = $wpdb->get_results($query, ARRAY_A);
        
    //     return array(
    //         'success' => true,
    //         'rows' => $results,
    //         'lastRow' => get_last_row_index($params)
    //     );
    // }
    // function build_sql($request) {
    //     return 'SELECT * FROM wp_products' . where_sql($request) . order_by_sql($request) . limit_sql($request);
    // }
    
    // function where_sql($request) {
    //     $whereParts = array();
    //     $filterModel = $request['filterModel'];
    
    //     if ($filterModel) {
    //         foreach ($filterModel as $columnKey => $filter) {
    //             if ($filter['filterType'] === 'set') {
    //                 $values = implode("', '", $filter['values']);
    //                 $whereParts[] = "$columnKey IN ('$values')";
    //                 continue;
    //             }
    
    //             error_log('unsupported filter type: ' . $filter['filterType']);
    //         }
    //     }
    
    //     if (count($whereParts) > 0) {
    //         return ' WHERE ' . implode(' AND ', $whereParts);
    //     }
    
    //     return '';
    // }
    
    // function order_by_sql($request) {
    //     $sortModel = $request['sortModel'];
    
    //     if (count($sortModel) === 0) return '';
    
    //     $sorts = array_map(function($s) {
    //         return $s['colId'] . ' ' . strtoupper($s['sort']);
    //     }, $sortModel);
    
    //     return ' ORDER BY ' . implode(', ', $sorts);
    // }
    
    // function limit_sql($request) {
    //     if (!isset($request['endRow']) || !isset($request['startRow'])) { return ''; }
    //     $blockSize = $request['endRow'] - $request['startRow'];
    
    //     return ' LIMIT ' . intval($blockSize) . ' OFFSET ' . intval($request['startRow']);
    // }
    
    // function get_last_row_index($request) {
    //     global $wpdb;
    //     $query = 'SELECT COUNT(*) FROM wp_products' . where_sql($request);
    //     return $wpdb->get_var($query);
    // }
    
    add_action('wp_ajax_handle_get_products_by_cat', 'handle_get_products_by_cat_callback');
    function handle_get_products_by_cat_callback(){
        global $wpdb;
        $cat_id = $_POST['catID'];
        $sub_cats = [];
        $sub_cats = sub_leaf_cat_ids($cat_id, $sub_cats);
        $search_values = implode(',', array_fill(0, count($sub_cats), '%s'));
        
        $items_per_page = 20;
        $current_page = isset($_GET['paged']) ? (int)$_GET['paged'] : 1;
        $offset = ($current_page - 1) * $items_per_page;
        
        $query = $wpdb->prepare("SELECT id, name, sku, sale_price, regular_price, status, fee_rate FROM wp_products WHERE category_id IN ($search_values)", ...$sub_cats);
        // $query = $wpdb->prepare("SELECT id, name, sku, sale_price, regular_price, status FROM wp_products WHERE category_id IN ($search_values) LIMIT %d OFFSET %d", array_merge($sub_cats, [$items_per_page, $offset]));
        $products = $wpdb->get_results($query);
        
        wp_send_json_success($products);
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
?>
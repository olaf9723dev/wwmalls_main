<?php
/**
 * Motta functions and definitions.
 *
 * @link    https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Motta
 */
 
 require_once get_template_directory() . '/inc/theme.php';

\Motta\Theme::instance()->init();
 
require_once 'custom/custom_api/dokan_custom_api.php';
require_once 'custom/delivery/delivery.php';
require_once 'custom/custom-woo-wallet/c-woo-wallet.php';
require_once 'custom/custom-member/edit-membership.php';
require_once 'custom/custom-register/c-register.php';
require_once 'custom/custom-store/custom-store.php';
require_once 'custom/custom-admin-sort/custom-sort.php';

// require_once 'custom/custom_api/post_hook.php';
// require_once 'custom/custom_api/user_hook.php';

// Add Custom Style an JavaScript
add_action( 'wp_enqueue_scripts', 'motta_child_enqueue_scripts', 20 );
function motta_child_enqueue_scripts() {
	wp_enqueue_style( 'motta-child', get_stylesheet_uri() );
	wp_enqueue_style( 'custom-styles', get_theme_file_uri('custom/custom_assets/css/custom.css'));
	wp_enqueue_style( 'toaster-styles', 'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css');
	wp_enqueue_style( 'modal-styles', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css');
	wp_enqueue_script( 'custom-scripts', get_theme_file_uri('custom/custom_assets/js/custom.js'));
	wp_enqueue_script( 'toaster-scripts', 'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js');
	wp_enqueue_script( 'modal-scripts', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js');
	wp_enqueue_script( 'custom-scripts1', 'https://cdn.ckeditor.com/ckeditor5/37.0.0/classic/ckeditor.js');
	
	if ( is_rtl() ) {
		wp_enqueue_style( 'motta-rtl', get_template_directory_uri() . '/rtl.css' );
	}
	global $wpdb;
	$query = "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = 'ads_def_fee'";
    $ads_def_fee = ($wpdb -> get_results($wpdb->prepare($query)))[0]->meta_value;
    $query = "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = 'ads_per_fee'";
    $ads_per_fee = ($wpdb -> get_results($wpdb->prepare($query)))[0]->meta_value;
	$current_user = wp_get_current_user();
	$store_categories=[];
	$categories = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}term_taxonomy INNER JOIN {$wpdb->prefix}terms ON {$wpdb->prefix}term_taxonomy.term_id = {$wpdb->prefix}terms.term_id WHERE taxonomy = %s", 'store_category'));
	if(count($categories)>0){
        foreach ($categories as $category){
            array_push($store_categories, $category->name);
        }            
    }
	wp_localize_script('custom-scripts', 'valueData', array('ajax_url' => admin_url('admin-ajax.php'), 'userID' => $current_user->ID, 'adsDef_Fee' => $ads_def_fee, 'adsPer_Fee' => $ads_per_fee, 'store_categories' => $store_categories));
}
add_action( 'admin_enqueue_scripts', 'admin_page_enqueue_scripts' );
function admin_page_enqueue_scripts(){
    wp_enqueue_style( 'custom-styles', get_theme_file_uri('custom/custom_assets/css/admin.css'));
    wp_enqueue_style( 'toaster-styles', 'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css');
    wp_enqueue_script( 'custom-scripts', get_theme_file_uri('custom/custom_assets/js/custom.js'), array('jquery'));
    wp_localize_script('custom-scripts', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    wp_enqueue_script( 'toaster-scripts', 'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js');
}
// Add dokan custom menu on admin dashboard
function dokan_vendor_custom_menu() {
    // dokan vendor import page
    add_submenu_page(
        'dokan',
        'Import/Remove Vendors', // Page title
        'Import/Remove Vendors', // Menu title
        'add_users', // Capability
        'dokan-vendor-import', // Menu slug
        'dokan_vendor_import_page', // Function to display the page
		4
	);
    // dokan vendor advertisement management page
    add_submenu_page(
        'dokan',
        'Manage Advertisement', // Page title
        'Manage Advertisement', // Menu title
        'add_users', // Capability
        'dokan-vendor-ads', // Menu slug
        'dokan_vendor_ads_page', // Function to display the page
		5
    );
}
add_action('admin_menu', 'dokan_vendor_custom_menu', 11);
// Handle to Import Vendors from CSV file
function dokan_vendor_importer_handle_upload() {
    if (isset($_FILES['import_file'])) {
        if (check_admin_referer('dokan_vendor_importer_action', 'dokan_vendor_importer_nonce')) {
            if ($_FILES['import_file']['error'] === UPLOAD_ERR_OK) {
                $filename = $_FILES['import_file']['tmp_name'];
                $handle = fopen($filename, "r");
                if ($handle !== FALSE) {
                    // Assuming the first row contains headers
                    $headers = fgetcsv($handle, 1000, ",");
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if(is_exist_c($data) == false){
                            dokan_vendor_importer_create_vendor($data); 
                        }
                    }
                    fclose($handle);
					echo '<div class="notice notice-success"><p>Import successful.</p></div>';
                } else {
                    echo '<div class="notice notice-error"><p>Error opening file.</p></div>';
                }
            } else {
                echo '<div class="notice notice-error"><p>Error uploading file.</p></div>';
            }
        } else {
            echo '<div class="notice notice-error"><p>Security check failed.</p></div>';
        }
    }
}
add_action('admin_init', 'dokan_vendor_importer_handle_upload');
//Genarate Random String
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}
//Genarate Random Email
function generateRandomEmail($domain = 'example.com') {
    $randomString = generateRandomString(10); // Generate a random string of length 10 for the email
    return $randomString . '@' . $domain;
}
//Genarate Random Password
function generateRandomPassword($length = 6) {
    $digits = '0123456789';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $digits[random_int(0, strlen($digits) - 1)];
    }
    return $password;
}
//Genarate Random UserName
function generate_wp_username($string, $maxLength = 16) {
    $sanitizedString = remove_accents($string);
    $sanitizedString = strtolower(str_replace(' ', '_', $sanitizedString));
    $sanitizedString = preg_replace('/[^a-z0-9_]/', '', $sanitizedString);
    $sanitizedString = substr($sanitizedString, 0, $maxLength);
    $baseUsername = $sanitizedString;
    $counter = 1;
    while (username_exists($sanitizedString)) {
        $sanitizedString = $baseUsername . $counter;
        $counter++;
    }
    return $sanitizedString;
}
//Function to Update the Database with data from CSV.
function dokan_vendor_importer_create_vendor($data) {
    global $wpdb;
    $email = generateRandomEmail('yourdomain.com');
    $password = generateRandomPassword();
    $username = generate_wp_username($data[1]);
// Register User on DB
    $user_id = wp_create_user($username, $password, $email);     
    $user = new WP_User($user_id);
// Update the User as Vendor
    $user->set_role('seller');
    $new_store_name = $data[1];
    $new_address = array(
        'street_1' => $data[2],
        'city' => $data[3],
        'zip' => $data[5],
        'province' => $data[4],
    );
    $names = explode(' ', $data[7]);
    if(empty($names[0])){
        $names[0] = 'test1';
        $names[1] = 'test2';
    }
    wp_update_user(array('ID' => $user_id, 'display_name' => $new_store_name, 'first_name' => $names[0], 'last_name' => $names[1]));   
// Send request to add User on Subdomain(restaurant, ...)
    // $user_data = array(
    //     'user_id' => $user_id,
    //     'username'=>$username,
    //     'password'=>$password,
    //     'email' => $email,
    //     'update_user' => array('ID' => $user_id, 'display_name' => $new_store_name, 'first_name' => $names[0], 'last_name' => $names[1]),
    // );
    // $response = wp_remote_post('https://restaurants.wwmalls.com/wp-json/restaurant/user/create/', array(
    //     'method' => 'POST',
    //     'headers'     => array(
    //         'Content-Type' => 'application/json',
    //     ),
    //     'body' => json_encode($user_data),
    //     'data_format' => 'body',
    // ));
    // $res_user = wp_remote_retrieve_body( $response );
// Create the Store info for User-Vendor.
    $store_info = dokan_get_store_info($user_id);
    $store_info['address'] = array_merge($store_info['address'], $new_address);
    $store_info['store_name'] = $new_store_name;
    $store_info['phone'] = $data[6];
    $store_info['subdomain'] = $data[13];
    $temp_categories_un = array(
	    (object)array(
	        'term_id'=>'',
	        'term_taxonomy_id' => '',
	        'name' => '',
	        'slug'=>'',
	        'taxonomy'=> '',
	    ),
	);
// Send request to add Vendor info on Subdomain(restaurant, ...)
    // if($store_info['subdomain'] == 'restaurant'){
    //     $store_info['categories'] = $temp_categories_un;
    //     $request_data = array(
    //         'user' => $res_user,
    //         'parent_id' => $user_id,
    //         'store_info'=> $store_info,
    //         'imported_data' => $data, 
    //         'temp_categories_un' => $temp_categories_un,
    //     );
    //     $response = wp_remote_post('https://restaurants.wwmalls.com/wp-json/restaurant/vendor/create/', array(
    //         'method' => 'POST',
    //         'headers'     => array(
    //             'Content-Type' => 'application/json',
    //         ),
    //         'body' => json_encode($request_data),
    //         'data_format' => 'body',
    //     ));
    // }
// On Main domain, Create data for Vendor and User realtionships
    global $new_term, $new_term_taxonomy, $new_term_relationships;
    $category_str = (string)$data[0];
    $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}terms WHERE name = %s", $category_str);
    $term = $GLOBALS['wpdb']->get_results($query);
    // Check if the Store Category is Exist or not on DB. 
    if ($term != null){
        // if Exist, only make the Relationships with User.
        $temp_categories_un[0]->term_id =$term[0]->term_id;
        $query = $wpdb->prepare("SELECT term_taxonomy_id FROM {$wpdb->prefix}term_taxonomy WHERE term_id = %s", (string)($term[0]->term_id));
        $term_taxonomy = $GLOBALS['wpdb']->get_results($query);
        $temp_categories_un[0]->term_taxonomy_id =$term_taxonomy[0]->term_taxonomy_id;
        $temp_categories_un[0]->name = $data[0];
        $temp_categories_un[0]->slug = $term[0]->slug;
        $temp_categories_un[0]->taxonomy = "store_category";
        $where = array(
            'object_id' => $user_id,
            'term_taxonomy_id' => cget_term_id(),
        );
        $new_term_relationships= array(
            'term_taxonomy_id' => $term_taxonomy[0]->term_taxonomy_id,
            'term_order' => 0,
        );
        $wpdb->update($wpdb->prefix . 'term_relationships', $new_term_relationships, $where);
    }
    else{
        // if not Exist, Add new Category and make the Relationships with User.
        $new_term = array(
            'name' => $data[0],
            'slug' => implode('-', explode(' ',strtolower($data[0]))),
            'term_group' => 0,
        );
        $wpdb->insert($wpdb->prefix . 'terms', $new_term, array('%s','%s','%d'));    
        $new_term_id = $wpdb->insert_id;
        
        $new_term_taxonomy= array(
            'term_id' => $new_term_id,
            'taxonomy' => 'store_category',
            'description' => $data[0],
            'parent' => 0,
            'count' => 1, 
        );
        $wpdb->insert($wpdb->prefix . 'term_taxonomy', $new_term_taxonomy, array('%d','%s','%s','%d','%d'));    
        $new_term_taxonomy_id = $wpdb->insert_id;   

        $where = array(
            'object_id' => $user_id,
            'term_taxonomy_id' => cget_term_id(),
        );
        $new_term_relationships= array(
            'object_id' => $user_id,
            'term_taxonomy_id' => $new_term_taxonomy_id,
            'term_order' => 0,
        );
        $wpdb->update($wpdb->prefix . 'term_relationships', $new_term_relationships, $where);   

        $temp_categories_un[0]->term_id = $new_term_id;
        $temp_categories_un[0]->term_taxonomy_id = $new_term_taxonomy_id;
        $temp_categories_un[0]->name = $data[0];
        $temp_categories_un[0]->slug = implode('-', explode(' ',strtolower($data[0])));
        $temp_categories_un[0]->taxonomy = "store_category";
    }

    $store_info['categories'] = $temp_categories_un;
    update_user_meta($user_id, 'dokan_profile_settings', $store_info);
    // Enabled Status as Vendor.
    update_user_meta($user_id, 'dokan_enable_selling', "yes");
}
//The Page for managing the Import and Remove Vendors
function dokan_vendor_import_page() {
    ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    
	<div id="csvDataTable" class="wrap content-area">
        <h1 class="wp-heading-inline">Import/Remove Vendors</h1>
        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('dokan_vendor_importer_action', 'dokan_vendor_importer_nonce'); ?>
            <input type='file' name='import_file' />
            <?php submit_button('Import CSV'); ?>
        </form>
        <main id="main" class="site-main">
        <!--Hook for Displaying DataTable.-->
        <?php echo apply_filters('view_vendor', '', 1, 3)?>
        </main>
    </div>
    <script>
        // Jquery to Select the Vendor rows.
        $(document).ready(function(){
            $("#masterCheckbox").change(function(){
                $(".subCheckbox").prop('checked', $(this).prop("checked"));
            });

            $(".subCheckbox").change(function(){
                if($(".subCheckbox:checked").length == $(".subCheckbox").length){
                    $("#masterCheckbox").prop('checked', true);
                }
                else{
                    $("#masterCheckbox").prop('checked', false);
                }
            });
        });
        // SearchPanel Action
        document.addEventListener('DOMContentLoaded', function() {

            var paramInput = document.getElementById('post-search-input');
            var paramBy = document.getElementById('badge_name');

            var savedParam = sessionStorage.getItem('param');
            if (savedParam) {
                paramInput.value = savedParam;
            }
            
            var savedParamBy = sessionStorage.getItem('paramBy');
            var savedParamByT = sessionStorage.getItem('paramByT');
            if (savedParamBy) {
                paramBy.value = savedParamBy;
                paramBy.text = savedParamByT;
            }

            document.getElementById('searchButton').addEventListener('click', function() {
                var paramValue = paramInput.value;
                var paramValueBy = paramBy.value;
                var paramValueByT = paramBy.text;

                sessionStorage.setItem('param', paramValue);
                sessionStorage.setItem('paramBy', paramValueBy);
                sessionStorage.setItem('paramByT', paramValueByT);
                
                if(paramInput.value !==""){
                    var newUrl = 'https://wwmalls.com/wp-admin/admin.php?page=dokan-vendor-import' + "&search=" + paramInput.value + "&searchBy=" + paramBy.value;
                    window.location.href= newUrl;
                }
            });
            document.getElementById('showAll').addEventListener('click', function() {
                paramInput.value='';
                sessionStorage.setItem('param', '');
                sessionStorage.setItem('paramBy', '');
                sessionStorage.setItem('paramByT', '');
                var newUrl = 'https://wwmalls.com/wp-admin/admin.php?page=dokan-vendor-import';
                window.location.href= newUrl;
            });
        });
    </script>
    <style>
        .highlight {
            background-color: yellow;
        }
    </style>
    <?php
}
// Get term_id which is 'Uncategorised' for vendor category.
function cget_term_id(){
    global $wpdb;
    $datas = $GLOBALS['wpdb']->get_results("SELECT u.*, um.taxonomy, um.term_taxonomy_id 
    FROM {$wpdb->prefix}terms AS u 
    LEFT JOIN {$wpdb->prefix}term_taxonomy AS um ON u.term_id = um.term_id 
    WHERE name = 'Uncategorized' AND taxonomy = 'store_category'");
    
    return $datas[0]->term_taxonomy_id;
}
// Function to Check Duplicated Vendors.
function is_exist_c($cdata){
    global $wpdb;
    $store_name = $cdata[1];
    $address = $cdata[2];
    $province = $cdata[4];
    $search_data = $wpdb->get_results("SELECT meta_key, meta_value FROM {$wpdb->prefix}usermeta WHERE meta_key = 'dokan_profile_settings'");
    $is_exist=false;
    foreach($search_data as $data) {
        if(strtolower(unserialize($data->meta_value)['store_name']) == strtolower($store_name) && strtolower(unserialize($data->meta_value)['address']['street_1']) == strtolower($address)){
            $is_exist = true;
            break;
        }
    }
    return $is_exist;
}
// Hook for view Table Dinamically
function dokan_vendor_view($content){
    global $wpdb;
    $limit = 40;
    $page = isset($_GET['paged']) ? $_GET['paged'] : 1;
    $search = isset($_GET['search']) ? $_GET['search'] :'';
    $search_by = isset($_GET['searchBy']) ? $_GET['searchBy'] :'';
    $offset = ($page -1) * $limit;
    $count_query = "SELECT COUNT(*) 
                    FROM {$wpdb->prefix}users AS u 
                    LEFT JOIN {$wpdb->prefix}usermeta AS um ON u.ID = um.user_id 
                    WHERE meta_key = 'dokan_profile_settings'";
    $totalrows = $wpdb->get_var($count_query);
    $totalpages=ceil($totalrows/$limit);
    // Read data With Search Option 
    if($search_by == ''){
        $query = "SELECT u.*, um.meta_key, um.meta_value 
                FROM {$wpdb->prefix}users AS u 
                LEFT JOIN {$wpdb->prefix}usermeta AS um ON u.ID = um.user_id 
                WHERE meta_key = 'dokan_profile_settings'
                LIMIT %d OFFSET %d";
    }
    if($search_by == 0){
        $query = "SELECT u.*, um.meta_key, um.meta_value 
                FROM {$wpdb->prefix}users AS u 
                LEFT JOIN {$wpdb->prefix}usermeta AS um ON u.ID = um.user_id 
                WHERE meta_key = 'dokan_profile_settings' AND user_id = {$search}
                LIMIT %d OFFSET %d";
    }
    if($search_by == 1){
        $query = "SELECT u.*, um.meta_key, um.meta_value 
                FROM {$wpdb->prefix}users AS u 
                LEFT JOIN {$wpdb->prefix}usermeta AS um ON u.ID = um.user_id 
                WHERE meta_key = 'dokan_profile_settings' AND user_email LIKE '%{$search}%'
                LIMIT %d OFFSET %d";
    }
    if($search_by == 2){
        $query = "SELECT u.*, um.meta_key, um.meta_value 
                FROM {$wpdb->prefix}users AS u 
                LEFT JOIN {$wpdb->prefix}usermeta AS um ON u.ID = um.user_id 
                WHERE meta_key = 'dokan_profile_settings' AND meta_value LIKE '%{$search}%'
                LIMIT %d OFFSET %d";
    }
    // Read data with Page counts and limitation.
    $datas = $wpdb -> get_results($wpdb->prepare($query, $limit, $offset));
    ob_start();
    ?>
    <div>
        <div class="tablenav top">
            <button id="showAll" class="alignright button button-primary" style="margin-left: 10px;">Show All</button>
            <button id="searchButton" class="alignright button button-primary">Search</button>
            <div class="alignright actions">
                <div data-v-b24f2bca="" class="seller-badge-vendor-dropdown"><div data-v-b24f2bca="" class="column">
                    <select data-v-b24f2bca="" name="badge_status" id="badge_name" class="vendor-picker" style="min-width: 100%;">
                        <option data-v-b24f2bca="" value="0">Search by ID</option> 
                        <option data-v-b24f2bca="" value="1">Search by Email</option>
                        <option data-v-b24f2bca="" value="2">Search by Other</option> 
                    </select>
                </div>
            </div>
            </div>
            <p id="searchInput" class="search-box" style="margin-bottom: 10px;">
                <input type="search" id="post-search-input" name="s" placeholder="Search Vendors">
            </p>
        </div>
    <form action="" method="post">
	    <table id="vendortable" class="wp-list-table widefat fixed striped" style="width:100%;table-layout: auto;"> 
			<thead> 
				<tr>
                    <td class="check-column"><input id ="masterCheckbox" type="checkbox" name="all" value="all"></th>
                    <th class="column">ID</th>
                    <th class="column">Store</th> 
					<th class="column">E-mail</th> 
                    <th class="column">Categories</th> 
					<th class="column">Phone</th>
				</tr> 
			</thead> 
            <tbody>
                <?php 
                foreach ($datas as $data){
                    echo '<tr>';
                        echo '<th class="check-column"><input class="subCheckbox" type="checkbox" name="delete[]" value="' . ($data ->ID) . '"></td>';
                        echo '<td>' . ($data->ID) . '</td>';
                        echo '<td>' . (unserialize($data->meta_value)['store_name'] ?? ''). '</td>';
                        echo '<td>' . ($data->user_email ?? '') . '</td>';
                        $term_id = unserialize($data->meta_value)['categories'][0]->term_id;
                        $category_name = $wpdb->get_results("SELECT name FROM {$wpdb->prefix}terms WHERE term_id = $term_id");
                        echo '<td>' . ($category_name[0]->name ?? '') . '</td>';
                        echo '<td>' . (unserialize($data->meta_value)['phone'] ?? '') . '</td>';
                    echo '</tr>';            
                } 
                ?>
            </tbody>
            <tfoot> 
				<tr>
                    <td class="column">Action</th>
                    <th class="column">ID</th>
                    <th class="column">Store</th> 
					<th class="column">E-mail</th> 
                    <th class="column">Categories</th> 
					<th class="column">Phone</th>
				</tr> 
			</tfoot> 
        </table>
        <input type="submit" name="bulk_delete" value="Delete Selected Vendors" class="page-title-action" style="margin-top:10px">
    </form>
    <?php 
        ?>
        <div class="tablenav bottom">
            <div class="tablenav-pages">
                <?php 
                echo '<span class="displaying-num">' . $totalrows .' vendors</span>'; 
                echo '<span class="pagination-links">';
                    if ($page==1){
                        echo '<span aria-hidden="true" class="tablenav-pages-navspan button disabled" style = "margin:0px 5px">«</span>';
                        echo '<span aria-hidden="true" class="tablenav-pages-navspan button disabled" style = "margin:0px 5px">‹</span>';
                    }else{
                        echo '<a href="' . add_query_arg('paged', 1) . '" style = "margin:0px 5px">';
                        echo '<span aria-hidden="true" class="tablenav-pages-navspan button">«</span>';
                        echo '</a>';
                        echo '<a href="' . add_query_arg('paged', $page-1) . '" style = "margin:0px 5px">';
                        echo '<span aria-hidden="true" class="tablenav-pages-navspan button">‹</span>';
                        echo '</a>';
                    }
                    echo '<span class="paging-input">';
                    echo '<span class="tablenav-paging-text">' . $page . ' of <span class="total-pages">' .  $totalpages  . '</span></span>';
                    echo '</span>';
                ?>
                <?php 
                    if ($page==$totalpages){
                        echo '<span aria-hidden="true" class="tablenav-pages-navspan button disabled" style = "margin:0px 5px">›</span>';
                        echo '<span aria-hidden="true" class="tablenav-pages-navspan button disabled" style = "margin:0px 5px">»</span>';
                    }else{
                        echo '<a href="' . add_query_arg('paged', $page + 1) . '" style = "margin:0px 5px">';
                        echo '<span aria-hidden="true" class="tablenav-pages-navspan button" >›</span>';
                        echo '</a>';
                        echo '<a href="' . add_query_arg('paged', $totalpages) . '" style = "margin:0px 5px">';
                        echo '<span aria-hidden="true" class="tablenav-pages-navspan button">»</span>';
                        echo '</a>';
                    }
                ?>
                </span>
             </div>
        </div>
    </div>
	<?php
    // PHP Backend function to delete the vendors.  	 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['bulk_delete'])){
            if (isset($_POST["delete"]) && !empty($_POST["delete"])) {
                $selectedRows = $_POST["delete"];
                foreach ($selectedRows as $value) {
                    // global $wpdb;
                    wp_delete_user($value, true);
                }
                echo '<div class="notice notice-success"><p>Deleted successfully.</p></div>';
                header("Location: ".$_SERVER['REQUEST_URI']);
            }else {
                echo '<div class="notice notice-warning"><p>No checkboxes selected.</p></div>';
            }
        }
    }
    $content = ob_get_clean();
	return $content;
}
add_filter('view_vendor', 'dokan_vendor_view');
// /////////////////////////////////////////////////////////////////////////////////////// #Dokan dashboard Menu and Page for Advertisement /////////////////////////////////////////////////////////////////
add_filter( 'dokan_query_var_filter', 'dokan_load_document_menu' );
function dokan_load_document_menu( $query_vars ) {
    $query_vars['advertisement'] = 'advertisement';
    return $query_vars;
}
add_filter( 'dokan_get_dashboard_nav', 'get_dashboard_nav', 11);
function get_dashboard_nav( $urls ) {
    // var_dump("tetetetete"); exit;
    $urls['advertisement'] = array(
        'title' => __( 'Advertisement', 'dokan-lite'),
        'icon'  => '<i class="fa fa-ad"></i>',
        'url'   => dokan_get_navigation_url( 'advertisement' ),
        'pos'   => 51
    );
    return $urls;
}
add_action( 'dokan_load_custom_template', 'dokan_load_template' );
function dokan_load_template( $query_vars ) {
    if ( isset( $query_vars['advertisement'] ) ) {
        require_once dirname( __FILE__ ). '/custom/custom_dokan/dashboard/ads.php';
      }
}
// ////////////////////////////////////////////////////////////////////////////////////// #Vendor Advertisement - Admin dokan Dashboard Page ///////////////////////////////////////////////////////////////// 
function dokan_vendor_ads_page(){
    ?>
    <div id="csvDataTable" class ="custom-content wrap content-area">
        <h1 class="wp-heading-inline">Management Advertisement for Vendors</h1>
        <main id="main" class="site-main">
            <?php echo apply_filters('view_ads', '', 1, 3)?>
        </main>
    </div>
    <?php
}
function dokan_vendor_ads($content){
    global $wpdb;
    $limit=20;
    $page = isset($_GET['paged']) ? $_GET['paged'] : 1;
    $search = isset($_GET['search']) ? $_GET['search'] :'';
    $search_by = isset($_GET['searchBy']) ? $_GET['searchBy'] :'';

    $offset = ($page -1) * $limit;
    $count_query = "SELECT COUNT(*) 
                    FROM {$wpdb->prefix}posts AS u 
                    LEFT JOIN {$wpdb->prefix}postmeta AS um ON u.ID = um.post_id
                    WHERE meta_key = 'ads_info'";
    $totalrows = $wpdb->get_var($count_query);
    $totalpages=ceil($totalrows/$limit);

    $query = "SELECT u.*, um.meta_key, um.meta_value 
            FROM {$wpdb->prefix}posts AS u 
            LEFT JOIN {$wpdb->prefix}postmeta AS um ON u.ID = um.post_id 
            WHERE meta_key = 'ads_info'
            LIMIT %d OFFSET %d";
    $datas = $wpdb -> get_results($wpdb->prepare($query, $limit, $offset));
    
    $query = "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = 'ads_def_fee'";
    $ads_def_fee = ($wpdb -> get_results($wpdb->prepare($query)))[0]->meta_value;
    $query = "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = 'ads_per_fee'";
    $ads_per_fee = ($wpdb -> get_results($wpdb->prepare($query)))[0]->meta_value;
    
    ob_start();
    ?>
    <div>
        <div class="set-fee-form">
            <div class="fee-item">
                <label for="ads-def-fee-input">Default fee for 2 days ($) : </label>
                <input type="number" id="ads-def-fee-input" name="ads-def-fee-input" placeholder="" min="0">
            </div>
            <div class="fee-item">
                <label for="ads-per-fee-input">Fee per day ($): </label>
                <input type="number" id="ads-per-fee-input" name="ads-per-fee-input" placeholder="" min="0">
            </div>
            <div class="fee-item">
                <button id="update-fee-btn" class="alignright button button-primary" style="margin-left: 10px;">Update</button>
            </div>
        </div>
        <div class="present-fee-form">
            <div class="fee-item">
                <label for="present-ads-d-fee">Default fee for 2 days ($) : </label>
                <input type="number" id="present-ads-d-fee" name="present-ads-d-fee" value=<?php echo $ads_def_fee ? $ads_def_fee : 0; ?> placeholder="" min="0" readonly>
            </div>
            <div class="fee-item">
                <label for="present-ads-p-fee">Fee per day ($): </label>
                <input type="number" id="present-ads-p-fee" name="present-ads-p-fee" value=<?php echo $ads_per_fee ? $ads_per_fee : 0; ?> placeholder="" min="0" readonly>
            </div>
        </div>
        <div class="tablenav top">
            <button id="showAll" class="alignright button button-primary" style="margin-left: 10px;">Show All</button>
            <button id="searchButton" class="alignright button button-primary">Search</button>
            <p id="searchInput" class="search-box" style="margin-bottom: 10px;">
                <input type="search" id="post-search-input" name="s" placeholder="Search Vendors">
            </p>
        </div>
        <form action="" method="post">
            <table id="vendortable" class="wp-list-table widefat fixed striped" style="width:100%;table-layout: auto;"> 
                <thead> 
                    <tr>
                        <td class="check-column"><input id ="masterCheckbox" type="checkbox" name="all" value="all"></th>
                        <th class="column">Ads Title</th> 
                        <th class="column">Store</th> 
                        <th class="column">Product</th> 
                        <th class="column">Payout</th>
                        <th class="column">Paid</th>
                        <th class="column">Status</th> 
                        <th class="column">Published at</th> 
                        <th class="column">Expire at</th>
                    </tr> 
                </thead> 
                <tbody>
                    <?php
                    foreach ($datas as $data){
                        $meta_value = unserialize($data->meta_value);
                        $store_info = dokan_get_store_info($data->post_author);
                        $store_name = $store_info['store_name'];
                        $product_name = get_the_title(intval($meta_value['name']));
                        $payout = $meta_value['payout'];
                        // $paid_status = $meta_value['paid'];
                        $status = $meta_value['status'];
                        $pub_date = $data->post_date;
                        $expiration = (string)($meta_value['period']) . " days";
                        echo '<tr>';
                            echo '<th class="check-column"><input class="subCheckbox" type="checkbox" name="delete[]" value="' . ($data ->ID) . '"></td>';
                            echo '<td>' . ($data->post_title) . '</td>';
                            echo '<td>' . ($store_name ?? ''). '</td>';
                            echo '<td>' . ($product_name ?? '') . '</td>';
                            echo '<td>' . ($payout ?? '') . '</td>';
                            echo '<td>' . ($paid_status ?? 'no') . '</td>';
                        if($status == "pending"){
                            echo '<td><label class="switch"><input type="checkbox" id="status" name="status"><span class="slider round"></span></label></td>';
                        }else{
                            echo '<td><label class="switch"><input type="checkbox" id="status" name="status" checked><span class="slider round"></span></label></td>';
                        }
                            echo '<td>' . ($pub_date ?? '') . '</td>';
                            echo '<td>' . ($expiration ?? '') . '</td>';
                        echo '</tr>';            
                    } 
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="column">Action</th>
                        <th class="column">Ads Title</th> 
                        <th class="column">Store</th> 
                        <th class="column">Product</th>
                        <th class="column">Payout</th>
                        <th class="column">Paid</th>
                        <th class="column">Status</th> 
                        <th class="column">Published at</th> 
                        <th class="column">Expire at</th>
                    </tr> 
                </tfoot> 
            </table>
            <input type="submit" name="bulk_delete_ads" value="Delete Selected Ads" class="page-title-action" style="margin-top:10px">
        </form>
        <?php 
            ?>
        <div class="tablenav bottom">
            <div class="tablenav-pages">
                    <?php 
                    echo '<span class="displaying-num">' . $totalrows .' Advertisements</span>'; 
                    echo '<span class="pagination-links">';
                        if ($page==1){
                            echo '<span aria-hidden="true" class="tablenav-pages-navspan button disabled" style = "margin:0px 5px">«</span>';
                            echo '<span aria-hidden="true" class="tablenav-pages-navspan button disabled" style = "margin:0px 5px">‹</span>';
                        }else{
                            echo '<a href="' . add_query_arg('paged', 1) . '" style = "margin:0px 5px">';
                            echo '<span aria-hidden="true" class="tablenav-pages-navspan button">«</span>';
                            echo '</a>';
                            echo '<a href="' . add_query_arg('paged', $page-1) . '" style = "margin:0px 5px">';
                            echo '<span aria-hidden="true" class="tablenav-pages-navspan button">‹</span>';
                            echo '</a>';
                        }
                        echo '<span class="paging-input">';
                        echo '<span class="tablenav-paging-text">' . $page . ' of <span class="total-pages">' .  $totalpages  . '</span></span>';
                        echo '</span>';
                    ?>
                    <?php 
                        if ($page==$totalpages){
                            echo '<span aria-hidden="true" class="tablenav-pages-navspan button disabled" style = "margin:0px 5px">›</span>';
                            echo '<span aria-hidden="true" class="tablenav-pages-navspan button disabled" style = "margin:0px 5px">»</span>';
                        }else{
                            echo '<a href="' . add_query_arg('paged', $page + 1) . '" style = "margin:0px 5px">';
                            echo '<span aria-hidden="true" class="tablenav-pages-navspan button" >›</span>';
                            echo '</a>';
                            echo '<a href="' . add_query_arg('paged', $totalpages) . '" style = "margin:0px 5px">';
                            echo '<span aria-hidden="true" class="tablenav-pages-navspan button">»</span>';
                            echo '</a>';
                        }
                    ?>
            </div>
        </div>
    </div>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['bulk_delete_ads'])){
            if (isset($_POST["delete"]) && !empty($_POST["delete"])) {
                $selectedRows = $_POST["delete"];
                foreach ($selectedRows as $value) {
                    global $wpdb;
                    wp_delete_post(intval($value), true);     /////here
                }
                echo '<div class="notice notice-success"><p>Deleted successfully.</p></div>';
                header("Location: ".$_SERVER['REQUEST_URI']);
            }else {
                echo '<div class="notice notice-warning"><p>No checkboxes selected.</p></div>';
                echo '<script>alert("No one selected!");</script>';
            }
        }
    }
    $content = ob_get_clean();
    return $content;
}
add_filter('view_ads', 'dokan_vendor_ads');
function my_custom_admin_footer_script() {
}
add_action('admin_footer', 'my_custom_admin_footer_script');
?>
<?php 
    add_action('admin_notices', 'suppress_admin_notices_on_compliant_page', 1);
    add_action('all_admin_notices', 'suppress_admin_notices_on_compliant_page', 1);
    
    function suppress_admin_notices_on_compliant_page() {
        if ($_GET['page'] === 'non-compliant-setting' || $_GET['page'] === 'manage-non-compliants' || $_GET['page'] === 'non-compliant-products') {
            remove_all_actions('admin_notices');
            remove_all_actions('all_admin_notices');
        }
    }
    
    function manage_non_compliant_custom_menu(){
        add_menu_page(
            'Manage Non-compliant',  // Page title
            'Non-compliant',  // Menu title
            'manage_options',     // Capability
            'manage-non-compliants',   // Menu slug
            'manage_non_compliant_page_content',   // Function to display page content
            'dashicons-book',
            2                     // Position
        );
        
        add_submenu_page(
            'manage-non-compliants', // Parent slug
            'Non-Compliant Managing - Products', // Page title
            'Products', // Menu title
            'manage_options', // Capability required to view this menu
            'non-compliant-products', // Menu slug
            'non_compliant_products_content' // Callback function to display the content
        );
        
        add_submenu_page(
            'manage-non-compliants', // Parent slug
            'Non-Compliant Managing - Setting', // Page title
            'Setting', // Menu title
            'manage_options', // Capability required to view this menu
            'non-compliant-setting', // Menu slug
            'non_compliant_setting_content' // Callback function to display the content
        );
    }
    add_action('admin_menu', 'manage_non_compliant_custom_menu',11);
    
    function manage_non_compliant_page_content() {
        ?>
        <div class="wrap">
            <h1>Manage Non-Compliant Page</h1>
            <button id="nonc-remove-btn">remove ext products from wwmalls</button>
            <button id="nonc-remove-all-btn">remove all products from wwmalls</button>
        </div>
        <?php
    }
    
    function non_compliant_setting_content(){
        ?>
        <h1>Setting Page</h1>
        <div class="wrap">
            <div class='setting panel'>
                <div class="panel-title">
                    <p>Google Product Category Import</p>
                </div>
                <div class="panel-content">
                    <?php if (isset($_GET['status'])): ?>
                        <div class="notice notice-<?php echo $_GET['status'] === 'success' ? 'success' : 'error'; ?> is-dismissible">
                            <p><?php echo $_GET['status'] === 'success' ? 'Categories imported successfully.' : 'There was an error importing the categories.'; ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post" enctype="multipart/form-data" action="<?php echo admin_url('admin-post.php'); ?>">
                        <input type="hidden" name="action" value="import_google_taxonomy">
                        <input type="file" name="taxonomy_file" accept=".txt" required>
                        <?php submit_button('Upload and Import'); ?>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
    
    add_action('admin_post_import_google_taxonomy', 'handle_google_taxonomy_upload');
    function handle_google_taxonomy_upload() {
        if (!current_user_can('manage_options')) {
            wp_die('You do not have sufficient permissions to access this page.');
        }
    
        if (isset($_FILES['taxonomy_file']) && $_FILES['taxonomy_file']['error'] == UPLOAD_ERR_OK) {
            $file = $_FILES['taxonomy_file']['tmp_name'];
            
            // Parse and import the taxonomy
            $taxonomy = parse_google_product_taxonomy($file);
            if ($taxonomy !== false) {
                import_google_product_taxonomy($taxonomy);
                // wp_redirect(admin_url('tools.php?page=google-product-taxonomy-import&status=success'));
            } else {
                // wp_redirect(admin_url('tools.php?page=google-product-taxonomy-import&status=error'));
            }
            exit;
        } else {
            // wp_redirect(admin_url('tools.php?page=google-product-taxonomy-import&status=error'));
            exit;
        }
    }
    
    function parse_google_product_taxonomy($file_path) {
        $taxonomy = [];
    
        if (($handle = fopen($file_path, 'r')) !== false) {
            while (($line = fgets($handle)) !== false) {
                $line = trim($line);
                if (!empty($line)) {
                    $parts = explode(' - ', $line, 2);
                    if (count($parts) == 2) {
                        $taxonomy[] = [
                            'id' => trim($parts[0]),
                            'name' => trim($parts[1])
                        ];
                    }
                }
            }
            fclose($handle);
        } else {
            return false;
        }
    
        return $taxonomy;
    }
    
    function get_existing_category_id($name, $parent_id = 0) {
        $term = get_term_by('name', $name, 'product_cat', ARRAY_A, $parent_id);
        return $term ? $term['term_id'] : 0;
    }
    
    function import_google_product_taxonomy($taxonomy) {
        foreach ($taxonomy as $category) {
            $name_parts = explode(' > ', $category['name']);
            $parent_id = 0;
    
            foreach ($name_parts as $part) {
                $existing_category_id = get_existing_category_id($part, $parent_id);
                
                if (!$existing_category_id) {
                    $result = wp_insert_term(
                        $part,
                        'product_cat',
                        [
                            'parent' => $parent_id,
                            'slug' => sanitize_title($part)
                        ]
                    );
    
                    if (is_wp_error($result)) {
                        echo 'Error creating category: ' . $result->get_error_message() . '<br>';
                        break;
                    } else {
                        $parent_id = $result['term_id'];
                    }
                } else {
                    $parent_id = $existing_category_id;
                }
            }
        }
        echo 'All categories have been imported.';
    }

    
    function non_compliant_products_content(){
        $site_infos = read_sites();
        
        ?>
        <!--<link rel="stylesheet" type="text/css" href="https://cdn3.devexpress.com/jslib/23.2.6/css/dx.material.blue.light.css" />-->
        <!--<script src="https://cdn3.devexpress.com/jslib/23.2.6/js/dx.all.js"></script>-->
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/ag-grid-community@31.3.2/dist/ag-grid-community.js?t=1717759329750"></script>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        
        <style media="only screen">
              :root,
              body {
                height: 100%;
                width: 100%;
                margin: 0;
                box-sizing: border-box;
                /*-webkit-overflow-scrolling: touch;*/
              }
        
              html {
                position: absolute;
                top: 0;
                left: 0;
                padding: 0;
                /*overflow: auto;*/
              }
        
              body {
                padding: 16px;
                /*overflow: auto;*/
                background-color: transparent;
              }
        
              /* Hide codesandbox highlighter */
              body > #highlighter {
                display: none;
              }
            </style>
        <div class="wrap">
            <h1>Manage Products</h1>
            <div class = "prducts-table-content">
                <div class = "tablenav top">
                    <div class = "alignleft actions n-compliant-sites">
                        <select name="action" id="sites-selector-top">
                            <option value="-1"> - Select Site - </option> 
                            <?php 
                            foreach($site_infos as $site_info){
                                echo "<option value=\"{$site_info->id}\">{$site_info->domain}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class = "manage-products-wrapper">
                    <div id="c-side-panel">
                        <div class="data-viewport">
                            <input type="text" id="search" placeholder="Search Category">
                            <div id= "treeview"></div>
                        </div>
                        <div id= "import-setting-panel">
                            <div class="panel-title">
                                <p>Setting</p>
                            </div>
                            <div class="panel-content">
                                <div class="panel-item">
                                    <label for="fee-rate" class= "c-col-4" style="font-size:14px">Fee Rate:</label>
                                    <input type="number" class= "c-col-8" id="fee-rate" name="fee-rate" min="0" max="100" step="0.01" value="12" required>
                                </div>
                                <div class="panel-item justify-content-between">
                                    <button id="enable_btn" type="button" class="btn btn-success btn-sm">Enable/Update</button>
                                    <button id="disable_btn" type="button" class="btn btn-danger btn-sm">Disable</button>
                                </div>
                                <!--<div class="panel-item">-->
                                <!--    <button id="reset-all" class="btn btn-warning btn-sm" type="button">Reset All</button>-->
                                <!--</div>-->
                            </div>
                            <div class="panel-notice">
                                <p>Only selected products are enabled or disabled .</p>
                            </div>
                            
                            
                        </div>
                    </div>
                    <div class = "table-content">
                        <div id="data_table_for_tree" style="height: 100%" class="ag-theme-quartz"></div>
                        <div>
                            Selected Rows Count: <span id="selectedRowsCount">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            (function () {
                const appLocation = "";
        
                window.__basePath = appLocation;
            })();
        </script>
        <?php
    }
    
    function read_sites(){
        global $wpdb;
    
        $query = "SELECT * FROM wp_websites";
        $site_infos = $wpdb->get_results($query);
        
        return $site_infos;
    }
?>
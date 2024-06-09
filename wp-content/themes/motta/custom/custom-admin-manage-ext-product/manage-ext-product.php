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
        </div>
        <?php
    }
    
    function non_compliant_setting_content(){
        ?>
        <div class="wrap">
            <h1>Setting Page</h1>
        </div>
        <?php
    }
    
    function non_compliant_products_content(){
        $site_infos = read_sites();
        
        ?>
        <!--<link rel="stylesheet" type="text/css" href="https://cdn3.devexpress.com/jslib/23.2.6/css/dx.material.blue.light.css" />-->
        <!--<script src="https://cdn3.devexpress.com/jslib/23.2.6/js/dx.all.js"></script>-->
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/ag-grid-community@31.3.2/dist/ag-grid-community.js?t=1717759329750"></script>

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
                overflow: auto;
              }
        
              body {
                padding: 16px;
                overflow: auto;
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
                <div class = "manage_products_wrapper">
                    <div id="c_side_panel">
                        <div class="data-viewport">
                            <input type="text" id="search" placeholder="Search Category">
                            <div id= "treeview"></div>
                        </div>
                        <div id= "import_setting_panel">
                            <h4>Setting</h4>
                        </div>
                    </div>
                    <div class = "table-content">
                        <div id="data_table_for_tree" style="height: 100%" class="ag-theme-quartz"></div>
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
    
    // function replace_jquery_version(){
    //     wp_deregister_script('jquery-core-js');
    //     // wp_register_script('jquery-core-js', 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js', array(), '3.5.1', true);
    //     wp_register_script('jquery-core-js', 'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js', array(), '3.3.1', true);
    //     wp_enqueue_script('jquery-core-js');
    // }
    // add_action('wp_enqueue_scripts', 'replace_jquery_on_specific_page');
?>
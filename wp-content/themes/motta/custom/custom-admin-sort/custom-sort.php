<?php 
    function dokan_vendor_add_submenu(){
        add_submenu_page(
            'dokan',
            'Manage Sorting', // Page title
            'Manage Sorting', // Menu title
            'add_users', // Capability
            'dokan-vendor-sort', // Menu slug
            'dokan_vendor_sort_page', // Function to display the page
    		6
        );
    }
    add_action('admin_menu', 'dokan_vendor_add_submenu', 12);
    
    function dokan_vendor_sort_page(){
        ?>
        <div id="csvDataTable" class ="custom-content wrap content-area">
            <h1 class="wp-heading-inline">Management Sorting Vendors</h1>
            <main id="main" class="site-main">
                <?php echo apply_filters('view_sort_page', '', 1, 3)?>
            </main>
        </div>
        <?php
    }
    
    function dokan_vendor_sort(){
        return '';
    }
    add_filter('view_sort_page', 'dokan_vendor_sort');
?>
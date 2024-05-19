<?php 
    function add_biography_store(){
        $store_user = get_userdata(get_query_var('author'));
        $store_info = dokan_get_store_info($store_user->ID);
        
        if(dokan_is_store_page()){
            echo '  <div class="wrap-collabsible"> 
                        <input id="collapsible2" class="toggle" type="checkbox"> 
                        <label for="collapsible2" class="lbl-toggle">More info about us...</label>
                        <div class="collapsible-content">
                            <div class="content-inner">
                                <p>';
            echo $store_info['vendor_biography'];
            echo                '</p>
                            </div>
                        </div>
                    </div>';
        }
    }
    add_shortcode('store_biography', 'add_biography_store');
    
    if ( ! function_exists( 'dokan_is_store_page' ) ) {
        function dokan_is_store_page() {
            $custom_store_url = dokan_get_option( 'custom_store_url', 'dokan_general', 'store' );
        
            if ( get_query_var( $custom_store_url ) ) {
                return true;
            }
        
            return false;
        }
    }
?>
<?php 
    function dokan_remove_seller_info_option( $tabs ){  
      unset ($tabs ['seller']); 
      return $tabs;
    }
    add_filter( 'woocommerce_product_tabs', 'dokan_remove_seller_info_option', 1100 );
?>
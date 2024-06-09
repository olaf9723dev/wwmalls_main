<?php 
    add_filter( 'dokan_store_lists_sort_by_options', 'add_sort_by_options');
    function add_sort_by_options($options){
        $options['storename']     = __( 'Name', 'dokan' );
        return $options;
    }
    
    add_action( 'dokan_before_filter_user_query', 'filter_user_query', 10, 2 );
    function filter_user_query( $query, $orderby ){
        $query = filter_query_from($query, $orderby);
        $query = filter_query_orderby($query, $orderby);
    }
    
    function filter_query_from($query, $orderby){
        global $wpdb;
        
        if ( '. storename .' === $orderby ) {
            $query->query_from .= " LEFT JOIN (
                    SELECT user_id, meta_value AS store_name
                    FROM {$wpdb->prefix}usermeta
                    WHERE {$wpdb->prefix}usermeta.meta_key = 'dokan_store_name'
                    ) as storenames
                    ON ({$wpdb->users}.ID = storenames.user_id)";

        }
        return $query;
    }
    function filter_query_orderby($query, $orderby){
        if ( '. storename .' === $orderby ) {
            $query->query_orderby = 'ORDER BY storenames.store_name ASC';
        }
        return $query;
    }
?>
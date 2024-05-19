<?php 

    function change_membership_redirect_url($default_redirect){
        $page_id = get_page_by_title( 'VIP Membership Plan' )->ID;
        $memeber_url = get_permalink( $page_id );
        
        return $memeber_url ? $memeber_url : $default_redirect;
    }
    add_filter('membership_return_to_shop_redirect', 'change_membership_redirect_url');
?>
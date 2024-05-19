<?php 
    add_filter( 'woo_wallet_is_user_wallet_locked', 'woo_wallet_is_user_wallet_locked_callback', 100, 2 );
    if ( ! function_exists( 'woo_wallet_is_user_wallet_locked_callback' ) ) {
    	function woo_wallet_is_user_wallet_locked_callback( $is_locked, $user_id ) {
    // 		$user = new WP_User( $user_id );
    // 		if ( in_array( 'author', (array) $user->roles, true ) ) {
    // 			// The user has the "author" role so block wallet.
    // 			$is_locked = true;
    // 		}
            $member = get_user_meta($user_id, 'is_member', true);
    		if ( is_null($member) || $member != 'member' ) {
    			// The user has the this role so block wallet.
    			$is_locked = true;
    		}
    		return $is_locked;
    	}
    }
    add_filter( 'display_cashback_notice_at_woocommerce_page', 'display_cashback_notice_at_woocommerce_page_callback', 10, 1 );
    if ( ! function_exists( 'display_cashback_notice_at_woocommerce_page_callback' ) ) {
    	function display_cashback_notice_at_woocommerce_page_callback( $display ) {
    		if(is_wallet_account_locked()){
    			return false;
    		}
    		return $display;
    	}
    }
?>
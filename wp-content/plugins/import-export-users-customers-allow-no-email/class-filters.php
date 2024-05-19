<?php

class ACUI_ANM_Filters{
    function __construct(){
    }

    function bootstrap(){
        add_filter( 'pre_acui_import_single_user_email', array( $this, 'filter_email' ) );
        add_action( 'post_acui_import_single_user', array( $this, 'new_user' ), 10, 9 );
        add_action( 'personal_options_update', array( $this, 'updated' ) );
		add_action( 'template_redirect', array( $this, 'redirect' ) );
		add_action( 'current_screen', array( $this, 'redirect' ) );
		add_action( 'admin_notices', array( $this, 'notice' ) );
        add_filter( 'acui_documentation_email_message', array( $this, 'documentation_email_message' ) );
        add_filter( 'acui_allow_no_email', '__return_true' );

        if ( ! function_exists( 'is_plugin_active' ) )
            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

        if( is_plugin_active( 'woocommerce/woocommerce.php' ) ){
            add_filter( 'acui_force_user_change_email_edit_profile_url', array( $this, 'woocommerce_force_reset_password_edit_profile_url' ) );
            add_filter( 'acui_force_user_change_email_redirect_condition', array( $this, 'woocommerce_force_user_change_email_redirect_condition' ) );
            add_action( 'wp_head', array( $this, 'woocommerce_force_user_change_email_notice' ) );
            add_action( 'template_redirect', array( $this, 'woocommerce_force_user_change_email_save_account_details' ), 9 );
            add_action( 'woocommerce_edit_account_form', array( $this, 'woocommerce_remove_fake_email' ) );
        }
    }

    function filter_email( $email ){
        if( !empty( $email ) )
            return $email;
        
        $new_email = $this->get_random_unique_email( 'acui_' );

        $emails_generated = get_transient( 'acui_anm_emails_generated' );
        if( !is_array( $emails_generated ) )
            $emails_generated = array();

        $emails_generated[] = $new_email;
        set_transient( 'acui_anm_emails_generated', $emails_generated, HOUR_IN_SECONDS );

        return $new_email;
    }

    function get_random_unique_email( $prefix = '' ){
        do {
            $rnd_str = $this->random_string() . '@' . $this->random_string() . '.com';
        } while( email_exists( $rnd_str ) );
        
        return $prefix . $rnd_str;
    }

    function random_string( $length = 6 ) {
        return substr( str_shuffle( str_repeat( $x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil( $length / strlen( $x ) ) ) ), 1, $length );
    }

    function new_user( $headers, $data, $user_id, $role, $positions, $form_data, $is_frontend, $is_cron, $password_changed ){
        if( !isset( $form_data["force_user_change_emails_blank"] ) || !in_array( $form_data["force_user_change_emails_blank"], array( 'yes', 1 ) ) )
		    return;

        $user = new WP_User( $user_id );
        $emails_generated = get_transient( 'acui_anm_emails_generated' );

        if( !is_array( $emails_generated ) )
            return;

        if( !in_array( $user->user_email, $emails_generated ) )
            return;

        update_user_meta( $user_id, 'acui_force_user_change_email_blank', 1 );
	}

    function updated( $user_id ){
		if( !isset( $_POST['email'] ) )
            return;

        if( !get_user_meta( $user_id, 'acui_force_user_change_email_blank', true ) )
            return;

		$user = new WP_User( $user_id );
        if( $user->user_email != sanitize_text_field( $_POST['email'] ) )        
		    delete_user_meta( $user_id, 'acui_force_user_change_email_blank' );
	}

	function redirect() {
        if( is_admin() ) {
			$screen = get_current_screen();

			if ( in_array( $screen->base, array( 'profile', 'plugins' ) ) )
				return;
		}

		if( !is_user_logged_in() )
			return;

        if( apply_filters( 'acui_force_user_change_email_redirect_condition', false ) )
            return;

		if( get_user_meta( get_current_user_id(), 'acui_force_user_change_email_blank', true ) ) {
			wp_redirect( apply_filters( 'acui_force_user_change_email_edit_profile_url', admin_url( 'profile.php' ) ) );
			die();
		}
	}

	function notice(){
		if ( get_user_meta( get_current_user_id(), 'acui_force_user_change_email_blank', true ) ) {
			printf( '<div class="error"><p>%s</p></div>', apply_filters( 'acui_force_change_email_message', __( 'Please update your email', 'import-users-from-csv-with-meta' ) ) );
		}
	}

    function woocommerce_force_reset_password_edit_profile_url(){
        return wc_get_account_endpoint_url( 'edit-account' );
    }

    function woocommerce_force_user_change_email_redirect_condition( $condition ){
        return ( function_exists( 'is_edit_account_page' ) ) ? is_edit_account_page() : $condition;
    }

    function woocommerce_force_user_change_email_notice(){
        if ( get_user_meta( get_current_user_id(), 'acui_force_user_change_email_blank', true ) ) {
            wc_add_notice( apply_filters( 'acui_force_change_email_message', __( 'Please change your email', 'import-users-from-csv-with-meta' ) ), 'error' );
        }
    }

    function woocommerce_force_user_change_email_save_account_details(){
        if ( empty( $_POST['action'] ) || 'save_account_details' !== $_POST['action'] ) {
			return;
		}

        if( !is_user_logged_in() )
            return;

        $user = wp_get_current_user();

        if( !get_user_meta( $user->ID, 'acui_force_user_change_email_blank', true ) )
            return;
        
        if( !isset( $_POST['account_email'] ) || empty( $_POST['account_email'] ) )
            return;

        if( $user->user_email != sanitize_text_field( $_POST['account_email'] ) )        
		    delete_user_meta( $user->ID, 'acui_force_user_change_email_blank' );
    }

    function woocommerce_remove_fake_email(){
        if( !get_user_meta( get_current_user_id(), 'acui_force_user_change_email_blank', true ) )
            return;

        ?><script>jQuery( '#account_email' ).val( '' );</script><?php
    }

    function documentation_email_message(){
        return __( '<strong>You can leave the email empty</strong>, because you are using <em>Allow No Email Addon</em>. When importing you will be able to choose if you want to force your users to fill in the email when they log in.', 'import-users-from-csv-with-meta' );
    }
}

add_action( 'init', function(){
    $acui_anm_filters = new ACUI_ANM_Filters();
    $acui_anm_filters->bootstrap();
} );
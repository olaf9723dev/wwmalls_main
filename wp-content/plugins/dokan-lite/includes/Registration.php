<?php

namespace WeDevs\Dokan;

use WP_Error;

/**
 * Vendor Registration
 *
 * @since 2.8
 */
class Registration {

    public function __construct() {
        // validate registration
        add_filter( 'woocommerce_process_registration_errors', [ $this, 'validate_registration' ] );
        add_filter( 'woocommerce_registration_errors', [ $this, 'validate_registration' ] );

        // after registration
        add_filter( 'woocommerce_new_customer_data', [ $this, 'set_new_vendor_names' ] );
        add_action( 'woocommerce_created_customer', [ $this, 'save_vendor_info' ], 10, 2 );
    }

    /**
     * Validate vendor registration
     *
     * @param \WP_Error $error
     *
     * @return \WP_Error
     */
    public function validate_registration( $error ) {
        if ( is_checkout() ) {
            return $error;
        }

        if ( defined( 'WP_CLI' ) || defined( 'REST_REQUEST' ) ) {
            return $error;
        }

        $nonce_check = apply_filters( 'dokan_register_nonce_check', true );

        if ( $nonce_check ) {
            $nonce_value = isset( $_POST['_wpnonce'] ) ? sanitize_key( $_POST['_wpnonce'] ) : '';
            $nonce_value = isset( $_POST['woocommerce-register-nonce'] ) ? sanitize_key( $_POST['woocommerce-register-nonce'] ) : $nonce_value;

            if ( empty( $nonce_value ) || ! wp_verify_nonce( $nonce_value, 'woocommerce-register' ) ) {
                return new WP_Error( 'nonce_verification_failed', __( 'Nonce verification failed', 'dokan-lite' ) );
            }
        }

        $allowed_roles = apply_filters( 'dokan_register_user_role', [ 'customer', 'seller' ] );

        // is the role name allowed or user is trying to manipulate?
        if ( isset( $_POST['role'] ) && ! in_array( $_POST['role'], $allowed_roles, true ) ) {
            return new WP_Error( 'role-error', __( 'Cheating, eh?', 'dokan-lite' ) );
        }

        $role            = sanitize_text_field( wp_unslash( $_POST['role'] ) );
        $shop_url        = isset( $_POST['shopurl'] ) ? sanitize_text_field( wp_unslash( $_POST['shopurl'] ) ) : '';
        $store_category  = isset( $_POST['store-category'] ) ? sanitize_text_field( wp_unslash( $_POST['store-category'] ) ) : '';  //Custom Code for store_category of vendor
        $required_fields = apply_filters(
            'dokan_seller_registration_required_fields', [
                'fname'    => __( 'Please enter your first name.', 'dokan-lite' ),
                'lname'    => __( 'Please enter your last name.', 'dokan-lite' ),
                'phone'    => __( 'Please enter your phone number.', 'dokan-lite' ),
                'shopname' => __( 'Please provide a shop name.', 'dokan-lite' ),
                'shopurl'  => __( 'Please provide a unique shop URL.', 'dokan-lite' ),
            ]
        );

        if ( $role === 'seller' ) {
            foreach ( $required_fields as $field => $msg ) {
                $field_value = isset( $_POST[ $field ] ) ? trim( sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) ) : '';
                if ( empty( $field_value ) ) {
                    return new WP_Error( "$field-error", $msg );
                }
            }

            // Check if the shop URL already not in use.
            if ( ! empty( get_user_by( 'slug', $shop_url ) ) ) {
                return new WP_Error( 'shop-url-error', __( 'Shop URL is not available', 'dokan-lite' ) );
            }
        }

        return $error;
    }

    /**
     * Inject first and last name to WooCommerce for new vendor registraion
     *
     * @param array $data
     *
     * @return array
     */
    public function set_new_vendor_names( $data ) {
        $nonce_value = isset( $_POST['_wpnonce'] ) ? sanitize_key( wp_unslash( $_POST['_wpnonce'] ) ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        $nonce_value = isset( $_POST['woocommerce-register-nonce'] ) ? sanitize_key( wp_unslash( $_POST['woocommerce-register-nonce'] ) ) : $nonce_value; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

        if ( ! wp_verify_nonce( $nonce_value, 'woocommerce-register' ) ) {
            return $data;
        }

        $allowed_roles = apply_filters( 'dokan_register_user_role', [ 'customer', 'seller' ] );
        $role          = ( isset( $_POST['role'] ) && in_array( $_POST['role'], $allowed_roles, true ) ) ? sanitize_text_field( wp_unslash( $_POST['role'] ) ) : 'customer';

        $data['role'] = $role;

        if ( $role !== 'seller' ) {
            return $data;
        }

        $data['first_name']    = isset( $_POST['fname'] ) ? sanitize_text_field( wp_unslash( $_POST['fname'] ) ) : '';
        $data['last_name']     = isset( $_POST['lname'] ) ? sanitize_text_field( wp_unslash( $_POST['lname'] ) ) : '';
        $data['user_nicename'] = isset( $_POST['shopurl'] ) ? sanitize_user( wp_unslash( $_POST['shopurl'] ) ) : '';

        return $data;
    }

    /**
     * Adds default dokan store settings when a new vendor registers
     *
     * @param int   $user_id
     * @param array $data
     *
     * @return void
     */
    //  // Start Custom Code for vendor category field
    // private function cget_term_id(){
    //     global $wpdb;
    //     $datas = $GLOBALS['wpdb']->get_results("SELECT u.*, um.taxonomy, um.term_taxonomy_id 
    //     FROM {$wpdb->prefix}terms AS u 
    //     LEFT JOIN {$wpdb->prefix}term_taxonomy AS um ON u.term_id = um.term_id 
    //     WHERE name = 'Uncategorized' AND taxonomy = 'store_category'");
        
    //     return $datas[0]->term_taxonomy_id;
    // }
    //  // End Custom Code for vendor category field
     
    public function save_vendor_info( $user_id, $data ) {
        $nonce_value = isset( $_POST['_wpnonce'] ) ? sanitize_key( wp_unslash( $_POST['_wpnonce'] ) ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        $nonce_value = isset( $_POST['woocommerce-register-nonce'] ) ? sanitize_key( wp_unslash( $_POST['woocommerce-register-nonce'] ) ) : $nonce_value; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

        if ( ! wp_verify_nonce( $nonce_value, 'woocommerce-register' ) ) {
            return;
        }

        if ( ! isset( $data['role'] ) || $data['role'] !== 'seller' ) {
            return;
        }

        $social_profiles = [];

        foreach ( dokan_get_social_profile_fields() as $key => $item ) {
            $social_profiles[ $key ] = '';
        }

        $dokan_settings = [
            'store_name'     => isset( $_POST['shopname'] ) ? sanitize_text_field( wp_unslash( $_POST['shopname'] ) ) : '',
            'social'         => $social_profiles,
            'payment'        => [],
            'address'        => isset( $_POST['dokan_address'] ) ? wc_clean( wp_unslash( $_POST['dokan_address'] ) ) : '',
            'phone'          => isset( $_POST['phone'] ) ? dokan_sanitize_phone_number( wp_unslash( $_POST['phone'] ) ) : '',
            'show_email'     => 'no',
            'location'       => '',
            'find_address'   => '',
            'dokan_category' => '',
            'banner'         => 0,
        ];

        // Intially add values on profile completion progress bar
        $dokan_settings['profile_completion']['store_name']    = 10;
        $dokan_settings['profile_completion']['phone']         = 10;
        $dokan_settings['profile_completion']['next_todo']     = 'banner_val';
        $dokan_settings['profile_completion']['progress']      = 20;
        $dokan_settings['profile_completion']['progress_vals'] = [
            'banner_val'          => 15,
            'profile_picture_val' => 15,
            'store_name_val'      => 10,
            'address_val'         => 10,
            'phone_val'           => 10,
            'map_val'             => 15,
            'payment_method_val'  => 15,
            'social_val'          => [
                'fb'       => 4,
                'twitter'  => 2,
                'youtube'  => 2,
                'linkedin' => 2,
            ],
        ];

        $dokan_settings = $this->check_and_set_address_profile_completion( $user_id, $dokan_settings, $dokan_settings );
        
        // // Start Custom Code for vendor category field
        // global $wpdb, $new_term, $new_term_taxonomy, $new_term_relationships;
        // $store_category  = isset( $_POST['store-category'] ) ? sanitize_text_field( wp_unslash( $_POST['store-category'] ) ) : '';
        // $category = get_term_by('name', $store_category, 'store_category');
        // $term_id = $category->term_id;
        // $term_taxonomy_id = (get_term($term_id, 'store_category'))->term_taxonomy_id;

        // $temp_categories_un = array(
        //     (object)array(
        //         'term_id'=>'',
        //         'term_taxonomy_id' => '',
        //         'name' => '',
        //         'slug'=>'',
        //         'taxonomy'=> '',
        //     ),
        // );

        // $where = array(
        //     'object_id' => $user_id,
        //     'term_taxonomy_id' => $this->cget_term_id(),
        // );
        // $new_term_relationships= array(
        //     'object_id' => $user_id,
        //     'term_taxonomy_id' => $term_taxonomy_id,
        //     'term_order' => 0,
        // );
        // $wpdb->update($wpdb->prefix . 'term_relationships', $new_term_relationships, $where);   

        // $temp_categories_un[0]->term_id = $term_id;
        // $temp_categories_un[0]->term_taxonomy_id = $term_taxonomy_id;
        // $temp_categories_un[0]->name = $store_category;
        // $temp_categories_un[0]->slug = implode('-', explode(' ',strtolower($store_category)));
        // $temp_categories_un[0]->taxonomy = "store_category";
        // // End Custom Code for vendor category field
        // $dokan_settings['categories'] = $temp_categories_un; // Custom Code for vendor category field
        
        update_user_meta( $user_id, 'dokan_profile_settings', $dokan_settings );
        update_user_meta( $user_id, 'dokan_store_name', $dokan_settings['store_name'] );

        do_action( 'dokan_new_seller_created', $user_id, $dokan_settings );
    }

    /**
     * Adds address profile completion value in dokan settings.
     *
     * @3.10.2
     *
     * @param int   $vendor_id
     * @param array $new_dokan_settings
     * @param array $old_profile_settings
     *
     * @return array
     */
    public function check_and_set_address_profile_completion( $vendor_id, $new_dokan_settings, $old_profile_settings ) {
        // Check address and add manually values on Profile Completion also increase progress value
        if ( ! empty( $new_dokan_settings['profile_completion']['progress_vals']['address_val'] ) ) {
            $new_dokan_settings['profile_completion']['address'] = $new_dokan_settings['profile_completion']['progress_vals']['address_val'];
        }

        if ( empty( $new_dokan_settings['address']['street_1'] ) ) {
            unset( $new_dokan_settings['profile_completion']['address'] );
        }

        if ( empty( $new_dokan_settings['address']['city'] ) && ! empty( $new_dokan_settings['profile_completion']['address'] ) ) {
            unset( $new_dokan_settings['profile_completion']['address'] );
        }

        if ( empty( $new_dokan_settings['address']['zip'] ) && ! empty( $new_dokan_settings['profile_completion']['address'] ) ) {
            unset( $new_dokan_settings['profile_completion']['address'] );
        }

        if ( empty( $new_dokan_settings['address']['country'] ) && ! empty( $new_dokan_settings['profile_completion']['address'] ) ) {
            unset( $new_dokan_settings['profile_completion']['address'] );
        } else {
            $country = isset( $new_dokan_settings['address']['country'] ) ? $new_dokan_settings['address']['country'] : '';

            if ( isset( $states[ $country ] ) && is_array( $states[ $country ] ) && empty( $new_dokan_settings['address']['state'] ) && ! empty( $new_dokan_settings['profile_completion']['address'] ) ) {
                unset( $new_dokan_settings['profile_completion']['address'] );
            }
        }

        if ( ! empty( $new_dokan_settings['profile_completion']['address'] ) ) {
            $progress = empty( $old_profile_settings['profile_completion']['progress'] ) ? 0 : $old_profile_settings['profile_completion']['progress'];
            $new_dokan_settings['profile_completion']['progress'] = $progress + $new_dokan_settings['profile_completion']['progress_vals']['address_val'];
        }

        return $new_dokan_settings;
    }
}

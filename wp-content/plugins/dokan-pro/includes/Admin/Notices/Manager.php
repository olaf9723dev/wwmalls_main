<?php

namespace WeDevs\DokanPro\Admin\Notices;

/**
 * Admin notices handler class
 *
 * @since 3.4.3
 */
class Manager {
    /**
     * Class constructor
     */
    public function __construct() {
        $this->init_classes();
        // temporary disabling Dokan Pro Survey so not removing the existing codebase
        $this->init_hooks();
    }

    /**
     * Register all notices classes to chainable container
     *
     * @since 3.4.3
     *
     * @return void
     */
    private function init_classes() {
        new DokanLiteMissing();
        new WhatsNew();
    }

    /**
     * Load Hooks
     *
     * @since 3.4.3
     *
     * @return void
     */
    private function init_hooks() {
        // dokan pro survey notices
        //add_filter( 'dokan_admin_promo_notices', [ $this, 'dokan_pro_survey_notice' ] );
        //add_action( 'wp_ajax_dismiss_dokan_pro_survey_notice', [ $this, 'ajax_dismiss_dokan_pro_survey_notice' ] );

        // paypal adaptive module remove notices
        add_filter( 'dokan_admin_notices', [ $this, 'paypal_adaptive_module_remove_notice' ] );
        add_action( 'wp_ajax_dismiss_dokan_pam_remove_notice', [ $this, 'ajax_paypal_adaptive_module_remove_notice' ] );
    }

    /**
     * Display dismiss Table Rate Shipping module notice
     *
     * @since 3.4.3
     *
     * @param array $notices
     *
     * @return array
     */
    public function dokan_pro_survey_notice( $notices ) {
        if ( 'yes' === get_option( 'dismiss_dokan_pro_survey_notice', 'no' ) ) {
            return $notices;
        }

        $notices[] = [
            'type'              => 'info',
            'title'             => __( 'Would you mind spending 5-7 minutes to improve Dokan Pro by answering 7 simple questions?', 'dokan' ),
            /* translators: %s permalink settings url */
            'description'       => '',
            'priority'          => 1,
            'show_close_button' => true,
            'ajax_data'         => [
                'action' => 'dismiss_dokan_pro_survey_notice',
                'nonce'  => wp_create_nonce( 'dismiss_dokan_pro_survey_removed_nonce' ),
            ],
            'actions'           => [
                [
                    'type'   => 'primary',
                    'text'   => __( 'Take the Survey', 'dokan' ),
                    'action' => 'https://wedevs.com/dokan/survey',
                    'target' => '_blank',
                ],
                [
                    'type'           => 'secondary',
                    'text'           => __( 'Already Participated', 'dokan' ),
                    'loading_text'   => __( 'Please wait...', 'dokan' ),
                    'completed_text' => __( 'Done', 'dokan' ),
                    'reload'         => true,
                    'ajax_data'      => [
                        'action' => 'dismiss_dokan_pro_survey_notice',
                        'nonce'  => wp_create_nonce( 'dismiss_dokan_pro_survey_removed_nonce' ),
                    ],
                ],
            ],
        ];

        return $notices;
    }

    /**
     * Dismiss Table Rate Shipping module ajax action.
     *
     * @since 3.4.3
     *
     * @return void
     */
    public function ajax_dismiss_dokan_pro_survey_notice() {
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['nonce'] ) ), 'dismiss_dokan_pro_survey_removed_nonce' ) ) {
            wp_send_json_error( __( 'Invalid nonce', 'dokan' ) );
        }

        if ( ! current_user_can( 'activate_plugins' ) ) {
            wp_send_json_error( __( 'You do not have permission to perform this action.', 'dokan' ) );
        }

        update_option( 'dismiss_dokan_pro_survey_notice', 'yes' );

        wp_send_json_success();
    }

    /**
     * Display dismisses PayPal Adaptive module notice
     *
     * @since 3.9.5
     *
     * @param array $notices
     *
     * @return array
     */
    public function paypal_adaptive_module_remove_notice( $notices ) {
        if ( 'yes' === get_option( 'dismiss_dokan_pam_remove_notice', 'no' ) ) {
            return $notices;
        }

        $notices[] = [
            'type'              => 'info',
            'title'             => __( 'Dokan PayPal Marketplace: A Modernized Replacement of PayPal Adaptive Gateway', 'dokan' ),
            /* translators: %s permalink settings url */
            'description'       => 'In line with tech advancements, we’re phasing out the PayPal Adaptive Payment Gateway, as PayPal abandoned its <a href="https://packagist.org/packages/paypal/adaptivepayments-sdk-php">composer package</a>. We strongly recommend transitioning to our advanced <a href="https://dokan.co/wordpress/modules/dokan-paypal-marketplace/">“Dokan PayPal Marketplace”</a> module for a smoother payment experience. Please contact our <a href="https://app.dokan.co/support/create">support</a> team for any assistance. We appreciate your understanding as we strive to enhance our service.',
            'priority'          => 1,
            'show_close_button' => true,
            'ajax_data'         => [
                'action' => 'dismiss_dokan_pam_remove_notice',
                'nonce'  => wp_create_nonce( 'dismiss_dokan_pam_remove_notice' ),
            ],
        ];

        return $notices;
    }

    /**
     * Dismiss PayPal Adaptive module ajax action.
     *
     * @since 3.9.5
     *
     * @return void
     */
    public function ajax_paypal_adaptive_module_remove_notice() {
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['nonce'] ) ), 'dismiss_dokan_pam_remove_notice' ) ) {
            wp_send_json_error( __( 'Invalid nonce', 'dokan' ) );
        }

        if ( ! current_user_can( 'activate_plugins' ) ) {
            wp_send_json_error( __( 'You do not have permission to perform this action.', 'dokan' ) );
        }

        update_option( 'dismiss_dokan_pam_remove_notice', 'yes' );

        wp_send_json_success();
    }
}

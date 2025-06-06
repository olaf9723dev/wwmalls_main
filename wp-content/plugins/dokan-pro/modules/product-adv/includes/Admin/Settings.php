<?php
namespace WeDevs\DokanPro\Modules\ProductAdvertisement\Admin;

use WeDevs\DokanPro\Modules\ProductAdvertisement\Helper;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Class Settings
 *
 * @package WeDevs\DokanPro\Modules\ProductAdvertisement
 *
 * @since 3.5.0
 */
class Settings {

    /**
     * Settings constructor.
     *
     * @since 3.5.0
     */
    public function __construct() {
        // Hooks
        add_filter( 'dokan_settings_sections', [ $this, 'load_settings_section' ], 21 );
        add_filter( 'dokan_settings_fields', [ $this, 'load_settings_fields' ], 21 );
        add_action( 'dokan_before_saving_settings', [ $this, 'validate_admin_settings' ], 20, 2 );
        add_action( 'dokan_after_saving_settings', [ $this, 'create_advertisement_base_product' ], 20, 1 );
    }

    /**
     * Load admin settings section
     *
     * @since 3.5.0
     *
     * @param array $section
     *
     * @return array
     */
    public function load_settings_section( $section ) {
        $section[] = [
            'id'                   => 'dokan_product_advertisement',
            'title'                => __( 'Product Advertising', 'dokan' ),
            'icon_url'             => DOKAN_PRODUCT_ADVERTISEMENT_ASSETS . '/images/advertisment.svg',
            'description'          => __( 'Manage Product Advertising', 'dokan' ),
            'document_link'        => 'https://dokan.co/docs/wordpress/modules/product-advertising/',
            'settings_title'       => __( 'Product Advertisement Settings', 'dokan' ),
            'settings_description' => __( 'Configure settings for your vendor to feature their products on store pages.', 'dokan' ),
        ];

        return $section;
    }

    /**
     * Load all settings fields
     *
     * @since 3.5.0
     *
     * @param array $fields
     *
     * @return array
     */
    public function load_settings_fields( $fields ) {
        $fields['dokan_product_advertisement'] = [
            'total_available_slot' => [
                'name'    => 'total_available_slot',
                'label'   => __( 'No. of Available Slot', 'dokan' ),
                'desc'    => __( 'Enter how many products can be advertised, enter -1 for no limit.', 'dokan' ),
                'type'    => 'number',
                'min'     => '-1',
                'default' => '100',
            ],
            'expire_after_days' => [
                'name'    => 'expire_after_days',
                'label'   => __( 'Expire After Days', 'dokan' ),
                'desc'    => __( 'Enter how many days product will be advertised, enter -1 if you don\'t want to set any expiration period.', 'dokan' ),
                'type'    => 'number',
                'min'     => '-1',
                'default' => '10',
            ],
            'per_product_enabled' => [
                'name'    => 'per_product_enabled',
                'label'   => __( 'Vendor Can Purchase Advertisement', 'dokan' ),
                'desc'    => __( 'If you check this checkbox, vendors will be able to purchase advertisement from product listing and product edit page.', 'dokan' ),
                'type'    => 'switcher',
                'default' => 'on',
            ],
            'cost' => [
                'name'    => 'cost',
                'label'   => sprintf( '%1$s (%2$s)', __( 'Advertisement Cost', 'dokan' ), get_woocommerce_currency() ),
                'desc'    => __( 'Cost of per advertisement. Set 0 (zero) to purchase at no cost.', 'dokan' ),
                'type'    => 'number',
                'min'     => '0',
                'default' => '15',
                'show_if' => [
                    'per_product_enabled' => [
                        'equal' => 'on',
                    ],
                ],
            ],
            'vendor_subscription_enabled' => [
                'name'    => 'vendor_subscription_enabled',
                'label'   => __( 'Enable Advertisement In Subscription', 'dokan' ),
                'desc'    => __( 'If you check this checkbox, vendor will be able to advertise their products without any additional cost based on the plan they are subscribed to.', 'dokan' ),
                'type'    => 'switcher',
                'default' => 'off',
            ],
            'featured' => [
                'name'    => 'featured',
                'label'   => __( 'Mark Advertised Product as Featured?', 'dokan' ),
                'desc'    => __( 'If you check this checkbox, advertised product will be marked as featured. Products will be automatically removed from featured list after advertisement is expired.', 'dokan' ),
                'type'    => 'switcher',
                'default' => 'off',
            ],
            'catalog_priority' => [
                'name'    => 'catalog_priority',
                'label'   => __( 'Display Advertised Product on Top?', 'dokan' ),
                'desc'    => __( 'If you check this checkbox, advertised products will be displayed on top of the catalog listing eg: shop page, single store page etc.', 'dokan' ),
                'type'    => 'switcher',
                'default' => 'on',
            ],
            'hide_out_of_stock_items' => [
                'name'    => 'hide_out_of_stock_items',
                'label'   => __( 'Out of Stock Visibility', 'dokan' ),
                'desc'    => __( 'Hide out of stock items from the advertisement list. Note that, if WooCommerce setting for out of stock visibility is checked, product will be hidden despite this setting.', 'dokan' ),
                'type'    => 'switcher',
                'default' => 'off',
            ],
        ];

        return $fields;
    }


    /**
     * Validates admin delivery settings
     *
     * @since 3.5.0
     *
     * @param string $option_name
     * @param array $option_value
     *
     * @return void
     */
    public function validate_admin_settings( $option_name, $option_value ) {
        if ( 'dokan_product_advertisement' !== $option_name ) {
            return;
        }

        $total_available_slot = intval( $option_value['total_available_slot'] );
        $expire_after_days = intval( $option_value['expire_after_days'] );
        $cost = $option_value['cost'];

        $errors = [];

        if ( $total_available_slot !== -1 && $total_available_slot <= 0 ) {
            $errors[] = [
                'name' => 'total_available_slot',
                'error' => __( 'You need to enter a positive integer for this field. Enter -1 for no limit.', 'dokan' ),
            ];
        }

        if ( $expire_after_days !== -1 && $expire_after_days <= 0 ) {
            $errors[] = [
                'name' => 'expire_after_days',
                'error' => __( 'You need to enter a positive integer for this field. Enter -1 for no limit.', 'dokan' ),
            ];
        }

        if ( ! is_numeric( $cost ) || floatval( $cost ) < 0 ) {
            $errors[] = [
                'name' => 'cost',
                'error' => __( 'Cost can not be empty or less than 0', 'dokan' ),
            ];
        }

        if ( ! empty( $errors ) ) {
            wp_send_json_error(
                [
                    'settings' => [
                        'name'  => $option_name,
                        'value' => $option_value,
                    ],
                    'message'  => __( 'Validation error', 'dokan' ),
                    'errors' => $errors,
                ],
                400
            );
        }
    }

    /**
     * Validates admin delivery settings
     *
     * @since 3.7.0
     *
     * @param string $option_name
     * @param array $option_value
     *
     * @return void
     */
    public function create_advertisement_base_product( $option_name ) {
        if ( 'dokan_product_advertisement' !== $option_name ) {
            return;
        }

        if ( empty( Helper::get_advertisement_base_product() ) ) {
            Helper::create_advertisement_base_product();
        }
    }
}

<?php

namespace LicenseManagerForWooCommerce\Settings;

defined('ABSPATH') || exit;

class Tools
{
    /**
     * @var array
     */
    private $settings;

    /**
     * Tools constructor.
     */
    public function __construct()
    {
        $this->settings = get_option('lmfwc_settings_tools', array());

        /**
         * @see https://developer.wordpress.org/reference/functions/register_setting/#parameters
         */
        $args = array(
            'sanitize_callback' => array($this, 'sanitize')
        );

        // Register the initial settings group.
        register_setting('lmfwc_settings_group_tools', 'lmfwc_settings_tools', $args);

        // Initialize the individual sections
        $this->initSectionExport();
    }

    /**
     * @param array $settings
     *
     * @return array
     */
    public function sanitize($settings)
    {
        if ($settings === null) {
            return array();
        }

        return $settings;
    }

    /**
     * Initializes the "lmfwc_license_keys" section.
     *
     * @return void
     */
    private function initSectionExport()
    {
        // Add the settings sections.
        add_settings_section(
            'export_section',
            __('License key export', 'license-manager-for-woocommerce'),
            null,
            'lmfwc_export'
        );

        // lmfwc_export section fields.
        add_settings_field(
            'lmfwc_csv_export_columns',
            __('CSV Export Columns', 'license-manager-for-woocommerce'),
            array($this, 'fieldCsvExportColumns'),
            'lmfwc_export',
            'export_section'
        );
        // Add the settings sections.
       
          // lmfwc_export section fields.
        // add_settings_field(
        //     'lmfwc_database_migration',
        //     __('Database Migration', 'license-manager-for-woocommerce'),
        //     array($this, 'fieldDatabaseMigration'),
        //     'lmfwc_export',
        //     'lmfwc_data_tools'
        // );
        //    add_settings_field(
        //     'lmfwc_past_order_generator',
        //     __('Past Order Generator', 'license-manager-for-woocommerce'),
        //     array($this, 'fieldPastOrdersLicenseGenerator'),
        //     'lmfwc_export',
        //     'lmfwc_data_tools'
        // );
    }
    

    public function fieldCsvExportColumns()
    {
        $field   = 'lmfwc_csv_export_columns';
        $value   = array();
        $columns = array(
            array(
                'slug' => 'id',
                'name' => __('ID', 'license-manager-for-woocommerce')
            ),
            array(
                'slug' => 'order_id',
                'name' => __('Order ID', 'license-manager-for-woocommerce')
            ),
            array(
                'slug' => 'product_id',
                'name' => __('Product ID', 'license-manager-for-woocommerce')
            ),
            array(
                'slug' => 'user_id',
                'name' => __('User ID', 'license-manager-for-woocommerce')
            ),
            array(
                'slug' => 'license_key',
                'name' => __('License key', 'license-manager-for-woocommerce')
            ),
            array(
                'slug' => 'expires_at',
                'name' => __('Expires at', 'license-manager-for-woocommerce')
            ),
            array(
                'slug' => 'valid_for',
                'name' => __('Valid for', 'license-manager-for-woocommerce')
            ),
            array(
                'slug' => 'status',
                'name' => __('Status', 'license-manager-for-woocommerce')
            ),
            array(
                'slug' => 'times_activated',
                'name' => __('Times activated', 'license-manager-for-woocommerce')
            ),
            array(
                'slug' => 'times_activated_max',
                'name' => __('Times activated (max.)', 'license-manager-for-woocommerce')
            ),
            array(
                'slug' => 'created_at',
                'name' => __('Created at', 'license-manager-for-woocommerce')
            ),
            array(
                'slug' => 'created_by',
                'name' => __('Created by', 'license-manager-for-woocommerce')
            ),
            array(
                'slug' => 'updated_at',
                'name' => __('Updated at', 'license-manager-for-woocommerce')
            ),
            array(
                'slug' => 'updated_by',
                'name' => __('Updated by', 'license-manager-for-woocommerce')
            )
        );

        if (array_key_exists($field, $this->settings)) {
            $value = $this->settings[$field];
        }

        $html = '<fieldset>';

        foreach ($columns as $column) {
            $checked = false;

            if (array_key_exists($column['slug'], $value) && $value[$column['slug']] === '1') {
                $checked = true;
            }

            $html .= sprintf('<label for="%s-%s">', $field, $column['slug']);
            $html .= sprintf(
                '<input id="%s-%s" type="checkbox" name="lmfwc_settings_tools[%s][%s]" value="1" %s>',
                $field,
                $column['slug'],
                $field,
                $column['slug'],
                checked(true, $checked, false)
            );
            $html .= sprintf('<span>%s</span>', $column['name']);

            $html .= '</label>';
            $html .= '<br>';
        }

        $html .= sprintf(
            '<p class="description" style="margin-top: 1em;">%s</p>',
            __('The selected columns will appear on the CSV export for license keys.', 'license-manager-for-woocommerce')
        );
        $html .= '</fieldset>';

        echo $html;
    }

       public function fieldDatabaseMigration() {
        ?>
        <h3><?php _e( 'Past Orders License Generator', 'license-manager-for-woocommerce' ); ?></h3>
        <p><?php _e( 'This tool generates licenses for all past orders that doesn\'t have license assigned. Useful if you already have established shop and want to assign licenses to your existing orders.', 'license-manager-for-woocommerce' ); ?></p>
        <form class="lmfwc-tool-form" id="lmfwc-migration-tool" method="POST" action="">
            <div class="lmfwc-tool-form-row">
                <label for="generator"><?php _e( 'Generator', 'license-manager-for-woocommerce' ); ?> <span class="required">*</span></label>
                <select id="generator" name="generator" required>
                </select>
            </div>
            <div class="lmfwc-tool-form-row">
                <label>
                    <input type="checkbox" name="use_product_licensing_configuration" value="1">
                    <small><?php _e( 'Use product settings where possible, e.g some products have their own licensing configuration settings.', 'license-manager-for-woocommerce' ); ?></small>
                </label>
            </div>
            <div class="lmfwc-tool-form-row lmfwc-tool-form-row-progress" style="display: none;">
                <div class="lmfwc-tool-progress-bar">
                    <p class="lmfwc-tool-progress-bar-inner">&nbsp;</p>
                </div>
                <div class="lmfwc-tool-progress-info"><?php _e( 'Initializing...', 'license-manager-for-woocommerce' ); ?></div>
            </div>
            <div class="lmfwc-tool-form-row">
                <input type="hidden" name="id" value=""/>
                <input type="hidden" name="identifier" value=""/>
                <input type="hidden" name="tool" value="">
                <button type="submit" class="button button-small button-primary"><?php _e( 'Process', 'license-manager-for-woocommerce' ); ?></button>
            </div>
        </form>
        <?php
    }

    public function fieldPastOrdersLicenseGenerator() {
        ?>
        <h3><?php _e( 'Past Orders License Generator', 'license-manager-for-woocommerce' ); ?></h3>
        <p><?php _e( 'This tool generates licenses for all past orders that doesn\'t have license assigned. Useful if you already have established shop and want to assign licenses to your existing orders.', 'license-manager-for-woocommerce' ); ?></p>
        <form class="lmfwc-tool-form" id="lmfwc-generate-tool" method="POST" action="">
            <div class="lmfwc-tool-form-row">
                <label for="generator"><?php _e( 'Generator', 'license-manager-for-woocommerce' ); ?> <span class="required">*</span></label>
                <select id="generator" name="generator" required>
                </select>
            </div>
            <div class="lmfwc-tool-form-row">
                <label>
                    <input type="checkbox" name="use_product_licensing_configuration" value="1">
                    <small><?php _e( 'Use product settings where possible, e.g some products have their own licensing configuration settings.', 'license-manager-for-woocommerce' ); ?></small>
                </label>
            </div>
            <div class="lmfwc-tool-form-row lmfwc-tool-form-row-progress" style="display: none;">
                <div class="lmfwc-tool-progress-bar">
                    <p class="lmfwc-tool-progress-bar-inner">&nbsp;</p>
                </div>
                <div class="lmfwc-tool-progress-info"><?php _e( 'Initializing...', 'license-manager-for-woocommerce' ); ?></div>
            </div>
            <div class="lmfwc-tool-form-row">
                <input type="hidden" name="id" value=""/>
                <input type="hidden" name="identifier" value=""/>
                <input type="hidden" name="tool" value="">
                <button type="submit" class="button button-small button-primary"><?php _e( 'Process', 'license-manager-for-woocommerce' ); ?></button>
            </div>
        </form>

        <?php
    }

}

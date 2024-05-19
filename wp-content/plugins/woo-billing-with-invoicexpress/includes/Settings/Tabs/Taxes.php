<?php

namespace Webdados\InvoiceXpressWooCommerce\Settings\Tabs;
use Webdados\InvoiceXpressWooCommerce\Modules\Vat\VatController as VatController;

/* WooCommerce HPOS ready 2023-07-13 */

/**
 * Register taxes settings.
 *
 * @package InvoiceXpressWooCommerce
 * @since   2.0.0
 */
class Taxes extends \Webdados\InvoiceXpressWooCommerce\Settings\Tabs {

	/**
	 * Retrieve the array of plugin settings.
	 *
	 * @since  2.0.0
	 * @return array
	 */
	public function get_registered_settings() {

		if ( ! $this->settings->check_requirements() ) {
			return;
		}

		$exemption_reasons = VatController::get_exemption_reasons();

		$settings = array(
			'title'    => __( 'Taxes', 'woo-billing-with-invoicexpress' ),
			'sections' => array(
				'ix_taxes_misc'      => array(
					'title'       => __( 'General taxes settings', 'woo-billing-with-invoicexpress' ),
					'description' => sprintf(
						/* translators: %1$s: link tag opening, %2$s: link tag closing, %3$s: line break, %4$s: link for documentation */
						__( 'Before using the plugin, you have to make sure your %1$sWooCommerce taxes%2$s are properly configured.%3$s%4$s', 'woo-billing-with-invoicexpress' ),
						'<a href="admin.php?page=wc-settings&tab=tax">',
						'</a>',
						'<br/>',
						sprintf(
							'<a href="%s" target="_blank">%s</a>.',
							esc_html_x( 'https://invoicewoo.com/documentation/installation-guide/setting-up-woocommerce-taxes/', 'Documentation URL (Installation guide, Setting up WooCommerce taxes)', 'woo-billing-with-invoicexpress' ),
							esc_html__( 'Check the documentation', 'woo-billing-with-invoicexpress' )
						)
					),
					'fields'      => array(
						'hd_wc_ie_plus_tax_country' => array(
							'title'       => __( 'Portuguese company', 'woo-billing-with-invoicexpress' ),
							'suffix'      => __( 'This is a store of a Portuguese company', 'woo-billing-with-invoicexpress' ),
							'description' => __( 'Check only if you have a Portuguese VAT number and your business is a company (or similar VAT passive subject)', 'woo-billing-with-invoicexpress' ),
							'type'        => 'checkbox',
						),
						'hd_wc_ie_plus_default_tax' => array(
							'title'       => __( 'Default tax', 'woo-billing-with-invoicexpress' ),
							'description' => __( 'Tax to use, by default, when generating documents (this will also change your default tax on InvoiceXpress)', 'woo-billing-with-invoicexpress' ),
							'type'        => 'select_ix_tax',
						),
					),
				),
				'ix_taxes_vat_field' => array(
					'title'       => __( 'VAT field', 'woo-billing-with-invoicexpress' ),
					'fields'      => array(
						'hd_wc_ie_plus_vat_field' => array(
							'title'       => __( 'VAT field', 'woo-billing-with-invoicexpress' ),
							'suffix'      => __( 'VAT field on the checkout', 'woo-billing-with-invoicexpress' ),
							'description' => __( 'Include our own VAT field on the checkout', 'woo-billing-with-invoicexpress' ),
							'type'        => 'checkbox',
						),
						'hd_wc_ie_plus_vat_field_mandatory' => array(
							'title'        => __( 'Mandatory VAT field', 'woo-billing-with-invoicexpress' ),
							'suffix'       => __( 'Make the VAT field mandatory', 'woo-billing-with-invoicexpress' ),
							'description'  => __( 'Only recommended if you sell exclusively to B2B, as you should give consumers the choice not to fill in the VAT number.', 'woo-billing-with-invoicexpress' ),
							'type'         => 'checkbox',
							'parent_field' => 'hd_wc_ie_plus_vat_field',
							'parent_value' => '1',
						),
					),
				),
				'ix_taxes_exemption' => array(
					'title'       => __( 'Tax exemption', 'woo-billing-with-invoicexpress' ),
					'fields'      => array(
						'hd_wc_ie_plus_exemption_reason' => array(
							'title'       => __( 'Tax exemption motive', 'woo-billing-with-invoicexpress' ),
							'description' => __( 'You should set a Tax exemption motive if your business is exempt from taxes', 'woo-billing-with-invoicexpress' ),
							'type'        => 'select',
							'options'     => array_merge(
								array(
									'' => __( 'No exemption applicable', 'woo-billing-with-invoicexpress' )
								),
								$exemption_reasons
							),
							'parent_field' => 'hd_wc_ie_plus_tax_country',
							'parent_value' => '1',
						),
						'hd_wc_ie_plus_exemption_name'   => array(
							'title'       => __( 'Tax exemption name', 'woo-billing-with-invoicexpress' ),
							'description' => __( 'This should be the 0% tax name defined on your InvoiceXpress account', 'woo-billing-with-invoicexpress' ),
							'type'        => 'text',
						),
					),
				),
			),
		);

		return apply_filters( 'invoicexpress_woocommerce_registered_taxes_settings', $settings );
	}
}
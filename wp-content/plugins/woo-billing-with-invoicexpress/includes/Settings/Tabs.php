<?php

namespace Webdados\InvoiceXpressWooCommerce\Settings;

/* WooCommerce HPOS ready 2023-07-13 */

class Tabs {

	/**
	 * The settings's instance.
	 *
	 * @since  2.0.0
	 * @access protected
	 * @var    Settings
	 */
	protected $settings;

	/**
	 * The plugin's instance.
	 *
	 * @access protected
	 * @var    Plugin
	 */
	protected $plugin;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 2.0.0
	 * @param Settings $settings This settings's instance.
	 */
	public function __construct( Settings $settings, \Webdados\InvoiceXpressWooCommerce\Plugin $plugin ) {
		$this->settings = $settings;
		$this->plugin   = $plugin;
	}

	/**
	 * Get setting's instance.
	 *
	 * @return Settings
	 */
	public function get_settings() {
		return $this->settings;
	}
}

<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1 for parent theme EmallShop for publication on ThemeForest
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once EMALLSHOP_FRAMEWORK . '/thirdparty/tgm-plugin-activation/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'emallshop_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function emallshop_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
		
		// This is an example of how to include a plugin from the WordPress Plugin Repository.
		
		array(
			'name' 					=> esc_html__('PL EmallShop Extensions','emallshop'),
			'slug' 					=> 'pl-emallshop-extensions',
			'source'             	=> esc_url('https://presslayouts.com/plugins/pl-emallshop-extensions.zip'),
			'version'  				=> '1.3.2',
			'required' 				=> true,
		),
		array(
			'name' 					=> esc_html__('WPBakery Visual Composer','emallshop'),
			'slug' 					=> 'js_composer',
			'source'             	=> esc_url('https://presslayouts.com/plugins/js_composer.zip'),
			'version'  				=> '7.2',
			'required' 				=> true,
		),
		array(
			'name' 					=> esc_html__('Revolution Slider','emallshop'),
			'slug' 					=> 'revslider',
			'source'             	=> esc_url('https://presslayouts.com/plugins/revslider.zip'),
			'version'  				=> '6.6.18',
			'required' 				=> true,
		),
		array(
			'name' 					=> esc_html__('Ultimate Addons for Visual Composer','emallshop'),
			'slug' 					=> 'Ultimate_VC_Addons',
			'source'             	=> esc_url('https://presslayouts.com/plugins/Ultimate_VC_Addons.zip'),
			'version'  				=> '3.19.19',
			'required' 				=> true,
		),
		array(
			'name' 					=> esc_html__('CleverSwatches','emallshop'),
			'slug' 					=> 'clever-swatches',
			'source'             	=> esc_url('https://presslayouts.com/plugins/clever-swatches.zip'),
			'version'  				=> '2.2.3',
			'required' 				=> false,
		),
		array(
			'name' 					=> esc_html__('Woocommerce','emallshop'),
			'slug' 					=> 'woocommerce',
			'required' 				=> true,
		),
		array(
			'name' 					=> esc_html__('YITH WooCommerce Compare','emallshop'),
			'slug' 					=> 'yith-woocommerce-compare',
			'required' 				=> false,
		),
		array(
			'name' 					=> esc_html__('YITH WooCommerce Wishlist','emallshop'),
			'slug' 					=> 'yith-woocommerce-wishlist',
			'required' 				=> false,
		),
		array(
			'name' 					=> esc_html__('YITH WooCommerce Ajax Product Filter','emallshop'),
			'slug' 					=> 'yith-woocommerce-ajax-navigation',
			'required' 				=> false,
		),		
		array(
			'name' 					=> esc_html__('MailChimp for WordPress','emallshop'),
			'slug' 					=> 'mailchimp-for-wp',
			'required' 				=> false,
		),
		array(
            'name'      			=> esc_html__('Contact Form 7','emallshop'),
            'slug'     			 	=> 'contact-form-7',
            'required' 			 	=> false,
        ),

	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'es-install-plugins',    // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
		
	);

	tgmpa( $plugins, $config );
}

<?php
    /**
     * ReduxFramework Sample Config File
     */

    if ( ! class_exists( 'Redux' ) || ! emallshop_is_activated()) {
        return;
    }


    // This is your option name where all the Redux data is stored.
    $opt_name = 'emallshop_options';

    // This line is only for altering the demo. Can be easily removed.
    $opt_name = apply_filters( 'emallshop_options/opt_name', $opt_name );

   
    $admin_img_url = ReduxFramework::$_dir . '../images/';
	$admin_css_url = ReduxFramework::$_dir . '../css/';
	
	$emallshop_header_style = emallshop_options_header_style();
	$emallshop_footer_style = emallshop_options_footer_style();
	$emallshop_page_heading_style = emallshop_options_page_heading_style();

    /*SET ARGUMENTS */

    $theme = wp_get_theme( 'emallshop' ); // For use with some settings. Not necessary.
	$theme_slug = '';
	if( is_customize_preview() ){
		$theme_slug = ' ('.$theme.')';
	}
    $args = array(
        'opt_name'             => $opt_name,
        'display_name'         => $theme->get( 'Name' ),
        'display_version'      => $theme->get( 'Version' ),
        'menu_type'            => 'submenu',
        'allow_sub_menu'       => true,
        'menu_title'           => esc_html__( 'Theme Options', 'emallshop' ),
        'page_title'           => esc_html__( 'Theme Options', 'emallshop' ),
        'admin_bar_icon'       => 'dashicons-portfolio',
        'admin_bar_priority'   => 50,
        'global_variable'      => '',
        'dev_mode'             => false,
        'update_notice'        => false,
        'customizer'           => true,
        'page_priority'        => null, 
        'page_parent'          	=> 'emallshop-theme',
        'page_permissions'     	=> 'manage_options',
		'page_slug'            	=> 'emallshop-theme-option',    
        'show_import_export'   => true,
		'output'               => true,
		'admin_theme'          => 'classic',
        'output'               => true,
        'output_tag'           => true,
		'footer_credit'        => ' ',
    );

    Redux::setArgs( $opt_name, $args );

    /* END ARGUMENTS */

	 
    /* START SECTIONS */
 
	// General Setting Options
    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'General Settings', 'emallshop' ),
        'id'               => 'general-settings',
        'desc'             => '',
		'fields'           => array(
			array(
                'id'       => 'theme-layout',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Theme Layout', 'emallshop' ),
                'subtitle' => esc_html__( 'Choose theme wide or boxed layout.', 'emallshop' ),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    'full-layout' => array(
                        'alt' => '1 Column',
                        'img' => ReduxFramework::$_url . 'assets/img/1col.png'
                    ),                   
                    'boxed-layout' => array(
                        'alt' => '3 Column Middle',
                        'img' => ReduxFramework::$_url . 'assets/img/3cm.png'
                    ),                   
                ),
                'default'  => 'full-layout'
            ),
			array(
                'id'       => 'use-predefined-page-width',
                'type'     => 'button_set',
                'title'    => esc_html__('Page Width', 'emallshop'),
                'subtitle' => esc_html__('Choose one of the pre-defined widths or enter custom one.','emallshop'),
                'options'  => array(
                    'pre-defined'     => 'Pre-defined',
                    'custom' => 'Custom',
                ),
                'default'  => 'pre-defined'
            ),
			array(
                'id'       => 'predefined-page-width',
                'type'     => 'radio',
                'title'    => esc_html__( 'Pre-defined Page Width', 'emallshop' ),
                'options'  => array(
                   '1170'  => esc_html__( 'Wide Screen', 'emallshop' ) . ' (1170px)',
                    '980'   => esc_html__( 'NoteBook', 'emallshop' ) . ' (980px)',
                ),
                'default'  => '1170',
				'required' => array( 'use-predefined-page-width', '=', 'pre-defined' )
            ),
            array(
                'id'       => 'custom-page-width',
                'type'     => 'text',
                'title'    => esc_html__('Custom Page Width','emallshop'),
				'default'  => '1170',
                'required' => array( 'use-predefined-page-width', '=', 'custom' )
            ),
			array(
                'id'       => 'header-logo',
                'type'     => 'media',
                'url'      => true,
				//'readonly' => false,
                'title'    => esc_html__('Header Logo','emallshop'),
                'compiler' => 'true',
                'subtitle' =>  esc_html__('Upload header logo.','emallshop'),
                'default'  => array(),
            ),
			array(
                'id'       => 'sticky-header-logo',
                'type'     => 'media',
                'url'      => true,
                'title'    => esc_html__('Sticky Header Logo','emallshop'),
                'compiler' => 'true',
                'subtitle' =>  esc_html__('Upload sticky header logo.','emallshop'),
                'default'  => array(),
            ),
			array(
                'id'       => 'favicon-icon',
                'type'     => 'media',
                'url'      => true,
                'title'    => esc_html__('Favicon Icon','emallshop'),
                'compiler' => 'true',
                'subtitle' =>  esc_html__('Upload site favicon icon.','emallshop'),
                'default'  => array(),
            ),
			array(
                'id'       => 'back-to-top',
                'type'     => 'switch',
                'title'    => esc_html__('Back To Top Button','emallshop'),
                'subtitle' =>  esc_html__('Show back to top button.','emallshop'),
                'default'  => 1,
                'on'       => esc_html__('On','emallshop'),
                'off'      => esc_html__('Off','emallshop'),
            ),
			array(
                'id'       => 'show-login-register-popup',
                'type'     => 'switch',
                'title'    => esc_html__('Login Register Popup','emallshop'),
                'subtitle' =>  esc_html__('Show login register in popen or redirect on My Account page.','emallshop'),
                'default'  => 1,
                'on'       => esc_html__('On','emallshop'),
                'off'      => esc_html__('Off','emallshop'),
            ),
			array(
                'id'       => 'google-analytics',
                'type'     => 'textarea',
                'title'    => esc_html__('Tracking Code','emallshop'),
                'subtitle' => esc_html__('Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.','emallshop'),
                'default'  => '',
            )
		)
    ) );
	
	/*
	* Mobile
	*/
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Mobile', 'emallshop' ),
        'id'         => 'section-mobile',
		'icon'		 => 'el el-iphone-home',
        'fields'     => array(	
			array(
                'id'       		=> 'mobile-products-per-row',
                'type'     		=> 'button_set',
                'title'    		=> esc_html__( 'Product Columns On Mobile', 'emallshop' ),
				'subtitle'      => esc_html__( 'How many product you want to show per row on mobile?', 'emallshop' ),
                'options'  		=> array(
                    1		=> esc_html__('1', 'emallshop' ),
                    2	 	=> esc_html__('2', 'emallshop' ),
                ),
                'default'  		=> 2,
            ),
			
		),
	) );
	
	/*
	* Mobile Navbar
	*/
	Redux::setSection( $opt_name, array(
        'title'      		=> esc_html__( 'Footer Navbar', 'emallshop' ),
        'id'         		=> 'section-mobile-navbar',
		'subsection'		=> true,
        'fields'     		=> array(
			array(
                'id'       		=> 'mobile-bottom-navbar',
                'type'     		=> 'switch',
                'title'    		=> esc_html__('Mobile Bottom Navbar','emallshop'),
				'subtitle'    	=> esc_html__( 'Show mobile bottom navbar in mobile device.', 'emallshop' ),
                'on'       		=> esc_html__('Yes','emallshop'),
				'off'      		=> esc_html__('No','emallshop'),
				'default'  		=> 1
            ),
			array(
                'id'       		=> 'mobile-navbar-label',
                'type'     		=> 'switch',
                'title'    		=> esc_html__('Navbar Label','emallshop'),
				'subtitle'    	=> esc_html__( 'Show navbar label.', 'emallshop' ),
                'on'       		=> esc_html__('Yes','emallshop'),
				'off'      		=> esc_html__('No','emallshop'),
				'default'  		=> 1,
            ),
			array(
                'id'       		=> 'mobile-product-page-button',
                'type'     		=> 'switch',
                'title'    		=> esc_html__('Product Page Button','emallshop'),
				'subtitle'    	=> esc_html__( 'Enable/Disable Sticky Add to Cart/Buy Now button on single product page.', 'emallshop' ),
                'on'       		=> esc_html__('Enable','emallshop'),
				'off'      		=> esc_html__('Disable','emallshop'),
				'default'  		=> 1,
            ),
			array(
                'id'       		=> 'mobile-cart-page-button',
                'type'     		=> 'switch',
                'title'    		=> esc_html__('Cart Page Button','emallshop'),
				'subtitle'    	=> esc_html__( 'Enable/Disable Sticky Proceed To Checkout button on cart page.', 'emallshop' ),
                'on'       		=> esc_html__('Enable','emallshop'),
				'off'      		=> esc_html__('Disable','emallshop'),
				'default'  		=> 1,
            ),
			array(
                'id'       		=> 'mobile-checkout-page-button',
                'type'     		=> 'switch',
                'title'    		=> esc_html__('Checkout Page Button','emallshop'),
				'subtitle'    	=> esc_html__( 'Enable/Disable Sticky Place Order button on checkout page.', 'emallshop' ),
                'on'       		=> esc_html__('Enable','emallshop'),
				'off'      		=> esc_html__('Disable','emallshop'),
				'default'  		=> 1,
            ),
			array(
                'id'       	=> 'mobile-navbar-elements',
                'type'     	=> 'sorter',
                'title'    	=> esc_html__( 'Navbar Elements', 'emallshop' ),
                'compiler' 	=> 'true',
                'options'  	=> array(
                    'enabled'  => array(
						'shop'  		=> esc_html__( 'Shop', 'emallshop' ),
						'sidebar'  		=> esc_html__( 'Sidebar/Filters', 'emallshop' ),
						'wishlist' 		=> esc_html__( 'Wishlist', 'emallshop' ),
						'cart'     		=> esc_html__( 'Cart', 'emallshop' ),
						'account'  		=> esc_html__( 'Account', 'emallshop' ),
                    ),
                    'disabled' => array(						
                        'home'     		=> esc_html__( 'Home', 'emallshop' ),
						'menu'  		=> esc_html__( 'Menu', 'emallshop' ),
						'compare'  		=> esc_html__( 'Compare', 'emallshop' ),
						'order'			=> esc_html__( 'Order', 'emallshop' ),
						'order-tracking'=> esc_html__( 'Order Tracking', 'emallshop' ),
						'blog'  		=> esc_html__( 'Blog', 'emallshop' ),
						'custom_link1'  => esc_html__( 'Custom Link 1', 'emallshop' ),
						'custom_link2'  => esc_html__( 'Custom Link 2', 'emallshop' ),
						'custom_link3'  => esc_html__( 'Custom Link 3', 'emallshop' ),
					),
                ),
            ),
			array(
                'id'       			=> 'mobile-navbar-label-shop',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Shop Label','emallshop'),
                'subtitle'     		=> esc_html__('Enter shop navbar label','emallshop'),
				'default'  			=> esc_html__( 'Shop', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-label-icon-shop',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Shop Label Icon','emallshop'),
                'subtitle'     		=> esc_html__('Enter font awesome icon class. Ex. fa fa-home','emallshop'),
				'default'  			=> esc_html__( 'fa fa-home', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-label-wishlist',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Wishlist Label','emallshop'),
                'subtitle'     		=> esc_html__('Enter wishlist navbar label','emallshop'),
				'default'  			=> esc_html__( 'Wishlist', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-label-icon-wishlist',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Wishlist Label Icon','emallshop'),
                'subtitle'     		=> esc_html__('Enter font awesome icon class. Ex. fa fa-heart','emallshop'),
				'default'  			=> esc_html__( 'fa fa-heart', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-label-cart',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Cart Label','emallshop'),
                'subtitle'     		=> esc_html__('Enter cart navbar label','emallshop'),
				'default'  			=> esc_html__( 'Cart', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-label-icon-cart',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Cart Label Icon','emallshop'),
                'subtitle'     		=> esc_html__('Enter font awesome icon class. Ex. fa fa-shopping-cart','emallshop'),
				'default'  			=> esc_html__( 'fa fa-shopping-cart', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-label-account',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Account Label','emallshop'),
                'subtitle'     		=> esc_html__('Enter account navbar label','emallshop'),
				'default'  			=> esc_html__( 'Account', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-label-icon-account',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Account Label Icon','emallshop'),
                'subtitle'     		=> esc_html__('Enter font awesome icon class. Ex. fa fa-user','emallshop'),
				'default'  			=> esc_html__( 'fa fa-user', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-label-home',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Home Label','emallshop'),
                'subtitle'     		=> esc_html__('Enter home navbar label','emallshop'),
				'default'  			=> esc_html__( 'Home', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-label-icon-home',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Home Label Icon','emallshop'),
                'subtitle'     		=> esc_html__('Enter font awesome icon class. Ex. fa fa-home','emallshop'),
				'default'  			=> esc_html__( 'fa fa-home', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-label-menu',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Menu Label','emallshop'),
                'subtitle'     		=> esc_html__('Enter menu navbar label','emallshop'),
				'default'  			=> esc_html__( 'Menu', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-label-icon-menu',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Menu Label Icon','emallshop'),
                'subtitle'     		=> esc_html__('Enter font awesome icon class. Ex. fa fa-bars','emallshop'),
				'default'  			=> esc_html__( 'fa fa-bars', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-label-compare',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Compare Label','emallshop'),
                'subtitle'     		=> esc_html__('Enter compare navbar label','emallshop'),
				'default'  			=> esc_html__( 'Compare', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-label-icon-compare',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Compare Label Icon','emallshop'),
                'subtitle'     		=> esc_html__('Enter font awesome icon class. Ex. fa fa-refresh','emallshop'),
				'default'  			=> esc_html__( 'fa fa-refresh', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-label-filter',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Filter Label','emallshop'),
                'subtitle'     		=> esc_html__('Enter filter navbar label','emallshop'),
				'default'  			=> esc_html__( 'Filters', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-label-icon-filter',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Filter Label Icon','emallshop'),
                'subtitle'     		=> esc_html__('Enter font awesome icon class. Ex. fa fa-sliders','emallshop'),
				'default'  			=> esc_html__( 'fa fa-sliders', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-label-order',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Order Label','emallshop'),
                'subtitle'     		=> esc_html__('Enter order navbar label','emallshop'),
				'default'  			=> esc_html__( 'Order', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-label-icon-order',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Order Label Icon','emallshop'),
                'subtitle'     		=> esc_html__('Enter font awesome icon class. Ex. fa fa-file-text-o','emallshop'),
				'default'  			=> esc_html__( 'fa fa-file-text-o', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-label-order-tracking',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Order Tracking Label','emallshop'),
                'subtitle'     		=> esc_html__('Enter order tracking navbar label','emallshop'),
				'default'  			=> esc_html__( 'Order Tracking', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-label-icon-order-tracking',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Order Tracking Label Icon','emallshop'),
                'subtitle'     		=> esc_html__('Enter font awesome icon class. Ex. fa fa-plane','emallshop'),
				'default'  			=> esc_html__( 'fa fa-plane', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-label-sidebar',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Sidebar Label','emallshop'),
                'subtitle'     		=> esc_html__('Enter sidebar navbar label','emallshop'),
				'default'  			=> esc_html__( 'Sidebar', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-label-icon-sidebar',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Sidebar Label Icon','emallshop'),
                'subtitle'     		=> esc_html__('Enter font awesome icon class. Ex. fa fa-columns','emallshop'),
				'default'  			=> esc_html__( 'fa fa-columns', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-label-blog',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Blog Label','emallshop'),
                'subtitle'     		=> esc_html__('Enter blog navbar label','emallshop'),
				'default'  			=> esc_html__( 'Blog', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-label-icon-blog',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Blog Label Icon','emallshop'),
                'subtitle'     		=> esc_html__('Enter font awesome icon class. Ex. fa fa-pencil-square','emallshop'),
				'default'  			=> esc_html__( 'fa fa-pencil-square', 'emallshop' ),
            ),
			array(
                'id'    => 'custom-link-options',
                'type'   => 'info',
                'notice' => false,
                'title' => esc_html__( 'Custom Links', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-custom-link1-label',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Custom Link 1 Label','emallshop'),
                'subtitle'     		=> esc_html__('Enter custom link 1 label.','emallshop'),
				'default'  			=> esc_html__( 'Custom 1', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-custom-link1-icon',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Custom Link 1 Icon','emallshop'),
                'subtitle'     		=> esc_html__('Enter font awesome icon class.','emallshop'),
				'default'  			=> 'fa fa-home',
            ),
			array(
                'id'       			=> 'mobile-navbar-custom-link1-url',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Custom Link 1 URL','emallshop'),
                'subtitle'     		=> esc_html__('Enter custom link 1 url.','emallshop'),
				'default'  			=> '#',
            ),
			array(
                'id'       			=> 'mobile-navbar-custom-link2-label',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Custom Link 2 Label','emallshop'),
                'subtitle'     		=> esc_html__('Enter custom link 2 label.','emallshop'),
				'default'  			=> esc_html__( 'Custom 2', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-custom-link2-icon',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Custom Link 2 Icon','emallshop'),
                'subtitle'     		=> esc_html__('Enter font awesome icon class.','emallshop'),
				'default'  			=> 'fa fa-home',
            ),
			array(
                'id'       			=> 'mobile-navbar-custom-link2-url',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Custom Link 2 URL','emallshop'),
                'subtitle'     		=> esc_html__('Enter custom link 2 url.','emallshop'),
				'default'  			=> '#',
            ),
			array(
                'id'       			=> 'mobile-navbar-custom-link3-label',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Custom Link 3 Label','emallshop'),
                'subtitle'     		=> esc_html__('Enter custom link 3 label.','emallshop'),
				'default'  			=> esc_html__( 'Custom 3', 'emallshop' ),
            ),
			array(
                'id'       			=> 'mobile-navbar-custom-link3-icon',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Custom Link 3 Icon','emallshop'),
                'subtitle'     		=> esc_html__('Enter font awesome icon class.','emallshop'),
				'default'  			=> 'fa fa-home',
            ),
			array(
                'id'       			=> 'mobile-navbar-custom-link3-url',
                'type'     			=> 'text',
                'title'    			=> esc_html__('Custom Link 3 URL','emallshop'),
                'subtitle'     		=> esc_html__('Enter custom link 3 url.','emallshop'),
				'default'  			=> '#',
            ),
		)
	) );
	
	/*
	* Mobile colors
	*/
	Redux::setSection( $opt_name, array(
        'title'      	=> esc_html__( 'Colors', 'emallshop' ),
        'id'         	=> 'section-mobile-colors',
		'subsection'   	=> true,
        'fields'     	=> array(
			array(
				'id'       		=> 'google-theme-color',
				'type'     		=> 'color',
				'title'    		=> esc_html__( 'Google Theme Color', 'emallshop' ), 				
				'subtitle'   		=> wp_kses( sprintf( __( 'Applied only on mobile devices Android on chrome browser toolbar, <a href="%s" target="_blank">click here</a> plugin.', 'emallshop' ), esc_url( 'http://updates.html5rocks.com/2014/11/Support-for-theme-color-in-Chrome-39-for-Android/' ) ),
				array(
						'a' => array(
							'href'   => array(),
							'target' => array(),
						),
					) 
				),
				'validate' 		=> 'color',
				'default'  		=> '#0ba2e8'
			),			
		),
	) );
	
	// Theme Styling Options
	Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Theme Styling', 'emallshop' ),
        'id'               => 'theme-styling',
        'desc'             => '',
        'icon'		 	   => 'el el-brush',
		'fields'           => array(
		)
	) );
	 Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Body', 'emallshop' ),
        'id'         => 'body-styling',
        'subsection' => true,		
        'fields'     => array(
            array(
                'id'       => 'theme-color',
                'type'     => 'color',
                'title'    => esc_html__( 'Primary Color', 'emallshop' ),
                'default'  => '#0ba2e8',
            ),
			array(
                'id'       => 'theme-secondary-color',
                'type'     => 'color',
                'title'    => esc_html__( 'Secondary Color', 'emallshop' ),
                'default'  => '#ff8400',
            ),
			array(
                'id'       	=> 'body-background',
                'type'     	=> 'background',
                'title'    	=> esc_html__('Body Background','emallshop'),
                'subtitle' 	=>  esc_html__( 'Body background with image, color, etc.', 'emallshop' ),
				'output'   	=> array('body, .category-menu .categories-list, .emallshop-mobile-navbar'),
                'default' 	=> array(
								'background-color' 		=> '#ffffff',
								'background-image' 		=> '',
								'background-repeat' 	=> '',
								'background-size' 		=> '',
								'background-attachment' => '',
								'background-position' 	=> '',
							)
            ),
			array(
                'id'       => 'body-content-background',
                'type'     => 'background',
                'title'    => esc_html__( 'Wrapper Background', 'emallshop' ),
                'subtitle' => esc_html__( 'Body wrapper/content background with image, color, etc.', 'emallshop' ),
				'output'   => array( 'body .wrapper, body .wrapper.boxed-layout' ),
                'default' => array(
								'background-color' 		=> '#ffffff',
								'background-image' 		=> '',
								'background-repeat' 	=> '',
								'background-size' 		=> '',
								'background-attachment' => '',
								'background-position' 	=> '',
							)
            ),
			array(
                'id'       => 'body-text-color',
                'type'     => 'color',
                'title'    => esc_html__('Text Color','emallshop'),
                'default'  => '#656565',
            ),
			array(
                'id'       => 'body-heading-color',
                'type'     => 'color',
                'title'    => esc_html__('Heading/Title Color','emallshop'),
				'subtitle' =>  esc_html__( 'Body heading color h1, h2, h3, h4, h5, and h6.', 'emallshop' ),
                'default'  => '#212121',
            ),			
			array(
                'id'       => 'body-input-background',
                'type'     => 'color',
                'title'    => esc_html__( 'Input Field Background( TextBox, SelectBox, etc..)', 'emallshop' ),
                'default'  => '#ffffff',
            ),
			array(
                'id'       => 'body-input-color',
                'type'     => 'color',
                'title'    => esc_html__( 'Input Field Color( TextBox, SelectBox, etc..)', 'emallshop' ),
                'default'  => '#656565',
            ),
			array(
                'id'       => 'body-link-color',
                'type'     => 'link_color',
                'title'    => esc_html__( 'Link Color', 'emallshop' ),
                'default'  => array(
                    'regular' => '#212121',
                    'hover'   => '#ff8400',
                    'active'  => '#ff8400',
                )
            ),
			array(
                'id'       => 'theme-border',
                'type'     => 'border',
                'title'    => esc_html__( 'Border', 'emallshop' ),
                'subtitle' => esc_html__('General border of theme.','emallshop'),
                'default'  => array(
                    'border-color'  => '#e9e9e9',
                    'border-style'  => 'solid',
                    'border-top'    => '1px',
                    'border-right'  => '1px',
                    'border-bottom' => '1px',
                    'border-left'   => '1px'
                )
            ),
			array(
                'id'            => 'theme-border-radius',
                'type'          => 'slider',
                'title'         => esc_html__( 'Border Radius', 'emallshop' ),
				'subtitle' 		=> esc_html__('General border radius of theme.','emallshop'),
                'default'       => 0,
                'min'           => 0,
                'step'          => 1,
                'max'           => 10,
                'display_value' => 'label'
            ),			
			array(
                'id'             => 'body-wrapper-padding',
                'type'           => 'spacing',
                'mode'           => 'padding',
				'units'          => 'px',
                'right'         => false,     // Disable the right
                'left'          => false,     // Disable the left               
                'title'          => esc_html__( 'Body Wrapper Padding', 'emallshop' ),
                'subtitle'       => esc_html__( 'Default padding in px: 0 0', 'emallshop' ),
                'default'        => array(
                    'padding-top'    => '0px',
                    'padding-bottom' => '0px',
					'units'          => 'px',
                )
            ),
        )
    ) );
	
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Header', 'emallshop' ),
        'id'         => 'header-styling',
        'subsection' => true,
        'fields'     => array(
			array(
                'id'    => 'header-notice-info1',
                'type'   => 'info',
                'notice' => false,
                'title' => esc_html__( 'Header Wrapper Background.', 'emallshop' ),
            ),
			array(
                'id'       	=> 'header-wrapper-background',
                'type'     	=> 'background',
                'title'    	=> esc_html__('Background Color','emallshop'),
                'subtitle' 	=> esc_html__( 'Header wrapper background with image, color, etc.', 'emallshop' ),
				'output'   	=> array('#header'),
                'default' 	=> array(
								'background-color' => '#fcfcfc',
								'background-image' 		=> '',
								'background-repeat' 	=> '',
								'background-size' 		=> '',
								'background-attachment' => '',
								'background-position' 	=> '',
							)
            ),
			array(
                'id'       => 'header-overlay',
                'type'     => 'switch',
                'title'    => esc_html__('Header Overlay','emallshop'),
				'subtitle'     => esc_html__('Header overlay on home page slider. Note:- When you use header overlay on home page slider, at this time no any effect background color on topbar, header and navigation in home page header. But background color  work/effect in other all pages.','emallshop'),
                'on'       => esc_html__('Enable','emallshop'),
				'off'      => esc_html__('Disable','emallshop'),
				'default'  => 0,
            ),
			array(
                'id'    => 'header-notice-info2',
                'type'   => 'info',
                'notice' => false,
                'title' => esc_html__( 'Header Middle Background.', 'emallshop' ),
            ),
			array(
                'id'       => 'header-background',
                'type'     => 'background',
                'title'    => esc_html__('Background Color','emallshop'),
                'subtitle' => esc_html__( 'Header middle background with image, color, etc.', 'emallshop' ),
				'output'   => array('.header-middle'),
                'default' => array(
								'background-color' => '#fcfcfc',
								'background-image' 		=> '',
								'background-repeat' 	=> '',
								'background-size' 		=> '',
								'background-attachment' => '',
								'background-position' 	=> '',
							)
            ),
            array(
                'id'       => 'header-text-color',
                'type'     => 'color',
                'title'    => esc_html__( 'Text Color', 'emallshop' ),
                'default'  => '#656565',
            ),
			array(
                'id'       => 'header-input-background',
                'type'     => 'color',
                'title'    => esc_html__( 'Input Field Background( TextBox, SelectBox, etc..)', 'emallshop' ),
                'default'  => '#ffffff',
            ),
			array(
                'id'       => 'header-input-color',
                'type'     => 'color',
                'title'    => esc_html__( 'Input Field Color( TextBox, SelectBox, etc..)', 'emallshop' ),
                'default'  => '#656565',
            ),
			array(
                'id'       => 'header-link-color',
                'type'     => 'link_color',
                'title'    => esc_html__( 'Link Color', 'emallshop' ),
                'default'  => array(
                    'regular' => '#212121',
                    'hover'   => '#ff8400',
                    'active'  => '#ff8400',
                )
            ),
			array(
                'id'       => 'header-border',
                'type'     => 'border',
                'title'    => esc_html__( 'Header Border', 'emallshop' ),
                'subtitle' => esc_html__('Set Header border.','emallshop'),
                'default'  => array(
                    'border-color'  => '#e9e9e9',
                    'border-style'  => 'solid',
                    'border-top'    => '1px',
                    'border-right'  => '1px',
                    'border-bottom' => '1px',
                    'border-left'   => '1px'
                )
            ),			
			array(
                'id'             => 'header-padding',
                'type'           => 'spacing',
                'mode'           => 'padding',
				'units'          => 'px',
                'right'         => false,     // Disable the right
                'left'          => false,     // Disable the left               
                'title'          => esc_html__( 'Content Padding', 'emallshop' ),
                'subtitle'      => esc_html__( 'Default padding in px: 24 24', 'emallshop' ),
                'default'        => array(
                    'padding-top'    => '24px',
                    'padding-bottom' => '24px',
					'units'          => 'px',
                )
            ),			
			array(
                'id'    => 'header-notice-info3',
                'type'   => 'info',
                'notice' => false,
                'title' => esc_html__( 'Sticky Header', 'emallshop' ),
            ),
			array(
                'id'       => 'sticky-header-background',
                'type'     => 'background',
                'title'    => esc_html__('Background Color','emallshop'),
                'subtitle'  => esc_html__( 'Sticky Header background with image, color, etc.', 'emallshop' ),
				'output'   => array('.header-topbar.es-sticky, .es-sticky .wcaccount-topbar .wcaccount-dropdown, .header-middle.es-sticky, .header-navigation.es-sticky'),
                'default' => array(
								'background-color' => '#fcfcfc',
								'background-image' 		=> '',
								'background-repeat' 	=> '',
								'background-size' 		=> '',
								'background-attachment' => '',
								'background-position' 	=> '',
							)
            ),
			array(
                'id'       => 'sticky-header-text-color',
                'type'     => 'color',
                'title'    => esc_html__( 'Text Color', 'emallshop' ),
                'default'  => '#656565',
            ),
			array(
                'id'       => 'sticky-header-input-background',
                'type'     => 'color',
                'title'    => esc_html__( 'Input Field Background( TextBox, SelectBox, etc..)', 'emallshop' ),
                'default'  => '#ffffff',
            ),
			array(
                'id'       => 'sticky-header-input-color',
                'type'     => 'color',
                'title'    => esc_html__( 'Input Field Color( TextBox, SelectBox, etc..)', 'emallshop' ),
                'default'  => '#656565',
            ),
			array(
                'id'       => 'sticky-header-link-color',
                'type'     => 'link_color',
                'title'    => esc_html__( 'Text Color', 'emallshop' ),
                'default'  => array(
                    'regular' => '#212121',
                    'hover'   => '#ff8400',
                    'active'  => '#ff8400',
                )
            ),
			array(
                'id'       => 'sticky-header-border',
                'type'     => 'border',
                'title'    => esc_html__( 'Sticky Header Border', 'emallshop' ),
                'subtitle'     => esc_html__('Set Header border.','emallshop'),
                'default'  => array(
                    'border-color'  => '#e9e9e9',
                    'border-style'  => 'solid',
                    'border-top'    => '1px',
                    'border-right'  => '1px',
                    'border-bottom' => '1px',
                    'border-left'   => '1px'
                )
            ),
		)
	) );
	
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Topbar', 'emallshop' ),
        'id'         => 'topbar-styling',
        'subsection' => true,
        'fields'     => array(
			array(
                'id'       => 'topbar-background',
                'type'     => 'background',
                'title'    => esc_html__(' Topbar Background','emallshop'),
                'subtitle' => esc_html__( 'Topbar background with image, color, etc.', 'emallshop' ),
				'output'   => array('.header-topbar, .header-topbar .wcaccount-topbar .wcaccount-dropdown, .header-topbar .wpml-ls-statics-shortcode_actions .wpml-ls-sub-menu, .header-topbar .wcml-dropdown .wcml-cs-submenu, .header-topbar .demo-dropdown-sub-menu, .header-topbar .woocommerce-currency-switcher-form ul.dd-options, .header-topbar .dropdown-menu'),
                'default' => array(
								'background-color' => '#fcfcfc',
								'background-image' 		=> '',
								'background-repeat' 	=> '',
								'background-size' 		=> '',
								'background-attachment' => '',
								'background-position' 	=> '',
							)
            ),
			 array(
                'id'       => 'topbar-input-background',
                'type'     => 'color',
                'title'    => esc_html__( 'Input Field Background( TextBox, SelectBox, etc..)', 'emallshop' ),
                'default'  => '#ffffff',
            ),
			array(
                'id'       => 'topbar-input-color',
                'type'     => 'color',
                'title'    => esc_html__( 'Input Field Color( TextBox, SelectBox, etc..)', 'emallshop' ),
                'default'  => '#656565',
            ),
            array(
                'id'       => 'topbar-text-color',
                'type'     => 'color',
                'title'    => esc_html__( 'Text Color', 'emallshop' ),
                'default'  => '#656565',
            ),
			array(
                'id'       => 'topbar-link-color',
                'type'     => 'link_color',
                'title'    => esc_html__( 'Link Color', 'emallshop' ),
                'default'  => array(
                    'regular' => '#212121',
                    'hover'   => '#ff8400',
                    'active'  => '#ff8400',
                )
            ),
			array(
                'id'       => 'topbar-border',
                'type'     => 'border',
                'title'    => esc_html__( 'Topbar Border', 'emallshop' ),
                'subtitle' => esc_html__('Set topbar border.','emallshop'),
                'default'  => array(
                    'border-color'  => '#e9e9e9',
                    'border-style'  => 'solid',
                    'border-top'    => '1px',
                    'border-right'  => '1px',
                    'border-bottom' => '1px',
                    'border-left'   => '1px'
                )
            ),			
		)
	) );
	
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Navigation', 'emallshop' ),
        'id'         => 'navigation-styling',
        'subsection' => true,
        'fields'     => array(
			array(
                'id'       => 'navigation-background',
                'type'     => 'background',
                'title'    => esc_html__('Background Color','emallshop'),
                'subtitle' => esc_html__( 'Navigation background with image, color, etc.', 'emallshop' ),
				'output'   => array('.header-navigation'),
                'default'  => array(
								'background-color' => '#0ba2e8',
								'background-image' 		=> '',
								'background-repeat' 	=> '',
								'background-size' 		=> '',
								'background-attachment' => '',
								'background-position' 	=> '',
							)
            ),
			array(
                'id'       => 'navigation-secondary-color',
                'type'     => 'color',
                'title'    => esc_html__( 'Secondary Background Color', 'emallshop' ),
				'subtitle' => esc_html__('Secondary navigation background color.','emallshop'),
                'default'  => '#ff8400',
            ),
            array(
                'id'       => 'navigation-text-color',
                'type'     => 'color',
                'title'    => esc_html__( 'Text Color', 'emallshop' ),
                'default'  => '#ffffff',
            ),			
			array(
                'id'       => 'navigation-input-background',
                'type'     => 'color',
                'title'    => esc_html__( 'Input Field Background( TextBox, SelectBox, etc..)', 'emallshop' ),
                'default'  => '#ffffff',
            ),
			array(
                'id'       => 'navigation-input-color',
                'type'     => 'color',
                'title'    => esc_html__( 'Input Field Color( TextBox, SelectBox, etc..)', 'emallshop' ),
                'default'  => '#656565',
            ),
			array(
                'id'       => 'navigation-link-color',
                'type'     => 'link_color',
                'title'    => esc_html__( 'Link Color', 'emallshop' ),
                'default'  => array(
                    'regular' => '#ffffff',
                    'hover'   => '#ffffff',
                    'active'  => '#ffffff',
                )
            ),			 
			array(
                'id'       => 'navigation-border',
                'type'     => 'border',
                'title'    => esc_html__( 'Navigation Border', 'emallshop' ),
                'subtitle' => esc_html__('Set Navigation border.','emallshop'),
                'default'  => array(
                    'border-color'  => '#19b0f6',
                    'border-style'  => 'solid',
                    'border-top'    => '1px',
                    'border-right'  => '1px',
                    'border-bottom' => '1px',
                    'border-left'   => '1px'
                )
            ),			
		)
	) );
	
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Menu', 'emallshop' ),
        'id'         => 'menu-styling',
        'subsection' => true,
        'fields'     => array(
			array(
                'id'       => 'menu-background-color',
                'type'     => 'color',
                'title'    => esc_html__( 'Background Color', 'emallshop' ),
                'default'  => '#ffffff',
            ),
            array(
                'id'       => 'menu-text-color',
                'type'     => 'color',
                'title'    => esc_html__( 'Text Color', 'emallshop' ),
                'default'  => '#656565',
            ),
			array(
                'id'       => 'menu-link-color',
                'type'     => 'link_color',
                'title'    => esc_html__( 'Link Color', 'emallshop' ),
                'default'  => array(
                    'regular' => '#212121',
                    'hover'   => '#ff8400',
                    'active'  => '#ff8400',
                )
            ),
			array(
                'id'       => 'menu-border',
                'type'     => 'border',
                'title'    => esc_html__( 'Menu Border', 'emallshop' ),
                'subtitle'     => esc_html__('Set Menu border.','emallshop'),
                'default'  => array(
                    'border-color'  => '#e9e9e9',
                    'border-style'  => 'solid',
                    'border-top'    => '1px',
                    'border-right'  => '1px',
                    'border-bottom' => '1px',
                    'border-left'   => '1px'
                )
            ),			
		)
	) );
	
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Page Heading', 'emallshop' ),
        'id'         => 'page-heading-style',
        'subsection' => true,
        'fields'     => array(
			array(
                'id'       => 'page-heading-background',
                'type'     => 'background',
                'title'    =>  esc_html__('Background Color','emallshop'),
                'subtitle'     => esc_html__( 'Page heading background with image, color, etc.', 'emallshop' ),
				'output'   => array('#header .page-heading'),
                'default' => array(
								'background-color' => '#fcfcfc',
								'background-image' 		=> '',
								'background-repeat' 	=> '',
								'background-size' 		=> '',
								'background-attachment' => '',
								'background-position' 	=> '',
							)
            ),
			array(
                'id'       => 'page-heading-heading-color',
                'type'     => 'color',
                'title'    => esc_html__( 'Page Heading/Title Color', 'emallshop' ),
				'subtitle'     => esc_html__('Page heading/title color.','emallshop'),
                'default'  => '#212121',
            ),
            array(
                'id'       => 'page-heading-text-color',
                'type'     => 'color',
                'title'    => esc_html__( 'Text Color', 'emallshop' ),
                'default'  => '#656565',
            ),
			array(
                'id'       => 'page-heading-link-color',
                'type'     => 'link_color',
                'title'    => esc_html__( 'Link Color', 'emallshop' ),
                'default'  => array(
                    'regular' => '#212121',
                    'hover'   => '#ff8400',
                    'active'  => '#ff8400',
                )
            ),
			array(
                'id'       => 'page-heading-border',
                'type'     => 'border',
                'title'    => esc_html__( 'Page Heading Border', 'emallshop' ),
                'subtitle'     => esc_html__('Set page heading border.','emallshop'),
                'default'  => array(
                    'border-color'  => '#f5f5f5',
                    'border-style'  => 'solid',
                    'border-top'    => '1px',
                    'border-right'  => '1px',
                    'border-bottom' => '1px',
                    'border-left'   => '1px'
                )
            ),			
			array(
                'id'             => 'page-heading-padding',
                'type'           => 'spacing',
                'mode'           => 'padding',
				'units'          => 'px',
                'right'         => false,     // Disable the right
                'left'          => false,     // Disable the left               
                'title'          => esc_html__( 'Content Padding', 'emallshop' ),
                'subtitle'           => esc_html__( 'Default padding in px: 10 10', 'emallshop' ),
                'default'        => array(
                    'padding-top'    => '10px',
                    'padding-bottom' => '10px',
					'units'          => 'px',
                )
            ),
		)
	) );
		
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Footer', 'emallshop' ),
        'id'         => 'footer-styling',
        'subsection' => true,
        'fields'     => array(
			array(
                'id'    => 'footer-notice-info1',
                'type'   => 'info',
                'notice' => false,
                'title' => esc_html__( 'Footer Wrapper Background.', 'emallshop' ),
            ),
			array(
                'id'       => 'footer-wrapper-background',
                'type'     => 'background',
                'title'    => esc_html__('Background Color','emallshop'),
                'subtitle'     => esc_html__( 'Footer wrapper background with image, color, etc.', 'emallshop' ),
				'output'   => array('.footer'),
                'default' => array(
								'background-color' => '#fcfcfc',
								'background-image' 		=> '',
								'background-repeat' 	=> '',
								'background-size' 		=> '',
								'background-attachment' => '',
								'background-position' 	=> '',
							)
            ),
			array(
                'id'    => 'footer-notice-info2',
                'type'   => 'info',
                'notice' => false,
                'title' => esc_html__( 'Footer Top/Middle Background.', 'emallshop' ),
            ),
			array(
                'id'       => 'footer-background',
                'type'     => 'background',
                'title'    => esc_html__('Background Color','emallshop'),
                'subtitle'     => esc_html__( 'Footer top/middle background with image, color, etc.', 'emallshop' ),
				'output'   => array('.footer .footer-top, .footer .footer-middle'),
                'default' => array(
								'background-color' => '#fcfcfc',
								'background-image' 		=> '',
								'background-repeat' 	=> '',
								'background-size' 		=> '',
								'background-attachment' => '',
								'background-position' 	=> '',
							)
            ),
			array(
                'id'       => 'footer-heading-color',
                'type'     => 'color',
                'title'    => esc_html__( 'Heading Color', 'emallshop' ),
				'subtitle'     => esc_html__('Footer heading color.','emallshop'),
                'default'  => '#212121',
            ),
            array(
                'id'       => 'footer-text-color',
                'type'     => 'color',
                'title'    => esc_html__( 'Text Color', 'emallshop' ),
                'default'  => '#656565',
            ),
			array(
                'id'       => 'footer-input-background',
                'type'     => 'color',
                'title'    => esc_html__( 'Input Field Background( TextBox, SelectBox, etc..)', 'emallshop' ),
                'default'  => '#ffffff',
            ),
			array(
                'id'       => 'footer-input-color',
                'type'     => 'color',
                'title'    => esc_html__( 'Input Field Color( TextBox, SelectBox, etc..)', 'emallshop' ),
                'default'  => '#656565',
            ),
			array(
                'id'       => 'footer-link-color',
                'type'     => 'link_color',
                'title'    => esc_html__( 'Link Color', 'emallshop' ),
                'default'  => array(
                    'regular' => '#212121',
                    'hover'   => '#ff8400',
                    'active'  => '#ff8400',
                )
            ),
			array(
                'id'       => 'footer-border',
                'type'     => 'border',
                'title'    => esc_html__( 'Footer Border', 'emallshop' ),
                'default'  => array(
                    'border-color'  => '#e9e9e9',
                    'border-style'  => 'solid',
                    'border-top'    => '1px',
                    'border-right'  => '1px',
                    'border-bottom' => '1px',
                    'border-left'   => '1px'
                )
            ),			
			array(
                'id'             => 'footer-padding',
                'type'           => 'spacing',
                'mode'           => 'padding',
				'units'          => 'px',
                'right'         => false,     // Disable the right
                'left'          => false,     // Disable the left               
                'title'          => esc_html__( 'Content Padding', 'emallshop' ),
                'subtitle'           => esc_html__( 'Default padding in px: 42 42', 'emallshop' ),
                'default'        => array(
                    'padding-top'    => '42px',
                    'padding-bottom' => '42px',
					'units'          => 'px',
                )
            ),			
		)
	) );
	
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Copyright', 'emallshop' ),
        'id'         => 'copyright-styling',
        'subsection' => true,
        'fields'     => array(
			array(
                'id'       => 'copyright-background',
                'type'     => 'background',
                'title'    => esc_html__('Background Color','emallshop'),
                'subtitle' => esc_html__( 'Copyright background with image, color, etc.', 'emallshop' ),
				'output'   => array('.footer-copyright'),
                'default'  => array(
								'background-color' => '#fcfcfc',
								'background-image' 		=> '',
								'background-repeat' 	=> '',
								'background-size' 		=> '',
								'background-attachment' => '',
								'background-position' 	=> '',
							)
            ),
			array(
                'id'            => 'copyright-opacity',
                'type'          => 'slider',
                'title'         => esc_html__( 'Background Opacity', 'emallshop' ),
				'subtitle'     		=> esc_html__( 'Copyright background opacity.', 'emallshop' ),
                'default'       => 1,
                'min'           => 0,
                'step'          => .1,
                'max'           => 1,
                'resolution'    => 0.1,
                'display_value' => 'label'
            ),
            array(
                'id'       => 'copyright-text-color',
                'type'     => 'color',
                'title'    => esc_html__( 'Text Color', 'emallshop' ),
                'default'  => '#656565',
            ),
			array(
                'id'       => 'copyright-link-color',
                'type'     => 'link_color',
                'title'    => esc_html__( 'Link Color', 'emallshop' ),
                'default'  => array(
                    'regular' => '#212121',
                    'hover'   => '#ff8400',
                    'active'  => '#ff8400',
                )
            ),
			array(
                'id'       => 'copyright-border',
                'type'     => 'border',
                'title'    => esc_html__( 'Copyright Border', 'emallshop' ),
                'default'  => array(
                    'border-color'  => '#e9e9e9',
                    'border-style'  => 'solid',
                    'border-top'    => '1px',
                    'border-right'  => '1px',
                    'border-bottom' => '1px',
                    'border-left'   => '1px'
                )
            ),			
			array(
                'id'             => 'copyright-padding',
                'type'           => 'spacing',
                'mode'           => 'padding',
				'units'          => 'px',
                'right'         => false,     // Disable the right
                'left'          => false,     // Disable the left               
                'title'          => esc_html__( 'Content Padding', 'emallshop' ),
                'subtitle'           => esc_html__( 'Default padding: 14 14', 'emallshop' ),
                'default'        => array(
                    'padding-top'    => '14px',
                    'padding-bottom' => '14px',
					'units'          => 'px',
                )
            ),
		)
	) );
	
	// Theme Typography Options	
	Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Theme Typography', 'emallshop' ),
        'id'               => 'theme-typography',
		'desc'             => '',
        'icon'		 	   => 'el el-font',
        'fields'     => array(
			array(
                'id'       => 'google-charset',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Google Font Character Sets', 'emallshop' ),
				'subtitle'     => esc_html__( 'Select google font character sets', 'emallshop' ),
				'multi'    => true,
                'options'  => array(
                    'cyrillic' 		=> 'Cyrillic',
                    'cyrillic-ext' 	=> 'Cyrillic Extended',
                    'greek' 		=> 'Greek',
					'greek-ext' 	=> 'Greek Extended',
					'latin' 		=> 'Latin',
					'latin-ext' 	=> 'Latin Extneded',
                ),
                'default'  => array('cyrillic','cyrillic-ext','greek','greek-ext','latin-ext','latin'),
            ),
			array(
				'id'          => 'body-typography',
				'type'        => 'typography', 
				'title'       => esc_html__('Body Font', 'emallshop'),
				'google'      => true,
				'color'       => false,
				'text-align'  => false,
				'subsets'	  => false,
				'line-height' => false,
				'all_styles'  => true,
				'default'     => array(  
					'google'      => true,
					'font-family' => 'Open Sans',
					'font-style'  => '400',
					'font-weight'  => '400', 
					'font-size'   => '14px', 
					'line-height' => '22'
				),
			),
		)
	) );
	
	// Header Options
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Header', 'emallshop' ),
        'id'         => 'header',
		'icon'  	 => 'el el-website',
        'fields'     => array(
			array(
				'id'		=>'header-layout',
				'type' 		=> 'image_select',
				'full_width'=> true,
				'title' 	=> esc_html__('Header Layout', 'emallshop'),
				'subtitle' 	=> esc_html__('Choose Your header style/layout.', 'emallshop'),
				'options' 	=> $emallshop_header_style,
				'default' 	=> 'header-1',
			),
			array(
                'id'       => 'sticky-header',
                'type'     => 'switch',
                'title'    => esc_html__('Sticky Header','emallshop'),
                'on'       => esc_html__('Enable','emallshop'),
				'off'      => esc_html__('Disable','emallshop'),
				'default'  => 0,
            ),
			array(
                'id'       => 'sticky-header-mobile',
                'type'     => 'switch',
                'title'    => esc_html__('Sticky Header In Mobile','emallshop'),
                'on'       => esc_html__('Enable','emallshop'),
				'off'      => esc_html__('Disable','emallshop'),
				'default'  => 0,
				'required' => array( 'sticky-header', '=', 1 ),
            ),
			array(
                'id'       => 'sticky-header-part',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Select Sticky Header Part', 'emallshop' ),
                'options'  => array(
					'sticky-topbar'=>esc_html__('Topbar Header','emallshop'),
					'sticky-middle'=>esc_html__('Middle Header','emallshop'),
					'sticky-navigation'=>esc_html__('Navigation Header','emallshop'),					
				),
                'default'  => 'sticky-navigation',
				'required' => array( 'sticky-header', '=', 1 )
            ),
			array(
                'id'       => 'show-topbar',
                'type'     => 'switch',
                'title'    => esc_html__('Show Header Topbar','emallshop'),
                'subtitle' => esc_html__('Show header topbar on header.','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-language-switcher',
                'type'     => 'switch',
                'title'    => esc_html__('Show Language Switcher','emallshop'),
                'subtitle' => esc_html__('Show language switcher on topbar.','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( 'show-topbar', '=', 1 )
            ),			
			array(
                'id'       => 'show-currency-switcher',
                'type'     => 'switch',
                'title'    => esc_html__('Show Currency Switcher','emallshop'),
                'subtitle' => esc_html__('Show currency switcher on topbar.','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( 'show-topbar', '=', 1 )
            ),
			array(
                'id'       => 'show-topbar-email',
                'type'     => 'switch',
                'title'    => esc_html__('Show Topbar Email','emallshop'),
                'subtitle' => esc_html__('Show email address on topbar.','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( 'show-topbar', '=', 1 )
            ),
			array(
                'id'       => 'topbar-email',
                'type'     => 'text',
                'title'    => esc_html__('Enter Topbar Email','emallshop'),
                'subtitle' => esc_html__('Enter topbar email address.','emallshop'),
				'default'  => 'info@example.com',
				'required' => array( 'show-topbar-email', '=', 1 )
            ),
			array(
                'id'       => 'show-topbar-number',
                'type'     => 'switch',
                'title'    => esc_html__('Show Topbar Number','emallshop'),
                'subtitle' => esc_html__('Show mobile number on topbar.','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( 'show-topbar', '=', 1 )
            ),
			array(
                'id'       => 'topbar-number',
                'type'     => 'text',
                'title'    => esc_html__('Enter Topbar Number','emallshop'),
                'subtitle' => esc_html__('Enter topbar mobile number.','emallshop'),
				'default'  => '+81 59832452528',
				'required' => array( 'show-topbar-number', '=', 1 )
            ),
			array(
                'id'       => 'show-topbar-news',
                'type'     => 'switch',
                'title'    => esc_html__('Show Topbar News','emallshop'),
                'subtitle' => esc_html__('Show news on topbar.','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( 'show-topbar', '=', 1 )
            ),
			array(
                'id'       => 'topbar-news',
                'type'     => 'textarea',
                'title'    => esc_html__('Enter Topbar News','emallshop'),
                'subtitle' => esc_html__('Enter topbar news in "a" tab.','emallshop'),
				'default'  => wp_kses_post('<a href="#">Super Sale 50%</a><a href="#">Big Promotion on Valentine days</a><a href="#">Gift 15 Voucher for</a>'),
				'required' => array( 'show-topbar-news', '=', 1 )
            ),
			array(
                'id'       => 'show-topbar-social-link',
                'type'     => 'switch',
                'title'    => esc_html__('Show Topbar Social Link','emallshop'),
                'subtitle' => esc_html__('Show social link on topbar.','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( 'show-topbar', '=', 1 )
            ),
			array(
                'id'       => 'show-topbar-welcome-message',
                'type'     => 'switch',
                'title'    => esc_html__('Show Topbar Welcome Message','emallshop'),
                'subtitle' => esc_html__('Show welcome message on topbar.','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 0,
				'required' => array( 'show-topbar', '=', 1 )
            ),
			array(
                'id'       => 'topbar-welcome-message',
                'type'     => 'text',
                'title'    => esc_html__('Enter Topbar Welcome Message','emallshop'),
                'subtitle' => esc_html__('Enter topbar welcome message.','emallshop'),
				'default'  => 'Welcome to my Shop',
				'required' => array( 'show-topbar-welcome-message', '=', 1 )
            ),
			array(
                'id'       => 'show-topbar-help',
                'type'     => 'switch',
                'title'    => esc_html__('Show Topbar Help','emallshop'),
                'subtitle' => esc_html__('Show help on topbar.','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( 'show-topbar', '=', 1 )
            ),
			array(
                'id'       => 'topbar-help',
                'type'     => 'text',
                'title'    => esc_html__('Enter Help Link','emallshop'),
                'subtitle'     => esc_html__('Enter help link. of topbar','emallshop'),
				'default'  => '#',
				'placeholder' => 'www.help.com',
				'required' => array( 'show-topbar-help', '=', 1 )
            ),
			array(
                'id'       => 'live-search',
                'type'     => 'switch',
                'title'    => esc_html__('Product Live/Ajax Search','emallshop'),
                'subtitle'     => esc_html__('Live product search or not on header.','emallshop'),
                'on'       => esc_html__('Enable','emallshop'),
				'off'      => esc_html__('Disable','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-categories-dropdow',
                'type'     => 'switch',
                'title'    => esc_html__('Show Categories Dropdown','emallshop'),
                'subtitle' 	   => esc_html__('Show categories dropdow in product search.','emallshop'),
                'on'       => esc_html__('On','emallshop'),
				'off'      => esc_html__('Off','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'search-categories',
                'type'     => 'radio',
                'title'    => esc_html__('Search Categories Dropdown','emallshop'),
                'subtitle'     => esc_html__('Display categories in search categories dropdow.','emallshop'),
                'options'  => array(
								'all' 	 => esc_html__('Show All Categories','emallshop'),
								'parent' => esc_html__('Only Parent(top level) Categories','emallshop'),
							),
				'default'  => 'all',
				'required' => array( 'show-categories-dropdow', '=', 1 ),
            ),
			array(
                'id'       => 'categories-hierarchical',
                'type'     => 'switch',
                'title'    => esc_html__('Show Categories Hierarchical','emallshop'),
                'subtitle' 	   => esc_html__('Show categories in hierarchical (Must be need to select above option Show All Categories).','emallshop'),
                'on'       => esc_html__('On','emallshop'),
				'off'      => esc_html__('Off','emallshop'),
				'default'  => 1,
				'required' => array( 'search-categories', '=', 'all' )
            ),
			array(
                'id'       => 'search-placeholder-text',
                'type'     => 'text',
                'title'    => esc_html__('Search Palceholder Text','emallshop'),
                'subtitle'     => esc_html__('Enter search palceholder text','emallshop'),
				'default'  => "I'm shopping for...",
            ),
			array(
                'id'       => 'show-header-cart',
                'type'     => 'switch',
                'title'    => esc_html__('Show Cart','emallshop'),
                'subtitle'     => esc_html__('Show cart on header.','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-header-wishlist',
                'type'     => 'switch',
                'title'    => esc_html__('Show Wishlist','emallshop'),
                'subtitle'     => esc_html__('Show wishlist on header.','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-header-campare',
                'type'     => 'switch',
                'title'    => esc_html__('Show Compare','emallshop'),
                'subtitle'     => esc_html__('Show compare on header.','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),			
			array(
                'id'       => 'service-icon1',
                'type'     => 'text',
                'title'    => esc_html__('Header Services Icon 1','emallshop'),
				'default'  => 'fa-phone',
            ),
			array(
                'id'       => 'service-title1',
                'type'     => 'text',
                'title'    => esc_html__('Enter Service Title 1.','emallshop'),
				'default'  => '08 143 456 753',
            ),
			array(
                'id'       => 'service-des1',
                'type'     => 'text',
                'title'    => esc_html__('Enter Service Sort Description 1','emallshop'),
				'default'  => 'lorem ipsum dolor.',
            ),
			array(
                'id'       => 'service-icon2',
                'type'     => 'text',
                'title'    => esc_html__('Header Services Icon 3','emallshop'),
				'default'  => 'fa-truck',
            ),
			array(
                'id'       => 'service-title2',
                'type'     => 'text',
                'title'    => esc_html__('Enter Service Title 2','emallshop'),
				'default'  => esc_html__('Free Shipping','emallshop'),
            ),
			array(
                'id'       => 'service-des2',
                'type'     => 'text',
                'title'    => esc_html__('Enter Service Sort Description 2','emallshop'),
				'default'  => esc_html__('all order over $100.','emallshop'),
            ),
			array(
                'id'       => 'service-icon3',
                'type'     => 'text',
                'title'    => esc_html__('Header Services Icon 3','emallshop'),
				'default'  => 'fa-refresh',
            ),
			array(
                'id'       => 'service-title3',
                'type'     => 'text',
                'title'    => esc_html__('Enter Service Title 3','emallshop'),
				'default'  => esc_html__('Return & Exchange','emallshop'),
            ),
			array(
                'id'       => 'service-des3',
                'type'     => 'text',
                'title'    => esc_html__('Enter Service Sort Description 3','emallshop'),
				'default'  => 'In 5 working days.',
            ),
			array(
                'id'       => 'show-categories-menu',
                'type'     => 'switch',
                'title'    => esc_html__('Show Categories Menu','emallshop'),
                'subtitle'     => esc_html__('Show shopping categories menu on header or not.','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'categories-menu',
                'type'     => 'switch',
                'title'    => esc_html__('Open Categories(Vertical) Menu In Home Page','emallshop'),
                'subtitle'     => esc_html__('Categories(Vertical) menu open in home page. See demo ex. Electronic.','emallshop'),
                'on'       => esc_html__('Open','emallshop'),
				'off'      => esc_html__('Close','emallshop'),
				'default'  => 0,
            ),
			array(
                'id'       => 'shopping-categories-text',
                'type'     => 'text',
                'title'    => esc_html__('Shopping Categories Title','emallshop'),
				'default'  => 'Shopping Categories',
            ),
		)
	) );
	/*
	* Page Heading options
	*/
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Page Heading', 'emallshop' ),
        'id'         => 'page-heading',
		'subsection' => true,
        'fields'     => array(
			array(
                'id'       => 'show-page-heading',
                'type'     => 'switch',
                'title'    => esc_html__('Show Page Heading','emallshop'),
                'on'       => esc_html__('Yes','emallshop'),
				'off'      => esc_html__('No','emallshop'),
				'default'  => 1,
            ),
			array(
				'id'		=>'page-heading-layout',
				'type' 		=> 'image_select',
				'full_width'=> true,
				'title' 	=> esc_html__('Page Heading Layout', 'emallshop'),
				'subtitle' 	=> esc_html__('Choose Your page heading style/layout.', 'emallshop'),
				'options' 	=> $emallshop_page_heading_style,
				'default' 	=> 'page-heading-2',
				'required' => array( 'show-page-heading', '=', 1 )
			),
			array(
                'id'       => 'show-page-title',
                'type'     => 'switch',
                'title'    => esc_html__('Show Page Title','emallshop'),
                'on'       => esc_html__('Yes','emallshop'),
				'off'      => esc_html__('No','emallshop'),
				'default'  => 1,
				'required' => array( 'show-page-heading', '=', 1 )
            ),
			array(
                'id'       => 'show-title-breadcrumb-content',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Show Title In Page Heading Or Page Content', 'emallshop' ),
				'subtitle'     => esc_html__( 'Show page title in page heading area or page content area.', 'emallshop' ),
                'options'  => array(
                    'in-page-heading' => esc_html__('In  Page Heading','emallshop'),
                    'in-page-content' => esc_html__('In Page Content','emallshop'),
                ),
                'default'  => 'in-page-heading',
				'required' => array( 'show-page-title', '=', 1 )
            ),
			array(
                'id'       => 'show-page-breadcrumb',
                'type'     => 'switch',
                'title'    => esc_html__('Show Page Breadcrumb','emallshop'),
                'on'       => esc_html__('Yes','emallshop'),
				'off'      => esc_html__('No','emallshop'),
				'default'  => 1,
				'required' => array( 'show-page-heading', '=', 1 )
            ),			
		)
	) );
	
	//Footer Options
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Footer', 'emallshop' ),
        'id'         => 'footer',
		'icon'  	 => 'el el-website',
        'fields'     => array(
			array(
				'id'		=>'footer-layout',
				'type' 		=> 'image_select',
				'full_width'=> true,
				'title' 	=> esc_html__('Footer Layout', 'emallshop'),
				'subtitle' 	=> esc_html__('Choose Your footer style/layout.', 'emallshop'),
				'options' 	=> $emallshop_footer_style,
				'default' 	=> 'footer-1',
			),
			array(
				'id'       => 'footer-categories',
				'type'     => 'select',
				'multi'    => true,
				'data' => 'terms',
				/* 'args' => array('taxonomies'=>'product_cat', 'args'=>array("hide_empty" =>1,"parent" =>0)), */
				'args' => array('taxonomies'=>'product_cat'),
				'title'    => esc_html__('Select Your Popular Categories', 'emallshop'),
				'desc'     => esc_html__( 'Select your populare categories and display on footer. But need to be first select footer style third. ', 'emallshop' ),
				'placeholder' => 'Choose product categories',
			),			
			array(
                'id'       	=> 'copyright-text',
                'type'     	=> 'textarea',
                'title'    	=> esc_html__('Copyright','emallshop'),
				'subtitle'	=> esc_html__( 'Enter copyright text. Use {current_year} for get dynamic current year.', 'emallshop' ),
				'default'  	=> wp_kses_post('Emallshop &copy; {current_year} by <a href="https://presslayouts.com/" target="_blank">PressLayouts</a> All Rights Reserved.'),
            ),
			array(
                'id'       => 'show-payments-logo',
                'type'     => 'switch',
                'title'    => esc_html__('Show Payments Logo','emallshop'),
                'default'  => 0,
                'on'       => esc_html__('Show','emallshop'),
                'off'      => esc_html__('Hide','emallshop'),
            ),
			array(
                'id'       => 'payments-logo',
                'type'     => 'media',
                'url'      => true,
                'title'    => esc_html__('Payments Logo Image','emallshop'),
                'compiler' => 'true',
                'subtitle' => esc_html__('Upload payments logo image.','emallshop'),
                'default'  => array(),
				'required' => array( 'show-payments-logo', '=', 1 )
            ),
		)
	) );	
	
	/*
	* Woocommerce Options
	*/
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'WooCommerce'.$theme_slug , 'emallshop' ),
        'id'         => 'woocommerce',
		'icon'		 => 'el el-shopping-cart',
		'fields'     => array(
			array(
                'id'       => 'manage-password-strength',
                'type'     => 'switch',
                'title'    => esc_html__('Manage Password Strength','emallshop'),
				'desc'     => esc_html__( 'Reduce the strength requirement on the woocommerce use login/signup password ', 'emallshop' ),
                'on'       => esc_html__('Yes','emallshop'),
				'off'      => esc_html__('No','emallshop'),
				'default'  => 0,
            ),
			array(
                'id'       => 'user-password-strength',
                'type'     => 'button_set',
                'title'    => esc_html__( 'User Password Strength', 'emallshop' ),
                'options'  => array(
                    '3' => esc_html__('Strong (default)', 'emallshop' ),
                    '2' => esc_html__('Medium', 'emallshop' ),
					'1' => esc_html__('Weak', 'emallshop' ),
					'0' => esc_html__('Very Weak', 'emallshop' ),
                ),
                'default'  => '3',
				'required' => array( 'manage-password-strength', '=', 1 )
            ),
			array(
                'id'       => 'enable-search-by-sku',
                'type'     => 'switch',
                'title'    => esc_html__('Live Search By Product SKU','emallshop'),
				'desc'     => esc_html__( 'Live search by product sku or not.', 'emallshop' ),
                'on'       => esc_html__('Yes','emallshop'),
				'off'      => esc_html__('No','emallshop'),
				'default'  => 0,
            ),
			
			array(
                'id'       => 'enable-add-to-cart-ajax',
                'type'     => 'switch',
                'title'    => esc_html__('Add to Cart Using Ajax','emallshop'),
				'desc'     => esc_html__( 'Enable or disable add to cart product using ajax without load page. ', 'emallshop' ),
                'on'       => esc_html__('Enable','emallshop'),
				'off'      => esc_html__('Disable','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-cart-popup',
                'type'     => 'switch',
                'title'    => esc_html__('Show Cart Popup','emallshop'),
				'desc'     => esc_html__( 'Show cart popup after added product in cart. ', 'emallshop' ),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'mini-cart-quantity',
                'type'     => 'switch',
                'title'    => esc_html__('Quantity Field in Mini Cart','emallshop'),
				'subtitle'     => esc_html__( 'Show quantity field in mini cart. ', 'emallshop' ),
                'on'       => esc_html__( 'Show', 'emallshop' ),
				'off'      => esc_html__( 'Hide', 'emallshop' ),
				'default'  => 1,
            ),
			array(
                'id'       => 'login-to-see-price',
                'type'     => 'switch',
                'title'    => esc_html__('Login To See Price','emallshop'),
				'desc'     => esc_html__( 'Show product price after login user. ', 'emallshop' ),
                'on'       => esc_html__('Enable','emallshop'),
				'off'      => esc_html__('Disable','emallshop'),
				'default'  => 0,
            ),
			array(
                'id'       => 'show-product-highlight-labels',
                'type'     => 'switch',
                'title'    => esc_html__('Show Product Highlight Labels','emallshop'),
                'desc'     => esc_html__('Show product highlight labels sale, featured, new and out of stock on product.','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-sale-product-highlight-label',
                'type'     => 'switch',
                'title'    => esc_html__('Show Sale Product Highlight Label','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( 'show-product-highlight-labels', '=', 1 )
            ),
			array(
                'id'       => 'sale-label-percentages-text',
                'type'     => 'button_set',
                //'title'    => esc_html__( 'Default Product View Style', 'emallshop' ),
				'desc' => esc_html__( 'Show sale product label in percentages or text.', 'emallshop' ),
                'options'  => array(
                    'percentages' => esc_html__('Percentages','emallshop'),
                    'text' => esc_html__('Text','emallshop'),
                ),
                'default'  => 'percentages',
				'required' => array( 'show-sale-product-highlight-label', '=', 1 )
            ),
			array(
                'id'       => 'sale-highlight-percentages-label-text',
                'type'     => 'text',
                'desc'    => esc_html__('Sale product highlight percentages label text.','emallshop'),
				'default'  => esc_html__('Off','emallshop'),
				'required' => array( 'sale-label-percentages-text', '=', 'percentages' )
            ),
			array(
                'id'       => 'sale-highlight-label-text',
                'type'     => 'text',
                'desc'    => esc_html__('Sale product highlight label text.','emallshop'),
				'default'  => esc_html__('Sale','emallshop'),
				'required' => array( 'sale-label-percentages-text', '=', 'text' )
            ),
			array(
                'id'       => 'sale-highlight-label-color',
                'type'     => 'color',
                'desc'    => esc_html__( 'Sale product highlight label color.', 'emallshop' ),
                'default'  => '#60BF79',
				'required' => array( 'show-sale-product-highlight-label', '=', 1 )
            ),
			array(
                'id'       => 'show-new-product-highlight-label',
                'type'     => 'switch',
                'title'    => esc_html__('Show New Product Highlight Label','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( 'show-product-highlight-labels', '=', 1 )
            ),
			array(
                'id'       => 'new-highlight-label-text',
                'type'     => 'text',
                'desc'    => esc_html__('New product highlight label text.','emallshop'),
				'default'  => esc_html__('New','emallshop'),
				'required' => array( 'show-new-product-highlight-label', '=', 1 )
            ),
			array(
                'id'            => 'product-newness-days',
                'type'          => 'slider',
                'desc'          => esc_html__( 'Enter number of days to newness.', 'emallshop' ),
                'default'       => 30,
                'min'           => 1,
                'step'          => 1,
                'max'           => 90,
                'display_value' => 'text',
				'required' => array( 'show-new-product-highlight-label', '=', 1 )
            ),
			array(
                'id'       => 'new-highlight-label-color',
                'type'     => 'color',
                'desc'    => esc_html__( 'New product highlight label color.', 'emallshop' ),
                'default'  => '#48c2f5',
				'required' => array( 'show-new-product-highlight-label', '=', 1 )
            ),
			array(
                'id'       => 'show-featured-product-highlight-label',
                'type'     => 'switch',
                'title'    => esc_html__('Show Featured Product Highlight Label','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( 'show-product-highlight-labels', '=', 1 )
            ),
			array(
                'id'       => 'featured-highlight-label-text',
                'type'     => 'text',
                'desc'    => esc_html__('Featured product highlight label text.','emallshop'),
				'default'  => esc_html__('Featured','emallshop'),
				'required' => array( 'show-featured-product-highlight-label', '=', 1 )
            ),
			array(
                'id'       => 'featured-highlight-label-color',
                'type'     => 'color',
                'desc'     => esc_html__( 'Featured product highlight label color.', 'emallshop' ),
                'default'  => '#ff781e',
				'required' => array( 'show-featured-product-highlight-label', '=', 1 )
            ),
			array(
                'id'       => 'show-outofstock-product-highlight-label',
                'type'     => 'switch',
                'title'    => esc_html__('Show Out Of Stock Product Highlight Label','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( 'show-product-highlight-labels', '=', 1 )
            ),
			array(
                'id'       => 'outofstock-highlight-label-text',
                'type'     => 'text',
                'desc'     => esc_html__('out of stock product highlight label text.','emallshop'),
				'default'  => esc_html__('Out Of Stock','emallshop'),
				'required' => array( 'show-outofstock-product-highlight-label', '=', 1 )
            ),
			array(
                'id'       => 'outofstock-highlight-label-color',
                'type'     => 'color',
                'desc'    => esc_html__( 'out of stock product highlight label color.', 'emallshop' ),
                'default'  => '#FF4557',
				'required' => array( 'show-outofstock-product-highlight-label', '=', 1 )
            ),
			array(
                'id'            => 'outofstock_opacity',
                'type'          => 'slider',
                'desc'          => esc_html__( 'Apply opacity on out of stock product.', 'emallshop' ),
                'default'       => .6,
                'min'           => .4,
                'step'          => .1,
                'max'           => 1,
                'resolution'    => 0.1,
                'display_value' => 'text',
				'required' => array( 'show-outofstock-product-highlight-label', '=', 1 )
            ),
		),
	) );
	
	/*
	* Shop/Category Page Options
	*/
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Shop/Category', 'emallshop' ),
        'id'         => 'shop-category',
        'subsection' => true,		
        'fields'     => array(
			array(
                'id'       => 'shop-page-layout',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Shop Page Layout', 'emallshop' ),
                'desc'     => esc_html__( 'Select shop page layout with sidebar postion.', 'emallshop' ),
                'options'  => array(
                    'full-layout' => array(
                        'alt' => 'Full Width',
                        'img' => ReduxFramework::$_url . 'assets/img/1col.png'
                    ),                   
                    'left' => array(
                        'alt' => '2 Column Left',
                        'img' => ReduxFramework::$_url . 'assets/img/2cl.png'
                    ), 
					'right' => array(
                        'alt' => '2 Column Right',
                        'img' => ReduxFramework::$_url . 'assets/img/2cr.png'
                    ), 
                ),
                'default'  => 'left'
            ),
			array(
                'id'       => 'shop-page-sidebar-widget',
                'type'     => 'select',
                'title'    => 'Select Sidebar Widget Area',
				'desc'     => esc_html__( 'Select widget and display in sidebar on shop page.', 'emallshop' ),
                'data'     => 'sidebars',
                'default'  => 'shop-page',
                'required' => array( 'shop-page-layout', '=', array( 'left', 'right' ) )
            ),
			array(
                'id'       => 'banner-sub-categories-brands',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Show Banner, Sub Categories & Brands', 'emallshop' ),
				'desc'     => esc_html__( 'Show category banner, sub categories and brands on category page.', 'emallshop' ),
				'multi'    => true,
                'options'  => array(
                    'category-banner' => esc_html__('Category Banner','emallshop'),
                    'sub-categories' => esc_html__('Sub Category','emallshop'),
                    'caregory-brands' => esc_html__('Category Brands','emallshop'),
                ),
                'default'  => '',
            ),
			array(
                'id'       => 'sub-categories-style',
                'type'     => 'button_set',
                'title'    => esc_html__('Sub Categories Style','emallshop'),
                'options'  => array('only_category'=>esc_html__('Only Sub Categories','emallshop'),'sub_category_box'=>esc_html__('Sub Categories With Inner','emallshop')),
                'default'  => 'only_category',
            ),
			array(
                'id'       => 'show-product-category-description',
                'type'     => 'switch',
                'title'    => esc_html__('Show Product Category Description','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-description-position',
                'type'     => 'button_set',
                'title'    => esc_html__('Show Product Description Position','emallshop'),
                'options'  => array(
					'top'=>esc_html__('Top','emallshop'),
					'bottom'=>esc_html__('Bottom','emallshop')
				),
                'default'  => 'top',
				'required' => array( 'show-product-category-description', '=', 1 )
            ),
			array(
				'id'		=>'product-layout-style',
				'type' 		=> 'image_select',
				'title' 	=> esc_html__('Product Layout Style', 'emallshop'),
				'options' 	=> array(
					'product-style1' => array('alt' => esc_html__('Product Style 1', 'emallshop'), 'img' => EMALLSHOP_ADMIN_IMAGES.'/product-style1.png'),
					'product-style2' => array('alt' => esc_html__('Product Style 2', 'emallshop'), 'img' => EMALLSHOP_ADMIN_IMAGES.'/product-style2.png'),
					'product-style3' => array('alt' => esc_html__('Product Style 3', 'emallshop'), 'img' => EMALLSHOP_ADMIN_IMAGES.'/product-style3.png'),
				),
				'default' 	=> 'product-style1',
			),
			array(
                'id'       => 'product-image-hover-style',
                'type'     => 'button_set',
                'title'    => esc_html__('Product Image Hover Style','emallshop'),
                'options'  => array(
					'product-image-style1' => esc_html__('Default','emallshop'),
					'product-image-style2' => esc_html__('Image Switch','emallshop'),
					'product-image-style4' => esc_html__('Image Auto Play Slider','emallshop'),
					'product-image-style3' => esc_html__('Image Slider','emallshop')),
                'default'  => 'product-image-style2',
            ),
			array(
                'id'       => 'products-per-row',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Show Products Per Row', 'emallshop' ),
                'options'  => array(
                    '3' => '3',
                    '4' => '4',
                    '6' => esc_html__('6 (In full width)','emallshop'),
                ),
                'default'  => '4',
            ),
			array(
                'id'       => 'products-show-per-page',
                'type'     => 'select',
                'title'    => esc_html__( 'Show Number Of Products Per Page', 'emallshop' ),
                'options'  => array('6'=>'6','8'=>'8','10'=>'10','12'=>'12','15'=>'15','16'=>'16','18'=>'18','20'=>'20','24'=>'24','27'=>'27','28'=>'28','30'=>'30','32'=>'32','33'=>'33','36'=>'36','40'=>'40', '48'=>'48', '60'=>'60', '72'=>'72', '84'=>'84', '108'=>'108', '120'=>'120'),
                'default'  => '12',
            ),
			array(
                'id'       => 'show-grid-list-button',
                'type'     => 'switch',
                'title'    => esc_html__('Show Grid/List View Button','emallshop'),
                'on'       => esc_html__('Yes','emallshop'),
				'off'      => esc_html__('No','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'product-view-style',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Enable Product View Style', 'emallshop' ),
                'multi'    => true,
                'options'  => array(
                    'grid' => esc_html__('Grid','emallshop'),
                    'expand-grid' => esc_html__('Expand Grid','emallshop'),
                    'list' => esc_html__('List','emallshop'),
					'thin-list' => esc_html__('Thin List','emallshop'),
                ),
                'default'  => array( 'grid', 'list' ),
				'required' => array( 'show-grid-list-button', '=', 1 )
            ),
			array(
                'id'       => 'product-default-view-style',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Default Product View Style', 'emallshop' ),
                'options'  => array(
                    'grid' => esc_html__('Grid','emallshop'),
                    'expand-grid' => esc_html__('Expand Grid','emallshop'),
                    'list' => esc_html__('List','emallshop'),
					'thin-list' => esc_html__('Thin List','emallshop'),
                ),
                'default'  => 'grid',
				'required' => array( 'show-grid-list-button', '=', 1 )
            ),
			array(
                'id'       => 'product-pagination-style',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Products Pagination Style', 'emallshop' ),
                'options'  => array(
					'default_pagination'=>esc_html__('Default','emallshop'),
					'infinity_scroll'=>esc_html__('Infinity Scroll','emallshop'),
					'more_button'=>esc_html__('Load More Button','emallshop'),
					'pagination'=>esc_html__('AJAX Pagination','emallshop')
				),
                'default'  => 'default_pagination',
            ),
			array(
                'id'       => 'load-more-button-text',
                'type'     => 'text',
                'title'    => esc_html__('Load More Button Text','emallshop'),
				'default'  => 'Load More',
            ),
			array(
                'id'       => 'enable-lazy-load',
                'type'     => 'switch',
                'title'    => esc_html__('Enable Lazy Load','emallshop'),
                'desc' 	   => esc_html__('Enable lazy load product image.','emallshop'),
                'on'       => esc_html__('Enable','emallshop'),
				'off'      => esc_html__('Disable','emallshop'),
				'default'  => 0,
            ),
			array(
                'id'       => 'load-animation-style',
                'type'     => 'select',
                'title'    => esc_html__( 'Lazy Load Animation Style', 'emallshop' ),
                'subtitle' => esc_html__( 'Different styles of lazy load animation.', 'emallshop' ),
                'options'  => array('bounce' => 'bounce', 'flash' => 'flash', 'pulse' => 'pulse', 'rubberBand' => 'rubberBand', 'shake' => 'shake', 'swing' => 'swing', 'tada' => 'tada', 'bounceIn' => 'bounceIn', 'bounceInDown' => 'bounceInDown', 'bounceInLeft' => 'bounceInLeft' , 'bounceInRight' => 'bounceInRight', 'bounceInUp' => 'bounceInUp', 'fadeIn' => 'fadeIn'    , 'fadeInDown' => 'fadeInDown', 'fadeInDownBig' => 'fadeInDownBig', 'fadeInLeft' => 'fadeInLeft' , 'fadeInLeftBig' => 'fadeInLeftBig', 'fadeInRight' => 'fadeInRight', 'fadeInRightBig' => 'fadeInRightBig', 'fadeInUp' => 'fadeInUp', 'fadeInUpBig' => 'fadeInUpBig', 'zoomIn' => 'zoomIn', 'zoomInDown' => 'zoomInDown', 'zoomInLeft' => 'zoomInLeft', 'zoomInRight' => 'zoomInRight', 'zoomInUp' => 'zoomInUp'),
                'default'  => 'fadeInUp',
				'required' => array( 'enable-lazy-load', '=', 1 )
            ),
			array(
                'id'       => 'show-products-per-page',
                'type'     => 'switch',
                'title'    => esc_html__('Show Products Per Page Dropdown','emallshop'),
                'desc' 	   => esc_html__('Show products per page dropdow on shop page.','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-product-price',
                'type'     => 'switch',
                'title'    => esc_html__('Show Product Price','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-product-rating',
                'type'     => 'switch',
                'title'    => esc_html__('Show Product Rating','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-product-buttons',
                'type'     => 'switch',
                'title'    => esc_html__('Show Product Buttons','emallshop'),
                'desc'     => esc_html__('Show product buttons cart, wishlist, compare, quick view etc.','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-cart-button',
                'type'     => 'switch',
                'title'    => esc_html__('Show Cart Button','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( 'show-product-buttons', '=', 1 )
            ),
			array(
                'id'       => 'show-wishlist-button',
                'type'     => 'switch',
                'title'    => esc_html__('Show Wishlist Button','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( 'show-product-buttons', '=', 1 )
            ),
			array(
                'id'       => 'show-compare-button',
                'type'     => 'switch',
                'title'    => esc_html__('Show Compare Button','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( 'show-product-buttons', '=', 1 )
            ),
			array(
                'id'       => 'show-quick-view-button',
                'type'     => 'switch',
                'title'    => esc_html__('Show Quick View Button','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( 'show-product-buttons', '=', 1 )
            ),
			array(
                'id'       => 'show-short-description',
                'type'     => 'switch',
                'title'    => esc_html__('Show short Description','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),			
			array(
                'id'       => 'show-product-variation',
                'type'     => 'switch',
                'title'    => esc_html__('Show Product Variation(Options)','emallshop'),
				'desc'     => esc_html__('Show product variation(attribute) on product hover. Like Color, Size, ...', 'emallshop' ),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),			
		)
	) );
	
	/*
	* Single product page options
	*/
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Single Product', 'emallshop' ),
        'id'         => 'single-product',
        'subsection' => true,
        'fields'     => array(
			array(
                'id'       => 'single-product-page-layout',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Single Product Page Layout', 'emallshop' ),
                'desc'     => esc_html__( 'Select product page layout with sidebar postion.', 'emallshop' ),
                'options'  => array(					
					'none-left' => array(
                        'alt' => 'left thumbnail',
                        'img' => EMALLSHOP_ADMIN_IMAGES.'/thumbnail/none_left.png'
                    ),                   
                    'none-right' => array(
                        'alt' => 'right thumbnail',
                        'img' => EMALLSHOP_ADMIN_IMAGES.'/thumbnail/none_right.png'
                    ),
					'full-layout' => array(
                        'alt' => 'full width',
                        'img' => EMALLSHOP_ADMIN_IMAGES.'/thumbnail/none.png'
                    ),
					'right' => array(
                        'alt' => 'right sidebar',
                        'img' => EMALLSHOP_ADMIN_IMAGES.'/thumbnail/right.png'
                    ),
					'left' => array(
                        'alt' => 'left sidebar',
                        'img' => EMALLSHOP_ADMIN_IMAGES.'/thumbnail/left.png'
                    ),					
                    					 					
                ),
                'default'  => 'none-left'
            ),
			array(
                'id'       => 'single-product-page-sidebar-widget',
                'type'     => 'select',
                'title'    => esc_html__('Select Sidebar Widget Area','emallshop'),
				'desc'     => esc_html__( 'Select widget and display in sidebar on single product page.', 'emallshop' ),
                'data'     => 'sidebars',
                'default'  => 'single-product',
                'required' => array( 'single-product-page-layout', '=', array( 'left', 'right' ) )
            ),
			array(
                'id'       => 'enable-product-image-zoom',
                'type'     => 'switch',
                'title'    => esc_html__( 'Product Image Zoom', 'emallshop' ),
                'on'       => esc_html__('Enable','emallshop'),
				'off'      => esc_html__('Disable','emallshop'),
                'default'  => 1,
            ),
			array(
                'id'       => 'sticky-image-wrapper',
                'type'     => 'switch',
                'title'    => esc_html__( 'Sticky Product Image Part', 'emallshop' ),
				'desc'     => esc_html__('When you scroll the product page at this time you want to stick product image part or not.','emallshop'),
                'on'       => esc_html__('Yes','emallshop'),
				'off'      => esc_html__('No','emallshop'),
                'default'  => 1,
            ),
			array(
                'id'       => 'sticky-summary-wrapper',
                'type'     => 'switch',
                'title'    => esc_html__( 'Sticky Product Summary Part', 'emallshop' ),
				'desc'     => esc_html__('When you scroll the product page at this time you want to stick product summary part or not.','emallshop'),
                'on'       => esc_html__('Yes','emallshop'),
				'off'      => esc_html__('No','emallshop'),
                'default'  => 1,
            ),
			array(
                'id'       => 'sticky-add-to-cart-button',
                'type'     => 'switch',
                'title'    => esc_html__( 'Sticky Add to Cart Button', 'emallshop' ),
				'desc'     => esc_html__('Sticky add to cart button on bottom when scroll the page.','emallshop'),
                'on'       => esc_html__('Yes','emallshop'),
				'off'      => esc_html__('No','emallshop'),
                'default'  => 1,
            ),
			array(
                'id'       => 'show-single-product-highlight-labels',
                'type'     => 'switch',
                'title'    => esc_html__('Show Product Highlight Labels','emallshop'),
                'desc'     => esc_html__('Show product highlight labels sale, featured, new and out of stock on product.','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-single-product-highlight-label-sale',
                'type'     => 'switch',
                'title'    => esc_html__('Show Product Highlight Label Sale','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( 'show-single-product-highlight-labels', '=', 1 )
            ),
			array(
                'id'       => 'show-single-product-highlight-label-featured',
                'type'     => 'switch',
                'title'    => esc_html__('Show Product Highlight Label Featured','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( 'show-single-product-highlight-labels', '=', 1 )
            ),
			array(
                'id'       => 'show-single-product-highlight-label-new',
                'type'     => 'switch',
                'title'    => esc_html__('Show Product Highlight Label New','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( 'show-single-product-highlight-labels', '=', 1 )
            ),
			array(
                'id'       => 'show-single-product-highlight-label-outofstock',
                'type'     => 'switch',
                'title'    => esc_html__('Show Product Highlight Label Out Of Stock','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( 'show-single-product-highlight-labels', '=', 1 )
            ),
			array(
                'id'       => 'show-product-navigation',
                'type'     => 'switch',
                'title'    => esc_html__('Show Product Next/Previous Navigation','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-single-product-rating',
                'type'     => 'switch',
                'title'    => esc_html__('Show Product Rating','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-single-product-availability',
                'type'     => 'switch',
                'title'    => esc_html__('Show Product Availability','emallshop'),
				'desc'     => esc_html__('Show or hide stock in availability.','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-single-product-countdown',
                'type'     => 'switch',
                'title'    => esc_html__('Show Product CountDown','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'enable-size-guide',
                'type'     => 'switch',
                'title'    => esc_html__('Show Size Guide','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 0,
            ),
			array(
                'id'       => 'size-guide-image',
                'type'     => 'media',
                'url'      => true,
                'title'    => esc_html__('Choose Size Guide Image','emallshop'),
				'desc'     => esc_html__('You use global image size guide for all products','emallshop'),
                'compiler' => 'true',
                'default'  => array(),
				'required' => array( 'enable-size-guide', '=', 1 ),
            ),
			array(
                'id'       => 'show-single-productmetas',
                'type'     => 'switch',
                'title'    => esc_html__('Show Product Meta','emallshop'),
				'desc'     => esc_html__('Show or hide product brand, category, tag, etc...','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-specific-productmeta',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Show Specific Product Meta', 'emallshop' ),
				'multi'    => true,
                'options'  => array(
					'brand'=>esc_html__('Brand','emallshop'),
					'sku'  =>esc_html__('SKU','emallshop'),
					'cats' =>esc_html__('Category','emallshop'),
					'tags' =>esc_html__('Tag','emallshop'),					
				),
                'default'  => array('brand','sku','cats','tags'),
				'required' => array( 'show-single-productmetas', '=', 1 )
            ),			
			array(
                'id'       => 'show-related-products',
                'type'     => 'switch',
                'title'    => esc_html__('Show Related Products','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-upsell-products',
                'type'     => 'switch',
                'title'    => esc_html__('Show Up Sell Products','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-related-upsell-products',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Show Number Of Related/Up Sell Products', 'emallshop' ),
                'options'  => array('4'=>'4','6'=>'6','8'=>'8','10'=>'10','12'=>'12'),
                'default'  => '6',
            ),
			array(
                'id'       => 'related-upsell-products-per-row',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Select Related/Up Sell Products Show Per Row', 'emallshop' ),
                'options'  => array('3'=>'3','4'=>'4','5'=>'5 (In Full Width)','6'=>'6 (In Full Width)'),
                'default'  => '4',
            ),
			array(
                'id'       => 'related-upsell-auto-play',
                'type'     => 'switch',
                'title'    => esc_html__('Enable Carousel Autoplay Mode','emallshop'),
                'on'       => esc_html__('Yes','emallshop'),
				'off'      => esc_html__('No','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'related-upsell-loop',
                'type'     => 'switch',
                'title'    => esc_html__('Enables Carousel Inifnity Loop','emallshop'),
                'desc'     => esc_html__('Enables related/up sell products carousel Inifnity loop. Duplicate last and first products to get loop illusion.','emallshop'),
                'on'       => esc_html__('Yes','emallshop'),
				'off'      => esc_html__('No','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'related-upsell-navigation',
                'type'     => 'switch',
                'title'    => esc_html__('Show Carousel Navigation','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'related-upsell-product-dots',
                'type'     => 'switch',
                'title'    => esc_html__('Show Carousel Dots Navigation','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 0,
            ),			
		)
	) );
	
	/*
	* Free Shipping Bar		
	*/
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Free Shipping Bar', 'emallshop' ),
        'id'         => 'section-freeshipping',
		'subsection' => true,
        'fields'     => array(
			array(
                'id'       		=> 'free-shipping-bar',
                'type'     		=> 'switch',
                'title'    		=> esc_html__( 'Free Shipping Bar', 'emallshop' ),
				'subtitle' 		=> esc_html__( 'You want to enable free shipping bar or not?', 'emallshop' ),
                'on'       		=> esc_html__( 'Yes', 'emallshop' ),
				'off'      		=> esc_html__( 'No', 'emallshop' ),
				'default'  		=> 0,
            ),
			array(
				'id'      		=> 'free-shipping-amount',
				'type'    		=> 'text',
				'title'   		=> esc_html__( 'Enter Required Amount', 'emallshop' ), 
				'subtitle'   	=> wp_kses( sprintf( __( 'You can set frees hipping method amount from Woocommerce => settings => shipping => shipping zones => manage shipping method.For more read <a href="%s" target="_blank"> WooCommerce documentation </a> guide.', 'emallshop' ), esc_url( 'https://docs.woocommerce.com/document/free-shipping/' ) ),
				array(
						'a' => array(
							'href'   => array(),
							'target' => array(),
						),
					) 
				),     
				'default' 		=> '',								  
			),
			array(
				'id'      => 'free-shipping-msg',
				'type'    => 'textarea',
				'subtitle'    	=> esc_html__( 'Enter free shipping message text. Use {missing_amount} - The remaing amount for free shipping.', 'emallshop' ),
				'title'   => esc_html__( 'Free Shipping Message', 'emallshop' ),               
				'default' => 'Spend {missing_amount} to get <strong>free shipping</strong>',								  
			),
			array(
				'id'      => 'free-shipping-complete-msg',
				'type'    => 'textarea',
				'title'   => esc_html__( 'Free Shipping Success Message', 'emallshop' ), 
				'subtitle'    	=> esc_html__( 'Message show after reaching progress bar 100%.', 'emallshop' ),
				'default' => esc_html__( 'Congratulation! You have got free shipping', 'emallshop' ),	  
			),
			array(
				'id'      		=> 'shipping-bar-bg-color',
				'type'    		=> 'color',
				'title'   		=> esc_html__( 'Shipping Bar Background Color', 'emallshop' ),               
				'default' 		=> '#efefef',								  
			),
			array(
				'id'      		=> 'shipping-bar-color',
				'type'    		=> 'color',
				'title'   		=> esc_html__( 'Shipping Bar Color', 'emallshop' ),               
				'default' 		=> '#0ba2e8',								  
			),
		),
	) );
	
	/*
	* Cart Page
	*/
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Cart', 'emallshop' ),
        'id'         => 'section-cart-page',
		'subsection' => true,
        'fields'     => array(
			array(
                'id'       		=> 'cart-auto-update',
                'type'     		=> 'switch',
                'title'    		=> esc_html__( 'Auto Update Cart ', 'emallshop' ),
				'subtitle'    	=> esc_html__( 'Auto update cart when change product quantity.', 'emallshop' ),
                'on'       		=> esc_html__( 'Yes', 'emallshop' ),
                'off'      		=> esc_html__( 'No', 'emallshop' ),
                'default'  		=> 1,
            ),
		)
	) );
	
	/*
	* Checkout Page
	*/
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Checkout', 'emallshop' ),
        'id'         => 'section-checkout-page',
		'subsection' => true,
        'fields'     => array(
			array(
                'id'       		=> 'checkout-product-image',
                'type'     		=> 'switch',
                'title'    		=> esc_html__( 'Product Image', 'emallshop' ),
				'subtitle'    	=> esc_html__( 'Show product image on checkout page.', 'emallshop' ),
                'on'       		=> esc_html__( 'Yes', 'emallshop' ),
                'off'      		=> esc_html__( 'No', 'emallshop' ),
                'default'  		=> 1,
            ),
			array(
                'id'       		=> 'checkout-product-quantity',
                'type'     		=> 'switch',
                'title'    		=> esc_html__( 'Product Quantity Filed', 'emallshop' ),
				'subtitle'    	=> esc_html__( 'Show product quantity filed on checkout page.', 'emallshop' ),
                'on'       		=> esc_html__( 'Yes', 'emallshop' ),
                'off'      		=> esc_html__( 'No', 'emallshop' ),
                'default'  		=> 0,
            ),
		)
	) );
	
	/*
	* Dokan Options
	*/
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Dokan', 'emallshop' ),
        'id'         => 'dokan',
		'icon'		 => 'el el-shopping-cart',
		'fields'     => array(
			array(
                'id'       => 'dokan-store-page-layout',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Dokan Store Page Layout', 'emallshop' ),
                'desc'     => esc_html__( 'Select dokan store page layout with sidebar postion.', 'emallshop' ),
                'options'  => array(
                    'full-layout' => array(
                        'alt' => 'Full Width',
                        'img' => ReduxFramework::$_url . 'assets/img/1col.png'
                    ),                   
                    'left' => array(
                        'alt' => '2 Column Right',
                        'img' => ReduxFramework::$_url . 'assets/img/2cl.png'
                    ), 
					'right' => array(
                        'alt' => '2 Column Left',
                        'img' => ReduxFramework::$_url . 'assets/img/2cr.png'
                    ), 
                ),
                'default'  => 'left'
            ),
			array(
                'id'       => 'dokan-store-page-sidebar-widget',
                'type'     => 'select',
                'title'    => esc_html__('Select Sidebar Widget Area','emallshop'),
				'desc'     => esc_html__( 'Select widget area and display in sidebar on dokan store page.', 'emallshop' ),
                'data'     => 'sidebars',
                'default'  => 'dokan-widget-area',
                'required' => array( 'dokan-store-page-layout', '=', array( 'left', 'right' ) )
            ),
			array(
                'id'       => 'show-dokan-sold-by-label',
                'type'     => 'switch',
                'title'    => esc_html__('Show Sold By Label','emallshop'),
                'desc'     => esc_html__('Show sold by label or not on product listing page and single page.','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 0,
            ),
		)
	) );
	
	/*
	* WC Vendors Options
	*/
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'WC Vendors', 'emallshop' ),
        'id'         => 'wc-vendors',
		'icon'		 => 'el el-shopping-cart',
		'fields'     => array(
			array(
                'id'       => 'vendor-list-per-row',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Show Vendors Per Row', 'emallshop' ),
				'desc'     => esc_html__('Show vendors per row in vendor list page.','emallshop'),
                'options'  => array(
                    2 => '2',
                    3 => '3',
                    4 => '4',
                ),
                'default'  => 3,
            ),
			array(
                'id'       => 'show-wc-vendors-sold-by-label',
                'type'     => 'switch',
                'title'    => esc_html__('Show Sold By Label','emallshop'),
                'desc'     => esc_html__('Show sold by label or not on product.','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
		)
	) ); 
	
	/*
	* Page Options
	*/
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Page', 'emallshop' ),
        'id'         => 'page',
		'icon'		 => 'el el-blogger',
        'fields'     => array(
			array(
                'id'       => 'show-page-commnet',
                'type'     => 'switch',
                'title'    => esc_html__('Page Comment','emallshop'),
                'desc'     => esc_html__('Show page comments and comment form or not.','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
		)
	) );
	
	/*
	* Page Widget Options
	*/
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Sidebar Widget', 'emallshop' ),
        'id'         => 'page-widget',
        'subsection' => true,
        'fields'     => array(
			array(
                'id'       => 'enable-widget-toggle',
                'type'     => 'switch',
                'title'    => esc_html__('Enable Widget Toggle','emallshop'),
                'desc'     => esc_html__('Enable page widget toggle or not.','emallshop'),
                'on'       => esc_html__('Enable','emallshop'),
				'off'      => esc_html__('Disable','emallshop'),
				'default'  => 0,
            ),
			array(
                'id'       => 'enable-widget-menu-toggle',
                'type'     => 'switch',
                'title'    => esc_html__('Enable Widget Menu Toggle','emallshop'),
                'desc'     => esc_html__('Enable page widget menu toggle or not.','emallshop'),
                'on'       => esc_html__('Enable','emallshop'),
				'off'      => esc_html__('Disable','emallshop'),
				'default'  => 0,
            ),
			array(
                'id'       => 'widget-items-hide-max-limit',
                'type'     => 'switch',
                'title'    => esc_html__('Enable Widget Items Hide','emallshop'),
                'desc'     => esc_html__('Enable widget items hide max limit.','emallshop'),
                'on'       => esc_html__('Enable','emallshop'),
				'off'      => esc_html__('Disable','emallshop'),
				'default'  => 0,
            ),
			array(
                'id'            => 'number-of-show-widget-items',
                'type'          => 'slider',
                'title'         => esc_html__('Show Number Of Widget Items','emallshop'),
                'default'       => 8,
                'min'           => 5,
                'step'          => 1,
                'max'           => 50,
                'display_value' => 'text',
				'required' => array( 'widget-items-hide-max-limit', '=', 1 )
            ),
		)
	) );
	
	/*
	* Post options
	*/
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Blog', 'emallshop' ),
        'id'         => 'blog',
		'icon'		 => 'el el-blogger',
        'fields'     => array(
		
		)
	) );
	
	/*
	* Blog/Archives options
	*/
    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Blog/Archive Page', 'emallshop' ),
        'id'         => 'blog-archive',
		'subsection'	 => true,
        'fields'     => array(
			array(
                'id'       => 'blog-page-layout',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Select Blog Page Layout', 'emallshop' ),
                'desc' => esc_html__( 'Select blog/archive page layout with sidebar postion.', 'emallshop' ),
                'options'  => array(
                    'none' => array(
                        'alt' => 'Full Width',
                        'img' => ReduxFramework::$_url . 'assets/img/1col.png'
                    ),                   
                    'left' => array(
                        'alt' => '2 Column Left',
                        'img' => ReduxFramework::$_url . 'assets/img/2cl.png'
                    ), 
					'right' => array(
                        'alt' => '2 Column Right',
                        'img' => ReduxFramework::$_url . 'assets/img/2cr.png'
                    ), 
                ),
                'default'  => 'right'
            ),
			array(
                'id'       => 'blog-page-sidebar-widget',
                'type'     => 'select',
                'title'    => esc_html__('Select Sidebar Widget Area','emallshop'),
                'data'     => 'sidebars',
                'default'  => 'sidebar-1',
                'required' => array( 'blog-page-layout', '=', array( 'left', 'right' ) )
            ),
			array(
                'id'       => 'show-blog-page-breadcrumb',
                'type'     => 'switch',
                'title'    => esc_html__('Show Blog Page Breadcrumb','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-blog-page-title',
                'type'     => 'switch',
                'title'    => esc_html__('Show Blog Page Title','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'blog-page-style',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Select Blog Style', 'emallshop' ),
                'options'  => array('large_image'=>esc_html__('Large Image','emallshop'),'small_image'=>esc_html__('Small Image','emallshop'),'grid_column'=>esc_html__('Grid Column (Masonry Grid)','emallshop'),/* 'timeline'=>esc_html__('Timeline','emallshop') */ ),
                'default'  => 'large_image',
            ),
			array(
                'id'       => 'blog-page-show-column',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Select Blog Grid Columns', 'emallshop' ),
				'desc' => esc_html__( 'Show blog grid column of the post/blog page. Column option apply only on above masorny grid column option.', 'emallshop' ),
                'options'  => array('two'=>esc_html__('2 Columns','emallshop'),'three'=>esc_html__('3 Columns','emallshop'),'four'=>esc_html__('4 Columns (In Full Width)','emallshop')),
                'default'  => 'two',
				'required' => array( 'blog-page-style', '=', 'grid_column' )
            ),	
			array(
                'id'       => 'blog-page-show-column',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Select Blog Grid Columns', 'emallshop' ),
				'desc' => esc_html__( 'Show blog grid column of the post/blog page. Column option apply only on above masorny grid column option.', 'emallshop' ),
                'options'  => array('two'=>esc_html__('2 Columns','emallshop'),'three'=>esc_html__('3 Columns','emallshop'),'four'=>esc_html__('4 Columns (In Full Width)','emallshop')),
                'default'  => 'two',
				'required' => array( 'blog-page-style', '=', 'grid_column' )
            ),				
			array(
                'id'       => 'show-blogs-thumbnail',
                'type'     => 'switch',
                'title'    => esc_html__('Show Blogs Thumbnail','emallshop'),
                'desc'     => esc_html__('Show blogs thumbnail or not.','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-postdate',
                'type'     => 'switch',
                'title'    => esc_html__('Show Blogs Date','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-postmeta',
                'type'     => 'switch',
                'title'    => esc_html__('Show Blogs Meta','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-specific-postmeta',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Show Specific Post Meta', 'emallshop' ),
				'multi'    => true,
                'options'  => array(
					'post-format'=>esc_html__('Post Format','emallshop'),
					'post-author'=>esc_html__('Post Author','emallshop'),
					'cat-links'=>esc_html__('Post Cats','emallshop'),
					'tags-links'=>esc_html__('Post Tags','emallshop'),
					'comments-link'=>esc_html__('Comments','emallshop')
				),
                'default'  => array('post-format','post-author','cat-links','tags-links','comments-link'),
				'required' => array( 'show-postmeta', '=', 1 )
            ),
			
			array(
                'id'       => 'show-blog-excerpt',
                'type'     => 'switch',
                'title'    => esc_html__('Show Excerpt','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 0,
            ),
			array(
                'id'            => 'blog-excerpt-length',
                'type'          => 'slider',
                'title'         => esc_html__('Excerpt Length (words)','emallshop'),
                'desc'          => esc_html__('Show blogs listing content length (words).','emallshop'),
                'default'       => 75,
                'min'           => 50,
                'step'          => 1,
                'max'           => 100,
                'display_value' => 'text',
				'required' => array( 'show-blog-excerpt', '=', 1 )
            ),
			array(
                'id'       => 'show-post-readmore',
                'type'     => 'switch',
                'title'    => esc_html__('Show Read More Button','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 0,
            ),
			array(
                'id'       => 'post-readmore-text',
                'type'     => 'text',
                'title'    => esc_html__('Read More Button Text','emallshop'),
				'default'  => esc_html__('Read more','emallshop'),
				'required' => array( 'show-post-readmore', '=', 1 )
            ),
			array(
                'id'       => 'blogs-pagination-type',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Select Pagination Style Of Blog Page', 'emallshop' ),
                'options'  => array(
					'default_pagination'=>esc_html__('Default','emallshop'),
					'infinity_scroll'=>esc_html__('Infinity Scroll','emallshop'),
					'more_button'=>esc_html__('Load More Button','emallshop'),
					'pagination'=>esc_html__('AJAX Pagination','emallshop')
				),
                'default'  => 'default_pagination',
            ),
			array(
                'id'       => 'blog-load-more-button-text',
                'type'     => 'text',
                'title'    => esc_html__('Load More Button Text','emallshop'),
				'default'  => esc_html__('Load More','emallshop'),
				'required' => array( 'blogs-pagination-type', '=', 'more_button' )
            ),
			
		)
	) );
	
	/*
	* Single Post options
	*/
    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Single Post', 'emallshop' ),
        'id'         => 'single-post',
		'subsection'	 => true,
        'fields'     => array(
			array(
                'id'       => 'show-post-thumbnail',
                'type'     => 'switch',
                'title'    => esc_html__('Show Post Thumbnail','emallshop'),
                'desc'     => esc_html__('Show post thumbnail or not.','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-single-post-navigation',
                'type'     => 'switch',
                'title'    => esc_html__('Show Post Navigation','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-related-post',
                'type'     => 'switch',
                'title'    => esc_html__('Show Related Post','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'blog-per-row',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Show Post Per Row', 'emallshop' ),
				'desc'     => esc_html__( 'Show number of post per row.', 'emallshop' ),
                'options'  => array('2'=>'2','3'=>'3','4'=>'4 (In Full Width)'),
                'default'  => 2,
				'required' => array( 'show-related-post', '=', 1 )
            ),
			array(
                'id'       => 'blog-carousel-auto-play',
                'type'     => 'switch',
                'title'    => esc_html__('Enable Carousel Autoplay Mode','emallshop'),
                'on'       => esc_html__('Enable','emallshop'),
				'off'      => esc_html__('Disable','emallshop'),
				'default'  => 1,
				'required' => array( 'show-related-post', '=', 1 )
            ),
			array(
                'id'       => 'blog-carousel-navigation',
                'type'     => 'switch',
                'title'    => esc_html__('Show Carousel Navigation','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( 'show-related-post', '=', 1 )
            ),
			array(
                'id'       => 'blog-carousel-loop',
                'type'     => 'switch',
                'title'    => esc_html__('Enable Carousel loop','emallshop'),
                'desc'     => esc_html__('Enable blog carousel continue loop.','emallshop'),
                'on'       => esc_html__('Enable','emallshop'),
				'off'      => esc_html__('Disable','emallshop'),
				'default'  => 1,
				'required' => array( 'show-related-post', '=', 1 )
            ),
			array(
                'id'       => 'show-post-commnet',
                'type'     => 'switch',
                'title'    => esc_html__('Show Post Comment','emallshop'),
                'desc'     => esc_html__('Show post comments and comment form or not.','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),			
		)
	) );
	
	/*
	* Portfolio options
	*/
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Portfolio', 'emallshop' ),
        'id'         => 'portfolio',
		'icon'		 => 'el el-photo',
        'fields'     => array(
		
		)
	) );
	
	/*
	* Portfolio Archive Page options
	*/
    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Portfolio Archive', 'emallshop' ),
        'id'         => 'portfolio-archive',
		'subsection'	 => true,
        'fields'     => array(
			array(
                'id'       => 'portfolio-archive-page-layout',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Select Archive Page Layout', 'emallshop' ),
                'desc'     => esc_html__( 'Select portfolio archive page layout with sidebar postion.', 'emallshop' ),
                'options'  => array(
                    'none' => array(
                        'alt' => 'Full Width',
                        'img' => ReduxFramework::$_url . 'assets/img/1col.png'
                    ),                   
                    'left' => array(
                        'alt' => '2 Column Left',
                        'img' => ReduxFramework::$_url . 'assets/img/2cl.png'
                    ), 
					'right' => array(
                        'alt' => '2 Column Right',
                        'img' => ReduxFramework::$_url . 'assets/img/2cr.png'
                    ), 
                ),
                'default'  => 'right'
            ),
			array(
                'id'       => 'portfolio-archive-page-sidebar-widget',
                'type'     => 'select',
                'title'    => esc_html__('Select Sidebar Widget Area','emallshop'),
                'data'     => 'sidebars',
                'default'  => 'sidebar-1',
                'required' => array( 'portfolio-archive-page-layout', '=', array( 'left', 'right' ) )
            ),
			array(
                'id'       => 'show-portfolio-archive-page-Breadcrumb',
                'type'     => 'switch',
                'title'    => esc_html__('Show Page Breadcrumb','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-portfolio-archive-page-title',
                'type'     => 'switch',
                'title'    => esc_html__('Show Page Title','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'portfolio-archive-page-style',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Portfolio Listing Style', 'emallshop' ),
                'options'  => array(
								'portfolio_grid'=>esc_html__('Portfolio Grid','emallshop'),
								/*'timeline'=>esc_html__('Timeline','emallshop')*/
							),	
                'default'  => 'portfolio_grid',
            ),
			array(
                'id'       => 'portfolio-grid-style',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Portfolio Grid Style', 'emallshop' ),
                'options'  => array(
								'normal_grid'=>esc_html__('Normal Grid','emallshop'),
								'masonry_grid'=>esc_html__('Masonry Grid','emallshop')
							),
                'default'  => 'masonry_grid',
				'required' => array( 'portfolio-archive-page-style', '=', 'portfolio_grid' )
            ),
			array(
                'id'       => 'portfolio-grid-hover-effect',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Grid Hover Effect', 'emallshop' ),
				'desc'     => esc_html__( 'Select portfolio grid hover effect.', 'emallshop' ),
                'options'  => array(
								'default_effect'=>esc_html__('Default','emallshop'),
								'effect2'=>esc_html__('Effect 2','emallshop'),
								'effect3'=>esc_html__('Effect 3','emallshop'),
								'effect4'=>esc_html__('Effect 4','emallshop'),
								'effect5'=>esc_html__('Effect 5','emallshop')
							),	
                'default'  => 'default_effect',
				'required' => array( 'portfolio-archive-page-style', '=', 'portfolio_grid' )
            ),
			array(
                'id'       => 'portfolio-grid-column',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Select Portfolio Columns', 'emallshop' ),
                'options'  => array(
								'one'=>esc_html__('1 Column','emallshop'),
								'two'=>esc_html__('2 Columns','emallshop'),
								'three'=>esc_html__('3 Columns','emallshop'),
								'four'=>esc_html__('4 Columns','emallshop')
							),	
                'default'  => 'two',
				'required' => array( 'portfolio-archive-page-style', '=', 'portfolio_grid' )
            ),
			array(
                'id'            => 'portfolio-per-page',
                'type'          => 'slider',
                'title'         => esc_html__('Show Portfolio Per Page','emallshop'),
                'desc'          => esc_html__('Show number of portfolio per page.','emallshop'),
                'default'       => 10,
                'min'           => 4,
                'step'          => 1,
                'max'           => 50,
                'display_value' => 'text',
            ),
			array(
                'id'       => 'portfolio-pagination-type',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Pagination Type', 'emallshop' ),
                'options'  => array(
					'default_pagination'=>esc_html__('Default','emallshop'),
					'infinity_scroll'=>esc_html__('Infinity Scroll','emallshop'),
					'more_button'=>esc_html__('Load More Button','emallshop'),
					'pagination'=>esc_html__('AJAX Pagination','emallshop')
				),
                'default'  => 'default_pagination',
            ),
			array(
                'id'       => 'portfolio-load-more-button-text',
                'type'     => 'text',
                'title'    => esc_html__('Load More Button Text','emallshop'),
				'default'  => esc_html__('Load More','emallshop'),
				'required' => array( 'portfolio-pagination-type', '=', 'more_button' )
            ),			
		)
	) );
	
	/*
	* Single Portfolio options
	*/
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Single Portfolio', 'emallshop' ),
        'id'         => 'single-portfolio',
		'subsection'	 => true,
        'fields'     => array(
			array(
                'id'       => 'show-project-navigation',
                'type'     => 'switch',
                'title'    => esc_html__('Show Project Navigation','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-project-info',
                'type'     => 'switch',
                'title'    => esc_html__('Show Project Informations','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-project-share-link',
                'type'     => 'switch',
                'title'    => esc_html__('Show Social Share Links','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),			
			array(
                'id'       => 'show-related-projects',
                'type'     => 'switch',
                'title'    => esc_html__('Show Related Projects','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-related-Projects',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Show Number Of Projects', 'emallshop' ),
				'desc'    => esc_html__( 'Show number of related projects', 'emallshop' ),
                'options'  => array('4'=>'4','6'=>'6','8'=>'8','10'=>'10','12'=>'12'),
                'default'  => '6',
				'required' => array( 'show-related-projects', '=', 1 )
            ),
			array(
                'id'       => 'related-portfolio-per-row',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Show Projects Per Row', 'emallshop' ),
                'options'  => array('2'=>'2','3'=>'3','4'=>'4 (In Full Width)','5'=>'5 (In Full Width)'),
                'default'  => '3',
				'required' => array( 'show-related-projects', '=', 1 )
            ),
			array(
                'id'       => 'related-portfolio-auto-play',
                'type'     => 'switch',
                'title'    => esc_html__('Enable Carousel Autoplay Mode','emallshop'),
                'on'       => esc_html__('Yes','emallshop'),
				'off'      => esc_html__('No','emallshop'),
				'default'  => 1,
				'required' => array( 'show-related-projects', '=', 1 )
            ),
			array(
                'id'       => 'related-project-loop',
                'type'     => 'switch',
                'title'    => esc_html__('Enables Carousel Inifnity Loop','emallshop'),
                'desc'     => esc_html__('Enables portfolios carousel Inifnity loop. Duplicate last and first projects to get loop illusion.','emallshop'),
                'on'       => esc_html__('Yes','emallshop'),
				'off'      => esc_html__('No','emallshop'),
				'default'  => 1,
				'required' => array( 'show-related-projects', '=', 1 )
            ),
			array(
                'id'       => 'related-project-navigation',
                'type'     => 'switch',
                'title'    => esc_html__('Show Carousel Navigation','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( 'show-related-projects', '=', 1 )
            ),
			array(
                'id'       => 'related-project-dots',
                'type'     => 'switch',
                'title'    => esc_html__('Show Carousel Dots Navigation','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 0,
				'required' => array( 'show-related-projects', '=', 1 )
            ),
		)
	) );
	
	/*
	* Testimonial options
	*/
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Testimonial', 'emallshop' ),
        'id'         => 'testimonial',
		'icon'		 => 'el el-quotes',
        'fields'     => array(
		
		)
	) );
	
	/*
	* Testimonial Archive Page options
	*/
    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Testimonial Archive', 'emallshop' ),
        'id'         => 'testimonial-archive',
		'subsection' => true,		
        'fields'     => array(
			array(
                'id'       => 'testimonial-archive-page-layout',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Select Page Layout', 'emallshop' ),
                'desc' => esc_html__( 'Select page layout with sidebar postion.', 'emallshop' ),
                'options'  => array(
                    'none' => array(
                        'alt' => 'Full Width',
                        'img' => ReduxFramework::$_url . 'assets/img/1col.png'
                    ),                   
                    'left' => array(
                        'alt' => '2 Column Left',
                        'img' => ReduxFramework::$_url . 'assets/img/2cl.png'
                    ), 
					'right' => array(
                        'alt' => '2 Column Right',
                        'img' => ReduxFramework::$_url . 'assets/img/2cr.png'
                    ), 
                ),
                'default'  => 'right'
            ),
			array(
                'id'       => 'testimonial-archive-page-sidebar-widget',
                'type'     => 'select',
                'title'    => 'Select Sidebar Widget Area',
				'desc'     => esc_html__( 'Select widget display in sidebar on testimonial archive page.', 'emallshop' ),
                'data'     => 'sidebars',
                'default'  => 'sidebar-1',
                'required' => array( 'testimonial-archive-page-layout', '=', array( 'left', 'right' ) )
            ),
			array(
                'id'       => 'show-testimonial-archive-page-breadcrumb',
                'type'     => 'switch',
                'title'    => esc_html__('Show Page Breadcrumb','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-testimonialt-archive-page-title',
                'type'     => 'switch',
                'title'    => esc_html__('Show Page Title','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'testimonial-archive-page-show-column',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Select Testimonial Column', 'emallshop' ),
                'options'  => array(
								'two'=>esc_html__('2 Columns','emallshop'),
								'three'=>esc_html__('3 Columns','emallshop'),
								'four'=>esc_html__('4 Columns (In Full Width)','emallshop')
							),	
                'default'  => 'two',
            ),
		)
	) );
	
	/*
	* Single Testimonial options
	*/
    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Single Testimonial', 'emallshop' ),
        'id'         => 'single-testimonial',
		'subsection'	 => true,
        'fields'     => array(
			array(
                'id'       => 'testimonial-page-layout',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Select Page Layout', 'emallshop' ),
                'desc' => esc_html__( 'Select page layout with sidebar postion.', 'emallshop' ),
                'options'  => array(
                     'none' => array(
                        'alt' => 'Full Width',
                        'img' => ReduxFramework::$_url . 'assets/img/1col.png'
                    ),                   
                    'left' => array(
                        'alt' => '2 Column Left',
                        'img' => ReduxFramework::$_url . 'assets/img/2cl.png'
                    ), 
					'right' => array(
                        'alt' => '2 Column Right',
                        'img' => ReduxFramework::$_url . 'assets/img/2cr.png'
                    ), 
                ),
                'default'  => 'right'
            ),
			array(
                'id'       => 'testimonial-page-sidebar-widget',
                'type'     => 'select',
                'title'    => esc_html__('Select Sidebar Widget Area','emallshop'),
				'desc'     => esc_html__( 'Select widget display in sidebar on testimonial page.', 'emallshop' ),
                'data'     => 'sidebars',
                'default'  => 'sidebar-1',
                'required' => array( 'testimonial-page-layout', '=', array( 'left', 'right' ) )
            ),
			array(
                'id'       => 'show-testimonial-page-breadcrumb',
                'type'     => 'switch',
                'title'    => esc_html__('Show Page Breadcrumb','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'show-testimonialt-page-title',
                'type'     => 'switch',
                'title'    => esc_html__('Show Page Title','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
		)
	) );
	
	/*
	* 404 Page
	*/
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( '404 Page', 'emallshop' ),
        'id'         => '404-page',
		'icon'		 => 'el el-share',
        'fields'     => array(
			array(
                'id'       => '404-use-image-text',
                'type'     => 'button_set',
                'title'    => esc_html__('Use Text Or Image', 'emallshop'),
                'desc' => esc_html__('What a show on 404 page? Text or Image.','emallshop'),
                'options'  => array(
                    '404-text'     => 'Text',
                    '404-image' => 'Image',
                ),
                'default'  => '404-text'
            ),
			array(
                'id'       => '404-page-title',
                'type'     => 'textarea',
                'title'    => esc_html__('404 Page Title','emallshop'),
				'default'  => 'Oops! That page can&rsquo;t be found.',
				'required' => array( '404-use-image-text', '=', '404-text' )
            ),
			array(
                'id'       => 'show-previous-link',
                'type'     => 'switch',
                'title'    => esc_html__('Show Previous Page Link','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( '404-use-image-text', '=', '404-text' )
            ),
			array(
                'id'       => '404-page-tagline',
                'type'     => 'textarea',
                'title'    => esc_html__('404 Page Tag Line','emallshop'),
				'default'  => 'Try using the button below to go to back previous page.',
				'required' => array( 'show-previous-link', '=', 1 )
            ),
			array(
                'id'       => 'previous-link-title',
                'type'     => 'text',
                'title'    => esc_html__('Previous Page Link Title','emallshop'),
				'default'  => 'Go to Back',
				'required' => array( 'show-previous-link', '=', 1 )
            ),
			array(
                'id'       => '404-page-image',
                'type'     => 'media',
                'url'      => true,
                'title'    => esc_html__('404 Page Image','emallshop'),
                'compiler' => 'true',
                'desc' =>  esc_html__('Upload 404 page image and show on 404 page.','emallshop'),
                'default'  => array(),
				'required' => array( '404-use-image-text', '=', '404-image' )
            ),
		)
	) );
			
	/*
	* Social Share
	*/
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Social Link/Share', 'emallshop' ),
        'id'         => 'social-link',
		'icon'		 => 'el el-share',
        'fields'     => array(
			array(
                'id'    => 'social-link-info1',
                'type'   => 'info',
                'notice' => false,
                'title' => esc_html__( 'Show or Hide Social link Icon on Header And Footer', 'emallshop' ),
            ),
			array(
                'id'       => 'facebook_link',
                'type'     => 'text',
                'title'    => esc_html__('Facebook','emallshop'),
                'desc' => esc_html__('Enter your custom link to show the facebook icon. Leave blank to hide icon.','emallshop'),
				'default'  => 'https://www.facebook.com/',
            ),
			array(
                'id'       => 'twitter_link',
                'type'     => 'text',
                'title'    => esc_html__('Twitter','emallshop'),
                'desc'     => esc_html__('Enter your custom link to show the twitter icon. Leave blank to hide icon.','emallshop'),
				'default'  => 'http://twitter.com/',
            ),
			array(
                'id'       => 'instagram_link',
                'type'     => 'text',
                'title'    => esc_html__('Instagram','emallshop'),
                'desc'     => esc_html__('Enter your custom link to show the instagram icon. Leave blank to hide icon.','emallshop'),
				'default'  => 'https://www.instagram.com/',
            ),
			array(
                'id'       => 'linkedin_link',
                'type'     => 'text',
                'title'    => esc_html__('Linkedin','emallshop'),
                'desc'     => esc_html__('Enter your custom link to show the linkedin icon. Leave blank to hide icon.','emallshop'),
				'default'  => 'https://linkedin.com/',
            ),
			array(
                'id'       => 'flickr_link',
                'type'     => 'text',
                'title'    => esc_html__('Flickr','emallshop'),
                'desc'     => esc_html__('Enter your custom link to show the flickr icon. Leave blank to hide icon.','emallshop'),
				'default'  => 'https://www.flickr.com/',
            ),
			array(
                'id'       => 'youtube_link',
                'type'     => 'text',
                'title'    => esc_html__('Youtube','emallshop'),
                'desc'     => esc_html__('Enter your custom link to show the youtube icon. Leave blank to hide icon.','emallshop'),
				'default'  => 'https://www.youtube.com/',
            ),
			array(
                'id'       => 'rss_link',
                'type'     => 'text',
                'title'    => esc_html__('RSS','emallshop'),
                'desc'     => esc_html__('Enter your custom link to show the rss icon. Leave blank to hide icon.','emallshop'),
				'default'  => 'https://www.rss.com/',
            ),
			array(
                'id'       => 'pinterest_link',
                'type'     => 'text',
                'title'    => esc_html__('Pinterest','emallshop'),
                'desc'     => esc_html__('Enter your custom link to show the pinterest icon. Leave blank to hide icon.','emallshop'),
				'default'  => '',
            ),
			
			array(
                'id'       => 'github_link',
                'type'     => 'text',
                'title'    => esc_html__('Github','emallshop'),
                'desc'     => esc_html__('Enter your custom link to show the github icon. Leave blank to hide icon.','emallshop'),
				'default'  => '',
            ),			
			array(
                'id'       => 'whatsapp_link',
                'type'     => 'text',
                'title'    => esc_html__('WhatsApp','emallshop'),
                'desc'     => esc_html__('Enter your custom link to show the whatsapp icon. Leave blank to hide icon.','emallshop'),
				'default'  => '',
            ),
			
			array(
                'id'    => 'social-sharing-info1',
                'type'   => 'info',
                'notice' => false,
                'title' => esc_html__( 'Show or Hide Social Sharing Icon on Product, Post and Portfolio', 'emallshop' ),
            ),
			array(
                'id'       => 'show-social-sharing',
                'type'     => 'switch',
                'title'    => esc_html__('Show Social Share Links','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'social-sharing-style',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Social Sharing Style', 'emallshop' ),
                'options'  => array(
                    'style-1' => esc_html__('Style 1','emallshop'),
                    'style-2' => esc_html__('Style 2','emallshop'),
                ),
                'default'  => 'style-1',
				'required' => array( 'show-social-sharing', '=', 1 )
            ),
			array(
                'id'       => 'social-share-fb',
                'type'     => 'switch',
                'title'    => esc_html__('Facebook Icon','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( 'show-social-sharing', '=', 1 )
            ),
			array(
                'id'       => 'social-share-tw',
                'type'     => 'switch',
                'title'    => esc_html__('Twitter Icon','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( 'show-social-sharing', '=', 1 )
            ),
			array(
                'id'       => 'social-share-in',
                'type'     => 'switch',
                'title'    => esc_html__('Linkedin Icon','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( 'show-social-sharing', '=', 1 )
            ),
			array(
                'id'       => 'social-share-tg',
                'type'     => 'switch',
                'title'    => esc_html__('Telegram Icon','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( 'show-social-sharing', '=', 1 )
            ),
			array(
                'id'       => 'social-share-pr',
                'type'     => 'switch',
                'title'    => esc_html__('Pinterest Icon','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 1,
				'required' => array( 'show-social-sharing', '=', 1 )
            ),
			array(
                'id'       => 'social-share-sl',
                'type'     => 'switch',
                'title'    => esc_html__('StumbleUpon Icon','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 0,
				'required' => array( 'show-social-sharing', '=', 1 )
            ),
			array(
                'id'       => 'social-share-tl',
                'type'     => 'switch',
                'title'    => esc_html__('Tumblr Icon','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 0,
				'required' => array( 'show-social-sharing', '=', 1 )
            ),
			array(
                'id'       => 'social-share-ri',
                'type'     => 'switch',
                'title'    => esc_html__('Reddit Icon','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 0,
				'required' => array( 'show-social-sharing', '=', 1 )
            ),
			array(
                'id'       => 'social-share-vk',
                'type'     => 'switch',
                'title'    => esc_html__('VK Icon','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 0,
				'required' => array( 'show-social-sharing', '=', 1 )
            ),
			array(
                'id'       => 'social-share-ol',
                'type'     => 'switch',
                'title'    => esc_html__('Odnoklassniki Icon','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 0,
				'required' => array( 'show-social-sharing', '=', 1 )
            ),
			array(
                'id'       => 'social-share-pt',
                'type'     => 'switch',
                'title'    => esc_html__('Pocket Icon','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 0,
				'required' => array( 'show-social-sharing', '=', 1 )
            ),
			array(
                'id'       => 'social-share-wa',
                'type'     => 'switch',
                'title'    => esc_html__('WhatsApp Icon','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 0,
				'required' => array( 'show-social-sharing', '=', 1 )
            ),
			
			array(
                'id'       => 'social-share-em',
                'type'     => 'switch',
                'title'    => esc_html__('Email Icon','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 0,
				'required' => array( 'show-social-sharing', '=', 1 )
            ),			
			/* array(
                'id'       => 'social-share-prt',
                'type'     => 'switch',
                'title'    => esc_html__('Print Icon','emallshop'),
                'on'       => esc_html__('Show','emallshop'),
				'off'      => esc_html__('Hide','emallshop'),
				'default'  => 0,
				'required' => array( 'show-social-sharing', '=', 1 )
            ), */			
		)
	) );
	
	/*
	* Cookie Options
	*/
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Cookie Notice', 'emallshop' ),
        'id'         => 'cookie-notice',
		'icon'       => 'el el-dashboard',
        'fields'     => array(
			array(
                'id'       => 'cookie_enable',
                'type'     => 'switch',
                'title'    => esc_html__('Cookie Enable or Disable.','emallshop'),
                'on'       => esc_html__('Enable','emallshop'),
				'off'      => esc_html__('Disable','emallshop'),
				'desc'     => esc_html__('Cookie notice enable or disable in your site.','emallshop'),
				'default'  => 0,
            ),
			array(
                'id'       => 'cookie_title',
                'type'     => 'text',
                'title'    => 'Cookie Title',
                'desc'     => esc_html__('Enter the cookie title/name.','emallshop'),
				'default'  => esc_html__('Cookies Notice','emallshop'),
            ),
			array(
                'id'       => 'cookie_message_text',
                'type'     => 'textarea',
                'title'    => esc_html__('Message','emallshop'),
				'desc'     => esc_html__('Enter the cookie notice message.','emallshop'),
				'default'  => esc_html__('We use cookies to ensure that we give you the best experience on our website. If you continue to use this site we will assume that you are happy with it.','emallshop'),
            ),
			array(
                'id'       => 'cookie_accept_text',
                'type'     => 'text',
                'title'    => esc_html__('Button text','emallshop'),
                'desc'     => esc_html__('The text of the option to accept the usage of the cookies and make the notification disappear.','emallshop'),
				'default'  => esc_html__('Yes, I\'m Accept','emallshop'),
            ),
			array(
                'id'       => 'cookie_see_more_opt',
                'type'     => 'switch',
                'title'    => esc_html__('More info link','emallshop'),
                'on'       => esc_html__('Enable','emallshop'),
				'off'      => esc_html__('Disable','emallshop'),
				'desc'     => esc_html__('Enable Read more link.','emallshop'),
				'default'  => 0,
            ),
			array(
                'id'       => 'cookie_see_more_text',
                'type'     => 'text',
                'title'    => '',
                'desc'     => esc_html__('The text of the more info button.','emallshop'),
				'default'  => esc_html__('Read more','emallshop'),
				'required' => array( 'cookie_see_more_opt', '=', 1 ),
            ),
			array(
                'id'       => 'cookie_see_more_link_type',
                'type'     => 'radio',
                'title'    => '',
                'desc'     => esc_html__('Select where to redirect user for more information about cookies.','emallshop'),
                'options'  => array(
								'custom' 	 => esc_html__('Custom link','emallshop'),
								'page' => esc_html__('Page link','emallshop'),
							),
				'default'  => 'custom',
				'required' => array( 'cookie_see_more_opt', '=', 1 ),
            ),
			array(
                'id'       => 'cookie_see_more_link_custom',
                'type'     => 'text',
                'title'    => '',
                'desc'     => esc_html__('Enter the full URL starting with http://','emallshop'),
				'default'  => 'http://empty',
				'placeholder' => 'http://#',
				'required' => array( 'cookie_see_more_link_type', '=', 'custom' ),
            ),
			array(
                'id'       => 'cookie_see_more_link_pages',
                'type'     => 'select',
                'data'     => 'pages',
                'title'    => '',
                'desc'     => esc_html__( 'Select from one of your site\'s pages', 'emallshop' ),
				'required' => array( 'cookie_see_more_link_type', '=', 'page' ),
            ),
			array(
                'id'       => 'cookie_see_more_link_target',
                'type'     => 'select',
                'title'    => esc_html__( 'Link target', 'emallshop' ),
                'desc'     => esc_html__( 'Select the link target for more info page.', 'emallshop' ),
                'options'  => array(
                    '_blank' => '_blank',
                    '_self' => '_self',
                ),
                'default'  => '_blank',
            ),
			array(
                'id'       => 'cookie_refuse_opt',
                'type'     => 'switch',
                'title'    => esc_html__('Refuse button','emallshop'),
                'on'       => esc_html__('Enable','emallshop'),
				'off'      => esc_html__('Disable','emallshop'),
				'desc'     => esc_html__('Give to the user the possibility to refuse third party non functional cookies.','emallshop'),
				'default'  => 0,
            ),
			array(
                'id'       => 'cookie_refuse_text',
                'type'     => 'text',
                'title'    => '',
                'desc'     => esc_html__('The text of the option to refuse the usage of the cookies. To get the cookie notice status use emallshop_cn_cookies_accepted() function.','emallshop'),
				'default'  => esc_html__('No','emallshop'),
				'required' => array( 'cookie_refuse_opt', '=', 1 ),
            ),
			array(
                'id'       => 'cookie_refuse_code',
                'type'     => 'textarea',
                'title'    => '',
				'desc'     => esc_html__('Enter non functional cookies Javascript code here (for e.g. Google Analitycs). It will be used after cookies are accepted.','emallshop'),
				'required' => array( 'cookie_refuse_opt', '=', 1 ),
				
            ),
			array(
                'id'       => 'cookie_on_scroll',
                'type'     => 'switch',
                'title'    => esc_html__('On scroll','emallshop'),
                'on'       => esc_html__('Enable','emallshop'),
				'off'      => esc_html__('Disable','emallshop'),
				'desc'     => esc_html__('Enable cookie notice acceptance when users scroll.','emallshop'),
				'default'  => 0,
            ),
			array(
                'id'       => 'cookie_on_scroll_offset',
                'type'     => 'text',
                'title'    => '',
                'desc'     => esc_html__('Number of pixels user has to scroll to accept the usage of the cookies and make the notification disappear.','emallshop'),
				'default'  => 100,
				'required' => array( 'cookie_on_scroll', '=', 1 ),
            ),
			array(
                'id'       => 'cookie_expiry_times',
                'type'     => 'select',
                'title'    => esc_html__( 'Cookie expiry', 'emallshop' ),
                'desc'     => esc_html__( 'Select the link target for more info page.', 'emallshop' ),
                'options'  => array(
					'86400'	 	=> esc_html__( '1 day', 'emallshop' ),
					'604800'	=> esc_html__( '1 week', 'emallshop' ),
					'2592000'	=> esc_html__( '1 month', 'emallshop' ),
					'7862400'	=> esc_html__( '3 months', 'emallshop' ),
					'15811200'	=> esc_html__( '6 months', 'emallshop' ),
					'31536000'	=> esc_html__( '1 year', 'emallshop' ),
					'31337313373' => esc_html__( 'infinity', 'emallshop' ),
                ),
                'default'  => '2592000',
            ),
			array(
                'id'       => 'cookie_script_placements',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Script placement', 'emallshop' ),
                'desc'     => esc_html__( 'Select where all the plugin scripts should be placed.', 'emallshop' ),
                'options'  => array(
                    'header' => esc_html__('Header','emallshop'),
                    'footer' => esc_html__('Footer','emallshop'),
                ),
                'default'  => 'footer',
            ),
			array(
                'id'       => 'cookie_positions',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Position', 'emallshop' ),
                'desc'     => esc_html__( 'Select location for your cookie notice.', 'emallshop' ),
                'options'  => array(
                    'top' 		=> esc_html__('Top','emallshop'),
                    'bottom' 	=> esc_html__('Bottom','emallshop'),
                ),
                'default'  => 'bottom'
            ),
			array(
                'id'       => 'cookie_style',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Cookie Style', 'emallshop' ),
                'desc'     => esc_html__( 'Select style of cookie notice on bottom.', 'emallshop' ),
                'options'  => array(
                    'bar' 		=> esc_html__('Bar','emallshop'),
                    'box' 	=> esc_html__('Box','emallshop'),
                ),
                'default'  => 'box',
				'required' => array( 'cookie_positions', '=', 'bottom' ),
            ),
			array(
                'id'       => 'cookie_text_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Text Color', 'emallshop' ),
                'default'  => '#212121',
            ),
			array(
                'id'       => 'cookie_background_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Bar Background Color', 'emallshop' ),
                'default'  => '#fcfcfc',
            ),
		)
	) );
	
	/*
	* Newsletter Options
	*/
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Newsletter', 'emallshop' ),
        'id'         => 'newsletter',
		'icon'       => 'el el-envelope',
        'fields'     => array(
			array(
                'id'       => 'newsletter-enable',
                'type'     => 'switch',
                'title'    => esc_html__('Newsletter Enable or Disable.','emallshop'),
                'on'       => esc_html__('Enable','emallshop'),
				'off'      => esc_html__('Disable','emallshop'),
				'desc'     => esc_html__('Newsletter popup enable or disable in your site.','emallshop'),
				'default'  => 0,
            ),
			array(
                'id'       => 'newsletter-logo',
                'type'     => 'media',
                'url'      => true,
                'title'    => esc_html__('Newsletter Logo','emallshop'),
                'compiler' => 'true',
                'desc' =>  esc_html__('Upload newsletter logo.','emallshop'),
                'default'  => array(),
				'required' => array( 'newsletter-enable', '=', 1 ),
            ),
			array(
                'id'       => 'newsletter-title',
                'type'     => 'text',
                'title'    => esc_html__('Newsletter Title','emallshop'),
				'default'  => esc_html__('Join Us Now!','emallshop'),
				'required' => array( 'newsletter-enable', '=', 1 ),
            ),
			array(
                'id'       => 'newsletter-tag-line',
                'type'     => 'text',
                'title'    => esc_html__('Newsletter Tag Line','emallshop'),
				'default'  => esc_html__('Signup today for free and be the first to hear of special promotions, new arrivals and designer news.','emallshop'),
				'required' => array( 'newsletter-enable', '=', 1 ),
            ),
			array(
                'id'       => 'newsletter-dont-show',
                'type'     => 'text',
                'title'    => esc_html__('Newsletter Don\'t Show Msg', 'emallshop'),
				'default'  => esc_html__('Don\'t show this popup again','emallshop'),
				'required' => array( 'newsletter-enable', '=', 1 ),
            ),
			array(
                'id'       => 'newsletter-background',
                'type'     => 'background',
                'title'    => esc_html__('Background Color','emallshop'),
                'desc'     => esc_html__( 'Newsletter background with image, color, etc.', 'emallshop' ),
				'output'   => array( '.newsletter-content.modal-content' ),
                'default'  => array(
								'background-color' => '#0ba2e8',
								'background-image' 		=> '',
								'background-repeat' 	=> '',
								'background-size' 		=> '',
								'background-attachment' => '',
								'background-position' 	=> '',
							),
				'required' => array( 'newsletter-enable', '=', 1 ),
            ),
			array(
                'id'       => 'newsletter-color',
                'type'     => 'color',
                'title'    => esc_html__( 'Newsletter Color', 'emallshop' ),
                'default'  => '#ffffff',
				'required' => array( 'newsletter-enable', '=', 1 ),
            ),
			array(
                'id'       => 'newsletter-button-color',
                'type'     => 'color',
                'title'    => esc_html__( 'Newsletter Button Color', 'emallshop' ),
                'default'  => '#FF8400',
				'required' => array( 'newsletter-enable', '=', 1 ),
            ),
		)
	) );
	
	/*
	* Advanced Options
	*/
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Advanced', 'emallshop' ),
        'id'         => 'advanced',
		'icon'       => 'el el-cogs',
        'fields'     => array(
			array(
                'id'       => 'site-loader',
                'type'     => 'switch',
                'title'    => esc_html__('Site Loader Enable or Disable.','emallshop'),
                'on'       => esc_html__('Enable','emallshop'),
				'off'      => esc_html__('Disable','emallshop'),
				'desc'     => esc_html__('Site loader enable or disable in your site.','emallshop'),
				'default'  => 0,
            ),
			array(
                'id'       => 'site-loading-image',
                'type'     => 'button_set',
                'title'    => esc_html__('Site Loading Image', 'emallshop'),
                'desc' => esc_html__('Choose one of the pre-defined Loading Image or choose custom one.','emallshop'),
                'options'  => array(
                    'pre-defined'     => 'Pre-defined',
                    'custom' => 'Custom',
                ),
				'default'  => 'pre-defined',
				'required' => array( 'site-loader', '=', 1 ),
            ),
			array(
                'id'       => 'site-loader-img',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Site Loader Image', 'emallshop' ),
                'options'  => array(
                    'loader' => array(
                        'alt' => 'Loader',
                        'img' => EMALLSHOP_IMAGES.'/site-loader.gif'
                    ),
					'loader2' => array(
                        'alt' => 'Loader',
                        'img' => EMALLSHOP_IMAGES.'/site-loader2.gif'
                    ),
                ),
                'default'  => 'loader',
				'required' => array( 'site-loading-image', '=', 'pre-defined' ),
            ),		
			array(
                'id'       => 'custom-loader-img',
                'type'     => 'media',
                'url'      => true,
                'title'    => esc_html__('Choose your Loading Image','emallshop'),
                'compiler' => 'true',
                'desc' =>  esc_html__('Choose your own loading image.','emallshop'),
                'default'  => array(),
				'required' => array( 'site-loading-image', '=', 'custom' ),
            ),
			array(
                'id'       => 'pagination-loading-image',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Pagination Loading Image', 'emallshop' ),
                'options'  => array(
                    'loader' => array(
                        'alt' => 'Loader',
                        'img' => EMALLSHOP_ADMIN_IMAGES.'/ajax-loader.gif'
                    ),                   
                    'loader2' => array(
                        'alt' => 'Loader 2',
                        'img' => EMALLSHOP_ADMIN_IMAGES.'/ajax-loader2.gif'
                    ), 
					'loader3' => array(
                        'alt' => 'Loader 3',
                        'img' => EMALLSHOP_ADMIN_IMAGES.'/ajax-loader3.gif'
                    ), 
					'loader4' => array(
                        'alt' => 'Loader 4',
                        'img' => EMALLSHOP_ADMIN_IMAGES.'/ajax-loader4.gif'
                    ),
					'loader5' => array(
                        'alt' => 'Loader 5',
                        'img' => EMALLSHOP_ADMIN_IMAGES.'/ajax-loader5.gif'
                    ),
                ),
                'default'  => 'loader'
            ),
			array(
                'id'       => 'banner-hover-effect',
                'type'     => 'switch',
                'title'    => esc_html__('Single Image Hover Effect.','emallshop'),
				'desc'     => esc_html__('Enable or disable single image banner hover effect on home page.','emallshop'),
                'on'       => esc_html__('Enable','emallshop'),
				'off'      => esc_html__('Disable','emallshop'),
				'default'  => 1,
            ),
			array(
                'id'       => 'touch-slider',
                'type'     => 'switch',
                'title'    => esc_html__('Touch Slider Desktop','emallshop'),
                'on'       => esc_html__('Enable','emallshop'),
				'off'      => esc_html__('Disable','emallshop'),
				'default'  => 0,
            ),
			array(
                'id'       => 'touch-slider-mobile',
                'type'     => 'switch',
                'title'    => esc_html__('Touch Slider In Mobile','emallshop'),
                'on'       => esc_html__('Enable','emallshop'),
				'off'      => esc_html__('Disable','emallshop'),
				'default'  => 0,
            ),
			
		)
	) );
	
	/*
	* Custom CSS/JS Code
	*/
	Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Custom Code', 'emallshop' ),
        'id'         => 'custom-code',
		'icon'		 => 'el el-edit',
        'fields'     => array(
			array(
                'id'       => 'custom-css',
                'type'     => 'ace_editor',
                'title'    => esc_html__( 'CSS Code', 'emallshop' ),
                'subtitle' => esc_html__( 'Paste your CSS code here.', 'emallshop' ),
                'mode'     => 'css',
                'theme'    => 'monokai',
                'default'  => ""
            ),
            array(
                'id'       => 'custom_js',
                'type'     => 'ace_editor',
                'title'    => esc_html__( 'JS Code', 'emallshop' ),
                'subtitle' => esc_html__( 'Paste your JS code here.', 'emallshop' ),
                'mode'     => 'javascript',
                'theme'    => 'chrome',
                'default'  => "jQuery(document).ready(function(){\n\n});"
            ),		
		)
	) );
	
	
    /*
     * <--- END SECTIONS
     */


    /*
     *
     * YOU MUST PREFIX THE FUNCTIONS BELOW AND ACTION FUNCTION CALLS OR ANY OTHER CONFIG MAY OVERRIDE YOUR CODE.
     *
     */

      /**
     * This is a test function that will let you see when the compiler hook occurs.
     * It only runs if a field    set with compiler=>true is changed.
     * */
    if ( ! function_exists( 'compiler_action' ) ) {
        function compiler_action( $options, $css, $changed_values ) {
            echo '<h1>The compiler hook has run!</h1>';
            echo "<pre>";
            print_r( $changed_values ); // Values that have changed since the last save
            echo "</pre>";
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )
        }
    }

    /**
     * Custom function for the callback validation referenced above
     * */
    if ( ! function_exists( 'redux_validate_callback_function' ) ) {
        function redux_validate_callback_function( $field, $value, $existing_value ) {
            $error   = false;
            $warning = false;

            //do your validation
            if ( $value == 1 ) {
                $error = true;
                $value = $existing_value;
            } elseif ( $value == 2 ) {
                $warning = true;
                $value   = $existing_value;
            }

            $return['value'] = $value;

            if ( $error == true ) {
                $field['msg']    = 'your custom error message';
                $return['error'] = $field;
            }

            if ( $warning == true ) {
                $field['msg']      = 'your custom warning message';
                $return['warning'] = $field;
            }

            return $return;
        }
    }

    /**
     * Custom function for the callback referenced above
     */
    if ( ! function_exists( 'redux_my_custom_field' ) ) {
        function redux_my_custom_field( $field, $value ) {
            print_r( $field );
            echo '<br/>';
            print_r( $value );
        }
    }

    /**
     * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
     * Simply include this function in the child themes functions.php file.
     * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
     * so you must use get_template_directory_uri() if you want to use any of the built in icons
     * */
    if ( ! function_exists( 'dynamic_section' ) ) {
        function dynamic_section( $sections ) {
            //$sections = array();
            $sections[] = array(
                'title'  => esc_html__( 'Section via hook', 'emallshop' ),
                'desc'   => esc_html__( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'emallshop' ),
                'icon'   => 'el el-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }
    }

    /**
     * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
     * */
    if ( ! function_exists( 'change_arguments' ) ) {
        function change_arguments( $args ) {
            //$args['dev_mode'] = true;

            return $args;
        }
    }

    /**
     * Filter hook for filtering the default value of any given field. Very useful in development mode.
     * */
    if ( ! function_exists( 'change_defaults' ) ) {
        function change_defaults( $defaults ) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }
    }
<?php
/*
*
*	Meta Box Functions
*	------------------------------------------------
*	EmallShop Framework
*
*/
global $meta_boxes;

/********************* META BOX REGISTERING ***********************/

/**
 * Register meta boxes
 *
 * @return void
 */
function emallshop_register_meta_boxes(){
	
	/**
	* Check Theme Activation
	*/
	if (! emallshop_is_activated()) return;

	global $meta_boxes;
	$prefix = 'es_';

// POST FORMAT: Image
//--------------------------------------------------
	$meta_boxes[] = array(
		'title' 		=> esc_html__('Post Format: Image', 'emallshop'),
		'id' 			=> $prefix .'meta_box_post_format_image',
		'post_types' 	=> array('post'),
		'priority' 		=> 'high',
		'fields' 		=> array(
								array(
									'name' => esc_html__('Image', 'emallshop'),
									'id' => $prefix . 'post_format_image',
									'type' => 'image_advanced',
									'max_file_uploads' => 1,
									'desc' => esc_html__('Select a image for post','emallshop')
								),
						),
	);

// POST FORMAT: Gallery
//--------------------------------------------------
	$meta_boxes[] = array(
		'title' 		=> esc_html__('Post Format: Gallery', 'emallshop'),
		'id' 			=> $prefix . 'meta_box_post_format_gallery',
		'post_types' 	=> array('post'),
		'priority' 		=> 'high',
		'fields' 		=> array(
								array(
									'name' => esc_html__('Images', 'emallshop'),
									'id' => $prefix . 'post_format_gallery',
									'type' => 'image_advanced',
									'desc' => esc_html__('Select images gallery for post','emallshop')
								),
						),
	);

// POST FORMAT: Video
//--------------------------------------------------
	$meta_boxes[] = array(
		'title' 		=> esc_html__('Post Format: Video', 'emallshop'),
		'id' 			=> $prefix . 'meta_box_post_format_video',
		'post_types'	=> array('post'),
		'priority' 		=> 'high',
		'fields' 		=> array(
								array(
									'name' => esc_html__( 'Video URL or Embeded Code', 'emallshop' ),
									'id'   => $prefix . 'post_format_video',
									'type' => 'textarea',
									'desc' => esc_html__('Enter the URL or embed code of Vimeo.com or YouTube.com streaming services.<br>To get the code, go to the external video page, click "share" button and copy the Embed code.','emallshop')
								),
						),
	);

// POST FORMAT: Audio
//--------------------------------------------------
	$meta_boxes[] = array(
		'title' 		=> esc_html__('Post Format: Audio', 'emallshop'),
		'id' 			=> $prefix . 'meta_box_post_format_audio',
		'post_types' 	=> array('post'),
		'priority' 		=> 'high',
		'fields' 		=> array(
								array(
									'name' => esc_html__( 'Audio URL or Embeded Code', 'emallshop' ),
									'id'   => $prefix . 'post_format_audio',
									'type' => 'textarea',
									'desc' => esc_html__('Please enter the URL or Embeded code of the audio.','emallshop')
								),
						),
	);

// POST FORMAT: QUOTE
//--------------------------------------------------
    $meta_boxes[] = array(
        'title' => esc_html__('Post Format: Quote', 'emallshop'),
        'id' 	=> $prefix . 'meta_box_post_format_quote',
        'post_types' => array('post'),
		'priority' 	=> 'high',
        'fields' 	=> array(
							array(
								'name' => esc_html__( 'Quote', 'emallshop' ),
								'id'   => $prefix . 'post_format_quote',
								'type' => 'textarea',
								'desc' => esc_html__('Add a quote.','emallshop')
							),
							array(
								'name' => esc_html__( 'Author', 'emallshop' ),
								'id'   => $prefix . 'post_format_quote_author',
								'type' => 'text',
								'desc' => esc_html__('Add the quote author.','emallshop')
							),
							array(
								'name' => esc_html__( 'Author Url', 'emallshop' ),
								'id'   => $prefix . 'post_format_quote_author_url',
								'type' => 'url',
								'desc' => esc_html__('Add the quote author URL.','emallshop')
							),
					),
    );
    
// POST FORMAT: LINK
//--------------------------------------------------
$meta_boxes[] = array(
	'title' => esc_html__('Post Format: Link', 'emallshop'),
	'id' 	=> $prefix . 'meta_box_post_format_link',
	'post_types' => array('post'),
	'priority' 	=> 'high',
	'fields' 	=> array(
						array(
							'name' => esc_html__( 'Url', 'emallshop' ),
							'id'   => $prefix . 'post_format_link_url',
							'type' => 'url',
							'desc' => esc_html__('Add an URL link.','emallshop')
						),
						array(
							'name' => esc_html__( 'Text', 'emallshop' ),
							'id'   => $prefix . 'post_format_link_text',
							'type' => 'text',
							'desc' => esc_html__('Add text for the URL link.','emallshop')
						),
				),
);

// PORTFOLIO METABOX
//--------------------------------------------------
$meta_boxes[] = array(
	'title' => esc_html__('Portfolio Informations', 'emallshop'),
	'id' 	=> $prefix .'portfolio_informations',
	'post_types' => array('portfolio'),
	'priority' 	=> 'default',
	'priority' 	=> 'high',
	'fields' 	=> array(
						array(
							'name' => esc_html__( 'Enter Client Name', 'emallshop' ),
							'id'   => $prefix .'portfolio_client_name',
							'type' => 'text',
							'desc' => esc_html__('Enter your project client name.','emallshop')
						),
						array(
							'name' => esc_html__( 'Enter Live Project URL', 'emallshop' ),
							'id'   => $prefix .'portfolio_project_url',
							'type' => 'text',
							'desc' => esc_html__('Enter your live project url.','emallshop')
						),
				),
);

// TESTIMONIAL METABOX
//--------------------------------------------------
$meta_boxes[] = array(
	'title' => esc_html__('Testimonial Informations', 'emallshop'),
	'id' 	=> $prefix .'testimonial_informations',
	'post_types'=> array('testimonial'),
	'priority' 	=> 'default',
	'priority' 	=> 'high',
	'fields' 	=> array(
						array(
							'name' => esc_html__('Enter Designation','emallshop'),
							'id' => $prefix .'client_designation',
							'type' => 'text',
							'desc' => esc_html__('Enter client designation.','emallshop'),
						),
						array(
							'name' => esc_html__('Enter Company Name','emallshop'),
							'id' => $prefix .'client_company',
							'type' => 'text',
							'desc' => esc_html__('Enter client company name.','emallshop'),
						),
				),
);

// PAGE, POST, PORTFOLIO PAGE OPTIONS 
//--------------------------------------------------
$meta_boxes[] = array(
	'title' 		=> esc_html__('Page Layout Options', 'emallshop'),
	'id' 			=> 'page_options',
	'post_types' 	=> array('page', 'post','portfolio'),
	'priority' 		=> 'high',
	'fields' 		=> array(
					array(
						'name' 			=> esc_html__( 'Show Title', 'emallshop' ),
						'id'   			=> '_emallshop_show_title',
						'type' 			=> 'select_advanced',
						'desc' 			=> esc_html__('Show title of the page.','emallshop'),
						'options' 		=> array(
												'yes'		=> esc_html__( 'Yes', 'emallshop' ),
												'no'		=> esc_html__( 'No', 'emallshop' ),
											),
						'std' 			=> 'yes',
					),
					
					array(
						'name' 			=> esc_html__( 'Show Breadcrumb', 'emallshop' ),
						'id'   			=> '_emallshop_show_breadsrumb',
						'type' 			=> 'select_advanced',
						'desc' 			=> esc_html__('Show breadcrumb of the page.','emallshop'),
						'options' 		=> array(
												'yes'		=> esc_html__( 'Yes', 'emallshop' ),
												'no'		=> esc_html__( 'No', 'emallshop' ),
											),
						'std' 			=> 'yes',
					),
					
					array(
						'name'  		=> esc_html__( 'Page Sidebar', 'emallshop' ),
						'id'    		=> '_emallshop_sidebar_position',
						'type'  		=> 'image_set',
						//'allowClear' 	=> true,
						'options' 		=> array(
							'none'	  => EMALLSHOP_IMAGES.'/sidebar-none.png',
							'left'	  => EMALLSHOP_IMAGES.'/sidebar-left.png',
							'right'	  => EMALLSHOP_IMAGES.'/sidebar-right.png',
							//'both'    => EMALLSHOP_IMAGES.'/sidebar-both.png'
						),
						'std'			=> 'right',
						'multiple' 		=> false,
						'required' 		=> true,

					),
					
					array (
						'name' 			=> esc_html__('Sidebar Widget', 'emallshop'),
						'id' 			=> '_emallshop_sidebar_widget',
						'type' 				=> 'sidebar',
						'field_type'  		=> 'select_advanced',
						'placeholder' 	=> esc_html__('Select Sidebar','emallshop'),
						'std' 			=> 'sidebar-1',
						'required' 		=> true,
						//'required-field' => array($prefix . 'top_drawer_type','<>','none'),
						'required-field' => array('_emallshop_sidebar_position','=',array('','left','right')),
					),
			),
);

/* PRODUCT PAGE OPTIONS 
 * EmallShop 2.0
 *--------------------------------------------------*/
$meta_boxes[] = array(
	'title' 		=> esc_html__('Product Page Layout Options', 'emallshop'),
	'id' 			=> 'emallshop_product_page_options',
	'post_types' 	=> 'product',
	'context'   	=> 'normal',
	'priority'  	=> 'high',
	'fields' 		=> array(
					
					array(
						'name'  		=> esc_html__( 'Product Page Layout', 'emallshop' ),
						'id'    		=> '_emallshop_product_layout',
						'type'  		=> 'image_set',
						'allowClear' 	=> true,
						'options' 		=> array(
												'none-left'	  	=> EMALLSHOP_ADMIN_IMAGES.'/thumbnail/none_left.png',
												'none-right'	=> EMALLSHOP_ADMIN_IMAGES.'/thumbnail/none_right.png',
												'full-layout'	=> EMALLSHOP_ADMIN_IMAGES.'/thumbnail/none.png',
												'right'	  		=> EMALLSHOP_ADMIN_IMAGES.'/thumbnail/right.png',
												'left'	  		=> EMALLSHOP_ADMIN_IMAGES.'/thumbnail/left.png'	
											),
						//'std'			=> 'right',
						'multiple' 		=> false,
						//'required' 	=> true,
						'desc' 			=> sprintf( __( 'Choose product page layout for only this product. You can choose  global for all product page layout at <a target="_blank" href="%1$s">here</a> (WooCommerce > Single Product section).', 'emallshop' ), esc_url( admin_url( 'themes.php?page=EmallShop' ) ) ),

					),					
					array (
						'name' 			=> esc_html__('Sidebar Widget', 'emallshop'),
						'id' 			=> '_emallshop_product_sidebar_widget',
						'type' 			=> 'sidebars',
						'placeholder' 	=> esc_html__('Select Sidebar','emallshop'),
						//'std' 		=> 'sidebar-1',
						//'required' 	=> true,
						'required-field'=> array('_emallshop_product_layout','=',array('','left','right')),
					),
					array(
						'name' 			=> esc_html__('Product Size Guide Image', 'emallshop'),
						'id' 			=> '_emallshop_product_size_guide',
						'type' 			=> 'image_upload',
						'max_file_uploads' => 1,
						'desc' 			=> sprintf( __( 'Upload product size guide image for only this product. You can use image size guide for all product at <a target="_blank" href="%1$s">here</a> (WooCommerce > Single Product section).', 'emallshop' ), esc_url( admin_url( 'themes.php?page=EmallShop' ) ) )
					),
			),
);
	
	// Make sure there's no errors when the plugin is deactivated or during upgrade
	if (class_exists('RW_Meta_Box')) {
		foreach ($meta_boxes as $meta_box) {
			new RW_Meta_Box($meta_box);
		}
	}
}

// Hook to 'admin_init' to make sure the meta box class is loaded before
// (in case using the meta box class in another plugin)
// This is also helpful for some conditionals like checking page template, categories, etc.
add_action('admin_init', 'emallshop_register_meta_boxes');

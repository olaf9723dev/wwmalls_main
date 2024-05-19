<?php

$config = array(
	'theme_name' => __( 'Estatik Realtor Theme', 'ert' ),
	'version' => '2.0.3',
	'controls' => '<a class="ef-btn ef-btn-control" target="_blank" href="https://estatik.net/realtor-theme-documentation/"><i class="fa fa-folder-open" aria-hidden="true"></i> ' . __( 'Documentation', 'ert' ) . '</a>
				   <a class="ef-btn ef-btn-control" target="_blank" href="https://estatik.net/contact-us/"><i class="fa fa-envelope-o" aria-hidden="true"></i>  ' . __( 'Contact us', 'ert' ) . '</a>',
	'option_name' => 'ert-options',
	'options' => array(

		'logo_attachment_id' => array(
			'label' => __( 'Logo Image', 'ert' ),
			'field_type' => 'media',
			'name' => 'logo_attachment_id',
			'multiple' => false,
		),

		'logo_width' => array(
			'label' => __( 'Logo Width', 'ert' ),
			'field_type' => 'input',
			'type' => 'text',
			'placeholder' => 'auto',
			'name' => 'logo_width',
			'label_description' => __( 'Set your logo custom width', 'ert' ),
			'input_description' => __( 'Allowed values examples: 50px, 50% or auto.', 'ert' ),
			'default_value' => '100px',
		),

		'favicon_attachment_id' => array(
			'label' => __( 'Favicon Image', 'ert' ),
			'field_type' => 'media',
			'name' => 'favicon_attachment_id',
			'input_description' => __( 'Allowed sizes are 16x16 or 32x32', 'ert' ),
			'multiple' => false,
		),

		'theme_color' => array(
			'type' => 'color',
			'field_type' => 'input',
			'label' => __( 'Theme Color', 'ert' ),
			'name' => 'theme_color',
			'default_value' => '#000000'
		),

		'header_menu_color_hover' => array(
			'type' => 'color',
			'field_type' => 'input',
			'label' => __( 'Header Menu Hover', 'ert' ),
			'name' => 'header_menu_color_hover',
			'default_value' => '#555555',
		),

		'logo_height' => array(
			'label' => __( 'Logo Height', 'ert' ),
			'field_type' => 'input',
			'type' => 'text',
			'placeholder' => 'auto',
			'name' => 'logo_height',
			'label_description' => __( 'Set your logo custom height', 'ert' ),
			'input_description' => __( 'Allowed values examples: 50px, 50% or auto.', 'ert' ),
			'default_value' => 'auto',
		),

		'footer_copyright' => array(
			'label' => __( 'Copyright', 'ert' ),
			'field_type' => 'input',
			'type' => 'text',
			'name' => 'footer_copyright',
		),

		'property_archive_page_name' => array(
			'label' => __( 'Archive page name', 'ert' ),
			'field_type' => 'input',
			'type' => 'text',
			'name' => 'property_archive_page_name',
			'default_value' => 'Our Properties',
		),

		'property_sidebar' => array(
			'label' => __( 'Property Sidebar', 'ert' ),
			'field_type' => 'radio',
			'name' => 'property_sidebar',
			'default_value' => 'right',
			'options' => array(
				'' => __( 'Disabled', 'ert' ),
				'left' => __( 'Left', 'ert' ),
				'right' => __( 'Right', 'ert' ),
			),
		),

		'breadcrumbs_enabled' => array(
			'label' => __( 'Enable Breadcrumbs', 'ert' ),
			'field_type' => 'input',
			'type' => 'checkbox',
			'name' => 'breadcrumbs_enabled',
			'value' => 1,
			'default_value' => 1
		),

		'show_author' => array(
			'label' => __( 'Show Author', 'ert' ),
			'field_type' => 'input',
			'type' => 'checkbox',
			'name' => 'show_author',
			'value' => 1,
			'default_value' => 1
		),

		'is_badges_enabled' => array(
			'label' => __( 'Show badges', 'ert' ),
			'field_type' => 'input',
			'type' => 'checkbox',
			'name' => 'is_badges_enabled',
			'value' => 1,
			'default_value' => 1
		),

        'show_featured_image' => array(
            'label' => __( 'Show Featured Image', 'ert' ),
            'field_type' => 'input',
            'type' => 'checkbox',
            'name' => 'show_featured_image',
            'value' => 1,
            'default_value' => 1
        ),

		'property_archive_layout_version' => array(
			'label' => __( 'Listing Layout', 'ert' ),
			'field_type' => 'radio',
			'name' => 'property_archive_layout_version',
			'default_value' => 'list',
			'options' => array(
				'2_col' => __( 'Grid', 'ert' ),
				'list' => __( 'List', 'ert' ),
			),
		),

		'property_disabled_sidebar' => array(
			'label' => __( 'Disable Sidebar', 'ert' ),
			'field_type' => 'input',
			'type' => 'checkbox',
			'name' => 'property_disabled_sidebar',
		),

		'blog_items_per_row' => array(
			'label' => __( 'Items per row', 'ert' ),
			'field_type' => 'radio',
			'name' => 'blog_items_per_row',
			'default_value' => 'col-md-6',
			'options' => array(
				'col-md-6' => 2,
				'col-md-4' => 3,
			),
		),

		'blog_disable_sidebar' => array(
			'label' => __( 'Disable Sidebar', 'ert' ),
			'field_type' => 'input',
			'type' => 'checkbox',
			'name' => 'blog_disable_sidebar',
		),

		'show_sort_by' => array(
			'label' => __( 'Show Sort By', 'ert' ),
			'field_type' => 'input',
			'type' => 'checkbox',
			'name' => 'show_sort_by',
		),

		'sort_bar_categories' =>array(
			'label' => __( 'Categories To Show', 'ert' ),
			'field_type' => 'select',
			'type' => 'select',
			'name' => 'sort_bar_categories',
			'multiple' => 'multiple',
			'taxonomy' => 'es_category',
			'default_terms' => array( 'For rent', 'For sale' ),
		),

		'sticky_header' => array(
			'label' => __( 'Sticky Header', 'ert' ),
			'field_type' => 'input',
			'type' => 'checkbox',
			'name' => 'sticky_header',
		),
	),
);

return $config;

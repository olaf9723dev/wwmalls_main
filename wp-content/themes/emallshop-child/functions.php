<?php
// Insert your Customization Functions. Read More - http://codex.wordpress.org/Child_Themes

/* 	
 * @EmallShop child css
 * @since EmallShop Child 1.2.0
 */
if (!function_exists('emallshop_child_css')) {
	function emallshop_child_css() {
			
		$parent_style = 'parent-style';
		wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
		wp_enqueue_style( 'child-style',
			get_stylesheet_directory_uri() . '/style.css',
			array( $parent_style )
		);
	}
}
add_action('wp_enqueue_scripts', 'emallshop_child_css', 1001 );
?>
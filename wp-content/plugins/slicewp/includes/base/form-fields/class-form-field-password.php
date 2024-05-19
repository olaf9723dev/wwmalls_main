<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Form Field of type "password"
 *
 */
class SliceWP_Form_Field_Password extends SliceWP_Form_Field_Text {

	/**
	 * The form field's type.
	 *
	 * @access protected
	 * @var    string
	 *
	 */
	protected $type = 'password';

}


/**
 * Registers the form field type "password"
 *
 * @param array
 *
 * @return array
 *
 */
function slicewp_register_form_field_type_password( $field_types ) {

	$field_types['password'] = array(
		'nicename' => __( 'Password', 'slicewp' ),
		'class'	   => 'SliceWP_Form_Field_Password'
	);

	return $field_types;

}
add_action( 'slicewp_register_form_field_types', 'slicewp_register_form_field_type_password' );
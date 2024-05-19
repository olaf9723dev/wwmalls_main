<?php

namespace Motta\Addons\Elementor\Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class AutoComplete extends \Elementor\Base_Data_Control {

	/**
	 * Get heading control type.
	 *
	 * Retrieve the control type, in this case `heading`.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'motta-autocomplete';
	}

	/**
	 * Enqueue Script & Style
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue() {
		// Styles
		wp_register_style( 'motta-autocomplete', MOTTA_ADDONS_URL . 'assets/css/admin/autocomplete.css', [], '20191807' );
		wp_enqueue_style( 'motta-autocomplete' );

		// Scripts
		wp_register_script( 'motta-autocomplete', MOTTA_ADDONS_URL . 'assets/js/admin/autocomplete.js', [
			'jquery',
			'jquery-ui-autocomplete',
			'jquery-ui-sortable'
		], '20191807', true );
		wp_enqueue_script( 'motta-autocomplete' );
	}

	/**
	 * Get heading control default settings.
	 *
	 * Retrieve the default settings of the heading control. Used to return the
	 * default settings while initializing the heading control.
	 *
	 * @since  1.0.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'multiple' => false,
			'sortable' => false,
			'source'   => 'category' // post type or taxonomy
		];
	}

	/**
	 * Render heading control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
        <div class="elementor-control-field">
            <# if ( data.label ) {#>
            <label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
            <# } #>
            <div class="elementor-control-input-wrapper">
                <ul class="motta_autocomplete">
                    <li class="motta_autocomplete-input">
                        <input class="motta_autocomplete_param" type="text" placeholder="{{ data.placeholder }}"/>
                        <input class="motta_autocomplete_value" type="hidden" data-source="{{data.source}}"
                               data-multiple="{{data.multiple}}"
                               data-sortable="{{data.sortable}}" value="{{data.controlValue}}"/>
                        <span class="loading"></span>
                    </li>

                    <li class="motta_autocomplete-loading">
                        <span class="loading"></span>
                    </li>
					<?php
					?>
                </ul>

            </div>
        </div>
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
		<?php
	}
}
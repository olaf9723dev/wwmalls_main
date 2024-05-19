<?php

/**
 * Class Ert_Search_Helper
 */
class Ert_Search_Helper {

    /**
     * Render field for search widget.
     *
     * @param $name
     * @param $widget_instance
     * @return string|boolean
     */
	public static function render_field( $name, $widget_instance ) {
		$property = es_get_property( null );

		$widget_instance = wp_parse_args( $widget_instance, array(
			'layout' => 'vertical',
			'enable_unit_converter' => false,
		) );

		$field_col = $widget_instance['layout'] == 'vertical' ? 'col-12' : 'col-md-4';

		// If input data is empty.
		if ( empty( $name ) || ! $field = $property::get_field_info( $name ) ) return false;

		// Field content string;
		$content = null;
		$default_value = ! empty( $field['default_value'] ) ? $field['default_value'] : null;
		// Current field value.
        $value = isset( $widget_instance[ $name ] ) ? $widget_instance[ $name ] : $default_value;
        $value = isset( $_GET['es_search'][ $name ] ) ? $_GET['es_search'][ $name ] : $value;
		// Field options.
		$options = ' ';

		if ( in_array( $name, array( 'es_feature', 'es_amenities' ) ) ) {
			$field_col = "col-12";
			unset( $field['options']['class'] );
		}

		if ( empty( $field['options']['id'] ) && ! in_array( $name, array( 'es_feature', 'es_amenities' ) ) ) {
			$field['options']['id'] = $name . '-' . uniqid();
		}

        if ( empty( $field['options']['id'] ) ) {
            $field['options']['id'] = 'es-' . $name . '-input';
        }

		if ( isset( $field['options']['required'] ) ) {
			unset( $field['options']['required'] );
		}

		// Set value as data attribute for ajax fields.
		if ( ! empty( $value ) ) {
			$field['options']['data-value'] = $value;
			$field['options']['value'] = $value;
		} else {
			$field['options']['value'] = '';
		}

		if ( empty( $field['options']['value'] ) && ! empty( $field['default_value'] ) ) {
			$field['options']['value'] = $field['default_value'];
		}

        if ( 'ID' == $name ) {
            unset( $field['options']['readonly'] );
        }

		$field['type'] = ! empty( $field['type'] ) ? $field['type'] : null;

        if ( ! empty( $field['type'] ) && ( 'datetime-local' == $field['type'] || 'date' == $field['type'] || 'datetime' == $field['type'] ) ) {
            $field['options']['data-date-field'] = 1;
            global $es_settings;
//            $time = 'datetime-local' == $field['type'] ? ' H:i' : '';
            $field['options']['data-date-format'] = $es_settings->date_format;
        }

		// Generate label if empty.
		if (isset($field['label']) && $field['label'] == FALSE) {
			$field['label'] = '';
		}
		else {
			$label = ! empty( $field['label'] ) ? $field['label'] : __( Es_Html_Helper::generate_label( $name ), 'es-plugin' );
			$field['label'] = '<div class="es-field__label">
                <label for="' . $field['options']['id'] . '">
                ' . $label . '
                </label></div>';
		}

		if ( ! empty( $label ) ) {
			$field['options']['data-placeholder'] = $label;
			$field['options']['placeholder'] = $label;
		}

		if ( ! empty( $field['options'] ) ) {

			if ( ! empty( $field['search_range_mode'] ) ) {
				unset( $field['options']['value'] );
			}

			if ( in_array( $name, array( 'es_feature', 'es_amenities' ) ) ) {
				$field['options']['class'] = ! empty( $field['options']['class'] ) ? $field['options']['class'] . ' form-check-input' : 'form-check-input';
			} else {
				$field['options']['class'] = ! empty( $field['options']['class'] ) ? $field['options']['class'] . ' form-control' : 'form-control';
			}

			foreach ( $field['options'] as $key => $option ) {
                if ( ( ! empty( $field['search_range_mode'] ) && $key == 'id' ) || is_array( $option ) || ( $key == 'multiple' && empty( $option ) ) ) continue;
				$options .= $key . '="' . $option . '" ';
			}
		}

		$field['type'] = ! empty( $field['search_range_mode'] ) ? 'text' : $field['type'];

		$class_unit = null;

		if ( ! empty( $field['units'] ) ) {
			$class_unit = 'es-field__wrap--units';
		}

		if ( empty( $field['template'] ) ) {
			$content .= '<div class="' . $field_col . ' form-group ert-search-field ert-field__' . $name . '">';
			$content .= "<label for='{$field['options']['id']}'>{$label}</label>";
		}

		$multiple = ! empty( $field['options']['multiple'] ) ? '[]' : '';

		if ( $field['type'] == 'date' || $field['type'] == 'datetime-local' ) {
			$field['type'] = 'text';
		}

		if ( ! empty( $field['values_callback'] ) ) {
			$field['values'] = call_user_func_array( $field['values_callback']['callback'], $field['values_callback']['args'] );
		}

		switch ( $field['type'] ) {
			case 'list':
				if ( in_array( $name, array( 'es_feature', 'es_amenities' ) ) ) {
					if ( ! empty( $field['values'] ) ) {
						$content .= "<div class='row'>";
						$value = ! is_array( $value )? array( $value ) : $value;
						foreach ( $field['values'] as $_value => $label ) {
							$value = ! is_array( $value ) ? array( $value ) : $value;
							$checked = in_array( $_value, $value ) ? "checked" : "";
							$checkbox_id = 'es-search-' . $name . '-input' . '-' . uniqid();
							$content .= '<div class="col-sm-4">
											<div class="form-check checkbox checkbox-circle">
												<input ' . $checked . ' value="' . $_value . '" id="' . $checkbox_id . '" type="checkbox" name="es_search[' . $name . ']' . $multiple . '" ' . $options . '/>
												<label for="' . $checkbox_id . '" class="form-check-label"><span>' . $label . '</span></label>
											</div>
										</div>';
						}
						$content .= "</div>";

					}
				} else {
					$content .= '<select name="es_search[' . $name . ']' . $multiple . '" ' . $options .'>';

                    if ( ! empty( $field['prompt'] ) ) {
                        $content .= '<option>' . $field['prompt'] . '</option>';
                    } else if ( ! empty( $field['fbuilder'] ) ) {
                        $content .= '<option></option>';
                    } else if ( ! empty( $field['options']['data-placeholder'] ) ) {
                        if ( $name == 'post_author' ) {
                            $content .= "<option>{$field['options']['data-placeholder']}</option>";
                        } else {
                            $content .= '<option></option>';
                        }
                    }

					if ( ! empty( $field['values'] ) ) {
						foreach ( $field['values'] as $value => $label ) {
							$values = ! empty( $field['options']['value'] ) && is_array( $field['options']['value'] ) ?
								$field['options']['value'] : array();

							$selected = is_array( $value ) ?
								selected( true, in_array( $value, $values ), false ) : null;

							if ( is_array( $field['options']['value'] ) && ! $selected ) {
								if ( in_array( $value, $field['options']['value'] ) ) {
									$selected = selected( true, true, false );
								}
							} else {
								$selected = selected( $value, $field['options']['value'], false );
							}

							if ( is_array( $field['options']['value'] ) && ! $selected ) {
								if ( in_array( $value, $field['options']['value'] ) ) {
									$selected = selected( true, true, false );
								}
							}

							$content .= '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
						}
					}

					$content .= '</select>';
				}
				break;

			case 'radio':
			case 'checkbox':
				$content .= '<input type="' . $field['type'] . '" name="es_search[' . $name . ']" ' . $options . ' ' . checked( $value, $field['options']['value'], false ) . '/>';
				break;

			case 'autocomplete':
				$content .= '<div class="es-autocomplete-wrap js-autocomplete-wrap">';
				$content .= '<input type="text" name="es_search[' . $name . ']" ' . $options .' data-action="' . $field['autocomplete_action'] . '"/>';
				$content .= '<div class="es-autocomplete-result"></div>';
				$content .= '</div>';
				break;

			default:
				if ( ! empty( $field['search_range_mode'] ) ) {
                    $min = isset( $widget_instance[$name . '_min'] ) ? $widget_instance[$name . '_min'] : null;
                    $min = ! empty( $value['min'] ) ? $value['min'] : $min;

                    $max = isset( $widget_instance[$name . '_max'] ) ? $widget_instance[$name . '_max'] : null;
                    $max = ! empty( $value['max'] ) ? $value['max'] : $max;

					if ( ! empty( $field['units'] ) ) {
						$fields = Es_Property::get_fields();
						$unit = $fields[ $field['units'] ]['default_value'];
						$unit_label = $fields[ $field['units'] ]['values'][ $unit ];

						$unit = '<div class="input-group-append">
							    <span class="input-group-text">' . $unit_label . '</span>
							  </div>';

						$content .= '<div class="row"><div class="col"><div class="input-group"><input id="' . esc_attr( $field['options']['id'] ) . '-min" type="' . $field['type'] . '" placeholder="' . __( 'Min', 'ert' ) . '" name="es_search[' . $name . '][min]" ' . $options .' class="form-control" value="' . $min . '"/>' . $unit . '</div></div>';
						$content .= '<div class="col"><div class="input-group"><input id="' . esc_attr( $field['options']['id'] ) . '-max" type="' . $field['type'] . '" placeholder="' . __( 'Max', 'ert' ) . '" name="es_search[' . $name . '][max]" ' . $options .' class="form-control" value="' . $max . '"/>' . $unit . '</div></div></div>';
					} else {
						$content .= '<div class="row"><div class="col"><input id="' . esc_attr( $field['options']['id'] ) . '-min" type="' . $field['type'] . '" placeholder="' . sprintf( __( 'Min', 'ert' ), $label ) . '" name="es_search[' . $name . '][min]" ' . $options .' class="form-control" value="' . $min . '"/></div>';
						$content .= '<div class="col"><input id="' . esc_attr( $field['options']['id'] ) . '-max" type="' . $field['type'] . '" placeholder="' . __( 'Max', 'ert' ) . '" name="es_search[' . $name . '][max]" ' . $options .' class="form-control" value="' . $max . '"/></div></div>';
					}

				} else {
					$content .= '<input type="' . $field['type'] . '" name="es_search[' . $name . ']" ' . $options .'/>';
				}
		}

		if ( empty( $field['template'] ) ) {
			$content .= '</div>';
		}

		return apply_filters( 'es_search_render_field', $content, $field, $name );
	}
}

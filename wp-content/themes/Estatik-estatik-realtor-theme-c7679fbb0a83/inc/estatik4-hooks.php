<?php

/**
 * @param $value
 * @param $name
 * @return mixed
 */
function ert4_get_setting( $value, $name ) {
    if ( 'main_color' == $name || 'secondary_color' == $name ) {
        global $ef_options;
        $value = $ef_options->get( 'theme_color', '#000000' );
    }

    return $value;
}
add_filter( 'es_get_setting', 'ert4_get_setting', 10, 2 );

/**
 * @param $classes string
 * @param $classes_arr array
 * @param $atts array
 * @return mixed
 */
function ert4_alter_search_form_classes( $classes, $classes_arr, $atts ) {
    if ( 'advanced' == $atts['search_type'] ) {
        $classes_arr[] = 'ert-search__wrapper';
        $classes_arr[] = 'es-search__wrapper--' . $atts['layout'];

        foreach ( array( 'es-search', 'es-search--advanced', 'es-search--vertical' ) as $css_name ) {
            $search_key = array_search( $css_name, $classes_arr );

            if ( $search_key != -1 ) {
                unset( $classes_arr[ $search_key ] );
            }
        }

        $classes = implode( ' ', $classes_arr );
    }

    return $classes;
}
add_filter( 'es_search_form_get_container_classes', 'ert4_alter_search_form_classes', 10, 3 );

/**
 * @param $value
 * @param $name
 * @return mixed
 */
function ert4_alter_responsive_breakpoints( $value, $name ) {
    if ( 'responsive_breakpoints' == $name ) {
        unset( $value['listings']['breakpoints']['es-listings--list-sm'] );
        $value['listings']['breakpoints']['es-listings--list']['min'] = 700;
        $value['listings']['breakpoints']['es-listings--grid3']['min'] = 740;
    }

    return $value;
}
add_filter( 'es_get_setting', 'ert4_alter_responsive_breakpoints', 10, 2 );

remove_action( 'es_sort_dropdown', 'es_sort_dropdown' );
add_action( 'es_sort_dropdown', 'ert4_sort_dropdown' );

/**
 * @param $sort
 */
function ert4_sort_dropdown( $sort ) {
    $sorting = ests_selected( 'properties_sorting_options' );

    if ( ! empty( $sorting ) ) : ?>

        <button id="sortByDrop" type="button" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php if ( ! empty( $sorting[ $sort ] ) ) : ?>
                <?php echo $sorting[ $sort ]; ?>
            <?php else : ?>
                <?php _e( 'Sort By', 'ert' ); ?>
            <?php endif; ?>
        </button>
        <div class="dropdown-menu" aria-labelledby="sortByDrop">
            <?php foreach ( $sorting as $key => $value ) : ?>
                <a class="dropdown-item" href="<?php echo add_query_arg( 'sort', $key ); ?>"><?php echo $value; ?></a>
            <?php endforeach; ?>
        </div>
    <?php endif;
}

/**
 * @return void
 */
function ert4_property_gallery() {
    es_the_property_slider();
}

remove_action( 'ert_property_content', 'ert_property_gallery', 30 );
add_action( 'ert_property_content', 'ert4_property_gallery', 30 );
remove_action( 'es_before_single_content', 'ert_page_property_header' );
//remove_action( 'ert_property_content', 'ert_property_header', 20 );

/**
 * @param $sections
 * @param $entity
 *
 * @return array
 */
function ert4_sections_builder_get_sections( $sections, $entity ) {
    if ( 'property' == $entity ) {
        $sections['basic-facts']['frontend_action'] = 'ert_basic_facts_section';
    }
    return $sections;
}
add_filter( 'es_sections_builder_get_sections', 'ert4_sections_builder_get_sections', 10, 2 );

/**
 * Basic facts section implementation.
 *
 * @param $post_id
 * @param $section
 */
function ert_basic_facts_section( $post_id, $section ) {
    $wrapper_rendered = false;
    $fb_instance = es_get_fields_builder_instance();
    $fields = $fb_instance::get_section_fields( $section['machine_name'] );
    $i = 0;

    foreach ( $fields as $field => $field_info ) :
        if ( ! es_is_visible( $field_info ) ) continue;

        if ( ! empty( $field_info['taxonomy'] ) ) {
            $value = get_the_term_list( $post_id, $field, '', ', ' );
        } else {
            $value = es_get_the_formatted_field( $field, $post_id );
        }

        if ( ( is_string( $value ) || is_numeric( $value ) ) && strlen( $value ) ) :
            if ( ! $wrapper_rendered && empty( $field_info['ignore_field_wrapper'] ) ) : $wrapper_rendered = true; ?>
                <table>
            <?php endif; ?>

            <?php if ( ! $i ) : ?><tr><?php endif; ?>

            <td>
                <span class="label"><?php echo $field_info['label']; ?></span>
                <span class="content">
                    <?php echo $value; ?>
                </span>
            </td>

            <?php $i++; ?>

            <?php if ( $i == 4 ) : ?></tr><?php $i = 0; endif; ?>
        <?php endif;
    endforeach;

    if ( $wrapper_rendered && empty( $field_info['ignore_field_wrapper'] ) ) : ?>
        </table>
    <?php endif;

}
add_action( 'ert_basic_facts_section', 'ert_basic_facts_section', 10, 2 );

/**
 * Estatik search shortcode fields render handler.
 *
 * @param $field
 * @param array $attributes
 * @param null $force_type
 * @return void
 */
function ert4_search_render_field( $field, $attributes = array(), $force_type = null ) {
    $attributes = es_parse_args( $attributes, array( 'entity' => 'property', 'layout' => 'vertical' ) );
    $field_config = es_search_get_field_config( $field, $attributes['entity'] );
    $field_col = $attributes['layout'] == 'vertical' ? 'col-12' : 'col-md-4';
    $item_class = $attributes['layout'] == 'vertical' ? 'col-12' : 'col-sm-4';

    if ( in_array( $field, array( 'es_feature', 'es_amenities' ) ) ) {
        $field_col = "col-12";
    }

    if ( ! $field_config || empty( $field_config['search_support'] ) ) return;

    $search_settings = $field_config['search_settings'];
    if ( empty( $search_settings['attributes']['class'] ) ) {
        $search_settings['attributes']['class'] = '';
    }
    $search_settings['attributes']['class'] .= ' form-control';
    $search_settings['label_wrapper'] = "<div class='es-field__label'><label for='{id}'>%s</label></div>";
    $search_settings['skeleton'] = "{before}
                               <div class='{$field_col} form-group ert-search-field ert-field__{field_key} {wrapper_class}'>
                                   {label}{caption}{input}{description}{reset}
                               </div>
                           {after}";
    $type = $force_type ? $force_type : $search_settings['type'];
    $uid = uniqid();
    $selected_value = isset( $attributes[ $field ] ) ? $attributes[ $field ] : null;
    $selected_value = isset( $_GET[ $field ] ) ? es_clean( $_GET[ $field ] ) : $selected_value;

    if ( empty( $search_settings['values'] ) && ! empty( $search_settings['values_callback'] ) ) {
        if ( ! empty( $search_settings['values_callback']['args'] ) ) {
            $values = call_user_func_array( $search_settings['values_callback']['callback'], $search_settings['values_callback']['args'] );
        } else {
            $values = call_user_func( $search_settings['values_callback']['callback'] );
        }

        if ( $values && ! is_wp_error( $values ) ) {
            $search_settings['values'] = $values;
        }
    }

    $field_html = '';

    switch ( $type ) {
        case 'price':
            $values = array();
            if ( ests( 'is_same_price_for_categories_enabled' ) ) {
                $values['min'] = ests( 'min_prices_list' ) ? explode( ',', ests( 'min_prices_list' ) ) : array();
                $values['max'] = ests( 'max_prices_list' ) ? explode( ',', ests( 'max_prices_list' ) ) : array();

                $values['min'] = array_combine( $values['min'], $values['min'] );
                $values['max'] = array_combine( $values['max'], $values['max'] );

                $prices_list = array();
            } else {
                if ( $prices_list = ests( 'custom_prices_list' ) ) {
                    foreach ( $prices_list as $price_item ) {
                        if ( empty( $price_item['type'] ) && empty( $price_item['category'] ) ) {
                            $values['min'] = explode( ',', $price_item['min_prices_list'] );
                            $values['max'] = explode( ',', $price_item['max_prices_list'] );
                            break;
                        }
                    }
                }
            }

            $field_html = "<div class='{$field_col} form-group ert-search-field js-search-field-container'><div class='row align-items-end'>";

            foreach ( array( 'min', 'max' ) as $field_range ) {
                $values[ $field_range ] = es_format_values( $values[ $field_range ], $field_config['formatter'] );
                $range_label = ! empty( $search_settings['range_label'] ) ? $search_settings['range_label'] : $field_config['label'];
                $field_name = $field_range . '_' . $field;
                $value = isset( $attributes[ $field_name ] ) ? $attributes[ $field_name ] : null;
                $value = isset( $_GET[ $field_name ] ) ? es_clean( $_GET[ $field_name ] ) : $value;

                $config = array(
                    'label_wrapper' => "<div class='es-field__label'><label for='{id}'>%s</label></div>",
                    'skeleton' => "{before}
                               <div class='col-6 form-group ert-search-field ert-field__{field_key} {wrapper_class}'>
                                   {label}{caption}{input}{description}{reset}
                               </div>
                           {after}",
                    'type' => ! empty( $values[ $field_range ] ) ? 'select' : 'number',
                    'label' => $field_range == 'min' ? $range_label : false,
                    'value' => $value,
                    'attributes' => array(
                        'data-prices-list' => es_esc_json_attr( $prices_list ),
                        'id' => sprintf( '%s-%s-%s', $field, $field_range, $uid ),
                        'class' => 'js-es-search-field js-es-search-field--price ' . sprintf( 'js-es-search-field--price-%s', $field_range ),
                        'data-base-name' => $field,
                        'data-placeholder' => sprintf( __( 'No %s', 'es' ), $field_range ),
                    ),
                    'options' => ! empty( $values[ $field_range ] ) ? array( '' => '' ) + $values[ $field_range ] : array(),
                );

                $field_html .= es_framework_get_field_html( $field_name, es_parse_args( $config, $search_settings ) );
            }
            $field_html .= "</div></div>";

            break;
        case 'select':
        case 'list':
        case 'dropdown':
            $search_settings['values'] = es_format_values( $search_settings['values'], $field_config['formatter'] );
            $values = $search_settings['values'];

            if ( ! empty( $search_settings['attributes']['data-placeholder'] ) ) {
                $values = array( '' => '' ) + $values;
            }

            if ( 'keywords' == $field && $selected_value ) {
                $values = array_combine( $selected_value, $selected_value );
            }

//                    if ( ! $search_settings['attributes']['multiple'] ) {
//                        $values = array( '' => _x( 'All', 'search dropdown placeholder', 'es' ) ) + $values;
//                    }

            $config = array(
                'label_wrapper' => "<div class='es-field__label'><label for='{id}'>%s</label></div>",
                'skeleton' => "{before}
                               <div class='{$field_col} form-group ert-search-field ert-field__{field_key} {wrapper_class}'>
                                   {label}{caption}{input}{description}{reset}
                               </div>
                           {after}",
                'type'       => $type,
                'options'    => $values,
                'value' => $selected_value,
                'attributes' => array(
                    'id' => sprintf( '%s-%s', $field, $uid ),
                    'class' => sprintf( 'js-es-search-field js-es-search-field--%s', $field ),
                    'data-base-name' => $field,
                ),
                'label'      => ! empty( $field_config['label'] ) ? $field_config['label'] : false,
            );
            $search_settings['wrapper_class'] .= ' js-search-field-container';
            $field_html = es_framework_get_field_html( $field, es_parse_args( $config, $search_settings ) );
            break;

        case 'checkboxes':
            if ( ! empty( $search_settings['values'] ) ) {
                $values = es_format_values( $search_settings['values'], $field_config['formatter'] );
                $visible_items = ! empty( $search_settings['visible_items'] ) ? $search_settings['visible_items'] : false;

                if ( $values ) {
                    foreach ( $values as $value => $label ) {
                        $inputs_attr[ $value ] = array(
                            'label_wrapper' => "<label for='{id}' class='form-check-label'><span>%s</span></label>",
                            'skeleton' => "{before}
                               <div class='{$item_class} {wrapper_class}'>
                                   <div class='form-check checkbox checkbox-circle'>
                                       {input}{label}{caption}{description}{reset}
                                   </div>
                               </div>
                           {after}",
                            'attributes' => array(
                                'class' => "form-check-input js-es-search-field js-es-search-field--{$field} es-field__input",
                            )
                        );
                    }

                    $config = array(
                        'label_wrapper' => "<label for='{id}'><span>%s</span></label>",
                        'label'         => ! empty( $field_config['label'] ) ? $field_config['label'] : false,
                        'skeleton' => "{before}
                               <div class='js-es-field {$field_col} form-group ert-search-field ert-field__{field_key} {wrapper_class}'>
                                   {label}<div class='row'>{caption}{input}{description}{reset}</div>
                               </div>
                           {after}",
                        'type'       => $type,
                        'options'    => $values,
                        'disable_hidden_input' => true,
                        'items_attributes' => $inputs_attr,
                        'value' => $selected_value,
                        'visible_items' => $visible_items,
                        'button_label' => ! empty( $search_settings['show_more_label'] ) ? $search_settings['show_more_label'] : '',
                        'attributes' => array(
                            'id' => sprintf( '%s-%s', $field, $uid ),
                            'class' => sprintf( 'js-es-search-field js-es-search-field--%s', $field ),
                            'data-base-name' => $field,
                        ),
                        'show_more_button' => "<div class='{$item_class}'><a href='#' class='js-es-field__show-more es-field__show-more es-field--visibility'>{button_label}</a></div>",
                    );

                    $search_settings['wrapper_class'] .= ' js-search-field-container';
                    $field_html = es_framework_get_field_html( $field, es_parse_args( $config, $search_settings ) );
                }
            }
            break;

        case 'radio-bordered':
        case 'checkboxes-bordered':
        case 'checkboxes-boxed':
            if ( ! empty( $search_settings['values'] ) ) {
                $options = $search_settings['values'];
                $field_name = $field;
                $field_class = sprintf( 'js-es-search-field js-es-search-field--%s', $field_name );

                if ( in_array( $field, array( 'bedrooms', 'bathrooms', 'half_baths' ) ) ) {
                    array_walk( $search_settings['values'], 'es_arr_add_suffix_plus' );
                    $options = array( '' => __( 'Any', 'es' ) ) + $search_settings['values'];
                    $field_name = 'from_' . $field;
                    $selected_value = isset( $attributes[ $field_name ] ) ? $attributes[ $field_name ] : null;
                    $selected_value = isset( $_GET[ $field_name ] ) ? es_clean( $_GET[ $field_name ] ) : $selected_value;
                }

                foreach ( $options as $value => $label ) {
                    $inputs_attr[ $value ] = array(
                        'label_wrapper' => '<div class="es-field__label">%s</div>',
                    );
                }

                $config = array(
                    'label_wrapper' => "<label>%s</label>",
                    'label'         => ! empty( $field_config['label'] ) ? $field_config['label'] : false,
                    'type' => $type,
                    'options' => $options,
                    'value' => $selected_value,
                    'disable_hidden_input' => true,
                    'items_attributes' => $inputs_attr,
                    'attributes' => array(
                        'id' => sprintf( '%s-%s', $field_name, $uid ),
                        'class' => $field_class,
                        'data-formatter' => $field_config['formatter'],
                        'data-base-name' => $field,
                    ),
                );

                unset( $search_settings['label_wrapper'] );

                $search_settings['wrapper_class'] .= ' js-search-field-container';
                $field_html = es_framework_get_field_html( $field_name, es_parse_args( $config, $search_settings ) );
            }
            break;

        case 'range':
            $field_html = "<div class='{$field_col} form-group ert-search-field js-search-field-container'><div class='row align-items-end'>";
            foreach ( array( 'min', 'max' ) as $field_range ) {
                $range_label = ! empty( $search_settings['range_label'] ) ? $search_settings['range_label'] : $field_config['label'];
                $values = ! empty( $search_settings['values_' . $field_range] ) ? $search_settings['values_' . $field_range] : array();
                $values = es_format_values( $values, $field_config['formatter'] );
                $field_name = $field_range . '_' . $field;
                $selected_value = isset( $attributes[ $field_name ] ) ? $attributes[ $field_name ] : null;
                $selected_value = isset( $_GET[ $field_name ] ) ? es_clean( $_GET[ $field_name ] ) : $selected_value;
                $config = array(
                    'type' => $values ? 'select' : 'number',
                    'label' => $field_range == 'min' ? $range_label : false,
                    'value' => $selected_value,
                    'label_wrapper' => "<div class='es-field__label'><label for='{id}'>%s</label></div>",
//                    'label'         => ! empty( $field_config['label'] ) ? $field_config['label'] : false,
                    'skeleton' => "{before}
                               <div class='col-6 form-group ert-search-field ert-field__{field_key} {wrapper_class}'>
                                   {label}{caption}{input}{description}{reset}
                               </div>
                           {after}",
                    'attributes' => array(
                        'id' => sprintf( '%s-%s-%s', $field, $field_range, $uid ),
                        'min' => ests( 'search_min_' . $field ),
                        'max' => ests( 'search_max_' . $field ),
                        'data-formatter' => $field_config['formatter'],
                        'class' => sprintf( 'js-es-search-field js-es-search-field--%s', $field ),
                        'data-base-name' => $field,
                        'data-placeholder' => sprintf( __( 'No %s', 'es' ), $field_range ),
                        'placeholder' => sprintf( __( 'No %s', 'es' ), $field_range ),
                    ),
                    'options' => array( '' => '' ) + $values,
                );

                $field_html .= es_framework_get_field_html( $field_name, es_parse_args( $config, $search_settings ) );
            }
            $field_html .= "</div></div>";

            break;
        default:
            $search_settings['wrapper_class'] .= ' js-search-field-container';
            $field_config = array_merge( $field_config, $search_settings );
            $field_config['value'] = $selected_value;
            $field_html = es_framework_get_field_html( $field, $field_config );
    }

    if ( ! empty( $field_html ) || ( ! empty( $attributes['type'] ) && $attributes['type'] == 'range' ) ) {
        echo apply_filters( 'es_search_render_field_html', $field_html, $field, $attributes, $force_type );
    }

    if ( ! empty( $search_settings['range'] ) &&  $type != 'range' ) {
        $field_config['type'] = 'range';
        $field_config['search_settings']['type'] = 'range';
        ert4_search_render_field( $field, $field_config, 'range' );
    }
}

//remove_action( 'es_search_render_field', 'es_search_render_field' );
add_action( 'ert4_search_render_field', 'ert4_search_render_field', 10, 2 );

/**
 * @param $widget_instance
 * @return mixed
 */
function ert4_prepare_wp_query_widget_args( $instance ) {
    if ( ! empty( $instance['property_query']['taxonomies'] ) ) {
        $instance['property_query'] = array_merge( $instance['property_query'], $instance['property_query']['taxonomies'] );
        unset( $instance['property_query']['taxonomies'] );
    }

    if ( ! empty( $instance['property_query'] ) ) {
        $instance = array_merge( $instance, $instance['property_query'] );
        unset( $instance['property_query'] );
    }

    if ( ! empty( $instance['price_min'] ) ) {
        $instance['min_price'] = $instance['price_min'];
        unset( $instance['price_min'] );
    }

    if ( ! empty( $instance['price_max'] ) ) {
        $instance['max_price'] = $instance['price_max'];
        unset( $instance['price_max'] );
    }

    return $instance;
}

if ( ! function_exists( 'ert4_the_property_control' ) ) {

    /**
     * @param array $args
     *
     * @return void
     */
    function ert4_the_property_control( $args = array() ) {
        $args = es_parse_args( $args, array(
            'show_sharing' => true,
            'show_wishlist' => true,
            'show_compare' => true,
            'is_full' => true,
            'wishlist_confirm' => false,
            'entity' => 'property',
            'entity_plural' => 'properties',
            'share_popup_id' => 'es-share-popup'
        ) );
        extract( $args );
        include es_locate_template( 'front/partials/entity-control-properties.php' );
    }
}
remove_action( 'es_property_control', 'es_the_property_control', 10 );
add_action( 'es_property_control', 'ert4_the_property_control', 10, 2 );

function ert4_ajax_get_property_item( $response ) {
    if ( ! empty( $response['content'] ) ) {
        $response['content'] = "<div class='js-es-listings ert-listing js-es-entities es-listings es-listings--grid-1'>" . $response['content'] . '</div>';
    }
    return $response;
}
add_filter( 'es_ajax_get_property_item', 'ert4_ajax_get_property_item' );

function ert4_ajax_management_delete_property_response( $response ) {
    if ( ! empty( $response['message'] ) ) {
        $response['message'] = str_replace( 'es-listings es-listings--grid-1', 'js-es-listings ert-listing js-es-entities es-listings es-listings--grid-1', $response['message'] );
    }
    return $response;
}
add_filter( 'es_ajax_management_delete_property_response', 'ert4_ajax_management_delete_property_response' );

/**
 * @param $control_section Elementor_Es_Properties_Slider_Widget
 */
function ert4_el_properties_slider_end_controls( $control_section ) {
    $control_section->add_custom_control( 'slider_view', array(
        'label' => __( 'View', 'es' ),
        'type' => 'select',
        'default' => 'v1',
        'options' => array(
            'v1' => __( 'v1', 'es' ),
            'v2' => __( 'v2', 'es' ),
        ),
    ) );
}
add_action( 'es_el_properties_slider_end_controls', 'ert4_el_properties_slider_end_controls' );

/**
 * @param $atts
 * @return false|string
 */
function es4_gallery_shortcode( $atts ) {
    $atts = shortcode_atts( array(
            'property_id' => get_the_ID(),
    ), $atts );

    ob_start();
    es_the_property_slider( $atts['property_id'] );
    return ob_get_clean();
}
add_shortcode( 'es_gallery', 'es4_gallery_shortcode' );

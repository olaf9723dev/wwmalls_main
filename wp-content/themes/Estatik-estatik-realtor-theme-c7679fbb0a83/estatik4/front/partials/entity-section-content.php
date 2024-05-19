<?php

/**
 * @var $fields array
 * @var $section array
 * @var $entity_name string
 * @var $post_id int
 */

$wrapper_rendered = false;

foreach ( $fields as $field => $field_info ) :
    if ( ! es_is_visible( $field_info, $entity_name, 'field' ) ) continue;
    $formatter = ! empty( $field_info['formatter'] ) ? $field_info['formatter'] : 'default';

    if ( ! empty( $field_info['taxonomy'] ) ) {
        if ( $section['machine_name'] == 'features' ) {
            $terms = get_the_terms( $post_id, $field );
            $class = ests( 'is_terms_icons_enabled' ) ? 'es-property-field__terms--icons' : '';
            $value = es_get_the_term_list( $post_id, $field, '<div class="es-property-field__terms ' . $class . '">', '', '</div>' );
            $need_terms_more_link = ! is_wp_error( $terms ) && $terms && count( $terms ) > 5 ? true : $need_terms_more_link;
        } else {
            $value = get_the_term_list( $post_id, $field, '', ', ' );
        }
    } else {
        $value = es_get_the_formatted_field( $field, $post_id );
    }

    if ( ( is_string( $value ) || is_numeric( $value ) ) && strlen( $value ) ) :
        if ( ! $wrapper_rendered && empty( $field_info['ignore_field_wrapper'] ) ) : $wrapper_rendered = true; ?>
            <ul class='es-property-single-fields'>
        <?php endif;

        if ( ! empty( $field_info['ignore_field_wrapper'] ) ) : ?>
            <?php echo $value; ?>
        <?php else :
            $class_list = array( 'es-entity-field', "es-entity-field--{$field}", "es-{$entity_name}-field", "es-{$entity_name}-field--{$field}", "es-{$entity_name}-field--{$formatter}" );
            if ( ! empty( $field_info['is_full_width'] ) ) $class_list[] = 'es-entity-field--full-width'; ?>

            <li class="<?php echo implode( " ", $class_list ); ?>">
                <?php if ( ! empty( $field_info['label'] ) ) :
                    $label = ! empty( $field_info['id'] ) ? __( $field_info['label'], 'es' ) : $field_info['label']; ?>
                    <?php printf( "<strong class='es-{$entity_name}-field__label'>%s<span class='es-{$entity_name}-field__sep'>:</span> </strong>", $label );
                endif;

                $show_all = ! empty( $field_info['show_more_label'] ) ? $field_info['show_more_label'] : __( 'Show all', 'es' );

                printf( "<span class='es-{$entity_name}-field__value es-entity-field__value'>%s</span>", $value ); ?>
            </li>
        <?php endif;
    endif;
endforeach;

if ( $wrapper_rendered && empty( $field_info['ignore_field_wrapper'] ) ) : ?>
    </ul>
<?php endif;

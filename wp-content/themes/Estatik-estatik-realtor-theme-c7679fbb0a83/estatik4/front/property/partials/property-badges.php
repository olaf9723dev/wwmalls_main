<?php

/**
 * @var $property Es_Property
 */

$labels = apply_filters( 'es_property_badges_terms', get_the_terms( get_the_ID(), 'es_label' ), get_the_ID() );

if ( $labels && ! is_wp_error( $labels ) ) : $badges_num = apply_filters( 'es_property_badges_terms_num', 3 );
    foreach ( $labels as $label ) : if ( ! $badges_num ) break; $badges_num--;
        $color = es_get_term_color( $label->term_id );
        $style_color = stristr( $color, 'fff' ) ? 'color: #000;' : ''; ?>
        <span style="background: <?php echo $color . ' ' . $style_color; ?>;" class="badge badge-light badge-<?php echo $label->slug; ?>">
            <?php _e( $label->name, 'es-plugin' ) ; ?>
        </span>
    <?php endforeach; ?>
<?php endif;

<?php

/**
 * @var $args array
 */

$grid_layout = es_get_active_grid_layout( $args['layout'] ); ?>
<div class="js-es-control--layouts ert-filter__layout">
    <a href="<?php echo add_query_arg( 'layout', $grid_layout ); ?>" data-layout="<?php echo $grid_layout; ?>" class="js-es-change-layout <?php echo es_is_grid_layout( $args['layout'] ) ? 'es-btn--active' : ''; ?>">
        <i class="fa fa-th-large"></i>
    </a>

    <a href="<?php echo add_query_arg( 'layout', 'list' ); ?>" data-layout="list" class="js-es-change-layout <?php es_active_class( $args['layout'], 'list', 'es-btn--active' ); ?>">
        <i class="fa fa-list"></i>
    </a>

    <a href="<?php echo add_query_arg( 'layout', 'half_map' ); ?>" data-layout="half_map" class="js-es-change-layout <?php es_active_class( $args['layout'], 'half_map', 'es-btn--active' ); ?>">
        <span class="es-icon es-icon_marker"></span>
    </a>
</div>

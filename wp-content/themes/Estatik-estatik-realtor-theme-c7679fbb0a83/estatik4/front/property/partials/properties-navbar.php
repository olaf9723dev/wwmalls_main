<?php

/**
 * @var $query WP_Query
 * @var $args array Shortcode attribtes
 */

global $ef_options;
$categories = $ef_options->get( 'sort_bar_categories' );
$sorting = ests( 'properties_sorting_options' );
if ( $categories ) $categories = get_terms( 'es_category', array( 'term_taxonomy_id' => $categories ) ); ?>
<div class="ert-filter ert-filter__inner js-es-listings-filter">
    <div class="ert-filter__sort">
        <?php if ( $categories || $sorting ) : ?>
        <ul class="ert-filter__categories">
            <li><a href="<?php echo remove_query_arg( array_keys( $_GET ) ); ?>"><?php _e( 'All', 'ert' ); ?></a></li>
            <?php if ( $categories ) : ?>
                <?php foreach ( $categories as $category ) :
                    $list = add_query_arg( array( 'es' => 1, 'es_category' => array( $category->term_id ) ) ); ?>
                    <li><a href="<?php echo $list; ?>"><?php echo $category->name; ?></a></li>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if ( $ef_options->get( 'show_sort_by' ) && $sorting && ! empty( $args['show_sort'] ) ) : ?>
                <?php do_action( 'es_sort_dropdown', $args['sort'] ); ?>
            <?php endif; ?>
        </ul>
        <?php endif; ?>
    </div>
    <?php if ( ! empty( $args['show_layouts'] ) ) : ?>
        <?php do_action( 'es_layouts', $args ); ?>
    <?php endif; ?>
</div>


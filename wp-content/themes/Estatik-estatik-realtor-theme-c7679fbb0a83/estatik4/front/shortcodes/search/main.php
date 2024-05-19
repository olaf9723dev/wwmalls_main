<?php

/**
 * @var $container_classes string
 * @var $args array
 * @var $title string
 * @var $attributes array
 * @var $search_page_uri string
 * @var $search_page_exists bool
 * @var $search_page_id int
 * @var $atts array
 * @var $padding string
 * @var $enable_saved_search bool
 */

$atts = isset( $atts ) ? $atts : array();
$atts = wp_parse_args( $atts, array(
    'width' => '80%',
    'max-width' => '1180px',
) ); ?>

<form action="<?php echo $search_page_uri; ?>" method="GET" class="js-es-search ert-search" style="max-width: <?php echo $atts['max-width']; ?>; padding: <?php echo $attributes['padding']; ?>; width: <?php echo $atts['width']; ?>">

    <?php if ( ! $search_page_exists ) : ?>
        <input type="hidden" name="s"/>
        <input type="hidden" name="post_type" value="properties"/>
    <?php else: ?>
        <?php if ( ! get_option( 'permalink_structure' ) ) : ?>
            <input type="hidden" name="page_id" value="<?php echo $search_page_id; ?>"/>
        <?php endif; ?>
    <?php endif; ?>

    <input type="hidden" name="advanced_search" value="1"/>
    <input type="hidden" name="es" value="1"/>

    <?php if ( ! empty( $attributes['main_fields'] ) ) : ?>
        <div class="row">
            <?php foreach ( $attributes['main_fields'] as $field ) : ?>
                <?php do_action( 'ert4_search_render_field', $field, $attributes ); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ( ! empty( $attributes['collapsed_fields'] ) ) : ?>
        <div class="row ert-search__advanced js-ert-search__advanced hidden">
            <?php foreach ( $attributes['collapsed_fields'] as $field ) : ?>
                <?php do_action( 'ert4_search_render_field', $field, $attributes ); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="row advanced-search-control">
        <div class="col-md-4">
            <a href="#" class="advanced-search-link js-advanced-search-link"><i class="fa fa-plus"></i><?php _e( 'Advanced Search', 'ert' ); ?></a>
        </div>
        <div class="col-md-8">
            <div class="form-row">
                <div class="col ert-btn__wrap">
                    <input type="reset" class="btn btn-light form-control" value="<?php echo __( 'Reset Search', 'ert' ); ?>">
                </div>
                <div class="col ert-btn__wrap">
                    <input type="submit" class="btn btn-primary form-control" value="<?php echo __( 'Search', 'ert' ); ?>">
                </div>
                <?php if ( $attributes['enable_saved_search'] ) : ?>
                    <div class="col ert-btn__wrap">
                        <?php if ( get_current_user_id() ) : ?>
                            <input type="button" class="btn btn-secondary js-es-save-search form-control js-es-save-search" value="<?php _e( 'Save search', 'ert' ); ?>"/>
                        <?php else: ?>
                            <input data-popup-id="#es-authentication-popup" type="button" class="js-es-popup-link btn btn-secondary form-control" value="<?php _e( 'Save search', 'ert' ); ?>"/>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="es-search__messages js-es-search__messages"></div>
</form>

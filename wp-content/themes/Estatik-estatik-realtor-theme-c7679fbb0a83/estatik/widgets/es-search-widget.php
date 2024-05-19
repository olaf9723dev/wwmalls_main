<?php

/**
 * Search widget.
 *
 * @var array $instance
 * @var array $args
 * @var array $fields
 * @var Es_Search_Widget $this
 */

if ( ! class_exists( 'Ert_Search_Helper' ) )  {
	require_once get_template_directory() . '/inc/classes/class-search-helper.php';
}

$fields = Es_Search_Widget::get_widget_fields();
$page_exists = ! empty( $instance['page_id'] ) && get_post_status( $instance['page_id'] ) == 'publish';
$handler = $page_exists ? get_permalink( $instance['page_id'] ) : esc_url( home_url( '/' ) );

$single_field_class = null;

if ( ! empty( $instance['fields'] ) ) {
    $temp_fields = array_filter( $instance['fields'] );
    $single_field_class = count( $temp_fields ) == 1 && in_array( 'address', $temp_fields ) ? 'es-search__wrapper--address-only' : null;
}

echo ! empty( $args['before_widget'] ) ? $args['before_widget'] : ''; ?>

    <div class="ert-search__wrapper es-search__wrapper--<?php echo $instance['layout'] . ' ' . $single_field_class; ?>">

        <?php if ( ! empty( $instance['title'] ) ) : ?>
            <?php echo ! empty( $args['before_title'] ) ? $args['before_title'] : ''; ?>
            <?php echo apply_filters( 'widget_title', $instance['title'] ); ?>
            <?php echo ! empty( $args['after_title'] ) ? $args['after_title'] : ''; ?>
        <?php endif; ?>

        <form action="<?php echo $handler; ?>" role="search" method="get">

            <?php do_action( 'es_before_search' );

            if ( ! $page_exists ) : ?>
                <input type="hidden" name="s"/>
            <?php else: ?>
	            <?php if ( ! get_option( 'permalink_structure' ) ) : ?>
                    <input type="hidden" name="page_id" value="<?php echo $instance['page_id']; ?>"/>
	            <?php endif; ?>
            <?php endif; ?>

            <?php if ( ! empty( $instance['fields'] ) ) : ?>
                <div class="row">
                    <?php foreach ( $instance['fields'] as $name ) : ?>
                        <?php if ( in_array( $name, $fields ) ) : ?>
                            <?php echo Ert_Search_Helper::render_field( $name, $instance ); ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ( ! $page_exists ) : ?>
                <input type="hidden" name="post_type" value="<?php echo Es_Property::get_post_type_name(); ?>"/>
            <?php endif; ?>

            <div class="ert-search__buttons form-row">
                <div class="es-button__wrap col-lg-6 form-group">
                    <input type="reset" class="btn btn-gray" value="<?php _e( 'Reset', 'es-plugin' ); ?>"/>
                </div>
                <div class="es-button__wrap col-lg-6 form-group">
                    <input type="submit" class="btn btn-primary" value="<?php _e( 'Search', 'es-plugin' ); ?>"/>
                </div>
	            <?php if ( ! empty( $instance['save_search_button'] ) ) : ?>
                    <div class="es-button__wrap col-12">
			            <?php if ( get_current_user_id() ) : ?>
                            <input type="button" class="btn btn-light js-es-save-search" value="<?php _e( 'Save search', 'es-plugin' ); ?>"/>
			            <?php else: ?>
                            <input type="button" class="btn btn-light js-es-login-form" value="<?php _e( 'Save search', 'es-plugin' ); ?>"/>
			            <?php endif; ?>
                    </div>
	            <?php endif; ?>
            </div>

            <div class="es-search__messages js-es-search__messages"></div>

            <?php do_action( 'es_after_search' ); ?>

        </form>

    </div>
<?php echo ! empty( $args['after_widget'] ) ? $args['after_widget'] : '';

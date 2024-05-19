<?php

/**
 * @var $atts array
 */

global $es_settings;

if ( ! class_exists( 'Ert_Search_Helper' ) )  {
	require_once get_template_directory() . '/inc/classes/class-search-helper.php';
}

$page_exists = ! empty( $atts['page_id'] ) && get_post_status( $atts['page_id'] ) == 'publish';
$handler = $page_exists ? get_permalink( $atts['page_id'] ) : esc_url( home_url( '/' ) ); ?>

<form action="<?php echo $handler; ?>" method="GET" class="ert-search" style="max-width: <?php echo $atts['max-width']; ?>; padding: <?php echo $atts['padding']; ?>; width: <?php echo $atts['width']; ?>">

    <?php if ( ! $page_exists ) : ?>
        <input type="hidden" name="s"/>
        <input type="hidden" name="post_type" value="<?php echo Es_Property::get_post_type_name(); ?>"/>
    <?php else: ?>
	    <?php if ( ! get_option( 'permalink_structure' ) ) : ?>
            <input type="hidden" name="page_id" value="<?php echo $atts['page_id']; ?>"/>
	    <?php endif; ?>
    <?php endif; ?>

    <input type="hidden" name="advanced_search" value="1"/>

	<?php if ( ! empty( $atts['visible_fields'] ) ) : ?>
        <div class="row">
	        <?php foreach ( $atts['visible_fields'] as $field ) : ?>
                <?php echo Ert_Search_Helper::render_field( $field, array( 'layout' => 'horizontal' ) ) ?>
	        <?php endforeach; ?>
        </div>
	<?php endif; ?>

	<?php if ( ! empty( $atts['advanced_fields'] ) ) : ?>
        <div class="row ert-search__advanced js-ert-search__advanced hidden">
			<?php foreach ( $atts['advanced_fields'] as $field ) : ?>
				<?php echo Ert_Search_Helper::render_field( $field, array( 'layout' => 'horizontal' ) ) ?>
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
	            <?php if ( ! empty( $atts['save_search_button'] ) ) : ?>
                    <div class="col ert-btn__wrap">
			            <?php if ( get_current_user_id() ) : ?>
                            <input type="button" class="btn btn-secondary js-es-save-search form-control" value="<?php _e( 'Save search', 'ert' ); ?>"/>
			            <?php else: ?>
                            <input type="button" class="btn btn-secondary js-es-login-form form-control" value="<?php _e( 'Save search', 'ert' ); ?>"/>
			            <?php endif; ?>
                    </div>
	            <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="es-search__messages js-es-search__messages"></div>
</form>

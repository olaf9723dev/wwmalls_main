<?php

/**
 * @var $container_classes string
 * @var $args array
 * @var $attributes array
 * @var $search_page_uri string
 * @var $search_page_exists bool
 * @var $search_page_id int
 */

?>
<div class="<?php echo $container_classes; ?>">
    <form action="<?php echo $search_page_uri; ?>" role="search" method="get">

        <?php do_action( 'es_before_search' ); ?>

        <input type="hidden" name="es" value="1"/>

	    <?php if ( ! $search_page_exists ) : ?>
            <input type="hidden" name="s"/>
            <input type="hidden" name="post_type" value="properties"/>
	    <?php else: ?>
		    <?php if ( ! get_option( 'permalink_structure' ) ) : ?>
                <input type="hidden" name="page_id" value="<?php echo $search_page_id; ?>"/>
		    <?php endif; ?>
	    <?php endif; ?>

        <?php if ( ! empty( $attributes['title'] ) ) : ?>
            <h3><?php echo $attributes['title']; ?></h3>
        <?php endif; ?>

        <?php if ( ! empty( $attributes['fields'] ) ) : ?>
            <div class="row">
                <?php foreach ( $attributes['fields'] as $field ) :
                    if ( 'address' == $field && empty( $attributes['is_address_search_enabled'] ) ) continue; ?>
                    <?php do_action( 'ert4_search_render_field', $field, $attributes ); ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="ert-search__buttons form-row">
            <div class="es-button__wrap col-lg-6 form-group">
                <input type="reset" class="btn btn-gray" value="<?php _e( 'Reset', 'es-plugin' ); ?>"/>
            </div>
            <div class="es-button__wrap col-lg-6 form-group">
                <input type="submit" class="btn btn-primary" value="<?php _e( 'Search', 'es-plugin' ); ?>"/>
            </div>
            <?php if ( ! empty( $attributes['enable_saved_search'] ) ) : ?>
                <div class="es-button__wrap col-12">
                    <?php if ( get_current_user_id() ) : ?>
                        <button data-label="<?php _e( 'Save search', 'es' ); ?>" disabled data-nonce="<?php echo wp_create_nonce( 'es_save_search' ); ?>" type="button" class="btn btn-light js-es-save-search"><?php _e( 'Save search', 'es' ); ?></button>
                    <?php else: ?>
                        <a href="#" data-popup-id="#es-authentication-popup" type="button" class="btn btn-light js-es-popup-link"><?php _e( 'Save search', 'es' ); ?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </form>
</div>

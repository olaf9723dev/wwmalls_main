<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

get_header(); ?>
<div class="ert-404-container">
    <h2><?php _e( 'Error 404', 'ert' ); ?></h2>
    <div class="es-404-content">
        <h3><?php _e( 'Opps! Page Not Found.', 'ert' ); ?></h3>
        <p><?php _e( 'We couldn\'t find the page you were looking for.', 'ert' ); ?></p>
    </div>
    <a href="<?php echo home_url(); ?>" class="btn btn-primary"><?php _e( 'Back to home', 'ert' ); ?></a>
</div>
<?php get_footer();

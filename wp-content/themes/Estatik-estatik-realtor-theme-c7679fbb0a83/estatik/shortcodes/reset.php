<?php

global $es_settings;

$messenger = new Es_Messenger( 'login' );
$key = sanitize_text_field( filter_input( INPUT_GET, '_key' ) );
$login = sanitize_text_field( filter_input( INPUT_GET, '_login' ) );

$redirect = ! empty( $es_settings->login_page_id ) && get_post( $es_settings->login_page_id ) ?
    get_permalink( $es_settings->login_page_id ) : get_the_permalink(); ?>

<?php if ( ! is_user_logged_in() ) : ?>
	<div class="es-login__wrap">
		<h2><?php _e( 'Restore password', 'es-plugin' ); ?></h2>
		<form action="" method="post">

            <?php if ( $messages_list = $messenger->get_messages() ) : ?>
                <?php foreach ( $messages_list as $type => $messages ) {
                    if ( ! empty( $messages ) ) {
                        $type = $type == 'error' ? 'danger' : $type;
                        foreach ( $messages as $message ) {
                            echo '<div class="alert alert-' . $type .'" >' . $message . '</div>';
                        }
                    }
                }
                $messenger->clean_container(); ?>
            <?php endif; ?>

            <div class="form-group">
                <?php if ( $key && $login ) : ?>
                    <input type="password" required name="pwd" class="form-control" placeholder="<?php _e( 'Enter new password', 'es-plugin' ); ?>">
                <?php else: ?>
                    <input type="text" name="user_login" class="form-control" placeholder="<?php _e( 'Username or email address', 'es-plugin' ); ?>">
                <?php endif; ?>
            </div>

            <?php if ( $key && $login ) : ?>
                <input type="hidden" name="_login" value="<?php echo esc_attr( $login ); ?>"/>
                <input type="hidden" name="_key" value="<?php echo esc_attr( $key ); ?>"/>
            <?php endif; ?>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="<?php _e( 'Get new password', 'es-plugin' ); ?>"/>
            </div>

			<?php wp_nonce_field( 'es-restore-pwd', 'es-restore-pwd' ); ?>
			<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ); ?>" />

			<?php if ( $key && $login ) : ?>
				<input type="hidden" name="action" value="fill_password"/>
			<?php endif; ?>
		</form>
	</div>
<?php else: ?>
    <div class="es-agent-register__logged">
        <?php _e( 'You are already logged in.', 'es-plugin' ); ?><br>
        <a href="<?php echo wp_logout_url( get_the_permalink() ); ?>" class="btn btn-primary"><?php _e( 'Logout', 'es-plugin' ); ?></a>
    </div>
<?php endif;

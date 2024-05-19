<?php global $es_settings; $messenger = new Es_Messenger( 'login' ); ?>

<?php if ( ! is_user_logged_in() ) : ?>
	<div class="es-login__wrap">

		<h2><?php _e( 'Sign In to your Account', 'ert' ); ?></h2>

		<form action="" method="post">

			<?php $messenger->render_messages(); ?>

			<div class="form-group">
				<input type="text" name="log" class="form-control" id="es-user-login" placeholder="<?php _e( 'Username', 'es-plugin' ); ?>" required/>
			</div>

			<div class="form-group">
				<input type="password" name="pwd" class="form-control" id="es-user-pwd" placeholder="<?php _e( 'Password', 'es-plugin' ); ?>" required/>
			</div>

            <div class="form-group text-right forgot-pwd">
	            <?php if ( $es_settings->reset_password_page_id && ( get_post( $es_settings->reset_password_page_id ) ) ) : ?>
                    <a href="<?php echo get_the_permalink( $es_settings->reset_password_page_id ); ?>"><?php _e( 'Forgot a Password?', 'ert' ); ?></a><br/>
	            <?php endif; ?>
            </div>

			<?php if ( $es_settings->prop_management_page_id && ( get_post( $es_settings->prop_management_page_id ) ) ) : ?>
                <input type="hidden" name="redirect_to" value="<?php echo get_the_permalink( $es_settings->prop_management_page_id ); ?>" />
			<?php endif; ?>

            <input type="hidden" name="redirect" value="<?php the_permalink(); ?>" />

			<?php wp_nonce_field( 'es-login', 'es-login' ); ?>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="<?php _e( 'Sign In', 'ert' ); ?>"/>
                <?php do_action( 'es_login_after_submit_button', $atts ); ?>
            </div>
		</form>
	</div>
<?php else: ?>
	<div class="es-agent-register__logged">
		<?php _e( 'You are already logged in.', 'es-plugin' ); ?><br>
		<a href="<?php echo wp_logout_url( get_the_permalink() ); ?>" class="es-agent__logout btn btn-primary"><?php _e( 'Logout', 'es-plugin' ); ?></a>
	</div>
<?php endif; ?>



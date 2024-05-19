<?php

/**
 * @var $args array
 * @var $instance array
 */

global $post, $es_settings, $es_property;

$args = ! empty( $args ) ? $args : array();
$args = wp_parse_args( $args, array(
    'before_widget' => '',
    'after_widget' => '',
) );

$terms_of_use = __( 'Terms of Use' ,'es-plugin' );
$privacy_policy = __( 'Privacy Policy' ,'es-plugin' );

$terms_of_use = $es_settings->term_of_use_page_id && get_permalink( $es_settings->term_of_use_page_id ) ?
	"<a href='" . get_permalink( $es_settings->term_of_use_page_id ) . "' target='_blank'>{$terms_of_use}</a>" : $terms_of_use;

$privacy_policy = $es_settings->privacy_policy_page_id && get_permalink( $es_settings->privacy_policy_page_id ) ?
	"<a href='" . get_permalink( $es_settings->privacy_policy_page_id ) . "' target='_blank'>{$privacy_policy}</a>" : $privacy_policy;

$post_id = ! empty( $post ) ? $post->ID : null;

$message = ! empty( $instance['message'] ) ? $instance['message'] : null;
$send_to = ! empty( $instance['send_to'] ) ? $instance['send_to'] : null;
$subject = ! empty( $instance['subject'] ) ? $instance['subject'] : __( 'Estatik Request Info from', 'es-plugin' );
$disable_tel = ! empty( $instance['disable_tel'] ) ? $instance['disable_tel'] : false;
$disable_name = ! empty( $instance['disable_name'] ) ? $instance['disable_name'] : false;
$send_to_emails = ! empty( $instance['custom_email'] ) ? $instance['custom_email'] : false;

echo $args['before_widget']; ?>

	<?php if ( ! empty( $instance['title'] ) ) : ?>
		<h3 class="widgettitle"><?php echo $instance['title']; ?></h3>
	<?php endif; ?>

	<div class="es-request-widget-wrap">

		<?php if ( $es_settings->show_agent_tab && $es_property && ( class_exists( 'Es_Agent' ) && ( $agent = $es_property->get_agent() ) ) ) : ?>
			<?php include locate_template( 'template-parts/partials/agent-partial.php' ); ?>
		<?php endif; ?>

		<form action="" method="POST">

			<?php if ( ! $disable_name ) : ?>
				<div class="form-group ert-form-group">
					<input type="text" name="name" class="form-control ert-control" id="request-form-name" placeholder="<?php _e( 'Your Name', 'ert' ); ?>"/>
				</div>
			<?php endif; ?>

			<div class="form-group ert-form-group">
				<input type="email" name="email" class="form-control ert-control" id="request-form-email" placeholder="<?php _e( 'Your Email', 'ert' ); ?>" required/>
			</div>

			<?php if ( ! $disable_tel ) : ?>
				<div class="form-group ert-form-group">
					<input type="tel" name="tel" class="form-control ert-control" id="request-form-tel" placeholder="<?php _e( 'Phone number', 'ert' ); ?>" required/>
				</div>
			<?php endif; ?>

			<div class="form-group ert-form-group">
				<textarea name="message" class="form-control ert-control" id="request-form-message" required><?php echo $message; ?></textarea>
			</div>

			<?php if ( $es_settings->privacy_policy_checkbox == 'required' ) : ?>
                <div class="form-check">
                    <input type="checkbox" required class="form-check-input ert-checkbox-styled" name="agree_terms" value="1" id="agree_terms-checkbox">
                    <label class="form-check-label" for="agree_terms-checkbox"><?php printf( __( 'I agree to the %s and %s', 'es-plugin' ), $terms_of_use, $privacy_policy ); ?></label>
                </div>
			<?php endif; ?>

			<?php wp_nonce_field( 'es_request_send', 'es_request_send' ); ?>

			<div class="es-captcha">
				<?php do_action( 'es_recaptcha' ); ?>
			</div>

			<input type="hidden" name="action" value="es_request_send"/>
			<input type="hidden" name="send_to" value="<?php echo esc_attr( $send_to ); ?>"/>
			<?php if ( $send_to_emails ) : ?>
                <input type="hidden" name="send_to_emails" value="<?php echo esc_attr( $send_to_emails ); ?>"/>
			<?php endif; ?>
			<input type="hidden" name="post_id" value="<?php echo esc_attr( $post_id ); ?>"/>
			<input type="hidden" name="subject" value="<?php echo esc_attr( $subject ); ?>"/>

            <div class="text-right">
			    <input type="submit" class="btn btn-light" value="<?php _e( 'Send a message', 'ert' ); ?>"/>
            </div>

		</form>

		<div class="es-response-block"></div>

	</div>

<?php echo $args['after_widget'];
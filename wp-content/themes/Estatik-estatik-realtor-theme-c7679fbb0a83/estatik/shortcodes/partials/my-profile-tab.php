<?php global $es_settings;

$entity = es_get_user_entity( get_current_user_id() );
$user = $entity->get_entity();

$terms_of_use = __( 'Terms of Use' ,'es-plugin' );
$privacy_policy = __( 'Privacy Policy' ,'es-plugin' );

$terms_of_use = $es_settings->term_of_use_page_id && get_permalink( $es_settings->term_of_use_page_id ) ?
	"<a href='" . get_permalink( $es_settings->term_of_use_page_id ) . "' target='_blank'>{$terms_of_use}</a>" : $terms_of_use;

$privacy_policy = $es_settings->privacy_policy_page_id && get_permalink( $es_settings->privacy_policy_page_id ) ?
	"<a href='" . get_permalink( $es_settings->privacy_policy_page_id ) . "' target='_blank'>{$privacy_policy}</a>" : $privacy_policy;

$image_url = $entity->get_image_url( 'es-agent-size' );
$image_url = $image_url ? $image_url : ES_PLUGIN_URL . '/assets/images/agent.png'; ?>

<h3 class="es-profile__tab-title"><?php _e( 'Edit Your Profile', 'ert' ); ?></h3>

<form method="POST" action="" enctype="multipart/form-data">
	<?php wp_nonce_field( 'es_save_profile', 'es_save_profile' ); ?>

	<div class="ert-profile">

		<div class="row">
			<div class="col-md-6 form-group">
				<input type="text" class="form-control" placeholder="<?php _e( 'Name', 'ert' ); ?>" id="es-field-name" name="es_profile[name]" value="<?php echo $entity->get_full_name(); ?>"/>
			</div>
			<div class="col-md-6 form-group">
				<input type="text" class="form-control" placeholder="<?php _e( 'Username', 'ert' ); ?>" value="<?php echo $user->user_login; ?>" readonly id="es-field-username" name="user_login"/>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6 form-group">
				<input type="text" class="form-control" id="es-field-email" placeholder="<?php _e( 'Email', 'ert' ); ?>" readonly name="email" value="<?php echo $user->user_email; ?>"/>
			</div>
			<div class="col-md-6 form-group">
				<input type="text" class="form-control" id="es-field-telephone" placeholder="<?php _e( 'Phone number', 'ert' ); ?>" name="es_profile[tel]" value="<?php echo $entity->tel; ?>"/>
			</div>
		</div>

		<?php if ( $entity instanceof Es_Agent ) : ?>
            <div class="row">
                <div class="col-md-6 form-group">
                    <input type="text" class="form-control" id="es-field-company" placeholder="<?php _e( 'Company', 'ert' ); ?>" name="es_profile[company]" value="<?php echo $entity->company; ?>"/>
                </div>
                <div class="col-md-6 form-group">
                    <input type="text" class="form-control" id="es-field-www" name="url" placeholder="<?php _e( 'Company Website', 'ert' ); ?>"  value="<?php echo $user->user_url; ?>"/>
                </div>
            </div>
        <?php endif; ?>

		<div class="row">
			<div class="col-md-6 form-group">
				<input type="text" class="form-control" placeholder="<?php _e( 'Password', 'ert' ); ?>" id="es-field-password" name="pass1" value=""/>
			</div>
			<div class="col-md-6 form-group">
				<input type="text" class="form-control" placeholder="<?php _e( 'Confirm Password', 'ert' ); ?>" id="es-field-password2" name="pass2" value=""/>
			</div>
		</div>

		<div class="row">
			<div class="col-12 form-group">
				<textarea id="es-field-description" placeholder="<?php _e( 'About', 'ert' ); ?>" class="form-control" name="description"><?php echo esc_textarea( $user->description ); ?></textarea>
			</div>
		</div>

		<div class="row es-field__photo form-group">
			<div class="col-auto ert-label"><?php _e( 'Photo/Logo', 'ert' ); ?>:</div>
			<div class="col-auto ert-content">
				<div class="js-es-image">
                    <img src="<?php echo $image_url; ?>" alt="<?php echo __( 'Profile image', 'es-plugin' ); ?>">
                </div>
				<a href="#" class="js-trigger-upload" data-selector="#es-file-input">
					<i class="ert-icon ert-icon_upload"></i>
					<span><?php _e( 'Upload photo', 'es-plugin' ); ?></span>
				</a>
                <?php if ( $entity->profile_attachment_id ) : ?>
                    <input type="hidden" name="es_profile[profile_attachment_id]" value="<?php echo $entity->profile_attachment_id; ?>"/>
                <?php endif; ?>
				<input type="file" name="agent_photo" id="es-file-input" class="js-es-input-image"/>
			</div>
		</div>

		<input type="submit" class="btn btn-primary" value="<?php _e( 'Save edits', 'ert' ); ?>"/>
	</div>
</form>

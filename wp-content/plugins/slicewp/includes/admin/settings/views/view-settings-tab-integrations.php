<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<?php 

	/**
	 * Hook to add extra cards if needed to the Integrations tab
	 *
	 */
	do_action( 'slicewp_view_settings_tab_integrations_top' );

?>

<div class="slicewp-card">

	<?php foreach( slicewp()->integrations as $integration_slug => $integration ): ?>

		<?php if ( empty( $integration->get( 'supports' ) ) ) continue; ?>

		<div class="slicewp-card-integration-row">

			<!-- Integration Activation Switch -->
			<div class="slicewp-card-integration-switch">
				
				<div class="slicewp-switch">

					<input id="slicewp-integration-switch-<?php echo $integration_slug; ?>" class="slicewp-toggle slicewp-toggle-round" name="settings[active_integrations][]" type="checkbox" value="<?php echo $integration_slug; ?>" <?php checked( ! empty( $_POST['settings']['active_integrations'] ) && in_array( $integration_slug, $_POST['settings']['active_integrations'] ) ? '1' : ( empty( $_POST ) ? ( slicewp_is_integration_active( $integration_slug ) ? '1' : '' ) : '' ), '1' ); ?> data-supports="<?php echo htmlspecialchars( json_encode( $integration->get( 'supports' ) ), ENT_QUOTES, 'UTF-8' ); ?>" />
					<label for="slicewp-integration-switch-<?php echo $integration_slug; ?>"></label>

				</div>

			</div>

			<!-- Integration Name -->
			<div class="slicewp-card-integration-name">
				<?php echo $integration->get('name'); ?>
			</div>

		</div>

	<?php endforeach; ?>

</div>

<!-- reCAPTCHA -->
<div class="slicewp-card">

	<div class="slicewp-card-header">
		<span class="slicewp-card-title"><?php echo __( 'reCAPTCHA', 'slicewp' ); ?></span>

		<div class="slicewp-card-actions">
			<a href="https://slicewp.com/docs/google-recaptcha/" target="_blank" class="slicewp-button-info" title="<?php echo esc_attr( __( 'Click to learn more...', 'slicewp' ) ); ?>"><svg height="18" width="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g><path d="M13 9h-2V7h2v2zm0 2h-2v6h2v-6zm-1-7c-4.41 0-8 3.59-8 8s3.59 8 8 8 8-3.59 8-8-3.59-8-8-8m0-2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2z"></path></g></svg></a>
		</div>
	</div>

	<div class="slicewp-card-inner">

		<!-- Enable reCAPTCHA -->
		<div class="slicewp-field-wrapper slicewp-field-wrapper-inline">

			<div class="slicewp-field-label-wrapper">
				<label for="slicewp-enable-recaptcha">
					<?php echo __( 'Enable reCAPTCHA', 'slicewp' ); ?>
					<?php echo slicewp_output_tooltip( __( 'Adds a reCAPTCHA widget to affiliate specific forms to protect you against spam.' , 'slicewp' ) . '<hr />' . '<a href="https://slicewp.com/docs/google-recaptcha/" target="_blank">' . __( 'Click here to learn more', 'slicewp' ) . '</a>' ); ?>
				</label>
			</div>

			<div class="slicewp-switch">

				<input id="slicewp-enable-recaptcha" class="slicewp-toggle slicewp-toggle-round" name="settings[enable_recaptcha]" type="checkbox" value="1" <?php checked( ! empty( $_POST['settings']['enable_recaptcha'] ) ? esc_attr( $_POST['settings']['enable_recaptcha'] ) : ( empty( $_POST ) ? slicewp_get_setting( 'enable_recaptcha' ) : '' ), '1' ); ?> />
				<label for="slicewp-enable-recaptcha"></label>

			</div>

			<label for="slicewp-enable-recaptcha"><?php echo __( 'Enable Google reCAPTCHA on affiliate forms.', 'slicewp' ); ?></label>

		</div><!-- / Enable reCAPTCHA -->

		<!-- Site Key -->
		<div class="slicewp-field-wrapper slicewp-field-wrapper-inline">

			<div class="slicewp-field-label-wrapper">
				<label for="slicewp-recaptcha-site-key">
					<?php echo __( 'Site Key', 'slicewp' ); ?>
				</label>
			</div>

			<input id="slicewp-recaptcha-site-key" name="settings[recaptcha_site_key]" type="text" value="<?php echo esc_attr( ! empty( $_POST['settings']['recaptcha_site_key'] ) ? $_POST['settings']['recaptcha_site_key'] : ( empty( $_POST ) ? slicewp_get_setting( 'recaptcha_site_key' ) : '' ) ); ?>">

		</div><!-- / Site Key -->

		<!-- Secret Key -->
		<div class="slicewp-field-wrapper slicewp-field-wrapper-inline slicewp-last">

			<div class="slicewp-field-label-wrapper">
				<label for="slicewp-recaptcha-secret-key">
					<?php echo __( 'Secret Key', 'slicewp' ); ?>
				</label>
			</div>

			<input id="slicewp-recaptcha-secret-key" name="settings[recaptcha_secret_key]" type="text" value="<?php echo esc_attr( ! empty( $_POST['settings']['recaptcha_secret_key'] ) ? $_POST['settings']['recaptcha_secret_key'] : ( empty( $_POST ) ? slicewp_get_setting( 'recaptcha_secret_key' ) : '' ) ); ?>">

		</div><!-- / Secret Key -->

	</div>

</div><!-- / reCAPTCHA -->

<?php 

	/**
	 * Hook to add extra cards if needed to the Integrations tab
	 *
	 */
	do_action( 'slicewp_view_settings_tab_integrations_bottom' );

?>

<!-- Save Settings Button -->
<input type="submit" class="slicewp-form-submit slicewp-button-primary" value="<?php echo __( 'Save Settings', 'slicewp' ); ?>" />
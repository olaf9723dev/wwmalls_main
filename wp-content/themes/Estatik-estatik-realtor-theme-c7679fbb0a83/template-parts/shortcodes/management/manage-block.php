<?php
global $es_settings;
$can_manage = current_user_can( 'publish_es_properties' )
    || current_user_can( 'edit_es_properties' )
    || current_user_can( 'delete_es_properties' );
if ( $can_manage ) : ?>
	<div class="es-manage__wrap">
		<div class="row align-items-center">
			<div class="col">
				<div class="es-manage-buttons__wrap">
					<button type="button" class="es-link es-link-check icon-checkbox js-es-select-deselect-all">
						<span class="es-checkbox"></span><?php _e( 'Select / deselect all', 'es-plugin' ); ?>
					</button>

					<?php if ( $user->has_cap( 'edit_es_properties' ) ) : ?>
						<button type="submit" data-error="<?php _e( 'Please select properties you want to copy.', 'es-plugin' ); ?>"
						        data-confirm="<?php _e( 'Are you sure you want to copy these items?', 'es-plugin' ); ?>"
						        data-action="copy"
						        class="es-link es-button-copy js-es-copy">
							<i class="fa fa-clone" aria-hidden="true"></i>
							<?php _e( 'Copy', 'es-plugin' ); ?>
						</button>
					<?php endif; ?>

					<?php if ( $user->has_cap( 'delete_es_properties' ) ) : ?>
						<button type="submit" data-error="<?php _e( 'Please select properties you want to delete.', 'es-plugin' ); ?>"
						        data-confirm="<?php _e( 'Are you sure you want to delete these items?', 'es-plugin' ); ?>"
						        data-action="delete" class="es-link es-button-delete js-es-delete">
							<i class="fa fa-trash-o" aria-hidden="true"></i>
							<?php _e( 'Delete', 'es-plugin' ); ?>
						</button>
					<?php endif; ?>

					<?php if ( $user->has_cap( 'publish_es_properties' ) && ( $es_settings->listings_publishing != 'manual' || current_user_can( 'edit_others_es_properties' ) ) ) : ?>
						<button type="submit" data-error="<?php _e( 'Please select properties you want to publish.', 'es-plugin' ); ?>"
						        data-confirm="<?php _e( 'Are you sure you want to publish these items?', 'es-plugin' ); ?>"
						        data-action="publish" class="es-link es-button-publish js-es-publish">
							<i class="fa fa-eye" aria-hidden="true"></i><?php _e( 'Publish', 'es-plugin' ); ?>
						</button>
					<?php endif; ?>

					<button type="submit" data-error="<?php _e( 'Please select properties you want to unpublish.', 'es-plugin' ); ?>"
					        data-confirm="<?php _e( 'Are you sure you want to unpublish these items?', 'es-plugin' ); ?>"
					        data-action="unpublish" class="es-link es-button-unpublish js-es-unpublish">
						<i class="fa fa-eye-slash" aria-hidden="true"></i><?php _e( 'Unpublish', 'es-plugin' ); ?>
					</button>
				</div>

				<input type="hidden" name="es-action"/>
				<?php wp_nonce_field( 'manage_actions', 'manage_actions' ); ?>
				<input type="hidden" name="_redirect" value="<?php the_permalink(); ?>"/>

				<div class="es-confirm-popup"></div>
				<div class="es-message-popup"></div>
			</div>

			<div class="col-auto">
				<div class="form-group es-manage-add__wrap">
					<input type="submit" class="btn btn-primary" value="<?php _e( 'Search', 'es-plugin' ); ?>"/>
					<a href="<?php the_permalink(); ?>" class="btn btn-light es-reset-filter">
						<?php _e( 'Reset', 'es-plugin' ); ?></a>
				</div>
			</div>
		</div>
	</div>
<?php endif;

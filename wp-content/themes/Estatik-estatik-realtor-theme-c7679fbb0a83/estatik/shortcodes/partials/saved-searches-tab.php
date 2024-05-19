<?php

/**
 * @var $query WP_Query
 * @var $entity Es_Saved_Search
 */

global $es_settings; ?>

<h3 class="es-profile__tab-title"><?php _e( 'Saved Searches', 'es-plugin' ); ?></h3>
<p class="es-profile__subtitle">
	<?php _e( 'Your saved searches can be found here. You can delete or view new listings matching your searches.', 'es-plugin' ); ?>
</p>

<?php if ( $query->have_posts() ) : ?>

	<div class="row">
		<?php while ( $query->have_posts() ) :
			$query->the_post();
			$entity = es_get_saved_search( get_the_ID() );
			$title = get_the_title() ? get_the_title() : sprintf( __( 'Saved Search #%s', 'es-plugin' ), get_the_ID() ); ?>

			<div class="col-md-4 ert-saved-search">
				<div class="ert-saved-search__inner es-saved-search__inner">
					<h4><?php echo $title; ?></h4>

					<?php if ( $entity->fields ) : ?>
						<ul class="ert-saved-search__fields">
							<?php foreach ( $entity->fields as $field ) : ?>
								<li>
									<span><?php echo $entity->get_field_label( $field ); ?>: </span>
									<b><?php echo $entity->get_formatted_field( $field ); ?></b>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<div class="row es-radio-container">
						<?php foreach ( $entity::get_periods() as $period => $label ) : ?>
							<div class="col-12">
								<div class="form-check checkbox checkbox-circle">
									<input
										<?php checked( $entity->update_method, $period ); ?>
										data-id="<?php echo $entity->getID(); ?>"
										id="es-period-<?php echo $period; ?>-<?php echo $entity->getID(); ?>"
										type="radio"
										value="<?php echo $period; ?>"
										name="saved_search[update_method][<?php echo $entity->getID(); ?>]"
										class="form-check-input js-es-change-update-method">
									<label for="es-period-<?php echo $period; ?>-<?php echo $entity->getID(); ?>" class="form-check-label"><span><?php echo $label; ?></span></label>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<div class="es-msg-container"></div>
				</div>
				<div class="ert-saved-search__footer">
					<a class="ert-saved-search__delete" href="<?php echo $entity->delete_url(); ?>"><?php _e( 'Delete Search', 'es-plugin' ); ?></a>
					<?php if ( $url = $entity->view_properties_url() ) : ?>
						<a class="btn btn-light" href="<?php echo $url; ?>" target="_blank"><?php _e( 'View Listings', 'es-plugin' ); ?></a>
					<?php endif; ?>
				</div>
			</div>
		<?php endwhile; wp_reset_postdata(); ?>
	</div>

<?php endif;

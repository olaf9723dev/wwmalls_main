<?php

/**
 * @var $properties WP_Query
 * @var $instance Es_Profile_Shortcode
 */

$atts['layout'] = '3_col'; ?>

<h3 class="es-profile__tab-title"><?php _e( 'Saved Homes', 'es-plugin' ); ?></h3>
<p class="es-profile__subtitle"><?php _e( 'Your saved homes can be found here. You can view details or delete your favourite listings.', 'es-plugin' ); ?></p>

<?php if ( $properties->have_posts() ) : ?>

	<?php do_action( "es_shortcode_before_" . $instance->get_shortcode_name() . "_loop" ); ?>

    <div class="ert-listing ert-layout-2_col row">
		<?php while ( $properties->have_posts() ) : $properties->the_post(); $atts['layout'] = '2_col'; ?>
			<?php include es_locate_template( 'content-archive.php' ); ?>
		<?php endwhile; ?>
    </div>

	<?php echo es_the_pagination( $properties, array(
		'type' => 'list',
		'prev_text'    => __( 'Prev', 'ert' ),
		'next_text'    => __( 'Next', 'ert' ),
	) ); ?>

	<?php do_action( "es_shortcode_after_" . $instance->get_shortcode_name() . "_loop" ); ?>
	<?php wp_reset_postdata(); ?>
<?php else: ?>
	<p style="margin-top: 20px;"><?php _e( 'Nothing to display', 'es-plugin' ); ?></p>
<?php endif;

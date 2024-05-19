<?php

/**
 * @var $properties WP_Query
 */

if ( ! empty( $_GET['layout'] ) ) {
	$atts['layout'] = $_GET['layout'];
}

if ( $properties->have_posts() ) :

    $shortcode_identifier = ! empty( $properties->properties_loop_identifier ) ?
        $properties->properties_loop_identifier : '';?>

	<?php do_action( "es_shortcode_before_" . $this->get_shortcode_name() . "_loop" ); ?>

	<div class="<?php echo get_option( 'template' ); ?>">

        <div class="ert-listing__wrapper">

            <?php if ( ! empty( $atts['show_filter'] ) ) : ?>
                <?php do_action( 'ert_archive_sorting_dropdown', $shortcode_identifier, $atts['sort'] ); ?>
            <?php endif; ?>

            <div class="ert-listing ert-layout-<?php echo $atts['layout']; ?> row">
                <?php while ( $properties->have_posts() ) : $properties->the_post(); ?>
                    <?php include es_locate_template( 'content-archive.php' ); ?>
                <?php endwhile; ?>
            </div>

            <?php echo es_the_pagination( $properties, array(
                'type' => 'list',
                'prev_text'    => __( 'Prev', 'ert' ),
                'next_text'    => __( 'Next', 'ert' ),
            ) ); ?>

        </div>
	</div>
	<?php do_action( 'es_shortcode_list_after' ); ?>
	<?php do_action( "es_shortcode_after_" . $this->get_shortcode_name() . "_loop" ); ?>
	<?php wp_reset_postdata(); ?>
<?php else: ?>
	<?php _e( 'Nothing to display', 'es-plugin' ); ?>
<?php endif;

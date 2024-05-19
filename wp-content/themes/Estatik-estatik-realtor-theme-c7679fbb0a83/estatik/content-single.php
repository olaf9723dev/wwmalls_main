<?php
/**
 * @var Es_Property $es_property
 * @var Es_Settings_Container $es_settings
 */

global $es_settings; $es_property = es_get_property( get_the_ID() ); ?>

<?php do_action( 'es_before_single_content' ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php do_action( 'es_single_fields' ); ?>

	<?php if ( $sections = Es_Property_Single_Page::get_sections() ) : ?>
		<?php foreach ( $sections as $id => $section ) : ?>
			<?php if ( 'es-info' == $id ) continue; ?>
			<?php if ( ! empty( $section['render_action'] ) ) : ?>
				<?php do_action( $section['render_action'], $id, $section ); ?>
			<?php else: ?>
				<?php do_action( 'es_single_render_tab', $id, $section ); ?>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>

	<?php do_action( 'es_property_after_tabs', $es_property ); ?>
</article>

<?php do_action( 'es_after_single_content' );

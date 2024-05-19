<?php
/**
 * @var Es_Property $es_property
 * @var Es_Settings_Container $es_settings
 */

$es_property = es_get_property( get_the_ID() );
do_action( 'es_before_single_content' ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php $instance = es_get_sections_builder_instance();
    if ( $sections = $instance::get_items( 'property' ) ) :
        foreach ( $sections as $section ) :
            do_action( 'es_single_property_section', $section, get_the_ID() );
        endforeach;
    endif;
    do_action( 'es_property_after_tabs', $es_property ); ?>
</article>

<?php
do_action( 'es_after_single_property_content' );
do_action( 'es_after_single_content' );
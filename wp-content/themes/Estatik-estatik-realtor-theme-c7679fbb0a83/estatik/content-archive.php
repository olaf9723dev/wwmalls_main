<?php $layout = ! empty( $atts['layout'] ) ? ert_property_item_layout( $atts['layout'] ) : ert_property_item_layout(); ?>

<div class="<?php echo $layout; ?> ert-property-item properties">
    <?php include locate_template( 'estatik/content-archive-inner.php', false ); ?>
</div>

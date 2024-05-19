<?php

/**
 * @var $machine_name string
 * @var $content string
 * @var $label string
 */

if ( ! empty( $content ) ) : $machine_name = 'basic-facts' == $machine_name ? 'es-basic-info' : $machine_name; ?>
    <div class="es-tabbed-item <?php echo $machine_name; ?>" id="<?php echo $machine_name; ?>">
        <?php if ( ! empty( $label ) ) : ?>
            <h3><?php echo $label; ?></h3>
        <?php endif; ?>
        <div class="es-property-section__content">
            <?php echo $content; ?>
        </div>
    </div>
<?php endif;

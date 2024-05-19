<?php

$property = es_get_property( get_the_ID() );
$fields = es_property_get_meta_fields();

if ( ! empty( $fields ) ) : ?><div class="row ert-property-item__fields"><?php
	foreach ( $fields as $field ) :
		if ( ! empty( $field['enabled'] ) && ! empty( $property->{ $field['field'] } )  ) : ?>
            <div class="col-auto">
				<?php if ( ! empty( $use_icons ) ) : ?>
					<?php if ( ! empty( $field['svg'] ) ) : ?>
						<?php echo $field['svg']; ?>
					<?php elseif ( ! empty( $field['icon'] ) ) : ?>
                        <span class="es-meta-icon es-meta-icon--<?php echo $field['field']; ?>" style="background-image: url(<?php echo $field['icon']; ?>);"></span>
					<?php endif; ?>
				<?php endif; ?>
				<span><?php es_the_formatted_field( $field['field'] ); ?></span>
            </div>
		<?php endif;
	endforeach;
	?></div><?php
endif;

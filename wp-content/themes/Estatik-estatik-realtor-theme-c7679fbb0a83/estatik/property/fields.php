<?php if ( $fields = Es_Property_Single_Page::get_single_fields_data() ) : $i = 0; ?>
	<div class="es-tabbed-item es-basic-info" id="es-basic-info">
		<h3><?php _e( 'Quick Info', 'ert' ); ?></h3>
		<table>
			<?php foreach ( $fields as $field ) : ?>

				<?php if ( ! $i ) : ?><tr><?php endif; ?>

				<?php if ( ! empty( $field[ key( $field ) ] ) || ( isset( $field[ key( $field ) ] ) && strlen( $field[ key( $field ) ] ) ) ) : ?>
					<td>
						<span class="label"><?php echo key( $field ); ?>: </span>
						<span class="content">
                                <?php echo is_array( $field[ key( $field ) ] ) ? implode( ', ', $field[ key( $field ) ] ) : $field[ key( $field ) ]; ?>
                        </span>
					</td>
					<?php $i++; endif; ?>

				<?php if ( $i == 4 ) : ?></tr><?php $i = 0; endif; ?>

			<?php endforeach; ?>
		</table>
	</div>
<?php endif;

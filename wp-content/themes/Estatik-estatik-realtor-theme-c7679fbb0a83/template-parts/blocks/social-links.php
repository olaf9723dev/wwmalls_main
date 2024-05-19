<?php

/**
 * @var $use_wrapper book
 */

if ( ! empty( $use_wrapper ) ) : ?>
	<div class="ert-social">
<?php endif; ?>

<?php foreach ( $data as $key => $value ) : ?>
	<a href="<?php echo $value; ?>" class="btn btn-social" target="_blank">
		<span class="ert-icon ert-icon_<?php echo $key; ?>"></span>
	</a>
<?php endforeach;

if ( ! empty( $use_wrapper ) ) : ?>
	</div>
<?php endif;

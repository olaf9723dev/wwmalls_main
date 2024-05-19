<?php

/**
 * @var Es_Messenger $messenger
 */

$filter = filter_input( INPUT_GET, 'es_filter', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY ); ?>

<div class="es-manage-shortcode__wrap">

	<?php if ( current_user_can( 'create_es_properties' ) ) : ?>
		<div class="es-manage-add__wrap">
			<a href="<?php echo add_query_arg( array(
				'es-page' => 'new-property',
				'es_filter' => null,
				'es-action' => null ) ); ?>" class="btn btn-light"><?php _e( 'Add new', 'es-plugin' ); ?></a>
		</div>
	<?php endif; ?>

	<form method="GET">
		<?php include locate_template( 'template-parts/shortcodes/management/filter.php' );
			  include locate_template( 'template-parts/shortcodes/management/manage-block.php' ); ?>

		<?php if ( $messenger->get_messages() ) : ?>
			<?php $messenger->render_messages(); ?>
		<?php endif; ?>

		<?php es_subscription_agent_messages(); ?>

		<?php $table = new Es_Post_List_Table( $this->get_query(), array( 'table' => 'management' ) );
		$table->render(); ?>
	</form>
</div>

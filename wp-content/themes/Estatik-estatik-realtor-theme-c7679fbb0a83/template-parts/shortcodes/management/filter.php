<h3 class="es-profile__tab-title"><?php _e( 'Filter by', 'ert' ); ?>: </h3>
<div class="row form-group">
	<div class="col-md-4">
		<?php wp_dropdown_categories( array(
			'option_none_value' => '',
			'name' => 'es_filter[tax][es_category]',
			'class' => 'form-control',
			'id' => 'cat',
			'taxonomy' => 'es_category',
			'show_option_none' => __( 'Category', 'es-plugin' ),
			'selected' => ! empty( $filter['tax']['es_category'] ) ? $filter['tax']['es_category'] : null,
		) ); ?>
	</div>
	<div class="col-md-4">
		<?php wp_dropdown_categories( array(
			'option_none_value' => '',
			'name' => 'es_filter[tax][es_type]',
			'class' => 'form-control',
			'id' => 'type',
			'taxonomy' => 'es_type',
			'show_option_none' => __( 'Type', 'es-plugin' ),
			'selected' => ! empty( $filter['tax']['es_type'] ) ? $filter['tax']['es_type'] : null,
		) ); ?>
	</div>
	<div class="col-md-4">
		<?php wp_dropdown_categories( array(
			'option_none_value' => '',
			'name' => 'es_filter[tax][es_status]',
			'class' => 'form-control',
			'id' => 'status',
			'taxonomy' => 'es_status',
			'show_option_none' => __( 'Status', 'es-plugin' ),
			'selected' => ! empty( $filter['tax']['es_status'] ) ? $filter['tax']['es_status'] : null,
		) ); ?>
	</div>
</div>
<div class="row form-group">
	<div class="col-md-4">
		<input type='number' name='es_filter[property_id]' class="form-control"
	        placeholder="<?php _e( 'Property ID', 'ert' ); ?>" value='<?php echo ! empty( $filter['property_id'] ) ? $filter['property_id'] : null; ?>'>
	</div>

	<div class="col-md-4">
		<input type='text' placeholder="<?php _e( 'Address', 'es-plugin' ); ?>" name="es_filter[address]" class="form-control"
	       value='<?php echo ! empty( $filter['address'] ) ? $filter['address'] : null; ?>'>
	</div>

	<div class="col-md-4">
		<input type="text" placeholder="<?php _e( 'Date added', 'es-plugin' ); ?>" class="js-datepicker form-control" name="es_filter[date_added]">
	</div>
</div>

<?php if ( ! empty( $_GET['page_id'] ) ) : ?>
    <input type="hidden" name="page_id" value="<?php echo $_GET['page_id']; ?>">
<?php endif;

if ( ! empty( $_GET['p'] ) ) : ?>
    <input type="hidden" name="p" value="<?php echo $_GET['p']; ?>">
<?php endif;
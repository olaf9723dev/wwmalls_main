<div class="es-tabbed-item es-<?php echo $class; ?> es-features-list" id="es-<?php echo $class; ?>">
	<h3><?php echo $title; ?></h3>

	<ul class="list-unstyled row">
		<?php foreach ( $data as $term ) : ?>
			<li class="col-6 col-md-4 col-xs-6 col-sm-6">
				<div class="circle"><i class="fa fa-check"></i></div>
				<span class="ert-feature-label"><?php echo $term->name; ?></span>
			</li>
		<?php endforeach; ?>
	</ul>
</div>

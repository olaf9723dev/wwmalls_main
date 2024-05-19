<?php
/**
 * Emallshop Admin Dashboard Tab
 *
 * @package Emallshop
 * @since 2.3.0
 */
require_once EMALLSHOP_ADMIN.'/dashboard/header.php';
global $obj_kp_updatetheme, $wp_filesystem, $wpdb;;
$obj_es_dash = new Emallshop_Dashboard();
if ( isset( $_GET['tgmpa-deactivate'] ) && 'deactivate-plugin' == $_GET['tgmpa-deactivate'] ) {
	$plugins = TGM_Plugin_Activation::$instance->plugins;
	check_admin_referer( 'tgmpa-deactivate', 'tgmpa-deactivate-nonce' );
	foreach ( $plugins as $plugin ) {
		if ( $plugin['slug'] == $_GET['plugin'] ) {
			deactivate_plugins( $plugin['file_path'] );
		}
	}
}
if ( isset( $_GET['tgmpa-activate'] ) && 'activate-plugin' == $_GET['tgmpa-activate'] ) {
	check_admin_referer( 'tgmpa-activate', 'tgmpa-activate-nonce' );
	$plugins = TGM_Plugin_Activation::$instance->plugins;
	foreach ( $plugins as $plugin ) {
		if ( isset( $_GET['plugin'] ) && $plugin['slug'] == $_GET['plugin'] ) {
			activate_plugin( $plugin['file_path'] );
		}
	}
}

$plugins 				= TGM_Plugin_Activation::$instance->plugins;
$tgm_plugins_required 	= 0;
$tgm_plugins_action 	= array();
foreach ( $plugins as $plugin ) {
	$tgm_plugins_action[ $plugin['slug'] ] = $obj_es_dash->plugin_action( $plugin );
}
$is_theme_active 		= emallshop_is_activated();
$active_button_txt 		= esc_html__('Activate Theme', 'emallshop');
$active_button_class 	= 'emallshop-activate-btn';
$input_attr 			= '';
$theme_activate 		= 'theme-deactivated';
$status_txt 			= esc_html__('No Activated', 'emallshop');
$purchase_code 			= '';
$readonly 				= 'false';
$status_activate_class 	= ' red';

if( $is_theme_active ){
	
	$purchase_code 			= emallshop_get_purchase_code();
	$active_button_txt 		= esc_html__('Deactivate Theme', 'emallshop');
	$active_button_class 	= 'emallshop-deactivate-btn';
	$input_attr 			= ' value="'.$purchase_code.'" readonly="true"';
	$readonly				= 'true';
	$theme_activate 		= 'theme-activated';
	$status_txt 			= esc_html__('Activated', 'emallshop');
	$status_activate_class 	= ' green';
}
?>
<div class="emallshop-content-body">
	<div class="es-row">
		<div class="es-col-12">
			<div class="emallshop-box theme-activate <?php echo esc_attr($theme_activate);?>">
				<div class="emallshop-box-header">
					<div class="title"> <?php esc_html_e('Purchase Code', 'emallshop')?></div>
					<div class="emallshop-button<?php echo esc_attr($status_activate_class);?>"> <?php echo esc_html( $status_txt );?></div>
				</div>
				<div class="emallshop-box-body">
					<form action="" method="post">
						<?php if( $is_theme_active ){ ?>
						<input name="purchase-code" class="purchase-code" type="text" placeholder="<?php esc_attr_e('Purchase code','emallshop');?>" value="<?php echo esc_attr($purchase_code); ?>" readonly = "true">
						<?php } else { ?>
						<input name="purchase-code" class="purchase-code" type="text" placeholder="<?php esc_attr_e('Purchase code','emallshop');?>">
						<?php } ?>
						<button type="button"  id="emallshop-activate-theme"  class="button action <?php echo esc_attr($active_button_class);?>"><?php echo esc_html( $active_button_txt );?></button>
						
					</form>
					<div class="purchase-desc">
						<?php echo wp_kses ( sprintf( __( 'You can learn how to find your purchase key <a href="%s" target="_blank"> here </a>', 'emallshop' ),'https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code' ), emallshop_allowed_html( 'a' ) );?>
					</div>
				</div>
			</div>
		</div>
	</div>		
	<div class="es-row">
		<div class="es-col-md-6">
			<div class="emallshop-box docs">
				<div class="emallshop-box-header">
					<div class="title"><?php esc_html_e('Documentation','emallshop');?></div>
				</div>
				<div class="emallshop-box-body">	
					<p><?php esc_html_e('Our documentation is simple and functional wit full details and cover all essential aspects from beginning to the most advanced parts.','emallshop');?> </p>
					<div class="s-button">
						<a class="button" href="https://docs.presslayouts.com/emallshop" target="_blank"><?php esc_html_e('Documentation','emallshop');?></a>
					</div>
				</div>
			</div>
		</div>
		<div class="es-col-md-6">
			<div class="emallshop-box support">
				<div class="emallshop-box-header">
					<div class="title"><?php esc_html_e('Support','emallshop');?></div>
				</div>
				<div class="emallshop-box-body">	
					<p><?php esc_html_e('emallshop theme comes with 6 months of free support for every license you purchase. Support can be extended through subscriptions via ThemeForest.','emallshop');?> </p>
					<div class="s-button">
						<a class="button" href="https://docs.presslayouts.com/emallshop" target="_blank"><?php esc_html_e('Send Request','emallshop');?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="es-row">
		<div class="es-col-12">
			<div class="emallshop-box install-plugin ">
				<div class="emallshop-box-header">
					<div class="title"><?php esc_html_e('Installation Required Plugins','emallshop');?></div>
				</div>
				<div class="emallshop-box-body">
					<table class="widefat">
						<thead>
							<tr>
								<th> <?php esc_html_e('Plugin', 'emallshop');?> </th>
								<th> <?php esc_html_e('Version','emallshop');?> </th>
								<th> <?php esc_html_e('Type', 'emallshop');?> </th>
								<th> <?php esc_html_e('Action', 'emallshop');?> </th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $plugins as $tgm_plugin ) { ?>
								<tr>
									<td>
										<?php
										//$instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
										if ( isset( $tgm_plugin['required'] ) && ( $tgm_plugin['required'] == true ) ) {
											if ( ! emallshop_is_plugin_check_active( $tgm_plugin['slug'] ) ){
												echo '<span>' . $tgm_plugin['name'] . '</span>';
												$tgm_plugins_required ++;
											} else {
												echo '<span class="actived">' . $tgm_plugin['name'] . '</span>';
											}
										} else {
											echo esc_html( $tgm_plugin['name'] );
										}?>
									</td>
									<td><?php echo( isset( $tgm_plugin['version'] ) ? $tgm_plugin['version'] : '' ); ?></td>
									<td><?php echo( isset( $tgm_plugin['required'] ) && ( $tgm_plugin['required'] == true ) ? 'Required' : 'Recommended' ); ?></td>
									<td>
										<?php echo wp_kses_post( $tgm_plugins_action[ $tgm_plugin['slug'] ] ); ?>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>	
				</div>
			</div>
		</div>
	</div>
</div>
<?php 
require_once EMALLSHOP_ADMIN.'/dashboard/footer.php';
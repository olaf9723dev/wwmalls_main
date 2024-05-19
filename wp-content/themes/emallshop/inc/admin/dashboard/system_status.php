<?php
/**
 * Emallshop System Status Tab
 *
 * @package Emallshop
 * @since 2.3.0
 */
require_once EMALLSHOP_ADMIN.'/dashboard/header.php';

global $wp_filesystem,$wpdb;
$obj_es_dash 		= new Emallshop_Dashboard();
$mark_yes 			= '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
$mark_no 			= '<mark class="no"><span class="dashicons dashicons-no-alt"></span></mark>';
$active_plugins 	= (array) get_option( 'active_plugins', array() );

if ( is_multisite() ) {
	$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
}?>

<div class="notice-success emallshop-dashboard-notice">
    <p class="status-info">
		<span><?php esc_html_e( 'Please copy and paste this information in your ticket when contacting support:', 'emallshop' ); ?></span>
		<a href="#" class="button debug-report"><?php esc_html_e('Get System Report','emallshop');?></a>
	</p>
    <div id="emallshop-debug-report">
        <textarea readonly="readonly"></textarea>
        <p class="copy-error"><?php esc_html_e( 'Please press Ctrl/Cmd+C to copy.', 'emallshop' ); ?></p>
    </div>
</div>
<div id="emallshop-system-status" class="emallshop-content-body">
	<div class="es-row">
		<div class="es-col-md-6">
			<div class="emallshop-box">
				<div class="emallshop-box-header">
					<div class="title"><?php esc_html_e('Theme Information','emallshop');?></div>
				</div>
				<div class="emallshop-box-body no-padding">	
					<table class="widefat" cellspacing="0">
						<tbody>
						<tr>
							<td data-export-label="Theme Name"><?php esc_html_e( 'Theme Name:', 'emallshop' ); ?></td>
							<td><?php echo EMALLSHOP_THEME_NAME; ?></td>
						</tr>
						<tr>
							<td data-export-label="Current Version"><?php esc_html_e( 'Current Version:', 'emallshop' ); ?></td>
							<td><?php echo EMALLSHOP_VERSION; ?></td>
						</tr>
						<tr>
							<td data-export-label="Installation Path"><?php esc_html_e( 'Installation Path:', 'emallshop' ); ?></td>
							<td><code><?php echo esc_html( $obj_es_dash->get_installation_path() ); ?></code></td>
						</tr>
						<tr>
							<td data-export-label="Child Theme"><?php esc_html_e( 'Child Theme:', 'emallshop' ); ?></td>
							<td> <?php if(is_child_theme()) {?> <span class="yes">&#10004;</span> <?php } else echo esc_html__('No', 'emallshop'); ?></td>
						</tr>
						 <?php if(is_child_theme()) {?> 
						<tr>
							<td data-export-label="Child Theme Directory"><?php esc_html_e( 'Child Theme Path:', 'emallshop' ); ?></td>
							<td> <code><?php echo esc_html( $obj_es_dash->get_child_theme_path() ); ?></code></td>
						</tr>
						 <?php } ?>
						<tr>
							<td data-export-label="License Activated"><?php esc_html_e( 'License Activated:', 'emallshop' ); ?></td>
							<td> <?php if(emallshop_is_activated()) {?> <span class="yes">&#10004;</span> <?php } else echo esc_html__('No', 'emallshop') ?></td>
						</tr>
						<tr>
							<td data-export-label="emallshop Server Available"><?php esc_html_e( 'emallshop Server Available:', 'emallshop' ); ?></td>
							<td>
							<?php if($obj_es_dash->is_emallshop_server_available()) {?> <span class="yes">&#10004;</span> <?php } else { echo esc_html__('No', 'emallshop'); }?>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="es-col-md-6">
			<div class="emallshop-box">
				<div class="emallshop-box-header">
					<div class="title"><?php esc_html_e('WordPress Environment','emallshop');?></div>
				</div>
				<div class="emallshop-box-body no-padding">	
					<table class="widefat" cellspacing="0">
						<tbody>
						<tr>
							<td data-export-label="Home URL"><?php esc_html_e( 'Home URL:', 'emallshop' ); ?></td>
							<td><?php echo esc_url( home_url( '/' ) ); ?></td>
						</tr>
						<tr>
							<td data-export-label="Site URL"><?php esc_html_e( 'Site URL:', 'emallshop' ); ?></td>
							<td><?php echo site_url(); ?></td>
						</tr>
						<tr>
							<td data-export-label="WordPress Version"><?php esc_html_e( 'WordPress Version:', 'emallshop' ); ?></td>
							<td><?php echo bloginfo( 'version' ); ?></td>
						</tr>
						<tr>
							<td data-export-label="WordPress Multisite"><?php esc_html_e( 'WordPress Multisite:', 'emallshop' ); ?></td>
							<td><?php echo is_multisite() ? $mark_yes : '-'; ?></td>
						</tr>
						<tr>
							<td data-export-label="WordPress Memory Limit"><?php esc_html_e( 'WordPress Memory Limit:', 'emallshop' ); ?></td>
							<td>
								
								<?php
								$memory = $obj_es_dash->emallshop_let_to_num( WP_MEMORY_LIMIT );
								if ( $memory < 128000000 ) {                        
									echo '<span class="success">' . wp_kses(sprintf( __( '%1$s - We recommend setting memory to at least <strong>256MB</strong>. <br /> Please define memory limit in <strong>wp-config.php</strong> file. To learn how, see: <a href="%2$s" target="_blank">Increasing memory allocated to PHP.</a>', 'emallshop' ), size_format( $memory ), 'https://wordpress.org/support/article/editing-wp-config-php/#increasing-memory-allocated-to-php' ), array( 'strong' => array(), 'br' => array(), 'a' => array( 'href' => array(), 'target' => array() ) ) ) . '</span>';
								} else {
									echo '<mark class="yes">' . size_format( $memory ) . '</mark>';
									if ( $memory < 256000000 ) {
										echo '<br /><span class="success">' . wp_kses( __( 'Your current memory limit is sufficient, but if you installed many plugins or need to import demo content, the required memory limit is <strong>256MB.</strong>', 'emallshop' ), array( 'strong' => array(),  ) ) . '</span>';
									}
								}
								?>
							</td>
						</tr>
						<tr>
							<td data-export-label="WordPress Debug Mode"><?php esc_html_e( 'WordPress Debug Mode:', 'emallshop' ); ?></td>
							<td><?php echo ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? $mark_yes : '-'; ?></td>
						</tr>
						<tr>
							<td data-export-label="Language"><?php esc_html_e( 'Language:', 'emallshop' ); ?></td>
							<td><?php echo get_locale(); ?></td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="es-col-md-6">
			<div class="emallshop-box">
				<div class="emallshop-box-header">
					<div class="title"><?php esc_html_e('Server Environment','emallshop');?></div>
				</div>
				<div class="emallshop-box-body no-padding">	
					<table class="widefat" cellspacing="0">
						<tbody>
						<?php if( function_exists( 'emallshop_get_server_info' ) ) { ?>
							<tr>
								<td data-export-label="Server Info"><?php esc_html_e( 'Server Info:', 'emallshop' ); ?></td>
								<td><?php echo esc_html( emallshop_get_server_info() ); ?></td>
							</tr>
						<?php } ?>
						<tr>
							<td data-export-label="PHP Version"><?php esc_html_e( 'PHP Version:', 'emallshop' ); ?></td>
							<td>
								<?php 
									if ( function_exists( 'phpversion' ) ) { 
										$php_version = phpversion();
										if( version_compare(phpversion(), '5.6', '<') ){ 
										echo esc_html__('Currently:','emallshop').' '. phpversion().' ';  
										esc_html_e('(min: 5.6)','emallshop') ?> 
										<label class="hero button" for="php-version"> <?php esc_html_e('Please contact Host provider to fix it.','emallshop') ?> </label>
									<?php } else { 
										echo esc_html__('Currently:','emallshop').' '. phpversion() ?> </span>
									<?php }
									}else{
										echo  esc_html__('Couldn\'t determine PHP version because phpversion() doesn\'t exist.','emallshop');
									}
								?>
							</td>
						</tr>
						<tr>
							<td data-export-label="PHP Post Max Size"><?php esc_html_e( 'PHP Post Max Size:', 'emallshop' ); ?></td>
							<td><?php echo size_format( wp_convert_hr_to_bytes( ini_get( 'post_max_size' ) ) );	?>	</td>
						</tr>
						<tr>
							<td data-export-label="PHP Time Limit"><?php esc_html_e( 'PHP Time Limit:', 'emallshop' ); ?></td>
							<td>
								<?php
								$time_limit = ini_get('max_execution_time');

								if ( $time_limit < 180 && $time_limit != 0 ) {
									echo '<mark class="error">' . wp_kses(sprintf( __( '%1$s - We recommend setting max execution time to at least 600. <br /> To import demo content, <strong>600</strong> seconds of max execution time is required.<br />See: <a href="%2$s" target="_blank">Increasing max execution to PHP</a>', 'emallshop' ), $time_limit, 'https://wordpress.org/support/article/common-wordpress-errors/#php-errors' ), array( 'strong' => array(), 'br' => array(), 'a' => array( 'href' => array(), 'target' => array() ) ) ) . '</mark>';
								} else {
									echo  esc_html( $time_limit );
									if ( $time_limit < 600 && $time_limit != 0 ) {
										echo '<br /><mark class="error">' . wp_kses(__( 'Current time limit is sufficient, but if you need import demo content, the required time is <strong>600</strong>.', 'emallshop' ), array( 'strong' => array(),  ) ) . '</mark>';
									}
								}
								?>
							</td>
						</tr>
						<tr>
							<td data-export-label="PHP Max Input Vars"><?php esc_html_e( 'PHP Max Input Vars:', 'emallshop' ); ?></td>
							<td>
								<?php 
									$max_input_vars = ini_get( 'max_input_vars' );									
									echo esc_html( $max_input_vars );
								?>
							</td>
						</tr>
						 <tr>
							<td data-export-label="ZipArchive"><?php esc_html_e( 'ZipArchive:', 'emallshop' ); ?></td>
							<td><?php echo class_exists( 'ZipArchive' ) ? '<span class="yes">&#10004;</span>' : '<span class="error">No.</span>'; ?></td>
						</tr>
						<tr>
							<td data-export-label="Max Upload Size"><?php esc_html_e( 'Max Upload Size:', 'emallshop' ); ?></td>
							<td><?php echo size_format( wp_max_upload_size() ); ?></td>
						</tr>
						<tr>
							<td data-export-label="MySQL Version"><?php esc_html_e( 'MySQL Version:', 'emallshop' ); ?></td>
							<td><?php echo esc_html( $wpdb->db_version() ); ?></td>
						</tr>
						<tr>
							<td data-export-label="GD Library"><?php esc_html_e( 'GD Library:', 'emallshop' ); ?></td>
							<td>
								<?php
								$info = esc_attr__( 'Not Installed', 'emallshop' );
								if ( extension_loaded( 'gd' ) && function_exists( 'gd_info' ) ) {
									$info = esc_attr__( 'Installed', 'emallshop' );
									$gd_info = gd_info();
									if ( isset( $gd_info['GD Version'] ) ) {
										$info = $gd_info['GD Version'];
									}
								}
								echo esc_html( $info );
								?>
							</td>
						</tr>
						<tr>
							<td data-export-label="cURL"><?php esc_html_e( 'cURL:', 'emallshop' ); ?></td>
							<td>
								<?php
								$info = esc_attr__( 'Not Enabled', 'emallshop' );
								if ( function_exists( 'curl_version' ) ) {
									$curl_info = curl_version();
									$info = $curl_info['version'];
								}
								echo esc_html( $info );
								?>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="es-col-md-6">
			<div class="emallshop-box">
				<div class="emallshop-box-header">
					<div class="title"><?php esc_html_e('Active Plugins','emallshop');?>(<?php echo count($active_plugins);?>)</div>
				</div>
				<div class="emallshop-box-body no-padding">
					<table class="widefat" cellspacing="0">
						<tbody>
						<?php
							foreach ( $active_plugins as $plugin ) {
								$plugin_data = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin ); // PHPCS:Ignore Generic.PHP.NoSilencedErrors.Forbidden
	
								if ( empty( $plugin_data['Name'] ) ) {
									continue;
								}

								// Link the plugin name to the plugin url if available.
								$plugin_name = esc_html( $plugin_data['Name'] );
								if ( ! empty( $plugin_data['PluginURI'] ) ) {
									$plugin_name = '<a href="' . esc_url( $plugin_data['PluginURI'] ) . '" title="' . esc_attr__( 'Visit plugin homepage', 'emallshop' ) . '">' . $plugin_name . '</a>';
								}
								?>
								<tr>
									<td><?php echo wp_kses_post( $plugin_name ); ?></td>
									<td><?php
										printf( _x( 'by %s', 'admin status', 'emallshop' ), $plugin_data['Author'] );
										echo ' &ndash; ' . esc_html( $plugin_data['Version'] );
										?></td>
								</tr>
								<?php
							}
						?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php 
require_once EMALLSHOP_ADMIN.'/dashboard/footer.php';
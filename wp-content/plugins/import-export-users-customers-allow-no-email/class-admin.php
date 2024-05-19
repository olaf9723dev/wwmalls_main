<?php

class ACUI_ANE_Admin{
	private $nombre_plugin;
	private $slug_menu;
	private $slug;
	private $capability;

	function __construct( $nombre_plugin, $slug_menu, $slug ){
		$this->nombre_plugin = $nombre_plugin;
		$this->slug_menu = $slug_menu;
		$this->slug = $slug;

		$this->capability = 'manage_options';

		$this->licence_deactivation_check();
		add_filter( 'plugin_action_links', array( $this, 'action_links' ), 10, 2 );
		add_action( 'admin_menu', array( $this, 'menu' ), 20 );
        add_action( 'network_admin_menu', array ($this, 'network_menu'));
		add_action( 'admin_notices', array( $this, 'admin_no_key_notices' ) );
		add_action( 'network_admin_notices', array( $this, 'admin_no_key_notices') );
	}

	function action_links( $links, $file ) {
		if ($file == 'import-export-users-customers-allow-no-email/import-export-users-customers-allow-no-email.php') {
			$links[] = '<a href="admin.php?page=acui_ane_license">License</a>';
			return array_reverse( $links );		
		}
		
		return $links; 
	}

	function menu(){
		add_submenu_page( '', __( 'License Import and Export Users and Customers - Allow No Email Addon', 'import-users-from-csv-with-meta' ), __( 'License Import and Export Users and Customers - Allow No Email Addon', 'import-users-from-csv-with-meta'), $this->capability, 'acui_ane_license', array( $this, 'license' ) );
	}

    function network_menu(){
        add_submenu_page( '', __( 'License Import and Export Users and Customers - Allow No Email Addon', 'import-users-from-csv-with-meta' ), __( 'License Import and Export Users and Customers - Allow No Email Addon', 'import-users-from-csv-with-meta'), $this->capability, 'acui_ane_license', array( $this, 'license' ) );
    }
	
	function license(){
		$gestor_licencia = new ACUI_ANE_Admin( $this->nombre_plugin, $this->slug_menu, $this->slug );

		if( isset( $_POST ) || !empty( $_POST['acui_ane_licence_form_submit'] ) ){
			$gestor_licencia->licence_form_submit();
			$gestor_licencia->admin_notices();
		}

		$gestor_licencia->estilos();

		if( $gestor_licencia->licencia_verificada() ){
			$gestor_licencia->formulario_desactivar_licencia();
		}
		else{
			$gestor_licencia->formulario_licencia();
		}
	}

	function admin_no_key_notices(){
        if ( !current_user_can('manage_options'))
            return;

        if( $this->licencia_verificada() )
        	return;

		$manage_license_link = ( is_multisite() ) ? network_admin_url( '?page=' . $this->slug_menu ) : 'admin.php?page=' . $this->slug_menu;
        
        ?><div class="error fade"><p><?php echo $this->nombre_plugin; ?> <?php echo sprintf(__( 'is not receiving <strong>critical updates and new features</strong> because you do not have an active licence key. Please enter your licence in <a href="%s">manage plugin licence key</a>, in order to keep your plugin up to date and receive support.', 'import-users-from-csv-with-meta' ), $manage_license_link ); ?></p></div><?php
	}

	function licencia_verificada(){
		$licence_data = get_site_option( $this->slug . 'license');

		if( !isset( $licence_data['key'] ) || $licence_data['key'] == '')
			return false;

		return true;
	}

	function licence_form_submit(){
		global $cod_interface_messages;

		if( !is_array( $cod_interface_messages ) )
			$cod_interface_messages = array();

		//check for de-activation
		if( isset( $_POST['cod_interface_messages'] ) && isset( $_POST[$this->slug . 'license_deactivate'] ) && wp_verify_nonce( $_POST['nonce'], 'codection-seguridad' ) ){
			$licence_data = get_site_option( $this->slug . 'license' );                        
			$licence_key = $licence_data['key'];

			//build the request query
			$args = array(
				'woo_sl_action'         => 'deactivate',
				'licence_key'           => $licence_key,
				'product_unique_id'     => ACUI_ANE_PRODUCT_ID,
				'domain'                => ACUI_ANE_INSTANCE
			);
			$request_uri    = ACUI_ANE_UPDATE_API_URL . '?' . http_build_query( $args , '', '&');
			$data           = wp_remote_get( $request_uri );

			if( is_wp_error( $data ) || $data['response']['code'] != 200 ){
				$cod_interface_messages[] = array(
					'type'  =>  'error',
					'text'  =>  __('There was a problem connecting to ', 'import-users-from-csv-with-meta') . ACUI_ANE_UPDATE_API_URL);
				return;  
			}

			$response_block = json_decode($data['body']);
			$response_block = $response_block[count($response_block) - 1];
			$response = $response_block->message;

			if( isset( $response_block->status ) ){
				if( $response_block->status == 'success' && $response_block->status_code == 's201' ){
					//the license is active and the software is active
					$cod_interface_messages[] = array(
					'type'  =>  'updated',
					'text'  =>  $response_block->message);

					$licence_data = get_site_option($this->slug . 'licence');

					//save the license
					$licence_data['key']          = '';
					$licence_data['last_check']   = time();

					update_site_option( $this->slug . 'license', $licence_data );
				}
				else //if message code is e104  force de-activation
					if ( $response_block->status_code == 'e002' || $response_block->status_code == 'e104' || $response_block->status_code == 'e211' ){
						$licence_data = get_site_option( $this->slug . 'license' );

						//save the license
						$licence_data['key']          = '';
						$licence_data['last_check']   = time();

						update_site_option($this->slug . 'license', $licence_data);
					}
					else{
						$cod_interface_messages[] =   array(  
						'type'  =>  'error',
						'text'  =>  __( 'There was a problem activating the licence: ', 'import-users-from-csv-with-meta' ) . $response_block->message );

						return;
					}
			}
			else{
				$cod_interface_messages[] =   array(  
				'type'  =>  'error',
				'text'  => __( 'There has been a problem in the data packet received from ', 'import-users-from-csv-with-meta' ) . ACUI_ANE_UPDATE_API_URL );
				return;
			}
		}

		if ( isset( $_POST['cod_interface_messages'] ) && isset( $_POST[$this->slug . 'license_activate'] ) && wp_verify_nonce( $_POST['nonce'],'codection-seguridad' ) ){
			$licence_key = isset( $_POST['licence_key'] )? sanitize_key( trim($_POST['licence_key'] ) ) : '';

			if( $licence_key == '' ){
				$cod_interface_messages[] =   array(  
					'type'  =>  'error',
					'text'  =>  __( 'The licence cannot be empty', 'import-users-from-csv-with-meta' )
				);
				return;
			}

			//build the request query
			$args = array(
				'woo_sl_action' => 'activate',
				'licence_key' => $licence_key,
				'product_unique_id' => ACUI_ANE_PRODUCT_ID,
				'domain' => ACUI_ANE_INSTANCE
			);

			$request_uri    = ACUI_ANE_UPDATE_API_URL . '?' . http_build_query( $args , '', '&');
			$data           = wp_remote_get( $request_uri );

			if( is_wp_error( $data ) || $data['response']['code'] != 200 ){
				$cod_interface_messages[] =   array(  
				'type'  =>  'error',
				'text'  =>  __( 'There have been problems connecting to ', 'import-users-from-csv-with-meta' ) . ACUI_ANE_UPDATE_API_URL);
				return;
			}

			$response_block = json_decode( $data['body'] );

			//retrieve the last message within the $response_block
			$response_block = $response_block[count($response_block) - 1];
			$response = $response_block->message;

			if( isset( $response_block->status ) ){
				if( $response_block->status == 'success' && ( $response_block->status_code == 's100' || $response_block->status_code == 's101' ) ){
					//the license is active and the software is active
					$cod_interface_messages[] =   array(  
						'type'  =>  'updated',
						'text'  =>  $response_block->message
					);

					$licence_data = get_site_option($this->slug . 'licence');

					//save the license
					$licence_data['key']          = $licence_key;
					$licence_data['last_check']   = time();

					update_site_option($this->slug . 'license', $licence_data);
				}
				else{
					$cod_interface_messages[] =   array(  
						'type'  =>  'error',
						'text'  =>  __( 'There was a problem activating the licence ', 'import-users-from-csv-with-meta' )  . $response_block->message );
					return;
				}
			}
			else{
				$cod_interface_messages[] =   array(  
					'type'  =>  'error',
					'text'  =>  __( 'There has been a problem in the data packet received from ', 'import-users-from-csv-with-meta' ) . ACUI_ANE_UPDATE_API_URL );
				return;
			}
		}
	}

	function admin_notices(){
		global $cod_interface_messages;

		if( !is_array( $cod_interface_messages ) )
			return;

		if( count( $cod_interface_messages ) > 0 ){
			foreach ($cod_interface_messages  as  $message){
				echo "<div class='". $message['type'] ." fade'><p>". $message['text']  ."</p></div>";
			}
		}
	}

	function estilos(){
		?>
		<style type="text/css">
		.start-container {
			background-color: #fff;
			border-left: 4px solid #cc99c2;
			overflow: hidden;
			padding: 25px 20px 20px 30px;
			position: relative;
		}
		</style>
		<?php
	}

	function formulario_licencia(){
		?>
		<div class="wrap"> 
			<h2><?php echo $this->nombre_plugin; ?></h2>
			<br />
			<div class="start-container">
				<h3>License</h3>
				<form id="form_data" name="form" method="post">
					<?php wp_nonce_field( 'codection-seguridad','nonce' ); ?>
					<input type="hidden" name="cod_interface_messages" value="true" />
					<input type="hidden" name="<?php echo $this->slug; ?>license_activate" value="true" />

					<div class="section section-text">
						<div class="option">
							<div class="controls-license">
								<input type="text" value="" name="licence_key" class="text-input" size="40">
							</div>
							<div class="explain"><?php _e( 'Please enter the licence key you received when you purchased this product. If you lost the key, you can always retrieve it from', 'import-users-from-csv-with-meta' ) ?> <a href="https://import-wp.com/my-account/" target="_blank"><?php _e( 'My account in', 'import-users-from-csv-with-meta' ) ?> wp-import by Codection</a></div>
						</div> 
					</div>

					<p class="submit">
						<input type="submit" name="Submit" class="button-primary" value="<?php _e( 'Save', 'import-users-from-csv-with-meta' ) ?>">
					</p>
				</form> 
			</div>
		</div> 
		<?php  
	}

	function formulario_desactivar_licencia(){
		$licence_data = get_site_option($this->slug . 'license');
		?>
		<div class="wrap"> 
			<h2><?php echo $this->nombre_plugin; ?></h2>
			<br />
			<div class="start-container">
			<h3>License</h3>
			<div id="form_data">
				<form id="form_data" name="form" method="post">    
					<?php wp_nonce_field( 'codection-seguridad','nonce' ); ?>
					<input type="hidden" name="cod_interface_messages" value="true" />
					<input type="hidden" name="<?php echo $this->slug; ?>license_deactivate" value="true" />
					<div class="section section-text">
						<div class="option">
							<div class="controls-license">
								<p><b><?php echo substr( $licence_data['key'], 0, 20) ?>-xxxxxxxx-xxxxxxxx</b> &nbsp;&nbsp;&nbsp;<a class="button-secondary" title="<?php _e( 'Deactivate', 'import-users-from-csv-with-meta' ) ?>" href="javascript:void(0)" onclick="jQuery(this).closest('form').submit();"><?php _e( 'Deactivate', 'import-users-from-csv-with-meta' ) ?></a></p>
							
							</div>
						<div class="explain"><?php _e( 'You can generate more keys at', 'import-users-from-csv-with-meta' ) ?> <a href="https://import-wp.com/my-account/" target="_blank"><?php _e( 'My account in', 'import-users-from-csv-with-meta' ) ?> wp-import by Codection</a></div>
						</div> 
					</div>
				</form>
			</div>
			</div>
		</div>
		<?php
	}

	public function licence_key_verify(){
        $licence_data = get_site_option($this->slug . 'license');
        
        if( !isset($licence_data['key'] ) || $licence_data['key'] == '' )
            return false;
            
        return true;
    }

	function licence_deactivation_check(){
		if( !$this->licence_key_verify() )
			return;

		$api_parse_url = parse_url( ACUI_ANE_UPDATE_API_URL );
		if( $api_parse_url['host'] == ACUI_ANE_INSTANCE )
			return;

		//attempt to lock
		if ( !$this->create_lock( 'WOOSL__API_status-check', 60 ) )
			return;

		$licence_data = get_site_option( $this->slug . 'license' );

		if( isset($licence_data['last_check']) ){
			if( time() < ($licence_data['last_check'] + 86400 ) ){
				return;
			}
		}

		$licence_key = $licence_data['key'];
		$args = array(
			'woo_sl_action'         => 'status-check',
			'licence_key'           => $licence_key,
			'product_unique_id'     => ACUI_ANE_PRODUCT_ID,
			'domain'                => ACUI_ANE_INSTANCE
		);
		$request_uri = ACUI_ANE_UPDATE_API_URL . '?' . http_build_query( $args , '', '&');
		$data = wp_remote_get( $request_uri );

		if( is_wp_error( $data ) || $data['response']['code'] != 200 )
			return;   

		$response_block = json_decode( $data['body'] );
		$response_block = $response_block[ count($response_block) - 1 ];
		$response = $response_block->message;

		if( isset( $response_block->status ) ){
			if( $response_block->status == 'success' ){
				if( $response_block->status_code == 's203' || $response_block->status_code == 's204' || $response_block->status_code == 'e003' ){
					$licence_data['key'] = '';
				}
			}

			if( $response_block->status == 'error' ){
				$licence_data['key'] = '';
			} 
		}

		$licence_data['last_check'] = time();    
		update_site_option( $this->slug . 'license', $licence_data );

		$this->release_lock( 'WOOSL__API_status-check' );
	}

	function create_lock( $lock_name, $release_timeout = null ){
		global $wpdb, $blog_id;

		if ( ! $release_timeout ) {
			$release_timeout = 10;
		}
	
		$lock_option = $lock_name . '.lock';

		if ( is_multisite() ){
			$lock_result = $wpdb->query( $wpdb->prepare( "INSERT INTO `". $wpdb->sitemeta ."` (`site_id`, `meta_key`, `meta_value`) 
			SELECT %s, %s, %s FROM DUAL
			WHERE NOT EXISTS (SELECT * FROM `". $wpdb->sitemeta ."` 
			WHERE `meta_key` = %s AND `meta_value` != '') 
			LIMIT 1", $blog_id, $lock_option, time(), $lock_option) );
		}
		else{
			$lock_result = $wpdb->query( $wpdb->prepare( "INSERT IGNORE INTO `". $wpdb->options ."` (`option_name`, `option_value`, `autoload`) VALUES (%s, %s, 'no') /* LOCK */", $lock_option, time() ));   
		}


		if ( ! $lock_result ){
			$lock_result    =   $this->get_lock( $lock_option );

			// If a lock couldn't be created, and there isn't a lock, bail.
			if ( ! $lock_result ) {
				return false;
			}

			// Check to see if the lock is still valid. If it is, bail.
			if ( $lock_result > ( time() - $release_timeout ) ) {
				return false;
			}

			// There must exist an expired lock, clear it and re-gain it.
			$this->release_lock( $lock_name );

			return $this->create_lock( $lock_name, $release_timeout );
		}

		// Update the lock, as by this point we've definitely got a lock, just need to fire the actions.
		$this->update_lock( $lock_option, time() );

		return true;
	}

	private function get_lock( $lock_name, $return_full_row = FALSE ){
		global $wpdb;

		if ( is_multisite() ){
			$mysq_query =   $wpdb->get_row( $wpdb->prepare("SELECT `site_id`, `meta_key`, `meta_value` FROM  `". $wpdb->sitemeta ."` WHERE `meta_key`    =   %s", $lock_name ) );

			if( $return_full_row === TRUE )
			    return $mysq_query;

			if( is_object($mysq_query) && isset ( $mysq_query->meta_value ) )
			    return $mysq_query->meta_value;
		}
		else{
			$mysq_query = $wpdb->get_row( $wpdb->prepare("SELECT `option_name`, `option_value` FROM  `". $wpdb->options ."` WHERE `option_name`    =   %s", $lock_name ) );

			if( $return_full_row === TRUE )
				return $mysq_query;

			if( is_object($mysq_query) && isset ( $mysq_query->option_value ) )
				return $mysq_query->option_value;   
		}

		return FALSE;
	}

	private function update_lock( $lock_name, $lock_value ){
		global $wpdb;

        $mysq_query = ( is_multisite() ) ? $wpdb->query( $wpdb->prepare("UPDATE `". $wpdb->sitemeta ."` SET meta_value = %s WHERE meta_key = %s", $lock_value, $lock_name) ) : $wpdb->query( $wpdb->prepare("UPDATE `". $wpdb->options ."` SET option_value = %s WHERE option_name = %s", $lock_value, $lock_name) );

		return $mysq_query;
	}

	function release_lock( $lock_name ){
		global $wpdb;

		$lock_option = $lock_name . '.lock';

        $mysq_query = ( is_multisite() ) ? $wpdb->query( $wpdb->prepare( "DELETE FROM `". $wpdb->sitemeta ."` WHERE meta_key = %s", $lock_option ) ) : $wpdb->query( $wpdb->prepare( "DELETE FROM `". $wpdb->options ."` WHERE option_name = %s", $lock_option ) );

		return $mysq_query;
	}
}
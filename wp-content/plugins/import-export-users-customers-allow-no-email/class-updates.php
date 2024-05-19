<?php

class ACUI_ANE_Updates{
	public     $api_url;
	private    $slug;
	public     $plugin;

	function __construct( $api_url, $slug, $plugin ){
		$this->api_url = $api_url;
		$this->slug    = $slug;
		$this->plugin  = $plugin;
	}

	function check_for_plugin_update( $checked_data ){
		if ( !is_object( $checked_data ) ||  ! isset ( $checked_data->response ) )
			return $checked_data;

		$request_string = $this->prepare_request( 'plugin_update' );
		if($request_string === FALSE)
			return $checked_data;

		global $wp_version;
		$request_uri = $this->api_url . '?' . http_build_query( $request_string , '', '&');

		$data = get_site_transient( 'acui_ane-check_for_plugin_update_' . md5( $request_uri ) );

		if( $data === FALSE ){
			$data = wp_remote_get( $request_uri, array(
			                            'timeout'     => 20,
			                            'user-agent'  => 'WordPress/' . $wp_version . '; WooSoftwareLicense/' . ACUI_ANE_VERSION .'; ' . ACUI_ANE_INSTANCE,
			                            ) );

			if( is_wp_error( $data ) || $data['response']['code'] != 200 )
				return $checked_data;

			set_site_transient( 'acui_ane-check_for_plugin_update_' . md5( $request_uri ), $data, 60 * 60 * 4 );
		}

		$response_block = json_decode( $data['body'] );

		if( !is_array( $response_block ) || count( $response_block ) < 1)
			return $checked_data;

		$response_block = $response_block[count($response_block) - 1];
		$response = isset( $response_block->message ) ? $response_block->message : '';

		if( is_object( $response ) && !empty( $response ) ){
			$response->slug = $this->slug;
			$response->plugin = $this->plugin;
			$response->icons = ( isset( $response->icons ) && !empty( $response->icons ) ) ? $response->icons : '';
			$checked_data->response[ $this->plugin ] = $response;
		}

		return $checked_data;
	}

    function plugins_api_call( $def, $action, $args ){
		if( !is_object( $args ) || !isset( $args->slug ) || $args->slug != $this->slug )
			return $def;

		$request_string = $this->prepare_request($action, $args);

		if($request_string === FALSE){
			return new WP_Error('plugins_api_failed', __('An error occour when try to identify the pluguin.' , 'import-users-from-csv-with-meta') . '&lt;/p> &lt;p>&lt;a href=&quot;?&quot; onclick=&quot;document.location.reload(); return false;&quot;>'. __( 'Try again', 'software-license' ) .'&lt;/a>');;
		}

		$request_uri = $this->api_url . '?' . http_build_query( $request_string , '', '&');
		$data = wp_remote_get( $request_uri );

		if( is_wp_error( $data ) || $data['response']['code'] != 200 ){
			return new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.' , 'import-users-from-csv-with-meta') . '&lt;/p> &lt;p>&lt;a href=&quot;?&quot; onclick=&quot;document.location.reload(); return false;&quot;>'. __( 'Try again', 'software-license' ) .'&lt;/a>', $data->get_error_message());
		}

		$response_block = json_decode($data['body']);
		//retrieve the last message within the $response_block
		$response_block = $response_block[count($response_block) - 1];
		$response = $response_block->message;

		if( is_object( $response ) && !empty( $response ) ){
			//include slug and plugin data
			$response->slug = $this->slug;
			$response->plugin = $this->plugin;

			$response->sections = (array)$response->sections;
			$response->banners = (array)$response->banners;

			return $response;
		}
	}

	function prepare_request( $action, $args = array() ){
		global $wp_version;

		$licence_data = get_site_option( 'acui_ane_license' );

		if( !is_array( $licence_data ) ){
			$licence_data = array();
			$licence_data['key'] = '';
		}

		return array(
			'woo_sl_action'        => $action,
			'version'              => ACUI_ANE_VERSION,
			'product_unique_id'    => ACUI_ANE_PRODUCT_ID,
			'licence_key'          => $licence_data['key'],
			'domain'               => ACUI_ANE_INSTANCE,
			'wp-version'           => $wp_version,
		);
	}
}
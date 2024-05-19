<?php
 /**
 * EmallShop Include Admin Customizer Function
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 3.0
 */
class EmallShop_Admin {
	public $prefix;
	public $theme_data;
	public $current_version;
	public $slug;
	public $theme_update_data;
	public $option_name = 'envato_purchase_code_18513022';
	function __construct( $purchase_code = null ) {
		$this->prefix = '_es_';
		$this->theme_data = $this->get_theme_data();
		$this->current_version = $this->theme_data->get('Version');
        //$this->api_url = 'http://localhost/wordpress/envato';
        $this->api_url = 'https://www.presslayouts.com/api/envato';
        $this->token_key = $this->get_token_key();
		if($purchase_code)		{
			$this->purchase_code = $purchase_code;
		}else {
			$this->purchase_code = $this->get_purchase_code();
		}
        $this->item_name 	= 'EmallShop - Responsive WooCommerce WordPress Theme';
        $this->slug 		= 'emallshop';
		$this->item_id 		= '18513022';
		
		/*Admin menu*/
		add_action( 'admin_menu', array( $this, 'theme_page_menu' ) );
		
		/* Theme Update */
		add_action( 'wp_ajax_activate_theme', array( $this, 'activate_theme' ) );
		
		/* Theme Deactivate */
		add_action( 'wp_ajax_deactivate_theme', array( $this, 'deactivate_theme_data' ) );
		
	}
	
	public function theme_page_menu() {
        add_menu_page(
            esc_html__( 'EmallShop', 'emallshop' ),
            esc_html__( 'EmallShop', 'emallshop' ),
            'manage_options',
            'emallshop-theme',
            array( $this, 'emallshop_dashboard_page' ),
			EMALLSHOP_ADMIN_IMAGES.'/menu-icon.png',
			25
        );
		add_submenu_page( 'emallshop-theme',
            esc_html__( 'Welcome', 'emallshop' ),
            esc_html__( 'Welcome', 'emallshop' ),
            'manage_options',
            'emallshop-theme',
            array( $this, 'emallshop_dashboard_page' )
        );
		add_submenu_page( 'emallshop-theme',
            esc_html__( 'System Status', 'emallshop' ),
            esc_html__( 'System Status', 'emallshop' ),
            'manage_options',
            'emallshop-system-status',
            array( $this, 'emallshop_system_status' )
        );
	}
	
	public function emallshop_dashboard_page() {
		require( EMALLSHOP_ADMIN. '/dashboard/welcome.php' );
	}
	
	public function emallshop_system_status() {		 
		require(EMALLSHOP_ADMIN. '/dashboard/system_status.php' );
	}
	
	public function activate_theme(){
		check_ajax_referer( 'emallshop_nonce', 'nonce' );
		//$purchase_code = $_REQUEST['purchase_code'];
		$purchase_code = $_REQUEST['purchase_code'];
		$theme_data = $this->get_activate_theme_data($purchase_code);
		$data = json_decode($theme_data,true);
		$data['purchase_code'] = $purchase_code;
		$response = array('message'=> $data['message'],'success'=>0);
		if($data['success']){			
			$this->update_theme_data($data);
			$response = array('message'=> $data['message'],'success'=>1);
		}		
		echo json_encode($response);
		die();
	}
	
	public function update_theme_data($data){
		update_option( 'emallshop_token_key',$data['token'] );
		update_option( 'emallshop_is_activated', true );
		update_option( $this->option_name,$data['purchase_code'] );
	}
	
	public function deactivate_theme_data(){
		check_ajax_referer( 'emallshop_nonce', 'nonce' );
		$purchase_code = $_REQUEST['purchase_code'];
		$theme_data = $this->deactivate_theme($purchase_code);
		$data = json_decode($theme_data,true);
		$data['purchase_code'] = $purchase_code;
		$response = array('message'=> $data['message'],'success'=>0);
		if($data['success']){			
			$this->remove_theme_data();
			$response = array('message'=> $data['message'],'success'=>1);
		}		
		echo json_encode($response);
		die();
	}
	
	public function remove_theme_data(){
		delete_option( 'emallshop_token_key' );
		delete_option( 'emallshop_is_activated');
		delete_option( 'emallshop_activated_data');
		delete_option( $this->option_name );
	}
	
	public function get_activate_theme_data($purchase_code){
		global $wp_version;		
		$item_id = $this->item_id;		
		$domain = $this->get_domain();
		$response = wp_remote_request($this->api_url.'/activate.php', array(
				'user-agent' => 'WordPress/'.$wp_version.'; '. home_url( '/' ) ,
				'method' => 'POST',
				'sslverify' => false,
				'body' => array(
					'purchase_code' => urlencode($purchase_code),
					'item_id' => urlencode($item_id),
					'domain' => urlencode($domain),
				)
			)
		);

        $response_code = wp_remote_retrieve_response_code( $response );
        $activate_info = wp_remote_retrieve_body( $response );
		
        if ( $response_code != 200 || is_wp_error( $activate_info ) ) {
			return json_encode(array("message"=>"Registration Connection error",'success'=>0));
        }
		return $activate_info;
	}
	
	public function deactivate_theme($purchase_code){
		global $wp_version;		
		$token_key = $this->get_token_key();
		$item_id = $this->item_id;	
		$response = wp_remote_request($this->api_url.'/deactivate.php', array(
				'user-agent' => 'WordPress/'.$wp_version.'; '. home_url( '/' ) ,
				'method' => 'POST',
				'sslverify' => false,
				'body' => array(
					'purchase_code' => urlencode($purchase_code),
					'token_key' => urlencode($token_key),
					'item_id' => urlencode($item_id),
				)
			)
		);

        $response_code = wp_remote_retrieve_response_code( $response );
        $activate_info = wp_remote_retrieve_body( $response );

        if ( $response_code != 200 || is_wp_error( $activate_info ) ) {
            return json_encode(array("message"=>"Registration Connection error",'success'=>0));
        }
		if(  $response_code == 200 ){
			return json_encode( array( "message"=>"Successfully deactivate theme license.",'success'=> 1 ) ) ;
		}
		
		return $activate_info;
	}
	
	public function get_domain() {
        $domain = get_option('siteurl'); //or home
        $domain = str_replace('http://', '', $domain);
        $domain = str_replace('https://', '', $domain);
        $domain = str_replace('www', '', $domain); //add the . after the www if you don't want it
        return $domain;
    }
	public function get_theme_data(){
		return wp_get_theme();
	}
	
	public function get_current_version(){
		return $this->current_version;
	}
	
	public function get_token_key(){
		return get_option( 'emallshop_token_key');
	}
	
	public function get_purchase_code(){
		$purchase_code = '';
		if( $purchase_code = get_option( $this->option_name ) ){			
			return $purchase_code;
		}
		if( $activated_data = get_option( 'emallshop_activated_data' ) ){
			$purchase_code = isset( $activated_data['purchase'] ) ? $activated_data['purchase'] : '';
			return $purchase_code;
		}
		return $purchase_code;
	}
}
$obj_emallshop_admin = new EmallShop_Admin();
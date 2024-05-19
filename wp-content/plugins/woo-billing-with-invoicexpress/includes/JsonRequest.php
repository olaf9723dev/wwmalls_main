<?php
namespace Webdados\InvoiceXpressWooCommerce;

use Webdados\InvoiceXpressWooCommerce\JsonRequestException as JsonRequestException;

/* WooCommerce HPOS ready 2023-07-13 */

class JsonRequest {

	// invoicexpress app domain
	protected $domain;
	// api token needed to authenticate
	protected $api_token;
	// api url
	protected $api_url;
	// the raw request to be done
	protected $request;
	// arguments that are needed for this request
	protected $args;
	// current error
	protected $current_error_code;
	protected $current_error_message;


	/*
	 * Constructor
	 */
	public function __construct( $parameters = array() ) {
		// InvoiceXpress settings
		$this->api_token = get_option( 'hd_wc_ie_plus_api_token' );
		$this->domain    = get_option( 'hd_wc_ie_plus_subdomain' );
		// auto-populate object..
		foreach ( $parameters as $key => $value ) {
			$this->$key = $value;
		}
		//Set the API URL
		$this->setApiUrl();
	}

	/*
	 * Set the API URL
	 */
	public function setApiUrl() {
		$this->api_url = sprintf(
			'https://%1$s.app.invoicexpress.com/%2$s?api_key=%3$s',
			$this->domain,
			$this->request,
			$this->api_token
		);
	}

	/*
	 * Set basic wp_remote arguments
	 */
	public function wp_remote_args( $body = null ) {
		$args = array(
			'timeout'     => 30,
			'httpversion' => '1.1',
			'headers'     => array(
				'Accept'       => 'application/json; charset=utf-8',
				'Content-Type' => 'application/json; charset=utf-8',
			),
		);
		if ( $body ) {
			$args['body'] = $body;
		}
		if ( defined( 'WP_LOCAL_DEV' ) && WP_LOCAL_DEV ) {
			$args['sslverify'] = false;
		}
		return $args;
	}

	/*
	 * Parses the JSON response into an object
	 * @return php object with the parsed JSON
	 */
	public function jsonToObject( $json ) {
		if ( $json ) {
			if ( $object = json_decode( $json ) ) {
				if ( isset( $object->errors ) && is_array( $object->errors ) && count( $object->errors ) > 0 ) {
					$error_message = array();
					foreach ( $object->errors as $error ) {
						if ( isset( $error->error ) ) {
							$error_message[] = $error->error;
						}
					}
					return array(
						'success'       => false,
						'error_code'    => 0,
						'error_message' => implode( ', ', $error_message ),
					);
				} else {
					return array(
						'success' => true,
						'object' => $object,
					);
				}
			} else {
				return array(
					'success'       => false,
					'error_code'    => 0,
					'error_message' => 'Unable to decode the JSON string',
				);
			}
		} else {
			return array(
				'success'       => false,
				'error_code'    => 0,
				'error_message' => 'Not an JSON string',
			);
		}
	}

	/*
	 * Method that starts the get request
	 * @return php array with the parsed response
	 */
	public function getRequest() {
		try {
			$json = $this->processGetRequest();
			return $this->jsonToObject( $json );
		} catch( JsonRequestException $e ) {
			return array(
				'success'       => false,
				'error_code'    => $e->getCode(),
				'error_message' => $e->getMessage(),
			);
		}
	}

	/*
	 * Method that starts the request
	 * @return php array with the parsed response
	 */
	public function postRequest() {
		try {
			$json = $this->processPostRequest();
			return $this->jsonToObject( $json );
		} catch( JsonRequestException $e ) {
			return array(
				'success'       => false,
				'error_code'    => $e->getCode(),
				'error_message' => $e->getMessage(),
			);
		}
	}

	/*
	 * Method that starts the request
	 * @return php array with the parsed response
	 */
	public function putRequest() {
		try {
			$json = $this->processPutRequest();
			if ( trim( $json ) === '' ) {
				return array(
					'success'       => true
				);
			}
			return $this->jsonToObject( $json );
		} catch( JsonRequestException $e ) {
			return array(
				'success'       => false,
				'error_code'    => $e->getCode(),
				'error_message' => $e->getMessage(),
			);
		}
	}

	/* Handle response and return it */
	private function handle_and_return_response( $response ) {
		if ( is_wp_error( $response ) ) {
			$code    = intval( $response->get_error_code() );
			$message = $this->errorMessage( $response->get_error_message(), wp_remote_retrieve_body( $response ) );
			$this->current_error_code = $code;
			$this->current_error_message = $message;
			$array = array(
				'code'     => $code,
				'message'  => $message,
			);
			throw new JsonRequestException( $array );
		} else {
			$code = intval( wp_remote_retrieve_response_code( $response ) );
			$this->current_error_code = $code;
			if ( ! in_array(
				$code,
				array( 200, 201 )
			) ) {
				$message = $this->errorMessage( wp_remote_retrieve_response_message( $response ), wp_remote_retrieve_body( $response ) );
				$this->current_error_message = $message;
				$array = array(
					'code'     => $code,
					'message'  => $message,
				);
				throw new JsonRequestException( $array );
			} else {
				return wp_remote_retrieve_body( $response );
			}
		}
	}

	/*
	 * Better error message
	 */
	protected function errorMessage( $message, $return ) {
		$message = array( trim( $message ) );
		if ( ! empty( $return ) ) {
			if ( $return = json_decode( $return ) ) {
				if ( isset( $return->errors ) && is_countable( $return->errors ) && count( $return->errors ) > 0 ) {
					foreach ( $return->errors as $error ) {
						if ( isset( $error->error ) && ! empty( $error->error ) ) {
							$message[] = trim( $error->error );
						}
					}
				} elseif ( isset( $return->errors ) && is_object( $return->errors ) && isset( $return->errors->error ) ) { // Em algumas situações está a chegar um objecto e não um array de objectos
					$message[] = $return->errors->error;
				}
			}
		}
		return implode( ' - ', $message );
	}

	/*
	 * Core method that does the raw request and returns the JSON response using GET
	 */
	protected function processGetRequest() {
		//On GET all arguments are added to the URL, and because we already got the api key, we'll do it like this
		if ( isset( $this->args ) && is_array( $this->args ) && count( $this->args ) > 0 ) {
			$this->api_url .= '&'. http_build_query( $this->args );
		}
		//Do it - wp_remote_get
		do_action( 'invoicexpress_woocommerce_debug', 'API GET', null, array( 'url' => $this->api_url ) );
		$response = wp_remote_get( $this->api_url, $this->wp_remote_args() );
		return $this->handle_and_return_response( $response );
	}

	/*
	 * Core method that does the raw request and returns the JSON response using POST
	 */
	protected function processPostRequest() {
		//Do it - wp_remote_post
		do_action( 'invoicexpress_woocommerce_debug', 'API POST', null, array( 'url' => $this->api_url, 'body' => serialize( $this->args ) ) );
		$response = wp_remote_post( $this->api_url, $this->wp_remote_args( json_encode( $this->args ) ) );
		return $this->handle_and_return_response( $response );
	}

	/*
	 * Core method that does the raw request and returns the JSON response using PUT
	 */
	protected function processPutRequest() {
		//Do it - wp_remote_put
		$http        = _wp_http_get_object();
		$defaults    = array( 'method' => 'PUT' );
    	$parsed_args = wp_parse_args( $this->wp_remote_args( json_encode( $this->args ) ), $defaults );
    	do_action( 'invoicexpress_woocommerce_debug', 'API PUT', null, array( 'url' => $this->api_url, 'body' => serialize( $this->args ) ) );
		$response    = $http->request( $this->api_url, $parsed_args );
		return $this->handle_and_return_response( $response );
	}

	/*
	 * Method that starts the get request while the status code is not the specified
	 * @return php array with the parsed response
	 */
	public function getRequestWhileStatusCode( $code ) {
		//Do it - wp_remote_get
		$i    = 1;
		$json = '';

		do {
			try {
				$json = $this->processGetRequest();
			} catch( JsonRequestException $e ) {
				//Do not return yet, keep trying
				if ( $i == apply_filters( 'invoicexpress_woocommerce_get_pdf_timeout', 5 ) ) {
					break;
				} else {
					sleep( 2 );
				}
			}
			do_action( 'invoicexpress_woocommerce_debug', 'Trying to get PDF from InvoiceXpress', null, array(
				'i' => $i,
				'status' => $this->current_error_code,
			) );
			$i++;
		} while ( $this->current_error_code != $code );
		
		if ( $this->current_error_code != $code ) {
			do_action( 'invoicexpress_woocommerce_debug', 'Gave up trying to get PDF from InvoiceXpress', null );
			return array(
				'success'       => false,
				'error_code'    => $this->current_error_code,
				'error_message' => $this->current_error_message,
			);
		} else {
			return $this->jsonToObject( $json );
		}

	}


}

<?php

namespace Webdados\InvoiceXpressWooCommerce;

/* WooCommerce HPOS ready 2023-07-13 */

/**
 * Notices
 *
 * @package Webdados
 * @since   2.0.0
 */
class Notices {

	/**
	 * Add notice.
	 *
	 * @param string $message The notice message.
	 * @param string $type    The notice type.
	 *                        Can be success, error, warning or info.
	 * @return void
	 */
	public static function add_notice( $message, $type = 'success' ) {

		$admin_notices = get_option( 'hd_wc_ie_plus_notices', array() );

		$admin_notices[] = array(
			'type'    => $type,
			'message' => $message,
		);

		update_option( 'hd_wc_ie_plus_notices', $admin_notices );
	}

	/**
	 * Output notices.
	 *
	 * @param  array $notices Array of notices.
	 * @return void
	 */
	public static function output_notices() {

		$notices = get_option( 'hd_wc_ie_plus_notices', [] );

		if ( empty( $notices ) ) {
			return;
		}

		foreach ( $notices as $notice ) {
			static::output_notice( $notice );
		}

		update_option( 'hd_wc_ie_plus_notices', array() );
	}

	/**
	 * Output notice.
	 *
	 * @param  array $notice The notice data.
	 * @return void
	 */
	public static function output_notice( $notice ) {

		if ( empty( $notice['message'] ) ) {
			return;
		}

		printf(
			'<div class="notice notice-%1$s notice-ixwc">
				<p>%2$s</p>
			</div>',
			esc_attr( ! empty( $notice['type'] ) ? $notice['type'] : 'success' ),
			wp_kses_post( $notice['message'] )
		);
	}
}

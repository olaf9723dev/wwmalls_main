<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Add commission table reference column edit screen link
add_filter( 'slicewp_list_table_commissions_column_reference', 'slicewp_list_table_commissions_add_reference_edit_link_pmpro', 10, 2 );
add_filter( 'slicewp_list_table_payout_commissions_column_reference', 'slicewp_list_table_commissions_add_reference_edit_link_pmpro', 10, 2 );

// Insert a new pending/unpaid commission
add_action( 'pmpro_added_order', 'slicewp_insert_commission_pmpro', 10, 1 );

// Update the status of the commission to "unpaid", thus marking it as complete
add_action( 'pmpro_updated_order', 'slicewp_accept_pending_commission_pmpro', 10, 1 );

// Update the status of the commission to "rejected" when the originating order is failed
add_action( 'pmpro_updated_order', 'slicewp_reject_commission_on_fail_pmpro', 10, 1 );

// Update the status of the commission to "rejected" when the originating order is refunded
add_action( 'pmpro_updated_order', 'slicewp_reject_commission_on_refund_pmpro', 10, 1 );

// Update the status of the commission to "rejected" when the originating order is deleted
add_action( 'pmpro_delete_order', 'slicewp_reject_commission_on_delete_pmpro', 10, 1 );

// Add the commission settings in membership level pages
add_action( 'pmpro_membership_level_after_other_settings', 'slicewp_add_product_commission_settings_pmpro' );

// Saves the commissions settings in 
add_action( 'pmpro_save_membership_level', 'slicewp_save_product_commission_settings_pmpro' );

// Add the reference amount in the commission data
add_filter( 'slicewp_pre_insert_commission_data', 'slicewp_add_commission_data_reference_amount_pmpro' );
add_filter( 'slicewp_pre_update_commission_data', 'slicewp_add_commission_data_reference_amount_pmpro' );


/**
 * Adds the edit screen link to the reference column value from the commissions list table
 *
 * @param string $output
 * @param array  $item
 *
 * @return string
 *
 */
function slicewp_list_table_commissions_add_reference_edit_link_pmpro( $output, $item ) {

	if( empty( $item['reference'] ) )
		return $output;

	if( empty( $item['origin'] ) || $item['origin'] != 'pmpro' )
		return $output;

    // Get the order
	$order = new MemberOrder( $item['reference'] );

	// Create link to payment only if the payment exists
    if( ! empty( $order->id ) )
		$output = '<a href="' . add_query_arg( array( 'page' => 'pmpro-orders', 'order' => $item['reference'] ), admin_url( 'admin.php' ) ) . '">' . $item['reference'] . '</a>';

	return $output;

}


/**
 * Inserts a new pending commission when a new order is registered
 *
 * @param MemberOrder $order
 *
 */
function slicewp_insert_commission_pmpro( $order ) {

	global $pmpro_currency;

    // Verify if commissions are disabled for the purchased membership
    if ( get_pmpro_membership_level_meta( $order->membership_id, 'slicewp_disable_commissions', true ) ) {
		return;
	}

	// Get and check to see if referrer exists
	$affiliate_id = slicewp_get_referrer_affiliate_id();
	$visit_id	  = slicewp_get_referrer_visit_id();

	/**
	 * Filters the referrer affiliate ID for Paid Memberships Pro.
	 * This is mainly used by add-ons for different functionality.
	 *
	 * @param int $affiliate_id
	 * @param MemberOrder $order
	 *
	 */
	$affiliate_id = apply_filters( 'slicewp_referrer_affiliate_id_pmpro', $affiliate_id, $order );

	if ( empty( $affiliate_id ) ) {
		return;
	}

	// Verify if the affiliate is valid
	if ( ! slicewp_is_affiliate_valid( $affiliate_id ) ) {

		slicewp_add_log( 'PMPRO: Pending commission was not created because the affiliate is not valid.' );
		return;
		
	}

	// Check to see if a commission for this order has been registered
	$commissions = slicewp_get_commissions( array( 'reference' => $order->id, 'origin' => 'pmpro' ) );

	if ( ! empty( $commissions ) ) {

		slicewp_add_log( 'PMPRO: Commission was not created because another commission for the reference and origin already exists.' );
		return;

	}

	// Check to see if the affiliate made the purchase
	if ( empty( slicewp_get_setting( 'affiliate_own_commissions' ) ) ) {

		$affiliate = slicewp_get_affiliate( $affiliate_id );

		if ( is_user_logged_in() && get_current_user_id() == $affiliate->get('user_id') ) {

			slicewp_add_log( 'PMPRO: Pending commission was not created because the customer is also the affiliate.' );
			return;

		}

		if ( slicewp_affiliate_has_email( $affiliate_id, $order->Email ) ) {

			slicewp_add_log( 'PMPRO: Commission was not created because the customer is also the affiliate.' );
			return;

		}
		
	}


	// Get user attached to the payment
	$user = get_userdata( $order->user_id );

	// Process the customer
	$customer_args = array(
		'email'   	   => $order->Email,
		'user_id' 	   => $order->user_id,
		'first_name'   => ( $user ? $user->get( 'first_name' ) : $order->FirstName ),
		'last_name'    => ( $user ? $user->get( 'last_name' ) : $order->LastName ),
		'affiliate_id' => $affiliate_id
	);

	$customer_id = slicewp_process_customer( $customer_args );

	if ( $customer_id ) {
		slicewp_add_log( sprintf( 'PMPRO: Customer #%s has been successfully processed.', $customer_id ) );
	} else {
		slicewp_add_log( 'PMPRO: Customer could not be processed due to an unexpected error.' );
	}
	

    // Get the order amount
    $amount = $order->total;
    
    // Exclude tax
    if ( slicewp_get_setting( 'exclude_tax', false ) ) {
		$amount = $amount - $order->tax;
	}

    // Calculate the commission amount for the entire order
    $args = array(
        'origin'	   => 'pmpro',
        'type' 		   => 'subscription',
        'affiliate_id' => $affiliate_id,
        'product_id'   => $order->membership_id,
		'customer_id'  => $customer_id
    );

	$commission_amount = slicewp_calculate_commission_amount( slicewp_maybe_convert_amount( $amount, $pmpro_currency, slicewp_get_setting( 'active_currency', 'USD' ) ), $args );

    // Check that the commission amount is not zero
    if ( ( $commission_amount == 0 ) && empty( slicewp_get_setting( 'zero_amount_commissions' ) ) ) {

        slicewp_add_log( 'PMPRO: Commission was not inserted because the commission amount is zero. Order: ' . absint( $order->id ) );
        return;

    }


	// Get the commission status
	switch ( $order->status ) {

		case 'success':
			$commission_status = 'unpaid';
			break;

		case 'error':
			$commission_status = 'rejected';
			break;

		default:
			$commission_status = 'pending';
			break;

	}

	// Prepare commission data
	$commission_data = array(
		'affiliate_id'		=> $affiliate_id,
		'visit_id'			=> ( ! is_null( $visit_id ) ? $visit_id : 0 ),
		'date_created'		=> slicewp_mysql_gmdate(),
		'date_modified'		=> slicewp_mysql_gmdate(),
		'type'				=> 'subscription',
		'status'			=> $commission_status,
		'reference'			=> $order->id,
		'reference_amount'	=> slicewp_sanitize_amount( $order->total ),
		'customer_id'		=> $customer_id,
		'origin'			=> 'pmpro',
		'amount'			=> slicewp_sanitize_amount( $commission_amount ),
		'currency'			=> slicewp_get_setting( 'active_currency', 'USD' )
	);

	// Insert the commission
	$commission_id = slicewp_insert_commission( $commission_data );

	if ( ! empty( $commission_id ) ) {

		// Update the visit with the newly inserted commission_id
		if ( ! is_null( $visit_id ) ) {

			slicewp_update_visit( $visit_id, array( 'date_modified' => slicewp_mysql_gmdate(), 'commission_id' => $commission_id ) );
			
		}
		
		slicewp_add_log( sprintf( 'PMPRO: Commission #%s has been successfully inserted.', $commission_id ) );
		
	} else {

		slicewp_add_log( 'PMPRO: Commission could not be inserted due to an unexpected error.' );
		
	}

}


/**
 * Updates the status of the commission attached to a order to "unpaid", thus marking it as complete.
 *
 * @param MemberOrder $order
 *
 */
function slicewp_accept_pending_commission_pmpro( $order ) {

    // Check if the new order status is 'success'.
    if ( $order->status != 'success' )
        return;

	// Check to see if a commission for this order has been registered.
	$commissions = slicewp_get_commissions( array( 'number' => -1, 'reference' => $order->id, 'origin' => 'pmpro', 'order' => 'ASC' ) );

	if ( empty( $commissions ) )
		return;

	foreach ( $commissions as $commission ) {

		// Return if the commission has already been paid.
		if ( $commission->get( 'status' ) == 'paid' )
			continue;

		// Prepare commission data.
		$commission_data = array(
			'date_modified' => slicewp_mysql_gmdate(),
			'status' 		=> 'unpaid'
		);

		// Update the commission.
		$updated = slicewp_update_commission( $commission->get( 'id' ), $commission_data );

		if ( false !== $updated ) {

			slicewp_add_log( sprintf( 'PMPRO: Pending commission #%s successfully marked as completed.', $commission->get( 'id' ) ) );

		} else {

			slicewp_add_log( sprintf( 'PMPRO: Pending commission #%s could not be completed due to an unexpected error.', $commission->get( 'id' ) ) );

		}

	}

}


/**
 * Update the status of the commission to "rejected" when the originating order is failed.
 *
 * @param MemberOrder $order
 *
 */
function slicewp_reject_commission_on_fail_pmpro( $order ) {

	if ( $order->status != 'error' )
		return;

	// Check to see if a commission for this order has been registered.
	$commissions = slicewp_get_commissions( array( 'number' => -1, 'reference' => $order->id, 'origin' => 'pmpro', 'order' => 'ASC' ) );

	if ( empty( $commissions ) )
		return;

	foreach ( $commissions as $commission ) {

		if ( $commission->get( 'status' ) == 'paid' ) {

			slicewp_add_log( sprintf( 'PMPRO: Commission #%s could not be rejected because it was already paid.', $commission->get( 'id' ) ) );
			continue;
	
		}
	
		// Prepare commission data.
		$commission_data = array(
			'date_modified' => slicewp_mysql_gmdate(),      
			'status' 		=> 'rejected'
		);
	
		// Update the commission.
		$updated = slicewp_update_commission( $commission->get( 'id' ), $commission_data );
	
		if ( false !== $updated ) {

			slicewp_add_log( sprintf( 'PMPRO: Commission #%s successfully marked as rejected, after order #%s failed.', $commission->get( 'id' ), $order->id ) );

		} else {

			slicewp_add_log( sprintf( 'PMPRO: Commission #%s could not be rejected due to an unexpected error.', $commission->get( 'id' ) ) );

		}

	}

}


/**
 * Update the status of the commission to "rejected" when the originating order is refunded.
 *
 * @param MemberOrder $order
 *
 */
function slicewp_reject_commission_on_refund_pmpro( $order ) {

	if ( ! slicewp_get_setting( 'reject_commissions_on_refund', false ) )
		return;

	if ( $order->status != 'refunded' )
		return;

	// Check to see if a commission for this order has been registered.
	$commissions = slicewp_get_commissions( array( 'number' => -1, 'reference' => $order->id, 'origin' => 'pmpro', 'order' => 'ASC' ) );

	if ( empty( $commissions ) )
		return;

	foreach ( $commissions as $commission ) {

		if ( $commission->get( 'status' ) == 'paid' ) {

			slicewp_add_log( 'PMPRO: Commission could not be rejected because it was already paid.' );
			continue;
	
		}
	
		// Prepare commission data.
		$commission_data = array(
			'date_modified' => slicewp_mysql_gmdate(),
			'status' 		=> 'rejected'
		);
	
		// Update the commission.
		$updated = slicewp_update_commission( $commission->get( 'id' ), $commission_data );
	
		if ( false !== $updated ) {

			slicewp_add_log( sprintf( 'PMPRO: Commission #%s successfully marked as rejected, after order #%s was refunded.', $commission->get( 'id' ), $order->id ) );

		} else {

			slicewp_add_log( sprintf( 'PMPRO: Commission #%s could not be rejected due to an unexpected error.', $commission->get( 'id' ) ) );

		}

	}

}


/**
 * Update the status of the commission to "rejected" when the originating order is deleted.
 *
 * @param int $order_id
 *
 */
function slicewp_reject_commission_on_delete_pmpro( $order_id ) {

	// Check to see if a commission for this order has been registered.
	$commissions = slicewp_get_commissions( array( 'number' => -1, 'reference' => $order_id, 'origin' => 'pmpro', 'order' => 'ASC' ) );

	if ( empty( $commissions ) )
		return;

	foreach ( $commissions as $commission ) {

		if ( $commission->get( 'status' ) == 'paid' ) {

			slicewp_add_log( sprintf( 'PMPRO: Commission #%s could not be rejected because it was already paid.', $commission->get( 'id' ) ) );
			continue;
	
		}
	
		// Prepare commission data.
		$commission_data = array(
			'date_modified' => slicewp_mysql_gmdate(),
			'status' 		=> 'rejected'
		);
	
		// Update the commission.
		$updated = slicewp_update_commission( $commission->get( 'id' ), $commission_data );
	
		if ( false !== $updated ) {

			slicewp_add_log( sprintf( 'PMPRO: Commission #%s successfully marked as rejected, after order #%s was deleted.', $commission->get( 'id' ), $order_id ) );

		} else {

			slicewp_add_log( sprintf( 'PMPRO: Commission #%s could not be rejected due to an unexpected error.', $commission->get( 'id' ) ) );

		}

	}

}


/**
 * Adds the product commission settings fields in PMPRO add/edit membership page
 *
 */
function slicewp_add_product_commission_settings_pmpro() {

    // Check for membership level id
    if ( empty( $_GET['edit'] ) )
        return;

    $level_id = intval( $_GET['edit'] );

    // Get the disable commissions value
    $disable_commissions = get_pmpro_membership_level_meta( $level_id, 'slicewp_disable_commissions', true );

?>

    <hr>
    <h2 class="title"><?php echo __( 'Subscription Commission Settings', 'slicewp' ); ?></h2>

    <div id="slicewp_product_settings" class="slicewp-options-groups-wrapper">

        <?php

            /**
             * Hook to add option groups before the core one
             * 
             */
            do_action( 'slicewp_pmpro_commission_settings_top' );

        ?>

        <table class="slicewp-options-group form-table">
            <tbody>

            	<?php
        
	                /**
	                 * Hook to add settings before the core ones
	                 * 
	                 */
	                do_action( 'slicewp_pmpro_commission_settings_core_top' );

	            ?>

                <tr class="slicewp-option-field-wrapper">
                    <th scope="row" valign="top">
                        <?php echo __( 'Disable Commissions', 'slicewp' );?>:
                    </th>
                    <td>
                        <input type="checkbox" class="slicewp-option-field-disable-commissions" name="slicewp_disable_commissions" id="slicewp-disable-commissions" value="1" <?php checked( $disable_commissions, true ); ?> />
                        <label for="slicewp-disable-commissions"><?php echo __( 'Disable commissions for this subscription.', 'slicewp' ); ?></label>
                    </td>
                </tr>

                <?php

	                /**
	                 * Hook to add settings after the core ones
	                 * 
	                 */
	                do_action( 'slicewp_pmpro_commission_settings_core_bottom' );
	            ?>

            </tbody>
        </table>

        <?php

            /**
             * Hook to add option groups after the core one
             * 
             */
            do_action( 'slicewp_pmpro_commission_settings_bottom' );
        
        ?>

    </div>

<?php

    // Add nonce field
    wp_nonce_field( 'slicewp_save_membership', 'slicewp_token', false );

}


/**
 * Saves the commission settings into WordPress options
 * 
 * @param int $level_id
 * 
 */
function slicewp_save_product_commission_settings_pmpro( $level_id ) {

    // Verify for nonce
    if ( empty( $_POST['slicewp_token'] ) || ! wp_verify_nonce( $_POST['slicewp_token'], 'slicewp_save_membership' ) )
        return;

    // Update the disable commissions settings
    if ( ! empty( $_POST['slicewp_disable_commissions'] ) ) {

        update_pmpro_membership_level_meta( $level_id, 'slicewp_disable_commissions', 1 );

    } else {

        delete_pmpro_membership_level_meta( $level_id, 'slicewp_disable_commissions' );

    }

}


/**
 * Adds the reference amount in the commission data
 * 
 * @param array $commission_data
 * 
 * @return array
 * 
 */
function slicewp_add_commission_data_reference_amount_pmpro( $commission_data ) {

	if ( ! ( doing_action( 'slicewp_admin_action_add_commission' ) || doing_action( 'slicewp_admin_action_update_commission' ) ) )
		return $commission_data;

	// Check if the origin is Paid Memberships Pro
	if ( 'pmpro' != $commission_data['origin'] )
		return $commission_data;

	// Check if we have a reference
	if ( empty( $commission_data['reference'] ) )
		return $commission_data;

	// Get the order
	$order = new MemberOrder( $commission_data['reference'] );

	if ( empty( $order ) || empty( $order->total ) )
		return $commission_data;

	// Save the reference amount
	$commission_data['reference_amount'] = slicewp_sanitize_amount( $order->total );

	// Return the updated commission data
	return $commission_data;

}
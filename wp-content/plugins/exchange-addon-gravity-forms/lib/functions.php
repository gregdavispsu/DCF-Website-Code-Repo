<?php

/**
 * Translate the Gravity Forms payment status.
 *
 * @since 1.0
 *
 * @param string $gf_status
 *
 * @return string
 */
function itegfp_translate_gravity_forms_payment_status( $gf_status ) {

	$gf_payment_statuses = array(
		"Processing" => 'pending',
		"Pending"    => 'pending',
		"Paid"       => 'paid',
		"Active"     => 'paid',
		"Approved"   => 'paid',
		"Completed"  => 'paid',
		"Expired"    => 'voided',
		"Failed"     => 'voided',
		"Cancelled"  => 'voided',
		"Reversed"   => 'refunded',
		"Refunded"   => 'refunded',
		"Voided"     => 'voided',
		"Void"       => 'voided',
	);

	return isset( $gf_payment_statuses[ $gf_status ] ) ? $gf_payment_statuses[ $gf_status ] : '';
}

/**
 * Get a transaction by the method ID.
 *
 * @since 1.0
 *
 * @param $id int
 *
 * @return IT_Exchange_Transaction
 */
function itegfp_get_transaction_by_method_id( $id ) {
	$args = array(
		'transaction_method' => 'gravity-forms-exchange',
		'meta_query'         => array(
			'key'   => '_it_exchange_transaction_method_id',
			'value' => absint( $id ),
			'type'  => 'NUMERIC'
		),
		'numberposts'        => 1
	);

	$transactions = it_exchange_get_transactions( $args );

	return reset( $transactions );
}

/**
 * Override the registration form.
 *
 * @since 1.0
 *
 * @return bool
 */
function itegfp_override_registration() {

	$options = it_exchange_get_option( 'addon_gravity_forms' );

	return $options['registration_form'];
}

/**
 * Resets the session activity to the current timestamp.
 *
 * Clone of Guest Checkout method without checks for current page.
 *
 * @since 1.6.0
 *
 * @return void
 */
function itegfp_guest_checkout_bump_session() {
	$now            = time();
	$customer_email = it_exchange_get_cart_data( 'guest-checkout-user' );
	$customer_email = is_array( $customer_email ) ? reset( $customer_email ) : $customer_email;

	it_exchange_update_cart_data( 'guest-checkout', $now );
	$GLOBALS['current_user'] = it_exchange_guest_checkout_generate_guest_user_object( $customer_email );
}
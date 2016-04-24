<?php

add_action('wp_ajax_save_bank_info', 'v2w_stripe_recipient_send');

/**
 *	Create or update Recipient in Stripe
 *	Creates from currently logged in user
 *
 *	via POST
 */
function v2w_stripe_recipient_send() 
{
	if( ! isset($_POST['action']) || $_POST['action'] != 'save_bank_info' )
		return;

	$routing = filter_var($_POST['routing_number'], FILTER_SANITIZE_STRING);
	$account = filter_var($_POST['account_number'], FILTER_SANITIZE_STRING);
	$ssn = filter_var($_POST['ssn'], FILTER_SANITIZE_STRING);

	Stripe\Stripe::setApiKey( STRIPE_API_KEY );

	//get current user
	$user = wp_get_current_user();
	$designer = new Designer( $user->ID );
	//$customer = shopp_customer($user->ID, 'wpuser');

	if( ! $designer )
		throw new Exception('Invalid Designer...');

	//get current key, if any
	$key = $designer->get_stripe_key();

	if( ! $key || empty( $key ) ) {
		//create new 

		try {

			$rp = \Stripe\Recipient::create(array(
				'name' => $user->first_name . ' ' . $user->last_name,
				'type' => 'individual',
				'bank_account' => array(
					'country' => 'US',
					'routing_number' => $routing,
					'account_number' => $account
				),
				'tax_id' => $ssn,
				'email' => $designer->get_email(),
				'metadata' => array(
					'designer_id' => $designer->ID
				)
			));

			//save rp id
			$designer->update_stripe_key( $rp );

			$response = array(
				'code' => 200,
				'status' => 'success',
				'message' => 'You have updated your bank account information'
			);

		}catch(Stripe\Error\InvalidRequest $e) {

			$body = $e->getJsonBody();
			$err = $body['error'];

			$response = array(
				'code' => 400,
				'status' => 'error',
				'message' => $err['message']
			);

		}

	}else {
		//update the existing key

		try {
			$rp = \Stripe\Recipient::retrieve( $key );
			$rp->tax_id = $ssn;
			$rp->bank_account = array(
				'country' => 'US',
				'routing_number' => $routing,
				'account_number' => $account
			);
			$rp->save();

			//save rp id
			$designer->update_stripe_key( $rp );

			$response = array(
				'code' => 200,
				'status' => 'success',
				'message' => 'You have updated your bank account information'
			);

		}catch(Stripe\Error\InvalidRequest $e) {

			$body = $e->getJsonBody();
			$err = $body['error'];

			$response = array(
				'code' => 400,
				'status' => 'error',
				'message' => $err['message']
			);

		}
	}

	//return
	V2W::json($response);
}
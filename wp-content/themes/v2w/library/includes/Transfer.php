<?php

add_action('init', array('V2WTransfer', 'stripe_transfer_webhook'));

class V2WTransfer {
	
	static $table = 'v2w_transfers';

	/**
	 *	Add Transfer
	 */
	public static function add_transfer( $designer_id, $amt, $reference = '' ) 
	{
		global $wpdb;

		if( $amt < 0 )
			throw new Exception('Amount must be greater than 0');

		$transfer = $wpdb->insert(
			self::$table,
			array(
				'designer_id' => $designer_id,
				'reference' => $reference,
				'amt' => $amt,
				'created_at' => date('Y-m-d h:i:s')
			),
			array(
				'%d',
				'%s',
				'%d',
				'%s'
			)
		);

		if( ! $transfer )
			throw new Exception('Unable to add transfer');
		
		return $wpdb->insert_id;	
	}

	/**
	 *	Get Transfers
	 */
	public static function get_transfers( $designer_id ) 
	{
		global $wpdb;

		$table = self::$table;

		$transfers = $wpdb->get_results("
			SELECT * FROM {$table} 
			WHERE designer_id = $designer_id
			ORDER BY created_at
			LIMIT 99999
		");

		return $transfers;
	}

	/**
	 *	Receive transfer notifications from Stripe
	 */
	public static function stripe_transfer_webhook() 
	{
		if( ! isset($_GET['event']) || $_GET['event'] != 'stripe.transfer' )
			return;

		Stripe\Stripe::setApiKey( STRIPE_API_KEY );

		// Retrieve the request's body and parse it as JSON
		$input = @file_get_contents("php://input");
		$data = json_decode($input);

		if( ! isset($data->type) || $data->type != 'transfer.created' )
			return;	//not an event we want

		$transfer = $data->data->object;

		//get data
		$id = $transfer->id;
		$amt = $transfer->amount;
		$rp = $transfer->recipient;
		$designer_id = $transfer->meta_data->designer_id;

		//find designer
		//$designer_id = Designer::find_by_rp( $rp );

		if( ! $designer_id )
			return false;	//unable to find designer

		try {

			$insert = self::add_transfer( $designer_id, $amt, $id );

		}catch(Exception $e) {
			http_response_code(500);
			exit;
		}

		http_response_code(200);
	}

}
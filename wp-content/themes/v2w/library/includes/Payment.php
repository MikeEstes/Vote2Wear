<?php

add_action('shopp_captured_order_event', array('V2WPayment', 'save_payment'));
add_filter('manage_users_columns', array('V2WPayment', 'user_columns'));
add_action('manage_users_custom_column',  array('V2WPayment', 'add_balance_column'), 10, 3);

class V2WPayment {

	static $table = 'v2w_payments';

	/**
	 *	Add a payment for a Designer
	 */
	public static function add_payment( $designer_id, $design_id, $purchase_id, $amt ) 
	{
		global $wpdb;

		//validate amount
		if( $amt < 0 )
			throw new Exception('Amount must be greater than 0');

		$payment = $wpdb->insert(
			self::$table,
			array(
				'designer_id' => $designer_id,
				'design_id' => $design_id,
				'purchase_id' => $purchase_id,
				'amt' => $amt,
				'status' => 'pending',
				'created_at' => date('Y-m-d h:i:s')
			),
			array(
				'%d',
				'%d',
				'%d',
				'%d',
				'%s',
				'%s'
			)
		);

		if( ! $payment )
			throw new Exception('Unable to add payment for designer');

		return $wpdb->insert_id;
	}

	/**
	 *	Save payment on purchase
	 */
	public static function save_payment( $Event ) 
	{
		$Order = shopp_order ( $Event->order );

		foreach( $Order->purchased as $purchase ) {
			$product_id = $purchase->product;
			$qty = $purchase->quantity;
			$amt = number_format( AMOUNT_EARNED_PER_SHIRT * $qty, 2 );

			$designer = get_field('designer', $product_id);
			$design = get_field('design', $product_id);

			self::add_payment( $designer['ID'], $design->ID, $purchase->purchase, $amt );
		}
	}

	/**
	 *	Get payments for a designer
	 */
	public static function get_payments( $designer_id ) 
	{
		global $wpdb;

		$table = self::$table;

		$payments = $wpdb->get_results("
			SELECT * FROM {$table}
			WHERE designer_id = $designer_id
			ORDER BY created_at
			LIMIT 99999
		");

		return $payments;
	}

	/**
	 *	Get Balance for Designer
	 */
	public static function get_balance( $designer_id = 0 ) 
	{
		global $wpdb;

		//$table = self::$table;

		/*$balance = $wpdb->get_var("
			SELECT SUM(amt) 
			FROM {$table}
			WHERE status = 'pending' AND designer_id = {$designer_id}
			LIMIT 999999
		");*/

		$money = $wpdb->get_row("
			SELECT (
			    SELECT SUM(amt)
			    FROM v2w_payments
			    WHERE designer_id = {$designer_id}
			) as owed, (
			    SELECT SUM(amt) 
			    FROM v2w_transfers
			    WHERE designer_id = {$designer_id}
			) as paid
			LIMIT 1
		");

		if( is_null($money) )
			return 0;

		$owed = $money->owed ?: 0;
		$paid = $money->paid ?: 0;

		return number_format(($owed - $paid), 2);
	}

	//add balance to columns
	public static function user_columns( $columns ) 
	{
		$columns['balance'] = 'Balance';
		return $columns;
	}

	//populate balance column
	public static function add_balance_column( $value, $column_name, $user_id ) 
	{
		if( $column_name != 'balance' )
			return $value;

		$balance = self::get_balance( $user_id );
		return '$' . number_format($balance, 2);
	}
	
}

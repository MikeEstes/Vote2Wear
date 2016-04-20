<?php
/**
 * Stripe
 *
 * Uses the Stripe REST API
 *
 * @copyright Ingenesis Limited, April 2012-2014
 * @author Jonathan Davis
 * @package Shopp\Gateways\Stripe
 * @version 1.1
 * @since 1.2
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with the Shopp plugin. If not, see <http://www.gnu.org/licenses/>.
 *
 **/

defined( 'WPINC' ) || header( 'HTTP/1.1 403' ) & exit; // Prevent direct access

class Stripe extends GatewayFramework implements GatewayModule {

	public $secure = true;
	public $captures = true;
	public $refunds = true;
	public $cards = array('visa', 'mc', 'amex', 'disc', 'jcb', 'dc');
	public $codes = array(200, 400, 401, 402, 404, 500, 502, 503, 504);	// List of valid response codes

	public $response;

	const APIURL = 'https://api.stripe.com';
	const APIVERSION = 'v1';

	public function __construct () {
		parent::__construct();

		$this->setup('apikey');

		// Transaction processing handlers
		add_action('shopp_stripe_sale', array($this, 'sale'));
		add_action('shopp_stripe_auth', array($this, 'sale'));
		add_action('shopp_stripe_capture', array($this, 'capture'));
		add_action('shopp_stripe_refund', array($this, 'refund'));
		add_action('shopp_stripe_void', array($this, 'void'));
	}

	public function actions () { /* Not Used */ }

	public function sale ( $Event ) {

		$transaction = apply_filters('shopp_stripe_sale_txn', $this->apicharge($Event), $Event);
		$response = $this->send($transaction, 'charges');

		if ( ! $response || is_a($response, 'ShoppError') ) {
			return shopp_add_order_event($Event->order, 'auth-fail', array(
				'amount'  => $Event->amount,	             // Amount to be captured
				'error'   => $response->code,	             // Error code (if provided)
				'message' => join(' ', $response->messages), // Error message reported by the gateway
				'gateway' => $Event->gateway                 // Gateway handler name (module name from @subpackage)
			));
		}

		$Paymethod = ShoppOrder()->paymethod();
		$Billing = ShoppOrder()->Billing;

		shopp_add_order_event($Event->order, 'authed', array(
			'txnid'     => $response->id,
			'amount'    => $Event->amount,
			'gateway'   => $this->module,
			'paymethod' => $Paymethod->label,
			'paytype'   => $Billing->cardtype,
			'payid'     => $Billing->card,
			'capture'   => $response->captured
		));

	}

	public function capture ( $Event ) {

		$transaction = apply_filters('shopp_stripe_capture_txn', $this->apicapture($Event), $Event);
		$response = $this->send($transaction, "charges/$Event->txnid/capture");

		if ( ! $response || is_a($response, 'ShoppError') ) {
			return shopp_add_order_event($Event->order, 'capture-fail', array(
				'amount'  => $Event->amount,	             // Amount to be captured
				'error'   => $response->code,	             // Error code (if provided)
				'message' => join(' ', $response->messages), // Error message reported by the gateway
				'gateway' => $Event->gateway                 // Gateway handler name
			));
		}

		$Paymethod = ShoppOrder()->paymethod();
		$Billing = ShoppOrder()->Billing;

		shopp_add_order_event($Event->order, 'captured', array(
			'txnid'     => $response->id,
			'amount'    => $Event->amount,
			'fees'      => false,
			'gateway' => $Event->gateway
		));

	}

	public function refund ( $Event ) {

		$transaction = apply_filters('shopp_stripe_refund_txn', $this->apirefund($Event), $Event);
		$response = $this->send($transaction, "charges/$Event->txnid/refund");

		if ( ! $response || is_a($response,'ShoppError') || ! $response->refunded ) {
			return shopp_add_order_event($Event->order,'refund-fail',array(
				'amount'  => $Event->amount,					// Amount of the refund attempt
				'error'   => $response->code,					// Error code (if provided)
				'message' => join(' ', $response->messages),	// Error message reported by the gateway
				'gateway' => $Event->gateway				// Gateway handler name (module name from @subpackage)
			));
		}

		shopp_add_order_event($Event->order, 'refunded', array(
			'txnid'   => $response->id,						// Transaction ID for the REFUND event
			'amount'  => $Event->amount,						// Amount refunded
			'gateway' => $Event->gateway					// Gateway handler name (module name from @subpackage)
		));

	}

	public function apicharge ( $Event ) {
		$Order = ShoppOrder();
		$Billing = $Order->Billing;

		$_ = array(
			'amount'   => $this->amount('total'),
			'currency' => $this->currency(),
			'card'     => array(
				'number'          => $Billing->card,
				'exp_month'       => date('m', $Billing->cardexpires),
				'exp_year'        => date('Y', $Billing->cardexpires),
				'cvc'             => $Billing->cvv,
				'name'            => $Billing->name,
				'address_line1'   => $Billing->address,
				'address_line2'   => $Billing->xaddress,
				'address_zip'     => $Billing->postcode,
				'address_state'   => $Billing->state,
				'address_country' => $Billing->country
			),
			'description' => $Order->Customer->email,
			'capture' => ( 'sale' == $Event->name ? 'true' : 'false' )
		);

		return $_;

	}

	public function apicapture ( $Event ) {
		$_ = array(
			'amount' => $Event->amount
		);

		return $_;
	}


	public function amount ( $amount, $format = array() ) {
		$amount = parent::amount($amount, $format);
		return (int)($amount * 100); // Convert to integer (no decimals)
	}

	public function apirefund ( $Event ) {
		$_ = array(
			'amount' => $this->amount($Event->amount)
		);

		return $_;
	}

	public function send ( array $data, $resource = '' ) {

		$request = http_build_query($data);
		$url = array(self::APIURL, self::APIVERSION, $resource);
		$options = array(
			'headers' => array('Authorization' => 'Basic ' . base64_encode($this->settings['apikey'] . ':'))
		);

		$response = parent::send($request, join('/', $url), false, $options);

		if ( empty($response) ) return $response;
		$response = json_decode($response);

		if ( isset($response->error) )
			return new ShoppError($response->error->message, 'stripe_' . $this->response->error->code, SHOPP_TRXN_ERR);

		if ( isset($response->failure_message) )
			return new ShoppError($response->failure_message, 'stripe_transaction_error', SHOPP_TRXN_ERR);

		return $response;
	}

	public function error ( $reponse ) {
		return $response->failure_message;
	}

	/**
	 * Settings interface
	 *
	 * @since 1.2
	 *
	 * @return void
	 **/
	public function settings () {
		$this->ui->cardmenu(0,array(
			'name' => 'cards',
			'selected' => $this->settings['cards']
		), $this->cards);

		$this->ui->text(1,array(
			'name' => 'apikey',
			'size' => 40,
			'value' => $this->settings['apikey'],
			'label' => __('Enter your Stripe API key.', 'Shopp')
		));

	}

}
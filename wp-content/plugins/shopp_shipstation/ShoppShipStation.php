<?php
/**
 * Plugin Name: Shopp ShipStation
 * Plugin URI: http://wearespry.com
 * Description: Integrates Shopp into ShipStation via the Custom Store Integration API
 * Version: 1.0
 * Author: Spry Group
 * Author URI: http://wearespry.com
 *
 **/

if( ! class_exists('ShoppShipStation') ) {

	//
	class ShoppShipStation {

		protected $version = "1.0";

		//init
		public function __construct() 
		{
			add_filter('query_vars', array($this, 'add_query_vars'), 0);
			add_action('parse_request', array($this, 'parse_request'), 0);
			add_action('init', array($this, 'add_endpoint'), 0);
		}

		/**
		 *	Add Endpoint to communicate with
		 *	Ship Station Custom Store Integration
		 */
		public function add_endpoint() 
		{
			add_rewrite_endpoint( 'shipstation', EP_PERMALINK | EP_NONE );
		}

		/**
		 *	Parse Request to determine if
		 *	this is a ship station request.
		 *
		 */
		public function parse_request( $query ) 
		{
			global $wp;

			if( isset($wp->query_vars['name']) && $wp->query_vars['name'] == 'shipstation' ) {
				if( isset($wp->query_vars['action']) && in_array($wp->query_vars['action'], array('export', 'shipnotify')) ) {
					$this->handle_request();
					exit;
				}
			}

			return $query;
		}

		/**
		 *	Add Query Vars for Ship Station
		 *
		 */
		public function add_query_vars( $vars ) 
		{
			$vars[] = 'action';
			$vars[] = 'start_date';
			$vars[] = 'end_date';
			$vars[] = 'order_number';
			$vars[] = 'carrier';
			$vars[] = 'service';
			$vars[] = 'tracking_number';
			return $vars;
		}

		/**
		 *	Handle the request from 
		 *	Ship Station
		 *
		 */
		public function handle_request() 
		{
			global $wp;

			switch( $wp->query_vars['action'] ) {
				case 'export':
					$this->export( $wp->query_vars['start_date'], $wp->query_vars['end_date'] );
					break;
				case 'shipnotify':
					$this->shipnotify( $wp->query_vars['order_number'], $wp->query_vars['carrier'], $wp->query_vars['service'], $wp->query_vars['tracking_number'] );
					break;
			}

		}

		/**
		 *	Export data and provide to 
		 *	Ship Station
		 *
		 *	@param (string) Start Date
		 *	@param (string) End Date
		 *
		 */
		public function export( $start, $end ) 
		{
			$start = new DateTime( $start );
			$end = new DateTime( $end );

			$orders = shopp_orders( $start->format('Y-m-d H:i:s'), $end->format('Y-m-d H:i:s'), true, $limit );

			//if no orders....
			if( empty($orders) ) {
				echo '<Orders></Orders>';
				exit;
			}

			//headers
			status_header(200);
			header("Content-type: text/xml");
			echo '<?xml version="1.0" encoding="utf-8"?>';
			echo '<Orders>';

			foreach( $orders as $order ) {
				ShoppPurchase($order);

				//only worry about pending orders
				//IGNORE: Ship Station docs request all orders, regardless of status
				//if( strtolower(shopp('purchase.status', 'return=1')) != 'pending' )
					//continue;

				//get items (needed for custom fields for artwork)
				$designs = array();

				while( shopp('purchase', 'items') ) {
					$id = shopp('purchase.item-product', 'return=1');
					$design = get_field('field_5624297a079ef', $id);	//get the design for this product
					//$original_artwork = get_post_meta($design->ID, '_original_artwork', true);
					//$shirts = get_post_meta($design->ID, '_mens_shirts', true);

					//$designs[] = str_replace('/home/vote2wear/public_html', get_option('siteurl'), $original_artwork );
					$designs[] = get_post_meta($design->ID, '_original_artwork_url', true);

					/*
					foreach( $shirts as $attachment ) {
						$url = wp_get_attachment_url($attachment);
						$designs[] = "Generated: " . $url;
					}
					*/

				}

				?>

					<Order>
						<OrderID><![CDATA[<?php shopp('purchase.id'); ?>]]></OrderID>
						<OrderNumber><![CDATA[<?php shopp('purchase.id'); ?>]]></OrderNumber>
						<OrderDate><?php shopp('purchase.date', array('format' => 'm/d/Y H:i a')); ?></OrderDate>
						<OrderStatus><![CDATA[<?php shopp('purchase.status'); ?>]]></OrderStatus>
						<LastModified><?php shopp('purchase.date', array('format' => 'm/d/Y H:i a')); ?></LastModified>
						<ShippingMethod><![CDATA[<?php shopp('purchase.shipmethod'); ?>]]></ShippingMethod>
						<PaymentMethod><![CDATA[<?php shopp('purchase.cardtype'); ?>]]></PaymentMethod>
						<OrderTotal><?php echo str_replace('$', '', shopp('purchase.total', 'return=1')); ?></OrderTotal>
						<TaxAmount><?php echo str_replace('$', '', shopp('purchase.tax', 'return=1')); ?></TaxAmount>
						<ShippingAmount><?php echo str_replace('$', '', shopp('purchase.freight', 'return=1')); ?></ShippingAmount>
						<CustomerNotes><![CDATA[]]></CustomerNotes>
						<InternalNotes><![CDATA[]]></InternalNotes>
						<Gift>false</Gift>
						<GiftMessage></GiftMessage>

						<?php /*
						<?php $c = 1; ?>
						<?php foreach($designs as $url) : ?>
							<CustomField<?php echo $c; ?>><![CDATA[<?php echo $url; ?>]]></CustomField<?php echo $c; ?>>
							<?php $c++; ?>
						<?php endforeach; ?>
						*/ ?>

						<Customer>
							<CustomerCode><![CDATA[]]></CustomerCode>
							<BillTo>
								<Name><![CDATA[<?php shopp('purchase.firstname'); ?> <?php shopp('purchase.lastname'); ?>]]></Name>
								<Company><![CDATA[<?php shopp('purchase.company'); ?>]]></Company>
								<Phone><![CDATA[<?php shopp('purchase.phone'); ?>]]></Phone>
								<Email><![CDATA[<?php shopp('purchase.email'); ?>]]></Email>
							</BillTo>
							<ShipTo>
								<Name><![CDATA[<?php shopp('purchase.ship-name'); ?>]]></Name>
								<Company><![CDATA[<?php shopp('purchase.company'); ?>]]></Company>
								<Address1><![CDATA[<?php shopp('purchase.shipaddress'); ?>]]></Address1>
								<Address2><![CDATA[<?php shopp('purchase.shipxaddress'); ?>]]></Address2>
								<City><![CDATA[<?php shopp('purchase.shipcity'); ?>]]></City>
								<State><![CDATA[<?php shopp('purchase.shipstate'); ?>]]></State>
								<PostalCode><![CDATA[<?php shopp('purchase.shippostcode'); ?>]]></PostalCode>
								<!--<Country><![CDATA[<?php shopp('purchase.shipcountry'); ?>]]></Country>-->
								<Country><![CDATA[US]]></Country>
								<Phone><![CDATA[<?php shopp('purchase.phone'); ?>]]></Phone>
							</ShipTo>
						</Customer>
						<Items>
							<?php if( shopp('purchase', 'hasitems') ) : ?>
								<?php while( shopp('purchase', 'items') ) : ?>
									<Item>
										<SKU><![CDATA[<?php shopp('purchase.item-sku'); ?>]]></SKU>
										<Name><![CDATA[<?php shopp('purchase.item-name'); ?>]]></Name>
										<ImageUrl><![CDATA[]]></ImageUrl>
										<!--<Weight></Weight>-->
										<!--<WeightUnits></WeightUnits>-->
										<Quantity><?php shopp('purchase.item-quantity'); ?></Quantity>
										<UnitPrice><?php echo str_replace('$', '', shopp('purchase.item-unit-price', 'return=1')); ?></UnitPrice>
										<Location><![CDATA[]]></Location>

										<?php $option = shopp('purchase.item-options', 'return=1'); ?>
										<?php if( ! empty($option) && $option != '' ) : ?>
											<Options>
												<Option>
													<Name><![CDATA[Type, Color, Size]]></Name>
													<Value><![CDATA[<?php echo $option; ?>]]></Value>
													<!--<Weight></Weight>-->
												</Option>
											</Options>
										<?php endif; ?>

									</Item>
								<?php endwhile; ?>
							<?php endif; ?>
						</Items>
					</Order>

				<?php
			}

			echo '</Orders>';
		}

		/**
		 *	Update order information
		 *	and notify customer
		 *
		 */
		public function shipnotify( $order, $carrier, $service, $tracking ) 
		{
			//global $wpdb;

			$order = filter_var( $order, FILTER_SANITIZE_STRING );
			$carrier = filter_var( $carrier, FILTER_SANITIZE_STRING );
			$service = filter_var( $service, FILTER_SANITIZE_STRING );
			$tracking = filter_var( $tracking, FILTER_SANITIZE_STRING );

			//process order event
			$updated = shopp_add_order_event($order, 'shipped', array( 'tracking' => $tracking, 'carrier' => $carrier ));

			if( ! $updated ) {
				status_header(500);
			}else {
				//order updated
				status_header(200);
			}
			
		}

		public static function dd($data) 
		{
			echo '<pre>';
				print_r($data);
			echo '</pre>';
			exit;
		}

	}

	//init
	new ShoppShipStation();

}
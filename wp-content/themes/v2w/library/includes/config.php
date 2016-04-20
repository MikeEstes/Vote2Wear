<?php

error_reporting(E_ALL);

//Timezone for Application
date_default_timezone_set('America/New_York');

//SETTINGS
define( 'SHIRT_PREVIEW', 'preview' );							//Shirt preview image size (shopp setting)
define( 'EXAMPLE_DESIGN_NAME', 'Nightly Escapes' );				//Example name for a design
define( 'EXAMPLE_DESIGN_TAGS', 'FREE THROW, BLUE, SUNSHINE' );  //Example tags for a design

define( 'STRIPE_API_KEY', 'sk_live_DYHHzGSbaBCAh8hBSZgamDaa' );

//Social Networks
define( 'SOCIAL_URL_FACEBOOK' , 'https://www.facebook.com/Vote2Wear-1174122972604270/');
define( 'SOCIAL_URL_TWITTER', 'https://twitter.com/Vote2Wear' );

//bitly access token
define( 'BITLY_TOKEN', '4eb2a1249ff4b30249b048d604915cd6a398825a' );

//dropbox API
define( 'DROPBOX_KEY', 'gczcgs615d9zka8' );
define( 'DROPBOX_SECRET', '68mujpz993mmulq' );
define( 'DROPBOX_TOKEN', 'LA2_aCTMJiAAAAAAAAAABgQx2-R8gVWR6vCzVudyUsAT__sH9GxsivVjxocq7gHi' );

//minimum amount needed for funds transfer
define( 'BALANCE_TRANSFER_MINIMUM', 20.00 );

define( 'AMOUNT_EARNED_PER_SHIRT', 2.00 );

/**
 *	Get available shirt colors
 *	array. Used to build out colors
 *	on submit design page as well as
 *	build a product.
 *
 *	@todo Improve this data source
 */
function get_available_shirt_colors()
{
	return array(
		array(
			'primary' => 'Blues',
			'secondary' => array(
				'Royal' => '#20419a',
				'Tahiti Blue' => '#70d5f2',
				'Turquoise' => '#28a3e3',
				'Midnight Navy' => '#092263'
			)
		),
		array(
			'primary' => 'Reds',
			'secondary' => array(
				'Red' => '#c3373e',
				'Light Pink' => '#f6d9df',
				'Maroon' => '#5d2735'
			)
		),
		array(
			'primary' => 'Greens',
			'secondary' => array(
				'Forest Green' => '#273b32',
				'Kelly Green' => '#239952',
				'Light Olive' => '#898d6d',
			)
		),
		array(
			'primary' => 'Black & Whites',
			'secondary' => array(
				'White' => '#ffffff',
				'Black' => '#000000',
				'Warm Gray' => '#929b96',
				'Heather Gray' => '#b1b3b6',
				'Heavy Metal' => '#474c53',
				'Indigo' => '#415163'
			)
		),
		array(
			'primary' => 'Browns',
			'secondary' => array(
				'Dark Chocolate' => '#3c2516'
			)
		),
		/*array(
			'primary' => 'Orange',
			'secondary' => array(
			)
		),*/
		array(
			'primary' => 'Yellow & Golds',
			'secondary' => array(
				'Gold' => '#ecb82e',
				'Banana Cream' => '#f9e9a3',
			)
		),
		array(
			'primary' => 'Purples',
			'secondary' => array(
				'Purple Rush' => '#3f3370'
			)
		)
	);
}

/**
 *	Search color list and
 *	return color name by code
 *
 *	@param (string) Color code (i.e. #FFFFFF)
 *	@return (string) Color name
 */
function find_color_name_by_code( $code ) 
{
	$available = get_available_shirt_colors();
	$code = strtolower($code);

	foreach( $available as $primary ) {
		foreach( $primary['secondary'] as $name => $hex ) {
			if( $code === $hex )
				return $name;
		}
	}
}

/**
 *	Search color list and 
 *	return color code by name
 *
 *	@param (string) Color name
 *	@return (string) Color code
 */
function find_color_code_by_name( $color ) 
{
	$available = get_available_shirt_colors();

	foreach( $available as $primary ) {
		foreach( $primary['secondary'] as $name => $hex ) {
			if( $name == $color ) {
				return str_replace('#', '', $hex);
			}
		}
	}

	return false;
}

/**
 *	Get colors as JSON data
 *
 *	@return (json)
 */
function get_all_colors_as_json_data() 
{
	$available = get_available_shirt_colors();
	$colors = array();

	foreach( $available as $primary ) {
		foreach( $primary['secondary'] as $name => $code ) {
			$code = str_replace('#', '', $code);
			$colors[ $code ] = $name;
		}
	}

	return V2W::json( $colors, false );
}


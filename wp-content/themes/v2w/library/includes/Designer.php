<?php

//Add role needed for Designers
add_action('init', array('Designer', '_add_role'));
add_role('designer', 'Designer', array('read'));

//Designer class
class Designer extends WP_User {

	/**
	 *	Get Designer Name
	 *
	 *	@return (string) Public profile name
	 */
	public function get_name() {
		return $this->user_nicename;
	}

	/**
	 *	Get profile link
	 *
	 *	@return
	 */
	public function profile_url() {
		return get_author_posts_url( $this->ID );
	}

	/**
	 *	Get Designers location
	 *
	 *	@return (string) location i.e. Orlando, FL
	 */
	public function get_location() {
		return get_field( 'location', 'user_'.$this->ID );
	}

	/**
	 *	Get Twitter URL
	 *
	 *	@return (string) url
	 */
	public function get_twitter() {
		$handle = get_field( 'twitter_handle', 'user_'.$this->ID );

		if( empty($handle) )
			return '';

		return "https://twitter.com/{$handle}";
	}

	/**
	 *	Get Facebook URL
	 *
	 *	@return (string) url
	 */
	public function get_facebook() {
		return get_field( 'facebook_url', 'user_'.$this->ID );
	}

	/**
	 *	Get Instagram URL
	 *
	 *	@return (string) url
	 */
	public function get_instagram() {
		return get_field( 'instagram_url', 'user_'.$this->ID );
	}

	/**
	 *	Get Profile Picture
	 *
	 *	@param (int) size in pixels of avatar (square)
	 *	@return (html) img element of avatar
	 */
	public function get_avatar( $size = 74 ) 
	{
		return V2W::get_user_profile_pic( $size, $this->id );
		//return get_avatar( $this->id, $size );
	}

	/**
	 *	Get Profile Picture URL
	 *
	 *	@return (string) image url
	 */
	public function get_avatar_url() {
		return V2W::get_user_profile_pic_url( 96, $this->id );	//size doesn't really matter here
	}

	/**
	 *	Get Email Address
	 *
	 *	@return (string) Email address
	 */
	public function get_email() 
	{
		return $this->user_email;
	}

	/**
	 *	Get designer tagline
	 *
	 *	@return (string) tagline
	 */
	public function get_tagline() {
		return get_field( 'tagline', 'user_'.$this->ID );
	}

	/**
	 *	Update Stripe Recipient Key
	 *
	 *	@param (object) Stripe RP
	 */
	public function update_stripe_key( $rp ) 
	{
		$bank = array(
			'bank' => $rp->active_account->bank_name,
			'last4' => $rp->active_account->last4
		);

		update_field('field_55b7f6b1d4b8f', $rp->id, 'user_'.$this->ID);
		update_user_meta( $this->ID, '_stripe_bank', $bank );
	}

	/**
	 *	Get Stripe RP Key
	 *
	 *	@return (string) stripe rp key
	 */
	public function get_stripe_key() 
	{
		return get_field('stripe_rp', 'user_'.$this->ID);
	}

	/**
	 *	Get Stripe Bank Information
	 */
	public function get_stripe_bank() 
	{
		return get_user_meta( $this->ID, '_stripe_bank', true );
	}

	/**
	 *	Add role needed for managing Designers
	 */
	static function _add_role() {
		add_role('designer', 'Designer', array('read'));
	}

	/**
	 *	Submit new Design
	 *
	 *	@param $data (array) design data
	 */
	static function submit_design( $data ) {

		//@ToDo: validate data

	}

	/**
	 *	Get Designs
	 *
	 *	@return (array) Design objects
	 */
	public function get_designs() 
	{
		$q = get_posts(array(
			'post_type' => Design::$post_type,
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => 'designer',
					'value' => $this->ID
				)
			)
		));

		if( empty($q) )
			return array();

		$designs = array();

		foreach( $q as $design )
			$designs[] = new Design( $design );

		return $designs;
	}

	/**
	 *	Get Battles
	 *	Return all battles in order of
	 *	of history for this designer
	 */
	public function get_battles() 
	{
		$designs = $this->get_designs();
		$ids = array();

		foreach( $designs as $design )
			$ids[] = $design->get_post_id();

		//build meta query
		$meta_query = array(
			'relation' => 'OR'
		);

		foreach( $ids as $design ) {
			$meta_query[] = array(
				'key' => 'design_a',
				'value' => $design,
				'compare' => 'LIKE'
			);
			$meta_query[] = array(
				'key' => 'design_b',
				'value' => $design,
				'compare' => 'LIKE'
			);
		}

		$q = get_posts(array(
			'post_type' => Battle::$post_type,
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'meta_query' => $meta_query
		));

		if( empty($q) )
			return array();

		$battles = array();

		foreach( $q as $battle )
			$battles[] = new Battle( $battle );
		
		return $battles;
	}

	/**
	 *	Create a new Designer
	 *	This is simply taking a WP User
	 *	and changing their role to a Designer
	 *
	 *	@access static
	 *	@param $user (WP_User) wp user object to change
	 *	@return (mixed) WP_User | WP_Error
	 */
	static function create( WP_User $user ) {

		$is_admin = false;

		//if user is an administrator, deny
		foreach( $user->roles as $role ) {
			if( $role == 'administrator' ) {
				$is_admin = true;
				break;
			}
		}

		if( $is_admin )
			return new WP_Error('invalid', 'Administrators cannot be Designers');

		//change to designer
		$update = wp_update_user(array(
			'ID' => $user->ID,
			'role' => 'designer'
		));

		if( is_wp_error($update) )
			return $update;

		//return new user object
		$user = get_user_by('id', $update);
		return $user;

	}

	/**
	 *	Find by Stripe rp
	 */
	public static function find_by_rp( $rp ) 
	{
		global $wpdb;

		$designer_id = $wpdb->get_var("
			SELECT * FROM v2w_usermeta 
			WHERE meta_key = 'stripe_rp' AND meta_value = '{$rp}'
			LIMIT 1
		");

		if( is_null($designer_id) )
			return false;

		return $designer_id;
	}

}
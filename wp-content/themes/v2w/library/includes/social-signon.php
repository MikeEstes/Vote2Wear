<?php

require_once( TEMPLATEPATH.'/library/includes/hybridauth/Hybrid/Auth.php' );
require_once( TEMPLATEPATH.'/library/includes/hybridauth/Hybrid/Endpoint.php' );

//WP stuff
add_action('init', array('V2WSignon', 'init'));
add_action('parse_request', array('V2WSignon', 'parse_request'), 0);
add_filter('query_vars', array('V2WSignon', 'query_vars'), 0);

class V2WSignon {

	public static $baseurl = 'http://vote2wear.com/';
	public static $endpoint = 'socialsignon';

	/**
	 *	Facebook Connect
	 */
	public static function facebook() 
	{
		try {
			//attempt to connect
			$hybridauth = new Hybrid_Auth( self::config() );
			$facebook = $hybridauth->authenticate("Facebook");
			$user_profile = $facebook->getUserProfile();

		}catch( Exception $e ) {
			$facebook->logout();
			wp_redirect( get_option('siteurl').'/login?fb=error' );
			exit;
		}

		//successful

		//if no email, we can't do anything
		if( ! $user_profile->email ) {
			$facebook->logout();
			wp_redirect( get_option('siteurl').'/login?fb=error' );
			exit;
		}

		//check to see if the user exists
		$user_id = email_exists( $user_profile->email );

		if( $user_id ) {
			//has user connected with this server before?
			$identifier = get_user_meta($user_id, '_fb_identifier', true);

			if( empty($identifier) || $identifier !== $user_profile->identifier ) {

				//user has never connected with this service before
				//add the identifier
				update_user_meta( $user_id, '_fb_identifier', $user_profile->identifier );
				update_user_meta( $user_id, '_fb_profile_pic', $user_profile->photoURL );

			}

		}else {
			//new user

			$creds = array(
				'username' => $user_profile->displayName,
				'password' => $user_profile->identifier,
				'email' => $user_profile->email,
				'firstname' => $user_profile->firstName,
				'lastname' => $user_profile->lastName
			);

			$user_id = V2W::register( $creds );

			if( is_wp_error($user_id) ) {
				wp_redirect( get_option('siteurl').'/login?error='.$user_id->get_error_message );
				exit;
			}

			update_user_meta( $user_id, '_fb_identifier', $user_profile->identifier );
			update_user_meta( $user_id, '_fb_profile_pic', $user_profile->photoURL );

		}

		//login the user in and redirect
		wp_set_auth_cookie( $user_id );
		wp_redirect( get_option('siteurl') );
		exit;
	}

	/**
	 *	Twitter Connect
	 */
	public static function twitter() 
	{
		try {

			$hybridauth = new Hybrid_Auth( self::config() );
			$twitter = $hybridauth->authenticate("Twitter");
			$user_profile = $twitter->getUserProfile();

		}catch( Exception $e ) {
			wp_redirect( get_option('siteurl').'/login?tw=error' );
			$twitter->logout();
			exit;
		}

		//successful
		if( isset($_GET['email']) )
			$user_profile->email = filter_var($_GET['email'], FILTER_VALIDATE_EMAIL);


		if( ! $user_profile->email ) {
			$user_id = self::find_tw_user( $user_profile->identifier );
			if( ! $user_id ) {
				//request the email from the user
				wp_redirect( get_option('siteurl').'/login?tw=email' );
				exit;
			}

		}else {
			//check to see if the user exists
			$user_id = email_exists( $user_profile->email );

			if( ! $user_id ) {
				//user does not exist; register them
				$creds = array(
					'username' => $user_profile->displayName,
					'password' => $user_profile->identifier,
					'email' => $user_profile->email,
					'firstname' => $user_profile->firstName,
					'lastname' => $user_profile->lastName
				);

				$user_id = V2W::register( $creds );

				if( is_wp_error($user_id) ) {
					wp_redirect( get_option('siteurl').'/login?error='.$user_id->get_error_message );
					exit;
				}
			}
		}

		update_user_meta( $user_id, '_tw_identifier', $user_profile->identifier );
		update_user_meta( $user_id, '_tw_profile_pic', $user_profile->photoURL );

		//login the user in and redirect
		wp_set_auth_cookie( $user_id );
		wp_redirect( get_option('siteurl') );
		exit;

	}

	//google
	public static function google() 
	{
		try {
			//attempt to connect
			$hybridauth = new Hybrid_Auth( self::config() );
			$google = $hybridauth->authenticate("Google");
			$user_profile = $google->getUserProfile();

		}catch( Exception $e ) {
			$google->logout();
			wp_redirect( get_option('siteurl').'/login?fb=error' );
			exit;
		}

		//successful

		//if no email, we can't do anything
		if( ! $user_profile->email ) {
			$google->logout();
			wp_redirect( get_option('siteurl').'/login?fb=error' );
			exit;
		}

		//check to see if the user exists
		$user_id = email_exists( $user_profile->email );

		if( $user_id ) {
			//has user connected with this server before?
			$identifier = get_user_meta($user_id, '_gg_identifier', true);

			if( empty($identifier) || $identifier !== $user_profile->identifier ) {

				//user has never connected with this service before
				//add the identifier
				update_user_meta( $user_id, '_gg_identifier', $user_profile->identifier );
				update_user_meta( $user_id, '_gg_profile_pic', $user_profile->photoURL );

			}

		}else {
			//new user

			$creds = array(
					'username' => $user_profile->displayName,
					'password' => $user_profile->identifier,
					'email' => $user_profile->email,
					'firstname' => $user_profile->firstName,
					'lastname' => $user_profile->lastName
				);

			$user_id = V2W::register( $creds );

			if( is_wp_error($user_id) ) {
				wp_redirect( get_option('siteurl').'/login?error='.$user_id->get_error_message );
				exit;
			}

			update_user_meta( $user_id, '_gg_identifier', $user_profile->identifier );
			update_user_meta( $user_id, '_gg_profile_pic', $user_profile->photoURL );

		}

		//login the user in and redirect
		wp_set_auth_cookie( $user_id );
		wp_redirect( get_option('siteurl') );
		exit;
	}

	/**
	 *	Look for Twitter user
	 *	by identifier
	 *
	 *	@param (string) idenifier
	 *	@return (mixed) User ID if found, false if not found
	 */
	public static function find_tw_user( $identifier ) 
	{
		global $wpdb;
		$user = $wpdb->get_var("SELECT user_id FROM v2w_usermeta WHERE meta_key = '_tw_identifier' AND meta_value = '{$identifier}'");

		if( is_null($user) )
			return false;

		return $user;
	}

	//get config
	public static function config() 
	{
		return array(
			"base_url" => self::$baseurl . self::$endpoint,
			//"base_url" => "http://v2w.site:8888/wp-content/themes/v2w/library/includes/hybridauth/",
			"providers" => array(
				"Facebook" => array(
					"enabled" => true,
					"keys" => array("id" => "428730977317745", "secret" => "e587846fd749b61465ac2f568f77b9c2"),
					"trustForwarded" => false,
					"scope" => "email"
				),
				"Twitter" => array(
					"enabled" => true,
					"keys" => array("key" => "KlMhxA0aG51Zrf7zbzN3lyy09", "secret" => "kMCnO2PA4ej3CrzSubkEfMPIv92X2WQxzFtOOvtKnt5wSC60St"),
					"includeEmail" => true
				),
				"Google" => array ( // 'id' is your google client id
	               "enabled" => true,
	               "keys" => array ( "id" => "1053990332916-prk1enf35d9ntn34o0ut35def037rq1j.apps.googleusercontent.com", "secret" => "RYDuPGcdf6w5qi3kV4PdTfJt" ),
	               "scope" => "https://www.googleapis.com/auth/userinfo.profile ".
                              "https://www.googleapis.com/auth/userinfo.email"
	            ),
			),
			// If you want to enable logging, set 'debug_mode' to true.
			// You can also set it to
			// - "error" To log only error messages. Useful in production
			// - "info" To log info and error messages (ignore debug messages)
			"debug_mode" => false,
			// Path to file writable by the web server. Required if 'debug_mode' is not false
			"debug_file" => TEMPLATEPATH . '/library/includes/log.txt',
		);
	}

	//create endpoint for auth
	public static function init() 
	{
		add_rewrite_endpoint( self::$endpoint, EP_PERMALINK | EP_NONE );
	}

	//parse request for auth
	public static function parse_request( $query ) 
	{
		//V2W::dd($query);
		$query_vars = $query->query_vars;

		if( ! isset($query_vars['name']) || $query_vars['name'] != 'socialsignon' )
			return;

		$provider = $query_vars['provider'] ?: false;

		switch ( $provider ) {
			case 'fb':
				self::facebook();
				break;

			case 'tw':
				self::twitter();
				break;

			case 'gg':
				self::google();
				break;
			
			default:
				Hybrid_Endpoint::process();
				break;
		}
	}

	//add needed query vars
	public static function query_vars( $vars ) 
	{
		$vars[] = 'provider';
		$vars[] = 'hauth.start';
		$vars[] = 'hauth.time';
		$vars[] = 'hauth.done';
		return $vars;
	}

}
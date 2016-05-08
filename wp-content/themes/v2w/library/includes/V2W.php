<?php

//Update user profile extras and save
add_action('shopp_customer_update', array('V2W', 'update_user_profile'));

//notify designers of battle creation and design denial
add_action('battle_created', array('V2W', 'battle_created_notify'), 10, 1);
add_action('design_denied', array('V2W', 'design_denied_notify'), 10, 2);

add_action('wp_ajax_upload_profile_pic', array('V2W', 'ajax_upload_profile_pic'));	

/**
 *	General class for managing
 *	V2W logic
 */
class V2W {

	/**
	 *	Get Daily Battles
	 *	Returns an array of Battle Objects
	 *	based on a specific date.
	 *
	 *	@param $date (DateTime) defaults to current date
	 *	@return (array) Array of Battle Objects
	 */
	public static function get_daily_battles( DateTime $date = NULL ) 
	{

		//ensure date is correct
		if( is_null($date) )
			$date = new DateTime('today');

		//get all battles
		$battles = get_posts(array(
			'post_type' => 'battle',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'DESC',
			'date_query' => array(
				'year' => $date->format('Y'),
				'month' => $date->format('m'),
				'day' => $date->format('d')
			)
		));

		if( count($battles) == 0 )
			return array();

		//convert to Battle objects
		$_battles = array();

		foreach( $battles as $battle )
			array_push( $_battles, new Battle($battle) );

		//free up resources
		unset($battles);

		return $_battles;

	}

	/**
	 *	Generate Daily Battles
	 *	Creates daily battles for the
	 *	following day based on approved
	 *	designs submitted today
	 *
	 *	@todo currently pulls all published designs for the day, even if they are already in a battle. Perhaps prevent this?
	 *
	 *	@return (array) Battle objects
	 */
	public static function create_daily_battles() 
	{
		$date = new DateTime('yesterday');

		$designs = get_posts(array(
			'post_type' => 'design',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'order' => 'ASC',
			'date_query' => array(
				'year' => $date->format('Y'),
				'month' => $date->format('m'),
				'day' => $date->format('d')
			)
		));

		//@ToDo: If there is no designs, notify admin
		if( empty($designs) || count($designs) < 2 ) {
			mail('sregge@gmail.com', 'Battles Not Created', 'There were not enough designs to create Daily Battles.');
			return;
		}

		//create battles
		$_battles = array();

		while( ! empty($designs) ) {

			//if not at leaset 2 designs, ignore
			if( count($designs) <= 1 )
				break;

			$a = new Design( array_shift($designs) );
			$b = new Design( array_shift($designs) );

			//create battle
			$battle = Battle::create( $a, $b, new DateTime('today') );

			$_battles[] = $battle;
		}

		//if there was a left over design, we need to move its date to tomorrow (which is actually today since we are running the CRON same day (post midnight))
		if( ! empty($designs) ) {
			$date = new DateTime('today');
			foreach( $designs as $design ) {
				wp_update_post(array(
					'ID' => $design->ID,
					'post_date' => $date->format('Y:m:d H:i:a'),
					'post_date_gmt' => $date->format('Y:m:d H:i:a')
				));
			}
		}

		return $_battles;

	}

	/**
	 *	Get more Battles
	 *	Used on the Single Battle
	 *	pages
	 *
	 *	@param (int) limit
	 *	@param (array) battles to exclude
	 *	@return (array) Battle Objects
	 */
	public static function more_battles( $limit = 3, $exclude = array() ) 
	{
		if( !is_numeric($limit) )
			$limit = 3;

		$date = new DateTime('today');

		$battles = get_posts(array(
			'post_type' => 'battle',
			'post_status' => 'publish',
			'posts_per_page' => $limit,
			'orderby' => 'rand',
			'date_query' => array(
				'year' => $date->format('Y'),
				'month' => $date->format('m'),
				'day' => $date->format('d')
			),
			'post__not_in' => $exclude
		));

		$_battles = array();

		foreach( $battles as $battle )
			$_battles[] = new Battle($battle);

		unset($battles);
		return $_battles;
	}

	/**
	 *	Prettify time remaining on
	 *	battle
	 *
	 *	@param (string) time remaining in seconds
	 *	@return (string) time remaining prettified
	 */
	public static function prettify_time( $time = 0 ) 
	{
		$hours = gmdate('g', $time);
		$mins = gmdate('i', $time);
		$secs = gmdate('s', $time);

		$output = array($hours);
		$output[] = ($hours == 1) ? 'hr' : 'hrs';
		$output[] = $mins;
		$output[] = ($mins == 1) ? 'min' : 'mins';
		$output[] = $secs;
		$output[] = ($secs == 1) ? 'sec' : 'secs';

		return implode(' ', $output);
	}

	/**
	 *	When a design is approved
	 *	generate shirt previews
	 *
	 *	@param (WP_Post) post object
	 */
	public static function generate_shirts_on_approval( $post ) 
	{
		if( $post->post_type != Design::$post_type )
			return;

		$design = new Design($post);
		$m_shirts = $design->generate_shirts('mens');
		$w_shirts = $design->generate_shirts('womens');

		//update post thumbnail with first design
		if( $design->get_style() != 'mens' ) {
		    set_post_thumbnail( $post->ID, $w_shirts[0] );
		} else {
		    set_post_thumbnail( $post->ID, $m_shirts[0] );
		}

		//save reference
		update_post_meta( $post->ID, '_mens_shirts', $m_shirts );
		update_post_meta( $post->ID, '_womens_shirts', $w_shirts );
	}

	/**
	 *	Build Shopp product from a
	 *	Design
	 *
	 *	@param (Design) design to convert
	 *	@return (Shopp_Product) shopp product
	 */
	public static function convert_to_product( Design $design ) 
	{
		// @todo check to see if this design is already a product in case it has been in more than one battle

		//get colors
		$colors = $design->get_colors();
		$color_opts = array();

		foreach( $colors as $color )
			$color_opts[] = find_color_name_by_code( $color );

		//other variants
		$_types = array(
			'3600' => "Men's",
			'3900' => "Women's"
		);

		$_sizes_menu = array('Small', 'Medium', 'Large', 'XL', '2XL', '3XL', '4XL');

		//build product data
		$data = array(
			'name' => $design->get_name(),
			'description' => $design->get_description(),
			'publish' => array(
				'flag' => true
			),
			'tags' => array(
				'terms' => array (''),
			),
			'variants' => array(
				'menu' => array(
					'Type' => $_types,
					'Color' => $color_opts,
					'Size' => $_sizes_menu 
				)
			),
		);
		
		$designTags = $design->get_tags();
		$productTags = array();
		foreach ( $designTags as $designTag ) {
			array_push($productTags , $designTag->name);
		}
		
		$data['tags'] = array( 
		    'terms' => $productTags
		);

		//build product variants and get original artwork
		$artwork = get_post_meta( $design->get_post_id(), '_original_artwork', true );
		$pathinfo = pathinfo($artwork);

		$name = $pathinfo['basename'];
		

		foreach( $_types as $code => $type ) {
			foreach( $color_opts as $color ) {
				foreach( $_sizes_menu as $_size_menu ) {

					//build SKU
					$final_size = $_size_menu;
					
					switch( $final_size ) {
						case 'Small':
							$final_size = 'S';
							break;
						case 'Medium':
							$final_size = 'M';
							break;
						case 'Large':
							$final_size = 'L';
							break;
						default:
							break;
					}
					
					
					$sku = "{$design->get_post_id()}-{$code}-{$color}-{$final_size}";

					//build price
					$price = (in_array($_size_menu , array('2XL', '3XL', '4XL'))) ? 22.00 : 20.00;
					
					if( $code == '3900' && in_array($_size_menu , array('3XL', '4XL')) )
					{	
						//build variants
						$data['variants'][] = array(
							'option' => array('Type' => $type, 'Color' => $color, 'Size' => $_size_menu),
							'type' => 'N/A',
							'price' => $price,
							'taxed' => true,
							'inventory' => array(
								'sku' => $sku,
								'flag' => true,
								'stock' => 9999999
							)
						);
					}
					else
					{	
						//build variants
						$data['variants'][] = array(
							'option' => array('Type' => $type, 'Color' => $color, 'Size' => $_size_menu),
							'type' => 'Shipped',
							'price' => $price,
							'taxed' => true,
							'inventory' => array(
								'sku' => $sku,
								'flag' => true,
								'stock' => 9999999
							)
						);
					}
				}
			}
		}
		
		//attempt to add the product
		$Product = shopp_add_product( $data );


		if( ! $Product ) {
			// @todo Handle product creation failure
			echo "Unable to add product<br />";
		}

		//Set Designer for this product
		$designer = $design->get_designer();
		update_field( 'field_55da179a5aa64', $designer->ID, $Product->id );
		update_field( 'field_5624297a079ef', $design->get_post_id(), $Product->id );

		//Add product images
		$images = array_merge( get_post_meta( $design->get_post_id(), '_mens_shirts', true ), get_post_meta( $design->get_post_id(), '_womens_shirts', true ) );

		foreach( $images as $image ) {
			$meta = wp_get_attachment_metadata( $image );
			shopp_add_product_image( $Product->id, WP_CONTENT_DIR . '/uploads/' . $meta['file'] );
		}

		//upload product image to Dropbox
		$db = Design::send_to_dropbox( $artwork, $design->get_post_id() );

		return $Product;
	}

	/**
	 *	Place Vote via AJAX
	 *	
	 */
	public static function vote_via_ajax() 
	{
		$battle_id = filter_var($_POST['battle'], FILTER_SANITIZE_NUMBER_INT);
		$design = filter_var($_POST['design'], FILTER_SANITIZE_STRING);

		$battle = new Battle($battle_id);
		$user = wp_get_current_user();

		//place vote
		try {

			$vote = $battle->vote_for( $design, $user );

			if( is_wp_error($vote) ) {
				//error; vote not placed
				self::json(array(
					'status' => 'error',
					'code' => $vote->get_error_code(),
					'error' => $vote->get_error_message()
				));
			}else {
				//vote successfully placed
				self::json(array(
					'status' => 'success',
					'code' => 200
				));
			}

		} catch(Exception $e) {
			self::json(array(
				'status' => 'error',
				'code' => 'error',
				'error' => $e->get_message()
			));
		}
	}

	/**
	 *	Get user profile picture
	 *	Attempts to get profile pic
	 *	from social media outlet. If we can't
	 *	find one, default to the WP avatar
	 *
	 *	@param (int) size of the image
	 *	@param (int) user id to get image for; default to current user
	 *	@return (html|bool) img element; false if no user 
	 */
	public static function get_user_profile_pic( $size = 96, $user_id = false ) 
	{
		if( !is_user_logged_in() && ! $user_id )
			return false;

		if( ! $user_id ) {
			$user = wp_get_current_user();
			$user_id = $user->ID;
		}

		//check for uploaded version (always use over social media)
		$pic = get_user_meta( $user_id, '_uploaded_profile_pic', true );
		if( ! empty($pic) )
			return "<img src='{$pic}' width='{$size}' />";

		//default to FB
		$pic = get_user_meta( $user_id, '_fb_profile_pic', true );
		if( ! empty($pic) )
			return "<img src='{$pic}' width='{$size}' />";

		//try twitter
		$pic = get_user_meta( $user_id, '_tw_profile_pic', true );
		if( ! empty($pic) )
			return "<img src='{$pic}' width='{$size}' />";

		//get avatar as final option
		return get_avatar( $user_id, $size );
	}

	/**
	 *	Upload users profile pic via
	 *	AJAX
	 *
	 *	@output (json)
	 */
	public static function ajax_upload_profile_pic() 
	{
		$uploadedfile = $_FILES['profile_pic'];
		$upload_overrides = array( 'test_form' => false );
		$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

		if ( $movefile && !isset( $movefile['error'] ) ) {

			//get current user
			$user = wp_get_current_user();
			$user_id = $user->ID;

			//save reference to image
			update_user_meta( $user_id, '_uploaded_profile_pic', $movefile['url'] );

			self::json(array(
				'code' => 200,
				'message' => 'Profile picture successfully updated',
				'pic' => $movefile['url']
			));

		} else {
			self::json(array(
				'code' => 400,
				'message' => $movefile['error']
			));
		}
	}

	/**
	 *	Get Avatar URL only
	 *
	 *	@param (int) size
	 *	@param (int) user id
	 *	@return (string) url
	 */
	public static function get_user_profile_pic_url( $size = 96, $user_id = false ) 
	{
		$avatar = self::get_user_profile_pic( $size, $user_id );

		preg_match("/src='(.*?)'/i", $avatar, $matches);
    		return $matches[1];
	}

	/**
	 *	If user is not logged in and attempting
	 *	to Submit a Design, redirect to the login
	 *	page
	 *
	 *	called via filter template_redirect action
	 */
	public static function login_before_submit_design() 
	{
		global $wp_query;

		if( $wp_query->query['pagename'] == 'submit-design' && ! is_user_logged_in() ) {
			wp_redirect('login?redirect_to=submit-design&reason=design');
			exit;
		}
	}

	/**
	 *	Log user in via AJAX
	 *
	 *	@return dies w/ json data
	 */
	public static function login() 
	{

		$log = $_POST['log'];
		$pwd = $_POST['pwd'];
		$r = $_POST['remember'];

		//attempt to log the user in
		$user = wp_signon(array(
			'user_login' => $log,
			'user_password' => $pwd,
			'remember' => $remember
		), false);

		if( is_wp_error($user) ) {

			self::json(array(
				'status' => 'error',
				'code' => $user->get_error_code(),
				'error' => $user->get_error_message()
			));

		} else 
		{

			//user is logged in!
			self::json(array(
				'status' => 'success',
				'code' => 200,
				'user' => $user,
				'redirect' => get_option('siteurl')
			));

		}
	}

	/**
	 *	Register new V2W user & log in
	 *	By default, users are not "Designers"
	 *
	 *	@param $data (array) User data (firstname, lastname, username, email, password)
	 */
	public static function register( $data ) 
	{

		//@ToDo: validate data

		//Create user as a WP user first
		$wpuser = wp_insert_user(array(
			'user_login' => $data['username'],
			'user_pass' => $data['password'],
			'user_email' => $data['email'],
			'first_name' => $data['firstname'],
			'last_name' => $data['lastname']
		));

		//success?
		if( is_wp_error($wpuser) )
			return $wpuser;

		//create a shopp user and associate with WPUser
		$customer = shopp_add_customer(array(
			'wpuser' => $wpuser,
			'firstname' => $data['firstname'],
			'lastname' => $data['lastname'],
			'email' => $data['email']
		));
		
		//Send new User to GetResponse
		register_via_get_response($wpuser);

		//@ToDo: Handle Shopp register failure. At this point
		//		 the WP user is created. What now?

		//log the user in
		$creds = array(
			'user_login' => $data['username'],
			'user_password' => $data['password']
		);

		wp_signon( $creds, false );

		//all good. return user ID
		return $wpuser;

	}
	
	
	/**
	* Send new User to GetResponse
	*/
	public static function register_via_get_response($wpuser)
	{
		//self::dd($wpuser);
		/* POST /contacts
			"name": $wpuser['first_name'],
			"email": $wpuser['user_email'],
			"campaign": {
				"campaignId": "38971503"
			} */
	}
	
	/**
	 *	Controller to register user
	 *	via AJAX
	 *
	 *	@return dies w/ json data
	 */
	public static function register_via_ajax() 
	{

		$userdata = array(
			'firstname' => $_POST['firstname'],
			'lastname' => $_POST['lastname'],
			'username' => $_POST['username'],
			'email' => $_POST['email'],
			'password' => $_POST['password'],
			'confirm' => $_POST['confirm']
		);

		//validate password
		if( empty($userdata['password']) || ($userdata['password'] !== $userdata['confirm']) ) 
		{
			self::json(array(
				'status' => 'error',
				'code' => 'passwords_mismatch',
				'error' => 'The passwords entered do not match'
			));
		}

		//attempt to register user
		$user = self::register( $userdata );

		//success or failure?
		if( is_wp_error($user) ) 
		{
			self::json(array(
				'status' => 'error',
				'code' => $user->get_error_code(),
				'error' => $user->get_error_message()
			));
		} else 
		{
			self::json(array(
				'status' => 'success',
				'code' => 200,
				'user_id' => $user,
				'redirect' => get_option('siteurl')
			));
		}

	}

	/**
	 *	Redirect user from register/login
	 *	if user is already logged in
	 */
	public static function prevent_registration_if_logged_in() 
	{
		global $wp_query;

		//if already logged in, redirect to account
		if( in_array($wp_query->query['pagename'], array('login', 'register')) && is_user_logged_in() ) {
			wp_redirect('/shop/account/');
			exit;
		}

	}

	/**
	 *	Prevent users from accessing
	 *	wp-admin if they are not admins
	 *
	 *	@return null
	 */
	public static function prevent_wp_admin_access() 
	{
		if( is_admin() && !current_user_can('manage_options') && ! (defined('DOING_AJAX') && DOING_AJAX) ) 
		{
			wp_redirect( home_url() );
			exit;
		}
	}

	/**
	 *	Utility function to return JSON
	 *	data
	 *
	 *	@param (array|object) data to convert to JSON string
	 *	@param (bool) whether or not this is for AJAX responses; default = true
	 *	@return (json) json data (if AJAX true, headers are sent)
	 */
	public static function json( $data = array(), $is_ajax = true ) 
	{
		if( $is_ajax === true )
		{
			header('Content-Type:application/json');
			die( json_encode( $data ) );
		} else 
		{
			return json_encode( $data );
		}

	}

	/**
	 *	Is user a designer?
	 *
	 *	@param (WP_User) User
	 *	@return (bool)
	 */
	public static function is_user_designer( $user ) 
	{
		if ( in_array( 'designer', (array) $user->roles ) )
			return true;

		return false;
	}

	/**
	 *	Unique File name
	 *	Creates a unique file name for a
	 *	design if the filename already exists.
	 *
	 *	@param (string) filename
	 *	@param (string) path
	 *	@return (string) filename
	 */
	public static function unique_filename( $filename, $path ) 
	{
		//remove unfriendly characters
		$filename = preg_replace("/[^a-zA-Z0-9.-]/", "", $filename);

		if( ! file_exists($path . '/' . $filename) )
			return $filename;

		$parts = pathinfo($filename);
		$new = $parts['filename'] . '-' . time() . '.' . $parts['extension'];

		return self::unique_filename( $new, $path );
	}

	/**
	 *	Update the user profile
	 *	Shopp handles most of it,
	 *	but we need to handle the extra fields
	 */
	public static function update_user_profile() 
	{
		$facebook = filter_var($_POST['facebook_url'], FILTER_SANITIZE_STRING);
		$twitter = filter_var($_POST['twitter_handle'], FILTER_SANITIZE_STRING);
		$instagram = filter_var($_POST['instagram_url'], FILTER_SANITIZE_STRING);
		$city = filter_var($_POST['profile_city'], FILTER_SANITIZE_STRING);
		$state = filter_var($_POST['profile_state'], FILTER_SANITIZE_STRING);

		//get user
		$user = wp_get_current_user();

		//update WP_User
		$update = wp_update_user(array(
			'ID' => $user->ID,
			'user_email' => filter_var($_POST['email'], FILTER_SANITIZE_STRING),
			'first_name' => filter_var($_POST['firstname'], FILTER_SANITIZE_STRING),
			'last_name' => filter_var($_POST['lastname'], FILTER_SANITIZE_STRING),
			'display_name' => filter_var($_POST['firstname'], FILTER_SANITIZE_STRING) . ' ' . filter_var($_POST['lastname'], FILTER_SANITIZE_STRING)
		));

		//save social urls
		update_field('field_563fa6203d9d9', $facebook, 'user_'.$user->ID);
		update_field('field_562441a17a14c', $twitter, 'user_'.$user->ID);
		update_field('field_563fa63d3d9da', $instagram, 'user_'.$user->ID);

		//save location
		update_field('field_55c3821a7d503', $city . ', ' . $state, 'user_'.$user->ID);

		if( is_wp_error($update) ) {
			wp_redirect( get_option('siteurl') . '/shop/account/?menu&update=error&error='.$update->get_error_message() );
			exit;
		}

		wp_redirect( get_option('siteurl') . '/shop/account/' );
		exit;
	}

	/**
	 *	Notify Designers when a 
	 *	Battle has been created from their
	 *	designs
	 *
	 *	@param (Battle) Battle
	 */
	public static function battle_created_notify( Battle $battle ) 
	{
		$a = $battle->get_design('a');
		$da = $a->get_designer();

		$b = $battle->get_design('b');
		$db = $b->get_designer();

		$emails = array( $da->get_email(), $db->get_designer() );

		V2WMailer::send('battle.created', $emails, array(
			'%url%' => $battle->url()
		));
	}

	/**
	 *	Notify designer of a denied 
	 *	design
	 *
	 *	@param (Design) design
	 *	@param (string) reason for denial
	 */
	public static function design_denied_notify( Design $design, $reason = 'none provided' ) 
	{
		$designer = $design->get_designer();

		V2WMailer::send('design.denied', $designer, array(
			'%reason%' => $reason,
			'%url%' =>  get_option('siteurl').'/submit-design'
		));
	}

	/**
	 *	When an admin denies a design
	 *	by moving it from draft to trash
	 *
	 *	@param (WP_Post) post object
	 */
	public static function admin_denied_design( $design ) 
	{
		if( $design->post_type != Design::$post_type )
			return;

		$design = new Design( $design->ID );
		$reason = get_field('reason_for_denial', $design->ID);

		self::design_denied_notify( $design, $reason );
	}

	/**
	 *	Catch Fatal Errors
	 */
	public static function fatal_errors() 
	{
		$error = error_get_last();

		if( $error !== NULL ) {
			self::json(array(
				'code' => 500,
				'status' => 'error',
				'message' => 'An unexpected error has occurred. Please try your request again later.',
				'error' => $error
			));
		}
	}

	/**
	 *	Debugging
	 *	print_r with <pre> wrappers
	 *	then die
	 *
	 *	@param (mixed) data to debug
	 */
	public static function dd( $data ) 
	{
		echo '<pre>';
			print_r($data);
		echo '</pre>';
		exit;
	}


}
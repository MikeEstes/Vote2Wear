<?php

add_action('init', array('Design', 'register_post_type'));

//AJAX file upload
add_action('wp_ajax_upload_design', array('Design', 'upload_design'));
add_action('wp_ajax_nopriv_upload_design', array('Design', 'upload_design')); // @todo remove

//AJAX Design submit
add_action('wp_ajax_submit_design', array('Design', 'create'));

//When a design is approved/denied
add_action( 'draft_to_publish', array('Design', 'status_approved'), 10, 1 );
add_action( 'draft_to_trash', array('Design', 'status_denied'), 10, 1 );

//workaround
Design::init();

/**
 *	Design Class
 */
class Design {

	//post type
	public static $post_type = 'design';

	//tmp artwork path/url
	public static $tmp_path;
	public static $tmp_url;

	//persistent artwork path/url
	public static $path;
	public static $url;

	//shirt template path
	public static $template_path;

	//Post ID from WP
	protected $post_id;

	//Post Object
	protected $post;

	//Designer
	protected $designer;

	/**
	 *	Constructor
	 *
	 *	@param $post (int|WP_Post) ID of the post from WP
	 */
	public function __construct( $post ) 
	{
		$post = get_post( $post );

		if( is_null($post) )
			throw new Exception('Invalid post supplied. Unable to create design.');

		//set
		$this->post_id = $post->ID;
		$this->post = $post;

		//get designer
		$designer = get_field('designer', $this->post_id);
		$this->designer = new Designer($designer['ID']);

	}

	/**
	 *	Get Post ID
	 *
	 *	@return (int) WP Post ID
	 */
	public function get_post_id() 
	{
		return $this->post_id;
	}

	/**
	 *	Set the Designer who uploaded
	 *	this design, or owns the design
	 */
	public function set_designer(Designer $designer) 
	{

	}

	/**
	 *	Get Designer
	 *
	 *	@return (Designer)
	 */
	public function get_designer() 
	{
		return $this->designer;
	}

	/**
	 *	Get Design name
	 *
	 *	@return (string) name of design
	 */
	public function get_name() 
	{
		return apply_filters('the_title', $this->post->post_title);
	}

	/**
	 *	Get Design description
	 *
	 *	@return (string) Description
	 */
	public function get_description() 
	{
		return apply_filters('the_content', $this->post->post_content);
	}

	/**
	 *	Print Design description
	 *	w/ WP filters
	 */
	public function description()
	{
		echo apply_filters('the_content', $this->get_description());
	}

	/**
	 *	Get Preview Image
	 *	Returns the preview image
	 *	of the design, on a shirt bg
	 *
	 *	@param (string) size to get
	 *	@return (string) url to image
	 */
	public function get_design( $size = 'full' ) 
	{
		return get_the_post_thumbnail( $this->post_id, $size );
	}

	/**
	 *	Get Design url
	 *	Returns the url of the design, as
	 *	opposed to get_design(), which returns
	 *	an HTML <img> tag
	 *
	 *	@param (string) size to get
	 *	@return (string) url to image
	 */
	public function get_design_url( $size = 'full' ) 
	{
		$src = wp_get_attachment_image_src( get_post_thumbnail_id($this->post_id), $size );
		return @$src[0];
	}

	/**
	 *	Get Tags
	 *
	 *	@return (array)
	 */
	public function get_tags() 
	{
		return wp_get_post_tags( $this->post_id );
	}
	
	/**
	 *	Get Battles this design has
	 *	been in. This may include tournament battles
	 *	as well
	 */
	public function get_battles() 
	{

	}

	/**
	 *	Get Colors for this Design
	 *
	 *	@return (array) colors
	 */
	public function get_colors() 
	{
		return get_post_meta( $this->post_id, '_colors', true );
	}

        /**
	 *	Get Style for this Design
	 *
	 *	@return (string) style
	 */
	public function get_style() 
	{
		return get_post_meta( $this->post_id, '_prefix', true );
	}

	/**
	 *	Has the Designer been
	 *	notified of approval or denial?
	 *
	 *	@return (bool)
	 */
	public function designer_notified() 
	{
		return (bool) get_post_meta( $this->post_id, '_user_notified', true );
	}

	/**
	 *	Generate Shirt Design
	 *	Takes an uploaded design and generates
	 *	a tshirt with this design
	 *
	 *	@param (string) type of shirt (mens/womens)
	 *	@return (array) WP attachment IDs for each generated image
	 */
	public function generate_shirts($type) 
	{
              
		//ensure valid type
		if( ! in_array($type, array('mens', 'womens')) )
			throw new Exception('Invalid shirt type supplied to generator.');

		$colors = $this->get_colors();
		$info = get_post_meta( $this->post_id, '_artwork_info', true );
		$placement = get_post_meta( $this->post_id, '_placement', true );
		$file = get_post_meta( $this->post_id, '_original_artwork', true );
		$pathinfo = pathinfo($info['filename']);

		//figure template path
		$path = self::$template_path . $type . '/';
		$path .= ($type == 'mens') ? '3600-' : '3900-';

		$attachement_ids = array();

		foreach( $colors as $color ) {
			$code = str_replace('#', '', $color);
			
			$name = V2W::unique_filename( $pathinfo['filename'] . "-{$code}.png", self::$path );
			$filename = self::$path . $name;

			$template = imagecreatefrompng( $path . "{$code}.png" );
			imagealphablending( $template, true );
			imagesavealpha( $template, true );

			$artwork = imagecreatefrompng( $file );

			//scale
    		//$scaled = imagecreatetruecolor( $placement['width'], $placement['height'] );
    		$scaled = imagecreatetruecolor( 200, 200 );
    		imagealphablending( $scaled, false );
			imagesavealpha( $scaled, true );
    		//imagecopyresampled( $scaled, $artwork, 0, 0, 0, 0, $placement['width'], $placement['height'], $info['width'], $info['height'] );
    		imagecopyresampled( $scaled, $artwork, 0, 0, 0, 0, 200, 200, 3600, 3600 );

			//imagecopy( $template, $scaled, $placement['offset']['left'] + 145, $placement['offset']['top'] + 65, 0, 0, $placement['width'], $placement['height'] );
			imagecopy( $template, $scaled, 145, 65, 0, 0, 200, 200 );
			imagepng( $template, $filename );

			imagedestroy($template);
			imagedestroy($artwork);
			imagedestroy($scaled);

			//create an attachment for this new image
			$attachement_ids[] = self::create_attachment( $filename, $this->post_id );

		}

		return $attachement_ids;
	}

	/**
	 *	Create Design
	 *	via AJAX
	 *
	 */
	static function create() 
	{
		// Sanitize/Validate data
		$ers = array();
		$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
		$description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
		$tags = explode(',', $_POST['tags']);
		$colors = $_POST['colors'];
                $prefix = filter_var($_POST['default_prefix'], FILTER_SANITIZE_STRING);
		$artwork = filter_var($_POST['artwork']['filename'], FILTER_SANITIZE_STRING);
		$placement = array(
			'width' => filter_var($_POST['width'], FILTER_SANITIZE_STRING),
			'height' => filter_var($_POST['height'], FILTER_SANITIZE_STRING),
			'offset' => $_POST['offset']
		);

		//adjust offset to account for printable region
		$placement['offset']['top'] += 65;
		$placement['offset']['left'] += 145;

		//validate design name
		if( empty($name) )
			$ers['name'] = 'Design name required.';

		//description
		if( empty($description) )
			$ers['description'] = 'Design description required.';

		//parse tags
		//@todo are tags required?

		//at least one color?
		if( count($colors) < 1 )
			$ers['color'] = 'You must select at least one color.';

		//ensure artwork is present
		if( empty($artwork) )
			$ers['artwork'] = 'No artwork added. Please upload a design.';

		//any errors?
		if( ! empty($ers) ) {
			V2W::json(array(
				'status' => 'error',
				'code' => 400,
				'errors' => $ers,
				'message' => 'Please correct errors before submitting'
			));
		}

		//get the current user
		$user = wp_get_current_user();

		//make designer
		if( ! V2W::is_user_designer( $user ) ) {
			$designer = Designer::create( $user );

			//if error, user was an admin, no need to create as a designer
			if( is_wp_error($designer) )
				$designer = new Designer( $user );
		}

		//All seems good. Submit design
		$design = wp_insert_post(array(
			'post_type' => self::$post_type,
			'post_status' => 'draft',
			'post_title' => $name,
			'post_content' => $description,
			'tags_input' => $tags
		), true);

		//error ?
		if( is_wp_error($design) ) {
			V2W::json(array(
				'status' => 'error',
				'code' => 400,
				'message' => $design->get_error_message()
			));
		}

		//set designer
		update_field('field_55c02f0c9c27b', $user->ID, $design);

		//finialize design
		self::finalize_design( $design, self::$tmp_path . '/' . $artwork, $placement );

		//save artwork details for later use
		update_post_meta( $design, '_artwork_info', $_POST['artwork'] );
		update_post_meta( $design, '_placement', $placement );
                update_post_meta( $design, '_prefix', $prefix );
		update_post_meta( $design, '_colors', $colors );

		//All good. Notify user
		V2WMailer::send('design.submit', $designer->get_email() );

		//success
		V2W::json(array(
			'status' => 'success',
			'code' => 200,
			'message' => 'Design successfully submitted. You will receive an email with further details.'
		));

	}

	/**
	 *	Create a template for the printer. Move Design to 
	 *	perm location and Insert Design as WP Attachment. 
	 *
	 *	@param (int) design id (post)
	 *	@param (string) file path/name in artwork/tmp
	 *	@param (array) width, height and offset of design
	 *	@return (int) attachment id
	 */
	public static function finalize_design( $design, $filename, $placement ) 
	{
		//get file details
		//$filetype = wp_check_filetype( basename( $filename ), null );
		$info = pathinfo($filename);
		$size = getimagesize($filename);
		$path = self::$path;

		//ensure unique filename
		$name = V2W::unique_filename( $info['basename'], $path );

		//move the file to the persistent location
		$move = rename($filename, "{$path}{$name}");
		$filename = $path.$name;	//updated file

		//example 7200 x 7200 scaled to 76px during submission. Offset 62 x 39

		$ratio = 3600 / 200;	//fixed based on the upload template. 18

		//calulate the size of the users artwork
		//if the original artwork is smaller than the needed scale, then the users image will be stretched
		$scaled_w = $placement['width'] * $ratio;	//1368
		$scaled_h = $placement['height'] * $ratio;	//1368

		//calculate offset
		$offset_x = $placement['offset']['left'] * $ratio;  //1116
		$offset_y = $placement['offset']['top'] * $ratio;	//702

		/**
		 *	Generate template for printer (3600x3600 png)
		 *	If the users uploaded.png is wider than 3600, scale down
		 *	to 3600. If it is smaller or the same, do nothing.
		 */

		//base printer template
		$template = imagecreatefrompng( get_bloginfo('template_directory') . '/library/files/printer-template.png' );
		imagealphablending( $template, true );
		imagesavealpha( $template, true );

		//uploaded artwork
		$artwork = imagecreatefrompng( $filename );

		//scale
		//$scaled = imagecreatetruecolor( 3600, 3600 );
		imagealphablending( $template, false );
		imagesavealpha( $template, true );
		imagecopyresampled( $template, $artwork, $offset_x, $offset_y, 0, 0, $scaled_w, $scaled_h, $size[0], $size[1] );

		//imagecopy( $template, $scaled, $placement['offset']['left'], $placement['offset']['top'], 0, 0, $placement['width'], $placement['height'] );
		imagepng( $template, $filename );

		//imagedestroy($template);
		imagedestroy($artwork);
		imagedestroy($template);

		//create attachment
		$attach_id = self::create_attachment( $filename, $design );

		//set post thumbnail
		set_post_thumbnail($design, $attach_id);

		//save reference to original artwork
		update_post_meta( $design, '_original_artwork', $filename );

		//generate shortened url and save
		$url = wp_get_attachment_url( $attach_id );
		//$shortened = self::shorten_url( $url );
		//$url = $shortened ?: $url;	//just in case it failed

		update_post_meta( $design, '_original_artwork_url', $url );

		// @todo Create shirt templates here? Perhaps wait till the design is approved

		return $attach_id;
	}

	/**
	 *	Create a WP attachment
	 *	to associate with this Design
	 *
	 *	@param (string) filename
	 *	@param (int) Design Post ID
	 *	@return (int) attachment id
	 */
	public static function create_attachment( $filename, $design ) 
	{
		//get file details
		$filetype = wp_check_filetype( basename( $filename ), null );
		$info = pathinfo($filename);
		$path = self::$path;
		$name = $info['basename'];

		//build attachment details
		$attachment = array(
			'guid' => self::$url . $name,
			'post_mime_type' => $filetype['type'],
			'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
			'post_content' => '',
			'post_status' => 'inherit'
		);

		$attach_id = wp_insert_attachment( $attachment, "{$path}{$name}", $design);

		//WP dependency
		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		// Generate the metadata for the attachment, and update the database record.
		$attach_data = wp_generate_attachment_metadata( $attach_id, "{$path}{$name}" );
		wp_update_attachment_metadata( $attach_id, $attach_data );

		return $attach_id;
	}

	/**
	 *	Handle Design file upload
	 *	for design submission via AJAX
	 */
	public static function upload_design() 
	{
		$path = self::$tmp_path;
		$parts = pathinfo( $_FILES['artwork']['name'] );

		if( $parts['extension'] !== 'png' ) {
			V2W::json(array(
				'status' => 'error',
				'code' => 500,
				'message' => 'Invalid file type. Please upload a .png file.'
			));
		}

		//ensure a unique filename
		$name = V2W::unique_filename( $_FILES['artwork']['name'], $path );

		//move file to temp artwork folder
		$result = move_uploaded_file($_FILES['artwork']['tmp_name'], "{$path}/{$name}");

		if( $result ) {

			$size = getimagesize( "{$path}/{$name}" );

			//ensure file is not too large
			if( $size[0] > 3600 || $size[1] > 3600 ) {
				V2W::json(array(
					'status' => 'error',
					'code' => 500,
					'message' => 'Design is too large. Maximum width and height is 3600x3600 pixels.'
				));
			}

			//success
			V2W::json(array(
				'status' => 'success',
				'code' => 200,
				'original_name' => $_FILES['artwork']['name'],
				'filename' => $name,
				'url' => self::$tmp_url . $name,
				'width' => $size[0],
				'height' => $size[1]
			));
		}else {

			switch( $_FILES['artwork']['error'] ) {
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
					$error = 'File is too large. Unable to upload your design.';
					break;
				case UPLOAD_ERR_NO_TMP_DIR:
				case UPLOAD_ERR_CANT_WRITE:
					$error = 'Web server error. Please contact an adminstrator.';
					break;
				case UPLOAD_ERR_EXTENSION:
					$error = 'Invalid file type. We only expect .png files. Please try again using the correct format.';
					break;
				case UPLOAD_ERR_PARTIAL:
				case UPLOAD_ERR_NO_FILE:
				default:
					$error = 'An unexpected error occurred. Unable to upload your design. Please try again later.';
					break;
			}

			V2W::json(array(
				'status' => 'error',
				'code' => 500,
				'message' => $error,
				'error' => $_FILES['artwork']['error']
			));
		}
	}

	/**
	 *	Upload Design to Dropbox
	 *	Sends the file to Dropbox
	 *
	 *	@param (string) path to file
	 *	@return (string|bool) DL link to dropbox file or false on error
	 */
	public static function send_to_dropbox( $file, $design_post_id ) 
	{
		require_once TEMPLATEPATH . "/library/includes/dropbox-sdk-php-1.1.5/lib/Dropbox/autoload.php";

		$dropbox_config = array(
		    'key'    => DROPBOX_KEY,
		    'secret' => DROPBOX_SECRET
		);

		$appInfo = Dropbox\AppInfo::loadFromJson($dropbox_config);
		$dbxClient = new Dropbox\Client(DROPBOX_TOKEN, "PHP-Example/1.0");

		//get file info
		$info = pathinfo( $file );
		$filename = $design_post_id;
                $filename_with_ext = $filename.".png";

		// Uploading the file
		$f = fopen($file, "rb");
		$result = $dbxClient->uploadFile("/{$filename_with_ext}", Dropbox\WriteMode::add(), $f);
		fclose($f);
		print_r($result);

		// Get file info
		$file = $dbxClient->getMetadata("/{$filename_with_ext}");

		// sending the direct link:
		$dropboxPath = $file['path'];
		$pathError = Dropbox\Path::findError($dropboxPath);

		if ($pathError !== null)
			return false;
			//return "Invalid <dropbox-path>: $pathError\n";

		// The $link is an array!
		$link = $dbxClient->createTemporaryDirectLink($dropboxPath);
		// adding ?dl=1 to the link will force the file to be downloaded by the client.
		$dw_link = $link[0]."?dl=1";

		return $dw_link;
	}

	/**
	 *	When the design is approved, 
	 *	notify the Designer
	 *
	 *	@param (WP_Post) post object
	 */
	public static function status_approved( $post ) 
	{
		if( $post->post_type != self::$post_type )
			return;

		$design = new Design( $post );

		//ensure the user hasn't already been notified
		if( $design->designer_notified() )
			return;

		$designer = $design->get_designer();

		V2WMailer::send('design.approved', $designer);
	}

	/**
	 *	If the design is denied, notify,
	 *	notify the user
	 *
	 *	@param (WP_Post) post object
	 */
	public static function status_denied( $post ) 
	{
		if( $post->post_type != self::$post_type )
			return;

		$design = new Design( $post );

		//ensure the user hasn't already been notified
		if( $design->designer_notified() )
			return;

		$reason = get_field('reason_for_denial', $design->ID);
		$designer = $design->get_designer();

		V2WMailer::send('design.denied', $designer, array(
			'%reason%' => $reason
		));
	}

	/**
	 *	Shorten design urls via
	 *	Bit.ly service
	 *
	 *	@param (string) url to shorten
	 *	@return (mixed) false on failure, shortened url on success
	 */
	public static function shorten_url( $url = '' ) 
	{
		$params = array(
			'domain' => 'bit.ly',
			'format' => 'json',
			'access_token' => BITLY_TOKEN,
			'longUrl' => $url
		);

		$endpoint = "https://api-ssl.bitly.com/v3/shorten?" . http_build_query($params);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $endpoint);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		curl_close($ch);

		$response = json_decode( $response );

		if( $response->status_code == 200 )
			return $response->data->url;

		return false;
	}

	/**
	 *	Register Design post type
	 *	witin Wordpress
	 */
	static function register_post_type() 
	{
		register_post_type(self::$post_type, array(
			'label' => 'Designs',
			'description' => 'All submitted designs',
			'public' => false,
			'show_ui' => true,
			'supports' => array('title', 'editor', 'thumbnail', 'revisions'),
			'taxonomies' => array('post_tag')
		));

	}

	/**
	 *	Workaround to set static class
	 *	vars in previous php versions
	 */
	public static function init() 
	{
		//tmp artwork path/url
		self::$tmp_path = WP_CONTENT_DIR . '/uploads/artwork/tmp';
		self::$tmp_url = WP_CONTENT_URL . '/uploads/artwork/tmp/';

		//persistent artwork path/url
		self::$path = WP_CONTENT_DIR . '/uploads/artwork/';
		self::$url = WP_CONTENT_URL . '/uploads/artwork/';

		//shirt template path
		self::$template_path = TEMPLATEPATH . '/library/images/shirt_templates/';
	}

}
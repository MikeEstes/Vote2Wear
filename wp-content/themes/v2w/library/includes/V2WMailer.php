<?php

V2WMailer::init();

/**
 *	Handles various aspects of
 *	emailing admins and users.
 */
class V2WMailer 
{
	//path for email templates
	public static $path;

	//from address
	public static $from = 'Vote2Wear <no-reply@vote2wear.com>';

	/**
	 *	Workaround for setting static
	 *	class vars in previous PHP versions
	 */
	public static function init() 
	{
		self::$path = TEMPLATEPATH . '/library/emails/';
	}

	/**
	 *	Send mail
	 *
	 *	@param (string) Type of email to send (a pre defined template)
	 *	@param (string|array|WP_User) who to send to?
	 *	@param (array) optional data attrs needed for sending
	 *	@param (string) subject of email. predefined in many cases
	 *	@param (bool) html or plain text email?
	 */
	public static function send( $type, $to, $data = array(), $subject = false, $html = true ) 
	{
		if( ! self::is_valid_type($type) )
			throw new Exception('Invalid type supplied to mailer. Check list of valid types.');

		//get template details
		$template = self::get_type( $type );

		//prepare recipients
		$to = self::prepare_to($to);

		//subject
		$subject = $subject ?: $template['subject'];

		//build headers
		$headers = "From: " . self::$from . "\r\n";
		$headers .= "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

		//buld body from template
		$body = file_get_contents( self::$path . "{$type}.html" );

		if( ! empty($data) ) {
			foreach( $data as $needle => $replace )
				$body = str_replace($needle, $replace, $body);
		}

		//repalce image urls
		$body = str_replace('%template_url%', get_bloginfo('template_directory'), $body);

		foreach( $to as $email ) {

			//send
			wp_mail( $to, $subject, $body, $headers );
		}

	}

	/**
	 *	Prepare to addresses
	 *	Handles various input types
	 *
	 *	@param (mixed) $to
	 *	@return (array) of email addresses
	 */
	public static function prepare_to( $to ) 
	{
		//convert to array
		if( ! is_array($to) )
			$to = array($to);

		$emails = array();

		//iterate
		foreach( $to as $recipient ) {

			//string?
			if( is_string($recipient) ) {
				$email = filter_var($recipient, FILTER_VALIDATE_EMAIL);

				if( $email )
					$emails[] = $recipient;
				//else
					//throw new Exception('Invalid email address supplied to mailer.');

			} 

			//WP_User?
			if( is_a($recipient, 'WP_User') ) {
				$user = get_userdata($recipient->ID);
				$emails[] = $user->user_email;
			}

		}

		return $emails;
	}

	/**
	 *	Map types to email data such
	 *	as subject and template
	 */
	public static function _types() 
	{
		return array(

			//Submit a new design
			'design.submit' => array(
				'subject' => 'Design submission confirmation'
			),

			//Designs ready for approval
			'design.approvals' => array(
				'subject' => 'Designs are ready for approval'
			),

			//Design denied
			'design.denied' => array(
				'subject' => 'Your design was not approved'
			),

			//Design approved for battle
			'design.approved' => array(
				'subject' => 'Your design has been approved'
			),

			//Battle created
			'battle.created' => array(
				'subject' => 'Your design is ready for battle'
			),

			//Battle ended
			'battle.ended' => array(
				'subject' => 'Your designs battle has ended'
			),

			//notify designer of a win!
			'battle.win' => array(
				'subject' => 'Your design has won its battle'
			),

			//notify designer of a loss
			'battle.loss' => array(
				'subject' => 'Your design has lost its battle'
			),

			//notify designer of a tie
			'battle.tie' => array(
				'subject' => 'Your battle has ended in a tie'
			),

			//notify admin of a tie
			'admin.battle.tie' => array(
				'subject' => 'A battle ended in a tie'
			),

		);
	}

	/**
	 *	Get Type
	 *
	 *	@param (string) valid type
	 *	@return (array) type
	 */
	public static function get_type( $type ) 
	{
		if( ! self::is_valid_type($type) )
			throw new Exception('Invalid type supplied.');

		$types = self::_types();
		return $types[$type];
	}

	/**
	 *	Is type valid?
	 *
	 *	@param (string) type
	 *	@return (bool)
	 */
	public static function is_valid_type( $type ) 
	{
		$types = self::_types();
		return isset($types[$type]);
	}

	/**
	 *	Get admin emails
	 */
	public static function admins() 
	{
		return array(
			'chris@wearespry.com'
		);
	}
}
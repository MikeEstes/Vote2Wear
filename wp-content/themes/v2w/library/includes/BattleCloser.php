<?php

define('V2W_SECURITY_TOKEN', 'jf094h0werg');	//random security token for CRON jobs

add_action( 'init', array('BattleCloser', 'process_cron') );
add_action( 'wp_ajax_settle_tie', array('BattleCloser', 'settle_tie') );

/**
 *	Class to process ended
 *	Battles
 */
class BattleCloser {

	/**
	 *	Handle Ended Battles
	 *	CRON action
	 */
	public static function process_ended() 
	{
		//get all battles that just ended
		//determine outcome
		//send outcome results to designers
		//notify admin of results and any ties that need resolving
		//generate products for winners

		$date = new DateTime('yesterday');
		$battles = V2W::get_daily_battles( $date );

		foreach( $battles as $battle ) {
		
		//$message = $battle->get_design('a');
		//self::create_product( $message  );

			if( $battle->in_progress() )
				continue;	//weird, but lets be safe
				
			//only if battles hasn't been process
			if( $battle->has_outcome() )
				continue;	//battle has already been processed; prevents duplicates

			$winner = $battle->get_winner();
			$loser = $battle->get_loser();
			
			//Did the battle end in a Tie?
			if( $winner == 'Tie' ) {
				//notify admin
				V2WMailer::send( 'admin.battle.tie', V2WMailer::admins(), array(
					'%url%' => get_option('siteurl') . '/wp-admin/post.php?post=' . $battle->get_post_id() . '&action=edit'
				) );

				//notify designers
				$a = $battle->get_design('a');
				$designer_a = $a->get_designer();
				$b = $battle->get_design('b');
				$designer_b = $b->get_designer();

				$emails = array();
				$emails[] = $designer_a->get_email();
				$emails[] = $designer_b->get_email();

				//save outcome
				$battle->save_outcome(array(
					'detail' => $battle->vote_detail(),
					'tied' => true,
					'method' => 'panel'
				));

				V2WMailer::send( 'battle.tie', $emails, array(
					'%url%' => $battle->get_url()
				) );
			}else {

				//Battles have normal outcomes; notify designers
				$dwinner = $winner->get_designer();
				$dloser = $loser->get_designer();

				V2WMailer::send('battle.win', $dwinner, array( '%url%' => $battle->get_url() ));
				V2WMailer::send('battle.loss', $dloser, array( '%url%' => $battle->get_url() ));

				//save outcome
				$battle->save_outcome(array(
						'winner' => $winner->get_post_id(),
						'loser' => $loser->get_post_id(),
						'detail' => $battle->vote_detail(),
						'tied' => false,
						'method' => 'votes'
					));

				//convert winning design to product
				self::create_product( $winner );

			}

		}

	}

	/**
	 *	Settle a Tie
	 *	via AJAX
	 *	
	 */
	public static function settle_tie() 
	{
		$battle_id = filter_var( $_POST['battle'], FILTER_SANITIZE_STRING );
		$design = filter_var( $_POST['design'], FILTER_SANITIZE_STRING );

		if( ! in_array($design, array('a', 'b')) ) {
			V2W::json(array(
				'code' => 400,
				'message' => 'Design not found'
			));
		}

		$battle = new Battle( $battle_id );

		if( $battle->in_progress() || ! $battle->is_tied() || ! current_user_can('administrator') ) {
			V2W::json(array(
				'code' => 400,
				'message' => 'Invalid request'
			));
		}

		$admin = wp_get_current_user();
		$winner = $battle->get_design( $design );
		$loser = ($design == 'a') ? $battle->get_design('b') : $battle->get_design('a');

		//update outcome
		$battle->save_outcome(array(
			'winner' => $winner->get_post_id(),
			'loser' => $loser->get_post_id(),
			'detail' => $battle->vote_detail(),
			'tied' => false,
			'method' => 'panel',
			'settled_by' => $admin->ID
		));

		//notify designers
		$dwinner = $winner->get_designer();
		$dloser = $loser->get_designer();

		V2WMailer::send('battle.win', $dwinner, array( '%url%' => $battle->get_url() ));
		V2WMailer::send('battle.loss', $dloser, array( '%url%' => $battle->get_url() ));

		//convert winning design to product
		self::create_product( $winner );

		V2W::json(array(
			'code' => 200,
			'message' => 'Battle settled'
		));
	}

	/**
	 *	Convert winner to product
	 *
	 *	@param (Design) design to convert to product
	 */
	public static function create_product( Design $design ) 
	{
		V2W::convert_to_product( $design );
	}

	/**
	 *	Process CRON requests
	 */
	public static function process_cron() 
	{
	
		//only handle valid cron requests ?iscron=yes&token=[VALID TOKEN]
		if( ! isset($_GET['token']) || $_GET['token'] != V2W_SECURITY_TOKEN )
			return;

		//get action
		$service = $_GET['service'] ?: false;

		switch( $service ) {

			//process ended battles
			case 'close':
				self::process_ended();			//end previous day battles
				V2W::create_daily_battles();	//create next day battles
				mail('sregge@gmail.com', 'Battles Created', 'All battles Created Successfully');
				break;

			case false:
			default:
				return;
				break;
		}
	}

}
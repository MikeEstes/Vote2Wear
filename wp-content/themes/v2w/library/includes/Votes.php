<?php


class Votes {

	/**
	 *	Get votes for Battle
	 *
	 *	@param $battle (Battle) Battle Object
	 *	@return (int) votes
	 */
	static function for_battle( Battle $battle ) {
		global $wpdb;

		$votes = $wpdb->get_var("
			SELECT COUNT(id) FROM v2w_votes 
			WHERE battle_id = {$battle->get_post_id()}
		");

		return (int) $votes;

	}

	/**
	 *	Get Votes for a specific design
	 *	within a Battle
	 *
	 *	@param $battle (Battle) Battle Object
	 *	@param $design (Design) Design Object
	 *	@return (int) votes
	 */
	static function for_design( Battle $battle, Design $design ) {
		global $wpdb;

		$votes = $wpdb->get_var("
			SELECT COUNT(id) FROM v2w_votes 
			WHERE battle_id = {$battle->get_post_id()} 
				AND design_id = {$design->get_post_id()}
		");

		return (int) $votes;

	}

	/**
	 *	Place a Vote for a
	 *	design within a battle
	 *
	 *	@param $battle (Battle) battle voting on
	 *	@param $design (Design) design voting for
	 *	@param $user (WP_User) user placing vote
	 *
	 *	@return (mixed) WP_Error on error | true on success
	 */
	static function place( Battle $battle, Design $design, WP_User $user ) {
		global $wpdb;

		if( Votes::happened( $battle, $user ) )
			return Votes::change( $battle, $design, $user );

		$vote = $wpdb->insert( 'v2w_votes', array(
				'battle_id' => $battle->get_post_id(),
				'design_id' => $design->get_post_id(),
				'user_id' => $user->id,
				'created_at' => date('Y-m-d H:i:s'),
				'modified_at' => date('Y-m-d H:i:s')
			), array( '%d', '%d', '%d', '%s', '%s' ) );

		if( !$vote )
			return new WP_Error('error', 'Unable to place vote.');

		return true;

	}

	/**
	 *	Update a vote already
	 *	placed by a User
	 *
	 *	@param $battle (Battle)
	 *	@param $design (Design)
	 *	@param $user (WP_User)
	 *	@return 
	 */
	static function change( Battle $battle, Design $design, WP_User $user ) {
		global $wpdb;

		$change = $wpdb->update('v2w_votes', array(
				'design_id' => $design->get_post_id()
			), array(
				'battle_id' => $battle->get_post_id(),
				'user_id' => $user->id
			));

		if( $change === false )
			return new WP_Error('error', 'Unable to change vote.');

		return true;

	}

	/**
	 *	Get Votes by a User
	 *
	 *	@param $user (WP_User) User to get votes for
	 *	@return array
	 */
	static function by_user( WP_User $user ) {
		global $wpdb;

		$votes = $wpdb->get_results("
			SELECT * FROM v2w_users
			WHERE user_id = $user->id
			ORDER BY created_at DESC
		");

		return $votes;

	}

	/**
	 *	Get vote by user on
	 *	a specific battle. 
	 *
	 *	@param $user (WP_User)
	 *	@param $battle (Battle)
	 *	@return (mixed) false if not vote found | Design object voted for
	 *
	 */
	static function by_user_for_battle( WP_User $user, Battle $battle ) {
		global $wpdb;

		$vote = $wpdb->get_var("
			SELECT design_id FROM v2w_votes
			WHERE user_id = {$user->id}
				AND battle_id = {$battle->get_post_id()}
		");

		if( is_null($vote) )
			return false;

		return new Design( $vote );

	}

	/**
	 *	Check if a user has already
	 *	voted on a battle.
	 *
	 *	@param $battle (Battle)
	 *	@param $user (WP_User)
	 *	@return (bool)
	 */
	static function happened( Battle $battle, WP_User $user ) {
		global $wpdb;

		$check = $wpdb->get_var("
			SELECT id FROM v2w_votes
			WHERE battle_id = {$battle->get_post_id()}
				AND user_id = {$user->id}
		");

		return (is_null($check)) ? false : true;

	}

}
<?php

//register post type
add_action('init', array('Battle', 'register_post_type'));

//
class Battle {

	//post type
	static $post_type = 'battle';

	//WP Post ID
	protected $post_id;

	//WP Post Object
	protected $post;

	//design objects
	protected $design_a;
	protected $design_b;
	
	// Voters
	protected $voters_a;
	protected $voters_b;

	/**
	 *	Constructor
	 *
	 *	@param $post (int|WP_Post) WP Post object or ID
	 */
	public function __construct( $post ) {

		$post = get_post( $post );

		if( is_null($post) )
			throw new Exception('Invalid post supplied.');

		$this->post_id = $post->ID;
		$this->post = $post;
		$this->get_designs();

	}

	/**
	 *	Get Post ID
	 *
	 *	@return (int) WP Post ID
	 */
	public function get_post_id() {
		return $this->post_id;
	}

	/**
	 *	Get Battle Headline
	 *
	 *	@return (string) Title
	 */
	public function get_headline() {
		return $this->post->post_title;
	}

	/**
	 *	Get Battle URL
	 *
	 *	@return (string) battle url
	 */
	public function url() 
	{
		return get_permalink( $this->post_id );
	}

	/**
	 *	Alias for url
	 */
	public function get_url() {
		$this->url();
	}

	/**
	 *	Get Start Date of Battle
	 *
	 *	@return (DateTime)
	 */
	public function start_date() {
		return new DateTime( $battle->post->post_date );
	}

	/**
	 *	Return the amount of time
	 *	remaining in this battle in
	 *	seconds
	 */
	public function time_left() {

		//$oneday = 60*60*24;
		//$end = strtotime($this->post->post_date) + $oneday;
		$end = $this->end_time();

		//is the battle over?
		if( $end <= time() )
			return 0;

		return $end - time();

	}

	/**
	 *	Return end time in 
	 *	timestamp
	 *
	 *	@return (timestamp)
	 */
	public function end_time() 
	{
		$oneday = 60*60*24;
		$end = strtotime($this->post->post_date) + $oneday;

		return $end;
	}

	/**
	 *	Is this Battle in progress?
	 *	Helper method
	 *
	 *	@return bool
	 */
	public function in_progress() {

		if( $this->post->post_status == 'publish' && $this->time_left() > 0 )
			return true;

		return false;
	}

	/**
	 *	Get Total number of Votes
	 *	This includes votes from both designs
	 *
	 *	@return (int) Number of votes on this battle
	 */
	public function get_total_votes() {
		return Votes::for_battle( $this );
	}

	/**
	 *	Get votes for a specific Design
	 *
	 *	@param $design (string) 'a' | 'b'
	 *	@return (mixed) WP_Error | (int) number of votes for design
	 */
	public function votes_for( $design ) {

		$design = strtolower($design);
		$key = 'design_' . $design;

		//is valid param?
		if( !in_array($design, array('a', 'b')) )
			return new WP_Error('invalid', 'Method only supports votes for Design A or Design B');

		$votes = Votes::for_design( $this, $this->{$key} );

		return $votes;

	}

	/**
	 *	Get Vote Detail
	 *	Returns an array with various data
	 *	points
	 *
	 *	@return (array)
	 */
	public function vote_detail() 
	{
		$total = $this->get_total_votes();
		$a = $this->votes_for('a');
		$b = $this->votes_for('b');

		$leader = ($a > $b) ? 'a' : 'b';
		$leader = ($a == $b) ? 'tie' : $leader;

		return array(
			'total_votes' => $total,
			'a_votes' => $a,
			'b_votes' => $b,
			'leader' => $leader,
			'a_share' => round(($a/$total)*100, 2),
			'b_share' => round(($b/$total)*100, 2)
		);
	}

	/**
	 *	Vote on a Design
	 *
	 *	@param $design (string) 'a' | 'b'
	 *	@param $user (WP_User) User voting
	 *	@return true on success, WP_Error on error
	 */
	public function vote_for( $design, WP_User $user ) {

		if( ! $this->in_progress() )
			return new WP_Error('ended', 'This battle has ended. You can no longer place a vote');

		$design = strtolower($design);
		$key = 'design_' . $design;
		alert( $key );

		if( !in_array($design, array('a', 'b')) )
			return new WP_Error('invalid', 'Method only supports votes for Design A or Design B');

		$vote = Votes::place( $this, $this->{$key}, $user );		
			
		if ($key === 'design_a')
		{
			//$voters_a.push($user);
			alert( $voters_a );
		} else if ($key === 'design_b')
		{
			//$voters_b.push($user);
			alert( $voters_b );
		}

		return $vote;

	}

	/**
	 *	Has User Voted
	 *	Checks to see if a specific user has 
	 *	voted.
	 *
	 *	@param (WP_User) User or Designer Object
	 *	@return (mixed) false if no vote, Design voted for if voted
	 */
	public function has_user_voted( WP_User $user ) 
	{
		$voted = Votes::by_user_for_battle( $user, $this );
		return $voted ?: false;
	}

	/**
	 *	Load Designs
	 *	Helper method for building the
	 *	object out
	 */
	protected function get_designs() {

		$a = get_field( 'field_55c031c262287', $this->get_post_id() );
		$b = get_field( 'field_55c0322562288', $this->get_post_id() );

		$this->design_a = new Design( $a[0]->ID );
		$this->design_b = new Design( $b[0]->ID );

	}

	/**
	 *	Get Design from Battle
	 *
	 *	@param $design (string) 'a' | 'b'
	 *	@return Design
	 */
	public function get_design( $design ) {

		//setup
		$design = strtolower($design);
		$key = 'design_' . $design;

		//is valid param?
		if( !in_array($design, array('a', 'b')) )
			return new WP_Error('invalid', 'Method only supports votes for Design A or Design B');

		return $this->{$key};

	}

	/**
	 *	Get Winner
	 *	If the battle is ongoing, this
	 *	will be the current leader. If the 
	 *	battle is over, it will be the winner
	 *
	 *	@return (mixed) - (Design) object which is winning | (string) Tie if tied
	 */
	public function get_winner() {

		if( $this->has_outcome() ) {
			$outcome = $this->get_outcome();

			//check result
			if( $outcome['tied'] )
				return 'Tie';

			return new Design( $outcome['winner'] );

		}else {

			//get votes for both designs
			$votes_a = $this->votes_for('a');
			$votes_b = $this->votes_for('b');

			if( $votes_a == $votes_b )
				return 'Tie';

			if( $votes_a > $votes_b )
				return $this->design_a;

			return $this->design_b;

		}

	}

	/**
	 *	Get Loser
	 *	Exact opposite of get_winner()
	 *
	 *	@return (mixed) - (Design) object which is losing | (string) Tie if tied
	 */
	public function get_loser() {

		//check for outcome
		if( $this->has_outcome() ) {
			$outcome = $this->outcome();

			if( $outcome['tied'] )
				return 'Tie';

			return new Design( $outcome['loser'] );

		}else {

			$winner = $this->get_winner();

			//is tie?
			if( is_string($winner) )
				return $winner;

			//return the opposite of winner
			if( $winner === $this->design_a )
				return $this->design_b;

			return $this->design_a;

		}

	}

	/**
	 *	Is the Battle tied?
	 *
	 *	@return (bool)
	 */
	public function is_tied() 
	{
		//has outcome
		if( $this->has_outcome() ) {
			$outcome = $this->get_outcome();

			if( $outcome['tied'] )
				return true;
			else
				return false;

		}

		//no outcome
		$winner = $this->get_winner();
		return ($winner == 'Tie') ? true : false;
	}

	/**
	 *	Save the outcome of the battle
	 *	Stores a serialized array as a 
	 *	wp_postmeta field
	 *
	 *	@param (array) result
	 *	@return (bool)
	 */
	public function save_outcome( $result ) 
	{
		$update = update_post_meta( $this->get_post_id(), '_outcome', $result );
	}

	/**
	 *	Get Outcome
	 *
	 *	@return (array) outcome
	 */
	public function get_outcome() 
	{
		return get_post_meta( $this->get_post_id(), '_outcome', true );
	}

	/**
	 *	Has Outcome ?
	 */
	public function has_outcome() 
	{
		$outcome = $this->get_outcome();
		return (!empty($outcome));
	}

	/**
	 *	Create Battle
	 *
	 *	@access static
	 *	@param $a (Design) First design in battle
	 *	@param $b (Design) Second design in battle
	 *	@param $date (DateTime) date to start the battle. Default to current date
	 *
	 *	@return (mixed) WP_Error | Battle Object
	 */
	static function create( Design $a, Design $b, DateTime $date = NULL ) {

		if( is_null($date) )
			$date = new DateTime('today');

		//create the battle
		$battle = wp_insert_post(array(
			'post_type' => Battle::$post_type,
			'post_title' => $a->get_name() . ' vs ' . $b->get_name(),
			'post_status' => 'future',
			'post_date' => $date->format('Y-m-d H:i:s'),
			'post_date_gmt' => $date->format('Y-m-d H:i:s')
		));

		//success
		if( is_wp_error($battle) )
			return $battle;	//error!

		//add design relationships via ACF
		update_field( 'field_55c031c262287', array($a->get_post_id()), $battle );	//Design A
		update_field( 'field_55c0322562288', array($b->get_post_id()), $battle );	//Design B

		$battle = new Battle( $battle );

		do_action('battle_created', $battle);

		//return Battle Object
		return $battle;

	}

	/**
	 *	Register post type needed
	 *	for battle management
	 *
	 */
	static function register_post_type() {

		register_post_type('battle', array(
			'label' => 'Battles',
			'description' => 'All battles',
			'public' => true,
			'supports' => array('title', 'comments', 'page-attributes')
		));

	}

}
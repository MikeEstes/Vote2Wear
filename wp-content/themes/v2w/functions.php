<?php

require_once( TEMPLATEPATH.'/library/includes/config.php' );

show_admin_bar(false);

if(!defined('THEME_URL'))
	define('THEME_URL', get_bloginfo('template_directory'));

//	fix db after server move
//require_once( TEMPLATEPATH.'/library/includes/mysql-replace.php' );
//MySQL_Replace::replace('old', 'new');

//	dependicies
require_once( TEMPLATEPATH.'/library/includes/wp-header-remove.php' );
require_once( TEMPLATEPATH.'/library/includes/Stripe/init.php' );
require_once( TEMPLATEPATH.'/library/includes/V2WMailer.php' );
require_once( TEMPLATEPATH.'/library/includes/social-signon.php' );
require_once( TEMPLATEPATH.'/library/includes/V2W.php' );
require_once( TEMPLATEPATH.'/library/includes/Designer.php' );
require_once( TEMPLATEPATH.'/library/includes/Design.php' );
require_once( TEMPLATEPATH.'/library/includes/Battle.php' );
require_once( TEMPLATEPATH.'/library/includes/BattleCloser.php' );
require_once( TEMPLATEPATH.'/library/includes/Votes.php' );
require_once( TEMPLATEPATH.'/library/includes/faq.php' );
require_once( TEMPLATEPATH.'/library/includes/account-functions.php' );
require_once( TEMPLATEPATH.'/library/includes/Payment.php' );
require_once( TEMPLATEPATH.'/library/includes/Transfer.php' );

//	menus
register_nav_menus(array(
	'main-nav' => 'Main Navigation',
	'foot-nav' => 'Footer Navigation'
));

//	post thumbnails
add_theme_support( 'post-thumbnails' );
add_image_size( SHIRT_PREVIEW, 150, 150 );

#	Misc Hooks / Filters
########################################################

//prevent access where needed
//add_action('init', array('V2W', 'prevent_wp_admin_access')); // @todo remove
add_action('template_redirect', array('V2W', 'prevent_registration_if_logged_in'));

//prevent submit design access if not logged in
add_action('template_redirect', array('V2W', 'login_before_submit_design'));

//generate shirt previews on approval
add_action( 'draft_to_publish', array('V2W', 'generate_shirts_on_approval'), 10, 1 );

#	AJAX Requests
########################################################
add_action('wp_ajax_nopriv_vlogin', array('V2W', 'login'));						//Log user in
add_action('wp_ajax_nopriv_vregister', array('V2W', 'register_via_ajax'));		//Register user

add_action('wp_ajax_place_vote', array('V2W', 'vote_via_ajax'));
add_action('wp_ajax_nopriv_place_vote', array('V2W', 'vote_via_ajax'));

#	Scripts
########################################################
add_action( 'wp_enqueue_scripts', 'spry_print_scripts' );

function spry_print_scripts() {
	
	if( is_admin() )
		return false;
	
	wp_enqueue_script( 'jquery' );
	wp_register_script( 'modernizr', THEME_URL.'/library/js/modernizr.js', array( 'jquery' ), '' );
	wp_register_script( 'circleanimate', THEME_URL.'/library/js/circle-progress.js', array( 'jquery' ), '' );
	wp_register_script( 'lib', THEME_URL.'/library/js/lib.js', array( 'jquery', 'circleanimate' ), '' );
	wp_enqueue_script( 'modernizr' );
	wp_enqueue_script( 'circleanimate' );
	wp_enqueue_script( 'lib' );

	wp_localize_script( 'lib', 'V2W', array(
		'ajaxurl' => admin_url('admin-ajax.php'),
		'template_url' => get_bloginfo('template_directory'),
		'shirts' => get_bloginfo('template_directory') . '/library/images/shirt_templates'
	) );
}

#	General Functions
########################################################

//custom length excerpt
function my_excerpt($limit) {
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
	array_pop($excerpt);
	$excerpt = implode(" ",$excerpt).'...';
  } else {
	$excerpt = implode(" ",$excerpt);
  } 
  $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
  return $excerpt;
}

/**
 *	Custom Comment Format
 */
function v2w_comment_format( $comment, $args, $depth ) 
{
	$user = new WP_User( $comment->user_id );
	$is_designer = V2W::is_user_designer( $user );
	if( $is_designer ) 
		$designer = new Designer( $comment->user_id );
	?>

		<li <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
			<div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
				<div class="comment-author vcard">
					<?php echo V2W::get_user_profile_pic( $args['avatar_size'], $comment->user_id )  ?>
					<?php //echo get_avatar( $comment, $args['avatar_size'] ); ?>
					<?php printf( __( '<cite class="fn">%s</cite>' ), get_comment_author_link() ); ?>

					<?php if( $is_designer ) : ?>
						<span class="location"><?php echo $designer->get_location(); ?></span>
					<?php endif; ?>

				</div>

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></em>
					<br />
				<?php endif; ?>

				<div class="comment-text">
					<?php comment_text(); ?>
				</div>

				<div class="btm">
					<span class="time"><?php comment_date('M d Y'); ?> <span class="split">|</span> <?php comment_time('h:ia'); ?></span>
					<span class="reply">
						<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
					</span>
				</div>

			</div>

	<?php
}


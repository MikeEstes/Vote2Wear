<?php get_header(); ?>

<main class="content home">

	<?php /* VOTE2WIN Contest Header (Temporary) */ ?>
	<!--<div class="center">
		<a href="https://vote2wear.com/vote2win/">
			<img src="https://vote2wear.com/wp-content/uploads/2015/12/Winter-Contest-Promo21.png" style="padding-bottom: 50px; display:block; margin-left: auto; margin-right: auto; height: auto; max-width: 100%;"></img>
		</a>
	</div>-->
	<div class="center">
<?php /* VOTE2WIN Header (Main) */ ?>

       	<div class="home-text-main">
            <h1 class="title">May the best shirt <span class="green">win</span></h1>
            <h2 class="sub">Two designs enter, one shirt leaves.</h2>
            <div class="small">Vote for the design that strikes you the most in these Daily Battles and use the arrows to go to the next battle in the line up. Let the games begin!</div>
        </div>
        
        <?php
            //get daily battles
            $battles = V2W::get_daily_battles( new DateTime('today') );
        ?>

        <?php if( ! empty($battles) ) : ?>
            <div id="daily-batles-home" class="battles">
                <?php foreach( $battles as $battle ) : ?>
                    
                    <div class="battle" id="battle-<?php echo $battle->get_post_id(); ?>">

                        <?php
                            $design_a = $battle->get_design('a');
                            $designer_a = $design_a->get_designer();
                            $design_b = $battle->get_design('b');
                            $designer_b = $design_b->get_designer();
                            $votes = $battle->vote_detail();
                        ?>

                        <div class="battle-circle-wrap">
                            
                            <div class="circle-wrap cw-left">
                                <div class="circle-outer">
                                	<div class="circle-animate <?php if(in_array($votes['leader'], array('a', 'tie'))) echo 'leader'; ?>" data-votes="<?php echo $votes['a_share']; ?>"></div>
                                    
                                    <div class="circle-inner">
                                        
                                        <div class="image" style="background:url('<?php echo $design_a->get_design_url(); ?>') no-repeat center center; background-size:cover;">
                                            <div class="inside"></div>
                                        </div>
                                        
                                    </div>
                                </div>
                                
                                <?php if( $battle->in_progress() ) : ?>
                                    <?php if( is_user_logged_in() ) : ?>
                                        <?php
                                            $user = wp_get_current_user();
                                            $voted = $battle->has_user_voted( $user );
                                        ?>
                                        <?php if( ! $voted ) : ?>
                                            <a href="#" class="place-vote a-vote-btn" data-battle="<?php echo $battle->get_post_id(); ?>" data-design="a" data-once="1">PLACE VOTE</a>
                                        <?php else: ?>
                                            <a href="#" class="place-vote a-vote-btn <?php echo ( $design_a->get_post_id() === $voted->get_post_id() ) ? 'active-vote' : 'inactive-vote'; ?> disabled" data-battle="<?php echo $battle->get_post_id(); ?>" data-design="a" data-once="1">VOTED</a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <!--<a href="#" class="place-vote disabled">Login to Vote</a>-->
                                        <a href="<?php echo get_option('siteurl'); ?>/login" class="settle-tie">Login to Vote</a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php //Battle over. Do what? ?>
                                <?php endif; ?>
                            </div>
                            <?php // end circle wrap left ?>
                            
                            <div class="circle-wrap cw-right">
                                <div class="circle-outer">
                                	<div class="circle-animate <?php if(in_array($votes['leader'], array('b', 'tie'))) echo 'leader'; ?>" data-votes="<?php echo $votes['b_share']; ?>"></div>
                                    
                                    <div class="circle-inner">
                                        
                                        <div class="image" style="background:url('<?php echo $design_b->get_design_url(); ?>') no-repeat center center; background-size:cover;">
                                            <div class="inside"></div>
                                        </div>
                                        
                                    </div>
                                </div>
                                
                                <?php if( $battle->in_progress() ) : ?>
                                    <?php if( is_user_logged_in() ) : ?>
                                        <?php
                                            $user = wp_get_current_user();
                                            $voted = $battle->has_user_voted( $user );
                                        ?>
                                        <?php if( ! $voted ) : ?>
                                            <a href="#" class="place-vote b-vote-btn" data-battle="<?php echo $battle->get_post_id(); ?>" data-design="b" data-once="1">PLACE VOTE</a>
                                        <?php else: ?>
                                            <a href="#" class="place-vote b-vote-btn <?php echo ( $design_b->get_post_id() === $voted->get_post_id() ) ? 'active-vote' : 'inactive-vote'; ?> disabled" data-battle="<?php echo $battle->get_post_id(); ?>" data-design="b" data-once="1">VOTED</a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <!--<a href="#" class="place-vote disabled">Login to Vote</a>-->
                                        <a href="<?php echo get_option('siteurl'); ?>/login" class="settle-tie">Login to Vote</a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php //Battle over. Do what? ?>
                                <?php endif; ?>
                            </div>
                            <?php // end circle wrap right ?>
                            
                            <div class="clear"></div>
                            
                            <div class="timer-wrap" data-time="<?php echo $battle->time_left(); ?>">
                                <div class="time-left">
                                    <div class="line1">time left to vote</div>
                                    <div class="line2">
                                        <?php
                                            $time_left = $battle->time_left();
                                            echo V2W::prettify_time( $time_left );
                                        ?>
                                    </div>
                                </div>
                                
                                <div class="social">
                                    <div class="line line1"></div>
                                    <div class="text">SHARE</div>
                                    <div class="icons">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $battle->url(); ?>" class="facebook"></a>
                                        <a href="https://twitter.com/home?status=<?php echo $battle->url(); ?>" class="twitter"></a>
                                        <a href="https://pinterest.com/pin/create/button/?url=<?php echo $battle->url(); ?>&media=&description=" class="pinterest"></a>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="line line2"></div>
                                </div>
                                
                                <div class="date">
                                    <div class="line1">start date</div>
                                    <div class="line2">
                                        <?php 
                                            $start_date = $battle->start_date();
                                            echo $start_date->format('m-d-y');
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php // end timer-wrap ?>
                            
                            
                        </div>
                        <?php // end battle-circle-wrap ?>

                        <div class="battle-detail-wrap">
                            <div class="main-inner">
                                <div class="a-battle a-battle-left">
                                    <div class="title-wrap">
                                        <h3 class="design-name"><?php echo $design_a->get_name(); ?></h3>
                                    </div>
                                    
                                    <div class="desc-wrap">
                                        <div class="formatted">
                                            <?php echo $design_a->get_description(); ?>
                                        </div>
                                    </div>
                                    
                                    <div class="additional-details">
                                        <div class="a-designer"><div class="inner1"><div class="inner2">
                                            <div class="designer">
                                                <div class="icon" style="background:url(<?php echo $designer_a->get_avatar_url(); ?>) no-repeat center center; background-size:cover;"></div>
                                                <div class="name"><?php echo $designer_a->get_name(); ?></div>
                                                <div class="location"><?php echo $designer_a->get_location(); ?></div>
                                            </div>
                                        </div></div></div>
                                        
                                        <div class="view-profile-wrap"><div class="inner1"><div class="inner2">
                                            <a href="<?php echo $designer_a->profile_url(); ?>" class="view-profile">VIEW PROFILE</a>
                                        </div></div></div>
                                        
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <?php // end a-battle-left ?>
                                
                                <div class="a-battle a-battle-right">
                                    <div class="title-wrap">
                                        <h3 class="design-name"><?php echo $design_b->get_name(); ?></h3>
                                    </div>
                                    
                                    <div class="desc-wrap">
                                        <div class="formatted">
                                            <?php echo $design_b->get_description(); ?>
                                        </div>
                                    </div>
                                    
                                    <div class="additional-details">
                                        <div class="a-designer"><div class="inner1"><div class="inner2">
                                            <div class="designer">
                                                <div class="icon" style="background:url(<?php echo $designer_b->get_avatar_url(); ?>) no-repeat center center; background-size:cover;"></div>
                                                <div class="name"><?php echo $designer_b->get_name(); ?></div>
                                                <div class="location"><?php echo $designer_b->get_location(); ?></div>
                                            </div>
                                        </div></div></div>
                                        
                                        <div class="view-profile-wrap"><div class="inner1"><div class="inner2">
                                            <a href="<?php echo $designer_b->profile_url(); ?>" class="view-profile">VIEW PROFILE</a>
                                        </div></div></div>
                                        
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <?php // end a-battle-right ?>
                                
                                <div class="vs-divider">VS</div>
                            
                                <div class="clear"></div>
                            </div>
                            
                            <div class="comments-wrap">
                                <a href="<?php echo $battle->url(); ?>#comments" class="view-comments">VIEW COMMENTS</a>
                            </div>
                        </div>
                        <?php // end battle-detail-wrap ?>

                    </div>
                <?php endforeach; ?>

                <div class="battle">
                    <div class="final-slide">
                        <h2 class="headline">That's it!</h2>
                        <div class="formatted">
                            <p>Check out the daily battles page to conversate about your favorite battles. Check back tomorrow for new daily battles!</p>
                        </div>
                    </div>
                </div>

            </div>
        <?php else : ?>
            <h1 class="big-grn-title">No daily battles to show!</h1>
        <?php endif; ?>
    
	</div>

    <a href="<?php echo get_option('siteurl'); ?>/daily-battles" class="view-all-battles">VIEW ALL DAILY BATTLES</a>
    
    <div class="join-the-battle">
    	<div class="icon"></div>
        <div class="title">Join the battle</div>
        <div class="sub">start battling or purchase a winning shirt</div>
        <div class="link-split">
        	<a href="<?php echo get_option('siteurl'); ?>/submit-design" class="submit">SUBMIT A DESIGN</a>
            <div class="divider"></div>
            <a href="<?php echo get_option('siteurl'); ?>/shop" class="shop">START SHOPPING</a>
          
            <div class="clear"></div>
        </div>
    </div>
    <?php // end join-the-battle ?>

</main>
<?php //end content ?>

<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/library/js/slick/slick.min.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		
		$('.battles').slick({
			arrows: true,
			slide: '.battle',
			prevArrow: '<div class="slick-prev"></div>',
			nextArrow: '<div class="slick-next"></div>',
            infinite: false
		});
		
		$('body').on('click', '.slick-prev', function(evt) {
			return false;
		});
		$('body').on('click', '.slick-next', function(evt) {
			return false;
		});

        $('.a-vote-btn').on('click', function(evt) {
            if ($(this).hasClass('disabled')) {
                return;
            }
            $('.slick-current .a-vote-btn').addClass('active-vote');
            $('.slick-current .b-vote-btn').addClass('inactive-vote');
        });

        $('.b-vote-btn').on('click', function(evt) {
            if ($(this).hasClass('disabled')) {
                return;
            }
            $('.slick-current .b-vote-btn').addClass('active-vote');
            $('.slick-current .a-vote-btn').addClass('inactive-vote');
        });

        $(V2W).on('vote:placed', function(evt) {
            var battles = $('.battles'),
                btns = battles.find('.slick-current .place-vote');

            btns.addClass('disabled');
            btns.text('Voted');

            battles.slick('next');
        });

	});
</script>

<?php get_footer(); ?>
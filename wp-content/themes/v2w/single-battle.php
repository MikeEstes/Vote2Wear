<?php get_header(); ?>

<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
	<?php
		$battle = new Battle( $post->ID );
		$design_a = $battle->get_design('a');
		$design_b = $battle->get_design('b');
		$designer_a = $design_a->get_designer();
		$designer_b = $design_b->get_designer();
		$votes = $battle->vote_detail();
	?>

	<main class="content single-battle single center">

		<div class="battle" id="battle-<?php echo $battle->get_post_id(); ?>">

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
	                        	<a href="#" class="place-vote" data-battle="<?php echo $battle->get_post_id(); ?>" data-design="a" data-once="1">PLACE VOTE</a>
                        	<?php else: ?>
                        		<a href="#" class="place-vote <?php echo ( $design_a->get_post_id() === $voted->get_post_id() ) ? 'active-vote' : 'inactive-vote'; ?> disabled" data-battle="<?php echo $battle->get_post_id(); ?>" data-design="a" data-once="1">VOTED</a>
                        	<?php endif; ?>
	                    <?php else: ?>
	                        <a href="#" class="place-vote disabled">Login to Vote</a>
	                    <?php endif; ?>
	                <?php else: ?>
	                	<?php //Battle over. Do what? ?>
	                <?php endif; ?>

	                <?php // Admin Tie Closer ?>
	                <?php if( ! $battle->in_progress() && $battle->is_tied() && current_user_can('administrator') ) : ?>
	                	<a href="#" class="settle-tie" data-design="a" data-battle="<?php echo $battle->get_post_id(); ?>">Crown Winner</a>
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
							<?php $user = wp_get_current_user(); ?>
							<?php if( ! $battle->has_user_voted( $user ) ) : ?>
	                        	<a href="#" class="place-vote" data-battle="<?php echo $battle->get_post_id(); ?>" data-design="b" data-once="1">PLACE VOTE</a>
                        	<?php else: ?>
                        		<a href="#" class="place-vote <?php echo ( $design_b->get_post_id() ==  $voted->get_post_id() ) ? 'active-vote' : 'inactive-vote'; ?> disabled" data-battle="<?php echo $battle->get_post_id(); ?>" data-design="b" data-once="1">VOTED</a>
                        	<?php endif; ?>
	                    <?php else: ?>
	                        <a href="#" class="place-vote disabled">Login to Vote</a>
	                    <?php endif; ?>
	                <?php else: ?>
	                	<?php //Battle over. Do what? ?>
	                <?php endif; ?>

	                <?php // Admin Tie Closer ?>
	                <?php if( ! $battle->in_progress() && $battle->is_tied() && current_user_can('administrator') ) : ?>
	                	<a href="#" class="settle-tie" data-design="b" data-battle="<?php echo $battle->get_post_id(); ?>">Crown Winner</a>
	                <?php endif; ?>

				</div>
				<?php // end circle wrap right ?>
				
				<div class="clear"></div>
				
				<div class="timer-wrap" data-time="<?php echo $battle->time_left(); ?>">
					<div class="time-left">

						<div class="line1">
							<?php if( $battle->in_progress() ) : ?>
								time left to vote
							<?php else : ?>
								battle ended
							<?php endif; ?>
						</div>
						<div class="line2">
							<?php
							if( $battle->in_progress() ) {
								$time_left = $battle->time_left();
								echo V2W::prettify_time( $time_left );
							}
							?>
						</div>
					</div>
					
					<div class="social">
						<div class="line line1"></div>
						<div class="text">SHARE</div>
						<div class="icons">
							<a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" class="facebook"></a>
							<a href="https://twitter.com/home?status=<?php the_permalink(); ?>" class="twitter"></a>
							<a href="https://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=&description=" class="pinterest"></a>
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

		<?php //Comments and More Battles ?>
		<div class="battle-btm center">

			<section class="discussion" id="comments">
				<h5 class="h1title">There are <?php comments_number(0, 1, '%'); ?> comments</h5>
				<span class="divider"></span>
				<div class="grn-dash">Add a new comment</div>

				<?php //comment form ?>
				<div class="discuss">
					<?php comment_form(array(
						'logged_in_as' => '',
						'title_reply' => '',
						'label_submit' => 'Submit Comment'
					)); ?>
				</div>

				<?php //comments ?>
				<?php $comments = get_comments(array( 'post_id' => $post->ID )); ?>
				<ul class="comments">
					<?php wp_list_comments(array(
						'style' => 'ul',
						'avatar_size' => 40,
						'callback' => 'v2w_comment_format'
					), $comments); ?>
				</ul>

			</section>

			<?php get_template_part('battle', 'more'); ?>

		</div>

	</main>
	<?php //end content ?>

<?php endwhile; endif;  ?>

<script type="text/javascript">
jQuery(document).ready(function($) {

         var str = $('.cw-left .circle-inner .image').css('background');
         var metaImgStr = str.substring(str.lastIndexOf('(')+2, str.lastIndexOf(')')-1);
         $('meta[property=og\\:image]').attr('content', metaImgStr);

	//place vote extras
	$(function() {
		$(V2W).on('vote:placed', function(evt) {
			$('.place-vote').addClass('disabled').html('Voted');
			window.location.reload();
		});
	});

	<?php if( current_user_can('administrator') ) : ?>
		//settle ties
		$(function() {
			$('a.settle-tie').on('click', function(evt) {
				evt.preventDefault();
				var battle = $(this).data('battle'),
					design = $(this).data('design');

				$.ajax({
					url: V2W.ajaxurl,
					type: 'POST',
					data: {
						action: 'settle_tie',
						battle: battle,
						design: design
					},
					success: function( response ) {

						if( response.code == 200 ) {
							window.location.reload();
						}

					}
				});

				return false;
			});
		});
	<?php endif; ?>

});
</script>

<?php get_footer(); ?>
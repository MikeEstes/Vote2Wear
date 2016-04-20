<?php /* Template Name: About */ ?>
<?php get_header(); ?>

<main class="content about-page">
	<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
        
        <div class="main-page">
        	<div class="center">
            
                <div class="text-section-1">
                    <div class="line1">Connect through creativity</div>
                    <div class="line2">At Vote to Wear we believe that we connect artists to their fans and supporters. If you like a certain designer or artist vote for their works and stay up to date with what they are releasing. So, what are you waiting for? Find some of your favorite designs today!</div>
                </div>
                
                <div class="meet-the-team">
                    <div class="headline">Meet the Team</div>
                    
                    <div class="team-wrap">
                    
                        <div class="a-member">
                            <div class="image">
                            	<div class="inner"></div>
                                <div class="image-wrap"><img src="<?php bloginfo('template_directory'); ?>/library/images/team-1.jpg" alt="team member" /></div>
                            </div>
                            
                            <div class="name">Adam Polselli</div>
                            <div class="position">ceo &amp; co-founder</div>
                        </div>
                        
                        <div class="a-member">
                            <div class="image">
                            	<div class="inner"></div>
                                <div class="image-wrap"><img src="<?php bloginfo('template_directory'); ?>/library/images/team-2.jpg" alt="team member" /></div>
                            </div>
                            
                            <div class="name">Aditya Agarwal</div>
                            <div class="position">coo &amp; co-founder</div>
                        </div>
                        
                        <div class="a-member">
                            <div class="image">
                            	<div class="inner"></div>
                                <div class="image-wrap"><img src="<?php bloginfo('template_directory'); ?>/library/images/team-3.jpg" alt="team member" /></div>
                            </div>
                            
                            <div class="name">Alicia Chen</div>
                            <div class="position">marketing</div>
                        </div>
                        
                        <div class="a-member">
                            <div class="image">
                            	<div class="inner"></div>
                                <div class="image-wrap"><img src="<?php bloginfo('template_directory'); ?>/library/images/team-4.jpg" alt="team member" /></div>
                            </div>
                            
                            <div class="name">Brian Mattingly</div>
                            <div class="position">creative director</div>
                        </div>
                        
                    </div>
                </div>
                <?php // end meet-the-team ?>
                
                <div class="content-cols">
                    <div class="col col1">
                        <h2 class="headline">Are You a Trendsetter?</h2>
                        <p>In ut interdum justo, auctor euismod enim. Suspendisse id leo convallis, sagittis nibh ac, molestie nisl. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Suspendisse lacinia porttitor arcu eu ullamcorper. Cras nibh massa, elementum quis turpis sit amet, consequat auctor magna. Phasellus diam dui, dapibus quis sem a, feugiat viverra elit. Nulla ullamcorper felis quam, vel luctus felis pellentesque eget.</p>
                        <p>Nulla eu malesuada elit, id blandit leo. Aenean vel facilisis libero, id venenatis lacus. Suspendisse nec massa odio. Praesent tincidunt hendrerit erat, ac faucibus diam egestas at.</p>
                    </div>
                    
                    <div class="col col2">
                        <h2 class="headline">We Like You</h2>
                        <p>Donec ligula arcu, condimentum non tempus et, laoreet vitae justo. Cras varius augue vel turpis aliquam, a scelerisque nulla scelerisque.</p>
                        <p>Vestibulum venenatis lectus metus, in commodo enim scelerisque non. Phasellus ac vestibulum mauris, non faucibus erat. Etiam eget sapien efficitur nisi auctor interdum.</p>
                        <p>Nulla eu malesuada elit, id blandit leo. Aenean vel facilisis libero, id venenatis lacus. Suspendisse nec massa odio. Praesent tincidunt hendrerit erat, ac faucibus diam egestas at.</p>
                    </div>
                    
                    <div class="clear"></div>
                </div>
                <?php // end content-cols ?>
                
                <div class="connect">
                    <div class="headline">get to know us</div>
                    <div class="social">
                        <a href="<?php echo SOCIAL_URL_FACEBOOK; ?>" class="facebook" target="_blank"></a>
                        <a href="<?php echo SOCIAL_URL_TWITTER; ?>" class="twitter" target="_blank"></a>
                        <!--<a href="#" class="pinterest" target="_blank"></a>-->
                    </div>
                </div>
          
          	</div>  
        </div>

	<?php endwhile; endif; ?>
</main>
<?php //end content ?>

<?php get_footer(); ?>
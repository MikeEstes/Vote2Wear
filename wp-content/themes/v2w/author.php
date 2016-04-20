<?php get_header(); ?>
<?php $curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author')); ?>
<?php $designer = new Designer( $curauth->ID ); ?>

<main class="content a-designer">
	
    <div class="main-profile-wrap">
    	<div class="center">
        
        	<h1 class="main-title"><?php echo $designer->get_name(); ?> <span class="green">profile</span></h1>
            <div class="sub"><?php echo $designer->get_tagline(); ?></div>

            <?php $location = $designer->get_location(); ?>
            <div class="sub-sub">
                <?php if( ! empty($location) ) : ?>
                    based in <?php echo $location; ?>
                <?php endif; ?>
            </div>
            
            <div class="image">
				<!--<img src="<?php bloginfo('template_directory'); ?>/library/images/team-3.jpg" alt="profile photo" />-->
                <?php echo $designer->get_avatar(240); ?>
            </div>
            
            <div class="social">

                <?php
                    $fb = $designer->get_facebook();
                    $tw = $designer->get_twitter();
                    $ig = $designer->get_instagram();
                ?>

                <?php if( ! empty($fb) ) : ?>
            	   <a href="<?php echo $fb; ?>" class="facebook" target="_blank"></a>
                <?php endif; ?>

                <?php if( ! empty($tw) ) : ?>
            	   <a href="<?php echo $tw; ?>" class="twitter" target="_blank"></a>
                <?php endif; ?>

                <?php if( ! empty($ig) ) : ?>
                    <a href="<?php echo $ig; ?>" class="instagram" target="_blank"></a>
                <?php endif; ?>

            </div>
        
        </div>
    </div>
    <?php // end main-profile-wrap ?>
    
    <div class="profile-battles-wrap">
    	<div class="center">
        
        	<div class="battle-title">Battles</div>

            <?php $battles = $designer->get_battles(); ?>
            <?php if( empty($battles) ) : ?>
                <div style="text-align:center;">No battles to display</div>
            <?php else: ?>
                <div class="battles all-battles">
                    <?php foreach( $battles as $battle ) : ?>

                        <div class="sm-battle">
                            <div class="designs">
                                <div class="design design-a">
                                    <?php echo $battle->get_design('a')->get_design(); ?>
                                </div>
                                <div class="design design-b">
                                    <?php echo $battle->get_design('b')->get_design(); ?>
                                </div>
                            </div>
                            <div class="meta">
                                <div class="title">
                                    <?php echo $battle->get_design('a')->get_name(); ?> <span>vs</span> <?php echo $battle->get_design('b')->get_name(); ?>
                                </div>
                                <a href="<?php echo $battle->url(); ?>" class="battle-url">View Battle</a>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        
        </div>
    </div>
    <?php // end profile-battles-wrap ?>
    
</main>
<?php //end content ?>

<?php get_footer(); ?>
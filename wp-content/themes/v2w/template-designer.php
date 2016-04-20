<?php /* Template Name: designer */ ?>
<?php get_header(); ?>

<main class="content a-designer">
	
    <div class="main-profile-wrap">
    	<div class="center">
        
        	<h1 class="main-title">keisha armand's <span class="green">profile</span></h1>
            <div class="sub">all around geek</div>
            <div class="sub-sub">based in orlando, fl</div>
            
            <div class="image">
				<img src="<?php bloginfo('template_directory'); ?>/library/images/team-3.jpg" alt="profile photo" />            	
            </div>
            
            <div class="social">
            	<a href="#" class="facebook" target="_blank"></a>
            	<a href="#" class="twitter" target="_blank"></a>
                <a href="#" class="instagram" target="_blank"></a>
            </div>
        
        </div>
    </div>
    <?php // end main-profile-wrap ?>
    
    <div class="profile-battles-wrap">
    	<div class="center">
        
        	<div class="battle-title">Battles</div>
        
        </div>
    </div>
    <?php // end profile-battles-wrap ?>
    
</main>
<?php //end content ?>

<?php get_footer(); ?>
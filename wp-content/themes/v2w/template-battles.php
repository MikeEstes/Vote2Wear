<?php /* Template Name: Battles */ ?>
<?php get_header(); ?>

<main class="content daily-battles">
	
    <div class="main-profile-wrap">
    	<div class="center">
        
        	<h1 class="main-title big-grn-title">Daily Battles</h1>
        
        </div>
    </div>
    <?php // end main-profile-wrap ?>
    
    <div class="profile-battles-wrap">
    	<div class="center">
        
        	<!--<div class="battle-title">Battles</div>-->

            <div class="battles all-battles">

                <?php
                    //get daily battles
                    $battles = V2W::get_daily_battles( new DateTime('today') );
                ?>

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
        
        </div>
    </div>
    <?php // end profile-battles-wrap ?>
    
</main>
<?php //end content ?>

<?php get_footer(); ?>
<?php /*
Template Name: Page Template
*/ ?>

<?php get_header(); ?>

<main class="content standard-page">
	<?php if(have_posts()) : while(have_posts()) : the_post(); ?>

        <div class="title-hero-section">
            
            <h1 class="title green">Template Page <span class="line"></span></h1>
            <div class="line2">If there is a cool subheading this is what is will look like</div>
            
        </div>
        <?php // end title-section ?>
        
        <div class="main-page">
            
           <?php the_content(); ?> 
            
        </div>

	<?php endwhile; endif; ?>
</main>
<?php //end content ?>

<?php get_footer(); ?>
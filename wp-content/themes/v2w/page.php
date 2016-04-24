<?php get_header(); ?>

<main class="content main-page-template">
	<div class="center">

		<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
			<div class="formatted">
				<?php the_content(); ?>
				<br /><br />
			</div>
		<?php endwhile; endif; ?>
			
	</div>
</main>
<?php //end content ?>

<?php get_footer(); ?>
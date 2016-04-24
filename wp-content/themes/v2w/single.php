<?php get_header(); ?>

<main class="content blog single">

	<?php $battle = new Battle(15); ?>
	<?php echo $battle->time_left(); ?>

</main>
<?php //end content ?>

<?php get_footer(); ?>
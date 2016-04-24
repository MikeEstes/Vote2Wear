<?php
	//get more battles
	//exclude the current post
	$battles = V2W::more_battles(2, array($post->ID));
?>

<?php if( ! empty($battles) ) : ?>
	<section class="more-battles">
		<h6 class="grn-dash"><span>More Battles</span></h6>

		<div class="battles">

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

		<?php //should link to the daily battles page ?>
		<a href="<?php echo get_option('siteurl'); ?>/daily-battles" class="more-url btn1">More Daily Battles</a>

	</section>
<?php endif; ?>
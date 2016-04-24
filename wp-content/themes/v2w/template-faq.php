<?php /* Template Name: FAQ */ ?>
<?php get_header(); ?>

<main class="content faq center">
	<div class="cols">
		<div class="col left-side">

		<section id="faq">
			<h1 class="page-title">FAQ's</h1>
			<div class="divider"></div>

			<?php $faq = new WP_Query(array(
				'post_type' => 'faq',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'orderby' => 'menu_order',
				'order' => 'ASC'
			)); ?>

			<ul class="qs">
				<?php if( $faq->have_posts() ) : while( $faq->have_posts() ) : $faq->the_post(); ?>
					<li class="question" id="question-<?php the_ID(); ?>">
						<span class="text"><?php the_title(); ?><span class="icon"></span></span>
						<div class="answer formatted">
							<?php the_content(); ?>
						</div>
					</li>
				<?php endwhile; endif; ?>
			</ul>

			<?php wp_reset_postdata(); ?>

		</section>

		</div>
		<div class="col right-side">

			<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
				<section class="formatted">
					<h2 class="page-title"><?php the_title(); ?></h2>
	                <div class="divider"></div>
	                <?php the_content(); ?>
				</section>
			<?php endwhile; endif; ?>
			
		</div>
	</div>
</main>
<?php //end content ?>

<script type="text/javascript">
jQuery(document).ready(function($) {

	//FAQ
	$(function() {
		var qs = $('#faq .qs>li');
		qs.find('.text').on('click', function(evt) {
			$(this).closest('li').toggleClass('open');
		});
	});

});
</script>

<?php get_footer(); ?>
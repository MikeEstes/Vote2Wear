</div>	<!-- site wrap -->

<footer id="footer" class="">
	<div class="inner center">

		<div class="info">
			<a href="<?php echo get_option('siteurl'); ?>">
				<img src="<?php bloginfo('template_directory'); ?>/library/images/logo-condensed.png" alt="V2W" class="logo" />
			</a>
			<div class="formatted">
				<p>Vote for the design that strikes you the most and use the arrows to go to the next battle in the line up. Let the games begin!</p>
				<p class="copyright">Vote 2 Wear &copy; <?php echo date('Y'); ?></p>
			</div>
		</div>

		<nav id="foot-nav">
			<?php wp_nav_menu(array(
				'theme_location' => 'foot-nav',
				'container' => ''
			)); ?>
		</nav>

	</div>
	<a href="#" class="back-to-top"></a>
</footer>

<?php wp_footer(); ?>
</body>
</html>
<!DOCTYPE html>

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

<head>
	<meta charset="utf-8">
	
	<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
	
	<?php //mobile meta (hooray!) ?>
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
	<meta name="msvalidate.01" content="4C2DA88C6391E2C8365BDCEE6FA1860A" />
	<meta name="google-site-verification" content="QylC2uaMfxpm6AYiCdu5XS-0l9SPTaJ6w7e-qCLzy_k" />
	
	<?php //hide iOS browser bar ?>
	<meta name="apple-mobile-web-app-capable" content="yes" />

	<!--[if IE]>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<![endif]-->
	<!--[if lt IE 9]>
		<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
	<![endif]-->
	
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/library/css/style.css" type="text/css" media="screen" />
    	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/library/js/slick/slick.css" type="text/css" media="screen" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory'); ?>/library/images/favicon.png" />

	<script src="//use.typekit.net/dah4isf.js"></script>
	<script>try{Typekit.load({ async: true });}catch(e){}</script>

<meta property="og:image:width" content="200" />
<meta property="og:image:height" content="200" />
        
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div id="wrap">	
	<header id="header">
		<div class="top-blue-bar"></div>
	
		<div class="center">

			<div id="topbar">
				<ul>
					<li class="search">
						<form action="<?php echo get_option('siteurl'); ?>" id="global-search-form">
							<input type="text" name="s" value="" placeholder="Search" />
							<?php shopp('storefront.search'); ?>
						</form>
						<a href="#" class="search-trigger"></a>
					</li>
					<li class="login-url">
						<?php if( is_user_logged_in() ) : ?>
							<?php $current_user = wp_get_current_user(); ?>
							<a href="<?php echo get_option('siteurl'); ?>/shop/account"><?php echo $current_user->user_login; ?></a>
							<li class="logout-url">
								<a href="<?php echo get_option('siteurl'); ?>/shop/account/?logout" class="logout">Logout</a>
							</li>
						<?php else : ?>
							<a href="<?php echo get_option('siteurl'); ?>/login">Login</a>
						<?php endif; ?>
					</li>
					<li class="cart-preview">
						<a href="<?php shopp('cart.url'); ?>" class="cart-url">
							<span class="item-count"><?php shopp('cart.total-items'); ?></span>
						</a>
					</li>
				</ul>
			</div>
	
			<div id="btmbar">
	
				<a href="<?php echo get_option('siteurl'); ?>" title="Vote2Wear" class="logo"></a>
		
				<nav id="nav">
					<?php wp_nav_menu(array(
						'theme_location' => 'main-nav',
						'container' => ''
					)); ?>
				</nav>
	
				<a href="<?php echo get_permalink(56); ?>" class="submit-design">Submit A Design</a>
	
			</div>
	
			<?php //mobile stuff ?>
			<div id="mobile-header">
				<ul class="top">
					<li class="first"><a href="#" class="menu-trigger" id="mobile-menu-trigger"></a></li>
					<li class="mid"><a href="<?php echo get_option('siteurl'); ?>" class="logo"></a></li>
					<li class="last">
						<?php if( is_user_logged_in() ) : ?>
							<?php $current_user = wp_get_current_user(); ?>
							<a href="<?php echo get_option('siteurl'); ?>/shop/account"><?php echo $current_user->user_login; ?></a>
						<?php else : ?>
							<a href="<?php echo get_option('siteurl'); ?>/login">Log In</a>
						<?php endif; ?>
					</li>
				</ul>
			</div>
	
			<div id="mobile-open" class="closed">
				<div class="cart">
					<a href="#" class="icon-cart">Cart</a>
					<span class="item-count">1 Item(s)</span>
				</div>
				<div class="search">
					<form action="<?php echo get_option('siteurl'); ?>" method="get" id="mobile-search-form">
						<input type="text" name="s" value="" placeholder="Search" />
					</form>
				</div>
				<nav id="mobile-nav">
					<?php wp_nav_menu(array(
						'theme_location' => 'foot-nav',
						'container' => ''
					)); ?>
				</nav>
			</div>

		</div>
	</header>
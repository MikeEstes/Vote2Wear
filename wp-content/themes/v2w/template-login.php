<?php /* Template Name: Login */ ?>
<?php get_header(); ?>

<main class="content login">

	<?php /* VOTE2WIN Contest Header (Temporary) */ ?>
	<!--<div class="center">
		<a href="https://vote2wear.com/vote2win/">
			<img src="https://vote2wear.com/wp-content/uploads/2015/12/Winter-Contest-Promo21.png" style="padding-bottom: 50px; display:block; margin-left: auto; margin-right: auto; height: auto; max-width: 100%;"></img>
		</a>
	</div>-->
	
	<div class="center">	
    
        <div class="cols">
            <div class="col left">
                <div class="inside">
    
                    <?php //if( isset($_GET['tw']) && $_GET['tw'] == 'email' ) : ?>
                        <?php //do not show form. we need to collect the email for twitter signin ?>
                    <?php //else : ?>
			
                        <h1 class="page-title">Login</h1>
                        <div class="divider"></div>
        
                        <form action="<?php echo get_option('siteurl'); ?>" method="post" id="form-login" name="login">
        
                            <div class="errors"></div>
        
                            <input type="text" name="log" value="" placeholder="Username" id="usrlog" />
                            <input type="password" name="pwd" value="" placeholder="Password" id="usrpwd" />
                            <a href="<?php shopp( 'customer.recover-url' ); ?>" class="forgot-url">Forgot Password</a>
                            <input type="submit" value="Login" class="btn2" />
                            <span class="extra">Don't have an account? <a href="<?php echo get_option('siteurl'); ?>/join">Create one!</a></span>
        
                        </form>

                    <?php //endif; ?>
    
                </div>
            </div>
            <div class="col right create-account">
                <div class="inside">
    
                    <?php if( isset($_GET['tw']) && $_GET['tw'] == 'email' ) : ?>

                        <h2 class="page-title">Sign in With Twitter</h2>
                        <div class="divider"></div>

                        <form action="<?php echo get_option('siteurl'); ?>/socialsignon" method="GET" class="final-tw" id="tw-login-final">
                            <input type="text" name="email" placeholder="Enter your email address to continue" value="" id="email-for-twitter" />
                            <input type="submit" value="Sign in with Twitter" class="tw-submit btn2" />
                            <input type="hidden" name="provider" value="tw" />
                        </form>

                    <?php else: ?>

                        <h2 class="page-title">Sign in With</h2>
                        <div class="divider"></div>
        
                        <a href="<?php echo get_option('siteurl'); ?>/socialsignon?provider=fb" class="fb"></a>
                        <a href="<?php echo get_option('siteurl'); ?>/socialsignon?provider=tw" class="tw"></a>
                        <a href="<?php echo get_option('siteurl'); ?>/socialsignon?provider=gg" class="gg"></a>

                        <?php if( isset($_GET['tw']) || isset($_GET['fb']) ) : ?>
                            <?php $er = $_GET['tw'] ?: $_GET['fb']; ?>
                            <?php if( $er == 'error' ) : ?>
                                <div class="login-error error" style="color:#d23f3b">
                                    Unable to login with that account. Unknown error.<br /><br />
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <div class="divider"></div>
                        <a href="<?php echo get_option('siteurl'); ?>/join" class="btn2">Create an Account</a>

                    <?php endif; ?>
    
                </div>
            </div>
        </div>
	</div>
    
    <div class="join-the-battle get-help">
        <div class="icon"></div>
        <div class="title">Have Questions?</div>
        <div class="sub">these links might help</div>
        <div class="link-split">
            <a href="<?php echo get_option('siteurl'); ?>/faqs" class="faq">FAQ</a>
            <div class="divider"></div>
            <a href="<?php echo get_option('siteurl'); ?>/faqs" class="terms">TERMS</a>
            <div class="clear"></div>
        </div>
    </div>
</main>


<script type="text/javascript">
jQuery(document).ready(function($) {

	$('#form-login').on('submit', function(evt) {

		var log = $('#usrlog'),
			pwd = $('#usrpwd'),
			ers = $(this).find('.errors');

		//clear errors
		ers.html('');

		//submit
		$(this).v2wLogin( log.val(), pwd.val(), false, function(r) {
			
			if( r.status == 'success' ) 
			{
				window.location.replace( r.redirect );
			}else 
			{
				ers.html( r.error );
			}

		} );

		return false;
	});

    $('#tw-login-final').on('submit', function(evt) {
        var tw = $('#email-for-twitter');
        tw.removeClass('error');

        if( tw.val() == '' ) {
            tw.addClass('error');
            return false;
        }

        return true;

    });

});
</script>

<?php get_footer(); ?>
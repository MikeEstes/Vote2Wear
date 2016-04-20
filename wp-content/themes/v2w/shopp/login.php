

    <div class="center login">
    
        <div class="cols">
            <div class="col left">
                <div class="inside">
    
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
    
                </div>
            </div>
            <div class="col right create-account">
                <div class="inside">
    
                    <h2 class="page-title">Sign in With</h2>
                    <div class="divider"></div>
    
                    <!--<div class="formatted">
                        <p>Want to go into battle with your designs? Vote 2 Wear will print your winning shirt for all to wear.
        Sign up today!</p>
                    </div>-->
    
                    <a href="<?php echo get_option('siteurl'); ?>/socialsignon?provider=fb" class="fb"></a>
                    <a href="<?php echo get_option('siteurl'); ?>/socialsignon?provider=tw" class="tw"></a>
                    <a href="<?php echo get_option('siteurl'); ?>/socialsignon?provider=gg" class="gg"></a>
                    <div class="divider"></div>
                    <a href="<?php echo get_option('siteurl'); ?>/join" class="btn2">Create an Account</a>
    
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

});
</script>



<?php /*
<div class="center login-center">
	<?php if ( shopp( 'customer.notloggedin' ) ) : ?>
        <form action="<?php shopp( 'customer.url' ); ?>" method="post" class="shopp shopp_page" id="login">
        
        	<label for="login" class="account-login-title"><?php _e( 'Account Login', 'Shopp' ); ?></label>
            
            <div class="form-row">
            	<label for="login"><?php shopp( 'customer.login-label' ); ?></label>
                <?php shopp( 'customer.account-login', 'size=20&title=' . __( 'Login', 'Shopp' ) ); ?>
            </div>
            
            <div class="form-row">
            	<label for="password"><?php _e( 'Password', 'Shopp' ); ?></label>
                <?php shopp( 'customer.password-login', 'size=20&title=' . __( 'Password', 'Shopp' ) ); ?>
            </div>
            
            <div class="form-row submit-row">
                <?php shopp( 'customer.login-button' ); ?>
            </div>
            
            <a href="<?php shopp( 'customer.recover-url' ); ?>" class="lost-password-link"><?php _e( 'Lost your password?', 'Shopp' ); ?></a>
             
        </form>
    <?php endif; ?>
</div>
*/ ?>
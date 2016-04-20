
	<div class="center login">
    
        <div class="cols">
            <div class="col left">
                <div class="inside">
    
                    <h1 class="page-title">Reset Password</h1>
                    <div class="divider"></div>
    
                    <form action="<?php shopp( 'customer', 'url' ); ?>" method="post" id="form-login" name="login">
    
                        <div class="errors"></div>
    
                        <input type="text" name="account-login" value="" placeholder="Login" id="usrlog" />
                        <?php shopp('customer','recover-button', 'class=btn2'); ?>
    
                    </form>
    
                </div>
            </div>
            <div class="col right create-account">
                <div class="inside">
    
                    <h2 class="page-title">Create Account</h2>
                    <div class="divider"></div>
    
                    <div class="formatted">
                        <p>Want to go into battle with your designs? Vote 2 Wear will print your winning shirt for all to wear.
        Sign up today!</p>
                    </div>
    
                    <a href="<?php echo get_option('siteurl'); ?>/join" class="btn2">Create Account</a>
    
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


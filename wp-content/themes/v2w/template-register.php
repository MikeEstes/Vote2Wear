<?php /* Template Name: Register */ ?>
<?php get_header(); ?>

<main class="content login register">
	<div class="center">

        <div class="cols">
            <div class="col left">
                <div class="inside">
    
                    <h1 class="page-title">Create Account</h1>					
                    <div class="divider"></div>
                        
                    <form action="<?php echo get_option('siteurl'); ?>" method="post" id="form-register" name="join">
    
                        <div class="errors"></div>
    
                        <input type="text" name="username" value="" placeholder="Username" id="rusername" tabindex="1" />
                        <input type="text" name="email" value="" placeholder="Email Address" id="remail" tabindex="2" />
                        <input type="text" name="first_name" value="" placeholder="First Name" id="rfirst" tabindex="3" />
                        <input type="text" name="last_name" value="" placeholder="Last Name" id="rlast" tabindex="4" />
                        <input type="password" name="password" value="" placeholder="Password" id="rpass" tabindex="5" />
                        <input type="password" name="password_confirm" value="" placeholder="Confirm Password" id="rconfirm" tabindex="6" />

                        <div class="terms">
                            <input type="checkbox" name="terms" value="yes" id="terms" /> I agree to the terms and conditions of this site.
                        </div>
    
                        <div class="submit">
                            <input type="submit" value="Create Account" id="register-submit" class="btn2" />
                        </div>
    
                        <div class="notes">
                            Already have an account? <a href="<?php echo get_option('siteurl'); ?>/login">Sign in</a>
                        </div>
    
                    </form>
    
                </div>
            </div>
            <div class="col right create-account">
                <div class="inside">
    
                    <h2 class="page-title">Sign in With</h2>
                    <div class="divider"></div>
    
                    <a href="<?php echo get_option('siteurl'); ?>/socialsignon?provider=fb" class="fb"></a>
                    <a href="<?php echo get_option('siteurl'); ?>/socialsignon?provider=tw" class="tw"></a>
                    <a href="<?php echo get_option('siteurl'); ?>/socialsignon?provider=gg" class="gg"></a>
    
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

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script> 
<script type="text/javascript">
jQuery(document).ready(function($) {

	$('#form-register').on('submit', function(evt) {

		var ers = $(this).find('.errors'),
            terms = $('#terms');

        if( ! terms.is(':checked') ) {
            ers.html('You must agree to the terms first');
            return false;
        }

		var data = {
			firstname: $('#rfirst').val(),
			lastname: $('#rlast').val(),
			username: $('#rusername').val(),
			email: $('#remail').val(),
			password: $('#rpass').val(),
			confirm: $('#rconfirm').val()
		};

		//clear errors
		ers.html('');

		$(this).v2wRegister( data, function(r) {
			console.log(r);

			if( r.status !== 'success' ) 
			{
				ers.html( r.error );
			} else 
			{
				// Registers the New User with GetResponse, after we've validated the form, and created the User.
				//registerGetResponse( data );
				window.location.replace( r.redirect );
			}

		} );

		return false;
	});
	
	 /* function registerGetResponse( data ) {
		$.ajax({
			url: "https://api.getresponse.com/v3",
			type: "POST",
			data: { "name": "Bruce Wayne", "email": "Bruce@Batcave.com", "campaign": { "campaignId": "p3CQ3" } },
			dataType: "json",
			success: function (result) {
				console.log( result );
				switch (result) {
					case true:
						processResponse(result);
						break;
					default:
						resultDiv.html(result);
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
			}
		});
	}  */
});
</script>

<?php get_footer(); ?>
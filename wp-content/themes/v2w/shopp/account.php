
<?php $user = wp_get_current_user(); ?>

<div class="center">
    <div class="account-page-wrap">
        
        <div class="main-profile">
        
            <div class="photo-section">
                <div class="image" id="user-profile-img">
                    <?php echo V2W::get_user_profile_pic( 96 ); ?>
                    <!--<img src="<?php bloginfo('template_directory'); ?>/library/images/example-shirt.png" />-->
                </div>
                <a href="#" class="caption" id="upload-profile-img">UPLOAD IMAGE</a>
                <input type="file" id="profile-img-uploader" name="profile_pic" style="visibility:hidden;">
                
                <a href="<?php echo get_option('siteurl'); ?>/submit-design" class="upload-cta">
                        <div class="icon"></div>
                        <div class="line1">Submit a design!</div>
                        <div class="line2">SUBMIT DESIGN</div>
                </a>
            </div>
            <?php // end photo-section ?>
            
            <div class="profile-section" id="ptabsection">
                
                <div class="name-area">
                    <div class="name"><?php echo $user->display_name; ?><a href="<?php echo get_option('siteurl'); ?>/shop/account/?logout" class="logout">Logout</a></div>
                    <div class="username"><?php echo $user->user_nicename; ?></div>
                </div>

                <?php if ( shopp( 'customer.profile-saved' ) && shopp( 'customer.password-change-fail' ) ) : ?>
                    <div class="success"><?php _e( 'Your account has been updated.', 'Shopp' ); ?></div>
                <?php endif; ?>
                
                <div class="panel-select">
                    <ul id="account-options" class="tabs">
                        <li class="active">profile</li>
                        <li>password</li>

                        <?php if( V2W::is_user_designer($user) ) : ?>
                            <li>billing</li>
                            <li>payment info</li>
                            <li>balance &amp; deposits</li>
                        <?php endif; ?>

                    </ul>
                </div>
                    
                    <ul id="account-panels" class="steps">
                    
                        <li class="a-form-panel panel-profile active">
                            <form action="<?php shopp( 'customer.action' ); ?>" method="POST">

                                <?php
                                    $location = get_field('location', 'user_'.$user->ID);
                                    $location = explode(',', $location);
                                    $city = $location[0];
                                    $state = $location[1];
                                ?>
                            
                                <div class="field-group">
                                    
                                    <input type="text" name="firstname" class="half first" placeholder="FIRST NAME" value="<?php shopp('customer.firstname', 'mode=value'); ?>" />
                                    <input type="text" name="lastname" class="half" placeholder="LAST NAME" value="<?php shopp('customer.lastname', 'mode=value') ?>" />
                                    <input type="text" name="email" placeholder="EMAIL" value="<?php shopp('customer.email', 'mode=value'); ?>" />
                                    <input type="text" name="profile_city" class="half first" placeholder="CITY" value="<?php echo $city; ?>" />
                                    <input type="text" name="profile_state" class="half" placeholder="STATE" value="<?php echo $state; ?>" />
                                    <input type="text" name="display_name" class="half first" placeholder="USERNAME" value="<?php echo $user->user_nicename; ?>" readonly />

                                    <div class="socials">
                                        <div class="headline">Social Media</div>
                                        <div class="row">
                                            <label>Facebook URL</label>
                                            <input type="text" name="facebook_url" class="half" placeholder="FACEBOOK URL" value="<?php echo get_field('facebook_url', 'user_'.$user->ID); ?>" />
                                        </div>
                                        <div class="row">
                                            <label>Twitter Handle</label>
                                            <input type="text" name="twitter_handle" class="half" placeholder="TWITTER HANDLE" value="<?php echo get_field('twitter_handle', 'user_'.$user->ID); ?>" />
                                        </div>
                                        <div class="row">
                                            <label>Instagram URL</label>
                                            <input type="text" name="instagram_url" class="half" placeholder="INSTAGRAM URL" value="<?php echo get_field('instagram_url', 'user_'.$user->ID); ?>" />
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <div class="submit-row">
                                    <?php shopp( 'customer.save-button', 'label=UPDATE PROFILE&class=btn2' ); ?>
                                    <a href="#" class="cancel-btn">Cancel</a>   
                                </div>
                            
                            </form>
                        </li>
                        
                        <li class="a-form-panel panel-password">
                            <form action="<?php shopp( 'customer.action' ); ?>" method="POST">
                        
                                <div class="field-group">
                                    
                                    <input type="password" name="password" class="half first" placeholder="NEW PASSWORD" />
                                    <input type="password" name="confirm-password" class="half" placeholder="CONFIRM NEW PASSWORD" />
                                    
                                </div>
                                
                                <div class="submit-row">
                                    <?php shopp( 'customer.save-button', 'label=UPDATE PASSWORD&class=btn2' ); ?>
                                    <a href="#" onclick="window.location.reload()" class="cancel-btn">Cancel</a>
                                </div>
                            
                            </form>
                        </li>
                        
                        <?php if( V2W::is_user_designer($user) ) : ?>
                            <li class="a-form-panel panel-billing"><?php get_template_part('shopp/account', 'billing'); ?></li>
                            <li class="a-form-panel panel-payment"><?php get_template_part('shopp/account', 'payment'); ?></li>
                            <li class="a-form-panel panel-balance"><?php get_template_part('shopp/account', 'balance'); ?></li>
                        <?php endif; ?>
                        
                    </ul>
                
            </div>
            <?php // end profile-section ?>
        
        </div>
        <?php // end main-profile ?>
        
        <div class="history-title">Order History</div>
        
        <div class="order-history-wrap">

            <?php if ( shopp( 'customer.has-purchases' ) ) : ?>
                <?php $c = 0; ?>
                <?php while( shopp( 'customer.purchases' ) ) : ?>

                    <div class="a-order <?php echo ($c%2 == 1) ? 'even' : 'odd'; ?>">
                        <div class="col col1"><?php shopp( 'purchase.id' ); ?></div>
                        <div class="col col2"><?php shopp( 'purchase.date' ); ?></div>
                        <div class="col col3"><?php shopp( 'purchase.total' ); ?></div>
                        <div class="col col4"><?php shopp( 'purchase.status' ); ?></div>
                        <?php /*<div class="col col5">
                            <a href="#" class="track">TRACK ORDER</a>
                            <div class="col-divide">|</div>
                            <a href="#" class="view">VIEW ORDER</a>
                        </div>*/ ?>
                    </div>

                    <?php $c++; ?>
                <?php endwhile; ?>
            <?php else : ?>

                <div class="a-order">
                    <div class="col">No orders to display</div>
                </div>

            <?php endif; ?>
        
        </div>
        <?php // end order-history ?>
        
    </div>
</div>

<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/library/js/ui/jquery-ui.min.css" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/library/js/ui/jquery-ui.structure.min.css" />
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/library/js/ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/library/js/fileuploader/jquery.iframe-transport.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/library/js/fileuploader/jquery.fileupload.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/library/js/steps.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {

    $('#ptabsection').steps();

    //upload profile pic
    $(function() {

        var fileInput = $('#profile-img-uploader'),
            is_uploading = false;

        var btn = $('#upload-profile-img'),
            label = btn.text();

        //init file uploader
        var initFileUploader = function() 
        {
            //init
            fileInput.fileupload({
                //options
                url: V2W.ajaxurl,
                type: 'POST',
                formData: {
                    action: 'upload_profile_pic'
                },
                autoUpload: true,
                maxNumberOfFiles: 1,
                acceptFileTypes: '/(\.|\/)(png|jpg|jpeg)$/i',

                // on upload start, disable steps
                start: function(e) 
                {
                    is_uploading = true;
                    btn.text('Uploading... ');
                },

                // on upload finish, enable steps
                done: function(e, data) 
                {
                    is_uploading = false;

                    //reset btn label
                    btn.text( label );

                    if( data.result.code == 200 ) {
                        //swap profile picture
                        $('#user-profile-img img').attr('src', data.result.pic);
                    }else {
                        //error
                        btn.text('Error. Try again.');
                    }

                    //reset file uploader
                    initFileUploader();
                },

                // add file to queue
                add: function(e, data) 
                {

                    var filename = data.files[0].name;
                    data.submit();
                },

                // upload progress
                progress: function(e, data) 
                {
                    var progress = parseInt(data.loaded / data.total * 100, 10),
                        filename = data.files[0].name;

                    btn.text('Uploading... ' + progress + '%');
                },

                // on fail
                fail: function(e, data) 
                {
                }
            });

            
        };

        //trigger open
        btn.on('click', function(evt) {
            evt.preventDefault();

            if( is_uploading )
                return false;   //wait till previous upload has finished

            fileInput.trigger('click');
            return false;
        });

        //init
        initFileUploader();

    });

});
</script>
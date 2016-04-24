<form method="POST" id="form-banking">

    <?php
        $user = wp_get_current_user();
        $designer = new Designer($user->ID);
        $bank = $designer->get_stripe_bank();
    ?>

    <div class="field-group">

        <?php if( $bank ) : ?>
            <div class="form-heading">bank information</div>

            <div class="current-bank-information">
                <?php echo @$bank['bank']; ?> (<?php echo @$bank['last4']; ?>)
            </div>

            <div class="form-divider"></div>

        <?php endif; ?>

        <?php if( $bank ) : ?>
            <div class="form-heading">update bank information</div>
        <?php else: ?>
            <div class="form-heading">bank information</div>
        <?php endif; ?>
        
        <?php //<input type="text" name="bank_name" class="half first" placeholder="BANK NAME" /> ?>
        <input type="text" name="routing_number" class="half first" placeholder="ROUTING NUMBER" />
        <input type="text" name="account_number" class="half" placeholder="ACCOUNT NUMBER" />
        
        <div class="clear"></div>
        <div class="form-divider"></div>
        <div class="form-heading">supply your ssn</div>
        
        <input type="text" name="ssn" class="half first" placeholder="SSN" />
        <?php //<input type="text" name="ffin" class="half" placeholder="FFIN" /> ?>
        
    </div>

    <div class="submit-row">
        <input type="submit" name="save_bank" value="SAVE BANK INFORMATION" class="btn2" />
        <!--<a href="#" class="btn2 update-profile">EDIT BANK INFORMATION</a>-->
        <a href="#" onclick="window.location.reload();" class="cancel-btn">Cancel</a>
    </div>

    <div class="message-row" id="form-banking-msg"></div>

    <input type="hidden" name="action" value="save_bank_info" />
</form>

<script type="text/javascript">
jQuery(document).ready(function($) {

    $(function() {
        var form = $('#form-banking'),
            msg = $('#form-banking-msg');
        
        form.on('submit', function(evt) {
            evt.preventDefault();

            msg.html('');

            $.ajax({
                url: V2W.ajaxurl,
                type: 'POST',
                data: $(this).serialize(),
                success: function( response ) {
                    console.log(response);

                    msg.html( response.message );

                    //clear form if success
                    if( response.code === 200 ) {
                        form.find('input[type=text]').val('');
                    }

                }
            });

            return false;
        });
    });
    
});
</script>
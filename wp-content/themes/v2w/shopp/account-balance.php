<?php $user = wp_get_current_user(); ?>

<div class="field-group">
                                
    <div class="form-heading">balance</div>
    
    <div class="balance-line">
        <?php $balance = V2WPayment::get_balance( $user->ID ); ?>
        <div class="amt">$<?php echo number_format($balance, 2); ?></div>
        <!--<div class="other-box">this amount will be sent on <span>09/26/15</span></div>-->
        <div class="clear"></div>
    </div>
    
    <div class="clear"></div>
    
    <div class="note">Balances must be over $<?php echo number_format(BALANCE_TRANSFER_MINIMUM, 2); ?> to be sent to your bank. Please visit our <a href="<?php echo get_option('siteurl'); ?>/faq">FAQ</a> page for further questions or go to our <a href="<?php echo get_option('siteurl'); ?>/contact">Contact</a> page to get in touch.</div>
    
    <div class="form-divider"></div>
    <div class="form-heading">deposits</div>
    
    <?php $transfers = V2WTransfer::get_transfers( $user->ID ); ?>
    <div class="deposits-wrap">
        <div class="a-deposit">
            <?php if( ! empty($transfers) ) : ?>
                <?php $c = 0; ?>
                <?php foreach( $transfers as $transfer ) : ?>
                    <?php $design = new Design( $transfer->design_id ); ?>
                    <div class="a-deposit <?php echo ($c%2 == 1) ? 'odd' : 'even'; ?>">
                        <div class="col col1">Design: <?php echo $design->get_name(); ?></div>
                        <div class="col col2"><?php echo date('m/d/Y', strtotime($transfer->created_at)); ?></div>
                        <div class="col col3">$<?php echo number_format($transfer->amt, 2); ?></div>
                    </div>
                    <?php $c++; ?>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="col col1">no deposits to display</div>
            <?php endif; ?>
        </div>
        
        <?php /*
        <div class="a-deposit even">
            <div class="col col1">shirt: sunshine madness</div>
            <div class="col col2">08/15/2015</div>
            <div class="col col3">$84.71</div>
        </div>
        
        <div class="a-deposit">
            <div class="col col1">shirt: sunshine madness</div>
            <div class="col col2">08/15/2015</div>
            <div class="col col3">$84.71</div>
        </div>
        
        <div class="a-deposit even">
            <div class="col col1">shirt: sunshine madness</div>
            <div class="col col2">08/15/2015</div>
            <div class="col col3">$84.71</div>
        </div> 
        */ ?>
        
    </div>
    
</div>
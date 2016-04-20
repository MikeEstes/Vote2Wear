<form action="<?php shopp( 'customer.action' ); ?>" method="post" class="shopp validate" autocomplete="off">

    <div class="field-group">

        <input type="text" name="firstname" class="half first" placeholder="FIRST NAME*" value="<?php shopp('customer.firstname', 'mode=value'); ?>" />
        <input type="text" name="lastname" class="half" placeholder="LAST NAME*" value="<?php shopp('customer.lastname', 'mode=value'); ?>" />
        <input type="text" name="billing[country]" placeholder="COUNTRY*" value="<?php shopp('customer.billing-country', 'mode=value'); ?>" />
        <input type="text" name="billing[address]" class="half first" placeholder="ADDRESS*" value="<?php shopp('customer.billing-address', 'mode=value'); ?>" />
        <input type="text" name="billing[xaddress]" class="half" placeholder="APT/SUITE #" value="<?php shopp('customer.billing-xaddress', 'mode=value'); ?>" />
        <input type="text" name="billing[city]" class="half first" placeholder="CITY*" value="<?php shopp('customer.billing-city', 'mode=value'); ?>" />
        <input type="text" name="billing[postcode]" class="half" placeholder="ZIP OR POSTAL CODE*" value="<?php shopp('customer.billing-postcode', 'mode=value'); ?>" />
        <input type="text" name="phone" placeholder="PHONE" value="<?php shopp('customer.phone', 'mode=value'); ?>" />
        
    </div>

    <div class="submit-row">
        <?php shopp( 'customer.save-button', 'label=UPDATE PROFILE&class=btn2' ); ?>
        <?php //<a href="#" class="btn2 update-profile">UPDATE PROFILE</a> ?>
        <a href="#" class="cancel-btn">Cancel</a>
    </div>

</form>
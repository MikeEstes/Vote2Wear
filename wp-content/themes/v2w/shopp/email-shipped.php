Content-type: text/html; charset=utf-8
From: <?php shopp( 'purchase.email-from' ); ?>
To: <?php shopp( 'purchase.email-to' ); ?>
Subject: <?php shopp( 'purchase.email-subject' ); ?>

<html>
	<div id="body">
		<div id="receipt" class="shopp">
        
        	<?php /*
			<table class="labels">
				<tr>
					<td>
						<fieldset class="shipping">
							<legend><?php _e( 'Ship to', 'Shopp' ); ?></legend>
							<address><?php shopp( 'purchase.shipname' ); ?><br /><br />
								<?php shopp( 'purchase.shipaddress' ); ?><br />
								<?php shopp( 'purchase.shipxaddress' ); ?>
								<?php shopp( 'purchase.shipcity' ); ?>, <?php shopp( 'purchase.shipstate' ); ?> <?php shopp( 'purchase.shippostcode' ); ?><br />
								<?php shopp( 'purchase.shipcountry' ); ?></address>
						</fieldset>
					</td>
					<td>
						<fieldset class="shipping">
							<legend><?php _e( 'Shipment', 'Shopp' ); ?></legend>
							<table class="transaction">
								<tr>
									<th><?php _e( 'Tracking Number:', 'Shopp' ); ?></th>
									<td><?php shopp( 'purchase.email-event', 'name=tracking&link=on' ); ?></td>
								</tr>
								<tr>
									<th><?php _e( 'Carrier:', 'Shopp' ); ?></th>
									<td><?php shopp( 'purchase.email-event', 'name=carrier' ); ?></td>
								</tr>
								<tr>
									<th><?php _e( 'Order Date:', 'Shopp' ); ?></th>
									<td><?php shopp( 'purchase.date' ); ?></td>
								</tr>
							</table>
						</fieldset>
					</td>
				</tr>
			</table>
			*/ ?>
            
            <?php /*
			<?php if ( shopp( 'purchase.hasitems' ) ) : ?>
				<table class="order widefat">
					<thead>
						<tr>
							<th scope="col" class="item"><?php _e( 'Items', 'Shopp' ); ?></th>
							<th scope="col"><?php _e( 'Quantity', 'Shopp' ); ?></th>
						</tr>
					</thead>

					<?php while ( shopp( 'purchase.items' ) ) : ?>
						<tr>
							<td>
								<?php shopp( 'purchase.item-name' ); ?><?php shopp( 'purchase.item-options', 'before= – ' ); ?><br />
								<?php shopp( 'purchase.item-sku' ); ?><br />
								<?php shopp( 'purchase.item-addons-list' ); ?>
							</td>
							<td><?php shopp( 'purchase.item-quantity' ); ?></td>
						</tr>
					<?php endwhile; ?>

				</table>
			<?php endif; ?>
			*/ ?>
            
            <table width="100%" align="center" bgcolor="#0c121d" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td align="center">	
                    
                    	<table width="700" align="center" bgcolor="#0c121d" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                        <td width="100%">
					
						<table width="640" align="center" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="100%">
									
									<?php /* header row */ ?>
									<table width="640" align="center" bgcolor="#0c121d" border="0" cellpadding="0" cellspacing="0">
                                    	<tr>
                                        	<td height="22"></td>	
                                        </tr>
                                        
                                        <tr>
                                        	<td style="font-family:Helvetica, Arial, sans-serif; font-size:11px; color:#848f95;">Vote 2 Wear Shipping Confirmation</td>
                                        </tr>
                                        
                                        <tr>
                                        	<td height="36"></td>	
                                        </tr>
									</table>
									<?php /* end header row */ ?>
                                    
                                    <?php /* main row */ ?>
									<table width="640" align="center" bgcolor="#212834" border="0" cellpadding="0" cellspacing="0">
                                    	<tr>
                                        	<td height="130">
                                            	<img src="http://vote2wear.com/wp-content/themes/v2w/library/images/email/header.jpg" width="640" height="130" alt="" style="display:block;" />
                                            </td>	
                                        </tr>
                                        
                                        <tr>
                                        	<td>
                                            
                                            	<?php /* primary content */ ?>
                                                <table width="530" align="center" bgcolor="#212834" border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td style="font-family:Helvetica, Arial, sans-serif; font-weight:bold; font-size:18px; color:#FFF; text-align:center;">Your Order Has Shipped</td>
                                                    </tr>
                                                    
                                                    <tr>
                                                    	<td height="20"></td>
                                                    </tr>
                                                    
                                                    <tr>
                                                    	<td style="font-family:Helvetica, Arial, sans-serif; font-size:14px; color:#848f95; line-height:1.4em; text-align:center;">Thank you for shopping with us. We have shipped your items, and that this completes your order. If you would like to view your past orders please visit your account page.</td>
                                                    </tr>
                                                    
                                                    <tr>
                                                    	<td height="40"></td>
                                                    </tr>
                                                    
                                                    <tr>
                                                    	<td height="50" bgcolor="#0B121D" style="font-family:Helvetica, Arial, sans-serif; font-weight:bold; font-size:13px; letter-spacing:1px; color:#FFF; text-align:center;">TRACKING# <?php shopp( 'purchase.email-event', 'name=tracking&link=off' ); ?></td>
                                                    </tr>
                                                    
                                                    <tr>
                                                    	<td height="45"></td>
                                                    </tr>
                                                    
                                                    <tr>
                                                    	<td>
                                                        	<table class="labels">
                                                                <tr>
                                                                    <td style="font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#848f95; line-height:1.5em;">
                                                                        <span style="font-size:16px; color:#FFF;">Ship To:</span><br /><br />
                                                                        <?php shopp( 'purchase.shipname' ); ?><br />
                                                                        <?php shopp( 'purchase.shipaddress' ); ?><br />
                                                                        <?php shopp( 'purchase.shipxaddress' ); ?><br />
                                                                        <?php shopp( 'purchase.shipcity' ); ?>, <?php shopp( 'purchase.shipstate' ); ?> <?php shopp( 'purchase.shippostcode' ); ?><br />
                                                                        <?php shopp( 'purchase.shipcountry' ); ?>
                                                                    </td>
                                                                    <td style="font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#848f95; line-height:1.5em;">
                                                                        <span style="font-size:16px; color:#FFF;">Shipment:</span><br /><br />
                                                                        Carrier: <?php shopp( 'purchase.email-event', 'name=carrier' ); ?><br />
                                                                        Order Date: <?php shopp( 'purchase.date' ); ?>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    
                                                    <tr>
                                                    	<td height="30"></td>
                                                    </tr>
                                                    
                                                </table>
                                                <?php /* end primary content */ ?>
                                            
                                            </td>	
                                        </tr>
                                        
                                        <tr>
                                        	<td height="40"></td>	
                                        </tr>
									</table>
									<?php /* end main row */ ?>
                                    
                                    <?php /* footer row */ ?>
									<table width="640" align="center" bgcolor="#0c121d" border="0" cellpadding="0" cellspacing="0">
                                    	<tr>
                                        	<td colspan="2" height="25"></td>	
                                        </tr>
                                        
                                        <tr>
                                        	<td width="50%" height="42">
                                            	<img src="http://vote2wear.com/wp-content/themes/v2w/library/images/email/footer-logo.jpg" width="109" height="42" alt="" style="display:block;" />
                                            </td>
                                            
                                            <td width="50%" height="42">
                                            
                                            	<table width="135" align="right" border="0" cellpadding="0" cellspacing="0">
                                                	<tr>
                                                    	<td width="40" height="42"><img src="http://vote2wear.com/wp-content/themes/v2w/library/images/email/facebook.jpg" width="40" height="42" alt="" style="display:block;" /></td>
                                                        <td width="40" height="42"><img src="http://vote2wear.com/wp-content/themes/v2w/library/images/email/twitter.jpg" width="40" height="42" alt="" style="display:block;" /></td>
                                                        <td width="40" height="42"><img src="http://vote2wear.com/wp-content/themes/v2w/library/images/email/pinterest.jpg" width="40" height="42" alt="" style="display:block;" /></td>
                                                        <td width="15" height="42"></td>
                                                    </tr>
                                                </table>
                                            
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                        	<td colspan="2" height="22"></td>	
                                        </tr>
                                        
                                        <tr>
                                        	<td colspan="2" height="1" bgcolor="#FFF"></td>	
                                        </tr>
                                        
                                        <tr>
                                        	<td colspan="2" height="28"></td>	
                                        </tr>
                                        
                                        <tr>
                                        	<td colspan="2" style="font-family:Helvetica, Arial, sans-serif; font-size:11px; color:#848f95; text-align:center;">&copy; 2015 Vote 2 Wear. All Rights Reserved &nbsp;&nbsp;&nbsp;&nbsp; <a href="http://vote2wear.com/terms" style="color:#9aef9b; text-decoration:underline;">Privacy Policy</a> &nbsp;&nbsp; | &nbsp;&nbsp; <a href="http://vote2wear.com/terms" style="color:#9aef9b; text-decoration:underline;">Terms &amp; Conditions</a></td>	
                                        </tr>
                                        
                                        <tr>
                                        	<td colspan="2" height="50"></td>	
                                        </tr>
									</table>
									<?php /* end footer row */ ?>
                                    
                                </td>
                            </tr>
                        </table>
                        
                        </td>
                        </tr>
                        </table>
                        
                    </td>
                </tr>
            </table>
            
		</div>
	</div>
</html>
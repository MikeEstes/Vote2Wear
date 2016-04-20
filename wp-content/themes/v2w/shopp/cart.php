<div class="center">

	<h1 class="page-title">Shopping Cart</h1>

	<?php if ( shopp( 'cart.hasitems' ) ) : ?>
		<form id="cart" action="<?php shopp( 'cart.url' ); ?>" method="post">

			<?php shopp( 'cart.function' ); ?>
			
            <div class="order-history-wrap">
            
                <div class="a-order order-title-line">
                    <div class="col col1">PRODUCT</div>
                    <div class="col col2">PRICE</div>
                    <div class="col col3">QUANTITY</div>
                    <div class="col col4">TOTAL</div>
                </div>
            
            	<?php while ( shopp( 'cart.items' ) ) : ?>
                    <div class="a-order">
                        <div class="col col1"><a href="<?php shopp( 'cartitem.url' ); ?>"><?php shopp( 'cartitem.name' ); ?></a></div>
                        <div class="col col2"><span class="extra">PRICE - </span><?php shopp( 'cartitem.unitprice' ); ?></div>
                        <div class="col col3">
							<?php shopp( 'cartitem.quantity', 'input=text' ); ?>
							<?php shopp( 'cartitem.remove', 'input=button' ); ?>
                        </div>
                        <div class="col col4"><span class="extra">TOTAL - </span><?php shopp( 'cartitem.total' ); ?></div>
                    </div>
                <?php endwhile; ?>
            
            </div>
            <?php // end order-history-wrap ?>

            <div class="update-cart-row">
            	<?php shopp( 'cart.update-button' ); ?>
            </div>

            <div class="update-cart-row current-discounts-applied">
                <?php if ( shopp('cart', 'has-promos' ) ): ?>
                    <ul title="Applied Promotions"> 
                    <?php while( shopp('cart', 'discounts') ): ?>
                        <li>
                            <strong><?php shopp('cart','promo-name'); ?><strong>&nbsp;<?php shopp('cart','promo-discount'); ?>
                        </li>
                    <?php endwhile; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="update-cart-row">
            	<?php shopp( 'cart.applycode', 'value=Apply'); ?>
            </div>
            
            <div class="submit-row">
            	<a href="<?php shopp( 'storefront.url' ); ?>" class="continue">CONTINUE SHOPPING</a>
                <a href="<?php shopp( 'checkout.url' ); ?>" class="checkout-btn">CHECKOUT</a>
            </div>
        </form>

	<?php else : ?>
		<p class="notice"><?php _e( 'There are currently no items in your shopping cart.', 'Shopp' ); ?></p>
		<p>
			<a href="<?php shopp( 'storefront.url' ); ?>">CONTINUE SHOPPING</a>
		</p>
	<?php endif; ?>

</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		
		$('#discount-code').prop('placeholder', "ENTER A PROMO CODE");

	});
</script>
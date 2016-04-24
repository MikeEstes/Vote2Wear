<div class="center shop-center">

<h1 class="page-title">Confirm</h1>

<?php shopp( 'checkout.cart-summary' ); ?>

<form action="<?php shopp( 'checkout.url' ); ?>" method="post" class="shopp" id="checkout">
	<?php shopp( 'checkout.function', 'value=confirmed' ); ?>
	<p class="submit"><?php shopp( 'checkout.confirm-button', 'value=' . __( 'Confirm Order', 'Shopp') ); ?></p>
</form>

</div>

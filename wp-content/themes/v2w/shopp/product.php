<div class="center">

	<?php //Design does not support breadcrumbs ?>
	<?php //shopp( 'storefront.breadcrumb' ); ?>

	<?php if ( shopp( 'product.found' ) ) : ?>
		<?php shopp('product.schema'); ?>

		<?php
			//Get the Designer for this shirt
			$product_id = shopp('product.id', 'return=1');
			$user = get_field('designer', $product_id);
			$designer = new Designer( $user['ID'] );
		?>

		<div class="cols">
			<div class="col left">
				<section class="inner gallery">
					<?php //Product Gallery ?>
					<?php shopp( 'product.gallery', 'p_setting=gallery-previews&thumbsetting=gallery-thumbnails' ); ?>
				</section>
			</div>
			<div class="col right">
				<section class="inner product-info">
					<form action="<?php shopp( 'cart.url' ); ?>" method="post" class="shopp validate validation-alerts" id="pform">

						<?php //Product Information ?>

						<!--<a href="#" class="back-btn"><span class="icon"></span>Back to Battle</a>-->

						<h1 class="page-title shopp-title"><?php shopp( 'product.name' ); ?></h1>

						<div class="designer">
							<div class="profile">
								<?php echo $designer->get_avatar(74); ?>
								<strong><?php echo $designer->get_name(); ?></strong>
								<span class="location"><?php echo $designer->get_location(); ?></span>
							</div>
							<a href="<?php echo $designer->profile_url(); ?>" class="btn2 view-profile">View Profile</a>
						</div>

						<div class="description formatted">
							<?php shopp( 'product.description' ); ?>
						</div>

						<?php //Product options/variants ?>
						<div class="product-options">
							<div class="row">

								<?php shopp('product', 'variations', 'mode=multiple&before_menu=<div class="option">&after_menu=</div>'); ?>

								<?php //Qty ?>
								<div class="option">
									<label class="" data-label="Quantity">Quantity</label>
									<select name="products[<?php shopp('product.id'); ?>][quantity]">
										<?php for( $i=1; $i<=100; $i++ ) : ?>
											<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
										<?php endfor; ?>
									</select>
								</div>

							</div>

						</div>

						<?php //Product Price ?>
						<div class="price" id="price">
							<span class="amt"><?php shopp( 'product.price', 'starting=<span>From</span>' ); ?></span>
							<?php shopp( 'product', 'add-to-cart', array('class' => 'btn2 add-to-cart') ); ?>
						</div>

						<?php //notes (static) ?>
						<div class="notes formatted">
							<small><?php shopp('product.summary'); ?></small>
						</div>

						<?php //Share ?>
						<div class="share">
							<div class="small-headline">
								<span class="label">Share</span>
							</div>
							<div class="icons">
								<a href="https://www.facebook.com/sharer/sharer.php?u=<?php shopp('product.url'); ?>" class="fb fb-share-button" title="Share on FB" data-href="<?php shopp('product.url'); ?>" target="_blank"></a>
								<a href="https://twitter.com/home?status=Checkout%20this%20shirt%20design%20at%20<?php shopp('product.url'); ?>" class="tw" title="Share on Twitter" target="_blank"></a>
								<a href="https://pinterest.com/pin/create/button/?url=<?php shopp('product.url'); ?>&media=&description=" class="pt" title="Share on Pintrest" target="_blank"></a>
							</div>
						</div>

					</form>
				</section>
			</div>
		</div>

		<?php //Related Products ?>
		<section class="related-products">
			<h3 class="page-title">You May Also Like</h3>
			<div class="related">
				<?php shopp('storefront.related-products', 'show=3'); ?>
			</div>
		</section>

	<?php else : ?>
		<h3><?php _e( 'Product Not Found', 'Shopp' ); ?></h3>
		<p><?php _e( 'Sorry! The product you requested is not found in our catalog!', 'Shopp' ); ?></p>
	<?php endif; ?>

</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {

		//product options
		$('#pform .product-options .option').customSelect(function(ele, label) {
			ele.html(label);

			if( label.indexOf('Size') != -1 ) {
				if( label.indexOf('22') !== -1 ) {
					$('#price .amt').html('$22.00');
				}else {
					$('#price .amt').html('$20.00');
				}
			}

		});

		//gallery images based on color selection
		$(function() {

			var colors = JSON.parse( '<?php echo get_all_colors_as_json_data(); ?>' ),
				thumbs = $('.gallery .thumbnails>li');

			//expects the name of a color
			var showImagesByColor = function( color ) {

				//reset gallery thumbs
				thumbs.removeClass('active');

				//find color code
				var code = false;

				$.each(colors, function(hex, name) {
					if( name == color )
						code = hex;

					if( code )
						return true;
				});

				//find gallery images with code in filename
				thumbs.each(function() {
					var src = $(this).find('img').attr('src');

					//check for existence of code, and add active class if found
					if( src.indexOf(code) != -1 )
						$(this).addClass('active');
				});

				//trigger click on first found li
				$(thumbs).filter('.active:first').trigger('click');

			};

			//color select
			$('#options-2').on('change', function(evt) {
				var selected = $(this).find('option:selected'),
					color = selected.text();

				showImagesByColor( color );
			});

			//init
			$('#options-2').trigger('change');

		});

	});
</script>


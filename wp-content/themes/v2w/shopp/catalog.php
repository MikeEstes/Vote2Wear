<div class="shopp-header">
	<h1 class="shopp-title">Shop</h1>
	<div class="divider"></div>
	<div class="slogan formatted">
		Check out these awesome winning<br /> shirts you’ll never find anywhere else!
	</div>
</div>

<div class="center">

	<div class="cols catalog">
		<div class="col left">
			<div class="inner categories">

				<?php //Category Sidebar ?>
				<div class="sidebar">
					<h3 class="headline">Categories</h3>
					<?php shopp('storefront.categories', 'return=1'); ?>
					<?php if(shopp('storefront', 'has-categories')) : ?>
						<ul class="categories">
							<?php while(shopp('storefront', 'categories')) : ?>
								<li class="category" id="category-<?php shopp('category.id'); ?>">
									<a href="<?php shopp('category.url'); ?>" class="url name"><?php shopp('category.name'); ?></a>
								</li>
							<?php endwhile; ?>
						</ul>
					<?php endif; ?>

					<!--<a href="<?php shopp('storefront.url'); ?>categories" class="view-all">View All Categories</a>-->

				</div>

			</div>
		</div>
		<div class="col right">
			<div class="inner product-list">

			 	<?php //Catalog Products ?>
			 	<?php $c = 0; ?>
				<?php shopp( 'storefront.catalog-products', 'return=1' ); ?>
				<?php if( shopp('collection', 'load-products') ) : ?>
					<div class="text-center btnShop "><p><?php shopp( 'collection.pagination', 'show=5&before=<div class="alignright">&label=' ); ?><br/></p></div>
					<ul class="products">
						<?php while( shopp('collection', 'products') ) : ?>
							<?php $product_id = shopp('product.id', 'return=1'); ?>

							<li class="product <?php if( $c%3 == 0 ) echo 'first'; ?>" id="product-<?php shopp('product.id'); ?>" itemscope itemtype="http://schema.org/Product">
								<div class="inside">

									<a href="<?php shopp('product.url'); ?>" class="coverimage" itemprop="url">
										<?php shopp('product', 'coverimage', 'setting=thumbnails'); ?>
									</a>

									<h4 class="name">
										<a href="<?php shopp('product.url'); ?>" itemprop="url">
											<span class="itemprop"><?php shopp('product.name'); ?></span>
										</a>
									</h4>

									<div class="designer">
										<?php $designer = get_field('designer', $product_id); ?>
										<?php echo $designer['user_nicename']; ?>
									</div>

								</div>
							</li>

							<?php $c++; ?>
						<?php endwhile; ?>
					</ul>
					<div class="text-center btnShop "><br/><p><?php shopp( 'collection.pagination', 'show=5&before=<div class="alignright">&label=' ); ?></p></div>
				<?php else : ?>
					Huh?
				<?php endif; ?>

			</div>
		</div>
	</div>

</div>
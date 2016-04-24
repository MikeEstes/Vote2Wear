<?php get_header(); ?>

<div class="content" id="shopp">

<div class="shopp-header">
	<h1 class="shopp-title">Search Results</h1>
	<div class="divider"></div>
	<div class="slogan formatted">
		Results For "<?php the_search_query(); ?>" Shown Below
	</div>
</div>

<main class="search">
    <div class="center">
    
        <div class="category">
            
			<?php if(have_posts()) : ?>
            
            <div class="product-list">
            	<ul class="products">
                
                <?php $shopcount = 0; ?>
            
            <?php while(have_posts()) : the_post(); ?>
            
            	<?php if( $post->post_type == 'shopp_product' ) {
				ShoppProduct( shopp_product($post->ID) ); ?>
        
        			<?php $shopcount++; ?>
        			
                    <li class="product">
                        <div class="frame">
                            <a href="<?php shopp( 'product.url' ); ?>" itemprop="url"><?php shopp( 'product.coverimage', 'setting=thumbnails&itemprop=image' ); ?></a>
                            <div class="details">
                                <h4 class="name">
                                    <a href="<?php shopp( 'product.url' ); ?>"><span itemprop="name"><?php shopp( 'product.name' ); ?></span></a>
                                </h4>
                                <p class="price" itemscope itemtype="http://schema.org/Offer"><span itemprop="price"><?php shopp( 'product.saleprice', 'starting=' . __( 'from', 'Shopp' ) ); ?></span></p>
                                <?php if ( shopp( 'product.has-savings' ) ) : ?>
                                    <p class="savings"><?php _e( 'Save ', 'Shopp' ); ?><?php shopp( 'product.savings', 'show=percent' ); ?></p>
                                <?php endif; ?>
    
                                <div class="listview">
                                    <p><span itemprop="description"><?php shopp( 'product.summary' ); ?></span></p>
                                    <form action="<?php shopp( 'cart.url' ); ?>" method="post" class="shopp product">
                                        <?php shopp( 'product.addtocart' ); ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                
                <?php } ?>
            
            <?php endwhile; ?>
            
            </ul>
            </div>
            
            <?php else: ?>
            
                <div class="no-results-title">Sorry, no results were found.</div>
            
            <?php endif; ?>
            
            <?php $allsearch = &new WP_Query("s=$s&showposts=-1");
				  $count = $allsearch->post_count; _e(''); ?>
            
            <?php if($shopcount == 0 && $count != 0) { ?>
            
				<div class="no-results-title">Sorry, no results were found.</div>
                
            <?php } ?>                
               
        </div>
    
    </div>
</main>
<?php //end content ?>
</div>

<?php get_footer(); ?>
<?php

$product = array_shift(apiGetRequest('products/?lang=' . pll_current_language() . '&limit=4' ));

 ?>
 	<br class="clear"/>
  	<div class="tour_related">
	<h3 class="sub_title"><?php pll_e('You may also like'); ?></h3>
	<div id="portfolio_filter_wrapper" class="gallery classic <?php echo esc_attr('four_cols'); ?> portfolio-content section content clearfix" data-columns="<?php echo esc_attr(4); ?>">

	<?php

	$lat = $product['latitude'];
	$long = $product['longitude'];
	
	 echo do_shortcode("[product-list limit=4 button=hide lat=$lat long=$long range=30 ]");

    ?>
    </div>
  	</div>

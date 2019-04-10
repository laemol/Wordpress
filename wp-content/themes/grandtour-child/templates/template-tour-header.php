<?php

// Get current product
$product = array_shift(apiGetRequest('products/' . get_query_var('pid')));

$image_thumb = [$product['media'][0]['imageUrl']];

//Get page header display setting
$page_menu_transparent = 0;

//Get tour header option
$tg_tour_single_header = kirki_get_option('tg_tour_single_header');

if (isset($product['media'])) {
    if (isset($image_thumb[0]) && !empty($image_thumb[0])) {
        $pp_page_bg = $image_thumb[0];
        $page_menu_transparent = 1;
    }

    $grandtour_topbar = grandtour_get_topbar();
    $grandtour_screen_class = grandtour_get_screen_class(); ?>

<div id="page_caption" class="hasbg">
<div class="standard_wrapper">

<?php 
$tour_detail_header = false;

if($tour_detail_header){
    ?>
<div class="single_tour_header_info">
    
<?php

if ($product) {
            ?>

			<?php

                    ?>
				<div class="<?php echo esc_attr($tour_attribute_class); ?>">

					<div class="tour_attribute_content">
                    <span class="tour_attribute_icon ti-time"></span>
					<?php
                        // Display tour duration
                        echo $product['duration']; ?> 
                        <?php if (empty($product['duration'])) {
                            echo 'All Day';
                        } else {
                            if (!strpos($product['duration'], 'hours')) {
                                esc_html_e('Hours', 'grandtour');
                            }
                        } ?>
					</div>
				</div>
			
			<?php
                if (!empty($product['ageDescription'])) {
                    ?>
				<div class="<?php echo esc_attr($tour_attribute_class); ?>">
					
					<div class="tour_attribute_content">
                    <span class="tour_attribute_icon ti-id-badge"></span>
                        <!-- <?php esc_html_e('Free', 'grandtour'); ?> -->
                        <?php 
                        // Display age description
                        echo $product['ageDescription']; ?>
					</div>
				</div>
			<?php
                } ?>
			
			<?php
                if (!empty($product['period'])) {
                    ?>
				<div class="<?php echo esc_attr($tour_attribute_class); ?>">
					
					<div class="tour_attribute_content">
                    <span class="tour_attribute_icon ti-calendar"></span>
                    <?php 
                    // Display period
                    echo date_i18n('M', strtotime($product['period']['start']));
                    echo ' - ';
                    echo date_i18n('M', strtotime($product['period']['end'])); ?>
					</div>
				</div>
			<?php
                } ?>
			
			<?php
                if (!empty($product['status'])) {
                    if ($tour_attribute_class == 'one_fourth') {
                        $tour_attribute_class = 'one_fourth last';
                    } ?>
				<div class="<?php echo esc_attr($tour_attribute_class); ?>">
					
					<div class="tour_attribute_content">
                    <span class="tour_attribute_icon ti-home"></span>
						<?php echo $product['status'] ?>
					</div>
				</div>
			<?php
                } ?>
			
			<?php
                if ($tour_layout == 'Fullwidth') {
                    //Get tour price
                    $tour_price = get_post_meta($post->ID, 'tour_price', true);

                    if (!empty($tour_price)) {
                        $tour_discount_price = get_post_meta($post->ID, 'tour_discount_price', true); ?>
		 		<div class="<?php echo esc_attr($tour_attribute_class); ?> last" style="position:relative;">
			 		<?php
                        //Get tour label
                        $tour_label = get_post_meta($post->ID, 'tour_label', true);

                        if (!empty($tour_label)) {
                            ?>
					<div class="tour_label"><?php echo esc_html($tour_label); ?></div>
					<?php
                        } ?>
					<div class="tour_attribute_icon ti-money"></div>
					<div class="tour_attribute_content">
						<div class="single_tour_price <?php echo esc_attr(strtolower($tour_layout)); ?>">
						<?php
                        if ($product) {
                            ?>
		 					<span class="normalPrice">
		 						<?php echo $product['prices'][0]['originalPrice']; ?>
		 					</span>
		 					<?php echo $product['prices'][0]['discountPrice']; ?>
		 				<?php
                        } else {
                            ?>
		 					<?php echo $product['prices'][0]['discountPrice']; ?>
		 				<?php
                        } ?>
						</div>
					</div>
				</div>
		 	<?php
                    }
                } ?>
	<?php
        }
    ?>

        </div>

<?php

    }

    ?>
        
<!-- Slideshow container -->
<div class="slideshow-container">

<!-- Images with number and caption text -->
<?php 
foreach ($product['media'] as $image) {
    ?>
<div class="mySlides fade">
  <img src="<?php echo esc_url($image['imageUrl']); ?>" style="width:100%">
</div>

<?php
} 
?>

<?php if($product['video']){
    echo '<div class="mySlides fade">';
    $embed_code = wp_oembed_get($product['video'], ['width' => 800]); 
    echo $embed_code;
    echo '</div>';
}
?> 

<!-- Next and previous buttons -->
<a class="prevslide nav" onclick="plusSlides(-1)"><div class="ti-angle-left"></div></a>
<a class="nextslide nav" onclick="plusSlides(1)"><div class="ti-angle-right"></div></a>
<!-- The dots/circles -->
<div class="dots">
<?php 
$slide = 1;
    foreach ($product['media'] as $image) {
        ?>
<span class="dot" onclick="currentSlide($slide)"></span> 
<?php 
$slide++ ;
    } ?>
</div>
</div>

</div>

	<div class="single_tour_header_content">
		<div class="standard_wrapper">

	<?php

    if (!empty($product['prices'])) {
        ?>
			<div class="single_tour_header_price">
				<div class="single_tour_price">
					<?php
                    if ($product['prices'][0]['currentPrice'] < $product['prices'][0]['originalPrice']) {
                        ?>
						<span class="normal_price">
                        &euro;	<?php echo $product['prices'][0]['originalPrice']; ?>
						</span>
						&euro;	<?php echo $product['prices'][0]['currentPrice']; ?>
					<?php
                    } else {
                        ?>
                        &euro;	<?php echo $product['prices'][0]['currentPrice']; ?> 
					<?php
                    } ?>
				</div>
				<div class="single_tour_per_person">
					<?php pll_e('Per Person'); ?> 
				</div>
			</div>
			<?php
    } ?>
		</div>
	</div>
</div>
<?php
}
?>

<!-- Begin content -->
<?php
    $grandtour_page_content_class = grandtour_get_page_content_class();
?>
<div id="page_content_wrapper" class="<?php if (!empty($pp_page_bg)) {
    ?>hasbg <?php
} ?><?php if (!empty($pp_page_bg) && !empty($grandtour_topbar)) {
        ?>withtopbar <?php
    } ?><?php if (!empty($grandtour_page_content_class)) {
        echo esc_attr($grandtour_page_content_class);
    } ?>">

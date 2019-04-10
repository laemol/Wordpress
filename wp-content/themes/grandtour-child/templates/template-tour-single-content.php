<?php
    // Get current product

    $product = array_shift(apiGetRequest('products/' . get_query_var('pid'). '?lang=' . pll_current_language()));

?>

<div class="sidebar_content <?php if ($tour_layout == 'Fullwidth') {
    ?>full_width<?php
} ?>">

<!-- Slideshow container -->
<br>
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

	<?php
        //If single tour fullwidth layout, display title
        if ($tour_layout != 'Fullwidth') {
            ?>
		<h1><?php echo $product['name'] ?> </h1>
		
		<?php
            //Get tour label
            $tour_label = get_post_meta($post->ID, 'tour_label', true);

            if (!empty($tour_label)) {
                ?>
		<div class="tour_label sidebar"><?php echo esc_html($tour_label); ?></div>
		<?php
            } ?>
	<?php
            //Display tour tagline
            if (!empty($page_tagline)) {
                ?>
			<div class="page_tagline">
				<?php echo nl2br($page_tagline); ?>
			</div>
	<?php
            }
        }

        //Display tour attributes
        $tour_attribute_class = 'one_fourth';
        if ($tour_layout == 'Fullwidth') {
            $tour_attribute_class = 'one_fifth';
        }

    ?>
    <!-- Start main content -->

		<div class="single_tour_content">

            <?php echo $product['description']; ?>
            
		</div>

	<?php
        //If single tour fullwidth layout, display book, share buttons
        if ($tour_layout == 'Fullwidth') {
            ?>
		<br class="clear"/><div class="single_tour_after_content_wrapper">
	<?php

        //Include tour booking form
        get_template_part('/templates/template-booking-form'); ?>

	<?php
        //Check if enable tour sharing
        $tg_tour_single_share = kirki_get_option('tg_tour_single_share');

            if (!empty($tg_tour_single_share)) {
                ?>
 	<a id="single_tour_share_button" href="javascript:;" class="button ghost themeborder"><span class="ti-email"></span><?php pll_e('Share');?></a>
 	<?php
            } ?>
		</div>
	<?php
        }
    ?>
	
	<?php
        //Display tour departure information
        $tour_departure = get_post_meta($post->ID, 'tour_departure', true);
        $tour_departure_time = get_post_meta($post->ID, 'tour_departure_time', true);
        $tour_return_time = get_post_meta($post->ID, 'tour_return_time', true);
        $tour_included = $product['details'];
        $tour_not_included = $product['details'];
        $tour_usp = $product['details'];
        $tour_map_address = $product['address'];
    ?>
	<ul class="single_tour_departure_wrapper themeborder">
		<?php
            if ($product['type'] == 'tour') {
                ?>
		<!-- <li>
			<div class="single_tour_departure_title"><?php esc_html_e('Departure', 'grandtour'); ?></div>
			<div class="single_tour_departure_content"><?php echo $product['startTime']; ?></div>
		</li> -->
		<?php
            }
        ?>
		
		<?php
            if (!empty($tour_departure_time)) {
                ?>
		<li>
			<div class="single_tour_departure_title"><?php esc_html_e('Departure Time', 'grandtour'); ?></div>
			<div class="single_tour_departure_content indent_content"><?php echo $product['startTime']; ?></div>
		</li>
		<?php
            }
        ?>
		
		<?php
            if (!empty($tour_return_time)) {
                ?>
		<li>
			<div class="single_tour_departure_title"><?php esc_html_e('Return Time', 'grandtour'); ?></div>
			<div class="single_tour_departure_content indent_content"><?php echo $product['endTime']; ?></div>
		</li>
		<?php
            }
        ?>
		
		<?php
            if (!empty($tour_included)) {
                ?>
		<li>
			<div class="single_tour_departure_title"><?php pll_e('Benefits'); ?></div>
			<div class="single_tour_departure_content indent_content">
				<?php
                    if (!empty($tour_included) && is_array($tour_included)) {
                        foreach ($tour_included as $key => $tour_included_item) {
                            $last_class = '';
                            if (($key + 1) % 2 == 0) {
                                $last_class = 'last';
                            }
                            if ($tour_included_item['key'] == 'usp') {
                                ?>
				<div>
					<span class="ti-plus"></span><?php echo esc_html($tour_included_item['value']); ?>
				</div>
                <?php
                            }
                        }
                    } ?>
			</div>
		</li>
		<?php
            }
        ?>

        <?php
            if (!empty($tour_usp)) {
                ?>
		<li>
			<div class="single_tour_departure_title"><?php pll_e('Included'); ?></div>
			<div class="single_tour_departure_content indent_content">
				<?php
                    if (!empty($tour_included) && is_array($tour_included)) {
                        foreach ($tour_included as $key => $tour_included_item) {
                            if ($tour_included_item['key'] == 'include') {
                                ?>
				<div>
					<span class="ti-check"></span><?php echo esc_html($tour_included_item['value']); ?>
				</div>
                <?php
                            }
                        }
                    } ?>
                <?php
                    if (!empty($tour_not_included) && is_array($tour_not_included)) {
                        foreach ($tour_not_included as $key => $tour_not_included_item) {
                            if ($tour_not_included_item['key'] == 'exclude') {
                                ?>
				<div>
					<span class="ti-close"></span><?php echo esc_html($tour_not_included_item['value']); ?>
				</div>
                <?php
                            }
                        }
                    } ?>
			</div>
		</li>
		<?php
            }
        ?>

		<?php
            if (!empty($tour_included)) {
                ?>
		<li>
			<div class="single_tour_departure_title"><?php pll_e('Description'); ?></div>
			<div class="single_tour_departure_content">

            <div class="txt-wrapper">
            <a href="#panel-show" class="panel-show txt" id="panel-show"><?php pll_e('Read More'); ?></a> 
            <a href="#panel-hide" class="panel-hide txt" id="panel-hide"><?php pll_e('Read Less'); ?></a> 
            <div class="txt-panel">
            <?php echo $product['webDescription'] ?>
            
            </div><!-- end panel -->
            <div class="txt-fade"></div>
            </div><!-- end panel-wrapper -->

			</div>
		</li>
		<?php
            }
        ?>
		
        <?php
     
            if (!empty($tour_map_address)) {
                $tg_tour_map_marker = kirki_get_option('tg_tour_map_marker'); ?>
		<li>
			<div class="single_tour_departure_title"><?php pll_e('Map'); ?></div>
			<div class="single_tour_departure_content">
                <?php echo do_shortcode('[pw_map address="' . esc_attr($tour_map_address) . '" key="' . GOOGLE_KEY . '"]'); ?></div>
		</li>
		<?php
            }

        if($reviews){
        ?>

        <li>
        <div class="single_tour_departure_title"><?php esc_html_e('Reviews', 'grandtour'); ?></div>
        <div class="single_tour_departure_content">
        <?php 
        $shortcode_reviews = '[site_reviews_summary assigned_to="' . $product['productId'] . '" hide="if_empty"]';
        echo do_shortcode($shortcode_reviews);
        echo '<br>';

        if (strlen(do_shortcode($shortcode_reviews)) > 65) {
            echo '<a href="javascript:;" class="button showall" onclick="toggleReviews()">Show Reviews</a><br><br><div id="reviews" style="display:none">';
            $shortcode_reviews = '[site_reviews assigned_to="' . $product['productId'] . '"]';
            echo do_shortcode($shortcode_reviews) . '</div>';
        } else {
            echo '<p><i>Be the first one to write a review!</i></p>';
        }
        $shortcode_reviews_form = '[site_reviews_form assign_to="' . $product['productId'] . '" hide="title"]';
        echo do_shortcode($shortcode_reviews_form);

        ?>
        </div>
        </li>

        <?php

    }
    ?>

	</ul>

	<?php
        //Check if enable tour review
        $tg_tour_single_review = kirki_get_option('tg_tour_single_review');

        ?>
        
    <?php

        //Display tour comment
        if (comments_open($post->ID) && !empty($tg_tour_single_review)) {
            ?>
		<div class="fullwidth_comment_wrapper sidebar">
			<?php comments_template('', true); ?>
		</div>
	<?php
        }
    ?>
		
</div>

<script>
function toggleReviews() {
  var x = document.getElementById("reviews");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}
</script>
<script>
    function readMore() {
  var dots = document.getElementById("dots");
  var moreText = document.getElementById("more");
  var btnText = document.getElementById("myBtn");

  if (dots.style.display === "none") {
    dots.style.display = "inline";
    btnText.innerHTML = "Read more"; 
    moreText.style.display = "none";
  } else {
    dots.style.display = "none";
    btnText.innerHTML = "Read less"; 
    moreText.style.display = "inline";
  }
}
</script>
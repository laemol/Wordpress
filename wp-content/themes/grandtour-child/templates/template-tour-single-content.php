<?php
    // Get current product
    $product = array_shift(apiGetRequest('products/' . $_GET['pid'] . '?lang=' . pll_current_language() ));
    
?>

<div class="sidebar_content <?php if ($tour_layout == 'Fullwidth') {
    ?>full_width<?php
} ?>">
					
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
        $tour_days = 99;
        $tour_minimum_age = $product['ageDescription'];
        $tour_months = get_post_meta($post->ID, 'tour_months', true);
        $tour_availability = get_post_meta($post->ID, 'tour_availability', true);

        $tour_attribute_class = 'one_fourth';
        if ($tour_layout == 'Fullwidth') {
            $tour_attribute_class = 'one_fifth';
        }

        if (!empty($tour_days) or !empty($tour_minimum_age) or !empty($tour_months) or !empty($tour_availability)) {
            ?>
		<div class="single_tour_attribute_wrapper themeborder <?php echo esc_attr(strtolower($tour_layout)); ?>">
			<?php
                if (!empty($tour_days)) {
                    ?>
				<div class="<?php echo esc_attr($tour_attribute_class); ?>">
					<div class="tour_attribute_icon ti-time"></div>
					<div class="tour_attribute_content">
					<?php
                        //Display tour durations
                        echo $product['duration']; ?> <?php esc_html_e('Hours', 'grandtour'); ?>
					</div>
				</div>
			<?php
                } ?>
			
			<?php
                if (!empty($tour_minimum_age)) {
                    ?>
				<div class="<?php echo esc_attr($tour_attribute_class); ?>">
					<div class="tour_attribute_icon ti-id-badge"></div>
					<div class="tour_attribute_content">
						<?php esc_html_e('Age', 'grandtour'); ?>
						<?php echo intval($tour_minimum_age) . '+'; ?>
					</div>
				</div>
			<?php
                } ?>
			
			<?php
                if (!empty($tour_months)) {
                    ?>
				<div class="<?php echo esc_attr($tour_attribute_class); ?>">
					<div class="tour_attribute_icon ti-calendar"></div>
					<div class="tour_attribute_content">
						<?php 
                            if (is_array($tour_months)) {
                                if (count($tour_months) == 12) {
                                    echo esc_html__('All Months', 'grandtour');
                                } else {
                                    $i = 0;
                                    $len = count($tour_months);
                                    foreach ($tour_months as $tour_month) {
                                        echo date_i18n('M', strtotime('1 ' . $tour_month . ' 2017'));

                                        if ($i != $len - 1) {
                                            echo ',&nbsp;';
                                        }

                                        $i++;
                                    }
                                }
                            } ?>
					</div>
				</div>
			<?php
                } ?>
			
			<?php
                if (!empty($tour_availability)) {
                    if ($tour_attribute_class == 'one_fourth') {
                        $tour_attribute_class = 'one_fourth last';
                    } ?>
				<div class="<?php echo esc_attr($tour_attribute_class); ?>">
					<div class="tour_attribute_icon ti-user"></div>
					<div class="tour_attribute_content">
						<?php esc_html_e('Availability', 'grandtour'); ?>
						<?php echo intval($tour_availability); ?>
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
		</div><br class="clear"/>
	<?php
        }

    ?>
    <!-- Start main content -->

		<div class="single_tour_content">

            <div class="sidebar-box">
            <?php echo $product['webDescription']; ?>
            <p class="read-more"><a href="#" class="button">Read More</a></p>
            </div>
         
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
 	<a id="single_tour_share_button" href="javascript:;" class="button ghost themeborder"><span class="ti-email"></span><?php esc_html_e('Share this tour', 'grandtour'); ?></a>
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
        $tour_map_address = $product['address']
    ?>
	<ul class="single_tour_departure_wrapper themeborder">
		<?php
            if (!empty($tour_departure)) {
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
			<div class="single_tour_departure_content"><?php echo $product['startTime']; ?></div>
		</li>
		<?php
            }
        ?>
		
		<?php
            if (!empty($tour_return_time)) {
                ?>
		<li>
			<div class="single_tour_departure_title"><?php esc_html_e('Return Time', 'grandtour'); ?></div>
			<div class="single_tour_departure_content"><?php echo $product['endTime']; ?></div>
		</li>
		<?php
            }
        ?>
		
		<?php
            if (!empty($tour_included)) {
                ?>
		<li>
			<div class="single_tour_departure_title"><?php esc_html_e('Benefits', 'grandtour'); ?></div>
			<div class="single_tour_departure_content">
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
			<div class="single_tour_departure_title"><?php esc_html_e('Included', 'grandtour'); ?></div>
			<div class="single_tour_departure_content">
				<?php
                    if (!empty($tour_included) && is_array($tour_included)) {
                        foreach ($tour_included as $key => $tour_included_item) {
                            // $last_class = '';
                            // if (($key + 1) % 2 == 0) {
                            //     $last_class = 'last';
                            // }
                            if ($tour_included_item['key'] == 'include') {
                                ?>
				<div>
					<span class="ti-check"></span><?php echo esc_html($tour_included_item['value']); ?>
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
			<div class="single_tour_departure_title"><?php esc_html_e('Not Included', 'grandtour'); ?></div>
			<div class="single_tour_departure_content">
				<?php
                    if (!empty($tour_not_included) && is_array($tour_not_included)) {
                        foreach ($tour_not_included as $key => $tour_not_included_item) {
                            $last_class = '';
                            if (($key + 1) % 2 == 0) {
                                $last_class = 'last';
                            }
                            if ($tour_not_included_item['key'] == 'exclude') {
                                ?>
				<div class="one_half <?php echo esc_attr($last_class); ?>">
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
            if (!empty($tour_map_address)) {
                $tg_tour_map_marker = kirki_get_option('tg_tour_map_marker'); ?>
		<li>
			<div class="single_tour_departure_title"><?php esc_html_e('Maps', 'grandtour'); ?></div>
			<div class="single_tour_departure_content"><?php echo do_shortcode('[tg_map width="1000" height="300" address="' . esc_attr($tour_map_address) . '" zoom="13" marker="' . esc_url($tg_tour_map_marker) . '"]'); ?></div>
		</li>
		<?php
            }
        ?>
	</ul>

	<?php
        //Check if enable tour review
        $tg_tour_single_review = kirki_get_option('tg_tour_single_review');

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
    var $el, $ps, $up, totalHeight;

jQuery(".sidebar-box .button").click(function() {
      
  totalHeight = 0

  $el = jQuery(this);
  $p  = $el.parent();
  $up = $p.parent();
  $ps = $up.find("p:not('.read-more')");
  
  // measure how tall inside should be by adding together heights of all inside paragraphs (except read-more paragraph)
  $ps.each(function() {
    totalHeight += jQuery(this).outerHeight();
  });
        
  $up
    .css({
      // Set height to prevent instant jumpdown when max height is removed
      "height": $up.height(),
      "max-height": 9999
    })
    .animate({
      "height": totalHeight
    });
  
  // fade out read-more
  $p.fadeOut();
  
  // prevent jump-down
  return false;
    
});
</script>
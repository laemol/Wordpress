<div class="sidebar_wrapper">
    	
 <div class="sidebar_top"></div>

 <div class="sidebar">
 
 	<div class="content">
 		
		 <?php

         // Get Product data
		 $product = array_shift(apiGetRequest('products/' . get_query_var('pid')));
		
            if (!empty($product['prices'])) {
				$tour_discount_price = get_post_meta($post->ID, 'tour_discount_price', true); ?>
				<br>
 		<div class="single_tour_header_price">
 			<div class="single_tour_price">
 				<?php
                if ($product) {
                    ?>
					 <span class="normal_price">
					 &euro;  <?php echo $product['prices'][0]['originalPrice']; ?>
					 </span>
					 &euro; <?php echo $product['prices'][0]['currentPrice']; ?>
				 <?php
                } else {
                    ?>
					 &euro; <?php echo $product['prices'][0]['currentPrice']; ?>
				 <?php
                } ?>
 			</div>
 			<div class="single_tour_per_person">
 				<?php esc_html_e('Per Person', 'grandtour'); ?>
 			</div>
 		</div>
 		<?php
            }
        ?>

 		<?php
            //Get tour booking method
            $tour_booking_method = get_post_meta($post->ID, 'tour_booking_method', true);
        ?>
 		<div class="single_tour_booking_wrapper themeborder <?php echo esc_attr($tour_booking_method); ?>">
 			<?php

						get_template_part("/templates/template-booking-form");	

                    $tour_view_count = grandtour_get_post_view($post->ID, true);
                    if ($tour_view_count > 0) {
                        ?>
     		<div class="single_tour_view_wrapper themeborder">
     			<div class="single_tour_view_desc">
 	    			<?php esc_html_e("This tour's been viewed", 'grandtour'); ?>&nbsp;<?php echo number_format($tour_view_count); ?>&nbsp;<?php esc_html_e('times in the past week', 'grandtour'); ?>
     			</div>
     			
     			<div class="single_tour_view_icon ti-alarm-clock"></div>
     		</div>
     		<br class="clear"/>
     		<?php
                    }
                ?>
 		</div>
 		
 		<?php
            //Check if enable tour sharing
            $tg_tour_single_share = kirki_get_option('tg_tour_single_share');

            if (!empty($tg_tour_single_share)) {
                ?>
 		<a id="single_tour_share_button" href="javascript:;" class="button ghost themeborder"><span class="ti-email"></span><?php pll_e('Share'); ?></a>
 		<?php
            }
        ?>
 		
 		<?php 
            if (is_active_sidebar('single-tour-sidebar')) {
                ?>
    	    	<ul class="sidebar_widget">
    	    	<?php dynamic_sidebar('single-tour-sidebar'); ?>
    	    	</ul>
    	    <?php
            } ?>
 		
 		<?php 
            if (function_exists('users_online') && !isset($_COOKIE['grandtour_users_online'])): ?>
 		   <div class="single_tour_users_online_wrapper themeborder">
 			   <div class="single_tour_users_online_icon">
 				   	<span class="ti-info-alt"></span>
 			   </div>
 			   <div class="single_tour_users_online_content">
 			   		<?php users_online(); ?>
 			   </div>
 		   </div>
 		<?php endif; ?>
 	
 	</div>

 </div>
 <br class="clear"/>

 <div class="sidebar_bottom"></div>
</div>
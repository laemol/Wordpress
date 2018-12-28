<?php
/**
 * The template for displaying the footer.
 *
 * @package WordPress
 */
 
?>

<?php
$tg_tour_recently_view = kirki_get_option('tg_tour_recently_view');
    
if(!empty($tg_tour_recently_view))
 {
	$recently_view_tours = grandtour_get_recently_view_tours();
	
	if(!empty($recently_view_tours) && is_array($recently_view_tours))
	{
 ?>
 	<br class="clear"/>
  	<div class="tour_recently_view">
	  	
	<div class="standard_wrapper">
		
	<h3 class="sub_title"><?php echo esc_html_e('Recently Viewed Tours', 'grandtour' ); ?></h3>
	
	<div id="portfolio_filter_wrapper" class="gallery grid four_cols portfolio-content section content clearfix" data-columns="4">
    <?php
	    $count_tour = 1;
		foreach($recently_view_tours as $key => $recently_view_tour)
		{
	       	$image_url = '';
			$tour_ID = $recently_view_tour;
					
			if(has_post_thumbnail($tour_ID, 'grandtour-gallery-grid'))
			{
			    $image_id = get_post_thumbnail_id($tour_ID);
			    $small_image_url = wp_get_attachment_image_src($image_id, 'grandtour-gallery-grid', true);
			}
			
			$permalink_url = get_permalink($tour_ID);
    ?>
    <div class="element grid classic4_cols animated<?php echo esc_attr($key+1); ?>">
	
		<div class="one_fourth gallery4 grid static filterable portfolio_type themeborder" data-id="post-<?php echo esc_attr($key+1); ?>" style="background-image:url(<?php echo esc_url($small_image_url[0]); ?>);">	
			<a class="tour_image" href="<?php echo esc_url($permalink_url); ?>"></a>	
			<div class="portfolio_info_wrapper">
				<?php
					//Get tour price
					$tour_price = get_post_meta($tour_ID, 'tour_price', true);
					
					if(!empty($tour_price))
					{
						$tour_discount_price = get_post_meta($tour_ID, 'tour_discount_price', true);
				?>
				<div class="tour_price <?php if(!empty($tour_discount_price)) { ?>has_discount<?php } ?>">
					<?php
					if(!empty($tour_discount_price))
					{
					?>
						<span class="normal_price">
							<?php echo esc_html(grandtour_format_tour_price($tour_price)); ?>
						</span>
						<?php echo esc_html(grandtour_format_tour_price($tour_discount_price)); ?>
					<?php
					}
					else
					{
					?>
						<?php echo esc_html(grandtour_format_tour_price($tour_price)); ?>
					<?php
					}
					?>
				</div>
				<?php
					}
				?>
        	    <h5><?php echo get_the_title($tour_ID); ?></h5>
        	    <div class="tour_attribute_wrapper">
	        	    <?php
		    			$overall_rating_arr = grandtour_get_review($tour_ID, 'overall_rating');
						$overall_rating = intval($overall_rating_arr['average']);
						$overall_rating_count = intval($overall_rating_arr['count']);
						
						if(!empty($overall_rating))
						{
				?>
						<div class="tour_attribute_rating">
				<?php
							if($overall_rating > 0)
							{
				?>
							<div class="br-theme-fontawesome-stars-o">
								<div class="br-widget">
				<?php
								for( $i=1; $i <= $overall_rating; $i++ ) {
									echo '<a href="javascript:;" class="br-selected"></a>';
								}
								
								$empty_star = 5 - $overall_rating;
								
								if(!empty($empty_star))
								{
									for( $i=1; $i <= $empty_star; $i++ ) {
										echo '<a href="javascript:;"></a>';
									}
								}
					?>
								</div>
							</div>
					<?php
							}
							
							if($overall_rating_count > 0)
							{
					?>
							<div class="tour_attribute_rating_count">
								<?php echo intval($overall_rating_count); ?>&nbsp;
								<?php
									if($overall_rating_count > 1)
									{
										echo esc_html__('reviews', 'grandtour' );
									}
									else
									{
										echo esc_html__('review', 'grandtour' );
									}
								?>
							</div>
					<?php
							}
					?>
						</div>
					<?php
						}    
		    		?>
		    		
		    		<?php
						$tour_days = get_post_meta($tour_ID, 'tour_days', true);	
						
						if(!empty($tour_days))
						{
					?>
		    		    <div class="tour_attribute_days">
						    <span class="ti-time"></span>
						    <?php
								//Display tour durations
										echo grandtour_get_tour_duration($tour_ID);
							?>
		    		    </div>
		    		<?php
						}
					?>
        	    </div>
        	    <br class="clear"/>
			</div>
		</div>
	</div>
    <?php
	    	$count_tour++;
	    	
	    	if($count_tour > 4)
	    	{
		    	break;
	    	}
	 	}
    ?>
    </div>
  	</div>
  	</div>
<?php
  	}
} //end if show recently view tours
?>

<?php
	//Check if blank template
	$grandtour_is_no_header = grandtour_get_is_no_header();
	$grandtour_screen_class = grandtour_get_screen_class();
	
	if(!is_bool($grandtour_is_no_header) OR !$grandtour_is_no_header)
	{

	$grandtour_homepage_style = grandtour_get_homepage_style();
	
	$tg_footer_sidebar = kirki_get_option('tg_footer_sidebar');
?>

<?php
    if(!empty($tg_footer_sidebar) && $grandtour_homepage_style != 'fullscreen' && $grandtour_homepage_style != 'fullscreen_white' && $grandtour_homepage_style != 'split')
    {
    	$footer_class = '';
    	
    	switch($tg_footer_sidebar)
    	{
    		case 1:
    			$footer_class = 'one';
    		break;
    		case 2:
    			$footer_class = 'two';
    		break;
    		case 3:
    			$footer_class = 'three';
    		break;
    		case 4:
    			$footer_class = 'four';
    		break;
    		default:
    			$footer_class = 'four';
    		break;
    	}
?>
<div id="footer" class="<?php if(isset($grandtour_homepage_style) && !empty($grandtour_homepage_style)) { echo esc_attr($grandtour_homepage_style); } ?> <?php if(!empty($grandtour_screen_class)) { echo esc_attr($grandtour_screen_class); } ?>">
<ul class="sidebar_widget <?php echo esc_attr($footer_class); ?>">
    <?php dynamic_sidebar('Footer Sidebar'); ?>
</ul>
</div>
<?php
    }
?>

<?php	
	//If display photostream
	$pp_photostream = get_option('pp_photostream');
	if(GRANDTOUR_THEMEDEMO && isset($_GET['footer']) && !empty($_GET['footer']))
	{
		$pp_photostream = 0;
	}

	if(!empty($pp_photostream) && $grandtour_homepage_style != 'fullscreen' && $grandtour_homepage_style != 'fullscreen_white' && $grandtour_homepage_style != 'split')
	{
		$photos_arr = array();
	
		if($pp_photostream == 'flickr')
		{
			$pp_flickr_id = get_option('pp_flickr_id');
			$photos_arr = grandtour_get_flickr(array('type' => 'user', 'id' => $pp_flickr_id, 'items' => 30));
		}
		else
		{
			$pp_instagram_username = get_option('pp_instagram_username');
			$pp_instagram_access_token = get_option('pp_instagram_access_token');
			$photos_arr = grandtour_get_instagram($pp_instagram_username, $pp_instagram_access_token, 30);
		}
		
		if(!empty($photos_arr) && $grandtour_screen_class != 'split' && $grandtour_screen_class != 'split wide' && $grandtour_homepage_style != 'fullscreen' && $grandtour_homepage_style != 'flow')
		{
?>
<br class="clear"/>
<div id="footer_photostream" class="footer_photostream_wrapper ri-grid ri-grid-size-3">
	<ul>
		<?php
			foreach($photos_arr as $photo)
			{
		?>
			<li><a target="_blank" href="<?php echo esc_url($photo['link']); ?>"><img src="<?php echo esc_url($photo['thumb_url']); ?>" alt="" /></a></li>
		<?php
			}
		?>
	</ul>
</div>
<?php
		}
	}
?>

<?php
if($grandtour_homepage_style != 'fullscreen' && $grandtour_homepage_style != 'fullscreen_white' && $grandtour_homepage_style != 'split')
{
	//Get Footer Sidebar
	$tg_footer_sidebar = kirki_get_option('tg_footer_sidebar');
	if(GRANDTOUR_THEMEDEMO && isset($_GET['footer']) && !empty($_GET['footer']))
	{
	    $tg_footer_sidebar = 0;
	}
?>
<div class="footer_bar <?php if(isset($grandtour_homepage_style) && !empty($grandtour_homepage_style)) { echo esc_attr($grandtour_homepage_style); } ?> <?php if(!empty($grandtour_screen_class)) { echo esc_attr($grandtour_screen_class); } ?> <?php if(empty($tg_footer_sidebar)) { ?>noborder<?php } ?>">

	<div class="footer_bar_wrapper <?php if(isset($grandtour_homepage_style) && !empty($grandtour_homepage_style)) { echo esc_attr($grandtour_homepage_style); } ?>">
		<?php
			//Check if display social icons or footer menu
			$tg_footer_copyright_right_area = kirki_get_option('tg_footer_copyright_right_area');
			
			if($tg_footer_copyright_right_area=='social')
			{
				if($grandtour_homepage_style!='flow' && $grandtour_homepage_style!='fullscreen' && $grandtour_homepage_style!='carousel' && $grandtour_homepage_style!='flip' && $grandtour_homepage_style!='fullscreen_video')
				{	
					//Check if open link in new window
					$tg_footer_social_link = kirki_get_option('tg_footer_social_link');
			?>
			<div class="social_wrapper">
			    <ul>
			    	<?php
			    		$pp_facebook_url = get_option('pp_facebook_url');
			    		
			    		if(!empty($pp_facebook_url))
			    		{
			    	?>
			    	<li class="facebook"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> href="<?php echo esc_url($pp_facebook_url); ?>"><i class="fa fa-facebook-official"></i></a></li>
			    	<?php
			    		}
			    	?>
			    	<?php
			    		$pp_twitter_username = get_option('pp_twitter_username');
			    		
			    		if(!empty($pp_twitter_username))
			    		{
			    	?>
			    	<li class="twitter"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> href="http://twitter.com/<?php echo esc_attr($pp_twitter_username); ?>"><i class="fa fa-twitter"></i></a></li>
			    	<?php
			    		}
			    	?>
			    	<?php
			    		$pp_flickr_username = get_option('pp_flickr_username');
			    		
			    		if(!empty($pp_flickr_username))
			    		{
			    	?>
			    	<li class="flickr"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> title="Flickr" href="http://flickr.com/people/<?php echo esc_attr($pp_flickr_username); ?>"><i class="fa fa-flickr"></i></a></li>
			    	<?php
			    		}
			    	?>
			    	<?php
			    		$pp_youtube_url = get_option('pp_youtube_url');
			    		
			    		if(!empty($pp_youtube_url))
			    		{
			    	?>
			    	<li class="youtube"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> title="Youtube" href="<?php echo esc_url($pp_youtube_url); ?>"><i class="fa fa-youtube"></i></a></li>
			    	<?php
			    		}
			    	?>
			    	<?php
			    		$pp_vimeo_username = get_option('pp_vimeo_username');
			    		
			    		if(!empty($pp_vimeo_username))
			    		{
			    	?>
			    	<li class="vimeo"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> title="Vimeo" href="http://vimeo.com/<?php echo esc_attr($pp_vimeo_username); ?>"><i class="fa fa-vimeo-square"></i></a></li>
			    	<?php
			    		}
			    	?>
			    	<?php
			    		$pp_tumblr_username = get_option('pp_tumblr_username');
			    		
			    		if(!empty($pp_tumblr_username))
			    		{
			    	?>
			    	<li class="tumblr"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> title="Tumblr" href="http://<?php echo esc_attr($pp_tumblr_username); ?>.tumblr.com"><i class="fa fa-tumblr"></i></a></li>
			    	<?php
			    		}
			    	?>
			    	<?php
			    		$pp_google_url = get_option('pp_google_url');
			    		
			    		if(!empty($pp_google_url))
			    		{
			    	?>
			    	<li class="google"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> title="Google+" href="<?php echo esc_url($pp_google_url); ?>"><i class="fa fa-google-plus"></i></a></li>
			    	<?php
			    		}
			    	?>
			    	<?php
			    		$pp_dribbble_username = get_option('pp_dribbble_username');
			    		
			    		if(!empty($pp_dribbble_username))
			    		{
			    	?>
			    	<li class="dribbble"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> title="Dribbble" href="http://dribbble.com/<?php echo esc_attr($pp_dribbble_username); ?>"><i class="fa fa-dribbble"></i></a></li>
			    	<?php
			    		}
			    	?>
			    	<?php
			    		$pp_linkedin_url = get_option('pp_linkedin_url');
			    		
			    		if(!empty($pp_linkedin_url))
			    		{
			    	?>
			    	<li class="linkedin"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> title="Linkedin" href="<?php echo esc_url($pp_linkedin_url); ?>"><i class="fa fa-linkedin"></i></a></li>
			    	<?php
			    		}
			    	?>
			    	<?php
			            $pp_pinterest_username = get_option('pp_pinterest_username');
			            
			            if(!empty($pp_pinterest_username))
			            {
			        ?>
			        <li class="pinterest"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> title="Pinterest" href="http://pinterest.com/<?php echo esc_attr($pp_pinterest_username); ?>"><i class="fa fa-pinterest"></i></a></li>
			        <?php
			            }
			        ?>
			        <?php
			        	$pp_instagram_username = get_option('pp_instagram_username');
			        	
			        	if(!empty($pp_instagram_username))
			        	{
			        ?>
			        <li class="instagram"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> title="Instagram" href="http://instagram.com/<?php echo esc_attr($pp_instagram_username); ?>"><i class="fa fa-instagram"></i></a></li>
			        <?php
			        	}
			        ?>
			        <?php
			        	$pp_behance_username = get_option('pp_behance_username');
			        	
			        	if(!empty($pp_behance_username))
			        	{
			        ?>
			        <li class="behance"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> title="Behance" href="http://behance.net/<?php echo esc_attr($pp_behance_username); ?>"><i class="fa fa-behance-square"></i></a></li>
			        <?php
			        	}
			        ?>
			        <?php
					    $pp_500px_url = get_option('pp_500px_url');
					    
					    if(!empty($pp_500px_url))
					    {
					?>
					<li class="500px"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> title="500px" href="<?php echo esc_url($pp_500px_url); ?>"><i class="fa fa-500px"></i></a></li>
					<?php
					    }
					?>
					<?php
					    $pp_tripadvisor_url = get_option('pp_tripadvisor_url');
					    
					    if(!empty($pp_tripadvisor_url))
					    {
					?>
					<li class="tripadvisor"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> title="Tripadvisor" href="<?php echo esc_url($pp_tripadvisor_url); ?>"><i class="fa fa-tripadvisor"></i></a></li>
					<?php
					    }
					?>
					
					<?php
					    $pp_yelp_url = get_option('pp_yelp_url');
					    
					    if(!empty($pp_yelp_url))
					    {
					?>
					<li class="yelp"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> title="Yelp" href="<?php echo esc_url($pp_yelp_url); ?>"><i class="fa fa-yelp"></i></a></li>
					<?php
					    }
					?>
			    </ul>
			</div>
		<?php
				}
			} //End if display social icons
			else
			{
				if ( has_nav_menu( 'footer-menu' ) ) 
			    {
				    wp_nav_menu( 
				        	array( 
				        		'menu_id'			=> 'footer_menu',
				        		'menu_class'		=> 'footer_nav',
				        		'theme_location' 	=> 'footer-menu',
				        	) 
				    ); 
				}
			}
		?>
	    <?php
	    	//Display copyright text
	        $tg_footer_copyright_text = kirki_get_option('tg_footer_copyright_text');

	        if(!empty($tg_footer_copyright_text))
	        {
	        	echo '<div id="copyright">'.wp_kses_post(htmlspecialchars_decode($tg_footer_copyright_text)).'</div><br class="clear"/>';
	        }
	    ?>
	    
	    <?php
	    	//Check if display to top button
	    	$tg_footer_copyright_totop = kirki_get_option('tg_footer_copyright_totop');
	    	
	    	if(!empty($tg_footer_copyright_totop))
	    	{
	    ?>
	    	<a id="toTop" href="javascript:;"><i class="fa fa-angle-up"></i></a>
	    <?php
	    	}
	    ?>
	</div>
</div>
<?php
}
?>
</div>

<?php
    } //End if not blank template
?>

<div id="side_menu_wrapper" class="overlay_background">
	<a id="close_share" href="javascript:;"><span class="ti-close"></span></a>
	<?php
		if(is_single())
		{
	?>
	<div id="fullscreen_share_wrapper">
		<div class="fullscreen_share_content">
		<?php
			get_template_part("/templates/template-share");
		?>
		</div>
	</div>
	<?php
		}
	?>
	
	<?php
		$tour_layout = get_post_meta($post->ID, 'tour_layout', true);
		
		if(is_single() && get_post_type() == 'tour' && $tour_layout == 'Fullwidth')
		{
	?>
	<div id="fullscreen_tour_wrapper">
		<div class="fullscreen_tour_content">
		<?php
			get_template_part("/templates/template-tour-f-single-book");
		?>
		</div>
	</div>
	<?php
		}
	?>
</div>

<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>
</body>
</html>

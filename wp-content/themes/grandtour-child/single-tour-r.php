<?php
//Add data for recently viewed tours
grandtour_set_recently_view_tours();

/**
 * The main template file for display single post page.
 *
 * @package WordPress
*/

get_header(); 

//Include custom header feature
//get_template_part("/templates/template-tour-header");

$page_tagline = get_the_excerpt();
?>
    <div id="page_content_wrapper" class="<?php if (!empty($pp_page_bg)) {
    ?>hasbg <?php
	} ?><?php if (!empty($pp_page_bg) && !empty($grandtour_topbar)) {
        ?>withtopbar <?php
    } ?><?php if (!empty($grandtour_page_content_class)) {
        echo esc_attr($grandtour_page_content_class);
	} ?>">
	
    <div class="inner">

    	<!-- Begin main content -->
    	<div class="inner_wrapper">

    		<?php
	    		//Get how single tour content display on mobile
	    		$tg_tour_single_mobile_content = kirki_get_option('tg_tour_single_mobile_content');
	    		
	    		if($tg_tour_single_mobile_content == 'booking')
	    		{
		    		//Include tour booking
					get_template_part("/templates/template-tour-single-booking");	
					
		    		//Include tour content
					get_template_part("/templates/template-tour-single-content");
				}
				else
				{
					//Include tour content
					get_template_part("/templates/template-tour-single-content");
					
					//Include tour booking
					get_template_part("/templates/template-tour-single-booking");
				}
	    	?>
    	</div>
    </div>
    <!-- End main content -->
    
    <?php
	  	//Include related tours
	  	get_template_part("/templates/template-tour-single-related");
	?>
   
</div>
<br class="clear"/>
</div>
<?php get_footer(); ?>

<script>
var slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1} 
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none"; 
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block"; 
  dots[slideIndex-1].className += " active";
}
</script>
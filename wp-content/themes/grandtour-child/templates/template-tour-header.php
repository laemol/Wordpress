<?php

// Get current product
$product = array_shift(apiGetRequest('products/' . $_GET['pid']));

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
    $grandtour_screen_class = grandtour_get_screen_class();

?>

<div id="page_caption" class="hasbg parralax">
    <!-- Slideshow container -->
<div class="slideshow-container">

<!-- Full-width images with number and caption text -->
<?php 
foreach($product['media'] as $image){ ?>
<div class="mySlides fade">
  <img src="<?php echo esc_url($image['imageUrl']); ?>" style="width:100%">
</div>

<?php } ?>

<!-- Next and previous buttons -->
<!-- <a class="prevslide nav" onclick="plusSlides(-1)"><div class="ti-angle-left"></div></a>
<a class="nextslide nav" onclick="plusSlides(1)"><div class="ti-angle-right"></div></a> -->
</div>
<br>

<!-- The dots/circles -->
<!-- <div class="dots">
<?php 
$slide = 1;
foreach($product['media'] as $image){ ?>
<span class="dot" onclick="currentSlide($slide)"></span> 
<?php 
$slide++ ;
} ?>
</div> -->

	<div class="single_tour_header_content">
		<div class="standard_wrapper">
              <!-- Photo Gallery -->
              <?php if(count($product['media']) > 1){ ?>
                    <a href="<?php echo esc_url($image['imageUrl']); ?>" id="single_tour_gallery_open" class="button fancy-gallery"><span class="ti-camera"></span>View Photos</a>
                    <div style="display:none;">
                <?php
                $count = 1;
                foreach($product['media'] as $image){ ?>
			        <a id="single_tour_gallery_image<?php echo $count ?>"" href="<?php echo esc_url($image['imageUrl']); ?>" title="<?php echo esc_url($image['name']); ?>" class="fancy-gallery"></a>
                <?php 
                    $count++;
                    } 
                ?>
		                </div>
                <?php
                }
                ?>
<?php
if ($product['video']) {
        ?>
			<a href="#video_review<?php echo esc_attr($current_page_id); ?>" id="single_tour_video_preview_open" class="button" data-type="inline"><span class="ti-control-play"></span><?php esc_html_e('Video Preview', 'grandtour'); ?></a>
			
            <div id="video_review<?php echo esc_attr($current_page_id); ?>" class="tour_video_preview_wrapper" style="display:none;height:800px;width:100%">
            <!-- <iframe width="100%" height="800px" src="<?php echo $product['video'] ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->
            <?php $embed_code = wp_oembed_get( $product['video'], ['height' => 1420, 'width' => 1425]); echo $embed_code  ?> 
            
        </div>
			<?php
    } ?>

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

<script>

var slideIndex = 0;
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
//   var navs = document.getElementsByClassName("nav");
//   if (slides.length == 1) { 
//       for (i = 0; i < navs.length; i++) {
//       navs[i].className = nav[i].style.display = "none";  
//   }
    //} 
  if (n > slides.length) { slideIndex = 1;} 
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";   
  }
  
  slides[slideIndex-1].style.display = "block"; 

}

// var slideIndex = 0;
// showSlides();

// function showSlides() {
//   var i;
//   var slides = document.getElementsByClassName("mySlides");
//   for (i = 0; i < slides.length; i++) {
//     slides[i].style.display = "none"; 
//   }
//   slideIndex++;
//   if (slideIndex > slides.length) {slideIndex = 1} 
//   slides[slideIndex-1].style.display = "block"; 
//   setTimeout(showSlides, 5000); // Change image every 5 seconds
// }
</script>
<?php
session_start();
/**
 * Template Name: Checkout
 * The main template file for display tour page checkout.
 *
 * @package WordPress
*/

$product = array_shift(apiGetRequest('products/' . $_POST['productId']));

//Check if single attachment page
if ($post->post_type == 'attachment') {
    get_template_part('single-attachment');
    die;
}

//Check if content builder preview
if (isset($_GET['rel']) && !empty($_GET['rel']) && isset($_GET['ppb_preview'])) {
    get_template_part('page-preview');
    die;
}

//Check if content builder preview page
if (isset($_GET['ppb_preview_page'])) {
    get_template_part('page-preview-page');
    die;
}

/**
*	Get Current page object
**/
if (!is_null($post)) {
    $page_obj = get_page($post->ID);
}

$current_page_id = '';

/**
*	Get current page id
**/

if (!is_null($post) && isset($page_obj->ID)) {
    $current_page_id = $page_obj->ID;
}

get_header();
?>

<?php
//Get Page Menu Transparent Option
$page_menu_transparent = get_post_meta($current_page_id, 'page_menu_transparent', true);

//Get page header display setting
$page_title = get_the_title();
$page_show_title = get_post_meta($current_page_id, 'page_show_title', true);

if (empty($page_show_title)) {
    //Get current page tagline
    $page_tagline = get_post_meta($current_page_id, 'page_tagline', true);

    $pp_page_bg = '';
    //Get page featured image
    if (has_post_thumbnail($current_page_id, 'full')) {
        $image_id = get_post_thumbnail_id($current_page_id);
        $image_thumb = wp_get_attachment_image_src($image_id, 'full', true);

        if (isset($image_thumb[0]) && !empty($image_thumb[0])) {
            $pp_page_bg = $image_thumb[0];
        }
    }

    //Check if add parallax effect
    $tg_page_header_bg_parallax = kirki_get_option('tg_page_header_bg_parallax');

    //Check if enable content builder
    $ppb_enable = get_post_meta($current_page_id, 'ppb_enable', true);

    $grandtour_topbar = grandtour_get_topbar();
    $page_header_type = '';

    //Get header featured content
    $page_header_type = get_post_meta(get_the_ID(), 'page_header_type', true);

    $video_url = '';

    if ($page_header_type == 'Youtube Video' or $page_header_type == 'Vimeo Video') {
        //Add jarallax video script
        wp_enqueue_script('jarallax-video', get_template_directory_uri() . '/js/jarallax-video.js', false, GRANDTOUR_THEMEVERSION, true);

        if ($page_header_type == 'Youtube Video') {
            $page_header_youtube = get_post_meta(get_the_ID(), 'page_header_youtube', true);
            $video_url = 'https://www.youtube.com/watch?v=' . $page_header_youtube;
        } else {
            $page_header_vimeo = get_post_meta(get_the_ID(), 'page_header_vimeo', true);
            $video_url = 'https://vimeo.com/' . $page_header_vimeo;
        }
    } ?>
<div id="page_caption" class="<?php if (!empty($pp_page_bg)) {
        ?>hasbg <?php if (!empty($tg_page_header_bg_parallax)) {
            ?>parallax<?php
        } ?> <?php
    } ?> <?php if (!empty($grandtour_topbar)) {
        ?>withtopbar<?php
    } ?> <?php if (!empty($grandtour_screen_class)) {
        echo esc_attr($grandtour_screen_class);
    } ?> <?php if (!empty($grandtour_page_content_class)) {
        echo esc_attr($grandtour_page_content_class);
    } ?>" <?php if (!empty($pp_page_bg)) {
        ?>style="background-image:url(<?php echo esc_url($pp_page_bg); ?>);"<?php
    } ?> <?php if ($page_header_type == 'Youtube Video' or $page_header_type == 'Vimeo Video') {
        ?>data-jarallax-video="<?php echo esc_url($video_url); ?>"<?php
    } ?>>
	
	<?php
        //Check page title vertical alignment
        $tg_page_title_vertical_alignment = kirki_get_option('tg_page_title_vertical_alignment');
    if ($tg_page_title_vertical_alignment == 'center') {
        ?>
		<div class="overlay_background visible"></div>
	<?php
    } ?>

	<?php
        if (empty($page_show_title)) {
            ?>
	<div class="page_title_wrapper">
		<div class="page_title_inner">
			<div class="page_title_content">

            <h1><?php pll_e('Checkout'); ?></h1>
            <nav>
            <ol class="cd-multi-steps text-bottom count">
            <li class="current"><a href="#0"><?php pll_e('Checkout'); ?></a></li>
            <li ><a href="#0"><?php pll_e('Payment'); ?></a></li>
            <li ><a href="#0"><?php pll_e('Confirmation'); ?></a></li>
            </ol>
            </nav>

				<?php
                    if (!empty($page_tagline)) {
                        ?>
			    	<div class="page_tagline">
			    		<?php echo nl2br($page_tagline); ?>
			    	</div>
			    <?php
                    } ?>
            </div>

		</div>
	</div>
	<?php
        } ?>

</div>
<?php
}
?>

<?php
    //Check if use page builder
    $ppb_form_data_order = '';
    $ppb_form_item_arr = [];
    $ppb_enable = get_post_meta($current_page_id, 'ppb_enable', true);

    $grandtour_topbar = grandtour_get_topbar();
?>
<?php
    if (!empty($ppb_enable)) {
        $grandtour_screen_class = grandtour_get_screen_class();
        grandtour_set_screen_class('ppb_wrapper');

        //if dont have password set
        if (!post_password_required()) {
            wp_enqueue_script('grandtour-custom-onepage', get_template_directory_uri() . '/js/custom_onepage.js', false, GRANDTOUR_THEMEVERSION, true); ?>
<div class="ppb_wrapper <?php if (!empty($pp_page_bg)) {
                ?>hasbg<?php
            } ?> <?php if (!empty($pp_page_bg) && !empty($grandtour_topbar)) {
                ?>withtopbar<?php
            } ?>">
<?php
        grandtour_apply_builder($current_page_id); ?>
</div>
<?php
        } //end if dont have password set
        else {
            ?>
<div id="page_content_wrapper" class="<?php if (!empty($pp_page_bg)) {
                ?>hasbg<?php
            } ?> <?php if (!empty($pp_page_bg) && !empty($grandtour_topbar)) {
                ?>withtopbar<?php
            } ?>">
    <div class="inner">
    	<!-- Begin main content -->
    	<div class="inner_wrapper">
            
    		<div class="sidebar_content full_width">
<?php
            the_content(); ?>
    		<br/><br/></div>
    	</div>
    </div>
</div>
<?php
        }
    } else {
        ?>
<!-- Begin content -->

<div id="page_content_wrapper" class="<?php if (!empty($pp_page_bg)) {
            ?>hasbg<?php
        } ?> <?php if (!empty($pp_page_bg) && !empty($grandtour_topbar)) {
            ?>withtopbar<?php
        } ?>">

		<!-- Begin main content -->
		<form action="<?php echo site_url() ?><?php echo lang_url() ?>payment" method="POST" id="form" data-parsley-validate>

    	<div class="wrapper">
            
		<div class="form_wrapper">

		<div id="one">
		<h4><?php pll_e('Billing details'); ?></h4>
  
			<p><strong><?php pll_e('Name'); ?></strong><br>
			<input type="text" class="input-text" name="fullname" id="fullname" placeholder="" value="" autocomplete="fullname" required=""></p>
			<p><strong><?php pll_e('Email'); ?></strong><br> <?php echo $_POST['email'] ; ?>
			<input type="email" class="input-text" name="email" id="email" placeholder="" value="" autocomplete="email" required=""></p>
			<p><div class="text-container"><strong><?php pll_e('Phone'); ?></strong><br><?php echo $_POST['phone'] ; ?>
			
			<input type="text" class="input-text" name="phone" id="phone" placeholder="" value="" autocomplete="phone">
			<span id="valid-msg" class="hide valid inline"><?php pll_e('Valid'); ?></span>
			<span id="error-msg" class="hide error inline"></span>  
			</div>
			
		</p>
		</div>
	
<?php if ($product['type'] == 'tour') {
            $tour = $product['tours'][array_search($_POST['tour_id'], array_column($product['tours'], 'tourId'))]; ?>

		 <div id="two">
		 <h4><?php pll_e('Tour details'); ?></h4>

		 <p><span class="ti-calendar"></span> <strong><?php pll_e('Tour date'); ?>: </strong> <?php echo date_format(date_create($tour['date']), 'l d F Y') ?>

		 <p><span class="ti-direction-alt"></span> <strong><?php pll_e('Meeting point'); ?></strong><br>
		 <?php pll_e('Please select a meeting point'); ?>.
		  <?php 

         echo'<div style="overflow-y: scroll; height:250px; width:75%">';

            echo '<ul class="departure">';
            foreach ($tour['stops'] as $stop) {
                if ($stop['type'] == 'stop') {
                    echo '<li >';
                    echo '<div class="radio"><input type="radio" name="stop_id" value="' . $stop['stopId'] . '"></div>';
                    echo  '<h6>' . $stop['time'] . ' - ' . $stop['name'] . '</h6>';
                    echo  ucfirst($stop['description']) . '<br>';
                    echo '<a href="https://www.google.com/maps?q=@' . $stop['latitude'] . ',' . $stop['longitude'] . '" target="_blank"><span class="ti-location-pin"></span> Show on Google Maps </a></span>';
                    echo '</li>';
                }
            } ?>
		 </ul>
		 </div>
		</p>
			
		 </div>

		<?php
        } else {
            ?>

        <div id="two">
        <!-- no conetnt yet... -->
        </div>

        <?php
        } ?>
		<div style="clear: both; height: 20px;"></div>
        <h4><?php pll_e('Your order'); ?></h4>
			<div id="order_review" class="checkout-review-order">
		<table class="shop_table checkout-review-order-table">
	    <thead>
		<tr>
			<th class="product-name"><?php pll_e('Product'); ?></th>
			<th class="product-total"><?php pll_e('Total'); ?></th>
		</tr>
	    </thead>
	    <tbody>
	
	<?php 
    $total = 0;

        foreach ($_POST['price'] as $key => $value) {
            if ($value > 0) {
                $price = $product['prices'][array_search($key, array_column($product['prices'], 'ticketId'))];
                $total = $total + $value * $price['currentPrice']; ?>
	<tr class="cart_item">
		<td class="product-name">
		<?php echo $product['name'] ?> - <?php echo $price['name'] ?>&nbsp;	<strong class="product-quantity">&times; <?php echo $value ?></strong></td>
						<td class="product-total">
							<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&euro;</span><?php echo number_format($value * $price['currentPrice'], 2, '.', '')  ?></span></td>
	</tr>
	<?php
            }
        } ?>
	</tbody>
	<tfoot>

		<tr class="cart-subtotal">
			<th><?php pll_e('Subtotal'); ?></th>
			<td><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&euro;</span><?php echo number_format($total, 2, '.', '')  ?></span></td>
		</tr>

		<tr class="order-total">
			<th><?php pll_e('Total'); ?></th>
			<td><strong><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&euro;</span><?php echo number_format($total, 2, '.', '')  ?></span></strong> </td>
		</tr>

	</tfoot>
</table>
    </div>

    <!-- Payments -->
    
    <div class="payment-details">
        <input type="radio" name="payment_type" value="card" checked="checked"> <?php pll_e('Creditcard'); ?> <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/credit-cards.png" class="cards"/><br>
    </div>

    <!-- <div class="payment_options">
        <input type="radio" name="payment_type" value="ideal" disabled> iDeal (not available yet) <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ideal.png" class="cards" /><br>
    </div> -->

    <div style="clear: both; height: 20px;"></div>

	<!-- forward input fields -->
	<?php
    session_start();
        $rand = rand();
        $_SESSION['rand'] = $rand; ?>
 	<input type="hidden" value="<?php echo $rand; ?>" name="randcheck" />
	<input type="hidden" name="prices" value="<?php echo base64_encode(serialize($_POST['price'])) ; ?>" >
	<input type="hidden" name="tour_id" value="<?php echo $_POST['tour_id']; ?>" >
	<input type="hidden" name="product_id" value="<?php echo $product['productId']; ?>" >
	<button type="submit" class="button alt" name="submit" id="proceed_payment" value="payment"><?php pll_e('Proceed to Payment'); ?></button>
	</div>
    		<?php

            if (comments_open($post->ID)) {
                ?>
			<div class="fullwidth_comment_wrapper">
				<?php comments_template('', true); ?>
			</div>
			<?php
            } ?>
			</div>
 
		</form>
        <!-- End main content -->

        </div>

<?php
    }
?>

<!-- Help pre-footer -->
<div class="one withsmallpadding ppb_text pre-footer" style="padding:40px 0 40px 0;">
<div class="standard_wrapper">
    <div class="page_content_wrapper"><div class="inner"><div style="margin:auto;width:100%">   
    <h4><?php pll_e('Frequently asked questions'); ?></h4> 
    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer Checkout") ) : ?>
<?php endif;?>

</div></div></div></div></div>

<?php get_footer(); ?>

<script>
jQuery(function()
{
	jQuery('#form').submit(function(){
		jQuery("#proceed_payment", this)
      .text("Please Wait...")
	  .attr('disabled', 'disabled')
	  .addClass('disabled', 'disabled');
    return true;
  });
});
  </script>

<script>
    jQuery(".select_date").on("click", function(){
        jQuery('.selected').removeClass('selected');
        jQuery(this).addClass('selected');
        jQuery('#date').val(jQuery(this).data('date'));
        jQuery('#tour_id').val(jQuery(this).data('tour_id'));
        jQuery('#available').val(jQuery(this).data('available'));
});
</script>

<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() ?>/scripts/intlTelInput/css/intlTelInput.css">
<script src="<?php echo get_stylesheet_directory_uri() ?>/scripts/intlTelInput/js/intlTelInput.js"></script>
<script>
var input = document.querySelector("#phone"),
  errorMsg = document.querySelector("#error-msg"),
  validMsg = document.querySelector("#valid-msg");

// here, the index maps to the error code returned from getValidationError - see readme
var errorMap = [ "Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];

// initialise plugin
var iti = window.intlTelInput(input, {
	preferredCountries: ['IT', 'NL', 'DE', 'BE', 'DK', 'FR', 'GB'],
  	utilsScript: "<?php echo get_stylesheet_directory_uri() ?>/scripts/intlTelInput/js/utils.js?1537727621611"
});

var reset = function() {
  input.classList.remove("error");
  errorMsg.innerHTML = "";
  errorMsg.classList.add("hide");
  validMsg.classList.add("hide");
};

// on blur: validate
input.addEventListener('blur', function() {
  reset();
  if (input.value.trim()) {
    if (iti.isValidNumber()) {
      validMsg.classList.remove("hide");
    } else {
      input.classList.add("error");
      var errorCode = iti.getValidationError();
      errorMsg.innerHTML = errorMap[errorCode];
      errorMsg.classList.remove("hide");
    }
  }
});

// on keyup / change flag: reset
input.addEventListener('change', reset);
input.addEventListener('keyup', reset);

</script>

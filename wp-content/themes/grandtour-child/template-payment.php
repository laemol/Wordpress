<?php
session_start();
/**
 * Template Name: Payment
 * The main template file for display tour page payment.
 *
 * @package WordPress
*/

$product = array_shift(apiGetRequest('products/' . $_POST['productId']));

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
            <li class="visited"><a href="#0"><?php pll_e('Checkout'); ?></a></li>
            <li class="current"><a href="#0"><?php pll_e('Payment'); ?></a></li>
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
    		<div class="sidebar_content full_width"><br/><br/>
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
    <div class="inner">

<!-- Begin main content -->
<?php

session_start();
        // Prevent resubmission of the form
        if ($_POST['randcheck'] == $_SESSION['rand']) {
             //if (true) {
            
            unset($_SESSION['rand']);
            $prices = unserialize(base64_decode($_POST['prices']));
            $tickets = [];
            foreach ($prices as $key => $value) {
                $tickets[] = ['ticketId' => $key, 'quantity' => $value];
            };

            //$currentLang = qtrans_getLanguage();

            $data = [
                'name' => $_POST['fullname'],
                'phone' => $_POST['phone'],
                'email' => $_POST['email'],
                'productId' => $_POST['productId'],
                'productName' => $_POST['productName'],
                'tourId' => $_POST['tour_id'],
                'stopId' => $_POST['stop_id'],
                'scheduleId' => $_POST['scheduleId'],
                'date' => $_POST['date'],
                'timeslot' => $_POST['timeslot'],
                'items' => $tickets,
                'source' => 'web',
                'clientIp' => $_SERVER['REMOTE_ADDR'],
                'partnerId' => $_SESSION['partnerId']
            ];

            $result = apiPostRequest('orders', $data);

            if ($result['orderId']) {
                ?>
            <h4><?php pll_e('Almost there!'); ?></h4>
            
             <p><?php pll_e('Your tickets are reserved for'); ?> <strong><span id="time">30:00</span></strong> <?php pll_e('minutes. Please click the payment button to proceed with the creditcard payment'); ?> . </p>
            
             <div class="payment-details">
            <p> 
            <?php pll_e('Total'); ?>: <strong> &euro;<?php echo number_format($result['totalPrice'], 2) ?></strong><br>
		    </p><br>

            <?php
            if($_POST['payment_type'] == 'card'){
                ?>

			<form action="<?php echo lang_url() ?>return" method="POST">
            <script
                    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                    data-key="<?php echo STRIPE_KEY ?>"
                    data-amount="<?php echo $result['totalPrice'] * 100 ?>"
                    data-name="Order <?php echo $result['code'] ?>"
                    data-email="<?php echo $_POST['email'] ?>"
                    data-locale="auto"
                    data-currency="eur"
                    data-allow-remember-me="false">
			</script>
			
            <input type="hidden" name="order_id" value="<?php echo $result['orderId'] ?>" >
            <input type="hidden" name="code" value="<?php echo $result['code'] ?>" >
            </form>
            </div>
		  
            <?php
            }elseif($_POST['payment_type'] == 'ideal'){
            ?>

            <script src="https://js.stripe.com/v3/"></script>
            
            <div class="payment-details">
            <form id="payment-form">
                
                <p><strong><?php pll_e('Name'); ?></strong><br>
                <input type="text" class="input-text" name="name" id="name" placeholder="Name" value="<?php echo $_POST['fullname'] ?>" autocomplete="name" required=""></p>
            
                <div id="ideal-bank-element">
                  <!-- A Stripe Element will be inserted here. -->
                </div>
            
                <p>
              <button class="button"><?php pll_e('Submit Payment'); ?></button>
            </p>

              <!-- Used to display form errors. -->
                <div id="error-message" role="alert"></div>
            </form>
            </div>

            <?php
            }
            }else{
                ?>
                <h4><?php pll_e('Oops, something went wrong...'); ?></h4>
                <?php pll_e('Please try again or contact support'); ?>.
                <?php
            }
        } else {
            ?>

        <h4><?php pll_e('Oops, something went wrong...'); ?></h4>
        <?php pll_e('Please try again or contact support'); ?>.

				<?php
        } ?>
			
    		</div>
		</div>
		</form>
    	<!-- End main content -->
    </div> 
</div>
<?php
    }
?>

<!-- Help pre-footer -->
<!-- <div class="one withsmallpadding ppb_text pre-footer" style="padding:40px 0 40px 0;">
<div class="standard_wrapper">
    <div class="page_content_wrapper"><div class="inner">
        <div style="margin:auto;width:100%">   
    <h4><?php pll_e('Frequently asked questions'); ?></h4> 
    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer Payment") ) : ?>
<?php endif;?>
</div></div></div></div></div> -->

<?php get_footer(); ?>

<?php
   if($_POST['payment_type'] == 'card'){
?>

<script type="text/javascript" defer>
// Create a Stripe client
var stripe = Stripe('<?php echo STRIPE_KEY ?>');

// Create an instance of Elements.
var elements = stripe.elements();

// Custom styling can be passed to options when creating an Element.
// (Note that this demo uses a wider set of styles than the guide below.)
var style = {
  base: {
    color: '#32325d',
    lineHeight: '18px',
    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
    fontSmoothing: 'antialiased',
    fontSize: '16px',
    '::placeholder': {
      color: '#aab7c4'
    }
  },
  invalid: {
    color: '#fa755a',
    iconColor: '#fa755a'
  }
};

// Create an instance of the card Element.
var card = elements.create('card', {style: style});

// Add an instance of the card Element into the `card-element` <div>.
card.mount('#card-element');

// Handle real-time validation errors from the card Element.
card.addEventListener('change', function(event) {
  var displayError = document.getElementById('card-errors');
  if (event.error) {
    displayError.textContent = event.error.message;
  } else {
    displayError.textContent = '';
  }
});

// Handle form submission.
var form = document.getElementById('payment-form');
form.addEventListener('submit', function(event) {
  event.preventDefault();

  stripe.createToken(card).then(function(result) {
    if (result.error) {
      // Inform the user if there was an error.
      var errorElement = document.getElementById('card-errors');
      errorElement.textContent = result.error.message;
    } else {
      // Send the token to your server.
      stripeTokenHandler(result.token);
    }
  });
});

// Submit the form with the token ID.
function stripeTokenHandler(token) {
  // Insert the token ID into the form so it gets submitted to the server
  var form = document.getElementById('payment-form');
  var hiddenInput = document.createElement('input');
  hiddenInput.setAttribute('type', 'hidden');
  hiddenInput.setAttribute('name', 'stripeToken');
  hiddenInput.setAttribute('value', token.id);
  form.appendChild(hiddenInput);

  // Submit the form
  form.submit();
}
</script>

<?php
    }
?>

<script>
    function startTimer(duration, display) {
    var timer = duration, minutes, seconds;
    setInterval(function () {
        minutes = parseInt(timer / 60, 10)
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = minutes + ":" + seconds;

        if (--timer < 0) {
            timer = duration;
            window.location.href = location.protocol + '//' + window.location.hostname + '/return';

        }
    }, 1000);
}

window.onload = function () {
    var minutes = 30 * 60,
        display = document.querySelector('#time');
    startTimer(minutes, display);
};
</script>

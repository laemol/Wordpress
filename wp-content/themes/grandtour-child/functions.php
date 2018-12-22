<?php
/*
Theme Name: Grand Tour Child Theme
Theme URI: http://themes.themegoods.com/grandtour
*/

// Constants
if (!defined('HOLLI_BASICAUTH')) {
    define('HOLLI_BASICAUTH', 'hollidev:hollidev03847');
}
if (!defined('HOLLI_KEY')) {
    define('HOLLI_KEY', '539169d340eda42d50c384efc2f9aa227eabcce7');
}
if (!defined('API_URL')) {
    define('API_URL', 'http://ticketandtours.test/api/v3/');
}

// Shortcodes
add_shortcode('search', 'searchBar');
add_shortcode('product-list', 'addProductListCode');
add_shortcode('booking-form', 'addBookingForm');
add_shortcode('zones', 'addZoneTags');
add_shortcode('category-options', 'addCategoryOptionsList');

// Actions
add_action('wp_ajax_grandtour_ajax_search_product_result', 'grandtour_ajax_search_product_result');
add_action('wp_ajax_nopriv_grandtour_ajax_search_product_result', 'grandtour_ajax_search_product_result');
add_action('product-list', 'addProductListCode');
add_action('product', 'addProductData');
add_action('area', 'addAreaCode', 10, 1);
add_action('wp_ajax_getProductList', 'addProductListCode');
add_action('wp_ajax_nopriv_getProductList', 'addProductListCode');

/**
* API GET request
*/
function apiGetRequest($resource)
{
    $data = [];
    $product = $id ?: $_GET['pid'];

    $wp_request_headers = [
        'Authorization' => 'Basic ' . base64_encode(HOLLI_BASICAUTH),
        'X-Authorization:' . HOLLI_KEY,
        'X-Authorization' => HOLLI_KEY,
        'Content-Type' => 'application/json'
    ];

    $url = API_URL . $resource;

    $response = wp_remote_get($url, [
        'headers' => $wp_request_headers
    ]);

    if (is_array($response) && !is_wp_error($response)) {
        $data = array_shift(json_decode($response['body'], true));
    }
    return $data;
}

/**
* Api POST request
*/
function apiPostRequest($resource, $data)
{
    $wp_request_headers = [
        'Authorization' => 'Basic ' . base64_encode(HOLLI_BASICAUTH),
        'X-Authorization:' . HOLLI_KEY,
        'X-Authorization' => HOLLI_KEY,
        'Content-Type' => 'application/json'
    ];

    $url = API_URL . $resource;

    $response = wp_remote_post($url, [
        'method' => 'POST',
        'headers' => $wp_request_headers,
        'body' => json_encode($data)
    ]);

    if (is_array($response) && !is_wp_error($response)) {
        $data = array_shift(json_decode($response['body'], true));
    }

    return $data;
}

/**
* Adds the products list
*/
function addProductListCode()
{
    ob_start();
    $area = $_GET['area'];
    $category_id = $_GET['category_id'] ? implode(',', $_GET['category_id']) : null;
    $destination_id = $_GET['destination_id'];
    $offset = $_POST['offset'];
    $recommended = $_GET['destination_id'] || $_GET['category_id'] ? '' : 'true';

    $data = apiGetRequest('products?recommended=' . $recommended . '&offset=' . $offset . '&limit=12&&area=' . $area . '&category_id=' . $category_id . '&zone_id=' . $destination_id . '&lang=' . pll_current_language());

    if (!$data) {
        echo '[no data found]';
    }
    echo '<div  class="ppb_tour_classic one nopadding " style="margin-bottom:50px;" >';
    echo '<div class="page_content_wrapper page_main_content sidebar_content full_width fixed_column">';
    echo '<div class="standard_wrapper">';

    if ($data) {
        foreach ($data as $product) {
            $offset++;
            echo '<div class="element grid classic4_cols animated4">';
            echo '<div class="one_fourth gallery4 classic static filterable portfolio_type themeborder">';
            echo '<a class="tour_image" href="' . get_site_url() . '/' . pll_current_language() . '/tour/details?pid=' . $product['productId'] . '">';
            echo '<img src="' . $product['media'][0]['imageUrl'] . '" alt="' . $product['name'] . '" style="height:140px"/>';
            if ($product['prices'][0]['originalPrice'] > $product['prices'][0]['currentPrice']) {
                echo '<div class="tour_price has_discount"><span class="normal_price">&euro; ' . $product['prices'][0]['originalPrice'] . '</span>&euro; ' . $product['prices'][0]['currentPrice'] . '</div></a>';
            } else {
                echo '<div class="tour_price">&euro; ' . $product['prices'][0]['currentPrice'] . '</div></a>';
            }
            echo '<div class="portfolio_info_wrapper">';
            echo '<a class="tour_link" href="' . get_site_url() . '/' . pll_current_language() . '/tour/details?pid=' . $product['productId'] . '"><h4>' . $product['name'] . '</h4></a>';
            echo '<div class="tour_excerpt"><p>' . ucfirst($product['type']) . ', ' . $product['category'] . '</p></div>';
            echo '<div class="tour_attribute_wrapper">';
            echo '<div class="tour_attribute_rating"><div class="br-theme-fontawesome-stars-o">';
            echo '<div class="br-widget">';
            echo '<a href="javascript:;" class="br-selected"></a>';
            echo '<a href="javascript:;" class="br-selected"></a>';
            echo '<a href="javascript:;" class="br-selected"></a>';
            echo '<a href="javascript:;" class="br-selected"></a>';
            echo '<a href="javascript:;"></a></div></div>';
            echo '<div class="tour_attribute_rating_count">' . rand(0, 93) . ' Reviews</div></div>';
            if ($product['type'] == 'tourzzz') {
                echo '<div class="tour_attribute_days"><span class="ti-time"></span>' . ucfirst($product['duration']) . ' Hours</div>';
            }
            echo '</div><br class="clear"/>';
            echo '</div></div></div>';
        }

        echo '</div></div></div>';
    } ?>
       
    <?php

    /* AJAX check  */
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        ?>
        <div class="btn_wrapper">
        <a href="#/" class="button show_more" id="<?php echo $offset ?>">Show More</a>
        <span class="loding" style="display: none;"><span class="loding_txt">
            <img src="<?php echo get_stylesheet_directory_uri() ?>/img/ajax-loader.gif" /></span>
        </span>
    </div>

    <div class="products<?php echo $offset ?>"></div>

    <?php
        die();
    }

    return ob_get_clean(); ?>
    <!-- <script type="text/javascript">
    jQuery(document).ready(function(){
    jQuery(document).on('click','.show_more',function(){	var offset = jQuery(this).attr('id');	console.log(offset);	jQuery('.show_more').hide();	jQuery('.loding').show();
    jQuery.ajax({		type: 'POST',
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        data: {							action: 'getProductList',							offset: offset,											},
                        success: function( data ) {							jQuery('.products'+offset).append(data);							jQuery('.loding').hide();						}
            });
        });
    });
    </script> -->
<?php
}

/**
* Adds the booking code form
*/
function addBookingForm()
{
    //Include tour content
    get_template_part('/templates/template-booking-form');
}

/**
* Adds the category options list
*/
function addCategoryOptionsList()
{
    ob_start();

    $categories = apiGetRequest('product/categories');

    echo '<form action="' . get_site_url() . '/tours" method="get" id="filterform"><div>';
    foreach ($categories as $category) {
        echo '<input type="checkbox" class="filter" id="scales" name="category_id[]" value="' . $category['id'] . '"';
        if ($_GET['category_id'] ? in_array($category['id'], $_GET['category_id']) : false) {
            echo 'checked';
        }
        echo '> ' . $category['name'] . '<br>';
    }
    echo '<br>';
    echo '</div>';
    echo '<h2 class="widgettitle">Where do you want to go?</h2>';
    echo '<br>';

    $zones = apiGetRequest('zones');
    echo '<div class="radio-toolbar">';
    foreach ($zones as $zone) {
        echo '<label><input type="radio" id="destination_' . $zone['id'] . '" class="filter" name="destination_id" value="' . $zone['id'] . '"';
        if ($zone['id'] == $_GET['destination_id']) {
            echo 'checked';
        }
        echo '><span for="destination_' . $zone['id'] . '">' . $zone['name'] . '</span></label>';
    }
    echo '</div>';

    //echo '<input type="submit" value="Filter Results" class="submit full_width" id="submitform"></form>';?>
    
    <script>
       jQuery( ".filter" ).click(function() {
        jQuery('body').append('<div style="" id="loadingDiv"><div class="loader"><div class="lds-dual-ring"></div></div></div>');
        jQuery( "#filterform" ).submit();
    });
    </script>

<script>
  
  jQuery(window).on('load', function(){
  setTimeout(removeLoader, 1000); //wait for page load PLUS two seconds.
});
function removeLoader(){
    jQuery( "#loadingDiv" ).fadeOut(500, function() {
      // fadeOut complete. Remove the loading div
      jQuery( "#loadingDiv" ).remove(); //makes page more lightweight 
  });  
}
</script>
    
    <?php
    return ob_get_clean();
}

/**
* Adds the area tags cloud
*/
function addAreaCode()
{
    $data = apiGetRequest('products?limit=8&zone_id=' . get_field('zone_id'));

    echo '<div  class="ppb_tour_classic one nopadding " style="margin-bottom:50px;" >';
    echo '<div class="page_content_wrapper page_main_content sidebar_content full_width fixed_column">';
    echo '<div class="standard_wrapper">';

    echo '<div class="portfolio_filter_wrapper gallery classic four_cols" data-columns="4">';

    foreach ($data as $product) {
        echo '<div class="element grid classic4_cols animated4">';
        echo '<div class="one_fourth gallery4 classic static filterable portfolio_type themeborder">';
        echo '<a class="tour_image" href="' . get_site_url() . '/' . pll_current_language() . '/tour/details?pid=' . $product['productId'] . '">';
        echo '<img src="' . $product['media'][0]['imageUrl'] . '" alt="' . $product['name'] . '" style="height:140px"/>';
        if ($product['prices'][0]['originalPrice'] > $product['prices'][0]['currentPrice']) {
            echo '<div class="tour_price has_discount"><span class="normal_price">&euro; ' . $product['prices'][0]['originalPrice'] . '</span>&euro; ' . $product['prices'][0]['currentPrice'] . '</div></a>';
        } else {
            echo '<div class="tour_price">&euro; ' . $product['prices'][0]['currentPrice'] . '</div></a>';
        }
        echo '<div class="portfolio_info_wrapper">';
        echo '<a class="tour_link" href="' . get_site_url() . '/' . pll_current_language() . '/tour/details?pid=' . $product['productId'] . '"><h4>' . $product['name'] . '</h4></a>';
        echo '<div class="tour_excerpt"><p>' . ucfirst($product['type']) . ', ' . $product['category'] . '</p></div>';
        echo '<div class="tour_attribute_wrapper">';
        echo '<div class="tour_attribute_rating"><div class="br-theme-fontawesome-stars-o">';
        echo '<div class="br-widget">';
        echo '<a href="javascript:;" class="br-selected"></a>';
        echo '<a href="javascript:;" class="br-selected"></a>';
        echo '<a href="javascript:;" class="br-selected"></a>';
        echo '<a href="javascript:;" class="br-selected"></a>';
        echo '<a href="javascript:;"></a></div></div>';
        echo '<div class="tour_attribute_rating_count">' . rand(0, 93) . ' Reviews</div></div>';
        if ($product['type'] == 'tour') {
            echo '<div class="tour_attribute_days"><span class="ti-time"></span>' . ucfirst($product['duration']) . ' Hours</div>';
        }
        echo '</div><br class="clear"/>';
        echo '</div></div></div>';
    }

    echo '</div></div></div></div>';
}

/**
* Adds the products details code
*/
function addProductData()
{
    $data = apiGetRequest('products/' . $_GET['pid']);

    return($product);
}

/*
* Adds the destinations tag cloud
*/
function addZoneTags()
{
    ob_start();

    $zones = apiGetRequest('zones'); ?>
    
    <div class="tags_wrapper">
    <?php
    foreach ($zones as $zone) {
        echo '<span class="tagbox"><a href="';
        echo get_site_url();
        echo '/tours?destination_id=';
        echo $zone['id'];
        echo '"><div class="linkdiv"><h5>';
        echo $zone['name'];
        echo '</h5></div></a></span>';
    }
    echo '<br class="clear"></div>';

    return ob_get_clean();
}

/*
* Adds the product ajax search code
*/
function grandtour_ajax_search_product_result()
{
    if (strlen($_POST['keyword']) > 1) {
        $products = apiGetRequest('products?search=' . $_POST['keyword']);
        $zones = apiGetRequest('zones?search=' . $_POST['keyword']);

        echo '<ul>';

        foreach ($zones as $zone) {
            echo '<li>';
            echo '<a href="' . get_site_url() . '/tours?destination_id=' . $zone['id'] . '"><span class="ti-location-pin"></span> ' . $zone['name'] . '</a> ';
            echo '</li>';
        }

        echo '<li class="seperator"></li>';

        foreach ($products as $product) {
            echo '<li>';
            echo '<a href="' . get_site_url() . '/tour/details?pid=' . $product['productId'] . '"><span class="ti-ticket"></span> ' . $product['name'] . '</a>';
            echo '</li>';
        }

        echo '</ul>';
    } else {
        echo '';
    }
    die();
}

/*
* Adds the search bar code
*/
function searchBar()
{
    ob_start();

    wp_enqueue_script('script-ajax-search', admin_url('admin-ajax.php') . '?action=grandtour_ajax_search&id=keyword&form=tour_search_form&result=autocomplete', false, GRANDTOUR_THEMEVERSION, true);

    $categories = apiGetRequest('product/categories');
    //$zones = apiGetRequest('zones?search=' . $_POST['destination_id']);?>
        <div  class="one withsmallpadding ppb_tour_search" style="margin-top:-600px;" >
        
        <div class="standard_wrapper">
        <div class="page_content_wrapper">
        <div class="inner">
            <div class="searchtxt">
                <h1 >What do you want to do?</h1>
            </div>
        <form id="tour_search_form" class="tour_search_form" method="get" action="<?php echo get_site_url() ?>/<?php echo pll_current_language() ?>/search">
        <div class="tour_search_wrapper">
        <!-- <div class="one_fourth themeborder">
        <select id="category" name="category_id">
        <option value=""><?php pll_e('All Categories'); ?></option>
        <?php
        foreach ($categories as $category) {
            echo '<option value="' . $category['id'] . '"';
            if ($_GET['category_id'] == $category['id']) {
                echo 'selected="selected"';
            }
            echo '>' . $category['name'] . '</option>';
        } ?>    
        </select><span class="ti-angle-down"></span>
        </div> -->
        
        <div class="one_fourth themeborder keyword">
    		<input id="keyword" name="keyword" type="text" autocomplete="off" placeholder="<?php pll_e('Tour, Park or Destination'); ?>"/>
    		<span class="ti-search"></span>
    		<div id="autocomplete" class="autocomplete" data-mousedown="false"></div>
        </div>
    	<!-- <div class="one_fourth last themeborder">
    		<input id="tour_search_btn" type="submit" class="button" value="<?php pll_e('Search'); ?>"/>
        </div> -->
        
            </div></div>
        </div>
        </form></div></div>
        <?php
         return ob_get_clean();
}

/*
* Adds the ACF LOV
*/
function acf_load_product_field_choices($field)
{
    // reset choices
    $field['choices'] = ['' => 'Select'];

    // get the textarea value from options page without any formatting
    $zones = apiGetRequest('zones');

    foreach ($zones as $zone) {
        // vars
        $value = $zone['id'];
        $label = $zone['name'];

        // append to choices
        $field['choices'][$value] = $label;
    }

    // return the field
    return $field;
}
add_filter('acf/load_field/name=zone_id', 'acf_load_product_field_choices');

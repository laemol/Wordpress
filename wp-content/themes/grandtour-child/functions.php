<?php
/*
Theme Name: Grand Tour Child Theme
Theme URI: http://themes.themegoods.com/grandtour
*/

// Shortcodes
add_shortcode('search', 'searchBar');
add_shortcode('product-list', 'addProductListCode');
add_shortcode('booking-form', 'addBookingForm');
add_shortcode('zones', 'addRegionTags');
add_shortcode('region-name', 'addRegionName');
add_shortcode('filter-list', 'addCategoryOptionsList');
add_shortcode('calendar', 'showCalendar');

// Actions
add_action('wp_ajax_grandtour_ajax_search_product_result', 'grandtour_ajax_search_product_result');
add_action('wp_ajax_nopriv_grandtour_ajax_search_product_result', 'grandtour_ajax_search_product_result');
add_action('product-list', 'addProductListCode');
add_action('product', 'addProductData');
add_action('wp_ajax_getProductList', 'addProductListCode');
add_action('wp_ajax_nopriv_getProductList', 'addProductListCode');

// Create global
global $product;

function lang_url()
{
    return ($lang = pll_current_language() == 'en' ? '/' : '/' . pll_current_language() . '/');
}

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

    //var_dump($url);

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
function addProductListCode($atts = '')
{
    $limit = 12;
    $value = shortcode_atts([
        'limit' => 12,
        'button' => 'show',
        'lat' => null,
        'long' => null,
        'range' => null,
        'region' => null
    ], $atts);

    ob_start();
    $category_id = $_GET['category_id'] ? implode(',', $_GET['category_id']) : null;
    $region_id = $value['region'] ?: $_GET['region_id'];
    $recommended = $_GET['region_id'] || $_GET['category_id'] ? '' : 'true';

    if ($_POST['offset'] || $_POST['category'] || $_POST['destination']) {
        $offset = $_POST['offset'];
        $category_id = $_POST['category'] ? implode(',', $_POST['category']) : null;
        $region_id = $_POST['destination'];
        $recommended = '';
    }

    $url = 'products?region_id=' . $region_id . '&offset=' . $offset . '&limit=' . $value['limit'] . '&category_id=' . $category_id . '&lang=' . pll_current_language() . '&lat=' . $value['lat'] . '&long=' . $value['long'] . '&range=' . $value['range'];
    $data = apiGetRequest($url);

    echo '<div  class="ppb_tour_classic one nopadding" >';
    echo '<div class="page_content_wrapper page_main_content sidebar_content full_width fixed_column">';
    echo '<div class="standard_wrapper">';

    if ($data) {
        foreach ($data as $product) {
            $offset++;
            echo '<div class="element grid classic4_cols animated4">';
            echo '<div class="one_fourth gallery4 classic static filterable portfolio_type themeborder">';
            echo '<a class="tour_image" href="' . get_site_url() . '/' . pll_current_language() . '/tour/details?pid=' . $product['id'] . '">';
            echo '<img src="' . $product['media'][0]['imageUrl'] . '" alt="' . $product['name'] . '" style="height:140px"/>';
            if ($product['originalPrice'] > $product['currentPrice']) {
                echo '<div class="tour_price has_discount"><span class="normal_price">&euro; ' . $product['originalPrice'] . '</span>&euro; ' . $product['currentPrice'] . '</div></a>';
            } else {
                echo '<div class="tour_price">&euro; ' . $product['currentPrice'] . '</div></a>';
            }
            echo '<div class="portfolio_info_wrapper">';
            echo '<a class="tour_link" href="' . get_site_url() . '/' . pll_current_language() . '/tour/details?pid=' . $product['id'] . '"><h4>' . $product['name'] . '</h4></a>';
            if ($product['type'] == 'tour') {
                echo '<div class="tour_excerpt"><p><i class="ti-location-pin"> </i> ' . $product['location'] . '<br><i class="ti-time"> </i> ' . $product['duration'] . ' Hours</p></div>';
            } else {
                echo '<div class="tour_excerpt"><p><i class="ti-location-pin"> </i> ' . $product['location'] . '<br><i class="ti-ticket"> </i> ' . $product['category'] . '</p></div>';
            }
            //echo '<div class="tour_attribute_wrapper">';
            //echo '<div class="tour_attribute_rating">';
            //$shortcode_reviews_summary = '[site_reviews_summary assigned_to="' . $product['productId'] . '" ';
            //echo do_shortcode($shortcode_reviews_summary . 'hide="bars,if_empty,rating,summary"]');
            //preg_match('([1-9] reviews)', do_shortcode($shortcode_reviews_summary . 'hide="bars,if_empty,rating,stars"]'), $matches);
            //echo '<div class="tour_attribute_rating_count">' . $matches[0] . '</div></div>';
            //echo '</div></div><br class="clear"/>';
            echo '</div></div></div>';
        }
    } else {
        echo '<div class="infobox">';
        echo pll_e('Sorry, nothing found that matches your seach criteria') ;
        echo '</div>';
    }

    echo '</div></div></div>';

    if (count($data) >= $limit) {
        if ($value['button'] != 'hide') {
            ?>
            <div class="products<?php echo $offset ?>"></div>

        <div class="btn_wrapper">
        <a href="#/" class="button show_more" data-offset="<?php echo $offset ?>" data-category="[<?php echo $category_id ?>]" data-zone="<?php echo $region_id ?>"><?php echo pll_e('Show More')  ?></a>
        </div>

    <?php

        }
    }

    /* AJAX check  */
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        die();
    } ?>

    <script type="text/javascript">
    jQuery(document).ready(function(){
    jQuery(document).on('click','.show_more',function(){
        var offset = jQuery(this).data('offset');
        var category = jQuery(this).data('category');
        var destination = jQuery(this).data('zone');
        var loder = '<div class="loder"></div>'
        jQuery('.show_more').html(loder);
        jQuery('.loding').show();
        jQuery.ajax({
                        type: 'POST',
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        data: {		action: 'getProductList',
                                    offset: offset,
                                    category: category,
                                    destination: destination,
                        },
                        success: function( data ) {
                        jQuery('.show_more').hide();
                        jQuery('.products'+offset).append(data);
                        jQuery('.loding').hide();
                        }
            });
        });
    });
    </script>
<?php
return ob_get_clean();
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

    $categories = apiGetRequest('product/categories?lang=' . pll_current_language() . '&region_id=' . $_GET['region_id']);

    echo '<h2 class="widgettitle">';
    echo pll_e('What do you want to do?');
    echo '</h2>';
    echo '<br>';
    echo '<form action="" method="get" id="filterform"><div>';
    echo '<input type="checkbox" class="all" id="all" name="category_id" value=""';
    if (!$_GET['category_id']) {
        echo 'checked';
    }
    echo '> ';
    echo pll_e('All Categories');
    echo '<br>';
    foreach ($categories as $category) {
        if ($category['count'] > 0) {
            echo '<input type="checkbox" class="filter" name="category_id[]" value="' . $category['id'] . '"';
            if ($_GET['category_id'] ? in_array($category['id'], $_GET['category_id']) : false) {
                echo 'checked';
            }
            echo '> ' . ucfirst($category['name']) . '<span class="info">' . $category['count'] . '</span><br>';
        }
    }
    echo '<br>';
    echo '</div>';
    echo '<h2 class="widgettitle">';
    echo pll_e('Where do you want to go?');
    echo '</h2>';
    echo '<br>';

    $regions = apiGetRequest('regions');
    echo '<div class="radio-toolbar">';
    echo '<label><input type="radio" id="region_0" class="filter" name="region_id" value=""';
    if (!$_GET['region_id']) {
        echo 'checked';
    }
    echo '><span for="region_0">';
    echo pll_e('All Destination');
    echo '</span></label>';
    foreach ($regions as $region) {
        echo '<label><input type="radio" id="region_' . $zone['id'] . '" class="filter" name="region_id" value="' . $region['id'] . '"';
        if ($region['id'] == $_GET['region_id']) {
            echo 'checked';
        }
        echo '><span for="region_' . $region['id'] . '">' . ucfirst($region['name']) . '</span></label>';
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
       jQuery( "#all" ).click(function() {
        jQuery('.filter').removeAttr('checked');
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

/*
* Adds the destinations tag cloud
*/
function addRegionTags()
{
    ob_start();

    $regions = apiGetRequest('regions'); ?>

    <div class="tags_wrapper">
    <?php
    foreach ($regions as $region) {
        echo '<span class="tagbox"><a href="';
        echo get_site_url() . '/' . pll_current_language();
        echo '/tickets?region_id=';
        echo $region['id'];
        echo '"><div class="linkdiv"><h5>';
        echo ucfirst($region['name']);
        echo '</h5></div></a></span>';
    }
    echo '<br class="clear"></div>';

    return ob_get_clean();
}

/*
* Adds the destinations tag cloud
*/
function addRegionName()
{
    ob_start();
    $regions = apiGetRequest('regions');
    foreach ($regions as $region) {
        if ($_GET['region_id'] == $region['id']) {
            return ' ' . $region['name'];
        }
    }
    ob_get_clean();
}

/*
* Adds the product ajax search code
*/
function grandtour_ajax_search_product_result()
{
    if (strlen($_POST['keyword']) > 1) {
        $products = array_shift(apiGetRequest('list/products?q=' . $_POST['keyword']));
        $regions = apiGetRequest('regions?search=' . $_POST['keyword']);

        echo '<ul>';

        foreach ($regions as $region) {
            echo '<li>';
            echo '<a href="' . get_site_url() . '/' . pll_current_language() . '/tickets?region_id=' . $region['id'] . '"><span class="ti-location-pin"></span> ' . $region['name'] . '</a> ';
            echo '</li>';
        }
        if (count($regions)) {
            echo '<li class="seperator"></li>';
        }

        foreach ($products as $product) {
            echo '<li>';
            echo '<a href="' . get_site_url() . '/' . pll_current_language() . '/tour/details?pid=' . $product['id'] . '"><span class="ti-ticket"></span> ' . $product['name'] . '</a>';
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

    $categories = apiGetRequest('product/categories'); ?>
        <div  class="one withsmallpadding ppb_tour_search" style="margin-top:-600px;" >

        <div class="standard_wrapper">
        <div class="page_content_wrapper">
        <div class="inner">
            <div class="searchtxt">
                <h1 ><?php pll_e('What do you want to do?'); ?></h1>
            </div>
        <form id="tour_search_form" class="tour_search_form" method="get" action="<?php echo get_site_url() ?>/<?php echo pll_current_language() ?>/search">
        <div class="tour_search_wrapper">

        <div class="one_fourth themeborder keyword">
    		<input id="keyword" name="keyword" type="text" autocomplete="off" placeholder="<?php pll_e('Tour, Park or Destination'); ?>"/>
    		<span class="ti-search"></span>
    		<div id="autocomplete" class="autocomplete" data-mousedown="false"></div>
        </div></div></div>
        </div>
        </form></div></div>
        <?php
         return ob_get_clean();
}

/*
* Adds the calendar code
*/
function showCalendar() {
    //wp_enqueue_script('vue', 'https://cdn.jsdelivr.net/npm/vue@2.5.17/dist/vue.js', [], '2.5.17');
    wp_enqueue_script('calendar', get_stylesheet_directory_uri() . '/js/app.js', [], '1.0', true);
}
/*
* Adds the ACF LOV
*/
function acf_load_product_field_choices($field)
{
    $field['choices'] = ['' => 'Select'];

    $regions = apiGetRequest('regions');

    foreach ($regions as $region) {
        $value = $region['id'];
        $label = ucfirst($region['name']);
        $field['choices'][$value] = $label;
    }

    return $field;
}
add_filter('acf/load_field/name=region_id', 'acf_load_product_field_choices');

// function customize_post_admin_menu_labels()
// {
//     global $menu;
//     $menu[34][0] = 'Help';
//     echo '';
// }
//     add_action('admin_menu', 'customize_post_admin_menu_labels');

add_action('admin_head', 'custom_icons');

function custom_icons()
{
    echo '<style>
  #adminmenu div.wp-menu-image {
    -webkit-filter: grayscale(100%);
    -moz-filter: grayscale(100%);
      -o-filter: grayscale(100%);
     -ms-filter: grayscale(100%);
         filter: grayscale(100%);
    }
  </style>';
}

if (function_exists('register_sidebar')) {
    register_sidebar(
      [
    'name' => 'Footer Checkout',
    'before_widget' => '<div class = "widgetizedArea">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
  ]
);
}

if (function_exists('register_sidebar')) {
    register_sidebar(
      [
    'name' => 'Footer Payment',
    'before_widget' => '<div class = "widgetizedArea">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
  ]
);
}

if (function_exists('register_sidebar')) {
    register_sidebar(
      [
    'name' => 'Footer Return',
    'before_widget' => '<div class = "widgetizedArea">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
  ]
);
}

function remove_admin_menu_bar_items($wp_toolbar)
{
    $wp_toolbar->remove_node('my-sites');
    $wp_toolbar->remove_node('wp-logo');
    $wp_toolbar->remove_node('new-content');
    $wp_toolbar->remove_node('view');
    $wp_toolbar->remove_node('search');  // remove the search element
    return $wp_toolbar;
}
add_filter('admin_bar_menu', 'remove_admin_menu_bar_items');

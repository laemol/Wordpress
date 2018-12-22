<?php
/**
 * Plugin Name:       Holli
 * Description:       Plugin for the Holli API
 * Version:           1.1.0
 * Author:            Talpaq
 * Author URI:        https://talpaq.com
 * Text Domain:       talpaq
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: https://github.com/talpaq
 */
/*
 * Plugin constants
 */

 /** Allow for cross-domain requests (from the front end). */
send_origin_headers();

if (!defined('HOLLI_PLUGIN_VERSION')) {
    define('HOLLI_PLUGIN_VERSION', '1.1.0');
}
if (!defined('HOLLI_URL')) {
    define('HOLLI_URL', plugin_dir_url(__FILE__));
}
if (!defined('HOLLI_PATH')) {
    define('HOLLI_PATH', plugin_dir_path(__FILE__));
}
if (!defined('HOLLI_BASICAUTH')) {
    define('HOLLI_BASICAUTH', 'hollidev:hollidev03847');
}
if (!defined('HOLLI_VERSION')) {
    define('HOLLI_VERSION', 'v3');
}

function getProductData($id = null)
{
    $data = [];
    $product = $id ?: $_GET['pid'];
    $lang = pll_current_language();

    $wp_request_headers = [
                'Authorization' => 'Basic ' . base64_encode(HOLLI_BASICAUTH),
                'X-Authorization:' . '539169d340eda42d50c384efc2f9aa227eabcce7',
                'X-Authorization' => '539169d340eda42d50c384efc2f9aa227eabcce7',
                'Content-Type' => 'application/json'
        ];

    $url = 'https://test.backend.holliapp.com/api/v3/products/' . $product . '?limit=4&lang=' . $lang;

    $response = wp_remote_get($url, [
            'headers' => $wp_request_headers
        ]);

    if (is_array($response) && !is_wp_error($response)) {
        $data = array_shift(json_decode($response['body'], true));
    }

    return array_shift($data);
}

function getProductSearch($search, $category_id, $destination_id)
{
    $data = [];

    if ($search) {
        $wp_request_headers = [
                'Authorization' => 'Basic ' . base64_encode(HOLLI_BASICAUTH),
                'X-Authorization:' . '539169d340eda42d50c384efc2f9aa227eabcce7',
                'X-Authorization' => '539169d340eda42d50c384efc2f9aa227eabcce7',
                'Content-Type' => 'application/json'
        ];

        $url = 'https://test.backend.holliapp.com/api/v3/products?search=' . $search . '&category_id=' . $category_id . '&zone_id=' . $destination_id;

        $response = wp_remote_get($url, [
            'headers' => $wp_request_headers
        ]);

        if (is_array($response) && !is_wp_error($response)) {
            $data = array_shift(json_decode($response['body'], true));
        }

        return $data;
    }
}

function getZoneSearch($search)
{
    $data = [];

    if ($search) {
        $wp_request_headers = [
                'Authorization' => 'Basic ' . base64_encode(HOLLI_BASICAUTH),
                'X-Authorization:' . '539169d340eda42d50c384efc2f9aa227eabcce7',
                'X-Authorization' => '539169d340eda42d50c384efc2f9aa227eabcce7',
                'Content-Type' => 'application/json'
        ];

        $url = 'https://test.backend.holliapp.com/api/v3/zones?search=' . $search;

        $response = wp_remote_get($url, [
            'headers' => $wp_request_headers
        ]);

        if (is_array($response) && !is_wp_error($response)) {
            $data = array_shift(json_decode($response['body'], true));
        }

        return $data;
    }
}

/*
 * Main class
 */
/**
 * Class Holli
 *
 * This class creates the option page and add the web app script
 */
class Holli
{
    /**
     * The security nonce
     *
     * @var string
     */
    private $_nonce = 'holli-admin';

    /**
     * The option name
     *
     * @var string
     */
    private $option_name = 'holli_data';

    /**
     * Holli constructor.
     *
     * The main plugin actions registered for WordPress
     */
    public function __construct()
    {
        add_action('product-list', [$this, 'addProductListCode']);
        add_action('product', [$this, 'addProductCode']);
        add_action('area', [$this, 'addAreaCode'], 10, 1);
        add_action('wp_ajax_getProductList', [$this, 'addProductListCode'], 0, 0);
        add_action('wp_ajax_nopriv_getProductList', [$this, 'addProductListCode'], 0, 0);

        add_shortcode('product-list', [$this, 'addProductListCode']);
        add_shortcode('booking-form', [$this, 'addBookingForm']);
        add_shortcode('destinations', [$this, 'addDestinationCode']);
        add_shortcode('category-options', [$this, 'addCategoryOptionsCode']);

        // Admin page calls
        add_action('admin_menu', [$this, 'addAdminMenu']);
        add_action('wp_ajax_store_admin_data', [$this, 'storeAdminData']);
        add_action('admin_enqueue_scripts', [$this, 'addAdminScripts']);
        add_action('wp_enqueue_style', [$this, 'addStyleScripts']);
    }

    /**
     * Returns the saved options data as an array
     *
     * @return array
     */
    private function getOptions()
    {
        return get_option($this->option_name, []);
    }

    /**
     * Callback for the Ajax request
     *
     * Updates the options data
     *
     * @return void
     */
    public function storeAdminData()
    {
        if (wp_verify_nonce($_POST['security'], $this->_nonce) === false) {
            die('Invalid Request! Reload your page please.');
        }

        $data = $this->getOptions();

        foreach ($_POST as $field => $value) {
            if (substr($field, 0, 6) !== 'holli_') {
                continue;
            }

            if (empty($value)) {
                unset($data[$field]);
            }

            // We remove the holli_ prefix to clean things up
            $field = substr($field, 6);

            $data[$field] = esc_attr__($value);
        }

        update_option($this->option_name, $data);

        echo __('Saved!', 'holli');
        die();
    }

    /**
     * Adds Admin Scripts for the Ajax call
     */
    public function addAdminScripts()
    {
        wp_enqueue_style('holli-admin', HOLLI_URL . 'assets/css/admin.css', false, 1.0);

        wp_enqueue_script('holli-admin', HOLLI_URL . 'assets/js/admin.js', [], 1.0);

        $admin_options = [
            'ajax_url' => admin_url('admin-ajax.php'),
            '_nonce' => wp_create_nonce($this->_nonce)
        ];

        wp_localize_script('holli-admin', 'holli_exchanger', $admin_options);
    }

    /**
     * Adds the Holli label to the WordPress Admin Sidebar Menu
     */
    public function addAdminMenu()
    {
        add_menu_page(
            __('Holli API', 'holli'),
            __('Holli API', 'holli'),
            'manage_options',
            'holli',
            [$this, 'adminLayout'],
            'dashicons-admin-generic'
        );
    }

    /**
     * Make an API call to the Holli API and returns the response
     *
     * @return array
     */
    private function getData($resource)
    {
        $options = $this->getOptions();

        $data = [];

        $wp_request_headers = [
                'Authorization' => 'Basic ' . base64_encode(HOLLI_BASICAUTH),
                'X-Authorization:' . $options['api_key'],
                'X-Authorization' => $options['api_key'],
                'Content-Type' => 'application/json'
        ];

        $url = $options['domain'] . '/api/v3/' . $resource ;

        $response = wp_remote_get($url, [
            'headers' => $wp_request_headers
        ]);

        if (is_array($response) && !is_wp_error($response)) {
            $data = json_decode($response['body'], true);
        }

        //var_dump($data);exit;

        return $data;
    }

    /**
     * Get a Dashicon for a given status
     *
     * @param $valid boolean
     *
     * @return string
     */
    private function getStatusIcon($valid)
    {
        return ($valid) ? '<span class="dashicons dashicons-yes success-message"></span>' : '<span class="dashicons dashicons-no-alt error-message"></span>';
    }

    /**
     * Outputs the Admin Dashboard layout containing the form with all its options
     *
     * @return void
     */
    public function adminLayout()
    {
        $data = $this->getOptions();

        $api_response = $this->getData('customers');

        $not_ready = (empty($data['api_key']) || empty($api_response) || isset($api_response['error']));
        $has_engager_preview = (isset($_GET['feedier-demo-engager']) && $_GET['feedier-demo-engager'] === 'go'); ?>

		<div class="wrap">

            <h1><?php _e('Holli Settings - start using the Holli API', 'holli'); ?></h1>

			<?php if ($has_engager_preview): ?>
				<?php $this->addProductCode(true); ?>
                <p class="notice notice-warning p-10">
					<?php _e('The demo engager is enabled. You will see the widget, exactly as it will be displayed on your site.<br> The only difference is that until the preview is turned off it will always come back compared to the live version.', 'holli'); ?>
                </p>
			<?php endif; ?>

            <form id="holli-admin-form" class="postbox">

                <div class="form-group inside">

	                <?php
                    /*
                     * --------------------------
                     * API Settings
                     * --------------------------
                     */
                    ?>

                    <h3>
		                <?php echo $this->getStatusIcon(!$not_ready); ?>
		                <?php _e('Holli API Settings', 'holli'); ?>
                    </h3>

	                <?php if ($not_ready): ?>
                        <p>
                            <?php _e('You can find your api key on your <a href="https://backend.holliapp.com/profile#api" target="_blank">profile page</a>.', 'holli'); ?>
                            <br>
                        </p>
                    <?php else: ?>
		                <?php _e('Access your <a href="https://backend.holliapp.com" target="_blank">Holli dashboard here</a>.', 'holli'); ?>
                    <?php endif; ?>

                    <table class="form-table">
                        <tbody>
                          
                            <tr>
                                <td scope="row">
                                    <label><?php _e('Domain', 'holli'); ?></label>
                                </td>
                                <td>
                                    <input name="holli_domain"
                                           id="holli_domain"
                                           class="regular-text"
                                           type="text"
                                           value="<?php echo (isset($data['domain'])) ? $data['domain'] : ''; ?>"/>
                                </td>
                            </tr>

                              <tr>
                                <td scope="row">
                                    <label><?php _e('API key', 'holli'); ?></label>
                                </td>
                                <td>
                                    <input name="holli_api_key"
                                           id="holli_api_key"
                                           class="regular-text"
                                           type="text"
                                           value="<?php echo (isset($data['api_key'])) ? $data['api_key'] : ''; ?>"/>
                                </td>
                            </tr>

                        </tbody>
                    </table>

                </div>

	            <?php if (!empty($data['api_key']) && !empty($data['domain'])): ?>

                    <?php
                    // if we don't even have a response from the API
                    if (empty($api_response)) : ?>
                        <p class="notice notice-error">
                            <?php _e('An error happened on the WordPress side. Make sure your server allows remote calls.', 'holli'); ?>
                        </p>

                    <?php
                    // If we have an error returned by the API
                    elseif (isset($api_response['error'])): ?>

                        <p class="notice notice-error">
                            <?php echo $api_response['error']; ?><br>
                            <span style="color:#ccc"><?php echo var_dump($api_response) ?></span>
                        </p>

                    <?php
                    // If the products were returned
                    else: ?>

                        <?php
                        /*
                         * --------------------------
                         * Display Options
                         * --------------------------
                         */
                        ?>

                        <hr>

                        <div class="form-group inside">

                            <h3>
	                            <?php echo $this->getStatusIcon(isset($data['widget_carrier_id'])); ?>
                                <?php _e('Display options', 'holli'); ?>
                            </h3>

                            <table class="form-table">
                                <tbody>

                                    <tr>
                                        <td scope="row">
                                            <label>
                                                <?php _e('Products', 'holli'); ?>
                                            </label>
                                        </td>
                                        <td>
                                            <input name="feedier_widget_display_probability"
                                                   id="feedier_widget_display_probability"
                                                   type="text"
                                                   size="4"
                                                   class="regular-text"
                                                   value="<?php echo (isset($data['widget_display_probability'])) ? esc_attr__($data['widget_display_probability']) : '100'; ?>"/>
                                        </td>
                                    </tr>
                                
                                </tbody>
                            </table>

                        </div>

                    <?php endif; ?>

                <?php endif; ?>

                <hr>

                <div class="inside">

                    <button class="button button-primary" id="holli-admin-save" type="submit">
                        <?php _e('Save', 'holli'); ?>
                    </button>

                    <?php if (!$not_ready): ?>

                        <?php if ($has_engager_preview): ?>
                            <a href="<?php echo admin_url('admin.php?page=feedier'); ?>" class="button">
			                    <?php _e('Stop Preview', 'holli'); ?>
                            </a>
                        <?php else: ?>
                            <a href="<?php echo add_query_arg('feedier-demo-engager', 'go'); ?>" class="button">
                                <?php _e('Open Preview', 'holli'); ?>
                            </a>
                        <?php endif; ?>

                    <?php endif; ?>

                </div>

            </form>

		</div>

		<?php
    }

    /**
     * Add the web app code to the page
     *
     * This contains the widget markup used by the web app and the widget API call on the frontend
     * We use the options saved from the admin page
     *
     * @param $force boolean
     *
     * @return void
     */
    public function addProductListCode()
    {
        ob_start();
//$_GET['category_id']); exit;
        $area = $_GET['area'];
        $category_id = $_GET['category_id'];
        $destination_id = $_GET['destination_id'];
        $offset = $_POST['offset'];

        $data = $this->getData('products?offset=' . $offset . '&limit=12&recommended=true&area=' . $area . '&category_id=' . $category_id . '&zone_id=' . $destination_id . '&lang=' . pll_current_language());

        if (!$data) {
            echo '[no data found]';
        }

        echo '<div  class="ppb_tour_classic one nopadding " style="margin-bottom:50px;" >';
        echo '<div class="page_content_wrapper page_main_content sidebar_content full_width fixed_column">';
        echo '<div class="standard_wrapper">';

        //echo '<div class="portfolio_filter_wrapper gallery classic four_cols" data-columns="4">';

        foreach ($data['data'] as $product) {
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

        echo '</div></div></div>'; ?>
           
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
        jQuery(document).on('click','.show_more',function(){
		var offset = jQuery(this).attr('id');
		console.log(offset);
		jQuery('.show_more').hide();
		jQuery('.loding').show();
        jQuery.ajax({
			type: 'POST',
                            url: "<?php echo admin_url('admin-ajax.php'); ?>",
                            data: {
								action: 'getProductList',
								offset: offset,
						
							},
                            success: function( data ) {
								jQuery('.products'+offset).append(data);
								jQuery('.loding').hide();
							}
                });
            });
        });
        </script> -->
<?php
    }

    /**
    * Add the web app code to the page
    */
    public function addBookingForm()
    {
        //Include tour content
        get_template_part('/templates/template-booking-form');
    }

    /**
    * Add the web app code to the page
    */
    public function addCategoryOptionsCode()
    {
        ob_start();

        $categories = apiGetRequest('product/categories');

        echo '<form action="' . get_site_url() . '/tours" method="get"><div>';
        foreach ($categories as $category) {
            echo '<input type="checkbox" id="scales" name="category_id[]" value="' . $category['id'] . '"> ';
            echo $category['name']  ;
            echo '<br>';
        }
        echo '<br>';
        echo '<input type="submit" value="Filter Results" class="submit full_width" id="submitform">';
        echo '</div>';
        return ob_get_clean();
    }

    /**
     * Add the web app code to the page
     *
     * This contains the widget markup used by the web app and the widget API call on the frontend
     * We use the options saved from the admin page
     *
     * @param $force boolean
     *
     * @return void
     */
    public function addAreaCode($name)
    {
        $name = str_replace('-', ' ', $name);
        $data = $this->getData('products?zone_id=' . get_field('zone_id'));

        //var_dump($data);

        if (!$data) {
            echo '[no data found]';
        }

        echo '<div  class="ppb_tour_classic one nopadding " style="margin-bottom:50px;" >';
        echo '<div class="page_content_wrapper page_main_content sidebar_content full_width fixed_column">';
        echo '<div class="standard_wrapper">';

        echo '<div class="portfolio_filter_wrapper gallery classic four_cols" data-columns="4">';

        foreach ($data['data'] as $product) {
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
     * Add the Product details code to the page
     *
     * This contains the code for the Product details
     *
     * @param $force boolean
     *
     * @return void
     */
    public function addProductCode()
    {
        $data = $this->getData('products/' . $_GET['pid']);

        return($product);
    }

    /**
    * Add the Product details code to the page
    *
    * This contains the code for the Product details
    *
    * @param $force boolean
    *
    * @return void
    */
    public function addDestinationCode()
    {
        ob_start();

        $zones = apiGetRequest('zones'); ?>
        
        <div class="tags_wrapper">
        <?php
        foreach ($zones as $zone) {
            echo '<span class="tagbox"><a href="';
            echo get_site_url();
            echo '/search?destination_id=';
            echo $zone['id'];
            echo '"><div class="linkdiv"><h5>';
            echo $zone['name'];
            echo '</h5></div></a></span>';
        }
        echo '<br class="clear"></div>';

        return ob_get_clean();
    }
}

/*
 * Starts our plugin class, easy!
 */
new Holli();

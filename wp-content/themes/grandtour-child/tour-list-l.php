<?php
/**
 * Template Name: Tour List Left Sidebar
 * The main template file for display tour page.
 *
 * @package WordPress
*/

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

$grandtour_homepage_style = grandtour_get_homepage_style();

//Get page sidebar
$page_sidebar = get_post_meta($current_page_id, 'page_sidebar', true);

if (empty($page_sidebar)) {
    $page_sidebar = 'Page Sidebar';
}

get_header();

$grandtour_page_content_class = grandtour_get_page_content_class();

//Include custom header feature
get_template_part('/templates/template-header');

//Include custom tour search feature
//get_template_part('/templates/template-tour-search');
?>

<!-- Begin content -->
<?php
    //Get all portfolio items for paging
    $wp_query = grandtour_get_wp_query();
    $current_photo_count = $wp_query->post_count;
    $all_photo_count = $wp_query->found_posts;
?>
    
<div class="inner">

	<div class="inner_wrapper nopadding">
	
	<?php
        if (!empty($post->post_content) && empty($term)) {
            ?>
	    <div class="standard_wrapper"><?php echo grandtour_apply_content($post->post_content); ?></div><br class="clear"/><br/>
	<?php
        }
    ?>
	
	<div id="page_main_content" class="sidebar_content left_sidebar fixed_column">
	
	<div class="standard_wrapper">
	
	<div id="portfolio_filter_wrapper" class="gallery classic three_cols portfolio-content section content clearfix" data-columns="3">
	
    <?php echo do_shortcode('[product-list]'); ?>
		
	</div>
	<br class="clear"/>
	<?php
        if ($wp_query->max_num_pages > 1) {
            if (function_exists('grandtour_pagination')) {
                grandtour_pagination($wp_query->max_num_pages);
            } else {
                ?>
	    	    <div class="pagination"><p><?php posts_nav_link(' '); ?></p></div>
	    	<?php
            } ?>
	    <div class="pagination_detail">
	     	<?php
                 $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; ?>
	     	<?php esc_html_e('Page', 'grandtour'); ?> <?php echo esc_html($paged); ?> <?php esc_html_e('of', 'grandtour'); ?> <?php echo esc_html($wp_query->max_num_pages); ?>
	     </div>
	     <?php
        }
    ?>
	
	</div>
	</div>

	<div class="sidebar_wrapper left_sidebar">
	 <div class="sidebar">
	 
	  <div class="content">
	 
	  	<?php 
        $page_sidebar = sanitize_title($page_sidebar);

        if (is_active_sidebar($page_sidebar)) {
            ?>
	     		<ul class="sidebar_widget">
	     		<?php dynamic_sidebar($page_sidebar); ?>
	     		</ul>
	     	<?php
        } ?>
	  
	  </div>
	 
	 </div>
	</div>

</div>
</div>
</div>
<?php get_footer(); ?>
<!-- End content -->
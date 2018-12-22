<?php
session_start();
/**
 * Template Name: Tours
 * The main template file for display tour page.
 *
 * @package WordPress
*/

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
				<h1 <?php if (!empty($pp_page_bg) && !empty($grandtour_topbar)) {
                ?>class ="withtopbar"<?php
            } ?>><?php echo esc_html($page_title); ?></h1>
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

do_action('product-list'); ?>
		<div class="btn_wrapper">
			<a href="#/" class="button show_more" id="12">Show More</a>
			<span class="loding" style="display: none;"><span class="loding_txt">
				<img src="<?php echo get_stylesheet_directory_uri() ?>/img/loader.gif" width="100" height="100" /></span>
			</span>
        </div>
        <div class="products12"></div>
    	<!-- End main content -->
    </div> 
</div>
<?php
    }
?>
<?php get_footer(); ?>

<script type="text/javascript">
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
</script>

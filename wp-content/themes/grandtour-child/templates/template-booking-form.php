<?php
// Get current product
$product = array_shift(apiGetRequest('products/' . $_GET['pid']));

?>

<form action="<?php echo lang_url()  ?>checkout" method="POST">
<script>var api_url = 'http://ticketandtours.test/api/v4'; </script>

<?php if ($product['type'] == 'tour' || $product['scheduleRequired'] ) {
    $dates = apiGetRequest('products/' . $_GET['pid'] . '/dates');

    echo do_shortcode('[calendar]');
    ?>
    <div id="calendar-widget"><calendar-component :schedule-data='<?php echo json_encode($dates) ?>' :product-id="<?php echo $product['id'] ?>"></calendar-component></div><br>
    <?php

}

?>

<?php foreach ($product['prices'] as $price) {
    ?>
    <div class="tour_product_variable_wrapper">
 		<div class="tour_product_variable_title">
             <div class="title_wrapper">
           <?php echo mb_strimwidth($price['name'], 0, 20, '..'); ?>
           <?php echo $price['availableTickets'] === 0 ? '<br><span class="error">Sold Out<span>' : '<br><span class="subtitle">' . $price['description'] . '</span>' ?>
        </div>
         </div>
 		<div class="tour_product_variable_qty">
 			<input type="number" class="price" data-amount="<?php echo $price['currentPrice']; ?>" name="price[<?php echo $price['ticketId']; ?>]" id="<?php echo $price['ticketId']; ?>" value="0" min="0" max="<?php echo $price['availableTickets']; ?>">
 		</div>
 		&nbsp;x&nbsp;
 		<div id="tour_product_variable_price_3512" class="tour_product_variable_price" data-price="6000">
            <span class="amount"><span>&euro;</span><?php echo $price['currentPrice']; ?></span> 								
        </div>
    </div>
<?php
} ?>
<div class="tour_product_variable_title">
<?php pll_e('Free'); ?>
<br><span class="subtitle"><?php echo $product['ageDescription']; ?></span>
</div>

<div class="single_tour_view_wrapper themeborder">
     			<div class="single_tour_view_desc">
                 <div class="tour_product_variable_title">Total </div>			</div>
     			
                     <div>
                       <span class="total-price"><span>&euro;</span><span class="total">0.00</span> 								
                     </div>

                      <div>
                        <span class="count"></span> 								
                     </div>

     		</div>
                             
    <p>
    <input type="hidden" name="tour_id" value="" id="tour_id">
    <input type="hidden" name="productId" value="<?php echo $product['id']; ?>" >
    <input type='hidden' name='post_id' value="<?php echo $product['id']; ?>">
    <input type="submit" value="<?php pll_e('Book Now'); ?>" class="submit" id="submitform">
    </p>

</form>

<script>
    jQuery(".price").on("change", function(){
  var total = 0;
  jQuery(".price").each(function(){
      var val = jQuery(this).attr("data-amount");
      total = total + (jQuery(this).val()*jQuery(this).attr("data-amount") || 0);
  });
  jQuery(".total").text(total.toFixed(2));
});
</script>

<?php if ($product['type'] == 'tour') {
        ?>
<script>
    jQuery(".price").on("change", function(){
  var count = Number(0);
  jQuery(".price").each(function(){
      var val = jQuery(this).val();
      count = count + (Number(jQuery(this).val())||0);
  });
  if(count > jQuery("#available").val()){
  jQuery(".count").text('No more tickets available!');
  jQuery("#submitform").addClass('disabled');
  jQuery("#submitform").attr("disabled", "disabled")
  }else{
    jQuery(".count").text('');  
    jQuery("#submitform").removeClass('disabled');
    jQuery("#submitform").removeAttr("disabled", "disabled")
  }
});
</script>
<?php
    } ?>

<script>
    jQuery(".select_date").on("click", function(){
        jQuery('.selected').removeClass('selected');
        jQuery(this).addClass('selected');
        jQuery('#date').val(jQuery(this).data('date'));
        jQuery('#tour_id').val(jQuery(this).data('tour_id'));
        jQuery('#available').val(jQuery(this).data('available'));
});
</script>

<?php
// Get current product
$product = array_shift(apiGetRequest('products/' . $_GET['pid']));
?>

<form action="<?php echo site_url() ?><?php echo lang_url()  ?>checkout" method="POST">

<?php if ($product['type'] == 'tour') {
    $date = $_GET['date'] ?: date('Y-m-d');
    $month = date('m', strtotime($date));
    $year = date('Y', strtotime($date));
    $month_name = date('F', strtotime($date)); ?>

<div class="calendar-wrapper"> 
<div class="month"> 
  <ul>
<?php if ($date > date('Y-m-d')) {
        ?>
    <a href="<?php echo parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)?>?pid=<?php echo $product['productId'] ?>&date=<?php echo date('Y-m-d', strtotime('-1 month', strtotime($date))); ?>"><li class="prev">&#10094;</li></a>
<?php
    } else {
        echo '<li class="prev hidden">&#10094;</li>';
    } ?>
<a href="<?php echo parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)?>?pid=<?php echo $product['productId'] ?>&date=<?php echo date('Y-m-d', strtotime('+1 month', strtotime($date))); ?>"><li class="next">&#10095;</li></a>
    <li><span class="monthName"><?php echo $month_name ?> <?php echo $year ?></span></li>
  </ul>
</div>

<ul class="weekdays">
  <li>Mon</li>
  <li>Tue</li>
  <li>Wed</li>
  <li>Thu</li>
  <li>Fri</li>
  <li>Sat</li>
  <li>Sun</li>
</ul>

<ul class="days"> 
<?php
$running_day = date('w', mktime(0, 0, 0, $month, 1, $year));
    $days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));
    $x = 1;

    // Print blank days until the first of the current week
    for ($x = 1; $x < $running_day; $x++) {
        echo '<li></li>';
    }
    // Print days of the month
    for ($day = 1; $day <= $days_in_month; $day++) {
        $full_date = $year . '-' . $month . '-' . $day;
        // Check if the day is in the available tours tours
        $tour_date = in_array($full_date, array_column($product['tours'], 'date'));

        if ($tour_date) {
            $tour = $product['tours'][array_search($full_date, array_column($product['tours'], 'date'))];
        }
        echo '<li>';
        if ($tour_date) {
            echo '<a href="javascript:;" class="select_date" data-date="' . $full_date . '" data-tour_id="' . $tour['tourId'] . '" data-available="' . $tour['availableTickets'] . '"tooltip="' . $tour['availableTickets'] . ' Available">';
        }
        echo '<span class="' . ($full_date == date('Y-m-d') ? 'active' : '') . ($tour_date ? 'available' : '') . '">' . $day . '</span></li>';
        if ($tour_date) {
            echo '</a>';
        }
    }
    echo '</ul></div>';
}
?>
  
<?php foreach ($product['prices'] as $price) {
    ?>
    <div class="tour_product_variable_wrapper">
 		<div class="tour_product_variable_title">
           <?php echo mb_strimwidth($price['name'], 0, 22, '...');
    ; ?>
           <?php echo $price['availableTickets'] === 0 ? '<br><span class="error">Sold Out<span>' : null ?>
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
    <input type="hidden" name="date" value="" id="date">
    <input type="hidden" name="tour_id" value="" id="tour_id">
    <input type="hidden" name="available" value="" id="available">
    <input type="hidden" name="productId" value="<?php echo $product['productId']; ?>" >
    <input type='hidden' name='post_id' value="<?php echo $product['productId']; ?>">
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

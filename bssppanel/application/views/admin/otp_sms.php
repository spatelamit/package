 <br>
  <script src="https://s3.amazonaws.com/thurgoimages/hire/js/bootstrap-datepicker.min.js"></script>
<style>
#datepicker_1 {
  width: 516px;
}
</style>
<div class="container">
  <div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-11">
   <form  method="post" action="<?php echo base_url(); ?>process/send_otp_sms" autocomplete="off">
    <h2 >Send Message</h2>
    <br>
   <div class="form-group" style="margin-bottom: 0px;" >
      <label for="from_date" style="font-size: 18px;">Select Date:</label>
      <!-- <input type="date" class="form" id="date" placeholder="Enter email" name="from_date"> -->
    </div>
    <div class="input-daterange input-group" id="datepicker_1">
    <input type="text" class="form-control" value="2012-04-05" name="to_date">
    <div class="input-group-addon">To</div>
    <input type="text" class="form-control" value="2012-04-19"  name="from_date">
</div>
   

    <div class="form-group">
      <label for="Message" style="font-size: 18px; ">Message:</label>
     <textarea ype="text" name="message" class="form-control" id="message" placeholder="Enter Message" style="width:50%;" rows="5"></textarea>
    </div>

    <input type="submit" name="submit" value="Send" class="btn btn-default" style="background-color: royalblue ;color: white ;border: 0; font-weight: bold ;">
     
 </div>
</div>
</div>
 
<script>
$('.input-daterange').datepicker({
    todayBtn: true,
    autoclose: true
});

$('.input-daterange input').each(function() {
    $(this).datepicker('clearDates');
});

/***********************************************************/
/*     Bootstrap date picker from:
/*     https://github.com/uxsolutions/bootstrap-datepicker */
/***********************************************************/

</script>
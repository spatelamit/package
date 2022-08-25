
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
  <form  method="post" action="<?php echo base_url(); ?>process/meeting_mail"  autocomplete="off" >
    <h2 >Send Meeting Email</h2>
    <br>
    <!--  <div class="form-group">
      <label for="to_date" style="font-size: 18px; ">To Date:</label>
      <input type="date" class="form" id="date" placeholder="Enter email" name="to_date">
    </div> -->

   <div class="form-group" style="margin-bottom: 0px;" >
      <label for="from_date" style="font-size: 18px;">Select Date:</label>
      <!-- <input type="date" class="form" id="date" placeholder="Enter email" name="from_date"> -->
    </div>
    <div class="input-daterange input-group" id="datepicker_1">
    <input type="text" class="form-control" value="2012-04-05" name="to_date">
    <div class="input-group-addon">To</div>
    <input type="text" class="form-control" value="2012-04-19"  name="from_date">
</div>
    <div class="form-group" style="margin-top: 15px;" >
      <label for="from_date" style="font-size: 18px; ">Reply to:</label>
      <br>
      <textarea ype="text" name="reply" class="form-control" id="reply" placeholder="Enter Email to reply" name="to" style="width:50%;" ></textarea>
    </div>

   <div class="form-group">
      <label for="to" style="font-size: 18px; ">Subject:</label>
     <textarea ype="text" name="subject" class="form-control" id="email" placeholder="Enter subject" name="to" style="width:50%;" rows="4"></textarea>
    </div>
    <div class="form-group">
      <label style="font-size: 18px; ">Select Tamplate:</label>
      <br>
      <input type="radio" name="tempalte" value="Dear Valuable Customers,
We are pleased to announce the launch of our newly revamped website https://bulksmsserviceproviders.com, that aims to create a user-friendly browsing experience for our trusted and valued customers.

In addition to re-designing our website, we have also refined the product category and menu structure for an enhanced user experience in accessing information relating to our products.

We invite you to view the new website and let us know your thoughts. If you have any questions or feedback you would like to share with our team, please do so by filling out the form on our Contact Us page. 
--
Team Bulksmsserviceproviders
+918982-805000 " required="" type="button" class="btn btn-primary mybtn " data-toggle="modal" data-target="#myModal">&nbsp<span ><b>New Design Template</b></span>&nbsp&nbsp&nbsp
      <input type="radio" name="tempalte" value="Welcome
       to Bulksmsserviceproviders.com
--
Team Bulksmsserviceproviders
+918982-805000" required="" data-toggle="modal" data-target="#myModals">&nbsp<span ><b>Basic Tamplate</b></span>

    </div>
  <!-- <div class="form-group">
      <label for="message">message:</label>
      <input type="text" class="form-control" id="pwd" placeholder="Enter message" name="message" style="width: 50%;">
   </div> -->
     <input type="submit" name="submit" value="Send" class="btn btn-default" style="background-color: royalblue ;color: white ;border: 0; font-weight: bold ;">
     
 </div>
</div>
</div>

 

   <div class="modal fade" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
        <center>  <span class="modal-title">New Design Template</span></center>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
  
    <div class="form-div">
<pre style="font-size: 16px; color: black; font-family: sans-serif;">
Dear Valuable Customers,
We are pleased to announce the launch of our newly revamped website https://bulksmsserviceproviders.com, that aims to create a user-friendly browsing experience for our trusted and valued customers.

In addition to re-designing our website, we have also refined the product category and menu structure for an enhanced user experience in accessing information relating to our products.

We invite you to view the new website and let us know your thoughts. If you have any questions or feedback you would like to share with our team, please do so by filling out the form on our Contact Us page. 
--
Team Bulksmsserviceproviders
+918982-805000  
</pre>
    </div>
        </div>
        
       
      </div>
    </div>
  </div>


<div class="modal fade" id="myModals">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
        <center>  <span class="modal-title">Basic Tamplate</span></center>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
  
    <div class="form-div">
<pre style="font-size: 16px; color: black; font-family: sans-serif;">
Welcome
       to Bulksmsserviceproviders.com
       to Bulksmsserviceproviders.com 
       to Bulksmsserviceproviders.com
       to Bulksmsserviceproviders.com
--
Team Bulksmsserviceproviders
+918982-805000 
</pre>
    </div>
        </div>
        
       
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
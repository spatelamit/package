<?php

error_reporting(0);

$hostname = "localhost";

$username = "bulksms_user";

$password = "BALAJI@sr#ts7828";

$database = "bulksms_system";





$conn = mysql_connect("$hostname", "$username", "$password") or die(mysql_error());

mysql_select_db("$database", $conn) or die(mysql_error());

?>



<div class="page-content-title txt-center">

    <h3><i class="icon-chevron-sign-up"></i><i class="fa fa-sign-in" aria-hidden="true"></i> OTP TEST</h3> 





</div>




<!--<div style="margin-left: 750px;" class="col-md-3 padding15">

     <div class="form-group"><input class="form-control " type = "input" id = "name" size = "30" placeholder="Search Approval Payment By Date"  />

     </div>

 </div>

        <div class="col-md-1 padding15">

            <div class="form-group"> 

    <input class="btn btn-primary" data-loading-text="Searching..." autocomplete="off" type = "button" id = "driver" value = "GET REPORT" style="background-color: #2780E3; color: white; height: 33px;" /></div>

        </div>--><br/>
 <div class="row">
<div class="col-md-3">
        
    </div>
     <div class="col-md-3">
        
    </div>
     <div class="col-md-3">
        
    </div>
     <div class="col-md-3">
<a class="btn-primary" href="<?php echo base_url(); ?>admin/otp_sms" target="_blank" ><input style="background-color: royalblue ;color: white ;border: 0; font-weight: bold ; height: 25px;width: 90px;" type="submit"  name="submit" value="SMS"/></a>

</div>

</div>
<div class="table-responsive" class="col-md-6" id="search_payment_report_table" >

    

    <div class="tab-content bgf9" >

        <div class="tab-pane fade in active" >

            <div class="table-responsive" >

                <form role="form" id="sellerReportForm" method="post" action="http://sms.bulksmsserviceproviders.com/admin/get_otp_test" class="notify-forms">



    <div class="col-md-4 padding15">

        <div class="input-daterange input-group" id="">

            <input type="text" class="form-control " name="from_date" id="from_date" placeholder="Start Date">

            <span class="input-group-addon">to</span>

            <input type="text" class="form-control " name="to_date" id="to_date" placeholder="End Date"> 



        </div>

    </div>





    <div class="col-md-1 padding15">

        <div class="form-group">   

            <button id="click_report" class="btn btn-primary" data-loading-text="Searching..." autocomplete="off" type="submit">

                Get Report

            </button>

        </div>                        

    </div>

</form>

                <table class="table table-hover table-bordered" id="stage"  >

                    <thead>

                        <tr>





                            <th style="text-align: center">SR No</th>

                            <th style="text-align: center">Mobile Number</th>

                            <th style="text-align: center">Date/Time</th>

                            <th style="text-align: center">Status</th>



                        </tr>

                    </thead>

                    <?php

                    $i = 1;

                    if (isset($otp_test) && $otp_test) {



                        foreach ($otp_test as $otp_test_log) {

                            ?>

                            <tr>



                                <td style="text-align: center"><?php echo $i; ?>

                                </td>

                                <td style="text-align: center"><?php echo $otp_test_log['mobile_no']; ?>

                                </td>

                                <td style="text-align: center"><?php echo $otp_test_log['submit_date']; ?>

                                </td>



                                <td style="text-align: center"><?php echo $otp_test_log['status']; ?>

                                </td>



                            </tr>

                            <?php

                            $i++;

                        }

                    } else {

                        ?>

                        <tr>

                            <td align="center" colspan="10" style=" font-weight: bold;"><?php echo "No Record Found"; ?></td> 

                        </tr>                        

                        <?php

                    }

                    ?>







                </table>



                <!-- Pagination -->



            </div>





        </div>

    </div>

</div>



<script type="text/javascript">

    $(document).ready(function ()

    {

     

          var nowDate = new Date();

        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), nowDate.getHours(), nowDate.getMinutes(), 0, 0);

        $('#from_date').datetimepicker({

            format: "yyyy-mm-dd hh:ii:00",

            autoclose: true,

            todayBtn: true,

            minuteStep: 5,

            endDate: today,

            todayHighlight: true

        });

        

          var nowDate = new Date();

        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), nowDate.getHours(), nowDate.getMinutes(), 0, 0);

        $('#to_date').datetimepicker({

            format: "yyyy-mm-dd hh:ii:00",

            autoclose: true,

            todayBtn: true,

            minuteStep: 5,

            endDate: today,

            todayHighlight: true

        });



    });

</script>
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

    <h3><i class="icon-chevron-sign-up"></i><i class="fa fa-sign-in" aria-hidden="true"></i> Daily SignUp logs</h3> 






</div>
<div class="col-md-3">
        
    </div>
     <div class="col-md-3">
        
    </div>
     <div class="col-md-3">
        
    </div>
     <div class="col-md-3">
<a class="btn-primary" href="<?php echo base_url(); ?>admin/daily_sign_mail" target="_blank" ><input style="background-color: royalblue ;color: white ;border: 0; font-weight: bold ; height: 25px;width: 90px;" type="submit"  name="submit" value="Mailer"/></a>
&nbsp&nbsp
<a class="btn-primary" href="<?php echo base_url(); ?>admin/daily_sign_sms" target="_blank" ><input style="background-color: royalblue ;color: white ;border: 0; font-weight: bold ; height: 25px;width: 90px;" type="submit"  name="submit" value="Send Sms"/></a>

</div>

    <form role="form" id="sellerReportForm" method="post" autocomplete="off" action="http://sms.bulksmsserviceproviders.com/admin/get_signup_test" class="notify-forms">



    <div class="col-md-4 padding15">

        <div class="input-daterange input-group" id="">

            <input type="text" class="form-control " name="from_date" id="from_date" placeholder="Start Date">

          

           


        </div>

    </div>





    <div class="col-md-1 padding15">

        <div class="form-group">   

            <button id="click_report" class="btn btn-primary" data-loading-text="Searching..." autocomplete="off" type="submit">

                Show Data

            </button>

        </div>                        

    </div>

</form>

<!--<div style="margin-left: 750px;" class="col-md-3 padding15">

     <div class="form-group"><input class="form-control " type = "input" id = "name" size = "30" placeholder="Search Approval Payment By Date"  />

     </div>

 </div>

        <div class="col-md-1 padding15">

            <div class="form-group"> 

    <input class="btn btn-primary" data-loading-text="Searching..." autocomplete="off" type = "button" id = "driver" value = "GET REPORT" style="background-color: #2780E3; color: white; height: 33px;" /></div>

        </div>--><br/>



<div class="table-responsive" class="col-md-6" id="search_payment_report_table" style=" overflow-y: scroll ; width: 1350px;">

    <div class="tab-content bgf9" >

        <div class="tab-pane fade in active" >

            <div class="table-responsive" >

                <table class="table table-hover table-bordered" id="stage"  >

                    <thead>

                        <tr>





                            <th>Name</th>

                            <th>Username</th>

                            <th>E-mail</th>

                            <th>Contact</th>

                            <th>Signup Date</th>



                            <th>User Type</th>

                            <th>Company</th>

                            <th>Country</th>

                            <th>Lead By</th>

                            <th>Feedback</th>

                            <th>Action</th>





                        </tr>

                    </thead>

                    <?php

                    if (isset($daily_signup) && $daily_signup) {



                        foreach ($daily_signup as $daily_signup_log) {

                            ?>

                            <tr>



                                <td>

                                    <?php echo $daily_signup_log['name']; ?>

                                </td>

                                <td style="word-break: break-all; width: 400px;"><?php echo $daily_signup_log['username']; ?> </td>



                                <td style="word-break: break-all; width: 400px;">

                                    <?php echo $daily_signup_log['email_address']; ?>

                                </td> 

                                <td><?php echo $daily_signup_log['contact_number']; ?>

                                </td>

                                <td><?php echo $daily_signup_log['creation_date']; ?>

                                </td>

                                <td><?php echo $daily_signup_log['utype']; ?>

                                </td>



                                <td><?php echo $daily_signup_log['company_name']; ?>

                                </td>

                                <td><?php echo $daily_signup_log['country']; ?>

                                </td>

                            <form method="post" action="<?php echo base_url(); ?>admin/update_feedback"> 



                                <td>



                                    <select name="lead_by" value="" required="required">

                                        <option value="0">Select Admin Name</option>

                                        <?php

                                        if (isset($admin_name) && $admin_name) {



                                            foreach ($admin_name as $name) {

                                                if ($name['admin_name'] == $daily_signup_log['lead_by']) {

                                                    ?>

                                                    <option selected="" value="<?php echo $name['admin_name']; ?>"><?php echo $name['admin_name']; ?></option>

                                                    <?php

                                                }

                                                ?> 

                                                }

                                                ?>



                                                <option value="<?php echo $name['admin_name']; ?>"><?php echo $name['admin_name']; ?></option>

                                                <?php

                                            }

                                        }

                                        ?> 

                                    </select>

                                </td>



                                <td><textarea name="feedback" value="" placeholder="Written Feedback Of User " required="required"><?php echo $daily_signup_log['feedback']; ?></textarea>

                                </td>



                                <td>

                                    <input type="hidden"  name="user_id" value="<?php echo $daily_signup_log['user_id']; ?>"/>





                                    <b></b>



                                    <input style="background-color: royalblue ;color: white ;border: 0; font-weight: bold ; height: 25px;width: 90px;" type="submit"  name="submit" value="save"/>



                                </td>

                            </form>

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

            format: "yyyy-mm-dd",

            autoclose: true,

            todayBtn: true,

            minuteStep: 5,

            endDate: today,

            todayHighlight: true

        });

        

          var nowDate = new Date();

        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), nowDate.getHours(), nowDate.getMinutes(), 0, 0);

        $('#to_date').datetimepicker({

            format: "yyyy-mm-dd ",

            autoclose: true,

            todayBtn: true,

            minuteStep: 5,

            endDate: today,

            todayHighlight: true

        });



    });

</script>
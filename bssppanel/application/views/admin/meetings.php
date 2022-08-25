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
    <h3><i class="icon-chevron-sign-up"></i><i class="fa fa-sign-in" aria-hidden="true"></i> Scheduled Meetings</h3> 


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
     

       <a class="btn-primary" href="<?php echo base_url(); ?>admin/meeting_mail" target="_blank" ><input style="background-color: royalblue ;color: white ;border: 0; font-weight: bold ; height: 25px;width: 90px;" type="submit"  name="submit" value="Mailer"/></a>
   &nbsp&nbsp
 <a class="btn-primary" href="<?php echo base_url(); ?>admin/meeting_sms" target="_blank" ><input style="background-color: royalblue ;color: white ;border: 0; font-weight: bold ; height: 25px;width: 90px;" type="submit"  name="submit" value="Send Sms"/></a>
 
   </div>
</div>
<div class="table-responsive" class="col-md-6" id="search_payment_report_table" >

    <div class="tab-content bgf9" >
        <div class="tab-pane fade in active" >
            <div class="table-responsive" >
                <!--                <form role="form" id="sellerReportForm" method="post" action="http://sms.bulksmsserviceproviders.com/admin/get_otp_test" class="notify-forms">
                
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
                </form>-->
                <table class="table table-hover table-bordered" id="stage"  >
                    <thead>
                        <tr>


                            <th>SR No</th>
                            <th>Email Address</th>
                            <th>Mobile Number</th>
                            <th>connect</th>
                            <th>Schedule Time</th>
                            <th>DateTime</th>
                            <th>Lead By</th>
                            <th>Feedback</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                            if (isset($subscribe_data) && $subscribe_data) {
                                $i = 1;

                                foreach ($subscribe_data as $subscribe_data_row) {
                                    ?>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $subscribe_data_row['email']; ?></td>
                                    <td><?php echo $subscribe_data_row['mobileNumber']; ?></td>
                                    <td><?php echo $subscribe_data_row['connect']; ?></td>
                                    <td><?php echo $subscribe_data_row['ScheduleTime']; ?></td>
                                    <td><?php echo $subscribe_data_row['DateTime']; ?></td>


                                    
                             <form method="post" action="<?php echo base_url(); ?>admin/update_meeting_feedback"> 

                                <td style="text-align: center">

                                    <select name="admin_name" value="" required="required">
                                        <option value="0">Select Admin Name</option>
                                        <?php
                                        if (isset($admin_name) && $admin_name) {

                                            foreach ($admin_name as $name) {
                                                if ($name['admin_name'] == $subscribe_data_row['admin_name']) {
                                                    ?>
                                                    <option selected="" value="<?php echo $name['admin_name']; ?>"><?php echo $name['admin_name']; ?></option>
                                                    <?php
                                                }
                                                ?>

                                                <option value="<?php echo $name['admin_name']; ?>"><?php echo $name['admin_name']; ?></option>
                                                <?php
                                            }
                                        }
                                        ?> 
                                    </select>
                                </td>


                                
                                    <td><input type="text" name="feedback" value="<?php echo $subscribe_data_row['feedback']; ?>" placeholder="Feedback" required="required">
                                    </td>
                               
                                <td >
                                    <input type="hidden"  name="meeting_id" value="<?php echo $subscribe_data_row['Id']; ?>"/>

                                        <input style="background-color: royalblue ;color: white ;border: 0; font-weight: bold ; height: 25px;width: 90px;" type="submit"  name="submit" value="save"/>
                             
                                </td>
                            </form>
                            </tr>
                            <?php
                            $i++;
                        }
                    } else {
                        ?>
                        <td>No Record Found</td>     
                    <?php }
                    ?>
                    </tbody>

                    <tfoot>
                        <tr>
                            <td class="text-center" colspan="11">
                                <ul class="pagination margin0">
                                    <?php echo $pagination_helper; ?>
                                </ul>
                            </td>
                        </tr>
                    </tfoot>


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
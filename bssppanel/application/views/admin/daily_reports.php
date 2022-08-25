<script src="<?php echo base_url(); ?>Assets/admin/js/highcharts.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>Assets/admin/js/exporting.js" type="text/javascript"></script>
<div class="page-content-title txt-center">




    <h3><i class="fa fa-file-text"></i>  Daily Reports  </h3>

    <div class="col-md-12 col-sm-12 col-xs-12">
        <form role="form" id="dailyReportForm" method='post' action="javascript:getAllDailyReport();" class="notify-forms" autocomplete="off">
            <div class="col-md-4 padding15">
                <input type="text" id="date" name="date" placeholder="Enter Date" class="form-control" />

            </div>

            <div class="col-md-1 padding15">
                <button name="get_sms_btn" id="get_report_btn" class="btn btn-primary"
                        data-loading-text="Fetching..." autocomplete="off" type="submit">
                    Get Data
                </button>
            </div>
        </form>
    </div>


</div>

<div id="search_daily_report_table">
    <div class="col-md-6">
        <div class="col-md-12 bottom">
            <h4>Fund Transfer</h4>
        </div>
        <div class="col-md-12">    
            <div class="table-responsive scroll" id="daily_funds">
                <table class="table table-hover fade in bgf9">
                    <thead>
                        <tr>
                            <th>Account Admin</th>
                            <th>Username</th>
                            <th>Route</th>
                            <th>SMS</th>
                            <th>Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $values = array();
                        $sms_values = array();
                        $total_sms = 0;
                        $total = 0;
                        if (isset($daily_transaction) && $daily_transaction) {
                            foreach ($daily_transaction as $daily_transaction_records) {
                                $values[] = $daily_transaction_records['txn_amount'];
                                $sms_values[] = $daily_transaction_records['txn_sms'];
                                ?>
                                <tr>
                                    <td class="table_name" title="Fund transfer on the behalf of account admin"><?php echo $daily_transaction_records['admin_username']; ?></td>
                                    <td class="table_name"><?php echo $daily_transaction_records['username']; ?></td>
                                    <td class="table_name"><?php echo $daily_transaction_records['txn_route']; ?></td>   
                                    <td class="table_name"><?php echo $daily_transaction_records['txn_sms']; ?></td>
                                    <td class="table_name"><?php echo $daily_transaction_records['txn_amount']; ?></td>


                                    <?php
                                    $total = $total + $daily_transaction_records['txn_amount'];
                                    $total_sms = $total_sms + $daily_transaction_records['txn_sms'];
                                    ?>
                                </tr>



                            <?php }
                            ?>
                            <tr>
                                <th>Grand Total</th>
                                <th></th>
                                <th></th>
                                <th><?php echo $total_sms; ?></th>
                                <th><?php echo $total; ?></th>
                            </tr> 

                            <?php
                            $size = sizeof($values);
                            for ($i = 0; $i < $size; $i++) {
                                for ($k = 0; $k < $size; $k++) {
                                    if ($values[$i] > $values[$k]) {
                                        $temp = $values[$k];
                                        $values[$k] = $values[$i];
                                        $values[$i] = $temp;
                                    }
                                }
                            }

                            $size_sms = sizeof($sms_values);
                            for ($x = 0; $x < $size_sms; $x++) {
                                for ($y = 0; $y < $size_sms; $y++) {
                                    if ($sms_values[$x] > $sms_values[$y]) {
                                        $sms_temp = $sms_values[$y];
                                        $sms_values[$y] = $sms_values[$x];
                                        $sms_values[$x] = $sms_temp;
                                    }
                                }
                            }
                            //print_r($values);
                        } else {
                            ?>

                            <tr>

                                <td colspan="4" align="center" style="padding-top:20px;">No Records Found!</td>
                            </tr>   
                        <?php } ?>    
                    </tbody>
                </table>
            </div>
        </div>
    </div>   
    <div class="col-md-1"></div>
    <div class="col-md-5">

        <div class="col-md-12 bottom">
            <h4>Daily Subscription</h4>
        </div>
        <div class="col-md-12">
            <div class="table-responsive scroll" id="daily_sms">
                <table class="table table-hover fade in bgf9">
                    <thead>

                        <tr>
                            <th> Email</th>
                            <th> Mobile</th>
                            <th>Lead By</th>
                            <th>Feedback</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($daily_subscription) && $daily_subscription) {
                            foreach ($daily_subscription as $subscription) {
                                ?>
                                <tr>
                                    <td class="table_name" style="word-break: break-all;"><?php echo $subscription['subscribe_email']; ?></td>
                                    <td class="table_name"><?php echo $subscription['subscribe_mobile']; ?></td>
                                    <td class="table_name"><?php echo $subscription['admin_name']; ?></td>
                                    <td class="table_name" style="word-break: break-all;"><?php echo $subscription['feedback']; ?></td>


                                </tr>


                                <?php
                            }
                        } else {
                            ?>

                            <tr>

                                <td colspan="4" align="center" style="padding-top:20px;">No Records Found!</td>
                            </tr>   
                        <?php } ?>     
                    </tbody>

                </table>
            </div>
        </div>

    </div>
    <div class="col-md-6" style="margin-right: 8%;">

        <div class="col-md-12 bottom">

            <h4>Signup's</h4>
        </div>
        <div class="col-md-12">    
            <div class="table-responsive scroll" id="daily_signup">
                <table class="table table-hover arman-change fade in bgf9">
                    <thead>

                        <tr>
                            <th>Username</th>
                            <th>Lead By</th>
                            <th>Feedback</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($daily_signup) && $daily_signup) {
                            foreach ($daily_signup as $daily_signup_records) {
                                ?>
                                <tr>
                                    <td class="table_name"><?php echo $daily_signup_records['username']; ?></td>
                                    <td class="table_name" style="padding-left: 30px;"><?php echo $daily_signup_records['lead_by']; ?></td>
                                    <td class="table_name"><?php echo $daily_signup_records['feedback']; ?></td>
                                    <td class="table_name"><?php
                                        $status = $daily_signup_records['check_demo_user'];
                                        if ($status) {
                                            echo "<spane style='color: green'>Active</span>";
                                        } else {
                                            echo "Demo";
                                        }
                                        ?></td>
                                </tr>


                                <?php
                            }
                        } else {
                            ?>

                            <tr>

                                <td colspan="4" align="center" style="padding-top:20px;">No Records Found!</td>
                            </tr>   
                        <?php } ?>     
                    </tbody>
                </table>
            </div>
        </div>


    </div>

    <div class="col-md-1"></div>
    <div class="col-md-5">

        <div class="col-md-12 bottom">
            <h4>Targets</h4>
        </div>
        <div class="col-md-12">
            <div class="table-responsive scroll" id="daily_targets">
                <table class="table table-hover fade in bgf9">
                    <thead>
                        <tr>
                            <th>Admin Name</th>
                            <th>Total Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                   
                        $values = array();
                        $total = 0;
                        if (isset($subadmin_target) && $subadmin_target) {
                            foreach ($subadmin_target as $subadmin_target_records) {
                                $values[] = $subadmin_target_records['target'];
                                ?>
                                <tr>
                                    <td class="table_name"><?php echo $subadmin_target_records['admin_id']; ?></td>
                                    <td class="table_name"><?php echo $subadmin_target_records['target']; ?></td>
                                    <?php $total = $total + $subadmin_target_records['target']; ?>
                                </tr>



                            <?php }
                            ?>
                            <tr>
                                <th>Grand Total</th>
                                <th><?php echo $total; ?></th>

                            </tr> 

                            <?php
                            $size = sizeof($values);
                            for ($i = 0; $i < $size; $i++) {
                                for ($k = 0; $k < $size; $k++) {
                                    if ($values[$i] > $values[$k]) {
                                        $temp = $values[$k];
                                        $values[$k] = $values[$i];
                                        $values[$i] = $temp;
                                    }
                                }
                            }
                            //print_r($values);
                        } else {
                            ?>

                            <tr>

                                <td colspan="4" align="center" style="padding-top:20px;">No Records Found!</td>
                            </tr>   
                        <?php } ?>     
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div class="col-md-5">

        <div class="col-md-12 bottom">
            <h4>OTP TEST</h4>
        </div>
        <div class="col-md-12">
            <div class="table-responsive scroll" id="daily_targets">
                <table class="table table-hover fade in bgf9">
                    <thead>
                        <tr>
                            <th>Sr no </th>
                            <th>Mobile</th>
                            <th>Status </th>
                            <th>Submit Date </th>
                            <th>Done Date</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $values = 1;

                        if (isset($daily_otp) && $daily_otp) {
                            foreach ($daily_otp as $otp) {
                                ?>
                                <tr>
                                    <td class="table_name"><?php echo $values; ?></td>
                                    <td class="table_name"><?php echo $otp['mobile_no']; ?></td>
                                    <td class="table_name"><?php echo $otp['status']; ?></td>
                                    <td class="table_name"><?php echo $otp['submit_date']; ?></td>
                                    <td class="table_name"><?php echo $otp['done_date']; ?></td>

                                </tr>



                                <?php
                                $values++;
                            }
                            ?>


                            <?php
                        } else {
                            ?>

                            <tr>

                                <td colspan="4" align="center" style="padding-top:20px;">No Records Found!</td>
                            </tr>   
                        <?php } ?>     
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
<style>
    .table.table-hover.arman-change th:nth-child(2) {
        padding-left: 30px;
    }
    .table.table-hover.arman-change th:nth-child(4) {
        padding-left: 2px;
    }
    #settings{
        padding-top: 30px;
    }
    h4{font-size: 21px;}
    .bottom{padding-bottom: 13px;}
    .fade.in {
        opacity: 1;
    }
    .bgf9 {
        background-color: #f9f9f9 !important;
        border: 2px solid #E7E3E3;
    }
    .table_name{
        color: #08c;
        font-size: 13px;
    }


</style>
<script type="text/javascript">
    $(document).ready(function ()
    {
        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate());
        $('#date').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            endDate: today,
            todayHighlight: true
        });

        $('#datepicker1').datetimepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            endDate: today,
            todayHighlight: true
        });
        $('#filter_by_date').datepicker({
            format: "yyyy-mm-dd",
            endDate: today,
            autoclose: true,
            todayHighlight: true
        });
        $('#by_users').multiselect({
            //includeSelectAllOption: true,
            //selectAllText: 'Select All',
            maxHeight: 300,
            enableFiltering: true,
            enableClickableOptGroups: true,
            enableCaseInsensitiveFiltering: true
        });
    });
</script>
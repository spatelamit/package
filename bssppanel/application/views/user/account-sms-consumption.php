<?php
error_reporting(0);
$hostname = "localhost";
$username = "bulksms_user";
$password = "BALAJI@sr#ts7828";
$database = "bulksms_system";


$conn = mysql_connect("$hostname", "$username", "$password") or die(mysql_error());
mysql_select_db("$database", $conn) or die(mysql_error());
?>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="portlet">


                <div class="row">
                    <div class="col-md-6 col-sm-12 col-xs-12 pull-left">
                        <h2 class="content-header-title tbl">Daily Reseller Account SMS Consumption</h2>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class=" pull-right">
                        <form role="form" id="getReportAccountUserSms" method='post' action="javascript:getReportAccountUserSms();" class="notify-forms">
                            <div class="col-md-8 padding15">
                                <div class="input-daterange input-group" id="datepicker">
                                    <input type="text" class="form-control datepicker" name="user_from_date" id="user_from_date" placeholder="Enter From Date" />
                                    <span class="input-group-addon">to</span>
                                    <input type="text" class="form-control datepicker" name="user_to_date" id="user_to_date" placeholder="Enter To Date" />
                                </div>
                            </div>
                            <div class="col-md-4 padding15">
                                <button name="get_users_report" id="get_users_report" class="btn btn-primary"
                                        data-loading-text="Searching..." autocomplete="off" type="submit">
                                    Get Report
                                </button>
                            </div>
                        </form>
                        
                            
                        </div>
                    </div>
                </div>
                <div class="portlet-content">
                    <div class="table-responsive">
                        <table class="table table-bordered bgf" id="all_user_cunsumption">
                            <thead>
                                <tr style="background-color: darkgrey;">
                                    <th>Sr. No</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Total Promotional SMS</th>
                                    <th>Total Transactional SMS</th>
                                    <th>Total Stock SMS</th>
                                    <th>Total Premium SMS</th>


                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($users_sms) && $users_sms) {
                                    $i = 1;
                                    foreach ($users_sms as $row) {
                                        ?>
                                        <tr  <?php if ($i % 2 == 0) { ?>
                                                style="background-color: lightgray;"  
                                            <?php } else { ?>
                                                style="background-color: ghostwhite"  

                                            <?php } ?>>
                                            <td><?php echo $i; ?></td>
                                            <td><?php
                                                $user_id = $row['user_id'];
                                                $query = "SELECT `username`, `name` FROM `users` WHERE `user_id` = '" . $user_id . "'";
                                                $result = mysql_query($query);
                                                $row1 = mysql_fetch_array($result);
                                                $name = $row1["name"];
                                                echo $name;
                                                ?>
                                            </td>
                                            <td><?php echo $row1['username']; ?></td>
                                            <td><?php
                                                if ($row['promotional_sms'] != 0) {
                                                    echo $row['promotional_sms'];
                                                } else {
                                                    echo "0";
                                                }
                                                ?>

                                            </td>
                                            <td>
                                                <?php
                                                if ($row['transactional_sms'] != 0) {
                                                    echo $row['transactional_sms'];
                                                } else {
                                                    echo "0";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($row['stock_sms'] != 0) {
                                                    echo $row['stock_sms'];
                                                } else {
                                                    echo "0";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($row['premium_sms'] != 0) {
                                                    echo $row['premium_sms'];
                                                } else {
                                                    echo "0";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="20" align="center">
                                            <strong><b>No User Exist!</b></strong>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>

<!--                            <tfoot>
                                <tr>
                                    <td align="center" colspan="20">
                                        <ul class="pagination margin0">
                            <?php //echo $pagination_helper;    ?>
                                        </ul>
                                    </td>
                                </tr>
                            </tfoot>-->

                        </table>
                    </div>
                </div> 
            </div> 
        </div>
    </div>
</div><script type="text/javascript">
        $(document).ready(function ()
        {
            var nowDate = new Date();
            var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate());
            $('#user_from_date').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                endDate: today,
                todayHighlight: true
            });
            
              $('#user_to_date').datepicker({
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
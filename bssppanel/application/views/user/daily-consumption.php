<?php
$session_data = $this->session->userdata('logged_in');
$user_id = $session_data['user_id'];
?>
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
                <h2 class="content-header-title tbl">Users SMS Consumption</h2>
<!--                <div class="row" style="padding-bottom: 25px;">
                    <form role="form" id="getReportSms" method="post" action="javascript:getReportSms();" class="notify-forms">
                        <div class="col-md-4 padding15">
                            <div class="input-daterange input-group" id="datepicker">
                                <input class="form-control datepicker" name="by_from_date" id="export_from_date" placeholder="Enter From Date" type="text">
                                <span class="input-group-addon">to</span>
                                <input class="form-control datepicker" name="by_to_date" id="export_to_date" placeholder="Enter To Date" type="text">
                            </div>
                        </div>
                        <div class="col-md-1 padding15">
                            <button name="get_report" id="get_report" class="btn btn-primary" data-loading-text="Searching..." autocomplete="off" type="submit">
                                Get Report
                            </button>
                        </div>
                    </form>
                </div>-->
                <div class="portlet-content">

                    <div class="table-responsive" id="show_report">
                        <table class="table table-bordered table-hover bgf">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">Username</th>
                                    <th style="color:#4A9AB5;text-align: center;">Submit</th> 
                                    <th style="color:#4271A5;text-align: center;">Sent</th> 
                                    <th style="color:#8CAA52;text-align: center;">Delivered</th> 
                                    <th style="color:#AD4942;text-align: center;">Failed</th> 
                                    <th style="color:#DE8742;text-align: center;">Rejected</th> 
                                    <th style="color:#000;text-align: center;">Blocked</th> 
                                    <th style="color:#6515a7;text-align: center;">Landline</th> 
                                    <th style="text-align: center;">Total</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($daily_sms) && $daily_sms) {
                                    $i = 1;
                                    ?>
                                    <tr>
                                        <th style="text-align: center;"><?php
                                            $query = "SELECT `username` FROM `users` WHERE `user_id` = '" . $user_id . "'";
                                            $result = mysql_query($query);
                                            $row1 = mysql_fetch_array($result);
                                            $username = $row1["username"];
                                            echo $username;
                                            ?></th> 
                                        <th style="color:#4A9AB5;text-align: center;">
                                            <?php
                                            if ($daily_sms[31]) {
                                                echo $daily_sms[31];
                                            } else {
                                                echo '0';
                                            }
                                            ?>
                                        </th> 
                                        <th style="color:#4271A5;text-align: center;"> <?php
                                            if ($daily_sms[3]) {
                                                echo $daily_sms[3];
                                            } else {
                                                echo '0';
                                            }
                                            ?>
                                        </th> 
                                        <th style="color:#8CAA52;text-align: center;">
                                            <?php
                                            if ($daily_sms[1]) {
                                                echo $daily_sms[1];
                                            } else {
                                                echo '0';
                                            }
                                            ?> 
                                        </th> 
                                        <th style="color:#AD4942;text-align: center;">
                                            <?php
                                            if ($daily_sms[2]) {
                                                echo $daily_sms[2];
                                            } else {
                                                echo '0';
                                            }
                                            ?> 
                                        </th> 
                                        <th style="color:#DE8742;text-align: center;">
                                            <?php
                                            if ($daily_sms[16]) {
                                                echo $daily_sms[16];
                                            } else {
                                                echo '0';
                                            }
                                            ?> 
                                        </th> 
                                        <th style="color:#000;text-align: center;">
                                            <?php
                                            if ($daily_sms['Blocked']) {
                                                echo $daily_sms['Blocked'];
                                            } else {
                                                echo '0';
                                            }
                                            ?> 
                                        </th> 
                                        <th style="color:#6515a7;text-align: center;">
                                            <?php
                                            if ($daily_sms[48]) {
                                                echo $daily_sms[48];
                                            } else {
                                                echo '0';
                                            }
                                            ?> 
                                        </th> 
                                        <th style="text-align: center;">
                                            <?php
                                            $total_sms = $daily_sms[31] + $daily_sms[3] + $daily_sms[1] + $daily_sms[2] + $daily_sms[16] + $daily_sms[48];
                                            if ($total_sms) {
                                                echo $total_sms;
                                            } else {
                                                echo '0';
                                            }
                                            ?>

                                        </th> 
                                    </tr>
                                    <?php
                                    $i++;
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="9" align="center">
                                            <strong>No Record Found!</strong>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td align="center" colspan="9">
                                        <ul class="pagination margin0">
                                            <?php echo $pagination_helper; ?>
                                        </ul>
                                    </td>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div> 
            </div> 
        </div>
    </div>
</div>
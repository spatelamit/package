<?php
error_reporting(0);
$hostname = "localhost";
$username = "bulksms_user";
$password = "BALAJI@sr#ts7828";
$database = "bulksms_system";


$conn = mysql_connect("$hostname", "$username", "$password") or die(mysql_error());
mysql_select_db("$database", $conn) or die(mysql_error());
?>

<span class="hidden" id="msg_type"><?php echo (isset($msg_type) ? $msg_type : ""); ?></span>
<span class="hidden" id="msg_data"><?php echo (isset($msg_data) ? $msg_data : ""); ?></span>
<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li class="<?php echo (isset($subtab) && $subtab == "1") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="geLogsTab('1');">Transaction Logs</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "2") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="geLogsTab('2');">Error Logs</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "3") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="geLogsTab('3');">SMS Consumptions</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "4") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="geLogsTab('4');">SMS Consumptions TR</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "5") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="geLogsTab('5');">SMS Consumptions PR</a>
    </li>
</ul>
<div class="panel-group panel-color visible-xs"></div>

<!-- Tab panes -->
<div class="tab-content bgf9">
    <div class="tab-pane fade in active ">

        <?php if (isset($subtab) && $subtab == '1') { ?>
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-hover bgf9">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Admin Name</th>
                                <th>Particulars</th>
                                <th>Type</th>
                                <th>SMS</th>
                                <th>Pricing</th>
                                <th>Amount</th>
                                <th>Balance Type</th>
                                <th width="30%">Description</th>
                                <th width="30%">Admin Comment</th>
                                <th width="30%">Account Admin</th>
                            </tr>
                        </thead>
                        <tbody class="bgf7">
                            <?php
                            if (isset($transactions) && $transactions) {
                                $i = 1;
                                foreach ($transactions as $row) {
                                    ?>
                                    <tr <?php if ($i % 2 == 0) { ?>
                                            style="background-color: gainsboro;"  
                                        <?php } else { ?>
                                            style="background-color: white;"  

                                        <?php } ?>>
                                        <td width="25%"><?php echo $row['txn_date']; ?></td>
                                        <td><?php echo "-" ?></td>
                                        <td>
                                            <?php
                                            if ($row['from_admin_id'] == $admin_id) {
                                                if ($row['to_user_id'] != "")
                                                    echo $row['to_uname'];
                                                elseif ($row['to_admin_id'] != "")
                                                    echo $row['to_admin_name'] . " (Admin)";
                                            }elseif ($row['to_admin_id'] == $admin_id) {
                                                if ($row['from_user_id'] != "")
                                                    echo $row['from_uname'];
                                                elseif ($row['from_admin_id'] != "")
                                                    echo $row['from_admin_name'] . " (Admin)";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if ($row['txn_type'] == 'Add') { ?>
                                                <span class="label label-success">Add</span>
                                            <?php } elseif ($row['txn_type'] == 'Reduce') { ?>
                                                <span class="label label-danger">Reduce</span>
                                            <?php } elseif ($row['txn_type'] == 'Demo') { ?>
                                                <span class="label label-info">Demo</span>
                                            <?php } elseif ($row['txn_type'] == 'Refund') { ?>   
                                                <span class="label label-warning">Refund</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($row['txn_type'] == "Add") {
                                                echo $row['txn_sms'];
                                            } if ($row['txn_type'] == "Reduce") {
                                                echo $row['txn_sms'];
                                            }if ($row['txn_type'] == "Demo") {
                                                echo $row['txn_sms'];
                                            }if ($row['txn_type'] == "Refund") {
                                                echo $row['txn_sms'];
                                            }
                                            ?>
                                        </td>


                                        <td><?php echo $row['txn_price']; ?></td>
                                        <td><?php echo $row['txn_amount']; ?></td>
                                        <td>
                                            <?php if ($row['txn_route'] == 'A') { ?>
                                                <span class="label label-success">Promotional</span>
                                            <?php } elseif ($row['txn_route'] == 'B') { ?>
                                                <span class="label label-danger">Transactional</span> 
                                            <?php } elseif ($row['txn_route'] == 'Long') { ?>
                                                <span class="label label-info">Long Code</span> 
                                            <?php } elseif ($row['txn_route'] == 'Short') { ?>
                                                <span class="label label-primary">Short Code</span> 
                                            <?php } elseif ($row['txn_route'] == 'VA') { ?>
                                                <span class="label label-success">Promotional Voice</span>
                                            <?php } elseif ($row['txn_route'] == 'VB') { ?>
                                                <span class="label label-danger">Dynamic Voice</span>
                                            <?php } elseif ($row['txn_route'] == 'SPR') { ?>
                                                <span class="label label-danger">Special PR Credits</span>
                                            <?php } elseif ($row['txn_route'] == 'STR') { ?>
                                                <span class="label label-danger">Special TR Credits</span>
                                            <?php } ?>
                                        </td>
                                        <td width="30%">
                                            <?php
                                            echo ($row['txn_description'] != '') ? $row['txn_description'] : "-";
                                            ?>
                                        </td>
                                        <td width="30%">
                                            <?php
                                            echo ($row['admin_discription'] != '') ? $row['admin_discription'] : "-";
                                            ?>
                                        </td>
                                        <td width="30%">
                                            <?php
                                            $account_admin = $row['account_admin'];
                                            $query_account_admin = "SELECT `admin_username` FROM `administrators` WHERE `admin_id` = '" . $account_admin . "'";
                                            $result_account_admin = mysql_query($query_account_admin);
                                            $row_account_admin = mysql_fetch_array($result_account_admin);
                                            echo $admin_username = $row_account_admin["admin_username"];
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            } else { //whole transaxtion for admin 
                                if (isset($transactions1) && $transactions1) {
                                    $i = 1;
                                    foreach ($transactions1 as $row) {
                                        ?>
                                        <tr <?php if ($i % 2 == 0) { ?>
                                                style="background-color: gainsboro;"  
                                            <?php } else { ?>
                                                style="background-color: white;"  

                                            <?php } ?>>
                                            <td><?php echo $row['txn_date']; ?></td>
                                            <td>
                                                <?php
                                                if ($row['txn_type'] == 'Reduce') {
                                                    $user_id = $row['txn_user_from'];
                                                    $admin_id = $row['txn_admin_to'];
                                                } else {
                                                    $user_id = $row['txn_user_to'];
                                                    $admin_id = $row['txn_admin_from'];
                                                }
                                                $query1 = "SELECT `username` FROM `users` WHERE `user_id` = '" . $user_id . "'";
                                                $result1 = mysql_query($query1);
                                                $row1 = mysql_fetch_array($result1);
                                                $username = $row1["username"];



                                                if ($admin_id) {
                                                    $query = "SELECT `admin_username` FROM `administrators` WHERE `admin_id` = '" . $admin_id . "'";
                                                    $result = mysql_query($query);
                                                    $row2 = mysql_fetch_array($result);
                                                    $admin_username = $row2["admin_username"];

                                                    echo $admin_username;
                                                } else {
                                                    echo $admin_username = "Resseller";
                                                }
                                                ?>

                                            </td>
                                            <td>
                                                <?php
                                                echo $username;
                                                ?>
                                            </td>
                                            <td>
                                                <?php if ($row['txn_type'] == 'Add') { ?>
                                                    <span class="label label-success">Add</span>
                                                <?php } elseif ($row['txn_type'] == 'Reduce') { ?>
                                                    <span class="label label-danger">Reduce</span>
                                                <?php } elseif ($row['txn_type'] == 'Demo') { ?>
                                                    <span class="label label-info">Demo</span>
                                                <?php } elseif ($row['txn_type'] == 'Refund') { ?>   
                                                    <span class="label label-warning">Refund</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($row['txn_type'] == "Add") {
                                                    echo $row['txn_sms'];
                                                }
                                                if ($row['txn_type'] == "Reduce") {
                                                    echo $row['txn_sms'];
                                                }if ($row['txn_type'] == "Demo") {
                                                    echo $row['txn_sms'];
                                                }if ($row['txn_type'] == "Refund") {
                                                    echo $row['txn_sms'];
                                                }
                                                ?>
                                            </td>

                                            <td><?php echo $row['txn_price']; ?></td>
                                            <td><?php echo $row['txn_amount']; ?></td>
                                            <td>
                                                <?php if ($row['txn_route'] == 'A') { ?>
                                                    <span class="label label-success">Promotional</span>
                                                <?php } elseif ($row['txn_route'] == 'B') { ?>
                                                    <span class="label label-danger">Transactional</span> 
                                                <?php } elseif ($row['txn_route'] == 'Long') { ?>
                                                    <span class="label label-info">Long Code</span> 
                                                <?php } elseif ($row['txn_route'] == 'Short') { ?>
                                                    <span class="label label-primary">Short Code</span> 
                                                <?php } elseif ($row['txn_route'] == 'VA') { ?>
                                                    <span class="label label-success">Promotional Voice</span>
                                                <?php } elseif ($row['txn_route'] == 'VB') { ?>
                                                    <span class="label label-danger">Dynamic Voice</span>
                                                <?php } elseif ($row['txn_route'] == 'SPR') { ?>
                                                    <span class="label label-danger">Special PR Credits</span>
                                                <?php } elseif ($row['txn_route'] == 'STR') { ?>
                                                    <span class="label label-danger">Special TR Credits</span>
                                                <?php } ?>
                                            </td>
                                            <td width="30%">
                                                <?php
                                                echo ($row['txn_description'] != '') ? $row['txn_description'] : "-";
                                                ?>
                                            </td>
                                            <td width="30%">
                                                <?php
                                                echo ($row['admin_discription'] != '') ? $row['admin_discription'] : "-";
                                                ?>
                                            </td>
                                            <td width="30%">
                                                <?php
                                                $account_admin = $row['account_admin'];
                                                $query_account_admin = "SELECT `admin_username` FROM `administrators` WHERE `admin_id` = '" . $account_admin . "'";
                                                $result_account_admin = mysql_query($query_account_admin);
                                                $row_account_admin = mysql_fetch_array($result_account_admin);
                                                echo $admin_username = $row_account_admin["admin_username"];
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="9" align="center">
                                            <strong>No Transactions!</strong>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>


                                <tr>
                                    <td colspan="9" align="center">
                                        <strong>No Transactions!</strong>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <?php echo $paging; ?>
                </div>
            </div>
        <?php } ?>

        <?php if (isset($subtab) && $subtab == '2') { ?>
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-hover bgf9">
                        <thead>
                            <tr>
                                <th width="180">Date</th>
                                <th width="250">Username</th>
                                <th width="150">By</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody class="bgf7">
                            <?php
                            if (isset($sms_logs) && $sms_logs) {
                                $i = 1;
                                foreach ($sms_logs as $row) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['sms_log_time']; ?></td>
                                        <td>
                                            <?php echo $row['username']; ?>
                                            ( <?php echo ($row['parent_username'] == "") ? $row['admin_username'] : $row['parent_username']; ?> )
                                        </td>
                                        <td><?php echo $row['sms_log_by']; ?></td>
                                        <td><span class="text-danger"><?php echo $row['sms_log_reason']; ?></span></td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="4" align="center">
                                        <strong>No Error Logs!</strong>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <?php echo $paging; ?>
                </div>
            </div>
        <?php } ?>

        <?php if (isset($subtab) && $subtab == '3') { ?>
            <div class="row">
                <div class="col-md-3 padding15 text-left">
                    <input type="text" class="form-control" id="search_by_username" placeholder="Enter Username" onkeyup="filterSMSConsumption(this.value);" />
                </div>
                <div class="col-md-9"></div>
            </div>
            <div class="row">
                <div class="col-md-12" id="sms_consumptions_table">
                    <?php echo (isset($table) && $table) ? $table : ""; ?>
                </div>
            </div>
        <?php } ?>


        <!--sms consumption TR-->
        <?php if (isset($subtab) && $subtab == '4') { ?>
            <div class="row">
                <form role="form" id="getReportTrSms" method='post' action="javascript:getReportTrSms();" class="notify-forms">
                    <div class="col-md-4 padding15">
                        <div class="input-daterange input-group" id="datepicker">
                            <input type="text" class="form-control datepicker" name="by_from_date" id="by_from_date" placeholder="Enter From Date" autocomplete="off" />
                            <span class="input-group-addon">to</span>
                            <input type="text" class="form-control datepicker" name="by_to_date" id="by_to_date" placeholder="Enter To Date" autocomplete="off" />
                        </div>
                    </div>
                    <div class="col-md-1 padding15">
                        <button name="get_tr_report" id="get_tr_report" class="btn btn-primary"
                                data-loading-text="Searching..." autocomplete="off" type="submit">
                            Get Report
                        </button>
                    </div>
                </form>
            </div>


            <div class="table-responsive" id="show_tr_report">
                <table class="table table-hover bgf9">
                    <thead>
                        <tr>
                            <th rowspan="2">Sr. NO</th>
                            <th rowspan="2">Username</th>
                            <th rowspan="2">User Type</th>
                            <th colspan="2">Transactional SMS</th>
                        </tr>
                        <tr>

                            <th>Total SMS</th>
                            <th>Actual Deductions</th>
                        </tr>
                    </thead>
                    <tbody class="bgf7">
                        <?php
                        $total_tr_messages = 0;
                        $tr_deduction = 0;
                        if (isset($tr_consumption_logs) && $tr_consumption_logs) {
                            $i = 1;
                            foreach ($tr_consumption_logs as $row) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $i; ?>

                                    </td>
                                    <td>
                                        <?php echo $row['username']; ?>

                                    </td>
                                    <td>
                                        <?php if ($row['user_type'] == 'User') { ?>
                                            <span class="label label-success">User</span>
                                        <?php } elseif ($row['user_type'] == 'Reseller') { ?>
                                            <span class="label label-info">Reseller</span>
                                        <?php } ?>
                                    </td>

                                    <td><?php
                                        echo $row['total_tr_messages'];
                                        $total_tr_messages = $total_tr_messages + $row['total_tr_messages'];
                                        ?></td>
                                    <td><?php
                                        echo $row['tr_consumptions'];

                                        $tr_deduction = $tr_deduction + $row['tr_consumptions'];
                                        ?></td>
                                </tr>

                                <?php
                                $i++;
                            }
                            ?>
                            <tr>
                                <th>-- </th>
                                <th>Grand Total </th>
                                <th>-- </th>
                                <th><?php echo $total_tr_messages; ?></th>
                                <th><?php echo $tr_deduction; ?></th>

                            </tr>
                            <?php
                        } else {
                            ?>
                            <tr>
                                <td colspan="6" align="center">
                                    <strong>No Logs!</strong>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>

        <?php if (isset($subtab) && $subtab == '5') { ?>
            <div class="row">
                <form role="form" id="getReportPrSms" method='post' action="javascript:getReportPrSms();" class="notify-forms">
                    <div class="col-md-4 padding15">
                        <div class="input-daterange input-group" id="datepicker">
                            <input type="text" class="form-control datepicker" name="by_from_date" id="by_from_date" placeholder="Enter From Date" autocomplete="off" />
                            <span class="input-group-addon">to</span>
                            <input type="text" class="form-control datepicker" name="by_to_date" id="by_to_date" placeholder="Enter To Date" autocomplete="off" />
                        </div>
                    </div>
                    <div class="col-md-1 padding15">
                        <button name="get_pr_report" id="get_pr_report" class="btn btn-primary"
                                data-loading-text="Searching..." autocomplete="off" type="submit">
                            Get Report
                        </button>
                    </div>
                </form>
            </div>
            <div class="table-responsive" id="show_pr_report">
                <table class="table table-hover bgf9">
                    <thead>
                        <tr>
                            <th rowspan="2">Sr. NO</th>
                            <th rowspan="2">Username</th>
                            <th rowspan="2">User Type</th>
                            <th colspan="2">Promotional SMS</th>

                        </tr>
                        <tr>
                            <th>Total SMS</th>
                            <th>Actual Deductions</th>         
                        </tr>
                    </thead>
                    <tbody class="bgf7">
                        <?php
                        $total_pr_messages = 0;
                        $pr_consumptions = 0;
                        if (isset($pr_consumption_logs) && $pr_consumption_logs) {
                            $i = 1;
                            foreach ($pr_consumption_logs as $row) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $i; ?>

                                    </td>
                                    <td>
                                        <?php echo $row['username']; ?>

                                    </td>
                                    <td>
                                        <?php if ($row['user_type'] == 'User') { ?>
                                            <span class="label label-success">User</span>
                                        <?php } elseif ($row['user_type'] == 'Reseller') { ?>
                                            <span class="label label-info">Reseller</span>
                                        <?php } ?>
                                    </td>
                                    <td><?php
                                        echo $row['total_pr_messages'];
                                        $total_pr_messages = $total_pr_messages + $row['total_pr_messages'];
                                        ?></td>
                                    <td><?php
                                        echo $row['pr_consumptions'];

                                        $pr_consumptions = $pr_consumptions + $row['pr_consumptions'];
                                        ?></td>
                                </tr>
                                <?php
                                $i++;
                            }
                            ?>
                            <tr>
                                <th>-- </th>
                                <th>Grand Total </th>
                                <th>-- </th>
                                <th><?php echo $total_pr_messages; ?></th>
                                <th><?php echo $pr_consumptions; ?></th>

                            </tr>
                            <?php
                        } else {
                            ?>
                            <tr>
                                <td colspan="6" align="center">
                                    <strong>No Logs!</strong>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>   
        <?php } ?>


    </div>
</div>
<script type="text/javascript">
    $('#validate-basic').parsley();
    $('.settingClass').parsley();
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
<script type="text/javascript">
    $(document).ready(function ()
    {
        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate());
        $('#by_from_date').datetimepicker({
            format: "yyyy-mm-dd hh:ii:00",
            autoclose: true,
            endDate: today,
            todayHighlight: true
        });
        $('#by_to_date').datetimepicker({
            format: "yyyy-mm-dd hh:ii:00",
            autoclose: true,
            endDate: today,
            todayHighlight: true
        });

    });
</script>
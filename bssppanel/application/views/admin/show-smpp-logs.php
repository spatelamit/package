<?php
error_reporting(0);
$hostname = "localhost";
$username = "bulksms_user";
$password = "BALAJI@sr#ts7828";
$database = "bulksms_system";


$conn = mysql_connect("$hostname", "$username", "$password") or die(mysql_error());
mysql_select_db("$database", $conn) or die(mysql_error());
?>

<ul class="nav nav-tabs" role="tablist">
    <li class="<?php echo (isset($subtab) && $subtab == "1") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getSMPPTab('1');">Current Logs</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "4") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getSMPPTab('4');">Overall Logs</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "2") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getSMPPTab('2');">Day-Wise Logs</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "3") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getSMPPTab('3');">Export SMPP Logs</a>
    </li>
</ul>

<div class="panel-group panel-color visible-xs"></div>

<!-- Tab panes -->
<div class="tab-content bgf9">
    <div class="tab-pane fade in active ">

        <?php if (isset($subtab) && $subtab == '1') { ?>
            <div class="row">
                <div class="col-md-12">

                    <div class="table-responsive">
                        <form role="form" id="sellerReportForm" method="post" action="<?php echo base_url(); ?>admin/smpp_logs" class="notify-forms">

                            <div class="col-md-4 padding15">
                                <div class="input-daterange input-group" id="">
                                    <input type="text" class="form-control " name="from_date" value="<?php echo $fdate; ?>" id="from_date" placeholder="YYYY-MM-DD" autocomplete="off">
                                    <span class="input-group-addon">to</span>
                                    <input type="text" class="form-control " name="to_date" id="to_date" value="<?php echo $tdate; ?>" placeholder="YYYY-MM-DD" autocomplete="off"> 

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
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Connected SMPP</th>
                                    <th class="text-right">Delivered</th>
                                    <th class="text-right">Sent</th>
                                    <th class="text-right">Pending</th>
                                    <th class="text-right">Rejected</th>
                                    <th class="text-right">Failed</th>
                                    <th class="text-right">Admin Rejected</th>
                                    <th class="text-right">DND</th>
                                    <th class="text-right">Blocked</th>
                                    <th class="text-right">Landline</th>
                                    <th class="text-right">Total*</th>
                                  

                                </tr>
                            </thead>
                            <tbody class="bgf7">
                                <?php
                                $i = 1;
                                $size = sizeof($smpp_list);
                                if (isset($smpp_list) && $smpp_list) {
                                    for ($v = 0; $v < $size; $v++) {
                                        ?>
                                        <tr <?php if ($i % 2 == 0) { ?>
                                                style="background-color: gainsboro;"  
                                            <?php } else { ?>
                                                style="background-color: white;"  

                                            <?php } ?>>
                                            <td><?php
                                                $id = $smpp_list[$v][0]['route_id'];
                                                $query = "SELECT * FROM `user_groups` WHERE `user_group_id` = '" . $id . "'";
                                                $result = mysql_query($query);
                                                $row_data = mysql_fetch_array($result);
                                                echo $row_data['user_group_name'];
                                                ?></td>
                                            <td class="text-right"><?php
                                                if ($smpp_list[$v][0]['delivered']) {
                                                    echo $smpp_list[$v][0]['delivered'];
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                            <td class="text-right"><?php
                                                if ($smpp_list[$v][0]['sent']) {
                                                    echo $smpp_list[$v][0]['sent'];
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                            <td class="text-right"><?php
                                                if ($smpp_list[$v][0]['pending']) {
                                                    echo $smpp_list[$v][0]['pending'];
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                            <td class="text-right"><?php
                                                if ($smpp_list[$v][0]['rejected']) {
                                                    echo $smpp_list[$v][0]['rejected']; 
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                            <td class="text-right"><?php
                                                if ($smpp_list[$v][0]['failed']) {
                                                    echo $smpp_list[$v][0]['failed'];
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                               <td class="text-right"><?php
                                                if ($smpp_list[$v][0]['rejected_human']) {
                                                    echo $smpp_list[$v][0]['rejected_human'];
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                                  <td class="text-right"><?php
                                                if ($smpp_list[$v][0]['dnd']) {
                                                    echo $smpp_list[$v][0]['dnd'];
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                                     <td class="text-right"><?php
                                                if ($smpp_list[$v][0]['blocked']) {
                                                    echo $smpp_list[$v][0]['blocked'];
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                            <td class="text-right"><?php
                                                if ($smpp_list[$v][0]['landline']) {
                                                    echo $smpp_list[$v][0]['landline'];
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                                 <td class="text-right"><?php
                                                if ($smpp_list[$v][0]['total']) {
                                                    echo $smpp_list[$v][0]['total'];
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                         


                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                } else {
                                    ?>

                                    <tr>
                                        <td colspan="11" align="center">
                                            <strong>No Logs!</strong>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php } ?>




        <?php if (isset($subtab) && $subtab == '4') { ?>
            <div class="row">
                <form role="form" id="getOverallLogs" method='post' action="javascript:getOverallLogs();" class="notify-forms">

                    <div class="col-md-4 padding15">

                        <div class="input-daterange input-group" id="datepicker">
                            <input type="text" class="form-control" name="search_from_date" id="search_from_date" placeholder="Enter From Date" />
                            <span class="input-group-addon">to</span>
                            <input type="text" class="form-control" name="search_to_date" id="search_to_date" placeholder="Enter To Date" />
                        </div>
                    </div>
                    <div class="col-md-2 padding15">
                        <select class="form-control" name="selected_smpp" id="selected_smpp">
                            <option value="">Select User Group</option>
                            <?php
                            $i = 0;
                            if ($smpp_list) {
                                foreach ($smpp_list as $user_group) {
                                    if ($user_group['user_group_id'] == 53) {
                                        $i++;
                                    } elseif ($user_group['user_group_id'] == 56) {
                                        $i++;
                                    } elseif ($user_group['user_group_id'] == 46) {
                                        $i++;
                                    } else {
                                        ?>
                                        <option value="<?php echo $user_group['user_group_id']; ?>">
                                            <?php echo $user_group['smsc_id']; ?>
                                        </option>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-1 padding15">
                        <button name="search_report" id="search_report" class="btn btn-primary" data-loading-text="Searching..." autocomplete="off" type="submit">
                            Search
                        </button>         
                    </div>
                </form>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive" id="show_overall_report">    
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if (isset($subtab) && $subtab == '2') { ?>
            <div class="row padding15">
                <div class="col-md-3">
                    <input type="text" class="form-control" id="search_by_date" placeholder="Enter Date" />
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary btn-sm" onclick="filterSMPPLogs();"
                            data-loading-text="Searching..." autocomplete="off" id="search_logs">Search</button>
                </div>
                <div class="col-md-7"></div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive" id="smpp_logs_table">
                        <?php echo (isset($table) && $table) ? $table : ''; ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if (isset($subtab) && $subtab == '3') { ?>
            <div class="row padding15">
                <div class="col-md-4 col-sm-4 col-xs-12" id="exportSMPPLogs">
                    <div class="input-daterange input-group" id="datepicker">
                        <input type="text" class="form-control" name="export_from_date" id="export_from_date" placeholder="Enter From Date" />
                        <span class="input-group-addon">to</span>
                        <input type="text" class="form-control" name="export_to_date" id="export_to_date" placeholder="Enter To Date" />
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary btn-sm" onclick="searchSMPPLogs();"
                            data-loading-text="Searching..." autocomplete="off" id="search_logs">Search</button>
                </div>
                <div class="col-md-6 text-right">
                    <a href="javascript:void(0);" class="btn btn-info btn-sm hidden" onclick="exportSMPPLogs();"
                       data-loading-text="Processing..." autocomplete="off" id="export_logs">Export</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive" id="smpp_logs_table">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Connected SMPP</th>
                                    <th>Pending</th>
                                    <th>DND</th>
                                    <th>Rejected</th>
                                    <th>Blocked</th>
                                    <th>Submit</th>
                                    <th>Failed</th>
                                    <th>Delivered</th>
                                    <th>Total</th>
                                    <th>Actual Deduction*</th>
                                </tr>
                            </thead>
                            <tbody class="bgf7"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php } ?>

    </div>
</div>
<script type="text/javascript">
    $(document).ready(function ()
    {
        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
        $('#search_by_date').datepicker({
            format: "yyyy-mm-dd",
            endDate: today,
            autoclose: true,
            todayHighlight: true
        });

        $('#export_from_date').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            endDate: today,
            todayHighlight: true
        });

        $('#export_to_date').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            endDate: today,
            todayHighlight: true
        });
    });

</script>

<script type="text/javascript">
    $(document).ready(function ()
    {
        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate());
        $('#from_date').datetimepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            endDate: today,
            todayHighlight: true
        });
        $('#to_date').datetimepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            endDate: today,
            todayHighlight: true
        });

    });
</script>

<script src="<?php echo base_url(); ?>Assets/user/js/highcharts.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>Assets/user/js/exporting.js" type="text/javascript"></script>
<?php
$total = 0;
$total_pending = 0;
$total_cancelled = 0;
$total_sent = 0;
$total_dnd = 0;
$total_reject = 0;
$total_blocked = 0;
$total_submit = 0;
$total_failed = 0;
$total_delivered = 0;

if (isset($sent_sms_status) && $sent_sms_status) {
 
    foreach ($sent_sms_status AS $status) {
         $smsc_no =  $status['user_group_id'];
        if ($status['status'] == "1" || $status['status'] == "PROCESSED" || $status['status'] == 'ANSWERED_MACHINE' || $status['status'] == 'ANSWERED') {
            $total_delivered += $status['Count_Status'];
        } elseif ($status['status'] == "2" || $status['status'] == "ERROR" || $status['status'] == 'ERROR_NO_ANSWER' || $status['status'] == 'INVALID') {
            $total_failed += $status['Count_Status'];
        } elseif ($status['status'] == "31" || $status['status'] == "PENDING" || $status['status'] == "23") {
            $total_pending += $status['Count_Status'];
        } elseif ($status['status'] == "8" || $status['status'] == "ERROR_USER_BUSY" || $status['status'] == 'SUBMITTED') {
            $total_submit += $status['Count_Status'];
        } elseif ($status['status'] == "DND" || $status['status'] == "9") {
            $total_dnd += $status['Count_Status'];
        } elseif ($status['status'] == "16" || $status['status'] == "Rejected" || $status['status'] == "ERROR_NOT_ENOUGH_CREDITS" || $status['status'] == "ERROR_NETWORK_NOT_AVAILABLE" || $status['status'] == 'ERROR_ROUTE_NOT_AVAILABLE' || $status['status'] == 'ERROR_UNSUPPORTED_AUDIO_FORMAT' || $status['status'] == 'ERROR_DOWNLOADING_FILE') {
            $total_reject += $status['Count_Status'];
        } elseif ($status['status'] == "Blocked") {
            $total_blocked += $status['Count_Status'];
        } elseif ($status['status'] == "3" || $status['status'] == "4") {
            $total_sent += $status['Count_Status'];
        } elseif ($status['status'] == "25") {
            $total_cancelled += $status['Count_Status'];
        }
    }
}
$total = $total_delivered + $total_blocked + $total_cancelled + $total_dnd + $total_failed + $total_pending + $total_reject + $total_sent + $total_submit;
?>
<div class="page-content-title txt-center">
    <h3><i class="fa fa-download"></i> Sent SMS </h3> 
</div>
<div id="custom-sms">
    <div class="col-md-4 padding15">
        <input type="text" id="search_by_contact" placeholder="Search Mobile Number" class="form-control"
               onkeyup="searchSentSMS(this.value, <?php echo $campaign; ?>);" />
    </div>
    <div class="col-md-12 padding15">
        <div class="col-md-6" id="sent_sms_table">
            <div class="table-responsive" style="overflow-y: auto;height: 480px;">
                <table class="table table-hover fade in bgf9">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>To</th>
                            <th>Status</th>
                            <th>Submit Date</th>
                            <th>Done Date</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($sent_sms) && $sent_sms) {
                            $i = 1;
                            foreach ($sent_sms as $sms) {
                                $decode_dlr_reciept = "";
                                $report = "";
                                if ($sms['dlr_receipt'] != null) {
                                    $dlr_reciept = $sms['dlr_receipt'];
                                    $decode_dlr_reciept = urldecode($dlr_reciept);
                                    $array = explode(' ', $decode_dlr_reciept);
                                    if (sizeof($array) > 1) {
                                        $report.=$decode_dlr_reciept;
                                    } else {
                                        $report = $decode_dlr_reciept . " From SMSC<br/>Time: " . $sms['done_date'];
                                    }
                                } elseif ($sms['description'] != null) {
                                    $report = $sms['description'];
                                }
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td>
                                        <a href="#" data-container="body" data-toggle="popover" data-placement="right" data-html='true' data-trigger='click'
                                           data-content="<?php echo $report; ?>">
                                               <?php echo $sms['mobile_no']; ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php
                                        if ($sms['status'] == "1" || $sms['status'] == "PROCESSED" || $sms['status'] == 'ANSWERED_MACHINE') {
                                            echo "Delivered";
                                            //$total_delivered+=1;
                                        } elseif ($sms['status'] == "2" && strpos($decode_dlr_reciept, 'err:006') === false || $sms['status'] == "ERROR" || $sms['status'] == 'ERROR_NO_ANSWER') {
                                            echo "Failed";
                                            //$total_failed+=1;
                                        } elseif ($sms['status'] == "31" || $sms['status'] == "23" || $sms['status'] == "PENDING") {
                                            echo "Pending";
                                            //$total_pending+=1;
                                        } elseif ($sms['status'] == "8") {
                                            echo "Submitted";
                                            //$total_submit+=1;
                                        } elseif ($sms['status'] == "DND" || $sms['status'] == "9" || (strpos($decode_dlr_reciept, 'err:006') !== false && strpos($decode_dlr_reciept, 'stat:UNDELIV') !== false)) {
                                            echo "NDNC";
                                            //$total_dnd+=1;
                                        } elseif ($sms['status'] == "16" || $sms['status'] == "Rejected" || $sms['status'] == "ERROR_NOT_ENOUGH_CREDITS" || $sms['status'] == "ERROR_NETWORK_NOT_AVAILABLE" || $sms['status'] == 'ERROR_ROUTE_NOT_AVAILABLE' || $sms['status'] == 'ERROR_UNSUPPORTED_AUDIO_FORMAT' || $sms['status'] == 'ERROR_DOWNLOADING_FILE') {
                                            echo "Rejected From Operator";
                                            //$total_reject+=1;
                                        } elseif ($sms['status'] == "Blocked") {
                                            echo "Blocked By Operator";
                                            //$total_blocked+=1;
                                        } elseif ($sms['status'] == "3" || $sms['status'] == "4") {
                                            echo "Report Pending";
                                            //$total_sent+=1;
                                        } elseif ($sms['status'] == "25") {
                                            echo "Cancelled By User";
                                            //$total_cancelled+=1;
                                        } elseif ($sms['status'] == "ERROR_USER_BUSY") {
                                            echo "Processing";
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $sms['submit_date']; ?></td>
                                    <td>
                                        <?php
                                        if ($sms['status'] == "1" || $sms['status'] == "2" || $sms['status'] != "PENDING") {
                                            echo $sms['done_date'];
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a href="#" data-container="body" data-toggle="popover" data-placement="left" data-html='true' data-trigger='click'
                                           data-content="<label>Default: </label><?php echo $sms['default_ugroup_name']; ?>
                                           <br/>
                                           <label>Actual: </label>
                                           <?php echo $sms['actual_ugroup_name']; ?>">
                                            Details
                                        </a>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="6">No Record Found!</td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="row pager">
                <div class="col-md-2 padding0">
                    <div class="input-group">
                        <input type="text" name="page_no" id="page_no" class="form-control input-sm" value="<?php echo $page_no; ?>">
                        <span class="input-group-btn">
                            <button class="btn btn-primary btn-sm" type="button" onclick="pagingSentSMS(page_no.value, 100, <?php echo $campaign; ?>, <?php echo $total_pages; ?>);">Go</button>
                        </span>
                    </div>
                </div>
                <div class="col-md-10 text-right">
                    <nav>
                        <ul>
                            <?php if ($page_no > 1) { ?>
                                <li class="">
                                    <a href="javascript:void(0);" onclick="pagingSentSMS(<?php echo $page_no - 1; ?>, 100, <?php echo $campaign; ?>, <?php echo $total_pages; ?>);">
                                        <i class="fa fa-angle-double-left"></i>
                                    </a>
                                </li>
                            <?php } else { ?>
                                <li class="disabled">
                                    <a href="javascript:void(0);">
                                        <i class="fa fa-angle-double-left"></i>
                                    </a>
                                </li>
                            <?php } ?>
                            <li>
                                Page <strong><?php echo $page_no; ?></strong> of <strong><?php echo $total_pages; ?></strong>
                            </li>
                            <?php if ($page_no < $total_pages) { ?>
                                <li class="">
                                    <a href="javascript:void(0);" onclick="pagingSentSMS(<?php echo $page_no + 1; ?>, 100, <?php echo $campaign; ?>, <?php echo $total_pages; ?>);">
                                        <i class="fa fa-angle-double-right"></i>
                                    </a>
                                </li>
                            <?php } else { ?>
                                <li class="disabled">
                                    <a href="javascript:void(0);">
                                        <i class="fa fa-angle-double-right"></i>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </nav>
                </div>
            </div>

        </div>
        <div class="col-md-6">

            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered bgf9">
                        <tbody>
                            <tr>
                                <th>Status</th>
                                <?php echo ($total_dnd) ? "<th>NDNC</th>" : ""; ?>
                                <?php echo ($total_pending) ? "<th>Pending</th>" : ""; ?>
                                <?php echo ($total_blocked) ? "<th>Blocked</th>" : ""; ?>
                                <?php echo ($total_reject) ? "<th>Rejected</th>" : ""; ?>
                                <?php echo ($total_submit) ? "<th>Submitted</th>" : ""; ?>
                                <?php echo ($total_sent) ? "<th>Sent</th>" : ""; ?>
                                <?php echo ($total_failed) ? "<th>Failed</th>" : ""; ?>
                                <?php echo ($total_delivered) ? "<th>Delivered</th>" : ""; ?>
                                <?php echo ($total_cancelled) ? "<th>Cancelled</th>" : ""; ?>
                                <th>Total</th>
                            </tr>
                            <tr>
                                <th>Count</th>
                                <?php echo ($total_dnd) ? "<td>$total_dnd</td>" : ""; ?>
                                <?php echo ($total_pending) ? "<td>$total_pending</td>" : ""; ?>
                                <?php echo ($total_blocked) ? "<td>$total_blocked</td>" : ""; ?>
                                <?php echo ($total_reject) ? "<td>$total_reject</td>" : ""; ?>
                                <?php echo ($total_submit) ? "<td>$total_submit</td>" : ""; ?>
                                <?php echo ($total_sent) ? "<td>$total_sent</td>" : ""; ?>
                                <?php echo ($total_failed) ? "<td>$total_failed</td>" : ""; ?>
                                <?php echo ($total_delivered) ? "<td>$total_delivered</td>" : ""; ?>
                                <?php echo ($total_cancelled) ? "<td>$total_cancelled</td>" : ""; ?>
                                <th><?php echo $total; ?></th>
                            </tr>
                        </tbody>
                    </table>
                </div>      
            </div>

            <div class="col-md-12">
                <form action="javascript:reSendSMS();" id="resend_sms_form" id="validate-basic" class="form parsley-form">
                    <div class="col-md-4 padding0">
                        <label>Select Route</label>
                        <select name="resend_route" id="resend_route" class="form-control" required="" data-parsley-required-message="Please Select Route!">
                            <option value="">Select Route</option>
                            <?php
                           if($smsc_no != 0){
                            if (isset($pr_user_groups) && $pr_user_groups) {
                                ?>
                                <optgroup label="Promotional Routes">
                                    <?php
                                    foreach ($pr_user_groups as $pr_user_group) {
                                        ?>
                                        <option value="<?php echo $pr_user_group['smsc_id'] . "-A"; ?>"><?php echo $pr_user_group['user_group_name']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </optgroup>
                                <?php
                            }
                            if (isset($tr_user_groups) && $tr_user_groups) {
                                ?>
                                <optgroup label="Transactional Routes">
                                    <?php
                                    foreach ($tr_user_groups as $tr_user_group) {
                                        ?>
                                        <option value="<?php echo $tr_user_group['smsc_id'] . "-B"; ?>"><?php echo $tr_user_group['user_group_name']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </optgroup>
                                <?php
                            }
                           }else{
                             ?>
                                 <option value="A">Promotional</option>
                                  <option value="B">Dyanmic</option>
                         <?php       
                           }
                        
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Campaign</label>
                        <input type="text" name="resend_campaign_name" id="resend_campaign_name" value="Resend" class="form-control" />
                    </div>
                    <div class="col-md-3">
                        <label>Select Type</label>
                        <select name="resend_action_type" id="resend_action_type" class="form-control" required="" 
                                data-parsley-required-message="Please Select Action!">
                            <option value="">Select</option>
                            <option value="1">All</option>
                            <option value="2">Failed</option>
                            <option value="3">Sent</option>
                            <option value="4">Delivered</option>
                            <option value="5">Fake Failed</option>
                            <option value="6">Fake Delivered</option>
                            <option value="7">Rejected</option>
                            <option value="8">Pending Only</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <br/>
                        <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>" />
                        <input type="hidden" name="resend_campaign_id" id="resend_campaign_id" value="<?php echo $campaign; ?>" />
                        <button type="submit" name="resend" id="resend" class="btn btn-primary"
                                data-loading-text="Resending..." autocomplete="off">Resend</button>
                    </div>
                </form>
            </div>

            <div class="col-md-12" id="pie_graph_container"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var pending =<?php echo $total_pending; ?>;
        var dnd =<?php echo $total_dnd; ?>;
        var reject =<?php echo $total_reject; ?>;
        var block =<?php echo $total_blocked; ?>;
        var submit =<?php echo ($total_submit) ? $total_submit : $total_sent; ?>;
        var failed =<?php echo $total_failed; ?>;
        var dlrd =<?php echo $total_delivered; ?>;
        var cancelled =<?php echo $total_cancelled; ?>;
        // Build the pie chart
        Highcharts.setOptions({
            colors: ['#4A9AB5', '#C2C2C2', '#DE8742', '#735994', '#4271A5', '#AD4942', '#8CAA52', '#A3456B']
        });
        $('#pie_graph_container').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: true
            },
            title: {
                text: 'Status'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y:.f}</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                    type: 'pie',
                    name: 'Total',
                    data: [
                        ['Pending', pending],
                        ['NDNC', dnd],
                        ['Rejected', reject],
                        ['Blocked', block],
                        ['Submit', submit],
                        ['Failed', failed],
                        ['Delivered', dlrd],
                        ['Cancelled', cancelled]
                    ]
                }]
        });
    });
</script>

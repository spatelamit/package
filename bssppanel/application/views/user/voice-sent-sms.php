<?php
$campaign_id = 0;
$total = 0;
$total_pending = 0;
$total_sent = 0;
$total_dnd = 0;
$total_reject = 0;
$total_blocked = 0;
$total_submit = 0;
$total_failed = 0;
$total_delivered = 0;
?>
</div>
<script src="<?php echo base_url(); ?>Assets/user/js/highcharts.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>Assets/user/js/exporting.js" type="text/javascript"></script>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet">
                <div class="portlet-content">
                    <div class="row">
                        <div class="col-md-7 col-sm-6 borderR">
                            <div class="row">
                                <div class="col-md-12 content-header-title tbl">
                                    <div class="col-md-7 col-sm-6 col-xs-12">Voice Delivery Status</div>
                                </div>
                            </div>
                            <div class="table-responsive" style="overflow-y: auto;height: 550px;" id="sent_sms_table">
                                <table class="table table-hover bgf">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Receiver Number</th>
                                            <th>Status</th>
                                            <th>Submit Date</th>
                                            <th>Done Date</th>
                                            <th width="30%">Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($sent_sms) && $sent_sms) {
                                            $i = 1;
                                            foreach ($sent_sms as $sms) {
                                                $total+=1;
                                                ?>
                                                <tr>
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $sms['mobile_no']; ?></td>
                                                    <td>
                                                        <?php
                                                        if ($sms['status'] == "1" || $sms['status'] == "PROCESSED" || $sms['status'] == 'ANSWERED_MACHINE' || $sms['status'] == 'ANSWERED') {
                                                            echo "Delivered";
                                                          //  $total_delivered+=1;
                                                        } elseif ($sms['status'] == "2" || $sms['status'] == "ERROR" || $sms['status'] == 'ERROR_NO_ANSWER' || $sms['status'] == "INVALID") {
                                                            echo "Failed";
                                                           // $total_failed+=1;
                                                        } elseif ($sms['status'] == "PENDING" || $sms['status'] == 31) {
                                                            echo "Pending";
                                                           // $total_pending+=1;
                                                        } elseif ($sms['status'] == "8" || $sms['status'] == "ERROR_USER_BUSY" || $sms['status'] == "SUBMITTED") {
                                                            echo "Submitted";
                                                           // $total_submit+=1;
                                                        } elseif ($sms['status'] == "DND" || (strpos($decode_dlr_reciept, 'err:006') !== false && strpos($decode_dlr_reciept, 'stat:UNDELIV') !== false)) {
                                                            echo "NDNC";
                                                          //  $total_dnd+=1;
                                                        } elseif ($sms['status'] == "Rejected" || $sms['status'] == "ERROR_NOT_ENOUGH_CREDITS" || $sms['status'] == "ERROR_NETWORK_NOT_AVAILABLE" || $sms['status'] == 'ERROR_ROUTE_NOT_AVAILABLE' || $sms['status'] == 'ERROR_UNSUPPORTED_AUDIO_FORMAT' || $sms['status'] == 'ERROR_DOWNLOADING_FILE') {
                                                            echo "Rejected From Operator";
                                                          //  $total_reject+=1;
                                                        } elseif ($sms['status'] == "Blocked") {
                                                            echo "Block By Operator";
                                                          //  $total_blocked+=1;
                                                        } elseif ($sms['status'] == "3" || $sms['status'] == "4") {
                                                            echo "Report Pending";
                                                          //  $total_sent+=1;
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
                                                        <?php echo $sms['description']; ?>
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

                                    <tfoot>
                                        <tr>
                                            <td align="center" colspan="6">
                                                <ul class="pagination margin0">
                                                    <?php echo $pagination_helper; ?>
                                                </ul>
                                            </td>
                                        </tr>
                                    </tfoot>

                                </table>
                            </div>
                        </div>

                        <div class="col-md-5 col-sm-6" id="graph_container">
                            <div class="row">
                                <div class="col-md-12 col-xs-12 col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered bgf">
                                            <?php
//   $total_all=$total_delivered+$total_failed+$total_pending+$total_submit+$total_dnd+$total_reject+$total_reject+$total_blocked+$total_sent;
//$total_pending = $total_submit + $total_sent;
if (isset($sent_sms_status) && $sent_sms_status) {
    foreach ($sent_sms_status AS $status) {
        $smsc_no = $status['user_group_id'];
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
?>
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
                                                    <th><?php echo $total = $total_delivered + $total_blocked + $total_cancelled + $total_dnd + $total_failed + $total_pending + $total_reject + $total_sent + $total_submit; ?></th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>                                    
                                </div>
                                <div class="col-md-12">
                                    <hr/>
                                    <div class="col-md-12 col-sm-12 col-xs-12" id="pie_graph_container"></div>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div> 
        </div>

    </div>
</div>
<script type="text/javascript">
    $(function () {
        var pending =<?php echo $total_pending; ?>;
        var dnd =<?php echo $total_dnd; ?>;
        var reject =<?php echo $total_reject; ?>;
        var block =<?php echo $total_blocked; ?>;
        var submit =<?php echo ($total_submit) ? $total_submit : $total_sent; ?>;
        var failed =<?php echo $total_failed; ?>;
        var dlrd =<?php echo $total_delivered; ?>;
        // Build the pie chart
        Highcharts.setOptions({
            colors: ['#4A9AB5', '#C2C2C2', '#DE8742', '#735994', '#4271A5', '#AD4942', '#8CAA52']
        });

        $('#pie_graph_container').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
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
                        ['Delivered', dlrd]
                    ]
                }]
        });
    });
</script>
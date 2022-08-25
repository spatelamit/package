<?php
$campaign_id = 0;
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
                        <div class="col-md-6 col-sm-6 borderR">
                            <div class="row">
                                <div class="col-md-12 content-header-title tbl">
                                    <div class="col-md-7 col-sm-6 col-xs-12">Text Delivery Status</div>
                                    <div class="col-md-5 col-sm-6 col-xs-12">
                                        <div class="input-group um-search">
                                            <input class="form-control" placeholder="Search" type="text" onkeyup="searchSentSMS(this.value, <?php echo $campaign; ?>);">
                                            <span class="input-group-addon">
                                                <i class="fa fa-search"></i>
                                            </span>
                                        </div>
                                    </div>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $date = $schedule_date_msg->schedule_date;
                                        $current_date = date('Y-m-d H:i:s');
                                        if (strtotime($date) < strtotime($current_date)) {
                                            $date_status = 1;
                                        } else {
                                            $date_status = 0;
                                        }

                                        $i = 1;
                                        if (isset($sent_sms) && $sent_sms) {
                                            foreach ($sent_sms as $sms) {
                                                $dlr_reciept = $sms['dlr_receipt'];
                                                $decode_dlr_reciept = urldecode($dlr_reciept);
                                                $total+=1;
                                                ?>
                                                <tr>
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $sms['mobile_no']; ?></td>
                                                    <td>
                                                        <?php if ($date_status) {
                                                            ?>
                                                            <?php
                                                            if ($sms['status'] == "1") {
                                                                echo "Delivered";
                                                                $total_delivered+=1;
                                                            } elseif ($sms['status'] == "2" && strpos($decode_dlr_reciept, 'err:006') === false) {
                                                                echo "Failed";
                                                                $total_failed+=1;
                                                            } elseif ($sms['status'] == "31" || $sms['status'] == "23") {
                                                                echo "Pending";
                                                                $total_pending+=1;
                                                            } elseif ($sms['status'] == "8") {
                                                                echo "Submitted";
                                                                $total_submit+=1;
                                                            } elseif ($sms['status'] == "DND") {
                                                                echo "NDNC";
                                                                $total_dnd+=1;
                                                            } elseif ($sms['status'] == "16" || $sms['status'] == "Rejected") {
                                                                echo "Rejected From Operator";
                                                                $total_reject+=1;
                                                            } elseif ($sms['status'] == "Blocked") {
                                                                echo "Block By Operator";
                                                                $total_blocked+=1;
                                                            } elseif ($sms['status'] == "3" || $sms['status'] == "4") {
                                                                echo "Report Pending";
                                                                $total_sent+=1;
                                                            } elseif ($sms['status'] == "25") {
                                                                echo "Cancelled";
                                                                $total_cancelled+=1;
                                                            }
                                                            ?>
                                                            <?php
                                                        } else {
                                                             echo "Pending";
                                                              $total_pending+=1;
                                                            ?>
                                                        
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo $sms['submit_date']; ?></td>
                                                    <td><?php echo $sms['done_date']; ?></td>
                                                </tr>
        <?php
        $i++;
    }
} else {
    ?>
                                            <tr>
                                                <td colspan="5">No Record Found!</td>
                                            </tr>
    <?php
}
?>
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <td align="center" colspan="5">
                                                <ul class="pagination margin0">
<?php echo $pagination_helper; ?>
                                                </ul>
                                            </td>
                                        </tr>
                                    </tfoot>

                                </table>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6" id="graph_container">
                            <div class="row">
                                <div class="col-md-12 col-xs-12 col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered bgf">
<?php
//$total_pending = $total_submit + $total_sent;
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
                                                    <?php echo ($total_cancelled) ? "<th>Cancelled</th>" : ""; ?>
                                                    <th>Total</th>
                                                </tr>
<?php if ($date_status) {
    ?>
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
    <?php
} else {
    ?>
                                                    <tr>
                                                        <th>Count</th>

                                                        <th><?php echo $total; ?></th>
                                                    </tr>
    <?php
}
?>
                                            </tbody>
                                        </table>
                                    </div>                                    
                                </div>
                                <div class="col-md-12">
                                    <hr/>
<?php
//  $data = array('id' => "validate-basic", 'class' => "form parsley-form");
//echo form_open('user/resend_sms', $data);
?>
                                    <form action="javascript:reSendSchedule();" id="resend_schedule_form" id="validate-basic" class="form parsley-form">
                                        <div class="col-md-3">
                                            <label>Select Route</label>
                                            <select name="resend_route" id="resend_route" class="form-control" required="" data-parsley-required-message="Please Select Route!">
                                                <option value="">Select Route</option>                                            
                                                <option value="0-A">Promotional</option>
                                                <option value="0-B">Transactional</option>
                                                <option value="0-C">Stock Promotion</option>
                                                <option value="0-D">Premium Promotion</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Campaign name</label>
                                            <input type="text" name="resend_campaign_name" id="resend_campaign_name" value="Resend" class="form-control" />
                                        </div>
                                        <div class="col-md-4">
                                            <label>Select Type</label>
                                            <select name="resend_action_type" id="resend_action_type" class="form-control" required="" data-parsley-required-message="Please Select Action!">
                                                <option value="">Select</option>
                                                <option value="1">All</option>
                                                <option value="2">Failed</option>
                                                <option value="3">Sent</option>
                                                <option value="4">Delivered</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <br/>
                                            <input type="hidden" name="resend_campaign_id" id="resend_campaign_id" value="<?php echo $campaign; ?>" />
                                            <button type="submit" name="resend" class="btn btn-primary"  data-loading-text="Resending..." autocomplete="off" id="resend_sch">Resend</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-12">
                                    <hr/>
                                    <div class="col-md-12 col-sm-12 col-xs-12" id="pie_graph_container"></div>
                                    <!--<div class="col-md-12 col-sm-12 col-xs-12" id="bar_graph_container"></div>-->
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
        var cancelled =<?php echo $total_cancelled; ?>;
        // Build the bar chart
        /*
         $('#bar_graph_container').highcharts({
         chart: {
         type: 'column'
         },
         title: {
         text: 'Delivery Status'
         },
         xAxis: {
         title: {
         text: 'Designations'
         },
         categories: status,
         labels: {
         rotation: 0,
         align: 'center',
         style: {
         fontSize: '15px',
         fontFamily: 'Verdana, sans-serif'
         }
         }
         },
         yAxis: {
         title: {
         text: 'Total requests'
         }
         },
         legend: {
         enabled: false
         },
         plotOptions: {
         series: {
         borderWidth: 0,
         dataLabels: {
         enabled: true,
         format: '{point.y:.f}'
         }
         }
         },
         tooltip: {
         headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
         pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.f}</b><br/>'
         },
         series: [{
         name: 'Status',
         colorByPoint: true,
         data: [
         ['Sent', sent],
         ['DND', dnd],
         ['Blocked', block],
         ['Submit', submit],
         ['Failed', failed],
         ['Delivered', dlrd]
         ]
         }]
         });
         */
        // Build the pie chart
        Highcharts.setOptions({
            colors: ['#4A9AB5', '#C2C2C2', '#DE8742', '#735994', '#4271A5', '#AD4942', '#8CAA52', '#A3456B']
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
                        ['Delivered', dlrd],
                        ['Cancelled', cancelled]
                    ]
                }]
        });
    });
</script>
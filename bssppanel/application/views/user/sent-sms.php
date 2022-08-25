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
$total_landline = 0;
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
                                        $i = 1;
                                        if (isset($sent_sms) && $sent_sms) {
                                            foreach ($sent_sms as $sms) {
                                                $dlr_reciept = $sms['dlr_receipt'];
                                                $decode_dlr_reciept = urldecode($dlr_reciept);
                                                //      $total+=1;
                                                ?>
                                                <tr>
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $sms['mobile_no']; ?></td>
                                                    <td>
                                                        <?php
                                                        $campaign_status = $campaign_process_status->campaign_status;
                                                        if ($sms['status'] == "1" || $sms['status'] == "ANSWERED") {
                                                            echo "Delivered";
                                                            //  $total_delivered+=1;
                                                        } elseif ($sms['status'] == "2") {
                                                            echo "Failed";
                                                            //    $total_failed+=1;
                                                        } elseif ($sms['status'] == "31" || $sms['status'] == "23") {
                                                            if ($campaign_status == 2) {
                                                                echo "Pending";
                                                            } else {
                                                                echo "Submit";
                                                            }

                                                            //    $total_pending+=1;
                                                        } elseif ($sms['status'] == "8" || $sms['status'] == "SUBMITTED") {
                                                            echo "Submitted";
                                                            //  $total_submit+=1;
                                                        } elseif ($sms['status'] == "DND" || $sms['status'] == "9" || (strpos($decode_dlr_reciept, 'err:006') !== false && strpos($decode_dlr_reciept, 'stat:UNDELIV') !== false)) {
                                                            echo "NDNC";
                                                            //  $total_dnd+=1;
                                                        } elseif ($sms['status'] == "16" || $sms['status'] == "Rejected" || $sms['status'] == "INVALID") {
                                                            echo "Rejected From Operator";
                                                            //   $total_reject+=1;
                                                        } elseif ($sms['status'] == "Blocked") {
                                                            echo "Block By Operator";
                                                            //  $total_blocked+=1;
                                                        } elseif ($sms['status'] == "3" || $sms['status'] == "4") {
                                                            echo "Report Pending";
                                                            //   $total_sent+=1;
                                                        }elseif ($sms['status'] == "48") {
                                                            echo "Landline";
                                                            //   $total_sent+=1;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo $sms['submit_date']; ?></td>

                                                    <td>
                                                        <?php
                                                        if ($sms['status'] == "1" || $sms['status'] == "2" || $sms['status'] == "DND" || $sms['status'] == "9" || $sms['status'] == "16" || $sms['status'] == "Rejected" || $sms['status'] == "Blocked") {
                                                            echo $sms['done_date'];
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
                                                    } elseif ($status['status'] == "48") {
                                                        $total_landline += $status['Count_Status'];
                                                    }
                                                }
                                            }
                                            ?>
                                            <tbody>
                                                <tr>
                                                    <th>Status</th>
                                                    <?php echo ($total_dnd) ? "<th>NDNC</th>" : ""; ?>
                                                    <?php if ($campaign_status == 2) { ?>
                                                        <?php echo ($total_pending) ? "<th>Pending</th>" : ""; ?> 
                                                    <?php } else {
                                                        ?>
                                                        <?php echo ($total_pending) ? "<th>Submit</th>" : ""; ?>   
                                                        <?php
                                                    }
                                                    ?>

                                                    <?php echo ($total_blocked) ? "<th>Blocked</th>" : ""; ?>
                                                    <?php echo ($total_reject) ? "<th>Rejected</th>" : ""; ?>
                                                    <?php echo ($total_submit) ? "<th>Submitted</th>" : ""; ?>
                                                    <?php echo ($total_sent) ? "<th>Sent</th>" : ""; ?>
                                                    <?php echo ($total_failed) ? "<th>Failed</th>" : ""; ?>
                                                    <?php echo ($total_delivered) ? "<th>Delivered</th>" : ""; ?>
                                                     <?php echo ($total_landline) ? "<th>Landline</th>" : ""; ?>
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
                                                      <?php echo ($total_landline) ? "<td>$total_landline</td>" : ""; ?>
                                                    <th><?php echo $total = $total_delivered + $total_blocked + $total_cancelled + $total_dnd + $total_failed + $total_pending + $total_reject + $total_sent + $total_submit + $total_landline; ?></th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>                                    
                                </div>
                                <div class="col-md-12">
                                    <hr/>
                                    <?php
//$data = array('id' => "validate-basic", 'class' => "form parsley-form");
//echo form_open('user/resend_sms', $data);
                                    ?>
                                    <form action="javascript:reSendSMS();" id="resend_sms_form" id="validate-basic" class="form parsley-form">
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
                                            <button type="submit" name="resend" id="resend" class="btn btn-primary"
                                                    data-loading-text="Resending..." autocomplete="off">Resend</button>
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
          var landline =<?php echo $total_landline; ?>; 
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
            colors: ['#4A9AB5', '#C2C2C2', '#DE8742', '#735994', '#4271A5', '#AD4942', '#8CAA52','#d6ea6c']
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
                          ['Landline', landline]
                    ]
                }]
        });
    });
</script>
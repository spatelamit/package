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
$total_landline = 0;

if (isset($sent_sms_status) && $sent_sms_status) {
    foreach ($sent_sms_status AS $status) {
        if ($status['status'] == "1") {
            $total_delivered += $status['Count_Status'];
        } elseif ($status['status'] == "2") {
            $total_failed += $status['Count_Status'];
        } elseif ($status['status'] == "31") {
            $total_pending += $status['Count_Status'];
        } elseif ($status['status'] == "23") {
            $total_pending += $status['Count_Status'];
        } elseif ($status['status'] == "8") {
            $total_submit += $status['Count_Status'];
        } elseif ($status['status'] == "DND" || $status['status'] == "9") {
            $total_dnd += $status['Count_Status'];
        } elseif ($status['status'] == "16") {
            $total_reject += $status['Count_Status'];
        } elseif ($status['status'] == "Rejected") {
            $total_reject += $status['Count_Status'];
        } elseif ($status['status'] == "Blocked") {
            $total_blocked += $status['Count_Status'];
        } elseif ($status['status'] == "3") {
            $total_sent += $status['Count_Status'];
        } elseif ($status['status'] == "4") {
            $total_sent += $status['Count_Status'];
        } elseif ($status['status'] == "25") {
            $total_cancelled += $status['Count_Status'];
        }  elseif ($status['status'] == "48") {
            $total_landline += $status['Count_Status'];
        }
    }
}
?>
<div class="col-md-8 col-sm-12 col-xs-12" id="pie_graph_container"></div>
<div class="col-md-4 col-sm-12 col-xs-12">
    <form action="javascript:resendDeliveryReports();" id="resend_sms_form" class="form parsley-form">
        <div class="form-group padding0">
            <label>Select Route</label>
            <select name="resend_route" id="resend_route" class="form-control">
                <option value="">Select Route</option>
                <?php
                if (isset($pr_user_groups) && $pr_user_groups) {
                    ?>
                    <optgroup label="Promotional Routes">
                        <?php
                        foreach ($pr_user_groups as $pr_user_group) {
                            ?>
                            <option value="<?php echo $pr_user_group['smsc_id']; ?>"><?php echo $pr_user_group['user_group_name']; ?></option>
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
                            <option value="<?php echo $tr_user_group['smsc_id']; ?>"><?php echo $tr_user_group['user_group_name']; ?></option>
                            <?php
                        }
                        ?>
                    </optgroup>
                    <?php
                }
                ?>
            </select>
        </div>
        <div class="form-group padding0">
            <label>Select Type</label>
            <select name="resend_action_type" id="resend_action_type" class="form-control" required="" 
                    data-parsley-required-message="Please Select Action!">
                <option value="">Select</option>
                <option value="1">All</option>
                <option value="2">Failed</option>
                <option value="16">Rejected</option>
                <option value="3">Sent</option>
                 <option value="31">Pending Only</option>
                <option value="4">Delivered</option>
                <option value="5">Fake Failed</option>
                <option value="6">Fake Delivered</option>
            </select>
        </div>
        <div class="form-group padding0">
            <button type="submit" name="resend" id="resend" class="btn btn-primary"
                    data-loading-text="Resending..." autocomplete="off">Resend</button>
        </div>
    </form>
</div>
<script type = "text/javascript">
    $('#resend_sms_form').parsley();
    $(document).ready(function () {
        var pending = <?php echo $total_pending; ?>;
        var dnd =<?php echo $total_dnd; ?>;
        var reject =<?php echo $total_reject; ?>;
        var block =<?php echo $total_blocked; ?>;
        var sent =<?php echo ($total_submit) ? $total_submit : $total_sent; ?>;
        var failed =<?php echo $total_failed; ?>;
        var dlrd =<?php echo $total_delivered; ?>;
        var cancelled =<?php echo $total_cancelled; ?>;
         var landline =<?php echo $total_landline; ?>;
        // Build the pie chart
        Highcharts.setOptions({
            colors: ['#4A9AB5', '#C2C2C2', '#DE8742', '#735994', '#4271A5', '#AD4942', '#8CAA52', '#A3456B','#d6ea6c']
        });

        // Pie Chart
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
                        ['Sent', sent],
                        ['Failed', failed],
                        ['Delivered', dlrd],
                        ['Cancelled', cancelled],
                        ['Landline', landline]
                    ]
                }]
        });
    });
</script>
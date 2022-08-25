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

    if ($sent_sms_status[0][2]) {
        $total_failed = $sent_sms_status[0][2];
    } 
    if ($sent_sms_status[0][1]) {
        $total_delivered = $sent_sms_status[0][1];
    } 
    if ($sent_sms_status[0][31]) {
        $total_submit = $sent_sms_status[0][31];
    } 
    if ($sent_sms_status[0][8]) {
        $total_sent1 = $sent_sms_status[0][8];
    }
    if ($sent_sms_status[0][3]) {
        $total_sent2 = $sent_sms_status[0][3];
    } 
    if ($sent_sms_status[0][4]) {
        $total_sent3 = $sent_sms_status[0][4];
    } 
    if ($sent_sms_status[0][16]) {
        $total_reject1 = $sent_sms_status[0][16];
    }
    if ($sent_sms_status[0][25]) {
        $total_cancelled = $sent_sms_status[0][25];
    } 
    if ($sent_sms_status[0][48]) {
        $total_landline = $sent_sms_status[0][48];
    } 
    if ($sent_sms_status[0]['Blocked']) {
        $total_blocked = $sent_sms_status[0]['Blocked'];
    } 
    if ($sent_sms_status[0]['Rejected']) {
        $$total_reject2 = $sent_sms_status[0]['Rejected'];
    }
    
    if($total_sent1 || $total_sent2  || $total_sent3){
      $total_sent = $total_sent1 + $total_sent2 + $total_sent3;  
    }
    if($$total_reject2 || $$total_reject2){
      $$total_reject = $$total_reject1 + $$total_reject2 ;  
    }


     $total = $total_failed + $total_delivered + $total_pending + $total_sent + $total_reject + $total_cancelled + $total_landline;
echo "<b>Total SMS : ".$total."<b>";
     
    }
?>
<div class="col-md-8 col-sm-12 col-xs-12" id="pie_graph_container"></div>

<script type = "text/javascript">
    $('#resend_sms_form').parsley();
    $(document).ready(function () {
        var pending = <?php echo $total_pending; ?>;
        var dnd =<?php echo $total_dnd; ?>;
        var reject =<?php echo $total_reject; ?>;
        var block =<?php echo $total_blocked; ?>;
        var submit =<?php echo $total_sent; ?>;
        var failed =<?php echo $total_failed; ?>;
        var dlrd =<?php echo $total_delivered; ?>;
        var cancelled =<?php echo $total_cancelled; ?>;
        var landline =<?php echo $total_landline; ?>;
        // Build the pie chart
        Highcharts.setOptions({
            colors: ['#4A9AB5', '#C2C2C2', '#DE8742', '#735994', '#4271A5', '#AD4942', '#8CAA52', '#A3456B', '#d6ea6c']
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
                        ['Submit', submit],
                        ['Failed', failed],
                        ['Delivered', dlrd],
                        ['Cancelled', cancelled],
                        ['Landline', landline]
                    ]
                }]
        });
    });
</script>
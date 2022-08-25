 
<script src="<?php echo base_url(); ?>Assets/user/js/highcharts.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>Assets/user/js/exporting.js" type="text/javascript"></script>

<div class="col-md-12">
         <div class="col-md-12 col-sm-12 col-xs-12" id="pie_graph_container"></div>                     
 </div>

<script type="text/javascript">
   
    $(function () {
         var submit =<?php echo $total_submit; ?>;
        var dnd =<?php echo 0; ?>;
        var reject =<?php echo $rejected; ?>;
        var block =<?php echo 0; ?>;
       // var submit =<?php// echo ($total_submit) ? $total_submit : $total_sent; ?>;
       var sent =<?php echo $sent; ?>;
        var failed =<?php echo $failed; ?>;
        var dlrd =<?php echo $delivered; ?>;
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
                        ['Submit', submit],
                        ['NDNC', dnd],
                        ['Rejected', reject],
                        ['Blocked', block],
                        ['sent', sent],
                        ['Failed', failed],
                        ['Delivered', dlrd]
                    ]
                }]
        });
    });
</script>
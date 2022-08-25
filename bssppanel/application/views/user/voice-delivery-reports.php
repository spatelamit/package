</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet">
                <div class="portlet-content">
                    <div class="row">
                        <div class="col-md-12 content-header-title tbl">
                            <div class="col-md-5 col-sm-3 col-xs-12 padding0">Voice Delivery Report</div>
                        </div>
                    </div>
                    <div class="table-responsive mt5" id="data_table">
                        <table class="table table-hover bgf">
                            <thead>
                                <tr>
                                    <th>Sent Time</th>
                                    <th>Campaign</th>
                                    <th>Schedule</th>
                                    <th>Message</th>
                                    <th>SMS</th>
                                    <th>Request</th>
                                    <th>Download</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($voice_delivery_reports) && $voice_delivery_reports) {
                                    $i = 1;
                                    foreach ($voice_delivery_reports as $delivery_report) {
                                        ?>
                                        <tr>
                                            <td><?php echo $delivery_report['submit_date']; ?></td>
                                            <td>
                                                <?php echo $delivery_report['campaign_name']; ?><br/>
                                            </td>
                                            <td>
                                                <h5><strong>Start Date</strong>: <?php echo $delivery_report['start_date']; ?></h5>
                                                <h5><strong>End Date</strong>: <?php echo $delivery_report['end_date']; ?></h5>
                                            </td>
                                            <td width="35%" class="word-break">
                                                <b title="Caller Id" style=" font-weight: bold;">
                                                    <?php echo $delivery_report['caller_id']; ?>
                                                </b>
                                                <br/>
                                                <a  style=" color: royalblue;" title="Click To See Full Delivery Report" href="<?php echo base_url(); ?>user/voice_sent_sms/<?php echo $delivery_report['campaign_id'] . "/" . $delivery_report['route']; ?>">
                                                    <?php echo urldecode($delivery_report['message']); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <strong>Submitted: </strong><?php echo $delivery_report['total_messages']; ?><br/>
                                                <strong>Credit: </strong><?php echo $delivery_report['total_credits']; ?><br/>
                                                <strong>Deducted: </strong><?php echo $delivery_report['total_deducted']; ?><br/>
                                                <strong>Processed: </strong><?php echo $delivery_report['actual_message']; ?><br/>
                                            </td>
                                            <td>
                                                <ul class="fa-ul padding0">
                                                    <li>
                                                        <i class="fa-li fa fa-envelope-o"></i>
                                                        <?php if ($delivery_report['route'] == 'A') { ?>
                                                            <span class="label label-success">Promotional</span>                                                        
                                                        <?php } elseif ($delivery_report['route'] == 'B') { ?>
                                                            <span class="label label-danger">Dynamic</span>
                                                        <?php } ?>
                                                    </li>
                                                    <li>
                                                        <i class="fa-li fa fa-bookmark-o"></i>
                                                        <?php echo strtolower($delivery_report['campaign_uid']); ?>
                                                    </li>
                                                    <li>
                                                        <i class="fa-li fa fa-tags"></i>
                                                        <?php echo $delivery_report['request_by']; ?>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>
                                           
                                                <a href="<?php echo base_url(); ?>user/export_numbers/<?php echo $delivery_report['campaign_id']; ?>"  data-toggle="tooltip" title="Report Will Be Send On Your Register Email Address" data-placement="left"  class="btn btn-primary">Export</a>
                                        
                                                </td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="7">No Record Found!</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="text-center" colspan="7">
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
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
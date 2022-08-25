</div>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="portlet">
                <!--                <h2 class="content-header-title tbl">Schedule SMS</h2>-->
                <div class="row">
                    <div class="col-md-12 content-header-title tbl">
                        <div class="col-md-5 col-sm-3 col-xs-12 padding0">Schedule SMS</div>

                        <!--                            <div class="col-md-2 col-sm-3 col-xs-12 pull-right">
                                                        <div class="input-group um-search">
                                                            <input class="form-control" placeholder="Search" type="text" onkeyup="searchDscheduleReports(this.value);">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-search"></i>
                                                            </span>
                                                        </div>
                                                    </div>-->
                    </div>
                </div>
                <div class="portlet-content">
                    <div class="table-responsive mt5" id="data_table">
                        <table class="table table-hover bgf">
                            <thead>
                                <tr>
                                    <th>Sent Time</th>
                                    <th>Schedule Time</th>
                                    <th>Campaign</th>
                                    <th>Message</th>
                                    <th>SMS</th>
                                    <th>Request</th>
                                    <th>Summary</th>
                                    <th colspan="2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                if (isset($schedule_reports) && $schedule_reports) {
                                    foreach ($schedule_reports as $schedule_report) {
                                        ?>
                                        <tr>
                                            <td><?php echo $schedule_report['submit_date']; ?></td>
                                            <td><?php
                                                echo $date = $schedule_report['schedule_date'];
                                                $current_date = date('Y-m-d H:i:s');
                                                if (strtotime($date) < strtotime($current_date)) {
                                                    $date_status = 1;
                                                } else {
                                                    $date_status = 0;
                                                }
                                                ?></td>
                                            <td>
                                                <?php echo $schedule_report['campaign_name']; ?><br/>
                                                <?php
                                                if ($schedule_report['schedule_action']) {
                                                    if ($schedule_report['message_category'] == 2 && $schedule_report['schedule_status'] == 1) {
                                                        ?>
                                                        <span class="label label-warning">Campaign Scheduled</span>
                                                        <?php
                                                    } elseif ($schedule_report['message_category'] == 2 && $schedule_report['schedule_status'] == 0) {
                                                        ?>
                                                        <span class="label label-success">Campaign Completed</span>
                                                        <?php
                                                    } elseif ($schedule_report['campaign_status'] == 3) {
                                                        ?>
                                                        <span class="label label-danger">Campaign Rejected</span>
                                                        <?php
                                                    }
                                                } elseif (!$schedule_report['schedule_action']) {
                                                    ?>
                                                    <span class="label label-danger">Campaign Cancelled</span>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                            <td width="382px">
                                                <b>
                                                    <?php echo $schedule_report['sender_id']; ?>
                                                </b>
                                                <br/>
                                                <a href="<?php echo base_url(); ?>user/schedule_sms/<?php echo $schedule_report['campaign_id'] . "/" . $schedule_report['route']; ?>">
                                                    <?php echo urldecode($schedule_report['message']); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <strong>Submitted: </strong><?php echo $total_sms = $schedule_report['total_messages']; ?><br/>
                                                <strong>Credit: </strong><?php echo $schedule_report['total_credits']; ?><br/>
                                                <strong>Deducted: </strong><?php echo $schedule_report['total_deducted']; ?><br/>
                                                <strong>Processed: </strong><?php echo $schedule_report['actual_message']; ?><br/>
                                            </td>
                                            <td>
                                                <ul class="fa-ul padding0">
                                                    <li>
                                                        <i class="fa-li fa fa-envelope-o"></i>
                                                        <?php if ($schedule_report['route'] == 'A') { ?>
                                                            <span class="label label-success">Promotional</span>                                                        
                                                        <?php } elseif ($schedule_report['route'] == 'B') { ?>
                                                            <span class="label label-danger">Transactional</span>
                                                        <?php } elseif ($schedule_report['route'] == 'C') { ?>
                                                            <span class="label label-primary">Stock Promotion</span>
                                                        <?php } elseif ($schedule_report['route'] == 'D') { ?>
                                                            <span class="label label-warning">Premium Promotion</span> 

                                                        <?php } ?>
                                                    </li>
                                                    <li>
                                                        <i class="fa-li fa fa-bolt"></i>
                                                        <?php
                                                        if ($schedule_report['message_type'] == 2)
                                                            echo "Unicode";
                                                        elseif ($schedule_report['message_type'] == 1)
                                                            echo "Normal";
                                                        ?>
                                                    </li>
                                                    <?php
                                                    if ($schedule_report['flash_message']) {
                                                        ?>
                                                        <li>
                                                            <i class="fa-li fa fa-bookmark-o"></i>Flash
                                                        </li>
                                                        <?php
                                                    }
                                                    ?>
                                                    <li>
                                                        <i class="fa-li fa fa-bookmark-o"></i>
                                                        <?php echo strtolower($schedule_report['campaign_uid']); ?>
                                                    </li>
                                                    <li>
                                                        <i class="fa-li fa fa-tags"></i>
                                                        <?php echo $schedule_report['request_by']; ?>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>
                                                <ul class="fa-ul padding0">                                                            
                                                    <?php
                                                    if ($date_status) {
                                                        if ($schedule_report['summary']) {
                                                            $total_pending = 0;
                                                            $total_cancelled = 0;
                                                            $total_sent = 0;
                                                            $total_dnd = 0;
                                                            $total_reject = 0;
                                                            $total_blocked = 0;
                                                            $total_submit = 0;
                                                            $total_failed = 0;
                                                            $total_delivered = 0;
                                                            foreach ($schedule_report['summary'] as $key => $summary) {
                                                                if ($summary->status == "1") {
                                                                    $total_delivered += $summary->Count_Status;
                                                                } elseif ($summary->status == "2") {
                                                                    $total_failed += $summary->Count_Status;
                                                                } elseif ($summary->status == "31" || $summary->status == "23") {
                                                                    $total_pending += $summary->Count_Status;
                                                                } elseif ($summary->status == "8") {
                                                                    $total_submit += $summary->Count_Status;
                                                                } elseif ($summary->status == "DND") {
                                                                    $total_dnd += $summary->Count_Status;
                                                                } elseif ($summary->status == "16" || $summary->status == "Rejected") {
                                                                    $total_reject += $summary->Count_Status;
                                                                } elseif ($summary->status == "Blocked") {
                                                                    $total_blocked += $summary->Count_Status;
                                                                } elseif ($summary->status == "3" || $summary->status == "4") {
                                                                    $total_sent += $summary->Count_Status;
                                                                } elseif ($summary->status == "25") {
                                                                    $total_cancelled += $summary->Count_Status;
                                                                }
                                                            }
                                                            echo ($total_delivered) ? "<li style='color:#7FB338;font-weight: bold;'><i class='fa-li fa fa-check'></i>Delivered: $total_delivered</li>" : "";
                                                            echo ($total_failed) ? "<li style='color:#aa0000; font-weight: bold;' ><i class='fa-li fa fa-times'></i>Failed: $total_failed</li>" : "";

                                                            if ($schedule_report['campaign_status'] == 2) {
                                                                echo ($total_pending) ? "<li style='color:blue; font-weight: bold;'><i class='fa-li fa fa-spinner fa-spin'></i>Pending: $total_pending</li>" : "";
                                                            } else {
                                                                echo ($total_pending) ? "<li style='color:#4A9AB5; font-weight: bold;' ><i class='fa-li fa fa-cloud-download'></i>Submit: $total_pending</li>" : "";
                                                            }
                                                            echo ($total_dnd) ? "<li style='color:#888; font-weight: bold;'><i class='fa-li fa fa-ban'></i>DND: $total_dnd</li>" : "";
                                                            echo ($total_reject) ? "<li style='color:#DE8742; font-weight: bold;' ><i class='fa-li fa fa-times'></i>Rejetced: $total_reject</li>" : "";
                                                            echo ($total_blocked) ? "<li style='color:#1f1d1d; font-weight: bold;' ><i class='fa-li fa fa-lock'></i>Blocked: $total_blocked</li>" : "";
                                                            echo ($total_sent) ? "<li style='color:#4271A5; font-weight: bold;' ><i class='fa-li fa fa-arrow-up'></i>Sent: $total_sent</li>" : "";
                                                            echo ($total_cancelled) ? "<li style='color:#E67E22; font-weight: bold;' ><i class='fa-li fa fa-times'></i>Cancelled: $total_cancelled</li>" : "";
                                                        }
                                                    } else {
                                                        echo "<li style='color:#4A9AB5; font-weight: bold;' ><i class='fa-li fa fa-cloud-download'></i>Submit:" . $total_sms . "</li>";
                                                    }
                                                    ?>
                                                </ul>
                                            </td>
                                            <?php if ($schedule_report['schedule_action'] && $schedule_report['message_category'] == 2 && $schedule_report['schedule_status'] == 1) { ?>
                                                <td>
                                                    <a href="<?php echo base_url() . "user/send_sch_sms/" . $schedule_report['campaign_id']; ?>" class="btn btn-primary btn-sm">Send Now</a>
                                                </td>
                                                <td>
                                                   <button type="button" name="export_all" id="btn" class="btn btn-primary" data-loading-text="Processing..."  autocomplete="off" onclick="sechduleCancel(<?php echo $schedule_report['campaign_id'] ?>);">Cancel</button>                                              
                                                </td>
                                                <?php
                                            } else {
                                                ?>
                                                <td colspan="2">
                                                    <a class="btn btn-primary btn-sm" data-toggle="tooltip" title="Report Will Be Send On Your Register Email Address" data-placement="left" href="<?php echo base_url(); ?>export/export_schedule_reports/<?php echo $schedule_report['campaign_id']; ?>">
                                                        Export
                                                    </a>
                                                </td>
                                                <?php
                                            }
                                            ?>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="9">No Record Found!</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="text-center" colspan="9">
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
    $("button").click(function(){
        $("#btn").hide(100);
    });
});
</script>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
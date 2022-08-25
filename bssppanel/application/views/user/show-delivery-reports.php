  <div class="table-responsive mt5" id="data_table">
                        <table class="table table-hover bgf"  >
                            <thead>
                                <tr>
                                    <th>Sent Time</th>
                                    <th>Campaign</th>
                                    <th>Message</th>
                                    <th>SMS</th>
                                    <th>Request</th>
                                    <th>Summary</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($delivery_reports) && $delivery_reports) {
                                    $i = 1;
                                    foreach ($delivery_reports as $delivery_report) {
                                        ?>
                                        <tr  <?php if ($i % 2 == 0) { ?>
                                                style="background-color: gainsboro;"  
                                            <?php } else { ?>
                                                style="background-color: white;"  

                                            <?php } ?>>
                                            <td><?php echo $delivery_report['submit_date']; ?></td>
                                            <td>
                                                <?php echo $delivery_report['campaign_name']; ?><br/>
                                                <?php
                                                if ($delivery_report['campaign_status'] == 1) {
                                                    ?>
                                                    <span class="label label-success">Campaign Complete</span>
                                                    <?php
                                                } elseif ($delivery_report['campaign_status'] == 3) {
                                                    ?>
                                                    <span class="label label-danger">Campaign Rejected</span>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                            <td width="35%" class="word-break">
                                                <b title="Sender Id" style=" font-weight: bold;">
                                                    <?php echo $delivery_report['sender_id']; ?>
                                                </b>
                                                <br/>
                                                <a style=" color: royalblue;" title="Click To See Full Delivery Report" href="<?php echo base_url(); ?>user/sent_sms/<?php echo $delivery_report['campaign_id'] . "/" . $delivery_report['route']; ?>">
                                                    <?php
                                                    if ($user_info['encription'] == 1) {
                                                        echo md5($delivery_report['message']);
                                                    } else {
                                                        echo urldecode($delivery_report['message']);
                                                    }
                                                    ?>
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
                                                            <span class="label label-danger">Transactional</span>
                                                        <?php } elseif ($delivery_report['route'] == 'C') { ?>
                                                            <span class="label label-primary">Stock Promotion</span>
                                                        <?php } elseif ($delivery_report['route'] == 'I') { ?>
                                                            <span class="label label-info"> International</span>
                                                        <?php } elseif ($delivery_report['route'] == 'D') { ?>
                                                            <span class="label label-warning">Premium Promotion</span>
                                                        <?php } ?>
                                                    </li>
                                                    <li>
                                                        <i class="fa-li fa fa-bolt"></i>
                                                        <?php
                                                        if ($delivery_report['message_type'] == 2)
                                                            echo "Unicode";
                                                        elseif ($delivery_report['message_type'] == 1)
                                                            echo "Normal";
                                                        ?>
                                                    </li>
                                                    <?php
                                                    if ($delivery_report['flash_message']) {
                                                        ?>
                                                        <li>
                                                            <i class="fa-li fa fa-bookmark-o"></i>Flash
                                                        </li>
                                                        <?php
                                                    }
                                                    ?>
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
                                                <ul class="fa-ul padding0">
                                                    <?php
                                                    if ($delivery_report['summary']) {
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
                                                        foreach ($delivery_report['summary'] as $key => $summary) {
                                                            if ($summary->status == "1") {
                                                                $total_delivered += $summary->Count_Status;
                                                            } elseif ($summary->status == "2") {
                                                                $total_failed += $summary->Count_Status;
                                                            } elseif ($summary->status == "31" || $summary->status == "23") {
                                                                $total_pending += $summary->Count_Status;
                                                            } elseif ($summary->status == "8") {
                                                                $total_submit += $summary->Count_Status;
                                                            } elseif ($summary->status == "DND" || $summary->status == "9") {
                                                                $total_dnd += $summary->Count_Status;
                                                            } elseif ($summary->status == "16" || $summary->status == "Rejected") {
                                                                $total_reject += $summary->Count_Status;
                                                            } elseif ($summary->status == "Blocked") {
                                                                $total_blocked += $summary->Count_Status;
                                                            } elseif ($summary->status == "3" || $summary->status == "4") {
                                                                $total_sent += $summary->Count_Status;
                                                            } elseif ($summary->status == "25") {
                                                                $total_cancelled += $summary->Count_Status;
                                                            } elseif ($summary->status == "48") {
                                                                $total_landline += $summary->Count_Status;
                                                            }
                                                        }

                                                        echo ($total_delivered) ? "<li style='color:#7FB338;font-weight: bold;'><i class='fa-li fa fa-check'></i>Delivered: $total_delivered</li>" : "";
                                                        echo ($total_failed) ? "<li style='color:#aa0000; font-weight: bold;' ><i class='fa-li fa fa-times'></i>Failed: $total_failed</li>" : "";

                                                        if ($delivery_report['campaign_status'] == 2) {
                                                            echo ($total_pending) ? "<li style='color:blue; font-weight: bold;'><i class='fa-li fa fa-spinner fa-spin'></i>Pending: $total_pending</li>" : "";
                                                        } else {
                                                            echo ($total_pending) ? "<li style='color:#4A9AB5; font-weight: bold;' ><i class='fa-li fa fa-cloud-download'></i>Submit: $total_pending</li>" : "";
                                                        }
                                                        echo ($total_dnd) ? "<li style='color:#888; font-weight: bold;'><i class='fa-li fa fa-ban'></i>DND: $total_dnd</li>" : "";
                                                        echo ($total_reject) ? "<li style='color:#DE8742; font-weight: bold;' ><i class='fa-li fa fa-times'></i>Rejetced: $total_reject</li>" : "";
                                                        echo ($total_blocked) ? "<li style='color:#1f1d1d; font-weight: bold;' ><i class='fa-li fa fa-lock'></i>Blocked: $total_blocked</li>" : "";
                                                        echo ($total_sent) ? "<li style='color:#4271A5; font-weight: bold;' ><i class='fa-li fa fa-arrow-up'></i>Sent: $total_sent</li>" : "";
                                                        echo ($total_cancelled) ? "<li style='color:#E67E22; font-weight: bold;' ><i class='fa-li fa fa-times'></i>Cancelled: $total_cancelled</li>" : "";
                                                        echo ($total_landline) ? "<li style='color:#E67E22; font-weight: bold;'><i class='fa-li fa fa-phone'></i>Landline: $total_landline</li>" : "";
                                                    }
                                                    ?>
                                                </ul>
                                            </td>

                                            <td style="vertical-align: middle">

                                                <?php
                                                if ($user_id == 9070) {
                                                    
                                                } else {
                                                    ?>
                                                    <a class="btn btn-primary btn-sm" data-toggle="tooltip" title="Report Will Be Send On Your Register Email Address" data-placement="left"
                                                       href="<?php echo base_url(); ?>export/index/<?php echo $delivery_report['campaign_id']; ?>">
                                                        Export
                                                    </a>
                                                <?php } ?>

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
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
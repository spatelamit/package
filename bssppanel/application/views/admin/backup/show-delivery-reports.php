<div class="row" id="search_dlr_report_table">
    <div class="col-md-12">
        <nav>
            <ul class="pager">
                <?php if ($page_no > 1) { ?>
                    <li class="previous">
                        <a href="javascript:void(0);" onclick="pagingDlrReport(<?php echo $page_no - 1; ?>, 100);">
                            <span aria-hidden="true">&larr;</span> Newer
                        </a>
                    </li>
                <?php } else { ?>
                    <li class="previous disabled">
                        <a href="javascript:void(0);">
                            <span aria-hidden="true">&larr;</span> Newer
                        </a>
                    </li>
                <?php } ?>
                <li>
                    Page <strong><?php echo $page_no; ?></strong> of <strong><?php echo $total_pages; ?></strong>
                </li>
                <?php if ($page_no < $total_pages) { ?>
                    <li class="next">
                        <a href="javascript:void(0);" onclick="pagingDlrReport(<?php echo $page_no + 1; ?>, 100);">
                            Older <span aria-hidden="true">&rarr;</span>
                        </a>
                    </li>
                <?php } else { ?>
                    <li class="next disabled">
                        <a href="javascript:void(0);">
                            Older <span aria-hidden="true">&rarr;</span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    </div>
    <div class="col-md-12 padding15">
        <div class="table-responsive">
            <table class="table table-hover fade in">
                <thead>
                    <tr>
                        <!--<th>Username</th>-->
                        <th>Campaign</th>
                        <th>Message</th>
                        <th>SMS</th>
                        <th>Request</th>
                        <th>Summary</th>
                    </tr>
                </thead>
                <tbody class="bgf7">
                    <?php
                    if ($delivery_reports) {
                        $i = 1;
                        foreach ($delivery_reports as $delivery_report) {
                            ?>
                            <tr>
                                <td>
                                    <strong>
                                        <?php
                                        if ($delivery_report['admin_id'] == "") {
                                            ?>
                                            <?php echo $delivery_report['username']; ?>
                                            ( <?php echo ($delivery_report['parent_username'] == "") ? $delivery_report['admin_username'] : $delivery_report['parent_username']; ?> )                        
                                        <?php } else { ?>
                                            <?php echo "Control Admin"; ?>
                                        <?php } ?>
                                    </strong><br/>
                                    <?php echo $delivery_report['campaign_name']; ?><br/>
                                    <?php echo $delivery_report['submit_date']; ?><br/>
                                    <?php if ($delivery_report['message_category'] == 2) { ?>
                                        <?php echo $delivery_report['schedule_date'] . "<br/>"; ?>
                                    <?php } ?>
                                    <?php
                                    if ($delivery_report['campaign_status'] == 1 && $delivery_report['message_category'] == 1) {
                                        ?>
                                        <span class="label label-success">Campaign Completed</span><br/>
                                        <?php
                                    } elseif ($delivery_report['message_category'] == 2 && $delivery_report['schedule_status'] == 1) {
                                        ?>
                                        <span class="label label-warning">Campaign Scheduled</span><br/>
                                        <?php
                                    } elseif ($delivery_report['message_category'] == 2 && $delivery_report['schedule_status'] == 0) {
                                        ?>
                                        <span class="label label-success">Campaign Completed</span><br/>
                                        <?php
                                    } elseif ($delivery_report['campaign_status'] == 3) {
                                        ?>
                                        <span class="label label-danger">Campaign Rejected</span><br/>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td style="word-break: break-all;width: 400px;">
                                    <?php echo $delivery_report['sender_id']; ?><br/>
                                    <a href="<?php echo base_url(); ?>admin/sent_sms/<?php echo $delivery_report['campaign_id'] . "/" . $delivery_report['route'] . "/" . $delivery_report['user_id']; ?>">
                                        <?php echo urldecode($delivery_report['message']); ?>
                                    </a>
                                </td>
                                <td>
                                    <label>Submitted: </label><?php echo $delivery_report['total_messages']; ?><br/>
                                    <label>Credit: </label><?php echo $delivery_report['total_credits']; ?><br/>
                                    <label>Deducted: </label><?php echo $delivery_report['total_deducted']; ?><br/>
                                    <label>Processed: </label><?php echo $delivery_report['actual_message']; ?><br/>
                                </td>
                                <td>
                                    <ul class="fa-ul padding0">
                                        <li>
                                            <i class="fa-li fa fa-envelope-o"></i>
                                            <?php if ($delivery_report['route'] == 'A') { ?>
                                                <span class="label label-success">Promotional</span>
                                            <?php } elseif ($delivery_report['route'] == 'B') { ?>
                                                <span class="label label-danger">Transactional</span>
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
                                        <?php if ($delivery_report['message_category'] == 2) { ?>
                                            <li>
                                                <i class="fa-li fa fa-clock-o"></i> Scheduled
                                            </li>
                                        <?php } ?>
                                        <?php if ($delivery_report['flash_message']) { ?>
                                            <li>
                                                <i class="fa-li fa fa-bookmark-o"></i> Flash
                                            </li>
                                        <?php } ?>
                                        <li>
                                            <i class="fa-li fa fa-tags"></i>
                                            <?php echo $delivery_report['request_by']; ?>
                                        </li>
                                        <!--
                                        <li>
                                            <i class="fa-li fa fa-bookmark-o"></i>
                                        <?php //echo $delivery_report['campaign_uid']; ?>
                                        </li>
                                        -->
                                        <li>
                                            <i class="fa-li fa fa-check"></i>
                                            <?php echo $delivery_report['user_group_name']; ?>
                                        </li>
                                        <?php
                                        $black_listed = explode('|', $delivery_report['black_listed']);
                                        if ($black_listed[0]) {
                                            ?>
                                            <li>
                                                <i class="fa-li fa fa-ban"></i>
                                                <?php echo ($black_listed[0]) ? "Black Keyword" : ""; ?>
                                            </li>
                                            <?php
                                        }
                                        if ($black_listed[1]) {
                                            ?>
                                            <li>
                                                <i class="fa-li fa fa-ban"></i>
                                                <?php echo ($black_listed[1]) ? "Black Sender" : ""; ?>
                                            </li>
                                        <?php } ?>
                                        <?php if ($delivery_report['total_time']) { ?>
                                            <li>
                                                <i class="fa-li fa fa-clock-o"></i>
                                                <?php echo $delivery_report['total_time'] . " Seconds"; ?>
                                            </li>
                                        <?php } ?>
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
                                            foreach ($delivery_report['summary'] as $key => $summary) {
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
                                            echo ($total_delivered) ? "<li><i class='fa-li fa fa-check'></i>Delivered: $total_delivered</li>" : "";
                                            echo ($total_failed) ? "<li><i class='fa-li fa fa-check'></i>Failed: $total_failed</li>" : "";
                                            echo ($total_pending) ? "<li><i class='fa-li fa fa-check'></i>Pending: $total_pending</li>" : "";
                                            echo ($total_submit) ? "<li><i class='fa-li fa fa-check'></i>Submit: $total_submit</li>" : "";
                                            echo ($total_dnd) ? "<li><i class='fa-li fa fa-check'></i>DND: $total_dnd</li>" : "";
                                            echo ($total_reject) ? "<li><i class='fa-li fa fa-check'></i>Rejetced: $total_reject</li>" : "";
                                            echo ($total_blocked) ? "<li><i class='fa-li fa fa-check'></i>Blocked: $total_blocked</li>" : "";
                                            echo ($total_sent) ? "<li><i class='fa-li fa fa-check'></i>Sent: $total_sent</li>" : "";
                                            echo ($total_cancelled) ? "<li><i class='fa-li fa fa-check'></i>Cancelled: $total_cancelled</li>" : "";
                                        }
                                        ?>
                                    </ul>
                                    <button type="button" class="btn btn-primary btn-xs" data-container="body" data-toggle="popover" 
                                            data-placement="left" data-html="true"
                                            data-content='
                                            <form action="javascript:reSendSMS();" id="resend_sms_form" id="validate-basic" class="form parsley-form">
                                            <div class="form-group">
                                            <select name="resend_route" id="resend_route" class="form-control" required="" 
                                            data-parsley-required-message="Please Select Route!">
                                            <option value="">Select Route</option>
                                            <?php
                                            if (isset($pr_user_groups) && $pr_user_groups) {
                                                ?>
                                                <optgroup label="Promotional Routes">
                                                <?php
                                                foreach ($pr_user_groups as $pr_user_group) {
                                                    ?>
                                                    <option value="<?php echo $pr_user_group['user_group_id'] . "-A"; ?>"><?php echo $pr_user_group['user_group_name']; ?></option>
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
                                                    <option value="<?php echo $tr_user_group['user_group_id'] . "-B"; ?>"><?php echo $tr_user_group['user_group_name']; ?></option>
                                                    <?php
                                                }
                                                ?>
                                                </optgroup>
                                                <?php
                                            }
                                            ?>
                                            </select>
                                            </div>
                                            <div class="form-group">
                                            <input type="text" name="resend_campaign_name" id="resend_campaign_name" value="Resend" class="form-control" />
                                            </div>
                                            <div class="form-group">
                                            <select name="resend_action_type" id="resend_action_type" class="form-control" required="" 
                                            data-parsley-required-message="Please Select Action!">
                                            <option value="">Select</option>
                                            <option value="1">All</option>
                                            <option value="2">Failed</option>
                                            <option value="3">Sent</option>
                                            <option value="4">Delivered</option>
                                            <option value="5">Fake Failed</option>
                                            <option value="6">Fake Delivered</option>
                                            </select>
                                            </div>
                                            <div class="form-group">
                                            <input type="hidden" name="user_id" id="user_id" value="<?php echo $delivery_report['user_id']; ?>" />
                                            <input type="hidden" name="resend_campaign_id" id="resend_campaign_id" 
                                            value="<?php echo $delivery_report['campaign_id']; ?>" />
                                            <button type="submit" name="resend" id="resend" class="btn btn-primary btn-xs"
                                            data-loading-text="Resending..." autocomplete="off">Resend</button></div>'>
                                        Resend
                                    </button>
                                </td>
                            </tr>
                            <?php
                            $i++;
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="5" align="center">
                                <strong>No Reports!</strong>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-12">
        <nav>
            <ul class="pager">
                <?php if ($page_no > 1) { ?>
                    <li class="previous">
                        <a href="javascript:void(0);" onclick="pagingDlrReport(<?php echo $page_no - 1; ?>, 100);">
                            <span aria-hidden="true">&larr;</span> Newer
                        </a>
                    </li>
                <?php } else { ?>
                    <li class="previous disabled">
                        <a href="javascript:void(0);">
                            <span aria-hidden="true">&larr;</span> Newer
                        </a>
                    </li>
                <?php } ?>
                <li>
                    Page <strong><?php echo $page_no; ?></strong> of <strong><?php echo $total_pages; ?></strong>
                </li>
                <?php if ($page_no < $total_pages) { ?>
                    <li class="next">
                        <a href="javascript:void(0);" onclick="pagingDlrReport(<?php echo $page_no + 1; ?>, 100);">
                            Older <span aria-hidden="true">&rarr;</span>
                        </a>
                    </li>
                <?php } else { ?>
                    <li class="next disabled">
                        <a href="javascript:void(0);">
                            Older <span aria-hidden="true">&rarr;</span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    </div>
</div>

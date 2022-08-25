<div class="row" id="search_sms_dlr_table">
                <div class="col-md-12 padding15">
                    <div class="table-responsive" id="delivery_report_table">
                        <table class="table table-hover fade in" style="margin-left: -10px;">
                            <thead>
                                <tr>
                                    <!--<th>Username</th>-->
                                    <th></th>
                                    <th>Campaign</th>
                                    <th>Message</th>
                                    <th>SMS</th>
                                    <th>Request</th>
                                    <th>Summary</th>
                                </tr>
                            </thead>
                            <tbody class="bgf7">
                                <?php
                                if (isset($delivery_reports) && $delivery_reports) {
                                    $i = 1;
                                    foreach ($delivery_reports as $delivery_report) {
                                        ?>
                                        <tr <?php if ($i % 2 == 0) { ?>
                                                style="background-color: gainsboro;"  
                                            <?php } else { ?>
                                                style="background-color: white;"  

                                            <?php } ?>>
                                            <td></td>
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
                                                <?php
                                                if (isset($delivery_report['resend_smsc_id']) && $delivery_report['resend_smsc_id']) {
                                                    ?>
                                                    <span class="label label-info">Campaign Resend</span>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                            <td style="word-break: break-all; width: 400px;">
                                                <b  title="Sender Id" style="font-weight: bold;"> <?php
                                                    if ($delivery_report['service_type'] == "SMS") {
                                                        echo $delivery_report['sender_id'];
                                                        $pricing_error = $delivery_report['pricing_error'];
                                                        if ($pricing_error) {
                                                            echo " [" . $delivery_report['pricing_error'] . "]";
                                                        }
                                                    } elseif ($delivery_report['service_type'] == "VOICE") {
                                                        echo $delivery_report['caller_id'];
                                                    }
                                                    ?></b>
                                                <br/> 
                                                <a  title="Click To See Full Delivery Report" href="<?php echo base_url(); ?>admin/sent_sms/<?php echo $delivery_report['campaign_id'] . "/" . $delivery_report['route'] . "/" . $delivery_report['user_id']; ?>" style=" color: royalblue;">
                                                    <?php echo urldecode($delivery_report['message']); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <label>Submitted: </label><?php echo $delivery_report['total_messages']; ?><br/>
                                                <label>Credit: </label><?php echo $delivery_report['total_credits']; ?><br/>
                                                <label>Deducted: </label><?php echo $delivery_report['total_deducted']; ?><br/>
                                                <label>Processed: </label><?php echo $delivery_report['actual_message']; ?><br/>
                                                <?php
                                                if ($delivery_report['service_type'] == "SMS") {
                                                    if ($delivery_report['fake_failed'] >= 1) {
                                                        ?>
                                                        <label>Fake Failed: </label>
                                                        <?php echo $delivery_report['fake_failed']; ?>
                                                        <br/>
                                                        <?php
                                                    }
                                                    if ($delivery_report['fake_delivered'] >= 1) {
                                                        ?>
                                                        <label>Fake Delivered: </label>
                                                        <?php echo $delivery_report['fake_delivered']; ?>
                                                        <br/>
                                                        <?php
                                                    }
                                                    if ($delivery_report['fake_sent'] >= 1) {
                                                        ?>
                                                        <label>Fake Sent: </label>
                                                        <?php echo $delivery_report['fake_sent']; ?>
                                                        <br/>
                                                        <?php
                                                    }
                                                } elseif ($delivery_report['service_type'] == "VOICE") {
                                                    if ($delivery_report['fake_failed'] >= 1) {
                                                        ?>
                                                        <label>Fake Failed: </label>
                                                        <?php echo $delivery_report['fake_failed']; ?>
                                                        <br/>
                                                        <?php
                                                    }
                                                    if ($delivery_report['fake_delivered'] >= 1) {
                                                        ?>
                                                        <label>Fake Delivered: </label>
                                                        <?php echo $delivery_report['fake_delivered']; ?>
                                                        <br/>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <ul class="fa-ul padding0">
                                                    <li>
                                                        <i class="fa-li fa fa-envelope-o"></i>
                                                        <?php if ($delivery_report['route'] == 'A') { ?>
                                                            <span class="label label-success" title="Route">Promotional</span>
                                                        <?php } elseif ($delivery_report['route'] == 'B') { ?>
                                                            <span class="label label-danger" title="Route">Transactional</span>
                                                        <?php } elseif ($delivery_report['route'] == 'C') { ?>
                                                            <span class="label label-primary" title="Route">Stock Promotion</span>
                                                        <?php } elseif ($delivery_report['route'] == 'I') { ?>
                                                            <span class="label label-primary" title="Route"> International</span>
                                                        <?php } elseif ($delivery_report['route'] == 'D') { ?>
                                                            <span class="label label-warning" title="Route">Premium Promotion</span>
                                                        <?php } ?>
                                                    </li>
                                                    <li>
                                                        <i class="fa-li fa fa-bolt"></i>
                                                        <span title="Message Type">
                                                            <?php
                                                            if ($delivery_report['message_type'] == 2)
                                                                echo "Unicode";
                                                            elseif ($delivery_report['message_type'] == 1)
                                                                echo "Normal";
                                                            ?>
                                                        </span>
                                                    </li>
                                                    <?php if ($delivery_report['message_category'] == 2) { ?>
                                                        <li>
                                                            <i class="fa-li fa fa-clock-o"></i> 
                                                            <span title="Message Scheduled">
                                                                Scheduled
                                                            </span>
                                                        </li>
                                                    <?php } ?>
                                                    <?php if ($delivery_report['flash_message']) { ?>
                                                        <li>
                                                            <i class="fa-li fa fa-bookmark-o"></i> 
                                                            <span title="Flash Message">Flash</span>
                                                        </li>
                                                    <?php } ?>
                                                    <li>
                                                        <i class="fa-li fa fa-tags"></i>
                                                        <span title="Request By">
                                                            <?php echo $delivery_report['request_by']; ?>
                                                        </span>
                                                    </li>
                                                    <li>
                                                        <i class="fa-li fa fa-check"></i>
                                                        <span title="Default Route"><?php echo $delivery_report['user_group_name']; ?></span>
                                                    </li>
                                                    <?php
                                                    if (isset($delivery_report['resend_smsc_id']) && $delivery_report['resend_smsc_id']) {
                                                        ?>
                                                        <li>
                                                            <i class="fa-li fa fa-repeat"></i>
                                                            <span title="Resend Route"><?php echo $delivery_report['resend_ugroup_name']; ?></span>
                                                        </li>
                                                        <?php
                                                    }
                                                    ?>
                                                    <li>
                                                        <i class="fa-li fa fa-check"></i>
                                                        <span title="Service Type">
                                                            <?php $type = $delivery_report['service_type']; ?>
                                                            <?php echo $delivery_report['service_type']; ?>
                                                        </span>
                                                    </li>
                                                    <?php
                                                    $black_listed = explode('|', $delivery_report['black_listed']);
                                                    if ($black_listed[0]) {
                                                        ?>
                                                        <li>
                                                            <i class="fa-li fa fa-ban"></i>
                                                            <span title="Black Keyword">
                                                                <?php echo ($black_listed[0]) ? "Black Keyword" : ""; ?>
                                                            </span>
                                                        </li>
                                                        <?php
                                                    }
                                                    if ($black_listed[1]) {
                                                        ?>
                                                        <li>
                                                            <i class="fa-li fa fa-ban"></i>
                                                            <span title="Service Sender">
                                                                <?php echo ($black_listed[1]) ? "Black Sender" : ""; ?>
                                                            </span>
                                                        </li>
                                                    <?php } ?>
                                                    <?php if ($delivery_report['total_time']) { ?>
                                                        <li>
                                                            <i class="fa-li fa fa-clock-o"></i>
                                                            <span title="Processing Time">
                                                                <?php echo $delivery_report['total_time'] . " Seconds"; ?>
                                                            </span>
                                                        </li>
                                                    <?php } ?>
                                                    <li>
                                                        <i class="fa-li fa fa-user"></i>
                                                        <label class="label label-success" title="Processed By"><?php echo $delivery_report['processed_by']; ?></label>
                                                    </li>
                                                    <?php
                                                    if (isset($delivery_report['resend_by']) && $delivery_report['resend_by']) {
                                                        ?>
                                                        <li>
                                                            <i class="fa-li fa fa-user-times"></i>
                                                            <label class="label label-info" title="Resend By"><?php echo $delivery_report['resend_by']; ?></label>
                                                        </li>
                                                        <?php
                                                    }
                                                    ?>
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
                                                        echo ($total_reject) ? "<li style='color:#DE8742; font-weight: bold;' ><i class='fa-li fa fa-times'></i>Rejected: $total_reject</li>" : "";
                                                        echo ($total_blocked) ? "<li style='color:#1f1d1d; font-weight: bold;' ><i class='fa-li fa fa-lock'></i>Blocked: $total_blocked</li>" : "";
                                                        echo ($total_sent) ? "<li style='color:#4271A5; font-weight: bold;' ><i class='fa-li fa fa-arrow-up'></i>Sent: $total_sent</li>" : "";
                                                        echo ($total_cancelled) ? "<li style='color:#E67E22; font-weight: bold;' ><i class='fa-li fa fa-times'></i>Cancelled: $total_cancelled</li>" : "";
                                                        echo ($total_landline) ? "<li style='color:#E67E22; font-weight: bold;' ><i class='fa-li fa fa-phone'></i>Landline: $total_landline</li>" : "";
                                                    }
                                                    ?>
                                                </ul>
                                                <?php
                                                if ($delivery_report['service_type'] == "SMS") {
                                                    ?>
                                                    <button type="button" class="btn btn-primary btn-xs resend_campaign" data-container="body" data-toggle="popover" 
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
                                                                    <option value="<?php echo $pr_user_group['smsc_id'] . "-A-" . $pr_user_group['user_group_id']; ?>"><?php echo $pr_user_group['user_group_name']; ?></option>
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
                                                                    <option value="<?php echo $tr_user_group['smsc_id'] . "-B-" . $tr_user_group['user_group_id']; ?>"><?php echo $tr_user_group['user_group_name']; ?></option>
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
                                                            <option value="7">Rejected</option>
                                                            <option value="8">Pending Only</option>
                                                            <option value="9">Fake Sent</option>
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
                                                    <?php
                                                } else if ($delivery_report['service_type'] == "VOICE") {
                                                    ?>
                                                    <button type="button" class="btn btn-primary btn-xs resend_campaign" data-container="body" data-toggle="popover" 
                                                            data-placement="left" data-html="true"
                                                            data-content='
                                                            <form action="javascript:reSendVoiceSMS();" id="resend_sms_form" id="validate-basic" class="form parsley-form">
                                                            <div class="form-group">
                                                            <select name="resend_route" id="resend_route" class="form-control" required="" 
                                                            data-parsley-required-message="Please Select Route!">
                                                            <option value="">Select Route</option>
                                                            <option value="A">Pramotional</option>
                                                            <option value="B">Dynamic</option>

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
                                                            <option value="7">Rejected</option>
                                                            <option value="8">Pending Only</option>
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
                                                    <?php
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
    </div>
        
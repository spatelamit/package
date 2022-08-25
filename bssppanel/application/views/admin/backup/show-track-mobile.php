<table class="table table-hover fade in">
    <thead>
        <tr>
            <th>Match Results: <?php echo ($delivery_reports) ? sizeof($delivery_reports) : "0"; ?></th>
        </tr>
    </thead>
</table>
<table class="table table-hover fade in">
    <thead>
        <tr>
            <!--<th>Date</th>-->
            <!--<th>Username</th>-->
            <th>Campaign</th>
            <th>Message</th>
            <th>SMS</th>
            <th>Request</th>
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
                            <span class="label label-success">Campaign Completed</span>
                            <?php
                        } elseif ($delivery_report['message_category'] == 2 && $delivery_report['schedule_status'] == 1) {
                            ?>
                            <span class="label label-warning">Campaign Scheduled</span>
                            <?php
                        } elseif ($delivery_report['message_category'] == 2 && $delivery_report['schedule_status'] == 0) {
                            ?>
                            <span class="label label-success">Campaign Completed</span>
                            <?php
                        } elseif ($delivery_report['campaign_status'] == 3) {
                            ?>
                            <span class="label label-danger">Campaign Rejected</span>
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
                            <li>
                                <i class="fa-li fa fa-check"></i>
                                <?php echo $delivery_report['user_group_name']; ?>
                            </li>
                        </ul>
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
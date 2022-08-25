<div class="page-content-title txt-center">
    <h3><i class="fa fa-download"></i> SMS Tracking</h3> 
</div>
<div id="settings">
    <div class="horizontal-tabs tab-color padding15" id="sms_tracking">
        <ul class="nav nav-tabs" role="tablist">
            <li class="<?php echo (isset($subtab) && $subtab == "1") ? 'active"' : '' ?>">
                <a href="javascript:void(0)" onclick="getSMSTrackingTab('1');">SMS Tracking</a>
            </li>
            <li class="<?php echo (isset($subtab) && $subtab == "2") ? 'active"' : '' ?>">
                <a href="javascript:void(0)" onclick="getSMSTrackingTab('2');">Number Tracking</a>
            </li>
        </ul>
        <div class="panel-group panel-color visible-xs"></div>
        <div class="tab-content bgf9">
            <div class="tab-pane fade in active">

                <?php if ($subtab == '1') { ?>
                    <div class="row" id="delivery_report_table">
                        <div class="col-md-6 col-sm-6 col-xs-12 padding15">
                            <input type="text" id="search_by_msg_id" placeholder="Search By Message ID / Username / Sender Id / Message / Number of Request" 
                                   class="form-control" onkeyup="searchDlrReport(this.value);" />
                        </div>
                    </div>
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
                                            <!--<th>Date</th>-->
                                            <th>Username</th>
                                            <th>Campaign</th>
                                            <th>Message</th>
                                            <th>SMS</th>
                                            <th>Request</th>
                                            <!--<th>Action</th>-->
                                        </tr>
                                    </thead>
                                    <tbody class="bgf7">
                                        <?php
                                        if ($delivery_reports) {
                                            $i = 1;
                                            foreach ($delivery_reports as $delivery_report) {
                                                ?>
                                                <tr>
                                                    <!--<td><?php //echo $delivery_report['submit_date'];              ?></td>-->
                                                    <td>
                                                        <?php
                                                        if ($delivery_report['admin_id'] == "") {
                                                            ?>
                                                            <?php echo $delivery_report['username']; ?>
                                                            ( <?php echo ($delivery_report['parent_username'] == "") ? $delivery_report['admin_username'] : $delivery_report['parent_username']; ?> )                        
                                                        <?php } else { ?>
                                                            <?php echo "Control Admin"; ?>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $delivery_report['campaign_name']; ?><br/>
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
                                                                <i class="fa-li fa fa-bookmark-o"></i>
                                                                <?php echo $delivery_report['campaign_uid']; ?>
                                                            </li>
                                                            <li>
                                                                <i class="fa-li fa fa-check"></i>
                                                                <?php echo $delivery_report['user_group_name']; ?>
                                                            </li>
                                                            <li>
                                                                <i class="fa-li fa fa-calendar"></i>
                                                                <?php echo $delivery_report['submit_date']; ?>
                                                            </li>
                                                            <?php if ($delivery_report['message_category'] == 2) { ?>
                                                                <li>
                                                                    <i class="fa-li fa fa-calendar"></i>
                                                                    <?php echo $delivery_report['schedule_date']; ?>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </td>
                                                    <!--
                                                    <td>
                                                        <a href="<?php echo base_url(); ?>admin/sent_sms/<?php echo $delivery_report['campaign_id'] . "/" . $delivery_report['route'] . "/" . $delivery_report['user_id']; ?>" class="btn btn-primary btn-sm">
                                                            <i class="fa fa-eye"></i> View
                                                        </a>
                                                        <a href="<?php echo base_url(); ?>admin/delete_dlr_report/<?php echo $delivery_report['campaign_id']; ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>  Delete</a>
                                                    </td>
                                                    -->
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
                <?php } ?>

            </div>
        </div>
    </div>
</div>
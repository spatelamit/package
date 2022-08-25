<div class="table-responsive" style="overflow-y: auto;height: 480px;">
    <table class="table table-hover fade in bgf9">
        <thead>
            <tr>
                <th>S.No.</th>
                <th>To</th>
                <th>Status</th>
                <th>Submit Date</th>
                <th>Done Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            if ($sent_sms) {
                foreach ($sent_sms as $sms) {
                    $dlr_reciept = $sms['dlr_receipt'];
                    $decode_dlr_reciept = urldecode($dlr_reciept);
                    $array = explode(' ', $decode_dlr_reciept);
                    $report = "";
                    if (sizeof($array) > 1) {
                        $report.=$decode_dlr_reciept;
                    } else {
                        $report = $decode_dlr_reciept . " From SMSC<br/>Time: " . $sms['done_date'];
                    }
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td>
                            <a href="#" data-container="body" data-toggle="popover" data-placement="right" data-html='true' data-trigger='click'
                               data-content="<?php echo $report; ?>">
                                   <?php echo $sms['mobile_no']; ?>
                            </a>
                        </td>
                        <td>
                            <?php
                            if ($sms['status'] == "1") {
                                echo "Delivered";
                                //$total_delivered+=1;
                            } elseif ($sms['status'] == "2" && strpos($decode_dlr_reciept, 'err:006') === false) {
                                echo "Failed";
                                //$total_failed+=1;
                            } elseif ($sms['status'] == "31" || $sms['status'] == "23") {
                                echo "Pending";
                                //$total_pending+=1;
                            } elseif ($sms['status'] == "8") {
                                echo "Submitted";
                                //$total_submit+=1;
                            } elseif ($sms['status'] == "DND" || (strpos($decode_dlr_reciept, 'err:006') !== false && strpos($decode_dlr_reciept, 'stat:UNDELIV') !== false)) {
                                echo "NDNC";
                                //$total_dnd+=1;
                            } elseif ($sms['status'] == "16" || $sms['status'] == "Rejected") {
                                echo "Rejected From Operator";
                                //$total_reject+=1;
                            } elseif ($sms['status'] == "Blocked") {
                                echo "Blocked By Operator";
                                //$total_blocked+=1;
                            } elseif ($sms['status'] == "3" || $sms['status'] == "4") {
                                echo "Report Pending";
                                //$total_sent+=1;
                            } elseif ($sms['status'] == "25") {
                                echo "Cancelled By User";
                                //$total_cancelled+=1;
                            }
                            ?>
                        </td>
                        <td><?php echo $sms['submit_date']; ?></td>
                        <td>
                            <?php
                            if ($sms['status'] == "1" || $sms['status'] == "2") {
                                echo $sms['done_date'];
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
                    <td colspan="5">No Record Found!</td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>

<div class="row pager">
    <div class="col-md-2 padding0">
        <div class="input-group">
            <input type="text" name="page_no" id="page_no" class="form-control input-sm" value="<?php echo $page_no; ?>">
            <span class="input-group-btn">
                <button class="btn btn-primary btn-sm" type="button" onclick="pagingSentSMS(page_no.value, 100, <?php echo $campaign; ?>, <?php echo $total_pages; ?>);">Go</button>
            </span>
        </div>
    </div>
    <div class="col-md-10 text-right">
        <nav>
            <ul>
                <?php if ($page_no > 1) { ?>
                    <li class="">
                        <a href="javascript:void(0);" onclick="pagingSentSMS(<?php echo $page_no - 1; ?>, 100, <?php echo $campaign; ?>, <?php echo $total_pages; ?>);">
                            <i class="fa fa-angle-double-left"></i>
                        </a>
                    </li>
                <?php } else { ?>
                    <li class="disabled">
                        <a href="javascript:void(0);">
                            <i class="fa fa-angle-double-left"></i>
                        </a>
                    </li>
                <?php } ?>
                <li>
                    Page <strong><?php echo $page_no; ?></strong> of <strong><?php echo $total_pages; ?></strong>
                </li>
                <?php if ($page_no < $total_pages) { ?>
                    <li class="">
                        <a href="javascript:void(0);" onclick="pagingSentSMS(<?php echo $page_no + 1; ?>, 100, <?php echo $campaign; ?>, <?php echo $total_pages; ?>);">
                            <i class="fa fa-angle-double-right"></i>
                        </a>
                    </li>
                <?php } else { ?>
                    <li class="disabled">
                        <a href="javascript:void(0);">
                            <i class="fa fa-angle-double-right"></i>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $('[data-toggle="popover"]').popover();
    });
</script>
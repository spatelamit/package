<span class="hidden" id="msg_type"><?php echo (isset($msg_type) ? $msg_type : ""); ?></span>
<span class="hidden" id="msg_data"><?php echo (isset($msg_data) ? $msg_data : ""); ?></span>
<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li class="<?php echo (isset($subtab) && $subtab == "1") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="geLogsTab('1');">Transaction Logs</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "2") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="geLogsTab('2');">Error Logs</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "3") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="geLogsTab('3');">SMS Consumptions</a>
    </li>
</ul>
<div class="panel-group panel-color visible-xs"></div>

<!-- Tab panes -->
<div class="tab-content bgf9">
    <div class="tab-pane fade in active ">

        <?php if ($subtab == '1') { ?>
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-hover bgf9">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Particulars</th>
                                <th>Type</th>
                                <th>- SMS (Dr.)</th>
                                <th>+ SMS (Cr.)</th>
                                <th>Pricing</th>
                                <th>Amount</th>
                                <th>Balance Type</th>
                                <th width="30%">Description</th>
                            </tr>
                        </thead>
                        <tbody class="bgf7">
                            <?php
                            if ($transactions) {
                                $i = 1;
                                foreach ($transactions as $row) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['txn_date']; ?></td>
                                        <td>
                                            <?php
                                            if ($row['from_admin_id'] == $admin_id) {
                                                if ($row['to_user_id'] != "")
                                                    echo $row['to_uname'];
                                                elseif ($row['to_admin_id'] != "")
                                                    echo $row['to_admin_name'] . " (Admin)";
                                            }elseif ($row['to_admin_id'] == $admin_id) {
                                                if ($row['from_user_id'] != "")
                                                    echo $row['from_uname'];
                                                elseif ($row['from_admin_id'] != "")
                                                    echo $row['from_admin_name'] . " (Admin)";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if ($row['txn_type'] == 'Add') { ?>
                                                <span class="label label-success">Add</span>
                                            <?php } elseif ($row['txn_type'] == 'Reduce') { ?>
                                                <span class="label label-danger">Reduce</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($row['txn_type'] == "Add" && $row['from_admin_id'] == $admin_id) {
                                                echo $row['txn_sms'];
                                            } elseif ($row['txn_type'] == "Reduce" && $row['from_admin_id'] == $admin_id) {
                                                echo $row['txn_sms'];
                                            } else {
                                                echo "-";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($row['txn_type'] == "Reduce" && $row['to_admin_id'] == $admin_id) {
                                                echo $row['txn_sms'];
                                            } elseif ($row['txn_type'] == "Add" && $row['to_admin_id'] == $admin_id) {
                                                echo $row['txn_sms'];
                                            } else {
                                                echo "-";
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $row['txn_price']; ?></td>
                                        <td><?php echo $row['txn_amount']; ?></td>
                                        <td>
                                            <?php if ($row['txn_route'] == 'A') { ?>
                                                <span class="label label-success">Promotional</span>
                                            <?php } elseif ($row['txn_route'] == 'B') { ?>
                                                <span class="label label-danger">Transactional</span> 
                                            <?php } elseif ($row['txn_route'] == 'Long') { ?>
                                                <span class="label label-info">Long Code</span> 
                                            <?php } elseif ($row['txn_route'] == 'Short') { ?>
                                                <span class="label label-primary">Short Code</span> 
                                            <?php } ?>
                                        </td>
                                        <td width="30%">
                                            <?php
                                            echo ($row['txn_description'] != '') ? $row['txn_description'] : "-";
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="9" align="center">
                                        <strong>No Transactions!</strong>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <?php echo $paging; ?>
                </div>
            </div>
        <?php } ?>

        <?php if ($subtab == '2') { ?>
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-hover bgf9">
                        <thead>
                            <tr>
                                <th width="180">Date</th>
                                <th width="250">Username</th>
                                <th width="150">By</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody class="bgf7">
                            <?php
                            if ($sms_logs) {
                                $i = 1;
                                foreach ($sms_logs as $row) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['sms_log_time']; ?></td>
                                        <td>
                                            <?php echo $row['username']; ?>
                                            ( <?php echo ($row['parent_username'] == "") ? $row['admin_username'] : $row['parent_username']; ?> )
                                        </td>
                                        <td><?php echo $row['sms_log_by']; ?></td>
                                        <td><span class="text-danger"><?php echo $row['sms_log_reason']; ?></span></td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="4" align="center">
                                        <strong>No Error Logs!</strong>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <?php echo $paging; ?>
                </div>
            </div>
        <?php } ?>

        <?php if ($subtab == '3') { ?>
            <div class="row">
                <div class="col-md-3 padding15 text-left">
                    <input type="text" class="form-control" id="search_by_username" placeholder="Enter Username" onkeyup="filterSMSConsumption(this.value);" />
                </div>
                <div class="col-md-9"></div>
            </div>
            <div class="row">
                <div class="col-md-12" id="sms_consumptions_table">
                    <div class="table-responsive">
                        <table class="table table-hover bgf9">
                            <thead>
                                <tr>
                                    <th rowspan="2">Username</th>
                                    <th rowspan="2">User Type</th>
                                    <th colspan="2">Promotional SMS</th>
                                    <th colspan="2">Transactional SMS</th>
                                </tr>
                                <tr>
                                    <th>Total SMS</th>
                                    <th>Actual Deductions</th>
                                    <th>Total SMS</th>
                                    <th>Actual Deductions</th>
                                </tr>
                            </thead>
                            <tbody class="bgf7">
                                <?php
                                if ($consumption_logs) {
                                    $i = 1;
                                    foreach ($consumption_logs as $row) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $row['username']; ?>
                                                ( <?php echo $row['parent_username']; ?> )
                                            </td>
                                            <td>
                                                <?php if ($row['user_type'] == 'User') { ?>
                                                    <span class="label label-success">User</span>
                                                <?php } elseif ($row['user_type'] == 'Reseller') { ?>
                                                    <span class="label label-info">Reseller</span>
                                                <?php } ?>
                                            </td>
                                            <td><?php echo $row['total_pr_messages']; ?></td>
                                            <td><?php echo $row['pr_consumptions']; ?></td>
                                            <td><?php echo $row['total_tr_messages']; ?></td>
                                            <td><?php echo $row['tr_consumptions']; ?></td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="6" align="center">
                                            <strong>No Logs!</strong>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <?php echo $paging; ?>
                    </div>
                </div>
            </div>
        <?php } ?>

    </div>
</div>
<script type="text/javascript">
    $('#validate-basic').parsley();
    $('.settingClass').parsley();
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
<ul class="nav nav-tabs" role="tablist">
    <li class="<?php echo (isset($subtab) && $subtab == "1") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getSMPPTab('1');">Overall Logs</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "2") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getSMPPTab('2');">Day-Wise Logs</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "3") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getSMPPTab('3');">Export SMPP Logs</a>
    </li>
</ul>

<div class="panel-group panel-color visible-xs"></div>

<!-- Tab panes -->
<div class="tab-content bgf9">
    <div class="tab-pane fade in active ">

        <?php if ($subtab == '1') { ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Connected SMPP</th>
                                    <th>Pending</th>
                                    <th>DND</th>
                                    <th>Rejected</th>
                                    <th>Blocked</th>
                                    <th>Submit</th>
                                    <th>Failed</th>
                                    <th>Delivered</th>
                                    <th>Total</th>
                                    <th>Deduction*</th>
                                    <th>Balance*</th>
                                </tr>
                            </thead>
                            <tbody class="bgf7">
                                <?php
                                if ($smpp_logs) {
                                    $i = 1;
                                    foreach ($smpp_logs as $row) {
                                        $total_pending = 0;
                                        $total_dnd = 0;
                                        $total_rejected = 0;
                                        $total_blocked = 0;
                                        $total_submit = 0;
                                        $total_failed = 0;
                                        $total_delivered = 0;
                                        $total = 0;
                                        if (isset($row['31'])) {
                                            $total_pending = $total_pending + $row['31'];
                                        }
                                        if (isset($row['4'])) {
                                            $total_pending = $total_pending + $row['4'];
                                        }
                                        $total = $total + $total_pending;
                                        if (isset($row['DND'])) {
                                            $total_dnd = $total_dnd + $row['DND'];
                                            $total = $total + $total_dnd;
                                        }
                                        if (isset($row['16'])) {
                                            $total_rejected = $total_rejected + $row['16'];
                                            $total = $total + $total_rejected;
                                        }
                                        if (isset($row['Rejected'])) {
                                            $total_rejected = $total_rejected + $row['Rejected'];
                                            $total = $total + $total_rejected;
                                        }
                                        if (isset($row['Blocked'])) {
                                            $total_blocked = $total_blocked + $row['Blocked'];
                                            $total = $total + $total_blocked;
                                        }
                                        if (isset($row['8'])) {
                                            $total_submit = $total_submit + $row['8'];
                                            $total = $total + $total_submit;
                                        }
                                        if (isset($row['3'])) {
                                            $total_submit = $total_submit + $row['3'];
                                            $total = $total + $total_submit;
                                        }
                                        if (isset($row['2'])) {
                                            $total_failed = $total_failed + $row['2'];
                                            $total = $total + $total_failed;
                                        }
                                        if (isset($row['1'])) {
                                            $total_delivered = $total_delivered + $row['1'];
                                            $total = $total + $total_delivered;
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo $row['user_group_name'] . " (" . $row['smsc_id'] . ")"; ?></td>                            
                                            <td><?php echo $total_pending; ?></td>
                                            <td><?php echo $total_dnd; ?></td>
                                            <td><?php echo $total_rejected; ?></td>
                                            <td><?php echo $total_blocked; ?></td>
                                            <td><?php echo $total_submit; ?></td>
                                            <td><?php echo $total_failed; ?></td>
                                            <td><?php echo $total_delivered; ?></td>
                                            <td><?php echo $total; ?></td>
                                            <td><?php echo $row['total_deduction']; ?></td>
                                            <td><?php echo $row['total_balance']; ?></td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="11" align="center">
                                            <strong>No Logs!</strong>
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
        <?php } ?>

        <?php if ($subtab == '2') { ?>
            <div class="row padding15">
                <div class="col-md-3">
                    <input type="text" class="form-control" id="search_by_date" placeholder="Enter Date" />
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary btn-sm" onclick="filterSMPPLogs();"
                            data-loading-text="Searching..." autocomplete="off" id="search_logs">Search</button>
                </div>
                <div class="col-md-7"></div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive" id="smpp_logs_table">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Connected SMPP</th>
                                    <th>Pending</th>
                                    <th>DND</th>
                                    <th>Rejected</th>
                                    <th>Blocked</th>
                                    <th>Submit</th>
                                    <th>Failed</th>
                                    <th>Delivered</th>
                                    <th>Total</th>
                                    <th>Actual Deduction*</th>
                                </tr>
                            </thead>
                            <tbody class="bgf7">
                                <?php
                                if ($smpp_logs) {
                                    $i = 1;
                                    foreach ($smpp_logs as $row) {
                                        $total_pending = 0;
                                        $total_dnd = 0;
                                        $total_rejected = 0;
                                        $total_blocked = 0;
                                        $total_submit = 0;
                                        $total_failed = 0;
                                        $total_delivered = 0;
                                        $total = 0;
                                        if (isset($row['31'])) {
                                            $total_pending = $total_pending + $row['31'];
                                        }
                                        if (isset($row['4'])) {
                                            $total_pending = $total_pending + $row['4'];
                                        }
                                        $total = $total + $total_pending;
                                        if (isset($row['DND'])) {
                                            $total_dnd = $total_dnd + $row['DND'];
                                            $total = $total + $total_dnd;
                                        }
                                        if (isset($row['16'])) {
                                            $total_rejected = $total_rejected + $row['16'];
                                            $total = $total + $total_rejected;
                                        }
                                        if (isset($row['Rejected'])) {
                                            $total_rejected = $total_rejected + $row['Rejected'];
                                            $total = $total + $total_rejected;
                                        }
                                        if (isset($row['Blocked'])) {
                                            $total_blocked = $total_blocked + $row['Blocked'];
                                            $total = $total + $total_blocked;
                                        }
                                        if (isset($row['8'])) {
                                            $total_submit = $total_submit + $row['8'];
                                            $total = $total + $total_submit;
                                        }
                                        if (isset($row['3'])) {
                                            $total_submit = $total_submit + $row['3'];
                                            $total = $total + $total_submit;
                                        }
                                        if (isset($row['2'])) {
                                            $total_failed = $total_failed + $row['2'];
                                            $total = $total + $total_failed;
                                        }
                                        if (isset($row['1'])) {
                                            $total_delivered = $total_delivered + $row['1'];
                                            $total = $total + $total_delivered;
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo $row['user_group_name'] . " (" . $row['smsc_id'] . ")"; ?></td>                            
                                            <td><?php echo $total_pending; ?></td>
                                            <td><?php echo $total_dnd; ?></td>
                                            <td><?php echo $total_rejected; ?></td>
                                            <td><?php echo $total_blocked; ?></td>
                                            <td><?php echo $total_submit; ?></td>
                                            <td><?php echo $total_failed; ?></td>
                                            <td><?php echo $total_delivered; ?></td>
                                            <td><?php echo $total; ?></td>
                                            <td><?php echo $row['total_deduction']; ?></td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="10" align="center">
                                            <strong>No Logs!</strong>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="10">Default: Today's SMPP Logs</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if ($subtab == '3') { ?>
            <div class="row padding15">

                <div class="col-md-4 col-sm-4 col-xs-12" id="exportSMPPLogs">
                    <div class="input-daterange input-group" id="datepicker">
                        <input type="text" class="form-control" name="export_from_date" id="export_from_date" placeholder="Enter From Date" />
                        <span class="input-group-addon">to</span>
                        <input type="text" class="form-control" name="export_to_date" id="export_to_date" placeholder="Enter To Date" />
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary btn-sm" onclick="searchSMPPLogs();"
                            data-loading-text="Searching..." autocomplete="off" id="search_logs">Search</button>
                </div>
                <div class="col-md-6 text-right">
                    <a href="javascript:void(0);" class="btn btn-info btn-sm hidden" onclick="exportSMPPLogs();"
                       data-loading-text="Processing..." autocomplete="off" id="export_logs">Export</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive" id="smpp_logs_table">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Connected SMPP</th>
                                    <th>Pending</th>
                                    <th>DND</th>
                                    <th>Rejected</th>
                                    <th>Blocked</th>
                                    <th>Submit</th>
                                    <th>Failed</th>
                                    <th>Delivered</th>
                                    <th>Total</th>
                                    <th>Actual Deduction*</th>
                                </tr>
                            </thead>
                            <tbody class="bgf7"></tbody>
                    </div>
                </div>
            </div>
        <?php } ?>

    </div>
</div>
<script type="text/javascript">
    $(document).ready(function ()
    {
        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
        $('#search_by_date').datepicker({
            format: "yyyy-mm-dd",
            endDate: today
        });

        $('#exportSMPPLogs .input-daterange').datepicker({
            format: "yyyy-mm-dd",
            endDate: today
        });
    });

</script>
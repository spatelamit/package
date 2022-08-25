<span class="hidden" id="msg_type"><?php echo (isset($msg_type) ? $msg_type : ""); ?></span>
<span class="hidden" id="msg_data"><?php echo (isset($msg_data) ? $msg_data : ""); ?></span>

<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li class="<?php echo (isset($subtab) && $subtab == "1") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getUBalanceTab('1');">Current Balance</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "2") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getUBalanceTab('2');">Date-Wise Balance</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "3") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getUBalanceTab('3');">Analyze Balance</a>
    </li>
</ul>
<div class="panel-group panel-color visible-xs"></div>

<!-- Tab panes -->
<div class="tab-content bgf9">
    <div class="tab-pane fade in active">

        <?php if (isset($subtab) && $subtab == '1') { ?>
            <div class="row">
                <div class="col-md-3 padding15 text-left">
                    <input type="text" class="form-control" id="search_by_username" placeholder="Enter Username" onkeyup="filterBalanceLogs(this.value, 1, '', 1);" />
                </div>
                <div class="col-md-4 padding15 text-left"></div>
                <div class="col-md-2 padding15 text-right">
                    <input type="text" class="form-control" id="search_by_balance" placeholder="Enter Balance" onkeyup="filterBalanceLogs(this.value, 2, search_by_route.value, 1);" />
                </div>
                <div class="col-md-3 padding15 text-right">
                    <select class="form-control" id="search_by_route" onchange="filterBalanceLogs(search_by_balance.value, 2, this.value, 1);">
                        <option value="A">Promotional Route</option>
                        <option value="B">Transactional Route</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" id="users_balance_table">
                    <div class="table-responsive">
                        <table class="table table-hover bgf9">
                            <thead>
                                <tr>
                                    <th rowspan="2">Date</th>
                                    <th rowspan="2">Username</th>
                                    <th rowspan="2">Type</th>
                                    <th class="text-center" colspan="2">Individual</th>
                                    <th class="text-center" colspan="2">Overall</th>
                                </tr>
                                <tr>
                                    <th>Promotional Route</th>
                                    <th>Transactional Route</th>
                                    <th>Promotional Route</th>
                                    <th>Transactional Route</th>
                                </tr>
                            </thead>
                            <tbody class="bgf7">
                                <?php
                                if (isset($balance_logs) && $balance_logs) {
                                    $i = 1;
                                    foreach ($balance_logs as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['balance_date']; ?></td>
                                            <td>
                                                <?php echo $row['username']; ?>
                                                ( <?php echo ($row['parent_username'] == "") ? $row['admin_username'] : $row['parent_username']; ?> )
                                            </td>
                                            <td><?php echo $row['user_type']; ?></td>
                                            <td><?php echo $row['pr_balance']; ?></td>
                                            <td><?php echo $row['tr_balance']; ?></td>
                                            <td><?php echo $row['total_pr']; ?></td>
                                            <td><?php echo $row['total_tr']; ?></td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="5" align="center">
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

        <?php if (isset($subtab) && $subtab == '2') { ?>
            <div class="row">
                <div class="col-md-3 padding15 text-left">
                    <div class="input-group">
                        <input type="text" class="form-control" id="filter_by_date" placeholder="Enter Date" />
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" onclick="filterUsersBalance(filter_by_date.value, filter_by_username.value, 2);"
                                    data-loading-text="Filtering..." autocomplete="off" id="filter_balance">Filter</button>
                        </span>
                    </div>
                </div>
                <div class="col-md-3 padding15 text-left">
                    <select id="filter_by_username" class="form-control" onchange="filterUsersBalance(filter_by_date.value, this.value, 2);"
                            data-live-search="true">
                        <option value="">All Users</option>
                        <?php
                        if (isset($users) && $users) {
                            foreach ($users as $key => $user) {
                                ?>
                                <option value="<?php echo $user['user_id']; ?>"><?php echo $user['username']; ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive" id="users_balance_table">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Parent</th>
                                    <th>Reseller</th>
                                    <th>Name</th>
                                    <!--<th>Contact Number</th>-->
                                    <!--<th>Email Address</th>-->
                                    <th>User Type</th>
                                    <th>Promotional Route</th>
                                    <th>Transactional Route</th>
                                </tr>
                            </thead>
                            <tbody class="bgf7">
                                <?php
                                if (isset($users_balance) && $users_balance) {
                                    $i = 1;
                                    foreach ($users_balance as $row) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $row['username']; ?>
                                            </td>
                                            <td><?php echo ($row['parent_username'] == "") ? $row['admin_username'] : $row['parent_username']; ?></td>
                                            <td><?php echo ($row['ref_username'] == "") ? "-" : $row['ref_username']; ?></td>
                                            <td><?php echo $row['name']; ?></td>
                                            <!--<td><?php //echo $row['contact_number'];                           ?></td>-->
                                            <!--<td><?php //echo $row['email_address'];                           ?></td>-->
                                            <td><?php echo $row['utype']; ?></td>
                                            <td><?php echo $row['pr_balance']; ?></td>
                                            <td><?php echo $row['tr_balance']; ?></td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="7" align="center">
                                            <strong>No Record</strong>
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

        <?php if (isset($subtab) && $subtab == '3') { ?>
            <div class="row">
                <div class="col-md-4 padding15 text-left">
                    <div class="input-group" id="analyzeDate">
                        <div class="input-daterange input-group">
                            <input type="text" class="form-control" name="from_date" id="from_date" placeholder="Enter From Date" />
                            <span class="input-group-addon">To</span>
                            <input type="text" class="form-control" name="to_date" id="to_date" placeholder="Enter To Date" />
                        </div>
                    </div>
                </div>
                <div class="col-md-3 padding15 text-left">
                    <select id="filter_by_username" class="form-control" onchange="analyzeUsersBalance(3);" data-live-search="true">
                        <option value="">Select Users</option>
                        <?php
                        if (isset($users) && $users) {
                            foreach ($users as $key => $user) {
                                ?>
                                <option value="<?php echo $user['user_id']; ?>"><?php echo $user['username']; ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-5 padding15 text-left">
                    <button type="button" id="analyzeBtn" data-loading-text="Processing..." class="btn btn-primary hidden" autocomplete="off">
                        Loading state
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th rowspan="2">Date</th>
                                    <th class="text-center" colspan="2">Promotional</th>
                                    <th class="text-center" colspan="2">Transactional</th>
                                </tr>
                                <tr>
                                    <th>Available</th>
                                    <th>Consumed</th>
                                    <th>Available</th>
                                    <th>Consumed</th>
                                </tr>
                            </thead>
                            <tbody class="bgf7" id="users_balance_table"></tbody>
                        </table>
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
        $('#filter_by_date').datepicker({
            format: "dd-mm-yyyy",
            endDate: today,
            autoclose: true,
            todayHighlight: true
        });
        $('#filter_by_username').selectpicker({
            liveSearch: true,
            maxOptions: 1
        });
        $('#analyzeDate .input-daterange').datepicker({
            format: "yyyy-mm-dd",
            endDate: today,
            autoclose: true,
            todayHighlight: true
        });
    });
</script>
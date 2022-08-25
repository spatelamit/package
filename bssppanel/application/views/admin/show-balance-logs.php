<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li class="<?php echo (isset($subtab) && $subtab == "1") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getBalanceTab('1');">Current Balance Logs</a>
    </li>
    <!--
    <li class="<?php //echo (isset($subtab) && $subtab == "2") ? 'active"' : ''  ?>">
        <a href="javascript:void(0)" onclick="getBalanceTab('2');">Individual Balance</a>
    </li>
    <li class="<?php //echo (isset($subtab) && $subtab == "3") ? 'active"' : ''  ?>">
        <a href="javascript:void(0)" onclick="getBalanceTab('3');">Overall Balance</a>
    </li>
    -->
</ul>

<div class="panel-group panel-color visible-xs"></div>

<!-- Tab panes -->
<div class="tab-content bgf9">
    <div class="tab-pane fade in active ">

        <?php if (isset($subtab) && $subtab == '1') { ?>
            <div class="row padding15">
                <div class="col-md-3">
                    <input type="text" class="form-control" id="search_by_username" placeholder="Enter Username" onkeyup="filterBalanceLogs(this.value);" />
                </div>
                <div class="col-md-9"></div>
            </div>
            <div class="row padding15">
                <div class="col-md-12">
                    <div class="table-responsive" id="balance_logs_table">
                        <table class="table table-hover">
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
                                        <td colspan="4" align="center">
                                            <strong>No Logs!</strong>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <!--
                        <nav class="pull-right">
                            <ul class="pagination radius0">
                        <?php //echo $pagination_helper; ?>
                            </ul>
                        </nav>
                        -->
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if (isset($subtab) && $subtab == '2') { ?>
            <div class="table-responsive">

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th rowspan="2">Date</th>
                            <th rowspan="2">Username</th>
                            <th colspan="2">Individual</th>
                            <th colspan="2">Overall</th>
                        </tr>
                        <tr>
                            <th>Promotional Balance</th>
                            <th>Transactional Balance</th>
                            <th>Promotional Balance</th>
                            <th>Transactional Balance</th>
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
                                    <td><?php echo $row['pr_balance']; ?></td>
                                    <td><?php echo $row['tr_balance']; ?></td>
                                    <td><?php echo $row['pr_balance']; ?></td>
                                    <td><?php echo $row['tr_balance']; ?></td>
                                </tr>
                                <?php
                                $i++;
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="4" align="center">
                                    <strong>No Logs!</strong>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>

                <!--
                <nav class="pull-right">
                    <ul class="pagination radius0">
                <?php //echo $pagination_helper; ?>
                    </ul>
                </nav>
                -->

            </div>
        <?php } ?>

        <?php if (isset($subtab) && $subtab == '3') { ?>
            <div class="table-responsive">

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th rowspan="2">Date</th>
                            <th rowspan="2">Username</th>
                            <th colspan="2">Individual</th>
                            <th colspan="2">Overall</th>
                        </tr>
                        <tr>
                            <th>Promotional Balance</th>
                            <th>Transactional Balance</th>
                            <th>Promotional Balance</th>
                            <th>Transactional Balance</th>
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
                                    <td><?php echo $row['pr_balance']; ?></td>
                                    <td><?php echo $row['tr_balance']; ?></td>
                                    <td><?php echo $row['pr_balance']; ?></td>
                                    <td><?php echo $row['tr_balance']; ?></td>
                                </tr>
                                <?php
                                $i++;
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="4" align="center">
                                    <strong>No Logs!</strong>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>

                <!--
                <nav class="pull-right">
                    <ul class="pagination radius0">
                <?php //echo $pagination_helper; ?>
                    </ul>
                </nav>
                -->

            </div>
        <?php } ?>

    </div>
</div>
<?php
// Current Balance
if ($subtab == 1) {
    ?>
    <div class="table-responsive">
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
                if ($balance_logs) {
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
    </div>
    <!-- Pagination -->
    <?php echo $paging; ?>
    <?php
}

// Users Balance
if ($subtab == 2) {
    ?>
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
        <tbody class="bgf7" id="users_balance_table">
            <?php
            if ($users_balance) {
                $i = 1;
                foreach ($users_balance as $row) {
                    ?>
                    <tr>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo ($row['parent_username'] == "") ? $row['admin_username'] : $row['parent_username']; ?></td>
                        <td><?php echo ($row['ref_username'] == "") ? "-" : $row['ref_username']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <!--<td><?php // echo $row['contact_number'];            ?></td>-->
                        <!--<td><?php // echo $row['email_address'];            ?></td>-->
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
    <?php
}

// Analyze Users Balance
if ($subtab == 3) {
    $current_date = date('d-m-Y');
    if ($users_balance) {
        $total_pr = 0;
        $total_cpr = 0;
        $total_tr = 0;
        $total_ctr = 0;
        $last_pr_cunsumed = 0;
        $last_tr_cunsumed = 0;
        foreach ($users_balance as $row) {
            $total_pr = $row['available_pr_balance'];
            $total_tr = $row['available_tr_balance'];
            $total_cpr+=$row['consumed_pr_balance'];
            $total_ctr+=$row['consumed_tr_balance'];
            $last_pr_cunsumed = $row['consumed_pr_balance'];
            $last_tr_cunsumed = $row['consumed_tr_balance'];
            if (end($users_balance)) {
                $total_pr = $total_pr - $last_pr_cunsumed;
                $total_tr = $total_tr - $last_tr_cunsumed;
            }
            ?>
            <tr>
                <td><?php echo $row['balance_date']; ?></td>
                <td><?php echo $row['available_pr_balance']; ?></td>
                <td><?php echo $row['consumed_pr_balance']; ?></td>
                <td><?php echo $row['available_tr_balance']; ?></td>
                <td><?php echo $row['consumed_tr_balance']; ?></td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td><strong>Grand Total</strong></td>
            <td><strong><?php echo $total_pr; ?></strong></td>
            <td><strong><?php echo $total_cpr; ?></strong></td>
            <td><strong><?php echo $total_tr; ?></strong></td>
            <td><strong><?php echo $total_ctr; ?></strong></td>
        </tr>
        <?php
    } else {
        ?>
        <tr>
            <td colspan="5" align="center">
                <strong>No Record</strong>
            </td>
        </tr>
        <?php
    }
}
?>
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
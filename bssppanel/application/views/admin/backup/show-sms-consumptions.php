    
<div class="table-responsive">
    <table class="table table-hover">
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
</div>
<!-- Pagination -->
<?php echo $paging; ?>
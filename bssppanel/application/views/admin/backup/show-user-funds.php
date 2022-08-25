<table class="table table-hover">
    <thead>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Balance Type</th>
            <th>SMS</th>
            <th>Pricing</th>
            <th>Amount</th>
            <th>From</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $check = 1;
        if ($txn_logs) {
            foreach ($txn_logs as $row) {
                ?>
                <tr>
                    <td><?php echo $row['txn_date']; ?></td>
                    <td><?php echo $row['txn_type']; ?></td>
                    <td>
                        <?php if ($row['txn_route'] == 'A') { ?>
                            <span class="label label-success">Promotional</span>                                                
                        <?php } else { ?>
                            <span class="label label-danger">Transactional</span>                                                    
                        <?php } ?>
                    </td>
                    <td>
                        <?php
                        if ($row['txn_type'] == 'Add')
                            echo "+" . $row['txn_sms'];
                        elseif ($row['txn_type'] == 'Reduce')
                            echo "-" . $row['txn_sms'];
                        ?>
                    </td>
                    <td><?php echo $row['txn_price']; ?></td>
                    <td><?php echo $row['txn_amount']; ?></td>
                    <td>
                        <?php
                        echo (isset($row['to_name'])) ? $row['to_name'] : "";
                        echo (isset($row['from_name'])) ? $row['from_name'] : "";
                        ?>
                    </td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="7" align="center">
                    <strong>No Record Found!</strong>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>

<!-- Pagination -->
<?php echo $paging; ?>
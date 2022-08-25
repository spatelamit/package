<fieldset>
    <legend align="center"><h4>Transaction Logs</h4></legend>
    <br/>
    <table border="1" width="100%" cellpadding="5" cellspacing="5">
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
            </tr>
        </thead>
        <tbody>
            <?php
            if ($transactions) {
                $i = 1;
                foreach ($transactions as $row) {
                    ?>
                    <tr>
                        <td><?php echo $row['txn_date']; ?></td>
                        <td>
                            <?php
                            if ($row['from_user_id'] == $user_id) {
                                if ($row['to_admin_id'] == "")
                                    echo $row['to_name'];
                                else
                                    echo $row['to_admin_name'];
                            } elseif ($row['to_user_id'] == $user_id) {
                                if ($row['from_admin_id'] == "")
                                    echo $row['from_name'];
                                else
                                    echo $row['from_admin_name'];
                            }
                            ?>
                        </td>
                        <td><?php echo $row['txn_type']; ?></td>
                        <td align="right">
                            <?php
                            if ($row['to_admin_id'] != "" && $row['to_user_id'] == "" && $row['txn_type'] == "Reduce") {
                                echo $row['txn_sms'];
                            } elseif ($row['to_admin_id'] == "" && $row['to_user_id'] != "" && $row['txn_type'] == "Reduce") {
                                echo $row['txn_sms'];
                            } else {
                                echo "-";
                            }
                            ?>
                        </td>
                        <td align="right">
                            <?php
                            if ($row['from_admin_id'] != "" && $row['from_user_id'] == "" && $row['txn_type'] == "Add") {
                                echo $row['txn_sms'];
                            } elseif ($row['from_admin_id'] == "" && $row['from_user_id'] != "" && $row['txn_type'] == "Add") {
                                echo $row['txn_sms'];
                            } else {
                                echo "-";
                            }
                            ?>
                        </td>
                        <td align="right"><?php echo $row['txn_price']; ?></td>
                        <td align="right"><?php echo $row['txn_amount']; ?></td>
                        <td>
                            <?php
                            if ($row['txn_route'] == 'A')
                                echo "Promotional";
                            else
                                echo "Transactional";
                            ?>
                        </td>
                    </tr>
                    <?php
                    $i++;
                }
            } else {
                ?>
                <tr>
                    <td colspan="8" align="center">
                        <strong>No Transactions!</strong>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</fieldset>
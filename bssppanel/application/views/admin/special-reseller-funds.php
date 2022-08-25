<?php
error_reporting(0);
$hostname = "localhost";
$username = "bulksms_user";
$password = "BALAJI@sr#ts7828";
$database = "bulksms_system";


$conn = mysql_connect("$hostname", "$username", "$password") or die(mysql_error());
mysql_select_db("$database", $conn) or die(mysql_error());
?>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Balance Type</th>
            <th>Credits</th>
            <th>Pricing</th>
            <th>Amount</th>
            <th>From</th>
             <th>Discription</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $check = 1;
        if ($txn_logs) {
            foreach ($txn_logs as $row) {
                ?>
                <tr>
                    <td><?php echo $row['date_time']; ?></td>
                    <td><?php echo $row['type']; ?></td>
                    <td>
                        <?php if ($row['balance_type'] == 'A') { ?>
                            <span class="label label-success">Promotional</span>
                        <?php } elseif ($row['balance_type'] == 'B') { ?>
                            <span class="label label-danger">Transactional</span>
                        <?php } elseif ($row['balance_type'] == 'C') { ?>
                            <span class="label label-danger">Stock Promotion</span>
                        <?php } elseif ($row['balance_type'] == 'D') { ?>
                            <span class="label label-danger">Premium Promotion</span>
                        <?php } ?>
                    </td>
                    <td>
                        <?php
                        if ($row['type'] == 'Add')
                            echo "+" . $row['balance'];
                        elseif ($row['type'] == 'Reduce')
                            echo "-" . $row['balance'];
                        ?>
                    </td>
                    <td><?php echo $row['sms_price']; ?></td>
                     <td><?php echo $row['total_amount']; ?></td>
                    
                    <td>
                         
                        <?php
                        $row['admin_id'];
                        ?>
                           <?php
                                $admin_id = $show_transation_log['txn_admin_from'];
                                $query = "SELECT `admin_username` FROM `administrators` WHERE `admin_id` = '" .  $row['admin_id'] . "'";
                                $result = mysql_query($query);
                                $row1 = mysql_fetch_array($result);
                                $admin_username = $row1["admin_username"];
                                echo $admin_username;
                                ?>
                    </td>
                     <td>
                        <?php
                        echo $row['discription'];
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
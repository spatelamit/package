<?php
$session_data = $this->session->userdata('admin_logged_in');
$admin_id = $session_data['admin_id'];
?>
<table class="table table-hover" >
    <thead>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Balance Type</th>
            <th>SMS</th>
            <th>Pricing</th>
            <th>Amount</th>
            <th>From</th>
            <th>Discription</th>
            <th>Admin Comment</th> 
        </tr>
    </thead>
    <tbody>
        <?php
        $check = 1;
       $i = 1;
        if ($txn_logs) {
            foreach ($txn_logs as $row) {
                ?>
                <tr>
                    <td><?php echo $row['txn_date']; ?></td>
                    <td><?php echo $row['txn_type']; ?></td>
                    <td>
                        <?php if ($row['txn_route'] == 'A') { ?>
                            <span class="label label-success">Promotional</span>
                        <?php } elseif ($row['txn_route'] == 'B') { ?>
                            <span class="label label-danger">Transactional</span>
                        <?php } elseif ($row['txn_route'] == 'C') { ?>
                            <span class="label label-success">Stock Promotion</span>
                        <?php } elseif ($row['txn_route'] == 'D') { ?>
                            <span class="label label-success">Premium Promotion</span>
                        <?php } elseif ($row['txn_route'] == 'I') { ?>
                            <span class="label label-info">International</span>
                        <?php } elseif ($row['txn_route'] == 'Long') { ?>
                            <span class="label label-info">Long Code</span>
                        <?php } elseif ($row['txn_route'] == 'Short') { ?>
                            <span class="label label-primary">Short Code</span>
                        <?php } elseif ($row['txn_route'] == 'VA') { ?>
                            <span class="label label-success">Promotional Voice</span>
                        <?php } elseif ($row['txn_route'] == 'VB') { ?>
                            <span class="label label-danger">Dynamic Voice</span>
                        <?php } ?>
                    </td>
                    <td>
                        <?php
                        if ($row['txn_type'] == 'Add')
                            echo "+" . $row['txn_sms'];
                        elseif ($row['txn_type'] == 'Reduce')
                            echo "-" . $row['txn_sms'];
                        elseif ($row['txn_type'] == 'Demo')
                            echo "+" . $row['txn_sms'];
                        elseif ($row['txn_type'] == 'Refund')
                            echo "+" . $row['txn_sms'];
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
                    <td>
                        <?php
                        echo $row['txn_description'];
                        ?>
                    </td>
                    <td><?php if ($admin_id == 6 || $admin_id == 2 || $admin_id == 1) { 
                        $user_tab = 3;
                        ?>
                        
                            <form role="form" class="tab-forms" id="userSettingForm<?php echo $i.$user_tab; ?>admin_comment" method='post' action="javascript:saveAdminComment('user',<?php echo $i; ?> ,<?php echo $user_tab; ?>, 'admin_comment');">
                                <div>
                                    <input type="hidden" name="trans_id" value="<?php echo $row['txn_log_id']; ?>">
                                    <textarea name="admin_comment" ><?php echo $row['admin_discription'] ?></textarea>
                                   <button type="submit" class="btn btn-primary btn-xs" id="btnadmin_comment<?php echo $i.$user_tab; ?>" data-loading-text="Processing..." autocomplete="off">Add</button>
                                </div>
                            </form>

                        <?php } ?>
                    </td>
                </tr>
                <?php
         $i++;   }
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

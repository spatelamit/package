</div>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="portlet">
                <h2 class="content-header-title tbl">Transactions</h2>
                <div class="portlet-content">
                    <div class="table-responsive">
                        <table class="table table-bordered bgf">
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
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($transactions) && $transactions) {
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
                                            <td>
                                                <?php if ($row['txn_type'] == 'Add') { ?>
                                                    <span class="text-success"><?php echo $row['txn_type']; ?></span>
                                                <?php } elseif ($row['txn_type'] == 'Reduce') { ?>
                                                    <span class="text-danger"><?php echo $row['txn_type']; ?></span>
                                                <?php } ?>
                                            </td>
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
                                                if ($row['txn_route'] == 'A'){
                                                    echo "Promotional";
                                                }
                                                  if ($row['txn_route'] == 'B'){
                                                    echo "Transactional";
                                                  }
                                                  if ($row['txn_route'] == 'C'){
                                                    echo "STOCK";
                                                  }
                                                  if ($row['txn_route'] == 'D'){
                                                    echo "PRTODND";
                                                  }
                                                  if ($row['txn_route'] == 'I'){
                                                    echo "International";
                                                  }
                                                ?>
                                            </td>
                                            <td><?php echo $row['txn_description']; ?></td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="9" align="center">
                                            <strong>No Transactions!</strong>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td align="center" colspan="9">
                                        <ul class="pagination margin0">
                                            <?php echo $pagination_helper; ?>
                                        </ul>
                                    </td>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div> 
            </div> 
        </div>
    </div>
</div>
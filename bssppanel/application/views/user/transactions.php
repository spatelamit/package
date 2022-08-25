</div>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="portlet">
                <div class="col-md-12 padding0">
                    <h2 class="content-header-title">Transactions</h2>
                </div>
                <div class="portlet-content">
                    <div class="table-responsive col-md-12 col-sm-12 col-xs-12 padding0">
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
                                                <?php if ($row['txn_user_from']){ ?>
                                                   <span class="label label-success"><?php echo "Add TO User";?></span>
                                                <?php }else {?>
                                                    <span class="label label-success"><?php echo $row['txn_type'];?></span>
                                                <?php }?>
                                                <?php } elseif ($row['txn_type'] == 'Reduce') { ?>
                                                    <?php if ($row['txn_user_to']){ ?>
                                                     <span class="label label-danger"><?php echo "Reduce From User"; ?></span>
                                                        <?php } 
                                                        else { ?>
                                                     <span class="label label-danger"><?php echo $row['txn_type']; ?></span>
                                                    <?php } ?>
                                                   
                                                <?php } elseif ($row['txn_type'] == 'Demo') { ?>
                                                    <span class="label label-danger"><?php echo $row['txn_type']; ?></span>
                                                <?php } elseif ($row['txn_type'] == 'Refund') { ?>
                                                    <span class="label label-danger"><?php echo $row['txn_type']; ?></span>
                                                <?php } ?>

                                            </td>
                                            <td align="right">
                                                <?php
                                                if ($row['txn_type'] == 'Reduce') {
                                                    echo $row['txn_sms'];
                                                } else {
                                                    echo "-";
                                                }
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                if ($row['txn_type'] != 'Reduce') {
                                                    echo $row['txn_sms'];
                                                } else {
                                                    echo "-";
                                                }
                                                ?>
                                            </td>
                                            <?php
                                            if ($row['txn_type'] == 'Add') {
                                                ?>
                                                <td align="right"><?php echo $row['txn_price']; ?></td>
                                                <td align="right"><?php echo $row['txn_amount']; ?></td>
                                                <?php } else {
                                                ?>
                                                <td align="right"><?php echo "-"; ?></td>
                                                <td align="right"><?php echo "-"; ?></td> 
                                            <?php }
                                            ?>
                                            <td>
                                                <?php if ($row['txn_route'] == 'A') { ?>
                                                    <span class="label label-success">Promotional</span>
                                                <?php } elseif ($row['txn_route'] == 'B') { ?>
                                                    <span class="label label-danger">Transactional</span>
                                                <?php } elseif ($row['txn_route'] == 'C') { ?>
                                                    <span class="label label-success">Stock Promotion</span>
                                                <?php } elseif ($row['txn_route'] == 'D') { ?>
                                                    <span class="label label-success">Premium Promotion</span>
                                                <?php }elseif ($row['txn_route'] == 'I') { ?>
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
                                                echo ($row['txn_description'] != '') ? $row['txn_description'] : "-";
                                                ?>
                                            </td>
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
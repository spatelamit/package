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
                                            <td><?php echo $row['date_time']; ?></td>
                                            <td><?php echo $row['admin_name']; ?> </td> 
                                            <td><?php echo $row['type'];?> </td>
                                            <td align="right">
                                                <?php
                                                if ($row['type'] == 'Reduce') {
                                                    echo $row['balance'];
                                                } else {
                                                    echo "-";
                                                }
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                if ($row['type'] != 'Reduce') {
                                                    echo $row['balance'];
                                                } else {
                                                    echo "-";
                                                }
                                                ?>
                                            </td>
                                            <?php
                                            if ($row['type'] == 'Add') {
                                                ?>
                                                <td align="right"><?php echo $row['sms_price']; ?></td>
                                                
                                                <?php } else {
                                                ?>
                                                <td align="right"><?php echo "-"; ?></td>
                                                
                                            <?php }
                                            ?>
                                                 <td align="right">
                                                <?php echo $row['total_amount']; ?>  
                                            </td>
                                          
                                            <td>
                                                <?php if ($row['balance_type'] == 'A') { ?>
                                                    <span class="label label-success">Promotional</span>
                                                <?php } elseif ($row['balance_type'] == 'B') { ?>
                                                    <span class="label label-danger">Transactional</span>
                                                <?php } elseif ($row['balance_type'] == 'C') { ?>
                                                    <span class="label label-primary">Stock Promotion</span>
                                                <?php } elseif ($row['balance_type'] == 'D') { ?>
                                                    <span class="label label-info">Premium Promotion</span>
                                                <?php }  ?>
                                                 
                                            </td>
                                            <td>
                                                <?php
                                                echo ($row['discription'] != '') ? $row['discription'] : "-";
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
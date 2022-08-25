<div class="page-content-title txt-center">
    <h3><i class="fa fa-pie-chart"></i> Transaction Logs</h3> 
</div>
<div id="custom-sms">
    <div class="table-responsive padding15">
        <table class="table table-hover">
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
            <tbody class="bgf7">
                <?php
                if ($transactions) {
                    $i = 1;
                    foreach ($transactions as $row) {
                        ?>
                        <tr>
                            <td><?php echo $row['txn_date']; ?></td>
                            <td>
                                <?php
                                if ($row['from_admin_id'] != "") {
                                    echo $row['to_name']." (".$row['to_uname'].")";
                                }
                                if ($row['to_admin_id'] != "") {
                                    echo $row['from_name']." (".$row['from_uname'].")";
                                }
                                ?>
                            </td>
                            <td><?php echo $row['txn_type']; ?></td>
                            <td>
                                <?php
                                if ($row['txn_type'] == "Add") {
                                    echo $row['txn_sms'];
                                } else {
                                    echo "-";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($row['txn_type'] == "Reduce") {
                                    echo $row['txn_sms'];
                                } else {
                                    echo "-";
                                }
                                ?>
                            </td>
                            <td><?php echo $row['txn_price']; ?></td>
                            <td><?php echo $row['txn_amount']; ?></td>
                            <td>
                                <?php if ($row['txn_route'] == 'A') { ?>
                                    <span class="label label-success">Promotional</span> 
                                <?php } else { ?>
                                    <span class="label label-danger">Transactional</span> 
                                <?php } ?>
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

        <nav class="pull-right">
            <ul class="pagination radius0">
                <?php echo $pagination_helper; ?>
            </ul>
        </nav>
        <!--
                <nav class="pull-right">
                    <ul class="pagination radius0">
                        <li class="disabled"><a href="#">&laquo;</a></li>
                        <li class="active"><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                        <li><a href="#">&raquo;</a></li>
                    </ul>
                </nav>
        -->
    </div>
</div>  
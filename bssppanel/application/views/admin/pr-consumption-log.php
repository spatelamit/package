<div class="table-responsive">
    <table class="table table-hover bgf9">
        <thead>
            <tr>
                <th rowspan="2">Username</th>
                <th rowspan="2">User Type</th>
                <th colspan="2">Promotional SMS</th>
              
            </tr>
            <tr>
                <th>Total SMS</th>
                <th>Actual Deductions</th>         
            </tr>
        </thead>
        <tbody class="bgf7">
            <?php
              $total_pr_messages = 0;
            $pr_consumptions = 0;
            if (isset($pr_consumption_logs) && $pr_consumption_logs) {
                $i = 1;
                foreach ($pr_consumption_logs as $row) {
                    ?>
                    <tr>
                        <td>
                            <?php echo $row['username']; ?>
                            
                        </td>
                        <td>
                            <?php if ($row['user_type'] == 'User') { ?>
                                <span class="label label-success">User</span>
                            <?php } elseif ($row['user_type'] == 'Reseller') { ?>
                                <span class="label label-info">Reseller</span>
                            <?php } ?>
                        </td>
                        <td><?php echo $row['total_pr_messages'];
                         $total_pr_messages = $total_pr_messages +  $row['total_pr_messages'];
                        ?></td>
                        <td><?php echo $row['pr_consumptions'];
                           $pr_consumptions = $pr_consumptions + $row['pr_consumptions']; 
                        ?></td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>
                    <tr>
                    
                       <th>Grand Total </th>
                         <th>-- </th>
                        <th><?php echo $total_pr_messages; ?></th>
                         <th><?php echo  $pr_consumptions;?></th>
                    
                </tr>
                    <?php
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
<div class="table-responsive">
    <table class="table table-hover bgf9">
        <thead>
            <tr>
                <th rowspan="2">Username</th>
                <th rowspan="2">User Type</th>
                <th colspan="2">Transactional SMS</th>
            </tr>
            <tr>
               
                <th>Total SMS</th>
                <th>Actual Deductions</th>
            </tr>
        </thead>
        <tbody class="bgf7">
            <?php
             $total_tr_messages = 0;
            $tr_deduction = 0; 
            
            if (isset($tr_consumption_logs) && $tr_consumption_logs) {
                $i = 1;
                foreach ($tr_consumption_logs as $row) {
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
                        
                        <td><?php echo $row['total_tr_messages'];
                           $total_tr_messages = $total_tr_messages + $row['total_tr_messages'];
                        ?></td>
                        <td><?php echo $row['tr_consumptions']; 
                          $tr_deduction = $tr_deduction + $row['tr_consumptions']; 
                        ?></td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>
                    <tr>
                    
                       <th>Grand Total </th>
                         <th>-- </th>
                        <th><?php echo $total_tr_messages; ?></th>
                         <th><?php echo  $tr_deduction;?></th>
                    
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
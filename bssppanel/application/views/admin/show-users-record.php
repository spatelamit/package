<div class="row">
                <div class="table-responsive" id="show_report">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Sent</th>
                                <th>Actual Delivered</th>
                                <th>Actual Failed</th>
                                <th>Fake Deliver</th>
                                <th>Fake Failed</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody class="bgf7">
                            <?php
                           if (isset($sms_report) && $sms_report) {
                                foreach ($sms_report as $row) {
                                    ?>
                               <?php   if($row['ref_username'] != NULL){ ?>
                                    <tr>
                                        <td>
                                            <?php echo $row['ref_username']; ?>
                                        </td>
                                        
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['submit_date']; ?></td>
                                        <td> <?php echo $row['total_sent']; ?> </td>
                                        <td><?php  echo $row['actual_deliver']; ?></td>
                                        <td><?php  echo $row['actual_failed']; ?></td>
                                        <td>
                                            <?php echo $row['fake_deliver']; ?>
                                              
                                        </td>
                                        <td><?php echo $row['fake_failed']; ?></td>
                                        <td>
                                            <?php echo $row['total_messages'];?> 
                                        </td>
                                    </tr>
                                    <?php
                                 } else {
                                   
                                 }
                               }
                           } else {
                                ?>
                                <tr>
                                    <td colspan="9" align="center">
                                        <strong>No Users</strong>
                                    </td>
                                </tr>
                                <?php
                           }
                            ?>
                        </tbody>
                    </table>
                    <!-- Pagination -->
                    <?php // echo $paging; ?>
                </div>
            </div>
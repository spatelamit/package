 <table class="table table-bordered bgf" id="exist_balance">
                            <thead>
                                <tr style="background-color: darkgrey;">
                                    <th>Sr. No</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                      <th>Email</th>
                                    <th>Contact</th>
                                     <th>SignUp Date</th>
                                   <th>PR SMS</th>
                                    <th>TR SMS</th>
                                     <th>STOCK SMS</th>
                                    <th>Premium SMS</th>
                                    <th>PR Voice</th>
                                    <th>TR voice</th>
                                    <th>Short Code</th>
                                    <th>Long Code</th>
                                    <th>User Status</th>
                                    

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($users_balance) && $users_balance) {
                                    $i = 1;
                                    foreach ($users_balance as $row) {
                                        ?>
                                        <tr  <?php if ($i % 2 == 0) { ?>
                                                style="background-color: lightgray;"  
                                            <?php } else { ?>
                                                style="background-color: ghostwhite"  

                                            <?php } ?>>
                                             <td><?php echo $i; ?></td>
                                            <td><?php echo $row['name']; ?></td>
                                            <td><?php echo $row['username']; ?></td>
                                            <td><?php echo $row['email_address']; ?></td>
                                             <td><?php echo $row['contact_number']; ?></td>
                                                <td><?php echo $row['creation_date']; ?></td>
                                             <td><?php echo $row['pr_sms_balance']; ?></td>
                                             <td><?php echo $row['tr_sms_balance']; ?></td>
                                             <td><?php echo $row['stock_balance']; ?></td>
                                             <td><?php echo $row['prtodnd_balance']; ?></td>
                                              <td><?php echo $row['pr_voice_balance']; ?></td>
                                               <td><?php echo $row['tr_voice_balance']; ?></td>
                                                <td><?php echo $row['short_code_balance']; ?></td>
                                                 <td><?php echo $row['long_code_balance']; ?></td>
                                                 <?php if($row['user_status']==1){ ?>
                                                 <td><button style="background-color: #7FB338;font-weight: bold; color: white;">Open</button></td>
                                              <?php  } ?>
                                                 <?php if($row['user_status']==0){ ?>
                                                 <td><button style="background-color: #cc3f44;font-weight: bold; color: white;">Block</button></td>
                                              <?php  } ?>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="20" align="center">
                                            <strong><b>No User Exist!</b></strong>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td align="center" colspan="20">
                                        <ul class="pagination margin0">
                                            <?php echo $pagination_helper; ?>
                                        </ul>
                                    </td>
                                </tr>
                            </tfoot>

                        </table>
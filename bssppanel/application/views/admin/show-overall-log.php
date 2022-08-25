<div class="table-responsive" id="show_overall_report">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Connected SMPP</th>
                                    <th class="text-right">Rejected</th>
                                    <th class="text-right">Submit</th>
                                    <th class="text-right">Sent</th>
                                    <th class="text-right">Failed</th>
                                    <th class="text-right">Delivered</th>
                                    <th class="text-right">Total</th>
                                    <th class="text-right">Deduction*</th>
                                </tr>
                            </thead>
                            <tbody class="bgf7">
                                <?php  
                                 if (isset($smpp_logs) && $smpp_logs) {  
                                  
                                 
                                  ?>
                                  <tr>
                                  <td><?php  echo $smpp_logs['connected_smpp']; ?></td>    
                                  <td class="text-right"><?php  echo $smpp_logs['total_rejected']; ?></td>
                                  <td class="text-right"><?php echo $smpp_logs['total_submit']; ?></td>
                                  <td class="text-right"><?php echo $smpp_logs['total_sent']; ?></td>
                                  <td class="text-right"><?php  echo $smpp_logs['total_failed']; ?></td>
                                  <td class="text-right"><?php echo $smpp_logs['total_delivered']; ?></td>
                                  <td class="text-right"><?php echo $smpp_logs['total_sms']; ?></td>
                                  <td class="text-right"><?php  echo $smpp_logs['total_deduction']; ?></td>
                                  </tr>
                                 
                                  <?php
                                  
                                 } else {
                                  ?>
                                  <tr>
                                  <td colspan="11" align="center">
                                  <strong>No Logs!</strong>
                                  </td>
                                  </tr>
                                  <?php
                                  } 
                                ?>
                            </tbody>
                        </table>
                    </div>
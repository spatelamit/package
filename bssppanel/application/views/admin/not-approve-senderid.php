
<table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                     <th>No</th>
                                    <th>Sender Id</th>
                                    <th>Date</th>
                                    <th colspan="2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($result) && $result) {
                                    $i = 1;
                                    foreach ($result as $data) {
                                       
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $i;  ?> 
                                            </td>
                                            <td>
                                                <?php echo $data['sender_id']; ?> 
                                            </td>
                                            <td><?php echo $data['date']; ?></td>
                                            <td><b><div style=" height: 26px; width: 150px; background-color: #E74C3C;border-radius: 2px; " >
                                            <div style="margin-left: 4px;"><a style=" color: white;text-decoration: none; " href ="" >Click To Approve</a></div>
                                        </div></b></td>
                                           
                                          
                                        </tr>
                                        <?php
                                      $i++; 
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="5" align="center">
                                            <strong>No Sender Id</strong>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
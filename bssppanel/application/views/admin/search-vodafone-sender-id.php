
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>                       
                            <th>S.No</th>
                            <th>Sender Id</th>
                            <th>Route</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        if (isset($table_data) && $table_data) {
                            foreach ($table_data as $data) {
                               
                                ?>
                                <tr> 
                                    <td><?php echo $i; ?> </td>   
                                    <td><?php echo $data['sender_id']; ?> </td>
                                    <td><?php echo $data['user_group_name']; ?> </td>
                                    <td> <button type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Sender Id">
                                            <i class="fa fa-trash"></i>
                                        </button></td>    


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
                <?php } ?>
                    </tbody>
                </table>    